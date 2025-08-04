@extends('member.layout')

@section('title', 'Exercise Details')

@section('content')
<div class="container-fluid">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('member.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('member.exercises') }}">Exercises</a></li>
            <li class="breadcrumb-item active">{{ $exercise->name }}</li>
        </ol>
    </nav>

    <div class="row">
        <!-- Exercise Information -->
        <div class="col-lg-8 mb-4">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="mb-0">
                            <i class="fas fa-dumbbell me-2"></i>{{ $exercise->name }}
                        </h4>
                        @if($exercise->difficulty)
                            <span class="badge bg-{{ 
                                $exercise->difficulty == 'beginner' ? 'success' : 
                                ($exercise->difficulty == 'intermediate' ? 'warning' : 'danger') 
                            }} fs-6">
                                {{ ucfirst($exercise->difficulty) }}
                            </span>
                        @endif
                    </div>
                </div>
                
                @if($exercise->image)
                    <img src="{{ asset('storage/' . $exercise->image) }}" 
                         class="card-img-top exercise-image" 
                         alt="{{ $exercise->name }}">
                @else
                    <div class="card-img-top exercise-placeholder d-flex align-items-center justify-content-center">
                        <i class="fas fa-dumbbell fa-4x text-muted"></i>
                    </div>
                @endif
                
                <div class="card-body">
                    @if($exercise->description)
                        <div class="mb-4">
                            <h5>Description</h5>
                            <p class="text-muted">{{ $exercise->description }}</p>
                        </div>
                    @endif

                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="info-item">
                                <strong><i class="fas fa-tag me-2"></i>Category:</strong>
                                <span class="badge bg-primary ms-2">{{ ucfirst($exercise->category) }}</span>
                            </div>
                        </div>
                        
                        @if($exercise->duration)
                            <div class="col-md-6">
                                <div class="info-item">
                                    <strong><i class="fas fa-clock me-2"></i>Duration:</strong>
                                    <span class="ms-2">{{ $exercise->duration }} minutes</span>
                                </div>
                            </div>
                        @endif
                    </div>

                    @if($exercise->muscle_groups)
                        <div class="mb-4">
                            <h5><i class="fas fa-bullseye me-2"></i>Target Muscles</h5>
                            <div class="muscle-groups">
                                @foreach(explode(',', $exercise->muscle_groups) as $muscle)
                                    <span class="badge bg-info me-1 mb-1">{{ trim($muscle) }}</span>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    @if($exercise->equipment)
                        <div class="mb-4">
                            <h5><i class="fas fa-tools me-2"></i>Equipment Needed</h5>
                            <div class="equipment-list">
                                @foreach(explode(',', $exercise->equipment) as $equipment)
                                    <span class="badge bg-secondary me-1 mb-1">{{ trim($equipment) }}</span>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    @if($exercise->instructions)
                        <div class="mb-4">
                            <h5><i class="fas fa-list-ol me-2"></i>Instructions</h5>
                            <div class="instructions-box p-3 bg-light rounded">
                                <p class="mb-0">{{ $exercise->instructions }}</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Quick Actions -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-bolt me-2"></i>Quick Actions
                    </h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <button class="btn btn-primary" onclick="addToWorkout()">
                            <i class="fas fa-plus me-2"></i>Add to Workout
                        </button>
                        <button class="btn btn-success" onclick="addToFavorites()">
                            <i class="fas fa-heart me-2"></i>Add to Favorites
                        </button>
                        <a href="{{ route('member.workouts.book') }}" class="btn btn-outline-primary">
                            <i class="fas fa-calendar-plus me-2"></i>Book Workout
                        </a>
                        <a href="{{ route('member.exercises') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left me-2"></i>Back to Exercises
                        </a>
                    </div>
                </div>
            </div>

            <!-- Exercise Stats -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-chart-bar me-2"></i>Exercise Info
                    </h5>
                </div>
                <div class="card-body">
                    <div class="stat-item">
                        <div class="d-flex justify-content-between">
                            <span>Category:</span>
                            <strong>{{ ucfirst($exercise->category) }}</strong>
                        </div>
                    </div>
                    
                    @if($exercise->difficulty)
                        <div class="stat-item">
                            <div class="d-flex justify-content-between">
                                <span>Difficulty:</span>
                                <strong>{{ ucfirst($exercise->difficulty) }}</strong>
                            </div>
                        </div>
                    @endif
                    
                    @if($exercise->duration)
                        <div class="stat-item">
                            <div class="d-flex justify-content-between">
                                <span>Duration:</span>
                                <strong>{{ $exercise->duration }} min</strong>
                            </div>
                        </div>
                    @endif
                    
                    <div class="stat-item">
                        <div class="d-flex justify-content-between">
                            <span>Equipment:</span>
                            <strong>{{ $exercise->equipment ?: 'None' }}</strong>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Related Exercises -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-link me-2"></i>Related Exercises
                    </h5>
                </div>
                <div class="card-body">
                    @php
                        $relatedExercises = \App\Models\Exercise::where('category', $exercise->category)
                            ->where('id', '!=', $exercise->id)
                            ->where('is_active', true)
                            ->take(3)
                            ->get();
                    @endphp
                    
                    @forelse($relatedExercises as $related)
                        <div class="related-exercise mb-2">
                            <a href="{{ route('member.exercises.show', $related) }}" 
                               class="text-decoration-none">
                                <div class="d-flex align-items-center p-2 border rounded hover-effect">
                                    <i class="fas fa-dumbbell text-primary me-2"></i>
                                    <div>
                                        <div class="fw-bold">{{ $related->name }}</div>
                                        <small class="text-muted">{{ ucfirst($related->category) }}</small>
                                    </div>
                                </div>
                            </a>
                        </div>
                    @empty
                        <p class="text-muted">No related exercises found.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.exercise-image {
    height: 300px;
    object-fit: cover;
}

.exercise-placeholder {
    height: 300px;
    background: #f8f9fa;
}

.info-item {
    padding: 0.5rem 0;
    border-bottom: 1px solid #dee2e6;
}

.info-item:last-child {
    border-bottom: none;
}

.stat-item {
    padding: 0.5rem 0;
    border-bottom: 1px solid #dee2e6;
}

.stat-item:last-child {
    border-bottom: none;
}

.instructions-box {
    border-left: 4px solid #667eea;
}

.muscle-groups .badge,
.equipment-list .badge {
    font-size: 0.8rem;
}

.hover-effect:hover {
    background-color: #f8f9fa;
    transform: translateY(-1px);
    transition: all 0.2s ease;
}

.related-exercise {
    transition: all 0.2s ease;
}
</style>

<script>
function addToWorkout() {
    // Simulate adding to workout
    const btn = event.target;
    const originalText = btn.innerHTML;
    
    btn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Adding...';
    btn.disabled = true;
    
    setTimeout(() => {
        btn.innerHTML = '<i class="fas fa-check me-2"></i>Added!';
        btn.classList.remove('btn-primary');
        btn.classList.add('btn-success');
        
        setTimeout(() => {
            btn.innerHTML = originalText;
            btn.classList.remove('btn-success');
            btn.classList.add('btn-primary');
            btn.disabled = false;
        }, 2000);
    }, 1000);
}

function addToFavorites() {
    // Simulate adding to favorites
    const btn = event.target;
    const originalText = btn.innerHTML;
    
    btn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Adding...';
    btn.disabled = true;
    
    setTimeout(() => {
        btn.innerHTML = '<i class="fas fa-heart me-2"></i>Favorited!';
        
        setTimeout(() => {
            btn.disabled = false;
        }, 2000);
    }, 1000);
}
</script>
@endsection
