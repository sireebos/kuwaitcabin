<?php

namespace App\Policies;

use App\Models\Order;
use App\Models\User;

class OrderPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasPermission('view_orders') || $user->isAdmin();
    }

    public function view(User $user, Order $order): bool
    {
        return $user->property_id === $order->property_id && 
               ($user->hasPermission('view_orders') || $user->isAdmin());
    }

    public function create(User $user): bool
    {
        return $user->hasPermission('create_orders') || $user->isAdmin();
    }

    public function update(User $user, Order $order): bool
    {
        return $user->property_id === $order->property_id && 
               ($user->hasPermission('manage_orders') || $user->isAdmin());
    }

    public function delete(User $user, Order $order): bool
    {
        return $user->property_id === $order->property_id && 
               ($user->hasPermission('manage_orders') || $user->isAdmin());
    }

    public function updateStatus(User $user, Order $order): bool
    {
        return $user->property_id === $order->property_id && 
               ($user->hasPermission('update_order_status') || $user->isAdmin());
    }
}

