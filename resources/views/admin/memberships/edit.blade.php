@extends('admin.layouts.app')

@section('title', 'Edit Membership')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Edit Membership</h1>
        <a href="{{ route('admin.memberships.index') }}" class="btn btn-secondary btn-sm">
            <i class="fas fa-arrow-left fa-sm text-white-50"></i> Back to Memberships
        </a>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Membership Information</h6>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.memberships.update', $membership) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label for="user_id" class="form-label">Select Member</label>
                            <select class="form-control @error('user_id') is-invalid @enderror" id="user_id" name="user_id" required>
                                <option value="">Choose a member...</option>
                                @foreach($members as $member)
                                    <option value="{{ $member->id }}" {{ old('user_id', $membership->user_id) == $member->id ? 'selected' : '' }}>
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
                                <option value="platinum" {{ old('type', $membership->type) == 'platinum' ? 'selected' : '' }}>Platinum</option>
                                <option value="gold" {{ old('type', $membership->type) == 'gold' ? 'selected' : '' }}>Gold</option>
                                <option value="silver" {{ old('type', $membership->type) == 'silver' ? 'selected' : '' }}>Silver</option>
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
                                <option value="3_months" {{ old('duration', $membership->duration) == '3_months' ? 'selected' : '' }}>3 Months</option>
                                <option value="6_months" {{ old('duration', $membership->duration) == '6_months' ? 'selected' : '' }}>6 Months</option>
                                <option value="1_year" {{ old('duration', $membership->duration) == '1_year' ? 'selected' : '' }}>1 Year</option>
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
                                   id="price" name="price" value="{{ old('price', $membership->price) }}" required>
                            @error('price')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group mb-3">
                            <label for="start_date" class="form-label">Start Date</label>
                            <input type="date" class="form-control @error('start_date') is-invalid @enderror" 
                                   id="start_date" name="start_date" value="{{ old('start_date', $membership->start_date->format('Y-m-d')) }}" required>
                            @error('start_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="col-md-4">
                        <div class="form-group mb-3">
                            <label class="form-label">End Date</label>
                            <input type="text" class="form-control" value="{{ $membership->end_date->format('Y-m-d') }}" readonly>
                            <small class="text-muted">Will be recalculated if start date or duration changes</small>
                        </div>
                    </div>
                    
                    <div class="col-md-4">
                        <div class="form-group mb-3">
                            <label for="status" class="form-label">Status</label>
                            <select class="form-control @error('status') is-invalid @enderror" id="status" name="status" required>
                                <option value="active" {{ old('status', $membership->status) == 'active' ? 'selected' : '' }}>Active</option>
                                <option value="expired" {{ old('status', $membership->status) == 'expired' ? 'selected' : '' }}>Expired</option>
                                <option value="cancelled" {{ old('status', $membership->status) == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="form-group mb-3">
                    <label for="notes" class="form-label">Notes (Optional)</label>
                    <textarea class="form-control @error('notes') is-invalid @enderror" 
                              id="notes" name="notes" rows="3" placeholder="Any additional notes about this membership...">{{ old('notes', $membership->notes) }}</textarea>
                    @error('notes')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Update Membership
                    </button>
                    <a href="{{ route('admin.memberships.index') }}" class="btn btn-secondary">
                        <i class="fas fa-times"></i> Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Current Membership Details -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-info">Current Membership Details</h6>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <p><strong>Member:</strong> {{ $membership->user->name }}</p>
                    <p><strong>Email:</strong> {{ $membership->user->email }}</p>
                    <p><strong>Current Type:</strong> 
                        <span class="badge bg-info">{{ ucfirst($membership->type) }}</span>
                    </p>
                    <p><strong>Current Status:</strong> 
                        <span class="badge bg-{{ $membership->status === 'active' ? 'success' : ($membership->status === 'expired' ? 'warning' : 'danger') }}">
                            {{ ucfirst($membership->status) }}
                        </span>
                    </p>
                </div>
                <div class="col-md-6">
                    <p><strong>Duration:</strong> {{ str_replace('_', ' ', ucfirst($membership->duration)) }}</p>
                    <p><strong>Start Date:</strong> {{ $membership->start_date->format('F d, Y') }}</p>
                    <p><strong>End Date:</strong> {{ $membership->end_date->format('F d, Y') }}</p>
                    <p><strong>Days Remaining:</strong> 
                        @if($membership->status === 'active')
                            {{ $membership->end_date->diffInDays(now()) }} days
                        @else
                            N/A
                        @endif
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
