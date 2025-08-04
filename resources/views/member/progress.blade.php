@extends('member.layout')

@section('title', 'My Progress')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="page-header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h2 class="mb-1">
                    <i class="fas fa-chart-line me-2"></i>My Progress
                </h2>
                <p class="mb-0">Track your fitness journey and achievements</p>
            </div>
            <div class="btn-group">
                <button class="btn btn-light" id="exportProgress">
                    <i class="fas fa-download me-2"></i>Export Report
                </button>
            </div>
        </div>
    </div>

    <!-- Progress Statistics -->
    <div class="row mb-4">
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card text-center h-100">
                <div class="card-body">
                    <div class="progress-circle mb-3">
                        <i class="fas fa-running fa-3x text-primary"></i>
                    </div>
                    <h3 class="mb-1">{{ $totalWorkouts }}</h3>
                    <p class="text-muted mb-0">Total Workouts</p>
                </div>
            </div>
        </div>
        
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card text-center h-100">
                <div class="card-body">
                    <div class="progress-circle mb-3">
                        <i class="fas fa-check-circle fa-3x text-success"></i>
                    </div>
                    <h3 class="mb-1">{{ $completedWorkouts }}</h3>
                    <p class="text-muted mb-0">Completed</p>
                    <small class="text-success">
                        {{ $totalWorkouts > 0 ? round(($completedWorkouts / $totalWorkouts) * 100) : 0 }}% completion rate
                    </small>
                </div>
            </div>
        </div>
        
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card text-center h-100">
                <div class="card-body">
                    <div class="progress-circle mb-3">
                        <i class="fas fa-calendar fa-3x text-warning"></i>
                    </div>
                    <h3 class="mb-1">{{ $thisMonthWorkouts }}</h3>
                    <p class="text-muted mb-0">This Month</p>
                    <small class="text-warning">
                        {{ $thisMonthWorkouts >= 8 ? 'Great consistency!' : 'Keep it up!' }}
                    </small>
                </div>
            </div>
        </div>
        
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card text-center h-100">
                <div class="card-body">
                    <div class="progress-circle mb-3">
                        <i class="fas fa-fire fa-3x text-danger"></i>
                    </div>
                    <h3 class="mb-1">{{ auth()->user()->workouts()->sum('calories_burned') ?: 0 }}</h3>
                    <p class="text-muted mb-0">Calories Burned</p>
                    <small class="text-danger">Total lifetime</small>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Workout Trend Chart -->
        <div class="col-lg-8 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-chart-area me-2"></i>Workout Trend (Last 6 Months)
                    </h5>
                </div>
                <div class="card-body">
                    <canvas id="workoutTrendChart" height="100"></canvas>
                </div>
            </div>
        </div>

        <!-- Workout Types Distribution -->
        <div class="col-lg-4 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-chart-pie me-2"></i>Workout Types
                    </h5>
                </div>
                <div class="card-body">
                    @if(count($workoutTypes) > 0)
                        <canvas id="workoutTypesChart" height="200"></canvas>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-chart-pie fa-3x text-muted mb-3"></i>
                            <p class="text-muted">No workout data available yet</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Achievements Section -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-trophy me-2"></i>Achievements & Milestones
                    </h5>
                </div>
                <div class="card-body">
                    @if(count($achievements) > 0)
                        <div class="row">
                            @foreach($achievements as $achievement)
                                <div class="col-lg-3 col-md-4 col-sm-6 mb-3">
                                    <div class="achievement-card text-center p-3">
                                        <div class="achievement-icon mb-2">
                                            <span class="badge bg-{{ $achievement['color'] }} fs-1">
                                                {{ $achievement['icon'] }}
                                            </span>
                                        </div>
                                        <h6 class="achievement-name">{{ $achievement['name'] }}</h6>
                                        <small class="text-muted">Unlocked!</small>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-trophy fa-3x text-muted mb-3"></i>
                            <h6 class="text-muted">No achievements yet</h6>
                            <p class="text-muted">Complete workouts to unlock achievements!</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Goals Section -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            <i class="fas fa-bullseye me-2"></i>Fitness Goals
                        </h5>
                        <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#goalsModal">
                            <i class="fas fa-edit me-1"></i>Update Goals
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    @if(auth()->user()->fitness_goals)
                        <div class="goals-content">
                            <p>{{ auth()->user()->fitness_goals }}</p>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-bullseye fa-3x text-muted mb-3"></i>
                            <h6 class="text-muted">No fitness goals set</h6>
                            <p class="text-muted">Set your fitness goals to stay motivated!</p>
                            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#goalsModal">
                                <i class="fas fa-plus me-2"></i>Set Goals
                            </button>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Goals Modal -->
