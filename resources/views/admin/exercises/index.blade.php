@extends('admin.layouts.app')

@section('title', 'Exercise Library')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Exercise Library</h1>
        <a href="{{ route('admin.exercises.create') }}" class="btn btn-primary btn-sm">
            <i class="fas fa-plus fa-sm text-white-50"></i> Add New Exercise
        </a>
    </div>

    <!-- Filter Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Exercises</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $exercises->total() }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-dumbbell fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Strength Exercises</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $exercises->where('category', 'strength')->count() }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-fist-raised fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Cardio Exercises</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $exercises->where('category', 'cardio')->count() }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-heartbeat fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Active Exercises</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $exercises->where('is_active', true)->count() }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">All Exercises</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Category</th>
                            <th>Muscle Group</th>
                            <th>Difficulty</th>
                            <th>Equipment</th>
                            <th>Duration</th>
                            <th>Calories/Min</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($exercises as $exercise)
                        <tr>
                            <td>
                                <strong>{{ $exercise->name }}</strong>
                                @if($exercise->description)
                                    <br><small class="text-muted">{{ Str::limit($exercise->description, 50) }}</small>
                                @endif
                            </td>
                            <td>
                                <span class="badge bg-{{ $exercise->category == 'strength' ? 'primary' : ($exercise->category == 'cardio' ? 'danger' : 'info') }}">
                                    {{ ucfirst($exercise->category) }}
                                </span>
                            </td>
                            <td>{{ ucfirst(str_replace('_', ' ', $exercise->muscle_group)) }}</td>
                            <td>
                                <span class="badge bg-{{ $exercise->difficulty_level == 'beginner' ? 'success' : ($exercise->difficulty_level == 'intermediate' ? 'warning' : 'danger') }}">
                                    {{ ucfirst($exercise->difficulty_level) }}
                                </span>
                            </td>
                            <td>{{ $exercise->equipment_needed ?: 'None' }}</td>
                            <td>{{ $exercise->duration_minutes ? $exercise->duration_minutes . ' min' : 'N/A' }}</td>
                            <td>{{ $exercise->calories_burned_per_minute ?: 'N/A' }}</td>
                            <td>
                                <span class="badge bg-{{ $exercise->is_active ? 'success' : 'danger' }}">
                                    {{ $exercise->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('admin.exercises.show', $exercise) }}" class="btn btn-info btn-sm">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.exercises.edit', $exercise) }}" class="btn btn-warning btn-sm">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('admin.exercises.destroy', $exercise) }}" method="POST" style="display: inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            <div class="d-flex justify-content-center">
                {{ $exercises->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
