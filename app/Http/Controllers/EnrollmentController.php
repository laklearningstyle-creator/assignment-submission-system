<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\User;
use App\Models\Enrollment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class EnrollmentController extends Controller
{
    /**
     * Constructor - Apply middleware
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Check if user is authorized (Admin or Teacher)
     */
    private function isAuthorized(): bool
    {
        return in_array(Auth::user()->role_id, [1, 2]);
    }

    /**
     * Check if teacher owns the course
     */
    private function ownsCourse($courseId): bool
    {
        if (Auth::user()->role_id == 1) {
            return true; // Admin can do anything
        }

        $course = Course::find($courseId);
        return $course && $course->created_by == Auth::user()->user_id;
    }

    /**
     * Display a listing of enrollments (Admin/Teacher view).
     */
    public function index()
    {
        if (!$this->isAuthorized()) {
            return redirect()->route('dashboard')
                ->with('error', 'Unauthorized access. Only teachers and admins can view enrollments.');
        }

        $enrollments = Enrollment::with(['course', 'student'])
            ->orderBy('enrollment_id', 'desc')
            ->paginate(10);

        return view('enrollments.index', compact('enrollments'));
    }

    /**
     * Show the form for creating a new enrollment.
     */
    public function create(Request $request)
    {
        if (!$this->isAuthorized()) {
            return redirect()->route('dashboard')
                ->with('error', 'Unauthorized access. Only teachers and admins can create enrollments.');
        }

        $courses = Course::orderBy('course_name')->get();
        $students = User::where('role_id', 3)->orderBy('full_name')->get();

        // Pre-select course if provided
        $selectedCourse = $request->get('course_id');

        return view('enrollments.create', compact('courses', 'students', 'selectedCourse'));
    }

    /**
     * Store a newly created enrollment.
     */
    public function store(Request $request)
    {
        if (!$this->isAuthorized()) {
            return redirect()->route('dashboard')
                ->with('error', 'Unauthorized access.');
        }

        $request->validate([
            'course_id' => 'required|exists:courses,course_id',
            'student_id' => 'required|exists:users,user_id',
        ]);

        // Check if teacher owns the course
        if (!$this->ownsCourse($request->course_id)) {
            return redirect()->back()
                ->with('error', 'You can only enroll students in your own courses.')
                ->withInput();
        }

        // Check if already enrolled
        $exists = Enrollment::where('course_id', $request->course_id)
            ->where('student_id', $request->student_id)
            ->exists();

        if ($exists) {
            return redirect()->back()
                ->with('error', 'Student is already enrolled in this course.')
                ->withInput();
        }

        // Check if student exists and is a student role
        $student = User::find($request->student_id);
        if (!$student || $student->role_id != 3) {
            return redirect()->back()
                ->with('error', 'Invalid student selected.')
                ->withInput();
        }

        Enrollment::create([
            'course_id' => $request->course_id,
            'student_id' => $request->student_id,
            'enrolled_at' => now(),
        ]);

        return redirect()->route('enrollments.index')
            ->with('success', 'Student enrolled successfully.');
    }

    /**
     * Display the specified enrollment.
     */
    public function show($id)
    {
        $enrollment = Enrollment::with(['course', 'student'])->findOrFail($id);

        // Check permission: Admin, Teacher, or the student themselves
        if (!$this->isAuthorized() && $enrollment->student_id != Auth::user()->user_id) {
            return redirect()->route('dashboard')
                ->with('error', 'Unauthorized access.');
        }

        return view('enrollments.show', compact('enrollment'));
    }

    /**
     * Show the form for editing the specified enrollment.
     */
    public function edit($id)
    {
        if (!$this->isAuthorized()) {
            return redirect()->route('dashboard')
                ->with('error', 'Unauthorized access.');
        }

        $enrollment = Enrollment::findOrFail($id);

        // Check if teacher owns the course
        if (!$this->ownsCourse($enrollment->course_id)) {
            return redirect()->route('enrollments.index')
                ->with('error', 'You can only edit enrollments for your own courses.');
        }

        $courses = Course::orderBy('course_name')->get();
        $students = User::where('role_id', 3)->orderBy('full_name')->get();

        return view('enrollments.edit', compact('enrollment', 'courses', 'students'));
    }

    /**
     * Update the specified enrollment.
     */
    public function update(Request $request, $id)
    {
        if (!$this->isAuthorized()) {
            return redirect()->route('dashboard')
                ->with('error', 'Unauthorized access.');
        }

        $enrollment = Enrollment::findOrFail($id);

        // Check if teacher owns the course
        if (!$this->ownsCourse($enrollment->course_id)) {
            return redirect()->route('enrollments.index')
                ->with('error', 'You can only update enrollments for your own courses.');
        }

        $request->validate([
            'course_id' => 'required|exists:courses,course_id',
            'student_id' => 'required|exists:users,user_id',
        ]);

        // Check if already enrolled with different enrollment
        $exists = Enrollment::where('course_id', $request->course_id)
            ->where('student_id', $request->student_id)
            ->where('enrollment_id', '!=', $id)
            ->exists();

        if ($exists) {
            return redirect()->back()
                ->with('error', 'Student is already enrolled in this course.')
                ->withInput();
        }

        $enrollment->update([
            'course_id' => $request->course_id,
            'student_id' => $request->student_id,
        ]);

        return redirect()->route('enrollments.index')
            ->with('success', 'Enrollment updated successfully.');
    }

    /**
     * Remove the specified enrollment.
     */
    public function destroy($id)
    {
        if (!$this->isAuthorized()) {
            return redirect()->route('dashboard')
                ->with('error', 'Unauthorized access.');
        }

        $enrollment = Enrollment::findOrFail($id);

        // Check permission: Admin can delete any, Teacher only their own course enrollments
        if (Auth::user()->role_id != 1 && !$this->ownsCourse($enrollment->course_id)) {
            return redirect()->route('enrollments.index')
                ->with('error', 'You can only delete enrollments for your own courses.');
        }

        $enrollment->delete();

        return redirect()->route('enrollments.index')
            ->with('success', 'Enrollment removed successfully.');
    }

    /**
     * Display student's enrolled courses (Student view).
     */
    public function myCourses()
    {
        // Only students can access this
        if (Auth::user()->role_id != 3) {
            return redirect()->route('dashboard')
                ->with('error', 'Unauthorized access. Student only.');
        }

        $studentId = Auth::user()->user_id;

        $enrollments = Enrollment::with(['course.creator', 'course.assignments', 'course.enrollments'])
            ->where('student_id', $studentId)
            ->orderBy('enrolled_at', 'desc')
            ->paginate(12);

        // Get recommended courses (courses the student is not enrolled in)
        $enrolledCourseIds = $enrollments->pluck('course_id')->toArray();
        $recommendedCourses = Course::whereNotIn('course_id', $enrolledCourseIds)
            ->where('status', 'active')
            ->limit(3)
            ->get();

        return view('student.courses', compact('enrollments', 'recommendedCourses'));
    }

    /**
     * Check if a student is enrolled in a specific course.
     */
    public function isEnrolled($courseId, $studentId = null)
    {
        $studentId = $studentId ?? Auth::user()->user_id;

        return Enrollment::where('course_id', $courseId)
            ->where('student_id', $studentId)
            ->exists();
    }

    /**
     * Get enrolled students for a course (Teacher/Admin view).
     */
    public function courseStudents($courseId)
    {
        if (!$this->isAuthorized()) {
            return redirect()->route('dashboard')
                ->with('error', 'Unauthorized access.');
        }

        $course = Course::findOrFail($courseId);

        // Check if teacher owns the course
        if (!$this->ownsCourse($courseId)) {
            return redirect()->route('courses.index')
                ->with('error', 'You can only view students for your own courses.');
        }

        $students = Enrollment::with('student')
            ->where('course_id', $courseId)
            ->orderBy('enrolled_at', 'desc')
            ->paginate(20);

        return view('enrollments.course-students', compact('course', 'students'));
    }

    /**
     * Get enrollment count for a course
     */
    public function getEnrollmentCount($courseId)
    {
        return Enrollment::where('course_id', $courseId)->count();
    }

    /**
     * Bulk enroll students (Admin only)
     */
    public function bulkEnroll(Request $request)
    {
        if (Auth::user()->role_id != 1) {
            return redirect()->route('dashboard')
                ->with('error', 'Only administrators can perform bulk enrollment.');
        }

        $request->validate([
            'course_id' => 'required|exists:courses,course_id',
            'student_ids' => 'required|array',
            'student_ids.*' => 'exists:users,user_id',
        ]);

        $successCount = 0;
        $failCount = 0;

        foreach ($request->student_ids as $studentId) {
            $exists = Enrollment::where('course_id', $request->course_id)
                ->where('student_id', $studentId)
                ->exists();

            if (!$exists) {
                Enrollment::create([
                    'course_id' => $request->course_id,
                    'student_id' => $studentId,
                    'enrolled_at' => now(),
                ]);
                $successCount++;
            } else {
                $failCount++;
            }
        }

        return redirect()->route('enrollments.index')
            ->with('success', "$successCount students enrolled successfully. $failCount were already enrolled.");
    }
}
