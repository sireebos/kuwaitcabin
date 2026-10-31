<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderItem extends Model
{
    protected $fillable = [
        'order_id', 'menu_item_id', 'inventory_item_id', 'quantity', 'unit_price', 'modifiers',
        'special_instructions', 'status'
    ];

    protected $casts = [
        'unit_price' => 'decimal:2',
        'modifiers' => 'array',
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function menuItem(): BelongsTo
    {
        return $this->belongsTo(MenuItem::class);
    }

    public function inventoryItem(): BelongsTo
    {
        return $this->belongsTo(InventoryItem::class);
    }

    public function getLineTotal(): float
    {
        return $this->quantity * $this->unit_price;
    }
}

