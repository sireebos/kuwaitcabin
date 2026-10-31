<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Property;
use App\Models\Room;
use App\Services\BookingService;
use App\Repositories\BookingRepository;
use Illuminate\Http\Request;

class CheckinController extends Controller
{
    public function __construct(
        private BookingService $bookingService,
        private BookingRepository $bookingRepository
    ) {
    }

    public function index(Property $property)
    {
        // Get upcoming check-ins that are NOT checked out or cancelled
        // Show bookings that are pending, confirmed, or checked_in (not yet checked out)
        $upcomingCheckIns = $property->bookings()
            ->whereIn('status', ['pending', 'confirmed', 'checked_in'])
            ->where('check_in_date', '<=', now()->toDateString())
            ->where('check_out_date', '>=', now()->toDateString())
            ->with(['guest', 'room'])
            ->orderBy('check_in_date', 'asc')
            ->paginate(15);

        return view('checkin.index', compact('property', 'upcomingCheckIns'));
    }

    public function create(Property $property, Booking $booking)
    {
        $availableRooms = $property->rooms()
            ->where('status', 'available')
            ->get();

        // Get the room that was booked
        $bookedRoomId = $booking->room_id;

        return view('checkin.create', compact('property', 'booking', 'availableRooms', 'bookedRoomId'));
    }

    public function store(Request $request, Property $property, Booking $booking)
    {
        $validated = $request->validate([
            'room_id' => 'required|exists:rooms,id',
        ]);

        $this->bookingService->checkIn($booking, $validated['room_id']);

        return redirect()->route('properties.bookings.show', [$property, $booking])
            ->with('success', 'Guest checked in successfully');
    }
}

