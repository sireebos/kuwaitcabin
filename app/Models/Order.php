<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    protected $fillable = [
        'property_id', 'user_id', 'restaurant_table_id', 'booking_id', 'order_number', 'type',
        'order_type', 'status', 'subtotal', 'discount', 'tax', 'total', 'charged_to_room', 'notes',
        'payment_method', 'paid_at', 'customer_name'
    ];

    protected $casts = [
        'subtotal' => 'decimal:2',
        'discount' => 'decimal:2',
        'tax' => 'decimal:2',
        'total' => 'decimal:2',
        'charged_to_room' => 'boolean',
        'paid_at' => 'datetime',
    ];

    public function property(): BelongsTo
    {
        return $this->belongsTo(Property::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function table(): BelongsTo
    {
        return $this->belongsTo(RestaurantTable::class, 'restaurant_table_id');
    }

    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function inventoryLogs(): HasMany
    {
        return $this->hasMany(InventoryLog::class);
    }

    public function billSplits(): HasMany
    {
        return $this->hasMany(BillSplit::class);
    }

    public function calculateTotal(): void
    {
        $this->subtotal = $this->items()->sum('quantity' * 'unit_price');
        $this->tax = $this->subtotal * 0.1;
        $this->total = $this->subtotal - $this->discount + $this->tax;
        $this->save();
    }
}

