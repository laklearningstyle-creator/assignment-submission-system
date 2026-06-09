<?php

namespace App\Http\Controllers;

use App\Models\Enrollment;
use App\Models\Submission;
use App\Models\Grade;
use App\Models\Course;
use App\Models\Feedback;
use App\Models\Assignment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StudentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        // Student role check
        $this->middleware(function ($request, $next) {
            if (auth()->user()->role_id != 3) {
                abort(403, 'Unauthorized access. Student only.');
            }
            return $next($request);
        });
    }

    /**
     * Display student dashboard
     */
    public function dashboard()
    {
        $studentId = Auth::user()->user_id;

        // Get enrolled courses with pagination
        $enrollments = Enrollment::with(['course.creator', 'course.assignments'])
            ->where('student_id', $studentId)
            ->orderBy('enrolled_at', 'desc')
            ->paginate(6);

        $myCourses = $enrollments->pluck('course');

        // Get total submissions
        $totalSubmissions = Submission::where('student_id', $studentId)->count();

        // Get pending submissions (submitted but not graded)
        $pendingSubmissions = Submission::where('student_id', $studentId)
            ->whereDoesntHave('grade')
            ->count();

        // Get average grade
        $averageGrade = Grade::whereHas('submission', function($query) use ($studentId) {
                $query->where('student_id', $studentId);
            })
            ->avg('marks_obtained') ?? 0;

        // Get recent submissions
        $mySubmissions = Submission::with(['assignment.course', 'grade'])
            ->where('student_id', $studentId)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        // Get upcoming assignments (not yet submitted)
        $enrolledCourseIds = $myCourses->pluck('course_id')->toArray();

        $submittedAssignmentIds = Submission::where('student_id', $studentId)
            ->pluck('assignment_id')
            ->toArray();

        $upcomingAssignments = Assignment::whereIn('course_id', $enrolledCourseIds)
            ->whereNotIn('assignment_id', $submittedAssignmentIds)
            ->where('due_date', '>', now())
            ->where('status', 'Published')
            ->orderBy('due_date', 'asc')
            ->limit(5)
            ->get();

        return view('student.dashboard', compact(
            'enrollments',
            'myCourses',
            'totalSubmissions',
            'pendingSubmissions',
            'averageGrade',
            'mySubmissions',
            'upcomingAssignments'
        ));
    }

    /**
     * Display student's enrolled courses
     */
    public function myCourses()
    {
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
     * Display student's submissions
     */
    public function mySubmissions()
    {
        $studentId = Auth::user()->user_id;

        $submissions = Submission::with(['assignment.course', 'grade'])
            ->where('student_id', $studentId)
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        // Get statistics
        $totalSubmissions = Submission::where('student_id', $studentId)->count();
        $gradedCount = Submission::where('student_id', $studentId)
            ->whereHas('grade')
            ->count();
        $pendingCount = $totalSubmissions - $gradedCount;

        return view('student.submissions', compact('submissions', 'totalSubmissions', 'gradedCount', 'pendingCount'));
    }

    /**
     * Display student's grades
     */
    public function myGrades()
    {
        $studentId = Auth::user()->user_id;

        // Use 'grader' instead of 'gradedBy' (or make sure gradedBy exists in Grade model)
        $grades = Grade::with(['submission.assignment.course', 'grader'])
            ->whereHas('submission', function($query) use ($studentId) {
                $query->where('student_id', $studentId);
            })
            ->orderBy('graded_at', 'desc')
            ->paginate(15);

        // Calculate overall statistics
        $totalMarks = 0;
        $totalPossible = 0;
        $courseGrades = [];

        foreach ($grades as $grade) {
            $totalMarks += $grade->marks_obtained;
            $totalPossible += $grade->submission->assignment->total_marks ?? 100;

            $courseName = $grade->submission->assignment->course->course_name ?? 'Unknown';
            if (!isset($courseGrades[$courseName])) {
                $courseGrades[$courseName] = ['marks' => 0, 'possible' => 0, 'count' => 0];
            }
            $courseGrades[$courseName]['marks'] += $grade->marks_obtained;
            $courseGrades[$courseName]['possible'] += $grade->submission->assignment->total_marks ?? 100;
            $courseGrades[$courseName]['count']++;
        }

        $overallPercentage = $totalPossible > 0 ? round(($totalMarks / $totalPossible) * 100) : 0;

        return view('student.grades', compact('grades', 'overallPercentage', 'courseGrades'));
    }

    /**
     * Display student's feedback
     */
    public function myFeedback()
    {
        $studentId = Auth::user()->user_id;

        $feedbacks = Feedback::with(['submission.assignment.course', 'teacher'])
            ->whereHas('submission', function($query) use ($studentId) {
                $query->where('student_id', $studentId);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('student.feedback', compact('feedbacks'));
    }

    /**
     * Display single course details for student
     */
    public function courseDetails($courseId)
    {
        $studentId = Auth::user()->user_id;

        // Check if student is enrolled
        $isEnrolled = Enrollment::where('student_id', $studentId)
            ->where('course_id', $courseId)
            ->exists();

        if (!$isEnrolled) {
            return redirect()->route('student.my-courses')
                ->with('error', 'You are not enrolled in this course.');
        }

        $course = Course::with(['creator', 'assignments' => function($query) {
                $query->orderBy('due_date', 'asc');
            }])
            ->findOrFail($courseId);

        // Get student's submissions for this course
        $submissions = Submission::with(['assignment', 'grade'])
            ->where('student_id', $studentId)
            ->whereHas('assignment', function($query) use ($courseId) {
                $query->where('course_id', $courseId);
            })
            ->get()
            ->keyBy('assignment_id');

        return view('student.course-details', compact('course', 'submissions'));
    }

    /**
     * Get student's progress for a specific course
     */
    public function courseProgress($courseId)
    {
        $studentId = Auth::user()->user_id;

        $course = Course::findOrFail($courseId);

        $totalAssignments = $course->assignments->count();
        $submittedAssignments = Submission::where('student_id', $studentId)
            ->whereHas('assignment', function($query) use ($courseId) {
                $query->where('course_id', $courseId);
            })
            ->count();

        $gradedAssignments = Submission::where('student_id', $studentId)
            ->whereHas('assignment', function($query) use ($courseId) {
                $query->where('course_id', $courseId);
            })
            ->whereHas('grade')
            ->count();

        $progress = $totalAssignments > 0 ? round(($submittedAssignments / $totalAssignments) * 100) : 0;

        return response()->json([
            'total_assignments' => $totalAssignments,
            'submitted' => $submittedAssignments,
            'graded' => $gradedAssignments,
            'progress' => $progress,
            'pending' => $totalAssignments - $submittedAssignments
        ]);
    }
}
