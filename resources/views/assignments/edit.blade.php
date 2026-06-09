@extends('layouts.app')

@section('title', 'Edit Assignment')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <!-- Header Section -->
            <div class="d-flex align-items-center gap-3 mb-4">
                <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0" style="width: 55px; height: 55px; background: #0D6EFD;">
                    <i class="fas fa-edit fa-xl text-white"></i>
                </div>
                <div>
                    <h1 class="fw-bold mb-0" style="color: #1a1a2e;">Edit Assignment</h1>
                    <p class="text-muted mt-1 mb-0">Update assignment information</p>
                </div>
            </div>

            <!-- Form Card -->
            <div class="card shadow-sm border-0 rounded-4">
                <div class="card-header bg-white border-0 pt-4 pb-0 px-4">
                    <h5 class="fw-bold mb-0">
                        <i class="fas fa-tasks me-2" style="color: #0D6EFD;"></i> Assignment Details
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

                    <form method="POST" action="{{ route('assignments.update', $assignment->assignment_id) }}">
                        @csrf
                        @method('PUT')

                        <!-- Course Selection -->
                        <div class="mb-4">
                            <label class="form-label fw-bold">
                                <i class="fas fa-book me-1" style="color: #0D6EFD;"></i> Course <span class="text-danger">*</span>
                            </label>
                            <select name="course_id" class="form-select form-select-lg @error('course_id') is-invalid @enderror" required>
                                <option value="">-- Select Course --</option>
                                @foreach($courses as $course)
                                    <option value="{{ $course->course_id }}" {{ old('course_id', $assignment->course_id) == $course->course_id ? 'selected' : '' }}>
                                        {{ $course->course_name }} ({{ $course->course_code }})
                                    </option>
                                @endforeach
                            </select>
                            @error('course_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Assignment Title -->
                        <div class="mb-4">
                            <label class="form-label fw-bold">
                                <i class="fas fa-heading me-1" style="color: #0D6EFD;"></i> Assignment Title <span class="text-danger">*</span>
                            </label>
                            <input type="text" name="title"
                                   class="form-control form-control-lg @error('title') is-invalid @enderror"
                                   value="{{ old('title', $assignment->title) }}"
                                   placeholder="Enter assignment title"
                                   required>
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Description -->
                        <div class="mb-4">
                            <label class="form-label fw-bold">
                                <i class="fas fa-align-left me-1" style="color: #0D6EFD;"></i> Description
                            </label>
                            <textarea name="description" rows="5"
                                      class="form-control @error('description') is-invalid @enderror"
                                      placeholder="Describe the assignment requirements...">{{ old('description', $assignment->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Provide detailed instructions for students.</small>
                        </div>

                        <!-- Total Marks -->
                        <div class="mb-4">
                            <label class="form-label fw-bold">
                                <i class="fas fa-star me-1" style="color: #0D6EFD;"></i> Total Marks <span class="text-danger">*</span>
                            </label>
                            <input type="number" step="0.01" name="total_marks"
                                   class="form-control form-control-lg @error('total_marks') is-invalid @enderror"
                                   value="{{ old('total_marks', $assignment->total_marks) }}"
                                   placeholder="e.g., 100"
                                   required>
                            @error('total_marks')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Start Date & Due Date -->
                        <div class="row">
                            <div class="col-md-6 mb-4">
                                <label class="form-label fw-bold">
                                    <i class="fas fa-calendar-alt me-1" style="color: #0D6EFD;"></i> Start Date <span class="text-danger">*</span>
                                </label>
                                <input type="datetime-local" name="start_date"
                                       class="form-control @error('start_date') is-invalid @enderror"
                                       value="{{ old('start_date', $assignment->start_date->format('Y-m-d\TH:i')) }}"
                                       required>
                                @error('start_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-4">
                                <label class="form-label fw-bold">
                                    <i class="fas fa-hourglass-end me-1" style="color: #0D6EFD;"></i> Due Date <span class="text-danger">*</span>
                                </label>
                                <input type="datetime-local" name="due_date"
                                       class="form-control @error('due_date') is-invalid @enderror"
                                       value="{{ old('due_date', $assignment->due_date->format('Y-m-d\TH:i')) }}"
                                       required>
                                @error('due_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Allow Late Submission -->
                        <div class="mb-4">
                            <div class="form-check">
                                <input type="checkbox" name="allow_late_submission"
                                       class="form-check-input" id="allowLate" value="1"
                                       {{ old('allow_late_submission', $assignment->allow_late_submission) ? 'checked' : '' }}>
                                <label class="form-check-label fw-bold" for="allowLate">
                                    <i class="fas fa-clock me-1" style="color: #0D6EFD;"></i> Allow Late Submission
                                </label>
                                <br>
                                <small class="text-muted">Students can submit after the due date.</small>
                            </div>
                        </div>

                        <!-- Status -->
                        <div class="mb-4">
                            <label class="form-label fw-bold">
                                <i class="fas fa-flag-checkered me-1" style="color: #0D6EFD;"></i> Status <span class="text-danger">*</span>
                            </label>
                            <select name="status" class="form-select form-select-lg @error('status') is-invalid @enderror" required>
                                <option value="Draft" {{ old('status', $assignment->status) == 'Draft' ? 'selected' : '' }}>📄 Draft - Not visible to students</option>
                                <option value="Published" {{ old('status', $assignment->status) == 'Published' ? 'selected' : '' }}>🚀 Published - Visible to students</option>
                                <option value="Closed" {{ old('status', $assignment->status) == 'Closed' ? 'selected' : '' }}>🔒 Closed - No more submissions</option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Assignment Stats -->
                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <div class="card bg-light border-0 rounded-3">
                                    <div class="card-body p-3 text-center">
                                        <i class="fas fa-paper-plane fa-2x mb-2" style="color: #0D6EFD;"></i>
                                        <h5 class="mb-0">{{ $assignment->submissions->count() }}</h5>
                                        <small class="text-muted">Total Submissions</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card bg-light border-0 rounded-3">
                                    <div class="card-body p-3 text-center">
                                        <i class="fas fa-star fa-2x mb-2" style="color: #10b981;"></i>
                                        <h5 class="mb-0">{{ $assignment->submissions->where('grade', '!=', null)->count() }}</h5>
                                        <small class="text-muted">Graded Submissions</small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="d-flex gap-3">
                            <button type="submit" class="btn btn-primary flex-grow-1 py-2" style="background: #0D6EFD; border: none; border-radius: 10px;">
                                <i class="fas fa-save me-2"></i> Update Assignment
                            </button>
                            <a href="{{ route('assignments.index') }}" class="btn btn-outline-secondary flex-grow-1 py-2" style="border-radius: 10px;">
                                <i class="fas fa-times me-2"></i> Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Danger Zone (Admin only) -->
            @if(Auth::user()->role_id == 1)
            <div class="card border-danger rounded-4 mt-4">
                <div class="card-header bg-white border-danger text-danger pt-3 pb-0 px-4">
                    <h5 class="fw-bold mb-0">
                        <i class="fas fa-exclamation-triangle me-2"></i> Danger Zone
                    </h5>
                </div>
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="fw-bold mb-1">Delete this assignment</h6>
                            <p class="text-muted small mb-0">Once deleted, all submissions and grades will be permanently removed.</p>
                        </div>
                        <form action="{{ route('assignments.destroy', $assignment->assignment_id) }}" method="POST" onsubmit="return confirm('⚠️ Are you sure you want to delete this assignment?\n\nThis action cannot be undone.');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">
                                <i class="fas fa-trash-alt me-2"></i> Delete Assignment
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

<style>
    /* Form Controls */
    .form-control, .form-select {
        border: 1px solid #e2e8f0;
        border-radius: 10px;
        transition: all 0.2s ease;
    }

    .form-control:focus, .form-select:focus {
        border-color: #0D6EFD;
        box-shadow: 0 0 0 3px rgba(13, 110, 253, 0.1);
        outline: none;
    }

    /* Primary Button */
    .btn-primary {
        background: #0D6EFD;
        border: none;
        transition: all 0.2s ease;
        border-radius: 10px;
    }

    .btn-primary:hover {
        background: #0b5ed7;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(13, 110, 253, 0.3);
    }

    /* Secondary Button */
    .btn-outline-secondary {
        transition: all 0.2s ease;
        border: 1px solid #ced4da;
        border-radius: 10px;
    }

    .btn-outline-secondary:hover {
        transform: translateY(-2px);
        background: #f8f9fa;
        border-color: #adb5bd;
    }

    /* Danger Button */
    .btn-danger {
        background: #dc2626;
        border: none;
        transition: all 0.2s ease;
        border-radius: 10px;
    }

    .btn-danger:hover {
        background: #b91c1c;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(220, 38, 38, 0.3);
    }

    /* Cards */
    .card {
        border-radius: 16px;
    }

    .bg-light {
        background-color: #f8fafc !important;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .container {
            padding-left: 16px;
            padding-right: 16px;
        }

        h1 {
            font-size: 1.75rem;
        }

        .btn {
            padding: 8px 12px;
            font-size: 0.85rem;
        }
    }
</style>
@endsection
