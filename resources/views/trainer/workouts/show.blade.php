@extends('trainer.layouts.app')

@section('title', 'Workout Details')
@section('page-title', 'Workout Plan Details')

@section('page-actions')
    <div class="btn-group">
        <a href="{{ route('trainer.workouts.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-2"></i>Back to Workouts
        </a>
        <a href="{{ route('trainer.workouts.edit', $workout) }}" class="btn btn-warning">
            <i class="fas fa-edit me-2"></i>Edit
        </a>
        @if($workout->status === 'scheduled')
            <form action="{{ route('trainer.workouts.complete', $workout) }}" method="POST" class="d-inline">
                @csrf
                @method('PATCH')
                <button type="submit" class="btn btn-success">
                    <i class="fas fa-check me-2"></i>Mark Complete
                </button>
            </form>
        @endif
    </div>
@endsection

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-dumbbell me-2"></i>{{ $workout->name }}
                </h5>
            </div>
            <div class="card-body">
                <div class="row mb-4">
                    <div class="col-md-6">
                        <h6><i class="fas fa-user me-2"></i>Member Information</h6>
                        <p class="mb-1"><strong>Name:</strong> {{ $workout->member->name }}</p>
                        <p class="mb-1"><strong>Email:</strong> {{ $workout->member->email }}</p>
                    </div>
                    <div class="col-md-6">
                        <h6><i class="fas fa-user-tie me-2"></i>Trainer Information</h6>
                        <p class="mb-1"><strong>Name:</strong> {{ $workout->trainer->name }}</p>
                        <p class="mb-1"><strong>Email:</strong> {{ $workout->trainer->email }}</p>
                    </div>
                </div>
                
                @if($workout->description)
                <div class="mb-4">
                    <h6><i class="fas fa-info-circle me-2"></i>Description</h6>
                    <p>{{ $workout->description }}</p>
                </div>
                @endif
                
                <div class="row mb-4">
                    <div class="col-md-3">
                        <h6><i class="fas fa-calendar me-2"></i>Date</h6>
                        <p>{{ $workout->workout_date->format('M d, Y') }}</p>
                    </div>
                    <div class="col-md-3">
                        <h6><i class="fas fa-clock me-2"></i>Duration</h6>
                        <p>{{ $workout->duration }} minutes</p>
                    </div>
                    <div class="col-md-3">
                        <h6><i class="fas fa-signal me-2"></i>Difficulty</h6>
                        <span class="badge bg-{{ $workout->difficulty_level === 'beginner' ? 'success' : ($workout->difficulty_level === 'intermediate' ? 'warning' : 'danger') }} fs-6">
                            {{ ucfirst($workout->difficulty_level) }}
                        </span>
                    </div>
                    <div class="col-md-3">
                        <h6><i class="fas fa-fire me-2"></i>Calories Target</h6>
                        <p>{{ $workout->calories_target ?? 'Not set' }}</p>
                    </div>
                </div>
                
                @if($workout->notes)
                <div class="mb-4">
                    <h6><i class="fas fa-sticky-note me-2"></i>Notes</h6>
                    <p>{{ $workout->notes }}</p>
                </div>
                @endif
            </div>
        </div>
        
        <!-- Exercises -->
        <div class="card mt-4">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-list me-2"></i>Exercises ({{ $workout->exercises->count() }})
                </h5>
            </div>
            <div class="card-body">
                @if($workout->exercises->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Exercise</th>
                                    <th>Sets</th>
                                    <th>Reps</th>
                                    <th>Weight (kg)</th>
                                    <th>Rest (sec)</th>
                                    <th>Notes</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($workout->exercises as $exercise)
                                <tr>
                                    <td><strong>{{ $exercise->name }}</strong></td>
                                    <td>{{ $exercise->pivot->sets }}</td>
                                    <td>{{ $exercise->pivot->reps }}</td>
                                    <td>{{ $exercise->pivot->weight ?? '-' }}</td>
                                    <td>{{ $exercise->pivot->rest_time ?? '-' }}</td>
                                    <td>{{ $exercise->pivot->notes ?? '-' }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-4">
                        <i class="fas fa-list fa-2x text-muted mb-3"></i>
                        <p class="text-muted">No exercises added to this workout yet.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <!-- Status Card -->
        <div class="card">
            <div class="card-header">
                <h6 class="card-title mb-0">
                    <i class="fas fa-info me-2"></i>Workout Status
                </h6>
            </div>
            <div class="card-body">
                <div class="text-center mb-3">
                    <span class="badge bg-{{ $workout->status === 'completed' ? 'success' : ($workout->status === 'in_progress' ? 'primary' : ($workout->status === 'cancelled' ? 'danger' : 'secondary')) }} fs-6">
                        {{ ucfirst(str_replace('_', ' ', $workout->status)) }}
                    </span>
                </div>
                
                <div class="mb-3">
                    <small class="text-muted">Created:</small><br>
                    <strong>{{ $workout->created_at->format('M d, Y g:i A') }}</strong>
                </div>
                
                <div class="mb-3">
                    <small class="text-muted">Last Updated:</small><br>
                    <strong>{{ $workout->updated_at->format('M d, Y g:i A') }}</strong>
                </div>
            </div>
        </div>
        
        <!-- Price Card -->
        <div class="card mt-3">
            <div class="card-header">
                <h6 class="card-title mb-0">
                    <i class="fas fa-money-bill me-2"></i>Pricing
                </h6>
            </div>
            <div class="card-body">
                <div class="text-center">
                    @if($workout->price_lkr)
                        <h3 class="text-success mb-0">LKR {{ number_format($workout->price_lkr, 2) }}</h3>
                        <small class="text-muted">Sri Lankan Rupees</small>
                    @else
                        <p class="text-muted mb-0">Price not set</p>
                    @endif
                </div>
            </div>
        </div>
        
        <!-- Actions Card -->
        <div class="card mt-3">
            <div class="card-header">
                <h6 class="card-title mb-0">
                    <i class="fas fa-cogs me-2"></i>Actions
                </h6>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <form action="{{ route('trainer.workouts.duplicate', $workout) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-outline-info w-100">
                            <i class="fas fa-copy me-2"></i>Duplicate Workout
                        </button>
                    </form>
                    
                    @if($workout->status === 'scheduled')
                        <form action="{{ route('trainer.workouts.complete', $workout) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="btn btn-success w-100">
                                <i class="fas fa-check me-2"></i>Mark as Complete
                            </button>
                        </form>
                    @endif
                    
                    <form action="{{ route('trainer.workouts.destroy', $workout) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this workout plan?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-outline-danger w-100">
                            <i class="fas fa-trash me-2"></i>Delete Workout
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection