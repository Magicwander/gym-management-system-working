@extends('admin.layouts.app')

@section('title', 'Workout Details')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Workout Details</h1>
        <div>
            <a href="{{ route('admin.workouts.edit', $workout) }}" class="btn btn-warning btn-sm">
                <i class="fas fa-edit fa-sm text-white-50"></i> Edit Workout
            </a>
            <a href="{{ route('admin.workouts.index') }}" class="btn btn-secondary btn-sm">
                <i class="fas fa-arrow-left fa-sm text-white-50"></i> Back to Workouts
            </a>
        </div>
    </div>

    <div class="row">
        <!-- Workout Information -->
        <div class="col-lg-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Workout Information</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>Workout Name:</strong> {{ $workout->name }}</p>
                            <p><strong>Member:</strong> 
                                <a href="{{ route('admin.members.show', $workout->user) }}" class="text-decoration-none">
                                    {{ $workout->user->name }}
                                </a>
                            </p>
                            <p><strong>Trainer:</strong> 
                                @if($workout->trainer)
                                    <a href="{{ route('admin.trainers.show', $workout->trainer) }}" class="text-decoration-none">
                                        {{ $workout->trainer->name }}
                                    </a>
                                @else
                                    Self-guided
                                @endif
                            </p>
                            <p><strong>Type:</strong> 
                                <span class="badge bg-secondary fs-6">{{ ucfirst($workout->type) }}</span>
                            </p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Date:</strong> {{ $workout->workout_date->format('F d, Y') }}</p>
                            <p><strong>Time:</strong> 
                                @if($workout->start_time && $workout->end_time)
                                    {{ $workout->start_time }} - {{ $workout->end_time }}
                                @elseif($workout->start_time)
                                    From {{ $workout->start_time }}
                                @else
                                    Not specified
                                @endif
                            </p>
                            <p><strong>Duration:</strong> {{ $workout->total_duration_minutes ? $workout->total_duration_minutes . ' minutes' : 'Not set' }}</p>
                            <p><strong>Status:</strong> 
                                <span class="badge bg-{{ $workout->status === 'completed' ? 'success' : ($workout->status === 'in_progress' ? 'warning' : 'secondary') }} fs-6">
                                    {{ ucfirst($workout->status) }}
                                </span>
                            </p>
                        </div>
                    </div>
                    
                    @if($workout->description)
                    <div class="row mt-3">
                        <div class="col-12">
                            <p><strong>Description:</strong></p>
                            <p class="text-muted">{{ $workout->description }}</p>
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Workout Exercises -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Workout Exercises</h6>
                </div>
                <div class="card-body">
                    @if($workout->workoutExercises && $workout->workoutExercises->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Exercise</th>
                                        <th>Sets</th>
                                        <th>Reps</th>
                                        <th>Weight (kg)</th>
                                        <th>Duration</th>
                                        <th>Rest Time</th>
                                        <th>Notes</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($workout->workoutExercises as $workoutExercise)
                                    <tr>
                                        <td>
                                            <strong>{{ $workoutExercise->exercise->name }}</strong><br>
                                            <small class="text-muted">{{ ucfirst($workoutExercise->exercise->category) }}</small>
                                        </td>
                                        <td>{{ $workoutExercise->sets ?: '-' }}</td>
                                        <td>{{ $workoutExercise->reps ?: '-' }}</td>
                                        <td>{{ $workoutExercise->weight ? $workoutExercise->weight . ' kg' : '-' }}</td>
                                        <td>{{ $workoutExercise->duration_minutes ? $workoutExercise->duration_minutes . ' min' : '-' }}</td>
                                        <td>{{ $workoutExercise->rest_time_seconds ? $workoutExercise->rest_time_seconds . ' sec' : '-' }}</td>
                                        <td>{{ $workoutExercise->notes ?: '-' }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-dumbbell fa-3x text-muted mb-3"></i>
                            <p class="text-muted">No exercises added to this workout yet</p>
                            <button class="btn btn-primary btn-sm" onclick="addExercise()">
                                <i class="fas fa-plus"></i> Add Exercise
                            </button>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Workout Stats & Actions -->
        <div class="col-lg-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Workout Stats</h6>
                </div>
                <div class="card-body">
                    <div class="text-center">
                        <div class="mb-3">
                            <h4 class="text-primary">{{ $workout->workoutExercises ? $workout->workoutExercises->count() : 0 }}</h4>
                            <small class="text-muted">Exercises</small>
                        </div>
                        <div class="mb-3">
                            <h4 class="text-success">{{ $workout->calories_burned ?: 0 }}</h4>
                            <small class="text-muted">Calories Burned</small>
                        </div>
                        <div class="mb-3">
                            <h4 class="text-info">{{ $workout->total_duration_minutes ?: 0 }}</h4>
                            <small class="text-muted">Minutes</small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Quick Actions</h6>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        @if($workout->status !== 'completed')
                            <button class="btn btn-success btn-sm" onclick="markCompleted()">
                                <i class="fas fa-check"></i> Mark as Completed
                            </button>
                        @endif
                        
                        @if($workout->status === 'planned')
                            <button class="btn btn-warning btn-sm" onclick="startWorkout()">
                                <i class="fas fa-play"></i> Start Workout
                            </button>
                        @endif
                        
                        <button class="btn btn-outline-primary btn-sm" onclick="duplicateWorkout()">
                            <i class="fas fa-copy"></i> Duplicate Workout
                        </button>
                        
                        <a href="{{ route('admin.members.show', $workout->user) }}" class="btn btn-outline-info btn-sm">
                            <i class="fas fa-user"></i> View Member Profile
                        </a>
                        
                        @if($workout->trainer)
                            <a href="{{ route('admin.trainers.show', $workout->trainer) }}" class="btn btn-outline-secondary btn-sm">
                                <i class="fas fa-user-tie"></i> View Trainer Profile
                            </a>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Member Info -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Member Information</h6>
                </div>
                <div class="card-body">
                    <div class="text-center mb-3">
                        <div class="bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                            <i class="fas fa-user fa-2x"></i>
                        </div>
                    </div>
                    <h6 class="text-center">{{ $workout->user->name }}</h6>
                    <p class="text-center text-muted mb-2">{{ $workout->user->email }}</p>
                    
                    <div class="row text-center">
                        <div class="col-6">
                            <div class="border rounded p-2">
                                <h6 class="text-primary mb-0">{{ $workout->user->workouts->count() }}</h6>
                                <small class="text-muted">Total Workouts</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="border rounded p-2">
                                <h6 class="text-success mb-0">{{ $workout->user->workouts->where('status', 'completed')->count() }}</h6>
                                <small class="text-muted">Completed</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Notes Section -->
    @if($workout->notes)
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Notes</h6>
        </div>
        <div class="card-body">
            <p class="mb-0">{{ $workout->notes }}</p>
        </div>
    </div>
    @endif
</div>

<script>
function markCompleted() {
    if (confirm('Mark this workout as completed?')) {
        updateWorkoutStatus('completed');
    }
}

function startWorkout() {
    if (confirm('Start this workout? This will change the status to "In Progress".')) {
        updateWorkoutStatus('in_progress');
    }
}

function updateWorkoutStatus(status) {
    // Create a form to update workout status
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = "{{ route('admin.workouts.update', $workout) }}";
    
    const csrfToken = document.createElement('input');
    csrfToken.type = 'hidden';
    csrfToken.name = '_token';
    csrfToken.value = "{{ csrf_token() }}";
    
    const methodField = document.createElement('input');
    methodField.type = 'hidden';
    methodField.name = '_method';
    methodField.value = 'PUT';
    
    const statusField = document.createElement('input');
    statusField.type = 'hidden';
    statusField.name = 'status';
    statusField.value = status;
    
    // Add all current values as hidden fields
    const currentData = @json([
        'user_id' => $workout->user_id,
        'trainer_id' => $workout->trainer_id,
        'name' => $workout->name,
        'description' => $workout->description,
        'type' => $workout->type,
        'workout_date' => $workout->workout_date->format('Y-m-d'),
        'start_time' => $workout->start_time,
        'end_time' => $workout->end_time,
        'total_duration_minutes' => $workout->total_duration_minutes,
        'calories_burned' => $workout->calories_burned,
        'notes' => $workout->notes
    ]);
    
    Object.keys(currentData).forEach(key => {
        if (currentData[key] !== null) {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = key;
            input.value = currentData[key];
            form.appendChild(input);
        }
    });
    
    form.appendChild(csrfToken);
    form.appendChild(methodField);
    form.appendChild(statusField);
    
    document.body.appendChild(form);
    form.submit();
}

function duplicateWorkout() {
    if (confirm('Create a duplicate of this workout?')) {
        // Redirect to create page with pre-filled data
        const params = new URLSearchParams({
            duplicate: '{{ $workout->id }}',
            member: '{{ $workout->user_id }}',
            trainer: '{{ $workout->trainer_id ?? "" }}'
        });
        window.location.href = "{{ route('admin.workouts.create') }}?" + params.toString();
    }
}

function addExercise() {
    alert('Exercise management feature coming soon!');
}
</script>
@endsection
