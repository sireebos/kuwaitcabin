<?php

namespace App\Models;
 
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Property extends Model
{
    protected $fillable = [
        'name', 'slug', 'description', 'address', 'city', 'state', 'postal_code',
        'country', 'phone', 'email', 'website', 'total_rooms', 'check_in_time',
        'check_out_time', 'currency', 'is_active', 'display_name', 'logo_path', 'light_logo_path', 'favicon_path', 'background_path', 'tax_rate',
        'home_title', 'home_description', 'home_image_path', 'home_image_link', 'features_enabled',
        // Email settings
        'smtp_host', 'smtp_port', 'smtp_username', 'smtp_password', 'smtp_encryption',
        'mail_from_address', 'mail_from_name', 'email_notifications_enabled'
    ];
 
    protected $casts = [
        'is_active' => 'boolean',
        'check_in_time' => 'float',
        'check_out_time' => 'float',
        'email_notifications_enabled' => 'boolean',
        'tax_rate' => 'decimal:2',
    ];

    public function rooms(): HasMany
    {
        return $this->hasMany(Room::class);
    }

    public function roomTypes(): HasMany
    {
        return $this->hasMany(RoomType::class);
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }

    public function bookingHistory(): HasMany
    {
        return $this->hasMany(BookingHistory::class);
    }

    public function guests(): HasMany
    {
        return $this->hasMany(Guest::class);
    }

    public function menus(): HasMany
    {
        return $this->hasMany(Menu::class);
    }

    public function restaurantTables(): HasMany
    {
        return $this->hasMany(RestaurantTable::class);
    }

    public function restaurantReservations(): HasMany
    {
        return $this->hasMany(RestaurantReservation::class);
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function inventoryItems(): HasMany
    {
        return $this->hasMany(InventoryItem::class);
    }

    public function inventoryTypes(): HasMany
    {
        return $this->hasMany(InventoryType::class);
    }

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function ratePlans(): HasMany
    {
        return $this->hasMany(RatePlan::class);
    }

    public function customFields(): HasMany
    {
        return $this->hasMany(CustomField::class);
    }

    public function promotions(): HasMany
    {
        return $this->hasMany(Promotion::class);
    }

    public function stockSessions(): HasMany
    {
        return $this->hasMany(StockSession::class);
    }
}
