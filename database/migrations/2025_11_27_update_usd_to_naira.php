<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Update all properties with USD currency to use Nigerian Naira (₦)
        DB::table('properties')
            ->where('currency', 'USD')
            ->update(['currency' => '₦']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert back to USD if needed
        DB::table('properties')
            ->where('currency', '₦')
            ->update(['currency' => 'USD']);
    }
};

