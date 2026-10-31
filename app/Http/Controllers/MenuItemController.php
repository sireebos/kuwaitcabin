<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\MenuCategory;
use App\Models\MenuItem;
use App\Models\Property;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class MenuItemController extends Controller
{
    public function index(Property $property, Menu $menu, MenuCategory $category)
    {
        $items = $category->items()->orderBy('sort_order')->paginate(15);
        return view('menu-items.index', compact('property', 'menu', 'category', 'items'));
    }

    public function create(Property $property, Menu $menu, MenuCategory $category)
    {
        return view('menu-items.create', compact('property', 'menu', 'category'));
    }

    public function store(Request $request, Property $property, Menu $menu, MenuCategory $category)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'ingredients' => 'nullable|string',
            'modifiers' => 'nullable|string',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        $validated['menu_category_id'] = $category->id;
        $validated['slug'] = Str::slug($validated['name']);
        $validated['ingredients'] = $validated['ingredients'] ? array_map('trim', explode(',', $validated['ingredients'])) : null;
        $validated['modifiers'] = $validated['modifiers'] ? array_map('trim', explode(',', $validated['modifiers'])) : null;
        $validated['sort_order'] = $validated['sort_order'] ?? $category->items()->max('sort_order') + 1;

        MenuItem::create($validated);

        return redirect()->route('properties.menus.categories.items.index', [$property, $menu, $category])
            ->with('success', 'Menu item created successfully');
    }

    public function edit(Property $property, Menu $menu, MenuCategory $category, MenuItem $item)
    {
        return view('menu-items.edit', compact('property', 'menu', 'category', 'item'));
    }

    public function update(Request $request, Property $property, Menu $menu, MenuCategory $category, MenuItem $item)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'ingredients' => 'nullable|string',
            'modifiers' => 'nullable|string',
            'sort_order' => 'nullable|integer|min:0',
            'is_available' => 'boolean',
        ]);

        $validated['slug'] = Str::slug($validated['name']);
        $validated['ingredients'] = $validated['ingredients'] ? array_map('trim', explode(',', $validated['ingredients'])) : null;
        $validated['modifiers'] = $validated['modifiers'] ? array_map('trim', explode(',', $validated['modifiers'])) : null;
        $item->update($validated);

        return redirect()->route('properties.menus.categories.items.index', [$property, $menu, $category])
            ->with('success', 'Menu item updated successfully');
    }

    public function destroy(Property $property, Menu $menu, MenuCategory $category, MenuItem $item)
    {
        $item->delete();
        return redirect()->route('properties.menus.categories.items.index', [$property, $menu, $category])
            ->with('success', 'Menu item deleted successfully');
    }
}

