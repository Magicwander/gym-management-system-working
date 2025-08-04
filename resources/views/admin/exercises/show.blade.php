@extends('admin.layouts.app')

@section('title', 'Exercise Details')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Exercise Details</h1>
        <div>
            <a href="{{ route('admin.exercises.edit', $exercise) }}" class="btn btn-warning btn-sm">
                <i class="fas fa-edit fa-sm text-white-50"></i> Edit Exercise
            </a>
            <a href="{{ route('admin.exercises.index') }}" class="btn btn-secondary btn-sm">
                <i class="fas fa-arrow-left fa-sm text-white-50"></i> Back to Exercises
            </a>
        </div>
    </div>

    <div class="row">
        <!-- Exercise Information -->
        <div class="col-lg-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Exercise Information</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>Exercise Name:</strong> {{ $exercise->name }}</p>
                            <p><strong>Category:</strong> 
                                <span class="badge bg-primary fs-6">{{ ucfirst($exercise->category) }}</span>
                            </p>
                            <p><strong>Primary Muscle Group:</strong> 
                                <span class="badge bg-secondary fs-6">{{ ucfirst(str_replace('_', ' ', $exercise->muscle_group)) }}</span>
                            </p>
                            <p><strong>Difficulty Level:</strong> 
                                <span class="badge bg-{{ $exercise->difficulty_level === 'beginner' ? 'success' : ($exercise->difficulty_level === 'intermediate' ? 'warning' : 'danger') }} fs-6">
                                    {{ ucfirst($exercise->difficulty_level) }}
                                </span>
                            </p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Equipment Needed:</strong> {{ $exercise->equipment_needed ?: 'None' }}</p>
                            <p><strong>Typical Duration:</strong> {{ $exercise->duration_minutes ? $exercise->duration_minutes . ' minutes' : 'Variable' }}</p>
                            <p><strong>Calories per Minute:</strong> {{ $exercise->calories_burned_per_minute ?: 'Not specified' }}</p>
                            <p><strong>Status:</strong> 
                                <span class="badge bg-{{ $exercise->is_active ? 'success' : 'danger' }} fs-6">
                                    {{ $exercise->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </p>
                        </div>
                    </div>
                    
                    @if($exercise->description)
                    <div class="row mt-3">
                        <div class="col-12">
                            <p><strong>Description:</strong></p>
                            <p class="text-muted">{{ $exercise->description }}</p>
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Instructions -->
            @if($exercise->instructions)
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Instructions</h6>
                </div>
                <div class="card-body">
                    <div class="instructions-content">
                        {!! nl2br(e($exercise->instructions)) !!}
                    </div>
                </div>
            </div>
            @endif

            <!-- Media -->
            @if($exercise->video_url || $exercise->image_url)
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Media</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        @if($exercise->video_url)
                        <div class="col-md-6">
                            <h6>Video Demonstration</h6>
                            <div class="embed-responsive embed-responsive-16by9 mb-3">
                                @if(strpos($exercise->video_url, 'youtube.com') !== false || strpos($exercise->video_url, 'youtu.be') !== false)
                                    @php
                                        $videoId = '';
                                        if (strpos($exercise->video_url, 'youtube.com') !== false) {
                                            parse_str(parse_url($exercise->video_url, PHP_URL_QUERY), $params);
                                            $videoId = $params['v'] ?? '';
                                        } elseif (strpos($exercise->video_url, 'youtu.be') !== false) {
                                            $videoId = basename(parse_url($exercise->video_url, PHP_URL_PATH));
                                        }
                                    @endphp
                                    @if($videoId)
                                        <iframe width="100%" height="200" src="https://www.youtube.com/embed/{{ $videoId }}" 
                                                frameborder="0" allowfullscreen></iframe>
                                    @else
                                        <a href="{{ $exercise->video_url }}" target="_blank" class="btn btn-primary">
                                            <i class="fas fa-play"></i> Watch Video
                                        </a>
                                    @endif
                                @else
                                    <a href="{{ $exercise->video_url }}" target="_blank" class="btn btn-primary">
                                        <i class="fas fa-play"></i> Watch Video
                                    </a>
                                @endif
                            </div>
                        </div>
                        @endif
                        
                        @if($exercise->image_url)
                        <div class="col-md-6">
                            <h6>Exercise Image</h6>
                            <img src="{{ $exercise->image_url }}" alt="{{ $exercise->name }}" 
                                 class="img-fluid rounded" style="max-height: 200px;">
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            @endif
        </div>

        <!-- Exercise Stats & Actions -->
        <div class="col-lg-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Exercise Stats</h6>
                </div>
                <div class="card-body">
                    <div class="text-center">
                        <div class="mb-3">
                            <h4 class="text-primary">{{ $exercise->duration_minutes ?: 'Variable' }}</h4>
                            <small class="text-muted">Duration (Minutes)</small>
                        </div>
                        <div class="mb-3">
                            <h4 class="text-success">{{ $exercise->calories_burned_per_minute ?: 'N/A' }}</h4>
                            <small class="text-muted">Calories per Minute</small>
                        </div>
                        <div class="mb-3">
                            @php
                                $totalCalories = $exercise->duration_minutes && $exercise->calories_burned_per_minute 
                                    ? $exercise->duration_minutes * $exercise->calories_burned_per_minute 
                                    : 0;
                            @endphp
                            <h4 class="text-info">{{ $totalCalories ?: 'N/A' }}</h4>
                            <small class="text-muted">Total Calories (Est.)</small>
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
                        <button class="btn btn-primary btn-sm" onclick="addToWorkout()">
                            <i class="fas fa-plus"></i> Add to Workout
                        </button>
                        
                        <button class="btn btn-outline-success btn-sm" onclick="duplicateExercise()">
                            <i class="fas fa-copy"></i> Duplicate Exercise
                        </button>
                        
                        @if($exercise->video_url)
                            <a href="{{ $exercise->video_url }}" target="_blank" class="btn btn-outline-primary btn-sm">
                                <i class="fas fa-play"></i> Watch Video
                            </a>
                        @endif
                        
                        @if(!$exercise->is_active)
                            <button class="btn btn-outline-warning btn-sm" onclick="activateExercise()">
                                <i class="fas fa-check"></i> Activate Exercise
                            </button>
                        @else
                            <button class="btn btn-outline-danger btn-sm" onclick="deactivateExercise()">
                                <i class="fas fa-ban"></i> Deactivate Exercise
                            </button>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Exercise Categories Info -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Category Information</h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <h6 class="text-primary">{{ ucfirst($exercise->category) }}</h6>
                        <p class="small mb-0">
                            @switch($exercise->category)
                                @case('strength')
                                    Builds muscle strength and endurance
                                    @break
                                @case('cardio')
                                    Improves cardiovascular health
                                    @break
                                @case('flexibility')
                                    Enhances flexibility and mobility
                                    @break
                                @case('balance')
                                    Improves balance and stability
                                    @break
                                @case('sports')
                                    Sport-specific movements
                                    @break
                                @default
                                    General fitness exercise
                            @endswitch
                        </p>
                    </div>
                    
                    <div class="mb-3">
                        <h6 class="text-secondary">{{ ucfirst(str_replace('_', ' ', $exercise->muscle_group)) }}</h6>
                        <p class="small mb-0">Primary muscle group targeted</p>
                    </div>
                    
                    <div>
                        <h6 class="text-{{ $exercise->difficulty_level === 'beginner' ? 'success' : ($exercise->difficulty_level === 'intermediate' ? 'warning' : 'danger') }}">
                            {{ ucfirst($exercise->difficulty_level) }}
                        </h6>
                        <p class="small mb-0">
                            @switch($exercise->difficulty_level)
                                @case('beginner')
                                    Suitable for beginners
                                    @break
                                @case('intermediate')
                                    Requires some experience
                                    @break
                                @case('advanced')
                                    For experienced individuals
                                    @break
                            @endswitch
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function addToWorkout() {
    alert('Add to workout feature coming soon! This will allow you to quickly add this exercise to a new or existing workout plan.');
}

function duplicateExercise() {
    if (confirm('Create a duplicate of this exercise?')) {
        // Redirect to create page with pre-filled data
        const params = new URLSearchParams({
            duplicate: '{{ $exercise->id }}'
        });
        window.location.href = "{{ route('admin.exercises.create') }}?" + params.toString();
    }
}

function activateExercise() {
    if (confirm('Activate this exercise? It will be available for use in workouts.')) {
        updateExerciseStatus(true);
    }
}

function deactivateExercise() {
    if (confirm('Deactivate this exercise? It will not be available for new workouts.')) {
        updateExerciseStatus(false);
    }
}

function updateExerciseStatus(isActive) {
    // Create a form to update exercise status
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = "{{ route('admin.exercises.update', $exercise) }}";
    
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
    statusField.name = 'is_active';
    statusField.value = isActive ? '1' : '0';
    
    // Add all current values as hidden fields
    const currentData = @json([
        'name' => $exercise->name,
        'description' => $exercise->description,
        'category' => $exercise->category,
        'muscle_group' => $exercise->muscle_group,
        'difficulty_level' => $exercise->difficulty_level,
        'equipment_needed' => $exercise->equipment_needed,
        'instructions' => $exercise->instructions,
        'video_url' => $exercise->video_url,
        'image_url' => $exercise->image_url,
        'duration_minutes' => $exercise->duration_minutes,
        'calories_burned_per_minute' => $exercise->calories_burned_per_minute
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
</script>
@endsection
