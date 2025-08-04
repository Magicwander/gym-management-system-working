@extends('admin.layouts.app')

@section('title', 'Memberships Management')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Memberships Management</h1>
        <a href="{{ route('admin.memberships.create') }}" class="btn btn-primary btn-sm">
            <i class="fas fa-plus fa-sm text-white-50"></i> Add New Membership
        </a>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Memberships</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $memberships->total() }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-id-card fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Active Memberships</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $memberships->where('status', 'active')->count() }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Platinum Members</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $memberships->where('type', 'platinum')->count() }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-crown fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Monthly Revenue</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">Rs {{ number_format($memberships->where('created_at', '>=', now()->startOfMonth())->sum('price'), 2) }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">All Memberships</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Member</th>
                            <th>Type</th>
                            <th>Duration</th>
                            <th>Price</th>
                            <th>Start Date</th>
                            <th>End Date</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($memberships as $membership)
                        <tr>
                            <td>{{ $membership->id }}</td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="me-3">
                                        <div class="icon-circle bg-primary">
                                            <i class="fas fa-user text-white"></i>
                                        </div>
                                    </div>
                                    <div>
                                        <div class="font-weight-bold">{{ $membership->user->name }}</div>
                                        <div class="small text-gray-500">{{ $membership->user->email }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="badge bg-{{ $membership->type == 'platinum' ? 'warning' : ($membership->type == 'gold' ? 'success' : 'info') }}">
                                    <i class="fas fa-{{ $membership->type == 'platinum' ? 'crown' : ($membership->type == 'gold' ? 'medal' : 'star') }} me-1"></i>
                                    {{ ucfirst($membership->type) }}
                                </span>
                            </td>
                            <td>{{ ucfirst(str_replace('_', ' ', $membership->duration)) }}</td>
                            <td><strong>Rs {{ number_format($membership->price, 2) }}</strong></td>
                            <td>{{ $membership->start_date->format('M d, Y') }}</td>
                            <td>{{ $membership->end_date->format('M d, Y') }}</td>
                            <td>
                                @php
                                    $statusClass = 'secondary';
                                    $statusIcon = 'circle';
                                    if ($membership->status == 'active') {
                                        $statusClass = 'success';
                                        $statusIcon = 'check-circle';
                                    } elseif ($membership->status == 'expired') {
                                        $statusClass = 'warning';
                                        $statusIcon = 'clock';
                                    } elseif ($membership->status == 'cancelled') {
                                        $statusClass = 'danger';
                                        $statusIcon = 'times-circle';
                                    }
                                @endphp
                                <span class="badge bg-{{ $statusClass }}">
                                    <i class="fas fa-{{ $statusIcon }} me-1"></i>
                                    {{ ucfirst($membership->status) }}
                                </span>
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('admin.memberships.show', $membership) }}" class="btn btn-info btn-sm">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.memberships.edit', $membership) }}" class="btn btn-warning btn-sm">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('admin.memberships.destroy', $membership) }}" method="POST" style="display: inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            <div class="d-flex justify-content-center">
                {{ $memberships->links() }}
            </div>
        </div>
    </div>
</div>

<style>
.icon-circle {
    height: 2.5rem;
    width: 2.5rem;
    border-radius: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
}
</style>
@endsection
