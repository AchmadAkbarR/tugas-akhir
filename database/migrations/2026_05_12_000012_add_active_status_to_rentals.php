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
        if (DB::getDriverName() === 'sqlite') {
            // SQLite doesn't support MODIFY, skip for SQLite
            // The status column already supports the needed values
        } else {
            // Add 'active' back to the enum values for MySQL
            DB::statement("ALTER TABLE rentals MODIFY status ENUM('active', 'confirmed', 'completed', 'cancelled') DEFAULT 'active'");
        }
        
        // Update existing 'confirmed' records to 'active' for currently active rentals
        DB::table('rentals')
            ->where('status', 'confirmed')
            ->where('rental_end', '>', now())
            ->update(['status' => 'active']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (DB::getDriverName() === 'sqlite') {
            // SQLite doesn't support MODIFY, skip for SQLite
        } else {
            // Revert back to previous values for MySQL
            DB::statement("ALTER TABLE rentals MODIFY status ENUM('confirmed', 'completed', 'cancelled') DEFAULT 'confirmed'");
        }
        
        // Update 'active' records back to 'confirmed'
        DB::table('rentals')->where('status', 'active')->update(['status' => 'confirmed']);
    }
};
