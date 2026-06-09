@extends('layouts.app')

@section('title', $assignment->title)

@section('content')
<div class="container py-4">
    <!-- Header Section -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="display-6 fw-bold text-gradient mb-0">
                <i class="fas fa-tasks me-2" style="color: #0D6EFD;"></i> {{ $assignment->title }}
            </h1>
            <p class="text-muted mt-2">Assignment details and submissions</p>
        </div>
        <div>
            @if(Auth::user()->role_id == 1 || Auth::user()->role_id == 2)
                <a href="{{ route('assignments.edit', $assignment->assignment_id) }}" class="btn btn-warning btn-lg shadow-sm me-2">
                    <i class="fas fa-edit me-2"></i> Edit
                </a>
            @endif
            <a href="{{ route('assignments.index') }}" class="btn btn-secondary btn-lg shadow-sm">
                <i class="fas fa-arrow-left me-2"></i> Back
            </a>
        </div>
    </div>

    <div class="row g-4">
        <!-- Left Column - Assignment Info -->
        <div class="col-lg-8">
            <div class="card shadow-sm border-0 rounded-4">
                <div class="card-header text-white rounded-top-4" style="background: #0D6EFD;">
                    <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i> Assignment Information</h5>
                </div>
                <div class="card-body p-4">
                    <div class="row mb-3">
                        <div class="col-md-3 fw-bold" style="color: #0D6EFD;">Course:</div>
                        <div class="col-md-9">
                            <span class="badge bg-light text-dark px-3 py-2 rounded-pill">
                                <i class="fas fa-book" style="color: #0D6EFD;"></i> {{ $assignment->course->course_name ?? 'N/A' }}
                            </span>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-3 fw-bold" style="color: #0D6EFD;">Teacher:</div>
                        <div class="col-md-9">
                            <div class="d-flex align-items-center">
                                <div class="avatar-sm me-2">
                                    <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 32px; height: 32px; background: #0D6EFD;">
                                        <i class="fas fa-chalkboard-user text-white fa-xs"></i>
                                    </div>
                                </div>
                                <span class="fw-semibold">{{ $assignment->creator->full_name ?? 'N/A' }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-3 fw-bold" style="color: #0D6EFD;">Description:</div>
                        <div class="col-md-9">
                            <p class="mb-0 text-muted">{{ $assignment->description ?? 'No description provided.' }}</p>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-3 fw-bold" style="color: #0D6EFD;">Total Marks:</div>
                        <div class="col-md-9">
                            <span class="fw-bold fs-4" style="color: #0D6EFD;">{{ $assignment->total_marks }}</span>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-3 fw-bold" style="color: #0D6EFD;">Start Date:</div>
                        <div class="col-md-9">
                            <i class="fas fa-calendar-alt text-muted me-1"></i>
                            {{ \Carbon\Carbon::parse($assignment->start_date)->format('F d, Y H:i') }}
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-3 fw-bold" style="color: #0D6EFD;">Due Date:</div>
                        <div class="col-md-9">
                            @php
                                $dueDate = $assignment->due_date;
                                $isOverdue = $dueDate < now();
                                $daysLeft = now()->diffInDays($dueDate, false);
                                $roundedDays = floor(abs($daysLeft));

                                if ($isOverdue) {
                                    $dateBadge = 'danger';
                                    $dateText = 'Overdue';
                                } elseif ($daysLeft == 0) {
                                    $dateBadge = 'warning';
                                    $dateText = 'Due today';
                                } elseif ($daysLeft == 1) {
                                    $dateBadge = 'warning';
                                    $dateText = 'Tomorrow';
                                } else {
                                    $dateBadge = 'success';
                                    $dateText = $roundedDays . ' days left';
                                }
                            @endphp
                            <i class="fas fa-hourglass-half text-muted me-1"></i>
                            {{ $dueDate->format('F d, Y H:i') }}
                            <span class="badge bg-{{ $dateBadge }} ms-2 text-white">{{ $dateText }}</span>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-3 fw-bold" style="color: #0D6EFD;">Late Submission:</div>
                        <div class="col-md-9">
                            @if($assignment->allow_late_submission)
                                <span class="badge text-white" style="background: #10b981;">Allowed</span>
                            @else
                                <span class="badge text-white" style="background: #6c757d;">Not Allowed</span>
                            @endif
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-3 fw-bold" style="color: #0D6EFD;">Status:</div>
                        <div class="col-md-9">
                            @php
                                $statusClass = match($assignment->status) {
                                    'Published' => '#0d6efd',
                                    'Draft' => '#6c757d',
                                    'Closed' => '#212529',
                                    default => '#0dcaf0'
                                };
                            @endphp
                            <span class="badge px-3 py-2 rounded-pill text-white" style="background-color: {{ $statusClass }};">
                                {{ $assignment->status }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Assignment Files -->
            @if($assignment->assignmentFiles && $assignment->assignmentFiles->count() > 0)
            <div class="card shadow-sm border-0 rounded-4 mt-4">
                <div class="card-header bg-white border-0 pt-4">
                    <h5 class="fw-bold mb-0">
                        <i class="fas fa-paperclip me-2" style="color: #0D6EFD;"></i> Assignment Files
                    </h5>
                </div>
                <div class="card-body p-4 pt-0">
                    <div class="list-group">
                        @foreach($assignment->assignmentFiles as $file)
                            <a href="{{ Storage::url($file->file_path) }}" target="_blank" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center rounded-3 mb-2">
                                <div>
                                    <i class="fas fa-file-alt me-2" style="color: #0D6EFD;"></i>
                                    {{ $file->file_name }}
                                </div>
                                <i class="fas fa-download text-muted"></i>
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif
        </div>

        <!-- Right Column - Submission Info -->
        <div class="col-lg-4">
            @if(Auth::user()->role_id == 3) {{-- Student --}}
                @php
                    $existingSubmission = $assignment->submissions->where('student_id', Auth::user()->user_id)->first();
                @endphp

                @if($existingSubmission)
                    <div class="card shadow-sm border-0 rounded-4">
                        <div class="card-header text-white rounded-top-4" style="background: #10b981;">
                            <h5 class="mb-0"><i class="fas fa-paper-plane me-2"></i> Your Submission</h5>
                        </div>
                        <div class="card-body p-4">
                            <div class="mb-3">
                                <label class="fw-bold" style="color: #0D6EFD;">Submitted:</label>
                                <p class="mb-0">{{ \Carbon\Carbon::parse($existingSubmission->submitted_at)->format('F d, Y H:i') }}</p>
                            </div>
                            <div class="mb-3">
                                <label class="fw-bold" style="color: #0D6EFD;">Status:</label>
                                <p class="mb-0">
                                    @if($existingSubmission->grade)
                                        <span class="badge text-white" style="background: #10b981;">Graded</span>
                                    @else
                                        <span class="badge text-white" style="background: #f59e0b;">Pending</span>
                                    @endif
                                </p>
                            </div>
                            @if($existingSubmission->grade)
                                <div class="mb-3">
                                    <label class="fw-bold" style="color: #0D6EFD;">Grade:</label>
                                    <p class="mb-0 fs-4 fw-bold" style="color: #10b981;">
                                        {{ $existingSubmission->grade->marks_obtained }} / {{ $assignment->total_marks }}
                                    </p>
                                </div>
                            @endif
                            <a href="{{ route('submissions.show', $existingSubmission->submission_id) }}" class="btn w-100 text-white" style="background: #0D6EFD;">
                                <i class="fas fa-eye me-2"></i> View Submission
                            </a>
                        </div>
                    </div>
                @else
                    @if($assignment->status == 'Published' && (now() <= $assignment->due_date || $assignment->allow_late_submission))
                        <div class="card shadow-sm border-0 rounded-4">
                            <div class="card-header text-white rounded-top-4" style="background: #0D6EFD;">
                                <h5 class="mb-0"><i class="fas fa-upload me-2"></i> Ready to Submit?</h5>
                            </div>
                            <div class="card-body p-4 text-center">
                                <i class="fas fa-paper-plane fa-3x mb-3" style="color: #0D6EFD;"></i>
                                <p>You haven't submitted this assignment yet.</p>
                                <a href="{{ route('submissions.create', ['assignment_id' => $assignment->assignment_id]) }}" class="btn w-100 text-white" style="background: #10b981;">
                                    <i class="fas fa-upload me-2"></i> Submit Assignment
                                </a>
                            </div>
                        </div>
                    @endif
                @endif
            @endif
        </div>
    </div>

    <!-- Submissions Table (Teacher/Admin View) -->
    @if((Auth::user()->role_id == 1 || Auth::user()->role_id == 2) && $assignment->submissions && $assignment->submissions->count() > 0)
    <div class="row mt-4">
        <div class="col-12">
            <div class="card shadow-sm border-0 rounded-4">
                <div class="card-header bg-white border-0 pt-4">
                    <h5 class="fw-bold mb-0">
                        <i class="fas fa-users me-2" style="color: #0D6EFD;"></i> Student Submissions ({{ $assignment->submissions->count() }})
                    </h5>
                </div>
                <div class="card-body p-4 pt-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Student</th>
                                    <th>Submitted At</th>
                                    <th>Status</th>
                                    <th>Grade</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($assignment->submissions as $submission)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-sm me-2">
                                                <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 32px; height: 32px; background: #0D6EFD;">
                                                    <i class="fas fa-user-graduate text-white fa-xs"></i>
                                                </div>
                                            </div>
                                            {{ $submission->student->full_name ?? 'N/A' }}
                                        </div>
                                    </td>
                                    <td>
                                        <i class="fas fa-calendar-alt text-muted me-1"></i>
                                        {{ \Carbon\Carbon::parse($submission->submitted_at)->format('M d, Y H:i') }}
                                    </td>
                                    <td>
                                        @if($submission->grade)
                                            <span class="badge text-white" style="background: #10b981;">Graded</span>
                                        @else
                                            <span class="badge text-white" style="background: #f59e0b;">Pending</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($submission->grade)
                                            <span class="fw-bold" style="color: #10b981;">
                                                {{ $submission->grade->marks_obtained }} / {{ $assignment->total_marks }}
                                            </span>
                                            <br>
                                            <small class="text-muted">Grade: {{ $submission->grade->grade }}</small>
                                        @else
                                            <span class="text-muted">Not graded</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('submissions.show', $submission->submission_id) }}" class="btn btn-sm btn-outline-primary rounded-3 me-1">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            @if(!$submission->grade)
                                                <a href="{{ route('grades.create') }}?submission_id={{ $submission->submission_id }}" class="btn btn-sm btn-outline-primary rounded-3">
                                                    <i class="fas fa-star"></i>
                                                </a>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>

