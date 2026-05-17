@extends('layouts.admin')

@section('title', 'Detail Rental - ' . $rental->customer_name)
@section('page-title', '📋 Detail Pesanan Rental')

@section('content')
<div class="row mb-4">
    <div class="col-md-12">
        <a href="{{ route('rentals.index') }}" class="btn btn-outline-secondary mb-3">
            <i class="fas fa-arrow-left me-2"></i>Kembali ke Daftar
        </a>
    </div>
</div>

<div class="row">
    <!-- Informasi Pelanggan -->
    <div class="col-lg-6 mb-4">
        <div class="card">
            <div class="card-header bg-gradient" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                <h5 class="mb-0 text-white">
                    <i class="fas fa-user me-2"></i>Data Pelanggan
                </h5>
            </div>
            <div class="card-body">
                <div class="mb-3 pb-3 border-bottom">
                    <label class="form-label text-muted small fw-bold">Nama Lengkap</label>
                    <p class="mb-0"><strong style="font-size: 1.1rem; color: #667eea;">{{ $rental->customer_name }}</strong></p>
                </div>
                <div class="mb-3 pb-3 border-bottom">
                    <label class="form-label text-muted small fw-bold">Email</label>
                    <p class="mb-0"><strong>{{ $rental->customer_email }}</strong></p>
                </div>
                <div class="mb-3 pb-3 border-bottom">
                    <label class="form-label text-muted small fw-bold">Nomor HP</label>
                    <p class="mb-0"><strong>{{ $rental->customer_phone }}</strong></p>
                </div>
                <div class="mb-0">
                    <label class="form-label text-muted small fw-bold">Alamat</label>
                    <p class="mb-0"><strong>{{ $rental->customer_address }}</strong></p>
                </div>
            </div>
        </div>
    </div>

    <!-- Informasi AC -->
    <div class="col-lg-6 mb-4">
        <div class="card">
            <div class="card-header bg-gradient" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
                <h5 class="mb-0 text-white">
                    <i class="fas fa-snowflake me-2"></i>AC yang Disewa
                </h5>
            </div>
            <div class="card-body">
                @if($rental->airConditioner)
                    <div class="mb-0">
                        <label class="form-label text-muted small fw-bold">Model AC</label>
                        <p class="mb-0"><strong style="font-size: 1.1rem; color: #f5576c;">{{ $rental->airConditioner->model }}</strong></p>
                    </div>
                @else
                    <p class="text-muted text-center py-3">AC tidak ditemukan</p>
                @endif
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Detail Rental -->
    <div class="col-lg-6 mb-4">
        <div class="card">
            <div class="card-header bg-gradient" style="background: linear-gradient(135deg, #ffa500 0%, #ff6b6b 100%);">
                <h5 class="mb-0 text-white">
                    <i class="fas fa-calendar-alt me-2"></i>Detail Rental
                </h5>
            </div>
            <div class="card-body">
                <div class="mb-3 pb-3 border-bottom">
                    <label class="form-label text-muted small fw-bold">Tanggal Mulai</label>
                    <p class="mb-0"><strong>{{ $rental->rental_start->format('d M Y') }}</strong><br>
                    <small class="text-muted">{{ $rental->rental_start->format('H:i:s') }}</small></p>
                </div>
                <div class="mb-3 pb-3 border-bottom">
                    <label class="form-label text-muted small fw-bold">Tanggal Selesai</label>
                    <p class="mb-0"><strong>{{ $rental->rental_end ? $rental->rental_end->format('d M Y') : '-' }}</strong><br>
                    <small class="text-muted">{{ $rental->rental_end ? $rental->rental_end->format('H:i:s') : '-' }}</small></p>
                </div>
                <div class="mb-3 pb-3 border-bottom">
                    <label class="form-label text-muted small fw-bold">Durasi Sewa</label>
                    <p class="mb-0"><strong>{{ $rental->rental_end ? abs($rental->rental_end->diffInDays($rental->rental_start)) : '-' }} Hari</strong></p>
                </div>
                <div class="mb-0">
                    <label class="form-label text-muted small fw-bold">Jumlah Unit</label>
                    <p class="mb-0"><span class="badge bg-primary fw-bold" style="padding: 8px 12px;">{{ $rental->quantity ?? 1 }} Unit</span></p>
                </div>
            </div>
        </div>
    </div>

    <!-- Informasi Pembayaran -->
    <div class="col-lg-6 mb-4">
        <div class="card">
            <div class="card-header bg-gradient" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%);">
                <h5 class="mb-0 text-white">
                    <i class="fas fa-money-bill-wave me-2"></i>Ringkasan Pembayaran
                </h5>
            </div>
            <div class="card-body">
                @if($rental->airConditioner)
                    <div class="mb-3 pb-3 border-bottom">
                        <label class="form-label text-muted small fw-bold">Harga/Hari</label>
                        <p class="mb-0"><strong>Rp {{ number_format($rental->airConditioner->rental_price_per_day, 0, ',', '.') }}</strong></p>
                    </div>
                    <div class="mb-3 pb-3 border-bottom">
                        <label class="form-label text-muted small fw-bold">Durasi Rental</label>
                        <p class="mb-0"><strong>{{ $rental->rental_end ? abs($rental->rental_end->diffInDays($rental->rental_start)) : '-' }} Hari</strong></p>
                    </div>
                    <div class="mb-3 pb-3 border-bottom">
                        <label class="form-label text-muted small fw-bold">Subtotal</label>
                        <p class="mb-0"><strong>Rp {{ number_format($rental->total_price, 0, ',', '.') }}</strong></p>
                    </div>
                @endif
                <div class="mb-3 pb-3 border-bottom">
                    <label class="form-label text-muted small fw-bold">Status Pembayaran</label>
                    <p class="mb-0">
                        @if($rental->payment_status === 'confirmed')
                            <span class="badge bg-success fw-bold" style="padding: 8px 12px;">✓ Terbayar</span>
                        @elseif($rental->payment_status === 'pending')
                            <span class="badge bg-warning fw-bold" style="padding: 8px 12px;">Tertunda</span>
                        @elseif($rental->payment_status === 'failed')
                            <span class="badge bg-danger fw-bold" style="padding: 8px 12px;">Gagal</span>
                        @else
                            <span class="badge bg-secondary fw-bold" style="padding: 8px 12px;">{{ ucfirst($rental->payment_status) }}</span>
                        @endif
                    </p>
                </div>
                <div>
                    <label class="form-label text-muted small fw-bold">Total Harga</label>
                    <h4 class="mb-0" style="color: #10b981; font-weight: 700;">Rp {{ number_format($rental->total_price, 0, ',', '.') }}</h4>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Status Rental -->
