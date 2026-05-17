<?php
// Standalone script to reconcile AC stock

require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/bootstrap/app.php';

use App\Models\Admin;
use App\Models\Rental;

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);

// Setup Laravel
$kernel->bootstrap();

echo "=== Stock Reconciliation ===\n\n";

// Ambil semua AC
$acs = Admin::all();

foreach ($acs as $ac) {
    // Hitung berapa banyak unit yang sedang disewa (status: active atau confirmed)
    $activeRentals = Rental::where('air_conditioner_id', $ac->id)
        ->whereIn('status', ['active', 'confirmed'])
        ->sum('quantity');
    
    // Jika tidak ada rental aktif, set stock ke nilai maksimal dan status ke available
    if ($activeRentals == 0) {
        $defaultStock = 10;
        
        $ac->update([
            'stock' => $defaultStock,
            'status' => 'available'
        ]);
        
        echo "✓ {$ac->model} (ID: {$ac->id}) → Stock: {$defaultStock}, Status: available\n";
    } else {
        // Ada rental aktif, update status ke rented
        $ac->update(['status' => 'rented']);
        echo "⚠ {$ac->model} (ID: {$ac->id}) → Sedang disewa ({$activeRentals} unit), Status: rented\n";
    }
}

echo "\n=== Reconciliation Complete ===\n";
?>
