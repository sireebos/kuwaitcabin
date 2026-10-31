<?php

namespace App\Http\Controllers;

use App\Models\RestaurantReservation;
use App\Models\RestaurantTable;
use App\Models\Property;
use App\Models\Guest;
use Illuminate\Http\Request;

class RestaurantReservationController extends Controller
{
    public function index(Property $property)
    {
        $reservations = $property->restaurantReservations()
            ->with('table', 'guest')
            ->orderBy('reservation_time', 'desc')
            ->paginate(15);
        return view('restaurant-reservations.index', compact('property', 'reservations'));
    }

    public function create(Property $property)
    {
        $tables = $property->restaurantTables()->where('status', 'available')->get();
        $guests = Guest::where('property_id', $property->id)->get();
        return view('restaurant-reservations.create', compact('property', 'tables', 'guests'));
    }

    public function store(Request $request, Property $property)
    {
        $validated = $request->validate([
            'restaurant_table_id' => 'required|exists:restaurant_tables,id',
            'guest_id' => 'nullable|exists:guests,id',
            'guest_name' => 'required|string|max:255',
            'guest_phone' => 'required|string|max:20',
            'reservation_time' => 'required|date_format:Y-m-d H:i|after:now',
            'number_of_guests' => 'required|integer|min:1|max:20',
            'special_requests' => 'nullable|string',
        ]);

        $validated['property_id'] = $property->id;
        $validated['status'] = 'pending';

        RestaurantReservation::create($validated);

        return redirect()->route('properties.restaurant-reservations.index', $property)
            ->with('success', 'Reservation created successfully');
    }

    public function show(Property $property, RestaurantReservation $reservation)
    {
        $reservation->load('table', 'guest');
        return view('restaurant-reservations.show', compact('property', 'reservation'));
    }

    public function edit(Property $property, RestaurantReservation $reservation)
    {
        $tables = $property->restaurantTables()->get();
        $guests = Guest::where('property_id', $property->id)->get();
        return view('restaurant-reservations.edit', compact('property', 'reservation', 'tables', 'guests'));
    }

    public function update(Request $request, Property $property, RestaurantReservation $reservation)
    {
        $validated = $request->validate([
            'restaurant_table_id' => 'required|exists:restaurant_tables,id',
            'guest_id' => 'nullable|exists:guests,id',
            'guest_name' => 'required|string|max:255',
            'guest_phone' => 'required|string|max:20',
            'reservation_time' => 'required|date_format:Y-m-d H:i',
            'number_of_guests' => 'required|integer|min:1|max:20',
            'status' => 'required|in:pending,confirmed,seated,completed,cancelled',
            'special_requests' => 'nullable|string',
        ]);

        $reservation->update($validated);

        return redirect()->route('properties.restaurant-reservations.show', [$property, $reservation])
            ->with('success', 'Reservation updated successfully');
    }

    public function destroy(Property $property, RestaurantReservation $reservation)
    {
        $reservation->delete();
        return redirect()->route('properties.restaurant-reservations.index', $property)
            ->with('success', 'Reservation deleted successfully');
    }

    public function updateStatus(Request $request, Property $property, RestaurantReservation $reservation)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,confirmed,seated,completed,cancelled',
        ]);

        $reservation->update($validated);

        return redirect()->back()->with('success', 'Reservation status updated');
    }
}

