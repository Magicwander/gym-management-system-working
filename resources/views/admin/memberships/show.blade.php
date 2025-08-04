@extends('admin.layouts.app')

@section('title', 'Membership Details')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Membership Details</h1>
        <div>
            <a href="{{ route('admin.memberships.edit', $membership) }}" class="btn btn-warning btn-sm">
                <i class="fas fa-edit fa-sm text-white-50"></i> Edit Membership
            </a>
            <a href="{{ route('admin.memberships.index') }}" class="btn btn-secondary btn-sm">
                <i class="fas fa-arrow-left fa-sm text-white-50"></i> Back to Memberships
            </a>
        </div>
    </div>

    <div class="row">
        <!-- Membership Information -->
        <div class="col-lg-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Membership Information</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>Member Name:</strong> {{ $membership->user->name }}</p>
                            <p><strong>Email:</strong> {{ $membership->user->email }}</p>
                            <p><strong>Phone:</strong> {{ $membership->user->phone ?? 'Not provided' }}</p>
                            <p><strong>Membership Type:</strong> 
                                <span class="badge bg-info fs-6">{{ ucfirst($membership->type) }}</span>
                            </p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Duration:</strong> {{ str_replace('_', ' ', ucfirst($membership->duration)) }}</p>
                            <p><strong>Price:</strong> Rs {{ number_format($membership->price, 2) }}</p>
                            <p><strong>Status:</strong> 
                                <span class="badge bg-{{ $membership->status === 'active' ? 'success' : ($membership->status === 'expired' ? 'warning' : 'danger') }} fs-6">
                                    {{ ucfirst($membership->status) }}
                                </span>
                            </p>
                            <p><strong>Created:</strong> {{ $membership->created_at->format('F d, Y') }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Membership Timeline -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Membership Timeline</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4 text-center">
                            <div class="border rounded p-3 mb-3">
                                <i class="fas fa-play-circle fa-2x text-success mb-2"></i>
                                <h6>Start Date</h6>
                                <p class="mb-0">{{ $membership->start_date->format('F d, Y') }}</p>
                                <small class="text-muted">{{ $membership->start_date->diffForHumans() }}</small>
                            </div>
                        </div>
                        <div class="col-md-4 text-center">
                            <div class="border rounded p-3 mb-3">
                                <i class="fas fa-clock fa-2x text-warning mb-2"></i>
                                <h6>Duration</h6>
                                <p class="mb-0">{{ str_replace('_', ' ', ucfirst($membership->duration)) }}</p>
                                <small class="text-muted">{{ $membership->start_date->diffInDays($membership->end_date) }} days total</small>
                            </div>
                        </div>
                        <div class="col-md-4 text-center">
                            <div class="border rounded p-3 mb-3">
                                <i class="fas fa-stop-circle fa-2x text-danger mb-2"></i>
                                <h6>End Date</h6>
                                <p class="mb-0">{{ $membership->end_date->format('F d, Y') }}</p>
                                <small class="text-muted">{{ $membership->end_date->diffForHumans() }}</small>
                            </div>
                        </div>
                    </div>

                    <!-- Progress Bar -->
                    @php
                        $totalDays = $membership->start_date->diffInDays($membership->end_date);
                        $elapsedDays = $membership->start_date->diffInDays(now());
                        $progress = $totalDays > 0 ? min(100, ($elapsedDays / $totalDays) * 100) : 0;
                    @endphp
                    <div class="mt-4">
                        <div class="d-flex justify-content-between mb-2">
                            <span>Membership Progress</span>
                            <span>{{ number_format($progress, 1) }}%</span>
                        </div>
                        <div class="progress">
                            <div class="progress-bar bg-{{ $membership->status === 'active' ? 'success' : 'secondary' }}" 
                                 role="progressbar" style="width: {{ $progress }}%"></div>
                        </div>
                        <div class="d-flex justify-content-between mt-1">
                            <small class="text-muted">{{ $elapsedDays }} days elapsed</small>
                            <small class="text-muted">{{ max(0, $totalDays - $elapsedDays) }} days remaining</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Membership Stats -->
        <div class="col-lg-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Quick Stats</h6>
                </div>
                <div class="card-body">
                    <div class="text-center">
                        <div class="mb-3">
                            <h4 class="text-primary">{{ $membership->user->workouts->count() }}</h4>
                            <small class="text-muted">Total Workouts</small>
                        </div>
                        <div class="mb-3">
                            <h4 class="text-success">{{ $membership->user->workouts->where('status', 'completed')->count() }}</h4>
                            <small class="text-muted">Completed Workouts</small>
                        </div>
                        <div class="mb-3">
                            @php
                                $daysRemaining = $membership->status === 'active' ? max(0, $membership->end_date->diffInDays(now())) : 0;
                            @endphp
                            <h4 class="text-{{ $daysRemaining > 30 ? 'success' : ($daysRemaining > 7 ? 'warning' : 'danger') }}">
                                {{ $daysRemaining }}
                            </h4>
                            <small class="text-muted">Days Remaining</small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Member Actions -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Quick Actions</h6>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('admin.members.show', $membership->user) }}" class="btn btn-outline-primary btn-sm">
                            <i class="fas fa-user"></i> View Member Profile
                        </a>
                        <a href="{{ route('admin.workouts.create') }}?member={{ $membership->user->id }}" class="btn btn-outline-success btn-sm">
                            <i class="fas fa-plus"></i> Create Workout
                        </a>
                        @if($membership->status === 'active')
                            <button class="btn btn-outline-warning btn-sm" onclick="renewMembership()">
                                <i class="fas fa-redo"></i> Renew Membership
                            </button>
                        @endif
                        @if($membership->status === 'active')
                            <button class="btn btn-outline-danger btn-sm" onclick="cancelMembership()">
                                <i class="fas fa-ban"></i> Cancel Membership
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Notes Section -->
    @if($membership->notes)
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Notes</h6>
        </div>
        <div class="card-body">
            <p class="mb-0">{{ $membership->notes }}</p>
        </div>
    </div>
    @endif

    <!-- Member's Recent Workouts -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Member's Recent Workouts</h6>
        </div>
        <div class="card-body">
            @if($membership->user->workouts->count() > 0)
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Workout Name</th>
                                <th>Type</th>
                                <th>Trainer</th>
                                <th>Date</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($membership->user->workouts->take(10) as $workout)
                            <tr>
                                <td>{{ $workout->name }}</td>
                                <td><span class="badge bg-secondary">{{ ucfirst($workout->type) }}</span></td>
                                <td>{{ $workout->trainer ? $workout->trainer->name : 'Self-guided' }}</td>
                                <td>{{ $workout->workout_date->format('M d, Y') }}</td>
                                <td>
                                    <span class="badge bg-{{ $workout->status === 'completed' ? 'success' : ($workout->status === 'in_progress' ? 'warning' : 'secondary') }}">
                                        {{ ucfirst($workout->status) }}
                                    </span>
                                </td>
                                <td>
                                    <a href="{{ route('admin.workouts.show', $workout) }}" class="btn btn-info btn-sm">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-4">
                    <i class="fas fa-running fa-3x text-muted mb-3"></i>
                    <p class="text-muted">No workouts found for this member</p>
                    <a href="{{ route('admin.workouts.create') }}?member={{ $membership->user->id }}" class="btn btn-primary btn-sm">
                        <i class="fas fa-plus"></i> Create First Workout
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>

