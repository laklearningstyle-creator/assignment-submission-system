<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Assignment System') }} - @yield('title', 'Dashboard')</title>

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <link rel="apple-touch-icon" href="{{ asset('favicon.ico') }}">

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome 6 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,300;14..32,400;14..32,500;14..32,600;14..32,700;14..32,800&display=swap" rel="stylesheet">

    <style>
        :root {
            --primary: #4F46E5;
            --primary-dark: #4338CA;
            --secondary: #7C3AED;
            --success: #10B981;
            --info: #3B82F6;
            --warning: #F59E0B;
            --danger: #EF4444;
            --dark: #1F2937;
            --light: #F3F4F6;
        }

        * {
            font-family: 'Inter', sans-serif;
        }

        body {
            background: #F9FAFB;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .navbar {
            background: white !important;
            box-shadow: 0 1px 3px rgba(0,0,0,0.05);
            padding: 0.75rem 0;
        }

        .navbar-brand {
            font-weight: 800;
            font-size: 1.5rem;
            background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
            letter-spacing: -0.02em;
        }

        .navbar-brand i {
            background: none;
            -webkit-background-clip: unset;
            background-clip: unset;
            color: var(--primary);
        }

        .nav-link {
            font-weight: 500;
            color: #4B5563 !important;
            transition: all 0.2s ease;
            margin: 0 0.25rem;
            border-radius: 0.5rem;
            padding: 0.5rem 1rem;
        }

        .nav-link:hover {
            color: var(--primary) !important;
            background: #EEF2FF;
        }

        .nav-link.active {
            color: var(--primary) !important;
            background: #EEF2FF;
            font-weight: 600;
        }

        .dropdown-menu {
            border: none;
            border-radius: 1rem;
            box-shadow: 0 10px 25px -5px rgba(0,0,0,0.1), 0 8px 10px -6px rgba(0,0,0,0.02);
            padding: 0.5rem;
            margin-top: 0.5rem;
        }

        .dropdown-item {
            border-radius: 0.5rem;
            padding: 0.5rem 1rem;
            font-size: 0.875rem;
            transition: all 0.2s ease;
        }

        .dropdown-item:hover {
            background: #EEF2FF;
            color: var(--primary);
            transform: translateX(4px);
        }

        /* Avatar Styles */
        .user-avatar {
            width: 38px;
            height: 38px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 700;
            font-size: 1rem;
            transition: all 0.2s ease;
            background-size: cover;
            background-position: center;
        }

        .user-avatar:hover {
            transform: scale(1.05);
        }

        /* Role-based avatar borders */
        .avatar-border-admin {
            border: 2px solid #ef4444;
            box-shadow: 0 0 0 2px rgba(239, 68, 68, 0.2);
        }

        .avatar-border-teacher {
            border: 2px solid #10b981;
            box-shadow: 0 0 0 2px rgba(16, 185, 129, 0.2);
        }

        .avatar-border-student {
            border: 2px solid #3b82f6;
            box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.2);
        }

        .avatar-border-default {
            border: 2px solid #6c757d;
            box-shadow: 0 0 0 2px rgba(108, 117, 125, 0.2);
        }

        .role-badge {
            font-size: 0.65rem;
            padding: 3px 8px;
            border-radius: 20px;
            font-weight: 600;
        }
        .role-badge.admin { background: #FEE2E2; color: #DC2626; }
        .role-badge.teacher { background: #DBEAFE; color: #2563EB; }
        .role-badge.student { background: #D1FAE5; color: #059669; }

        .alert {
            border: none;
            border-radius: 1rem;
            padding: 1rem 1.25rem;
            margin-bottom: 1rem;
        }

        .footer {
            background: white;
            border-top: 1px solid #E5E7EB;
            margin-top: auto;
            padding: 1.5rem 0;
        }

        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }
        ::-webkit-scrollbar-track {
            background: #F1F1F1;
            border-radius: 10px;
        }
        ::-webkit-scrollbar-thumb {
            background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
            border-radius: 10px;
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
            border: none;
            padding: 0.5rem 1.25rem;
            font-weight: 500;
            border-radius: 0.5rem;
            transition: all 0.2s ease;
        }
        .btn-primary:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(79, 70, 229, 0.3);
        }

        .card {
            border: none;
            border-radius: 1rem;
            box-shadow: 0 1px 3px rgba(0,0,0,0.05);
            transition: all 0.2s ease;
        }
        .card:hover {
            box-shadow: 0 10px 25px -5px rgba(0,0,0,0.1);
        }

        @media (max-width: 768px) {
            .navbar-brand {
                font-size: 1.2rem;
            }
            .user-avatar {
                width: 32px;
                height: 32px;
                font-size: 0.875rem;
            }
        }
    </style>

    @stack('styles')
</head>
<body>
    <nav class="navbar navbar-expand-lg sticky-top">
        <div class="container">
            <a class="navbar-brand" href="{{ route('dashboard') }}">
                <i class="fas fa-graduation-cap me-2"></i>
                Assignment System
            </a>

            <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav mx-auto">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('dashboard*') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                            <i class="fas fa-chart-line me-1"></i> Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('courses*') ? 'active' : '' }}" href="{{ route('courses.index') }}">
                            <i class="fas fa-book me-1"></i> Courses
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('assignments*') ? 'active' : '' }}" href="{{ route('assignments.index') }}">
                            <i class="fas fa-tasks me-1"></i> Assignments
                        </a>
                    </li>
                </ul>

                <ul class="navbar-nav align-items-center">
                    @auth
                        @if(Auth::user()->role_id == 1)
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">
                                <i class="fas fa-crown me-1"></i> Admin
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><a class="dropdown-item" href="{{ route('admin.users.index') }}"><i class="fas fa-users me-2"></i>Manage Users</a></li>
                                <li><a class="dropdown-item" href="{{ route('admin.roles.index') }}"><i class="fas fa-key me-2"></i>Manage Roles</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="{{ route('admin.reports.index') }}"><i class="fas fa-chart-bar me-2"></i>Reports</a></li>
                            </ul>
                        </li>
                        @endif

                        <li class="nav-item dropdown ms-2">
                            <a class="nav-link dropdown-toggle d-flex align-items-center gap-2" href="#" data-bs-toggle="dropdown">
                                @php
                                    $user = Auth::user();
                                    $hasAvatar = $user->avatar && file_exists(storage_path('app/public/' . $user->avatar));
                                    $avatarBorderClass = match($user->role_id) {
                                        1 => 'avatar-border-admin',
                                        2 => 'avatar-border-teacher',
                                        3 => 'avatar-border-student',
                                        default => 'avatar-border-default',
                                    };
                                    $avatarColor = $user->avatar_color ?? match($user->role_id) {
                                        1 => '#ef4444',
                                        2 => '#10b981',
                                        3 => '#3b82f6',
                                        default => '#667eea',
                                    };
                                    $initials = '';
                                    $words = explode(' ', $user->full_name ?? 'U');
                                    foreach($words as $word) {
                                        if(!empty($word)) $initials .= strtoupper(substr($word, 0, 1));
                                    }
                                    $initials = substr($initials, 0, 2);
                                @endphp

                                @if($hasAvatar)
                                    <div class="user-avatar {{ $avatarBorderClass }}" style="background-image: url('{{ asset('storage/' . $user->avatar) }}'); background-size: cover; background-position: center; background-color: transparent;">
                                    </div>
                                @else
                                    <div class="user-avatar {{ $avatarBorderClass }}" style="background: {{ $avatarColor }};">
                                        {{ $initials }}
                                    </div>
                                @endif

                                <div class="d-none d-lg-block text-start">
                                    <div class="fw-semibold small">{{ Auth::user()->full_name ?? 'User' }}</div>
                                    @php
                                        $roleName = Auth::user()->role->role_name ?? 'student';
                                        $roleClass = match($roleName) {
                                            'admin' => 'admin',
                                            'teacher' => 'teacher',
                                            default => 'student'
                                        };
                                    @endphp
                                    <span class="role-badge {{ $roleClass }}">
                                        {{ ucfirst($roleName) }}
                                    </span>
                                </div>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><a class="dropdown-item" href="{{ route('profile.index') }}"><i class="fas fa-user-circle me-2"></i>My Profile</a></li>
                                <li><a class="dropdown-item" href="{{ route('notifications.index') }}">
                                    <i class="fas fa-bell me-2"></i>Notifications
                                    @if(isset($unreadNotifications) && $unreadNotifications > 0)
                                        <span class="badge bg-danger rounded-pill ms-2">{{ $unreadNotifications }}</span>
                                    @endif
                                </a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="dropdown-item text-danger" style="background: none; border: none; width: 100%; text-align: left;">
                                            <i class="fas fa-sign-out-alt me-2"></i>Logout
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </li>
                    @else
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}">
                                <i class="fas fa-sign-in-alt me-1"></i> Login
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('register') }}">
                                <i class="fas fa-user-plus me-1"></i> Register
                            </a>
                        </li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>

    <main class="py-4">
        <div class="container">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show d-flex align-items-center justify-content-between" role="alert">
                    <div><i class="fas fa-check-circle me-2"></i> {{ session('success') }}</div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show d-flex align-items-center justify-content-between" role="alert">
                    <div><i class="fas fa-exclamation-circle me-2"></i> {{ session('error') }}</div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if($errors->any())
                <div class="alert alert-warning alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    <strong>Please fix the following errors:</strong>
                    <ul class="mb-0 mt-2 ps-3">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
        </div>

        @yield('content')
    </main>

    <footer class="footer">
        <div class="container text-center">
            <small class="text-muted">
                <i class="fas fa-graduation-cap me-1"></i>
                &copy; {{ date('Y') }} {{ config('app.name', 'Assignment System') }}. All rights reserved.
            </small>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            setTimeout(function() {
                document.querySelectorAll('.alert-dismissible').forEach(function(alert) {
                    let bsAlert = new bootstrap.Alert(alert);
                    bsAlert.close();
                });
            }, 5000);
        });
    </script>

    @stack('scripts')
</body>
</html>
