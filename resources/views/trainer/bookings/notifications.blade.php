@extends('trainer.layouts.app')

@section('title', 'Notifications')
@section('page-title', 'Notifications & Alerts')

@section('page-actions')
    <a href="{{ route('trainer.bookings.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left me-2"></i>Back to Bookings
    </a>
@endsection

@section('content')
<div class="row">
    <div class="col-md-6">
        <!-- New Bookings -->
        <div class="card">
            <div class="card-header">
                <h6 class="card-title mb-0">
                    <i class="fas fa-bell me-2"></i>New Bookings (Last 24 Hours)
                    @if($newBookings->count() > 0)
                        <span class="badge bg-danger ms-2">{{ $newBookings->count() }}</span>
                    @endif
                </h6>
            </div>
            <div class="card-body">
                @if($newBookings->count() > 0)
                    @foreach($newBookings as $booking)
                    <div class="d-flex align-items-center mb-3 p-2 bg-light rounded">
                        <div class="flex-grow-1">
                            <strong>{{ $booking->member->name }}</strong>
                            <br>
                            <small class="text-muted">
                                {{ $booking->booking_date->format('M d, Y') }} at {{ $booking->start_time->format('g:i A') }}
                            </small>
                            <br>
                            <span class="badge bg-info">{{ ucfirst(str_replace('_', ' ', $booking->session_type)) }}</span>
                        </div>
                        <div>
                            <a href="{{ route('trainer.bookings.show', $booking) }}" class="btn btn-sm btn-outline-primary">
                                View
                            </a>
                        </div>
                    </div>
                    @endforeach
                @else
                    <div class="text-center py-4">
                        <i class="fas fa-bell-slash fa-2x text-muted mb-2"></i>
                        <p class="text-muted mb-0">No new bookings in the last 24 hours</p>
                    </div>
                @endif
            </div>
        </div>
        
        <!-- Today's Bookings -->
        <div class="card mt-4">
            <div class="card-header">
                <h6 class="card-title mb-0">
                    <i class="fas fa-calendar-day me-2"></i>Today's Sessions
                    @if($todayBookings->count() > 0)
                        <span class="badge bg-primary ms-2">{{ $todayBookings->count() }}</span>
                    @endif
                </h6>
            </div>
            <div class="card-body">
                @if($todayBookings->count() > 0)
                    @foreach($todayBookings as $booking)
                    <div class="d-flex align-items-center mb-3 p-2 border rounded">
                        <div class="flex-grow-1">
                            <strong>{{ $booking->member->name }}</strong>
                            <br>
                            <small class="text-muted">
                                {{ $booking->start_time->format('g:i A') }} - {{ $booking->end_time->format('g:i A') }}
                            </small>
                            <br>
                            <span class="badge bg-success">{{ ucfirst(str_replace('_', ' ', $booking->session_type)) }}</span>
                        </div>
                        <div>
                            <a href="{{ route('trainer.bookings.show', $booking) }}" class="btn btn-sm btn-outline-primary">
                                View
                            </a>
                        </div>
                    </div>
                    @endforeach
                @else
                    <div class="text-center py-4">
                        <i class="fas fa-calendar-times fa-2x text-muted mb-2"></i>
                        <p class="text-muted mb-0">No sessions scheduled for today</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
    
    <div class="col-md-6">
        <!-- Upcoming Bookings -->
        <div class="card">
            <div class="card-header">
                <h6 class="card-title mb-0">
                    <i class="fas fa-clock me-2"></i>Upcoming Sessions (Next 3 Days)
                    @if($upcomingBookings->count() > 0)
                        <span class="badge bg-warning ms-2">{{ $upcomingBookings->count() }}</span>
                    @endif
                </h6>
            </div>
            <div class="card-body">
                @if($upcomingBookings->count() > 0)
                    @foreach($upcomingBookings as $booking)
                    <div class="d-flex align-items-center mb-3 p-2 bg-light rounded">
                        <div class="flex-grow-1">
                            <strong>{{ $booking->member->name }}</strong>
                            <br>
                            <small class="text-muted">
                                {{ $booking->booking_date->format('M d, Y') }} at {{ $booking->start_time->format('g:i A') }}
                            </small>
                            <br>
                            <span class="badge bg-warning">{{ ucfirst(str_replace('_', ' ', $booking->session_type)) }}</span>
                        </div>
                        <div>
                            <a href="{{ route('trainer.bookings.show', $booking) }}" class="btn btn-sm btn-outline-primary">
                                View
                            </a>
                        </div>
                    </div>
                    @endforeach
                @else
                    <div class="text-center py-4">
                        <i class="fas fa-calendar-check fa-2x text-muted mb-2"></i>
                        <p class="text-muted mb-0">No upcoming sessions in the next 3 days</p>
                    </div>
                @endif
            </div>
        </div>
        
        <!-- Quick Stats -->
        <div class="card mt-4">
            <div class="card-header">
                <h6 class="card-title mb-0">
                    <i class="fas fa-chart-bar me-2"></i>Quick Stats
                </h6>
            </div>
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-6">
                        <div class="border-end">
                            <h4 class="text-primary mb-0">{{ $newBookings->count() }}</h4>
                            <small class="text-muted">New Bookings</small>
                        </div>
                    </div>
                    <div class="col-6">
                        <h4 class="text-success mb-0">{{ $todayBookings->count() }}</h4>
                        <small class="text-muted">Today's Sessions</small>
                    </div>
                </div>
                <hr>
                <div class="row text-center">
                    <div class="col-12">
                        <h4 class="text-warning mb-0">{{ $upcomingBookings->count() }}</h4>
                        <small class="text-muted">Upcoming Sessions</small>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Quick Actions -->
        <div class="card mt-4">
            <div class="card-header">
                <h6 class="card-title mb-0">
                    <i class="fas fa-bolt me-2"></i>Quick Actions
                </h6>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="{{ route('trainer.bookings.calendar') }}" class="btn btn-outline-primary">
                        <i class="fas fa-calendar-week me-2"></i>View Weekly Calendar
                    </a>
                    <a href="{{ route('trainer.bookings.day-view', ['date' => now()->format('Y-m-d')]) }}" class="btn btn-outline-info">
                        <i class="fas fa-calendar-day me-2"></i>View Today's Schedule
                    </a>
                    <a href="{{ route('trainer.workouts.index') }}" class="btn btn-outline-success">
                        <i class="fas fa-dumbbell me-2"></i>Manage Workout Plans
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection