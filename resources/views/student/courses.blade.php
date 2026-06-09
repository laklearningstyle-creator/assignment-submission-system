@extends('layouts.app')

@section('title', 'My Courses')

@section('content')
<div class="container py-4">
    <div class="row">
        <!-- Sidebar -->
        <div class="col-md-3 col-lg-2 mb-4">
            <div class="card border-0 rounded-4 shadow-sm sticky-top" style="top: 80px;">
                <div class="card-header bg-white border-0 pt-4">
                    <h5 class="fw-bold mb-0" style="font-size: 1rem;">
                        <i class="fas fa-graduation-cap me-2" style="color: #0D6EFD;"></i> Student Menu
                    </h5>
                </div>
                <div class="card-body p-0">
                    <div class="list-group list-group-flush">
                        <a href="{{ route('student.dashboard') }}" class="list-group-item list-group-item-action border-0 rounded-0 py-3">
                            <i class="fas fa-tachometer-alt me-2"></i> Dashboard
                        </a>
                        <a href="{{ route('student.my-courses') }}" class="list-group-item list-group-item-action border-0 rounded-0 py-3 active" style="background: #0D6EFD; color: white;">
                            <i class="fas fa-book me-2"></i> My Courses
                            <span class="badge bg-white text-primary rounded-pill ms-2">{{ $enrollments->count() }}</span>
                        </a>
                        <a href="{{ route('student.submissions.index') }}" class="list-group-item list-group-item-action border-0 rounded-0 py-3">
                            <i class="fas fa-paper-plane me-2"></i> My Submissions
                        </a>
                        <a href="{{ route('student.my-grades') }}" class="list-group-item list-group-item-action border-0 rounded-0 py-3">
                            <i class="fas fa-star me-2"></i> My Grades
                        </a>
                        <a href="{{ route('assignments.index') }}" class="list-group-item list-group-item-action border-0 rounded-0 py-3">
                            <i class="fas fa-tasks me-2"></i> All Assignments
                        </a>
                        <a href="{{ route('courses.index') }}" class="list-group-item list-group-item-action border-0 rounded-0 py-3">
                            <i class="fas fa-search me-2"></i> Browse Courses
                        </a>
                        <hr class="my-2">
                        <a href="{{ route('profile.index') }}" class="list-group-item list-group-item-action border-0 rounded-0 py-3">
                            <i class="fas fa-user-circle me-2"></i> My Profile
                        </a>
                        <a href="{{ route('notifications.index') }}" class="list-group-item list-group-item-action border-0 rounded-0 py-3">
                            <i class="fas fa-bell me-2"></i> Notifications
                            @if(isset($unreadNotifications) && $unreadNotifications > 0)
                                <span class="badge bg-danger rounded-pill ms-2">{{ $unreadNotifications }}</span>
                            @endif
                        </a>
                    </div>
                </div>

                <div class="card-footer bg-white border-0 pb-4">
                    <hr>
                    <div class="small text-muted">
                        <div class="d-flex justify-content-between mb-2">
                            <span><i class="fas fa-book me-1"></i> Enrolled:</span>
                            <span class="fw-bold">{{ $enrollments->count() }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span><i class="fas fa-chalkboard-user me-1"></i> Active Courses:</span>
                            <span class="fw-bold">{{ $enrollments->where('course.status', 'active')->count() }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="col-md-9 col-lg-10">
            <!-- Header Section -->
            <div class="d-flex align-items-center gap-3 mb-4">
                <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0" style="width: 45px; height: 45px; background: #0D6EFD;">
                    <i class="fas fa-book fa-lg text-white"></i>
                </div>
                <div>
                    <h1 class="fw-bold mb-0" style="color: #1a1a2e; font-size: 1.75rem;">My Enrolled Courses</h1>
                    <p class="text-muted mt-1 mb-0" style="font-size: 0.875rem;">Track and manage your enrolled courses</p>
                </div>
            </div>

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show rounded-3 mb-4">
                    <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show rounded-3 mb-4">
                    <i class="fas fa-exclamation-circle me-2"></i> {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if($enrollments->count() > 0)
                <div class="row g-4">
                    @foreach($enrollments as $enrollment)
                        @php
                            $course = $enrollment->course;
                            $totalAssignments = $course->assignments->count();
                            $submittedAssignments = $course->assignments->filter(function($assignment) {
                                return $assignment->submissions->where('student_id', Auth::user()->user_id)->count() > 0;
                            })->count();
                            $progress = $totalAssignments > 0 ? round(($submittedAssignments / $totalAssignments) * 100) : 0;
                        @endphp
                        <div class="col-md-6 col-lg-4">
                            <div class="card h-100 border-0 rounded-4 shadow-sm hover-card">
                                <div class="card-header bg-white border-0 pt-3 pb-0">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div class="rounded-circle bg-primary bg-opacity-10 p-3">
                                            <i class="fas fa-graduation-cap fa-lg text-primary"></i>
                                        </div>
                                        <span class="badge bg-success px-3 py-2 rounded-pill" style="font-size: 0.65rem;">
                                            <i class="fas fa-check-circle me-1"></i> Enrolled
                                        </span>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <h5 class="fw-bold mb-2" style="font-size: 1rem;">{{ Str::limit($course->course_name ?? 'N/A', 35) }}</h5>
                                    <p class="text-muted small mb-2" style="font-size: 0.7rem;">
                                        <i class="fas fa-code me-1"></i> Code: {{ $course->course_code ?? 'N/A' }}
                                    </p>
                                    <p class="card-text text-muted small mb-3" style="font-size: 0.75rem;">
                                        {{ Str::limit($course->description ?? 'No description available', 80) }}
                                    </p>

                                    <!-- Course Info -->
                                    <div class="course-info mb-3">
                                        <div class="d-flex align-items-center mb-2">
                                            <i class="fas fa-chalkboard-user text-primary me-2" style="width: 18px; font-size: 0.7rem;"></i>
                                            <span class="small text-muted" style="font-size: 0.7rem;">
                                                Teacher: {{ $course->creator->full_name ?? 'N/A' }}
                                            </span>
                                        </div>
                                        <div class="d-flex align-items-center mb-2">
                                            <i class="fas fa-tasks text-primary me-2" style="width: 18px; font-size: 0.7rem;"></i>
                                            <span class="small text-muted" style="font-size: 0.7rem;">
                                                Assignments: {{ $totalAssignments }}
                                            </span>
                                        </div>
                                        <div class="d-flex align-items-center mb-2">
                                            <i class="fas fa-paper-plane text-primary me-2" style="width: 18px; font-size: 0.7rem;"></i>
                                            <span class="small text-muted" style="font-size: 0.7rem;">
                                                Submitted: {{ $submittedAssignments }}
                                            </span>
                                        </div>
                                    </div>

                                    <!-- Progress Bar -->
                                    <div class="mt-3">
                                        <div class="d-flex justify-content-between mb-1">
                                            <small class="text-muted" style="font-size: 0.65rem;">Course Progress</small>
                                            <small class="text-muted fw-bold" style="font-size: 0.65rem;">{{ $progress }}%</small>
                                        </div>
                                        <div class="progress bg-light rounded-pill" style="height: 6px;">
                                            <div class="progress-bar bg-primary rounded-pill" style="width: {{ $progress }}%;"></div>
                                        </div>
                                    </div>
                                </div>

                                <div class="card-footer bg-transparent border-0 pb-3">
                                    <div class="d-flex gap-2">
                                        <a href="{{ route('courses.show', $course->course_id) }}" class="btn btn-primary btn-sm flex-grow-1" style="font-size: 0.75rem;">
                                            <i class="fas fa-eye me-1"></i> View Course
                                        </a>
                                        <a href="{{ route('assignments.index') }}?course_id={{ $course->course_id }}" class="btn btn-outline-primary btn-sm" title="View Assignments" style="font-size: 0.75rem;">
                                            <i class="fas fa-tasks"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                @if(method_exists($enrollments, 'links') && $enrollments->hasPages())
                    <div class="mt-4 d-flex justify-content-center">
                        {{ $enrollments->links() }}
                    </div>
                @endif

            @else
                <div class="card border-0 rounded-4 shadow-sm">
                    <div class="card-body text-center py-5">
                        <div class="mb-3">
                            <i class="fas fa-book fa-4x text-muted mb-3 opacity-25"></i>
                        </div>
                        <h5 class="fw-bold text-muted" style="font-size: 1rem;">No Courses Enrolled</h5>
                        <p class="text-muted mb-3" style="font-size: 0.875rem;">You haven't enrolled in any courses yet.</p>
                        <a href="{{ route('courses.index') }}" class="btn btn-primary" style="font-size: 0.875rem;">
                            <i class="fas fa-search me-2"></i> Browse Available Courses
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

<style>
    .hover-card {
        transition: all 0.3s ease;
    }
    .hover-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(0,0,0,0.1) !important;
    }
    .progress-bar {
        transition: width 0.6s ease;
    }
    .course-info .small {
        line-height: 1.6;
    }

    /* Sidebar Styling */
    .list-group-item {
        transition: all 0.2s ease;
        font-size: 0.8rem;
    }
    .list-group-item:not(.active):hover {
        background-color: #f8fafc;
        color: #0D6EFD;
        padding-left: 24px;
    }
    .list-group-item.active {
        background: #0D6EFD;
        color: white;
        box-shadow: 0 2px 8px rgba(13, 110, 253, 0.3);
    }
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
        .container {
            padding-left: 16px;
            padding-right: 16px;
        }
        h1 {
            font-size: 1.5rem !important;
        }
    }
</style>
@endsection
