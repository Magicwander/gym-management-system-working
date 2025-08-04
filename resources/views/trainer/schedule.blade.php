@extends('admin.layouts.app')

@section('title', 'My Schedule')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">My Schedule</h1>
        <div>
            <a href="{{ route('trainer.workouts.create') }}" class="btn btn-primary btn-sm">
                <i class="fas fa-plus fa-sm text-white-50"></i> Schedule New Workout
            </a>
            <a href="{{ route('dashboard') }}" class="btn btn-secondary btn-sm">
                <i class="fas fa-arrow-left fa-sm text-white-50"></i> Back to Dashboard
            </a>
        </div>
    </div>

    <!-- Week Navigation -->
    <div class="card shadow mb-4">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="fas fa-calendar-week me-2"></i>
                    Week of {{ now()->startOfWeek()->format('M d') }} - {{ now()->endOfWeek()->format('M d, Y') }}
                </h5>
                <div class="text-muted">
                    <i class="fas fa-clock me-1"></i>
                    {{ $workouts->count() }} workouts scheduled
                </div>
            </div>
        </div>
    </div>

    @if($workouts->count() > 0)
        <!-- Schedule Grid -->
        <div class="row">
            @php
                $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
                $weekStart = now()->startOfWeek();
            @endphp
            
            @foreach($days as $index => $day)
                @php
                    $currentDate = $weekStart->copy()->addDays($index);
                    $dayWorkouts = $workouts->filter(function($workout) use ($currentDate) {
                        return $workout->workout_date->format('Y-m-d') === $currentDate->format('Y-m-d');
                    });
                @endphp
                
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="card shadow h-100">
                        <div class="card-header bg-{{ $currentDate->isToday() ? 'primary' : 'light' }} {{ $currentDate->isToday() ? 'text-white' : '' }}">
                            <h6 class="mb-0">
                                <i class="fas fa-calendar-day me-2"></i>
                                {{ $day }}
                                <small class="d-block mt-1">{{ $currentDate->format('M d, Y') }}</small>
                            </h6>
                        </div>
                        <div class="card-body">
                            @if($dayWorkouts->count() > 0)
                                @foreach($dayWorkouts as $workout)
                                <div class="workout-item mb-3 p-2 border rounded">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div class="flex-grow-1">
                                            <h6 class="mb-1">{{ $workout->name }}</h6>
                                            <p class="mb-1 text-muted">
                                                <i class="fas fa-user me-1"></i>{{ $workout->user->name }}
                                            </p>
                                            <div class="d-flex align-items-center">
                                                <span class="badge bg-{{ $workout->type === 'strength' ? 'danger' : ($workout->type === 'cardio' ? 'warning' : 'info') }} me-2">
                                                    {{ ucfirst($workout->type) }}
                                                </span>
                                                @if($workout->total_duration_minutes)
                                                    <small class="text-muted">
                                                        <i class="fas fa-clock me-1"></i>{{ $workout->total_duration_minutes }} min
                                                    </small>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="dropdown">
                                            <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                                <i class="fas fa-ellipsis-v"></i>
                                            </button>
                                            <ul class="dropdown-menu">
                                                <li>
                                                    <button class="dropdown-item" data-bs-toggle="modal" data-bs-target="#workoutModal{{ $workout->id }}">
                                                        <i class="fas fa-eye me-2"></i>View Details
                                                    </button>
                                                </li>
                                                <li>
                                                    <a class="dropdown-item" href="{{ route('trainer.client-progress', $workout->user->id) }}">
                                                        <i class="fas fa-chart-line me-2"></i>Client Progress
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            @else
                                <div class="text-center py-3">
                                    <i class="fas fa-calendar-times fa-2x text-muted mb-2"></i>
                                    <p class="text-muted mb-0">No workouts scheduled</p>
                                </div>
                            @endif
                        </div>
                        @if($dayWorkouts->count() === 0)
                        <div class="card-footer">
                            <a href="{{ route('trainer.workouts.create') }}?date={{ $currentDate->format('Y-m-d') }}" class="btn btn-outline-primary btn-sm w-100">
                                <i class="fas fa-plus me-1"></i> Schedule Workout
                            </a>
                        </div>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Upcoming Workouts (Next 2 weeks) -->
        @php
            $upcomingWorkouts = $workouts->filter(function($workout) {
                return $workout->workout_date->gt(now()->endOfWeek());
            });
        @endphp
        
        @if($upcomingWorkouts->count() > 0)
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="fas fa-calendar-plus me-2"></i>Upcoming Workouts (Next 2 Weeks)
                </h6>
            </div>
            <div class="card-body">
                <div class="row">
                    @foreach($upcomingWorkouts->take(6) as $workout)
                    <div class="col-md-6 col-lg-4 mb-3">
                        <div class="card border-left-primary">
                            <div class="card-body">
                                <h6 class="card-title">{{ $workout->name }}</h6>
                                <p class="card-text">
                                    <small class="text-muted">
                                        <i class="fas fa-user me-1"></i>{{ $workout->user->name }}<br>
                                        <i class="fas fa-calendar me-1"></i>{{ $workout->workout_date->format('M d, Y') }}<br>
                                        <span class="badge bg-secondary">{{ ucfirst($workout->type) }}</span>
                                    </small>
                                </p>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        @endif

    @else
        <div class="card shadow">
            <div class="card-body text-center py-5">
                <i class="fas fa-calendar-times fa-4x text-muted mb-4"></i>
                <h4 class="text-muted">No Workouts Scheduled</h4>
                <p class="text-muted mb-4">You don't have any workouts scheduled for the next two weeks.</p>
                <a href="{{ route('trainer.workouts.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i> Schedule Your First Workout
                </a>
            </div>
        </div>
    @endif
