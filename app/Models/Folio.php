<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Folio extends Model
{
    protected $fillable = [
        'booking_id', 'room_charges', 'restaurant_charges', 'other_charges',
        'total_charges', 'payments_received', 'balance'
    ];

    protected $casts = [
        'room_charges' => 'decimal:2',
        'restaurant_charges' => 'decimal:2',
        'other_charges' => 'decimal:2',
        'total_charges' => 'decimal:2',
        'payments_received' => 'decimal:2',
        'balance' => 'decimal:2',
    ];

    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(FolioItem::class);
    }

    public function updateBalance(): void
    {
        $this->balance = $this->total_charges - $this->payments_received;
        $this->save();
    }
}

