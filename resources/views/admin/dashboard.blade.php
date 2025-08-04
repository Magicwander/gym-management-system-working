@extends('admin.layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="container-fluid">
    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card stat-card h-100">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-uppercase mb-1">Total Members</div>
                            <div class="h5 mb-0 font-weight-bold">{{ $stats['total_members'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-users fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card stat-card-success h-100">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-uppercase mb-1">Active Members</div>
                            <div class="h5 mb-0 font-weight-bold">{{ $stats['active_members'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-user-check fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card stat-card-warning h-100">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-uppercase mb-1">Total Trainers</div>
                            <div class="h5 mb-0 font-weight-bold">{{ $stats['total_trainers'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-user-tie fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card stat-card-info h-100">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-uppercase mb-1">Monthly Revenue</div>
                            <div class="h5 mb-0 font-weight-bold">Rs {{ number_format($stats['monthly_revenue'], 2) }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-dollar-sign fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Additional Stats Row -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card stat-card h-100">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-uppercase mb-1">Active Memberships</div>
                            <div class="h5 mb-0 font-weight-bold">{{ $stats['active_memberships'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-id-card fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card stat-card-success h-100">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-uppercase mb-1">Total Workouts</div>
                            <div class="h5 mb-0 font-weight-bold">{{ $stats['total_workouts'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-running fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card stat-card-warning h-100">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-uppercase mb-1">Completed Workouts</div>
                            <div class="h5 mb-0 font-weight-bold">{{ $stats['completed_workouts'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-check-circle fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card stat-card-info h-100">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-uppercase mb-1">Total Exercises</div>
                            <div class="h5 mb-0 font-weight-bold">{{ $stats['total_exercises'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-dumbbell fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts and Tables Row -->
    <div class="row">
        <!-- Membership Distribution Chart -->
        <div class="col-xl-6 col-lg-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Membership Distribution</h6>
                </div>
                <div class="card-body">
                    <div class="chart-pie pt-4 pb-2">
                        <canvas id="membershipChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Members -->
        <div class="col-xl-6 col-lg-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Recent Members</h6>
                    <a href="{{ route('admin.members.index') }}" class="btn btn-sm btn-primary">View All</a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Phone</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recent_members as $member)
                                <tr>
                                    <td>{{ $member->name }}</td>
                                    <td>{{ $member->email }}</td>
                                    <td>{{ $member->phone }}</td>
                                    <td>
                                        <span class="badge bg-{{ $member->is_active ? 'success' : 'danger' }}">
                                            {{ $member->is_active ? 'Active' : 'Inactive' }}
                                        </span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Workouts -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Recent Workouts</h6>
                    <a href="{{ route('admin.workouts.index') }}" class="btn btn-sm btn-primary">View All</a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>Workout Name</th>
                                    <th>Member</th>
                                    <th>Trainer</th>
                                    <th>Date</th>
                                    <th>Status</th>
                                    <th>Duration</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recent_workouts as $workout)
                                <tr>
                                    <td>{{ $workout->name }}</td>
                                    <td>{{ $workout->user->name }}</td>
                                    <td>{{ $workout->trainer ? $workout->trainer->name : 'Self-guided' }}</td>
                                    <td>{{ $workout->workout_date->format('M d, Y') }}</td>
                                    <td>
                                        <span class="badge bg-{{ $workout->status === 'completed' ? 'success' : ($workout->status === 'in_progress' ? 'warning' : 'secondary') }}">
                                            {{ ucfirst($workout->status) }}
                                        </span>
                                    </td>
                                    <td>{{ $workout->total_duration_minutes ? $workout->total_duration_minutes . ' min' : 'N/A' }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
// Membership Distribution Chart
const ctx = document.getElementById('membershipChart').getContext('2d');
const membershipData = @json($membership_stats);

const labels = membershipData.map(item => item.type.charAt(0).toUpperCase() + item.type.slice(1));
const data = membershipData.map(item => item.count);

new Chart(ctx, {
    type: 'doughnut',
    data: {
        labels: labels,
        datasets: [{
            data: data,
            backgroundColor: [
                '#4e73df',
                '#1cc88a',
                '#36b9cc',
                '#f6c23e',
                '#e74a3b'
            ],
            hoverBackgroundColor: [
                '#2e59d9',
                '#17a673',
                '#2c9faf',
                '#f4b619',
                '#e02d1b'
            ],
            hoverBorderColor: "rgba(234, 236, 244, 1)",
        }],
    },
    options: {
        maintainAspectRatio: false,
        plugins: {
            legend: {
                display: true,
                position: 'bottom'
            }
        },
        cutout: '80%',
    },
});
</script>
@endsection
