@extends('layouts.customer')

@section('title', 'Pembayaran - ' . $ac->model)

@section('content')
<div class="container mt-4">
    <div class="row">
        <div class="col-md-8 mx-auto">
            <h1 class="mb-4">💳 Pembayaran</h1>

            <div class="row">
                <!-- Order Summary -->
                <div class="col-md-5">
                    <div class="card h-100">
                        <div class="card-header bg-primary text-white">
                            <h5 class="mb-0">Ringkasan Pesanan</h5>
                        </div>
                        <div class="card-body">
                            <div class="mb-3 pb-3 border-bottom">
                                <p class="text-muted small mb-1">AC Pilihan</p>
                                <h6 class="mb-0">{{ $ac->model }}</h6>
                            </div>

                            <div class="mb-3 pb-3 border-bottom">
                                <p class="text-muted small mb-1">Jumlah Unit</p>
                                <h6 class="mb-0">{{ $rentalData['quantity'] }} Unit</h6>
                            </div>

                            <div class="mb-3 pb-3 border-bottom">
                                <p class="text-muted small mb-1">Tanggal Mulai</p>
                                <h6 class="mb-0">{{ \Carbon\Carbon::parse($rentalData['rental_start'])->format('d M Y') }}</h6>
                            </div>

                            <div class="mb-3 pb-3 border-bottom">
                                <p class="text-muted small mb-1">Tanggal Akhir</p>
                                <h6 class="mb-0">{{ \Carbon\Carbon::parse($rentalData['rental_end'])->format('d M Y') }}</h6>
                            </div>

                            <div class="mb-3 pb-3 border-bottom">
                                <p class="text-muted small mb-1">Durasi Sewa</p>
                                <h6 class="mb-0">{{ abs(\Carbon\Carbon::parse($rentalData['rental_end'])->diffInDays(\Carbon\Carbon::parse($rentalData['rental_start']))) }} Hari</h6>
                            </div>

                            <div class="mb-3 pb-3 border-bottom">
                                <p class="text-muted small mb-1">Harga Per Unit/Hari</p>
                                <h6 class="mb-0">Rp {{ number_format($ac->rental_price_per_day, 0, ',', '.') }}</h6>
                            </div>

                            <div class="bg-light p-3 rounded">
                                <p class="text-muted small mb-1">Total Pembayaran</p>
                                <h3 class="text-danger mb-0">Rp {{ number_format(abs($rentalData['total_price']), 0, ',', '.') }}</h3>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Payment Method -->
                <div class="col-md-7">
                    <div class="card">
                        <div class="card-header bg-success text-white">
                            <h5 class="mb-0">Pembayaran Aman dengan Midtrans</h5>
                        </div>
                        <div class="card-body">
                            <!-- Customer Info Summary -->
                            <div class="alert alert-light mb-4">
                                <h6 class="alert-heading">👤 Data Pemesan</h6>
                                <p class="mb-1"><small><strong>Nama:</strong> {{ $rentalData['customer_name'] }}</small></p>
                                <p class="mb-1"><small><strong>Email:</strong> {{ $rentalData['customer_email'] }}</small></p>
                                <p><small><strong>Telepon:</strong> {{ $rentalData['customer_phone'] }}</small></p>
                            </div>

                            <!-- Midtrans Payment Button -->
                            <div class="alert alert-info mb-4">
                                <h6 class="alert-heading">💳 Proses Pembayaran</h6>
                                <p class="mb-0"><small>Klik tombol di bawah untuk melanjutkan ke halaman pembayaran Midtrans yang aman.</small></p>
                            </div>

                            <div class="d-grid gap-2 mb-3">
                                @if($snapToken && $rentalData['total_price'] > 0)
                                    <button 
                                        id="pay-button" 
                                        class="btn btn-success btn-lg"
                                        data-snap-token="{{ $snapToken }}"
                                        onclick="handlePayment()"
                                    >
                                        💳 Lanjutkan Pembayaran
                                    </button>
                                @else
                                    <button 
                                        class="btn btn-success btn-lg"
                                        disabled
                                        title="Tidak dapat melanjutkan pembayaran. Silakan periksa pesanan Anda."
                                    >
                                        💳 Lanjutkan Pembayaran
                                    </button>
                                    @if(!$snapToken)
                                        <div class="alert alert-danger" role="alert">
                                            <small><strong>Error:</strong> Gagal membuat token pembayaran. Silakan coba lagi atau hubungi support.</small>
                                        </div>
                                    @endif
                                    @if($rentalData['total_price'] <= 0)
                                        <div class="alert alert-danger" role="alert">
                                            <small><strong>Error:</strong> Total pembayaran tidak valid. Silakan periksa tanggal sewa Anda.</small>
                                        </div>
                                    @endif
                                @endif
                                <a href="{{ route('customer.home') }}" class="btn btn-outline-secondary">
                                    ← Kembali
                                </a>
                            </div>

                            <div class="alert alert-warning" role="alert">
                                <small>
                                    <strong>Catatan:</strong> Anda akan diarahkan ke halaman pembayaran Midtrans yang aman. 
                                    Midtrans mendukung berbagai metode pembayaran termasuk transfer bank, e-wallet, dan cicilan tanpa bunga.
                                </small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://app.sandbox.midtrans.com/snap/snap.js"></script>
