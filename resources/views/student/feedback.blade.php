@extends('layouts.app')

@section('title', 'My Feedback')

@section('content')
<div class="container py-4">
    <div class="row">
        <!-- Sidebar -->
        <div class="col-md-3 col-lg-2 mb-4">
            <div class="card border-0 rounded-4 shadow-sm sticky-top" style="top: 80px;">
                <div class="card-header bg-white border-0 pt-4">
                    <h5 class="fw-bold mb-0" style="font-size: 1rem;">
                        <i class="fas fa-graduation-cap me-2" style="color: #0D6EFD;"></i> Student Menu
                    </h5>
                </div>
                <div class="card-body p-0">
                    <div class="list-group list-group-flush">
                        <a href="{{ route('student.dashboard') }}" class="list-group-item list-group-item-action border-0 rounded-0 py-3">
                            <i class="fas fa-tachometer-alt me-2"></i> Dashboard
                        </a>
                        <a href="{{ route('student.my-courses') }}" class="list-group-item list-group-item-action border-0 rounded-0 py-3">
                            <i class="fas fa-book me-2"></i> My Courses
                        </a>
                        <a href="{{ route('student.my-submissions') }}" class="list-group-item list-group-item-action border-0 rounded-0 py-3">
                            <i class="fas fa-paper-plane me-2"></i> My Submissions
                        </a>
                        <a href="{{ route('student.my-grades') }}" class="list-group-item list-group-item-action border-0 rounded-0 py-3">
                            <i class="fas fa-star me-2"></i> My Grades
                        </a>
                        <a href="{{ route('student.my-feedback') }}" class="list-group-item list-group-item-action border-0 rounded-0 py-3 active" style="background: #0D6EFD; color: white;">
                            <i class="fas fa-comment me-2"></i> My Feedback
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="col-md-9 col-lg-10">
            <div class="card shadow-lg border-0 rounded-4">
                <div class="card-header bg-gradient-info text-white rounded-top-4" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);">
                    <h4 class="mb-0">
                        <i class="fas fa-comment-dots me-2"></i> My Feedback
                    </h4>
                </div>

                <div class="card-body p-4">
                    @if($feedbacks->count() > 0)
                        <div class="row g-4">
                            @foreach($feedbacks as $feedback)
                                <div class="col-md-6">
                                    <div class="card h-100 border-0 shadow-sm feedback-card">
                                        <div class="card-body">
                                            <div class="d-flex justify-content-between align-items-start mb-3">
                                                <div class="d-flex align-items-center">
                                                    <div class="rounded-circle bg-primary bg-opacity-10 p-3 me-3">
                                                        <i class="fas fa-comment text-primary"></i>
                                                    </div>
                                                    <div>
                                                        <h5 class="mb-0" style="font-size: 1rem;">{{ $feedback->submission->assignment->title ?? 'N/A' }}</h5>
                                                        <small class="text-muted" style="font-size: 0.7rem;">{{ $feedback->submission->assignment->course->course_name ?? 'N/A' }}</small>
                                                    </div>
                                                </div>
                                                <span class="badge bg-info" style="font-size: 0.65rem;">{{ $feedback->created_at->format('M d, Y') }}</span>
                                            </div>

                                            <div class="feedback-content bg-light p-3 rounded-3 mb-3">
                                                <i class="fas fa-quote-left text-muted me-2"></i>
                                                {{ $feedback->comment }}
                                            </div>

                                            <div class="d-flex justify-content-between align-items-center">
                                                <div>
                                                    <i class="fas fa-chalkboard-user text-muted me-1"></i>
                                                    <small class="text-muted" style="font-size: 0.7rem;">Teacher: {{ $feedback->teacher->full_name ?? 'N/A' }}</small>
                                                </div>
                                                @if($feedback->submission->grade)
                                                    <span class="badge bg-success" style="font-size: 0.65rem;">
                                                        Grade: {{ $feedback->submission->grade->grade }} ({{ $feedback->submission->grade->marks_obtained }}/{{ $feedback->submission->assignment->total_marks ?? 100 }})
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <div class="mt-4">
                            {{ $feedbacks->links() ?? '' }}
                        </div>
                    @else
                        <div class="text-center text-muted py-5">
                            <i class="fas fa-comments fa-3x mb-3"></i>
                            <h5 class="text-muted" style="font-size: 1rem;">No Feedback Yet</h5>
                            <p class="text-muted" style="font-size: 0.875rem;">You haven't received any feedback from teachers yet.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .bg-gradient-info {
        background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
    }
    .feedback-card {
        transition: all 0.3s ease;
    }
    .feedback-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(0,0,0,0.1) !important;
    }
    .feedback-content {
        border-left: 4px solid #667eea;
        font-size: 0.85rem;
    }

    /* Sidebar Styling */
    .list-group-item {
        transition: all 0.2s ease;
        font-size: 0.8rem;
    }
    .list-group-item:not(.active):hover {
        background-color: #f8fafc;
        color: #0D6EFD;
        padding-left: 24px;
    }
    .list-group-item.active {
        background: #0D6EFD;
        color: white;
        box-shadow: 0 2px 8px rgba(13, 110, 253, 0.3);
    }
    .sticky-top {
        position: sticky;
        top: 20px;
        z-index: 100;
    }

    @media (max-width: 768px) {
        .sticky-top {
            position: relative;
            top: 0;
            margin-bottom: 1rem;
        }
        h4 {
            font-size: 1.25rem;
        }
    }
</style>
@endsection
