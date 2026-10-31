<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class InventoryItem extends Model
{
    protected $fillable = [
        'property_id', 'name', 'sku', 'type', 'unit', 'quantity', 'reorder_level',
        'unit_cost', 'supplier', 'is_active'
    ];

    protected $casts = [
        'quantity' => 'decimal:2',
        'reorder_level' => 'decimal:2',
        'unit_cost' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    public function property(): BelongsTo
    {
        return $this->belongsTo(Property::class);
    }

    public function logs(): HasMany
    {
        return $this->hasMany(InventoryLog::class);
    }

    public function customFieldValues(): MorphMany
    {
        return $this->morphMany(CustomFieldValue::class, 'fieldable');
    }

    public function isLowStock(): bool
    {
        return $this->quantity <= $this->reorder_level;
    }

    public function getCustomFieldValue($fieldName)
    {
        return $this->customFieldValues()
            ->whereHas('customField', function ($query) use ($fieldName) {
                $query->where('name', $fieldName);
            })
            ->first()?->value;
    }
}

