@extends('layouts.customer')

@section('title', 'AC Rental - Home')

@section('content')
<div class="container-fluid">
    <!-- Hero Section -->
    <div class="row mb-5 mt-4">
        <div class="col-lg-8 mx-auto text-center">
            <h1 class="display-4 fw-bold mb-3" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;">Sewa AC Berkualitas</h1>
            <p class="lead text-muted" style="font-weight: 500;">✨ Nikmati kenyamanan premium dengan AC terbaik untuk kebutuhan Anda</p>
            <p class="text-muted small" style="font-style: italic;">Harga terjangkau • Layanan terpercaya • Pengiriman cepat</p>
        </div>
    </div>

    <!-- Carousel Banner -->
    <div class="row mb-5">
        <div class="col-lg-12">
            <div id="promoCarousel" class="carousel slide" data-bs-ride="carousel" style="border-radius: 16px; overflow: hidden; box-shadow: 0 10px 40px rgba(102, 126, 234, 0.2);">
                <div class="carousel-indicators" style="bottom: 15px;">
                    <button type="button" data-bs-target="#promoCarousel" data-bs-slide-to="0" class="active" aria-current="true" style="width: 12px; height: 12px; border-radius: 50%; background-color: rgba(255,255,255, 0.8);"></button>
                    <button type="button" data-bs-target="#promoCarousel" data-bs-slide-to="1" style="width: 12px; height: 12px; border-radius: 50%; background-color: rgba(255,255,255, 0.5);"></button>
                    <button type="button" data-bs-target="#promoCarousel" data-bs-slide-to="2" style="width: 12px; height: 12px; border-radius: 50%; background-color: rgba(255,255,255, 0.5);"></button>
                    <button type="button" data-bs-target="#promoCarousel" data-bs-slide-to="3" style="width: 12px; height: 12px; border-radius: 50%; background-color: rgba(255,255,255, 0.5);"></button>
                </div>
                <div class="carousel-inner" style="overflow: hidden;">
                    <div class="carousel-item active">
                        <img src="{{ asset('images/ac1.jpeg') }}" class="d-block w-100" alt="Promo 1" style="height: 320px; object-fit: cover; object-position: center;">
                    </div>
                    <div class="carousel-item">
                        <img src="{{ asset('images/ac2.jpeg') }}" class="d-block w-100" alt="Promo 2" style="height: 320px; object-fit: cover; object-position: center;">
                    </div>
                    <div class="carousel-item">
                        <img src="{{ asset('images/ac4.jpeg') }}" class="d-block w-100" alt="Promo 4" style="height: 320px; object-fit: cover; object-position: center;">
                    </div>
                    <div class="carousel-item">
                        <img src="{{ asset('images/ac5.jpeg') }}" class="d-block w-100" alt="Promo 5" style="height: 320px; object-fit: cover; object-position: center;">
                    </div>
                </div>
                <button class="carousel-control-prev" type="button" data-bs-target="#promoCarousel" data-bs-slide="prev" style="width: 60px; background: linear-gradient(90deg, rgba(0,0,0,0.4), transparent);">
                    <span class="carousel-control-prev-icon"></span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#promoCarousel" data-bs-slide="next" style="width: 60px; background: linear-gradient(90deg, transparent, rgba(0,0,0,0.4));">
                    <span class="carousel-control-next-icon"></span>
                </button>
            </div>
        </div>
    </div>

    <!-- AC List Section -->
    <div class="row mb-4">
        <div class="col-lg-12">
            <div class="text-center mb-4">
                <h2 class="fw-bold mb-2" style="color: #333;">Daftar AC Tersedia</h2>
                <p class="text-muted">Pilih AC impian Anda dari koleksi terbaik kami</p>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            @if($admins->count() > 0)
                <div class="row g-4 justify-content-center">
                    @foreach($admins as $ac)
                        <div class="col-md-6 col-lg-4">
                            <div class="card h-100 product-card" style="border: none; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);">
                                <div class="position-relative overflow-hidden product-image-container" style="height: 280px; background-color: #f8f9fa;">
                                    @if($ac->image)
<img src="{{ asset('images/' . $ac->image) }}"
     class="card-img-top product-image w-100 h-100"
     alt="Product"
     style="object-fit:contain;
            object-position:center;
            padding:15px;">
@endif
                                    <div class="position-absolute top-0 end-0 m-3">
                                        @if($ac->stock > 0)
                                            <span class="badge bg-success" style="font-size: 0.8rem; padding: 6px 12px; border-radius: 20px;">Tersedia</span>
                                        @else
                                            <span class="badge bg-danger" style="font-size: 0.8rem; padding: 6px 12px; border-radius: 20px;">Habis</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="card-body" style="padding: 18px;">
                                    <h5 class="card-title fw-bold mb-2" style="color: #333; font-size: 1.1rem;">{{ $ac->model }}</h5>
                                    
                                    <div class="mb-3 pb-2" style="border-bottom: 1px solid #e0e0e0;">
                                        <small class="text-muted d-block mb-1">Harga Sewa/Hari</small>
                                        <h4 class="fw-bold" style="color: #667eea; margin: 0;">Rp {{ number_format($ac->rental_price_per_day, 0, ',', '.') }}</h4>
                                    </div>

                                    <div class="mb-3">
                                        <small class="text-muted d-block mb-1">Stok Tersedia</small>
                                        <span class="badge bg-light" style="color: #667eea; padding: 6px 10px;">{{ $ac->stock }} Unit</span>
                                    </div>

                                    <a href="{{ route('customer.checkout', $ac->id) }}" class="btn w-100 fw-bold" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border: none; padding: 10px; border-radius: 8px;" @if($ac->stock == 0) disabled @endif>
                                        <i class="fas fa-shopping-cart me-2"></i>Sewa Sekarang
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="alert alert-info text-center py-5" role="alert" style="border-radius: 12px; border: none; background: linear-gradient(135deg, rgba(102, 126, 234, 0.1) 0%, rgba(118, 75, 162, 0.1) 100%);">
                    <i class="fas fa-inbox text-muted mb-3" style="font-size: 3rem; display: block;"></i>
                    <h5>Maaf, tidak ada AC yang tersedia saat ini</h5>
                    <p class="mb-0 text-muted">Silakan cek kembali nanti atau hubungi kami untuk informasi lebih lanjut.</p>
                </div>
            @endif
        </div>
    </div>
</div>

<style>
.product-card {
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

.product-card:hover {
    box-shadow: 0 12px 35px rgba(102, 126, 234, 0.25) !important;
    transform: translateY(-8px);
}

/* Styling untuk container gambar produk */
.product-image-container {
    display: flex;
    align-items: center;
    justify-content: center;
    background-color: #f8f9fa;
}

.product-image {
    max-width: 100%;
    max-height: 100%;
    object-fit: contain;
    object-position: center;
    padding: 15px;
    display: block;
    transition: transform 0.3s ease;
}

.product-card:hover .product-image {
    transform: scale(1.05);
}

.btn:disabled {
    opacity: 0.6;
    cursor: not-allowed;
}

.btn:not(:disabled) {
    cursor: pointer;
}

.btn:not(:disabled):hover {
    box-shadow: 0 8px 20px rgba(102, 126, 234, 0.3);
    transform: translateY(-2px);
}
</style>
@endsection
