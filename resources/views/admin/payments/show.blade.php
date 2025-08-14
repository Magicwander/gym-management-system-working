@extends('admin.layouts.app')

@section('title', 'Payment Details')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-credit-card me-2"></i>Payment Details
        </h1>
        <div class="btn-group">
            <a href="{{ route('admin.payments.edit', $payment) }}" class="btn btn-primary btn-sm">
                <i class="fas fa-edit fa-sm me-1"></i> Edit Payment
            </a>
            <a href="{{ route('admin.payments.index') }}" class="btn btn-secondary btn-sm">
                <i class="fas fa-arrow-left fa-sm me-1"></i> Back to Payments
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <!-- Payment Information -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Payment Information</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label text-muted">Transaction ID</label>
                                <div class="fw-bold">{{ $payment->transaction_id }}</div>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label text-muted">Amount</label>
                                <div class="fw-bold text-success fs-4">${{ number_format($payment->amount, 2) }}</div>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label text-muted">Payment Method</label>
                                <div>
                                    <span class="badge bg-info fs-6">{{ ucfirst(str_replace('_', ' ', $payment->payment_method)) }}</span>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label text-muted">Status</label>
                                <div>
                                    @switch($payment->status)
                                        @case('completed')
                                            <span class="badge bg-success fs-6">Completed</span>
                                            @break
                                        @case('pending')
                                            <span class="badge bg-warning fs-6">Pending</span>
                                            @break
                                        @case('failed')
                                            <span class="badge bg-danger fs-6">Failed</span>
                                            @break
                                        @case('refunded')
                                            <span class="badge bg-secondary fs-6">Refunded</span>
                                            @break
                                    @endswitch
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label text-muted">Payment Date</label>
                                <div class="fw-bold">{{ $payment->payment_date->format('F d, Y \a\t H:i') }}</div>
                            </div>
                            
                            @if($payment->card_last_four)
                            <div class="mb-3">
                                <label class="form-label text-muted">Card Information</label>
                                <div class="fw-bold">
                                    {{ $payment->card_type ?? 'Card' }} ending in {{ $payment->card_last_four }}
                                </div>
                            </div>
                            @endif
                            
                            <div class="mb-3">
                                <label class="form-label text-muted">Created</label>
                                <div>{{ $payment->created_at->format('F d, Y \a\t H:i') }}</div>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label text-muted">Last Updated</label>
                                <div>{{ $payment->updated_at->format('F d, Y \a\t H:i') }}</div>
                            </div>
                        </div>
                    </div>
                    
                    @if($payment->description)
                    <div class="mt-3">
                        <label class="form-label text-muted">Description</label>
                        <div class="p-3 bg-light rounded">{{ $payment->description }}</div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Member Information -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Member Information</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label text-muted">Name</label>
                                <div class="fw-bold">{{ $payment->member->name }}</div>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label text-muted">Email</label>
                                <div>{{ $payment->member->email }}</div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            @if($payment->member->phone)
                            <div class="mb-3">
                                <label class="form-label text-muted">Phone</label>
                                <div>{{ $payment->member->phone }}</div>
                            </div>
                            @endif
                            
                            <div class="mb-3">
                                <label class="form-label text-muted">Member Status</label>
                                <div>
                                    @if($payment->member->is_active)
                                        <span class="badge bg-success">Active</span>
                                    @else
                                        <span class="badge bg-danger">Inactive</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mt-3">
                        <a href="{{ route('admin.accounts.members.show', $payment->member) }}" class="btn btn-outline-primary btn-sm">
                            <i class="fas fa-user me-1"></i> View Member Profile
                        </a>
                    </div>
                </div>
            </div>

            <!-- Booking Information (if exists) -->
            @if($payment->booking)
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Related Booking</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label text-muted">Trainer</label>
                                <div class="fw-bold">{{ $payment->booking->trainer->name }}</div>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label text-muted">Booking Date</label>
                                <div>{{ $payment->booking->booking_date->format('F d, Y \a\t H:i') }}</div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label text-muted">Session Type</label>
                                <div>{{ $payment->booking->session_type ?? 'N/A' }}</div>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label text-muted">Booking Status</label>
                                <div>
                                    <span class="badge bg-info">{{ ucfirst($payment->booking->status) }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endif
        </div>

        <div class="col-lg-4">
            <!-- Quick Actions -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Quick Actions</h6>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('admin.payments.edit', $payment) }}" class="btn btn-primary">
                            <i class="fas fa-edit me-1"></i> Edit Payment
                        </a>
                        
                        @if($payment->status === 'pending')
                        <form action="{{ route('admin.payments.bulk-action') }}" method="POST" class="d-inline">
                            @csrf
                            <input type="hidden" name="action" value="mark_completed">
                            <input type="hidden" name="payment_ids" value='[{{ $payment->id }}]'>
                            <button type="submit" class="btn btn-success w-100" onclick="return confirm('Mark this payment as completed?')">
                                <i class="fas fa-check me-1"></i> Mark as Completed
                            </button>
                        </form>
                        @endif
                        
                        @if($payment->status === 'completed')
                        <form action="{{ route('admin.payments.bulk-action') }}" method="POST" class="d-inline">
                            @csrf
                            <input type="hidden" name="action" value="mark_refunded">
                            <input type="hidden" name="payment_ids" value='[{{ $payment->id }}]'>
                            <button type="submit" class="btn btn-warning w-100" onclick="return confirm('Mark this payment as refunded?')">
                                <i class="fas fa-undo me-1"></i> Mark as Refunded
                            </button>
                        </form>
                        @endif
                        
                        <form action="{{ route('admin.payments.destroy', $payment) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this payment record? This action cannot be undone.')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger w-100">
                                <i class="fas fa-trash me-1"></i> Delete Payment
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Payment Summary -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Payment Summary</h6>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-12 mb-3">
                            <div class="text-muted small">Amount</div>
                            <div class="h4 text-success mb-0">${{ number_format($payment->amount, 2) }}</div>
                        </div>
                    </div>
                    
                    <hr>
                    
                    <div class="small text-muted">
                        <div class="d-flex justify-content-between mb-1">
                            <span>Payment Method:</span>
                            <span>{{ ucfirst(str_replace('_', ' ', $payment->payment_method)) }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-1">
                            <span>Status:</span>
                            <span>{{ ucfirst($payment->status) }}</span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span>Date:</span>
                            <span>{{ $payment->payment_date->format('M d, Y') }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection