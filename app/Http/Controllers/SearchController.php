<?php

namespace App\Http\Controllers;

use App\Models\Property;
use App\Models\RoomType;
use App\Models\RoomInventory;
use Illuminate\Http\Request;
use Carbon\Carbon;

class SearchController extends Controller
{
    public function index()
    {
        $properties = Property::where('is_active', true)->get();
        return view('search.index', compact('properties'));
    }

    public function search(Request $request)
    {
        $validated = $request->validate([
            'property_id' => 'nullable|exists:properties,id',
            'check_in' => 'required|date|after_or_equal:today',
            'check_out' => 'required|date|after:check_in',
            'guests' => 'required|integer|min:1',
            'room_type_id' => 'nullable|exists:room_types,id',
        ]);

        $checkIn = Carbon::parse($validated['check_in']);
        $checkOut = Carbon::parse($validated['check_out']);
        $guests = $validated['guests'];

        // Get available rooms
        $query = RoomType::where('is_active', true)
            ->where('capacity', '>=', $guests);

        if ($validated['property_id']) {
            $query->where('property_id', $validated['property_id']);
        }

        if ($validated['room_type_id']) {
            $query->where('id', $validated['room_type_id']);
        }

        $roomTypes = $query->with(['property', 'rooms' => function ($q) use ($checkIn, $checkOut) {
            $q->with(['inventory' => function ($inv) use ($checkIn, $checkOut) {
                $inv->whereBetween('date', [$checkIn->toDateString(), $checkOut->copy()->subDay()->toDateString()]);
            }]);
        }])->get();

        // Filter available rooms
        $availableRooms = [];
        foreach ($roomTypes as $roomType) {
            $availableCount = 0;
            foreach ($roomType->rooms as $room) {
                $isAvailable = true;
                $currentDate = $checkIn->copy();
                while ($currentDate < $checkOut) {
                    $inventory = $room->inventory->firstWhere('date', $currentDate->toDateString());
                    if (!$inventory || $inventory->status !== 'available') {
                        $isAvailable = false;
                        break;
                    }
                    $currentDate->addDay();
                }
                if ($isAvailable) {
                    $availableCount++;
                }
            }

            if ($availableCount > 0) {
                $availableRooms[] = [
                    'room_type' => $roomType,
                    'available_count' => $availableCount,
                    'nights' => $checkOut->diffInDays($checkIn),
                    'total_price' => $roomType->base_price * $checkOut->diffInDays($checkIn),
                ];
            }
        }

        return view('search.results', compact('availableRooms', 'checkIn', 'checkOut', 'guests'));
    }

    public function availability(Request $request)
    {
        $validated = $request->validate([
            'property_id' => 'required|exists:properties,id',
            'check_in' => 'required|date',
            'check_out' => 'required|date|after:check_in',
        ]);

        $property = Property::findOrFail($validated['property_id']);
        $checkIn = Carbon::parse($validated['check_in']);
        $checkOut = Carbon::parse($validated['check_out']);

        $availability = [];
        $currentDate = $checkIn->copy();

        while ($currentDate < $checkOut) {
            $dateStr = $currentDate->toDateString();
            $availableRooms = RoomInventory::where('date', $dateStr)
                ->where('status', 'available')
                ->count();

            $availability[] = [
                'date' => $dateStr,
                'available_rooms' => $availableRooms,
            ];

            $currentDate->addDay();
        }

        return response()->json($availability);
    }
}

