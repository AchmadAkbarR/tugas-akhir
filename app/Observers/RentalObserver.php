<?php

namespace App\Observers;

use App\Models\Rental;
use App\Models\Admin;

class RentalObserver
{
    /**
     * Handle the Rental "created" event.
     */
    public function created(Rental $rental): void
    {
        //
    }

    /**
     * Handle the Rental "updated" event.
     */
    public function updated(Rental $rental): void
    {
        // Return stock jika status berubah ke completed atau cancelled
        $oldStatus = $rental->getOriginal('status');
        
        if (
            ($rental->status === 'completed' || $rental->status === 'cancelled') && 
            $oldStatus !== 'completed' && 
            $oldStatus !== 'cancelled'
        ) {
            $this->returnStock($rental);
        }
    }

    /**
     * Handle the Rental "deleted" event.
     */
    public function deleted(Rental $rental): void
    {
        // Return stock saat rental dihapus
        $this->returnStock($rental);
    }

    /**
     * Return stock to air conditioner
     */
    private function returnStock(Rental $rental): void
    {
        $ac = Admin::find($rental->air_conditioner_id);
        
        if ($ac) {
            // Tambah kembali stock
            $ac->increment('stock', $rental->quantity);
            
            // Update status jika diperlukan
            if ($ac->status === 'rented') {
                $ac->update(['status' => 'available']);
            }
        }
    }
}

