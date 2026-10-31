<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BookingHistory extends Model
{
    protected $table = 'booking_history';

    protected $fillable = [
        'booking_id', 'property_id', 'guest_id', 'room_id', 'booking_reference',
        'check_in_date', 'check_out_date', 'number_of_guests', 'number_of_nights',
        'room_rate', 'subtotal', 'tax', 'total_amount', 'final_amount',
        'payments_received', 'balance', 'currency', 'special_requests', 'checked_out_at'
    ];

    protected $casts = [
        'check_in_date' => 'date',
        'check_out_date' => 'date',
        'room_rate' => 'decimal:2',
        'subtotal' => 'decimal:2',
        'tax' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'final_amount' => 'decimal:2',
        'payments_received' => 'decimal:2',
        'balance' => 'decimal:2',
        'checked_out_at' => 'datetime',
    ];

    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class);
    }

    public function property(): BelongsTo
    {
        return $this->belongsTo(Property::class);
    }

    public function guest(): BelongsTo
    {
        return $this->belongsTo(Guest::class);
    }

    public function room(): BelongsTo
    {
        return $this->belongsTo(Room::class);
    }
}