<style>
    /* Text Gradient */
    .text-gradient {
        background: linear-gradient(135deg, #0D6EFD 0%, #0b5ed7 100%);
        -webkit-background-clip: text;
        background-clip: text;
        color: transparent;
    }

    /* Avatar */
    .avatar-sm {
        width: 32px;
        height: 32px;
    }

    /* Table Hover */
    .table-hover tbody tr:hover {
        background-color: rgba(13, 110, 253, 0.04);
        transition: all 0.3s ease;
    }

    /* List Group */
    .list-group-item {
        transition: all 0.2s ease;
    }

    .list-group-item:hover {
        transform: translateX(5px);
        background-color: rgba(13, 110, 253, 0.04);
    }

    /* Button Group */
    .btn-group .btn {
        transition: all 0.2s ease;
    }

    .btn-group .btn:hover {
        transform: translateY(-2px);
    }

    /* Badge */
    .badge {
        font-weight: 500;
    }

    /* Edit Button (Warning) */
    .btn-warning {
        background: #f59e0b;
        border: none;
        color: white;
    }

    .btn-warning:hover {
        background: #d97706;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(245, 158, 11, 0.3);
        color: white;
    }

    /* Secondary Button */
    .btn-secondary {
        background: #6c757d;
        border: none;
    }

    .btn-secondary:hover {
        background: #5a6268;
        transform: translateY(-2px);
    }

    /* Outline Primary Button */
    .btn-outline-primary {
        color: #0D6EFD;
        border-color: #0D6EFD;
    }

    .btn-outline-primary:hover {
        background: #0D6EFD;
        border-color: #0D6EFD;
        color: white;
        transform: translateY(-2px);
    }

    /* Responsive */
    @media (max-width: 768px) {
        .display-6 {
            font-size: 1.5rem;
        }

        .btn {
            padding: 6px 12px;
            font-size: 0.85rem;
        }

        .avatar-sm {
            width: 28px;
            height: 28px;
        }
    }
</style>
@endsection
