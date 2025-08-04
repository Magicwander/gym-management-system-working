@extends('admin.layouts.app')

@section('title', 'Workouts Management')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Workouts Management</h1>
        <a href="{{ route('admin.workouts.create') }}" class="btn btn-primary btn-sm">
            <i class="fas fa-plus fa-sm text-white-50"></i> Create New Workout
        </a>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Workouts</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $workouts->total() }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-running fa-2x text-gray-300"></i>
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
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Completed</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $workouts->where('status', 'completed')->count() }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-check-circle fa-2x text-gray-300"></i>
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
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">In Progress</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $workouts->where('status', 'in_progress')->count() }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clock fa-2x text-gray-300"></i>
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
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Planned</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $workouts->where('status', 'planned')->count() }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-calendar fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">All Workouts</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Workout</th>
                            <th>Member</th>
                            <th>Trainer</th>
                            <th>Type</th>
                            <th>Date</th>
                            <th>Duration</th>
                            <th>Calories</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($workouts as $workout)
                        <tr>
                            <td>
                                <div>
                                    <div class="font-weight-bold">{{ $workout->name }}</div>
                                    @if($workout->description)
                                        <div class="small text-gray-500">{{ Str::limit($workout->description, 50) }}</div>
                                    @endif
                                </div>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="me-2">
                                        <div class="icon-circle bg-primary">
                                            <i class="fas fa-user text-white"></i>
                                        </div>
                                    </div>
                                    <div>
                                        <div class="font-weight-bold">{{ $workout->user->name }}</div>
                                        <div class="small text-gray-500">{{ $workout->user->email }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                @if($workout->trainer)
                                    <div class="d-flex align-items-center">
                                        <div class="me-2">
                                            <div class="icon-circle bg-success">
                                                <i class="fas fa-user-tie text-white"></i>
                                            </div>
                                        </div>
                                        <div>
                                            <div class="font-weight-bold">{{ $workout->trainer->name }}</div>
                                            <div class="small text-gray-500">Trainer</div>
                                        </div>
                                    </div>
                                @else
                                    <span class="text-muted">Self-guided</span>
                                @endif
                            </td>
                            <td>
                                <span class="badge bg-{{ $workout->type == 'strength' ? 'primary' : ($workout->type == 'cardio' ? 'danger' : ($workout->type == 'mixed' ? 'warning' : 'info')) }}">
                                    {{ ucfirst($workout->type) }}
                                </span>
                            </td>
                            <td>
                                <div>
                                    <div class="font-weight-bold">{{ $workout->workout_date->format('M d, Y') }}</div>
                                    @if($workout->start_time)
                                        <div class="small text-gray-500">{{ $workout->start_time->format('H:i') }} - {{ $workout->end_time ? $workout->end_time->format('H:i') : 'Ongoing' }}</div>
                                    @endif
                                </div>
                            </td>
                            <td>{{ $workout->total_duration_minutes ? $workout->total_duration_minutes . ' min' : 'N/A' }}</td>
                            <td>{{ $workout->calories_burned ? $workout->calories_burned . ' cal' : 'N/A' }}</td>
                            <td>
                                @php
                                    $statusClass = 'secondary';
                                    $statusIcon = 'circle';
                                    if ($workout->status == 'completed') {
                                        $statusClass = 'success';
                                        $statusIcon = 'check-circle';
                                    } elseif ($workout->status == 'in_progress') {
                                        $statusClass = 'warning';
                                        $statusIcon = 'clock';
                                    } elseif ($workout->status == 'planned') {
                                        $statusClass = 'info';
                                        $statusIcon = 'calendar';
                                    } elseif ($workout->status == 'skipped') {
                                        $statusClass = 'danger';
                                        $statusIcon = 'times-circle';
                                    }
                                @endphp
                                <span class="badge bg-{{ $statusClass }}">
                                    <i class="fas fa-{{ $statusIcon }} me-1"></i>
                                    {{ ucfirst($workout->status) }}
                                </span>
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('admin.workouts.show', $workout) }}" class="btn btn-info btn-sm">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.workouts.edit', $workout) }}" class="btn btn-warning btn-sm">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('admin.workouts.destroy', $workout) }}" method="POST" style="display: inline;">
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
                {{ $workouts->links() }}
            </div>
        </div>
    </div>
</div>

<style>
.icon-circle {
    height: 2rem;
    width: 2rem;
    border-radius: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
}
</style>
@endsection
