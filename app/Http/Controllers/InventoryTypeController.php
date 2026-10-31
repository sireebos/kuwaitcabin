<?php

namespace App\Http\Controllers;

use App\Models\InventoryType;
use App\Models\Property;
use Illuminate\Http\Request;

class InventoryTypeController extends Controller
{
    public function index(Property $property)
    {
        $types = $property->inventoryTypes()->orderBy('name')->paginate(15);
        return view('inventory-types.index', compact('property', 'types'));
    }

    public function create(Property $property)
    {
        return view('inventory-types.create', compact('property'));
    }

    public function store(Request $request, Property $property)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:inventory_types,name,NULL,id,property_id,' . $property->id,
            'description' => 'nullable|string|max:500',
        ]);

        $validated['property_id'] = $property->id;
        InventoryType::create($validated);

        return redirect()->route('properties.inventory-types.index', $property)
            ->with('success', 'Inventory type created successfully');
    }

    public function edit(Property $property, InventoryType $inventoryType)
    {
        return view('inventory-types.edit', compact('property', 'inventoryType'));
    }

    public function update(Request $request, Property $property, InventoryType $inventoryType)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:inventory_types,name,' . $inventoryType->id . ',id,property_id,' . $property->id,
            'description' => 'nullable|string|max:500',
        ]);

        $inventoryType->update($validated);

        return redirect()->route('properties.inventory-types.index', $property)
            ->with('success', 'Inventory type updated successfully');
    }

    public function destroy(Property $property, InventoryType $inventoryType)
    {
        $inventoryType->delete();

        return redirect()->route('properties.inventory-types.index', $property)
            ->with('success', 'Inventory type deleted successfully');
    }
}
