@extends('member.layout')

@section('title', 'Browse Exercises')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="page-header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h2 class="mb-1">
                    <i class="fas fa-dumbbell me-2"></i>Exercise Library
                </h2>
                <p class="mb-0">Discover exercises to enhance your workouts</p>
            </div>
            <div class="d-flex gap-2">
                <select class="form-select" id="categoryFilter">
                    <option value="">All Categories</option>
                    @foreach($categories as $category)
                        <option value="{{ $category }}">{{ ucfirst($category) }}</option>
                    @endforeach
                </select>
                <select class="form-select" id="difficultyFilter">
                    <option value="">All Levels</option>
                    <option value="beginner">Beginner</option>
                    <option value="intermediate">Intermediate</option>
                    <option value="advanced">Advanced</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Exercise Grid -->
    <div class="row" id="exerciseGrid">
        @forelse($exercises as $exercise)
            <div class="col-lg-4 col-md-6 mb-4 exercise-card" 
                 data-category="{{ $exercise->category }}" 
                 data-difficulty="{{ $exercise->difficulty }}">
                <div class="card h-100 exercise-item">
                    @if($exercise->image)
                        <img src="{{ asset('storage/' . $exercise->image) }}" 
                             class="card-img-top exercise-image" 
                             alt="{{ $exercise->name }}">
                    @else
                        <div class="card-img-top exercise-placeholder d-flex align-items-center justify-content-center">
                            <i class="fas fa-dumbbell fa-3x text-muted"></i>
                        </div>
                    @endif
                    
                    <div class="card-body d-flex flex-column">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <h5 class="card-title mb-0">{{ $exercise->name }}</h5>
                            @if($exercise->difficulty)
                                <span class="badge bg-{{ 
                                    $exercise->difficulty == 'beginner' ? 'success' : 
                                    ($exercise->difficulty == 'intermediate' ? 'warning' : 'danger') 
                                }}">
                                    {{ ucfirst($exercise->difficulty) }}
                                </span>
                            @endif
                        </div>
                        
                        <div class="mb-2">
                            <small class="text-muted">
                                <i class="fas fa-tag me-1"></i>{{ ucfirst($exercise->category) }}
                            </small>
                        </div>
                        
                        @if($exercise->description)
                            <p class="card-text text-muted small flex-grow-1">
                                {{ Str::limit($exercise->description, 100) }}
                            </p>
                        @endif
                        
                        <div class="exercise-details mb-3">
                            @if($exercise->muscle_groups)
                                <div class="mb-1">
                                    <small class="text-muted">
                                        <i class="fas fa-bullseye me-1"></i>
                                        <strong>Target:</strong> {{ $exercise->muscle_groups }}
                                    </small>
                                </div>
                            @endif
                            
                            @if($exercise->equipment)
                                <div class="mb-1">
                                    <small class="text-muted">
                                        <i class="fas fa-tools me-1"></i>
                                        <strong>Equipment:</strong> {{ $exercise->equipment }}
                                    </small>
                                </div>
                            @endif
                            
                            @if($exercise->duration)
                                <div class="mb-1">
                                    <small class="text-muted">
                                        <i class="fas fa-clock me-1"></i>
                                        <strong>Duration:</strong> {{ $exercise->duration }} min
                                    </small>
                                </div>
                            @endif
                        </div>
                        
                        <div class="mt-auto">
                            <div class="d-grid gap-2">
                                <a href="{{ route('member.exercises.show', $exercise) }}" 
                                   class="btn btn-primary btn-sm">
                                    <i class="fas fa-eye me-2"></i>View Details
                                </a>
                                <button class="btn btn-outline-success btn-sm add-to-favorites" 
                                        data-exercise-id="{{ $exercise->id }}">
                                    <i class="fas fa-heart me-2"></i>Add to Favorites
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="text-center py-5">
                    <i class="fas fa-dumbbell fa-4x text-muted mb-3"></i>
                    <h5 class="text-muted">No exercises found</h5>
                    <p class="text-muted">Try adjusting your filters or check back later for new exercises.</p>
                </div>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($exercises->hasPages())
        <div class="d-flex justify-content-center mt-4">
            {{ $exercises->links() }}
        </div>
    @endif
</div>

<!-- Exercise Quick View Modal -->
<div class="modal fade" id="exerciseModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Exercise Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="exerciseModalBody">
                <!-- Content loaded via AJAX -->
            </div>
        </div>
    </div>
</div>

<style>
.exercise-image {
    height: 200px;
    object-fit: cover;
}

.exercise-placeholder {
    height: 200px;
    background: #f8f9fa;
}

.exercise-item {
    transition: all 0.3s ease;
    border: none;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.exercise-item:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 16px rgba(0,0,0,0.15);
}

.exercise-details {
    background: #f8f9fa;
    border-radius: 8px;
    padding: 0.75rem;
}

.badge {
    font-size: 0.75em;
}

.card-title {
    font-size: 1.1rem;
    font-weight: 600;
}

.btn-group-sm .btn {
    font-size: 0.8rem;
}

@media (max-width: 768px) {
    .exercise-card {
        margin-bottom: 1rem;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const categoryFilter = document.getElementById('categoryFilter');
    const difficultyFilter = document.getElementById('difficultyFilter');
    const exerciseCards = document.querySelectorAll('.exercise-card');
    
    // Filter functionality
    function filterExercises() {
        const selectedCategory = categoryFilter.value;
        const selectedDifficulty = difficultyFilter.value;
        
        exerciseCards.forEach(card => {
            const cardCategory = card.getAttribute('data-category');
            const cardDifficulty = card.getAttribute('data-difficulty');
            
            const categoryMatch = !selectedCategory || cardCategory === selectedCategory;
            const difficultyMatch = !selectedDifficulty || cardDifficulty === selectedDifficulty;
            
            if (categoryMatch && difficultyMatch) {
                card.style.display = 'block';
            } else {
                card.style.display = 'none';
            }
        });
    }
    
    categoryFilter.addEventListener('change', filterExercises);
    difficultyFilter.addEventListener('change', filterExercises);
    
    // Add to favorites functionality
    document.querySelectorAll('.add-to-favorites').forEach(button => {
        button.addEventListener('click', function() {
            const exerciseId = this.getAttribute('data-exercise-id');
            const btn = this;
            
            // Simulate adding to favorites (you can implement actual functionality)
            btn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Adding...';
            btn.disabled = true;
            
            setTimeout(() => {
                btn.innerHTML = '<i class="fas fa-check me-2"></i>Added!';
                btn.classList.remove('btn-outline-success');
                btn.classList.add('btn-success');
                
                setTimeout(() => {
                    btn.innerHTML = '<i class="fas fa-heart me-2"></i>Favorited';
                    btn.disabled = false;
                }, 1000);
            }, 1000);
        });
    });
    
    // Quick view functionality (optional)
    document.querySelectorAll('.btn-primary').forEach(button => {
        button.addEventListener('click', function(e) {
            // You can add quick view modal functionality here
            // For now, it will just navigate to the detail page
        });
    });
});
</script>
@endsection
