@extends('layouts.admin')

@section('title', isset($rental) ? 'Edit Pesanan Rental' : 'Buat Pesanan Rental Baru')
@section('page-title', isset($rental) ? '✏️ Edit Pesanan Rental' : '➕ Buat Pesanan Rental Baru')

@section('content')
<div class="row mb-4">
    <div class="col-md-12">
        <a href="{{ route('rentals.index') }}" class="btn btn-outline-secondary mb-3">
            <i class="fas fa-arrow-left me-2"></i>Kembali ke Daftar
        </a>
    </div>
</div>

<form action="@if(isset($rental)) {{ route('rentals.update', $rental->id) }} @else {{ route('rentals.store') }} @endif" method="POST">
    @csrf
    @if(isset($rental)) @method('PUT') @endif

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
                    <div class="mb-3">
                        <label for="customer_name" class="form-label fw-bold">Nama Pelanggan</label>
                        <input type="text" class="form-control @error('customer_name') is-invalid @enderror" id="customer_name" name="customer_name"
                            value="{{ isset($rental) ? $rental->customer_name : old('customer_name') }}" required>
                        @error('customer_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-3">
                        <label for="customer_email" class="form-label fw-bold">Email</label>
                        <input type="email" class="form-control @error('customer_email') is-invalid @enderror" id="customer_email" name="customer_email"
                            value="{{ isset($rental) ? $rental->customer_email : old('customer_email') }}" required>
                        @error('customer_email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-3">
                        <label for="customer_phone" class="form-label fw-bold">Nomor Telepon</label>
                        <input type="tel" class="form-control @error('customer_phone') is-invalid @enderror" id="customer_phone" name="customer_phone"
                            value="{{ isset($rental) ? $rental->customer_phone : old('customer_phone') }}" required>
                        @error('customer_phone') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-0">
                        <label for="customer_address" class="form-label fw-bold">Alamat</label>
                        <textarea class="form-control @error('customer_address') is-invalid @enderror" id="customer_address" name="customer_address" rows="3" required>{{ isset($rental) ? $rental->customer_address : old('customer_address') }}</textarea>
                        @error('customer_address') <div class="invalid-feedback">{{ $message }}</div> @enderror
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
                    <div class="mb-0">
                        <label for="air_conditioner_id" class="form-label fw-bold">Pilih AC Model</label>
                        <select class="form-control @error('air_conditioner_id') is-invalid @enderror" id="air_conditioner_id" name="air_conditioner_id" required>
                            <option value="">-- Pilih AC --</option>
                            @foreach($admins as $ac)
                                <option value="{{ $ac->id }}" @if(isset($rental) && $rental->air_conditioner_id == $ac->id) selected @endif>
                                    {{ $ac->model }} - Rp {{ number_format($ac->rental_price_per_day, 0, ',', '.') }}/Hari
                                </option>
                            @endforeach
                        </select>
                        @error('air_conditioner_id') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                    </div>
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
                    <div class="mb-3">
                        <label for="rental_type" class="form-label fw-bold">Tipe Rental</label>
                        <select class="form-control @error('rental_type') is-invalid @enderror" id="rental_type" name="rental_type" required>
                            <option value="">-- Pilih Tipe --</option>
                            <option value="daily" @if(isset($rental) && $rental->rental_type == 'daily') selected @endif>Harian</option>
                            <option value="monthly" @if(isset($rental) && $rental->rental_type == 'monthly') selected @endif>Bulanan</option>
                        </select>
                        @error('rental_type') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-3">
                        <label for="rental_start" class="form-label fw-bold">Tanggal & Waktu Mulai</label>
                        <input type="datetime-local" class="form-control @error('rental_start') is-invalid @enderror" id="rental_start" name="rental_start"
                            value="{{ isset($rental) ? $rental->rental_start->format('Y-m-d\TH:i') : old('rental_start') }}" required>
                        @error('rental_start') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-0">
                        <label for="rental_end" class="form-label fw-bold">Tanggal & Waktu Selesai</label>
                        <input type="datetime-local" class="form-control @error('rental_end') is-invalid @enderror" id="rental_end" name="rental_end"
                            value="{{ isset($rental) && $rental->rental_end ? $rental->rental_end->format('Y-m-d\TH:i') : old('rental_end') }}">
                        @error('rental_end') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>
            </div>
        </div>

        <!-- Informasi Pembayaran -->
        <div class="col-lg-6 mb-4">
            <div class="card">
                <div class="card-header bg-gradient" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%);">
                    <h5 class="mb-0 text-white">
                        <i class="fas fa-money-bill-wave me-2"></i>Informasi Pembayaran
                    </h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label for="total_price" class="form-label fw-bold">Total Harga (Rp)</label>
                        <input type="number" step="0.01" class="form-control @error('total_price') is-invalid @enderror" id="total_price"
                            name="total_price" value="{{ isset($rental) ? $rental->total_price : old('total_price') }}" required>
                        @error('total_price') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-0">
                        <label for="status" class="form-label fw-bold">Status Pesanan</label>
                        <select class="form-control @error('status') is-invalid @enderror" id="status" name="status" required>
                            <option value="confirmed" @if(!isset($rental) || $rental->status == 'confirmed') selected @endif>Dikonfirmasi</option>
                            <option value="completed" @if(isset($rental) && $rental->status == 'completed') selected @endif>Selesai</option>
                            <option value="cancelled" @if(isset($rental) && $rental->status == 'cancelled') selected @endif>Dibatalkan</option>
                        </select>
                        @error('status') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Catatan -->
    <div class="row mb-4">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header bg-gradient" style="background: linear-gradient(135deg, #8b5cf6 0%, #6366f1 100%);">
                    <h5 class="mb-0 text-white">
                        <i class="fas fa-sticky-note me-2"></i>Catatan Tambahan
                    </h5>
                </div>
                <div class="card-body">
                    <textarea class="form-control @error('notes') is-invalid @enderror" id="notes" name="notes" rows="3" placeholder="Masukkan catatan tambahan (opsional)">{{ isset($rental) ? $rental->notes : old('notes') }}</textarea>
                    @error('notes') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                </div>
            </div>
        </div>
    </div>

    <!-- Tombol Aksi -->
    <div class="row mb-4">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-success fw-bold">
                            <i class="fas fa-save me-2"></i>@if(isset($rental)) Update Pesanan @else Simpan Pesanan Baru @endif
                        </button>
                        <a href="{{ route('rentals.index') }}" class="btn btn-secondary fw-bold">
                            <i class="fas fa-times me-2"></i>Batal
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection
