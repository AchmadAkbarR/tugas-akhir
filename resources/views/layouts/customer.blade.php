<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'AC Rental System')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root {
            --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            --primary-color: #667eea;
            --secondary-color: #764ba2;
        }

        * {
            transition: all 0.3s ease;
        }

        html {
            scroll-behavior: smooth;
        }

        body {
            background: linear-gradient(to bottom, #f8f9fa, #f0f2f5);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .navbar {
            background: var(--primary-gradient) !important;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
            padding: 12px 0;
        }

        .navbar-brand {
            font-size: 1.3rem;
            font-weight: 700;
            letter-spacing: 0.5px;
        }

        .nav-link {
            font-weight: 500;
            margin-left: 8px;
            border-radius: 6px;
            padding: 8px 16px !important;
            position: relative;
        }

        .nav-link::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            width: 0;
            height: 2px;
            background-color: #fff;
            transition: all 0.3s ease;
            transform: translateX(-50%);
        }

        .nav-link:hover::after {
            width: calc(100% - 32px);
        }

        .nav-link:hover {
            background-color: rgba(255, 255, 255, 0.15);
        }

        main {
            min-height: calc(100vh - 200px);
            padding: 20px 0;
        }

        footer {
            background: var(--primary-gradient);
            color: #fff;
            padding: 8px 0;
            margin-top: 20px;
            position: relative;
        }

        footer h6 {
            font-weight: 700;
            margin-bottom: 4px;
            font-size: 0.85rem;
        }

        footer p {
            margin: 0;
            line-height: 1.3;
        }

        footer a:hover {
            color: #fff !important;
            text-decoration: underline;
        }

        .text-white-50 {
            opacity: 0.85;
        }

        .container-fluid {
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 15px;
        }
    </style>
    @stack('styles')
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="{{ route('customer.home') }}">
                <strong>❄️ Anugrah AC</strong>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('customer.home') }}">Home</a>
                    </li>
                    <li class="nav-item">
                        @if(auth()->check())
                            <a class="nav-link" href="{{ route('customer.dashboard') }}">
                                <i class="fas fa-user-circle me-1"></i>{{ auth()->user()->name }}
                            </a>
                        @else
                            <a class="nav-link" href="{{ route('customer.dashboard') }}">
                                <i class="fas fa-sign-in-alt me-1"></i>Login/Register
                            </a>
                        @endif
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main>
        @yield('content')
    </main>

    <!-- Chat FAB Component -->
    @include('components.chat-fab')

    <!-- Footer -->
    <footer>
        <div class="container">
            <div class="row">
                <div class="col-md-4">
                    <h6>Anugrah AC</h6>
                    <p class="small">Solusi sewa AC berkualitas untuk kebutuhan Anda</p>
                </div>
                <div class="col-md-4">
                    <h6>Menu Cepat</h6>
                    <ul class="list-unstyled small">
                        <li><a href="{{ route('customer.home') }}" class="text-white-50 text-decoration-none">Daftar Barang</a></li>
                    </ul>
                </div>
                <div class="col-md-4">
                    <h6>Kontak</h6>
                    <p class="small text-white-50 mb-0">
                        <a href="https://wa.me/1234567890" class="text-white-50 text-decoration-none">
                            <i class="fas fa-phone"></i> WhatsApp
                        </a>
                    </p>
                </div>
            </div>
            <p class="text-center small mb-0 mt-2">&copy; 2026 Anugrah AC. Semua hak dilindungi.</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>
