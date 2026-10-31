<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Models\Property;
use App\Models\RoomType;
use Illuminate\Http\Request;

class RoomController extends Controller
{
    public function index(Property $property)
    {
        $rooms = $property->rooms()->with('roomType')->paginate(15);
        return view('rooms.index', compact('property', 'rooms'));
    }

    public function create(Property $property)
    {
        $roomTypes = $property->roomTypes()->where('is_active', true)->get();
        return view('rooms.create', compact('property', 'roomTypes'));
    }

    public function store(Request $request, Property $property)
    {
        $validated = $request->validate([
            'room_number' => 'required|string',
            'room_type_id' => 'required|exists:room_types,id',
            'floor' => 'required|integer|min:0',
            'wing' => 'nullable|string',
            'status' => 'required|in:available,maintenance,blocked',
            'notes' => 'nullable|string',
            'quantity' => 'nullable|integer|min:1|max:100',
            'price' => 'nullable|numeric|min:0',
        ]);

        $quantity = $validated['quantity'] ?? 1;
        $baseRoomNumber = (int) $validated['room_number'];
        $createdCount = 0;

        for ($i = 0; $i < $quantity; $i++) {
            $roomNumber = $baseRoomNumber + $i;

            // Check if room already exists
            if (Room::where('room_number', $roomNumber)->exists()) {
                continue;
            }

            Room::create([
                'property_id' => $property->id,
                'room_number' => $roomNumber,
                'room_type_id' => $validated['room_type_id'],
                'floor' => $validated['floor'],
                'wing' => $validated['wing'],
                'status' => $validated['status'],
                'notes' => $validated['notes'],
                'price' => $validated['price'],
            ]);

            $createdCount++;
        }

        $message = $quantity === 1
            ? 'Room created successfully'
            : "Created $createdCount room(s) successfully";

        return redirect()->route('properties.rooms.index', $property)
            ->with('success', $message);
    }

    public function show(Property $property, Room $room)
    {
        $room->load('roomType', 'bookings');
        return view('rooms.show', compact('property', 'room'));
    }

    public function edit(Property $property, Room $room)
    {
        $roomTypes = $property->roomTypes()->where('is_active', true)->get();
        return view('rooms.edit', compact('property', 'room', 'roomTypes'));
    }

    public function update(Request $request, Property $property, Room $room)
    {
        $validated = $request->validate([
            'room_number' => 'required|string|unique:rooms,room_number,' . $room->id,
            'room_type_id' => 'required|exists:room_types,id',
            'floor' => 'required|integer|min:0',
            'wing' => 'nullable|string',
            'status' => 'required|in:available,maintenance,blocked',
            'notes' => 'nullable|string',
            'price' => 'nullable|numeric|min:0',
        ]);

        $room->update($validated);

        return redirect()->route('properties.rooms.show', [$property, $room])
            ->with('success', 'Room updated successfully');
    }

    public function destroy(Property $property, Room $room)
    {
        $room->delete();
        return redirect()->route('properties.rooms.index', $property)
            ->with('success', 'Room deleted successfully');
    }

    public function bulkDelete(Request $request, Property $property)
    {
        $validated = $request->validate([
            'room_ids' => 'required|string',
        ]);

        $roomIds = json_decode($validated['room_ids'], true);

        if (!is_array($roomIds) || empty($roomIds)) {
            return redirect()->route('properties.rooms.index', $property)
                ->with('error', 'No rooms selected for deletion');
        }

        $deletedCount = Room::whereIn('id', $roomIds)
            ->where('property_id', $property->id)
            ->delete();

        return redirect()->route('properties.rooms.index', $property)
            ->with('success', "$deletedCount room(s) deleted successfully");
    }
}
