<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Admin;
use App\Models\Rental;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Sync stock berdasarkan active rentals
        $acs = Admin::all();
        
        foreach ($acs as $ac) {
            // Count active rentals for this AC
            $activeRentals = Rental::where('air_conditioner_id', $ac->id)
                ->where('status', 'active')
                ->count();
            
            if ($activeRentals > 0) {
                // Original stock + active rentals = total stock
                // But we need to know original stock first
                // For now, just ensure stock is reduced by active rentals
                $ac->stock = max(0, $ac->stock - $activeRentals);
                $ac->save();
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // This migration only syncs data, no columns to drop
    }
};
