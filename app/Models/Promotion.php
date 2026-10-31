<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Promotion extends Model
{
    protected $fillable = [
        'property_id', 'code', 'name', 'description', 'type', 'value',
        'min_order_amount', 'max_uses', 'used_count', 'valid_from', 'valid_to', 'is_active'
    ];

    protected $casts = [
        'value' => 'decimal:2',
        'min_order_amount' => 'decimal:2',
        'valid_from' => 'date',
        'valid_to' => 'date',
        'is_active' => 'boolean',
    ];

    public function property(): BelongsTo
    {
        return $this->belongsTo(Property::class);
    }

    public function isValid(): bool
    {
        if (!$this->is_active) {
            return false;
        }

        if ($this->max_uses && $this->used_count >= $this->max_uses) {
            return false;
        }

        $today = now()->toDateString();
        if ($this->valid_from && $today < $this->valid_from->toDateString()) {
            return false;
        }

        if ($this->valid_to && $today > $this->valid_to->toDateString()) {
            return false;
        }

        return true;
    }

    public function calculateDiscount(float $amount): float
    {
        if ($this->type === 'percentage') {
            return ($amount * $this->value) / 100;
        }
        return min($this->value, $amount);
    }
}
