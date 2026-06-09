<?php

namespace App\Http\Controllers;

use App\Models\Report;
use App\Models\Submission;
use App\Models\Grade;
use App\Models\Assignment;
use App\Models\User;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ReportController extends Controller
{
    /**
     * Display a listing of reports.
     */
    public function index()
    {
        $reports = Report::with('generator')->orderBy('created_at', 'desc')->paginate(10);
        $assignments = Assignment::with('course')->orderBy('created_at', 'desc')->get();
        $students = User::where('role_id', 3)->orderBy('full_name')->get();
        $courses = Course::orderBy('course_name')->get();

        return view('reports.index', compact('reports', 'assignments', 'students', 'courses'));
    }

    /**
     * Generate submissions report.
     */
    public function generateSubmissionsReport(Request $request)
    {
        $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date'
        ]);

        $submissions = Submission::with(['student', 'assignment.course'])
            ->whereBetween('submitted_at', [$request->start_date, $request->end_date])
            ->get();

        // Generate CSV content
        $content = "Submission Report\n";
        $content .= "Period: {$request->start_date} to {$request->end_date}\n";
        $content .= "Generated: " . now() . "\n\n";
        $content .= "Student,Assignment,Course,Submitted At,Status,Is Late\n";

        foreach ($submissions as $submission) {
            $studentName = $submission->student->full_name ?? 'N/A';
            $assignmentTitle = $submission->assignment->title ?? 'N/A';
            $courseName = $submission->assignment->course->course_name ?? 'N/A';

            $content .= "\"{$studentName}\",";
            $content .= "\"{$assignmentTitle}\",";
            $content .= "\"{$courseName}\",";
            $content .= "{$submission->submitted_at},";
            $content .= "{$submission->status},";
            $content .= ($submission->is_late ? 'Yes' : 'No') . "\n";
        }

        $fileName = 'submissions_report_' . date('Y-m-d_His') . '.csv';
        $filePath = 'reports/' . $fileName;
        Storage::disk('public')->put($filePath, $content);

        Report::create([
            'report_type' => 'Submissions Report',
            'report_date' => now(),
            'generated_by' => Auth::user()->user_id,
            'file_path' => $filePath,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('admin.reports.index')
            ->with('success', 'Submissions report generated successfully.');
    }

    /**
     * Generate grades report.
     */
    public function generateGradesReport(Request $request)
    {
        $request->validate([
            'assignment_id' => 'required|exists:assignments,assignment_id'
        ]);

        $assignment = Assignment::findOrFail($request->assignment_id);

        $grades = Grade::with(['submission.student'])
            ->whereHas('submission', function ($query) use ($request) {
                $query->where('assignment_id', $request->assignment_id);
            })
            ->get();

        // Generate CSV content
        $content = "Grades Report\n";
        $content .= "Assignment: {$assignment->title}\n";
        $content .= "Course: {$assignment->course->course_name}\n";
        $content .= "Total Marks: {$assignment->total_marks}\n";
        $content .= "Generated: " . now() . "\n\n";
        $content .= "Student,Email,Marks Obtained,Grade,Status\n";

        foreach ($grades as $grade) {
            $studentName = $grade->submission->student->full_name ?? 'N/A';
            $studentEmail = $grade->submission->student->email ?? 'N/A';

            $content .= "\"{$studentName}\",";
            $content .= "\"{$studentEmail}\",";
            $content .= "{$grade->marks_obtained},";
            $content .= "{$grade->grade},";
            $content .= "{$grade->submission->status}\n";
        }

        // Add students who didn't submit
        $enrolledStudents = $assignment->course->enrollments->pluck('student_id')->toArray();
        $submittedStudents = $grades->pluck('submission.student_id')->toArray();
        $nonSubmitters = array_diff($enrolledStudents, $submittedStudents);

        if (!empty($nonSubmitters)) {
            $content .= "\nStudents who did not submit:\n";
            $nonSubmitterNames = User::whereIn('user_id', $nonSubmitters)->pluck('full_name')->toArray();
            foreach ($nonSubmitterNames as $name) {
                $content .= "- {$name}\n";
            }
        }

        $fileName = 'grades_report_' . date('Y-m-d_His') . '.csv';
        $filePath = 'reports/' . $fileName;
        Storage::disk('public')->put($filePath, $content);

        Report::create([
            'report_type' => 'Grades Report',
            'report_date' => now(),
            'generated_by' => Auth::user()->user_id,
            'file_path' => $filePath,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('admin.reports.index')
            ->with('success', 'Grades report generated successfully.');
    }

    /**
     * Generate student performance report.
     */
    public function generatePerformanceReport(Request $request)
    {
        $request->validate([
            'student_id' => 'required|exists:users,user_id',
            'course_id' => 'required|exists:courses,course_id'
        ]);

        $student = User::findOrFail($request->student_id);
        $course = Course::findOrFail($request->course_id);

        $submissions = Submission::with(['assignment', 'grade'])
            ->where('student_id', $request->student_id)
            ->whereHas('assignment', function ($query) use ($request) {
                $query->where('course_id', $request->course_id);
            })
            ->get();

        // Generate CSV content
        $content = "Student Performance Report\n";
        $content .= "Student: {$student->full_name}\n";
        $content .= "Email: {$student->email}\n";
        $content .= "Course: {$course->course_name} ({$course->course_code})\n";
        $content .= "Generated: " . now() . "\n\n";
        $content .= "Assignment,Marks Obtained,Total Marks,Percentage,Grade,Submitted Date,Status\n";

        $totalMarks = 0;
        $obtainedMarks = 0;

        foreach ($submissions as $submission) {
            $assignmentTotal = $submission->assignment->total_marks;
            $marksObtained = $submission->grade ? $submission->grade->marks_obtained : 0;
            $gradeLetter = $submission->grade ? $submission->grade->grade : 'N/A';
            $percentage = $assignmentTotal > 0 ? ($marksObtained / $assignmentTotal) * 100 : 0;

            $totalMarks += $assignmentTotal;
            $obtainedMarks += $marksObtained;

            $content .= "\"{$submission->assignment->title}\",";
            $content .= "{$marksObtained},";
            $content .= "{$assignmentTotal},";
            $content .= round($percentage, 2) . "%,";
            $content .= "{$gradeLetter},";
            $content .= "{$submission->submitted_at},";
            $content .= "{$submission->status}\n";
        }

        $overallPercentage = $totalMarks > 0 ? ($obtainedMarks / $totalMarks) * 100 : 0;

        $content .= "\nSummary:\n";
        $content .= "Total Assignments: {$submissions->count()}\n";
        $content .= "Total Marks: {$obtainedMarks} / {$totalMarks}\n";
        $content .= "Overall Percentage: " . round($overallPercentage, 2) . "%\n";
        $content .= "Overall Grade: " . $this->calculateGrade($overallPercentage) . "\n";

        $fileName = 'performance_report_' . date('Y-m-d_His') . '.csv';
        $filePath = 'reports/' . $fileName;
        Storage::disk('public')->put($filePath, $content);

        Report::create([
            'report_type' => 'Student Performance Report',
            'report_date' => now(),
            'generated_by' => Auth::user()->user_id,
            'file_path' => $filePath,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('admin.reports.index')
            ->with('success', 'Student performance report generated successfully.');
    }

    /**
     * Download report file.
     */
    public function download($id)
    {
        $report = Report::findOrFail($id);

        // Check permission
        if (Auth::user()->role_id != 1 && $report->generated_by != Auth::user()->user_id) {
            return redirect()->route('admin.reports.index')
                ->with('error', 'You do not have permission to download this report.');
        }

        if (!Storage::disk('public')->exists($report->file_path)) {
            return redirect()->route('admin.reports.index')
                ->with('error', 'Report file not found.');
        }

        return Storage::disk('public')->download($report->file_path, basename($report->file_path));
    }

    /**
     * Delete report.
     */
    public function destroy($id)
    {
        $report = Report::findOrFail($id);

        // Check permission
        if (Auth::user()->role_id != 1 && $report->generated_by != Auth::user()->user_id) {
            return redirect()->route('admin.reports.index')
                ->with('error', 'You do not have permission to delete this report.');
        }

        // Delete file from storage
        if (Storage::disk('public')->exists($report->file_path)) {
            Storage::disk('public')->delete($report->file_path);
        }

        $report->delete();

        return redirect()->route('admin.reports.index')
            ->with('success', 'Report deleted successfully.');
    }

    /**
     * Calculate grade based on percentage.
     */
    private function calculateGrade($percentage)
    {
        if ($percentage >= 90) return 'A+';
        if ($percentage >= 80) return 'A';
        if ($percentage >= 70) return 'B';
        if ($percentage >= 60) return 'C';
        if ($percentage >= 50) return 'D';
        return 'F';
    }
}
