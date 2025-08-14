@extends('trainer.layouts.app')

@section('title', 'Booking Details')
@section('page-title', 'Booking Details')

@section('page-actions')
    <a href="{{ route('trainer.bookings.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left me-2"></i>Back to Bookings
    </a>
@endsection

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-calendar-alt me-2"></i>Booking Information
                </h5>
            </div>
            <div class="card-body">
                <div class="row mb-4">
                    <div class="col-md-6">
                        <h6><i class="fas fa-user me-2"></i>Member Information</h6>
                        <p class="mb-1"><strong>Name:</strong> {{ $booking->member->name }}</p>
                        <p class="mb-1"><strong>Email:</strong> {{ $booking->member->email }}</p>
                        @if($booking->member->phone)
                            <p class="mb-1"><strong>Phone:</strong> {{ $booking->member->phone }}</p>
                        @endif
                    </div>
                    <div class="col-md-6">
                        <h6><i class="fas fa-calendar me-2"></i>Session Details</h6>
                        <p class="mb-1"><strong>Date:</strong> {{ $booking->booking_date->format('M d, Y') }}</p>
                        <p class="mb-1"><strong>Time:</strong> {{ $booking->formatted_time }}</p>
                        <p class="mb-1"><strong>Type:</strong> {{ ucfirst(str_replace('_', ' ', $booking->session_type)) }}</p>
                    </div>
                </div>
                
                @if($booking->notes)
                <div class="mb-4">
                    <h6><i class="fas fa-sticky-note me-2"></i>Session Notes</h6>
                    <div class="bg-light p-3 rounded">
                        {{ $booking->notes }}
                    </div>
                </div>
                @endif
                
                @if($booking->payment)
                <div class="mb-4">
                    <h6><i class="fas fa-credit-card me-2"></i>Payment Information</h6>
                    <div class="row">
                        <div class="col-md-6">
                            <p class="mb-1"><strong>Amount:</strong> LKR {{ number_format($booking->payment->amount, 2) }}</p>
                            <p class="mb-1"><strong>Status:</strong> 
                                <span class="badge bg-{{ $booking->payment->status === 'completed' ? 'success' : ($booking->payment->status === 'pending' ? 'warning' : 'danger') }}">
                                    {{ ucfirst($booking->payment->status) }}
                                </span>
                            </p>
                        </div>
                        <div class="col-md-6">
                            <p class="mb-1"><strong>Method:</strong> {{ ucfirst($booking->payment->payment_method) }}</p>
                            <p class="mb-1"><strong>Date:</strong> {{ $booking->payment->created_at->format('M d, Y g:i A') }}</p>
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>
        
        <!-- Status Update Form -->
        @if(in_array($booking->status, ['pending', 'confirmed']))
        <div class="card mt-4">
            <div class="card-header">
                <h6 class="card-title mb-0">
                    <i class="fas fa-edit me-2"></i>Update Booking Status
                </h6>
            </div>
            <div class="card-body">
                <form action="{{ route('trainer.bookings.update-status', $booking) }}" method="POST">
                    @csrf
                    @method('PATCH')
                    <div class="row">
                        <div class="col-md-6">
                            <label for="status" class="form-label">Status</label>
                            <select class="form-select" id="status" name="status" required>
                                @if($booking->status === 'pending')
                                    <option value="confirmed">Confirm Booking</option>
                                    <option value="cancelled">Cancel Booking</option>
                                @elseif($booking->status === 'confirmed')
                                    <option value="completed">Mark as Completed</option>
                                    <option value="cancelled">Cancel Booking</option>
                                @endif
                            </select>
                        </div>
                        <div class="col-md-6 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>Update Status
                            </button>
                        </div>
                    </div>
                    <div class="mt-3">
                        <label for="notes" class="form-label">Notes (Optional)</label>
                        <textarea class="form-control" id="notes" name="notes" rows="3" placeholder="Add any notes about this status change...">{{ old('notes') }}</textarea>
                    </div>
                </form>
            </div>
        </div>
        @endif
        
        <!-- Add Notes Form -->
        <div class="card mt-4">
            <div class="card-header">
                <h6 class="card-title mb-0">
                    <i class="fas fa-comment me-2"></i>Add Session Notes
                </h6>
            </div>
            <div class="card-body">
                <form action="{{ route('trainer.bookings.add-notes', $booking) }}" method="POST">
                    @csrf
                    @method('PATCH')
                    <div class="mb-3">
                        <label for="session_notes" class="form-label">Session Notes</label>
                        <textarea class="form-control" id="session_notes" name="notes" rows="4" placeholder="Add notes about the session, member progress, recommendations, etc..." required>{{ old('notes', $booking->notes) }}</textarea>
                    </div>
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-save me-2"></i>Save Notes
                    </button>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <!-- Status Card -->
        <div class="card">
            <div class="card-header">
                <h6 class="card-title mb-0">
                    <i class="fas fa-info me-2"></i>Booking Status
                </h6>
            </div>
            <div class="card-body">
                <div class="text-center mb-3">
                    <span class="badge bg-{{ $booking->status === 'confirmed' ? 'success' : ($booking->status === 'completed' ? 'primary' : ($booking->status === 'cancelled' ? 'danger' : 'warning')) }} fs-6">
                        {{ ucfirst($booking->status) }}
                    </span>
                </div>
                
                <div class="mb-3">
                    <small class="text-muted">Booked:</small><br>
                    <strong>{{ $booking->created_at->format('M d, Y g:i A') }}</strong>
                </div>
                
                <div class="mb-3">
                    <small class="text-muted">Last Updated:</small><br>
                    <strong>{{ $booking->updated_at->format('M d, Y g:i A') }}</strong>
                </div>
            </div>
        </div>
        
        <!-- Price Card -->
        <div class="card mt-3">
            <div class="card-header">
                <h6 class="card-title mb-0">
                    <i class="fas fa-money-bill me-2"></i>Session Price
                </h6>
            </div>
            <div class="card-body">
                <div class="text-center">
                    @if($booking->price)
                        <h3 class="text-success mb-0">LKR {{ number_format($booking->price, 2) }}</h3>
                        <small class="text-muted">Sri Lankan Rupees</small>
                    @else
                        <p class="text-muted mb-0">Price not set</p>
                    @endif
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
                    @if($booking->status === 'pending')
                        <form action="{{ route('trainer.bookings.update-status', $booking) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <input type="hidden" name="status" value="confirmed">
                            <button type="submit" class="btn btn-success w-100">
                                <i class="fas fa-check me-2"></i>Confirm Booking
                            </button>
                        </form>
                        <form action="{{ route('trainer.bookings.update-status', $booking) }}" method="POST" onsubmit="return confirm('Are you sure you want to cancel this booking?')">
                            @csrf
                            @method('PATCH')
                            <input type="hidden" name="status" value="cancelled">
                            <button type="submit" class="btn btn-outline-danger w-100">
                                <i class="fas fa-times me-2"></i>Cancel Booking
                            </button>
                        </form>
                    @elseif($booking->status === 'confirmed')
                        <form action="{{ route('trainer.bookings.update-status', $booking) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <input type="hidden" name="status" value="completed">
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="fas fa-flag-checkered me-2"></i>Mark Complete
                            </button>
                        </form>
                    @endif
                    
                    <a href="{{ route('trainer.bookings.calendar') }}" class="btn btn-outline-info w-100">
                        <i class="fas fa-calendar-week me-2"></i>View Calendar
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection