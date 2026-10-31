<?php

namespace App\Http\Controllers;

use App\Models\RoomType;
use App\Models\Property;
use Illuminate\Http\Request;

class RoomTypeController extends Controller
{
    public function index(Property $property)
    {
        $roomTypes = $property->roomTypes()->paginate(15);
        return view('room-types.index', compact('property', 'roomTypes'));
    }

    public function create(Property $property)
    {
        $amenitiesList = [
            'WiFi', 'Air Conditioning', 'Heating', 'TV', 'Mini Bar', 'Safe',
            'Desk', 'Phone', 'Bathroom', 'Shower', 'Bathtub', 'Hairdryer',
            'Iron', 'Ironing Board', 'Coffee Maker', 'Microwave', 'Refrigerator',
            'Balcony', 'Terrace', 'Garden View', 'City View', 'Sea View',
            'Kitchenette', 'Sofa', 'Dining Area', 'Work Area', 'Wheelchair Access'
        ];
        return view('room-types.create', compact('property', 'amenitiesList'));
    }

    public function store(Request $request, Property $property)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:room_types,slug',
            'description' => 'nullable|string',
            'capacity' => 'required|integer|min:1',
            'beds' => 'required|integer|min:1',
            'base_price' => 'required|numeric|min:0',
            'total_rooms' => 'required|integer|min:1',
            'amenities' => 'nullable|array',
            'is_active' => 'boolean',
        ]);

        $validated['property_id'] = $property->id;
        $validated['is_active'] = $request->has('is_active');
        $validated['amenities'] = $request->input('amenities', []);

        $roomType = RoomType::create($validated);

        return redirect()->route('properties.room-types.show', [$property, $roomType])
            ->with('success', 'Room type created successfully');
    }

    public function show(Property $property, RoomType $roomType)
    {
        $roomType->load('rooms', 'ratePlans');
        return view('room-types.show', compact('property', 'roomType'));
    }

    public function edit(Property $property, RoomType $roomType)
    {
        $amenitiesList = [
            'WiFi', 'Air Conditioning', 'Heating', 'TV', 'Mini Bar', 'Safe',
            'Desk', 'Phone', 'Bathroom', 'Shower', 'Bathtub', 'Hairdryer',
            'Iron', 'Ironing Board', 'Coffee Maker', 'Microwave', 'Refrigerator',
            'Balcony', 'Terrace', 'Garden View', 'City View', 'Sea View',
            'Kitchenette', 'Sofa', 'Dining Area', 'Work Area', 'Wheelchair Access'
        ];
        return view('room-types.edit', compact('property', 'roomType', 'amenitiesList'));
    }

    public function update(Request $request, Property $property, RoomType $roomType)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:room_types,slug,' . $roomType->id,
            'description' => 'nullable|string',
            'capacity' => 'required|integer|min:1',
            'beds' => 'required|integer|min:1',
            'base_price' => 'required|numeric|min:0',
            'total_rooms' => 'required|integer|min:1',
            'amenities' => 'nullable|array',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');
        $validated['amenities'] = $request->input('amenities', []);

        $roomType->update($validated);

        return redirect()->route('properties.room-types.show', [$property, $roomType])
            ->with('success', 'Room type updated successfully');
    }

    public function destroy(Property $property, RoomType $roomType)
    {
        $roomType->delete();
        return redirect()->route('properties.room-types.index', $property)
            ->with('success', 'Room type deleted successfully');
    }
}

