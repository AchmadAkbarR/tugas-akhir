<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\Rental;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $totalAC = Admin::count();
        $availableAC = Admin::where('status', 'available')->count();
        $activeRentals = Rental::where('status', 'active')->count();
        
        // Pendapatan bulan ini
        $monthlyRevenue = Rental::whereBetween(
            'created_at',
            [now()->startOfMonth(), now()->endOfMonth()]
        )->sum('total_price');
        
        $recentACs = Admin::latest()->take(5)->get();
        $recentRentals = Rental::latest()->take(5)->get();
        
        return view('dashboard', compact(
            'totalAC',
            'availableAC',
            'activeRentals',
            'monthlyRevenue',
            'recentACs',
            'recentRentals'
        ));
    }
}
