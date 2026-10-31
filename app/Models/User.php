<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'property_id', 'name', 'email', 'password', 'role_id', 'status', 'phone'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function property(): BelongsTo
    {
        return $this->belongsTo(Property::class);
    }

    /**
     * Get the primary role (for backward compatibility)
     */
    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }

    /**
     * Get all roles for the user (many-to-many)
     */
    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class, 'role_user')->withTimestamps();
    }

    /**
     * Sync roles for the user
     */
    public function syncRoles(array $roleIds): void
    {
        $this->roles()->sync($roleIds);
        
        // Also set the primary role_id to the first role for backward compatibility
        if (!empty($roleIds)) {
            $this->update(['role_id' => $roleIds[0]]);
        }
    }

    /**
     * Check if user has a specific role
     */
    public function hasRole(string $roleName): bool
    {
        // First check primary role (role_id) for backward compatibility
        if ($this->role && $this->role->name === $roleName) {
            return true;
        }
        
        // Then check many-to-many roles if the table exists
        try {
            return $this->roles()->where('name', $roleName)->exists();
        } catch (\Exception $e) {
            // role_user table doesn't exist, use only primary role
            return false;
        }
    }

    /**
     * Get all role names
     */
    public function getRoleNames(): array
    {
        try {
            $roleNames = $this->roles()->pluck('name')->toArray();
            // Include primary role if not already in the list
            if ($this->role && !in_array($this->role->name, $roleNames)) {
                $roleNames[] = $this->role->name;
            }
            return $roleNames;
        } catch (\Exception $e) {
            // role_user table doesn't exist, use only primary role
            return $this->role ? [$this->role->name] : [];
        }
    }

    public function auditLogs(): HasMany
    {
        return $this->hasMany(AuditLog::class);
    }

    public function inventoryLogs(): HasMany
    {
        return $this->hasMany(InventoryLog::class);
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }

    public function hasPermission(string $permission): bool
    {
        // First check primary role for backward compatibility
        if ($this->role?->hasPermission($permission)) {
            return true;
        }
        
        // Then check permissions across all roles if pivot table exists
        try {
            foreach ($this->roles as $role) {
                if ($role->hasPermission($permission)) {
                    return true;
                }
            }
        } catch (\Exception $e) {
            // role_user table doesn't exist, already checked primary role
        }
        
        return false;
    }

    public function isAdmin(): bool
    {
        return $this->hasRole('admin') || $this->role?->name === 'admin';
    }
}
