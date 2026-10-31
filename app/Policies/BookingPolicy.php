<?php

namespace App\Policies;

use App\Models\Booking;
use App\Models\User;

class BookingPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasPermission('view_bookings') || $user->isAdmin();
    }

    public function view(User $user, Booking $booking): bool
    {
        return $user->property_id === $booking->property_id && 
               ($user->hasPermission('view_bookings') || $user->isAdmin());
    }

    public function create(User $user): bool
    {
        return $user->hasPermission('manage_bookings') || $user->isAdmin();
    }

    public function update(User $user, Booking $booking): bool
    {
        return $user->property_id === $booking->property_id && 
               ($user->hasPermission('manage_bookings') || $user->isAdmin());
    }

    public function delete(User $user, Booking $booking): bool
    {
        return $user->property_id === $booking->property_id && 
               ($user->hasPermission('manage_bookings') || $user->isAdmin());
    }

    public function confirm(User $user, Booking $booking): bool
    {
        return $user->property_id === $booking->property_id && 
               ($user->hasPermission('manage_bookings') || $user->isAdmin());
    }

    public function checkin(User $user, Booking $booking): bool
    {
        return $user->property_id === $booking->property_id && 
               ($user->hasPermission('checkin_checkout') || $user->isAdmin());
    }

    public function checkout(User $user, Booking $booking): bool
    {
        return $user->property_id === $booking->property_id && 
               ($user->hasPermission('checkin_checkout') || $user->isAdmin());
    }
}

