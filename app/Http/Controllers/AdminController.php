<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $admins = Admin::with('category')->paginate(10);
        return view('admin.index', compact('admins'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.form');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'model' => 'required|string|unique:air_conditioners',
            'description' => 'required|string',
            'rental_price_per_day' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        $imagePath = null;

if ($request->hasFile('image')) {
    $file = $request->file('image');
    $filename = time() . '_' . $file->getClientOriginalName();

    $file->move(public_path('images'), $filename);

    $imagePath = $filename;
}

        // Create with default values
        Admin::create([
            'model' => $validated['model'],
            'description' => $validated['description'],
            'rental_price_per_day' => $validated['rental_price_per_day'],
            'rental_price_per_month' => $validated['rental_price_per_day'] * 20, // Default: 20 hari sewa
            'cooling_capacity' => 9000, // Default
            'type' => 'split', // Default
            'status' => 'available',
            'serial_number' => 'SN-' . time(),
            'category_id' => 1, // Default category
            'stock' => $validated['stock'],
            'image' => $imagePath,
        ]);

        return redirect()->route('admin.index')->with('success', 'Barang berhasil ditambahkan!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Admin $admin)
    {
        return view('admin.show', compact('admin'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Admin $admin)
    {
        return view('admin.form', compact('admin'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Admin $admin)
    {
        $validated = $request->validate([
            'model' => 'required|string|unique:air_conditioners,model,' . $admin->id,
            'description' => 'required|string',
            'rental_price_per_day' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($admin->image) {
                Storage::disk('public')->delete($admin->image);
            }
            $validated['image'] = $request->file('image')->store('products', 'public');
        }

        $admin->update($validated);
        return redirect()->route('admin.index')->with('success', 'Barang berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Admin $admin)
    {
        // Delete image if exists
        if ($admin->image) {
            Storage::disk('public')->delete($admin->image);
        }
        
        $admin->delete();
        return redirect()->route('admin.index')->with('success', 'Barang berhasil dihapus!');
    }
}
