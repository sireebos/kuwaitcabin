<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('stock_sessions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('property_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->date('session_date');
            $table->enum('status', ['open', 'closed'])->default('open');
            $table->text('notes')->nullable();
            $table->decimal('opening_balance', 10, 2)->nullable();
            $table->decimal('closing_balance', 10, 2)->nullable();
            $table->decimal('total_sales', 10, 2)->default(0);
            $table->decimal('total_usage', 10, 2)->default(0);
            $table->decimal('variance', 10, 2)->nullable();
            $table->decimal('variance_percentage', 5, 2)->nullable();
            $table->timestamps();
            
            $table->unique(['property_id', 'session_date', 'user_id']);
            $table->index(['property_id', 'session_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stock_sessions');
    }
};
