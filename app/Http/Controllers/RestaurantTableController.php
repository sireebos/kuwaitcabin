<?php

namespace App\Http\Controllers;

use App\Models\RestaurantTable;
use App\Models\Property;
use Illuminate\Http\Request;

class RestaurantTableController extends Controller
{
    public function index(Property $property)
    {
        $tables = $property->restaurantTables()->orderBy('section')->orderBy('table_number')->paginate(20);
        $sections = $property->restaurantTables()->distinct()->pluck('section')->filter()->sort();
        return view('restaurant-tables.index', compact('property', 'tables', 'sections'));
    }

    public function layout(Property $property)
    {
        $tables = $property->restaurantTables()->orderBy('section')->orderBy('table_number')->get();
        $sections = $tables->pluck('section')->unique()->filter()->sort()->values();
        return view('restaurant-tables.layout', compact('property', 'tables', 'sections'));
    }

    public function create(Property $property)
    {
        $sections = $property->restaurantTables()->distinct()->pluck('section')->filter()->sort();
        return view('restaurant-tables.create', compact('property', 'sections'));
    }

    public function store(Request $request, Property $property)
    {
        $validated = $request->validate([
            'table_number' => 'required|string|max:50|unique:restaurant_tables,table_number',
            'capacity' => 'required|integer|min:1|max:20',
            'section' => 'nullable|string|max:100',
            'notes' => 'nullable|string',
        ]);

        $validated['property_id'] = $property->id;
        RestaurantTable::create($validated);

        return redirect()->route('properties.restaurant-tables.index', $property)
            ->with('success', 'Table created successfully');
    }

    public function edit(Property $property, RestaurantTable $restaurantTable)
    {
        $sections = $property->restaurantTables()->distinct()->pluck('section')->filter()->sort();
        return view('restaurant-tables.edit', compact('property', 'restaurantTable', 'sections'));
    }

    public function update(Request $request, Property $property, RestaurantTable $restaurantTable)
    {
        $validated = $request->validate([
            'table_number' => 'required|string|max:50|unique:restaurant_tables,table_number,' . $restaurantTable->id,
            'capacity' => 'required|integer|min:1|max:20',
            'section' => 'nullable|string|max:100',
            'status' => 'required|in:available,occupied,reserved,maintenance',
            'notes' => 'nullable|string',
        ]);

        $restaurantTable->update($validated);

        return redirect()->route('properties.restaurant-tables.index', $property)
            ->with('success', 'Table updated successfully');
    }

    public function destroy(Property $property, RestaurantTable $restaurantTable)
    {
        $restaurantTable->delete();
        return redirect()->route('properties.restaurant-tables.index', $property)
            ->with('success', 'Table deleted successfully');
    }

    public function bulkCreate(Request $request, Property $property)
    {
        $validated = $request->validate([
            'start_number' => 'required|integer|min:1',
            'count' => 'required|integer|min:1|max:100',
            'capacity' => 'required|integer|min:1|max:20',
            'section' => 'nullable|string|max:100',
        ]);

        for ($i = 0; $i < $validated['count']; $i++) {
            RestaurantTable::create([
                'property_id' => $property->id,
                'table_number' => (string)($validated['start_number'] + $i),
                'capacity' => $validated['capacity'],
                'section' => $validated['section'],
            ]);
        }

        return redirect()->route('properties.restaurant-tables.index', $property)
            ->with('success', $validated['count'] . ' tables created successfully');
    }
}

