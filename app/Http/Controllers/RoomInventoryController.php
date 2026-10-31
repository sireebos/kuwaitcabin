<?php

namespace App\Http\Controllers;

use App\Models\RoomInventory;
use App\Models\Property;
use App\Models\Room;
use Illuminate\Http\Request;
use Carbon\Carbon;

class RoomInventoryController extends Controller
{
    public function index(Property $property)
    {
        $startDate = request('start_date') ? Carbon::parse(request('start_date')) : now();
        $endDate = request('end_date') ? Carbon::parse(request('end_date')) : now()->addDays(30);

        $rooms = $property->rooms()->with(['roomType', 'inventory' => function ($query) use ($startDate, $endDate) {
            $query->whereBetween('date', [$startDate, $endDate])->orderBy('date');
        }])->get();

        $dates = [];
        $currentDate = $startDate->copy();
        while ($currentDate <= $endDate) {
            $dates[] = $currentDate->copy();
            $currentDate->addDay();
        }

        return view('room-inventory.index', compact('property', 'rooms', 'dates', 'startDate', 'endDate'));
    }

    public function create(Property $property)
    {
        $rooms = $property->rooms()->with('roomType')->get();
        return view('room-inventory.create', compact('property', 'rooms'));
    }

    public function store(Request $request, Property $property)
    {
        $validated = $request->validate([
            'room_id' => 'required|exists:rooms,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'status' => 'required|in:available,occupied,maintenance,blocked',
            'price' => 'nullable|numeric|min:0',
        ]);

        $room = Room::findOrFail($validated['room_id']);
        $startDate = Carbon::parse($validated['start_date']);
        $endDate = Carbon::parse($validated['end_date']);

        $currentDate = $startDate->copy();
        while ($currentDate <= $endDate) {
            RoomInventory::updateOrCreate(
                ['room_id' => $room->id, 'date' => $currentDate->toDateString()],
                [
                    'status' => $validated['status'],
                    'price' => $validated['price'] ?? $room->roomType->base_price,
                ]
            );
            $currentDate->addDay();
        }

        return redirect()->route('properties.room-inventory.index', $property)
            ->with('success', 'Room inventory updated successfully');
    }

    public function show(Property $property, RoomInventory $roomInventory)
    {
        $roomInventory->load('room.roomType');
        return view('room-inventory.show', compact('property', 'roomInventory'));
    }

    public function edit(Property $property, RoomInventory $roomInventory)
    {
        $rooms = $property->rooms()->with('roomType')->get();
        return view('room-inventory.edit', compact('property', 'roomInventory', 'rooms'));
    }

    public function update(Request $request, Property $property, RoomInventory $roomInventory)
    {
        $validated = $request->validate([
            'room_id' => 'required|exists:rooms,id',
            'date' => 'required|date',
            'status' => 'required|in:available,occupied,maintenance,blocked',
            'price' => 'nullable|numeric|min:0',
        ]);

        $roomInventory->update($validated);

        return redirect()->route('properties.room-inventory.show', [$property, $roomInventory])
            ->with('success', 'Room inventory updated successfully');
    }

    public function destroy(Property $property, RoomInventory $roomInventory)
    {
        $roomInventory->delete();
        return redirect()->route('properties.room-inventory.index', $property)
            ->with('success', 'Room inventory deleted successfully');
    }

    public function bulkUpdate(Request $request, Property $property)
    {
        $validated = $request->validate([
            'room_id' => 'required|exists:rooms,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'status' => 'required|in:available,occupied,maintenance,blocked',
            'price' => 'nullable|numeric|min:0',
        ]);

        $room = Room::findOrFail($validated['room_id']);
        $startDate = Carbon::parse($validated['start_date']);
        $endDate = Carbon::parse($validated['end_date']);

        $currentDate = $startDate->copy();
        while ($currentDate <= $endDate) {
            RoomInventory::updateOrCreate(
                ['room_id' => $room->id, 'date' => $currentDate->toDateString()],
                [
                    'status' => $validated['status'],
                    'price' => $validated['price'] ?? $room->roomType->base_price,
                ]
            );
            $currentDate->addDay();
        }

        return redirect()->route('properties.room-inventory.index', $property)
            ->with('success', 'Room inventory bulk updated successfully');
    }
}

