@extends('admin.layouts.app')

@section('title', 'Create Workout')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Create New Workout</h1>
        <a href="{{ route('trainer.workouts') }}" class="btn btn-secondary btn-sm">
            <i class="fas fa-arrow-left fa-sm text-white-50"></i> Back to Workouts
        </a>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Workout Information</h6>
        </div>
        <div class="card-body">
            <form action="{{ route('trainer.workouts.store') }}" method="POST" id="workoutForm">
                @csrf
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label for="name" class="form-label">Workout Name</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                   id="name" name="name" value="{{ old('name') }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label for="user_id" class="form-label">Client</label>
                            <select class="form-control @error('user_id') is-invalid @enderror" id="user_id" name="user_id" required>
                                <option value="">Select Client</option>
                                @foreach($clients as $client)
                                    <option value="{{ $client->id }}" {{ old('user_id', request('client')) == $client->id ? 'selected' : '' }}>
                                        {{ $client->name }} ({{ $client->email }})
                                    </option>
                                @endforeach
                            </select>
                            @error('user_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group mb-3">
                            <label for="type" class="form-label">Workout Type</label>
                            <select class="form-control @error('type') is-invalid @enderror" id="type" name="type" required>
                                <option value="">Select Type</option>
                                <option value="strength" {{ old('type') == 'strength' ? 'selected' : '' }}>Strength Training</option>
                                <option value="cardio" {{ old('type') == 'cardio' ? 'selected' : '' }}>Cardio</option>
                                <option value="flexibility" {{ old('type') == 'flexibility' ? 'selected' : '' }}>Flexibility</option>
                                <option value="mixed" {{ old('type') == 'mixed' ? 'selected' : '' }}>Mixed</option>
                            </select>
                            @error('type')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="col-md-4">
                        <div class="form-group mb-3">
                            <label for="workout_date" class="form-label">Workout Date</label>
                            <input type="date" class="form-control @error('workout_date') is-invalid @enderror" 
                                   id="workout_date" name="workout_date" value="{{ old('workout_date', date('Y-m-d')) }}" required>
                            @error('workout_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="col-md-4">
                        <div class="form-group mb-3">
                            <label for="total_duration_minutes" class="form-label">Duration (minutes)</label>
                            <input type="number" class="form-control @error('total_duration_minutes') is-invalid @enderror" 
                                   id="total_duration_minutes" name="total_duration_minutes" value="{{ old('total_duration_minutes') }}" min="1">
                            @error('total_duration_minutes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="form-group mb-4">
                    <label for="description" class="form-label">Description</label>
                    <textarea class="form-control @error('description') is-invalid @enderror" 
                              id="description" name="description" rows="3">{{ old('description') }}</textarea>
                    @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Exercises Section -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h6 class="m-0 font-weight-bold text-primary">Exercises</h6>
                    </div>
                    <div class="card-body">
                        <div id="exercises-container">
                            <!-- Exercise rows will be added here -->
                        </div>
                        <button type="button" class="btn btn-success btn-sm" id="add-exercise">
                            <i class="fas fa-plus"></i> Add Exercise
                        </button>
                    </div>
                </div>

                <div class="form-group">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Create Workout
                    </button>
                    <a href="{{ route('trainer.workouts') }}" class="btn btn-secondary">
                        <i class="fas fa-times"></i> Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    let exerciseIndex = 0;
    const exercises = @json($exercises);
    
    function addExerciseRow() {
        const container = document.getElementById('exercises-container');
        const exerciseRow = document.createElement('div');
        exerciseRow.className = 'exercise-row border rounded p-3 mb-3';
        exerciseRow.innerHTML = `
            <div class="row">
                <div class="col-md-3">
                    <label class="form-label">Exercise</label>
                    <select class="form-control" name="exercises[${exerciseIndex}][exercise_id]" required>
                        <option value="">Select Exercise</option>
                        ${exercises.map(ex => `<option value="${ex.id}">${ex.name}</option>`).join('')}
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Sets</label>
                    <input type="number" class="form-control" name="exercises[${exerciseIndex}][sets]" min="1" required>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Reps</label>
                    <input type="number" class="form-control" name="exercises[${exerciseIndex}][reps]" min="1" required>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Weight (kg)</label>
                    <input type="number" class="form-control" name="exercises[${exerciseIndex}][weight]" min="0" step="0.5">
                </div>
                <div class="col-md-2">
                    <label class="form-label">Duration (min)</label>
                    <input type="number" class="form-control" name="exercises[${exerciseIndex}][duration_minutes]" min="1">
                </div>
                <div class="col-md-1">
                    <label class="form-label">&nbsp;</label>
                    <button type="button" class="btn btn-danger btn-sm d-block remove-exercise">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </div>
        `;
        
        container.appendChild(exerciseRow);
        exerciseIndex++;
        
        // Add remove functionality
        exerciseRow.querySelector('.remove-exercise').addEventListener('click', function() {
            exerciseRow.remove();
        });
    }
    
    document.getElementById('add-exercise').addEventListener('click', addExerciseRow);
    
    // Add first exercise row by default
    addExerciseRow();
});
</script>

<style>
.exercise-row {
    background-color: #f8f9fa;
}
</style>
@endsection
