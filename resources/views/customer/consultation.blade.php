@extends('layouts.customer')

@section('title', 'Konsultasi')

@section('content')
<div class="container mt-4">
    <div class="row mb-4">
        <div class="col-md-8 mx-auto">
            <h1 class="mb-4">📞 Hubungi Admin</h1>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8 mx-auto">
            <!-- WhatsApp Section -->
            <div class="card mb-4 border-success">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0">💬 Chat via WhatsApp</h5>
                </div>
                <div class="card-body text-center py-5">
                    <p class="fs-5 mb-4">Hubungi kami langsung via WhatsApp untuk konsultasi lebih lanjut!</p>
                    <p class="mb-4">
                        <strong>Respon cepat:</strong> Biasanya kami balas dalam 5-15 menit
                    </p>
                    
                    <!-- WhatsApp Button -->
                    <a href="https://wa.me/" target="_blank" class="btn btn-success btn-lg">
                        <i class="fab fa-whatsapp"></i> Chat WhatsApp
                    </a>
                    
                    <p class="text-muted mt-4 small mb-0">
                        <em>📌 Klik tombol di atas untuk membuka WhatsApp. Pastikan Anda sudah memiliki aplikasi WhatsApp.</em>
                    </p>
                </div>
            </div>

            <!-- FAQ Quick Links -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">❓ FAQ Cepat</h5>
                </div>
                <div class="card-body">
                    <p class="text-muted">Untuk pertanyaan umum, gunakan Chat Bot kami di sudut kanan bawah halaman. Bot siap menjawab semua pertanyaan Anda! 💬</p>
                </div>
            </div>

            <!-- Other Contact Methods -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">📧 Metode Kontak Lain</h5>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled">
                        <li class="mb-3">
                            <strong>WhatsApp:</strong> Klik tombol di atas 👆
                        </li>
                        <li class="mb-3">
                            <strong>Telepon:</strong> Hubungi admin secara langsung (nomor ada di WhatsApp)
                        </li>
                        <li class="mb-3">
                            <strong>Email:</strong> 
                        </li>
                        <li>
                            <strong>Kunjungi Toko:</strong> 
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Info -->
            <div class="alert alert-info" role="alert">
                <h5 class="alert-heading">💡 Tips</h5>
                <ul class="mb-0">
                    <li>Chat WhatsApp adalah cara tercepat untuk mendapat respons</li>
                    <li>Siapkan informasi AC yang ingin Anda sewa</li>
                    <li>Tanyakan tentang promo dan diskon khusus untuk sewa jangka panjang</li>
                    <li>Tim kami siap membantu Anda memilih AC yang tepat sesuai kebutuhan</li>
                </ul>
            </div>

            <!-- Back Button -->
            <div class="text-center mt-4">
                <a href="{{ route('customer.home') }}" class="btn btn-outline-secondary">
                    ← Kembali ke Home
                </a>
            </div>
        </div>
    </div>
</div>

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
@endpush
@endsection
