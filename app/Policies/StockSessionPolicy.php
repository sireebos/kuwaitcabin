<?php

namespace App\Policies;

use App\Models\StockSession;
use App\Models\User;

class StockSessionPolicy
{
    /**
     * Determine whether the user can view any stock sessions
     */
    public function viewAny(User $user): bool
    {
        return $user->isAdmin() || $user->hasPermission('create_bar_orders') || $user->hasPermission('manage_bar_orders');
    }

    /**
     * Determine whether the user can view the stock session
     */
    public function view(User $user, StockSession $session): bool
    {
        return $user->isAdmin() || $user->id === $session->user_id;
    }

    /**
     * Determine whether the user can create stock sessions
     */
    public function create(User $user): bool
    {
        return $user->isAdmin() || $user->hasPermission('create_bar_orders') || $user->hasPermission('manage_bar_orders');
    }

    /**
     * Determine whether the user can update the stock session
     */
    public function update(User $user, StockSession $session): bool
    {
        return $user->isAdmin() || ($user->id === $session->user_id && $session->isOpen());
    }

    /**
     * Determine whether the user can delete the stock session
     */
    public function delete(User $user, StockSession $session): bool
    {
        return $user->isAdmin() && $session->isOpen();
    }
}
