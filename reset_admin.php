<?php
/**
 * Admin Password Reset Script
 * Run: php artisan tinker < reset_admin.php
 * Or upload to server and run: php reset_admin.php
 * DELETE THIS FILE AFTER USE!
 */

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\User;
use App\Models\Role;
use App\Models\Property;
use Illuminate\Support\Facades\Hash;

echo "=== Admin Password Reset ===\n\n";

// Find admin role
$adminRole = Role::where('name', 'admin')->first();
$property = Property::first();

if (!$adminRole) {
    echo "ERROR: Admin role not found. Creating it...\n";
    $adminRole = Role::create(['name' => 'admin', 'description' => 'Administrator']);
}

if (!$property) {
    echo "ERROR: No property found in database.\n";
    exit(1);
}

echo "Property: {$property->name} (ID: {$property->id})\n";
echo "Admin Role ID: {$adminRole->id}\n\n";

// Check existing admin user
$existingAdmin = User::where('email', 'admin@hotel.test')->first();

if ($existingAdmin) {
    echo "Found existing admin user. Resetting password...\n";
    $existingAdmin->update([
        'password' => Hash::make('password'),
        'status' => 'active',
        'email_verified_at' => now(),
    ]);
    echo "Password reset successfully!\n";
} else {
    echo "No admin user found. Creating new admin...\n";
    User::create([
        'name' => 'Admin User',
        'email' => 'admin@hotel.test',
        'password' => Hash::make('password'),
        'property_id' => $property->id,
        'role_id' => $adminRole->id,
        'status' => 'active',
        'email_verified_at' => now(),
    ]);
    echo "Admin user created!\n";
}

echo "\n=== Login Credentials ===\n";
echo "Email: admin@hotel.test\n";
echo "Password: password\n";
echo "\n⚠️  DELETE THIS FILE AFTER USE!\n";
