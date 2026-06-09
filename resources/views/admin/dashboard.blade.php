@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('content')
<div class="container-fluid py-4">
    <!-- Welcome Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card text-white border-0 rounded-4 shadow-sm" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                <div class="card-body p-4">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h2 class="fw-bold mb-2">
                                <i class="fas fa-crown me-2"></i> Welcome back, {{ Auth::user()->full_name }}!
                            </h2>
                            <p class="mb-0 opacity-90">
                                Here's your complete overview of the Assignment Submission System.
                            </p>
                        </div>
                        <div class="col-md-4 text-center">
                            <i class="fas fa-chalkboard-user fa-3x opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Cards - Row 1 -->
    <div class="row g-4 mb-4">
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 rounded-4 shadow-sm hover-card">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-2">Total Users</h6>
                            <h2 class="fw-bold mb-0">{{ $totalUsers }}</h2>
                            <small class="text-success">
                                <i class="fas fa-arrow-up me-1"></i> +{{ $newUsersThisMonth }} this month
                            </small>
                        </div>
                        <div class="stat-icon-circle" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                            <i class="fas fa-users"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card border-0 rounded-4 shadow-sm hover-card">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-2">Total Students</h6>
                            <h2 class="fw-bold mb-0">{{ $totalStudents }}</h2>
                            <small class="text-muted">
                                <i class="fas fa-user-graduate me-1"></i> Active learners
                            </small>
                        </div>
                        <div class="stat-icon-circle" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%);">
                            <i class="fas fa-user-graduate"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card border-0 rounded-4 shadow-sm hover-card">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-2">Total Teachers</h6>
                            <h2 class="fw-bold mb-0">{{ $totalTeachers }}</h2>
                            <small class="text-muted">
                                <i class="fas fa-chalkboard-user me-1"></i> Educators
                            </small>
                        </div>
                        <div class="stat-icon-circle" style="background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);">
                            <i class="fas fa-chalkboard-user"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card border-0 rounded-4 shadow-sm hover-card">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-2">Active Courses</h6>
                            <h2 class="fw-bold mb-0">{{ $totalCourses }}</h2>
                            <small class="text-muted">
                                <i class="fas fa-book me-1"></i> Available
                            </small>
                        </div>
                        <div class="stat-icon-circle" style="background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);">
                            <i class="fas fa-book"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Cards - Row 2 -->
    <div class="row g-4 mb-4">
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 rounded-4 shadow-sm hover-card">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-2">Total Assignments</h6>
                            <h2 class="fw-bold mb-0">{{ $totalAssignments }}</h2>
                            <small class="text-muted">
                                <i class="fas fa-tasks me-1"></i> Created
                            </small>
                        </div>
                        <div class="stat-icon-circle" style="background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);">
                            <i class="fas fa-tasks"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card border-0 rounded-4 shadow-sm hover-card">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-2">Total Submissions</h6>
                            <h2 class="fw-bold mb-0">{{ $totalSubmissions }}</h2>
                            <small class="text-success">
                                <i class="fas fa-check-circle me-1"></i> {{ $gradedSubmissions }} graded
                            </small>
                        </div>
                        <div class="stat-icon-circle" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%);">
                            <i class="fas fa-paper-plane"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card border-0 rounded-4 shadow-sm hover-card">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-2">Pending Grading</h6>
                            <h2 class="fw-bold mb-0">{{ $pendingGrading }}</h2>
                            <small class="text-muted">
                                <i class="fas fa-hourglass-half me-1"></i> Awaiting review
                            </small>
                        </div>
                        <div class="stat-icon-circle" style="background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);">
                            <i class="fas fa-hourglass-half"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card border-0 rounded-4 shadow-sm hover-card">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-2">Average Score</h6>
                            <h2 class="fw-bold mb-0">{{ round($averageScore) }}%</h2>
                            <small class="text-muted">
                                <i class="fas fa-chart-line me-1"></i> Overall performance
                            </small>
                        </div>
                        <div class="stat-icon-circle" style="background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);">
                            <i class="fas fa-chart-line"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="row g-4 mb-4">
        <div class="col-lg-6">
            <div class="card border-0 rounded-4 shadow-sm">
                <div class="card-header bg-transparent border-0 pt-4">
                    <h5 class="fw-bold mb-0">
                        <i class="fas fa-chart-bar text-primary me-2"></i> Submissions by Course
                    </h5>
                </div>
                <div class="card-body p-4">
                    <canvas id="submissionsChart" height="250"></canvas>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="card border-0 rounded-4 shadow-sm">
                <div class="card-header bg-transparent border-0 pt-4">
                    <h5 class="fw-bold mb-0">
                        <i class="fas fa-chart-pie text-success me-2"></i> Grade Distribution
                    </h5>
                </div>
                <div class="card-body p-4">
                    <canvas id="gradesChart" height="250"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Activity Row -->
    <div class="row g-4">
        <div class="col-lg-6">
            <div class="card border-0 rounded-4 shadow-sm">
                <div class="card-header bg-transparent border-0 pt-4 d-flex justify-content-between align-items-center">
                    <h5 class="fw-bold mb-0">
                        <i class="fas fa-users text-primary me-2"></i> Recent Users
                    </h5>
                    <a href="{{ route('admin.users.index') }}" class="btn btn-sm btn-outline-primary">
                        View All <i class="fas fa-arrow-right ms-1"></i>
                    </a>
                </div>
                <div class="card-body p-4">
                    <div class="list-group list-group-flush">
                        @forelse($recentUsers as $user)
                            <div class="list-group-item bg-transparent px-0 py-3 border-bottom">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-circle-sm" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                                            <i class="fas fa-user text-white"></i>
                                        </div>
                                        <div class="ms-3">
                                            <h6 class="mb-0 fw-bold">{{ $user->full_name }}</h6>
                                            <small class="text-muted">{{ $user->email }} • {{ ucfirst($user->role->role_name ?? 'User') }}</small>
                                        </div>
                                    </div>
                                    <small class="text-muted">{{ $user->created_at->diffForHumans() }}</small>
                                </div>
                            </div>
                        @empty
                            <p class="text-muted text-center py-3">No recent users</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="card border-0 rounded-4 shadow-sm">
                <div class="card-header bg-transparent border-0 pt-4 d-flex justify-content-between align-items-center">
                    <h5 class="fw-bold mb-0">
                        <i class="fas fa-clock text-warning me-2"></i> Recent Submissions
                    </h5>
                    <a href="{{ route('submissions.index') }}" class="btn btn-sm btn-outline-primary">
                        View All <i class="fas fa-arrow-right ms-1"></i>
                    </a>
                </div>
                <div class="card-body p-4">
                    <div class="list-group list-group-flush">
                        @forelse($recentSubmissions as $submission)
                            <div class="list-group-item bg-transparent px-0 py-3 border-bottom">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="mb-0 fw-bold">{{ $submission->assignment->title ?? 'N/A' }}</h6>
                                        <small class="text-muted">
                                            by {{ $submission->student->full_name ?? 'Student' }} •
                                            {{ $submission->created_at->diffForHumans() }}
                                        </small>
                                    </div>
                                    <div>
                                        @if($submission->grade)
                                            <span class="badge bg-success">Graded</span>
                                        @else
                                            <span class="badge bg-warning">Pending</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @empty
                            <p class="text-muted text-center py-3">No recent submissions</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Grade Distribution Details -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card border-0 rounded-4 shadow-sm">
                <div class="card-header bg-transparent border-0 pt-4">
                    <h5 class="fw-bold mb-0">
                        <i class="fas fa-chart-simple text-info me-2"></i> Grade Distribution Details
                    </h5>
                </div>
                <div class="card-body p-4">
                    <div class="row">
                        @foreach($gradeLabels as $index => $grade)
                            @php
                                $total = $gradeData->sum();
                                $percentage = $total > 0 ? ($gradeData[$index] / $total) * 100 : 0;
                                $colors = ['success', 'info', 'warning', 'orange', 'danger'];
                                $color = $colors[$index] ?? 'secondary';
                            @endphp
                            <div class="col-md-6 mb-3">
                                <div class="d-flex justify-content-between mb-1">
                                    <span class="fw-bold">Grade {{ $grade }}</span>
                                    <span class="text-muted">{{ $gradeData[$index] }} students ({{ round($percentage) }}%)</span>
                                </div>
                                <div class="progress bg-light rounded-pill" style="height: 8px;">
                                    <div class="progress-bar bg-{{ $color }} rounded-pill" style="width: {{ $percentage }}%;"></div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Submissions by Course Chart
    const ctx1 = document.getElementById('submissionsChart').getContext('2d');
    new Chart(ctx1, {
        type: 'bar',
        data: {
            labels: @json($courseLabels),
            datasets: [{
                label: 'Number of Submissions',
                data: @json($submissionData),
                backgroundColor: 'rgba(102, 126, 234, 0.8)',
                borderRadius: 10,
                barPercentage: 0.6
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: { position: 'top' },
                tooltip: { backgroundColor: '#333' }
            },
            scales: {
                y: { beginAtZero: true, grid: { color: 'rgba(0,0,0,0.05)' } },
                x: { grid: { display: false } }
            }
        }
    });

    // Grade Distribution Chart
    const ctx2 = document.getElementById('gradesChart').getContext('2d');
    new Chart(ctx2, {
        type: 'doughnut',
        data: {
            labels: @json($gradeLabels),
            datasets: [{
                data: @json($gradeData),
                backgroundColor: ['#10b981', '#3b82f6', '#f59e0b', '#f97316', '#ef4444'],
                borderWidth: 0,
                hoverOffset: 10
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: { position: 'bottom' },
                tooltip: { backgroundColor: '#333' }
            },
            cutout: '60%'
        }
    });
});
</script>
@endpush

<style>
    .hover-card {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    .hover-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 35px rgba(0,0,0,0.1) !important;
    }

    /* Stat Icon Circle - Visible with gradient */
    .stat-icon-circle {
        width: 55px;
        height: 55px;
        border-radius: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s ease;
        box-shadow: 0 4px 10px rgba(0,0,0,0.1);
    }

    .stat-icon-circle i {
        font-size: 26px;
        color: white;
    }

    .stat-icon-circle:hover {
        transform: scale(1.05);
        box-shadow: 0 6px 15px rgba(0,0,0,0.15);
    }

    /* Avatar Circle */
    .avatar-circle-sm {
        width: 42px;
        height: 42px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }

    .avatar-circle-sm i {
        font-size: 18px;
        color: white;
    }

    .bg-gradient-primary {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }

    .list-group-item {
        transition: all 0.3s ease;
    }

    .list-group-item:hover {
        transform: translateX(5px);
        background-color: rgba(102, 126, 234, 0.04);
    }

    .progress-bar {
        transition: width 0.6s ease;
    }

    .badge {
        font-weight: 500;
        padding: 6px 12px;
    }
</style>
@endsection
