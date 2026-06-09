@extends('layouts.app')

@section('title', 'Teacher Dashboard')

@section('content')
<div class="dashboard-container">
    <div class="dashboard-layout">
        <!-- Sidebar - Only Navigation -->
        <aside class="dashboard-sidebar">
            <div class="sidebar-header">
                <div class="avatar-circle">
                    {{ strtoupper(substr(Auth::user()->full_name, 0, 1)) }}
                </div>
                <h5 class="mt-3 mb-0">{{ Auth::user()->full_name }}</h5>
                <span class="badge role-badge teacher mt-2">
                    <i class="fas fa-chalkboard-user me-1"></i> Teacher
                </span>
            </div>

            <nav class="sidebar-nav">
                <a href="{{ route('teacher.dashboard') }}" class="nav-item active">
                    <i class="fas fa-tachometer-alt"></i> <span>Dashboard</span>
                </a>
                <a href="{{ route('courses.index') }}" class="nav-item">
                    <i class="fas fa-book-open"></i> <span>My Courses</span>
                </a>
                <a href="{{ route('assignments.index') }}" class="nav-item">
                    <i class="fas fa-list-check"></i> <span>Assignments</span>
                </a>
                <a href="{{ route('submissions.index') }}" class="nav-item">
                    <i class="fas fa-paper-plane"></i> <span>Submissions</span>
                </a>
                <a href="{{ route('grades.index') }}" class="nav-item">
                    <i class="fas fa-star"></i> <span>Grading</span>
                </a>
                <a href="{{ route('enrollments.index') }}" class="nav-item">
                    <i class="fas fa-users"></i> <span>Enrollments</span>
                </a>
                <hr class="mx-3 my-2">
                <a href="{{ route('profile.index') }}" class="nav-item">
                    <i class="fas fa-user-circle"></i> <span>Profile</span>
                </a>
                <a href="{{ route('notifications.index') }}" class="nav-item">
                    <i class="fas fa-bell"></i> <span>Notifications</span>
                    @if(isset($unreadNotifications) && $unreadNotifications > 0)
                        <span class="badge bg-danger">{{ $unreadNotifications }}</span>
                    @endif
                </a>
                <a href="{{ route('logout') }}" class="nav-item text-danger" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    <i class="fas fa-sign-out-alt"></i> <span>Logout</span>
                </a>
            </nav>

            <!-- Sidebar Footer Stats -->
            <div class="sidebar-footer">
                <hr class="mx-3">
                <div class="px-3 pb-3">
                    <div class="small text-muted">
                        <div class="d-flex justify-content-between mb-2">
                            <span><i class="fas fa-chalkboard-user me-1"></i> Role:</span>
                            <span class="fw-bold text-primary">Teacher</span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span><i class="fas fa-calendar-week me-1"></i> Since:</span>
                            <span class="fw-bold">{{ Auth::user()->created_at->format('M Y') }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="dashboard-main">
            <!-- Welcome Banner -->
            <div class="welcome-banner mb-4">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <h2 class="fw-bold mb-2">
                            <i class="fas fa-hand-peace me-2"></i> Welcome back, {{ Auth::user()->full_name }}!
                        </h2>
                        <p class="mb-0">Manage your courses, assignments, and track student progress from your teacher dashboard.</p>
                    </div>
                    <div class="col-md-4 text-center">
                        <i class="fas fa-chalkboard-user fa-3x opacity-50"></i>
                    </div>
                </div>
            </div>

            <!-- Stats Cards -->
            <div class="stats-grid mb-4">
                <div class="stat-card">
                    <div class="stat-icon bg-primary"><i class="fas fa-book-open"></i></div>
                    <div><h3 class="stat-number">{{ $myCourses->count() }}</h3><p class="stat-label">My Courses</p></div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon bg-success"><i class="fas fa-list-check"></i></div>
                    <div><h3 class="stat-number">{{ $totalAssignments }}</h3><p class="stat-label">Assignments</p></div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon bg-info"><i class="fas fa-paper-plane"></i></div>
                    <div><h3 class="stat-number">{{ $totalSubmissions }}</h3><p class="stat-label">Submissions</p></div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon bg-warning"><i class="fas fa-hourglass-half"></i></div>
                    <div><h3 class="stat-number">{{ $pendingGrading }}</h3><p class="stat-label">Pending Grading</p></div>
                </div>
            </div>

            <!-- My Courses Section -->
            <div class="section-card mb-4">
                <div class="section-header">
                    <h5><i class="fas fa-book-open me-2 text-primary"></i> My Courses</h5>
                    <a href="{{ route('courses.create') }}" class="btn btn-sm btn-primary">
                        <i class="fas fa-plus me-1"></i> New Course
                    </a>
                </div>
                <div class="row g-3">
                    @forelse($myCourses as $course)
                        <div class="col-md-6 col-lg-4">
                            <div class="course-card">
                                <div class="d-flex justify-content-between mb-2">
                                    <div class="course-icon">
                                        <i class="fas fa-school"></i>
                                    </div>
                                    <span class="badge bg-success rounded-pill">
                                        <i class="fas fa-users me-1"></i> {{ $course->enrollments_count ?? $course->enrollments->count() }} students
                                    </span>
                                </div>
                                <h6 class="fw-bold mb-1">{{ $course->course_name }}</h6>
                                <small class="text-muted d-block mb-2">{{ $course->course_code }}</small>
                                <p class="small text-muted mb-2">{{ Str::limit($course->description ?? 'No description', 60) }}</p>
                                <div class="d-flex gap-2 mt-2">
                                    <a href="{{ route('courses.show', $course->course_id) }}" class="btn btn-sm btn-outline-primary flex-grow-1">
                                        <i class="fas fa-eye me-1"></i> View
                                    </a>
                                    <a href="{{ route('assignments.index', ['course_id' => $course->course_id]) }}" class="btn btn-sm btn-outline-success">
                                        <i class="fas fa-tasks me-1"></i> Tasks
                                    </a>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-12 text-center py-4">
                            <i class="fas fa-book fa-3x text-muted mb-2 opacity-25"></i>
                            <p class="text-muted">No courses yet</p>
                            <a href="{{ route('courses.create') }}" class="btn btn-primary btn-sm">
                                <i class="fas fa-plus me-1"></i> Create Course
                            </a>
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Recent Activity Row -->
            <div class="row g-4 mb-4">
                <div class="col-lg-6">
                    <div class="section-card">
                        <div class="section-header">
                            <h5><i class="fas fa-clock me-2 text-info"></i> Recent Assignments</h5>
                            <a href="{{ route('assignments.index') }}" class="btn btn-sm btn-outline-primary">View All</a>
                        </div>
                        @forelse($myAssignments as $assignment)
                            <div class="activity-item">
                                <div>
                                    <strong>{{ $assignment->title }}</strong>
                                    <div class="small text-muted">
                                        <i class="fas fa-calendar-alt me-1"></i> Due: {{ $assignment->due_date->format('M d, Y') }}
                                    </div>
                                </div>
                                <div class="text-end">
                                    <span class="badge bg-info rounded-pill">
                                        <i class="fas fa-paper-plane me-1"></i> {{ $assignment->submissions_count ?? $assignment->submissions->count() }} subs
                                    </span>
                                    <div class="small text-warning">
                                        {{ $assignment->submissions()->whereDoesntHave('grade')->count() }} pending
                                    </div>
                                </div>
                            </div>
                        @empty
                            <p class="text-muted text-center py-3">No assignments created yet</p>
                        @endforelse
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="section-card">
                        <div class="section-header">
                            <h5><i class="fas fa-paper-plane me-2 text-warning"></i> Recent Submissions</h5>
                            <a href="{{ route('submissions.index') }}" class="btn btn-sm btn-outline-primary">View All</a>
                        </div>
                        @forelse($recentSubmissions as $submission)
                            <div class="activity-item">
                                <div>
                                    <strong>{{ $submission->assignment->title ?? 'N/A' }}</strong>
                                    <div class="small text-muted">
                                        <i class="fas fa-user-graduate me-1"></i> {{ $submission->student->full_name ?? 'Student' }} • {{ $submission->created_at->diffForHumans() }}
                                    </div>
                                </div>
                                @if($submission->grade)
                                    <span class="badge bg-success rounded-pill">
                                        <i class="fas fa-check-circle me-1"></i> Graded
                                    </span>
                                @else
                                    <a href="{{ route('grades.create') }}?submission_id={{ $submission->submission_id }}" class="btn btn-sm btn-primary rounded-pill">
                                        <i class="fas fa-star me-1"></i> Grade
                                    </a>
                                @endif
                            </div>
                        @empty
                            <p class="text-muted text-center py-3">No submissions yet</p>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Upcoming Deadlines -->
            <div class="section-card">
                <div class="section-header">
                    <h5><i class="fas fa-calendar-alt me-2 text-danger"></i> Upcoming Deadlines</h5>
                    <a href="{{ route('assignments.index') }}" class="btn btn-sm btn-outline-primary">Manage</a>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th><i class="fas fa-file-alt me-1"></i> Assignment</th>
                                <th><i class="fas fa-book me-1"></i> Course</th>
                                <th><i class="fas fa-calendar me-1"></i> Due Date</th>
                                <th><i class="fas fa-paper-plane me-1"></i> Submissions</th>
                                <th><i class="fas fa-hourglass-half me-1"></i> Pending</th>
                                <th><i class="fas fa-cog me-1"></i> Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($upcomingDeadlines as $assignment)
                                @php
                                    $daysLeft = now()->diffInDays($assignment->due_date, false);
                                    $roundedDays = floor(abs($daysLeft));

                                    if ($daysLeft < 0) {
                                        $rowClass = 'table-danger';
                                        $statusText = 'Overdue';
                                        $badgeClass = 'danger';
                                    } elseif ($daysLeft == 0) {
                                        $rowClass = 'table-danger';
                                        $statusText = 'Due today';
                                        $badgeClass = 'danger';
                                    } elseif ($daysLeft == 1) {
                                        $rowClass = 'table-warning';
                                        $statusText = 'Tomorrow';
                                        $badgeClass = 'warning';
                                    } elseif ($daysLeft <= 3) {
                                        $rowClass = 'table-danger';
                                        $statusText = $roundedDays . ' days left';
                                        $badgeClass = 'danger';
                                    } elseif ($daysLeft <= 7) {
                                        $rowClass = 'table-warning';
                                        $statusText = $roundedDays . ' days left';
                                        $badgeClass = 'warning';
                                    } else {
                                        $rowClass = '';
                                        $statusText = $roundedDays . ' days left';
                                        $badgeClass = 'success';
                                    }
                                @endphp
                                <tr class="{{ $rowClass }}">
                                    <td class="fw-bold">{{ $assignment->title }}</td>
                                    <td>{{ $assignment->course->course_name ?? 'N/A' }}</td>
                                    <td>
                                        <i class="fas fa-calendar-alt me-1"></i> {{ $assignment->due_date->format('M d, H:i') }}
                                        <br>
                                        <span class="badge bg-{{ $badgeClass }} mt-1">{{ $statusText }}</span>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-info rounded-pill">{{ $assignment->submissions_count ?? $assignment->submissions->count() }}</span>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-warning rounded-pill">{{ $assignment->submissions()->whereDoesntHave('grade')->count() }}</span>
                                    </td>
                                    <td>
                                        <a href="{{ route('grades.by-assignment', $assignment->assignment_id) }}" class="btn btn-sm btn-primary rounded-pill">
                                            <i class="fas fa-star me-1"></i> Grade
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-4 text-muted">
                                        <i class="fas fa-calendar-check fa-2x mb-2 opacity-25 d-block"></i>
                                        No upcoming deadlines
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>
</div>

<style>
.dashboard-container {
    min-height: calc(100vh - 56px);
}

.dashboard-layout {
    display: flex;
    background: #f5f7fb;
    min-height: calc(100vh - 56px);
}

/* Sidebar */
.dashboard-sidebar {
    width: 280px;
    background: white;
    display: flex;
    flex-direction: column;
    position: sticky;
    top: 56px;
    height: calc(100vh - 56px);
    border-right: 1px solid #eef2f7;
    box-shadow: 2px 0 8px rgba(0,0,0,0.02);
}

.sidebar-header {
    padding: 25px 20px;
    text-align: center;
    border-bottom: 1px solid #eef2f7;
}

.avatar-circle {
    width: 80px;
    height: 80px;
    background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 32px;
    font-weight: bold;
    color: white;
    margin: 0 auto;
    box-shadow: 0 4px 12px rgba(79, 172, 254, 0.3);
}

.role-badge {
    padding: 5px 16px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
    display: inline-flex;
    align-items: center;
    gap: 5px;
}
.role-badge.teacher { background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); color: white; }

.sidebar-nav {
    flex: 1;
    padding: 20px 0;
}

.nav-item {
    display: flex;
    align-items: center;
    padding: 12px 20px;
    margin: 4px 12px;
    color: #5a6e8a;
    text-decoration: none;
    border-radius: 12px;
    transition: all 0.25s ease;
    font-size: 0.9rem;
}

.nav-item i {
    width: 24px;
    font-size: 1.1rem;
    margin-right: 12px;
}

.nav-item:hover {
    background: #f0f7ff;
    color: #4facfe;
    transform: translateX(4px);
}

.nav-item.active {
    background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
    color: white;
    box-shadow: 0 4px 12px rgba(79, 172, 254, 0.3);
}

.nav-item .badge {
    margin-left: auto;
    background: #dc3545;
}

.sidebar-footer {
    border-top: 1px solid #eef2f7;
    padding: 12px 0;
}

/* Main Content */
.dashboard-main {
    flex: 1;
    padding: 25px 30px;
    overflow-y: auto;
}

/* Welcome Banner */
.welcome-banner {
    background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
    border-radius: 20px;
    padding: 25px 30px;
    color: white;
}

/* Stats Grid */
.stats-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 20px;
}

.stat-card {
    background: white;
    border-radius: 20px;
    padding: 20px;
    display: flex;
    align-items: center;
    gap: 15px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.04);
    transition: all 0.3s ease;
}

