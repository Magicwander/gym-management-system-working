@extends('trainer.layouts.app')

@section('title', 'Trainer Dashboard')
@section('page-title', 'Dashboard Overview')

@section('content')
<div class="container-fluid">
    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card stat-card h-100">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-uppercase mb-1 text-white-50">Total Bookings</div>
                            <div class="h5 mb-0 font-weight-bold text-white">{{ $stats['total_bookings'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-calendar-check fa-2x text-white-50"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card stat-card success h-100">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-uppercase mb-1 text-white-50">Today's Sessions</div>
                            <div class="h5 mb-0 font-weight-bold text-white">{{ $stats['today_bookings'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clock fa-2x text-white-50"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card stat-card warning h-100">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-uppercase mb-1 text-white-50">Active Clients</div>
                            <div class="h5 mb-0 font-weight-bold text-white">{{ $stats['active_clients'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-users fa-2x text-white-50"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card stat-card info h-100">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-uppercase mb-1">Workout Plans</div>
                            <div class="h5 mb-0 font-weight-bold">{{ $stats['total_workouts'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-dumbbell fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Today's Schedule -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Today's Schedule</h5>
                    <a href="{{ route('trainer.bookings.day-view') }}" class="btn btn-sm btn-primary">
                        <i class="fas fa-calendar-day me-1"></i>Day View
                    </a>
                </div>
                <div class="card-body">
                    @if($todayBookings->count() > 0)
                        <div class="row">
                            @foreach($todayBookings as $booking)
                                <div class="col-md-4 mb-3">
                                    <div class="card border-start border-success border-3">
                                        <div class="card-body">
                                            <h6 class="card-title">{{ $booking->start_time->format('g:i A') }}</h6>
                                            <p class="card-text">
                                                <strong>{{ $booking->member->name }}</strong><br>
                                                <small class="text-muted">{{ ucfirst(str_replace('_', ' ', $booking->session_type)) }}</small>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-muted">No sessions scheduled for today.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Activity -->
    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Upcoming Sessions</h5>
                </div>
                <div class="card-body">
                    @if($upcomingBookings->count() > 0)
                        <div class="list-group list-group-flush">
                            @foreach($upcomingBookings as $booking)
                                <div class="list-group-item d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="mb-1">{{ $booking->member->name }}</h6>
                                        <small class="text-muted">{{ $booking->booking_date->format('M j, Y') }} at {{ $booking->start_time->format('g:i A') }}</small>
                                    </div>
                                    <span class="badge bg-primary">{{ ucfirst(str_replace('_', ' ', $booking->session_type)) }}</span>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-muted">No upcoming sessions.</p>
                    @endif
                </div>
            </div>
        </div>
        
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Recent Workouts</h5>
                </div>
                <div class="card-body">
                    @if($recentWorkouts->count() > 0)
                        <div class="list-group list-group-flush">
                            @foreach($recentWorkouts as $workout)
                                <div class="list-group-item d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="mb-1">{{ $workout->name }}</h6>
                                        <small class="text-muted">{{ $workout->member->name }} - {{ $workout->workout_date->format('M j, Y') }}</small>
                                    </div>
                                    <span class="badge bg-{{ $workout->status === 'completed' ? 'success' : 'warning' }}">
                                        {{ ucfirst($workout->status) }}
                                    </span>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-muted">No recent workouts found.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection