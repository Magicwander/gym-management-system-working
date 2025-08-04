@extends('member.layout')

@section('title', 'Workout Details')

@section('content')
<div class="container-fluid">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('member.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('member.workouts') }}">My Workouts</a></li>
            <li class="breadcrumb-item active">{{ $workout->name }}</li>
        </ol>
    </nav>

    <!-- Workout Header -->
    <div class="page-header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h2 class="mb-1">
                    @switch($workout->type)
                        @case('cardio')
                            <i class="fas fa-heartbeat me-2"></i>
                            @break
                        @case('strength')
                            <i class="fas fa-dumbbell me-2"></i>
                            @break
                        @case('flexibility')
                            <i class="fas fa-leaf me-2"></i>
                            @break
                        @case('sports')
                            <i class="fas fa-futbol me-2"></i>
                            @break
                        @case('group_class')
                            <i class="fas fa-users me-2"></i>
                            @break
                        @default
                            <i class="fas fa-running me-2"></i>
                    @endswitch
                    {{ $workout->name }}
                </h2>
                <p class="mb-0">{{ $workout->workout_date->format('F d, Y \a\t g:i A') }}</p>
            </div>
            <div>
                <span class="badge bg-{{ 
                    $workout->status === 'completed' ? 'success' : 
                    ($workout->status === 'scheduled' ? 'primary' : 
                    ($workout->status === 'in_progress' ? 'warning' : 'secondary')) 
                }} fs-5">
                    {{ ucfirst(str_replace('_', ' ', $workout->status)) }}
                </span>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Workout Information -->
        <div class="col-lg-8 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-info-circle me-2"></i>Workout Information
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <strong>Type:</strong>
                            <span class="ms-2">{{ ucfirst(str_replace('_', ' ', $workout->type)) }}</span>
                        </div>
                        <div class="col-md-6 mb-3">
                            <strong>Duration:</strong>
                            <span class="ms-2">{{ $workout->duration ?? 'Not specified' }} minutes</span>
                        </div>
                        <div class="col-md-6 mb-3">
                            <strong>Trainer:</strong>
                            <span class="ms-2">
                                @if($workout->trainer)
                                    {{ $workout->trainer->name }}
                                @else
                                    Self-guided
                                @endif
                            </span>
                        </div>
                        <div class="col-md-6 mb-3">
                            <strong>Calories Burned:</strong>
                            <span class="ms-2">{{ $workout->calories_burned ?? 'Not recorded' }}</span>
                        </div>
                    </div>
                    
                    @if($workout->notes)
                        <div class="mt-3">
                            <strong>Notes:</strong>
                            <div class="mt-2 p-3 bg-light rounded">
                                {{ $workout->notes }}
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Exercises -->
            @if($workout->exercises->count() > 0)
                <div class="card mt-4">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="fas fa-list me-2"></i>Exercises ({{ $workout->exercises->count() }})
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            @foreach($workout->exercises as $exercise)
                                <div class="col-md-6 mb-3">
                                    <div class="exercise-item p-3 border rounded">
                                        <div class="d-flex justify-content-between align-items-start">
                                            <div>
                                                <h6 class="mb-1">{{ $exercise->name }}</h6>
                                                <small class="text-muted">{{ ucfirst($exercise->category) }}</small>
                                            </div>
                                            @if($exercise->difficulty)
                                                <span class="badge bg-{{ 
                                                    $exercise->difficulty == 'beginner' ? 'success' : 
                                                    ($exercise->difficulty == 'intermediate' ? 'warning' : 'danger') 
                                                }}">
                                                    {{ ucfirst($exercise->difficulty) }}
                                                </span>
                                            @endif
                                        </div>
                                        
                                        @if($exercise->muscle_groups)
                                            <div class="mt-2">
                                                <small class="text-muted">
                                                    <i class="fas fa-bullseye me-1"></i>{{ $exercise->muscle_groups }}
                                                </small>
                                            </div>
                                        @endif
                                        
                                        <div class="mt-2">
                                            <a href="{{ route('member.exercises.show', $exercise) }}" 
                                               class="btn btn-sm btn-outline-primary">
                                                <i class="fas fa-eye me-1"></i>View Details
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Actions -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-cogs me-2"></i>Actions
                    </h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        @if($workout->status === 'scheduled' && $workout->workout_date->diffInHours(now()) >= 2)
                            <form action="{{ route('member.workouts.cancel', $workout) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="btn btn-danger w-100"
                                        onclick="return confirm('Are you sure you want to cancel this workout?')">
                                    <i class="fas fa-times me-2"></i>Cancel Workout
                                </button>
                            </form>
                        @endif
                        
                        <a href="{{ route('member.workouts') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-2"></i>Back to Workouts
                        </a>
                        
                        <a href="{{ route('member.workouts.book') }}" class="btn btn-primary">
                            <i class="fas fa-plus me-2"></i>Book Another Workout
                        </a>
                    </div>
                </div>
            </div>

            <!-- Workout Stats -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-chart-bar me-2"></i>Quick Stats
                    </h5>
                </div>
                <div class="card-body">
                    <div class="stat-item mb-3">
                        <div class="d-flex justify-content-between">
                            <span>Scheduled Date:</span>
                            <strong>{{ $workout->workout_date->format('M d, Y') }}</strong>
                        </div>
                    </div>
                    
                    <div class="stat-item mb-3">
                        <div class="d-flex justify-content-between">
                            <span>Time:</span>
                            <strong>{{ $workout->workout_date->format('g:i A') }}</strong>
                        </div>
                    </div>
                    
                    <div class="stat-item mb-3">
                        <div class="d-flex justify-content-between">
                            <span>Days Until:</span>
                            <strong>
                                @if($workout->workout_date->isPast())
                                    {{ $workout->workout_date->diffForHumans() }}
                                @else
                                    {{ $workout->workout_date->diffInDays(now()) }} days
                                @endif
                            </strong>
                        </div>
                    </div>
                    
                    <div class="stat-item">
                        <div class="d-flex justify-content-between">
                            <span>Exercises:</span>
                            <strong>{{ $workout->exercises->count() }}</strong>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.exercise-item {
    background: #f8f9fa;
    transition: all 0.3s ease;
}

.exercise-item:hover {
    background: #e9ecef;
    transform: translateY(-2px);
}

.stat-item {
    padding: 0.5rem 0;
    border-bottom: 1px solid #dee2e6;
}

.stat-item:last-child {
    border-bottom: none;
}
</style>
@endsection
