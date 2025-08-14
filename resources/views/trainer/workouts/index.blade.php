@extends('trainer.layouts.app')

@section('title', 'Workout Plans')
@section('page-title', 'Workout Plans')

@section('page-actions')
    <a href="{{ route('trainer.workouts.create') }}" class="btn btn-success">
        <i class="fas fa-plus me-2"></i>Create New Workout Plan
    </a>
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-dumbbell me-2"></i>My Workout Plans
                </h5>
            </div>
            <div class="card-body">
                @if($workouts->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Workout Name</th>
                                    <th>Member</th>
                                    <th>Date</th>
                                    <th>Duration</th>
                                    <th>Difficulty</th>
                                    <th>Price (LKR)</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($workouts as $workout)
                                <tr>
                                    <td>
                                        <strong>{{ $workout->name }}</strong>
                                        @if($workout->description)
                                            <br><small class="text-muted">{{ Str::limit($workout->description, 50) }}</small>
                                        @endif
                                    </td>
                                    <td>{{ $workout->member->name }}</td>
                                    <td>{{ $workout->workout_date->format('M d, Y') }}</td>
                                    <td>{{ $workout->duration }} min</td>
                                    <td>
                                        <span class="badge bg-{{ $workout->difficulty_level === 'beginner' ? 'success' : ($workout->difficulty_level === 'intermediate' ? 'warning' : 'danger') }}">
                                            {{ ucfirst($workout->difficulty_level) }}
                                        </span>
                                    </td>
                                    <td>
                                        @if($workout->price_lkr)
                                            <strong>LKR {{ number_format($workout->price_lkr, 2) }}</strong>
                                        @else
                                            <span class="text-muted">Not set</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $workout->status === 'completed' ? 'success' : ($workout->status === 'in_progress' ? 'primary' : ($workout->status === 'cancelled' ? 'danger' : 'secondary')) }}">
                                            {{ ucfirst(str_replace('_', ' ', $workout->status)) }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('trainer.workouts.show', $workout) }}" class="btn btn-sm btn-outline-primary" title="View">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('trainer.workouts.edit', $workout) }}" class="btn btn-sm btn-outline-warning" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            @if($workout->status === 'scheduled')
                                                <form action="{{ route('trainer.workouts.complete', $workout) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit" class="btn btn-sm btn-outline-success" title="Mark Complete">
                                                        <i class="fas fa-check"></i>
                                                    </button>
                                                </form>
                                            @endif
                                            <form action="{{ route('trainer.workouts.duplicate', $workout) }}" method="POST" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-outline-info" title="Duplicate">
                                                    <i class="fas fa-copy"></i>
                                                </button>
                                            </form>
                                            <form action="{{ route('trainer.workouts.destroy', $workout) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete" onclick="return confirm('Are you sure you want to delete this workout plan?')">
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
                    
                    <div class="d-flex justify-content-center">
                        {{ $workouts->links() }}
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="fas fa-dumbbell fa-3x text-muted mb-3"></i>
                        <h5 class="text-muted">No workout plans created yet</h5>
                        <p class="text-muted">Create your first workout plan to get started!</p>
                        <a href="{{ route('trainer.workouts.create') }}" class="btn btn-success">
                            <i class="fas fa-plus me-2"></i>Create Workout Plan
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection