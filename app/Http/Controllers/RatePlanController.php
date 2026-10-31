<?php

namespace App\Http\Controllers;

use App\Models\RatePlan;
use App\Models\Property;
use App\Models\RoomType;
use Illuminate\Http\Request;

class RatePlanController extends Controller
{
    public function index(Property $property)
    {
        $ratePlans = $property->ratePlans()->with('roomType')->paginate(15);
        return view('rate-plans.index', compact('property', 'ratePlans'));
    }

    public function create(Property $property)
    {
        $roomTypes = $property->roomTypes()->where('is_active', true)->get();
        return view('rate-plans.create', compact('property', 'roomTypes'));
    }

    public function store(Request $request, Property $property)
    {
        $validated = $request->validate([
            'room_type_id' => 'required|exists:room_types,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'base_rate' => 'required|numeric|min:0',
            'discount_percentage' => 'nullable|numeric|min:0|max:100',
            'valid_from' => 'required|date',
            'valid_to' => 'required|date|after_or_equal:valid_from',
            'is_active' => 'boolean',
        ]);

        $validated['property_id'] = $property->id;
        $validated['is_active'] = $request->has('is_active');
        $validated['discount_percentage'] = $request->input('discount_percentage', 0);

        $ratePlan = RatePlan::create($validated);

        return redirect()->route('properties.rate-plans.show', [$property, $ratePlan])
            ->with('success', 'Rate plan created successfully');
    }

    public function show(Property $property, RatePlan $ratePlan)
    {
        $ratePlan->load('roomType');
        return view('rate-plans.show', compact('property', 'ratePlan'));
    }

    public function edit(Property $property, RatePlan $ratePlan)
    {
        $roomTypes = $property->roomTypes()->where('is_active', true)->get();
        return view('rate-plans.edit', compact('property', 'ratePlan', 'roomTypes'));
    }

    public function update(Request $request, Property $property, RatePlan $ratePlan)
    {
        $validated = $request->validate([
            'room_type_id' => 'required|exists:room_types,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'base_rate' => 'required|numeric|min:0',
            'discount_percentage' => 'nullable|numeric|min:0|max:100',
            'valid_from' => 'required|date',
            'valid_to' => 'required|date|after_or_equal:valid_from',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');
        $validated['discount_percentage'] = $request->input('discount_percentage', 0);

        $ratePlan->update($validated);

        return redirect()->route('properties.rate-plans.show', [$property, $ratePlan])
            ->with('success', 'Rate plan updated successfully');
    }

    public function destroy(Property $property, RatePlan $ratePlan)
    {
        $ratePlan->delete();
        return redirect()->route('properties.rate-plans.index', $property)
            ->with('success', 'Rate plan deleted successfully');
    }
}

