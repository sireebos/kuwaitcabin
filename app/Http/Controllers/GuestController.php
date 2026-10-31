<?php

namespace App\Http\Controllers;

use App\Models\Guest;
use App\Models\Property;
use Illuminate\Http\Request;

class GuestController extends Controller
{
    public function index(Property $property)
    {
        $guests = $property->guests()->paginate(15);
        return view('guests.index', compact('property', 'guests'));
    }

    public function create(Property $property)
    {
        return view('guests.create', compact('property'));
    }

    public function store(Request $request, Property $property)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:guests,email',
            'phone' => 'required|string|max:20',
            'address' => 'nullable|string|max:255',
        ]);

        $validated['property_id'] = $property->id;
        $guest = Guest::create($validated);

        return redirect()->route('properties.guests.show', [$property, $guest])
            ->with('success', 'Guest registered successfully');
    }

    public function show(Property $property, Guest $guest)
    {
        $guest->load('bookings');
        return view('guests.show', compact('property', 'guest'));
    }

    public function edit(Property $property, Guest $guest)
    {
        return view('guests.edit', compact('property', 'guest'));
    }

    public function update(Request $request, Property $property, Guest $guest)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:guests,email,' . $guest->id,
            'phone' => 'required|string|max:20',
            'address' => 'nullable|string|max:255',
        ]);

        $guest->update($validated);

        return redirect()->route('properties.guests.show', [$property, $guest])
            ->with('success', 'Guest updated successfully');
    }

    public function destroy(Property $property, Guest $guest)
    {
        $guest->delete();
        return redirect()->route('properties.guests.index', $property)
            ->with('success', 'Guest deleted successfully');
    }

    public function quickCreate(Request $request, Property $property)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:guests,email',
            'phone' => 'required|string|max:20',
            'address' => 'nullable|string|max:255',
        ]);

        $validated['property_id'] = $property->id;
        $guest = Guest::create($validated);

        return response()->json([
            'success' => true,
            'guest' => $guest,
            'message' => 'Guest created successfully'
        ]);
    }
}
