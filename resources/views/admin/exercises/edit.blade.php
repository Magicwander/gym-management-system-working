@extends('admin.layouts.app')

@section('title', 'Edit Exercise')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Edit Exercise</h1>
        <a href="{{ route('admin.exercises.index') }}" class="btn btn-secondary btn-sm">
            <i class="fas fa-arrow-left fa-sm text-white-50"></i> Back to Exercises
        </a>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Exercise Information</h6>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.exercises.update', $exercise) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label for="name" class="form-label">Exercise Name</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                   id="name" name="name" value="{{ old('name', $exercise->name) }}" required 
                                   placeholder="e.g., Push-ups">
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label for="category" class="form-label">Category</label>
                            <select class="form-control @error('category') is-invalid @enderror" id="category" name="category" required>
                                <option value="">Select Category</option>
                                <option value="strength" {{ old('category', $exercise->category) == 'strength' ? 'selected' : '' }}>Strength</option>
                                <option value="cardio" {{ old('category', $exercise->category) == 'cardio' ? 'selected' : '' }}>Cardio</option>
                                <option value="flexibility" {{ old('category', $exercise->category) == 'flexibility' ? 'selected' : '' }}>Flexibility</option>
                                <option value="balance" {{ old('category', $exercise->category) == 'balance' ? 'selected' : '' }}>Balance</option>
                                <option value="sports" {{ old('category', $exercise->category) == 'sports' ? 'selected' : '' }}>Sports</option>
                            </select>
                            @error('category')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label for="muscle_group" class="form-label">Primary Muscle Group</label>
                            <select class="form-control @error('muscle_group') is-invalid @enderror" id="muscle_group" name="muscle_group" required>
                                <option value="">Select Muscle Group</option>
                                <option value="chest" {{ old('muscle_group', $exercise->muscle_group) == 'chest' ? 'selected' : '' }}>Chest</option>
                                <option value="back" {{ old('muscle_group', $exercise->muscle_group) == 'back' ? 'selected' : '' }}>Back</option>
                                <option value="shoulders" {{ old('muscle_group', $exercise->muscle_group) == 'shoulders' ? 'selected' : '' }}>Shoulders</option>
                                <option value="arms" {{ old('muscle_group', $exercise->muscle_group) == 'arms' ? 'selected' : '' }}>Arms</option>
                                <option value="legs" {{ old('muscle_group', $exercise->muscle_group) == 'legs' ? 'selected' : '' }}>Legs</option>
                                <option value="core" {{ old('muscle_group', $exercise->muscle_group) == 'core' ? 'selected' : '' }}>Core</option>
                                <option value="full_body" {{ old('muscle_group', $exercise->muscle_group) == 'full_body' ? 'selected' : '' }}>Full Body</option>
                            </select>
                            @error('muscle_group')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label for="difficulty_level" class="form-label">Difficulty Level</label>
                            <select class="form-control @error('difficulty_level') is-invalid @enderror" id="difficulty_level" name="difficulty_level" required>
                                <option value="">Select Difficulty</option>
                                <option value="beginner" {{ old('difficulty_level', $exercise->difficulty_level) == 'beginner' ? 'selected' : '' }}>Beginner</option>
                                <option value="intermediate" {{ old('difficulty_level', $exercise->difficulty_level) == 'intermediate' ? 'selected' : '' }}>Intermediate</option>
                                <option value="advanced" {{ old('difficulty_level', $exercise->difficulty_level) == 'advanced' ? 'selected' : '' }}>Advanced</option>
                            </select>
                            @error('difficulty_level')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="form-group mb-3">
                    <label for="description" class="form-label">Description</label>
                    <textarea class="form-control @error('description') is-invalid @enderror" 
                              id="description" name="description" rows="3" 
                              placeholder="Brief description of the exercise...">{{ old('description', $exercise->description) }}</textarea>
                    @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group mb-3">
                    <label for="equipment_needed" class="form-label">Equipment Needed</label>
                    <input type="text" class="form-control @error('equipment_needed') is-invalid @enderror" 
                           id="equipment_needed" name="equipment_needed" value="{{ old('equipment_needed', $exercise->equipment_needed) }}" 
                           placeholder="e.g., Dumbbells, Barbell, None">
                    @error('equipment_needed')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group mb-3">
                    <label for="instructions" class="form-label">Instructions</label>
                    <textarea class="form-control @error('instructions') is-invalid @enderror" 
                              id="instructions" name="instructions" rows="5" 
                              placeholder="Step-by-step instructions for performing the exercise...">{{ old('instructions', $exercise->instructions) }}</textarea>
                    @error('instructions')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label for="video_url" class="form-label">Video URL (Optional)</label>
                            <input type="url" class="form-control @error('video_url') is-invalid @enderror" 
                                   id="video_url" name="video_url" value="{{ old('video_url', $exercise->video_url) }}" 
                                   placeholder="https://youtube.com/watch?v=...">
                            @error('video_url')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label for="image_url" class="form-label">Image URL (Optional)</label>
                            <input type="url" class="form-control @error('image_url') is-invalid @enderror" 
                                   id="image_url" name="image_url" value="{{ old('image_url', $exercise->image_url) }}" 
                                   placeholder="https://example.com/image.jpg">
                            @error('image_url')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group mb-3">
                            <label for="duration_minutes" class="form-label">Typical Duration (Minutes)</label>
                            <input type="number" min="1" class="form-control @error('duration_minutes') is-invalid @enderror" 
                                   id="duration_minutes" name="duration_minutes" value="{{ old('duration_minutes', $exercise->duration_minutes) }}" 
                                   placeholder="e.g., 5">
                            @error('duration_minutes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="col-md-4">
                        <div class="form-group mb-3">
                            <label for="calories_burned_per_minute" class="form-label">Calories/Minute</label>
                            <input type="number" min="1" class="form-control @error('calories_burned_per_minute') is-invalid @enderror" 
                                   id="calories_burned_per_minute" name="calories_burned_per_minute" value="{{ old('calories_burned_per_minute', $exercise->calories_burned_per_minute) }}" 
                                   placeholder="e.g., 8">
                            @error('calories_burned_per_minute')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="col-md-4">
                        <div class="form-group mb-3">
                            <label for="is_active" class="form-label">Status</label>
                            <select class="form-control @error('is_active') is-invalid @enderror" id="is_active" name="is_active">
                                <option value="1" {{ old('is_active', $exercise->is_active) == '1' ? 'selected' : '' }}>Active</option>
                                <option value="0" {{ old('is_active', $exercise->is_active) == '0' ? 'selected' : '' }}>Inactive</option>
                            </select>
                            @error('is_active')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Update Exercise
                    </button>
                    <a href="{{ route('admin.exercises.index') }}" class="btn btn-secondary">
                        <i class="fas fa-times"></i> Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Current Exercise Details -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-info">Current Exercise Details</h6>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <p><strong>Name:</strong> {{ $exercise->name }}</p>
                    <p><strong>Category:</strong> 
                        <span class="badge bg-primary">{{ ucfirst($exercise->category) }}</span>
                    </p>
                    <p><strong>Muscle Group:</strong> 
                        <span class="badge bg-secondary">{{ ucfirst(str_replace('_', ' ', $exercise->muscle_group)) }}</span>
                    </p>
                    <p><strong>Difficulty:</strong> 
                        <span class="badge bg-{{ $exercise->difficulty_level === 'beginner' ? 'success' : ($exercise->difficulty_level === 'intermediate' ? 'warning' : 'danger') }}">
                            {{ ucfirst($exercise->difficulty_level) }}
                        </span>
                    </p>
                </div>
                <div class="col-md-6">
                    <p><strong>Equipment:</strong> {{ $exercise->equipment_needed ?: 'None' }}</p>
                    <p><strong>Duration:</strong> {{ $exercise->duration_minutes ? $exercise->duration_minutes . ' minutes' : 'Not set' }}</p>
                    <p><strong>Calories/Min:</strong> {{ $exercise->calories_burned_per_minute ?: 'Not set' }}</p>
                    <p><strong>Status:</strong> 
                        <span class="badge bg-{{ $exercise->is_active ? 'success' : 'danger' }}">
                            {{ $exercise->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
