@extends('admin.layouts.app')

@section('title', 'Trainer Details')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Trainer Details</h1>
        <div>
            <a href="{{ route('admin.trainers.edit', $trainer) }}" class="btn btn-warning btn-sm">
                <i class="fas fa-edit fa-sm text-white-50"></i> Edit Trainer
            </a>
            <a href="{{ route('admin.trainers.index') }}" class="btn btn-secondary btn-sm">
                <i class="fas fa-arrow-left fa-sm text-white-50"></i> Back to Trainers
            </a>
        </div>
    </div>

    <div class="row">
        <!-- Trainer Information -->
        <div class="col-lg-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Personal Information</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>Full Name:</strong> {{ $trainer->name }}</p>
                            <p><strong>Email:</strong> {{ $trainer->email }}</p>
                            <p><strong>Phone:</strong> {{ $trainer->phone ?? 'Not provided' }}</p>
                            <p><strong>Gender:</strong> {{ $trainer->gender ? ucfirst($trainer->gender) : 'Not specified' }}</p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Date of Birth:</strong> {{ $trainer->date_of_birth ? $trainer->date_of_birth->format('F d, Y') : 'Not provided' }}</p>
                            <p><strong>Age:</strong> {{ $trainer->date_of_birth ? $trainer->date_of_birth->age . ' years' : 'N/A' }}</p>
                            <p><strong>Status:</strong> 
                                <span class="badge bg-{{ $trainer->is_active ? 'success' : 'danger' }}">
                                    {{ $trainer->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </p>
                            <p><strong>Joined:</strong> {{ $trainer->created_at->format('F d, Y') }}</p>
                        </div>
                    </div>
                    @if($trainer->address)
                    <div class="row">
                        <div class="col-12">
                            <p><strong>Address:</strong> {{ $trainer->address }}</p>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Trainer Stats -->
        <div class="col-lg-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Trainer Statistics</h6>
                </div>
                <div class="card-body">
                    <div class="text-center">
                        <div class="mb-3">
                            <h4 class="text-primary">{{ $trainer->assignedWorkouts->count() }}</h4>
                            <small class="text-muted">Total Workouts Assigned</small>
                        </div>
                        <div class="mb-3">
                            <h4 class="text-success">{{ $trainer->assignedWorkouts->where('status', 'completed')->count() }}</h4>
                            <small class="text-muted">Completed Workouts</small>
                        </div>
                        <div class="mb-3">
                            <h4 class="text-info">{{ $trainer->assignedWorkouts->pluck('user_id')->unique()->count() }}</h4>
                            <small class="text-muted">Active Clients</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Assigned Workouts -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Assigned Workouts</h6>
        </div>
        <div class="card-body">
            @if($trainer->assignedWorkouts->count() > 0)
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Workout Name</th>
                                <th>Client</th>
                                <th>Type</th>
                                <th>Date</th>
                                <th>Duration</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($trainer->assignedWorkouts->take(20) as $workout)
                            <tr>
                                <td>{{ $workout->name }}</td>
                                <td>{{ $workout->user->name }}</td>
                                <td><span class="badge bg-secondary">{{ ucfirst($workout->type) }}</span></td>
                                <td>{{ $workout->workout_date->format('M d, Y') }}</td>
                                <td>{{ $workout->total_duration_minutes ? $workout->total_duration_minutes . ' min' : 'N/A' }}</td>
                                <td>
                                    <span class="badge bg-{{ $workout->status === 'completed' ? 'success' : ($workout->status === 'in_progress' ? 'warning' : 'secondary') }}">
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
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-4">
                    <i class="fas fa-clipboard-list fa-3x text-muted mb-3"></i>
                    <p class="text-muted">No workouts assigned yet</p>
                    <a href="{{ route('admin.workouts.create') }}" class="btn btn-primary btn-sm">
                        <i class="fas fa-plus"></i> Assign Workout
                    </a>
                </div>
            @endif
        </div>
    </div>

    <!-- Client List -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Clients</h6>
        </div>
        <div class="card-body">
            @php
                $clients = $trainer->assignedWorkouts->pluck('user')->unique('id');
            @endphp
            @if($clients->count() > 0)
                <div class="row">
                    @foreach($clients as $client)
                    <div class="col-md-6 col-lg-4 mb-3">
                        <div class="card">
                            <div class="card-body">
                                <h6 class="card-title">{{ $client->name }}</h6>
                                <p class="card-text">
                                    <small class="text-muted">{{ $client->email }}</small><br>
                                    <span class="badge bg-info">
                                        {{ $trainer->assignedWorkouts->where('user_id', $client->id)->count() }} workouts
                                    </span>
                                </p>
                                <a href="{{ route('admin.members.show', $client) }}" class="btn btn-sm btn-outline-primary">
                                    View Profile
                                </a>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-4">
                    <i class="fas fa-users fa-3x text-muted mb-3"></i>
                    <p class="text-muted">No clients assigned yet</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
