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
        Schema::create('air_conditioners', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained()->onDelete('cascade');
            $table->string('model')->unique();
            $table->text('description');
            $table->decimal('cooling_capacity', 8, 2); // in BTU
            $table->enum('type', ['window', 'split', 'portable', 'central'])->default('split');
            $table->decimal('rental_price_per_day', 10, 2);
            $table->decimal('rental_price_per_month', 10, 2);
            $table->enum('status', ['available', 'rented', 'maintenance', 'inactive'])->default('available');
            $table->string('serial_number')->unique();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('air_conditioners');
    }
};
