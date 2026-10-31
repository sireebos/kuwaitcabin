<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RatePlan extends Model
{
    protected $fillable = [
        'property_id', 'room_type_id', 'name', 'description', 'base_rate',
        'discount_percentage', 'valid_from', 'valid_to', 'is_active'
    ];

    protected $casts = [
        'base_rate' => 'decimal:2',
        'discount_percentage' => 'decimal:2',
        'valid_from' => 'date',
        'valid_to' => 'date',
        'is_active' => 'boolean',
    ];

    public function property(): BelongsTo
    {
        return $this->belongsTo(Property::class);
    }

    public function roomType(): BelongsTo
    {
        return $this->belongsTo(RoomType::class);
    }

    public function getEffectiveRate(): float
    {
        $rate = $this->base_rate;
        if ($this->discount_percentage > 0) {
            $rate = $rate - ($rate * $this->discount_percentage / 100);
        }
        return $rate;
    }
}

