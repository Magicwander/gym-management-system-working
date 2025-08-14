<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }} - Admin Dashboard</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        .sidebar {
            min-height: 100vh;
            background: #fff;
            position: fixed;
            top: 0;
            left: 0;
            width: 260px;
            z-index: 1000;
            overflow-y: auto;
            border-right: 1px solid #e9ecef;
            box-shadow: 2px 0 10px rgba(0,0,0,0.05);
        }
        .sidebar .nav-link {
            color: #495057;
            padding: 0.875rem 1.5rem;
            margin: 0.125rem 0.75rem;
            border-radius: 8px;
            transition: all 0.3s ease;
            font-weight: 500;
            border-left: 3px solid transparent;
        }
        .sidebar .nav-link:hover {
            color: #007bff;
            background-color: #f8f9fa;
            border-left-color: #007bff;
        }
        .sidebar .nav-link.active {
            color: #007bff;
            background-color: rgba(0, 123, 255, 0.1);
            border-left-color: #007bff;
            font-weight: 600;
        }
        .card {
            border: 1px solid #e9ecef;
            border-radius: 12px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
            transition: all 0.3s ease;
        }
        .card:hover {
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            transform: translateY(-2px);
        }
        .stat-card {
            background: #007bff;
            color: white;
            border: none;
        }
        .stat-card-success {
            background: #28a745;
            color: white;
            border: none;
        }
        .stat-card-warning {
            background: #ffc107;
            color: #212529;
            border: none;
        }
        .stat-card-info {
            background: #17a2b8;
            color: white;
            border: none;
        }
        .main-content {
            background-color: #f8f9fa;
            min-height: 100vh;
            margin-left: 260px;
            padding: 0;
        }

        @media (max-width: 768px) {
            .sidebar {
                width: 100%;
                position: relative;
                border-right: none;
                border-bottom: 1px solid #e9ecef;
            }
            .main-content {
                margin-left: 0;
            }
        }
    </style>
</head>
<body class="font-sans antialiased">
    <!-- Sidebar -->
    <nav class="sidebar d-md-block">
        <div class="pt-3">
                    <div class="text-center mb-4 py-3" style="border-bottom: 1px solid #e9ecef;">
                        <h5 class="mb-1" style="color: #212529; font-weight: 700;">
                            Hermes Fitness
                        </h5>
                        <small style="color: #6c757d; font-weight: 500;">Admin Panel</small>
                    </div>
                    
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                                <i class="fas fa-tachometer-alt me-2"></i>
                                Dashboard
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.accounts.members*') ? 'active' : '' }}" href="{{ route('admin.accounts.members.index') }}">
                                <i class="fas fa-users me-2"></i>
                                Members
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.accounts.trainers*') ? 'active' : '' }}" href="{{ route('admin.accounts.trainers.index') }}">
                                <i class="fas fa-user-tie me-2"></i>
                                Trainers
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.payments*') ? 'active' : '' }}" href="{{ route('admin.payments.index') }}">
                                <i class="fas fa-credit-card me-2"></i>
                                Payments
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.reports*') ? 'active' : '' }}" href="{{ route('admin.reports.index') }}">
                                <i class="fas fa-chart-bar me-2"></i>
                                Reports
                            </a>
                        </li>
                        {{-- Commented out until routes are implemented
                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                <i class="fas fa-running me-2"></i>
                                Workouts
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                <i class="fas fa-dumbbell me-2"></i>
                                Exercises
                            </a>
                        </li>
                        --}}
                        <li class="nav-item mt-3">
                            <hr class="text-white-50">
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('profile.edit') }}">
                                <i class="fas fa-user-cog me-2"></i>
                                Profile
                            </a>
                        </li>
                        <li class="nav-item">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <a class="nav-link" href="{{ route('logout') }}"
                                   onclick="event.preventDefault(); this.closest('form').submit();">
                                    <i class="fas fa-sign-out-alt me-2"></i>
                                    Logout
                                </a>
                            </form>
                        </li>
                    </ul>
        </div>
    </nav>

    <!-- Main content -->
    <main class="main-content">
        <div class="container-fluid px-4">
                <div class="text-center pt-4 pb-3 mb-4"
                     style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); margin: -1rem -1rem 1rem -1rem; padding-left: 2rem !important; padding-right: 2rem !important; border-radius: 0; color: white; position: relative;">

                    <!-- Centered Logo and Branding -->
                    <div class="mb-3">
                        <img src="{{ url('frontend/images/gymlogo.png') }}" alt="Hermes Fitness"
                             style="width: 80px; height: 80px; border-radius: 50%; margin-bottom: 1rem;
                                    border: 4px solid rgba(255,255,255,0.3); box-shadow: 0 4px 15px rgba(0,0,0,0.3);">
                        <h2 class="mb-1" style="color: white; font-weight: 300; font-size: 1.8rem; letter-spacing: 1px;">
                            HERMES FITNESS
                        </h2>
                        <p class="mb-2" style="font-size: 0.9rem; color: rgba(255,255,255,0.8); font-weight: 500;">
                            Admin Panel
                        </p>
                    </div>

                    <!-- Page Title -->
                    <h1 class="h4 mb-0" style="color: rgba(255,255,255,0.9); font-weight: 600;">
                        @yield('title', 'Dashboard')
                    </h1>

                    <!-- Welcome Message - Positioned Absolutely -->
                    <div style="position: absolute; top: 1rem; right: 2rem;">
                        <span style="font-weight: 500; color: rgba(255,255,255,0.9); font-size: 0.9rem;">
                            Welcome, {{ auth()->user()->name }}
                        </span>
                    </div>
                </div>

                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if (session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

            @yield('content')
        </div>
    </main>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @yield('scripts')
</body>
</html>
