@extends('layouts.app')

@section('title', 'Add Grade')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <!-- Header Section -->
            <div class="d-flex align-items-center gap-3 mb-4">
                <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0" style="width: 45px; height: 45px; background: #0D6EFD;">
                    <i class="fas fa-plus-circle fa-lg text-white"></i>
                </div>
                <div>
                    <h1 class="fw-bold mb-0" style="color: #1a1a2e; font-size: 1.75rem;">Add Grade</h1>
                    <p class="text-muted mt-1 mb-0" style="font-size: 0.875rem;">Grade student assignment submissions</p>
                </div>
            </div>

            <div class="card shadow-lg border-0 rounded-4">
                <div class="card-header bg-gradient-primary text-white rounded-top-4" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                    <h5 class="mb-0">
                        <i class="fas fa-award me-2"></i> Grade Information
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

                    @if($submissions->isEmpty())
                        <div class="alert alert-warning rounded-3">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            <strong>No submissions available for grading.</strong><br>
                            All submissions have been graded or there are no submissions in your courses.
                        </div>
                    @endif

                    <form action="{{ route('grades.store') }}" method="POST">
                        @csrf

                        <div class="mb-4">
                            <label for="submission_id" class="form-label fw-bold" style="font-size: 0.875rem;">
                                <i class="fas fa-paper-plane me-1" style="color: #0D6EFD;"></i> Select Submission <span class="text-danger">*</span>
                            </label>
                            <select name="submission_id" id="submission_id" class="form-select @error('submission_id') is-invalid @enderror" style="font-size: 0.875rem; border-radius: 8px;" required {{ $submissions->isEmpty() ? 'disabled' : '' }}>
                                <option value="">-- Choose Submission --</option>
                                @foreach($submissions as $submission)
                                    <option value="{{ $submission->submission_id }}"
                                        {{ (old('submission_id') == $submission->submission_id || (isset($selectedSubmissionId) && $selectedSubmissionId == $submission->submission_id)) ? 'selected' : '' }}>
                                        {{ $submission->student->full_name ?? 'N/A' }} - {{ $submission->assignment->title ?? 'N/A' }}
                                        (Course: {{ $submission->assignment->course->course_name ?? 'N/A' }})
                                    </option>
                                @endforeach
                            </select>
                            @error('submission_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            @if($submissions->isEmpty())
                                <small class="text-danger" style="font-size: 0.75rem;">
                                    <i class="fas fa-exclamation-circle me-1"></i> No submissions available. Please check if students have submitted assignments to your courses.
                                </small>
                            @else
                                <small class="text-muted" style="font-size: 0.75rem;">Select the student submission you want to grade.</small>
                            @endif
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-4">
                                <label for="marks_obtained" class="form-label fw-bold" style="font-size: 0.875rem;">
                                    <i class="fas fa-percent me-1" style="color: #0D6EFD;"></i> Marks Obtained <span class="text-danger">*</span>
                                </label>
                                <input type="number" step="0.01" name="marks_obtained" id="marks_obtained"
                                       class="form-control @error('marks_obtained') is-invalid @enderror"
                                       style="font-size: 0.875rem; border-radius: 8px;"
                                       value="{{ old('marks_obtained') }}"
                                       placeholder="e.g., 85.5"
                                       {{ $submissions->isEmpty() ? 'disabled' : '' }}
                                       required>
                                @error('marks_obtained')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-4">
                                <label for="grade" class="form-label fw-bold" style="font-size: 0.875rem;">
                                    <i class="fas fa-award me-1" style="color: #0D6EFD;"></i> Grade <span class="text-danger">*</span>
                                </label>
                                <select name="grade" id="grade" class="form-select @error('grade') is-invalid @enderror" style="font-size: 0.875rem; border-radius: 8px;" required {{ $submissions->isEmpty() ? 'disabled' : '' }}>
                                    <option value="">-- Select Grade --</option>
                                    <option value="A+" {{ old('grade') == 'A+' ? 'selected' : '' }}>🌟 A+ (90-100) - Excellent</option>
                                    <option value="A" {{ old('grade') == 'A' ? 'selected' : '' }}>⭐ A (80-89) - Very Good</option>
                                    <option value="B" {{ old('grade') == 'B' ? 'selected' : '' }}>👍 B (70-79) - Good</option>
                                    <option value="C" {{ old('grade') == 'C' ? 'selected' : '' }}>📘 C (60-69) - Average</option>
                                    <option value="D" {{ old('grade') == 'D' ? 'selected' : '' }}>⚠️ D (50-59) - Poor</option>
                                    <option value="F" {{ old('grade') == 'F' ? 'selected' : '' }}>❌ F (Below 50) - Fail</option>
                                </select>
                                @error('grade')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="d-flex gap-3 mt-4">
                            <button type="submit" class="btn btn-primary flex-grow-1 py-2" style="background: #0D6EFD; border: none; border-radius: 8px; font-size: 0.875rem;" {{ $submissions->isEmpty() ? 'disabled' : '' }}>
                                <i class="fas fa-save me-2"></i> Save Grade
                            </button>
                            <a href="{{ route('grades.index') }}" class="btn btn-outline-secondary flex-grow-1 py-2" style="border-radius: 8px; font-size: 0.875rem;">
                                <i class="fas fa-times me-2"></i> Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Info Card -->
            <div class="card bg-light border-0 rounded-4 mt-4">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-info-circle me-3 fa-lg" style="color: #0D6EFD;"></i>
                        <div>
                            <h6 class="mb-0 fw-bold" style="font-size: 0.875rem;">Grading Guidelines</h6>
                            <small class="text-muted" style="font-size: 0.75rem;">
                                A+: 90-100% | A: 80-89% | B: 70-79% | C: 60-69% | D: 50-59% | F: Below 50%
                            </small>
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

    .form-control, .form-select {
        border: 1px solid #e2e8f0;
        transition: all 0.2s ease;
    }

    .form-control:focus, .form-select:focus {
        border-color: #0D6EFD;
        box-shadow: 0 0 0 3px rgba(13, 110, 253, 0.1);
        outline: none;
    }

    .btn-primary {
        background: #0D6EFD;
        border: none;
        transition: all 0.2s ease;
    }

    .btn-primary:hover {
        background: #0b5ed7;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(13, 110, 253, 0.3);
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

        .btn {
            padding: 8px 12px;
        }
    }
</style>
@endsection
