<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        Role::create([
            'name' => 'admin',
            'display_name' => 'Administrator',
            'description' => 'Full system access',
            'permissions' => [
                'view_dashboard',
                'manage_bookings',
                'manage_rooms',
                'manage_menus',
                'manage_orders',
                'manage_inventory',
                'manage_users',
                'view_reports',
                'manage_settings',
            ],
        ]);

        Role::create([
            'name' => 'manager',
            'display_name' => 'Manager',
            'description' => 'Can manage bookings, orders, bar orders, and inventory',
            'permissions' => [
                'view_dashboard',
                'manage_bookings',
                'manage_orders',
                'manage_bar_orders',
                'create_bar_orders',
                'manage_inventory',
                'view_reports',
            ],
        ]);

        Role::create([
            'name' => 'receptionist',
            'display_name' => 'Receptionist',
            'description' => 'Can manage bookings and check-in/out',
            'permissions' => [
                'view_dashboard',
                'manage_bookings',
                'checkin_checkout',
            ],
        ]);

        Role::create([
            'name' => 'chef',
            'display_name' => 'Chef',
            'description' => 'Can manage kitchen orders',
            'permissions' => [
                'view_orders',
                'update_order_status',
            ],
        ]);

        Role::create([
            'name' => 'waiter',
            'display_name' => 'Waiter',
            'description' => 'Can take and manage orders',
            'permissions' => [
                'view_menus',
                'create_orders',
                'manage_orders',
            ],
        ]);

        Role::create([
            'name' => 'bartender',
            'display_name' => 'Bartender',
            'description' => 'Can manage bar orders and track drink inventory',
            'permissions' => [
                'view_bar_menus',
                'create_bar_orders',
                'manage_bar_orders',
                'view_inventory',
                'update_order_status',
            ],
        ]);

        Role::create([
            'name' => 'bar_manager',
            'display_name' => 'Bar Manager',
            'description' => 'Can manage bar operations and orders',
            'permissions' => [
                'view_dashboard',
                'manage_bar_orders',
                'create_bar_orders',
                'update_order_status',
            ],
        ]);
    }
}

