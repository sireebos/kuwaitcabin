<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\MenuCategory;
use App\Models\Property;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class MenuCategoryController extends Controller
{
    public function index(Property $property, Menu $menu)
    {
        $categories = $menu->categories()->orderBy('sort_order')->paginate(15);
        return view('menu-categories.index', compact('property', 'menu', 'categories'));
    }

    public function create(Property $property, Menu $menu)
    {
        return view('menu-categories.create', compact('property', 'menu'));
    }

    public function store(Request $request, Property $property, Menu $menu)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        $validated['menu_id'] = $menu->id;
        $validated['slug'] = Str::slug($validated['name']);
        $validated['sort_order'] = $validated['sort_order'] ?? $menu->categories()->max('sort_order') + 1;

        MenuCategory::create($validated);

        return redirect()->route('properties.menus.categories.index', [$property, $menu])
            ->with('success', 'Category created successfully');
    }

    public function edit(Property $property, Menu $menu, MenuCategory $category)
    {
        return view('menu-categories.edit', compact('property', 'menu', 'category'));
    }

    public function update(Request $request, Property $property, Menu $menu, MenuCategory $category)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'sort_order' => 'nullable|integer|min:0',
            'is_active' => 'boolean',
        ]);

        $validated['slug'] = Str::slug($validated['name']);
        $category->update($validated);

        return redirect()->route('properties.menus.categories.index', [$property, $menu])
            ->with('success', 'Category updated successfully');
    }

    public function destroy(Property $property, Menu $menu, MenuCategory $category)
    {
        $category->delete();
        return redirect()->route('properties.menus.categories.index', [$property, $menu])
            ->with('success', 'Category deleted successfully');
    }
}

