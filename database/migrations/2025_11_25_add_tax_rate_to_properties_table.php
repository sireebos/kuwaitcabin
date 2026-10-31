<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('properties', function (Blueprint $table) {
            $table->decimal('tax_rate', 5, 2)->default(10.00)->nullable()->after('currency');
        });

        // Update existing records to have default tax rate
        DB::table('properties')->whereNull('tax_rate')->update(['tax_rate' => 10.00]);
    }

    public function down(): void
    {
        Schema::table('properties', function (Blueprint $table) {
            $table->dropColumn('tax_rate');
        });
    }
};

