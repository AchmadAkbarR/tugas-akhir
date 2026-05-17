@extends('layouts.admin')

@section('title', 'Daftar Barang')
@section('page-title', '❄️ Daftar Barang AC')

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header bg-gradient d-flex justify-content-between align-items-center" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                <h5 class="mb-0 text-white">
                    <i class="fas fa-snowflake me-2"></i>Manajemen Barang AC
                </h5>
                <a href="{{ route('admin.create') }}" class="btn btn-light btn-sm fw-bold">
                    <i class="fas fa-plus me-1"></i> Tambah Barang
                </a>
            </div>
            <div class="card-body">
                @if($admins->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-dark">
                                <tr>
                                    <th style="width: 5%;">No</th>
                                    <th style="width: 20%;">Model AC</th>
                                    <th style="width: 12%;">Gambar</th>
                                    <th style="width: 15%;">Harga/Hari</th>
                                    <th style="width: 10%;">Stok</th>
                                    <th style="width: 15%;">Status</th>
                                    <th style="width: 23%;">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($admins as $index => $ac)
                                    <tr>
                                        <td class="fw-bold">{{ ($admins->currentPage() - 1) * $admins->perPage() + $index + 1 }}</td>
                                        <td>
                                            <strong style="color: #667eea;">{{ $ac->model }}</strong><br>
                                            <small class="text-muted">{{ $ac->serial_number }}</small>
                                        </td>
                                        <td>
                                            @if($ac->image)
                                                <img src="{{ asset('storage/' . $ac->image) }}" alt="Product" style="max-width: 70px; max-height: 70px; border-radius: 8px; object-fit: cover; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td>
                                            <strong style="color: #667eea; font-size: 1.1rem;">Rp {{ number_format($ac->rental_price_per_day, 0, ',', '.') }}</strong>
                                        </td>
                                        <td>
                                            @if($ac->stock > 0)
                                                <span class="badge bg-success fw-bold" style="padding: 8px 12px; font-size: 0.85rem;">{{ $ac->stock }} Unit</span>
                                            @else
                                                <span class="badge bg-danger fw-bold" style="padding: 8px 12px; font-size: 0.85rem;">Habis</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($ac->status === 'available')
                                                <span class="badge bg-success fw-bold" style="padding: 8px 12px;">Tersedia</span>
                                            @elseif($ac->status === 'rented')
                                                <span class="badge bg-info fw-bold" style="padding: 8px 12px;">Sedang Disewa</span>
                                            @elseif($ac->status === 'maintenance')
                                                <span class="badge bg-warning fw-bold" style="padding: 8px 12px;">Perawatan</span>
                                            @else
                                                <span class="badge bg-secondary fw-bold" style="padding: 8px 12px;">Tidak Aktif</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="btn-group btn-group-sm" role="group">
                                                <a href="{{ route('admin.show', $ac->id) }}" class="btn btn-outline-info" title="Lihat Detail">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('admin.edit', $ac->id) }}" class="btn btn-outline-warning" title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <form action="{{ route('admin.destroy', $ac->id) }}" method="POST" style="display: inline;" onclick="return confirm('Yakin ingin menghapus barang ini?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-outline-danger" title="Hapus">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center text-muted py-5">
                                            <i class="fas fa-inbox" style="font-size: 48px; opacity: 0.5;"></i>
                                            <h5 class="mt-3">Tidak ada data AC</h5>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Pagination -->
                    <div class="mt-4">
                        {{ $admins->links() }}
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="fas fa-snowflake" style="font-size: 64px; color: #ccc;"></i>
                        <h5 class="mt-4 text-muted fw-bold">Belum ada data AC</h5>
                        <p class="text-muted mb-4">Mulai dengan menambahkan AC baru ke dalam sistem</p>
                        <a href="{{ route('admin.create') }}" class="btn btn-primary btn-lg">
                            <i class="fas fa-plus me-2"></i>Tambah Barang Baru
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<style>
.table thead th {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    font-weight: 700;
    border: none;
}

.table tbody tr {
    transition: all 0.3s ease;
}

.table tbody tr:hover {
    background-color: #f8f9ff;
    box-shadow: inset 0 0 0 1px rgba(102, 126, 234, 0.1);
}

.btn-outline-info {
    color: #667eea;
    border-color: #667eea;
}

.btn-outline-info:hover {
    background-color: #667eea;
    color: white;
}

.btn-outline-warning {
    color: #ff9800;
    border-color: #ff9800;
}

.btn-outline-warning:hover {
    background-color: #ff9800;
    color: white;
}

.btn-outline-danger:hover {
    background-color: #dc3545;
    color: white;
}
</style>
@endsection