.stat-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 20px rgba(0,0,0,0.08);
}

.stat-icon {
    width: 55px;
    height: 55px;
    border-radius: 14px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 24px;
    color: white;
}

.stat-icon.bg-primary { background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); }
.stat-icon.bg-success { background: #28a745; }
.stat-icon.bg-info { background: #17a2b8; }
.stat-icon.bg-warning { background: #ffc107; }

.stat-number {
    font-size: 28px;
    font-weight: 700;
    margin-bottom: 4px;
    color: #2c3e50;
}

.stat-label {
    color: #7f8c8d;
    font-size: 12px;
    margin-bottom: 0;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

/* Section Card */
.section-card {
    background: white;
    border-radius: 20px;
    padding: 20px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.04);
}

.section-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
    padding-bottom: 12px;
    border-bottom: 1px solid #eef2f7;
}

.section-header h5 {
    margin-bottom: 0;
    font-weight: 600;
    font-size: 1rem;
}

/* Course Card */
.course-card {
    background: #f8fafc;
    border-radius: 14px;
    padding: 15px;
    transition: all 0.3s ease;
    border: 1px solid transparent;
}

.course-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 20px rgba(0,0,0,0.08);
    border-color: #e0e7ff;
    background: white;
}

.course-icon {
    width: 45px;
    height: 45px;
    background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.2rem;
}

/* Activity Item */
.activity-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 12px 0;
    border-bottom: 1px solid #f0f0f0;
}

