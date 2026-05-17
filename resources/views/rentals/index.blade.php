@extends('layouts.admin')

@section('title', 'Daftar Rental')
@section('page-title', '📋 Daftar Rental AC')

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header bg-gradient d-flex justify-content-between align-items-center" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
                <h5 class="mb-0 text-white">
                    <i class="fas fa-shopping-cart me-2"></i>Manajemen Rental AC
                </h5>
                <a href="{{ route('rentals.create') }}" class="btn btn-light btn-sm fw-bold">
                    <i class="fas fa-plus me-1"></i> Buat Rental Baru
                </a>
            </div>
            <div class="card-body">
                @if($rentals->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-dark">
                                <tr>
                                    <th style="width: 5%;">No</th>
                                    <th style="width: 15%;">Nama Pelanggan</th>
                                    <th style="width: 15%;">AC Model</th>
                                    <th style="width: 10%;">Qty</th>
                                    <th style="width: 12%;">Tanggal Mulai</th>
                                    <th style="width: 12%;">Tanggal Selesai</th>
                                    <th style="width: 12%;">Total Harga</th>
                                    <th style="width: 12%;">Status</th>
                                    <th style="width: 17%;">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($rentals as $index => $rental)
                                    <tr>
                                        <td class="fw-bold">{{ ($rentals->currentPage() - 1) * $rentals->perPage() + $index + 1 }}</td>
                                        <td>
                                            <strong style="color: #667eea;">{{ $rental->customer_name }}</strong><br>
                                            <small class="text-muted">{{ $rental->email ?? 'N/A' }}</small>
                                        </td>
                                        <td>
                                            <strong>{{ $rental->airConditioner->model ?? 'N/A' }}</strong><br>
                                            <small class="text-muted">{{ $rental->airConditioner->serial_number ?? 'N/A' }}</small>
                                        </td>
                                        <td>
                                            <span class="badge bg-primary fw-bold">{{ $rental->quantity ?? 1 }} Unit</span>
                                        </td>
                                        <td>
                                            {{ $rental->rental_start->format('d/m/Y') }}<br>
                                            <small class="text-muted">{{ $rental->rental_start->format('H:i') }}</small>
                                        </td>
                                        <td>
                                            @if($rental->rental_end)
                                                {{ $rental->rental_end->format('d/m/Y') }}<br>
                                                <small class="text-muted">{{ $rental->rental_end->format('H:i') }}</small>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td>
                                            <strong style="color: #f5576c; font-size: 1.1rem;">Rp {{ number_format($rental->total_price, 0, ',', '.') }}</strong>
                                        </td>
                                        <td>
                                            @if($rental->status === 'confirmed')
                                                <span class="badge bg-success fw-bold" style="padding: 8px 12px;">✓ Dikonfirmasi</span>
                                            @elseif($rental->status === 'completed')
                                                <span class="badge bg-secondary fw-bold" style="padding: 8px 12px;">Selesai</span>
                                            @elseif($rental->status === 'cancelled')
                                                <span class="badge bg-danger fw-bold" style="padding: 8px 12px;">Dibatalkan</span>
                                            @elseif($rental->status === 'active')
                                                <span class="badge bg-info fw-bold" style="padding: 8px 12px;">Aktif</span>
                                            @else
                                                <span class="badge bg-warning fw-bold" style="padding: 8px 12px;">{{ ucfirst($rental->status) }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="btn-group btn-group-sm" role="group">
                                                <a href="{{ route('rentals.show', $rental->id) }}" class="btn btn-outline-info" title="Lihat Detail">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('rentals.edit', $rental->id) }}" class="btn btn-outline-warning" title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <form action="{{ route('rentals.destroy', $rental->id) }}" method="POST" style="display: inline;" onclick="return confirm('Yakin ingin menghapus rental ini?')">
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
                                        <td colspan="9" class="text-center text-muted py-5">
                                            <i class="fas fa-inbox" style="font-size: 48px; opacity: 0.5;"></i>
                                            <h5 class="mt-3">Tidak ada data rental</h5>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Pagination -->
                    <div class="mt-4">
                        {{ $rentals->links() }}
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="fas fa-inbox" style="font-size: 48px; opacity: 0.5;"></i>
                        <h5 class="mt-3">Tidak ada data rental</h5>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