</div>

<!-- Workout Detail Modals -->
@foreach($workouts as $workout)
<div class="modal fade" id="workoutModal{{ $workout->id }}" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ $workout->name }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <p><strong>Client:</strong> {{ $workout->user->name }}</p>
                        <p><strong>Type:</strong> {{ ucfirst($workout->type) }}</p>
                        <p><strong>Date:</strong> {{ $workout->workout_date->format('F d, Y') }}</p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>Duration:</strong> {{ $workout->total_duration_minutes ? $workout->total_duration_minutes . ' minutes' : 'Not specified' }}</p>
                        <p><strong>Status:</strong> 
                            <span class="badge bg-{{ $workout->status === 'completed' ? 'success' : ($workout->status === 'in_progress' ? 'warning' : 'secondary') }}">
                                {{ ucfirst(str_replace('_', ' ', $workout->status)) }}
                            </span>
                        </p>
                    </div>
                </div>
                
                @if($workout->description)
                <div class="mb-3">
                    <strong>Description:</strong>
                    <p>{{ $workout->description }}</p>
                </div>
                @endif

                @if($workout->workoutExercises->count() > 0)
                <h6>Exercises:</h6>
                <div class="table-responsive">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>Exercise</th>
                                <th>Sets</th>
                                <th>Reps</th>
                                <th>Weight</th>
                                <th>Duration</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($workout->workoutExercises as $we)
                            <tr>
                                <td>{{ $we->exercise->name }}</td>
                                <td>{{ $we->sets }}</td>
                                <td>{{ $we->reps }}</td>
                                <td>{{ $we->weight ? $we->weight . ' kg' : 'N/A' }}</td>
                                <td>{{ $we->duration_minutes ? $we->duration_minutes . ' min' : 'N/A' }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endforeach

<style>
.workout-item {
    background-color: #f8f9fa;
    transition: all 0.2s;
}
.workout-item:hover {
    background-color: #e9ecef;
    transform: translateY(-1px);
}
.border-left-primary {
    border-left: 4px solid #4e73df !important;
}
</style>
@endsection
