<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('properties', function (Blueprint $table) {
            $table->string('display_name')->nullable()->after('name')->comment('Display name for the hotel (shown in UI)');
            $table->string('logo_path')->nullable()->after('display_name')->comment('Path to hotel logo image');
        });
    }

    public function down(): void
    {
        Schema::table('properties', function (Blueprint $table) {
            $table->dropColumn(['display_name', 'logo_path']);
        });
    }
};

