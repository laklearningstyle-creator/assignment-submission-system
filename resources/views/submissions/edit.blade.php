@extends('layouts.app')

@section('title', 'Edit Submission')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <!-- Header Section -->
            <div class="d-flex align-items-center gap-3 mb-4">
                <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0" style="width: 45px; height: 45px; background: #f59e0b;">
                    <i class="fas fa-edit fa-lg text-white"></i>
                </div>
                <div>
                    <h1 class="fw-bold mb-0" style="color: #1a1a2e; font-size: 1.75rem;">Edit Submission</h1>
                    <p class="text-muted mt-1 mb-0" style="font-size: 0.875rem;">Update your assignment submission</p>
                </div>
            </div>

            <div class="card shadow-lg border-0 rounded-4">
                <div class="card-header bg-gradient-warning text-white rounded-top-4" style="background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);">
                    <h5 class="mb-0">
                        <i class="fas fa-paper-plane me-2"></i> Edit Submission
                    </h5>
                </div>

                <div class="card-body p-4">
                    @if ($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show rounded-3">
                            <i class="fas fa-exclamation-circle me-2"></i> Please fix the following errors:
                            <ul class="mb-0 mt-2">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show rounded-3">
                            <i class="fas fa-exclamation-circle me-2"></i> {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <!-- Assignment Info -->
                    <div class="alert alert-info mb-4">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-info-circle fa-2x me-3"></i>
                            <div>
                                <h6 class="mb-0 fw-bold">{{ $submission->assignment->title ?? 'N/A' }}</h6>
                                <small class="text-muted">
                                    Course: {{ $submission->assignment->course->course_name ?? 'N/A' }} |
                                    Due Date: {{ $submission->assignment->due_date ? \Carbon\Carbon::parse($submission->assignment->due_date)->format('M d, Y H:i') : 'N/A' }}
                                </small>
                            </div>
                        </div>
                    </div>

                    <form method="POST" action="{{ route('submissions.update', $submission->submission_id) }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <!-- Submission Content -->
                        <div class="mb-4">
                            <label for="submission_text" class="form-label fw-bold" style="font-size: 0.875rem;">
                                <i class="fas fa-align-left me-1" style="color: #f59e0b;"></i> Submission Content
                            </label>
                            <textarea name="submission_text" id="submission_text" rows="6"
                                      class="form-control @error('submission_text') is-invalid @enderror"
                                      style="font-size: 0.875rem; border-radius: 8px;"
                                      placeholder="Write your submission here...">{{ old('submission_text', $submission->submission_text) }}</textarea>
                            @error('submission_text')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- File Attachment -->
                        <div class="mb-4">
                            <label for="file" class="form-label fw-bold" style="font-size: 0.875rem;">
                                <i class="fas fa-paperclip me-1" style="color: #f59e0b;"></i> Additional File (Optional)
                            </label>
                            <input type="file" name="file" id="file"
                                   class="form-control @error('file') is-invalid @enderror"
                                   style="font-size: 0.875rem; border-radius: 8px;"
                                   accept=".pdf,.doc,.docx,.txt,.zip">
                            @error('file')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted" style="font-size: 0.75rem;">Allowed formats: PDF, DOC, DOCX, TXT, ZIP (Max: 10MB)</small>
                        </div>

                        <!-- Existing Files -->
                        @if(isset($submission->files) && $submission->files && $submission->files->count() > 0)
                            <div class="mb-4">
                                <label class="form-label fw-bold" style="font-size: 0.875rem;">
                                    <i class="fas fa-folder-open me-1" style="color: #f59e0b;"></i> Uploaded Files
                                </label>
                                @foreach($submission->files as $file)
                                    <div class="border rounded-3 p-2 mb-2 bg-light">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <i class="fas fa-file-alt text-primary me-2"></i>
                                                {{ $file->file_name }}
                                                <small class="text-muted">({{ round($file->file_size / 1024) }} KB)</small>
                                            </div>
                                            <a href="{{ route('submissions.download', $file->submission_file_id ?? $file->id) }}" class="btn btn-sm btn-outline-primary">
                                                <i class="fas fa-download me-1"></i> Download
                                            </a>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif

                        <!-- Submission Info -->
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <div class="bg-light rounded-3 p-2 text-center">
                                    <small class="text-muted">Submitted On</small>
                                    <div class="fw-bold" style="font-size: 0.875rem;">
                                        {{ \Carbon\Carbon::parse($submission->submitted_at)->format('M d, Y H:i') }}
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="bg-light rounded-3 p-2 text-center">
                                    <small class="text-muted">Status</small>
                                    <div>
                                        @if($submission->is_late)
                                            <span class="badge bg-danger">Late Submission</span>
                                        @else
                                            <span class="badge bg-success">On Time</span>
                                        @endif
                                        @if($submission->grade)
                                            <span class="badge bg-info ms-1">Graded</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="d-flex gap-3 mt-4">
                            <button type="submit" class="btn btn-primary flex-grow-1 py-2" style="background: #f59e0b; border: none; border-radius: 8px; font-size: 0.875rem;">
                                <i class="fas fa-save me-2"></i> Update Submission
                            </button>
                            <a href="{{ route('submissions.show', $submission->submission_id) }}" class="btn btn-outline-secondary flex-grow-1 py-2" style="border-radius: 8px; font-size: 0.875rem;">
                                <i class="fas fa-times me-2"></i> Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Warning Card -->
            <div class="card bg-light border-0 rounded-4 mt-4">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-exclamation-triangle text-warning me-3 fa-lg"></i>
                        <div>
                            <h6 class="mb-0 fw-bold" style="font-size: 0.875rem;">Note:</h6>
                            <small class="text-muted" style="font-size: 0.75rem;">
                                You can only edit this submission if it hasn't been graded yet.
                                @if($submission->grade)
                                    <span class="text-danger">This submission has already been graded and cannot be edited.</span>
                                @endif
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .bg-gradient-warning {
        background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
    }

    .form-control, .form-select {
        border: 1px solid #e2e8f0;
        transition: all 0.2s ease;
    }

    .form-control:focus, .form-select:focus {
        border-color: #f59e0b;
        box-shadow: 0 0 0 3px rgba(245, 158, 11, 0.1);
        outline: none;
    }

    .btn-primary {
        background: #f59e0b;
        border: none;
        transition: all 0.2s ease;
    }

    .btn-primary:hover {
        background: #d97706;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(245, 158, 11, 0.3);
    }

    .btn-outline-secondary {
        transition: all 0.2s ease;
        border: 1px solid #ced4da;
    }

    .btn-outline-secondary:hover {
        transform: translateY(-1px);
        background: #f8f9fa;
        border-color: #adb5bd;
    }

    .card {
        border-radius: 12px;
    }

    .bg-light {
        background-color: #f8fafc !important;
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
