@extends('layouts.app')

@section('title', 'Submission History')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card shadow-lg border-0 rounded-4">
                <div class="card-header bg-gradient-primary text-white rounded-top-4" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h4 class="mb-0">
                                <i class="fas fa-history me-2"></i> Submission History
                            </h4>
                            <p class="mb-0 mt-1 opacity-75 small">Track all submission activities and changes</p>
                        </div>
                        <div>
                            <span class="badge bg-light text-dark px-3 py-2 rounded-pill">
                                <i class="fas fa-clock me-1"></i> {{ $histories->total() }} Records
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

                    <!-- Filter Form -->
                    <form method="GET" action="{{ route('submission-history.index') }}" class="row g-3 mb-4">
                        <div class="col-md-3">
                            <label class="form-label fw-semibold small">Filter by Student</label>
                            <select name="student_id" class="form-select">
                                <option value="">All Students</option>
                                @foreach($students ?? [] as $student)
                                    <option value="{{ $student->user_id }}" {{ request('student_id') == $student->user_id ? 'selected' : '' }}>
                                        {{ $student->full_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-semibold small">Filter by Action</label>
                            <select name="action" class="form-select">
                                <option value="">All Actions</option>
                                <option value="Submitted" {{ request('action') == 'Submitted' ? 'selected' : '' }}>Submitted</option>
                                <option value="Updated" {{ request('action') == 'Updated' ? 'selected' : '' }}>Updated</option>
                                <option value="Graded" {{ request('action') == 'Graded' ? 'selected' : '' }}>Graded</option>
                                <option value="Resubmitted" {{ request('action') == 'Resubmitted' ? 'selected' : '' }}>Resubmitted</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-semibold small">Start Date</label>
                            <input type="date" name="start_date" class="form-control" value="{{ request('start_date') }}">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-semibold small">End Date</label>
                            <input type="date" name="end_date" class="form-control" value="{{ request('end_date') }}">
                        </div>
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-filter me-2"></i> Apply Filters
                            </button>
                            <a href="{{ route('submission-history.index') }}" class="btn btn-secondary">
                                <i class="fas fa-undo me-2"></i> Reset
                            </a>
                        </div>
                    </form>

                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>ID</th>
                                    <th>Assignment</th>
                                    <th>Student</th>
                                    <th>Action</th>
                                    <th>Performed By</th>
                                    <th>Performed At</th>
                                    <th>Details</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($histories as $history)
                                <tr>
                                    <td>{{ $history->history_id }}</td>
                                    <td>
                                        <a href="{{ route('assignments.show', $history->submission->assignment->assignment_id ?? 0) }}" class="text-decoration-none">
                                            {{ Str::limit($history->submission->assignment->title ?? 'N/A', 40) }}
                                        </a>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-sm me-2">
                                                <div class="rounded-circle bg-primary bg-opacity-10 p-2 text-center" style="width: 32px; height: 32px;">
                                                    {{ strtoupper(substr($history->submission->student->full_name ?? 'S', 0, 1)) }}
                                                </div>
                                            </div>
                                            {{ $history->submission->student->full_name ?? 'N/A' }}
                                        </div>
                                    </td>
                                    <td>
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
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-sm me-2">
                                                <div class="rounded-circle bg-secondary bg-opacity-10 p-2 text-center" style="width: 32px; height: 32px;">
                                                    {{ strtoupper(substr($history->performer->full_name ?? 'U', 0, 1)) }}
                                                </div>
                                            </div>
                                            {{ $history->performer->full_name ?? 'N/A' }}
                                        </div>
                                    </td>
                                    <td>
                                        <i class="fas fa-calendar-alt text-muted me-1"></i>
                                        {{ \Carbon\Carbon::parse($history->performed_at)->format('M d, Y H:i') }}
                                        <br>
                                        <small class="text-muted">{{ \Carbon\Carbon::parse($history->performed_at)->diffForHumans() }}</small>
                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-sm btn-outline-info" data-bs-toggle="modal" data-bs-target="#historyModal{{ $history->history_id }}">
                                            <i class="fas fa-info-circle"></i>
                                        </button>
                                    </td>
                                </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center text-muted py-5">
                                            <i class="fas fa-history fa-3x mb-3 d-block"></i>
                                            <h5>No Submission History Found</h5>
                                            <p>No submission activities recorded yet.</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="mt-4">
                        {{ $histories->withQueryString()->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- History Detail Modals -->
@foreach($histories as $history)
<div class="modal fade" id="historyModal{{ $history->history_id }}" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-gradient-primary text-white">
                <h5 class="modal-title">
                    <i class="fas fa-info-circle me-2"></i> Submission History Details
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row mb-3">
                    <div class="col-md-4 fw-bold">History ID:</div>
                    <div class="col-md-8">{{ $history->history_id }}</div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-4 fw-bold">Assignment:</div>
                    <div class="col-md-8">{{ $history->submission->assignment->title ?? 'N/A' }}</div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-4 fw-bold">Student:</div>
                    <div class="col-md-8">{{ $history->submission->student->full_name ?? 'N/A' }}</div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-4 fw-bold">Action:</div>
                    <div class="col-md-8">{{ $history->action }}</div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-4 fw-bold">Performed By:</div>
                    <div class="col-md-8">{{ $history->performer->full_name ?? 'N/A' }}</div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-4 fw-bold">Performed At:</div>
                    <div class="col-md-8">{{ \Carbon\Carbon::parse($history->performed_at)->format('F d, Y H:i:s') }}</div>
                </div>
                @if($history->submission->grade)
                <div class="row mb-3">
                    <div class="col-md-4 fw-bold">Grade:</div>
                    <div class="col-md-8">
                        {{ $history->submission->grade->marks_obtained }}/{{ $history->submission->assignment->total_marks ?? 100 }}
                        ({{ $history->submission->grade->grade }})
                    </div>
                </div>
                @endif
                @if($history->submission->is_late)
                <div class="row mb-3">
                    <div class="col-md-4 fw-bold">Late Submission:</div>
                    <div class="col-md-8"><span class="badge bg-danger">Yes</span></div>
                </div>
                @endif
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
@endforeach

<style>
    .bg-gradient-primary {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }
    .table-hover tbody tr:hover {
        background-color: rgba(102, 126, 234, 0.05);
    }
    .avatar-sm {
        width: 32px;
        height: 32px;
    }
    .badge {
        font-weight: 500;
    }
</style>
@endsection
