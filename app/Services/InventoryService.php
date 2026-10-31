<?php

namespace App\Services;

use App\Models\InventoryItem;
use App\Models\InventoryLog;
use App\Repositories\InventoryRepository;
use App\Notifications\LowStockAlert;
use DB;
use Notification;

class InventoryService
{
    public function __construct(private InventoryRepository $repository)
    {
    }

    public function recordUsage(InventoryItem $item, float $quantity, $userId, $orderId = null, $notes = null): InventoryLog
    {
        return DB::transaction(function () use ($item, $quantity, $userId, $orderId, $notes) {
            $item->decrement('quantity', $quantity);

            $log = InventoryLog::create([
                'inventory_item_id' => $item->id,
                'user_id' => $userId,
                'type' => 'usage',
                'quantity' => $quantity,
                'unit_cost' => $item->unit_cost,
                'notes' => $notes,
                'order_id' => $orderId
            ]);

            // Check if stock is now low and send notification
            $this->checkAndNotifyLowStock($item);

            return $log;
        });
    }

    public function recordPurchase(InventoryItem $item, float $quantity, float $unitCost, $userId, $notes = null): InventoryLog
    {
        return DB::transaction(function () use ($item, $quantity, $unitCost, $userId, $notes) {
            $item->increment('quantity', $quantity);
            $item->update(['unit_cost' => $unitCost]);
            
            return InventoryLog::create([
                'inventory_item_id' => $item->id,
                'user_id' => $userId,
                'type' => 'purchase',
                'quantity' => $quantity,
                'unit_cost' => $unitCost,
                'notes' => $notes
            ]);
        });
    }

    public function recordAdjustment(InventoryItem $item, float $quantity, $userId, $notes = null, $isTopUp = false): InventoryLog
    {
        return DB::transaction(function () use ($item, $quantity, $userId, $notes, $isTopUp) {
            // If it's a top-up, increment the quantity; otherwise, set it to the exact quantity
            if ($isTopUp) {
                $item->increment('quantity', $quantity);
            } else {
                $item->update(['quantity' => $quantity]);
            }

            return InventoryLog::create([
                'inventory_item_id' => $item->id,
                'user_id' => $userId,
                'type' => 'adjustment',
                'quantity' => $quantity,
                'unit_cost' => $item->unit_cost,
                'notes' => $notes
            ]);
        });
    }

    public function getLowStockItems($propertyId)
    {
        return $this->repository->getLowStockItems($propertyId);
    }

    public function getInventoryValue($propertyId): float
    {
        return $this->repository->getByProperty($propertyId)
            ->sum(function ($item) {
                return $item->quantity * $item->unit_cost;
            });
    }

    public function checkAndNotifyLowStock(InventoryItem $item): void
    {
        if ($item->isLowStock()) {
            $property = $item->property;
            $admins = $property->users()->whereHas('role', function ($query) {
                $query->where('name', 'admin');
            })->get();

            if ($admins->isNotEmpty()) {
                Notification::send($admins, new LowStockAlert($item));
            }
        }
    }
}

