@extends('admin.layouts.app')

@section('title', 'Create Payment Record')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-plus me-2"></i>Create Payment Record
        </h1>
        <a href="{{ route('admin.payments.index') }}" class="btn btn-secondary btn-sm">
            <i class="fas fa-arrow-left fa-sm me-1"></i> Back to Payments
        </a>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Payment Information</h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.payments.store') }}" method="POST">
                        @csrf
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="member_id" class="form-label">Member <span class="text-danger">*</span></label>
                                <select name="member_id" id="member_id" class="form-select @error('member_id') is-invalid @enderror" required>
                                    <option value="">Select Member</option>
                                    @foreach($members as $member)
                                        <option value="{{ $member->id }}" {{ old('member_id') == $member->id ? 'selected' : '' }}>
                                            {{ $member->name }} ({{ $member->email }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('member_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6">
                                <label for="booking_id" class="form-label">Related Booking (Optional)</label>
                                <select name="booking_id" id="booking_id" class="form-select @error('booking_id') is-invalid @enderror">
                                    <option value="">No Related Booking</option>
                                    @foreach($bookings as $booking)
                                        <option value="{{ $booking->id }}" {{ old('booking_id') == $booking->id ? 'selected' : '' }}>
                                            {{ $booking->member->name }} - {{ $booking->trainer->name }} ({{ $booking->booking_date->format('M d, Y') }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('booking_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="amount" class="form-label">Amount <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text">$</span>
                                    <input type="number" name="amount" id="amount" class="form-control @error('amount') is-invalid @enderror" 
                                           value="{{ old('amount') }}" step="0.01" min="0.01" max="99999.99" required>
                                </div>
                                @error('amount')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6">
                                <label for="payment_method" class="form-label">Payment Method <span class="text-danger">*</span></label>
                                <select name="payment_method" id="payment_method" class="form-select @error('payment_method') is-invalid @enderror" required>
                                    <option value="">Select Payment Method</option>
                                    <option value="credit_card" {{ old('payment_method') == 'credit_card' ? 'selected' : '' }}>Credit Card</option>
                                    <option value="debit_card" {{ old('payment_method') == 'debit_card' ? 'selected' : '' }}>Debit Card</option>
                                    <option value="paypal" {{ old('payment_method') == 'paypal' ? 'selected' : '' }}>PayPal</option>
                                    <option value="cash" {{ old('payment_method') == 'cash' ? 'selected' : '' }}>Cash</option>
                                    <option value="bank_transfer" {{ old('payment_method') == 'bank_transfer' ? 'selected' : '' }}>Bank Transfer</option>
                                </select>
                                @error('payment_method')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                                <select name="status" id="status" class="form-select @error('status') is-invalid @enderror" required>
                                    <option value="">Select Status</option>
                                    <option value="pending" {{ old('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="completed" {{ old('status') == 'completed' ? 'selected' : 'selected' }}>Completed</option>
                                    <option value="failed" {{ old('status') == 'failed' ? 'selected' : '' }}>Failed</option>
                                    <option value="refunded" {{ old('status') == 'refunded' ? 'selected' : '' }}>Refunded</option>
                                </select>
                                @error('status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6">
                                <label for="payment_date" class="form-label">Payment Date <span class="text-danger">*</span></label>
                                <input type="datetime-local" name="payment_date" id="payment_date" 
                                       class="form-control @error('payment_date') is-invalid @enderror" 
                                       value="{{ old('payment_date', now()->format('Y-m-d\TH:i')) }}" required>
                                @error('payment_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3" id="card-details" style="display: none;">
                            <div class="col-md-6">
                                <label for="card_last_four" class="form-label">Card Last Four Digits</label>
                                <input type="text" name="card_last_four" id="card_last_four" 
                                       class="form-control @error('card_last_four') is-invalid @enderror" 
                                       value="{{ old('card_last_four') }}" maxlength="4" pattern="[0-9]{4}">
                                @error('card_last_four')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6">
                                <label for="card_type" class="form-label">Card Type</label>
                                <select name="card_type" id="card_type" class="form-select @error('card_type') is-invalid @enderror">
                                    <option value="">Select Card Type</option>
                                    <option value="Visa" {{ old('card_type') == 'Visa' ? 'selected' : '' }}>Visa</option>
                                    <option value="Mastercard" {{ old('card_type') == 'Mastercard' ? 'selected' : '' }}>Mastercard</option>
                                    <option value="American Express" {{ old('card_type') == 'American Express' ? 'selected' : '' }}>American Express</option>
                                    <option value="Discover" {{ old('card_type') == 'Discover' ? 'selected' : '' }}>Discover</option>
                                </select>
                                @error('card_type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea name="description" id="description" rows="3" 
                                      class="form-control @error('description') is-invalid @enderror" 
                                      placeholder="Enter payment description or notes...">{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-end">
                            <a href="{{ route('admin.payments.index') }}" class="btn btn-secondary me-2">Cancel</a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-1"></i> Create Payment Record
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Payment Guidelines</h6>
                </div>
                <div class="card-body">
                    <div class="alert alert-info">
                        <h6><i class="fas fa-info-circle me-1"></i> Important Notes:</h6>
                        <ul class="mb-0 small">
                            <li>Transaction ID will be generated automatically</li>
                            <li>Select the appropriate member for this payment</li>
                            <li>Link to a booking if this payment is for a specific session</li>
                            <li>Card details are optional but recommended for card payments</li>
                            <li>Use description field for additional payment notes</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Show/hide card details based on payment method
document.getElementById('payment_method').addEventListener('change', function() {
    const cardDetails = document.getElementById('card-details');
    const selectedMethod = this.value;
    
    if (selectedMethod === 'credit_card' || selectedMethod === 'debit_card') {
        cardDetails.style.display = 'block';
    } else {
        cardDetails.style.display = 'none';
        // Clear card fields when hidden
        document.getElementById('card_last_four').value = '';
        document.getElementById('card_type').value = '';
    }
});

// Trigger the change event on page load to handle old values
document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('payment_method').dispatchEvent(new Event('change'));
});
</script>
@endsection