@extends('customer.layouts.app')

@section('title', 'Member Dashboard')
@section('page-title', 'Welcome to Your Fitness Journey')

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
                            <div class="text-xs font-weight-bold text-uppercase mb-1 text-white-50">Completed Sessions</div>
                            <div class="h5 mb-0 font-weight-bold text-white">{{ $stats['completed_bookings'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-check-circle fa-2x text-white-50"></i>
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
                            <div class="text-xs font-weight-bold text-uppercase mb-1 text-white-50">Upcoming Sessions</div>
                            <div class="h5 mb-0 font-weight-bold text-white">{{ $stats['upcoming_bookings'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clock fa-2x text-white-50"></i>
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
                            <div class="text-xs font-weight-bold text-uppercase mb-1 text-white-50">Total Spent</div>
                            <div class="h5 mb-0 font-weight-bold text-white">${{ number_format($stats['total_spent'], 2) }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-dollar-sign fa-2x text-white-50"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Quick Actions</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <a href="{{ route('customer.bookings.create') }}" class="btn btn-primary w-100 py-3">
                                <i class="fas fa-plus-circle fa-2x d-block mb-2"></i>
                                <h5>Book a Trainer</h5>
                                <small>Schedule your next session</small>
                            </a>
                        </div>
                        <div class="col-md-4 mb-3">
                            <a href="{{ route('customer.bookings.index') }}" class="btn btn-success w-100 py-3">
                                <i class="fas fa-calendar-alt fa-2x d-block mb-2"></i>
                                <h5>My Bookings</h5>
                                <small>View your scheduled sessions</small>
                            </a>
                        </div>
                        <div class="col-md-4 mb-3">
                            <a href="{{ route('customer.payments.history') }}" class="btn btn-info w-100 py-3">
                                <i class="fas fa-credit-card fa-2x d-block mb-2"></i>
                                <h5>Payment History</h5>
                                <small>Track your payments</small>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Upcoming Sessions -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Upcoming Sessions</h5>
                    <a href="{{ route('customer.bookings.index') }}" class="btn btn-sm btn-outline-primary">View All</a>
                </div>
                <div class="card-body">
                    @if($upcomingBookings->count() > 0)
                        <div class="row">
                            @foreach($upcomingBookings as $booking)
                                <div class="col-md-6 mb-3">
                                    <div class="card border-start border-primary border-3">
                                        <div class="card-body">
                                            <div class="d-flex justify-content-between align-items-start">
                                                <div>
                                                    <h6 class="card-title">{{ $booking->trainer->name }}</h6>
                                                    <p class="card-text">
                                                        <i class="fas fa-calendar me-1"></i>{{ $booking->booking_date->format('M j, Y') }}<br>
                                                        <i class="fas fa-clock me-1"></i>{{ $booking->start_time->format('g:i A') }} - {{ $booking->end_time->format('g:i A') }}<br>
                                                        <i class="fas fa-dumbbell me-1"></i>{{ ucfirst(str_replace('_', ' ', $booking->session_type)) }}
                                                    </p>
                                                </div>
                                                <span class="badge bg-{{ $booking->status === 'confirmed' ? 'success' : 'warning' }}">
                                                    {{ ucfirst($booking->status) }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-calendar-times fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">No upcoming sessions</h5>
                            <p class="text-muted">Book a trainer to get started with your fitness journey!</p>
                            <a href="{{ route('customer.bookings.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus me-1"></i>Book Now
                            </a>
                        </div>
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
                    <h5 class="card-title mb-0">Recent Sessions</h5>
                </div>
                <div class="card-body">
                    @if($recentBookings->count() > 0)
                        <div class="list-group list-group-flush">
                            @foreach($recentBookings as $booking)
                                <div class="list-group-item d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="mb-1">{{ $booking->trainer->name }}</h6>
                                        <small class="text-muted">{{ $booking->booking_date->format('M j, Y') }} at {{ $booking->start_time->format('g:i A') }}</small>
                                    </div>
                                    <span class="badge bg-{{ $booking->status === 'completed' ? 'success' : ($booking->status === 'confirmed' ? 'primary' : 'warning') }}">
                                        {{ ucfirst($booking->status) }}
                                    </span>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-muted">No recent sessions found.</p>
                    @endif
                </div>
            </div>
        </div>
        
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Available Trainers</h5>
                </div>
                <div class="card-body">
                    @if($availableTrainers->count() > 0)
                        <div class="list-group list-group-flush">
                            @foreach($availableTrainers as $trainer)
                                <div class="list-group-item d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="mb-1">{{ $trainer->name }}</h6>
                                        <small class="text-muted">{{ $trainer->specialization ?? 'General Fitness' }}</small>
                                    </div>
                                    <a href="{{ route('customer.bookings.create', ['trainer' => $trainer->id]) }}" class="btn btn-sm btn-outline-primary">
                                        Book
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-muted">No trainers available at the moment.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection