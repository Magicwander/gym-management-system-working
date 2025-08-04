@extends('member.layout')

@section('title', 'My Workouts')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="page-header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h2 class="mb-1">
                    <i class="fas fa-running me-2"></i>My Workouts
                </h2>
                <p class="mb-0">Track and manage your fitness sessions</p>
            </div>
            <a href="{{ route('member.workouts.book') }}" class="btn btn-light">
                <i class="fas fa-plus me-2"></i>Book New Workout
            </a>
        </div>
    </div>

    <!-- Workout Statistics -->
    <div class="row mb-4">
        <div class="col-md-3 mb-3">
            <div class="card text-center">
                <div class="card-body">
                    <i class="fas fa-calendar-alt fa-2x text-primary mb-2"></i>
                    <h4 class="mb-1">{{ $workouts->total() }}</h4>
                    <small class="text-muted">Total Workouts</small>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card text-center">
                <div class="card-body">
                    <i class="fas fa-check-circle fa-2x text-success mb-2"></i>
                    <h4 class="mb-1">{{ auth()->user()->workouts()->where('status', 'completed')->count() }}</h4>
                    <small class="text-muted">Completed</small>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card text-center">
                <div class="card-body">
                    <i class="fas fa-clock fa-2x text-warning mb-2"></i>
                    <h4 class="mb-1">{{ auth()->user()->workouts()->where('status', 'scheduled')->count() }}</h4>
                    <small class="text-muted">Scheduled</small>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card text-center">
                <div class="card-body">
                    <i class="fas fa-fire fa-2x text-danger mb-2"></i>
                    <h4 class="mb-1">{{ auth()->user()->workouts()->where('workout_date', '>=', now()->startOfMonth())->count() }}</h4>
                    <small class="text-muted">This Month</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Workouts List -->
    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="fas fa-list me-2"></i>Workout History
                </h5>
                <div class="btn-group btn-group-sm">
                    <button class="btn btn-outline-primary active" data-filter="all">All</button>
                    <button class="btn btn-outline-primary" data-filter="scheduled">Scheduled</button>
                    <button class="btn btn-outline-primary" data-filter="completed">Completed</button>
                    <button class="btn btn-outline-primary" data-filter="cancelled">Cancelled</button>
                </div>
            </div>
        </div>
        
        <div class="card-body">
            @forelse($workouts as $workout)
                <div class="workout-item mb-3 p-3 border rounded" data-status="{{ $workout->status }}">
                    <div class="row align-items-center">
                        <div class="col-md-6">
                            <div class="d-flex align-items-center">
                                <div class="workout-icon me-3">
                                    @switch($workout->type)
                                        @case('cardio')
                                            <i class="fas fa-heartbeat fa-2x text-danger"></i>
                                            @break
                                        @case('strength')
                                            <i class="fas fa-dumbbell fa-2x text-primary"></i>
                                            @break
                                        @case('flexibility')
                                            <i class="fas fa-leaf fa-2x text-success"></i>
                                            @break
                                        @case('sports')
                                            <i class="fas fa-futbol fa-2x text-warning"></i>
                                            @break
                                        @case('group_class')
                                            <i class="fas fa-users fa-2x text-info"></i>
                                            @break
                                        @default
                                            <i class="fas fa-running fa-2x text-secondary"></i>
                                    @endswitch
                                </div>
                                <div>
                                    <h6 class="mb-1">{{ $workout->name }}</h6>
                                    <div class="text-muted small">
                                        <i class="fas fa-calendar me-1"></i>
                                        {{ $workout->workout_date->format('M d, Y') }} at {{ $workout->workout_date->format('g:i A') }}
                                    </div>
                                    <div class="text-muted small">
                                        <i class="fas fa-tag me-1"></i>{{ ucfirst(str_replace('_', ' ', $workout->type)) }}
                                        @if($workout->trainer)
                                            • <i class="fas fa-user me-1"></i>{{ $workout->trainer->name }}
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-3 text-center">
                            <span class="badge bg-{{ 
                                $workout->status === 'completed' ? 'success' : 
                                ($workout->status === 'scheduled' ? 'primary' : 
                                ($workout->status === 'in_progress' ? 'warning' : 'secondary')) 
                            }} fs-6">
                                {{ ucfirst(str_replace('_', ' ', $workout->status)) }}
                            </span>
                            
                            @if($workout->duration)
                                <div class="text-muted small mt-1">
                                    <i class="fas fa-clock me-1"></i>{{ $workout->duration }} min
                                </div>
                            @endif
                            
                            @if($workout->calories_burned)
                                <div class="text-muted small">
                                    <i class="fas fa-fire me-1"></i>{{ $workout->calories_burned }} cal
                                </div>
                            @endif
                        </div>
                        
                        <div class="col-md-3 text-end">
                            <div class="btn-group btn-group-sm">
                                <a href="{{ route('member.workouts.show', $workout) }}" 
                                   class="btn btn-outline-info" title="View Details">
                                    <i class="fas fa-eye"></i>
                                </a>
                                
                                @if($workout->status === 'scheduled' && $workout->workout_date->diffInHours(now()) >= 2)
                                    <form action="{{ route('member.workouts.cancel', $workout) }}" 
                                          method="POST" style="display: inline;">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="btn btn-outline-danger" 
                                                title="Cancel Workout"
                                                onclick="return confirm('Are you sure you want to cancel this workout?')">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    </div>
                    
                    @if($workout->exercises->count() > 0)
                        <div class="mt-2">
                            <small class="text-muted">
                                <i class="fas fa-list me-1"></i>Exercises: 
                                {{ $workout->exercises->pluck('name')->take(3)->join(', ') }}
                                @if($workout->exercises->count() > 3)
                                    and {{ $workout->exercises->count() - 3 }} more
                                @endif
                            </small>
                        </div>
                    @endif
                </div>
            @empty
                <div class="text-center py-5">
                    <i class="fas fa-running fa-4x text-muted mb-3"></i>
                    <h5 class="text-muted">No workouts found</h5>
                    <p class="text-muted">Start your fitness journey by booking your first workout!</p>
                    <a href="{{ route('member.workouts.book') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i>Book First Workout
                    </a>
                </div>
            @endforelse
        </div>
        
        @if($workouts->hasPages())
            <div class="card-footer">
                {{ $workouts->links() }}
            </div>
        @endif
    </div>
</div>

<style>
.workout-item {
    transition: all 0.3s ease;
    background: #f8f9fa;
}

.workout-item:hover {
    background: #e9ecef;
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
}

.workout-icon {
    width: 60px;
    text-align: center;
}

.btn-group .btn.active {
    background-color: #667eea;
    border-color: #667eea;
    color: white;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Filter functionality
    const filterButtons = document.querySelectorAll('[data-filter]');
    const workoutItems = document.querySelectorAll('.workout-item');
    
    filterButtons.forEach(button => {
        button.addEventListener('click', function() {
            // Update active button
            filterButtons.forEach(btn => btn.classList.remove('active'));
            this.classList.add('active');
            
            const filter = this.getAttribute('data-filter');
            
            workoutItems.forEach(item => {
                if (filter === 'all' || item.getAttribute('data-status') === filter) {
                    item.style.display = 'block';
                } else {
                    item.style.display = 'none';
                }
            });
        });
    });
});
</script>
@endsection
