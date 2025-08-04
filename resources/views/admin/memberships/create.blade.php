@extends('admin.layouts.app')

@section('title', 'Create New Membership')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Create New Membership</h1>
        <a href="{{ route('admin.memberships.index') }}" class="btn btn-secondary btn-sm">
            <i class="fas fa-arrow-left fa-sm text-white-50"></i> Back to Memberships
        </a>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Membership Information</h6>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.memberships.store') }}" method="POST">
                @csrf
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label for="user_id" class="form-label">Select Member</label>
                            <select class="form-control @error('user_id') is-invalid @enderror" id="user_id" name="user_id" required>
                                <option value="">Choose a member...</option>
                                @foreach($members as $member)
                                    <option value="{{ $member->id }}" {{ old('user_id') == $member->id ? 'selected' : '' }}>
                                        {{ $member->name }} ({{ $member->email }})
                                    </option>
                                @endforeach
                            </select>
                            @error('user_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label for="type" class="form-label">Membership Type</label>
                            <select class="form-control @error('type') is-invalid @enderror" id="type" name="type" required>
                                <option value="">Select Type</option>
                                <option value="platinum" {{ old('type') == 'platinum' ? 'selected' : '' }}>Platinum</option>
                                <option value="gold" {{ old('type') == 'gold' ? 'selected' : '' }}>Gold</option>
                                <option value="silver" {{ old('type') == 'silver' ? 'selected' : '' }}>Silver</option>
                            </select>
                            @error('type')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label for="duration" class="form-label">Duration</label>
                            <select class="form-control @error('duration') is-invalid @enderror" id="duration" name="duration" required>
                                <option value="">Select Duration</option>
                                <option value="3_months" {{ old('duration') == '3_months' ? 'selected' : '' }}>3 Months</option>
                                <option value="6_months" {{ old('duration') == '6_months' ? 'selected' : '' }}>6 Months</option>
                                <option value="1_year" {{ old('duration') == '1_year' ? 'selected' : '' }}>1 Year</option>
                            </select>
                            @error('duration')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label for="price" class="form-label">Price (Rs)</label>
                            <input type="number" step="0.01" min="0" class="form-control @error('price') is-invalid @enderror" 
                                   id="price" name="price" value="{{ old('price') }}" required>
                            @error('price')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label for="start_date" class="form-label">Start Date</label>
                            <input type="date" class="form-control @error('start_date') is-invalid @enderror" 
                                   id="start_date" name="start_date" value="{{ old('start_date', date('Y-m-d')) }}" required>
                            @error('start_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label class="form-label">End Date</label>
                            <input type="text" class="form-control" value="Will be calculated automatically" readonly>
                            <small class="text-muted">End date will be calculated based on start date and duration</small>
                        </div>
                    </div>
                </div>

                <div class="form-group mb-3">
                    <label for="notes" class="form-label">Notes (Optional)</label>
                    <textarea class="form-control @error('notes') is-invalid @enderror" 
                              id="notes" name="notes" rows="3" placeholder="Any additional notes about this membership...">{{ old('notes') }}</textarea>
                    @error('notes')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Create Membership
                    </button>
                    <a href="{{ route('admin.memberships.index') }}" class="btn btn-secondary">
                        <i class="fas fa-times"></i> Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Membership Type Information -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-info">Membership Types Information</h6>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-4">
                    <div class="card border-left-primary">
                        <div class="card-body">
                            <h6 class="text-primary">Platinum Membership</h6>
                            <ul class="list-unstyled">
                                <li><i class="fas fa-check text-success"></i> All gym equipment access</li>
                                <li><i class="fas fa-check text-success"></i> Personal trainer sessions</li>
                                <li><i class="fas fa-check text-success"></i> Group classes</li>
                                <li><i class="fas fa-check text-success"></i> Nutrition consultation</li>
                                <li><i class="fas fa-check text-success"></i> Locker facility</li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card border-left-warning">
                        <div class="card-body">
                            <h6 class="text-warning">Gold Membership</h6>
                            <ul class="list-unstyled">
                                <li><i class="fas fa-check text-success"></i> All gym equipment access</li>
                                <li><i class="fas fa-check text-success"></i> Limited trainer sessions</li>
                                <li><i class="fas fa-check text-success"></i> Group classes</li>
                                <li><i class="fas fa-check text-success"></i> Locker facility</li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card border-left-secondary">
                        <div class="card-body">
                            <h6 class="text-secondary">Silver Membership</h6>
                            <ul class="list-unstyled">
                                <li><i class="fas fa-check text-success"></i> Basic gym equipment access</li>
                                <li><i class="fas fa-check text-success"></i> Limited group classes</li>
                                <li><i class="fas fa-check text-success"></i> Locker facility</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Auto-calculate suggested prices based on type and duration
document.addEventListener('DOMContentLoaded', function() {
    const typeSelect = document.getElementById('type');
    const durationSelect = document.getElementById('duration');
    const priceInput = document.getElementById('price');
    
    const prices = {
        'platinum': { '3_months': 15000, '6_months': 28000, '1_year': 50000 },
        'gold': { '3_months': 10000, '6_months': 18000, '1_year': 32000 },
        'silver': { '3_months': 6000, '6_months': 11000, '1_year': 20000 }
    };
    
    function updatePrice() {
        const type = typeSelect.value;
        const duration = durationSelect.value;
        
        if (type && duration && prices[type] && prices[type][duration]) {
            priceInput.value = prices[type][duration];
        }
    }
    
    typeSelect.addEventListener('change', updatePrice);
    durationSelect.addEventListener('change', updatePrice);
});
</script>
@endsection
