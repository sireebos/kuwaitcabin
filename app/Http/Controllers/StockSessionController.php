<?php

namespace App\Http\Controllers;

use App\Models\Property;
use App\Models\StockSession;
use App\Models\InventoryItem;
use App\Services\StockSessionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Carbon\Carbon;

class StockSessionController extends Controller
{
    public function __construct(private StockSessionService $service)
    {
    }

    /**
     * Display list of stock sessions
     */
    public function index(Property $property)
    {
        Gate::authorize('viewAny', StockSession::class);

        $sessions = StockSession::where('property_id', $property->id)
            ->with(['user'])
            ->orderByDesc('session_date')
            ->orderByDesc('created_at')
            ->paginate(15);

        return view('stock-sessions.index', [
            'property' => $property,
            'sessions' => $sessions,
        ]);
    }

    /**
     * Show form to create new session
     */
    public function create(Property $property)
    {
        Gate::authorize('create', StockSession::class);

        // Check for existing open session today
        $existingSession = StockSession::where('property_id', $property->id)
            ->where('session_date', Carbon::now()->toDateString())
            ->where('user_id', auth()->id())
            ->where('status', 'open')
            ->first();

        if ($existingSession) {
            return redirect()->route('properties.stock-sessions.show', [$property, $existingSession]);
        }

        $items = InventoryItem::where('property_id', $property->id)
            ->orderBy('name')
            ->get();

        $previousSession = StockSession::where('property_id', $property->id)
            ->where('status', 'closed')
            ->orderByDesc('session_date')
            ->first();

        return view('stock-sessions.create', [
            'property' => $property,
            'items' => $items,
            'previousSession' => $previousSession,
        ]);
    }

    /**
     * Store new session
     */
    public function store(Request $request, Property $property)
    {
        Gate::authorize('create', StockSession::class);

        $validated = $request->validate([
            'session_date' => 'required|date',
            'notes' => 'nullable|string|max:500',
            'items' => 'required|array',
            'items.*.opening_quantity' => 'required|numeric|min:0',
            'items.*.include' => 'nullable|boolean',
        ]);

        try {
            $itemsData = [];
            foreach ($request->input('items', []) as $itemId => $data) {
                // Only include items that are checked
                if (!isset($data['include']) || $data['include'] != 1) {
                    continue;
                }

                $itemsData[$itemId] = [
                    'opening_quantity' => (float)$data['opening_quantity'],
                ];
            }

            if (empty($itemsData)) {
                return back()->withInput()->withErrors(['items' => 'Please select at least one item to track']);
            }

            $validated['items'] = $itemsData;

            $session = $this->service->openSession($property, $validated);

            return redirect()->route('properties.stock-sessions.show', [$property, $session])
                ->with('success', 'Stock session opened successfully');
        } catch (\Exception $e) {
            return back()->withInput()->withErrors(['error' => $e->getMessage()]);
        }
    }

    /**
     * Show active session
     */
    public function show(Property $property, StockSession $session)
    {
        Gate::authorize('view', $session);

        $session->load(['items.inventoryItem', 'user']);

        return view('stock-sessions.show', [
            'property' => $property,
            'session' => $session,
        ]);
    }

    /**
     * Show close session form
     */
    public function edit(Property $property, StockSession $session)
    {
        Gate::authorize('update', $session);

        if ($session->isClosed()) {
            return redirect()->route('properties.stock-sessions.show', [$property, $session])
                ->withErrors(['error' => 'Cannot edit a closed session']);
        }

        $session->load('items.inventoryItem');

        return view('stock-sessions.close', [
            'property' => $property,
            'session' => $session,
        ]);
    }

    /**
     * Close stock session
     */
    public function update(Request $request, Property $property, StockSession $session)
    {
        Gate::authorize('update', $session);

        if ($session->isClosed()) {
            return back()->withErrors(['error' => 'Session is already closed']);
        }

        $validated = $request->validate([
            'closing_quantity.*' => 'required|numeric|min:0',
        ]);

        try {
            $closingData = [];
            foreach ($session->items as $item) {
                $closingData[$item->inventory_item_id] = (float)$request->input("closing_quantity.{$item->id}");
            }

            $this->service->closeSession($session, $closingData);

            return redirect()->route('properties.stock-sessions.show', [$property, $session])
                ->with('success', 'Stock session closed successfully');
        } catch (\Exception $e) {
            return back()->withInput()->withErrors(['error' => $e->getMessage()]);
        }
    }

    /**
     * Generate session report
     */
    public function report(Property $property, StockSession $session)
    {
        Gate::authorize('view', $session);

        $reportData = $this->service->getSessionReport($session);

        return view('stock-sessions.report', [
            'property' => $property,
            'session' => $session,
            ...$reportData,
        ]);
    }

    /**
     * Record usage adjustment
     */
    public function recordAdjustment(Request $request, Property $property, StockSession $session)
    {
        Gate::authorize('update', $session);

        if ($session->isClosed()) {
            return response()->json(['error' => 'Cannot adjust closed session'], 400);
        }

        $validated = $request->validate([
            'inventory_item_id' => 'required|exists:inventory_items,id',
            'quantity' => 'required|numeric',
            'reason' => 'required|string|max:255',
        ]);

        try {
            $item = InventoryItem::findOrFail($validated['inventory_item_id']);
            $this->service->recordAdjustment($session, $item, $validated['quantity'], $validated['reason']);

            return response()->json(['success' => true, 'message' => 'Adjustment recorded']);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
