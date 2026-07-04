@extends('layouts.customer')

@section('title', 'Checkout - ' . $admin->model)

@section('content')
<div class="container mt-4">
    <div class="row">
        <div class="col-md-8 mx-auto">
            <h1 class="mb-4">📝 Form Pemesanan AC</h1>

            <div class="row mb-4">
                <!-- AC Details -->
                <div class="col-md-5">
                    <div class="card h-100">
                        <div class="card-header bg-primary text-white">
                            <h5 class="mb-0">AC Pilihan Anda</h5>
                        </div>
                        <div class="card-body">
                            <h6 class="mb-3">{{ $admin->model }}</h6>

                            @if($admin->description)
                                <div class="mb-3">
                                    <label class="form-label text-muted small">Deskripsi</label>
                                    <p class="mb-0 small">{{ $admin->description }}</p>
                                </div>
                            @endif

                            <div class="mb-3">
                                @if($admin->stock > 0)
                                    <label class="form-label text-muted small">Stok Tersedia</label>
                                    <p class="mb-0"><span class="badge bg-success">{{ $admin->stock }} Unit</span></p>
                                @else
                                    <p class="mb-0"><span class="badge bg-danger">Stok Habis</span></p>
                                @endif
                            </div>

                            <div class="mb-3">
                                <label class="form-label text-muted small">Harga Sewa</label>
                                <p class="mb-0">
                                    <small class="text-muted">Per Hari:</small><br>
                                    <strong class="text-danger">Rp {{ number_format($admin->rental_price_per_day, 0, ',', '.') }}</strong>
                                </p>
                            </div>

                            <!-- Price Summary (Dynamic) -->
                            <div class="alert alert-warning mt-3" id="priceSummary">
                                <small class="text-muted">Total Sewa:</small><br>
                                <h5 class="text-danger mb-0">Rp <span id="totalPrice">0</span></h5>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Form -->
                <div class="col-md-7">
                    <div class="card">
                        <div class="card-header bg-success text-white">
                            <h5 class="mb-0">Data Penyewa</h5>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('customer.store-rental') }}" method="POST" id="checkoutForm">
                                @csrf

                                <input type="hidden" name="air_conditioner_id" value="{{ $admin->id }}">

                                <!-- Nama -->
                                <div class="mb-3">
                                    <label for="customer_name" class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                                    <input 
                                        type="text" 
                                        class="form-control @error('customer_name') is-invalid @enderror" 
                                        id="customer_name" 
                                        name="customer_name"
                                        value="{{ old('customer_name') }}"
                                        placeholder="Masukkan nama lengkap Anda"
                                        required>
                                    @error('customer_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Email -->
                                <div class="mb-3">
                                    <label for="customer_email" class="form-label">Email <span class="text-danger">*</span></label>
                                    <input 
                                        type="email" 
                                        class="form-control @error('customer_email') is-invalid @enderror" 
                                        id="customer_email" 
                                        name="customer_email"
                                        value="{{ old('customer_email') }}"
                                        placeholder="contoh@email.com"
                                        required>
                                    @error('customer_email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- No HP -->
                                <div class="mb-3">
                                    <label for="customer_phone" class="form-label">Nomor HP <span class="text-danger">*</span></label>
                                    <input 
                                        type="tel" 
                                        class="form-control @error('customer_phone') is-invalid @enderror" 
                                        id="customer_phone" 
                                        name="customer_phone"
                                        value="{{ old('customer_phone') }}"
                                        placeholder="08xx xxxx xxxx"
                                        required>
                                    @error('customer_phone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Alamat -->
                                <div class="mb-3">
                                    <label for="customer_address" class="form-label">Alamat Lengkap <span class="text-danger">*</span></label>
                                    <textarea 
                                        class="form-control @error('customer_address') is-invalid @enderror" 
                                        id="customer_address" 
                                        name="customer_address"
                                        rows="3"
                                        placeholder="Masukkan alamat lengkap termasuk kota dan kodepos"
                                        required>{{ old('customer_address') }}</textarea>
                                    @error('customer_address')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Tanggal Mulai Sewa -->
                                <div class="mb-3">
                                    <label for="rental_start" class="form-label">Tanggal Mulai Sewa <span class="text-danger">*</span></label>
                                    <input 
                                        type="date" 
                                        class="form-control @error('rental_start') is-invalid @enderror" 
                                        id="rental_start" 
                                        name="rental_start"
                                        value="{{ old('rental_start') }}"
                                        required>
                                    @error('rental_start')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Tanggal Akhir Sewa -->
                                <div class="mb-3">
                                    <label for="rental_end" class="form-label">Tanggal Akhir Sewa <span class="text-danger">*</span></label>
                                    <input 
                                        type="date" 
                                        class="form-control @error('rental_end') is-invalid @enderror" 
                                        id="rental_end" 
                                        name="rental_end"
                                        value="{{ old('rental_end') }}"
                                        required>
                                    <small class="text-muted" id="durationInfo">Durasi sewa akan dihitung otomatis</small>
                                    @error('rental_end')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Jumlah Unit -->
                                <div class="mb-3">
                                    <label for="quantity" class="form-label">Jumlah Unit <span class="text-danger">*</span></label>
                                    <input 
                                        type="number" 
                                        class="form-control @error('quantity') is-invalid @enderror" 
                                        id="quantity" 
                                        name="quantity"
                                        value="{{ old('quantity', 1) }}"
                                        min="1"
                                        max="{{ $admin->stock }}"
                                        placeholder="Berapa unit?"
                                        required>
                                    <small class="text-muted">Stok tersedia: {{ $admin->stock }} unit</small>
                                    @error('quantity')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Catatan -->
                                <div class="mb-3">
                                    <label for="notes" class="form-label">Catatan Tambahan (Opsional)</label>
                                    <textarea 
                                        class="form-control @error('notes') is-invalid @enderror" 
                                        id="notes" 
                                        name="notes"
                                        rows="2"
                                        placeholder="Misalnya: permintaan khusus, kondisi lokasi, dll">{{ old('notes') }}</textarea>
                                    @error('notes')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Buttons -->
                                <div class="d-grid gap-2">
                                    @if($admin->stock > 0)
                                        <button type="submit" class="btn btn-success btn-lg">
                                            ✓ Lanjutkan Pemesanan
                                        </button>
                                    @else
                                        <button type="submit" class="btn btn-success btn-lg" disabled>
                                            Stok Habis
                                        </button>
                                    @endif
                                    <a href="{{ route('customer.home') }}" class="btn btn-outline-secondary">
                                        ← Kembali
                                    </a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.getElementById('rental_start').addEventListener('change', function() {
    updatePrice();
});

document.getElementById('rental_end').addEventListener('change', function() {
    updatePrice();
});

document.getElementById('quantity').addEventListener('change', function() {
    updatePrice();
});

// Add form submit validation
document.getElementById('checkoutForm').addEventListener('submit', function(e) {
    const rentalStart = document.getElementById('rental_start').value;
    const rentalEnd = document.getElementById('rental_end').value;
    
    if (!rentalStart || !rentalEnd) {
        e.preventDefault();
        alert('Silakan pilih tanggal mulai dan tanggal akhir sewa');
        return false;
    }
    
    const startDate = new Date(rentalStart);
    const endDate = new Date(rentalEnd);
    
    if (endDate <= startDate) {
        e.preventDefault();
        alert('Tanggal akhir sewa harus lebih besar dari tanggal mulai sewa');
        return false;
    }
    
    return true;
});

function updatePrice() {
    const pricePerDay = {{ $admin->rental_price_per_day }};
    const rentalStart = document.getElementById('rental_start').value;
    const rentalEnd = document.getElementById('rental_end').value;
    const quantity = parseInt(document.getElementById('quantity').value) || 1;
    
    if (!rentalStart || !rentalEnd) {
        document.getElementById('totalPrice').textContent = '0';
        document.getElementById('durationInfo').textContent = 'Pilih tanggal mulai dan akhir untuk melihat durasi';
        return;
    }
    
    const startDate = new Date(rentalStart);
    const endDate = new Date(rentalEnd);
    
    if (endDate <= startDate) {
        document.getElementById('durationInfo').textContent = '❌ Tanggal akhir harus lebih besar dari tanggal mulai';
        document.getElementById('totalPrice').textContent = '0';
        return;
    }
    
    const rentalDays = Math.ceil((endDate - startDate) / (1000 * 60 * 60 * 24));
    const totalPrice = pricePerDay * rentalDays * quantity;
    
    document.getElementById('totalPrice').textContent = totalPrice.toLocaleString('id-ID');
    document.getElementById('durationInfo').textContent = `Durasi: ${rentalDays} hari`;
}

// Initialize price on page load
updatePrice();
</script>
@endpush
@endsection