<div class="row mb-4">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header bg-gradient" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                <h5 class="mb-0 text-white">
                    <i class="fas fa-info-circle me-2"></i>Status Pesanan
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <label class="form-label text-muted small fw-bold">Status Rental</label>
                        <p class="mb-0">
                            @if($rental->status === 'confirmed')
                                <span class="badge bg-success fw-bold" style="padding: 8px 12px;">✓ Dikonfirmasi</span>
                            @elseif($rental->status === 'completed')
                                <span class="badge bg-secondary fw-bold" style="padding: 8px 12px;">Selesai</span>
                            @elseif($rental->status === 'cancelled')
                                <span class="badge bg-danger fw-bold" style="padding: 8px 12px;">Dibatalkan</span>
                            @elseif($rental->status === 'active')
                                <span class="badge bg-info fw-bold" style="padding: 8px 12px;">Aktif</span>
                            @elseif($rental->status === 'pending')
                                <span class="badge bg-warning fw-bold" style="padding: 8px 12px;">Menunggu Konfirmasi</span>
                            @else
                                <span class="badge bg-secondary fw-bold" style="padding: 8px 12px;">{{ ucfirst($rental->status) }}</span>
                            @endif
                        </p>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label text-muted small fw-bold">Dibuat pada</label>
                        <p class="mb-0"><strong>{{ $rental->created_at->format('d M Y H:i:s') }}</strong></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@if($rental->notes)
<!-- Catatan Tambahan -->
<div class="row mb-4">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header bg-gradient" style="background: linear-gradient(135deg, #8b5cf6 0%, #6366f1 100%);">
                <h5 class="mb-0 text-white">
                    <i class="fas fa-sticky-note me-2"></i>Catatan Tambahan
                </h5>
            </div>
            <div class="card-body">
                <p class="mb-0">{{ $rental->notes }}</p>
            </div>
        </div>
    </div>
</div>
@endif

<!-- Tombol Aksi -->
<div class="row mb-4">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <div class="d-flex gap-2 flex-wrap">
                    <a href="{{ route('rentals.edit', $rental->id) }}" class="btn btn-warning fw-bold">
                        <i class="fas fa-edit me-2"></i>Edit Rental
                    </a>
                    <form action="{{ route('rentals.destroy', $rental->id) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger fw-bold" onclick="return confirm('Yakin ingin menghapus pesanan ini?')">
                            <i class="fas fa-trash me-2"></i>Hapus Rental
                        </button>
                    </form>
                    <a href="{{ route('rentals.index') }}" class="btn btn-secondary fw-bold">
                        <i class="fas fa-list me-2"></i>Kembali ke Daftar
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
