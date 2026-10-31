<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\MenuItem;
use App\Services\OrderService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class OrderApiController extends Controller
{
    public function __construct(private OrderService $orderService)
    {
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'property_id' => 'required|exists:properties,id',
            'restaurant_table_id' => 'nullable|exists:restaurant_tables,id',
            'booking_id' => 'nullable|exists:bookings,id',
            'type' => 'required|in:dine_in,takeaway,room_service',
        ]);

        try {
            $order = $this->orderService->createOrder($validated);
            return response()->json([
                'success' => true,
                'message' => 'Order created successfully',
                'order' => $order,
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    public function addItem(Request $request, Order $order): JsonResponse
    {
        $validated = $request->validate([
            'menu_item_id' => 'required|exists:menu_items,id',
            'quantity' => 'required|integer|min:1',
            'modifiers' => 'nullable|array',
            'special_instructions' => 'nullable|string',
        ]);

        try {
            $menuItem = MenuItem::find($validated['menu_item_id']);
            $item = $this->orderService->addItem($order, [
                'menu_item_id' => $validated['menu_item_id'],
                'quantity' => $validated['quantity'],
                'unit_price' => $menuItem->price,
                'modifiers' => $validated['modifiers'] ?? null,
                'special_instructions' => $validated['special_instructions'] ?? null,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Item added to order',
                'item' => $item,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    public function show(Order $order): JsonResponse
    {
        $order->load('items.menuItem');
        return response()->json([
            'success' => true,
            'order' => $order,
        ]);
    }

    public function updateStatus(Request $request, Order $order): JsonResponse
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,preparing,ready,served,closed',
        ]);

        $this->orderService->updateOrderStatus($order, $validated['status']);
        return response()->json([
            'success' => true,
            'message' => 'Order status updated',
            'order' => $order,
        ]);
    }
}

