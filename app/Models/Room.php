<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Room extends Model
{
    protected $fillable = [
        'property_id', 'room_type_id', 'room_number', 'status', 'floor', 'wing', 'notes', 'price'
    ];

    protected $casts = [
        'floor' => 'integer',
        'price' => 'decimal:2',
    ];

    public function property(): BelongsTo
    {
        return $this->belongsTo(Property::class);
    }

    public function roomType(): BelongsTo
    {
        return $this->belongsTo(RoomType::class);
    }

    public function inventory(): HasMany
    {
        return $this->hasMany(RoomInventory::class);
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }

    /**
     * Check if room is available for given date range
     * A room is unavailable if there are existing active bookings that overlap with the requested dates
     */
    public function isAvailable($checkInDate, $checkOutDate): bool
    {
        $checkIn = \Carbon\Carbon::parse($checkInDate)->startOfDay();
        $checkOut = \Carbon\Carbon::parse($checkOutDate)->startOfDay();
        
        // Check for active bookings that overlap with the requested dates
        // Active bookings are those NOT in checked_out, cancelled, or no_show status
        $hasBookingConflict = $this->bookings()
            ->whereNotIn('status', ['checked_out', 'cancelled', 'no_show'])
            ->where(function ($query) use ($checkIn, $checkOut) {
                // Booking overlaps if:
                // (booking starts before requested checkout AND booking ends after requested checkin)
                $query->where('check_in_date', '<', $checkOut->format('Y-m-d'))
                      ->where('check_out_date', '>', $checkIn->format('Y-m-d'));
            })
            ->exists();
            
        return !$hasBookingConflict;
    }

    /**
     * Get the effective price for this room
     * Returns room-specific price if set, otherwise returns room type base price
     */
    public function getEffectivePrice(): float
    {
        return $this->price ?? $this->roomType->base_price ?? 0;
    }
}

