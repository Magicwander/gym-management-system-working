@extends('admin.layouts.app')

@section('title', 'Add New Exercise')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Add New Exercise</h1>
        <a href="{{ route('admin.exercises.index') }}" class="btn btn-secondary btn-sm">
            <i class="fas fa-arrow-left fa-sm text-white-50"></i> Back to Exercises
        </a>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Exercise Information</h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.exercises.store') }}" method="POST">
                        @csrf
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="name" class="form-label">Exercise Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                           id="name" name="name" value="{{ old('name') }}" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="category" class="form-label">Category <span class="text-danger">*</span></label>
                                    <select class="form-select @error('category') is-invalid @enderror" id="category" name="category" required>
                                        <option value="">Select Category</option>
                                        <option value="strength" {{ old('category') == 'strength' ? 'selected' : '' }}>Strength</option>
                                        <option value="cardio" {{ old('category') == 'cardio' ? 'selected' : '' }}>Cardio</option>
                                        <option value="flexibility" {{ old('category') == 'flexibility' ? 'selected' : '' }}>Flexibility</option>
                                        <option value="balance" {{ old('category') == 'balance' ? 'selected' : '' }}>Balance</option>
                                        <option value="sports" {{ old('category') == 'sports' ? 'selected' : '' }}>Sports</option>
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
                                    <label for="muscle_group" class="form-label">Muscle Group <span class="text-danger">*</span></label>
                                    <select class="form-select @error('muscle_group') is-invalid @enderror" id="muscle_group" name="muscle_group" required>
                                        <option value="">Select Muscle Group</option>
                                        <option value="chest" {{ old('muscle_group') == 'chest' ? 'selected' : '' }}>Chest</option>
                                        <option value="back" {{ old('muscle_group') == 'back' ? 'selected' : '' }}>Back</option>
                                        <option value="shoulders" {{ old('muscle_group') == 'shoulders' ? 'selected' : '' }}>Shoulders</option>
                                        <option value="arms" {{ old('muscle_group') == 'arms' ? 'selected' : '' }}>Arms</option>
                                        <option value="legs" {{ old('muscle_group') == 'legs' ? 'selected' : '' }}>Legs</option>
                                        <option value="core" {{ old('muscle_group') == 'core' ? 'selected' : '' }}>Core</option>
                                        <option value="full_body" {{ old('muscle_group') == 'full_body' ? 'selected' : '' }}>Full Body</option>
                                    </select>
                                    @error('muscle_group')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="difficulty_level" class="form-label">Difficulty Level <span class="text-danger">*</span></label>
                                    <select class="form-select @error('difficulty_level') is-invalid @enderror" id="difficulty_level" name="difficulty_level" required>
                                        <option value="">Select Difficulty</option>
                                        <option value="beginner" {{ old('difficulty_level') == 'beginner' ? 'selected' : '' }}>Beginner</option>
                                        <option value="intermediate" {{ old('difficulty_level') == 'intermediate' ? 'selected' : '' }}>Intermediate</option>
                                        <option value="advanced" {{ old('difficulty_level') == 'advanced' ? 'selected' : '' }}>Advanced</option>
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
                                      id="description" name="description" rows="3">{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="form-group mb-3">
                            <label for="equipment_needed" class="form-label">Equipment Needed</label>
                            <input type="text" class="form-control @error('equipment_needed') is-invalid @enderror" 
                                   id="equipment_needed" name="equipment_needed" value="{{ old('equipment_needed') }}" 
                                   placeholder="e.g., Dumbbells, Barbell, None">
                            @error('equipment_needed')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="form-group mb-3">
                            <label for="instructions" class="form-label">Instructions</label>
                            <textarea class="form-control @error('instructions') is-invalid @enderror" 
                                      id="instructions" name="instructions" rows="4">{{ old('instructions') }}</textarea>
                            @error('instructions')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="duration_minutes" class="form-label">Duration (Minutes)</label>
                                    <input type="number" class="form-control @error('duration_minutes') is-invalid @enderror" 
                                           id="duration_minutes" name="duration_minutes" value="{{ old('duration_minutes') }}" min="1">
                                    @error('duration_minutes')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="calories_burned_per_minute" class="form-label">Calories Burned per Minute</label>
                                    <input type="number" class="form-control @error('calories_burned_per_minute') is-invalid @enderror" 
                                           id="calories_burned_per_minute" name="calories_burned_per_minute" value="{{ old('calories_burned_per_minute') }}" min="1">
                                    @error('calories_burned_per_minute')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="video_url" class="form-label">Video URL</label>
                                    <input type="url" class="form-control @error('video_url') is-invalid @enderror" 
                                           id="video_url" name="video_url" value="{{ old('video_url') }}" 
                                           placeholder="https://youtube.com/watch?v=...">
                                    @error('video_url')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="image_url" class="form-label">Image URL</label>
                                    <input type="url" class="form-control @error('image_url') is-invalid @enderror" 
                                           id="image_url" name="image_url" value="{{ old('image_url') }}" 
                                           placeholder="https://example.com/image.jpg">
                                    @error('image_url')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-group mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" 
                                       {{ old('is_active', '1') ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_active">
                                    Active (Available for workouts)
                                </label>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>Create Exercise
                            </button>
                            <a href="{{ route('admin.exercises.index') }}" class="btn btn-secondary ms-2">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Exercise Guidelines</h6>
                </div>
                <div class="card-body">
                    <div class="text-center">
                        <i class="fas fa-dumbbell fa-3x text-gray-300 mb-3"></i>
                    </div>
                    <ul class="list-unstyled">
                        <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Choose appropriate difficulty level</li>
                        <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Provide clear instructions</li>
                        <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Specify required equipment</li>
                        <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Add video for demonstration</li>
                        <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Estimate calorie burn accurately</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
