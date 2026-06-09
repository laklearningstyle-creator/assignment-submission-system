@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="dashboard-container">
    <div class="dashboard-layout">
        <!-- Sidebar -->
        <aside class="dashboard-sidebar">
            <div class="sidebar-header">
                <div class="avatar-circle">
                    {{ strtoupper(substr(Auth::user()->full_name, 0, 1)) }}
                </div>
                <h6 class="mt-2 mb-0">{{ Auth::user()->full_name }}</h6>
                <span class="badge role-badge {{ Auth::user()->role->role_name ?? 'student' }} mt-1">
                    {{ ucfirst(Auth::user()->role->role_name ?? 'Student') }}
                </span>
            </div>
            <nav class="sidebar-nav">
                <a href="{{ route('dashboard') }}" class="nav-item active">
                    <i class="fas fa-tachometer-alt"></i><span>Dashboard</span>
                </a>
                <a href="{{ route('courses.index') }}" class="nav-item">
                    <i class="fas fa-book"></i><span>Courses</span>
                </a>
                <a href="{{ route('assignments.index') }}" class="nav-item">
                    <i class="fas fa-tasks"></i><span>Assignments</span>
                </a>
                @if(Auth::user()->role_id == 3)
                <a href="{{ route('student.submissions.index') }}" class="nav-item">
                    <i class="fas fa-paper-plane"></i><span>My Submissions</span>
                </a>
                <a href="{{ route('student.my-grades') }}" class="nav-item">
                    <i class="fas fa-chart-line"></i><span>My Grades</span>
                </a>
                @endif
                @if(Auth::user()->role_id == 2)
                <a href="{{ route('teacher.dashboard') }}" class="nav-item">
                    <i class="fas fa-chalkboard-user"></i><span>Teacher Panel</span>
                </a>
                @endif
                @if(Auth::user()->role_id == 1)
                <a href="{{ route('admin.users.index') }}" class="nav-item">
                    <i class="fas fa-users-cog"></i><span>Admin Panel</span>
                </a>
                @endif
                <a href="{{ route('profile.index') }}" class="nav-item">
                    <i class="fas fa-user-circle"></i><span>Profile</span>
                </a>
                <a href="{{ route('notifications.index') }}" class="nav-item">
                    <i class="fas fa-bell"></i><span>Notifications</span>
                    @if(isset($unreadNotifications) && $unreadNotifications > 0)
                        <span class="badge bg-danger">{{ $unreadNotifications }}</span>
                    @endif
                </a>
                <a href="{{ route('logout') }}" class="nav-item text-danger" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    <i class="fas fa-sign-out-alt"></i><span>Logout</span>
                </a>
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="dashboard-main">
            <!-- Welcome Banner -->
            <div class="welcome-banner">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <h2 class="fw-bold mb-2">Welcome back, {{ Auth::user()->full_name }}! 👋</h2>
                        <p class="mb-0">Track your learning progress</p>
                    </div>
                    <div class="col-md-4 text-center">
                        <i class="fas fa-graduation-cap fa-3x opacity-50"></i>
                    </div>
                </div>
            </div>

            <!-- Stats Cards -->
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-icon bg-primary"><i class="fas fa-book"></i></div>
                    <div><h3 class="stat-number">{{ $totalCourses }}</h3><p class="stat-label">Courses</p></div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon bg-success"><i class="fas fa-tasks"></i></div>
                    <div><h3 class="stat-number">{{ $totalAssignments }}</h3><p class="stat-label">Assignments</p></div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon bg-info"><i class="fas fa-paper-plane"></i></div>
                    <div><h3 class="stat-number">{{ $totalSubmissions }}</h3><p class="stat-label">Submissions</p></div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon bg-warning"><i class="fas fa-chart-line"></i></div>
                    <div><h3 class="stat-number">{{ round($averageScore) }}%</h3><p class="stat-label">Avg Score</p></div>
                </div>
            </div>

            <!-- Recent Activity -->
            <div class="row g-3">
                <div class="col-lg-6">
                    <div class="section-card h-100">
                        <div class="section-header">
                            <h5><i class="fas fa-clock me-2 text-primary"></i> Recent Submissions</h5>
                            <a href="{{ route('submissions.index') }}" class="btn btn-sm btn-outline-primary">View All</a>
                        </div>
                        @forelse($recentSubmissions as $submission)
                            <div class="activity-item">
                                <div>
                                    <strong>{{ $submission->assignment->title ?? 'N/A' }}</strong>
                                    <div class="small text-muted">
                                        {{ $submission->student->full_name ?? 'Student' }} • {{ $submission->created_at->diffForHumans() }}
                                    </div>
                                </div>
                                <span class="badge {{ $submission->grade ? 'bg-success' : 'bg-warning' }}">
                                    {{ $submission->grade ? 'Graded' : 'Pending' }}
                                </span>
                            </div>
                        @empty
                            <p class="text-muted text-center py-3">No recent submissions</p>
                        @endforelse
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="section-card h-100">
                        <div class="section-header">
                            <h5><i class="fas fa-hourglass-half me-2 text-warning"></i> Upcoming Deadlines</h5>
                            <a href="{{ route('assignments.index') }}" class="btn btn-sm btn-outline-primary">View All</a>
                        </div>
                        @forelse($upcomingDeadlines as $assignment)
                            @php
                                $daysLeft = now()->diffInDays($assignment->due_date, false);
                                $roundedDays = floor(abs($daysLeft));

                                if ($daysLeft < 0) {
                                    $displayText = 'Overdue';
                                    $urgency = 'urgent';
                                } elseif ($daysLeft == 0) {
                                    $displayText = 'Due today';
                                    $urgency = 'urgent';
                                } elseif ($daysLeft == 1) {
                                    $displayText = 'Tomorrow';
                                    $urgency = 'warning';
                                } elseif ($daysLeft <= 3) {
                                    $displayText = $roundedDays . ' days left';
                                    $urgency = 'urgent';
                                } elseif ($daysLeft <= 7) {
                                    $displayText = $roundedDays . ' days left';
                                    $urgency = 'warning';
                                } else {
                                    $displayText = $roundedDays . ' days left';
                                    $urgency = 'normal';
                                }
                            @endphp
                            <div class="deadline-item {{ $urgency }}">
                                <div>
                                    <strong>{{ $assignment->title }}</strong>
                                    <div class="small text-muted">{{ $assignment->course->course_name ?? 'N/A' }}</div>
                                </div>
                                <div class="text-end">
                                    <span class="badge bg-{{ $urgency == 'urgent' ? 'danger' : ($urgency == 'warning' ? 'warning' : 'success') }}">
                                        {{ $displayText }}
                                    </span>
                                    <div class="small">{{ $assignment->due_date->format('M d, H:i') }}</div>
                                </div>
                            </div>
                        @empty
                            <p class="text-muted text-center py-3">No upcoming deadlines</p>
                        @endforelse
                    </div>
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
    width: 260px;
    background: white;
    display: flex;
    flex-direction: column;
    position: sticky;
    top: 56px;
    height: calc(100vh - 56px);
    border-right: 1px solid #eef2f7;
}

