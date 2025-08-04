@extends('member.layout')

@section('title', 'Book Workout')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card shadow-lg border-0">
                <div class="card-header bg-gradient-primary text-white">
                    <h4 class="mb-0">
                        <i class="fas fa-plus-circle me-2"></i>Book New Workout
                    </h4>
                </div>
                
                <div class="card-body p-4">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('member.workouts.store') }}" method="POST">
                        @csrf
                        
                        <div class="row">
                            <!-- Workout Details -->
                            <div class="col-md-6">
                                <h5 class="text-primary mb-3">
                                    <i class="fas fa-info-circle me-2"></i>Workout Details
                                </h5>
                                
                                <div class="mb-3">
                                    <label for="name" class="form-label">Workout Name *</label>
                                    <input type="text" class="form-control" id="name" name="name" 
                                           value="{{ old('name') }}" required>
                                </div>

                                <div class="mb-3">
                                    <label for="type" class="form-label">Workout Type *</label>
                                    <select class="form-select" id="type" name="type" required>
                                        <option value="">Select Type</option>
                                        <option value="cardio" {{ old('type') == 'cardio' ? 'selected' : '' }}>
                                            Cardio
                                        </option>
                                        <option value="strength" {{ old('type') == 'strength' ? 'selected' : '' }}>
                                            Strength Training
                                        </option>
                                        <option value="flexibility" {{ old('type') == 'flexibility' ? 'selected' : '' }}>
                                            Flexibility & Stretching
                                        </option>
                                        <option value="sports" {{ old('type') == 'sports' ? 'selected' : '' }}>
                                            Sports Training
                                        </option>
                                        <option value="group_class" {{ old('type') == 'group_class' ? 'selected' : '' }}>
                                            Group Class
                                        </option>
                                    </select>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="workout_date" class="form-label">Date *</label>
                                            <input type="date" class="form-control" id="workout_date" 
                                                   name="workout_date" value="{{ old('workout_date') }}" 
                                                   min="{{ date('Y-m-d') }}" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="workout_time" class="form-label">Time *</label>
                                            <select class="form-select" id="workout_time" name="workout_time" required>
                                                <option value="">Select Time</option>
                                                @for($hour = 6; $hour <= 22; $hour++)
                                                    @foreach(['00', '30'] as $minute)
                                                        @php
                                                            $time = sprintf('%02d:%s', $hour, $minute);
                                                            $display = date('g:i A', strtotime($time));
                                                        @endphp
                                                        <option value="{{ $time }}" {{ old('workout_time') == $time ? 'selected' : '' }}>
                                                            {{ $display }}
                                                        </option>
                                                    @endforeach
                                                @endfor
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="trainer_id" class="form-label">Trainer (Optional)</label>
                                    <select class="form-select" id="trainer_id" name="trainer_id">
                                        <option value="">Self-guided workout</option>
                                        @foreach($trainers as $trainer)
                                            <option value="{{ $trainer->id }}" {{ old('trainer_id') == $trainer->id ? 'selected' : '' }}>
                                                {{ $trainer->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <div class="form-text">
                                        <i class="fas fa-info-circle me-1"></i>
                                        Select a trainer for personalized guidance
                                    </div>
                                </div>
                            </div>

                            <!-- Exercise Selection -->
                            <div class="col-md-6">
                                <h5 class="text-primary mb-3">
                                    <i class="fas fa-dumbbell me-2"></i>Exercise Selection
                                </h5>
                                
                                <div class="mb-3">
                                    <label class="form-label">Choose Exercises (Optional)</label>
                                    <div class="exercise-selection" style="max-height: 300px; overflow-y: auto;">
                                        @foreach($exercises->groupBy('category') as $category => $categoryExercises)
                                            <div class="mb-3">
                                                <h6 class="text-secondary">{{ ucfirst($category) }}</h6>
                                                @foreach($categoryExercises as $exercise)
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" 
                                                               name="exercises[]" value="{{ $exercise->id }}" 
                                                               id="exercise_{{ $exercise->id }}"
                                                               {{ in_array($exercise->id, old('exercises', [])) ? 'checked' : '' }}>
                                                        <label class="form-check-label" for="exercise_{{ $exercise->id }}">
                                                            {{ $exercise->name }}
                                                            @if($exercise->difficulty)
                                                                <span class="badge bg-{{ $exercise->difficulty == 'beginner' ? 'success' : ($exercise->difficulty == 'intermediate' ? 'warning' : 'danger') }} ms-1">
                                                                    {{ ucfirst($exercise->difficulty) }}
                                                                </span>
                                                            @endif
                                                        </label>
                                                    </div>
                                                @endforeach
                                            </div>
                                        @endforeach
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="notes" class="form-label">Notes</label>
                                    <textarea class="form-control" id="notes" name="notes" rows="4" 
                                              placeholder="Any specific goals, preferences, or requirements...">{{ old('notes') }}</textarea>
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="row mt-4">
                            <div class="col-12">
                                <div class="d-flex justify-content-between">
                                    <a href="{{ route('member.dashboard') }}" class="btn btn-secondary">
                                        <i class="fas fa-arrow-left me-2"></i>Back to Dashboard
                                    </a>
                                    
                                    <div>
                                        <button type="reset" class="btn btn-outline-secondary me-2">
                                            <i class="fas fa-undo me-2"></i>Reset
                                        </button>
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fas fa-calendar-plus me-2"></i>Book Workout
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.bg-gradient-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

.exercise-selection {
    border: 1px solid #dee2e6;
    border-radius: 0.375rem;
    padding: 1rem;
    background-color: #f8f9fa;
}

.form-check {
    margin-bottom: 0.5rem;
}

.form-check-label {
    font-size: 0.9rem;
}

.card {
    border-radius: 15px;
}

.card-header {
    border-radius: 15px 15px 0 0 !important;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto-generate workout name based on type and date
    const typeSelect = document.getElementById('type');
    const dateInput = document.getElementById('workout_date');
    const nameInput = document.getElementById('name');
    
    function generateWorkoutName() {
        const type = typeSelect.value;
        const date = dateInput.value;
        
        if (type && date) {
            const dateObj = new Date(date);
            const formattedDate = dateObj.toLocaleDateString('en-US', { 
                month: 'short', 
                day: 'numeric' 
            });
            
            const typeNames = {
                'cardio': 'Cardio Session',
                'strength': 'Strength Training',
                'flexibility': 'Flexibility Session',
                'sports': 'Sports Training',
                'group_class': 'Group Class'
            };
            
            if (!nameInput.value || nameInput.value.includes('Session') || nameInput.value.includes('Training')) {
                nameInput.value = `${typeNames[type]} - ${formattedDate}`;
            }
        }
    }
    
    typeSelect.addEventListener('change', generateWorkoutName);
    dateInput.addEventListener('change', generateWorkoutName);
});
</script>
@endsection
