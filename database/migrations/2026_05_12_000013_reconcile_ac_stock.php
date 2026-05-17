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
            // SQLite doesn't support table aliases in UPDATE, use simpler syntax
            
            // Update semua AC yang tidak punya rental aktif: set stock = 10 dan status = available
            DB::statement("
                UPDATE air_conditioners 
                SET stock = 10, status = 'available'
                WHERE id NOT IN (
                    SELECT DISTINCT air_conditioner_id 
                    FROM rentals 
                    WHERE status IN ('active', 'confirmed')
                )
            ");
            
            // Update AC yang punya rental aktif: set status = rented
            DB::statement("
                UPDATE air_conditioners 
                SET status = 'rented'
                WHERE id IN (
                    SELECT DISTINCT air_conditioner_id 
                    FROM rentals 
                    WHERE status IN ('active', 'confirmed')
                )
            ");
        } else {
            // MySQL syntax with table alias
            DB::statement("
                UPDATE air_conditioners ac
                SET ac.stock = 10, ac.status = 'available'
                WHERE ac.id NOT IN (
                    SELECT DISTINCT air_conditioner_id 
                    FROM rentals 
                    WHERE status IN ('active', 'confirmed')
                )
            ");
            
            // Update AC yang punya rental aktif: set status = rented
            DB::statement("
                UPDATE air_conditioners ac
                SET ac.status = 'rented'
                WHERE ac.id IN (
                    SELECT DISTINCT air_conditioner_id 
                    FROM rentals 
                    WHERE status IN ('active', 'confirmed')
                )
            ");
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No rollback needed
    }
};
