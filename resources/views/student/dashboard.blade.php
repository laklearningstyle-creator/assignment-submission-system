@extends('layouts.app')

@section('title', 'Student Dashboard')

@section('content')
<div class="container-fluid py-3 py-md-4">
    <div class="row g-4">
        <!-- Sidebar -->
        <div class="col-md-3 col-lg-2 mb-4">
            <div class="card border-0 rounded-4 shadow-sm sticky-top" style="top: 80px;">
                <div class="card-header bg-white border-0 pt-4 pb-2">
                    <h5 class="fw-bold mb-0" style="font-size: 1rem;">
                        <i class="fas fa-graduation-cap me-2" style="color: #0D6EFD;"></i> Student Menu
                    </h5>
                </div>
                <div class="card-body p-0">
                    <div class="list-group list-group-flush">
                        <a href="{{ route('student.dashboard') }}" class="list-group-item list-group-item-action border-0 rounded-0 py-3 active" style="background: #0D6EFD; color: white;">
                            <i class="fas fa-tachometer-alt fa-fw me-2"></i> Dashboard
                        </a>
                        <a href="{{ route('student.my-courses') }}" class="list-group-item list-group-item-action border-0 rounded-0 py-3">
                            <i class="fas fa-book-open fa-fw me-2"></i> My Courses
                        </a>
                        <a href="{{ route('student.submissions.index') }}" class="list-group-item list-group-item-action border-0 rounded-0 py-3">
                            <i class="fas fa-upload fa-fw me-2"></i> My Submissions
                        </a>
                        <a href="{{ route('student.my-grades') }}" class="list-group-item list-group-item-action border-0 rounded-0 py-3">
                            <i class="fas fa-medal fa-fw me-2"></i> My Grades
                        </a>
                        <a href="{{ route('assignments.index') }}" class="list-group-item list-group-item-action border-0 rounded-0 py-3">
                            <i class="fas fa-list-check fa-fw me-2"></i> All Assignments
                        </a>
                        <a href="{{ route('courses.index') }}" class="list-group-item list-group-item-action border-0 rounded-0 py-3">
                            <i class="fas fa-compass fa-fw me-2"></i> Browse Courses
                        </a>
                        <hr class="my-2 mx-3">
                        <a href="{{ route('profile.index') }}" class="list-group-item list-group-item-action border-0 rounded-0 py-3">
                            <i class="fas fa-user-circle fa-fw me-2"></i> My Profile
                        </a>
                        <a href="{{ route('notifications.index') }}" class="list-group-item list-group-item-action border-0 rounded-0 py-3">
                            <i class="fas fa-bell fa-fw me-2"></i> Notifications
                            @if(isset($unreadNotifications) && $unreadNotifications > 0)
                                <span class="badge bg-danger rounded-pill ms-2">{{ $unreadNotifications }}</span>
                            @endif
                        </a>
                    </div>
                </div>

                <!-- Quick Stats in Sidebar -->
                <div class="card-footer bg-white border-0 pt-2 pb-4">
                    <hr class="mb-3">
                    <div class="small text-muted">
                        <div class="d-flex justify-content-between mb-3">
                            <span><i class="fas fa-book fa-fw me-1"></i> Enrolled:</span>
                            <span class="fw-bold text-primary">{{ $myCourses->count() }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-3">
                            <span><i class="fas fa-paper-plane fa-fw me-1"></i> Submissions:</span>
                            <span class="fw-bold text-success">{{ $totalSubmissions }}</span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span><i class="fas fa-chart-line fa-fw me-1"></i> Avg. Grade:</span>
                            <span class="fw-bold text-info">{{ round($averageGrade) }}%</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="col-md-9 col-lg-10">
            <!-- Welcome Section -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="card bg-gradient-primary text-white border-0 rounded-4 shadow-sm" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                        <div class="card-body p-4">
                            <div class="row align-items-center">
                                <div class="col-md-8">
                                    <h2 class="fw-bold mb-2" style="font-size: 1.5rem;">
                                        <i class="fas fa-user-graduate me-2"></i> Welcome, {{ Auth::user()->full_name }}!
                                    </h2>
                                    <p class="mb-0 opacity-90" style="font-size: 0.875rem;">
                                        Track your courses, assignments, and academic progress from your student dashboard.
                                    </p>
                                </div>
                                <div class="col-md-4 text-center">
                                    <i class="fas fa-graduation-cap fa-3x opacity-50"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Statistics Cards -->
            <div class="row g-4 mb-5">
                <div class="col-sm-6 col-xl-3">
                    <div class="card border-0 rounded-4 shadow-sm hover-card h-100">
                        <div class="card-body p-4">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="text-muted mb-2" style="font-size: 0.7rem; letter-spacing: 0.5px;">ENROLLED COURSES</h6>
                                    <h2 class="fw-bold mb-0 text-primary" style="font-size: 1.75rem;">{{ $myCourses->count() }}</h2>
                                </div>
                                <i class="fas fa-graduation-cap fa-2x text-primary"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-xl-3">
                    <div class="card border-0 rounded-4 shadow-sm hover-card h-100">
                        <div class="card-body p-4">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="text-muted mb-2" style="font-size: 0.7rem; letter-spacing: 0.5px;">TOTAL SUBMISSIONS</h6>
                                    <h2 class="fw-bold mb-0 text-success" style="font-size: 1.75rem;">{{ $totalSubmissions }}</h2>
                                </div>
                                <i class="fas fa-file-alt fa-2x text-success"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-xl-3">
                    <div class="card border-0 rounded-4 shadow-sm hover-card h-100">
                        <div class="card-body p-4">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="text-muted mb-2" style="font-size: 0.7rem; letter-spacing: 0.5px;">PENDING GRADING</h6>
                                    <h2 class="fw-bold mb-0 text-warning" style="font-size: 1.75rem;">{{ $pendingSubmissions }}</h2>
                                </div>
                                <i class="fas fa-hourglass-half fa-2x text-warning"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-xl-3">
                    <div class="card border-0 rounded-4 shadow-sm hover-card h-100">
                        <div class="card-body p-4">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="text-muted mb-2" style="font-size: 0.7rem; letter-spacing: 0.5px;">AVERAGE GRADE</h6>
                                    <h2 class="fw-bold mb-0 text-info" style="font-size: 1.75rem;">{{ round($averageGrade) }}%</h2>
                                </div>
                                <i class="fas fa-chart-simple fa-2x text-info"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- My Courses Section -->
            <div class="row mb-5">
                <div class="col-12">
                    <div class="card shadow-sm border-0 rounded-4">
                        <div class="card-header bg-transparent border-0 pt-4 pb-2 px-4">
                            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                                <h5 class="fw-bold mb-0" style="font-size: 1rem;">
                                    <i class="fas fa-book-open text-primary me-2"></i> My Enrolled Courses
                                </h5>
                                @if($myCourses->count() > 0)
                                    <a href="{{ route('student.my-courses') }}" class="btn btn-sm btn-outline-primary" style="font-size: 0.75rem; border-radius: 20px;">
                                        View All <i class="fas fa-arrow-right ms-1"></i>
                                    </a>
                                @endif
                            </div>
                        </div>
                        <div class="card-body p-4 pt-2">
                            @if($myCourses->count() > 0)
                                <div class="row g-4">
                                    @foreach($myCourses->take(6) as $course)
                                    <div class="col-md-6 col-lg-4">
                                        <div class="card h-100 border-0 shadow-sm course-card">
                                            <div class="card-body p-4">
                                                <div class="d-flex justify-content-between align-items-start mb-3">
                                                    <i class="fas fa-school fa-2x text-primary"></i>
                                                    <span class="badge bg-success px-3 py-2 rounded-pill" style="font-size: 0.65rem;">
                                                        <i class="fas fa-check-circle me-1"></i> Enrolled
                                                    </span>
                                                </div>
                                                <h5 class="fw-bold mb-2" style="font-size: 1rem;">{{ Str::limit($course->course_name, 35) }}</h5>
                                                <p class="text-muted small mb-2" style="font-size: 0.7rem;">
                                                    <i class="fas fa-code me-1"></i> Code: {{ $course->course_code }}
                                                </p>
                                                <p class="card-text text-muted small mb-3" style="font-size: 0.7rem; line-height: 1.4;">
                                                    {{ Str::limit($course->description ?? 'No description', 60) }}
                                                </p>
                                                <a href="{{ route('courses.show', $course->course_id) }}" class="btn btn-primary btn-sm w-100" style="font-size: 0.75rem; border-radius: 8px;">
                                                    <i class="fas fa-eye me-1"></i> View Course
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                                @if($myCourses->count() > 6)
                                    <div class="text-center mt-4">
                                        <a href="{{ route('student.my-courses') }}" class="btn btn-outline-primary btn-sm" style="border-radius: 20px;">
                                            Load More <i class="fas fa-arrow-down ms-1"></i>
                                        </a>
                                    </div>
                                @endif
                            @else
                                <div class="text-center py-5">
                                    <i class="fas fa-book fa-3x text-muted mb-3 opacity-25"></i>
                                    <h5 class="text-muted" style="font-size: 1rem;">No Courses Enrolled</h5>
                                    <p class="text-muted small mb-3">You haven't enrolled in any courses yet.</p>
                                    <a href="{{ route('courses.index') }}" class="btn btn-primary btn-sm px-4" style="border-radius: 20px;">
                                        <i class="fas fa-search me-1"></i> Browse Courses
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Submissions Section -->
            <div class="row">
                <div class="col-12">
                    <div class="card shadow-sm border-0 rounded-4">
                        <div class="card-header bg-transparent border-0 pt-4 pb-2 px-4">
                            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                                <h5 class="fw-bold mb-0" style="font-size: 1rem;">
                                    <i class="fas fa-clock text-warning me-2"></i> Recent Submissions
                                </h5>
                                <a href="{{ route('student.submissions.index') }}" class="btn btn-sm btn-outline-primary" style="font-size: 0.75rem; border-radius: 20px;">
                                    View All <i class="fas fa-arrow-right ms-1"></i>
                                </a>
                            </div>
                        </div>
                        <div class="card-body p-4 pt-2">
                            @if($mySubmissions->count() > 0)
                                <div class="table-responsive">
                                    <table class="table table-hover align-middle mb-0">
                                        <thead class="table-light">
                                            <tr>
                                                <th style="font-size: 0.75rem; padding: 12px 8px;">Assignment</th>
                                                <th style="font-size: 0.75rem; padding: 12px 8px;">Course</th>
                                                <th style="font-size: 0.75rem; padding: 12px 8px;">Submitted Date</th>
                                                <th style="font-size: 0.75rem; padding: 12px 8px;">Grade</th>
                                                <th style="font-size: 0.75rem; padding: 12px 8px;">Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($mySubmissions->take(5) as $submission)
                                            @php
                                                $submittedAt = $submission->created_at;
                                                $now = now();
                                                $daysAgo = $submittedAt->diffInDays($now);

                                                if ($daysAgo == 0) {
                                                    $timeAgo = 'Today';
                                                } elseif ($daysAgo == 1) {
                                                    $timeAgo = 'Yesterday';
                                                } elseif ($daysAgo <= 7) {
                                                    $timeAgo = $daysAgo . ' days ago';
                                                } else {
                                                    $timeAgo = $submittedAt->format('M d, Y');
                                                }
                                            @endphp
                                            <tr>
                                                <td style="padding: 12px 8px;">
                                                    <a href="{{ route('submissions.show', $submission->submission_id) }}" class="text-decoration-none fw-semibold" style="font-size: 0.8rem; color: #1a1a2e;">
                                                        {{ Str::limit($submission->assignment->title ?? 'N/A', 30) }}
                                                    </a>
                                                </td>
                                                <td style="padding: 12px 8px; font-size: 0.75rem;">{{ $submission->assignment->course->course_name ?? 'N/A' }}</td>
                                                <td style="padding: 12px 8px; font-size: 0.75rem;">
                                                    <i class="fas fa-calendar-alt text-muted me-1"></i>
                                                    {{ $timeAgo }}
                                                </td>
                                                <td style="padding: 12px 8px;">
                                                    @if($submission->grade)
                                                        <span class="fw-bold text-success" style="font-size: 0.75rem;">
                                                            {{ $submission->grade->marks_obtained }}/{{ $submission->assignment->total_marks ?? 100 }}
                                                        </span>
                                                        <br>
                                                        <small class="text-muted" style="font-size: 0.65rem;">Grade: {{ $submission->grade->grade }}</small>
                                                    @else
                                                        <span class="text-muted" style="font-size: 0.75rem;">—</span>
                                                    @endif
                                                </td>
                                                <td style="padding: 12px 8px;">
                                                    @if($submission->grade)
                                                        <span class="badge bg-success px-3 py-2 rounded-pill" style="font-size: 0.65rem;"><i class="fas fa-check-circle me-1"></i> Graded</span>
                                                    @else
                                                        <span class="badge bg-warning px-3 py-2 rounded-pill" style="font-size: 0.65rem;"><i class="fas fa-clock me-1"></i> Submitted</span>
                                                    @endif
                                                    @if($submission->is_late)
                                                        <span class="badge bg-danger ms-1 px-3 py-2 rounded-pill" style="font-size: 0.65rem;"><i class="fas fa-exclamation-triangle me-1"></i> Late</span>
                                                    @endif
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <div class="text-center py-5">
                                    <i class="fas fa-inbox fa-3x text-muted mb-3 opacity-25"></i>
                                    <p class="text-muted mb-0" style="font-size: 0.8rem;">No submissions yet.</p>
                                    <a href="{{ route('assignments.index') }}" class="btn btn-primary btn-sm mt-3 px-4" style="border-radius: 20px;">
                                        <i class="fas fa-plus me-1"></i> Submit Assignment
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .bg-gradient-primary {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }

    .hover-card {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    .hover-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(0,0,0,0.1) !important;
    }

    .course-card {
        transition: all 0.3s ease;
        border-radius: 12px;
    }
    .course-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 12px 28px rgba(0,0,0,0.12) !important;
    }

    .table-hover tbody tr:hover {
        background-color: rgba(102, 126, 234, 0.04);
    }

    .badge {
        font-weight: 500;
    }

    /* Sidebar Styling */
    .list-group-item {
        transition: all 0.25s ease;
        font-size: 0.8rem;
        border: none;
    }

    .list-group-item:not(.active):hover {
        background-color: #f8fafc;
        color: #0D6EFD;
        padding-left: 28px;
    }

    .list-group-item.active {
        background: #0D6EFD;
        color: white;
        box-shadow: 0 2px 8px rgba(13, 110, 253, 0.3);
    }

    /* Sticky Sidebar */
    .sticky-top {
        position: sticky;
        top: 20px;
        z-index: 100;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .sticky-top {
            position: relative;
            top: 0;
            margin-bottom: 1rem;
        }

        .container-fluid {
            padding-left: 16px;
            padding-right: 16px;
        }

        h2 {
            font-size: 1.25rem !important;
        }

        .row.g-4 {
            --bs-gutter-y: 1rem;
        }
    }

    /* Better spacing for cards */
    .card-body {
        transition: all 0.2s ease;
    }

    /* Improved typography */
    .small, small {
        line-height: 1.5;
    }
</style>
@endsection
