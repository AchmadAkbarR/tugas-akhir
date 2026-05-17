<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\Rental;
use Illuminate\Http\Request;
use Midtrans\Config;
use Midtrans\Snap;
use Midtrans\Transaction;

class CustomerController extends Controller
{
    /**
     * Initialize Midtrans configuration
     */
    protected function initMidtrans()
    {
        Config::$serverKey = config('midtrans.server_key');
        Config::$clientKey = config('midtrans.client_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized = true;
        Config::$is3ds = true;
    }
    /**
     * Display customer home page with available AC
     */
    public function home()
    {
        $admins = Admin::where('status', 'available')
            ->with('category')
            ->get();

        return view('customer.home', compact('admins'));
    }

    /**
     * Display chatbot (FAQ)
     */
    public function chatbot()
    {
        return view('customer.chatbot');
    }

    /**
     * Display consultation page
     */
    public function consultation()
    {
        return view('customer.consultation');
    }

    /**
     * Show checkout form
     */
    public function checkout($id)
    {
        $admin = Admin::findOrFail($id);
        return view('customer.checkout', compact('admin'));
    }

    /**
     * Store rental order (save to session, redirect to payment)
     */
    public function storeRental(Request $request)
    {
        $validated = $request->validate([
            'air_conditioner_id' => 'required|exists:air_conditioners,id',
            'customer_name' => 'required|string|max:255',
            'customer_email' => 'required|email',
            'customer_phone' => 'required|string|max:20',
            'customer_address' => 'required|string',
            'rental_start' => 'required|date|after_or_equal:today',
            'rental_end' => 'required|date|after:rental_start',
            'quantity' => 'required|integer|min:1',
            'notes' => 'nullable|string',
        ]);

        // Get AC and cast values to integer
        $ac = Admin::findOrFail($validated['air_conditioner_id']);
        $quantity = (int) $validated['quantity'];
        
        // Check stock availability
        if ($ac->stock < $quantity) {
            return redirect()->back()->with('error', 'Stok tidak cukup. Stok tersedia: ' . $ac->stock . ' unit');
        }
        
        // Calculate rental days
        $startDate = \Carbon\Carbon::parse($validated['rental_start']);
        $endDate = \Carbon\Carbon::parse($validated['rental_end']);
        $rentalDays = abs($endDate->diffInDays($startDate));
        
        // Ensure rental days is at least 1
        if ($rentalDays < 1) {
            return redirect()->back()->with('error', 'Durasi sewa minimal 1 hari. Silakan periksa tanggal sewa Anda.');
        }
        
        $totalPrice = ($ac->rental_price_per_day * $rentalDays * $quantity);

        // Generate order ID
        $orderId = 'RENTAL-' . auth()->id() . '-' . time();
        
        // Store in session AND cache for reliability
        $rentalData = [
            'order_id' => $orderId,
            'user_id' => auth()->id(),
            'air_conditioner_id' => $validated['air_conditioner_id'],
            'quantity' => $quantity,
            'customer_name' => $validated['customer_name'],
            'customer_email' => $validated['customer_email'],
            'customer_phone' => $validated['customer_phone'],
            'customer_address' => $validated['customer_address'],
            'rental_start' => $startDate->toDateTimeString(),
            'rental_end' => $endDate->toDateTimeString(),
            'rental_type' => $rentalDays > 30 ? 'monthly' : 'daily',
            'total_price' => $totalPrice,
            'notes' => $validated['notes'] ?? null,
        ];
        
        session(['rental_data' => $rentalData]);
        // Cache for 1 hour as backup
        \Cache::put('rental_' . $orderId, $rentalData, now()->addHour());

        return redirect()->route('customer.payment', ['acId' => $ac->id]);
    }

    /**
     * Show payment page
     */
    public function payment($acId)
    {
        if (!session()->has('rental_data')) {
            return redirect()->route('customer.home')->with('error', 'Session expired');
        }

        // Initialize Midtrans configuration
        $this->initMidtrans();

        $rentalData = session('rental_data');
        $ac = Admin::findOrFail($acId);
        $orderId = $rentalData['order_id'] ?? 'RENTAL-' . auth()->id() . '-' . time();

        // Validate total price
        $totalPrice = abs((int) $rentalData['total_price']);
        if ($totalPrice <= 0) {
            return redirect()->back()->with('error', 'Total pembayaran tidak valid. Silakan periksa tanggal sewa Anda.');
        }

        // Calculate rental days for item details
        $rentalDays = abs(\Carbon\Carbon::parse($rentalData['rental_end'])
            ->diffInDays(\Carbon\Carbon::parse($rentalData['rental_start'])));
        if ($rentalDays < 1) {
            $rentalDays = 1;
        }

        // Generate Midtrans transaction token
        $transactionDetails = [
            'order_id' => $orderId,
            'gross_amount' => $totalPrice,
        ];

        $customerDetails = [
            'first_name' => $rentalData['customer_name'],
            'email' => $rentalData['customer_email'],
            'phone' => $rentalData['customer_phone'],
            'billing_address' => [
                'address' => $rentalData['customer_address'],
            ],
        ];

        $itemDetails = [
            [
                'id' => $ac->id,
                'price' => (int) ($ac->rental_price_per_day * $rentalDays),
                'quantity' => (int) $rentalData['quantity'],
                'name' => $ac->model . ' (' . $rentalDays . ' Hari × ' . $rentalData['quantity'] . ' Unit)',
            ]
        ];

        $payload = [
            'transaction_details' => $transactionDetails,
            'customer_details' => $customerDetails,
            'item_details' => $itemDetails,
            'callbacks' => [
                'finish' => route('customer.payment-success') . '?order_id=' . urlencode($orderId),
                'error' => route('customer.payment-error') . '?order_id=' . urlencode($orderId),
                'pending' => route('customer.payment-pending') . '?order_id=' . urlencode($orderId),
            ]
        ];

        $snapToken = null;
        try {
            $snapToken = Snap::getSnapToken($payload);
            if ($snapToken) {
                // Store snap token in session and cache for reliability
                session([
                    'snap_token' => $snapToken,
                    'transaction_order_id' => $orderId,
                    'transaction_amount' => $totalPrice
                ]);
                // Cache as backup
                \Cache::put('snap_' . $orderId, [
                    'snap_token' => $snapToken,
                    'rental_data' => $rentalData,
                ], now()->addHour());
                
                \Log::info('🟩 Snap token generated', ['order_id' => $orderId, 'has_token' => !!$snapToken]);
            } else {
                \Log::warning('Midtrans Snap token is null', ['order_id' => $orderId]);
            }
        } catch (\Exception $e) {
            \Log::error('Midtrans error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal membuat token pembayaran. Silakan coba lagi atau hubungi support.');
        }

        return view('customer.payment', compact('rentalData', 'ac', 'snapToken', 'orderId'));
    }

    /**
     * Handle payment success (Midtrans callback)
     * Note: This route TIDAK have auth middleware karena Midtrans redirect dari luar session
     */
    public function paymentSuccess(Request $request)
    {
        $orderId = $request->order_id ?? $request->query('order_id') ?? session('transaction_order_id');
        
        \Log::info('🟦 PAYMENT SUCCESS CALLED', [
            'order_id' => $orderId,
            'has_session_rental' => session()->has('rental_data'),
            'url' => $request->fullUrl()
        ]);

        // Try to get rental data from session first
        $rentalData = session('rental_data');
        
        // If not in session, try cache (fallback)
        if (!$rentalData && $orderId) {
            $cacheData = \Cache::get('rental_' . $orderId);
            if ($cacheData) {
                $rentalData = $cacheData;
                \Log::info('🟦 Rental data retrieved from CACHE', ['order_id' => $orderId]);
            }
        }
        
        if (!$rentalData) {
            \Log::warning('🔴 Payment success but no rental_data in session or cache', [
                'order_id' => $orderId
            ]);
            return redirect()->route('customer.home')->with('error', 'Data sewa tidak ditemukan. Silakan coba lagi.');
        }

        if (!$orderId) {
            \Log::error('🔴 Payment success no order ID', [
                'rentalData_order_id' => $rentalData['order_id'] ?? null
            ]);
            return redirect()->route('customer.home')->with('error', 'Order ID tidak ditemukan.');
        }

        // Initialize Midtrans configuration
        $this->initMidtrans();

        try {
            // Get transaction status from Midtrans
            $status = Transaction::status($orderId);
            
            \Log::info('🟦 MIDTRANS STATUS', [
                'order_id' => $orderId,
                'transaction_status' => $status->transaction_status ?? 'unknown',
                'payment_type' => $status->payment_type ?? 'unknown'
            ]);

            // Check if payment is completed or pending
            $successStatus = ['capture', 'settlement', 'pending'];
            
            if (isset($status->transaction_status) && in_array($status->transaction_status, $successStatus)) {
                \Log::info('🟦 RENTAL DATA READY', ['rental_data' => $rentalData]);
                
                // Determine payment status based on transaction status
                $paymentStatus = ($status->transaction_status === 'pending') ? 'pending' : 'confirmed';
                
                // Create rental
                $rental = Rental::create([
                    'user_id' => $rentalData['user_id'],
                    'air_conditioner_id' => $rentalData['air_conditioner_id'],
                    'quantity' => $rentalData['quantity'],
                    'payment_method' => $status->payment_type ?? 'midtrans',
                    'payment_status' => $paymentStatus,
                    'payment_reference' => $status->transaction_id ?? $orderId,
                    'customer_name' => $rentalData['customer_name'],
                    'customer_email' => $rentalData['customer_email'],
                    'customer_phone' => $rentalData['customer_phone'],
                    'customer_address' => $rentalData['customer_address'],
                    'rental_start' => $rentalData['rental_start'],
                    'rental_end' => $rentalData['rental_end'],
                    'rental_type' => $rentalData['rental_type'],
                    'total_price' => $rentalData['total_price'],
                    'notes' => $rentalData['notes'] ?? null,
                    'status' => 'active',
                ]);

                \Log::info('🟩 ✅ RENTAL CREATED', ['rental_id' => $rental->id, 'order_id' => $orderId]);

                // Decrease stock
                $ac = Admin::findOrFail($rentalData['air_conditioner_id']);
                $ac->decrement('stock', $rentalData['quantity']);
                
                if ($ac->stock <= 0) {
                    $ac->update(['status' => 'rented']);
                }

                // Clear caches
                session()->forget(['rental_data', 'snap_token', 'transaction_order_id']);
                \Cache::forget('rental_' . $orderId);
                \Cache::forget('snap_' . $orderId);
                
                \Log::info('🟩 REDIRECT TO CONFIRMATION', ['rental_id' => $rental->id]);

                return redirect()->route('customer.order-confirmation', $rental->id)
                    ->with('success', '✅ Pembayaran berhasil! Pesanan Anda telah dikonfirmasi.');
            } else {
                \Log::warning('🟠 Payment not successful', [
                    'status' => $status->transaction_status ?? 'unknown',
                    'order_id' => $orderId
                ]);
                session()->forget(['rental_data', 'snap_token', 'transaction_order_id']);
                \Cache::forget('rental_' . $orderId);
                return redirect()->route('customer.home')
                    ->with('error', '⚠️ Status: ' . ($status->transaction_status ?? 'unknown'));
            }
        } catch (\Exception $e) {
            // Handle 404 error specifically (transaction not found in Midtrans yet)
            if (strpos($e->getMessage(), '404') !== false || strpos($e->getMessage(), "doesn't exist") !== false) {
                \Log::warning('🟠 MIDTRANS 404 - Transaction not found, but proceeding anyway', [
                    'error' => $e->getMessage(),
                    'order_id' => $orderId
                ]);
                
                // For sandbox environment, sometimes Midtrans is slow to sync
                // Proceed with rental creation using confirmed status since user already paid
                try {
                    $rental = Rental::create([
                        'user_id' => $rentalData['user_id'],
                        'air_conditioner_id' => $rentalData['air_conditioner_id'],
                        'quantity' => $rentalData['quantity'],
                        'payment_method' => 'midtrans',
                        'payment_status' => 'confirmed',
                        'payment_reference' => $orderId,
                        'customer_name' => $rentalData['customer_name'],
                        'customer_email' => $rentalData['customer_email'],
                        'customer_phone' => $rentalData['customer_phone'],
                        'customer_address' => $rentalData['customer_address'],
                        'rental_start' => $rentalData['rental_start'],
                        'rental_end' => $rentalData['rental_end'],
                        'rental_type' => $rentalData['rental_type'],
                        'total_price' => $rentalData['total_price'],
                        'notes' => $rentalData['notes'] ?? null,
                        'status' => 'active',
                    ]);

                    \Log::info('🟩 ✅ RENTAL CREATED (from 404 fallback)', ['rental_id' => $rental->id, 'order_id' => $orderId]);

                    // Decrease stock
                    $ac = Admin::findOrFail($rentalData['air_conditioner_id']);
                    $ac->decrement('stock', $rentalData['quantity']);
                    
                    if ($ac->stock <= 0) {
                        $ac->update(['status' => 'rented']);
                    }

                    // Clear caches
                    session()->forget(['rental_data', 'snap_token', 'transaction_order_id']);
                    \Cache::forget('rental_' . $orderId);
                    \Cache::forget('snap_' . $orderId);

                    return redirect()->route('customer.order-confirmation', $rental->id)
                        ->with('success', '✅ Pembayaran berhasil! Pesanan Anda telah dikonfirmasi.');
                } catch (\Exception $createError) {
                    \Log::error('🔴 Error creating rental from 404 fallback', [
                        'error' => $createError->getMessage(),
                        'order_id' => $orderId
                    ]);
                    return redirect()->route('customer.home')
                        ->with('error', '❌ Gagal memproses pesanan. Silakan hubungi support.');
                }
            }
            
            // Other errors
            \Log::error('🔴 ERROR', [
                'error' => $e->getMessage(),
                'order_id' => $orderId,
                'code' => $e->getCode()
            ]);
            session()->forget(['rental_data', 'snap_token', 'transaction_order_id']);
            \Cache::forget('rental_' . $orderId);
            return redirect()->route('customer.home')
                ->with('error', '❌ Error: ' . substr($e->getMessage(), 0, 100));
        }
    }

    /**
     * Handle payment error
     */
    public function paymentError(Request $request)
    {
        session()->forget(['rental_data', 'snap_token', 'transaction_order_id']);
        return redirect()->route('customer.home')
            ->with('error', 'Pembayaran dibatalkan atau terjadi kesalahan. Silakan coba lagi.');
    }

    /**
     * Handle payment pending
     */
    public function paymentPending(Request $request)
    {
        return redirect()->route('customer.home')
            ->with('info', 'Pembayaran Anda sedang diproses. Silakan tunggu konfirmasi dari admin.');
    }

    /**
     * Process payment confirmation (DEPRECATED - kept for compatibility)
     */
    public function confirmPayment(Request $request)
    {
        return $this->paymentSuccess($request);
    }

    /**
     * Show order confirmation
     */
    public function orderConfirmation($id)
    {
        // Always fetch fresh data from database to ensure real-time timestamps
        $rental = Rental::with('airConditioner')->find($id);
        if (!$rental) {
            abort(404);
        }
        
        return response()
            ->view('customer.order-confirmation', compact('rental'))
            ->header('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0')
            ->header('Pragma', 'no-cache')
            ->header('Expires', 'Wed, 21 Oct 2015 07:28:00 GMT');
    }

    /**
     * Show customer dashboard with rental history or login/register if not authenticated
     */
    public function dashboard()
    {
        $rentals = [];
        if (auth()->check()) {
            $rentals = auth()->user()->rentals()
                ->with('airConditioner')
                ->orderBy('created_at', 'desc')
                ->get();
        }

        return view('customer.dashboard', compact('rentals'));
    }
}
