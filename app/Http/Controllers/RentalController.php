<?php

namespace App\Http\Controllers;

use App\Models\Rental;
use App\Models\Admin;
use Illuminate\Http\Request;
use Auth;

class RentalController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $rentals = Rental::with(['user', 'airConditioner'])->paginate(10);
        return view('rentals.index', compact('rentals'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $admins = Admin::where('status', 'available')->get();
        return view('rentals.form', compact('admins'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'air_conditioner_id' => 'required|exists:air_conditioners,id',
            'customer_name' => 'required|string',
            'customer_phone' => 'required|string',
            'customer_address' => 'required|string',
            'rental_type' => 'required|in:daily,monthly',
            'rental_start' => 'required|date_format:Y-m-d\TH:i',
            'rental_end' => 'nullable|date_format:Y-m-d\TH:i',
            'total_price' => 'required|numeric|min:0',
            'status' => 'required|in:confirmed,completed,cancelled',
            'notes' => 'nullable|string',
            'quantity' => 'required|integer|min:1',
        ]);

        $ac = Admin::find($validated['air_conditioner_id']);
        
        // Check stock if status is confirmed
        if ($validated['status'] == 'confirmed' && $ac->stock < $validated['quantity']) {
            return redirect()->back()->with('error', 'Stok tidak cukup');
        }

        $validated['user_id'] = Auth::id();
        $rental = Rental::create($validated);

        // Decrease stock if rental is confirmed
        if ($validated['status'] == 'confirmed') {
            $ac->decrement('stock', $validated['quantity']);
            // Update status to 'rented' only if all stock is used up
            if ($ac->stock == 0) {
                $ac->update(['status' => 'rented']);
            }
        }

        return redirect()->route('rentals.index')->with('success', 'Rental berhasil dibuat!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Rental $rental)
    {
        return view('rentals.show', compact('rental'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Rental $rental)
    {
        $admins = Admin::get();
        return view('rentals.form', compact('rental', 'admins'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Rental $rental)
    {
        $validated = $request->validate([
            'air_conditioner_id' => 'required|exists:air_conditioners,id',
            'customer_name' => 'required|string',
            'customer_phone' => 'required|string',
            'customer_address' => 'required|string',
            'rental_type' => 'required|in:daily,monthly',
            'rental_start' => 'required|date_format:Y-m-d\TH:i',
            'rental_end' => 'nullable|date_format:Y-m-d\TH:i',
            'total_price' => 'required|numeric|min:0',
            'status' => 'required|in:confirmed,completed,cancelled',
            'notes' => 'nullable|string',
        ]);

        // Update status - observer akan handle stock return otomatis
        $rental->update($validated);
        return redirect()->route('rentals.index')->with('success', 'Rental berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Rental $rental)
    {
        // Observer akan handle stock return otomatis
        $rental->delete();
        return redirect()->route('rentals.index')->with('success', 'Rental berhasil dihapus!');
    }

    /**
     * Update rental status (for quick status change)
     */
    public function updateStatus(Request $request, Rental $rental)
    {
        $validated = $request->validate([
            'status' => 'required|in:confirmed,completed,cancelled',
        ]);

        // Update status - observer akan handle stock return otomatis
        $rental->update($validated);
        
        return redirect()->back()->with('success', 'Status rental berhasil diperbarui!');
    }
}
