@extends('layouts.app')

@section('title', 'Role Details')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-lg border-0 rounded-4">
                <div class="card-header bg-gradient-info text-white rounded-top-4 d-flex justify-content-between align-items-center" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);">
                    <div>
                        <h5 class="mb-0">
                            <i class="fas fa-info-circle me-2"></i> Role Details
                        </h5>
                        <p class="mb-0 mt-1 small opacity-75">View role information</p>
                    </div>
                    <div>
                        @if(!in_array($role->role_name, ['admin', 'teacher', 'student']))
                            <a href="{{ route('admin.roles.edit', $role->role_id) }}" class="btn btn-warning btn-sm me-1">
                                <i class="fas fa-edit me-1"></i> Edit
                            </a>
                        @endif
                        <a href="{{ route('admin.roles.index') }}" class="btn btn-light btn-sm">
                            <i class="fas fa-arrow-left me-1"></i> Back
                        </a>
                    </div>
                </div>

                <div class="card-body p-4">
                    <div class="row mb-3">
                        <div class="col-md-4 fw-bold text-primary">Role ID:</div>
                        <div class="col-md-8">{{ $role->role_id }}</div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-4 fw-bold text-primary">Role Name:</div>
                        <div class="col-md-8">
                            @php
                                $badgeClass = match($role->role_name) {
                                    'admin' => 'danger',
                                    'teacher' => 'primary',
                                    default => 'success'
                                };
                                $roleIcon = match($role->role_name) {
                                    'admin' => 'fa-crown',
                                    'teacher' => 'fa-chalkboard-user',
                                    default => 'fa-user-graduate'
                                };
                            @endphp
                            <span class="badge bg-{{ $badgeClass }} bg-opacity-10 text-{{ $badgeClass }} px-3 py-2 rounded-pill">
                                <i class="fas {{ $roleIcon }} me-1"></i>
                                {{ ucfirst($role->role_name) }}
                            </span>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-4 fw-bold text-primary">Users with this role:</div>
                        <div class="col-md-8">
                            <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-2 rounded-pill">
                                <i class="fas fa-users me-1"></i> {{ $role->users_count ?? $role->users()->count() }} users
                            </span>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-4 fw-bold text-primary">Created At:</div>
                        <div class="col-md-8">
                            <i class="fas fa-calendar-alt text-muted me-1"></i>
                            {{ $role->created_at ? $role->created_at->format('F d, Y H:i') : 'N/A' }}
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-4 fw-bold text-primary">Last Updated:</div>
                        <div class="col-md-8">
                            <i class="fas fa-clock text-muted me-1"></i>
                            {{ $role->updated_at ? $role->updated_at->format('F d, Y H:i') : 'N/A' }}
                        </div>
                    </div>

                    @if($role->role_name == 'admin')
                        <div class="alert alert-info mt-3">
                            <i class="fas fa-info-circle me-2"></i>
                            Admin role has full system access including user management, role management, and system settings.
                        </div>
                    @elseif($role->role_name == 'teacher')
                        <div class="alert alert-info mt-3">
                            <i class="fas fa-info-circle me-2"></i>
                            Teacher role can create courses, manage assignments, grade submissions, and provide feedback.
                        </div>
                    @elseif($role->role_name == 'student')
                        <div class="alert alert-info mt-3">
                            <i class="fas fa-info-circle me-2"></i>
                            Student role can submit assignments, view grades, receive feedback, and track progress.
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
