@extends('trainer.layouts.app')

@section('title', 'Edit Workout Plan')
@section('page-title', 'Edit Workout Plan')

@section('page-actions')
    <div class="btn-group">
        <a href="{{ route('trainer.workouts.show', $workout) }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-2"></i>Back to Details
        </a>
        <a href="{{ route('trainer.workouts.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-list me-2"></i>All Workouts
        </a>
    </div>
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-edit me-2"></i>Edit Workout Plan: {{ $workout->name }}
                </h5>
            </div>
            <div class="card-body">
                <form action="{{ route('trainer.workouts.update', $workout) }}" method="POST" id="workoutForm">
                    @csrf
                    @method('PUT')
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="member_id" class="form-label">Select Member <span class="text-danger">*</span></label>
                                <select class="form-select @error('member_id') is-invalid @enderror" id="member_id" name="member_id" required>
                                    <option value="">Choose a member...</option>
                                    @foreach($members as $member)
                                        <option value="{{ $member->id }}" {{ (old('member_id', $workout->member_id) == $member->id) ? 'selected' : '' }}>
                                            {{ $member->name }} ({{ $member->email }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('member_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="name" class="form-label">Workout Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $workout->name) }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="3">{{ old('description', $workout->description) }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="row">
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label for="workout_date" class="form-label">Workout Date <span class="text-danger">*</span></label>
                                <input type="date" class="form-control @error('workout_date') is-invalid @enderror" id="workout_date" name="workout_date" value="{{ old('workout_date', $workout->workout_date->format('Y-m-d')) }}" required>
                                @error('workout_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label for="duration" class="form-label">Duration (minutes) <span class="text-danger">*</span></label>
                                <input type="number" class="form-control @error('duration') is-invalid @enderror" id="duration" name="duration" value="{{ old('duration', $workout->duration) }}" min="15" max="180" required>
                                @error('duration')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label for="difficulty_level" class="form-label">Difficulty Level <span class="text-danger">*</span></label>
                                <select class="form-select @error('difficulty_level') is-invalid @enderror" id="difficulty_level" name="difficulty_level" required>
                                    <option value="">Select difficulty...</option>
                                    <option value="beginner" {{ old('difficulty_level', $workout->difficulty_level) == 'beginner' ? 'selected' : '' }}>Beginner</option>
                                    <option value="intermediate" {{ old('difficulty_level', $workout->difficulty_level) == 'intermediate' ? 'selected' : '' }}>Intermediate</option>
                                    <option value="advanced" {{ old('difficulty_level', $workout->difficulty_level) == 'advanced' ? 'selected' : '' }}>Advanced</option>
                                </select>
                                @error('difficulty_level')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                                <select class="form-select @error('status') is-invalid @enderror" id="status" name="status" required>
                                    <option value="scheduled" {{ old('status', $workout->status) == 'scheduled' ? 'selected' : '' }}>Scheduled</option>
                                    <option value="in_progress" {{ old('status', $workout->status) == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                                    <option value="completed" {{ old('status', $workout->status) == 'completed' ? 'selected' : '' }}>Completed</option>
                                    <option value="cancelled" {{ old('status', $workout->status) == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                </select>
                                @error('status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="price_lkr" class="form-label">Price (LKR) <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text">LKR</span>
                                    <input type="number" class="form-control @error('price_lkr') is-invalid @enderror" id="price_lkr" name="price_lkr" value="{{ old('price_lkr', $workout->price_lkr) }}" min="0" step="0.01" max="999999.99" required>
                                </div>
                                @error('price_lkr')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">Enter the price for this workout plan in Sri Lankan Rupees</div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="calories_target" class="form-label">Calories Target</label>
                                <input type="number" class="form-control @error('calories_target') is-invalid @enderror" id="calories_target" name="calories_target" value="{{ old('calories_target', $workout->calories_target) }}" min="0">
                                @error('calories_target')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="notes" class="form-label">Notes</label>
                        <textarea class="form-control @error('notes') is-invalid @enderror" id="notes" name="notes" rows="3">{{ old('notes', $workout->notes) }}</textarea>
                        @error('notes')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <!-- Exercises Section -->
                    <div class="card mt-4">
                        <div class="card-header">
                            <h6 class="card-title mb-0">
                                <i class="fas fa-list me-2"></i>Exercises
                            </h6>
                        </div>
                        <div class="card-body">
                            <div id="exercises-container">
                                @foreach($workout->exercises as $index => $exercise)
                                <div class="exercise-item border rounded p-3 mb-3">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <label class="form-label">Exercise <span class="text-danger">*</span></label>
                                            <select class="form-select" name="exercises[{{ $index }}][exercise_id]" required>
                                                <option value="">Select exercise...</option>
                                                @foreach($exercises as $ex)
                                                    <option value="{{ $ex->id }}" {{ $exercise->id == $ex->id ? 'selected' : '' }}>{{ $ex->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-2">
                                            <label class="form-label">Sets <span class="text-danger">*</span></label>
                                            <input type="number" class="form-control" name="exercises[{{ $index }}][sets]" value="{{ $exercise->pivot->sets }}" min="1" required>
                                        </div>
                                        <div class="col-md-2">
                                            <label class="form-label">Reps <span class="text-danger">*</span></label>
                                            <input type="number" class="form-control" name="exercises[{{ $index }}][reps]" value="{{ $exercise->pivot->reps }}" min="1" required>
                                        </div>
                                        <div class="col-md-2">
                                            <label class="form-label">Weight (kg)</label>
                                            <input type="number" class="form-control" name="exercises[{{ $index }}][weight]" value="{{ $exercise->pivot->weight }}" min="0" step="0.5">
                                        </div>
                                        <div class="col-md-2">
                                            <label class="form-label">Rest (sec)</label>
                                            <input type="number" class="form-control" name="exercises[{{ $index }}][rest_time]" value="{{ $exercise->pivot->rest_time }}" min="0">
                                        </div>
                                    </div>
                                    <div class="row mt-2">
                                        <div class="col-md-10">
                                            <label class="form-label">Exercise Notes</label>
                                            <input type="text" class="form-control" name="exercises[{{ $index }}][notes]" value="{{ $exercise->pivot->notes }}" placeholder="Special instructions...">
                                        </div>
                                        <div class="col-md-2 d-flex align-items-end">
                                            <button type="button" class="btn btn-danger btn-sm remove-exercise" {{ $workout->exercises->count() <= 1 ? 'style=display:none;' : '' }}>
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                            
                            <button type="button" class="btn btn-outline-success" id="add-exercise">
                                <i class="fas fa-plus me-2"></i>Add Exercise
                            </button>
                        </div>
                    </div>
                    
                    <div class="d-flex justify-content-end mt-4">
                        <a href="{{ route('trainer.workouts.show', $workout) }}" class="btn btn-secondary me-2">Cancel</a>
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-save me-2"></i>Update Workout Plan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
let exerciseIndex = {{ $workout->exercises->count() }};

document.getElementById('add-exercise').addEventListener('click', function() {
    const container = document.getElementById('exercises-container');
    const exerciseItem = document.createElement('div');
    exerciseItem.className = 'exercise-item border rounded p-3 mb-3';
    exerciseItem.innerHTML = `
        <div class="row">
            <div class="col-md-4">
                <label class="form-label">Exercise <span class="text-danger">*</span></label>
                <select class="form-select" name="exercises[${exerciseIndex}][exercise_id]" required>
                    <option value="">Select exercise...</option>
                    @foreach($exercises as $exercise)
                        <option value="{{ $exercise->id }}">{{ $exercise->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label">Sets <span class="text-danger">*</span></label>
                <input type="number" class="form-control" name="exercises[${exerciseIndex}][sets]" min="1" required>
            </div>
            <div class="col-md-2">
                <label class="form-label">Reps <span class="text-danger">*</span></label>
                <input type="number" class="form-control" name="exercises[${exerciseIndex}][reps]" min="1" required>
            </div>
            <div class="col-md-2">
                <label class="form-label">Weight (kg)</label>
                <input type="number" class="form-control" name="exercises[${exerciseIndex}][weight]" min="0" step="0.5">
            </div>
            <div class="col-md-2">
                <label class="form-label">Rest (sec)</label>
                <input type="number" class="form-control" name="exercises[${exerciseIndex}][rest_time]" min="0">
            </div>
        </div>
        <div class="row mt-2">
            <div class="col-md-10">
                <label class="form-label">Exercise Notes</label>
                <input type="text" class="form-control" name="exercises[${exerciseIndex}][notes]" placeholder="Special instructions...">
            </div>
            <div class="col-md-2 d-flex align-items-end">
                <button type="button" class="btn btn-danger btn-sm remove-exercise">
                    <i class="fas fa-trash"></i>
                </button>
            </div>
        </div>
    `;
    
    container.appendChild(exerciseItem);
    exerciseIndex++;
    
    // Show remove buttons if more than one exercise
    updateRemoveButtons();
});

document.addEventListener('click', function(e) {
    if (e.target.closest('.remove-exercise')) {
        e.target.closest('.exercise-item').remove();
        updateRemoveButtons();
    }
});

function updateRemoveButtons() {
    const exercises = document.querySelectorAll('.exercise-item');
    const removeButtons = document.querySelectorAll('.remove-exercise');
    
    removeButtons.forEach(button => {
        button.style.display = exercises.length > 1 ? 'block' : 'none';
    });
}
</script>
@endpush