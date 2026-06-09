<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\AssignmentController;
use App\Http\Controllers\EnrollmentController;
use App\Http\Controllers\SubmissionController;
use App\Http\Controllers\GradeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\FeedbackController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\StudentController;

// ==================== PUBLIC ROUTES ====================
Route::get('/', [HomeController::class, 'welcome'])->name('welcome');

// Authentication Routes (Breeze)
require __DIR__.'/auth.php';

// ==================== PROTECTED ROUTES ====================
Route::middleware(['auth'])->group(function () {

    // ==================== DASHBOARD ROUTES ====================
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/admin/dashboard', [DashboardController::class, 'adminDashboard'])->name('admin.dashboard');
    Route::get('/teacher/dashboard', [DashboardController::class, 'teacherDashboard'])->name('teacher.dashboard');
    Route::get('/student/dashboard', [StudentController::class, 'dashboard'])->name('student.dashboard');

    // ==================== COURSE ROUTES ====================
    Route::prefix('courses')->name('courses.')->group(function () {
        Route::get('/', [CourseController::class, 'index'])->name('index');
        Route::get('/create', [CourseController::class, 'create'])->name('create');
        Route::post('/', [CourseController::class, 'store'])->name('store');
        Route::get('/{id}', [CourseController::class, 'show'])->name('show');
        Route::get('/{id}/edit', [CourseController::class, 'edit'])->name('edit');
        Route::put('/{id}', [CourseController::class, 'update'])->name('update');
        Route::delete('/{id}', [CourseController::class, 'destroy'])->name('destroy');
    });

    // ==================== ASSIGNMENT ROUTES ====================
    Route::prefix('assignments')->name('assignments.')->group(function () {
        Route::get('/', [AssignmentController::class, 'index'])->name('index');
        Route::get('/create', [AssignmentController::class, 'create'])->name('create');
        Route::post('/', [AssignmentController::class, 'store'])->name('store');
        Route::get('/{id}', [AssignmentController::class, 'show'])->name('show');
        Route::get('/{id}/edit', [AssignmentController::class, 'edit'])->name('edit');
        Route::put('/{id}', [AssignmentController::class, 'update'])->name('update');
        Route::delete('/{id}', [AssignmentController::class, 'destroy'])->name('destroy');
    });

    // ==================== ENROLLMENT ROUTES ====================
    Route::prefix('enrollments')->name('enrollments.')->group(function () {
        Route::get('/', [EnrollmentController::class, 'index'])->name('index');
        Route::get('/create', [EnrollmentController::class, 'create'])->name('create');
        Route::post('/', [EnrollmentController::class, 'store'])->name('store');
        Route::get('/{id}', [EnrollmentController::class, 'show'])->name('show');
        Route::get('/{id}/edit', [EnrollmentController::class, 'edit'])->name('edit');
        Route::put('/{id}', [EnrollmentController::class, 'update'])->name('update');
        Route::delete('/{id}', [EnrollmentController::class, 'destroy'])->name('destroy');
        Route::post('/bulk', [EnrollmentController::class, 'bulkEnroll'])->name('bulk');
    });

    // Course students route
    Route::get('/courses/{courseId}/students', [EnrollmentController::class, 'courseStudents'])->name('enrollments.course-students');

    // ==================== SUBMISSION ROUTES ====================
    Route::prefix('submissions')->name('submissions.')->group(function () {
        Route::get('/', [SubmissionController::class, 'index'])->name('index');
        Route::get('/create', [SubmissionController::class, 'create'])->name('create');
        Route::post('/', [SubmissionController::class, 'store'])->name('store');
        Route::get('/{id}', [SubmissionController::class, 'show'])->name('show');
        Route::get('/{id}/edit', [SubmissionController::class, 'edit'])->name('edit');
        Route::put('/{id}', [SubmissionController::class, 'update'])->name('update');
        Route::delete('/{id}', [SubmissionController::class, 'destroy'])->name('destroy');
        Route::get('/download/{id}', [SubmissionController::class, 'downloadFile'])->name('download');
    });

    // ==================== FEEDBACK ROUTES ====================
    Route::prefix('feedbacks')->name('feedbacks.')->group(function () {
        Route::get('/', [FeedbackController::class, 'index'])->name('index');
        Route::get('/create', [FeedbackController::class, 'create'])->name('create');
        Route::post('/', [FeedbackController::class, 'store'])->name('store');
        Route::get('/{id}', [FeedbackController::class, 'show'])->name('show');
        Route::get('/{id}/edit', [FeedbackController::class, 'edit'])->name('edit');
        Route::put('/{id}', [FeedbackController::class, 'update'])->name('update');
        Route::delete('/{id}', [FeedbackController::class, 'destroy'])->name('destroy');
    });

    // ==================== GRADE ROUTES ====================
    Route::prefix('grades')->name('grades.')->group(function () {
        Route::get('/', [GradeController::class, 'index'])->name('index');
        Route::get('/create', [GradeController::class, 'create'])->name('create');
        Route::post('/', [GradeController::class, 'store'])->name('store');
        Route::get('/{id}', [GradeController::class, 'show'])->name('show');
        Route::get('/{id}/edit', [GradeController::class, 'edit'])->name('edit');
        Route::put('/{id}', [GradeController::class, 'update'])->name('update');
        Route::delete('/{id}', [GradeController::class, 'destroy'])->name('destroy');
        Route::get('/assignment/{assignment_id}', [GradeController::class, 'byAssignment'])->name('by-assignment');
    });

    // ==================== STUDENT ROUTES ====================
    Route::prefix('student')->name('student.')->middleware(['role:student'])->group(function () {
        Route::get('/dashboard', [StudentController::class, 'dashboard'])->name('dashboard');
        Route::get('/my-courses', [StudentController::class, 'myCourses'])->name('my-courses');
        Route::get('/my-submissions', [StudentController::class, 'mySubmissions'])->name('my-submissions');
        Route::get('/my-grades', [StudentController::class, 'myGrades'])->name('my-grades');
        Route::get('/my-feedback', [FeedbackController::class, 'myFeedback'])->name('my-feedback');
        Route::get('/submissions', [SubmissionController::class, 'mySubmissions'])->name('submissions.index');
    });

    // ==================== ADMIN ROUTES ====================
    Route::prefix('admin')->name('admin.')->middleware(['role:admin'])->group(function () {
        Route::resource('users', UserController::class);
        Route::resource('roles', RoleController::class);

        Route::prefix('reports')->name('reports.')->group(function () {
            Route::get('/', [ReportController::class, 'index'])->name('index');
            Route::post('/submissions', [ReportController::class, 'generateSubmissionsReport'])->name('submissions');
            Route::post('/grades', [ReportController::class, 'generateGradesReport'])->name('grades');
            Route::post('/performance', [ReportController::class, 'generatePerformanceReport'])->name('performance');
            Route::get('/download/{id}', [ReportController::class, 'download'])->name('download');
            Route::delete('/{id}', [ReportController::class, 'destroy'])->name('destroy');
        });
    });

    // ==================== TEACHER ROUTES ====================
    Route::prefix('teacher')->name('teacher.')->middleware(['role:teacher'])->group(function () {
        Route::get('/grades/assignment/{assignment_id}', [GradeController::class, 'byAssignment'])->name('grades.by-assignment');
        Route::get('/grades/submission/{submission_id}/edit', [GradeController::class, 'edit'])->name('grades.edit');
        Route::put('/grades/submission/{submission_id}', [GradeController::class, 'update'])->name('grades.update');

        Route::prefix('feedbacks')->name('feedbacks.')->group(function () {
            Route::get('/submission/{submission_id}', [FeedbackController::class, 'index'])->name('index');
            Route::post('/submission/{submission_id}', [FeedbackController::class, 'store'])->name('store');
            Route::get('/{id}/edit', [FeedbackController::class, 'edit'])->name('edit');
            Route::put('/{id}', [FeedbackController::class, 'update'])->name('update');
            Route::delete('/{id}', [FeedbackController::class, 'destroy'])->name('destroy');
        });
    });

    // ==================== NOTIFICATION ROUTES ====================
    Route::prefix('notifications')->name('notifications.')->group(function () {
        Route::get('/', [NotificationController::class, 'index'])->name('index');
        Route::post('/{id}/read', [NotificationController::class, 'markAsRead'])->name('mark-read');
        Route::post('/mark-all-read', [NotificationController::class, 'markAllAsRead'])->name('mark-all-read');
        Route::delete('/{id}', [NotificationController::class, 'destroy'])->name('destroy');
        Route::delete('/destroy-all', [NotificationController::class, 'destroyAll'])->name('destroy-all');
    });

    // ==================== PROFILE ROUTES ====================
    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('/', [ProfileController::class, 'index'])->name('index');
        Route::put('/', [ProfileController::class, 'update'])->name('update');
        Route::put('/password', [ProfileController::class, 'updatePassword'])->name('password');
        Route::post('/avatar', [ProfileController::class, 'uploadAvatar'])->name('avatar');
    });
});
