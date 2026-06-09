@extends('layouts.app')

@section('title', 'My Submissions')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card shadow-lg border-0 rounded-4">
                <div class="card-header bg-gradient-primary text-white rounded-top-4" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h4 class="mb-0">
                                <i class="fas fa-paper-plane me-2"></i> My Submissions
                            </h4>
                            <p class="mb-0 mt-1 opacity-75 small">Track all your assignment submissions</p>
                        </div>
                        <div>
                            <span class="badge bg-light text-dark px-3 py-2 rounded-pill">
                                <i class="fas fa-tasks me-1"></i> {{ $submissions->total() }} Total
                            </span>
                        </div>
                    </div>
                </div>

                <div class="card-body p-4">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show">
                            <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show">
                            <i class="fas fa-exclamation-circle me-2"></i> {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if($submissions->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>Assignment</th>
                                        <th>Course</th>
                                        <th>Submitted Date</th>
                                        <th>Marks</th>
                                        <th>Grade</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($submissions as $submission)
                                    <tr>
                                        <td>
                                            <a href="{{ route('submissions.show', $submission->submission_id) }}" class="text-decoration-none fw-semibold">
                                                {{ $submission->assignment->title ?? 'N/A' }}
                                            </a>
                                        </td>
                                        <td>
                                            <i class="fas fa-book text-primary me-1"></i>
                                            {{ $submission->assignment->course->course_name ?? 'N/A' }}
                                        </td>
                                        <td>
                                            <i class="fas fa-calendar-alt text-muted me-1"></i>
                                            {{ $submission->created_at->format('M d, Y H:i') }}
                                            @if($submission->is_late)
                                                <span class="badge bg-danger ms-1">Late</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($submission->grade)
                                                <span class="fw-bold text-success">
                                                    {{ $submission->grade->marks_obtained }}/{{ $submission->assignment->total_marks ?? 100 }}
                                                </span>
                                            @else
                                                <span class="text-muted">—</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($submission->grade)
                                                <span class="badge bg-success">{{ $submission->grade->grade }}</span>
                                            @else
                                                <span class="badge bg-warning">Pending</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($submission->grade)
                                                <span class="badge bg-success">Graded</span>
                                            @else
                                                <span class="badge bg-info">Submitted</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="btn-group">
                                                <a href="{{ route('submissions.show', $submission->submission_id) }}" class="btn btn-sm btn-outline-info" title="View">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                @if(!$submission->grade)
                                                    <a href="{{ route('submissions.edit', $submission->submission_id) }}" class="btn btn-sm btn-outline-warning" title="Edit">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <button type="button" class="btn btn-sm btn-outline-danger" title="Delete"
                                                            onclick="confirmDelete({{ $submission->submission_id }})">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                    <form id="delete-form-{{ $submission->submission_id }}"
                                                          action="{{ route('submissions.destroy', $submission->submission_id) }}"
                                                          method="POST" style="display: none;">
                                                        @csrf
                                                        @method('DELETE')
                                                    </form>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div class="mt-4">
                            {{ $submissions->links() }}
                        </div>
                    @else
                        <div class="text-center text-muted py-5">
                            <i class="fas fa-inbox fa-3x mb-3"></i>
                            <h5>No Submissions Yet</h5>
                            <p>You haven't submitted any assignments yet.</p>
                            <a href="{{ route('assignments.index') }}" class="btn btn-primary">
                                <i class="fas fa-plus me-2"></i> Submit Assignment
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function confirmDelete(id) {
        if (confirm('⚠️ Are you sure you want to delete this submission?\n\nThis action cannot be undone.')) {
            document.getElementById('delete-form-' + id).submit();
        }
    }
</script>

<style>
    .bg-gradient-primary {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }
    .table-hover tbody tr:hover {
        background-color: rgba(102, 126, 234, 0.05);
    }
    .btn-group .btn {
        margin: 0 2px;
        border-radius: 6px;
        transition: all 0.2s ease;
    }
    .btn-group .btn:hover {
        transform: translateY(-2px);
    }

    @media (max-width: 768px) {
        .table-responsive {
            font-size: 0.8rem;
        }
        .btn-group .btn {
            padding: 0.25rem 0.5rem;
        }
    }
</style>
@endsection
