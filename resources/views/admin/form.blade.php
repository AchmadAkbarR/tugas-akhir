@extends('layouts.admin')

@section('title', isset($admin) ? 'Edit Barang - ' . $admin->model : 'Tambah Barang Baru')
@section('page-title', isset($admin) ? 'Edit Barang' : 'Tambah Barang Baru')

@section('content')
<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">{{ isset($admin) ? 'Edit Barang' : 'Tambah Barang Baru' }}</h5>
            </div>
            <div class="card-body">
                <form action="@if(isset($admin)) {{ route('admin.update', $admin->id) }} @else {{ route('admin.store') }} @endif" method="POST" enctype="multipart/form-data">
                    @csrf
                    @if(isset($admin)) @method('PUT') @endif

                    <!-- Nama Barang -->
                    <div class="mb-3">
                        <label for="model" class="form-label">Nama Barang <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('model') is-invalid @enderror" id="model" name="model" 
                            value="{{ old('model', $admin->model ?? '') }}" placeholder="Misal: AC Split 1 PK Inverter" required>
                        @error('model') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <!-- Deskripsi -->
                    <div class="mb-3">
                        <label for="description" class="form-label">Deskripsi <span class="text-danger">*</span></label>
                        <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" 
                            rows="4" placeholder="Deskripsikan produk, fitur, dan spesifikasi" required>{{ old('description', $admin->description ?? '') }}</textarea>
                        @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <!-- Harga -->
                    <div class="mb-3">
                        <label for="rental_price_per_day" class="form-label">Harga (Rp) <span class="text-danger">*</span></label>
                        <input type="number" class="form-control @error('rental_price_per_day') is-invalid @enderror" id="rental_price_per_day" 
                            name="rental_price_per_day" value="{{ old('rental_price_per_day', $admin->rental_price_per_day ?? '') }}" placeholder="Misal: 100000" step="1000" required>
                        @error('rental_price_per_day') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <!-- Stok -->
                    <div class="mb-3">
                        <label for="stock" class="form-label">Stok <span class="text-danger">*</span></label>
                        <input type="number" class="form-control @error('stock') is-invalid @enderror" id="stock" 
                            name="stock" value="{{ old('stock', $admin->stock ?? 0) }}" placeholder="Jumlah stok tersedia" min="0" required>
                        @error('stock') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="mb-3">
                        <label for="image" class="form-label">Gambar Barang</label>
                        <input type="file" class="form-control @error('image') is-invalid @enderror" id="image" name="image" accept="image/*">
                        <small class="text-muted d-block mt-1">Format: JPG, PNG, WEBP (Max 2MB)</small>
                        @if(isset($admin) && $admin->image)
                            <div class="mt-2">
                                <img src="{{ asset('storage/' . $admin->image) }}" alt="Current Image" style="max-width: 150px; border-radius: 8px;">
                            </div>
                        @endif
                        @error('image') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <!-- Buttons -->
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> {{ isset($admin) ? 'Perbarui' : 'Simpan' }}
                        </button>
                        <a href="{{ route('admin.index') }}" class="btn btn-secondary">
                            <i class="fas fa-times"></i> Batal
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Info Box -->
    <div class="col-lg-4">
        <div class="card bg-light">
            <div class="card-header">
                <h6 class="mb-0">💡 Tips</h6>
            </div>
            <div class="card-body small">
                <ul class="mb-0">
                    <li>Isi nama barang dengan deskripsi singkat</li>
                    <li>Deskripsikan fitur dan keunggulan produk</li>
                    <li>Pastikan harga sudah termasuk semua biaya</li>
                    <li>Upload gambar produk yang menarik</li>
                    <li>Gunakan tanda koma untuk pemisah ribuan jika perlu</li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection
