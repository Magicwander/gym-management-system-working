<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Trainer Dashboard - Hermes Fitness</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
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
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            color: white;
            border-radius: 15px;
            padding: 2rem;
            margin-bottom: 2rem;
            text-align: center;
        }
        
        .stat-card {
            background: white;
            border-radius: 15px;
            padding: 1.5rem;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease;
            border-left: 4px solid #28a745;
        }
        
        .stat-card:hover {
            transform: translateY(-5px);
        }
        
        .stat-card.primary {
            border-left-color: #007bff;
        }
        
        .stat-card.warning {
            border-left-color: #ffc107;
        }
        
        .stat-card.info {
            border-left-color: #17a2b8;
        }
        
        .client-card {
            background: white;
            border-radius: 15px;
            padding: 1.5rem;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            margin-bottom: 1rem;
            border-left: 4px solid #28a745;
        }
        
        .workout-card {
            background: white;
            border-radius: 15px;
            padding: 1.5rem;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            margin-bottom: 1rem;
        }
        
        .btn-custom {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            border: none;
            border-radius: 10px;
            padding: 0.8rem 2rem;
            font-weight: 600;
            color: white;
            transition: all 0.3s ease;
        }
        
        .btn-custom:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(40, 167, 69, 0.3);
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
        
        .trainer-badge {
            background: linear-gradient(135deg, #ffd700 0%, #ffed4e 100%);
            color: #333;
            border-radius: 10px;
            padding: 0.5rem 1rem;
            font-weight: 600;
            display: inline-block;
            margin-bottom: 1rem;
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-custom">
        <div class="container">
            <a class="navbar-brand" href="#">
                <i class="fas fa-dumbbell me-2"></i>Hermes Fitness
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link active" href="{{ route('trainer.dashboard') }}">
                            <i class="fas fa-tachometer-alt me-1"></i>Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">
                            <i class="fas fa-users me-1"></i>My Clients
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">
                            <i class="fas fa-calendar me-1"></i>Schedule
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">
                            <i class="fas fa-dumbbell me-1"></i>Exercises
                        </a>
                    </li>
                </ul>
                <ul class="navbar-nav">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                            <i class="fas fa-user-tie me-1"></i>{{ auth()->user()->name }}
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
        <!-- Welcome Header -->
        <div class="welcome-header">
            <div class="trainer-badge">
                <i class="fas fa-star me-2"></i>Professional Trainer
            </div>
            <h1><i class="fas fa-user-tie me-2"></i>Welcome, Coach {{ auth()->user()->name }}!</h1>
            <p class="mb-0">Inspiring transformations, one workout at a time</p>
        </div>

        <!-- Statistics Cards -->
        <div class="row mb-4">
            <div class="col-md-3 mb-3">
                <div class="stat-card">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-1">Assigned Workouts</h6>
                            <h3 class="mb-0">{{ auth()->user()->assignedWorkouts->count() }}</h3>
                        </div>
                        <i class="fas fa-running fa-2x text-success"></i>
                    </div>
                </div>
            </div>
            
            <div class="col-md-3 mb-3">
                <div class="stat-card primary">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-1">Active Clients</h6>
                            <h3 class="mb-0">{{ auth()->user()->assignedWorkouts->pluck('user_id')->unique()->count() }}</h3>
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
                            <h3 class="mb-0">{{ auth()->user()->assignedWorkouts->where('workout_date', '>=', now()->startOfWeek())->count() }}</h3>
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
                            <h3 class="mb-0">{{ auth()->user()->assignedWorkouts->where('status', 'completed')->count() }}</h3>
                        </div>
                        <i class="fas fa-check-circle fa-2x text-info"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Recent Workouts -->
            <div class="col-md-8">
                <h4 class="mb-3"><i class="fas fa-clipboard-list me-2"></i>Recent Assigned Workouts</h4>
                @forelse(auth()->user()->assignedWorkouts->take(5) as $workout)
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
                            <br>
                            <div class="btn-group btn-group-sm">
                                <button class="btn btn-outline-primary btn-sm">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <button class="btn btn-outline-success btn-sm">
                                    <i class="fas fa-edit"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                @empty
                <div class="text-center py-5">
                    <i class="fas fa-clipboard-list fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted">No workouts assigned yet</h5>
                    <p class="text-muted">Start creating personalized workout plans for your clients!</p>
                    <button class="btn btn-custom">Create First Workout</button>
                </div>
                @endforelse
            </div>
            
            <!-- Quick Actions & Clients -->
            <div class="col-md-4">
                <h4 class="mb-3"><i class="fas fa-bolt me-2"></i>Quick Actions</h4>
                <div class="d-grid gap-2 mb-4">
                    <button class="btn btn-custom">
                        <i class="fas fa-plus me-2"></i>Create Workout Plan
                    </button>
                    <button class="btn btn-outline-primary">
                        <i class="fas fa-users me-2"></i>View All Clients
                    </button>
                    <button class="btn btn-outline-success">
                        <i class="fas fa-calendar-plus me-2"></i>Schedule Session
                    </button>
                    <button class="btn btn-outline-info">
                        <i class="fas fa-chart-line me-2"></i>Client Progress
                    </button>
                </div>
                
                <h5><i class="fas fa-users me-2"></i>Recent Clients</h5>
                @php
                    $recentClients = auth()->user()->assignedWorkouts->pluck('user')->unique('id')->take(3);
                @endphp
                @forelse($recentClients as $client)
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
                
                <div class="mt-4">
                    <h5><i class="fas fa-trophy me-2"></i>Trainer Stats</h5>
                    <div class="row text-center">
                        <div class="col-6">
                            <div class="border rounded p-2">
                                <h4 class="text-success mb-0">{{ auth()->user()->assignedWorkouts->where('status', 'completed')->count() }}</h4>
                                <small class="text-muted">Completed</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="border rounded p-2">
                                <h4 class="text-primary mb-0">{{ auth()->user()->assignedWorkouts->pluck('user_id')->unique()->count() }}</h4>
                                <small class="text-muted">Clients</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
