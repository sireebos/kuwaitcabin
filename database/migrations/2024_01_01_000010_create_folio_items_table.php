<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('folio_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('folio_id')->constrained('folios')->onDelete('cascade');
            $table->string('description');
            $table->enum('type', ['room', 'restaurant', 'bar', 'service', 'other'])->default('other');
            $table->decimal('amount', 10, 2);
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('folio_items');
    }
};

