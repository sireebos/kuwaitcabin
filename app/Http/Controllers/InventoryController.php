<?php

namespace App\Http\Controllers;

use App\Models\InventoryItem;
use App\Models\Property;
use App\Services\InventoryService;
use App\Repositories\InventoryRepository;
use Illuminate\Http\Request;

class InventoryController extends Controller
{
    public function __construct(
        private InventoryService $inventoryService,
        private InventoryRepository $inventoryRepository
    ) {
    }

    public function index(Property $property)
    {
        $items = $this->inventoryRepository->getByProperty($property->id);
        $lowStockItems = $this->inventoryService->getLowStockItems($property->id);
        
        return view('inventory.index', compact('property', 'items', 'lowStockItems'));
    }

    public function create(Property $property)
    {
        return view('inventory.create', compact('property'));
    }

    public function store(Request $request, Property $property)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'sku' => 'required|string|unique:inventory_items',
            'type' => 'required|in:ingredient,beverage,supply',
            'unit' => 'required|string',
            'quantity' => 'required|numeric|min:0',
            'reorder_level' => 'required|numeric|min:0',
            'unit_cost' => 'required|numeric|min:0',
            'supplier' => 'nullable|string',
        ]);

        $validated['property_id'] = $property->id;
        InventoryItem::create($validated);

        return redirect()->route('properties.inventory.index', $property)
            ->with('success', 'Inventory item created successfully');
    }

    public function edit(Property $property, InventoryItem $item)
    {
        return view('inventory.edit', compact('property', 'item'));
    }

    public function update(Request $request, Property $property, InventoryItem $item)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'unit' => 'required|string',
            'reorder_level' => 'required|numeric|min:0',
            'unit_cost' => 'required|numeric|min:0',
            'supplier' => 'nullable|string',
        ]);

        $item->update($validated);

        return redirect()->route('properties.inventory.index', $property)
            ->with('success', 'Inventory item updated successfully');
    }

    public function recordUsage(Request $request, Property $property, InventoryItem $item)
    {
        $validated = $request->validate([
            'quantity' => 'required|numeric|min:0',
            'notes' => 'nullable|string',
        ]);

        $this->inventoryService->recordUsage(
            $item,
            $validated['quantity'],
            auth()->id(),
            null,
            $validated['notes'] ?? null
        );

        return redirect()->back()->with('success', 'Usage recorded');
    }

    public function destroy(Property $property, InventoryItem $item)
    {
        $item->update(['is_active' => false]);
        return redirect()->route('properties.inventory.index', $property)
            ->with('success', 'Inventory item deactivated');
    }

    public function bulkDelete(Request $request, Property $property)
    {
        $validated = $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'required|integer|exists:inventory_items,id',
        ]);

        try {
            // Get all items to delete
            $items = InventoryItem::where('property_id', $property->id)
                ->whereIn('id', $validated['ids'])
                ->get();

            $deletedCount = 0;

            foreach ($items as $item) {
                $item->update(['is_active' => false]);
                $deletedCount++;
            }

            return response()->json([
                'success' => true,
                'message' => "$deletedCount inventory item(s) deactivated successfully"
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error deleting items: ' . $e->getMessage()
            ], 500);
        }
    }

    public function recordAdjustment(Request $request, Property $property, InventoryItem $item)
    {
        $validated = $request->validate([
            'quantity' => 'required|numeric|min:0',
            'notes' => 'nullable|string',
        ]);

        // Check if this is a top-up request - either from AJAX or explicitly set
        $isTopUp = $request->expectsJson() || $request->has('top_up') || $request->input('notes') === 'Top up inventory';

        $this->inventoryService->recordAdjustment(
            $item,
            $validated['quantity'],
            auth()->id(),
            $validated['notes'] ?? null,
            $isTopUp
        );

        if ($request->expectsJson()) {
            return response()->json(['success' => true, 'message' => 'Inventory topped up successfully', 'new_quantity' => $item->fresh()->quantity]);
        }

        return redirect()->back()->with('success', 'Inventory adjusted');
    }

    public function logs(Property $property, InventoryItem $item)
    {
        $logs = $item->logs()->with('user', 'order')->paginate(15);
        return view('inventory.logs', compact('property', 'item', 'logs'));
    }
}

