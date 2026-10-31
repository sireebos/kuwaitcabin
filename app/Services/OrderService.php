<?php

namespace App\Services;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Folio;
use App\Models\FolioItem;
use App\Models\InventoryItem;
use App\Models\MenuItem;
use App\Repositories\OrderRepository;
use Illuminate\Support\Str;
use DB;

class OrderService
{
    public function __construct(private OrderRepository $repository)
    {
    }

    public function createOrder(array $data): Order
    {
        $data['order_number'] = $this->generateOrderNumber();
        return $this->repository->create($data);
    }

    public function addItem(Order $order, array $itemData): OrderItem
    {
        return DB::transaction(function () use ($order, $itemData) {
            $item = $order->items()->create($itemData);
            $this->updateOrderTotal($order);

            // Track inventory consumption if this is a menu item with ingredients
            if (isset($itemData['menu_item_id'])) {
                $this->trackMenuItemInventory($order, $itemData['menu_item_id'], $itemData['quantity']);
            }

            return $item;
        });
    }

    private function trackMenuItemInventory($order, $menuItemId, $quantity): void
    {
        // Get the menu item to find its ingredients
        $menuItem = MenuItem::find($menuItemId);
        if (!$menuItem || !$menuItem->ingredients) {
            return;
        }

        // Decrement inventory for each ingredient
        foreach ($menuItem->ingredients as $ingredient) {
            if (isset($ingredient['inventory_item_id'])) {
                $inventoryItem = InventoryItem::find($ingredient['inventory_item_id']);
                if ($inventoryItem) {
                    $consumedQuantity = ($ingredient['quantity'] ?? 1) * $quantity;
                    $inventoryItem->decrement('quantity', $consumedQuantity);

                    // Log the consumption
                    DB::table('inventory_logs')->insert([
                        'property_id' => $order->property_id,
                        'inventory_item_id' => $inventoryItem->id,
                        'user_id' => auth()->id(),
                        'type' => 'usage',
                        'quantity' => $consumedQuantity,
                        'order_id' => $order->id,
                        'notes' => 'Restaurant order consumption',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }
        }
    }

    public function removeItem(OrderItem $item): bool
    {
        return DB::transaction(function () use ($item) {
            $order = $item->order;
            $item->delete();
            $this->updateOrderTotal($order);
            return true;
        });
    }

    public function updateOrderStatus(Order $order, string $status, ?string $paymentMethod = null): bool
    {
        $data = ['status' => $status];

        // If closing the order and payment method is provided, record payment
        if ($status === 'closed' && $paymentMethod) {
            $data['payment_method'] = $paymentMethod;
            $data['paid_at'] = now();
        }

        return $order->update($data);
    }

    public function chargeToRoom(Order $order, $bookingId): bool
    {
        return DB::transaction(function () use ($order, $bookingId) {
            $order->update([
                'charged_to_room' => true,
                'booking_id' => $bookingId,
                'status' => 'closed'
            ]);
            
            // Add to folio
            $folio = Folio::where('booking_id', $bookingId)->first();
            if ($folio) {
                FolioItem::create([
                    'folio_id' => $folio->id,
                    'description' => 'Restaurant Order #' . $order->order_number,
                    'type' => 'restaurant',
                    'amount' => $order->total
                ]);
                
                $folio->restaurant_charges += $order->total;
                $folio->total_charges += $order->total;
                $folio->updateBalance();
            }
            
            return true;
        });
    }

    private function updateOrderTotal(Order $order): void
    {
        $subtotal = $order->items()->sum(DB::raw('quantity * unit_price'));
        $property = $order->property;
        $taxRate = ($property->tax_rate ?? 10) / 100;
        $tax = $subtotal * $taxRate;
        $total = $subtotal - $order->discount + $tax;
        
        $order->update([
            'subtotal' => $subtotal,
            'tax' => $tax,
            'total' => $total
        ]);
    }

    private function generateOrderNumber(): string
    {
        return 'ORD' . strtoupper(Str::random(6)) . date('Ymd');
    }
}

