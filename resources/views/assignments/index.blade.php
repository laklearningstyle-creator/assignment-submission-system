@extends('layouts.app')

@section('title', 'Assignments')

@section('content')
<div class="container-fluid py-3 py-md-4">
    <div class="row">
        <!-- Sidebar -->
        <div class="col-md-3 col-lg-2 mb-4">
            <div class="card border-0 rounded-4 shadow-sm sticky-top" style="top: 80px;">
                <div class="card-header bg-white border-0 pt-4">
                    <h5 class="fw-bold mb-0" style="font-size: 1rem;">
                        <i class="fas fa-tasks me-2" style="color: #0D6EFD;"></i> Assignment Menu
                    </h5>
                </div>
                <div class="card-body p-0">
                    <div class="list-group list-group-flush">
                        <a href="{{ route('assignments.index') }}" class="list-group-item list-group-item-action border-0 rounded-0 py-3 active" style="background: #0D6EFD; color: white;">
                            <i class="fas fa-list me-2"></i> All Assignments
                        </a>
                        @if(Auth::user()->role_id == 1 || Auth::user()->role_id == 2)
                        <a href="{{ route('assignments.create') }}" class="list-group-item list-group-item-action border-0 rounded-0 py-3">
                            <i class="fas fa-plus-circle me-2"></i> Create Assignment
                        </a>
                        @endif
                        @if(Auth::user()->role_id == 3)
                        <a href="{{ route('student.submissions.index') }}" class="list-group-item list-group-item-action border-0 rounded-0 py-3">
                            <i class="fas fa-paper-plane me-2"></i> My Submissions
                        </a>
                        <a href="{{ route('student.my-grades') }}" class="list-group-item list-group-item-action border-0 rounded-0 py-3">
                            <i class="fas fa-star me-2"></i> My Grades
                        </a>
                        @endif
                        <hr class="my-2">
                        <a href="{{ route('courses.index') }}" class="list-group-item list-group-item-action border-0 rounded-0 py-3">
                            <i class="fas fa-book me-2"></i> Courses
                        </a>
                        <a href="{{ route('dashboard') }}" class="list-group-item list-group-item-action border-0 rounded-0 py-3">
                            <i class="fas fa-tachometer-alt me-2"></i> Dashboard
                        </a>
                    </div>
                </div>

                <!-- Quick Stats in Sidebar -->
                <div class="card-footer bg-white border-0 pb-4">
                    <hr>
                    <div class="small text-muted">
                        <div class="d-flex justify-content-between mb-2">
                            <span><i class="fas fa-tasks me-1"></i> Total:</span>
                            <span class="fw-bold">{{ $assignments->total() }}</span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span><i class="fas fa-check-circle me-1"></i> Published:</span>
                            <span class="fw-bold">{{ $assignments->where('status', 'Published')->count() }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="col-md-9 col-lg-10">
            <!-- Header Section -->
            <div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-3 mb-4">
                <div>
                    <h1 class="display-6 fw-bold text-gradient mb-0">
                        <i class="fas fa-tasks me-2 text-primary"></i> Assignments
                    </h1>
                    <p class="text-muted mt-2">Manage and track all course assignments</p>
                </div>
                @if(Auth::user()->role_id == 1 || Auth::user()->role_id == 2)
                <a href="{{ route('assignments.create') }}" class="btn btn-primary btn-lg shadow-sm align-self-start align-self-sm-auto">
                    <i class="fas fa-plus me-2"></i> Create Assignment
                </a>
                @endif
            </div>

            <!-- Filter Bar -->
            <div class="card shadow-sm border-0 rounded-4 mb-4">
                <div class="card-body p-3">
                    <form method="GET" action="{{ route('assignments.index') }}" class="row g-2 g-md-3 align-items-end">
                        <div class="col-12 col-md-5">
                            <label class="form-label fw-semibold small text-muted">
                                <i class="fas fa-filter me-1"></i> Filter by Course
                            </label>
                            <select name="course_id" class="form-select bg-light border-0 rounded-3">
                                <option value="">All Courses</option>
                                @foreach($courses as $course)
                                    <option value="{{ $course->course_id }}" {{ request('course_id') == $course->course_id ? 'selected' : '' }}>
                                        {{ $course->course_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-12 col-md-5">
                            <label class="form-label fw-semibold small text-muted">
                                <i class="fas fa-flag-checkered me-1"></i> Filter by Status
                            </label>
                            <select name="status" class="form-select bg-light border-0 rounded-3">
                                <option value="">All Status</option>
                                <option value="Draft" {{ request('status') == 'Draft' ? 'selected' : '' }}>📄 Draft</option>
                                <option value="Published" {{ request('status') == 'Published' ? 'selected' : '' }}>🚀 Published</option>
                                <option value="Closed" {{ request('status') == 'Closed' ? 'selected' : '' }}>🔒 Closed</option>
                            </select>
                        </div>
                        <div class="col-12 col-md-2">
                            <button type="submit" class="btn btn-primary w-100 rounded-3">
                                <i class="fas fa-search me-2"></i> Apply
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Mobile Card View -->
            <div class="d-md-none">
                @forelse($assignments as $assignment)
                @php
                    $dueDate = $assignment->due_date;
                    $isOverdue = $dueDate < now();
                    $daysLeft = now()->diffInDays($dueDate, false);
                    $roundedDays = floor(abs($daysLeft));

                    if ($isOverdue) {
                        $dateText = 'Overdue';
                        $dateClass = 'bg-danger';
                    } elseif ($daysLeft == 0) {
                        $dateText = 'Due today';
                        $dateClass = 'bg-warning';
                    } elseif ($daysLeft == 1) {
                        $dateText = 'Tomorrow';
                        $dateClass = 'bg-info';
                    } else {
                        $dateText = $roundedDays . ' days left';
                        $dateClass = 'bg-success';
                    }

                    $statusConfig = [
                        'Published' => ['bg' => '#0d6efd', 'icon' => 'fa-check-circle'],
                        'Draft' => ['bg' => '#6c757d', 'icon' => 'fa-edit'],
                        'Closed' => ['bg' => '#212529', 'icon' => 'fa-lock'],
                    ];
                    $config = $statusConfig[$assignment->status] ?? ['bg' => '#0dcaf0', 'icon' => 'fa-info-circle'];
                @endphp
                <div class="card shadow-sm border-0 rounded-4 mb-3">
                    <div class="card-body p-3">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <div>
                                <a href="{{ route('assignments.show', $assignment->assignment_id) }}" class="fw-bold text-decoration-none text-dark fs-5">
                                    {{ $assignment->title }}
                                </a>
                                <div class="mt-1">
                                    <span class="badge px-2 py-1 rounded-pill text-white" style="background-color: {{ $config['bg'] }};">
                                        <i class="fas {{ $config['icon'] }} me-1"></i> {{ $assignment->status }}
                                    </span>
                                </div>
                            </div>
                            <span class="badge bg-light text-dark px-3 py-2 rounded-pill">
                                <i class="fas fa-book text-primary me-1"></i> {{ $assignment->course->course_name ?? 'N/A' }}
                            </span>
                        </div>

                        <div class="d-flex align-items-center mb-2">
                            <div class="rounded-circle d-flex align-items-center justify-content-center me-2" style="width: 32px; height: 32px; background: #0d6efd;">
                                <i class="fas fa-chalkboard-user text-white fa-xs"></i>
                            </div>
                            <div>
                                <span class="fw-semibold text-dark">{{ $assignment->creator->full_name ?? 'N/A' }}</span>
                                <br>
                                <small class="text-muted">Teacher</small>
                            </div>
                        </div>

                        <div class="row g-2 mb-2">
                            <div class="col-6">
                                <div class="bg-light rounded-3 p-2 text-center">
                                    <small class="text-muted">Total Marks</small>
                                    <div class="fw-bold text-primary fs-5">{{ $assignment->total_marks }}</div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="bg-light rounded-3 p-2 text-center">
                                    <small class="text-muted">Submissions</small>
                                    <div class="fw-bold text-primary fs-5">{{ $assignment->submissions_count ?? $assignment->submissions->count() }}</div>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between align-items-center mb-2 p-2 bg-light rounded-3">
                            <div>
                                <i class="fas fa-calendar-alt me-2 {{ $isOverdue ? 'text-danger' : 'text-success' }}"></i>
                                <span class="fw-semibold {{ $isOverdue ? 'text-danger' : 'text-success' }}">
                                    {{ $dueDate->format('M d, Y') }}
                                </span>
                            </div>
                            <span class="badge {{ $dateClass }} text-white px-2 py-1 rounded-pill">
                                @if($isOverdue)
                                    <i class="fas fa-exclamation-triangle me-1"></i> Overdue
                                @elseif($daysLeft == 0)
                                    <i class="fas fa-clock me-1"></i> Due today
                                @elseif($daysLeft == 1)
                                    <i class="fas fa-hourglass-half me-1"></i> Tomorrow
                                @else
                                    <i class="fas fa-hourglass-half me-1"></i> {{ $roundedDays }} days
                                @endif
                            </span>
                        </div>

                        <div class="d-flex gap-2 mt-3">
                            <a href="{{ route('assignments.show', $assignment->assignment_id) }}" class="btn btn-sm btn-outline-primary flex-grow-1">
                                <i class="fas fa-eye me-1"></i> View
                            </a>
                            @if(Auth::user()->role_id == 1 || Auth::user()->role_id == 2)
                            <a href="{{ route('assignments.edit', $assignment->assignment_id) }}" class="btn btn-sm btn-outline-warning flex-grow-1">
                                <i class="fas fa-edit me-1"></i> Edit
                            </a>
                            @endif
                            @if(Auth::user()->role_id == 3 && $assignment->status == 'Published')
                            <a href="{{ route('submissions.create', ['assignment_id' => $assignment->assignment_id]) }}" class="btn btn-sm btn-outline-success flex-grow-1">
                                <i class="fas fa-paper-plane me-1"></i> Submit
                            </a>
                            @endif
                        </div>
                    </div>
                </div>
                @empty
                <div class="text-center py-5">
                    <i class="fas fa-inbox fa-3x text-muted mb-3 d-block"></i>
                    <h5 class="text-muted">No assignments found</h5>
                    @if(Auth::user()->role_id == 1 || Auth::user()->role_id == 2)
                    <a href="{{ route('assignments.create') }}" class="btn btn-primary mt-2">
                        <i class="fas fa-plus me-1"></i> Create Assignment
                    </a>
                    @endif
                </div>
                @endforelse

                @if($assignments->hasPages())
                <div class="mt-4">
                    {{ $assignments->withQueryString()->links() }}
                </div>
                @endif
            </div>

            <!-- Desktop Table View -->
            <div class="card shadow-sm border-0 rounded-4 overflow-hidden d-none d-md-block">
                <div class="card-header bg-white border-0 py-3 px-4">
                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                        <span class="text-muted">
                            <i class="fas fa-list me-1"></i> Showing {{ $assignments->firstItem() ?? 0 }} - {{ $assignments->lastItem() ?? 0 }} of {{ $assignments->total() }} assignments
                        </span>
                    </div>
                </div>

                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="px-4 py-3">Title</th>
                                    <th class="py-3">Course</th>
                                    <th class="py-3">Teacher</th>
                                    <th class="text-center py-3">Marks</th>
                                    <th class="py-3">Due Date</th>
                                    <th class="text-center py-3">Submissions</th>
                                    <th class="text-center py-3">Status</th>
                                    <th class="text-center py-3">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($assignments as $assignment)
                                @php
                                    $dueDate = $assignment->due_date;
                                    $isOverdue = $dueDate < now();
                                    $daysLeft = now()->diffInDays($dueDate, false);
                                    $roundedDays = floor(abs($daysLeft));

                                    if ($isOverdue) {
                                        $dateText = 'Overdue';
                                        $dateClass = 'bg-danger';
                                    } elseif ($daysLeft == 0) {
                                        $dateText = 'Due today';
                                        $dateClass = 'bg-warning';
                                    } elseif ($daysLeft == 1) {
                                        $dateText = 'Tomorrow';
                                        $dateClass = 'bg-info';
                                    } elseif ($daysLeft <= 3) {
                                        $dateText = $roundedDays . ' days left';
                                        $dateClass = 'bg-warning';
                                    } else {
                                        $dateText = $roundedDays . ' days left';
                                        $dateClass = 'bg-success';
                                    }
                                @endphp
                                <tr>
                                    <td class="px-4">
                                        <a href="{{ route('assignments.show', $assignment->assignment_id) }}" class="fw-semibold text-decoration-none text-dark">
                                            {{ $assignment->title }}
                                        </a>
                                        <div class="small text-muted mt-1">
                                            <i class="far fa-calendar-alt me-1"></i> Created: {{ $assignment->created_at->format('M d, Y') }}
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-light text-dark px-3 py-2 rounded-pill">
                                            <i class="fas fa-book text-primary me-1"></i> {{ $assignment->course->course_name ?? 'N/A' }}
                                        </span>
                                    </td>
                                    <td class="py-3">
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-sm me-2">
                                                <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 32px; height: 32px; background: #0d6efd;">
                                                    <i class="fas fa-chalkboard-user text-white fa-xs"></i>
                                                </div>
                                            </div>
                                            <div>
                                                <span class="fw-semibold text-dark">{{ $assignment->creator->full_name ?? 'N/A' }}</span>
                                                <br>
                                                <small class="text-muted">Teacher</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <span class="fw-bold text-primary">{{ $assignment->total_marks }}</span>
                                    </td>
                                    <td>
                                        <div class="d-flex flex-column">
                                            <span class="{{ $isOverdue ? 'text-danger' : 'text-success' }} fw-semibold">
                                                <i class="fas fa-calendar-alt me-1"></i>
                                                {{ $dueDate->format('M d, Y') }}
                                            </span>
                                            <div class="mt-1">
                                                <span class="badge {{ $dateClass }} text-white px-2 py-1 rounded-pill">
                                                    @if($isOverdue)
                                                        <i class="fas fa-exclamation-triangle me-1"></i> Overdue
                                                    @elseif($daysLeft == 0)
                                                        <i class="fas fa-clock me-1"></i> Due today
                                                    @elseif($daysLeft == 1)
                                                        <i class="fas fa-hourglass-half me-1"></i> Tomorrow
                                                    @else
                                                        <i class="fas fa-hourglass-half me-1"></i> {{ $roundedDays }} days
                                                    @endif
                                                </span>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <div class="d-inline-block text-center">
                                            <div>
                                                <span class="fw-bold text-primary" style="font-size: 1.5rem;">
                                                    {{ $assignment->submissions_count ?? $assignment->submissions->count() }}
                                                </span>
                                            </div>
                                            <div>
                                                <small class="text-muted">submissions</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        @php
                                            $statusConfig = [
                                                'Published' => ['bg' => '#0d6efd', 'icon' => 'fa-check-circle'],
                                                'Draft' => ['bg' => '#6c757d', 'icon' => 'fa-edit'],
                                                'Closed' => ['bg' => '#212529', 'icon' => 'fa-lock'],
                                            ];
                                            $config = $statusConfig[$assignment->status] ?? ['bg' => '#0dcaf0', 'icon' => 'fa-info-circle'];
                                        @endphp
                                        <span class="badge px-3 py-2 rounded-pill text-white" style="background-color: {{ $config['bg'] }};">
                                            <i class="fas {{ $config['icon'] }} me-1"></i> {{ $assignment->status }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('assignments.show', $assignment->assignment_id) }}" class="btn btn-sm btn-outline-primary rounded-3 me-1" title="View Details">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            @if(Auth::user()->role_id == 1 || Auth::user()->role_id == 2)
                                            <a href="{{ route('assignments.edit', $assignment->assignment_id) }}" class="btn btn-sm btn-outline-warning rounded-3 me-1" title="Edit Assignment">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            @endif
                                            @if(Auth::user()->role_id == 3 && $assignment->status == 'Published')
                                            <a href="{{ route('submissions.create', ['assignment_id' => $assignment->assignment_id]) }}" class="btn btn-sm btn-outline-success rounded-3" title="Submit Assignment">
                                                <i class="fas fa-paper-plane"></i>
                                            </a>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center py-5">
                                            <i class="fas fa-inbox fa-3x text-muted mb-3 d-block"></i>
                                            <h5 class="text-muted">No assignments found</h5>
                                            <p class="text-muted small">Get started by creating your first assignment</p>
                                            @if(Auth::user()->role_id == 1 || Auth::user()->role_id == 2)
                                            <a href="{{ route('assignments.create') }}" class="btn btn-primary mt-2">
                                                <i class="fas fa-plus me-1"></i> Create Assignment
                                            </a>
                                            @endif
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                @if($assignments->hasPages())
                <div class="card-footer bg-white border-0 py-3 px-4">
                    <div class="d-flex justify-content-center">
                        {{ $assignments->withQueryString()->links() }}
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<style>
    .text-gradient {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        -webkit-background-clip: text;
        background-clip: text;
        color: transparent;
    }

    .table-hover tbody tr:hover {
        background-color: rgba(13, 110, 253, 0.04);
        transition: all 0.3s ease;
    }

    .btn-group .btn {
        transition: all 0.2s ease;
    }

    .btn-group .btn:hover {
        transform: translateY(-2px);
    }

    .avatar-sm {
        width: 32px;
        height: 32px;
    }

    .form-select, .btn {
        cursor: pointer;
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

    /* Mobile card styles */
    @media (max-width: 767.98px) {
        .sticky-top {
            position: relative;
            top: 0;
            margin-bottom: 1rem;
        }

        .container-fluid {
            padding-left: 12px;
            padding-right: 12px;
        }

        .card-body {
            padding: 16px;
        }

        .btn-group {
            flex-wrap: wrap;
            gap: 6px;
        }

        .btn-group .btn {
            flex: 1;
            min-width: 80px;
        }
    }

    /* Small devices */
    @media (max-width: 576px) {
        .display-6 {
            font-size: 1.5rem;
        }

        .btn-lg {
            padding: 8px 16px;
            font-size: 0.9rem;
        }
    }

    /* Desktop optimizations */
    @media (min-width: 992px) {
        .table th, .table td {
            white-space: nowrap;
        }

        .table td:first-child {
            white-space: normal;
            min-width: 200px;
        }
    }
</style>
@endsection
