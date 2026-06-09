@extends('layouts.app')

@section('title', 'Grades Management')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card shadow-lg border-0 rounded-4">
                <div class="card-header bg-gradient-primary text-white rounded-top-4 d-flex justify-content-between align-items-center" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                    <div>
                        <h5 class="mb-0">
                            <i class="fas fa-chart-line me-2"></i> Grades Management
                        </h5>
                        <p class="mb-0 mt-1 small opacity-75">Manage and track student grades</p>
                    </div>
                    <a href="{{ route('grades.create') }}" class="btn btn-light btn-sm">
                        <i class="fas fa-plus me-1"></i> Add Grade
                    </a>
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

                    <!-- Filter Bar (Optional Enhancement) -->
                    <div class="row g-3 mb-4">
                        <div class="col-md-4">
                            <label class="form-label small text-muted">Filter by Student</label>
                            <select id="filterStudent" class="form-select form-select-sm bg-light border-0">
                                <option value="">All Students</option>
                                @php
                                    $uniqueStudents = $grades->unique(function($item) {
                                        return $item->submission->student->user_id ?? null;
                                    });
                                @endphp
                                @foreach($uniqueStudents as $grade)
                                    @if($grade->submission && $grade->submission->student)
                                        <option value="{{ $grade->submission->student->full_name }}">
                                            {{ $grade->submission->student->full_name }}
                                        </option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small text-muted">Filter by Grade</label>
                            <select id="filterGrade" class="form-select form-select-sm bg-light border-0">
                                <option value="">All Grades</option>
                                <option value="A+">A+</option>
                                <option value="A">A</option>
                                <option value="B">B</option>
                                <option value="C">C</option>
                                <option value="D">D</option>
                                <option value="F">F</option>
                            </select>
                        </div>
                        <div class="col-md-4 d-flex align-items-end">
                            <button id="resetFilter" class="btn btn-sm btn-outline-secondary w-100">
                                <i class="fas fa-undo me-1"></i> Reset Filters
                            </button>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-hover align-middle" id="gradesTable">
                            <thead class="table-light">
                                <tr>
                                    <th>ID</th>
                                    <th>Student</th>
                                    <th>Assignment</th>
                                    <th>Course</th>
                                    <th class="text-center">Marks</th>
                                    <th class="text-center">Grade</th>
                                    <th>Graded By</th>
                                    <th>Date</th>
                                    <th class="text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($grades as $grade)
                                <tr>
                                    <td class="fw-bold">{{ $grade->grade_id }}</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-sm me-2">
                                                <div class="rounded-circle bg-primary bg-opacity-10 p-2 text-center" style="width: 32px; height: 32px;">
                                                    <i class="fas fa-user-graduate text-primary fa-xs"></i>
                                                </div>
                                            </div>
                                            <span class="student-name">{{ $grade->submission->student->full_name ?? 'N/A' }}</span>
                                        </div>
                                    </td>
                                    <td>{{ $grade->submission->assignment->title ?? 'N/A' }}</td>
                                    <td>{{ $grade->submission->assignment->course->course_name ?? 'N/A' }}</td>
                                    <td class="text-center">
                                        <span class="fw-bold">{{ $grade->marks_obtained }}</span>
                                        <small class="text-muted">/{{ $grade->submission->assignment->total_marks ?? 100 }}</small>
                                    </td>
                                    <td class="text-center grade-cell">
                                        @php
                                            $gradeLetter = $grade->grade;
                                            $badgeClass = match($gradeLetter) {
                                                'A+', 'A' => 'success',
                                                'B' => 'info',
                                                'C' => 'warning',
                                                'D' => 'warning',
                                                default => 'danger'
                                            };
                                        @endphp
                                        <span class="badge bg-{{ $badgeClass }} px-3 py-2 grade-badge">{{ $grade->grade }}</span>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-sm me-2">
                                                <div class="rounded-circle bg-secondary bg-opacity-10 p-2 text-center" style="width: 32px; height: 32px;">
                                                    <i class="fas fa-chalkboard-user text-secondary fa-xs"></i>
                                                </div>
                                            </div>
                                            {{ $grade->grader->full_name ?? 'N/A' }}
                                        </div>
                                    </td>
                                    <td>
                                        <i class="fas fa-calendar-alt text-muted me-1"></i>
                                        {{ $grade->created_at->format('M d, Y') }}
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('grades.show', $grade->grade_id) }}" class="btn btn-sm btn-outline-info rounded-3 me-1" title="View">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('grades.edit', $grade->grade_id) }}" class="btn btn-sm btn-outline-warning rounded-3 me-1" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <button type="button" class="btn btn-sm btn-outline-danger rounded-3" title="Delete"
                                                    onclick="confirmDelete({{ $grade->grade_id }})">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                            <form id="delete-form-{{ $grade->grade_id }}"
                                                  action="{{ route('grades.destroy', $grade->grade_id) }}"
                                                  method="POST" style="display: none;">
                                                @csrf
                                                @method('DELETE')
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                    <tr>
                                        <td colspan="9" class="text-center text-muted py-5">
                                            <i class="fas fa-chart-simple fa-3x mb-3 d-block"></i>
                                            <h5>No grades found</h5>
                                            <p>Click "Add Grade" to create your first grade entry.</p>
                                            <a href="{{ route('grades.create') }}" class="btn btn-primary mt-2">
                                                <i class="fas fa-plus me-1"></i> Add First Grade
                                            </a>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        {{ $grades->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function confirmDelete(id) {
        if (confirm('Are you sure you want to delete this grade? This action cannot be undone.')) {
            document.getElementById('delete-form-' + id).submit();
        }
    }

    // Filter functionality
    document.addEventListener('DOMContentLoaded', function() {
        const filterStudent = document.getElementById('filterStudent');
        const filterGrade = document.getElementById('filterGrade');
        const resetBtn = document.getElementById('resetFilter');
        const rows = document.querySelectorAll('#gradesTable tbody tr');

        function filterTable() {
            const studentValue = filterStudent?.value.toLowerCase() || '';
            const gradeValue = filterGrade?.value.toLowerCase() || '';

            rows.forEach(row => {
                if (row.querySelector('td')) {
                    const studentName = row.querySelector('.student-name')?.innerText.toLowerCase() || '';
                    const gradeBadge = row.querySelector('.grade-badge')?.innerText.toLowerCase() || '';

                    const matchStudent = !studentValue || studentName.includes(studentValue);
                    const matchGrade = !gradeValue || gradeBadge === gradeValue;

                    row.style.display = (matchStudent && matchGrade) ? '' : 'none';
                }
            });
        }

        if (filterStudent) filterStudent.addEventListener('change', filterTable);
        if (filterGrade) filterGrade.addEventListener('change', filterTable);
        if (resetBtn) {
            resetBtn.addEventListener('click', function() {
                if (filterStudent) filterStudent.value = '';
                if (filterGrade) filterGrade.value = '';
                rows.forEach(row => row.style.display = '');
            });
        }
    });
</script>

<style>
    .table-hover tbody tr:hover {
        background-color: rgba(102, 126, 234, 0.05);
        transition: all 0.3s ease;
    }
    .btn-group .btn {
        transition: all 0.2s ease;
    }
    .btn-group .btn:hover {
        transform: translateY(-2px);
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
