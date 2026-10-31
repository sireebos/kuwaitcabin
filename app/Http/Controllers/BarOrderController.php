<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Property;
use App\Models\MenuItem;
use App\Models\InventoryItem;
use App\Services\OrderService;
use App\Repositories\OrderRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BarOrderController extends Controller
{
    public function __construct(
        private OrderService $orderService,
        private OrderRepository $orderRepository
    ) {
    }

    public function index(Property $property, Request $request)
    {
        $date = $request->input('date', now()->toDateString());
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        $query = Order::where('property_id', $property->id)
            ->where('order_type', 'bar');

        // Apply date filters
        if ($startDate && $endDate) {
            // Date range filter
            $query->whereDate('created_at', '>=', $startDate)
                  ->whereDate('created_at', '<=', $endDate);
        } else {
            // Single date filter
            $query->whereDate('created_at', $date);
        }

        $orders = $query->with(['items.menuItem', 'user'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('bar-orders.index', compact('property', 'orders', 'date'));
    }

    public function pos(Property $property)
    {
        // Get all beverage inventory items for the bar
        $drinks = InventoryItem::where('property_id', $property->id)
            ->where('type', 'beverage')
            ->where('is_active', true)
            ->get();

        return view('bar-orders.pos', compact('property', 'drinks'));
    }

    public function createWithItems(Request $request, Property $property)
    {
        try {
            $validated = $request->validate([
                'type' => 'required|in:dine_in,takeaway,room_service',
                'items' => 'required|string',
                'customer_name' => 'required|string|max:255',
            ]);

            $validated['property_id'] = $property->id;
            $validated['user_id'] = auth()->id();
            $validated['order_type'] = 'bar';

            $order = $this->orderService->createOrder($validated);
            $itemsData = json_decode($validated['items'], true);
            $itemsAdded = 0;

            foreach ($itemsData as $itemData) {
                $inventoryItem = InventoryItem::find($itemData['id']);
                if ($inventoryItem) {
                    // Use inventory item directly for bar orders
                    $this->orderService->addItem($order, [
                        'inventory_item_id' => $inventoryItem->id,
                        'quantity' => $itemData['quantity'],
                        'unit_price' => $inventoryItem->unit_cost ?? 0,
                        'modifiers' => $itemData['modifiers'] ?? null,
                        'special_instructions' => $itemData['special_instructions'] ?? null,
                    ]);

                    // Track inventory consumption for drinks
                    $this->trackInventoryConsumption($property, $inventoryItem, $itemData['quantity']);
                    $itemsAdded++;
                }
            }

            return redirect()->route('properties.bar-orders.show', [$property, $order])
                ->with('success', 'Bar order created successfully');
        } catch (\Exception $e) {
            return redirect()->route('properties.bar-orders.pos', $property)
                ->with('error', 'Error creating bar order: ' . $e->getMessage());
        }
    }

    public function show(Property $property, Order $order)
    {
        if ($order->order_type !== 'bar') {
            return redirect()->route('properties.bar-orders.index', $property)
                ->with('error', 'Invalid order');
        }

        $order->load('items.menuItem');
        return view('bar-orders.show', compact('property', 'order'));
    }

    public function receipt(Property $property, Order $order)
    {
        if ($order->order_type !== 'bar') {
            return redirect()->route('properties.bar-orders.index', $property)
                ->with('error', 'Invalid order');
        }

        $order->load('items.inventoryItem');
        return view('bar-orders.receipt', compact('property', 'order'));
    }

    public function updateStatus(Request $request, Property $property, Order $order)
    {
        if ($order->order_type !== 'bar') {
            return redirect()->back()->with('error', 'Invalid order');
        }

        $validated = $request->validate([
            'status' => 'required|in:pending,preparing,ready,served,closed',
            'payment_method' => 'nullable|in:cash,card,mobile_money,room_charge',
        ]);

        $this->orderService->updateOrderStatus($order, $validated['status'], $validated['payment_method'] ?? null);
        return redirect()->back()->with('success', 'Bar order updated successfully');
    }

    public function destroy(Property $property, Order $order)
    {
        if ($order->order_type !== 'bar') {
            return redirect()->route('properties.bar-orders.index', $property)
                ->with('error', 'Invalid order');
        }

        // Delete order items first
        $order->items()->delete();

        // Delete the order
        $order->delete();

        return redirect()->route('properties.bar-orders.index', $property)
            ->with('success', 'Bar order deleted successfully');
    }

    public function bulkDelete(Request $request, Property $property)
    {
        $validated = $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'required|integer|exists:orders,id',
        ]);

        try {
            // Get all orders to delete
            $orders = Order::where('property_id', $property->id)
                ->where('order_type', 'bar')
                ->whereIn('id', $validated['ids'])
                ->get();

            $deletedCount = 0;

            foreach ($orders as $order) {
                // Delete order items first
                $order->items()->delete();

                // Delete the order
                $order->delete();
                $deletedCount++;
            }

            return response()->json([
                'success' => true,
                'message' => "$deletedCount bar order(s) deleted successfully"
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error deleting orders: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get unpaid orders for reopening
     */
    public function getUnpaidOrders(Property $property)
    {
        $unpaidOrders = Order::where('property_id', $property->id)
            ->where('order_type', 'bar')
            ->where('status', '!=', 'closed')
            ->with(['items.inventoryItem'])
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'orders' => $unpaidOrders->map(function ($order) {
                return [
                    'id' => $order->id,
                    'customer_name' => $order->customer_name,
                    'order_number' => $order->order_number,
                    'total' => $order->total,
                    'status' => $order->status,
                    'created_at' => $order->created_at->format('H:i'),
                    'items_count' => $order->items->count(),
                ];
            })
        ]);
    }

    /**
     * Reopen an unpaid order
     */
    public function reopenOrder(Property $property, Order $order)
    {
        if ($order->order_type !== 'bar' || $order->status === 'closed') {
            return redirect()->back()->with('error', 'Cannot reopen this order');
        }

        $order->load('items.inventoryItem');
        return view('bar-orders.reopen', compact('property', 'order'));
    }

    /**
     * Add items to an existing bar order
     */
    public function addItems(Request $request, Property $property, Order $order)
    {
        if ($order->order_type !== 'bar') {
            return redirect()->back()->with('error', 'Invalid order');
        }

        try {
            $validated = $request->validate([
                'items' => 'required|string',
            ]);

            $itemsData = json_decode($validated['items'], true);
            $itemsAdded = 0;

            foreach ($itemsData as $itemData) {
                $inventoryItem = InventoryItem::find($itemData['id']);
                if ($inventoryItem) {
                    // Check if item already exists in order
                    $existingItem = $order->items()
                        ->where('inventory_item_id', $inventoryItem->id)
                        ->first();

                    if ($existingItem) {
                        // For existing items, the itemData['quantity'] is the NEW/TOTAL quantity
                        // We need to calculate how much to ADD based on the difference
                        $quantityToAdd = $itemData['quantity'] - $existingItem->quantity;
                        
                        if ($quantityToAdd > 0) {
                            // Only track consumption for the NEW quantity added
                            $existingItem->quantity = $itemData['quantity'];
                            $existingItem->save();
                            $this->trackInventoryConsumption($property, $inventoryItem, $quantityToAdd);
                        } else if ($quantityToAdd < 0) {
                            // Quantity decreased
                            $existingItem->quantity = $itemData['quantity'];
                            $existingItem->save();
                        }
                    } else {
                        // Add new item to order
                        $this->orderService->addItem($order, [
                            'inventory_item_id' => $inventoryItem->id,
                            'quantity' => $itemData['quantity'],
                            'unit_price' => $inventoryItem->unit_cost ?? 0,
                            'modifiers' => $itemData['modifiers'] ?? null,
                            'special_instructions' => $itemData['special_instructions'] ?? null,
                        ]);
                        
                        // Track inventory consumption for the new item
                        $this->trackInventoryConsumption($property, $inventoryItem, $itemData['quantity']);
                    }
                    
                    $itemsAdded++;
                }
            }

            // Recalculate order totals
            $order->load('items');
            $subtotal = $order->items->sum(function ($item) {
                return $item->quantity * $item->unit_price;
            });
            $taxRate = $property->tax_rate / 100;
            $tax = $subtotal * $taxRate;
            $total = $subtotal + $tax;

            $order->update([
                'subtotal' => $subtotal,
                'tax' => $tax,
                'total' => $total,
            ]);

            return redirect()->route('properties.bar-orders.show', [$property, $order])
                ->with('success', "$itemsAdded item(s) updated successfully");
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error adding items: ' . $e->getMessage());
        }
    }

    /**
     * Remove an item from a bar order
     */
    public function removeItem(Property $property, Order $order, InventoryItem $inventoryItem)
    {
        if ($order->order_type !== 'bar') {
            return response()->json(['success' => false, 'message' => 'Invalid order'], 400);
        }

        try {
            // Find and delete the order item
            $orderItem = $order->items()
                ->where('inventory_item_id', $inventoryItem->id)
                ->first();

            if (!$orderItem) {
                return response()->json(['success' => false, 'message' => 'Item not found in order'], 404);
            }

            // Delete the item
            $orderItem->delete();

            // Recalculate order totals
            $order->load('items');
            $subtotal = $order->items->sum(function ($item) {
                return $item->quantity * $item->unit_price;
            });
            $taxRate = $property->tax_rate / 100;
            $tax = $subtotal * $taxRate;
            $total = $subtotal + $tax;

            $order->update([
                'subtotal' => $subtotal,
                'tax' => $tax,
                'total' => $total,
            ]);

            return response()->json(['success' => true, 'message' => 'Item removed successfully']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Continue editing an order in POS
     */
    public function continueOrder(Property $property, Order $order)
    {
        if ($order->order_type !== 'bar' || $order->status === 'closed') {
            return redirect()->back()->with('error', 'Cannot continue this order');
        }

        $order->load('items.inventoryItem');
        $drinks = InventoryItem::where('property_id', $property->id)
            ->where('type', 'beverage')
            ->where('is_active', true)
            ->get();

        return view('bar-orders.pos', compact('property', 'drinks', 'order'));
    }

    private function trackInventoryConsumption(Property $property, InventoryItem $inventoryItem, int $quantity)
    {
        // Decrease inventory quantity
        $inventoryItem->quantity -= $quantity;
        $inventoryItem->save();

        // Log the consumption
        DB::table('inventory_logs')->insert([
            'property_id' => $property->id,
            'inventory_item_id' => $inventoryItem->id,
            'user_id' => auth()->id(),
            'type' => 'usage',
            'quantity' => $quantity,
            'notes' => 'Bar order consumption',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}

