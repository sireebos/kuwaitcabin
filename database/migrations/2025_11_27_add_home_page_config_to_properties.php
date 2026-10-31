<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('properties', function (Blueprint $table) {
            $table->string('home_title')->nullable()->after('background_path');
            $table->text('home_description')->nullable()->after('home_title');
            $table->string('home_image_path')->nullable()->after('home_description');
            $table->string('home_image_link')->nullable()->after('home_image_path');
            $table->json('features_enabled')->nullable()->after('home_image_link');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('properties', function (Blueprint $table) {
            $table->dropColumn(['home_title', 'home_description', 'home_image_path', 'home_image_link', 'features_enabled']);
        });
    }
};

