<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RoomInventory extends Model
{
    protected $table = 'room_inventory';
    protected $fillable = ['room_id', 'date', 'status', 'price'];

    protected $casts = [
        'date' => 'date',
        'price' => 'decimal:2',
    ];

    public function room(): BelongsTo
    {
        return $this->belongsTo(Room::class);
    }
}

