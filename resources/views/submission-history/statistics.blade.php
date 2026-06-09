@extends('layouts.app')

@section('title', 'Submission History Statistics')

@section('content')
<div class="container py-4">
    <div class="row">
        <div class="col-12">
            <div class="card shadow-lg border-0 rounded-4">
                <div class="card-header bg-gradient-primary text-white rounded-top-4">
                    <h4 class="mb-0">
                        <i class="fas fa-chart-bar me-2"></i> Submission History Statistics
                    </h4>
                </div>
                <div class="card-body p-4">
                    <!-- Summary Cards -->
                    <div class="row g-4 mb-4">
                        <div class="col-md-3">
                            <div class="card bg-primary bg-opacity-10 border-0 text-center p-3">
                                <h2 class="mb-0 text-primary">{{ $totalActions }}</h2>
                                <small>Total Actions</small>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-success bg-opacity-10 border-0 text-center p-3">
                                <h2 class="mb-0 text-success">{{ $actionsByType->sum('count') }}</h2>
                                <small>Recorded Events</small>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-info bg-opacity-10 border-0 text-center p-3">
                                <h2 class="mb-0 text-info">{{ $actionsByType->count() }}</h2>
                                <small>Action Types</small>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-warning bg-opacity-10 border-0 text-center p-3">
                                <h2 class="mb-0 text-warning">{{ $dailyActivity->count() }}</h2>
                                <small>Active Days</small>
                            </div>
                        </div>
                    </div>

                    <!-- Actions by Type -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="card border-0 shadow-sm">
                                <div class="card-header bg-transparent">
                                    <h6 class="fw-bold">Actions by Type</h6>
                                </div>
                                <div class="card-body">
                                    @foreach($actionsByType as $action)
                                        <div class="mb-3">
                                            <div class="d-flex justify-content-between mb-1">
                                                <span>{{ $action->action }}</span>
                                                <span class="fw-bold">{{ $action->count }}</span>
                                            </div>
                                            <div class="progress bg-light rounded-pill" style="height: 8px;">
                                                <div class="progress-bar bg-primary rounded-pill" style="width: {{ ($action->count / $totalActions) * 100 }}%"></div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="card border-0 shadow-sm">
                                <div class="card-header bg-transparent">
                                    <h6 class="fw-bold">Recent Activity (30 Days)</h6>
                                </div>
                                <div class="card-body" style="height: 300px;">
                                    <canvas id="activityChart"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Recent Activity List -->
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-transparent">
                            <h6 class="fw-bold">Recent Activity</h6>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover align-middle">
                                    <thead>
                                        <tr><th>Student</th><th>Action</th><th>Performed By</th><th>Time</th></tr>
                                    </thead>
                                    <tbody>
                                        @foreach($recentActivity as $activity)
                                        <tr>
                                            <td>{{ $activity->submission->student->full_name ?? 'N/A' }}</td>
                                            <td><span class="badge bg-info">{{ $activity->action }}</span></td>
                                            <td>{{ $activity->performer->full_name ?? 'N/A' }}</td>
                                            <td>{{ $activity->performed_at->diffForHumans() }}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('activityChart').getContext('2d');
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: @json($dailyActivity->pluck('date')),
            datasets: [{
                label: 'Activities',
                data: @json($dailyActivity->pluck('count')),
                borderColor: '#667eea',
                backgroundColor: 'rgba(102, 126, 234, 0.1)',
                fill: true,
                tension: 0.4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: { legend: { position: 'top' } }
        }
    });
</script>
@endsection
