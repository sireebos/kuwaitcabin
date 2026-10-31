<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Guest extends Model
{
    protected $fillable = [
        'property_id', 'first_name', 'last_name', 'email', 'phone', 'id_type',
        'id_number', 'nationality', 'address', 'city', 'state', 'postal_code',
        'country', 'notes'
    ];

    public function property(): BelongsTo
    {
        return $this->belongsTo(Property::class);
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }

    public function customFieldValues(): MorphMany
    {
        return $this->morphMany(CustomFieldValue::class, 'fieldable');
    }

    public function getFullNameAttribute(): string
    {
        return "{$this->first_name} {$this->last_name}";
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

