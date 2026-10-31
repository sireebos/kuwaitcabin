<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RestaurantReservation extends Model
{
    protected $table = 'restaurant_reservations';
    protected $fillable = [
        'property_id', 'restaurant_table_id', 'guest_id', 'guest_name', 'guest_phone',
        'reservation_time', 'number_of_guests', 'status', 'special_requests'
    ];

    protected $casts = [
        'reservation_time' => 'datetime',
    ];

    public function property(): BelongsTo
    {
        return $this->belongsTo(Property::class);
    }

    public function table(): BelongsTo
    {
        return $this->belongsTo(RestaurantTable::class, 'restaurant_table_id');
    }

    public function guest(): BelongsTo
    {
        return $this->belongsTo(Guest::class);
    }
}

