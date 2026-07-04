@extends('layouts.customer')

@section('title', 'Konfirmasi Pesanan')

@section('content')
@php
    // Fetch fresh data from database every time to ensure real-time timestamps
    $rental = \App\Models\Rental::with('airConditioner')->find($rental->id);
@endphp
<div class="container mt-4">
    <div class="row">
        <div class="col-md-8 mx-auto">
            <!-- Success Alert -->
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <h4 class="alert-heading">✅ Pembayaran Berhasil!</h4>
                <p>Pesanan Anda telah dikonfirmasi. Tim kami akan segera menghubungi Anda untuk koordinasi pengiriman AC.</p>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>

            <!-- Payment Status -->
            <div class="card mb-4 border-success">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0">✓ Status Pembayaran</h5>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <p class="text-muted small mb-1">Metode Pembayaran</p>
                            <h6 class="mb-3">
                                @if($rental->payment_method === 'bank_transfer')
                                    🏦 Transfer Bank
                                @else
                                    📱 E-Wallet
                                @endif
                            </h6>

                            <p class="text-muted small mb-1">Status Pembayaran</p>
                            <h6 class="mb-0">
                                <span class="badge bg-success">{{ ucfirst($rental->payment_status) }}</span>
                            </h6>
                        </div>
                        <div class="col-md-6">
                            <p class="text-muted small mb-1">Referensi Pembayaran</p>
                            <h6 class="mb-3">{{ $rental->payment_reference }}</h6>

                            <p class="text-muted small mb-1">Tanggal Pembayaran</p>
                            <h6 class="mb-0" id="payment-time"></h6>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Order Details -->
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">📋 Detail Pesanan</h5>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <p class="text-muted small mb-1">Nomor Pesanan</p>
                            <h6 class="mb-3">#{{ $rental->id }}</h6>

                            <p class="text-muted small mb-1">Tanggal Pemesanan</p>
                            <h6 class="mb-3" id="order-time"></h6>

                            <p class="text-muted small mb-1">Status Pesanan</p>
                            <h6 class="mb-0">
                                <span class="badge bg-info">{{ ucfirst($rental->status) }}</span>
                            </h6>
                        </div>
                        <div class="col-md-6">
                            <p class="text-muted small mb-1">AC yang Disewa</p>
                            <h6 class="mb-3">{{ $rental->airConditioner->model }}</h6>

                            <p class="text-muted small mb-1">Jumlah Unit</p>
                            <h6 class="mb-3">{{ $rental->quantity ?? 1 }} Unit</h6>

                            <p class="text-muted small mb-1">Durasi Sewa</p>
                            <h6 class="mb-3">{{ abs($rental->rental_end->diffInDays($rental->rental_start)) }} Hari</h6>

                            <p class="text-muted small mb-1">Total Harga</p>
                            <h6 class="mb-0 text-danger">
                                <strong>Rp {{ number_format($rental->total_price, 0, ',', '.') }}</strong>
                            </h6>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Customer Details -->
            <div class="card mb-4">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0">👤 Data Penyewa</h5>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <p class="text-muted small mb-1">Nama</p>
                            <h6 class="mb-3">{{ $rental->customer_name }}</h6>

                            <p class="text-muted small mb-1">Email</p>
                            <h6 class="mb-3">
                                <a href="mailto:{{ $rental->customer_email }}">{{ $rental->customer_email }}</a>
                            </h6>
                        </div>
                        <div class="col-md-6">
                            <p class="text-muted small mb-1">Nomor HP</p>
                            <h6 class="mb-3">
                                <a href="tel:{{ str_replace(' ', '', $rental->customer_phone) }}">{{ $rental->customer_phone }}</a>
                            </h6>

                            <p class="text-muted small mb-1">Kota/Alamat Pengiriman</p>
                            <h6 class="mb-0">{{ $rental->customer_address }}</h6>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Rental Period -->
            <div class="card mb-4">
                <div class="card-header bg-secondary text-white">
                    <h5 class="mb-0">📅 Periode Sewa</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <p class="text-muted small mb-1">Tanggal Mulai</p>
                            <h6 class="mb-0">{{ $rental->rental_start->format('d M Y') }}</h6>
                        </div>
                        <div class="col-md-6">
                            <p class="text-muted small mb-1">Tanggal Berakhir</p>
                            <h6 class="mb-0">{{ $rental->rental_end->format('d M Y') }}</h6>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Buttons -->
            <div class="d-grid gap-2 mb-4">
                <a href="{{ route('customer.dashboard') }}" class="btn btn-success btn-lg">
                    📋 Lihat Riwayat Sewa Saya
                </a>
                <a href="{{ route('customer.consultation') }}" class="btn btn-primary">
                    💬 Hubungi Admin via WhatsApp
                </a>
                <a href="{{ route('customer.home') }}" class="btn btn-outline-secondary">
                    ← Kembali ke Home
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Real-time Status Display -->


@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Nama hari dan bulan dalam Bahasa Indonesia
    const hariId = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
    const bulanId = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
    
    function formatDateTimeId(date) {
        const day = String(date.getDate()).padStart(2, '0');
        const month = bulanId[date.getMonth()];
        const year = date.getFullYear();
        const hours = String(date.getHours()).padStart(2, '0');
        const minutes = String(date.getMinutes()).padStart(2, '0');
        const seconds = String(date.getSeconds()).padStart(2, '0');
        return `${day} ${month} ${year} ${hours}:${minutes}:${seconds}`;
    }
    
    // Tampilkan waktu SEKARANG saat page dimuat (HANYA SEKALI, TIDAK BERUBAH)
    const now = new Date();
    
    // Set Tanggal Pembayaran
    document.getElementById('payment-time').textContent = formatDateTimeId(now);
    
    // Set Tanggal Pemesanan
    document.getElementById('order-time').textContent = formatDateTimeId(now);
    
    // Set status updated dengan live clock (hanya jam:menit:detik yang update)
    function updateStatusTime() {
        const currentTime = new Date();
        const hours = String(currentTime.getHours()).padStart(2, '0');
        const minutes = String(currentTime.getMinutes()).padStart(2, '0');
        const seconds = String(currentTime.getSeconds()).padStart(2, '0');
        document.getElementById('status-updated').textContent = `${hours}:${minutes}:${seconds}`;
    }
    
    updateStatusTime();
    setInterval(updateStatusTime, 1000);
});
</script>
@endpush

@endsection
