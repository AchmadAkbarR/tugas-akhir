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
        Schema::table('rentals', function (Blueprint $table) {
            // Make user_id nullable for customer orders
            $table->foreignId('user_id')->nullable()->change();
            
            // Add customer_email column
            $table->string('customer_email')->nullable()->after('customer_name');
            
            // Add rental_end_date as alias for rental_end (for consistency)
            // Or we can just use rental_end in code
            
            // Change status enum to include more options
            // $table->enum('status', ['active', 'completed', 'cancelled', 'pending'])->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('rentals', function (Blueprint $table) {
            $table->dropColumn('customer_email');
            // Revert user_id to not nullable in migration down
        });
    }
};
