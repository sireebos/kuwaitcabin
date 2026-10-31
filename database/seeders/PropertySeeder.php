<?php

namespace Database\Seeders;

use App\Models\Property;
use Illuminate\Database\Seeder;

class PropertySeeder extends Seeder
{
    public function run(): void
    {
        Property::create([
            'name' => 'Grand Hotel & Restaurant',
            'slug' => 'grand-hotel-restaurant',
            'display_name' => 'Kuwait Cabin',
            'description' => 'A luxury 5-star hotel with world-class dining facilities',
            'address' => '123 Main Street',
            'city' => 'New York',
            'state' => 'NY',
            'postal_code' => '10001',
            'country' => 'USA',
            'phone' => '+1-212-555-0100',
            'email' => 'info@grandhotel.com',
            'website' => 'www.grandhotel.com',
            'total_rooms' => 150,
            'check_in_time' => 14.00,
            'check_out_time' => 11.00,
            'currency' => 'USD',
            'is_active' => true,
        ]);

        Property::create([
            'name' => 'Riverside Inn',
            'slug' => 'riverside-inn',
            'description' => 'A cozy boutique hotel with scenic views',
            'address' => '456 River Road',
            'city' => 'San Francisco',
            'state' => 'CA',
            'postal_code' => '94102',
            'country' => 'USA',
            'phone' => '+1-415-555-0200',
            'email' => 'info@riversideinn.com',
            'website' => 'www.riversideinn.com',
            'total_rooms' => 50,
            'check_in_time' => 15.00,
            'check_out_time' => 10.00,
            'currency' => 'USD',
            'is_active' => true,
        ]);
    }
}

