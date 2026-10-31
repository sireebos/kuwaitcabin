<?php

namespace App\Http\Controllers;

use App\Models\Property;
use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Carbon\Carbon;

class RoomAvailabilityController extends Controller
{
    /**
     * Check availability for multiple rooms
     */
    public function checkAvailability(Request $request, Property $property): JsonResponse
    {
        $validated = $request->validate([
            'room_ids' => 'required|array|min:1',
            'room_ids.*' => 'exists:rooms,id',
            'check_in_date' => 'required|date',
            'check_out_date' => 'required|date|after:check_in_date',
        ]);

        $checkInDate = Carbon::parse($validated['check_in_date']);
        $checkOutDate = Carbon::parse($validated['check_out_date']);
        $roomIds = $validated['room_ids'];

        $availability = [];
        $allAvailable = true;

        foreach ($roomIds as $roomId) {
            $room = Room::find($roomId);
            
            if (!$room) {
                continue;
            }

            $isAvailable = $room->isAvailable($checkInDate, $checkOutDate);
            
            $availability[] = [
                'room_id' => $room->id,
                'room_number' => $room->room_number,
                'room_type' => $room->roomType->name,
                'available' => $isAvailable,
                'status' => $isAvailable ? 'available' : 'taken',
                'message' => $isAvailable 
                    ? "✅ Room {$room->room_number} is available"
                    : "⚠️ Room {$room->room_number} is taken for these dates",
            ];

            if (!$isAvailable) {
                $allAvailable = false;
            }
        }

        return response()->json([
            'success' => true,
            'all_available' => $allAvailable,
            'availability' => $availability,
        ]);
    }

    /**
     * Get available rooms for a date range
     */
    public function getAvailableRooms(Request $request, Property $property): JsonResponse
    {
        $validated = $request->validate([
            'check_in_date' => 'required|date',
            'check_out_date' => 'required|date|after:check_in_date',
        ]);

        $checkInDate = Carbon::parse($validated['check_in_date']);
        $checkOutDate = Carbon::parse($validated['check_out_date']);

        $allRooms = $property->rooms()
            ->with('roomType')
            ->get();

        $rooms = $allRooms->map(function ($room) use ($checkInDate, $checkOutDate) {
            $isAvailable = $room->isAvailable($checkInDate, $checkOutDate);
            return [
                'id' => $room->id,
                'room_number' => $room->room_number,
                'room_type' => $room->roomType->name,
                'price' => $room->getEffectivePrice(),
                'available' => $isAvailable,
            ];
        })->values();

        return response()->json([
            'success' => true,
            'rooms' => $rooms,
        ]);
    }
}