.activity-item:last-child {
    border-bottom: none;
}

/* Table Styles */
.table th {
    font-size: 0.75rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    font-weight: 600;
    color: #6c757d;
    padding: 12px 8px;
}

.table td {
    vertical-align: middle;
    padding: 12px 8px;
}

/* Responsive */
@media (max-width: 992px) {
    .dashboard-sidebar { width: 80px; }
    .sidebar-header h5, .sidebar-header .role-badge, .nav-item span, .sidebar-footer { display: none; }
    .nav-item i { margin-right: 0; }
    .nav-item { justify-content: center; }
    .stats-grid { grid-template-columns: repeat(2, 1fr); }
}

@media (max-width: 768px) {
    .dashboard-layout { flex-direction: column; }
    .dashboard-sidebar { width: 100%; height: auto; position: relative; top: 0; flex-direction: row; flex-wrap: wrap; padding: 10px; align-items: center; }
    .sidebar-header { display: none; }
    .sidebar-nav { display: flex; flex-wrap: wrap; justify-content: center; padding: 0; }
    .nav-item { padding: 8px 12px; margin: 4px; border-radius: 10px; }
    .nav-item span { display: inline; margin-left: 8px; }
    .dashboard-main { padding: 20px; }
    .stats-grid { grid-template-columns: repeat(2, 1fr); gap: 15px; }
    .welcome-banner h2 { font-size: 1.3rem; }
    .welcome-banner { padding: 20px; }
}
</style>

<form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
    @csrf
</form>
@endsection
