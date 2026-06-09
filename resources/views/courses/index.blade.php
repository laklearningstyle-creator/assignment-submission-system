@extends('layouts.app')

@section('title', 'Courses')

@section('content')
<div class="container-fluid py-3 py-md-4">
    <div class="row">
        <!-- Sidebar -->
        <div class="col-md-3 col-lg-2 mb-4">
            <div class="card border-0 rounded-4 shadow-sm sticky-top" style="top: 80px;">
                <div class="card-header bg-white border-0 pt-4">
                    <h5 class="fw-bold mb-0">
                        <i class="fas fa-compass me-2" style="color: #0D6EFD;"></i> Navigation
                    </h5>
                </div>
                <div class="card-body p-0">
                    <div class="list-group list-group-flush">
                        <a href="{{ route('courses.index') }}" class="list-group-item list-group-item-action border-0 rounded-0 py-3 active" style="background: #0D6EFD; color: white;">
                            <i class="fas fa-book me-2"></i> All Courses
                            <span class="badge bg-white text-primary rounded-pill ms-2">{{ $courses->total() }}</span>
                        </a>
                        <a href="{{ route('assignments.index') }}" class="list-group-item list-group-item-action border-0 rounded-0 py-3">
                            <i class="fas fa-tasks me-2"></i> Assignments
                        </a>
                        <a href="{{ route('student.my-courses') }}" class="list-group-item list-group-item-action border-0 rounded-0 py-3">
                            <i class="fas fa-user-graduate me-2"></i> My Courses
                        </a>
                        @if(Auth::user()->role_id == 1 || Auth::user()->role_id == 2)
                        <a href="{{ route('courses.create') }}" class="list-group-item list-group-item-action border-0 rounded-0 py-3" style="background: #eef2ff; color: #0D6EFD;">
                            <i class="fas fa-plus-circle me-2"></i> Create New Course
                        </a>
                        @endif
                    </div>
                </div>

                <!-- Quick Stats in Sidebar -->
                <div class="card-footer bg-white border-0 pb-4">
                    <hr>
                    <div class="small text-muted">
                        <div class="d-flex justify-content-between mb-2">
                            <span><i class="fas fa-chalkboard-user me-1"></i> Total Teachers:</span>
                            <span class="fw-bold">{{ \App\Models\User::where('role_id', 2)->count() }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span><i class="fas fa-users me-1"></i> Total Students:</span>
                            <span class="fw-bold">{{ \App\Models\User::where('role_id', 3)->count() }}</span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span><i class="fas fa-check-circle me-1"></i> Completion Rate:</span>
                            <span class="fw-bold">{{ number_format($courses->avg(function($c) { return $c->enrollments->count() > 0 ? 65 : 0; }) ?? 0, 1) }}%</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="col-md-9 col-lg-10">
            <!-- Header Section -->
            <div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-3 mb-4">
                <div class="d-flex align-items-center gap-3">
                    <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0" style="width: 50px; height: 50px; background: #0D6EFD;">
                        <i class="fas fa-book fa-xl text-white"></i>
                    </div>
                    <div>
                        <h1 class="fw-bold mb-0" style="color: #1a1a2e; font-size: clamp(1.5rem, 5vw, 2rem);">Courses</h1>
                        <p class="text-muted mt-1 mb-0 small">Manage and track all courses</p>
                    </div>
                </div>
            </div>

            <!-- Statistics Cards -->
            <div class="row g-3 g-md-4 mb-4">
                <div class="col-6 col-md-3">
                    <div class="card border-0 rounded-4 shadow-sm stat-card h-100">
                        <div class="card-body p-3 p-md-4">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="text-muted mb-1 small">Total Courses</h6>
                                    <h2 class="fw-bold mb-0" style="color: #0D6EFD; font-size: clamp(1.5rem, 4vw, 2rem);">{{ $courses->total() }}</h2>
                                    <small class="text-muted d-none d-md-block">All courses</small>
                                </div>
                                <div class="stat-icon-circle" style="background: #0D6EFD;">
                                    <i class="fas fa-book"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="card border-0 rounded-4 shadow-sm stat-card h-100">
                        <div class="card-body p-3 p-md-4">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="text-muted mb-1 small">Assignments</h6>
                                    <h2 class="fw-bold mb-0" style="color: #10b981; font-size: clamp(1.5rem, 4vw, 2rem);">{{ $courses->sum(function($c) { return $c->assignments->count(); }) }}</h2>
                                    <small class="text-muted d-none d-md-block">Across courses</small>
                                </div>
                                <div class="stat-icon-circle" style="background: #10b981;">
                                    <i class="fas fa-tasks"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="card border-0 rounded-4 shadow-sm stat-card h-100">
                        <div class="card-body p-3 p-md-4">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="text-muted mb-1 small">Students</h6>
                                    <h2 class="fw-bold mb-0" style="color: #f59e0b; font-size: clamp(1.5rem, 4vw, 2rem);">{{ $courses->sum(function($c) { return $c->enrollments->count(); }) }}</h2>
                                    <small class="text-muted d-none d-md-block">Enrolled</small>
                                </div>
                                <div class="stat-icon-circle" style="background: #f59e0b;">
                                    <i class="fas fa-users"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="card border-0 rounded-4 shadow-sm stat-card h-100">
                        <div class="card-body p-3 p-md-4">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="text-muted mb-1 small">Active</h6>
                                    <h2 class="fw-bold mb-0" style="color: #22c55e; font-size: clamp(1.5rem, 4vw, 2rem);">{{ $courses->where('status', 'active')->count() }}</h2>
                                    <small class="text-muted d-none d-md-block">Courses</small>
                                </div>
                                <div class="stat-icon-circle" style="background: #22c55e;">
                                    <i class="fas fa-check-circle"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Desktop Table View -->
            <div class="card border-0 rounded-4 shadow-sm d-none d-md-block">
                <div class="card-header bg-white border-0 pt-4 pb-0 px-4">
                    <div class="d-flex justify-content-between align-items-center flex-wrap">
                        <h5 class="fw-bold mb-3">
                            <i class="fas fa-list me-2" style="color: #0D6EFD;"></i> Course List
                            <span class="badge ms-2 px-3 py-2 rounded-pill" style="background: #0D6EFD; color: white; font-size: 0.75rem;">
                                <i class="fas fa-book me-1"></i> {{ $courses->total() }} total
                            </span>
                        </h5>
                    </div>
                </div>

                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th style="width: 5%">ID</th>
                                    <th style="width: 25%">Course Name</th>
                                    <th style="width: 15%">Code</th>
                                    <th style="width: 20%">Teacher</th>
                                    <th style="width: 10%">Assignments</th>
                                    <th style="width: 10%">Students</th>
                                    <th style="width: 8%">Status</th>
                                    <th style="width: 7%">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($courses as $course)
                                <tr>
                                    <td class="fw-bold" style="color: #0D6EFD;">{{ $course->course_id }}</td>
                                    <td>
                                        <a href="{{ route('courses.show', $course->course_id) }}" class="fw-semibold text-decoration-none" style="color: #1a1a2e;">
                                            {{ $course->course_name }}
                                        </a>
                                    </td>
                                    <td>
                                        <span class="badge px-3 py-2 rounded-pill" style="background: #eef2ff; color: #0D6EFD;">
                                            <i class="fas fa-code me-1" style="color: #0D6EFD;"></i> {{ $course->course_code }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="teacher-avatar me-2">
                                                {{ strtoupper(substr($course->creator->full_name ?? 'U', 0, 1)) }}
                                            </div>
                                            <div>
                                                <div class="fw-semibold" style="color: #1a1a2e;">{{ $course->creator->full_name ?? 'N/A' }}</div>
                                                <small class="text-muted">{{ $course->creator->email ?? '' }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <div class="count-badge assignments">
                                            <span class="count-number">{{ $course->assignments->count() }}</span>
                                            <span class="count-label">assignments</span>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <div class="count-badge students">
                                            <span class="count-number">{{ $course->enrollments->count() }}</span>
                                            <span class="count-label">students</span>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        @if($course->status == 'active')
                                            <span class="status-badge status-active">
                                                <i class="fas fa-circle me-1" style="font-size: 8px;"></i> Active
                                            </span>
                                        @else
                                            <span class="status-badge status-inactive">
                                                <i class="fas fa-circle me-1" style="font-size: 8px;"></i> Inactive
                                            </span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <div class="action-buttons">
                                            <a href="{{ route('courses.show', $course->course_id) }}" class="btn-action view" title="View">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            @if(Auth::user()->role_id == 1 || Auth::user()->role_id == 2)
                                            <a href="{{ route('courses.edit', $course->course_id) }}" class="btn-action edit" title="Edit">
                                                <i class="fas fa-pencil-alt"></i>
                                            </a>
                                            @endif
                                            @if(Auth::user()->role_id == 1)
                                            <button type="button" class="btn-action delete" title="Delete"
                                                    onclick="confirmDelete({{ $course->course_id }}, '{{ $course->course_name }}')">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                            <form id="delete-form-{{ $course->course_id }}"
                                                  action="{{ route('courses.destroy', $course->course_id) }}"
                                                  method="POST" style="display: none;">
                                                @csrf @method('DELETE')
                                            </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="8" class="text-center py-5">
                                        <i class="fas fa-book fa-3x text-muted mb-3 d-block"></i>
                                        <h5 class="text-muted">No courses found</h5>
                                        <p class="text-muted small">Get started by creating your first course</p>
                                        @if(Auth::user()->role_id == 1 || Auth::user()->role_id == 2)
                                        <a href="{{ route('courses.create') }}" class="btn btn-primary mt-2">Create Course</a>
                                        @endif
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                @if($courses->hasPages())
                <div class="card-footer bg-white border-0 py-3 px-4">
                    <div class="d-flex justify-content-center">
                        {{ $courses->links() }}
                    </div>
                </div>
                @endif
            </div>

            <!-- Mobile Card View -->
            <div class="d-md-none">
                @forelse($courses as $course)
                <div class="card border-0 rounded-4 shadow-sm mb-3">
                    <div class="card-body p-3">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <div>
                                <span class="badge me-2" style="background: #eef2ff; color: #0D6EFD;">#{{ $course->course_id }}</span>
                                <span class="fw-bold" style="color: #1a1a2e;">{{ $course->course_name }}</span>
                            </div>
                            @if($course->status == 'active')
                                <span class="status-badge status-active">Active</span>
                            @else
                                <span class="status-badge status-inactive">Inactive</span>
                            @endif
                        </div>

                        <div class="mb-2">
                            <span class="badge px-3 py-2 rounded-pill" style="background: #eef2ff; color: #0D6EFD;">
                                <i class="fas fa-code me-1"></i> {{ $course->course_code }}
                            </span>
                        </div>

                        <div class="d-flex align-items-center mb-3">
                            <div class="teacher-avatar-sm me-2">
                                {{ strtoupper(substr($course->creator->full_name ?? 'U', 0, 1)) }}
                            </div>
                            <div>
                                <div class="fw-semibold small">{{ $course->creator->full_name ?? 'N/A' }}</div>
                                <small class="text-muted">{{ $course->creator->email ?? '' }}</small>
                            </div>
                        </div>

                        <div class="row g-2 mb-3">
                            <div class="col-6">
                                <div class="count-badge-mobile assignments">
                                    <i class="fas fa-tasks me-1"></i>
                                    <span class="fw-bold">{{ $course->assignments->count() }}</span>
                                    <span class="text-muted ms-1">assignments</span>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="count-badge-mobile students">
                                    <i class="fas fa-users me-1"></i>
                                    <span class="fw-bold">{{ $course->enrollments->count() }}</span>
                                    <span class="text-muted ms-1">students</span>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex gap-2">
                            <a href="{{ route('courses.show', $course->course_id) }}" class="btn btn-sm flex-grow-1" style="background: #eef2ff; color: #0D6EFD;">
                                <i class="fas fa-eye me-1"></i> View
                            </a>
                            @if(Auth::user()->role_id == 1 || Auth::user()->role_id == 2)
                            <a href="{{ route('courses.edit', $course->course_id) }}" class="btn btn-sm flex-grow-1" style="background: #fef3c7; color: #d97706;">
                                <i class="fas fa-edit me-1"></i> Edit
                            </a>
                            @endif
                            @if(Auth::user()->role_id == 1)
                            <button type="button" class="btn btn-sm flex-grow-1" style="background: #fee2e2; color: #dc2626;"
                                    onclick="confirmDelete({{ $course->course_id }}, '{{ $course->course_name }}')">
                                <i class="fas fa-trash-alt me-1"></i> Delete
                            </button>
                            @endif
                        </div>
                    </div>
                </div>
                @empty
                <div class="text-center py-5">
                    <i class="fas fa-book fa-3x text-muted mb-3 d-block"></i>
                    <h5 class="text-muted">No courses found</h5>
                    @if(Auth::user()->role_id == 1 || Auth::user()->role_id == 2)
                    <a href="{{ route('courses.create') }}" class="btn btn-primary mt-2">Create Course</a>
                    @endif
                </div>
                @endforelse

                @if($courses->hasPages())
                <div class="mt-4">
                    {{ $courses->links() }}
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<script>
    function confirmDelete(id, name) {
        if (confirm('⚠️ Are you sure you want to delete course "' + name + '"?\n\nThis action cannot be undone.')) {
            document.getElementById('delete-form-' + id).submit();
        }
    }
</script>

<style>
    /* Sidebar Styling */
    .list-group-item {
        transition: all 0.2s ease;
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

    /* Stat Cards */
    .stat-card {
        transition: all 0.3s ease;
        border: 1px solid rgba(0,0,0,0.04);
    }

    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 35px rgba(0,0,0,0.1) !important;
    }

    .stat-icon-circle {
        width: 45px;
        height: 45px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .stat-icon-circle i {
        font-size: 20px;
        color: white;
    }

    /* Teacher Avatar */
    .teacher-avatar {
        width: 40px;
        height: 40px;
        background: #0D6EFD;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: bold;
        font-size: 16px;
    }

    .teacher-avatar-sm {
        width: 36px;
        height: 36px;
        background: #0D6EFD;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: bold;
        font-size: 14px;
    }

    /* Count Badges */
    .count-badge {
        display: inline-flex;
        flex-direction: column;
        align-items: center;
        padding: 8px 12px;
        border-radius: 12px;
        min-width: 80px;
    }

    .count-badge.assignments {
        background: #eef2ff;
    }

    .count-badge .count-number {
        font-size: 1.3rem;
        font-weight: 700;
        color: #0D6EFD;
    }

    .count-badge.students {
        background: #ecfdf5;
    }

    .count-badge.students .count-number {
        color: #10b981;
    }

    .count-badge .count-label {
        font-size: 0.7rem;
        color: #6c757d;
    }

    /* Mobile Count Badges */
    .count-badge-mobile {
        padding: 8px 12px;
        border-radius: 10px;
        text-align: center;
    }

    .count-badge-mobile.assignments {
        background: #eef2ff;
        color: #0D6EFD;
    }

    .count-badge-mobile.students {
        background: #ecfdf5;
        color: #10b981;
    }

    /* Status Badges */
    .status-badge {
        display: inline-flex;
        align-items: center;
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 0.7rem;
        font-weight: 500;
        gap: 6px;
    }

    .status-active {
        background: #ecfdf5;
        color: #10b981;
    }

    .status-inactive {
        background: #fef2f2;
        color: #ef4444;
    }

    /* Action Buttons */
    .action-buttons {
        display: flex;
        gap: 5px;
        justify-content: center;
    }

    .btn-action {
        width: 32px;
        height: 32px;
        border-radius: 8px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s ease;
        border: none;
    }

    .btn-action.view {
        background: #eef2ff;
        color: #0D6EFD;
    }

    .btn-action.view:hover {
        background: #0D6EFD;
        color: white;
        transform: translateY(-2px);
    }

    .btn-action.edit {
        background: #fef3c7;
        color: #d97706;
    }

    .btn-action.edit:hover {
        background: #d97706;
        color: white;
        transform: translateY(-2px);
    }

    .btn-action.delete {
        background: #fee2e2;
        color: #dc2626;
    }

    .btn-action.delete:hover {
        background: #dc2626;
        color: white;
        transform: translateY(-2px);
    }

    /* Table Hover */
    .table-hover tbody tr:hover {
        background-color: rgba(13, 110, 253, 0.04);
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
    }
</style>
@endsection