<script>
console.log('Snap Token:', '{{ $snapToken }}');
console.log('Snap object available:', typeof snap !== 'undefined');

function handlePayment() {
    const snapToken = document.getElementById('pay-button').getAttribute('data-snap-token');
    
    console.log('handlePayment called with snap token:', snapToken);
    
    if (!snapToken || snapToken.trim() === '') {
        console.error('Snap token is empty or null');
        alert('Token pembayaran tidak valid. Silakan coba lagi atau hubungi support.');
        return;
    }

    if (typeof snap === 'undefined') {
        console.error('Snap object not loaded');
        alert('Midtrans library tidak berhasil dimuat. Silakan refresh halaman dan coba lagi.');
        return;
    }

    snap.pay(snapToken, {
        onSuccess: function(result) {
            console.log('Payment success callback:', result);
            // Redirect to success page with order_id (use backend order_id, not Midtrans)
            const orderId = "{{ $orderId }}";
            const successUrl = "{{ route('customer.payment-success') }}" + "?order_id=" + encodeURIComponent(orderId);
            console.log('Redirecting to:', successUrl, 'with orderId:', orderId);
            setTimeout(function() {
                window.location.href = successUrl;
            }, 500);
        },
        onPending: function(result) {
            console.log('Payment pending:', result);
            // Redirect to success page anyway - let backend verify
            const orderId = "{{ $orderId }}";
            const pendingUrl = "{{ route('customer.payment-success') }}" + "?order_id=" + encodeURIComponent(orderId);
            console.log('Pending - Redirecting to:', pendingUrl, 'with orderId:', orderId);
            setTimeout(function() {
                window.location.href = pendingUrl;
            }, 1000);
        },
        onError: function(result) {
            console.log('Payment error:', result);
            alert('Pembayaran gagal atau dibatalkan. Silakan coba lagi.');
        },
        onClose: function() {
            console.log('Payment window closed');
            // If user closes the dialog, try to check status with correct order_id
            const orderId = "{{ $orderId }}";
            setTimeout(function() {
                const successUrl = "{{ route('customer.payment-success') }}" + "?order_id=" + encodeURIComponent(orderId);
                console.log('Dialog closed - Checking payment status with orderId:', orderId);
                window.location.href = successUrl;
            }, 1000);
        }
    });
}

// Ensure snap is loaded before payment
document.addEventListener('DOMContentLoaded', function() {
    // Check if snap is loaded
    if (typeof snap === 'undefined') {
        console.warn('Snap library not loaded immediately, waiting...');
        setTimeout(function() {
            if (typeof snap === 'undefined') {
                console.error('Snap library failed to load');
                document.getElementById('pay-button').disabled = true;
                alert('Gagal memuat Midtrans. Silakan refresh halaman.');
            }
        }, 3000);
    }
});
</script>
@endpush
@endsection
