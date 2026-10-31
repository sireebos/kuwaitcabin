<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Property;
use App\Services\BookingService;
use Illuminate\Http\Request;

class CheckoutController extends Controller
{
    public function __construct(private BookingService $bookingService)
    {
    }

    public function index(Property $property)
    {
        $status = request('status', 'checked_in');

        $occupiedRooms = $property->bookings()
            ->where('status', $status)
            ->with(['guest', 'room', 'folio'])
            ->paginate(15);

        return view('checkout.index', compact('property', 'occupiedRooms'));
    }

    public function show(Property $property, Booking $booking)
    {
        $booking->load(['guest', 'room', 'folio.items', 'payments']);
        return view('checkout.show', compact('property', 'booking'));
    }

    public function checkout(Request $request, Property $property, Booking $booking)
    {
        $this->bookingService->checkOut($booking);

        return redirect()->route('checkout.index', [$property])
            ->with('success', 'Guest checked out successfully');
    }
}

