<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin - Anugrah AC')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root {
            --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            --primary-color: #667eea;
            --secondary-color: #764ba2;
            --sidebar-width: 280px;
        }

        * {
            transition: all 0.3s ease;
        }

        html {
            scroll-behavior: smooth;
        }
        
        body {
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            display: flex;
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        .sidebar {
            width: var(--sidebar-width);
            background: var(--primary-gradient);
            color: #fff;
            position: fixed;
            height: 100vh;
            overflow-y: auto;
            left: 0;
            top: 0;
            padding-top: 25px;
            box-shadow: 0 8px 32px rgba(102, 126, 234, 0.2);
            z-index: 1000;
        }
        
        .sidebar .brand {
            padding: 0 25px 25px;
            border-bottom: 2px solid rgba(255, 255, 255, 0.2);
            margin-bottom: 25px;
        }
        
        .sidebar .brand h6 {
            margin: 0;
            font-size: 20px;
            font-weight: 700;
            letter-spacing: 0.5px;
        }

        .sidebar .brand small {
            opacity: 0.9;
            font-size: 0.8rem;
        }
        
        .sidebar .nav-menu {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        
        .sidebar .nav-menu li {
            margin: 0;
        }
        
        .sidebar .nav-menu a {
            display: flex;
            align-items: center;
            color: rgba(255, 255, 255, 0.8);
            text-decoration: none;
            padding: 14px 25px;
            gap: 12px;
            font-weight: 500;
            position: relative;
        }

        .sidebar .nav-menu a::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            bottom: 0;
            width: 4px;
            background-color: rgba(255, 255, 255, 0.3);
            opacity: 0;
            transition: all 0.3s ease;
        }
        
        .sidebar .nav-menu a:hover {
            background-color: rgba(255, 255, 255, 0.15);
            color: #fff;
            padding-left: 32px;
        }

        .sidebar .nav-menu a:hover::before {
            opacity: 1;
        }
        
        .sidebar .nav-menu a.active {
            background-color: rgba(255, 255, 255, 0.25);
            color: #fff;
            font-weight: 600;
        }

        .sidebar .nav-menu a.active::before {
            opacity: 1;
        }
        
        .main-content {
            margin-left: var(--sidebar-width);
            width: calc(100% - var(--sidebar-width));
            display: flex;
            flex-direction: column;
        }
        
        .topbar {
            background: #fff;
            padding: 20px 35px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .topbar h5 {
            font-weight: 700;
            color: #333;
            margin: 0;
            font-size: 1.4rem;
        }
        
        .topbar-user {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .topbar-user span {
            color: #666;
            font-weight: 500;
        }

        .topbar-user img {
            border: 3px solid #667eea;
        }
        
        .content {
            padding: 35px;
            flex: 1;
            overflow-y: auto;
        }
        
        .card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.08);
            margin-bottom: 25px;
            transition: all 0.3s ease;
        }

        .card:hover {
            box-shadow: 0 12px 35px rgba(0, 0, 0, 0.12);
            transform: translateY(-2px);
        }

        .card-header {
            border-radius: 12px 12px 0 0;
            font-weight: 700;
            border: none;
        }
        
        .stat-card {
            border-left: 5px solid #667eea;
            position: relative;
            overflow: hidden;
        }

        .stat-card::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -50%;
            width: 200px;
            height: 200px;
            background: rgba(102, 126, 234, 0.05);
            border-radius: 50%;
        }

        .stat-card .card-body {
            position: relative;
            z-index: 1;
        }

        .stat-card h3 {
            color: #667eea;
            font-weight: 700;
            font-size: 2rem;
        }

        .stat-card p {
            color: #999;
            font-weight: 600;
        }
        
        .stat-card.success {
            border-left-color: #28a745;
        }

        .stat-card.success h3 {
            color: #28a745;
        }
        
        .stat-card.warning {
            border-left-color: #ffc107;
        }

        .stat-card.warning h3 {
            color: #ff9800;
        }
        
        .stat-card.danger {
            border-left-color: #dc3545;
        }

        .stat-card.danger h3 {
            color: #dc3545;
        }

        .stat-card i {
            opacity: 0.6;
            color: #667eea !important;
        }

        .stat-card.success i {
            color: #28a745 !important;
        }

        .stat-card.warning i {
            color: #ff9800 !important;
        }

        .stat-card.danger i {
            color: #dc3545 !important;
        }

        .btn-primary {
            background: var(--primary-gradient);
            border: none;
            font-weight: 600;
        }

        .btn-primary:hover {
            background: linear-gradient(135deg, #5568d3 0%, #6a3f91 100%);
            box-shadow: 0 8px 20px rgba(102, 126, 234, 0.3);
        }

        .table {
            font-weight: 500;
        }

        .table thead {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }

        .table tbody tr:hover {
            background-color: #f8f9ff;
        }

        .badge {
            font-weight: 600;
            padding: 8px 12px;
            border-radius: 20px;
        }

        /* Scrollbar styling */
        .sidebar::-webkit-scrollbar {
            width: 8px;
        }
        
        .sidebar::-webkit-scrollbar-track {
            background: rgba(255, 255, 255, 0.1);
        }
        
        .sidebar::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.3);
            border-radius: 4px;
        }

        .sidebar::-webkit-scrollbar-thumb:hover {
            background: rgba(255, 255, 255, 0.5);
        }

        .alert {
            border: none;
            border-radius: 12px;
            border-left: 5px solid;
        }

        .alert-success {
            background-color: #d4edda;
            border-color: #28a745;
            color: #155724;
        }

        .alert-danger {
            background-color: #f8d7da;
            border-color: #dc3545;
            color: #721c24;
        }

        .sidebar form button.btn-link {
            border: none !important;
            margin: 0 !important;
            padding: 14px 25px !important;
            font-weight: 500;
            color: rgba(255, 255, 255, 0.8) !important;
            transition: all 0.3s ease;
        }

        .sidebar form button.btn-link:hover {
            background-color: rgba(255, 255, 255, 0.15) !important;
            color: white !important;
            padding-left: 32px !important;
        }
        
        @media (max-width: 768px) {
            :root {
                --sidebar-width: 0;
            }
            
            .sidebar {
                display: none;
            }
            
            .main-content {
                margin-left: 0;
                width: 100%;
            }

            .content {
                padding: 20px;
            }
        }
    </style>
    @stack('styles')
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <div class="brand">
            <h6>
                <i class="fas fa-snowflake me-2"></i>Anugrah AC
            </h6>
            <small>Admin Panel</small>
        </div>
        
        <ul class="nav-menu">
            <li>
                <a href="{{ route('dashboard') }}" class="@if(Route::currentRouteName() == 'dashboard') active @endif">
                    <i class="fas fa-chart-line"></i> Dashboard
                </a>
            </li>
            <li>
                <a href="{{ route('admin.index') }}" class="@if(Str::startsWith(Route::currentRouteName(), 'admin')) active @endif">
                    <i class="fas fa-snowflake"></i> Daftar Barang
                </a>
            </li>
            <li>
                <a href="{{ route('rentals.index') }}" class="@if(Str::startsWith(Route::currentRouteName(), 'rentals')) active @endif">
                    <i class="fas fa-clipboard-list"></i> Pesanan Rental
                </a>
            </li>
            <li style="margin-top: 20px; padding: 0 25px;">
                <hr style="background-color: rgba(255, 255, 255, 0.2); margin: 0;">
            </li>
           
            <li>
                <form method="POST" action="{{ route('logout') }}" class="w-100 mb-0">
                    @csrf
                    <button type="submit" class="btn btn-link text-white text-decoration-none w-100 text-start" style="padding: 14px 25px; font-weight: 500; display: block; text-align: left;">
                        <i class="fas fa-sign-out-alt me-2"></i>Logout
                    </button>
                </form>
            </li>
        </ul>
    </div>
    
    <!-- Main Content -->
    <div class="main-content">
        <!-- Topbar -->
        <div class="topbar">
            <h5>@yield('page-title', 'Dashboard')</h5>
            <div class="topbar-user">
                <span>{{ Auth::user()->name ?? 'Admin' }}</span>
                <img src="https://ui-avatars.com/api/?name={{ Auth::user()->name ?? 'Admin' }}&background=667eea&color=fff&bold=true" alt="Avatar" class="rounded-circle" width="40">
            </div>
        </div>
        
        <!-- Page Content -->
        <div class="content">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <strong>✓ Sukses!</strong> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <strong>✕ Error!</strong> {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            
            @yield('content')
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>
