<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Member Dashboard - Hermes Fitness</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
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
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
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
            border-left: 4px solid #667eea;
        }
        
        .stat-card:hover {
            transform: translateY(-5px);
        }
        
        .stat-card.success {
            border-left-color: #28a745;
        }
        
        .stat-card.warning {
            border-left-color: #ffc107;
        }
        
        .stat-card.info {
            border-left-color: #17a2b8;
        }
        
        .membership-card {
            background: linear-gradient(135deg, #ffd700 0%, #ffed4e 100%);
            color: #333;
            border-radius: 15px;
            padding: 1.5rem;
            margin-bottom: 2rem;
        }
        
        .workout-card {
            background: white;
            border-radius: 15px;
            padding: 1.5rem;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            margin-bottom: 1rem;
        }
        
        .btn-custom {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
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
                        <a class="nav-link active" href="{{ route('member.dashboard') }}">
                            <i class="fas fa-tachometer-alt me-1"></i>Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('member.workouts') }}">
                            <i class="fas fa-running me-1"></i>My Workouts
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('member.progress') }}">
                            <i class="fas fa-chart-line me-1"></i>Progress
                        </a>
                    </li>
                </ul>
                <ul class="navbar-nav">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                            <i class="fas fa-user me-1"></i>{{ auth()->user()->name }}
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
            <h1><i class="fas fa-fire me-2"></i>Welcome Back, {{ auth()->user()->name }}!</h1>
            <p class="mb-0">Ready to crush your fitness goals today?</p>
        </div>

        <!-- Membership Status -->
        @php
            $activeMembership = auth()->user()->activeMembership();
        @endphp
        @if($activeMembership)
        <div class="membership-card">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h4><i class="fas fa-crown me-2"></i>{{ ucfirst($activeMembership->type) }} Membership</h4>
                    <p class="mb-0">Valid until {{ $activeMembership->end_date->format('F d, Y') }}</p>
                    <small>{{ $activeMembership->end_date->diffForHumans() }}</small>
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

        <!-- Statistics Cards -->
        <div class="row mb-4">
            <div class="col-md-3 mb-3">
                <div class="stat-card">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-1">Total Workouts</h6>
                            <h3 class="mb-0">{{ auth()->user()->workouts->count() }}</h3>
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
                            <h3 class="mb-0">{{ auth()->user()->workouts->where('status', 'completed')->count() }}</h3>
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
                            <h3 class="mb-0">{{ auth()->user()->workouts->where('workout_date', '>=', now()->startOfMonth())->count() }}</h3>
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
                            <h3 class="mb-0">{{ auth()->user()->workouts->sum('calories_burned') ?: 0 }}</h3>
                        </div>
                        <i class="fas fa-fire fa-2x text-info"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Workouts -->
        <div class="row">
            <div class="col-md-8">
                <h4 class="mb-3"><i class="fas fa-history me-2"></i>Recent Workouts</h4>
                @forelse(auth()->user()->workouts->take(5) as $workout)
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
                    <a href="{{ route('member.workouts.book') }}" class="btn btn-custom">Book First Workout</a>
                </div>
                @endforelse
            </div>
            
            <div class="col-md-4">
                <h4 class="mb-3"><i class="fas fa-bullseye me-2"></i>Quick Actions</h4>
                <div class="d-grid gap-2">
                    <a href="{{ route('member.workouts.book') }}" class="btn btn-custom">
                        <i class="fas fa-plus me-2"></i>Book Workout
                    </a>
                    <a href="{{ route('member.exercises') }}" class="btn btn-outline-primary">
                        <i class="fas fa-dumbbell me-2"></i>Browse Exercises
                    </a>
                    <a href="{{ route('member.progress') }}" class="btn btn-outline-success">
                        <i class="fas fa-chart-line me-2"></i>View Progress
                    </a>
                    <a href="{{ route('profile.edit') }}" class="btn btn-outline-info">
                        <i class="fas fa-user-edit me-2"></i>Update Profile
                    </a>
                </div>
                
                <div class="mt-4">
                    <h5><i class="fas fa-trophy me-2"></i>Achievements</h5>
                    <div class="achievement-badge mb-2">
                        <span class="badge bg-warning">🏃 First Workout</span>
                    </div>
                    @if(auth()->user()->workouts->where('status', 'completed')->count() >= 5)
                    <div class="achievement-badge mb-2">
                        <span class="badge bg-success">💪 5 Workouts Complete</span>
                    </div>
                    @endif
                    @if(auth()->user()->workouts->where('status', 'completed')->count() >= 10)
                    <div class="achievement-badge mb-2">
                        <span class="badge bg-primary">🔥 Fitness Enthusiast</span>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
