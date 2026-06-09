@extends('layouts.app')

@section('title', 'Submission Details')

@section('content')
<div class="container py-4">
    <div class="row">
        <div class="col-md-8">
            <!-- Header Section -->
            <div class="d-flex align-items-center gap-3 mb-4">
                <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0" style="width: 45px; height: 45px; background: #0D6EFD;">
                    <i class="fas fa-paper-plane fa-lg text-white"></i>
                </div>
                <div>
                    <h1 class="fw-bold mb-0" style="color: #1a1a2e; font-size: 1.75rem;">Submission Details</h1>
                    <p class="text-muted mt-1 mb-0" style="font-size: 0.875rem;">View assignment submission information</p>
                </div>
            </div>

            <div class="card shadow-lg border-0 rounded-4">
                <div class="card-header bg-gradient-primary text-white rounded-top-4 d-flex justify-content-between align-items-center" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                    <h5 class="mb-0">
                        <i class="fas fa-info-circle me-2"></i> Submission Information
                    </h5>
                    <a href="{{ route('submissions.index') }}" class="btn btn-sm btn-light rounded-pill">
                        <i class="fas fa-arrow-left me-1"></i> Back
                    </a>
                </div>

                <div class="card-body p-4">
                    <div class="row mb-3">
                        <div class="col-md-3 fw-bold text-primary">Submission ID:</div>
                        <div class="col-md-9">{{ $submission->submission_id }}</div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-3 fw-bold text-primary">Student:</div>
                        <div class="col-md-9">
                            <i class="fas fa-user-graduate me-1"></i> {{ $submission->student->full_name ?? 'N/A' }}
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-3 fw-bold text-primary">Assignment:</div>
                        <div class="col-md-9">
                            <i class="fas fa-tasks me-1"></i> {{ $submission->assignment->title ?? 'N/A' }}
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-3 fw-bold text-primary">Course:</div>
                        <div class="col-md-9">
                            <i class="fas fa-book me-1"></i> {{ $submission->assignment->course->course_name ?? 'N/A' }}
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-3 fw-bold text-primary">Submission Text:</div>
                        <div class="col-md-9">
                            <div class="bg-light p-3 rounded-3">
                                {{ $submission->submission_text ?? 'No text provided' }}
                            </div>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-3 fw-bold text-primary">Submitted At:</div>
                        <div class="col-md-9">
                            <i class="fas fa-calendar-alt me-1"></i> {{ \Carbon\Carbon::parse($submission->submitted_at)->format('F d, Y H:i') }}
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-3 fw-bold text-primary">Late Submission:</div>
                        <div class="col-md-9">
                            @if($submission->is_late)
                                <span class="badge bg-danger">Yes</span>
                            @else
                                <span class="badge bg-success">No</span>
                            @endif
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-3 fw-bold text-primary">Status:</div>
                        <div class="col-md-9">
                            @php
                                $statusColor = match($submission->status) {
                                    'Graded' => 'success',
                                    'Submitted' => 'info',
                                    'Resubmitted' => 'warning',
                                    default => 'secondary'
                                };
                            @endphp
                            <span class="badge bg-{{ $statusColor }} px-3 py-2 rounded-pill">
                                {{ $submission->status ?? 'Submitted' }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <!-- Submitted Files -->
            @if($submission->files && $submission->files->count() > 0)
            <div class="card shadow-sm border-0 rounded-4 mb-4">
                <div class="card-header bg-white border-0 pt-4">
                    <h6 class="mb-0 fw-bold">
                        <i class="fas fa-paperclip text-primary me-2"></i> Submitted Files
                    </h6>
                </div>
                <div class="card-body">
                    @foreach($submission->files as $file)
                        <div class="mb-2">
                            <a href="{{ route('submissions.download', $file->submission_file_id ?? $file->id) }}" class="btn btn-sm btn-outline-primary w-100">
                                <i class="fas fa-download me-2"></i> {{ $file->file_name }}
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
            @endif

            <!-- Grade Information -->
            @if($submission->grade)
            <div class="card shadow-sm border-0 rounded-4 mb-4">
                <div class="card-header bg-white border-0 pt-4">
                    <h6 class="mb-0 fw-bold">
                        <i class="fas fa-star text-success me-2"></i> Grade Information
                    </h6>
                </div>
                <div class="card-body">
                    <div class="mb-2">
                        <strong>Marks Obtained:</strong>
                        <span class="fw-bold text-primary">{{ $submission->grade->marks_obtained }}</span>
                        <span class="text-muted">/ {{ $submission->assignment->total_marks ?? 'N/A' }}</span>
                    </div>
                    <div class="mb-2">
                        <strong>Grade:</strong>
                        @php
                            $gradeColor = match($submission->grade->grade) {
                                'A+', 'A' => 'success',
                                'B', 'C' => 'warning',
                                'D', 'F' => 'danger',
                                default => 'secondary'
                            };
                        @endphp
                        <span class="badge bg-{{ $gradeColor }} px-3 py-2 rounded-pill">
                            {{ $submission->grade->grade }}
                        </span>
                    </div>
                    <div class="mb-2">
                        <strong>Graded By:</strong> {{ $submission->grade->grader->full_name ?? 'N/A' }}
                    </div>
                    <div>
                        <strong>Graded At:</strong> {{ $submission->grade->graded_at ? \Carbon\Carbon::parse($submission->grade->graded_at)->format('M d, Y H:i') : 'N/A' }}
                    </div>
                </div>
            </div>
            @endif

            <!-- Feedback Section -->
            @if(isset($submission->feedback) && $submission->feedback->count() > 0)
            <div class="card shadow-sm border-0 rounded-4 mb-4">
                <div class="card-header bg-white border-0 pt-4">
                    <h6 class="mb-0 fw-bold">
                        <i class="fas fa-comment text-info me-2"></i> Feedback
                    </h6>
                </div>
                <div class="card-body">
                    @foreach($submission->feedback as $feedback)
                        <div class="mb-3">
                            <div class="d-flex justify-content-between align-items-start">
                                <strong class="text-primary">{{ $feedback->teacher->full_name ?? 'Teacher' }}</strong>
                                <small class="text-muted">{{ $feedback->created_at->format('M d, Y H:i') }}</small>
                            </div>
                            <p class="mt-2 mb-0">{{ $feedback->comment }}</p>
                        </div>
                        @if(!$loop->last)
                            <hr>
                        @endif
                    @endforeach
                </div>
            </div>
            @endif

            <!-- Grade Button -->
            @if((auth()->user()->isTeacher() || auth()->user()->isAdmin()) && !$submission->grade)
            <div class="card shadow-sm border-0 rounded-4 mb-4">
                <div class="card-body">
                    <a href="{{ route('grades.create', ['submission_id' => $submission->submission_id]) }}" class="btn btn-primary w-100 py-2 rounded-3">
                        <i class="fas fa-star me-2"></i> Grade Submission
                    </a>
                </div>
            </div>
            @endif

            <!-- Feedback Button -->
            @if(auth()->user()->isTeacher() || auth()->user()->isAdmin())
            <div class="card shadow-sm border-0 rounded-4">
                <div class="card-body">
                    <a href="{{ route('feedbacks.create', ['submission_id' => $submission->submission_id]) }}" class="btn btn-info w-100 py-2 rounded-3 text-white">
                        <i class="fas fa-comment me-2"></i> Add Feedback
                    </a>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

<style>
    .bg-gradient-primary {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }

    .card {
        border-radius: 12px;
    }

    @media (max-width: 768px) {
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
