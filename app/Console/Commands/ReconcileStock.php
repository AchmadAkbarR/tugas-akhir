<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Admin;
use App\Models\Rental;

class ReconcileStock extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'stock:reconcile';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Reconcile AC stock berdasarkan rental aktif';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting stock reconciliation...');
        
        // Ambil semua AC
        $acs = Admin::all();
        
        foreach ($acs as $ac) {
            // Hitung berapa banyak unit yang sedang disewa (status: active atau confirmed)
            $activeRentals = Rental::where('air_conditioner_id', $ac->id)
                ->whereIn('status', ['active', 'confirmed'])
                ->sum('quantity');
            
            // Jika tidak ada rental aktif, set stock ke nilai maksimal dan status ke available
            if ($activeRentals == 0) {
                // Default stock = 10 jika tidak ada history
                $defaultStock = 10;
                
                $ac->update([
                    'stock' => $defaultStock,
                    'status' => 'available'
                ]);
                
                $this->info("✓ {$ac->model} (ID: {$ac->id}) → Stock: {$defaultStock}, Status: available");
            } else {
                // Ada rental aktif, update status ke rented
                $ac->update(['status' => 'rented']);
                $this->info("⚠ {$ac->model} (ID: {$ac->id}) → Sedang disewa ({$activeRentals} unit), Status: rented");
            }
        }
        
        $this->info('Stock reconciliation completed!');
    }
}
