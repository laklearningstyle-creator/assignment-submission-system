@extends('layouts.app')

@section('title', 'Reports Management')

@section('content')
<div class="container-fluid py-3 py-md-4">
    <div class="row">
        <!-- Sidebar -->
        <div class="col-md-3 col-lg-2 mb-4">
            <div class="card border-0 rounded-4 shadow-sm sticky-top" style="top: 80px;">
                <div class="card-header bg-white border-0 pt-4">
                    <h5 class="fw-bold mb-0" style="font-size: 1rem;">
                        <i class="fas fa-chart-bar me-2" style="color: #0D6EFD;"></i> Navigation
                    </h5>
                </div>
                <div class="card-body p-0">
                    <div class="list-group list-group-flush">
                        <a href="{{ route('admin.reports.index') }}" class="list-group-item list-group-item-action border-0 rounded-0 py-3 active" style="background: #0D6EFD; color: white;">
                            <i class="fas fa-chart-line me-2"></i> Reports
                        </a>
                        <a href="{{ route('admin.dashboard') }}" class="list-group-item list-group-item-action border-0 rounded-0 py-3">
                            <i class="fas fa-tachometer-alt me-2"></i> Dashboard
                        </a>
                        <a href="{{ route('admin.users.index') }}" class="list-group-item list-group-item-action border-0 rounded-0 py-3">
                            <i class="fas fa-users me-2"></i> Users
                        </a>
                        <a href="{{ route('admin.roles.index') }}" class="list-group-item list-group-item-action border-0 rounded-0 py-3">
                            <i class="fas fa-key me-2"></i> Roles
                        </a>
                        <hr class="my-2">
                        <a href="{{ route('courses.index') }}" class="list-group-item list-group-item-action border-0 rounded-0 py-3">
                            <i class="fas fa-book me-2"></i> Courses
                        </a>
                        <a href="{{ route('assignments.index') }}" class="list-group-item list-group-item-action border-0 rounded-0 py-3">
                            <i class="fas fa-tasks me-2"></i> Assignments
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="col-md-9 col-lg-10">
            <!-- Header -->
            <div class="d-flex align-items-center gap-3 mb-4">
                <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 45px; height: 45px; background: #0D6EFD;">
                    <i class="fas fa-chart-bar fa-lg text-white"></i>
                </div>
                <div>
                    <h1 class="fw-bold mb-0" style="color: #1a1a2e; font-size: 1.75rem;">Reports</h1>
                    <p class="text-muted mt-1 mb-0" style="font-size: 0.875rem;">Generate and download system reports</p>
                </div>
            </div>

            <div class="card shadow-lg border-0 rounded-4">
                <div class="card-header bg-gradient-primary text-white rounded-top-4">
                    <h4 class="mb-0" style="font-size: 1.1rem;">
                        <i class="fas fa-chart-bar me-2"></i> Generate Reports
                    </h4>
                </div>

                <div class="card-body p-4">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show rounded-3">
                            <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show rounded-3">
                            <i class="fas fa-exclamation-circle me-2"></i> {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <!-- Report Cards -->
                    <div class="row g-4 mb-5">
                        <!-- Submissions Report - Blue -->
                        <div class="col-md-4">
                            <div class="card h-100 border-0 shadow-sm rounded-4 text-center p-3" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                                <div class="card-body">
                                    <i class="fas fa-paper-plane fa-3x text-white mb-3"></i>
                                    <h5 class="fw-bold text-white">Submissions Report</h5>
                                    <p class="text-white-50 small">Submissions within date range</p>
                                    <form action="{{ route('admin.reports.submissions') }}" method="POST">
                                        @csrf
                                        <div class="mb-2">
                                            <input type="date" name="start_date" class="form-control form-control-sm" style="border-radius: 8px;" required>
                                        </div>
                                        <div class="mb-3">
                                            <input type="date" name="end_date" class="form-control form-control-sm" style="border-radius: 8px;" required>
                                        </div>
                                        <button type="submit" class="btn btn-light w-100 btn-sm" style="border-radius: 8px;">
                                            <i class="fas fa-download me-2"></i> Generate
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <!-- Grades Report - Green -->
                        <div class="col-md-4">
                            <div class="card h-100 border-0 shadow-sm rounded-4 text-center p-3" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%);">
                                <div class="card-body">
                                    <i class="fas fa-star fa-3x text-white mb-3"></i>
                                    <h5 class="fw-bold text-white">Grades Report</h5>
                                    <p class="text-white-50 small">Grades by assignment</p>
                                    <form action="{{ route('admin.reports.grades') }}" method="POST">
                                        @csrf
                                        <div class="mb-3">
                                            <select name="assignment_id" class="form-select form-select-sm" style="border-radius: 8px;" required>
                                                <option value="">Select Assignment</option>
                                                @foreach($assignments ?? [] as $assignment)
                                                    <option value="{{ $assignment->assignment_id }}">{{ $assignment->title }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <button type="submit" class="btn btn-light w-100 btn-sm" style="border-radius: 8px;">
                                            <i class="fas fa-download me-2"></i> Generate
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <!-- Performance Report - Orange -->
                        <div class="col-md-4">
                            <div class="card h-100 border-0 shadow-sm rounded-4 text-center p-3" style="background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);">
                                <div class="card-body">
                                    <i class="fas fa-chart-line fa-3x text-white mb-3"></i>
                                    <h5 class="fw-bold text-white">Performance Report</h5>
                                    <p class="text-white-50 small">Student performance by course</p>
                                    <form action="{{ route('admin.reports.performance') }}" method="POST">
                                        @csrf
                                        <div class="mb-2">
                                            <select name="student_id" class="form-select form-select-sm" style="border-radius: 8px;" required>
                                                <option value="">Select Student</option>
                                                @foreach($students ?? [] as $student)
                                                    <option value="{{ $student->user_id }}">{{ $student->full_name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="mb-3">
                                            <select name="course_id" class="form-select form-select-sm" style="border-radius: 8px;" required>
                                                <option value="">Select Course</option>
                                                @foreach($courses ?? [] as $course)
                                                    <option value="{{ $course->course_id }}">{{ $course->course_name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <button type="submit" class="btn btn-light w-100 btn-sm" style="border-radius: 8px;">
                                            <i class="fas fa-download me-2"></i> Generate
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Generated Reports List -->
                    <div class="card border-0 shadow-sm rounded-4">
                        <div class="card-header bg-transparent border-0 pt-4">
                            <h5 class="fw-bold mb-0" style="font-size: 0.9rem;">
                                <i class="fas fa-history me-2 text-primary"></i> Generated Reports
                            </h5>
                        </div>
                        <div class="card-body">
                            @if($reports->count() > 0)
                                <div class="table-responsive">
                                    <table class="table table-hover align-middle mb-0">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Type</th>
                                                <th>Generated By</th>
                                                <th>Date</th>
                                                <th></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($reports as $report)
                                            <tr>
                                                <td>
                                                    <span class="badge px-3 py-2 rounded-pill" style="background: #eef2ff; color: #0D6EFD; font-size: 0.7rem;">
                                                        <i class="fas fa-file-alt me-1"></i> {{ $report->report_type ?? 'Report' }}
                                                    </span>
                                                </td>
                                                <td style="font-size: 0.8rem;">{{ $report->generator->full_name ?? 'N/A' }}</td>
                                                <td style="font-size: 0.75rem;">{{ $report->created_at->format('M d, Y') }}</td>
                                                <td>
                                                    <div class="d-flex gap-1">
                                                        <a href="{{ route('admin.reports.download', $report->report_id ?? $report->id) }}" class="btn btn-sm btn-outline-primary" title="Download">
                                                            <i class="fas fa-download"></i>
                                                        </a>
                                                        <button type="button" class="btn btn-sm btn-outline-danger" title="Delete" onclick="showDeleteModal({{ $report->report_id ?? $report->id }})">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                        <form id="delete-form-{{ $report->report_id ?? $report->id }}" action="{{ route('admin.reports.destroy', $report->report_id ?? $report->id) }}" method="POST" style="display: none;">
                                                            @csrf @method('DELETE')
                                                        </form>
                                                    </div>
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                <div class="mt-3">
                                    {{ $reports->links() }}
                                </div>
                            @else
                                <div class="text-center py-4">
                                    <i class="fas fa-chart-bar fa-3x text-muted mb-2 d-block opacity-25"></i>
                                    <p class="text-muted mb-0">No reports generated yet</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content rounded-4">
            <div class="modal-header border-0 pb-0">
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center pb-4">
                <i class="fas fa-trash-alt fa-3x text-danger mb-3"></i>
                <h6 class="fw-bold">Delete Report?</h6>
                <p class="text-muted small mb-0">This action cannot be undone.</p>
            </div>
            <div class="modal-footer border-0 justify-content-center gap-2 pb-4">
                <button type="button" class="btn btn-sm btn-secondary px-3" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-sm btn-danger px-3" id="confirmDeleteBtn">Delete</button>
            </div>
        </div>
    </div>
</div>

<script>
    let currentDeleteId = null;
    function showDeleteModal(id) {
        currentDeleteId = id;
        new bootstrap.Modal(document.getElementById('deleteModal')).show();
    }
    document.getElementById('confirmDeleteBtn')?.addEventListener('click', function() {
        if (currentDeleteId) document.getElementById('delete-form-' + currentDeleteId).submit();
    });
</script>

<style>
    .bg-gradient-primary {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }

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
    }
    .sticky-top {
        position: sticky;
        top: 20px;
        z-index: 100;
    }
    .card {
        border-radius: 12px;
    }
    @media (max-width: 768px) {
        .sticky-top { position: relative; top: 0; margin-bottom: 1rem; }
        h1 { font-size: 1.5rem !important; }
    }
</style>
@endsection
