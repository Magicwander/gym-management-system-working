@extends('trainer.layouts.app')

@section('title', 'Day Schedule')
@section('page-title', 'Daily Schedule')

@section('page-actions')
    <div class="btn-group">
        <a href="{{ route('trainer.bookings.calendar') }}" class="btn btn-secondary">
            <i class="fas fa-calendar-week me-2"></i>Week View
        </a>
        <a href="{{ route('trainer.bookings.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-list me-2"></i>List View
        </a>
    </div>
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <!-- Date Navigation -->
        <div class="card mb-4">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <a href="{{ route('trainer.bookings.day-view', ['date' => $currentDate->copy()->subDay()->format('Y-m-d')]) }}" class="btn btn-outline-primary">
                        <i class="fas fa-chevron-left me-2"></i>Previous Day
                    </a>
                    <div class="text-center">
                        <h5 class="mb-0">{{ $currentDate->format('l, F d, Y') }}</h5>
                        @if($currentDate->isToday())
                            <small class="text-primary">Today</small>
                        @elseif($currentDate->isTomorrow())
                            <small class="text-info">Tomorrow</small>
                        @elseif($currentDate->isYesterday())
                            <small class="text-muted">Yesterday</small>
                        @endif
                    </div>
                    <a href="{{ route('trainer.bookings.day-view', ['date' => $currentDate->copy()->addDay()->format('Y-m-d')]) }}" class="btn btn-outline-primary">
                        Next Day<i class="fas fa-chevron-right ms-2"></i>
                    </a>
                </div>
            </div>
        </div>

        <!-- Day Schedule -->
        <div class="card">
            <div class="card-header">
                <h6 class="card-title mb-0">
                    <i class="fas fa-clock me-2"></i>Schedule for {{ $currentDate->format('M d, Y') }}
                    @if($bookings->count() > 0)
                        <span class="badge bg-primary ms-2">{{ $bookings->count() }} sessions</span>
                    @endif
                </h6>
            </div>
            <div class="card-body">
                @if($bookings->count() > 0)
                    <div class="row">
                        <div class="col-md-8">
                            <!-- Time Slots -->
                            <div class="schedule-container">
                                @foreach($timeSlots as $slot)
                                <div class="time-slot d-flex align-items-start mb-3 p-3 border rounded {{ $slot['booking'] ? 'bg-light' : '' }}">
                                    <div class="time-label me-3" style="min-width: 80px;">
                                        <strong>{{ $slot['display_time'] }}</strong>
                                    </div>
                                    <div class="flex-grow-1">
                                        @if($slot['booking'])
                                            <div class="booking-card card border-0 bg-white">
                                                <div class="card-body p-3">
                                                    <div class="d-flex justify-content-between align-items-start">
                                                        <div>
                                                            <h6 class="mb-1">{{ $slot['booking']->member->name }}</h6>
                                                            <p class="mb-1 text-muted">{{ $slot['booking']->member->email }}</p>
                                                            <span class="badge bg-info">{{ ucfirst(str_replace('_', ' ', $slot['booking']->session_type)) }}</span>
                                                            <span class="badge bg-{{ $slot['booking']->status === 'confirmed' ? 'success' : ($slot['booking']->status === 'completed' ? 'primary' : ($slot['booking']->status === 'cancelled' ? 'danger' : 'warning')) }}">
                                                                {{ ucfirst($slot['booking']->status) }}
                                                            </span>
                                                        </div>
                                                        <div class="dropdown">
                                                            <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                                                Actions
                                                            </button>
                                                            <ul class="dropdown-menu">
                                                                <li>
                                                                    <a class="dropdown-item" href="{{ route('trainer.bookings.show', $slot['booking']) }}">
                                                                        <i class="fas fa-eye me-2"></i>View Details
                                                                    </a>
                                                                </li>
                                                                @if($slot['booking']->status === 'pending')
                                                                    <li>
                                                                        <form action="{{ route('trainer.bookings.update-status', $slot['booking']) }}" method="POST">
                                                                            @csrf
                                                                            @method('PATCH')
                                                                            <input type="hidden" name="status" value="confirmed">
                                                                            <button type="submit" class="dropdown-item text-success">
                                                                                <i class="fas fa-check me-2"></i>Confirm
                                                                            </button>
                                                                        </form>
                                                                    </li>
                                                                @elseif($slot['booking']->status === 'confirmed')
                                                                    <li>
                                                                        <form action="{{ route('trainer.bookings.update-status', $slot['booking']) }}" method="POST">
                                                                            @csrf
                                                                            @method('PATCH')
                                                                            <input type="hidden" name="status" value="completed">
                                                                            <button type="submit" class="dropdown-item text-primary">
                                                                                <i class="fas fa-flag-checkered me-2"></i>Mark Complete
                                                                            </button>
                                                                        </form>
                                                                    </li>
                                                                @endif
                                                            </ul>
                                                        </div>
                                                    </div>
                                                    @if($slot['booking']->notes)
                                                        <div class="mt-2">
                                                            <small class="text-muted">Notes: {{ Str::limit($slot['booking']->notes, 100) }}</small>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        @else
                                            <div class="text-muted">
                                                <i class="fas fa-calendar-times me-2"></i>Available
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        
                        <div class="col-md-4">
                            <!-- Day Summary -->
                            <div class="card">
                                <div class="card-header">
                                    <h6 class="card-title mb-0">
                                        <i class="fas fa-chart-pie me-2"></i>Day Summary
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <div class="row text-center">
                                        <div class="col-6">
                                            <h4 class="text-primary mb-0">{{ $bookings->count() }}</h4>
                                            <small class="text-muted">Total Sessions</small>
                                        </div>
                                        <div class="col-6">
                                            <h4 class="text-success mb-0">{{ $bookings->where('status', 'confirmed')->count() }}</h4>
                                            <small class="text-muted">Confirmed</small>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="row text-center">
                                        <div class="col-6">
                                            <h4 class="text-warning mb-0">{{ $bookings->where('status', 'pending')->count() }}</h4>
                                            <small class="text-muted">Pending</small>
                                        </div>
                                        <div class="col-6">
                                            <h4 class="text-info mb-0">{{ $bookings->where('status', 'completed')->count() }}</h4>
                                            <small class="text-muted">Completed</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Quick Actions -->
                            <div class="card mt-3">
                                <div class="card-header">
                                    <h6 class="card-title mb-0">
                                        <i class="fas fa-bolt me-2"></i>Quick Actions
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <div class="d-grid gap-2">
                                        <a href="{{ route('trainer.bookings.day-view', ['date' => now()->format('Y-m-d')]) }}" class="btn btn-outline-primary">
                                            <i class="fas fa-calendar-day me-2"></i>Today's Schedule
                                        </a>
                                        <a href="{{ route('trainer.bookings.day-view', ['date' => now()->addDay()->format('Y-m-d')]) }}" class="btn btn-outline-info">
                                            <i class="fas fa-arrow-right me-2"></i>Tomorrow's Schedule
                                        </a>
                                        <a href="{{ route('trainer.workouts.create') }}" class="btn btn-outline-success">
                                            <i class="fas fa-plus me-2"></i>Create Workout Plan
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="fas fa-calendar-times fa-3x text-muted mb-3"></i>
                        <h5 class="text-muted">No sessions scheduled</h5>
                        <p class="text-muted">You don't have any sessions scheduled for {{ $currentDate->format('M d, Y') }}.</p>
                        <div class="mt-3">
                            <a href="{{ route('trainer.bookings.day-view', ['date' => now()->format('Y-m-d')]) }}" class="btn btn-outline-primary me-2">
                                <i class="fas fa-calendar-day me-2"></i>View Today
                            </a>
                            <a href="{{ route('trainer.bookings.calendar') }}" class="btn btn-outline-info">
                                <i class="fas fa-calendar-week me-2"></i>View Calendar
                            </a>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.schedule-container {
    max-height: 600px;
    overflow-y: auto;
}

.time-slot {
    transition: all 0.3s ease;
}

.time-slot:hover {
    background-color: #f8f9fa !important;
}

.booking-card {
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}
</style>
@endpush