<?php
/**
 * Quick script to add light_logo_path column to properties table
 * Run this from your server's root directory: php add_light_logo_column.php
 */

// Load Laravel
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

try {
    if (!Schema::hasColumn('properties', 'light_logo_path')) {
        Schema::table('properties', function (Blueprint $table) {
            $table->string('light_logo_path')->nullable()->after('logo_path');
        });
        echo "✅ Successfully added light_logo_path column to properties table!\n";
    } else {
        echo "ℹ️  light_logo_path column already exists in properties table.\n";
    }
} catch (\Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    exit(1);
}

