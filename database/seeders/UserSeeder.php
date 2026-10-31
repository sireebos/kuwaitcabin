<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $adminRole = Role::where('name', 'admin')->first();
        $managerRole = Role::where('name', 'manager')->first();
        $receptionistRole = Role::where('name', 'receptionist')->first();
        $chefRole = Role::where('name', 'chef')->first();
        $waiterRole = Role::where('name', 'waiter')->first();

        User::create([
            'name' => 'Admin User',
            'email' => 'admin@hotel.test',
            'password' => Hash::make('password'),
            'property_id' => 1,
            'role_id' => $adminRole->id,
            'phone' => '+1-212-555-0101',
            'status' => 'active',
            'email_verified_at' => now(),
        ]);

        User::create([
            'name' => 'Manager User',
            'email' => 'manager@hotel.test',
            'password' => Hash::make('password'),
            'property_id' => 1,
            'role_id' => $managerRole->id,
            'phone' => '+1-212-555-0102',
            'status' => 'active',
            'email_verified_at' => now(),
        ]);

        User::create([
            'name' => 'Receptionist User',
            'email' => 'receptionist@hotel.test',
            'password' => Hash::make('password'),
            'property_id' => 1,
            'role_id' => $receptionistRole->id,
            'phone' => '+1-212-555-0103',
            'status' => 'active',
            'email_verified_at' => now(),
        ]);

        User::create([
            'name' => 'Chef User',
            'email' => 'chef@hotel.test',
            'password' => Hash::make('password'),
            'property_id' => 1,
            'role_id' => $chefRole->id,
            'phone' => '+1-212-555-0104',
            'status' => 'active',
            'email_verified_at' => now(),
        ]);

        User::create([
            'name' => 'Waiter User',
            'email' => 'waiter@hotel.test',
            'password' => Hash::make('password'),
            'property_id' => 1,
            'role_id' => $waiterRole->id,
            'phone' => '+1-212-555-0105',
            'status' => 'active',
            'email_verified_at' => now(),
        ]);
    }
}

