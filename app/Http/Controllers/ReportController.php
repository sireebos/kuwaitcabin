<?php

namespace App\Http\Controllers;

use App\Models\Property;
use App\Models\Booking;
use App\Models\Payment;
use App\Models\Order;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class ReportController extends Controller
{
    public function sales(Request $request, Property $property)
    {
        $startDate = $request->get('start_date', now()->startOfDay());
        $endDate = $request->get('end_date', now()->endOfDay());
        $sortBy = $request->get('sort_by', 'date');
        $sortOrder = $request->get('sort_order', 'desc');

        $startDate = Carbon::parse($startDate)->startOfDay();
        $endDate = Carbon::parse($endDate)->endOfDay();

        // Get booking payments
        $payments = Payment::join('bookings', 'payments.booking_id', '=', 'bookings.id')
            ->where('bookings.property_id', $property->id)
            ->whereBetween('payments.created_at', [$startDate, $endDate])
            ->select('payments.*')
            ->with(['booking.user', 'booking.guest']);

        // Get restaurant orders (closed/paid orders)
        $orders = Order::where('property_id', $property->id)
            ->where('order_type', 'restaurant')
            ->where('status', 'closed')
            ->whereNotNull('paid_at')
            ->whereBetween('paid_at', [$startDate, $endDate])
            ->with('user')
            ->get();

        // Apply sorting to payment query
        if ($sortBy === 'date') {
            $payments->orderBy('payments.created_at', $sortOrder);
        } elseif ($sortBy === 'amount') {
            $payments->orderBy('payments.amount', $sortOrder);
        } elseif ($sortBy === 'user') {
            $payments->join('users', 'bookings.user_id', '=', 'users.id')
                ->orderBy('users.name', $sortOrder);
        }

        $payments = $payments->paginate(20);

        // Calculate totals from both payments and orders
        $paymentTotal = Payment::join('bookings', 'payments.booking_id', '=', 'bookings.id')
            ->where('bookings.property_id', $property->id)
            ->whereBetween('payments.created_at', [$startDate, $endDate])
            ->sum('payments.amount');

        $orderTotal = Order::where('property_id', $property->id)
            ->where('order_type', 'restaurant')
            ->where('status', 'closed')
            ->whereNotNull('paid_at')
            ->whereBetween('paid_at', [$startDate, $endDate])
            ->sum('total');

        $totalSales = $paymentTotal + $orderTotal;

        // Sales by user (from payments only, as orders don't have user attribution yet)
        $salesByUser = Payment::join('bookings', 'payments.booking_id', '=', 'bookings.id')
            ->join('users', 'bookings.user_id', '=', 'users.id')
            ->where('bookings.property_id', $property->id)
            ->whereBetween('payments.created_at', [$startDate, $endDate])
            ->select('users.id', 'users.name', DB::raw('COUNT(*) as transaction_count'), DB::raw('SUM(payments.amount) as total'))
            ->groupBy('users.id', 'users.name')
            ->orderBy('total', 'desc')
            ->get();

        return view('reports.sales', compact('property', 'payments', 'orders', 'totalSales', 'salesByUser', 'startDate', 'endDate', 'sortBy', 'sortOrder'));
    }

    public function bookings(Request $request, Property $property)
    {
        $startDate = $request->get('start_date', now()->startOfDay());
        $endDate = $request->get('end_date', now()->endOfDay());
        $sortBy = $request->get('sort_by', 'date');
        $sortOrder = $request->get('sort_order', 'desc');

        $startDate = Carbon::parse($startDate)->startOfDay();
        $endDate = Carbon::parse($endDate)->endOfDay();

        $query = Booking::where('bookings.property_id', $property->id)
            ->whereBetween('bookings.created_at', [$startDate, $endDate])
            ->with(['user', 'guest', 'room']);

        // Apply sorting
        if ($sortBy === 'date') {
            $query->orderBy('bookings.created_at', $sortOrder);
        } elseif ($sortBy === 'amount') {
            $query->orderBy('bookings.total_amount', $sortOrder);
        } elseif ($sortBy === 'user') {
            $query->join('users', 'bookings.user_id', '=', 'users.id')
                ->select('bookings.*')
                ->orderBy('users.name', $sortOrder);
        }

        $bookings = $query->paginate(20);

        // Calculate totals
        $totalBookings = Booking::where('bookings.property_id', $property->id)
            ->whereBetween('bookings.created_at', [$startDate, $endDate])
            ->count();

        $totalRevenue = Booking::where('bookings.property_id', $property->id)
            ->whereBetween('bookings.created_at', [$startDate, $endDate])
            ->sum('bookings.total_amount');

        $bookingsByUser = Booking::where('bookings.property_id', $property->id)
            ->whereBetween('bookings.created_at', [$startDate, $endDate])
            ->join('users', 'bookings.user_id', '=', 'users.id')
            ->select('users.id', 'users.name', DB::raw('COUNT(*) as booking_count'), DB::raw('SUM(bookings.total_amount) as total_revenue'))
            ->groupBy('users.id', 'users.name')
            ->orderBy('booking_count', 'desc')
            ->get();

        return view('reports.bookings', compact('property', 'bookings', 'totalBookings', 'totalRevenue', 'bookingsByUser', 'startDate', 'endDate', 'sortBy', 'sortOrder'));
    }

    public function salesPdf(Request $request, Property $property)
    {
        $startDate = $request->get('start_date', now()->startOfDay());
        $endDate = $request->get('end_date', now()->endOfDay());
        $sortBy = $request->get('sort_by', 'date');
        $sortOrder = $request->get('sort_order', 'desc');

        $startDate = Carbon::parse($startDate)->startOfDay();
        $endDate = Carbon::parse($endDate)->endOfDay();

        $query = Payment::join('bookings', 'payments.booking_id', '=', 'bookings.id')
            ->where('bookings.property_id', $property->id)
            ->whereBetween('payments.created_at', [$startDate, $endDate])
            ->select('payments.*')
            ->with(['booking.user', 'booking.guest']);

        if ($sortBy === 'date') {
            $query->orderBy('payments.created_at', $sortOrder);
        } elseif ($sortBy === 'amount') {
            $query->orderBy('payments.amount', $sortOrder);
        } elseif ($sortBy === 'user') {
            $query->join('users', 'bookings.user_id', '=', 'users.id')
                ->orderBy('users.name', $sortOrder);
        }

        $payments = $query->get();

        // Get restaurant orders
        $orders = Order::where('property_id', $property->id)
            ->where('status', 'closed')
            ->whereNotNull('paid_at')
            ->whereBetween('paid_at', [$startDate, $endDate])
            ->get();

        $paymentTotal = Payment::join('bookings', 'payments.booking_id', '=', 'bookings.id')
            ->where('bookings.property_id', $property->id)
            ->whereBetween('payments.created_at', [$startDate, $endDate])
            ->sum('payments.amount');

        $orderTotal = Order::where('property_id', $property->id)
            ->where('status', 'closed')
            ->whereNotNull('paid_at')
            ->whereBetween('paid_at', [$startDate, $endDate])
            ->sum('total');

        $totalSales = $paymentTotal + $orderTotal;

        $salesByUser = Payment::join('bookings', 'payments.booking_id', '=', 'bookings.id')
            ->join('users', 'bookings.user_id', '=', 'users.id')
            ->where('bookings.property_id', $property->id)
            ->whereBetween('payments.created_at', [$startDate, $endDate])
            ->select('users.id', 'users.name', DB::raw('COUNT(*) as transaction_count'), DB::raw('SUM(payments.amount) as total'))
            ->groupBy('users.id', 'users.name')
            ->orderBy('total', 'desc')
            ->get();

        $pdf = Pdf::loadView('reports.sales-pdf', compact('property', 'payments', 'orders', 'totalSales', 'salesByUser', 'startDate', 'endDate'));
        return $pdf->download('sales-report-' . now()->format('Y-m-d') . '.pdf');
    }

    public function bookingsPdf(Request $request, Property $property)
    {
        $startDate = $request->get('start_date', now()->startOfDay());
        $endDate = $request->get('end_date', now()->endOfDay());
        $sortBy = $request->get('sort_by', 'date');
        $sortOrder = $request->get('sort_order', 'desc');

        $startDate = Carbon::parse($startDate)->startOfDay();
        $endDate = Carbon::parse($endDate)->endOfDay();

        $query = Booking::where('bookings.property_id', $property->id)
            ->whereBetween('bookings.created_at', [$startDate, $endDate])
            ->with(['user', 'guest', 'room']);

        if ($sortBy === 'date') {
            $query->orderBy('bookings.created_at', $sortOrder);
        } elseif ($sortBy === 'amount') {
            $query->orderBy('bookings.total_amount', $sortOrder);
        } elseif ($sortBy === 'user') {
            $query->join('users', 'bookings.user_id', '=', 'users.id')
                ->select('bookings.*')
                ->orderBy('users.name', $sortOrder);
        }

        $bookings = $query->get();

        $totalBookings = Booking::where('bookings.property_id', $property->id)
            ->whereBetween('bookings.created_at', [$startDate, $endDate])
            ->count();

        $totalRevenue = Booking::where('bookings.property_id', $property->id)
            ->whereBetween('bookings.created_at', [$startDate, $endDate])
            ->sum('bookings.total_amount');

        $bookingsByUser = Booking::where('bookings.property_id', $property->id)
            ->whereBetween('bookings.created_at', [$startDate, $endDate])
            ->join('users', 'bookings.user_id', '=', 'users.id')
            ->select('users.id', 'users.name', DB::raw('COUNT(*) as booking_count'), DB::raw('SUM(bookings.total_amount) as total_revenue'))
            ->groupBy('users.id', 'users.name')
            ->orderBy('booking_count', 'desc')
            ->get();

        $pdf = Pdf::loadView('reports.bookings-pdf', compact('property', 'bookings', 'totalBookings', 'totalRevenue', 'bookingsByUser', 'startDate', 'endDate'));
        return $pdf->download('bookings-report-' . now()->format('Y-m-d') . '.pdf');
    }

    public function occupancy(Request $request, Property $property)
    {
        $startDate = $request->get('start_date', now()->subDays(30)->toDateString());
        $endDate = $request->get('end_date', now()->toDateString());

        $startDate = Carbon::parse($startDate)->startOfDay();
        $endDate = Carbon::parse($endDate)->endOfDay();

        $totalRooms = $property->rooms()->count();

        // Get bookings in date range
        $bookings = Booking::where('property_id', $property->id)
            ->where('status', '!=', 'cancelled')
            ->whereBetween('check_in_date', [$startDate, $endDate])
            ->with(['room', 'guest'])
            ->get();

        // Calculate occupancy by date
        $occupancyByDate = [];
        $currentDate = $startDate->copy();

        while ($currentDate <= $endDate) {
            $dateStr = $currentDate->toDateString();
            $occupiedRooms = 0;

            foreach ($bookings as $booking) {
                $checkIn = Carbon::parse($booking->check_in_date)->startOfDay();
                $checkOut = Carbon::parse($booking->check_out_date)->startOfDay();

                if ($currentDate >= $checkIn && $currentDate < $checkOut) {
                    $occupiedRooms++;
                }
            }

            $occupancyByDate[$dateStr] = [
                'occupied' => $occupiedRooms,
                'available' => $totalRooms - $occupiedRooms,
                'rate' => $totalRooms > 0 ? round(($occupiedRooms / $totalRooms) * 100, 2) : 0
            ];

            $currentDate->addDay();
        }

        // Calculate average occupancy
        $avgOccupancy = count($occupancyByDate) > 0
            ? array_sum(array_column($occupancyByDate, 'rate')) / count($occupancyByDate)
            : 0;

        // Occupancy by room type
        $occupancyByRoomType = $property->roomTypes()
            ->with(['rooms' => function ($query) use ($startDate, $endDate) {
                $query->withCount(['bookings' => function ($q) use ($startDate, $endDate) {
                    $q->where('status', '!=', 'cancelled')
                        ->whereBetween('check_in_date', [$startDate, $endDate]);
                }]);
            }])
            ->get();

        return view('reports.occupancy', compact(
            'property',
            'startDate',
            'endDate',
            'totalRooms',
            'occupancyByDate',
            'avgOccupancy',
            'occupancyByRoomType'
        ));
    }

    public function foodCost(Request $request, Property $property)
    {
        $startDate = $request->get('start_date', now()->subDays(30)->toDateString());
        $endDate = $request->get('end_date', now()->toDateString());

        $startDate = Carbon::parse($startDate)->startOfDay();
        $endDate = Carbon::parse($endDate)->endOfDay();

        // Get all restaurant orders with items
        $orders = Order::where('property_id', $property->id)
            ->where('order_type', 'restaurant')
            ->where('status', 'closed')
            ->whereBetween('paid_at', [$startDate, $endDate])
            ->with(['items.menuItem'])
            ->get();

        // Calculate food cost
        $totalFoodCost = 0;
        $totalRevenue = 0;
        $foodCostByItem = [];

        foreach ($orders as $order) {
            $totalRevenue += $order->total;

            foreach ($order->items as $item) {
                $itemCost = ($item->menuItem->cost_price ?? 0) * $item->quantity;
                $totalFoodCost += $itemCost;

                $itemName = $item->menuItem->name;
                if (!isset($foodCostByItem[$itemName])) {
                    $foodCostByItem[$itemName] = [
                        'quantity' => 0,
                        'cost' => 0,
                        'revenue' => 0
                    ];
                }

                $foodCostByItem[$itemName]['quantity'] += $item->quantity;
                $foodCostByItem[$itemName]['cost'] += $itemCost;
                $foodCostByItem[$itemName]['revenue'] += $item->unit_price * $item->quantity;
            }
        }

        $foodCostPercentage = $totalRevenue > 0 ? round(($totalFoodCost / $totalRevenue) * 100, 2) : 0;

        // Sort by cost descending
        uasort($foodCostByItem, function ($a, $b) {
            return $b['cost'] <=> $a['cost'];
        });

        return view('reports.food-cost', compact(
            'property',
            'startDate',
            'endDate',
            'totalFoodCost',
            'totalRevenue',
            'foodCostPercentage',
            'foodCostByItem',
            'orders'
        ));
    }

    public function stockUsage(Request $request, Property $property)
    {
        $startDate = $request->get('start_date', now()->subDays(30)->toDateString());
        $endDate = $request->get('end_date', now()->toDateString());

        $startDate = Carbon::parse($startDate)->startOfDay();
        $endDate = Carbon::parse($endDate)->endOfDay();

        // Get inventory items
        $inventoryItems = \App\Models\InventoryItem::where('property_id', $property->id)
            ->where('is_active', true)
            ->get();

        // Calculate usage by item
        $usageByItem = [];
        $totalUsage = 0;

        foreach ($inventoryItems as $item) {
            $itemLogs = DB::table('inventory_logs')
                ->where('property_id', $property->id)
                ->where('inventory_item_id', $item->id)
                ->where('type', 'usage')
                ->whereBetween('created_at', [$startDate, $endDate])
                ->get();

            $usageQuantity = $itemLogs->sum('quantity');
            $usageCost = $usageQuantity * ($item->unit_cost ?? 0);
            $totalUsage += $usageCost;

            if ($usageQuantity > 0) {
                $usageByItem[$item->name] = [
                    'quantity' => $usageQuantity,
                    'unit' => $item->unit,
                    'cost' => $usageCost,
                    'current_stock' => $item->quantity,
                    'reorder_level' => $item->reorder_level
                ];
            }
        }

        // Sort by cost descending
        uasort($usageByItem, function ($a, $b) {
            return $b['cost'] <=> $a['cost'];
        });

        return view('reports.stock-usage', compact(
            'property',
            'startDate',
            'endDate',
            'usageByItem',
            'totalUsage',
            'inventoryItems'
        ));
    }
}