<div class="modal fade" id="goalsModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Update Fitness Goals</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('member.profile.update') }}" method="POST">
                @csrf
                @method('PATCH')
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="fitness_goals" class="form-label">Your Fitness Goals</label>
                        <textarea class="form-control" id="fitness_goals" name="fitness_goals" 
                                  rows="5" placeholder="Describe your fitness goals...">{{ auth()->user()->fitness_goals }}</textarea>
                        <div class="form-text">
                            Be specific about what you want to achieve (e.g., lose weight, build muscle, improve endurance)
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save Goals</button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
.progress-circle {
    width: 80px;
    height: 80px;
    margin: 0 auto;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 50%;
    background: rgba(102, 126, 234, 0.1);
}

.achievement-card {
    background: #f8f9fa;
    border-radius: 15px;
    transition: all 0.3s ease;
    border: 2px solid transparent;
}

.achievement-card:hover {
    transform: translateY(-5px);
    border-color: #667eea;
    box-shadow: 0 8px 16px rgba(0,0,0,0.1);
}

.achievement-icon {
    font-size: 2rem;
}

.achievement-name {
    font-weight: 600;
    color: #333;
}

.goals-content {
    background: #f8f9fa;
    border-radius: 10px;
    padding: 1.5rem;
    border-left: 4px solid #667eea;
}
</style>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Workout Trend Chart
    const trendCtx = document.getElementById('workoutTrendChart');
    if (trendCtx) {
        const monthlyData = @json($monthlyData);
        
        new Chart(trendCtx, {
            type: 'line',
            data: {
                labels: monthlyData.map(item => item.month),
                datasets: [{
                    label: 'Workouts',
                    data: monthlyData.map(item => item.count),
                    borderColor: '#667eea',
                    backgroundColor: 'rgba(102, 126, 234, 0.1)',
                    tension: 0.4,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1
                        }
                    }
                }
            }
        });
    }

    // Workout Types Chart
    const typesCtx = document.getElementById('workoutTypesChart');
    if (typesCtx) {
        const workoutTypes = @json($workoutTypes);
        
        if (Object.keys(workoutTypes).length > 0) {
            new Chart(typesCtx, {
                type: 'doughnut',
                data: {
                    labels: Object.keys(workoutTypes).map(type => 
                        type.charAt(0).toUpperCase() + type.slice(1).replace('_', ' ')
                    ),
                    datasets: [{
                        data: Object.values(workoutTypes),
                        backgroundColor: [
                            '#667eea',
                            '#764ba2',
                            '#f093fb',
                            '#f5576c',
                            '#4facfe',
                            '#00f2fe'
                        ]
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom'
                        }
                    }
                }
            });
        }
    }

    // Export functionality
    document.getElementById('exportProgress').addEventListener('click', function() {
        // Simulate export (you can implement actual functionality)
        this.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Exporting...';
        
        setTimeout(() => {
            this.innerHTML = '<i class="fas fa-download me-2"></i>Export Report';
            alert('Progress report exported successfully!');
        }, 2000);
    });
});
</script>
@endsection
