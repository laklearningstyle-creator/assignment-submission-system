@extends('layouts.app')

@section('title', 'User Management')

@section('content')
<div class="container-fluid py-4">
    <!-- Header Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card text-white border-0 rounded-4 shadow-sm" style="background: #0D6EFD;">
                <div class="card-body p-4">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h2 class="fw-bold mb-2">
                                <i class="fas fa-users-cog me-2"></i> User Management
                            </h2>
                            <p class="mb-0 opacity-90">Manage system users, assign roles, and control access permissions.</p>
                        </div>
                        <div class="col-md-4 text-center">
                            <i class="fas fa-users fa-3x opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row g-4 mb-4">
        <div class="col-md-3">
            <div class="card border-0 rounded-4 shadow-sm stat-card">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-1">Total Users</h6>
                            <h2 class="fw-bold mb-0" style="color: #0D6EFD;">{{ $users->total() }}</h2>
                            <small class="text-muted">All registered users</small>
                        </div>
                        <div class="stat-icon-circle" style="background: #0D6EFD;">
                            <i class="fas fa-users"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 rounded-4 shadow-sm stat-card">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-1">Students</h6>
                            <h2 class="fw-bold mb-0 text-success">{{ $totalStudents ?? 0 }}</h2>
                            <small class="text-muted">Active learners</small>
                        </div>
                        <div class="stat-icon-circle" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%);">
                            <i class="fas fa-user-graduate"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 rounded-4 shadow-sm stat-card">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-1">Teachers</h6>
                            <h2 class="fw-bold mb-0 text-info">{{ $totalTeachers ?? 0 }}</h2>
                            <small class="text-muted">Educators</small>
                        </div>
                        <div class="stat-icon-circle" style="background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);">
                            <i class="fas fa-chalkboard-user"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 rounded-4 shadow-sm stat-card">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-1">Admins</h6>
                            <h2 class="fw-bold mb-0 text-warning">{{ $totalAdmins ?? 0 }}</h2>
                            <small class="text-muted">System administrators</small>
                        </div>
                        <div class="stat-icon-circle" style="background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);">
                            <i class="fas fa-crown"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Search and Filter Bar -->
    <div class="card border-0 rounded-4 shadow-sm mb-4">
        <div class="card-body p-3">
            <div class="row g-3">
                <div class="col-md-4">
                    <div class="input-group">
                        <span class="input-group-text bg-light border-0 rounded-3">
                            <i class="fas fa-search text-muted"></i>
                        </span>
                        <input type="text" id="searchInput" class="form-control bg-light border-0 rounded-3" placeholder="Search by name, email or username...">
                    </div>
                </div>
                <div class="col-md-3">
                    <select id="roleFilter" class="form-select bg-light border-0 rounded-3">
                        <option value="">All Roles</option>
                        <option value="admin">👑 Admin</option>
                        <option value="teacher">👨‍🏫 Teacher</option>
                        <option value="student">🎓 Student</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <select id="statusFilter" class="form-select bg-light border-0 rounded-3">
                        <option value="">All Status</option>
                        <option value="active">✅ Active</option>
                        <option value="inactive">❌ Inactive</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <button id="resetFilters" class="btn btn-outline-secondary w-100 rounded-3">
                        <i class="fas fa-undo me-1"></i> Reset
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Users Table -->
    <div class="card border-0 rounded-4 shadow-sm">
        <div class="card-header bg-white border-0 pt-4 pb-0 px-4">
            <div class="d-flex justify-content-between align-items-center flex-wrap">
                <h5 class="fw-bold mb-3">
                    <i class="fas fa-list me-2" style="color: #0D6EFD;"></i> User List
                    <span class="badge ms-2 px-3 py-2 rounded-pill" style="background: #0D6EFD; color: white;">
                        <i class="fas fa-users me-1"></i> {{ $users->total() }} total
                    </span>
                </h5>
                <a href="{{ route('admin.users.create') }}" class="btn rounded-3 mb-3" style="background: #0D6EFD; color: white;">
                    <i class="fas fa-plus me-1"></i> Add New User
                </a>
            </div>
        </div>

        <div class="card-body p-4 pt-2">
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

            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" id="usersTable">
                    <thead class="table-light">
                        <tr>
                            <th style="width: 5%">ID</th>
                            <th style="width: 25%">User</th>
                            <th style="width: 20%">Contact</th>
                            <th style="width: 12%">Role</th>
                            <th style="width: 10%">Status</th>
                            <th style="width: 10%">Joined</th>
                            <th style="width: 18%">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($users as $user)
                        <tr class="user-row">
                            <td class="fw-bold" style="color: #0D6EFD;">{{ $user->user_id }}</td>
                            <td class="py-3">
                                <div class="d-flex align-items-center">
                                    <div class="user-avatar" style="background: linear-gradient(135deg, #0D6EFD 0%, #0b5ed7 100%);">
                                        {{ strtoupper(substr($user->full_name, 0, 1)) }}
                                    </div>
                                    <div class="ms-3">
                                        <div class="fw-bold text-dark">{{ $user->full_name }}</div>
                                        <div class="text-muted small">
                                            <i class="fas fa-at me-1"></i> {{ $user->username }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="py-3">
                                <div class="d-flex flex-column">
                                    <div class="mb-1">
                                        <i class="fas fa-envelope text-muted me-1"></i>
                                        <span class="small">{{ $user->email }}</span>
                                    </div>
                                    @if($user->phone)
                                    <div>
                                        <i class="fas fa-phone text-muted me-1"></i>
                                        <span class="small">{{ $user->phone }}</span>
                                    </div>
                                    @endif
                                </div>
                            </td>
                            <td class="py-3">
                                @php
                                    $roleName = $user->role->role_name ?? 'user';
                                    $roleIcon = match($roleName) {
                                        'admin' => 'fa-crown',
                                        'teacher' => 'fa-chalkboard-user',
                                        default => 'fa-user-graduate'
                                    };
                                @endphp
                                <span class="role-badge role-{{ $roleName }}">
                                    <i class="fas {{ $roleIcon }} me-1"></i> {{ ucfirst($roleName) }}
                                </span>
                            </td>
                            <td class="py-3">
                                @if($user->status == 'active')
                                    <span class="status-badge status-active">
                                        <i class="fas fa-check-circle me-1"></i>
                                        Active
                                    </span>
                                @else
                                    <span class="status-badge status-inactive">
                                        <i class="fas fa-ban me-1"></i>
                                        Inactive
                                    </span>
                                @endif
                            </td>
                            <td class="py-3">
                                <div class="d-flex flex-column">
                                    <span class="small">
                                        <i class="fas fa-calendar-alt text-muted me-1"></i>
                                        {{ $user->created_at->format('M d, Y') }}
                                    </span>
                                    <span class="small text-muted">
                                        {{ $user->created_at->diffForHumans() }}
                                    </span>
                                </div>
                            </td>
                            <td class="py-3">
                                <div class="action-buttons">
                                    <a href="{{ route('admin.users.show', $user->user_id) }}" class="btn-action view" title="View Details">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.users.edit', $user->user_id) }}" class="btn-action edit" title="Edit User">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    @if($user->user_id != Auth::user()->user_id)
                                        <button type="button" class="btn-action delete" title="Delete User"
                                                onclick="confirmDelete({{ $user->user_id }}, '{{ $user->full_name }}')">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                        <form id="delete-form-{{ $user->user_id }}"
                                              action="{{ route('admin.users.destroy', $user->user_id) }}"
                                              method="POST" style="display: none;">
                                            @csrf @method('DELETE')
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-5">
                                    <div class="empty-state">
                                        <i class="fas fa-users fa-4x text-muted mb-3 d-block"></i>
                                        <h5 class="text-muted">No users found</h5>
                                        <p class="text-muted small">Click "Add New User" to create your first user.</p>
                                        <a href="{{ route('admin.users.create') }}" class="btn btn-primary mt-2">
                                            <i class="fas fa-plus me-1"></i> Add New User
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($users->hasPages())
            <div class="mt-4 d-flex justify-content-center">
                {{ $users->links() }}
            </div>
            @endif
        </div>
    </div>
</div>

<script>
    function confirmDelete(id, name) {
        if(confirm(`⚠️ Are you sure you want to delete user "${name}"?\n\nThis action cannot be undone.`)) {
            document.getElementById('delete-form-' + id).submit();
        }
    }

    // Search and filter functionality
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('searchInput');
        const roleFilter = document.getElementById('roleFilter');
        const statusFilter = document.getElementById('statusFilter');
        const resetBtn = document.getElementById('resetFilters');
        const tableRows = document.querySelectorAll('#usersTable tbody tr');

        function filterTable() {
            const searchTerm = searchInput.value.toLowerCase();
            const roleValue = roleFilter.value.toLowerCase();
            const statusValue = statusFilter.value.toLowerCase();

            tableRows.forEach(row => {
                const userName = row.cells[1]?.innerText.toLowerCase() || '';
                const userEmail = row.cells[2]?.innerText.toLowerCase() || '';
                const userRole = row.cells[3]?.innerText.toLowerCase() || '';
                const userStatus = row.cells[4]?.innerText.toLowerCase() || '';

                const matchesSearch = userName.includes(searchTerm) || userEmail.includes(searchTerm);
                const matchesRole = !roleValue || userRole.includes(roleValue);
                const matchesStatus = !statusValue || userStatus.includes(statusValue);

                if (matchesSearch && matchesRole && matchesStatus) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        }

        searchInput.addEventListener('keyup', filterTable);
        roleFilter.addEventListener('change', filterTable);
        statusFilter.addEventListener('change', filterTable);

        resetBtn.addEventListener('click', function() {
            searchInput.value = '';
            roleFilter.value = '';
            statusFilter.value = '';
            filterTable();
        });
    });
</script>

<style>
    /* Header Gradient */
    .bg-gradient-primary {
        background: #0D6EFD;
    }

    /* Stat Cards */
    .stat-card {
        transition: all 0.3s ease;
        border: 1px solid rgba(0,0,0,0.04);
    }

    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 35px rgba(0,0,0,0.1) !important;
    }

    /* Stat Icon Circle */
    .stat-icon-circle {
        width: 50px;
        height: 50px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s ease;
        box-shadow: 0 4px 10px rgba(0,0,0,0.1);
    }

    .stat-icon-circle i {
        font-size: 24px;
        color: white;
    }

    .stat-icon-circle:hover {
        transform: scale(1.05);
        box-shadow: 0 6px 15px rgba(0,0,0,0.15);
    }

    /* User Avatar */
    .user-avatar {
        width: 45px;
        height: 45px;
        background: linear-gradient(135deg, #0D6EFD 0%, #0b5ed7 100%);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: bold;
        font-size: 18px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        transition: all 0.3s ease;
    }

    .user-row:hover .user-avatar {
        transform: scale(1.05);
        border-radius: 50%;
    }

    /* Role Badges */
    .role-badge {
        display: inline-flex;
        align-items: center;
        padding: 6px 14px;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 500;
        gap: 6px;
    }

    .role-admin {
        background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        color: white;
    }

    .role-teacher {
        background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
        color: white;
    }

    .role-student {
        background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);
        color: white;
    }

    /* Status Badges */
    .status-badge {
        display: inline-flex;
        align-items: center;
        padding: 6px 14px;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 500;
        gap: 6px;
    }

    .status-active {
        background: #22c55e;
        color: white;
        box-shadow: 0 2px 8px rgba(34, 197, 94, 0.3);
    }

    .status-inactive {
        background: #ef4444;
        color: white;
        box-shadow: 0 2px 8px rgba(239, 68, 68, 0.3);
    }

    /* Action Buttons */
    .action-buttons {
        display: flex;
        gap: 8px;
        flex-wrap: wrap;
    }

    .btn-action {
        width: 34px;
        height: 34px;
        border-radius: 8px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s ease;
        text-decoration: none;
        cursor: pointer;
        border: none;
        background: transparent;
    }

    .btn-action.view {
        background: #e0e7ff;
        color: #4338ca;
    }

    .btn-action.view:hover {
        background: #4338ca;
        color: white;
        transform: translateY(-2px);
    }

    .btn-action.edit {
        background: #fed7aa;
        color: #9a3412;
    }

    .btn-action.edit:hover {
        background: #9a3412;
        color: white;
        transform: translateY(-2px);
    }

    .btn-action.delete {
        background: #fee2e2;
        color: #dc2626;
    }

    .btn-action.delete:hover {
        background: #dc2626;
        color: white;
        transform: translateY(-2px);
    }

    /* Table Row Hover */
    .table-hover tbody tr {
        transition: all 0.2s ease;
        border-left: 3px solid transparent;
    }

    .table-hover tbody tr:hover {
        background-color: rgba(13, 110, 253, 0.04);
        border-left-color: #0D6EFD;
    }

    .table-hover tbody tr:hover .user-avatar {
        transform: scale(1.02);
    }

    /* Table Header */
    .table-light th {
        font-weight: 600;
        font-size: 0.85rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: #4b5563;
    }

    /* Empty State */
    .empty-state {
        padding: 40px 20px;
    }

    /* Pagination */
    .pagination {
        margin-bottom: 0;
    }

    .page-link {
        border-radius: 8px;
        margin: 0 3px;
        color: #0D6EFD;
    }

    .page-item.active .page-link {
        background: #0D6EFD;
        border-color: #0D6EFD;
        color: white;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .action-buttons {
            flex-direction: column;
            gap: 5px;
        }

        .btn-action {
            width: 100%;
        }

        .user-avatar {
            width: 35px;
            height: 35px;
            font-size: 14px;
        }

        .role-badge, .status-badge {
            padding: 4px 10px;
            font-size: 0.7rem;
        }
    }
</style>
@endsection
