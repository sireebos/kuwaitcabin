<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Property;
use App\Models\RestaurantTable;
use App\Models\MenuItem;
use App\Services\OrderService;
use App\Repositories\OrderRepository;
use Illuminate\Http\Request;

class OrderController extends Controller
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
            ->where('order_type', 'restaurant');

        // Apply date filters
        if ($startDate && $endDate) {
            // Date range filter
            $query->whereDate('created_at', '>=', $startDate)
                  ->whereDate('created_at', '<=', $endDate);
        } else {
            // Single date filter
            $query->whereDate('created_at', $date);
        }

        $orders = $query->with(['items', 'table', 'booking'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('orders.index', compact('property', 'orders', 'date'));
    }

    public function pos(Property $property)
    {
        $tables = $property->restaurantTables;
        $menus = $property->menus()->with('categories.items')->get();
        
        return view('orders.pos', compact('property', 'tables', 'menus'));
    }

    public function create(Request $request, Property $property)
    {
        $validated = $request->validate([
            'restaurant_table_id' => 'nullable|exists:restaurant_tables,id',
            'type' => 'required|in:dine_in,takeaway,room_service',
        ]);

        $validated['property_id'] = $property->id;
        $order = $this->orderService->createOrder($validated);

        return redirect()->route('orders.edit', [$property, $order]);
    }

    public function edit(Property $property, Order $order)
    {
        $order->load('items.menuItem');
        $menus = $property->menus()->with('categories.items')->get();
        
        return view('orders.edit', compact('property', 'order', 'menus'));
    }

    public function addItem(Request $request, Property $property, Order $order)
    {
        $validated = $request->validate([
            'menu_item_id' => 'required|exists:menu_items,id',
            'quantity' => 'required|integer|min:1',
            'modifiers' => 'nullable|string',
            'special_instructions' => 'nullable|string',
        ]);

        $menuItem = MenuItem::find($validated['menu_item_id']);

        // Convert comma-separated modifiers string to array
        $modifiers = null;
        if (!empty($validated['modifiers'])) {
            $modifiers = array_map('trim', explode(',', $validated['modifiers']));
        }

        $this->orderService->addItem($order, [
            'menu_item_id' => $validated['menu_item_id'],
            'quantity' => $validated['quantity'],
            'unit_price' => $menuItem->price,
            'modifiers' => $modifiers,
            'special_instructions' => $validated['special_instructions'] ?? null,
        ]);

        return redirect()->back()->with('success', 'Item added to order');
    }

    public function removeItem(Property $property, Order $order, OrderItem $item)
    {
        $this->orderService->removeItem($item);
        return redirect()->back()->with('success', 'Item removed from order');
    }

    public function updateStatus(Request $request, Property $property, Order $order)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,preparing,ready,served,closed',
            'payment_method' => 'nullable|in:cash,card,mobile_money,room_charge',
        ]);

        $this->orderService->updateOrderStatus($order, $validated['status'], $validated['payment_method'] ?? null);
        return redirect()->back()->with('success', 'Payment completed successfully');
    }

    public function chargeToRoom(Request $request, Property $property, Order $order)
    {
        $validated = $request->validate([
            'booking_id' => 'required|exists:bookings,id',
        ]);

        $this->orderService->chargeToRoom($order, $validated['booking_id']);
        return redirect()->back()->with('success', 'Order charged to room');
    }

    public function show(Property $property, Order $order)
    {
        $order->load('items.menuItem');
        return view('orders.show', compact('property', 'order'));
    }

    public function receipt(Property $property, Order $order)
    {
        $order->load('items.menuItem');
        return view('orders.receipt', compact('property', 'order'));
    }

    public function kitchen(Property $property)
    {
        $orders = $property->orders()
            ->where('order_type', 'restaurant')
            ->with('items.menuItem', 'table')
            ->whereIn('status', ['pending', 'preparing'])
            ->orderBy('created_at', 'asc')
            ->get();

        return view('orders.kitchen', compact('property', 'orders'));
    }

    public function store(Request $request, Property $property)
    {
        $validated = $request->validate([
            'restaurant_table_id' => 'nullable|exists:restaurant_tables,id',
            'type' => 'required|in:dine_in,takeaway,room_service',
        ]);

        $validated['property_id'] = $property->id;
        $order = $this->orderService->createOrder($validated);

        return redirect()->route('properties.orders.edit', [$property, $order])->with('success', 'Order created');
    }

    public function createWithItems(Request $request, Property $property)
    {
        try {
            // Log the request for debugging
            \Log::info('createWithItems called', [
                'property_id' => $property->id,
                'request_data' => $request->all(),
            ]);

            $validated = $request->validate([
                'table_number' => 'nullable|string',
                'type' => 'required|in:dine_in,takeaway,room_service',
                'items' => 'required|string',
            ]);

            $validated['property_id'] = $property->id;
            $validated['user_id'] = auth()->id();

            // If dine_in and table_number provided, store it in notes
            $tableNumber = $validated['table_number'] ?? null;
            unset($validated['table_number']);

            // Store table number in notes if provided
            if ($tableNumber && $validated['type'] === 'dine_in') {
                $validated['notes'] = 'Table: ' . $tableNumber;
            }

            $order = $this->orderService->createOrder($validated);

            // Parse and add items to the order
            $items = json_decode($validated['items'], true);

            \Log::info('Parsed items', ['items' => $items]);

            if (!is_array($items) || empty($items)) {
                \Log::warning('No items in order');
                return redirect()->route('orders.pos', $property)
                    ->with('error', 'No items in order');
            }

            $itemsAdded = 0;
            foreach ($items as $item) {
                if (!isset($item['id']) || !isset($item['quantity'])) {
                    \Log::warning('Invalid item structure', ['item' => $item]);
                    continue;
                }

                $menuItem = MenuItem::find($item['id']);
                if ($menuItem) {
                    $this->orderService->addItem($order, [
                        'menu_item_id' => $item['id'],
                        'quantity' => $item['quantity'],
                        'unit_price' => $menuItem->price,
                        'modifiers' => null,
                        'special_instructions' => null,
                    ]);
                    $itemsAdded++;
                }
            }

            \Log::info('Order created with items', ['order_id' => $order->id, 'items_added' => $itemsAdded]);

            return redirect()->route('properties.orders.show', [$property, $order])->with('success', 'Order created successfully');
        } catch (\Exception $e) {
            \Log::error('Error creating order', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            return redirect()->route('orders.pos', $property)
                ->with('error', 'Error creating order: ' . $e->getMessage());
        }
    }

    public function update(Request $request, Property $property, Order $order)
    {
        $validated = $request->validate([
            'type' => 'required|in:dine_in,takeaway,room_service',
        ]);

        $order->update($validated);

        return redirect()->route('properties.orders.show', [$property, $order])->with('success', 'Order updated');
    }

    public function destroy(Property $property, Order $order)
    {
        // Only admin can delete orders
        if (!auth()->user()->isAdmin()) {
            return redirect()->route('properties.orders.show', [$property, $order])
                ->with('error', 'You do not have permission to delete orders');
        }

        $order->delete();
        return redirect()->route('properties.orders.index', $property)->with('success', 'Order deleted');
    }

    public function bulkDelete(Request $request, Property $property)
    {
        // Only admin can bulk delete orders
        if (!auth()->user()->isAdmin()) {
            return response()->json(['message' => 'You do not have permission to delete orders'], 403);
        }

        $validated = $request->validate([
            'order_ids' => 'required|array',
            'order_ids.*' => 'required|integer|exists:orders,id',
        ]);

        try {
            Order::whereIn('id', $validated['order_ids'])
                ->where('property_id', $property->id)
                ->delete();

            return response()->json(['message' => 'Orders deleted successfully'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error deleting orders: ' . $e->getMessage()], 500);
        }
    }
}

