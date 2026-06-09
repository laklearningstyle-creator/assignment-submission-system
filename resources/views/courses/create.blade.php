@extends('layouts.app')

@section('title', 'Create New Course')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <!-- Header Card -->
            <div class="card shadow-lg border-0 rounded-4 mb-4">
                <div class="card-header bg-gradient-primary text-white rounded-top-4 py-3" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-plus-circle fa-2x me-3"></i>
                        <div>
                            <h4 class="mb-0 fw-bold">Create New Course</h4>
                            <p class="mb-0 opacity-75 small">Add a new course to the system</p>
                        </div>
                    </div>
                </div>

                <div class="card-body p-4">
                    <form method="POST" action="{{ route('courses.store') }}">
                        @csrf

                        <!-- Course Name -->
                        <div class="mb-4">
                            <label for="course_name" class="form-label fw-bold">
                                <i class="fas fa-book text-primary me-2"></i>Course Name <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0">
                                    <i class="fas fa-graduation-cap text-muted"></i>
                                </span>
                                <input type="text"
                                       name="course_name"
                                       id="course_name"
                                       class="form-control form-control-lg @error('course_name') is-invalid @enderror"
                                       value="{{ old('course_name') }}"
                                       placeholder="e.g., Introduction to Web Development"
                                       required>
                            </div>
                            @error('course_name')
                                <div class="invalid-feedback d-block">
                                    <i class="fas fa-exclamation-circle me-1"></i> {{ $message }}
                                </div>
                            @enderror
                            <small class="text-muted">Enter the full name of the course</small>
                        </div>

                        <!-- Course Code -->
                        <div class="mb-4">
                            <label for="course_code" class="form-label fw-bold">
                                <i class="fas fa-code text-primary me-2"></i>Course Code <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0">
                                    <i class="fas fa-tag text-muted"></i>
                                </span>
                                <input type="text"
                                       name="course_code"
                                       id="course_code"
                                       class="form-control form-control-lg @error('course_code') is-invalid @enderror"
                                       value="{{ old('course_code') }}"
                                       placeholder="e.g., CS101"
                                       required>
                            </div>
                            @error('course_code')
                                <div class="invalid-feedback d-block">
                                    <i class="fas fa-exclamation-circle me-1"></i> {{ $message }}
                                </div>
                            @enderror
                            <small class="text-muted">Unique identifier for the course (e.g., CS101, MATH201)</small>
                        </div>

                        <!-- Description -->
                        <div class="mb-4">
                            <label for="description" class="form-label fw-bold">
                                <i class="fas fa-align-left text-primary me-2"></i>Description
                            </label>
                            <div class="input-group">
                                <span class="input-group-text bg-light align-items-start border-end-0 pt-3">
                                    <i class="fas fa-file-alt text-muted"></i>
                                </span>
                                <textarea name="description"
                                          id="description"
                                          rows="5"
                                          class="form-control @error('description') is-invalid @enderror"
                                          placeholder="Provide a detailed description of the course...">{{ old('description') }}</textarea>
                            </div>
                            @error('description')
                                <div class="invalid-feedback d-block">
                                    <i class="fas fa-exclamation-circle me-1"></i> {{ $message }}
                                </div>
                            @enderror
                            <small class="text-muted">Describe what students will learn in this course</small>
                        </div>

                        <!-- Preview Section (Optional) -->
                        <div class="card bg-light border-0 rounded-3 mb-4">
                            <div class="card-body p-3">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-eye text-primary me-2"></i>
                                    <span class="small text-muted">Course Preview</span>
                                </div>
                                <div id="coursePreview" class="mt-2">
                                    <p class="mb-0 small">
                                        <strong id="previewName">Course Name</strong><br>
                                        <span id="previewCode">Course Code</span>
                                    </p>
                                    <p id="previewDesc" class="text-muted small mt-2">Description will appear here...</p>
                                </div>
                            </div>
                        </div>

                        <!-- Form Actions -->
                        <div class="d-flex gap-3 mt-4">
                            <button type="submit" class="btn btn-primary btn-lg px-5">
                                <i class="fas fa-save me-2"></i> Create Course
                            </button>
                            <a href="{{ route('courses.index') }}" class="btn btn-outline-secondary btn-lg px-4">
                                <i class="fas fa-times me-2"></i> Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Info Card -->
            <div class="card bg-light border-0 rounded-4">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-info-circle text-primary me-3 fa-2x"></i>
                        <div>
                            <h6 class="mb-0 fw-bold">Need help creating a course?</h6>
                            <small class="text-muted">Make sure to use a unique course code and provide a clear description.</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Live preview
    document.getElementById('course_name').addEventListener('input', function() {
        document.getElementById('previewName').textContent = this.value || 'Course Name';
    });

    document.getElementById('course_code').addEventListener('input', function() {
        document.getElementById('previewCode').textContent = this.value || 'Course Code';
    });

    document.getElementById('description').addEventListener('input', function() {
        var desc = this.value || 'Description will appear here...';
        document.getElementById('previewDesc').textContent = desc.length > 100 ? desc.substring(0, 100) + '...' : desc;
    });
</script>
@endpush

<style>
    .bg-gradient-primary {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }
    .form-control, .input-group-text {
        border-radius: 10px;
    }
    .form-control:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
    }
    .input-group-text {
        border-radius: 10px 0 0 10px;
    }
    .form-control:focus + .input-group-text {
        border-color: #667eea;
    }
    .btn-primary {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border: none;
        transition: all 0.3s ease;
        border-radius: 10px;
    }
    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
    }
    .btn-outline-secondary:hover {
        transform: translateY(-2px);
    }
</style>
@endsection
