@extends('admin.layouts.app')

@section('title', 'My Workouts')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">My Assigned Workouts</h1>
        <div>
            <a href="{{ route('trainer.workouts.create') }}" class="btn btn-primary btn-sm">
                <i class="fas fa-plus fa-sm text-white-50"></i> Create New Workout
            </a>
            <a href="{{ route('dashboard') }}" class="btn btn-secondary btn-sm">
                <i class="fas fa-arrow-left fa-sm text-white-50"></i> Back to Dashboard
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card bg-primary text-white shadow">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <div class="text-xs font-weight-bold text-uppercase mb-1">Total Workouts</div>
                            <div class="h5 mb-0">{{ $workouts->total() }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clipboard-list fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-success text-white shadow">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <div class="text-xs font-weight-bold text-uppercase mb-1">Completed</div>
                            <div class="h5 mb-0">{{ $trainer->assignedWorkouts->where('status', 'completed')->count() }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-check-circle fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-warning text-white shadow">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <div class="text-xs font-weight-bold text-uppercase mb-1">In Progress</div>
                            <div class="h5 mb-0">{{ $trainer->assignedWorkouts->where('status', 'in_progress')->count() }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-running fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-info text-white shadow">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <div class="text-xs font-weight-bold text-uppercase mb-1">Active Clients</div>
                            <div class="h5 mb-0">{{ $trainer->assignedWorkouts->pluck('user_id')->unique()->count() }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-users fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Workouts Table -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Assigned Workouts</h6>
        </div>
        <div class="card-body">
            @if($workouts->count() > 0)
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Workout Name</th>
                                <th>Client</th>
                                <th>Type</th>
                                <th>Date</th>
                                <th>Duration</th>
                                <th>Status</th>
                                <th>Exercises</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($workouts as $workout)
                            <tr>
                                <td>
                                    <strong>{{ $workout->name }}</strong>
                                    @if($workout->description)
                                        <br><small class="text-muted">{{ Str::limit($workout->description, 50) }}</small>
                                    @endif
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="me-2">
                                            <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 30px; height: 30px;">
                                                <i class="fas fa-user fa-sm"></i>
                                            </div>
                                        </div>
                                        <div>
                                            <div class="font-weight-bold">{{ $workout->user->name }}</div>
                                            <small class="text-muted">{{ $workout->user->email }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td><span class="badge bg-secondary">{{ ucfirst($workout->type) }}</span></td>
                                <td>{{ $workout->workout_date->format('M d, Y') }}</td>
                                <td>{{ $workout->total_duration_minutes ? $workout->total_duration_minutes . ' min' : 'N/A' }}</td>
                                <td>
                                    <span class="badge bg-{{ $workout->status === 'completed' ? 'success' : ($workout->status === 'in_progress' ? 'warning' : 'secondary') }}">
                                        {{ ucfirst(str_replace('_', ' ', $workout->status)) }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge bg-info">{{ $workout->workoutExercises->count() }} exercises</span>
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <button type="button" class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#workoutModal{{ $workout->id }}">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                <!-- Pagination -->
                <div class="d-flex justify-content-center">
                    {{ $workouts->links() }}
                </div>
            @else
                <div class="text-center py-4">
                    <i class="fas fa-clipboard-list fa-3x text-muted mb-3"></i>
                    <p class="text-muted">No workouts assigned yet</p>
                    <a href="{{ route('trainer.workouts.create') }}" class="btn btn-primary btn-sm">
                        <i class="fas fa-plus"></i> Create Your First Workout
                    </a>
                </div>
            @endif
        </div>
    </div>
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
@endsection
