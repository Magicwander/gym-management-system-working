@extends('trainer.layouts.app')

@section('title', 'Bookings')
@section('page-title', 'My Bookings')

@section('page-actions')
    <div class="btn-group">
        <a href="{{ route('trainer.bookings.calendar') }}" class="btn btn-outline-primary">
            <i class="fas fa-calendar-week me-2"></i>Calendar View
        </a>
        <a href="{{ route('trainer.bookings.notifications') }}" class="btn btn-outline-info">
            <i class="fas fa-bell me-2"></i>Notifications
        </a>
    </div>
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <!-- Filter Form -->
        <div class="card mb-4">
            <div class="card-body">
                <form method="GET" action="{{ route('trainer.bookings.index') }}" class="row g-3">
                    <div class="col-md-3">
                        <label for="status" class="form-label">Status</label>
                        <select class="form-select" id="status" name="status">
                            <option value="">All Statuses</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="confirmed" {{ request('status') == 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                            <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                            <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="date_from" class="form-label">From Date</label>
                        <input type="date" class="form-control" id="date_from" name="date_from" value="{{ request('date_from') }}">
                    </div>
                    <div class="col-md-3">
                        <label for="date_to" class="form-label">To Date</label>
                        <input type="date" class="form-control" id="date_to" name="date_to" value="{{ request('date_to') }}">
                    </div>
                    <div class="col-md-3 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary me-2">
                            <i class="fas fa-filter me-2"></i>Filter
                        </button>
                        <a href="{{ route('trainer.bookings.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-times me-2"></i>Clear
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Bookings List -->
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-calendar-alt me-2"></i>Booking Sessions
                </h5>
            </div>
            <div class="card-body">
                @if($bookings->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Member</th>
                                    <th>Date & Time</th>
                                    <th>Session Type</th>
                                    <th>Price (LKR)</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($bookings as $booking)
                                <tr>
                                    <td>
                                        <div>
                                            <strong>{{ $booking->member->name }}</strong>
                                            <br><small class="text-muted">{{ $booking->member->email }}</small>
                                        </div>
                                    </td>
                                    <td>
                                        <div>
                                            <strong>{{ $booking->booking_date->format('M d, Y') }}</strong>
                                            <br><small class="text-muted">{{ $booking->formatted_time }}</small>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-info">{{ ucfirst(str_replace('_', ' ', $booking->session_type)) }}</span>
                                    </td>
                                    <td>
                                        @if($booking->price)
                                            <strong>LKR {{ number_format($booking->price, 2) }}</strong>
                                        @else
                                            <span class="text-muted">Not set</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $booking->status === 'confirmed' ? 'success' : ($booking->status === 'completed' ? 'primary' : ($booking->status === 'cancelled' ? 'danger' : 'warning')) }}">
                                            {{ ucfirst($booking->status) }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('trainer.bookings.show', $booking) }}" class="btn btn-sm btn-outline-primary" title="View Details">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            @if($booking->status === 'pending')
                                                <form action="{{ route('trainer.bookings.update-status', $booking) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('PATCH')
                                                    <input type="hidden" name="status" value="confirmed">
                                                    <button type="submit" class="btn btn-sm btn-outline-success" title="Confirm">
                                                        <i class="fas fa-check"></i>
                                                    </button>
                                                </form>
                                                <form action="{{ route('trainer.bookings.update-status', $booking) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('PATCH')
                                                    <input type="hidden" name="status" value="cancelled">
                                                    <button type="submit" class="btn btn-sm btn-outline-danger" title="Cancel" onclick="return confirm('Are you sure you want to cancel this booking?')">
                                                        <i class="fas fa-times"></i>
                                                    </button>
                                                </form>
                                            @elseif($booking->status === 'confirmed')
                                                <form action="{{ route('trainer.bookings.update-status', $booking) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('PATCH')
                                                    <input type="hidden" name="status" value="completed">
                                                    <button type="submit" class="btn btn-sm btn-outline-primary" title="Mark Complete">
                                                        <i class="fas fa-flag-checkered"></i>
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="d-flex justify-content-center">
                        {{ $bookings->appends(request()->query())->links() }}
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="fas fa-calendar-alt fa-3x text-muted mb-3"></i>
                        <h5 class="text-muted">No bookings found</h5>
                        <p class="text-muted">
                            @if(request()->hasAny(['status', 'date_from', 'date_to']))
                                Try adjusting your filters to see more results.
                            @else
                                You don't have any bookings yet. Members can book sessions with you through the platform.
                            @endif
                        </p>
                        @if(request()->hasAny(['status', 'date_from', 'date_to']))
                            <a href="{{ route('trainer.bookings.index') }}" class="btn btn-outline-primary">
                                <i class="fas fa-times me-2"></i>Clear Filters
                            </a>
                        @endif
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection