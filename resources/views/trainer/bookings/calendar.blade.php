@extends('trainer.layouts.app')

@section('title', 'Calendar')
@section('page-title', 'Weekly Schedule')

@section('page-actions')
    <div class="btn-group">
        <a href="{{ route('trainer.bookings.index') }}" class="btn btn-secondary">
            <i class="fas fa-list me-2"></i>List View
        </a>
        <a href="{{ route('trainer.bookings.day-view', ['date' => $currentDate->format('Y-m-d')]) }}" class="btn btn-outline-primary">
            <i class="fas fa-calendar-day me-2"></i>Day View
        </a>
    </div>
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <!-- Week Navigation -->
        <div class="card mb-4">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <a href="{{ route('trainer.bookings.calendar', ['date' => $currentDate->copy()->subWeek()->format('Y-m-d')]) }}" class="btn btn-outline-primary">
                        <i class="fas fa-chevron-left me-2"></i>Previous Week
                    </a>
                    <h5 class="mb-0">
                        Week of {{ $currentDate->startOfWeek()->format('M d') }} - {{ $currentDate->endOfWeek()->format('M d, Y') }}
                    </h5>
                    <a href="{{ route('trainer.bookings.calendar', ['date' => $currentDate->copy()->addWeek()->format('Y-m-d')]) }}" class="btn btn-outline-primary">
                        Next Week<i class="fas fa-chevron-right ms-2"></i>
                    </a>
                </div>
            </div>
        </div>

        <!-- Calendar Grid -->
        <div class="card">
            <div class="card-body">
                <div class="row">
                    @foreach($weekDays as $day)
                    <div class="col">
                        <div class="card h-100 {{ $day['is_today'] ? 'border-primary' : '' }}">
                            <div class="card-header text-center {{ $day['is_today'] ? 'bg-primary text-white' : '' }}">
                                <h6 class="mb-0">{{ $day['day_name'] }}</h6>
                                <small>{{ $day['day_number'] }}</small>
                            </div>
                            <div class="card-body p-2" style="min-height: 300px;">
                                @if($day['bookings']->count() > 0)
                                    @foreach($day['bookings'] as $booking)
                                    <div class="mb-2">
                                        <div class="card border-0 bg-light">
                                            <div class="card-body p-2">
                                                <div class="d-flex justify-content-between align-items-start">
                                                    <div>
                                                        <small class="text-muted">{{ $booking->start_time->format('g:i A') }}</small>
                                                        <br>
                                                        <strong class="small">{{ $booking->member->name }}</strong>
                                                        <br>
                                                        <span class="badge badge-sm bg-{{ $booking->status === 'confirmed' ? 'success' : ($booking->status === 'completed' ? 'primary' : ($booking->status === 'cancelled' ? 'danger' : 'warning')) }}">
                                                            {{ ucfirst($booking->status) }}
                                                        </span>
                                                    </div>
                                                    <div class="dropdown">
                                                        <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                                            <i class="fas fa-ellipsis-v"></i>
                                                        </button>
                                                        <ul class="dropdown-menu">
                                                            <li>
                                                                <a class="dropdown-item" href="{{ route('trainer.bookings.show', $booking) }}">
                                                                    <i class="fas fa-eye me-2"></i>View Details
                                                                </a>
                                                            </li>
                                                            @if($booking->status === 'pending')
                                                                <li>
                                                                    <form action="{{ route('trainer.bookings.update-status', $booking) }}" method="POST">
                                                                        @csrf
                                                                        @method('PATCH')
                                                                        <input type="hidden" name="status" value="confirmed">
                                                                        <button type="submit" class="dropdown-item text-success">
                                                                            <i class="fas fa-check me-2"></i>Confirm
                                                                        </button>
                                                                    </form>
                                                                </li>
                                                            @elseif($booking->status === 'confirmed')
                                                                <li>
                                                                    <form action="{{ route('trainer.bookings.update-status', $booking) }}" method="POST">
                                                                        @csrf
                                                                        @method('PATCH')
                                                                        <input type="hidden" name="status" value="completed">
                                                                        <button type="submit" class="dropdown-item text-primary">
                                                                            <i class="fas fa-flag-checkered me-2"></i>Complete
                                                                        </button>
                                                                    </form>
                                                                </li>
                                                            @endif
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                @else
                                    <div class="text-center text-muted mt-4">
                                        <i class="fas fa-calendar-times fa-2x mb-2"></i>
                                        <p class="small">No bookings</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.badge-sm {
    font-size: 0.7em;
}
</style>
@endpush