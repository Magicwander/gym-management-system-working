@extends('admin.layouts.app')

@section('title', 'My Clients')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">My Clients</h1>
        <a href="{{ route('dashboard') }}" class="btn btn-secondary btn-sm">
            <i class="fas fa-arrow-left fa-sm text-white-50"></i> Back to Dashboard
        </a>
    </div>

    @if($clients->count() > 0)
        <div class="row">
            @foreach($clients as $client)
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="card shadow h-100">
                    <div class="card-header bg-primary text-white">
                        <div class="d-flex align-items-center">
                            <div class="me-3">
                                <div class="bg-white text-primary rounded-circle d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                                    <i class="fas fa-user fa-lg"></i>
                                </div>
                            </div>
                            <div>
                                <h6 class="mb-0">{{ $client->name }}</h6>
                                <small class="text-light">{{ $client->email }}</small>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row text-center mb-3">
                            <div class="col-6">
                                <div class="border rounded p-2">
                                    <h5 class="text-primary mb-0">{{ $trainer->assignedWorkouts->where('user_id', $client->id)->count() }}</h5>
                                    <small class="text-muted">Total Workouts</small>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="border rounded p-2">
                                    <h5 class="text-success mb-0">{{ $trainer->assignedWorkouts->where('user_id', $client->id)->where('status', 'completed')->count() }}</h5>
                                    <small class="text-muted">Completed</small>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <p class="mb-1"><strong>Phone:</strong> {{ $client->phone ?? 'Not provided' }}</p>
                            <p class="mb-1"><strong>Gender:</strong> {{ $client->gender ? ucfirst($client->gender) : 'Not specified' }}</p>
                            <p class="mb-1"><strong>Status:</strong> 
                                <span class="badge bg-{{ $client->is_active ? 'success' : 'danger' }}">
                                    {{ $client->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </p>
                        </div>

                        @php
                            $latestWorkout = $trainer->assignedWorkouts->where('user_id', $client->id)->sortByDesc('workout_date')->first();
                        @endphp
                        @if($latestWorkout)
                        <div class="mb-3">
                            <h6 class="text-muted">Latest Workout:</h6>
                            <p class="mb-1"><strong>{{ $latestWorkout->name }}</strong></p>
                            <small class="text-muted">{{ $latestWorkout->workout_date->format('M d, Y') }}</small>
                        </div>
                        @endif
                    </div>
                    <div class="card-footer">
                        <div class="d-grid gap-2">
                            <a href="{{ route('trainer.client-progress', $client->id) }}" class="btn btn-primary btn-sm">
                                <i class="fas fa-chart-line me-1"></i> View Progress
                            </a>
                            <a href="{{ route('trainer.workouts.create') }}?client={{ $client->id }}" class="btn btn-success btn-sm">
                                <i class="fas fa-plus me-1"></i> Create Workout
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    @else
        <div class="card shadow">
            <div class="card-body text-center py-5">
                <i class="fas fa-users fa-4x text-muted mb-4"></i>
                <h4 class="text-muted">No Clients Assigned</h4>
                <p class="text-muted mb-4">You don't have any clients assigned yet. Contact your admin to get clients assigned to you.</p>
                <a href="{{ route('dashboard') }}" class="btn btn-primary">
                    <i class="fas fa-arrow-left me-2"></i> Back to Dashboard
                </a>
            </div>
        </div>
    @endif
</div>

<style>
.card {
    transition: transform 0.2s;
}
.card:hover {
    transform: translateY(-5px);
}
</style>
@endsection
