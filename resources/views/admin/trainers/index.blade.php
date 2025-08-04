@extends('admin.layouts.app')

@section('title', 'Trainers Management')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Trainers Management</h1>
        <a href="{{ route('admin.trainers.create') }}" class="btn btn-primary btn-sm">
            <i class="fas fa-plus fa-sm text-white-50"></i> Add New Trainer
        </a>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Trainers</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $trainers->total() }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-user-tie fa-2x text-gray-300"></i>
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
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Active Trainers</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $trainers->where('is_active', true)->count() }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-check-circle fa-2x text-gray-300"></i>
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
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Total Workouts Assigned</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $trainers->sum(function($trainer) { return $trainer->assignedWorkouts->count(); }) }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-running fa-2x text-gray-300"></i>
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
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Avg Workouts/Trainer</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $trainers->count() > 0 ? round($trainers->sum(function($trainer) { return $trainer->assignedWorkouts->count(); }) / $trainers->count(), 1) : 0 }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-chart-line fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">All Trainers</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Trainer</th>
                            <th>Contact Info</th>
                            <th>Gender</th>
                            <th>Assigned Workouts</th>
                            <th>Status</th>
                            <th>Joined</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($trainers as $trainer)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="me-3">
                                        <div class="icon-circle bg-success">
                                            <i class="fas fa-user-tie text-white"></i>
                                        </div>
                                    </div>
                                    <div>
                                        <div class="font-weight-bold">{{ $trainer->name }}</div>
                                        <div class="small text-gray-500">Professional Trainer</div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div>
                                    <div><i class="fas fa-envelope me-2 text-gray-400"></i>{{ $trainer->email }}</div>
                                    @if($trainer->phone)
                                        <div class="mt-1"><i class="fas fa-phone me-2 text-gray-400"></i>{{ $trainer->phone }}</div>
                                    @endif
                                </div>
                            </td>
                            <td>
                                @if($trainer->gender)
                                    <span class="badge bg-{{ $trainer->gender == 'male' ? 'primary' : ($trainer->gender == 'female' ? 'pink' : 'secondary') }}">
                                        <i class="fas fa-{{ $trainer->gender == 'male' ? 'mars' : ($trainer->gender == 'female' ? 'venus' : 'genderless') }} me-1"></i>
                                        {{ ucfirst($trainer->gender) }}
                                    </span>
                                @else
                                    <span class="text-muted">Not specified</span>
                                @endif
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="me-2">
                                        <span class="badge bg-info">{{ $trainer->assignedWorkouts->count() }}</span>
                                    </div>
                                    <div>
                                        <div class="small">
                                            <span class="text-success">{{ $trainer->assignedWorkouts->where('status', 'completed')->count() }} completed</span>
                                        </div>
                                        <div class="small">
                                            <span class="text-warning">{{ $trainer->assignedWorkouts->where('status', 'planned')->count() }} planned</span>
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="badge bg-{{ $trainer->is_active ? 'success' : 'danger' }}">
                                    <i class="fas fa-{{ $trainer->is_active ? 'check-circle' : 'times-circle' }} me-1"></i>
                                    {{ $trainer->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td>
                                <div>
                                    <div class="font-weight-bold">{{ $trainer->created_at->format('M d, Y') }}</div>
                                    <div class="small text-gray-500">{{ $trainer->created_at->diffForHumans() }}</div>
                                </div>
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('admin.trainers.show', $trainer) }}" class="btn btn-info btn-sm">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.trainers.edit', $trainer) }}" class="btn btn-warning btn-sm">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('admin.trainers.destroy', $trainer) }}" method="POST" style="display: inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure? This will also remove all assigned workouts.')">
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
                {{ $trainers->links() }}
            </div>
        </div>
    </div>
</div>

<style>
.icon-circle {
    height: 2.5rem;
    width: 2.5rem;
    border-radius: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
}

.bg-pink {
    background-color: #e83e8c !important;
}
</style>
@endsection
