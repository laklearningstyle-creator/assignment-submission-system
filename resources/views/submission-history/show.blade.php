@extends('layouts.app')

@section('title', 'Submission History Details')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-lg border-0 rounded-4">
                <div class="card-header bg-gradient-primary text-white rounded-top-4" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="mb-0">
                            <i class="fas fa-history me-2"></i> Submission History Details
                        </h4>
                        <a href="{{ route('submission-history.index') }}" class="btn btn-light btn-sm">
                            <i class="fas fa-arrow-left me-1"></i> Back
                        </a>
                    </div>
                </div>

                <div class="card-body p-4">
                    <div class="row mb-3">
                        <div class="col-md-3 fw-bold text-primary">History ID:</div>
                        <div class="col-md-9">{{ $history->history_id }}</div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-3 fw-bold text-primary">Assignment:</div>
                        <div class="col-md-9">
                            <a href="{{ route('assignments.show', $history->submission->assignment->assignment_id ?? 0) }}" class="text-decoration-none">
                                <i class="fas fa-tasks me-1"></i> {{ $history->submission->assignment->title ?? 'N/A' }}
                            </a>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-3 fw-bold text-primary">Course:</div>
                        <div class="col-md-9">
                            <i class="fas fa-book me-1"></i>
                            {{ $history->submission->assignment->course->course_name ?? 'N/A' }}
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-3 fw-bold text-primary">Student:</div>
                        <div class="col-md-9">
                            <div class="d-flex align-items-center">
                                <div class="rounded-circle bg-primary bg-opacity-10 p-2 me-2 text-center" style="width: 32px; height: 32px;">
                                    {{ strtoupper(substr($history->submission->student->full_name ?? 'S', 0, 1)) }}
                                </div>
                                <div>
                                    <strong>{{ $history->submission->student->full_name ?? 'N/A' }}</strong>
                                    <br>
                                    <small class="text-muted">{{ $history->submission->student->email ?? 'N/A' }}</small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-3 fw-bold text-primary">Action:</div>
                        <div class="col-md-9">
                            @php
                                $actionClass = match($history->action) {
                                    'Submitted' => 'success',
                                    'Updated' => 'info',
                                    'Graded' => 'warning',
                                    'Resubmitted' => 'primary',
                                    default => 'secondary'
                                };
                                $actionIcon = match($history->action) {
                                    'Submitted' => 'fa-paper-plane',
                                    'Updated' => 'fa-edit',
                                    'Graded' => 'fa-star',
                                    'Resubmitted' => 'fa-redo',
                                    default => 'fa-info-circle'
                                };
                            @endphp
                            <span class="badge bg-{{ $actionClass }} bg-opacity-10 text-{{ $actionClass }} px-3 py-2">
                                <i class="fas {{ $actionIcon }} me-1"></i> {{ $history->action }}
                            </span>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-3 fw-bold text-primary">Performed By:</div>
                        <div class="col-md-9">
                            <div class="d-flex align-items-center">
                                <div class="rounded-circle bg-secondary bg-opacity-10 p-2 me-2 text-center" style="width: 32px; height: 32px;">
                                    {{ strtoupper(substr($history->performer->full_name ?? 'U', 0, 1)) }}
                                </div>
                                <div>
                                    <strong>{{ $history->performer->full_name ?? 'N/A' }}</strong>
                                    <br>
                                    <small class="text-muted">{{ $history->performer->role->role_name ?? 'User' }}</small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-3 fw-bold text-primary">Performed At:</div>
                        <div class="col-md-9">
                            <i class="fas fa-calendar-alt text-muted me-1"></i>
                            {{ \Carbon\Carbon::parse($history->performed_at)->format('F d, Y H:i:s') }}
                            <br>
                            <small class="text-muted">{{ \Carbon\Carbon::parse($history->performed_at)->diffForHumans() }}</small>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-3 fw-bold text-primary">Submission Content:</div>
                        <div class="col-md-9">
                            <div class="bg-light p-3 rounded-3">
                                <pre class="mb-0" style="white-space: pre-wrap;">{{ $history->submission->submission_text ?? 'No content provided' }}</pre>
                            </div>
                        </div>
                    </div>

                    @if($history->submission->grade)
                    <div class="row mb-3">
                        <div class="col-md-3 fw-bold text-primary">Grade Information:</div>
                        <div class="col-md-9">
                            <div class="bg-light p-3 rounded-3">
                                <p class="mb-1"><strong>Marks:</strong> {{ $history->submission->grade->marks_obtained }}/{{ $history->submission->assignment->total_marks ?? 100 }}</p>
                                <p class="mb-1"><strong>Grade:</strong> <span class="badge bg-success">{{ $history->submission->grade->grade }}</span></p>
                                <p class="mb-0"><strong>Graded At:</strong> {{ \Carbon\Carbon::parse($history->submission->grade->graded_at)->format('M d, Y H:i') }}</p>
                            </div>
                        </div>
                    </div>
                    @endif

                    @if($history->submission->files->count() > 0)
                    <div class="row mb-3">
                        <div class="col-md-3 fw-bold text-primary">Attached Files:</div>
                        <div class="col-md-9">
                            @foreach($history->submission->files as $file)
                                <div class="bg-light p-2 rounded-3 mb-2">
                                    <i class="fas fa-file-alt text-primary me-2"></i>
                                    {{ $file->file_name }}
                                    <a href="{{ route('submission-files.download', $file->submission_file_id) }}" class="btn btn-sm btn-outline-primary float-end">
                                        <i class="fas fa-download me-1"></i> Download
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    <div class="row mb-3">
                        <div class="col-md-3 fw-bold text-primary">Late Submission:</div>
                        <div class="col-md-9">
                            @if($history->submission->is_late)
                                <span class="badge bg-danger">Yes</span>
                            @else
                                <span class="badge bg-success">No</span>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="card-footer bg-transparent border-0 pb-4">
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('submission-history.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-2"></i> Back to List
                        </a>
                        <a href="{{ route('submissions.show', $history->submission->submission_id) }}" class="btn btn-primary">
                            <i class="fas fa-eye me-2"></i> View Full Submission
                        </a>
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
    pre {
        font-family: 'Inter', sans-serif;
        font-size: 14px;
    }
</style>
@endsection
