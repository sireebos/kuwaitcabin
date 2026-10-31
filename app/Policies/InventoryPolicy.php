<?php

namespace App\Policies;

use App\Models\InventoryItem;
use App\Models\User;

class InventoryPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasPermission('view_inventory') || $user->isAdmin();
    }

    public function view(User $user, InventoryItem $item): bool
    {
        return $user->property_id === $item->property_id && 
               ($user->hasPermission('view_inventory') || $user->isAdmin());
    }

    public function create(User $user): bool
    {
        return $user->hasPermission('manage_inventory') || $user->isAdmin();
    }

    public function update(User $user, InventoryItem $item): bool
    {
        return $user->property_id === $item->property_id && 
               ($user->hasPermission('manage_inventory') || $user->isAdmin());
    }

    public function delete(User $user, InventoryItem $item): bool
    {
        return $user->property_id === $item->property_id && 
               ($user->hasPermission('manage_inventory') || $user->isAdmin());
    }
}

