@extends('layouts.app')

@section('title', $course->course_name)

@section('content')
<div class="container py-4">
    <!-- Header Section -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="display-6 fw-bold text-gradient mb-0">
                <i class="fas fa-book me-2 text-primary"></i> {{ $course->course_name }}
            </h1>
            <p class="text-muted mt-2">Course details and information</p>
        </div>
        <div>
            @if(Auth::user()->role_id == 1 || Auth::user()->role_id == 2)
            <a href="{{ route('courses.edit', $course->course_id) }}" class="btn btn-warning btn-lg shadow-sm me-2">
                <i class="fas fa-edit me-2"></i> Edit Course
            </a>
            @endif
            <a href="{{ route('courses.index') }}" class="btn btn-secondary btn-lg shadow-sm">
                <i class="fas fa-arrow-left me-2"></i> Back
            </a>
        </div>
    </div>

    <!-- Course Info Card -->
    <div class="row g-4 mb-4">
        <div class="col-md-8">
            <div class="card shadow-sm border-0 rounded-4 h-100">
                <div class="card-header bg-gradient-primary text-white rounded-top-4" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                    <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i> Course Information</h5>
                </div>
                <div class="card-body p-4">
                    <div class="row mb-3">
                        <div class="col-md-3 fw-bold text-primary">Course Code:</div>
                        <div class="col-md-9">
                            <span class="badge bg-light text-dark px-3 py-2 rounded-pill">
                                <i class="fas fa-code me-1"></i> {{ $course->course_code }}
                            </span>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-3 fw-bold text-primary">Teacher:</div>
                        <div class="col-md-9">
                            <div class="d-flex align-items-center">
                                <div class="avatar-sm me-2">
                                    <div class="rounded-circle bg-primary bg-opacity-10 p-2 text-center" style="width: 32px; height: 32px;">
                                        <i class="fas fa-chalkboard-user text-primary fa-xs"></i>
                                    </div>
                                </div>
                                <span class="fw-semibold">{{ $course->creator->full_name ?? 'N/A' }}</span>
                                <small class="text-muted ms-2">({{ $course->creator->email ?? '' }})</small>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-3 fw-bold text-primary">Status:</div>
                        <div class="col-md-9">
                            @php
                                $statusClass = $course->status == 'active' ? 'success' : 'secondary';
                                $statusIcon = $course->status == 'active' ? 'fa-check-circle' : 'fa-ban';
                            @endphp
                            <span class="badge bg-{{ $statusClass }} bg-opacity-10 text-{{ $statusClass }} px-3 py-2 rounded-pill">
                                <i class="fas {{ $statusIcon }} me-1"></i> {{ ucfirst($course->status ?? 'Active') }}
                            </span>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-3 fw-bold text-primary">Description:</div>
                        <div class="col-md-9">
                            <p class="mb-0 text-muted">{{ $course->description ?? 'No description available.' }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistics Card -->
        <div class="col-md-4">
            <div class="card shadow-sm border-0 rounded-4 h-100">
                <div class="card-header bg-gradient-info text-white rounded-top-4" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);">
                    <h5 class="mb-0"><i class="fas fa-chart-line me-2"></i> Statistics</h5>
                </div>
                <div class="card-body p-4">
                    <div class="text-center mb-4">
                        <div class="display-4 fw-bold text-primary">{{ $course->enrollments->count() }}</div>
                        <p class="text-muted mb-0">Enrolled Students</p>
                    </div>
                    <div class="text-center">
                        <div class="display-4 fw-bold text-success">{{ $course->assignments->count() }}</div>
                        <p class="text-muted mb-0">Total Assignments</p>
                    </div>
                    <hr>
                    <div class="d-grid gap-2">
                        <a href="{{ route('enrollments.create') }}?course_id={{ $course->course_id }}" class="btn btn-outline-primary">
                            <i class="fas fa-user-plus me-2"></i> Enroll Student
                        </a>
                        <a href="{{ route('assignments.create') }}?course_id={{ $course->course_id }}" class="btn btn-outline-success">
                            <i class="fas fa-plus me-2"></i> Add Assignment
                        </a>
                        <a href="{{ route('enrollments.course-students', $course->course_id) }}" class="btn btn-outline-info">
                            <i class="fas fa-users me-2"></i> View All Students
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Assignments Section -->
    <div class="card shadow-sm border-0 rounded-4">
        <div class="card-header bg-white border-0 pt-4">
            <h5 class="fw-bold mb-0">
                <i class="fas fa-tasks text-primary me-2"></i> Course Assignments
            </h5>
        </div>
        <div class="card-body p-4">
            @if($course->assignments->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Title</th>
                                <th>Total Marks</th>
                                <th>Due Date</th>
                                <th>Status</th>
                                <th>Submissions</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($course->assignments as $assignment)
                                @php
                                    $dueDate = $assignment->due_date;
                                    $isOverdue = $dueDate < now();
                                    $daysLeft = now()->diffInDays($dueDate, false);
                                    $roundedDays = floor(abs($daysLeft));

                                    if ($isOverdue) {
                                        $dateText = 'Overdue';
                                        $dateClass = 'text-danger';
                                    } elseif ($daysLeft == 0) {
                                        $dateText = 'Due today';
                                        $dateClass = 'text-warning';
                                    } elseif ($daysLeft == 1) {
                                        $dateText = 'Tomorrow';
                                        $dateClass = 'text-warning';
                                    } else {
                                        $dateText = $roundedDays . ' days left';
                                        $dateClass = 'text-success';
                                    }

                                    $statusClass = match($assignment->status) {
                                        'Published' => 'success',
                                        'Draft' => 'warning',
                                        'Closed' => 'secondary',
                                        default => 'info'
                                    };
                                @endphp
                                <tr>
                                    <td>
                                        <a href="{{ route('assignments.show', $assignment->assignment_id) }}" class="fw-semibold text-decoration-none">
                                            {{ $assignment->title }}
                                        </a>
                                    </td>
                                    <td class="text-center">{{ $assignment->total_marks }}</td>
                                    <td>
                                        <div class="d-flex flex-column">
                                            <span class="{{ $isOverdue ? 'text-danger' : 'text-success' }}">
                                                <i class="fas fa-calendar-alt me-1"></i>
                                                {{ $dueDate->format('M d, Y') }}
                                            </span>
                                            <small class="{{ $dateClass }}">{{ $dateText }}</small>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-{{ $statusClass }} bg-opacity-10 text-{{ $statusClass }} px-3 py-2 rounded-pill">
                                            {{ $assignment->status }}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-info bg-opacity-10 text-info rounded-pill px-3 py-2">
                                            <i class="fas fa-paper-plane me-1"></i> {{ $assignment->submissions->count() }}
                                        </span>
                                    </td>
                                    <td>
                                        <a href="{{ route('assignments.show', $assignment->assignment_id) }}" class="btn btn-sm btn-outline-primary rounded-3">
                                            <i class="fas fa-eye me-1"></i> View
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-5">
                    <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted">No assignments yet</h5>
                    <p class="text-muted">Click "Add Assignment" to create your first assignment.</p>
                    @if(Auth::user()->role_id == 1 || Auth::user()->role_id == 2)
                    <a href="{{ route('assignments.create') }}?course_id={{ $course->course_id }}" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i> Add Assignment
                    </a>
                    @endif
                </div>
            @endif
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
    .bg-gradient-primary {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }
    .bg-gradient-info {
        background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
    }
    .avatar-sm {
        width: 32px;
        height: 32px;
    }
    .table-hover tbody tr:hover {
        background-color: rgba(102, 126, 234, 0.04);
        transition: all 0.3s ease;
    }
</style>
@endsection
