<?php

namespace App\Http\Controllers;

use App\Models\Property;
use Illuminate\Http\Request;

class PropertyController extends Controller
{
    public function index()
    {
        $properties = Property::paginate(15);
        return view('properties.index', compact('properties'));
    }

    public function create()
    {
        return view('properties.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:properties,slug',
            'description' => 'nullable|string',
            'address' => 'required|string|max:255',
            'city' => 'required|string|max:100',
            'state' => 'required|string|max:100',
            'postal_code' => 'required|string|max:20',
            'country' => 'required|string|max:100',
            'phone' => 'required|string|max:20',
            'email' => 'required|email',
            'website' => 'nullable|string|max:255',
            'total_rooms' => 'required|integer|min:1',
            'check_in_time' => 'required|numeric|min:0|max:24',
            'check_out_time' => 'required|numeric|min:0|max:24',
            'currency' => 'required|string|max:10',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');
        $property = Property::create($validated);

        return redirect()->route('properties.show', $property)
            ->with('success', 'Property created successfully');
    }

    public function show(Property $property)
    {
        $property->load(['rooms', 'roomTypes', 'bookings', 'guests']);
        $stats = [
            'total_rooms' => $property->rooms->count(),
            'total_bookings' => $property->bookings->count(),
            'total_guests' => $property->guests->count(),
            'room_types' => $property->roomTypes->count(),
        ];
        return view('properties.show', compact('property', 'stats'));
    }

    public function edit(Property $property)
    {
        return view('properties.edit', compact('property'));
    }

    public function update(Request $request, Property $property)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:properties,slug,' . $property->id,
            'description' => 'nullable|string',
            'address' => 'required|string|max:255',
            'city' => 'required|string|max:100',
            'state' => 'required|string|max:100',
            'postal_code' => 'required|string|max:20',
            'country' => 'required|string|max:100',
            'phone' => 'required|string|max:20',
            'email' => 'required|email',
            'website' => 'nullable|string|max:255',
            'total_rooms' => 'required|integer|min:1',
            'check_in_time' => 'required|numeric|min:0|max:24',
            'check_out_time' => 'required|numeric|min:0|max:24',
            'currency' => 'required|string|max:10',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');
        $property->update($validated);

        return redirect()->route('properties.show', $property)
            ->with('success', 'Property updated successfully');
    }

    public function destroy(Property $property)
    {
        $property->delete();
        return redirect()->route('properties.index')
            ->with('success', 'Property deleted successfully');
    }
}

