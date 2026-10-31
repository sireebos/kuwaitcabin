<?php

namespace App\Http\Controllers;

use App\Models\Property;
use App\Models\Payment;
use App\Models\Order;
use App\Models\InventoryItem;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DailySummaryController extends Controller
{
    public function show(Property $property, Request $request)
    {
        $date = $request->input('date', Carbon::today()->toDateString());
        $filterUser = $request->input('user_id', null);
        $filterStatus = $request->input('status', null);

        // Non-admin users can only see their own data
        if (!auth()->user()->isAdmin()) {
            $filterUser = auth()->user()->id;
        }

        // Get all payments for the selected date
        $paymentsQuery = Payment::where('property_id', $property->id)
            ->whereDate('created_at', $date)
            ->with('booking.guest', 'booking.user');

        if ($filterUser) {
            $paymentsQuery->whereHas('booking', function ($q) use ($filterUser) {
                $q->where('user_id', $filterUser);
            });
        }

        if ($filterStatus) {
            $paymentsQuery->where('status', $filterStatus);
        }

        $payments = $paymentsQuery->get();

        // Get all restaurant orders for the selected date
        $ordersQuery = Order::where('property_id', $property->id)
            ->where('order_type', 'restaurant')
            ->where('status', 'closed')
            ->whereNotNull('paid_at')
            ->whereDate('paid_at', $date)
            ->with('user');

        if ($filterUser) {
            $ordersQuery->where('user_id', $filterUser);
        }

        if ($filterStatus) {
            $ordersQuery->where('status', $filterStatus);
        }

        $orders = $ordersQuery->get();

        // Get inventory items with low stock
        $lowStockItems = InventoryItem::where('property_id', $property->id)
            ->where('is_active', true)
            ->whereRaw('quantity <= reorder_level')
            ->get();

        // Calculate totals
        $totalPayments = $payments->sum('amount');
        $totalOrders = $orders->sum('total');
        $grandTotal = $totalPayments + $totalOrders;

        // Get all users for filter
        $users = $property->users()->where('status', 'active')->get();

        return view('admin.daily-summary', compact(
            'property',
            'payments',
            'orders',
            'lowStockItems',
            'totalPayments',
            'totalOrders',
            'grandTotal',
            'date',
            'users',
            'filterUser',
            'filterStatus'
        ));
    }
}
