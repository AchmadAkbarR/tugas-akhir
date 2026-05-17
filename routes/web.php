<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\RentalController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

// Admin Login
Route::get('/admin/login', function () {
    if (Auth::check() && Auth::user()->role === 'admin') {
        return redirect('/dashboard');
    }
    return view('auth.admin-login');
})->name('admin.login');

Route::post('/admin/login', function () {
    $credentials = request()->validate([
        'email' => 'required|email',
        'password' => 'required',
    ]);

    if (Auth::attempt($credentials)) {
        $user = Auth::user();
        
        // Cek apakah user adalah admin
        if ($user->role !== 'admin') {
            Auth::logout();
            return back()->withErrors(['email' => 'Anda bukan admin, gunakan login user biasa.'])->onlyInput('email');
        }
        
        request()->session()->regenerate();
        return redirect('/dashboard');
    }

    return back()->withErrors(['email' => 'Email atau password salah.'])->onlyInput('email');
})->name('admin.login.post');

Route::post('/logout', function () {
    Auth::logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect('/');
})->middleware('auth')->name('logout');

// Customer Routes (No Auth Required)
Route::name('customer.')->group(function () {
    Route::get('/', [CustomerController::class, 'home'])->name('home');
    Route::get('/consultation', [CustomerController::class, 'consultation'])->name('consultation');
    Route::get('/checkout/{id}', function($id) {
        if (!auth()->check()) {
            return redirect()->route('customer.dashboard');
        }
        return app(CustomerController::class)->checkout($id);
    })->name('checkout');
});

// Customer Authenticated Routes
Route::middleware('auth')->name('customer.')->group(function () {
    Route::post('/checkout/submit', [CustomerController::class, 'storeRental'])->name('store-rental');
    Route::get('/payment/{acId}', [CustomerController::class, 'payment'])->name('payment');
    Route::post('/confirm-payment', [CustomerController::class, 'confirmPayment'])->name('confirm-payment');
});

// Payment Callback Routes - TANPA AUTH MIDDLEWARE (Midtrans redirect dari luar session)
Route::name('customer.')->group(function () {
    Route::get('/payment-success', [CustomerController::class, 'paymentSuccess'])->name('payment-success');
    Route::get('/payment-error', [CustomerController::class, 'paymentError'])->name('payment-error');
    Route::get('/payment-pending', [CustomerController::class, 'paymentPending'])->name('payment-pending');
});

// Authenticated Order Confirmation Route
Route::middleware('auth')->name('customer.')->group(function () {
    Route::get('/order-confirmation/{id}', [CustomerController::class, 'orderConfirmation'])->name('order-confirmation');
});

// Customer Dashboard Routes (Public for Login/Register, Shows History if Authenticated)
Route::get('/customer-dashboard', [CustomerController::class, 'dashboard'])->name('customer.dashboard');

// Login POST
Route::post('/customer-dashboard/login', function () {
    $credentials = request()->validate([
        'email' => 'required|email',
        'password' => 'required',
    ]);

    if (Auth::attempt($credentials)) {
        $user = Auth::user();
        
        if ($user->role === 'admin') {
            Auth::logout();
            return back()->withErrors(['email' => 'Admin tidak bisa login di sini, gunakan login admin.'])->onlyInput('email');
        }
        
        request()->session()->regenerate();
        return redirect('/customer-dashboard');
    }

    return back()->withErrors(['email' => 'Email atau password salah.'])->onlyInput('email');
})->name('login.post');

// Register POST
Route::post('/customer-dashboard/register', function () {
    $validator = Validator::make(request()->all(), [
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users',
        'password' => 'required|confirmed|min:6',
    ], [
        'password.confirmed' => 'Konfirmasi password tidak cocok.',
        'password.min' => 'Password minimal harus 6 karakter.',
        'email.unique' => 'Email sudah terdaftar.',
        'name.required' => 'Nama harus diisi.',
        'email.required' => 'Email harus diisi.',
        'email.email' => 'Format email tidak valid.',
        'password.required' => 'Password harus diisi.',
    ]);

    if ($validator->fails()) {
        return redirect('/customer-dashboard?tab=register')
            ->withErrors($validator)
            ->withInput(request()->only('name', 'email', 'password_confirmation'));
    }

    $validated = $validator->validated();

    \App\Models\User::create([
        'name' => $validated['name'],
        'email' => $validated['email'],
        'password' => bcrypt($validated['password']),
        'role' => 'user',
    ]);

    return redirect('/customer-dashboard')->with('success', '✅ Registrasi berhasil! Silakan login dengan email dan password Anda.');
})->name('register.post');

Route::middleware(['auth', 'verified'])->group(function () {
    // Admin only routes
    Route::get('/dashboard', function () {
        if (auth()->user()->role !== 'admin') {
            return redirect('/customer-dashboard');
        }
        return app(DashboardController::class)->index();
    })->name('dashboard');

    // Admin Management
    Route::resource('admin', AdminController::class);

    // Rental Management
    Route::resource('rentals', RentalController::class);
    Route::patch('/rentals/{rental}/status', [RentalController::class, 'updateStatus'])->name('rentals.updateStatus');
});
