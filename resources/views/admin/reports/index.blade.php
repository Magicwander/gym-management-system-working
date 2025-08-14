@extends('admin.layouts.app')

@section('title', 'Reports Dashboard')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-chart-bar me-2"></i>Reports Dashboard
        </h1>
    </div>

    <!-- Report Categories -->
    <div class="row">
        <!-- Member Reports -->
        <div class="col-lg-6 col-md-12 mb-4">
            <div class="card shadow h-100">
                <div class="card-header py-3 bg-primary text-white">
                    <h6 class="m-0 font-weight-bold">
                        <i class="fas fa-users me-2"></i>Member Reports
                    </h6>
                </div>
                <div class="card-body">
                    <p class="card-text">Generate comprehensive reports about gym members, their activities, and membership statistics.</p>
                    
                    <div class="mb-3">
                        <h6 class="text-muted">Available Reports:</h6>
                        <ul class="list-unstyled">
                            <li><i class="fas fa-check text-success me-2"></i>Member registration trends</li>
                            <li><i class="fas fa-check text-success me-2"></i>Active vs inactive members</li>
                            <li><i class="fas fa-check text-success me-2"></i>Member spending analysis</li>
                            <li><i class="fas fa-check text-success me-2"></i>Membership duration statistics</li>
                        </ul>
                    </div>
                    
                    <div class="d-grid gap-2">
                        <a href="{{ route('admin.reports.members') }}" class="btn btn-primary">
                            <i class="fas fa-chart-line me-1"></i> View Member Reports
                        </a>
                        <a href="{{ route('admin.reports.export.csv', ['type' => 'members']) }}" class="btn btn-outline-primary">
                            <i class="fas fa-download me-1"></i> Export Member Data
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Trainer Reports -->
        <div class="col-lg-6 col-md-12 mb-4">
            <div class="card shadow h-100">
                <div class="card-header py-3 bg-success text-white">
                    <h6 class="m-0 font-weight-bold">
                        <i class="fas fa-dumbbell me-2"></i>Trainer Reports
                    </h6>
                </div>
                <div class="card-body">
                    <p class="card-text">Analyze trainer performance, booking statistics, and earnings reports.</p>
                    
                    <div class="mb-3">
                        <h6 class="text-muted">Available Reports:</h6>
                        <ul class="list-unstyled">
                            <li><i class="fas fa-check text-success me-2"></i>Trainer booking statistics</li>
                            <li><i class="fas fa-check text-success me-2"></i>Earnings and commission reports</li>
                            <li><i class="fas fa-check text-success me-2"></i>Client satisfaction metrics</li>
                            <li><i class="fas fa-check text-success me-2"></i>Performance comparisons</li>
                        </ul>
                    </div>
                    
                    <div class="d-grid gap-2">
                        <a href="{{ route('admin.reports.trainers') }}" class="btn btn-success">
                            <i class="fas fa-chart-line me-1"></i> View Trainer Reports
                        </a>
                        <a href="{{ route('admin.reports.export.csv', ['type' => 'trainers']) }}" class="btn btn-outline-success">
                            <i class="fas fa-download me-1"></i> Export Trainer Data
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Booking Reports -->
        <div class="col-lg-6 col-md-12 mb-4">
            <div class="card shadow h-100">
                <div class="card-header py-3 bg-info text-white">
                    <h6 class="m-0 font-weight-bold">
                        <i class="fas fa-calendar-alt me-2"></i>Booking Reports
                    </h6>
                </div>
                <div class="card-body">
                    <p class="card-text">Track booking patterns, session types, and scheduling efficiency.</p>
                    
                    <div class="mb-3">
                        <h6 class="text-muted">Available Reports:</h6>
                        <ul class="list-unstyled">
                            <li><i class="fas fa-check text-success me-2"></i>Booking volume trends</li>
                            <li><i class="fas fa-check text-success me-2"></i>Peak hours analysis</li>
                            <li><i class="fas fa-check text-success me-2"></i>Cancellation rates</li>
                            <li><i class="fas fa-check text-success me-2"></i>Session type popularity</li>
                        </ul>
                    </div>
                    
                    <div class="d-grid gap-2">
                        <a href="{{ route('admin.reports.bookings') }}" class="btn btn-info">
                            <i class="fas fa-chart-line me-1"></i> View Booking Reports
                        </a>
                        <a href="{{ route('admin.reports.export.csv', ['type' => 'bookings']) }}" class="btn btn-outline-info">
                            <i class="fas fa-download me-1"></i> Export Booking Data
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Revenue Reports -->
        <div class="col-lg-6 col-md-12 mb-4">
            <div class="card shadow h-100">
                <div class="card-header py-3 bg-warning text-white">
                    <h6 class="m-0 font-weight-bold">
                        <i class="fas fa-dollar-sign me-2"></i>Revenue Reports
                    </h6>
                </div>
                <div class="card-body">
                    <p class="card-text">Comprehensive financial reports including payments, revenue trends, and profitability analysis.</p>
                    
                    <div class="mb-3">
                        <h6 class="text-muted">Available Reports:</h6>
                        <ul class="list-unstyled">
                            <li><i class="fas fa-check text-success me-2"></i>Daily/Monthly revenue</li>
                            <li><i class="fas fa-check text-success me-2"></i>Payment method analysis</li>
                            <li><i class="fas fa-check text-success me-2"></i>Revenue forecasting</li>
                            <li><i class="fas fa-check text-success me-2"></i>Profit margin reports</li>
                        </ul>
                    </div>
                    
                    <div class="d-grid gap-2">
                        <a href="{{ route('admin.reports.revenue') }}" class="btn btn-warning">
                            <i class="fas fa-chart-line me-1"></i> View Revenue Reports
                        </a>
                        <a href="{{ route('admin.reports.export.csv', ['type' => 'payments']) }}" class="btn btn-outline-warning">
                            <i class="fas fa-download me-1"></i> Export Payment Data
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Stats -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Quick Statistics</h6>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-md-3 mb-3">
                            <div class="card border-left-primary h-100">
                                <div class="card-body">
                                    <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Members</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">{{ \App\Models\User::where('role', 'member')->count() }}</div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-3 mb-3">
                            <div class="card border-left-success h-100">
                                <div class="card-body">
                                    <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Active Trainers</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">{{ \App\Models\User::where('role', 'trainer')->where('is_active', true)->count() }}</div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-3 mb-3">
                            <div class="card border-left-info h-100">
                                <div class="card-body">
                                    <div class="text-xs font-weight-bold text-info text-uppercase mb-1">This Month's Bookings</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">{{ \App\Models\Customer\CustomerBooking::whereMonth('created_at', now()->month)->count() }}</div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-3 mb-3">
                            <div class="card border-left-warning h-100">
                                <div class="card-body">
                                    <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Monthly Revenue</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">${{ number_format(\App\Models\Customer\PaymentRecord::thisMonth()->completed()->sum('amount'), 2) }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Custom Report Generator -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Custom Report Generator</h6>
                </div>
                <div class="card-body">
                    <form method="GET" id="customReportForm">
                        <div class="row">
                            <div class="col-md-3">
                                <label for="report_type" class="form-label">Report Type</label>
                                <select name="report_type" id="report_type" class="form-select">
                                    <option value="">Select Report Type</option>
                                    <option value="members">Members</option>
                                    <option value="trainers">Trainers</option>
                                    <option value="bookings">Bookings</option>
                                    <option value="revenue">Revenue</option>
                                </select>
                            </div>
                            
                            <div class="col-md-3">
                                <label for="date_from" class="form-label">From Date</label>
                                <input type="date" name="date_from" id="date_from" class="form-control" value="{{ now()->startOfMonth()->format('Y-m-d') }}">
                            </div>
                            
                            <div class="col-md-3">
                                <label for="date_to" class="form-label">To Date</label>
                                <input type="date" name="date_to" id="date_to" class="form-control" value="{{ now()->format('Y-m-d') }}">
                            </div>
                            
                            <div class="col-md-3 d-flex align-items-end">
                                <div class="btn-group w-100">
                                    <button type="button" class="btn btn-primary" onclick="generateReport('view')">
                                        <i class="fas fa-eye me-1"></i> View
                                    </button>
                                    <button type="button" class="btn btn-success" onclick="generateReport('pdf')">
                                        <i class="fas fa-file-pdf me-1"></i> PDF
                                    </button>
                                    <button type="button" class="btn btn-info" onclick="generateReport('csv')">
                                        <i class="fas fa-file-csv me-1"></i> CSV
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function generateReport(format) {
    const reportType = document.getElementById('report_type').value;
    const dateFrom = document.getElementById('date_from').value;
    const dateTo = document.getElementById('date_to').value;
    
    if (!reportType) {
        alert('Please select a report type.');
        return;
    }
    
    let url = '';
    const params = new URLSearchParams({
        date_from: dateFrom,
        date_to: dateTo
    });
    
    if (format === 'pdf') {
        params.append('format', 'pdf');
    }
    
    switch (reportType) {
        case 'members':
            url = format === 'csv' ? 
                '{{ route("admin.reports.export.csv") }}?' + params.toString() + '&type=members' :
                '{{ route("admin.reports.members") }}?' + params.toString();
            break;
        case 'trainers':
            url = format === 'csv' ? 
                '{{ route("admin.reports.export.csv") }}?' + params.toString() + '&type=trainers' :
                '{{ route("admin.reports.trainers") }}?' + params.toString();
            break;
        case 'bookings':
            url = format === 'csv' ? 
                '{{ route("admin.reports.export.csv") }}?' + params.toString() + '&type=bookings' :
                '{{ route("admin.reports.bookings") }}?' + params.toString();
            break;
        case 'revenue':
            url = format === 'csv' ? 
                '{{ route("admin.reports.export.csv") }}?' + params.toString() + '&type=payments' :
                '{{ route("admin.reports.revenue") }}?' + params.toString();
            break;
    }
    
    if (url) {
        window.open(url, format === 'view' ? '_self' : '_blank');
    }
}
</script>
@endsection