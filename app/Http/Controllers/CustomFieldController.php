<?php

namespace App\Http\Controllers;

use App\Models\CustomField;
use App\Models\Property;
use Illuminate\Http\Request;

class CustomFieldController extends Controller
{
    public function index(Property $property, $entityType)
    {
        $customFields = $property->customFields()
            ->where('entity_type', $entityType)
            ->orderBy('order')
            ->get();

        return view('custom-fields.index', compact('property', 'customFields', 'entityType'));
    }

    public function create(Property $property, $entityType)
    {
        return view('custom-fields.create', compact('property', 'entityType'));
    }

    public function store(Request $request, Property $property, $entityType)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:custom_fields,name,NULL,id,property_id,' . $property->id . ',entity_type,' . $entityType,
            'label' => 'required|string|max:255',
            'type' => 'required|in:text,number,email,phone,date,textarea,select,checkbox',
            'options' => 'nullable|string',
            'is_required' => 'boolean',
            'order' => 'nullable|integer|min:0',
        ]);

        $validated['property_id'] = $property->id;
        $validated['entity_type'] = $entityType;
        $validated['is_required'] = $request->has('is_required');

        if ($validated['type'] === 'select' && $validated['options']) {
            $validated['options'] = array_filter(array_map('trim', explode(',', $validated['options'])));
        }

        CustomField::create($validated);

        return redirect()->route('properties.custom-fields.index', [$property, $entityType])
            ->with('success', 'Custom field created successfully');
    }

    public function edit(Property $property, CustomField $customField)
    {
        return view('custom-fields.edit', compact('property', 'customField'));
    }

    public function update(Request $request, Property $property, CustomField $customField)
    {
        $validated = $request->validate([
            'label' => 'required|string|max:255',
            'type' => 'required|in:text,number,email,phone,date,textarea,select,checkbox',
            'options' => 'nullable|string',
            'is_required' => 'boolean',
            'order' => 'nullable|integer|min:0',
        ]);

        $validated['is_required'] = $request->has('is_required');

        if ($validated['type'] === 'select' && $validated['options']) {
            $validated['options'] = array_filter(array_map('trim', explode(',', $validated['options'])));
        }

        $customField->update($validated);

        return redirect()->route('properties.custom-fields.index', [$property, $customField->entity_type])
            ->with('success', 'Custom field updated successfully');
    }

    public function destroy(Property $property, CustomField $customField)
    {
        $entityType = $customField->entity_type;
        $customField->delete();

        return redirect()->route('properties.custom-fields.index', [$property, $entityType])
            ->with('success', 'Custom field deleted successfully');
    }
}
