@extends('admin.layouts.app')

@section('title', 'Member Details')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Member Details</h1>
        <div>
            <a href="{{ route('admin.members.edit', $member) }}" class="btn btn-warning btn-sm">
                <i class="fas fa-edit fa-sm text-white-50"></i> Edit Member
            </a>
            <a href="{{ route('admin.members.index') }}" class="btn btn-secondary btn-sm">
                <i class="fas fa-arrow-left fa-sm text-white-50"></i> Back to Members
            </a>
        </div>
    </div>

    <div class="row">
        <!-- Member Information -->
        <div class="col-lg-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Personal Information</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>Full Name:</strong> {{ $member->name }}</p>
                            <p><strong>Email:</strong> {{ $member->email }}</p>
                            <p><strong>Phone:</strong> {{ $member->phone ?? 'Not provided' }}</p>
                            <p><strong>Gender:</strong> {{ $member->gender ? ucfirst($member->gender) : 'Not specified' }}</p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Date of Birth:</strong> {{ $member->date_of_birth ? $member->date_of_birth->format('F d, Y') : 'Not provided' }}</p>
                            <p><strong>Age:</strong> {{ $member->date_of_birth ? $member->date_of_birth->age . ' years' : 'N/A' }}</p>
                            <p><strong>Status:</strong> 
                                <span class="badge bg-{{ $member->is_active ? 'success' : 'danger' }}">
                                    {{ $member->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </p>
                            <p><strong>Member Since:</strong> {{ $member->created_at->format('F d, Y') }}</p>
                        </div>
                    </div>
                    @if($member->address)
                    <div class="row">
                        <div class="col-12">
                            <p><strong>Address:</strong> {{ $member->address }}</p>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Member Stats -->
        <div class="col-lg-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Member Statistics</h6>
                </div>
                <div class="card-body">
                    <div class="text-center">
                        <div class="mb-3">
                            <h4 class="text-primary">{{ $member->workouts->count() }}</h4>
                            <small class="text-muted">Total Workouts</small>
                        </div>
                        <div class="mb-3">
                            <h4 class="text-success">{{ $member->workouts->where('status', 'completed')->count() }}</h4>
                            <small class="text-muted">Completed Workouts</small>
                        </div>
                        <div class="mb-3">
                            <h4 class="text-info">{{ $member->workouts->sum('calories_burned') ?: 0 }}</h4>
                            <small class="text-muted">Total Calories Burned</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Memberships -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Membership History</h6>
        </div>
        <div class="card-body">
            @if($member->memberships->count() > 0)
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Type</th>
                                <th>Duration</th>
                                <th>Start Date</th>
                                <th>End Date</th>
                                <th>Price</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($member->memberships as $membership)
                            <tr>
                                <td><span class="badge bg-info">{{ ucfirst($membership->type) }}</span></td>
                                <td>{{ str_replace('_', ' ', ucfirst($membership->duration)) }}</td>
                                <td>{{ $membership->start_date->format('M d, Y') }}</td>
                                <td>{{ $membership->end_date->format('M d, Y') }}</td>
                                <td>Rs {{ number_format($membership->price, 2) }}</td>
                                <td>
                                    <span class="badge bg-{{ $membership->status === 'active' ? 'success' : ($membership->status === 'expired' ? 'warning' : 'danger') }}">
                                        {{ ucfirst($membership->status) }}
                                    </span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-4">
                    <i class="fas fa-id-card fa-3x text-muted mb-3"></i>
                    <p class="text-muted">No membership history found</p>
                    <a href="{{ route('admin.memberships.create') }}" class="btn btn-primary btn-sm">
                        <i class="fas fa-plus"></i> Create Membership
                    </a>
                </div>
            @endif
        </div>
    </div>

    <!-- Recent Workouts -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Recent Workouts</h6>
        </div>
        <div class="card-body">
            @if($member->workouts->count() > 0)
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Workout Name</th>
                                <th>Type</th>
                                <th>Trainer</th>
                                <th>Date</th>
                                <th>Duration</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($member->workouts->take(10) as $workout)
                            <tr>
                                <td>{{ $workout->name }}</td>
                                <td><span class="badge bg-secondary">{{ ucfirst($workout->type) }}</span></td>
                                <td>{{ $workout->trainer ? $workout->trainer->name : 'Self-guided' }}</td>
                                <td>{{ $workout->workout_date->format('M d, Y') }}</td>
                                <td>{{ $workout->total_duration_minutes ? $workout->total_duration_minutes . ' min' : 'N/A' }}</td>
                                <td>
                                    <span class="badge bg-{{ $workout->status === 'completed' ? 'success' : ($workout->status === 'in_progress' ? 'warning' : 'secondary') }}">
                                        {{ ucfirst($workout->status) }}
                                    </span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-4">
                    <i class="fas fa-running fa-3x text-muted mb-3"></i>
                    <p class="text-muted">No workouts found</p>
                    <a href="{{ route('admin.workouts.create') }}" class="btn btn-primary btn-sm">
                        <i class="fas fa-plus"></i> Create Workout
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
