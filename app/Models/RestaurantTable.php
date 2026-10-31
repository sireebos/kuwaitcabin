<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class RestaurantTable extends Model
{
    protected $table = 'restaurant_tables';
    protected $fillable = ['property_id', 'table_number', 'capacity', 'status', 'section', 'notes'];

    public function property(): BelongsTo
    {
        return $this->belongsTo(Property::class);
    }

    public function reservations(): HasMany
    {
        return $this->hasMany(RestaurantReservation::class);
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }
}

