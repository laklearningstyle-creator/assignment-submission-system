@extends('layouts.app')

@section('title', 'Course Students')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card shadow-lg border-0 rounded-4">
                <div class="card-header bg-gradient-primary text-white rounded-top-4" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="mb-0">
                            <i class="fas fa-users me-2"></i> Students Enrolled in: {{ $course->course_name }}
                        </h4>
                        <a href="{{ route('courses.show', $course->course_id) }}" class="btn btn-light btn-sm">
                            <i class="fas fa-arrow-left me-1"></i> Back to Course
                        </a>
                    </div>
                </div>

                <div class="card-body p-4">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show">
                            <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>ID</th>
                                    <th>Student Name</th>
                                    <th>Email</th>
                                    <th>Phone</th>
                                    <th>Enrolled Date</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($students as $enrollment)
                                <tr>
                                    <td>{{ $enrollment->student->user_id }}</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-sm me-2">
                                                <div class="rounded-circle bg-primary bg-opacity-10 p-2 text-center" style="width: 32px; height: 32px;">
                                                    {{ strtoupper(substr($enrollment->student->full_name, 0, 1)) }}
                                                </div>
                                            </div>
                                            {{ $enrollment->student->full_name }}
                                        </div>
                                    </td>
                                    <td>
                                        <i class="fas fa-envelope text-muted me-1"></i>
                                        {{ $enrollment->student->email }}
                                    </td>
                                    <td>
                                        @if($enrollment->student->phone)
                                            <i class="fas fa-phone text-muted me-1"></i>
                                            {{ $enrollment->student->phone }}
                                        @else
                                            <span class="text-muted">—</span>
                                        @endif
                                    </td>
                                    <td>
                                        <i class="fas fa-calendar-alt text-muted me-1"></i>
                                        {{ $enrollment->enrolled_at ? \Carbon\Carbon::parse($enrollment->enrolled_at)->format('M d, Y') : 'N/A' }}
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('users.show', $enrollment->student->user_id) }}" class="btn btn-sm btn-outline-info" title="View Student">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <button type="button" class="btn btn-sm btn-outline-danger" title="Remove from Course"
                                                    onclick="confirmRemove({{ $enrollment->enrollment_id }}, '{{ $enrollment->student->full_name }}')">
                                                <i class="fas fa-user-minus"></i>
                                            </button>
                                            <form id="remove-form-{{ $enrollment->enrollment_id }}"
                                                  action="{{ route('enrollments.destroy', $enrollment->enrollment_id) }}"
                                                  method="POST" style="display: none;">
                                                @csrf
                                                @method('DELETE')
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center text-muted py-4">
                                            <i class="fas fa-users fa-2x mb-2 d-block"></i>
                                            No students enrolled in this course yet.
                                            <br>
                                            <a href="{{ route('enrollments.create') }}?course_id={{ $course->course_id }}" class="btn btn-sm btn-primary mt-2">
                                                <i class="fas fa-plus me-1"></i> Enroll Student
                                            </a>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="mt-4">
                        {{ $students->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function confirmRemove(id, studentName) {
        if (confirm('Are you sure you want to remove ' + studentName + ' from this course?')) {
            document.getElementById('remove-form-' + id).submit();
        }
    }
</script>

<style>
    .avatar-sm {
        width: 32px;
        height: 32px;
    }
    .table-hover tbody tr:hover {
        background-color: rgba(102, 126, 234, 0.05);
        transition: all 0.3s ease;
    }
    .btn-group .btn {
        margin: 0 2px;
        border-radius: 6px;
    }
</style>
@endsection