<script>
function renewMembership() {
    if (confirm('Are you sure you want to create a renewal for this membership?')) {
        // Redirect to create new membership with pre-filled data
        window.location.href = "{{ route('admin.memberships.create') }}?renew={{ $membership->id }}";
    }
}

function cancelMembership() {
    if (confirm('Are you sure you want to cancel this membership? This action cannot be undone.')) {
        // Create a form to update membership status
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = "{{ route('admin.memberships.update', $membership) }}";
        
        const csrfToken = document.createElement('input');
        csrfToken.type = 'hidden';
        csrfToken.name = '_token';
        csrfToken.value = "{{ csrf_token() }}";
        
        const methodField = document.createElement('input');
        methodField.type = 'hidden';
        methodField.name = '_method';
        methodField.value = 'PUT';
        
        const statusField = document.createElement('input');
        statusField.type = 'hidden';
        statusField.name = 'status';
        statusField.value = 'cancelled';
        
        // Add all current values as hidden fields
        const fields = ['user_id', 'type', 'duration', 'price', 'start_date', 'notes'];
        fields.forEach(field => {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = field;
            input.value = @json([
                'user_id' => $membership->user_id,
                'type' => $membership->type,
                'duration' => $membership->duration,
                'price' => $membership->price,
                'start_date' => $membership->start_date->format('Y-m-d'),
                'notes' => $membership->notes
            ])[field] || '';
            form.appendChild(input);
        });
        
        form.appendChild(csrfToken);
        form.appendChild(methodField);
        form.appendChild(statusField);
        
        document.body.appendChild(form);
        form.submit();
    }
}
</script>
@endsection
