<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Dashboard - Hermes Fitness</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Chart.js -->
    @if($role === 'admin')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    @endif

    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg,
                @if($role === 'admin') #667eea 0%, #764ba2 100%
                @elseif($role === 'trainer') #28a745 0%, #20c997 100%
                @else #667eea 0%, #764ba2 100%
                @endif
            );
            min-height: 100vh;
        }

        .dashboard-container {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            margin: 2rem;
            padding: 2rem;
        }

        .welcome-header {
            background: linear-gradient(135deg,
                @if($role === 'admin') #667eea 0%, #764ba2 100%
                @elseif($role === 'trainer') #28a745 0%, #20c997 100%
                @else #667eea 0%, #764ba2 100%
                @endif
            );
            color: white;
            border-radius: 20px;
            padding: 3rem 2rem;
            margin-bottom: 2rem;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .welcome-header::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
            animation: pulse 4s ease-in-out infinite;
        }

        @keyframes pulse {
            0%, 100% { transform: scale(1); opacity: 0.5; }
            50% { transform: scale(1.1); opacity: 0.8; }
        }

        .welcome-header > * {
            position: relative;
            z-index: 1;
        }

        .gym-logo {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            margin: 0 auto 1.5rem auto;
            border: 6px solid rgba(255, 255, 255, 0.4);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.3);
            display: block;
            transition: transform 0.3s ease;
            object-fit: cover;
            background: rgba(255, 255, 255, 0.1);
        }

        .gym-logo:hover {
            transform: scale(1.05);
        }

        .logo-fallback {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            margin: 0 auto 1.5rem auto;
            border: 6px solid rgba(255, 255, 255, 0.4);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.3);
            display: flex;
            align-items: center;
            justify-content: center;
            background: rgba(255, 255, 255, 0.2);
            font-size: 3rem;
            color: white;
        }

        .stat-card {
            background: white;
            border-radius: 15px;
            padding: 1.5rem;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease;
            border-left: 4px solid
                @if($role === 'admin') #667eea
                @elseif($role === 'trainer') #28a745
                @else #667eea
                @endif;
        }

        .stat-card:hover {
            transform: translateY(-5px);
        }

        .stat-card.success { border-left-color: #28a745; }
        .stat-card.warning { border-left-color: #ffc107; }
        .stat-card.info { border-left-color: #17a2b8; }
        .stat-card.danger { border-left-color: #dc3545; }

        .membership-card {
            background: linear-gradient(135deg, #ffd700 0%, #ffed4e 100%);
            color: #333;
            border-radius: 15px;
            padding: 1.5rem;
            margin-bottom: 2rem;
        }

        .workout-card, .member-card, .client-card {
            background: white;
            border-radius: 15px;
            padding: 1.5rem;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            margin-bottom: 1rem;
        }

        .btn-custom {
            background: linear-gradient(135deg,
                @if($role === 'admin') #667eea 0%, #764ba2 100%
                @elseif($role === 'trainer') #28a745 0%, #20c997 100%
                @else #667eea 0%, #764ba2 100%
                @endif
            );
            border: none;
            border-radius: 10px;
            padding: 0.8rem 2rem;
            font-weight: 600;
            color: white;
            transition: all 0.3s ease;
        }

        .btn-custom:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(102, 126, 234, 0.3);
            color: white;
        }

        .navbar-custom {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
        }

        .navbar-brand {
            font-weight: 700;
            color: white !important;
        }

        .nav-link {
            color: rgba(255, 255, 255, 0.8) !important;
            font-weight: 500;
        }

        .nav-link:hover {
            color: white !important;
        }

        .role-badge {
            background: rgba(255, 255, 255, 0.2);
            color: white;
            border-radius: 20px;
            padding: 0.3rem 1rem;
            font-size: 0.8rem;
            font-weight: 600;
            text-transform: uppercase;
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-custom">
        <div class="container">
            <a class="navbar-brand" href="{{ route('dashboard') }}">
                <img src="{{ url('frontend/images/gymlogo.png') }}" alt="Hermes Fitness"
                     style="width: 25px; height: 25px; border-radius: 50%; margin-right: 8px; opacity: 0.8;">
                Hermes Fitness
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link active" href="{{ route('dashboard') }}">
                            <i class="fas fa-tachometer-alt me-1"></i>Dashboard
                        </a>
                    </li>
                    @if($role === 'admin')
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('admin.members.index') }}">
                                <i class="fas fa-users me-1"></i>Members
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('admin.trainers.index') }}">
                                <i class="fas fa-user-tie me-1"></i>Trainers
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('admin.workouts.index') }}">
                                <i class="fas fa-running me-1"></i>Workouts
                            </a>
                        </li>
                    @elseif($role === 'trainer')
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('trainer.clients') }}">
                                <i class="fas fa-users me-1"></i>My Clients
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('trainer.workouts') }}">
                                <i class="fas fa-running me-1"></i>My Workouts
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('trainer.schedule') }}">
                                <i class="fas fa-calendar me-1"></i>Schedule
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('trainer.client-progress') }}">
                                <i class="fas fa-chart-line me-1"></i>Progress
                            </a>
                        </li>
                    @else
                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                <i class="fas fa-running me-1"></i>My Workouts
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                <i class="fas fa-chart-line me-1"></i>Progress
                            </a>
                        </li>
                    @endif
                </ul>
                <ul class="navbar-nav">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                            <i class="fas fa-user me-1"></i>{{ $user->name }}
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="{{ route('profile.edit') }}">Profile</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                       onclick="event.preventDefault(); this.closest('form').submit();">
                                        Logout
                                    </a>
                                </form>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="dashboard-container">
        <!-- Welcome Header with Gym Logo -->
        <div class="welcome-header">
            <img src="{{ url('frontend/images/gymlogo.png') }}" alt="Hermes Fitness" class="gym-logo"
                 onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
            <div class="logo-fallback" style="display: none;">
                <i class="fas fa-dumbbell"></i>
            </div>
            <h2 class="mb-3" style="font-weight: 300; font-size: 2.5rem; letter-spacing: 2px;">
                HERMES FITNESS
            </h2>
            <h1 class="mb-3">
                <i class="fas fa-{{ $role === 'admin' ? 'crown' : ($role === 'trainer' ? 'user-tie' : 'fire') }} me-2"></i>
                Welcome Back, {{ $user->name }}!
            </h1>
            <div class="role-badge">{{ ucfirst($role) }}</div>
            <p class="mb-0 mt-3" style="font-size: 1.1rem; opacity: 0.9;">
                @if($role === 'admin')
                    Manage your gym operations efficiently
                @elseif($role === 'trainer')
                    Inspiring transformations, one workout at a time
                @else
                    Ready to crush your fitness goals today?
                @endif
            </p>
        </div>

        @if($role === 'member' && isset($active_membership))
            <!-- Membership Status for Members -->
            @if($active_membership)
            <div class="membership-card">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <h4><i class="fas fa-crown me-2"></i>{{ ucfirst($active_membership->type) }} Membership</h4>
                        <p class="mb-0">Valid until {{ $active_membership->end_date->format('F d, Y') }}</p>
                        <small>{{ $active_membership->end_date->diffForHumans() }}</small>
                    </div>
                    <div class="col-md-4 text-end">
                        <span class="badge bg-success fs-6">ACTIVE</span>
                    </div>
                </div>
            </div>
            @else
            <div class="alert alert-warning">
                <h5><i class="fas fa-exclamation-triangle me-2"></i>No Active Membership</h5>
                <p class="mb-0">Contact our staff to activate your membership and start your fitness journey!</p>
            </div>
            @endif
        @endif

        <!-- Statistics Cards -->
        <div class="row mb-4">
            @if($role === 'admin')
                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="stat-card">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-muted mb-1">Total Members</h6>
                                <h3 class="mb-0">{{ $stats['total_members'] }}</h3>
                            </div>
                            <i class="fas fa-users fa-2x text-primary"></i>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="stat-card success">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-muted mb-1">Active Members</h6>
                                <h3 class="mb-0">{{ $stats['active_members'] }}</h3>
                            </div>
                            <i class="fas fa-user-check fa-2x text-success"></i>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="stat-card warning">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-muted mb-1">Total Trainers</h6>
                                <h3 class="mb-0">{{ $stats['total_trainers'] }}</h3>
                            </div>
                            <i class="fas fa-user-tie fa-2x text-warning"></i>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="stat-card info">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-muted mb-1">Monthly Revenue</h6>
                                <h3 class="mb-0">Rs {{ number_format($stats['monthly_revenue'], 2) }}</h3>
                            </div>
                            <i class="fas fa-dollar-sign fa-2x text-info"></i>
                        </div>
                    </div>
                </div>
            @elseif($role === 'trainer')
                <div class="col-md-3 mb-3">
                    <div class="stat-card">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-muted mb-1">Assigned Workouts</h6>
                                <h3 class="mb-0">{{ $stats['assigned_workouts'] }}</h3>
                            </div>
                            <i class="fas fa-running fa-2x text-success"></i>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 mb-3">
                    <div class="stat-card success">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-muted mb-1">Active Clients</h6>
                                <h3 class="mb-0">{{ $stats['active_clients'] }}</h3>
                            </div>
                            <i class="fas fa-users fa-2x text-primary"></i>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 mb-3">
                    <div class="stat-card warning">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-muted mb-1">This Week</h6>
                                <h3 class="mb-0">{{ $stats['this_week_workouts'] }}</h3>
                            </div>
                            <i class="fas fa-calendar-week fa-2x text-warning"></i>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 mb-3">
                    <div class="stat-card info">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-muted mb-1">Completed</h6>
                                <h3 class="mb-0">{{ $stats['completed_workouts'] }}</h3>
                            </div>
                            <i class="fas fa-check-circle fa-2x text-info"></i>
                        </div>
                    </div>
                </div>
            @else
                <div class="col-md-3 mb-3">
                    <div class="stat-card">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-muted mb-1">Total Workouts</h6>
                                <h3 class="mb-0">{{ $stats['total_workouts'] }}</h3>
                            </div>
                            <i class="fas fa-running fa-2x text-primary"></i>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 mb-3">
                    <div class="stat-card success">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-muted mb-1">Completed</h6>
                                <h3 class="mb-0">{{ $stats['completed_workouts'] }}</h3>
                            </div>
                            <i class="fas fa-check-circle fa-2x text-success"></i>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 mb-3">
                    <div class="stat-card warning">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-muted mb-1">This Month</h6>
                                <h3 class="mb-0">{{ $stats['this_month_workouts'] }}</h3>
                            </div>
                            <i class="fas fa-calendar fa-2x text-warning"></i>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 mb-3">
                    <div class="stat-card info">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-muted mb-1">Calories Burned</h6>
                                <h3 class="mb-0">{{ $stats['total_calories'] }}</h3>
                            </div>
                            <i class="fas fa-fire fa-2x text-info"></i>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <!-- Role-specific content -->
        @if($role === 'admin')
            <!-- Admin Dashboard Content -->
            <div class="row">
                <!-- Membership Distribution Chart -->
                <div class="col-xl-6 col-lg-6">
                    <div class="card shadow mb-4">
                        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                            <h6 class="m-0 font-weight-bold text-primary">Membership Distribution</h6>
                        </div>
                        <div class="card-body">
                            <div class="chart-pie pt-4 pb-2">
                                <canvas id="membershipChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Recent Members -->
                <div class="col-xl-6 col-lg-6">
                    <div class="card shadow mb-4">
                        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                            <h6 class="m-0 font-weight-bold text-primary">Recent Members</h6>
                            <a href="{{ route('admin.members.index') }}" class="btn btn-sm btn-primary">View All</a>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>Name</th>
                                            <th>Email</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($recent_members as $member)
                                        <tr>
                                            <td>{{ $member->name }}</td>
                                            <td>{{ $member->email }}</td>
                                            <td>
                                                <span class="badge bg-{{ $member->is_active ? 'success' : 'danger' }}">
                                                    {{ $member->is_active ? 'Active' : 'Inactive' }}
                                                </span>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Workouts -->
            <div class="row">
                <div class="col-12">
                    <div class="card shadow mb-4">
                        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                            <h6 class="m-0 font-weight-bold text-primary">Recent Workouts</h6>
                            <a href="{{ route('admin.workouts.index') }}" class="btn btn-sm btn-primary">View All</a>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>Workout Name</th>
                                            <th>Member</th>
                                            <th>Trainer</th>
                                            <th>Date</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($recent_workouts as $workout)
                                        <tr>
                                            <td>{{ $workout->name }}</td>
                                            <td>{{ $workout->user->name }}</td>
                                            <td>{{ $workout->trainer ? $workout->trainer->name : 'Self-guided' }}</td>
                                            <td>{{ $workout->workout_date->format('M d, Y') }}</td>
                                            <td>
                                                <span class="badge bg-{{ $workout->status === 'completed' ? 'success' : ($workout->status === 'in_progress' ? 'warning' : 'secondary') }}">
                                                    {{ ucfirst($workout->status) }}
                                                </span>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @elseif($role === 'trainer')
            <!-- Trainer Dashboard Content -->
            <div class="row">
                <!-- Recent Workouts -->
                <div class="col-md-8">
                    <h4 class="mb-3"><i class="fas fa-clipboard-list me-2"></i>Recent Assigned Workouts</h4>
                    @forelse($recent_workouts as $workout)
                    <div class="workout-card">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <h6 class="mb-1">{{ $workout->name }}</h6>
                                <p class="text-muted mb-1">
                                    <i class="fas fa-user me-1"></i>{{ $workout->user->name }} •
                                    <i class="fas fa-calendar me-1"></i>{{ $workout->workout_date->format('M d, Y') }}
                                </p>
                                <small class="text-muted">
                                    {{ ucfirst($workout->type) }} workout
                                    @if($workout->total_duration_minutes)
                                        • {{ $workout->total_duration_minutes }} minutes
                                    @endif
                                </small>
                            </div>
                            <div class="text-end">
                                <span class="badge bg-{{ $workout->status === 'completed' ? 'success' : ($workout->status === 'in_progress' ? 'warning' : 'secondary') }} mb-2">
                                    {{ ucfirst($workout->status) }}
                                </span>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-5">
                        <i class="fas fa-clipboard-list fa-3x text-muted mb-3"></i>
                        <h5 class="text-muted">No workouts assigned yet</h5>
                        <p class="text-muted">Start creating personalized workout plans for your clients!</p>
                    </div>
                    @endforelse
                </div>

                <!-- Quick Actions & Clients -->
                <div class="col-md-4">
                    <h4 class="mb-3"><i class="fas fa-bolt me-2"></i>Quick Actions</h4>
                    <div class="d-grid gap-2 mb-4">
                        <a href="{{ route('trainer.workouts.create') }}" class="btn btn-custom">
                            <i class="fas fa-plus me-2"></i>Create Workout Plan
                        </a>
                        <a href="{{ route('trainer.clients') }}" class="btn btn-outline-primary">
                            <i class="fas fa-users me-2"></i>View All Clients
                        </a>
                        <a href="{{ route('trainer.schedule') }}" class="btn btn-outline-success">
                            <i class="fas fa-calendar-plus me-2"></i>Schedule Session
                        </a>
                        <a href="{{ route('trainer.client-progress') }}" class="btn btn-outline-info">
                            <i class="fas fa-chart-line me-2"></i>Client Progress
                        </a>
                    </div>

                    <h5><i class="fas fa-users me-2"></i>Recent Clients</h5>
                    @forelse($recent_clients as $client)
                    <div class="client-card">
                        <div class="d-flex align-items-center">
                            <div class="me-3">
                                <div class="bg-success text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                    <i class="fas fa-user"></i>
                                </div>
                            </div>
                            <div>
                                <h6 class="mb-0">{{ $client->name }}</h6>
                                <small class="text-muted">{{ $client->email }}</small>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-3">
                        <i class="fas fa-users fa-2x text-muted mb-2"></i>
                        <p class="text-muted mb-0">No clients assigned yet</p>
                    </div>
                    @endforelse
                </div>
            </div>
        @else
            <!-- Member Dashboard Content -->
            <div class="row">
                <div class="col-md-8">
                    <h4 class="mb-3"><i class="fas fa-history me-2"></i>Recent Workouts</h4>
                    @forelse($recent_workouts as $workout)
                    <div class="workout-card">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="mb-1">{{ $workout->name }}</h6>
                                <small class="text-muted">
                                    {{ $workout->workout_date->format('M d, Y') }} •
                                    {{ ucfirst($workout->type) }} •
                                    @if($workout->trainer)
                                        with {{ $workout->trainer->name }}
                                    @else
                                        Self-guided
                                    @endif
                                </small>
                            </div>
                            <span class="badge bg-{{ $workout->status === 'completed' ? 'success' : ($workout->status === 'in_progress' ? 'warning' : 'secondary') }}">
                                {{ ucfirst($workout->status) }}
                            </span>
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-5">
                        <i class="fas fa-running fa-3x text-muted mb-3"></i>
                        <h5 class="text-muted">No workouts yet</h5>
                        <p class="text-muted">Start your fitness journey by booking a session with our trainers!</p>
                        <button class="btn btn-custom">Book First Workout</button>
                    </div>
                    @endforelse
                </div>

                <div class="col-md-4">
                    <h4 class="mb-3"><i class="fas fa-bullseye me-2"></i>Quick Actions</h4>
                    <div class="d-grid gap-2">
                        <button class="btn btn-custom">
                            <i class="fas fa-plus me-2"></i>Book Workout
                        </button>
                        <button class="btn btn-outline-primary">
                            <i class="fas fa-dumbbell me-2"></i>Browse Exercises
                        </button>
                        <button class="btn btn-outline-success">
                            <i class="fas fa-chart-line me-2"></i>View Progress
                        </button>
                        <button class="btn btn-outline-info">
                            <i class="fas fa-user-edit me-2"></i>Update Profile
                        </button>
                    </div>
                </div>
            </div>
        @endif
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
    // Debug logo loading
    document.addEventListener('DOMContentLoaded', function() {
        const logo = document.querySelector('.gym-logo');
        if (logo) {
            logo.onload = function() {
                console.log('Logo loaded successfully');
            };
            logo.onerror = function() {
                console.log('Logo failed to load, showing fallback');
                this.style.display = 'none';
                const fallback = this.nextElementSibling;
                if (fallback) {
                    fallback.style.display = 'flex';
                }
            };
        }
    });
    </script>

    @if($role === 'admin' && isset($membership_stats))
    <script>
    // Membership Distribution Chart for Admin
    const ctx = document.getElementById('membershipChart').getContext('2d');
    const membershipData = @json($membership_stats);

    const labels = membershipData.map(item => item.type.charAt(0).toUpperCase() + item.type.slice(1));
    const data = membershipData.map(item => item.count);

    new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: labels,
            datasets: [{
                data: data,
                backgroundColor: ['#4e73df', '#1cc88a', '#36b9cc', '#f6c23e', '#e74a3b'],
                hoverBackgroundColor: ['#2e59d9', '#17a673', '#2c9faf', '#f4b619', '#e02d1b'],
                hoverBorderColor: "rgba(234, 236, 244, 1)",
            }],
        },
        options: {
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: true,
                    position: 'bottom'
                }
            },
            cutout: '80%',
        },
    });
    </script>
    @endif
</body>
</html>