.sidebar-header {
    padding: 20px;
    text-align: center;
    border-bottom: 1px solid #eef2f7;
}

.avatar-circle {
    width: 60px;
    height: 60px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 24px;
    font-weight: bold;
    color: white;
    margin: 0 auto;
}

.role-badge {
    padding: 4px 10px;
    border-radius: 20px;
    font-size: 11px;
}
.role-badge.admin { background: #f5576c; color: white; }
.role-badge.teacher { background: #4facfe; color: white; }
.role-badge.student { background: #43e97b; color: white; }

.sidebar-nav {
    flex: 1;
    padding: 15px 0;
}

.nav-item {
    display: flex;
    align-items: center;
    padding: 10px 20px;
    margin: 2px 10px;
    color: #5a6e8a;
    text-decoration: none;
    border-radius: 10px;
    transition: 0.3s;
}

.nav-item i {
    width: 24px;
    margin-right: 12px;
}

.nav-item:hover, .nav-item.active {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
}

.nav-item .badge {
    margin-left: auto;
}

/* Main Content */
.dashboard-main {
    flex: 1;
    padding: 20px;
    overflow-y: auto;
}

/* Welcome Banner */
.welcome-banner {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 16px;
    padding: 25px;
    color: white;
    margin-bottom: 20px;
}

/* Stats Grid */
.stats-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 15px;
    margin-bottom: 20px;
}

.stat-card {
    background: white;
    border-radius: 14px;
    padding: 15px;
    display: flex;
    align-items: center;
    gap: 12px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.05);
}

.stat-icon {
    width: 45px;
    height: 45px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 20px;
    color: white;
}

.stat-icon.bg-primary { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); }
.stat-icon.bg-success { background: #28a745; }
.stat-icon.bg-info { background: #17a2b8; }
.stat-icon.bg-warning { background: #ffc107; }

.stat-number {
    font-size: 24px;
    font-weight: 700;
    margin-bottom: 0;
    color: #2c3e50;
}

.stat-label {
    color: #7f8c8d;
    font-size: 12px;
}

/* Section Card */
.section-card {
    background: white;
    border-radius: 14px;
    padding: 18px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.05);
}

.section-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 15px;
    padding-bottom: 10px;
    border-bottom: 1px solid #eef2f7;
}

.section-header h5 {
    margin-bottom: 0;
    font-size: 16px;
    font-weight: 600;
}

/* Activity Item */
.activity-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 10px 0;
    border-bottom: 1px solid #f0f0f0;
}

.activity-item:last-child {
    border-bottom: none;
}

/* Deadline Item */
.deadline-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 10px 12px;
    background: #f8f9fa;
    border-radius: 10px;
    margin-bottom: 8px;
    border-left: 4px solid;
}

.deadline-item:last-child {
    margin-bottom: 0;
}

.deadline-item.urgent { border-left-color: #dc3545; }
.deadline-item.warning { border-left-color: #ffc107; }
.deadline-item.normal { border-left-color: #28a745; }

/* Responsive */
@media (max-width: 992px) {
    .dashboard-sidebar { width: 70px; }
    .sidebar-header h6, .sidebar-header .role-badge, .nav-item span { display: none; }
    .nav-item i { margin-right: 0; }
    .nav-item { justify-content: center; }
    .stats-grid { grid-template-columns: repeat(2, 1fr); }
}

@media (max-width: 768px) {
    .dashboard-layout { flex-direction: column; }
    .dashboard-sidebar { width: 100%; height: auto; position: relative; top: 0; flex-direction: row; flex-wrap: wrap; padding: 10px; }
    .sidebar-header { display: none; }
    .sidebar-nav { display: flex; flex-wrap: wrap; justify-content: center; padding: 0; }
    .nav-item { padding: 8px 12px; margin: 4px; }
    .nav-item span { display: inline; margin-left: 8px; }
    .stats-grid { grid-template-columns: repeat(2, 1fr); }
    .welcome-banner h2 { font-size: 1.3rem; }
}
</style>

<form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
    @csrf
</form>
@endsection
