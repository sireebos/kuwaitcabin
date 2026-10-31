<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Property;
use App\Models\Guest;
use App\Models\Room;
use App\Models\RoomType;
use App\Services\BookingService;
use App\Repositories\BookingRepository;
use Illuminate\Http\Request;
use Carbon\Carbon;

class BookingController extends Controller
{
    public function __construct(
        private BookingService $bookingService,
        private BookingRepository $bookingRepository
    ) {
    }

    public function index(Property $property, Request $request)
    {
        $date = $request->input('date', now()->toDateString());
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        $query = Booking::where('property_id', $property->id);

        // Apply date filters
        if ($startDate && $endDate) {
            // Date range filter - filter by created_at date
            $query->whereDate('created_at', '>=', $startDate)
                  ->whereDate('created_at', '<=', $endDate);
        } else {
            // Single date filter - filter by created_at date
            $query->whereDate('created_at', $date);
        }

        $bookings = $query->with(['guest', 'room', 'folio'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('bookings.index', compact('bookings', 'property', 'date'));
    }

    public function create(Property $property)
    {
        $guests = $property->guests;
        return view('bookings.create', compact('property', 'guests'));
    }

    public function store(Request $request, Property $property)
    {
        // Refresh property to get latest tax_rate
        $property = $property->fresh();

        $validated = $request->validate([
            'guest_id' => 'required|exists:guests,id',
            'room_ids' => 'required|array|min:1',
            'room_ids.*' => 'exists:rooms,id',
            'check_in_date' => 'required|date|after_or_equal:today',
            'check_out_date' => 'required|date|after:check_in_date',
            'number_of_guests' => 'required|integer|min:1',
            'room_rate' => 'nullable|numeric|min:0',
            'room_rates' => 'nullable|array',
            'room_rates.*' => 'numeric|min:0',
            'special_requests' => 'nullable|string',
        ]);

        // Check room availability before proceeding
        $checkInDate = \Carbon\Carbon::parse($validated['check_in_date']);
        $checkOutDate = \Carbon\Carbon::parse($validated['check_out_date']);
        $unavailableRooms = [];
        
        foreach ($validated['room_ids'] as $roomId) {
            $room = \App\Models\Room::find($roomId);
            if ($room && !$room->isAvailable($checkInDate, $checkOutDate)) {
                $unavailableRooms[] = $room->room_number;
            }
        }
        
        if (!empty($unavailableRooms)) {
            $roomList = implode(', ', $unavailableRooms);
            return back()->withInput()->withErrors([
                'room_ids' => "Room(s) {$roomList} are not available for the selected dates. Please choose different dates or rooms."
            ]);
        }

        // If single room, use room_rate field
        if (count($validated['room_ids']) === 1 && !empty($validated['room_rate'])) {
            $roomRates = [
                $validated['room_ids'][0] => $validated['room_rate']
            ];
        } else {
            // Multiple rooms - use room_rates array
            $roomRates = $validated['room_rates'] ?? [];
        }

        // Validate that all rooms have rates
        foreach ($validated['room_ids'] as $roomId) {
            if (!isset($roomRates[$roomId]) || $roomRates[$roomId] <= 0) {
                return back()->withErrors(['room_rates' => 'Please enter a valid rate for all selected rooms']);
            }
        }

        $validated['property_id'] = $property->id;
        $bookings = [];

        // Create a booking for each selected room
        foreach ($validated['room_ids'] as $roomId) {
            $roomRate = $roomRates[$roomId];
            $nights = abs(\Carbon\Carbon::parse($validated['check_out_date'])
                ->diffInDays(\Carbon\Carbon::parse($validated['check_in_date'])));

            $bookingData = [
                'property_id' => $property->id,
                'guest_id' => $validated['guest_id'],
                'room_id' => $roomId,
                'user_id' => auth()->id(),
                'check_in_date' => $validated['check_in_date'],
                'check_out_date' => $validated['check_out_date'],
                'number_of_guests' => $validated['number_of_guests'],
                'room_rate' => $roomRate,
                'special_requests' => $validated['special_requests'] ?? null,
                'subtotal' => abs($roomRate * $nights),
            ];

            $taxRate = ($property->tax_rate !== null ? $property->tax_rate : 10) / 100;
            $bookingData['tax'] = $bookingData['subtotal'] * $taxRate;
            $bookingData['total_amount'] = $bookingData['subtotal'] + $bookingData['tax'];

            $booking = $this->bookingService->createBooking($bookingData);
            $bookings[] = $booking;
        }

        $message = count($bookings) === 1
            ? 'Booking created successfully'
            : count($bookings) . ' bookings created successfully for ' . count($validated['room_ids']) . ' rooms';

        // Redirect to the first booking's show page
        $firstBooking = $bookings[0];
        return redirect()->route('properties.bookings.show', [$property, $firstBooking])
            ->with('success', $message);
    }

    public function show(Property $property, Booking $booking)
    {
        $booking->load(['guest', 'room', 'payments', 'folio.items']);
        // Refresh folio to get latest payments
        $booking->folio->refresh();
        return view('bookings.show', compact('booking', 'property'));
    }

    public function edit(Property $property, Booking $booking)
    {
        return view('bookings.edit', compact('booking', 'property'));
    }

    public function update(Request $request, Property $property, Booking $booking)
    {
        $validated = $request->validate([
            'special_requests' => 'nullable|string',
        ]);

        $booking->update($validated);

        return redirect()->route('properties.bookings.show', [$property, $booking])
            ->with('success', 'Booking updated successfully');
    }

    public function confirm(Property $property, Booking $booking)
    {
        $this->bookingService->confirmBooking($booking);
        return redirect()->back()->with('success', 'Booking confirmed');
    }

    public function destroy(Property $property, Booking $booking)
    {
        // Only admins can delete bookings
        if (!auth()->user()->isAdmin()) {
            abort(403, 'Unauthorized. Only admins can delete bookings.');
        }

        // Release room inventory before deleting the booking
        $this->bookingService->releaseRoomInventory($booking);

        // Completely delete the booking
        $booking->delete();
        return redirect()->route('properties.bookings.index', $property)
            ->with('success', 'Booking deleted successfully');
    }

    public function deleteMultiple(Request $request, Property $property)
    {
        // Only admins can delete bookings
        if (!auth()->user()->isAdmin()) {
            abort(403, 'Unauthorized. Only admins can delete bookings.');
        }

        $validated = $request->validate([
            'booking_ids' => 'required|array|min:1',
            'booking_ids.*' => 'exists:bookings,id',
        ]);

        $bookingIds = $validated['booking_ids'];
        $count = count($bookingIds);

        // Get all bookings to release their room inventory
        $bookings = Booking::whereIn('id', $bookingIds)
            ->where('property_id', $property->id)
            ->get();

        // Release room inventory for each booking
        foreach ($bookings as $booking) {
            $this->bookingService->releaseRoomInventory($booking);
        }

        // Completely delete all selected bookings
        Booking::whereIn('id', $bookingIds)
            ->where('property_id', $property->id)
            ->delete();

        return redirect()->route('properties.bookings.index', $property)
            ->with('success', "$count booking(s) deleted successfully");
    }

    // Public booking methods (no auth required)
    public function publicCreate(RoomType $roomType)
    {
        $checkIn = request('check_in') ? Carbon::parse(request('check_in')) : now();
        $checkOut = request('check_out') ? Carbon::parse(request('check_out')) : now()->addDays(1);
        $guests = request('guests', 1);

        $availableRooms = $roomType->rooms()
            ->with(['inventory' => function ($q) use ($checkIn, $checkOut) {
                $q->whereBetween('date', [$checkIn->toDateString(), $checkOut->copy()->subDay()->toDateString()]);
            }])
            ->get()
            ->filter(function ($room) use ($checkIn, $checkOut) {
                $currentDate = $checkIn->copy();
                while ($currentDate < $checkOut) {
                    $inventory = $room->inventory->firstWhere('date', $currentDate->toDateString());
                    if (!$inventory || $inventory->status !== 'available') {
                        return false;
                    }
                    $currentDate->addDay();
                }
                return true;
            });

        return view('bookings.public-create', compact('roomType', 'availableRooms', 'checkIn', 'checkOut', 'guests'));
    }

    public function publicStore(Request $request)
    {
        $validated = $request->validate([
            'room_type_id' => 'required|exists:room_types,id',
            'room_id' => 'required|exists:rooms,id',
            'check_in_date' => 'required|date|after_or_equal:today',
            'check_out_date' => 'required|date|after:check_in_date',
            'number_of_guests' => 'required|integer|min:1',
            'room_rate' => 'required|numeric|min:0',
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'special_requests' => 'nullable|string',
        ]);

        $roomType = RoomType::findOrFail($validated['room_type_id']);
        $property = $roomType->property;

        // Create or find guest
        $guest = Guest::firstOrCreate(
            ['email' => $validated['email']],
            [
                'property_id' => $property->id,
                'first_name' => $validated['first_name'],
                'last_name' => $validated['last_name'],
                'phone' => $validated['phone'],
            ]
        );

        // Create booking
        $bookingData = [
            'property_id' => $property->id,
            'guest_id' => $guest->id,
            'room_id' => $validated['room_id'],
            'check_in_date' => $validated['check_in_date'],
            'check_out_date' => $validated['check_out_date'],
            'number_of_guests' => $validated['number_of_guests'],
            'room_rate' => $validated['room_rate'],
            'special_requests' => $validated['special_requests'] ?? null,
        ];

        $nights = abs(Carbon::parse($validated['check_out_date'])
            ->diffInDays(Carbon::parse($validated['check_in_date'])));
        $bookingData['subtotal'] = abs($validated['room_rate'] * $nights);
        $taxRate = ($property->tax_rate ?? 10) / 100;
        $bookingData['tax'] = $bookingData['subtotal'] * $taxRate;
        $bookingData['total_amount'] = $bookingData['subtotal'] + $bookingData['tax'];

        $booking = $this->bookingService->createBooking($bookingData);

        return redirect()->route('booking.confirmation', $booking)
            ->with('success', 'Booking created successfully! Check your email for confirmation.');
    }

    public function confirmation(Booking $booking)
    {
        $booking->load(['guest', 'room', 'property']);
        return view('bookings.confirmation', compact('booking'));
    }
}

