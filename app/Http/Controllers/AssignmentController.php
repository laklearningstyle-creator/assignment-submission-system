<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Assignment;
use App\Models\Enrollment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class AssignmentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $user = Auth::user();
        $course_id = $request->get('course_id');
        $status = $request->get('status');

        // Load assignments with course, creator, and submissions count
        $assignments = Assignment::with(['course', 'creator'])
            ->withCount('submissions')
            ->when($course_id, function ($query, $course_id) {
                return $query->where('course_id', $course_id);
            })
            ->when($status, function ($query, $status) {
                return $query->where('status', $status);
            })
            ->when($user->role_id == 2, function ($query) use ($user) {
                // Teachers only see assignments from their courses
                return $query->whereHas('course', function ($q) use ($user) {
                    $q->where('created_by', $user->user_id);
                });
            })
            ->when($user->role_id == 3, function ($query) use ($user) {
                // Students only see assignments from enrolled courses
                $enrolledCourseIds = Enrollment::where('student_id', $user->user_id)
                    ->pluck('course_id');
                return $query->whereIn('course_id', $enrolledCourseIds)
                    ->where('status', 'Published'); // Students only see published assignments
            })
            ->orderBy('due_date', 'asc')
            ->paginate(10);

        $courses = Course::orderBy('course_name', 'asc')->get();

        return view('assignments.index', compact('assignments', 'courses'));
    }

    /**
     * Show the form for creating a new assignment.
     * Only Admin (role_id=1) and Teacher (role_id=2) can access
     */
    public function create()
    {
        // Prevent students from accessing assignment creation
        if (!in_array(Auth::user()->role_id, [1, 2])) {
            return redirect()->route('assignments.index')
                ->with('error', 'You do not have permission to create assignments.');
        }

        $courses = Course::orderBy('course_name', 'asc')
            ->when(Auth::user()->role_id == 2, function ($query) {
                // Teachers can only create assignments for their own courses
                return $query->where('created_by', Auth::user()->user_id);
            })
            ->get();

        if ($courses->isEmpty()) {
            return redirect()->route('courses.index')
                ->with('error', 'Please create a course first before creating an assignment.');
        }

        return view('assignments.create', compact('courses'));
    }

    /**
     * Store a newly created assignment.
     * Only Admin (role_id=1) and Teacher (role_id=2) can access
     */
    public function store(Request $request)
    {
        // Prevent students from storing assignments
        if (!in_array(Auth::user()->role_id, [1, 2])) {
            return redirect()->route('assignments.index')
                ->with('error', 'You do not have permission to create assignments.');
        }

        $request->validate([
            'course_id' => 'required|exists:courses,course_id',
            'title' => 'required|string|max:150',
            'description' => 'nullable|string',
            'total_marks' => 'required|numeric|min:0|max:999.99',
            'start_date' => 'required|date',
            'due_date' => 'required|date|after:start_date',
            'allow_late_submission' => 'sometimes|boolean',
            'status' => 'required|in:Draft,Published,Closed'
        ]);

        // Check if teacher owns the course
        if (Auth::user()->role_id == 2) {
            $course = Course::find($request->course_id);
            if (!$course || $course->created_by != Auth::user()->user_id) {
                return redirect()->back()
                    ->with('error', 'You can only create assignments for your own courses.')
                    ->withInput();
            }
        }

        Assignment::create([
            'course_id' => $request->course_id,
            'title' => $request->title,
            'description' => $request->description,
            'total_marks' => $request->total_marks,
            'start_date' => Carbon::parse($request->start_date),
            'due_date' => Carbon::parse($request->due_date),
            'allow_late_submission' => $request->has('allow_late_submission'),
            'created_by' => Auth::user()->user_id,
            'status' => $request->status
        ]);

        return redirect()->route('assignments.index')
            ->with('success', 'Assignment created successfully.');
    }

    public function show($id)
    {
        $assignment = Assignment::with([
            'course',
            'creator',
            'submissions.student',
            'submissions.grade'
        ])->findOrFail($id);

        $user = Auth::user();

        // Check if student is enrolled to view assignment
        if ($user->role_id == 3) {
            $isEnrolled = Enrollment::where('student_id', $user->user_id)
                ->where('course_id', $assignment->course_id)
                ->exists();

            if (!$isEnrolled) {
                return redirect()->route('assignments.index')
                    ->with('error', 'You are not enrolled in this course.');
            }
        }

        return view('assignments.show', compact('assignment'));
    }

    /**
     * Show the form for editing an assignment.
     * Only Admin (role_id=1) and Teacher (role_id=2) can access
     */
    public function edit($id)
    {
        // Prevent students from editing assignments
        if (!in_array(Auth::user()->role_id, [1, 2])) {
            return redirect()->route('assignments.index')
                ->with('error', 'You do not have permission to edit assignments.');
        }

        $assignment = Assignment::findOrFail($id);
        $courses = Course::orderBy('course_name', 'asc')
            ->when(Auth::user()->role_id == 2, function ($query) {
                return $query->where('created_by', Auth::user()->user_id);
            })
            ->get();

        // Check permission (admin or creator can edit)
        if (Auth::user()->role_id != 1 && $assignment->created_by != Auth::user()->user_id) {
            return redirect()->route('assignments.index')
                ->with('error', 'You do not have permission to edit this assignment.');
        }

        return view('assignments.edit', compact('assignment', 'courses'));
    }

    /**
     * Update an assignment.
     * Only Admin (role_id=1) and Teacher (role_id=2) can access
     */
    public function update(Request $request, $id)
    {
        // Prevent students from updating assignments
        if (!in_array(Auth::user()->role_id, [1, 2])) {
            return redirect()->route('assignments.index')
                ->with('error', 'You do not have permission to update assignments.');
        }

        $assignment = Assignment::findOrFail($id);

        // Check permission
        if (Auth::user()->role_id != 1 && $assignment->created_by != Auth::user()->user_id) {
            return redirect()->route('assignments.index')
                ->with('error', 'You do not have permission to update this assignment.');
        }

        $request->validate([
            'course_id' => 'required|exists:courses,course_id',
            'title' => 'required|string|max:150',
            'description' => 'nullable|string',
            'total_marks' => 'required|numeric|min:0|max:999.99',
            'start_date' => 'required|date',
            'due_date' => 'required|date|after:start_date',
            'allow_late_submission' => 'sometimes|boolean',
            'status' => 'required|in:Draft,Published,Closed'
        ]);

        $assignment->update([
            'course_id' => $request->course_id,
            'title' => $request->title,
            'description' => $request->description,
            'total_marks' => $request->total_marks,
            'start_date' => Carbon::parse($request->start_date),
            'due_date' => Carbon::parse($request->due_date),
            'allow_late_submission' => $request->has('allow_late_submission'),
            'status' => $request->status
        ]);

        return redirect()->route('assignments.index')
            ->with('success', 'Assignment updated successfully.');
    }

    /**
     * Delete an assignment.
     * Only Admin (role_id=1) can delete
     */
    public function destroy($id)
    {
        // Only admin can delete assignments
        if (Auth::user()->role_id != 1) {
            return redirect()->route('assignments.index')
                ->with('error', 'Only administrators can delete assignments.');
        }

        $assignment = Assignment::findOrFail($id);

        // Check if assignment has submissions
        if ($assignment->submissions()->count() > 0) {
            return redirect()->route('assignments.index')
                ->with('error', 'Cannot delete assignment with existing submissions.');
        }

        $assignment->delete();

        return redirect()->route('assignments.index')
            ->with('success', 'Assignment deleted successfully.');
    }
}
