@extends('admin.layouts.app')

@section('title', 'Edit Workout')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Edit Workout</h1>
        <a href="{{ route('admin.workouts.index') }}" class="btn btn-secondary btn-sm">
            <i class="fas fa-arrow-left fa-sm text-white-50"></i> Back to Workouts
        </a>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Workout Information</h6>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.workouts.update', $workout) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label for="user_id" class="form-label">Select Member</label>
                            <select class="form-control @error('user_id') is-invalid @enderror" id="user_id" name="user_id" required>
                                <option value="">Choose a member...</option>
                                @foreach($members as $member)
                                    <option value="{{ $member->id }}" {{ old('user_id', $workout->user_id) == $member->id ? 'selected' : '' }}>
                                        {{ $member->name }} ({{ $member->email }})
                                    </option>
                                @endforeach
                            </select>
                            @error('user_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label for="trainer_id" class="form-label">Assign Trainer (Optional)</label>
                            <select class="form-control @error('trainer_id') is-invalid @enderror" id="trainer_id" name="trainer_id">
                                <option value="">Self-guided workout</option>
                                @foreach($trainers as $trainer)
                                    <option value="{{ $trainer->id }}" {{ old('trainer_id', $workout->trainer_id) == $trainer->id ? 'selected' : '' }}>
                                        {{ $trainer->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('trainer_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label for="name" class="form-label">Workout Name</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                   id="name" name="name" value="{{ old('name', $workout->name) }}" required 
                                   placeholder="e.g., Morning Cardio Session">
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label for="type" class="form-label">Workout Type</label>
                            <select class="form-control @error('type') is-invalid @enderror" id="type" name="type" required>
                                <option value="">Select Type</option>
                                <option value="strength" {{ old('type', $workout->type) == 'strength' ? 'selected' : '' }}>Strength Training</option>
                                <option value="cardio" {{ old('type', $workout->type) == 'cardio' ? 'selected' : '' }}>Cardio</option>
                                <option value="mixed" {{ old('type', $workout->type) == 'mixed' ? 'selected' : '' }}>Mixed Training</option>
                                <option value="flexibility" {{ old('type', $workout->type) == 'flexibility' ? 'selected' : '' }}>Flexibility</option>
                            </select>
                            @error('type')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="form-group mb-3">
                    <label for="description" class="form-label">Description</label>
                    <textarea class="form-control @error('description') is-invalid @enderror" 
                              id="description" name="description" rows="3" 
                              placeholder="Describe the workout plan and objectives...">{{ old('description', $workout->description) }}</textarea>
                    @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group mb-3">
                            <label for="workout_date" class="form-label">Workout Date</label>
                            <input type="date" class="form-control @error('workout_date') is-invalid @enderror" 
                                   id="workout_date" name="workout_date" value="{{ old('workout_date', $workout->workout_date->format('Y-m-d')) }}" required>
                            @error('workout_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="col-md-4">
                        <div class="form-group mb-3">
                            <label for="start_time" class="form-label">Start Time (Optional)</label>
                            <input type="time" class="form-control @error('start_time') is-invalid @enderror" 
                                   id="start_time" name="start_time" value="{{ old('start_time', $workout->start_time) }}">
                            @error('start_time')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="col-md-4">
                        <div class="form-group mb-3">
                            <label for="end_time" class="form-label">End Time (Optional)</label>
                            <input type="time" class="form-control @error('end_time') is-invalid @enderror" 
                                   id="end_time" name="end_time" value="{{ old('end_time', $workout->end_time) }}">
                            @error('end_time')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group mb-3">
                            <label for="total_duration_minutes" class="form-label">Duration (Minutes)</label>
                            <input type="number" min="1" class="form-control @error('total_duration_minutes') is-invalid @enderror" 
                                   id="total_duration_minutes" name="total_duration_minutes" value="{{ old('total_duration_minutes', $workout->total_duration_minutes) }}" 
                                   placeholder="e.g., 60">
                            @error('total_duration_minutes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="col-md-4">
                        <div class="form-group mb-3">
                            <label for="calories_burned" class="form-label">Calories Burned</label>
                            <input type="number" min="0" class="form-control @error('calories_burned') is-invalid @enderror" 
                                   id="calories_burned" name="calories_burned" value="{{ old('calories_burned', $workout->calories_burned) }}" 
                                   placeholder="e.g., 300">
                            @error('calories_burned')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="col-md-4">
                        <div class="form-group mb-3">
                            <label for="status" class="form-label">Status</label>
                            <select class="form-control @error('status') is-invalid @enderror" id="status" name="status" required>
                                <option value="planned" {{ old('status', $workout->status) == 'planned' ? 'selected' : '' }}>Planned</option>
                                <option value="in_progress" {{ old('status', $workout->status) == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                                <option value="completed" {{ old('status', $workout->status) == 'completed' ? 'selected' : '' }}>Completed</option>
                                <option value="skipped" {{ old('status', $workout->status) == 'skipped' ? 'selected' : '' }}>Skipped</option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="form-group mb-3">
                    <label for="notes" class="form-label">Notes (Optional)</label>
                    <textarea class="form-control @error('notes') is-invalid @enderror" 
                              id="notes" name="notes" rows="3" 
                              placeholder="Any additional notes or instructions...">{{ old('notes', $workout->notes) }}</textarea>
                    @error('notes')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Update Workout
                    </button>
                    <a href="{{ route('admin.workouts.index') }}" class="btn btn-secondary">
                        <i class="fas fa-times"></i> Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Current Workout Details -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-info">Current Workout Details</h6>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <p><strong>Member:</strong> {{ $workout->user->name }}</p>
                    <p><strong>Trainer:</strong> {{ $workout->trainer ? $workout->trainer->name : 'Self-guided' }}</p>
                    <p><strong>Current Type:</strong> 
                        <span class="badge bg-secondary">{{ ucfirst($workout->type) }}</span>
                    </p>
                    <p><strong>Current Status:</strong> 
                        <span class="badge bg-{{ $workout->status === 'completed' ? 'success' : ($workout->status === 'in_progress' ? 'warning' : 'secondary') }}">
                            {{ ucfirst($workout->status) }}
                        </span>
                    </p>
                </div>
                <div class="col-md-6">
                    <p><strong>Date:</strong> {{ $workout->workout_date->format('F d, Y') }}</p>
                    <p><strong>Duration:</strong> {{ $workout->total_duration_minutes ? $workout->total_duration_minutes . ' minutes' : 'Not set' }}</p>
                    <p><strong>Calories:</strong> {{ $workout->calories_burned ?: 'Not recorded' }}</p>
                    <p><strong>Created:</strong> {{ $workout->created_at->format('F d, Y') }}</p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Auto-calculate duration based on start and end time
document.addEventListener('DOMContentLoaded', function() {
    const startTimeInput = document.getElementById('start_time');
    const endTimeInput = document.getElementById('end_time');
    const durationInput = document.getElementById('total_duration_minutes');
    
    function calculateDuration() {
        if (startTimeInput.value && endTimeInput.value) {
            const start = new Date('2000-01-01 ' + startTimeInput.value);
            const end = new Date('2000-01-01 ' + endTimeInput.value);
            
            if (end > start) {
                const diffMs = end - start;
                const diffMins = Math.floor(diffMs / 60000);
                durationInput.value = diffMins;
            }
        }
    }
    
    startTimeInput.addEventListener('change', calculateDuration);
    endTimeInput.addEventListener('change', calculateDuration);
});
</script>
@endsection
