@extends('layouts.customer')

@section('title', auth()->check() ? 'Dashboard - ' . auth()->user()->name : 'Login/Register')

@section('content')
<div class="container mt-5">
    @if(auth()->check())
        <!-- Header Section -->
        <div class="row mb-5">
            <div class="col-lg-12">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h1 class="display-6 mb-0">👋 Selamat Datang, {{ auth()->user()->name }}!</h1>
                        <p class="text-muted mt-2">Email: {{ auth()->user()->email }}</p>
                    </div>
                    <div>
                        <a href="{{ route('customer.home') }}" class="btn btn-outline-primary me-2">
                            <i class="fas fa-shopping-bag me-2"></i>Sewa AC Lagi
                        </a>
                        <form method="POST" action="{{ route('logout') }}" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-outline-danger">
                                <i class="fas fa-sign-out-alt me-2"></i>Logout
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Rental History Section -->
        <div class="row">
            <div class="col-lg-12">
                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white py-3">
                        <h5 class="mb-0">
                            <i class="fas fa-history me-2"></i>Riwayat Pemesanan Saya
                        </h5>
                    </div>
                    <div class="card-body">
                        @if($rentals->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead class="table-light">
                                        <tr>
                                            <th>No</th>
                                            <th>Tipe AC</th>
                                            <th>Jumlah Unit</th>
                                            <th>Nama Penerima</th>
                                            <th>Tanggal Mulai</th>
                                            <th>Tanggal Selesai</th>
                                            <th>Total Harga</th>
                                            <th>Pembayaran</th>
                                            <th>Status</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($rentals as $index => $rental)
                                            <tr>
                                                <td>{{ $index + 1 }}</td>
                                                <td>
                                                    <strong>{{ $rental->airConditioner->model ?? 'N/A' }}</strong>
                                                </td>
                                                <td>
                                                    <strong>{{ $rental->quantity ?? 1 }} Unit</strong>
                                                </td>
                                                <td>{{ $rental->customer_name }}</td>
                                                <td>{{ $rental->rental_start->format('d M Y H:i') }}</td>
                                                <td>{{ $rental->rental_end->format('d M Y H:i') }}</td>
                                                <td>
                                                    <strong class="text-danger">
                                                        Rp {{ number_format($rental->total_price, 0, ',', '.') }}
                                                    </strong>
                                                </td>
                                                <td>
                                                    @if($rental->payment_status === 'confirmed')
                                                        <span class="badge bg-success">✓ Terbayar</span>
                                                    @elseif($rental->payment_status === 'pending')
                                                        <span class="badge bg-warning">Tertunda</span>
                                                    @elseif($rental->payment_status === 'failed')
                                                        <span class="badge bg-danger">Gagal</span>
                                                    @else
                                                        <span class="badge bg-secondary">{{ ucfirst($rental->payment_status) }}</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if($rental->status === 'confirmed')
                                                        <span class="badge bg-success">Dikonfirmasi</span>
                                                    @elseif($rental->status === 'completed')
                                                        <span class="badge bg-secondary">Selesai</span>
                                                    @elseif($rental->status === 'cancelled')
                                                        <span class="badge bg-danger">Dibatalkan</span>
                                                    @else
                                                        <span class="badge bg-info">{{ ucfirst($rental->status) }}</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <button type="button" class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#detailModal{{ $rental->id }}">
                                                        <i class="fas fa-eye"></i> Detail
                                                    </button>
                                                </td>
                                            </tr>

                                            <!-- Detail Modal -->
                                            <div class="modal fade" id="detailModal{{ $rental->id }}" tabindex="-1">
                                                <div class="modal-dialog modal-lg">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title">Detail Pemesanan #{{ $rental->id }}</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <div class="row mb-3">
                                                                <div class="col-md-6">
                                                                    <h6 class="text-muted">Informasi AC</h6>
                                                                    <p class="mb-1"><strong>Model:</strong> {{ $rental->airConditioner->model ?? 'N/A' }}</p>
                                                                    <p class="mb-1"><strong>Tipe:</strong> {{ ucfirst($rental->airConditioner->type ?? 'N/A') }}</p>
                                                                    @if($rental->airConditioner->description)
                                                                        <p><strong>Deskripsi:</strong> {{ $rental->airConditioner->description }}</p>
                                                                    @endif
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <h6 class="text-muted">Informasi Penerima</h6>
                                                                    <p class="mb-1"><strong>Nama:</strong> {{ $rental->customer_name }}</p>
                                                                    <p class="mb-1"><strong>Email:</strong> {{ $rental->customer_email }}</p>
                                                                    <p class="mb-1"><strong>Telepon:</strong> {{ $rental->customer_phone }}</p>
                                                                    <p><strong>Alamat:</strong> {{ $rental->customer_address }}</p>
                                                                </div>
                                                            </div>

                                                            <hr>

                                                            <div class="row mb-3">
                                                                <div class="col-md-6">
                                                                    <h6 class="text-muted">Periode Rental</h6>
                                                                    <p class="mb-1"><strong>Mulai:</strong> {{ $rental->rental_start->format('d M Y H:i') }}</p>
                                                                    <p class="mb-1"><strong>Selesai:</strong> {{ $rental->rental_end->format('d M Y H:i') }}</p>
                                                                    <p><strong>Tipe:</strong> {{ ucfirst($rental->rental_type) }}</p>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <h6 class="text-muted">Detail Pesanan</h6>
                                                                    <p class="mb-1"><strong>Jumlah Unit:</strong> {{ $rental->quantity ?? 1 }}</p>
                                                                    <p><strong>Total Harga:</strong><br><span class="text-danger h5">Rp {{ number_format($rental->total_price, 0, ',', '.') }}</span></p>
                                                                </div>
                                                            </div>

                                                            <hr>

                                                            <div class="row mb-3">
                                                                <div class="col-md-6">
                                                                    <h6 class="text-muted">Status Pembayaran</h6>
                                                                    <p class="mb-1"><strong>Metode:</strong>
                                                                        @if($rental->payment_method === 'bank_transfer')
                                                                            🏦 Transfer Bank
                                                                        @elseif($rental->payment_method === 'e_wallet')
                                                                            📱 E-Wallet
                                                                        @else
                                                                            {{ ucfirst($rental->payment_method) }}
                                                                        @endif
                                                                    </p>
                                                                    <p><strong>Status:</strong>
                                                                        @if($rental->payment_status === 'confirmed')
                                                                            <span class="badge bg-success">Terbayar</span>
                                                                        @elseif($rental->payment_status === 'pending')
                                                                            <span class="badge bg-warning">Tertunda</span>
                                                                        @else
                                                                            <span class="badge bg-danger">Gagal</span>
                                                                        @endif
                                                                    </p>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <h6 class="text-muted">Status Pesanan</h6>
                                                                    <p class="mb-1"><strong>Referensi:</strong> {{ $rental->payment_reference }}</p>
                                                                    <p><strong>Status:</strong>
                                                                        @if($rental->status === 'confirmed')
                                                                            <span class="badge bg-success">Dikonfirmasi</span>
                                                                        @elseif($rental->status === 'completed')
                                                                            <span class="badge bg-secondary">Selesai</span>
                                                                        @elseif($rental->status === 'cancelled')
                                                                            <span class="badge bg-danger">Dibatalkan</span>
                                                                        @else
                                                                            <span class="badge bg-info">{{ ucfirst($rental->status) }}</span>
                                                                        @endif
                                                                    </p>
                                                                </div>
                                                            </div>

                                                            @if($rental->notes)
                                                                <hr>
                                                                <div>
                                                                    <h6 class="text-muted">Catatan Tambahan</h6>
                                                                    <p>{{ $rental->notes }}</p>
                                                                </div>
                                                            @endif
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-center py-5">
                                <i class="fas fa-inbox" style="font-size: 48px; color: #ccc;"></i>
                                <p class="mt-3 text-muted">Anda belum memiliki riwayat pemesanan.</p>
                                <a href="{{ route('customer.home') }}" class="btn btn-primary mt-2">
                                    <i class="fas fa-shopping-bag me-2"></i>Mulai Sewa AC Sekarang
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @else
        <!-- Login & Register Section -->
        <div class="row">
            <div class="col-lg-5 mx-auto">
                <!-- Nav Tabs for Login/Register -->
                <ul class="nav nav-tabs mb-4" role="tablist" style="border-bottom: 2px solid #dee2e6;">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="login-tab" data-bs-toggle="tab" data-bs-target="#login" type="button" role="tab" aria-controls="login" aria-selected="true">
                            <i class="fas fa-sign-in-alt me-2"></i>Login
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="register-tab" data-bs-toggle="tab" data-bs-target="#register" type="button" role="tab" aria-controls="register" aria-selected="false">
                            <i class="fas fa-user-plus me-2"></i>Register
                        </button>
                    </li>
                </ul>

                <!-- Tab Content -->
                <div class="tab-content">
                    <!-- Login Form -->
                    <div class="tab-pane fade show active" id="login" role="tabpanel" aria-labelledby="login-tab">
                        <div class="card shadow-sm border-0">
                            <div class="card-body p-4">
                                <h5 class="card-title mb-4">Masuk ke Akun Anda</h5>

                                @if ($errors->any())
                                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                        @foreach ($errors->all() as $error)
                                            <div>{{ $error }}</div>
                                        @endforeach
                                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                    </div>
                                @endif

                                <form action="{{ route('login.post') }}" method="POST">
                                    @csrf

                                    <div class="mb-3">
                                        <label for="email" class="form-label">Email</label>
                                        <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                            id="email" name="email" value="{{ old('email') }}" 
                                            placeholder="email@example.com" required>
                                        @error('email')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label for="password" class="form-label">Password</label>
                                        <input type="password" class="form-control @error('password') is-invalid @enderror" 
                                            id="password" name="password" placeholder="••••••••" required>
                                        @error('password')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <button type="submit" class="btn btn-primary w-100">
                                        <i class="fas fa-sign-in-alt me-2"></i>Login
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Register Form -->
                    <div class="tab-pane fade" id="register" role="tabpanel" aria-labelledby="register-tab">
                        <div class="card shadow-sm border-0">
                            <div class="card-body p-4">
                                <h5 class="card-title mb-4">Buat Akun Baru</h5>

                                @if ($errors->any())
                                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                        @foreach ($errors->all() as $error)
                                            <div>{{ $error }}</div>
                                        @endforeach
                                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                    </div>
                                @endif

                                <form action="{{ route('register.post') }}" method="POST">
                                    @csrf

                                    <div class="mb-3">
                                        <label for="name" class="form-label">Nama Lengkap</label>
                                        <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                            id="name" name="name" value="{{ old('name') }}" 
                                            placeholder="John Doe" required>
                                        @error('name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label for="email_register" class="form-label">Email</label>
                                        <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                            id="email_register" name="email" value="{{ old('email') }}" 
                                            placeholder="email@example.com" required>
                                        @error('email')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label for="password_register" class="form-label">Password</label>
                                        <input type="password" class="form-control @error('password') is-invalid @enderror" 
                                            id="password_register" name="password" placeholder="••••••••" required>
                                        @error('password')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label for="password_confirmation" class="form-label">Konfirmasi Password</label>
                                        <input type="password" class="form-control" 
                                            id="password_confirmation" name="password_confirmation" 
                                            placeholder="••••••••" required>
                                    </div>

                                    <button type="submit" class="btn btn-success w-100">
                                        <i class="fas fa-user-plus me-2"></i>Register
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Back to Home Link -->
                <div class="text-center mt-4">
                    <p class="text-muted mb-3"></p>
                    <a href="{{ route('customer.home') }}" class="btn btn-outline-secondary me-2">
                        <i class="fas fa-arrow-left me-2"></i>Kembali ke Beranda
                    </a>
                    <a href="{{ route('admin.login') }}" class="btn btn-outline-warning">
                        <i class="fas fa-lock me-2"></i>Admin Login
                    </a>
                </div>
            </div>
        </div>
    @endif
</div>

<style>
    .display-6 {
        font-weight: 700;
        color: #2c3e50;
    }

    .table-hover tbody tr:hover {
        background-color: #f8f9fa;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Get tab parameter from URL
        const urlParams = new URLSearchParams(window.location.search);
        const tab = urlParams.get('tab');
        
        if (tab === 'register') {
            // Activate register tab
            const registerTab = document.getElementById('register-tab');
            if (registerTab) {
                const tabInstance = new bootstrap.Tab(registerTab);
                tabInstance.show();
            }
        }
    });
</script>
@endsection
