<?php

namespace App\Http\Controllers;

use App\Models\Property;
use App\Models\Booking;
use App\Models\Order;
use App\Models\Payment;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(Property $property)
    {
        $today = Carbon::now()->toDateString();

        // Hotel metrics
        $totalRooms = $property->rooms()->count();

        // Count rooms that are NOT available today using room_inventory table
        // This aligns with the availability checker logic
        $occupiedRooms = DB::table('room_inventory')
            ->whereIn('room_id', function ($query) use ($property) {
                $query->select('id')->from('rooms')->where('property_id', $property->id);
            })
            ->where('date', $today)
            ->where('status', '!=', 'available')
            ->distinct('room_id')
            ->count('room_id');

        // Calculate available rooms
        $availableRooms = $totalRooms - $occupiedRooms;

        $occupancyRate = $totalRooms > 0 ? ($occupiedRooms / $totalRooms) * 100 : 0;

        // Room types
        $roomTypes = $property->roomTypes()->where('is_active', true)->get();

        // Today's bookings
        $todayCheckIns = $property->bookings()
            ->where('check_in_date', $today)
            ->where('status', '!=', 'cancelled')
            ->count();

        $todayCheckOuts = $property->bookings()
            ->where('check_out_date', $today)
            ->where('status', '!=', 'cancelled')
            ->where('status', '!=', 'checked_out')
            ->count();

        // Revenue
        $todayRevenue = Payment::whereHas('booking', function ($query) use ($property) {
            $query->where('property_id', $property->id);
        })
        ->whereDate('created_at', $today)
        ->where('status', 'completed')
        ->sum('amount');

        $monthRevenue = Payment::whereHas('booking', function ($query) use ($property) {
            $query->where('property_id', $property->id);
        })
        ->whereMonth('created_at', Carbon::now()->month)
        ->where('status', 'completed')
        ->sum('amount');

        // Restaurant metrics
        $todayOrders = $property->orders()
            ->whereDate('created_at', $today)
            ->count();

        $todayOrdersRevenue = $property->orders()
            ->whereDate('created_at', $today)
            ->sum('total');

        // Upcoming events
        $upcomingCheckIns = $property->bookings()
            ->where('check_in_date', '>=', $today)
            ->where('status', '!=', 'cancelled')
            ->orderBy('check_in_date')
            ->limit(5)
            ->with('guest', 'room')
            ->get();

        // Pending orders
        $pendingOrders = $property->orders()
            ->whereIn('status', ['pending', 'preparing'])
            ->with('table', 'items')
            ->limit(5)
            ->get();

        return view('dashboard', compact(
            'property',
            'totalRooms',
            'occupiedRooms',
            'availableRooms',
            'occupancyRate',
            'roomTypes',
            'todayCheckIns',
            'todayCheckOuts',
            'todayRevenue',
            'monthRevenue',
            'todayOrders',
            'todayOrdersRevenue',
            'upcomingCheckIns',
            'pendingOrders'
        ));
    }

    public function salesReport(Property $property, Request $request)
    {
        $startDate = Carbon::parse($request->input('start_date', Carbon::now()->subDays(30)->toDateString()))->startOfDay();
        $endDate = Carbon::parse($request->input('end_date', Carbon::now()->toDateString()))->endOfDay();
        $sortBy = $request->get('sort_by', 'date');
        $sortOrder = $request->get('sort_order', 'desc');

        // Get booking payments
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

        $payments = $query->paginate(20);

        // Get restaurant orders
        $orders = Order::where('property_id', $property->id)
            ->where('status', 'closed')
            ->whereNotNull('paid_at')
            ->whereBetween('paid_at', [$startDate, $endDate])
            ->with('user')
            ->get();

        // Calculate totals
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

        // Sales by user
        $salesByUser = Payment::join('bookings', 'payments.booking_id', '=', 'bookings.id')
            ->join('users', 'bookings.user_id', '=', 'users.id')
            ->where('bookings.property_id', $property->id)
            ->whereBetween('payments.created_at', [$startDate, $endDate])
            ->select('users.id', 'users.name', DB::raw('COUNT(*) as transaction_count'), DB::raw('SUM(payments.amount) as total'))
            ->groupBy('users.id', 'users.name')
            ->orderBy('total', 'desc')
            ->get();

        return view('reports.sales', compact(
            'property',
            'payments',
            'orders',
            'totalSales',
            'salesByUser',
            'startDate',
            'endDate',
            'sortBy',
            'sortOrder'
        ));
    }

    public function occupancyReport(Property $property, Request $request)
    {
        $startDate = Carbon::parse($request->input('start_date', Carbon::now()->subDays(30)->toDateString()))->startOfDay();
        $endDate = Carbon::parse($request->input('end_date', Carbon::now()->toDateString()))->endOfDay();

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

    public function dailySalesSummary(Property $property, Request $request)
    {
        $date = $request->input('date', Carbon::now()->toDateString());
        $filterUser = $request->input('user_id', null);
        $filterType = $request->input('type', null);
        $filterStatus = $request->input('status', null);

        // Non-admin users can only see their own data
        if (!auth()->user()->isAdmin()) {
            $filterUser = auth()->user()->id;
        }

        // Get all inventory items at start of day
        $inventoryItems = $property->inventoryItems()
            ->where('is_active', true)
            ->get();

        // Get all orders for the day
        $ordersQuery = $property->orders()
            ->whereDate('created_at', $date)
            ->with('user', 'items.inventoryItem', 'items.menuItem');

        if ($filterUser) {
            $ordersQuery->where('user_id', $filterUser);
        }

        if ($filterStatus) {
            $ordersQuery->where('status', $filterStatus);
        }

        $orders = $ordersQuery->orderBy('created_at', 'desc')->get();

        // Get all bookings for the day
        $bookingsQuery = $property->bookings()
            ->whereDate('created_at', $date)
            ->with('user', 'guest');

        if ($filterUser) {
            $bookingsQuery->where('user_id', $filterUser);
        }

        if ($filterStatus) {
            $bookingsQuery->where('status', $filterStatus);
        }

        $bookings = $bookingsQuery->orderBy('created_at', 'desc')->get();

        // Apply type filter
        if ($filterType === 'sales') {
            $bookings = collect();
        } elseif ($filterType === 'bookings') {
            $orders = collect();
        }

        // Calculate totals
        $totalSalesAmount = $orders->sum('total');
        $totalBookingsAmount = $bookings->sum('total_amount');
        $totalAmount = $totalSalesAmount + $totalBookingsAmount;

        // Get all users for filter
        $users = $property->users()->where('status', 'active')->get();

        return view('reports.daily-sales-summary', compact(
            'property',
            'date',
            'inventoryItems',
            'orders',
            'bookings',
            'totalSalesAmount',
            'totalBookingsAmount',
            'totalAmount',
            'users',
            'filterUser',
            'filterType',
            'filterStatus'
        ));
    }
}

