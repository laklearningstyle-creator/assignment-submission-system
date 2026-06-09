@extends('layouts.app')

@section('title', 'Edit Course')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <!-- Header Section -->
            <div class="d-flex align-items-center gap-3 mb-4">
                <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0" style="width: 45px; height: 45px; background: #0D6EFD;">
                    <i class="fas fa-edit fa-lg text-white"></i>
                </div>
                <div>
                    <h1 class="fw-bold mb-0" style="color: #1a1a2e; font-size: 1.75rem;">Edit Course</h1>
                    <p class="text-muted mt-1 mb-0" style="font-size: 0.875rem;">Update course information</p>
                </div>
            </div>

            <!-- Form Card -->
            <div class="card shadow-sm border-0 rounded-4">
                <div class="card-header bg-white border-0 pt-4 pb-0 px-4">
                    <h5 class="fw-bold mb-0" style="font-size: 1.1rem;">
                        <i class="fas fa-book me-2" style="color: #0D6EFD;"></i> Course Details
                    </h5>
                </div>
                <div class="card-body p-4">
                    @if ($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show rounded-3" style="font-size: 0.875rem;">
                            <i class="fas fa-exclamation-circle me-2"></i> Please fix the following errors:
                            <ul class="mb-0 mt-2" style="font-size: 0.875rem;">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('courses.update', $course->course_id) }}">
                        @csrf
                        @method('PUT')

                        <!-- Course Name -->
                        <div class="mb-4">
                            <label class="form-label fw-bold" style="font-size: 0.875rem;">
                                <i class="fas fa-graduation-cap me-1" style="color: #0D6EFD;"></i> Course Name <span class="text-danger">*</span>
                            </label>
                            <input type="text" name="course_name"
                                   class="form-control @error('course_name') is-invalid @enderror"
                                   style="font-size: 0.875rem; border-radius: 8px;"
                                   value="{{ old('course_name', $course->course_name) }}"
                                   placeholder="Enter course name"
                                   required>
                            @error('course_name')
                                <div class="invalid-feedback" style="font-size: 0.8rem;">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Course Code -->
                        <div class="mb-4">
                            <label class="form-label fw-bold" style="font-size: 0.875rem;">
                                <i class="fas fa-code me-1" style="color: #0D6EFD;"></i> Course Code <span class="text-danger">*</span>
                            </label>
                            <input type="text" name="course_code"
                                   class="form-control @error('course_code') is-invalid @enderror"
                                   style="font-size: 0.875rem; border-radius: 8px;"
                                   value="{{ old('course_code', $course->course_code) }}"
                                   placeholder="e.g., CS101"
                                   required>
                            @error('course_code')
                                <div class="invalid-feedback" style="font-size: 0.8rem;">{{ $message }}</div>
                            @enderror
                            <small class="text-muted" style="font-size: 0.75rem;">Unique identifier for the course</small>
                        </div>

                        <!-- Description -->
                        <div class="mb-4">
                            <label class="form-label fw-bold" style="font-size: 0.875rem;">
                                <i class="fas fa-align-left me-1" style="color: #0D6EFD;"></i> Description
                            </label>
                            <textarea name="description" rows="5"
                                      class="form-control @error('description') is-invalid @enderror"
                                      style="font-size: 0.875rem; border-radius: 8px;"
                                      placeholder="Provide a detailed description of the course...">{{ old('description', $course->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback" style="font-size: 0.8rem;">{{ $message }}</div>
                            @enderror
                            <small class="text-muted" style="font-size: 0.75rem;">Describe what students will learn in this course</small>
                        </div>

                        <!-- Status -->
                        <div class="mb-4">
                            <label class="form-label fw-bold" style="font-size: 0.875rem;">
                                <i class="fas fa-toggle-on me-1" style="color: #0D6EFD;"></i> Status
                            </label>
                            <select name="status" class="form-select @error('status') is-invalid @enderror" style="font-size: 0.875rem; border-radius: 8px;">
                                <option value="active" {{ old('status', $course->status) == 'active' ? 'selected' : '' }}>🟢 Active</option>
                                <option value="inactive" {{ old('status', $course->status) == 'inactive' ? 'selected' : '' }}>🔴 Inactive</option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback" style="font-size: 0.8rem;">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Action Buttons -->
                        <div class="d-flex gap-3 mt-4">
                            <button type="submit" class="btn btn-primary flex-grow-1 py-2" style="background: #0D6EFD; border: none; border-radius: 8px; font-size: 0.875rem;">
                                <i class="fas fa-save me-2"></i> Update Course
                            </button>
                            <a href="{{ route('courses.index') }}" class="btn btn-outline-secondary flex-grow-1 py-2" style="border-radius: 8px; font-size: 0.875rem;">
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
                    <h5 class="fw-bold mb-0" style="font-size: 1rem;">
                        <i class="fas fa-exclamation-triangle me-2"></i> Danger Zone
                    </h5>
                </div>
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                        <div>
                            <h6 class="fw-bold mb-1" style="font-size: 0.875rem;">Delete this course</h6>
                            <p class="text-muted small mb-0" style="font-size: 0.75rem;">Once deleted, all assignments and enrollments will be affected.</p>
                        </div>
                        <form action="{{ route('courses.destroy', $course->course_id) }}" method="POST" onsubmit="return confirm('⚠️ Are you sure you want to delete this course?\n\nThis action cannot be undone and will affect all related data.');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger" style="border-radius: 8px; font-size: 0.875rem;">
                                <i class="fas fa-trash-alt me-2"></i> Delete Course
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
    }

    .btn-primary:hover {
        background: #0b5ed7;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(13, 110, 253, 0.3);
    }

    /* Secondary Button */
    .btn-outline-secondary {
        transition: all 0.2s ease;
        border: 1px solid #ced4da;
    }

    .btn-outline-secondary:hover {
        transform: translateY(-1px);
        background: #f8f9fa;
        border-color: #adb5bd;
    }

    /* Danger Button */
    .btn-danger {
        background: #dc2626;
        border: none;
        transition: all 0.2s ease;
    }

    .btn-danger:hover {
        background: #b91c1c;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(220, 38, 38, 0.3);
    }

    /* Cards */
    .card {
        border-radius: 12px;
    }

    /* Responsive */
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
