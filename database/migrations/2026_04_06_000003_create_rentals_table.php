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
        Schema::create('rentals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('air_conditioner_id')->constrained()->onDelete('cascade');
            $table->dateTime('rental_start');
            $table->dateTime('rental_end')->nullable();
            $table->enum('rental_type', ['daily', 'monthly']);
            $table->decimal('total_price', 12, 2);
            $table->enum('status', ['active', 'completed', 'cancelled'])->default('active');
            $table->string('customer_name');
            $table->string('customer_phone');
            $table->text('customer_address');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rentals');
    }
};
