<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('restaurant_reservations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('property_id')->constrained('properties')->onDelete('cascade');
            $table->foreignId('restaurant_table_id')->constrained('restaurant_tables')->onDelete('cascade');
            $table->foreignId('guest_id')->nullable()->constrained('guests')->onDelete('set null');
            $table->string('guest_name');
            $table->string('guest_phone');
            $table->dateTime('reservation_time');
            $table->integer('number_of_guests');
            $table->enum('status', ['pending', 'confirmed', 'seated', 'completed', 'cancelled'])->default('pending');
            $table->text('special_requests')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('restaurant_reservations');
    }
};

