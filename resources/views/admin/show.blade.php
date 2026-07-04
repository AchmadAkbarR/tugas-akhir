@extends('layouts.admin')

@section('title', 'Detail AC - ' . $admin->model)
@section('page-title', 'Detail AC')

@section('content')
<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">{{ $admin->model }}</h5>
                <div>
                    <a href="{{ route('admin.edit', $admin->id) }}" class="btn btn-warning btn-sm">
                        <i class="fas fa-edit"></i> Edit
                    </a>
                    <form action="{{ route('admin.destroy', $admin->id) }}" method="POST" style="display: inline;" onclick="return confirm('Yakin ingin menghapus?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm">
                            <i class="fas fa-trash"></i> Hapus
                        </button>
                    </form>
                </div>
            </div>
            <div class="card-body">
                @if($admin->image)
<img src="{{ asset('images/' . $admin->image) }}"
     alt="Product Image"
     style="max-width:300px;
            max-height:300px;
            border-radius:8px;
            object-fit:cover;">
@endif
                
                <div class="row mb-4">
                    <div class="col-md-12">
                        <h6 class="text-muted mb-2">Status</h6>
                        <p class="mb-4">
                            @if($admin->status === 'available')
                                <span class="badge bg-success">Tersedia</span>
                            @elseif($admin->status === 'rented')
                                <span class="badge bg-primary">Sedang Disewa</span>
                            @elseif($admin->status === 'maintenance')
                                <span class="badge bg-warning">Perawatan</span>
                            @else
                                <span class="badge bg-secondary">Tidak Aktif</span>
                            @endif
                        </p>

                        <h6 class="text-muted mb-2">Harga Sewa/Hari</h6>
                        <p class="mb-4"><strong>Rp {{ number_format($admin->rental_price_per_day, 0, ',', '.') }}</strong></p>

                        <h6 class="text-muted mb-2">Harga Sewa/Bulan</h6>
                        <p class="mb-4"><strong>Rp {{ number_format($admin->rental_price_per_month, 0, ',', '.') }}</strong></p>

                        <h6 class="text-muted mb-2">Stok</h6>
                        <p class="mb-4">
                            @if($admin->stock > 0)
                                <span class="badge bg-success">{{ $admin->stock }} Unit Tersedia</span>
                            @else
                                <span class="badge bg-danger">Stok Habis</span>
                            @endif
                        </p>
                    </div>
                </div>

                <hr>

                <h6 class="text-muted mb-2">Deskripsi</h6>
                <p>{{ $admin->description }}</p>

                <hr>

                <h6 class="text-muted mb-3">Informasi Sistem</h6>
                <div class="row">
                    <div class="col-md-6">
                        <small class="text-muted">Dibuat pada:</small>
                        <p><strong>{{ $admin->created_at->format('d M Y H:i') }}</strong></p>
                    </div>
                    <div class="col-md-6">
                        <small class="text-muted">Diperbarui pada:</small>
                        <p><strong>{{ $admin->updated_at->format('d M Y H:i') }}</strong></p>
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <a href="{{ route('admin.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Kembali ke Daftar
                </a>
            </div>
        </div>
    </div>

    <!-- Stats -->
    <div class="col-lg-4">
        <div class="card stat-card">
            <div class="card-body text-center">
                <h3 class="mb-0">{{ $admin->model }}</h3>
                <small class="text-muted">Model AC</small>
            </div>
        </div>

        <div class="card stat-card success">
            <div class="card-body">
                <h6 class="text-muted">Total Rental AC Ini</h6>
                <h3 class="mb-0">{{ $admin->rentals->count() ?? 0 }}</h3>
            </div>
        </div>

        <div class="card bg-light">
            <div class="card-header">
                <h6 class="mb-0">📋 Ringkasan</h6>
            </div>
            <div class="card-body small">
                <ul class="list-unstyled mb-0">
                    <li class="mb-2">
                        <strong>Kategori:</strong> {{ $admin->category->name ?? '-' }}
                    </li>
                    <li class="mb-2">
                        <strong>Tipe:</strong> {{ ucfirst($admin->type) }}
                    </li>
                    <li class="mb-2">
                        <strong>Kapasitas:</strong> {{ $admin->cooling_capacity }} BTU
                    </li>
                    <li >
                        <strong>Harga:</strong> Rp {{ number_format($admin->rental_price_per_day, 0, ',', '.') }}/hari
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection
