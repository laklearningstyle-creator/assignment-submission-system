<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CourseController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Check if user is authorized for course management (Admin or Teacher)
     */
    private function canManageCourses()
    {
        return in_array(Auth::user()->role_id, [1, 2]); // 1=Admin, 2=Teacher
    }

    /**
     * Display a listing of courses.
     */
    public function index()
    {
        $user = Auth::user();

        $courses = Course::with(['creator', 'assignments', 'enrollments'])
            ->when($user->role_id == 2, function ($query) use ($user) {
                // Teachers only see their own courses
                return $query->where('created_by', $user->user_id);
            })
            ->when($user->role_id == 3, function ($query) use ($user) {
                // Students only see courses they are enrolled in
                $enrolledCourseIds = \App\Models\Enrollment::where('student_id', $user->user_id)
                    ->pluck('course_id');
                return $query->whereIn('course_id', $enrolledCourseIds);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('courses.index', compact('courses'));
    }

    /**
     * Show the form for creating a new course.
     */
    public function create()
    {
        // Only Admin and Teacher can create courses
        if (!$this->canManageCourses()) {
            return redirect()->route('courses.index')
                ->with('error', 'Unauthorized action. Only teachers and admins can create courses.');
        }

        return view('courses.create');
    }

    /**
     * Store a newly created course in storage.
     */
    public function store(Request $request)
    {
        // Only Admin and Teacher can create courses
        if (!$this->canManageCourses()) {
            return redirect()->route('courses.index')
                ->with('error', 'Unauthorized action.');
        }

        $request->validate([
            'course_name' => 'required|string|max:255',
            'course_code' => 'required|string|max:50|unique:courses,course_code',
            'description' => 'nullable|string',
        ]);

        Course::create([
            'course_name' => $request->course_name,
            'course_code' => $request->course_code,
            'description' => $request->description,
            'created_by' => Auth::user()->user_id,
            'status' => 'active',
        ]);

        return redirect()->route('courses.index')
            ->with('success', 'Course created successfully.');
    }

    /**
     * Display the specified course.
     */
    public function show($id)
    {
        $course = Course::with(['creator', 'assignments.submissions', 'enrollments.student'])
            ->findOrFail($id);

        // Check if student is enrolled to view course
        $user = Auth::user();
        if ($user->role_id == 3) {
            $isEnrolled = \App\Models\Enrollment::where('student_id', $user->user_id)
                ->where('course_id', $id)
                ->exists();

            if (!$isEnrolled) {
                return redirect()->route('courses.index')
                    ->with('error', 'You are not enrolled in this course.');
            }
        }

        // Check if teacher owns the course
        if ($user->role_id == 2 && $course->created_by != $user->user_id) {
            return redirect()->route('courses.index')
                ->with('error', 'You do not have permission to view this course.');
        }

        return view('courses.show', compact('course'));
    }

    /**
     * Show the form for editing the specified course.
     */
    public function edit($id)
    {
        // Only Admin and Teacher can edit courses
        if (!$this->canManageCourses()) {
            return redirect()->route('courses.index')
                ->with('error', 'Unauthorized action.');
        }

        $course = Course::findOrFail($id);

        // Check permission - only creator or admin can edit
        if (Auth::user()->role_id != 1 && $course->created_by != Auth::user()->user_id) {
            return redirect()->route('courses.index')
                ->with('error', 'You do not have permission to edit this course.');
        }

        return view('courses.edit', compact('course'));
    }

    /**
     * Update the specified course in storage.
     */
    public function update(Request $request, $id)
    {
        // Only Admin and Teacher can update courses
        if (!$this->canManageCourses()) {
            return redirect()->route('courses.index')
                ->with('error', 'Unauthorized action.');
        }

        $course = Course::findOrFail($id);

        // Check permission
        if (Auth::user()->role_id != 1 && $course->created_by != Auth::user()->user_id) {
            return redirect()->route('courses.index')
                ->with('error', 'You do not have permission to update this course.');
        }

        $request->validate([
            'course_name' => 'required|string|max:255',
            'course_code' => 'required|string|max:50|unique:courses,course_code,' . $id . ',course_id',
            'description' => 'nullable|string',
            'status' => 'nullable|in:active,inactive',
        ]);

        $course->update([
            'course_name' => $request->course_name,
            'course_code' => $request->course_code,
            'description' => $request->description,
            'status' => $request->status ?? 'active',
        ]);

        return redirect()->route('courses.index')
            ->with('success', 'Course updated successfully.');
    }

    /**
     * Remove the specified course from storage.
     */
    public function destroy($id)
    {
        // Only Admin can delete courses
        if (Auth::user()->role_id != 1) {
            return redirect()->route('courses.index')
                ->with('error', 'Only administrators can delete courses.');
        }

        $course = Course::findOrFail($id);

        // Check if course has assignments
        if ($course->assignments()->count() > 0) {
            return redirect()->route('courses.index')
                ->with('error', 'Cannot delete course with existing assignments.');
        }

        // Check if course has enrollments
        if ($course->enrollments()->count() > 0) {
            return redirect()->route('courses.index')
                ->with('error', 'Cannot delete course with enrolled students.');
        }

        $course->delete();

        return redirect()->route('courses.index')
            ->with('success', 'Course deleted successfully.');
    }
}
