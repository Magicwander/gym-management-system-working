@extends('admin.layouts.app')

@section('title', 'Client Progress')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Client Progress</h1>
        <a href="{{ route('dashboard') }}" class="btn btn-secondary btn-sm">
            <i class="fas fa-arrow-left fa-sm text-white-50"></i> Back to Dashboard
        </a>
    </div>

    <!-- Client Selection -->
    <div class="card shadow mb-4">
        <div class="card-body">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <label for="clientSelect" class="form-label">Select Client to View Progress:</label>
                    <select class="form-control" id="clientSelect" onchange="window.location.href = this.value">
                        <option value="{{ route('trainer.client-progress') }}">Choose a client...</option>
                        @foreach($clients as $client)
                            <option value="{{ route('trainer.client-progress', $client->id) }}" 
                                    {{ $selectedClient && $selectedClient->id == $client->id ? 'selected' : '' }}>
                                {{ $client->name }} ({{ $client->email }})
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4 text-end">
                    @if($selectedClient)
                        <a href="{{ route('trainer.workouts.create') }}?client={{ $selectedClient->id }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-plus me-1"></i> Create Workout
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>

    @if($selectedClient)
        <!-- Client Info Card -->
        <div class="row mb-4">
            <div class="col-lg-4">
                <div class="card shadow">
                    <div class="card-header bg-primary text-white">
                        <h6 class="mb-0">
                            <i class="fas fa-user me-2"></i>{{ $selectedClient->name }}
                        </h6>
                    </div>
                    <div class="card-body">
                        <p class="mb-2"><strong>Email:</strong> {{ $selectedClient->email }}</p>
                        <p class="mb-2"><strong>Phone:</strong> {{ $selectedClient->phone ?? 'Not provided' }}</p>
                        <p class="mb-2"><strong>Gender:</strong> {{ $selectedClient->gender ? ucfirst($selectedClient->gender) : 'Not specified' }}</p>
                        <p class="mb-2"><strong>Status:</strong> 
                            <span class="badge bg-{{ $selectedClient->is_active ? 'success' : 'danger' }}">
                                {{ $selectedClient->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </p>
                        <p class="mb-0"><strong>Member Since:</strong> {{ $selectedClient->created_at->format('M d, Y') }}</p>
                    </div>
                </div>
            </div>
            
            <!-- Progress Stats -->
            <div class="col-lg-8">
                <div class="row">
                    <div class="col-md-3 mb-3">
                        <div class="card bg-primary text-white shadow">
                            <div class="card-body">
                                <div class="text-center">
                                    <div class="text-xs font-weight-bold text-uppercase mb-1">Total Workouts</div>
                                    <div class="h5 mb-0">{{ $clientWorkouts->count() }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="card bg-success text-white shadow">
                            <div class="card-body">
                                <div class="text-center">
                                    <div class="text-xs font-weight-bold text-uppercase mb-1">Completed</div>
                                    <div class="h5 mb-0">{{ $clientWorkouts->where('status', 'completed')->count() }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="card bg-warning text-white shadow">
                            <div class="card-body">
                                <div class="text-center">
                                    <div class="text-xs font-weight-bold text-uppercase mb-1">In Progress</div>
                                    <div class="h5 mb-0">{{ $clientWorkouts->where('status', 'in_progress')->count() }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="card bg-info text-white shadow">
                            <div class="card-body">
                                <div class="text-center">
                                    <div class="text-xs font-weight-bold text-uppercase mb-1">This Month</div>
                                    <div class="h5 mb-0">{{ $clientWorkouts->where('workout_date', '>=', now()->startOfMonth())->count() }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @if($clientWorkouts->count() > 0)
            <!-- Workout History -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-history me-2"></i>Workout History
                    </h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Workout Name</th>
                                    <th>Type</th>
                                    <th>Duration</th>
                                    <th>Exercises</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($clientWorkouts->take(10) as $workout)
                                <tr>
                                    <td>{{ $workout->workout_date->format('M d, Y') }}</td>
                                    <td>
                                        <strong>{{ $workout->name }}</strong>
                                        @if($workout->description)
                                            <br><small class="text-muted">{{ Str::limit($workout->description, 40) }}</small>
                                        @endif
                                    </td>
                                    <td><span class="badge bg-secondary">{{ ucfirst($workout->type) }}</span></td>
                                    <td>{{ $workout->total_duration_minutes ? $workout->total_duration_minutes . ' min' : 'N/A' }}</td>
                                    <td>
                                        <span class="badge bg-info">{{ $workout->workoutExercises->count() }} exercises</span>
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $workout->status === 'completed' ? 'success' : ($workout->status === 'in_progress' ? 'warning' : 'secondary') }}">
                                            {{ ucfirst(str_replace('_', ' ', $workout->status)) }}
                                        </span>
                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#workoutModal{{ $workout->id }}">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                    @if($clientWorkouts->count() > 10)
                        <div class="text-center mt-3">
                            <p class="text-muted">Showing latest 10 workouts out of {{ $clientWorkouts->count() }} total</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Progress Chart Placeholder -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-chart-line me-2"></i>Progress Overview
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6>Workout Types Distribution</h6>
                            @php
                                $typeStats = $clientWorkouts->groupBy('type')->map->count();
                            @endphp
                            @foreach($typeStats as $type => $count)
                                <div class="mb-2">
                                    <div class="d-flex justify-content-between">
                                        <span>{{ ucfirst($type) }}</span>
                                        <span>{{ $count }} workouts</span>
                                    </div>
                                    <div class="progress" style="height: 10px;">
                                        <div class="progress-bar bg-{{ $type === 'strength' ? 'danger' : ($type === 'cardio' ? 'warning' : 'info') }}" 
                                             style="width: {{ ($count / $clientWorkouts->count()) * 100 }}%"></div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <div class="col-md-6">
                            <h6>Recent Activity</h6>
                            @php
                                $recentWorkouts = $clientWorkouts->where('workout_date', '>=', now()->subDays(30));
                                $completionRate = $recentWorkouts->count() > 0 ? ($recentWorkouts->where('status', 'completed')->count() / $recentWorkouts->count()) * 100 : 0;
                            @endphp
                            <div class="mb-3">
                                <div class="d-flex justify-content-between">
                                    <span>Completion Rate (Last 30 days)</span>
                                    <span>{{ number_format($completionRate, 1) }}%</span>
                                </div>
                                <div class="progress">
                                    <div class="progress-bar bg-success" style="width: {{ $completionRate }}%"></div>
                                </div>
                            </div>
                            <p class="text-muted">
                                <i class="fas fa-calendar me-1"></i>
                                {{ $recentWorkouts->count() }} workouts in the last 30 days
                            </p>
                        </div>
                    </div>
                </div>
            </div>

        @else
            <div class="card shadow">
                <div class="card-body text-center py-5">
                    <i class="fas fa-chart-line fa-4x text-muted mb-4"></i>
                    <h4 class="text-muted">No Workout History</h4>
                    <p class="text-muted mb-4">{{ $selectedClient->name }} doesn't have any workouts assigned yet.</p>
                    <a href="{{ route('trainer.workouts.create') }}?client={{ $selectedClient->id }}" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i> Create First Workout
                    </a>
                </div>
            </div>
        @endif

    @else
        <div class="card shadow">
            <div class="card-body text-center py-5">
                <i class="fas fa-user-friends fa-4x text-muted mb-4"></i>
                <h4 class="text-muted">Select a Client</h4>
                <p class="text-muted">Choose a client from the dropdown above to view their progress and workout history.</p>
            </div>
        </div>
    @endif
</div>

<!-- Workout Detail Modals -->
@if($selectedClient && $clientWorkouts->count() > 0)
    @foreach($clientWorkouts->take(10) as $workout)
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
@endif
@endsection
