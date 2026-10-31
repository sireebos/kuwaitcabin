<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StockSessionItem extends Model
{
    protected $fillable = [
        'stock_session_id',
        'inventory_item_id',
        'opening_quantity',
        'quantity_received',
        'quantity_used',
        'manual_adjustment',
        'adjustment_reason',
        'closing_quantity',
        'expected_closing',
        'variance',
        'unit_cost',
        'opening_value',
        'closing_value',
    ];

    protected $casts = [
        'opening_quantity' => 'decimal:3',
        'quantity_received' => 'decimal:3',
        'quantity_used' => 'decimal:3',
        'manual_adjustment' => 'decimal:3',
        'closing_quantity' => 'decimal:3',
        'expected_closing' => 'decimal:3',
        'variance' => 'decimal:3',
        'unit_cost' => 'decimal:2',
        'opening_value' => 'decimal:2',
        'closing_value' => 'decimal:2',
    ];

    public function stockSession(): BelongsTo
    {
        return $this->belongsTo(StockSession::class);
    }

    public function inventoryItem(): BelongsTo
    {
        return $this->belongsTo(InventoryItem::class);
    }
}
