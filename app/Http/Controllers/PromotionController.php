<?php

namespace App\Http\Controllers;

use App\Models\Property;
use App\Models\Promotion;
use Illuminate\Http\Request;

class PromotionController extends Controller
{
    public function index(Property $property)
    {
        $promotions = $property->promotions()->paginate(15);
        return view('promotions.index', compact('property', 'promotions'));
    }

    public function create(Property $property)
    {
        return view('promotions.create', compact('property'));
    }

    public function store(Request $request, Property $property)
    {
        $validated = $request->validate([
            'code' => 'required|string|unique:promotions,code',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'required|in:percentage,fixed',
            'value' => 'required|numeric|min:0.01',
            'min_order_amount' => 'nullable|numeric|min:0',
            'max_uses' => 'nullable|integer|min:1',
            'valid_from' => 'nullable|date',
            'valid_to' => 'nullable|date|after_or_equal:valid_from',
            'is_active' => 'boolean',
        ]);

        $property->promotions()->create($validated);
        return redirect()->route('properties.promotions.index', $property)->with('success', 'Promotion created successfully');
    }

    public function edit(Property $property, Promotion $promotion)
    {
        return view('promotions.edit', compact('property', 'promotion'));
    }

    public function update(Request $request, Property $property, Promotion $promotion)
    {
        $validated = $request->validate([
            'code' => 'required|string|unique:promotions,code,' . $promotion->id,
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'required|in:percentage,fixed',
            'value' => 'required|numeric|min:0.01',
            'min_order_amount' => 'nullable|numeric|min:0',
            'max_uses' => 'nullable|integer|min:1',
            'valid_from' => 'nullable|date',
            'valid_to' => 'nullable|date|after_or_equal:valid_from',
            'is_active' => 'boolean',
        ]);

        $promotion->update($validated);
        return redirect()->route('properties.promotions.index', $property)->with('success', 'Promotion updated successfully');
    }

    public function destroy(Property $property, Promotion $promotion)
    {
        $promotion->delete();
        return redirect()->route('properties.promotions.index', $property)->with('success', 'Promotion deleted successfully');
    }

    public function validateCode(Request $request, Property $property)
    {
        $code = $request->input('code');
        $promotion = $property->promotions()->where('code', $code)->first();

        if (!$promotion || !$promotion->isValid()) {
            return response()->json(['valid' => false, 'message' => 'Invalid or expired promotion code']);
        }

        return response()->json([
            'valid' => true,
            'promotion' => $promotion,
            'discount' => $promotion->calculateDiscount($request->input('amount', 0))
        ]);
    }
}
