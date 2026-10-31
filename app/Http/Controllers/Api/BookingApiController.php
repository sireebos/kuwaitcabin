<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Property;
use App\Services\BookingService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class BookingApiController extends Controller
{
    public function __construct(private BookingService $bookingService)
    {
    }

    public function search(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'property_id' => 'required|exists:properties,id',
            'check_in_date' => 'required|date',
            'check_out_date' => 'required|date|after:check_in_date',
            'guests' => 'required|integer|min:1',
        ]);

        $property = Property::find($validated['property_id']);
        $availableRooms = $this->bookingService->getAvailableRooms(
            $validated['check_in_date'],
            $validated['check_out_date'],
            $property->id
        );

        return response()->json([
            'success' => true,
            'available_rooms' => $availableRooms,
            'count' => count($availableRooms),
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'property_id' => 'required|exists:properties,id',
            'guest_id' => 'required|exists:guests,id',
            'room_id' => 'required|exists:rooms,id',
            'check_in_date' => 'required|date',
            'check_out_date' => 'required|date|after:check_in_date',
            'number_of_guests' => 'required|integer|min:1',
            'room_rate' => 'required|numeric|min:0',
        ]);

        try {
            $booking = $this->bookingService->createBooking($validated);
            return response()->json([
                'success' => true,
                'message' => 'Booking created successfully',
                'booking' => $booking,
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    public function show(Booking $booking): JsonResponse
    {
        $booking->load(['guest', 'room', 'payments', 'folio']);
        return response()->json([
            'success' => true,
            'booking' => $booking,
        ]);
    }

    public function confirm(Booking $booking): JsonResponse
    {
        $this->bookingService->confirmBooking($booking);
        return response()->json([
            'success' => true,
            'message' => 'Booking confirmed',
            'booking' => $booking,
        ]);
    }
}

