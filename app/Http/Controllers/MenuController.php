<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\MenuCategory;
use App\Models\MenuItem;
use App\Models\Property;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class MenuController extends Controller
{
    public function index(Property $property)
    {
        $menus = $property->menus()->paginate(15);
        return view('menus.index', compact('property', 'menus'));
    }

    public function create(Property $property)
    {
        return view('menus.create', compact('property'));
    }

    public function store(Request $request, Property $property)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'required|in:food,beverage,both',
        ]);

        $validated['property_id'] = $property->id;
        $validated['slug'] = Str::slug($validated['name']);

        $menu = Menu::create($validated);

        return redirect()->route('properties.menus.show', [$property, $menu])
            ->with('success', 'Menu created successfully');
    }

    public function show(Property $property, Menu $menu)
    {
        $menu->load('categories.items');
        return view('menus.show', compact('property', 'menu'));
    }

    public function edit(Property $property, Menu $menu)
    {
        return view('menus.edit', compact('property', 'menu'));
    }

    public function update(Request $request, Property $property, Menu $menu)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'required|in:food,beverage,both',
            'is_active' => 'boolean',
        ]);

        $validated['slug'] = Str::slug($validated['name']);
        $menu->update($validated);

        return redirect()->route('properties.menus.show', [$property, $menu])
            ->with('success', 'Menu updated successfully');
    }

    public function destroy(Property $property, Menu $menu)
    {
        $menu->delete();
        return redirect()->route('properties.menus.index', $property)
            ->with('success', 'Menu deleted successfully');
    }
}

