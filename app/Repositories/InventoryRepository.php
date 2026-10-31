<?php

namespace App\Repositories;

use App\Models\InventoryItem;

class InventoryRepository extends BaseRepository
{
    public function getModel(): string
    {
        return InventoryItem::class;
    }

    public function getByProperty($propertyId)
    {
        return $this->model
            ->where('property_id', $propertyId)
            ->where('is_active', true)
            ->get();
    }

    public function getLowStockItems($propertyId)
    {
        return $this->model
            ->where('property_id', $propertyId)
            ->where('is_active', true)
            ->whereRaw('quantity <= reorder_level')
            ->get();
    }

    public function getByType($propertyId, $type)
    {
        return $this->model
            ->where('property_id', $propertyId)
            ->where('type', $type)
            ->where('is_active', true)
            ->get();
    }

    public function getBySku($sku)
    {
        return $this->findBy('sku', $sku);
    }

    public function updateQuantity($id, $quantity): bool
    {
        return $this->update($id, ['quantity' => $quantity]);
    }
}

