@extends('layouts.app')

@section('title', 'My Grades')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card shadow-lg border-0 rounded-4">
                <div class="card-header bg-gradient-success text-white rounded-top-4" style="background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h4 class="mb-0">
                                <i class="fas fa-chart-line me-2"></i> My Grades
                            </h4>
                            <p class="mb-0 mt-1 opacity-75 small">View your academic performance</p>
                        </div>
                        <div>
                            <span class="badge bg-light text-dark px-3 py-2 rounded-pill">
                                <i class="fas fa-star me-1"></i> GPA: {{ number_format($averageGpa ?? 0, 2) }}
                            </span>
                        </div>
                    </div>
                </div>

                <div class="card-body p-4">
                    @if($grades->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>Assignment</th>
                                        <th>Course</th>
                                        <th>Marks Obtained</th>
                                        <th>Total Marks</th>
                                        <th>Percentage</th>
                                        <th>Grade</th>
                                        <th>Graded Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($grades as $grade)
                                        @php
                                            $totalMarks = $grade->submission->assignment->total_marks ?? 100;
                                            $percentage = ($grade->marks_obtained / $totalMarks) * 100;
                                            $badgeClass = match($grade->grade) {
                                                'A+', 'A' => 'success',
                                                'B' => 'info',
                                                'C' => 'warning',
                                                'D' => 'warning',
                                                default => 'danger'
                                            };
                                        @endphp
                                        <tr>
                                            <td>
                                                <a href="{{ route('assignments.show', $grade->submission->assignment->assignment_id) }}" class="text-decoration-none">
                                                    {{ $grade->submission->assignment->title ?? 'N/A' }}
                                                </a>
                                            </td>
                                            <td>{{ $grade->submission->assignment->course->course_name ?? 'N/A' }}</td>
                                            <td>
                                                <span class="fw-bold">{{ $grade->marks_obtained }}</span>
                                            </td>
                                            <td>{{ $totalMarks }}</td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="progress flex-grow-1 me-2" style="height: 6px;">
                                                        <div class="progress-bar bg-{{ $badgeClass }}" style="width: {{ $percentage }}%"></div>
                                                    </div>
                                                    <span class="small">{{ number_format($percentage, 1) }}%</span>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="badge bg-{{ $badgeClass }} px-3 py-2">
                                                    {{ $grade->grade }}
                                                </span>
                                            </td>
                                            <td>
                                                <i class="fas fa-calendar-alt text-muted me-1"></i>
                                                {{ $grade->graded_at ? \Carbon\Carbon::parse($grade->graded_at)->format('M d, Y') : 'N/A' }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Summary Statistics -->
                        <div class="row mt-4 g-3">
                            <div class="col-md-3">
                                <div class="card bg-light border-0 text-center p-3">
                                    <h3 class="mb-0 text-primary">{{ $grades->count() }}</h3>
                                    <small class="text-muted">Total Grades</small>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card bg-light border-0 text-center p-3">
                                    <h3 class="mb-0 text-success">{{ number_format($grades->avg('marks_obtained'), 1) }}</h3>
                                    <small class="text-muted">Average Marks</small>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card bg-light border-0 text-center p-3">
                                    <h3 class="mb-0 text-warning">{{ $grades->max('marks_obtained') }}</h3>
                                    <small class="text-muted">Highest Score</small>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card bg-light border-0 text-center p-3">
                                    @php
                                        $passed = $grades->filter(fn($g) => !in_array($g->grade, ['F']))->count();
                                        $passRate = ($passed / $grades->count()) * 100;
                                    @endphp
                                    <h3 class="mb-0 text-info">{{ round($passRate) }}%</h3>
                                    <small class="text-muted">Pass Rate</small>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="text-center text-muted py-5">
                            <i class="fas fa-chart-simple fa-3x mb-3"></i>
                            <h5>No Grades Available</h5>
                            <p>Your grades will appear here once teachers grade your submissions.</p>
                            <a href="{{ route('assignments.index') }}" class="btn btn-primary">
                                <i class="fas fa-tasks me-2"></i> Browse Assignments
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .bg-gradient-success {
        background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);
    }
    .progress-bar {
        transition: width 0.6s ease;
    }
</style>
@endsection
