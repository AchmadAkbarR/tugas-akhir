@extends('layouts.admin')

@section('title', 'Dashboard')
@section('page-title', '📊 Dashboard')

@section('content')
<!-- Stats Cards -->
<div class="row mb-5">
    <div class="col-md-3 col-sm-6 mb-4">
        <div class="card stat-card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <p class="text-muted mb-1">Total Barang</p>
                        <h3 class="mb-0">{{ $totalAC ?? 0 }}</h3>
                        <small class="text-muted">AC tersedia</small>
                    </div>
                    <i class="fas fa-snowflake" style="font-size: 40px;"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6 mb-4">
        <div class="card stat-card success">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <p class="text-muted mb-1">Barang Tersedia</p>
                        <h3 class="mb-0">{{ $availableAC ?? 0 }}</h3>
                        <small class="text-muted">siap disewa</small>
                    </div>
                    <i class="fas fa-check-circle" style="font-size: 40px;"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6 mb-4">
        <div class="card stat-card warning">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <p class="text-muted mb-1">Rental Aktif</p>
                        <h3 class="mb-0">{{ $activeRentals ?? 0 }}</h3>
                        <small class="text-muted">sedang disewa</small>
                    </div>
                    <i class="fas fa-hourglass-half" style="font-size: 40px;"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6 mb-4">
        <div class="card stat-card" style="border-left-color: #10b981;">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <p class="text-muted mb-1">Pendapatan Bulan Ini</p>
                        <h3 class="mb-0" style="color: #10b981; font-size: 1.6rem;">Rp {{ number_format($monthlyRevenue ?? 0, 0, ',', '.') }}</h3>
                        <small class="text-muted">total revenue</small>
                    </div>
                    <i class="fas fa-money-bill-wave" style="font-size: 40px; color: #10b981;"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Recent Data -->
<div class="row">
    <div class="col-lg-6 mb-4">
        <div class="card h-100">
            <div class="card-header bg-gradient" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                <h5 class="mb-0 text-white">
                    <i class="fas fa-box me-2"></i>Barang Terbaru
                </h5>
            </div>
            <div class="card-body">
                @forelse($recentACs ?? [] as $ac)
                    <div class="mb-3 pb-3 border-bottom">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <div>
                                <h6 class="mb-1 fw-bold">{{ $ac->model }}</h6>
                                <small class="text-muted">Serial: {{ $ac->serial_number }}</small>
                            </div>
                            <span class="badge bg-primary">Rp {{ number_format($ac->rental_price_per_day, 0, ',', '.') }}/hari</span>
                        </div>
                        <small class="text-muted">
                            <i class="fas fa-cube me-1"></i>Stok: 
                            @if($ac->stock > 0)
                                <span class="text-success fw-bold">{{ $ac->stock }} Unit</span>
                            @else
                                <span class="text-danger fw-bold">Habis</span>
                            @endif
                        </small>
                    </div>
                @empty
                    <p class="text-muted text-center py-5">
                        <i class="fas fa-inbox" style="font-size: 32px; opacity: 0.5;"></i><br>
                        Belum ada AC
                    </p>
                @endforelse
            </div>
            <div class="card-footer bg-light">
                <a href="{{ route('admin.index') }}" class="btn btn-sm btn-primary w-100">
                    <i class="fas fa-arrow-right me-1"></i>Lihat Semua
                </a>
            </div>
        </div>
    </div>

    <div class="col-lg-6 mb-4">
        <div class="card h-100">
            <div class="card-header bg-gradient" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
                <h5 class="mb-0 text-white">
                    <i class="fas fa-shopping-cart me-2"></i>Rental Terbaru
                </h5>
            </div>
            <div class="card-body">
                @forelse($recentRentals ?? [] as $rental)
                    <div class="mb-3 pb-3 border-bottom">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <div>
                                <h6 class="mb-1 fw-bold">{{ $rental->customer_name }}</h6>
                                <small class="text-muted">{{ $rental->airConditioner->model ?? 'N/A' }}</small>
                            </div>
                            @if($rental->status === 'confirmed')
                                <span class="badge bg-success">Dikonfirmasi</span>
                            @elseif($rental->status === 'completed')
                                <span class="badge bg-secondary">Selesai</span>
                            @elseif($rental->status === 'cancelled')
                                <span class="badge bg-danger">Dibatalkan</span>
                            @elseif($rental->status === 'active')
                                <span class="badge bg-info">Aktif</span>
                            @else
                                <span class="badge bg-warning">{{ ucfirst($rental->status) }}</span>
                            @endif
                        </div>
                        <small class="text-muted">
                            <i class="fas fa-money-bill-wave me-1"></i>Rp {{ number_format($rental->total_price, 0, ',', '.') }}
                        </small>
                    </div>
                @empty
                    <p class="text-muted text-center py-5">
                        <i class="fas fa-inbox" style="font-size: 32px; opacity: 0.5;"></i><br>
                        Belum ada rental
                    </p>
                @endforelse
            </div>
            <div class="card-footer bg-light">
                <a href="{{ route('rentals.index') }}" class="btn btn-sm btn-primary w-100">
                    <i class="fas fa-arrow-right me-1"></i>Lihat Semua
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="row mt-2">
    <div class="col-12">
        <div class="card" style="background: linear-gradient(135deg, rgba(102, 126, 234, 0.1) 0%, rgba(118, 75, 162, 0.1) 100%); border: 2px solid rgba(102, 126, 234, 0.2);">
            <div class="card-body">
                <h6 class="mb-3 fw-bold">
                    <i class="fas fa-rocket me-2" style="color: #667eea;"></i>Aksi Cepat
                </h6>
                <div class="d-flex gap-3 flex-wrap">
                    <a href="{{ route('admin.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i>Tambah Barang Baru
                    </a>
                    <a href="{{ route('admin.index') }}" class="btn btn-outline-primary">
                        <i class="fas fa-list me-2"></i>Kelola Barang
                    </a>
                    <a href="{{ route('rentals.index') }}" class="btn btn-outline-primary">
                        <i class="fas fa-clipboard-list me-2"></i>Kelola Rental
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
