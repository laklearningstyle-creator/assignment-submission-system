<?php

namespace App\Http\Controllers;

use App\Models\Submission;
use App\Models\Grade;
use App\Models\Assignment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GradeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of grades (Admin/Teacher view).
     */
    public function index(Request $request)
    {
        $submission_id = $request->get('submission_id');
        $assignment_id = $request->get('assignment_id');

        $grades = Grade::with(['submission.assignment.course', 'submission.student', 'grader'])
            ->when($submission_id, function ($query, $submission_id) {
                return $query->where('submission_id', $submission_id);
            })
            ->when($assignment_id, function ($query, $assignment_id) {
                return $query->whereHas('submission', function($q) use ($assignment_id) {
                    $q->where('assignment_id', $assignment_id);
                });
            })
            ->orderBy('graded_at', 'desc')
            ->paginate(15);

        return view('grades.index', compact('grades'));
    }

    /**
     * Show the form for creating a new grade.
     */
    public function create(Request $request)
    {
        // Get submissions that don't have grades yet
        $submissions = Submission::with(['student', 'assignment.course'])
            ->whereDoesntHave('grade')
            ->whereHas('assignment', function($query) {
                // For teachers, only show submissions from their courses
                if (Auth::user()->role_id != 1) {
                    $query->whereHas('course', function($q) {
                        $q->where('created_by', Auth::user()->user_id);
                    });
                }
            })
            ->orderBy('submitted_at', 'desc')
            ->get();

        // Get selected submission ID from request
        $selectedSubmissionId = $request->get('submission_id');

        return view('grades.create', compact('submissions', 'selectedSubmissionId'));
    }

    /**
     * Store a newly created grade.
     */
    public function store(Request $request)
    {
        $request->validate([
            'submission_id' => 'required|exists:submissions,submission_id',
            'marks_obtained' => 'required|numeric|min:0',
            'grade' => 'nullable|string|max:2'
        ]);

        $submission = Submission::with('assignment')->findOrFail($request->submission_id);

        // Check if grade already exists
        if ($submission->grade) {
            return redirect()->back()
                ->with('error', 'Grade already exists for this submission.')
                ->withInput();
        }

        // Validate marks against total
        $totalMarks = $submission->assignment->total_marks ?? 100;
        if ($request->marks_obtained > $totalMarks) {
            return redirect()->back()
                ->with('error', 'Marks obtained cannot exceed ' . $totalMarks)
                ->withInput();
        }

        // Calculate grade letter if not provided
        $gradeLetter = $request->grade ?? $this->calculateGrade($request->marks_obtained, $totalMarks);

        Grade::create([
            'submission_id' => $request->submission_id,
            'marks_obtained' => $request->marks_obtained,
            'grade' => $gradeLetter,
            'graded_by' => Auth::user()->user_id,
            'graded_at' => now()
        ]);

        // Update submission status
        $submission->update(['status' => 'Graded']);

        return redirect()->route('grades.index')
            ->with('success', 'Grade assigned successfully.');
    }

    /**
     * Display the specified grade.
     */
    public function show($id)
    {
        $grade = Grade::with(['submission.assignment.course', 'submission.student', 'grader'])
            ->findOrFail($id);

        // Check permission
        if (Auth::user()->role_id != 1 &&
            Auth::user()->role_id != 2 &&
            $grade->submission->student_id != Auth::user()->user_id) {
            return redirect()->route('dashboard')
                ->with('error', 'You do not have permission to view this grade.');
        }

        return view('grades.show', compact('grade'));
    }

    /**
     * Show the form for editing a grade.
     */
    public function edit($id)
    {
        $grade = Grade::with('submission.assignment.course')->findOrFail($id);
        $submission = $grade->submission;

        // Check permission (only admin or teacher who owns the course)
        if (Auth::user()->role_id != 1) {
            $courseTeacher = $submission->assignment->course->created_by ?? null;
            if ($courseTeacher != Auth::user()->user_id) {
                return redirect()->route('grades.index')
                    ->with('error', 'You do not have permission to edit this grade.');
            }
        }

        return view('grades.edit', compact('grade', 'submission'));
    }

    /**
     * Update a grade.
     */
    public function update(Request $request, $id)
    {
        $grade = Grade::findOrFail($id);

        $request->validate([
            'marks_obtained' => 'required|numeric|min:0',
            'grade' => 'nullable|string|max:2'
        ]);

        $totalMarks = $grade->submission->assignment->total_marks ?? 100;

        if ($request->marks_obtained > $totalMarks) {
            return redirect()->back()
                ->with('error', 'Marks obtained cannot exceed ' . $totalMarks)
                ->withInput();
        }

        $gradeLetter = $request->grade ?? $this->calculateGrade($request->marks_obtained, $totalMarks);

        $grade->update([
            'marks_obtained' => $request->marks_obtained,
            'grade' => $gradeLetter,
            'graded_by' => Auth::user()->user_id,
            'graded_at' => now()
        ]);

        return redirect()->route('grades.index')
            ->with('success', 'Grade updated successfully.');
    }

    /**
     * Delete a grade.
     */
    public function destroy($id)
    {
        $grade = Grade::findOrFail($id);

        // Check permission (only admin or teacher who owns the course)
        if (Auth::user()->role_id != 1) {
            $courseTeacher = $grade->submission->assignment->course->created_by ?? null;
            if ($courseTeacher != Auth::user()->user_id) {
                return redirect()->route('grades.index')
                    ->with('error', 'You do not have permission to delete this grade.');
            }
        }

        $submission = $grade->submission;
        $grade->delete();
        $submission->update(['status' => 'Submitted']);

        return redirect()->route('grades.index')
            ->with('success', 'Grade deleted successfully.');
    }

    /**
     * Calculate grade letter based on percentage.
     */
    private function calculateGrade($marksObtained, $totalMarks)
    {
        $percentage = ($marksObtained / $totalMarks) * 100;

        if ($percentage >= 90) {
            return 'A+';
        } elseif ($percentage >= 80) {
            return 'A';
        } elseif ($percentage >= 70) {
            return 'B';
        } elseif ($percentage >= 60) {
            return 'C';
        } elseif ($percentage >= 50) {
            return 'D';
        } else {
            return 'F';
        }
    }

    /**
     * Display grades for a specific assignment (Teacher view).
     */
    public function byAssignment($assignment_id)
    {
        $assignment = Assignment::with('course')->findOrFail($assignment_id);

        // Check permission
        if (Auth::user()->role_id != 1) {
            $courseTeacher = $assignment->course->created_by ?? null;
            if ($courseTeacher != Auth::user()->user_id) {
                return redirect()->route('assignments.index')
                    ->with('error', 'You do not have permission to view grades for this assignment.');
            }
        }

        $grades = Grade::with('submission.student')
            ->whereHas('submission', function ($query) use ($assignment_id) {
                $query->where('assignment_id', $assignment_id);
            })
            ->orderBy('graded_at', 'desc')
            ->paginate(15);

        return view('grades.by-assignment', compact('grades', 'assignment', 'assignment_id'));
    }

    /**
     * Display student's own grades.
     */
    public function myGrades()
    {
        $studentId = Auth::user()->user_id;

        $grades = Grade::with(['submission.assignment.course', 'grader'])
            ->whereHas('submission', function ($query) use ($studentId) {
                $query->where('student_id', $studentId);
            })
            ->orderBy('graded_at', 'desc')
            ->paginate(15);

        // Calculate statistics
        $totalMarks = 0;
        $totalPossible = 0;
        $courseGrades = [];

        foreach ($grades as $grade) {
            $totalMarks += $grade->marks_obtained;
            $courseName = $grade->submission->assignment->course->course_name ?? 'Unknown';
            $totalPossible += $grade->submission->assignment->total_marks ?? 100;

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
     * Get grade statistics for a course (Teacher/Admin view).
     */
    public function courseStatistics($course_id)
    {
        $grades = Grade::with(['submission.assignment', 'submission.student'])
            ->whereHas('submission.assignment', function ($query) use ($course_id) {
                $query->where('course_id', $course_id);
            })
            ->get();

        $averageScore = $grades->avg('marks_obtained') ?? 0;
        $totalStudents = $grades->groupBy('submission.student_id')->count();
        $gradeDistribution = [
            'A+' => $grades->where('grade', 'A+')->count(),
            'A' => $grades->where('grade', 'A')->count(),
            'B' => $grades->where('grade', 'B')->count(),
            'C' => $grades->where('grade', 'C')->count(),
            'D' => $grades->where('grade', 'D')->count(),
            'F' => $grades->where('grade', 'F')->count(),
        ];

        return response()->json([
            'average_score' => round($averageScore, 2),
            'total_students' => $totalStudents,
            'total_grades' => $grades->count(),
            'distribution' => $gradeDistribution
        ]);
    }
}
