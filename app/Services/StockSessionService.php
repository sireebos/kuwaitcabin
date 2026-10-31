<?php

namespace App\Services;

use App\Models\StockSession;
use App\Models\StockSessionItem;
use App\Models\InventoryItem;
use App\Models\Property;
use Carbon\Carbon;
use DB;

class StockSessionService
{
    /**
     * Open a new stock session
     */
    public function openSession(Property $property, array $data): StockSession
    {
        return DB::transaction(function () use ($property, $data) {
            $sessionDate = $data['session_date'] ?? Carbon::now()->toDateString();
            $userId = auth()->id();

            // Check if session already exists for today
            $existingSession = StockSession::where('property_id', $property->id)
                ->where('session_date', $sessionDate)
                ->where('user_id', $userId)
                ->first();

            if ($existingSession && $existingSession->isOpen()) {
                return $existingSession;
            }

            // Create new session
            $session = StockSession::create([
                'property_id' => $property->id,
                'user_id' => $userId,
                'session_date' => $sessionDate,
                'status' => 'open',
                'notes' => $data['notes'] ?? null,
            ]);

            // Add inventory items to session
            $this->addItemsToSession($session, $data['items'] ?? []);

            return $session;
        });
    }

    /**
     * Add items to a stock session
     */
    public function addItemsToSession(StockSession $session, array $itemsData): void
    {
        DB::transaction(function () use ($session, $itemsData) {
            foreach ($itemsData as $itemId => $data) {
                $inventoryItem = InventoryItem::find($itemId);
                if (!$inventoryItem) continue;

                $openingQuantity = (float)($data['opening_quantity'] ?? 0);

                // Try to load from previous session if not provided
                if ($openingQuantity === 0 && isset($data['use_previous_balance'])) {
                    $openingQuantity = $this->getPreviousClosingQuantity($session->property_id, $itemId);
                }

                StockSessionItem::create([
                    'stock_session_id' => $session->id,
                    'inventory_item_id' => $itemId,
                    'opening_quantity' => $openingQuantity,
                    'unit_cost' => $inventoryItem->unit_cost,
                    'opening_value' => $openingQuantity * $inventoryItem->unit_cost,
                ]);
            }
        });
    }

    /**
     * Record usage from orders
     */
    public function recordUsage(StockSession $session, InventoryItem $item, float $quantity, ?string $reason = null): void
    {
        DB::transaction(function () use ($session, $item, $quantity, $reason) {
            $sessionItem = StockSessionItem::where('stock_session_id', $session->id)
                ->where('inventory_item_id', $item->id)
                ->first();

            if ($sessionItem) {
                $sessionItem->increment('quantity_used', $quantity);
                $sessionItem->save();
            }
        });
    }

    /**
     * Record manual adjustment
     */
    public function recordAdjustment(StockSession $session, InventoryItem $item, float $quantity, string $reason): void
    {
        DB::transaction(function () use ($session, $item, $quantity, $reason) {
            $sessionItem = StockSessionItem::where('stock_session_id', $session->id)
                ->where('inventory_item_id', $item->id)
                ->first();

            if ($sessionItem) {
                $sessionItem->update([
                    'manual_adjustment' => $sessionItem->manual_adjustment + $quantity,
                    'adjustment_reason' => $reason,
                ]);
            }
        });
    }

    /**
     * Close a stock session
     */
    public function closeSession(StockSession $session, array $closingData): StockSession
    {
        return DB::transaction(function () use ($session, $closingData) {
            foreach ($closingData as $itemId => $closingQuantity) {
                $sessionItem = StockSessionItem::where('stock_session_id', $session->id)
                    ->where('inventory_item_id', $itemId)
                    ->first();

                if ($sessionItem) {
                    $expectedClosing = $sessionItem->opening_quantity 
                        + $sessionItem->quantity_received 
                        - $sessionItem->quantity_used 
                        + $sessionItem->manual_adjustment;

                    $variance = (float)$closingQuantity - $expectedClosing;

                    $sessionItem->update([
                        'closing_quantity' => $closingQuantity,
                        'expected_closing' => $expectedClosing,
                        'variance' => $variance,
                        'closing_value' => $closingQuantity * $sessionItem->unit_cost,
                    ]);
                }
            }

            // Calculate session totals
            $items = $session->items;
            $totalUsage = $items->sum('quantity_used');
            $totalVariance = $items->sum('variance');
            $closingBalance = $items->sum('closing_value');

            $session->update([
                'status' => 'closed',
                'closing_balance' => $closingBalance,
                'total_usage' => $totalUsage,
                'variance' => $totalVariance,
                'variance_percentage' => $closingBalance > 0 ? ($totalVariance / $closingBalance) * 100 : 0,
            ]);

            return $session->refresh();
        });
    }

    /**
     * Get previous closing quantity for an item
     */
    public function getPreviousClosingQuantity(int $propertyId, int $itemId): float
    {
        $previousSession = StockSession::where('property_id', $propertyId)
            ->where('status', 'closed')
            ->orderByDesc('session_date')
            ->first();

        if (!$previousSession) return 0;

        $previousItem = StockSessionItem::where('stock_session_id', $previousSession->id)
            ->where('inventory_item_id', $itemId)
            ->first();

        return $previousItem?->closing_quantity ?? 0;
    }

    /**
     * Get session report
     */
    public function getSessionReport(StockSession $session): array
    {
        $items = $session->items()->with('inventoryItem')->get();

        return [
            'session' => $session,
            'items' => $items,
            'summary' => [
                'total_opening_value' => $items->sum('opening_value'),
                'total_closing_value' => $items->sum('closing_value'),
                'total_usage_value' => $items->sum(function ($item) {
                    return $item->quantity_used * $item->unit_cost;
                }),
                'total_variance_value' => $items->sum(function ($item) {
                    return $item->variance * $item->unit_cost;
                }),
                'total_items_count' => $items->count(),
            ],
        ];
    }
}
