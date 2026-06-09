<?php

namespace App\Http\Controllers;

use App\Models\Assignment;
use App\Models\Submission;
use App\Models\SubmissionFile;
use App\Models\Enrollment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class SubmissionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of submissions (Teacher/Admin view).
     */
    public function index(Request $request)
    {
        $assignment_id = $request->get('assignment_id');
        $student_id = $request->get('student_id');

        $submissions = Submission::with(['student', 'assignment.course', 'grade'])
            ->when($assignment_id, function ($query, $assignment_id) {
                return $query->where('assignment_id', $assignment_id);
            })
            ->when($student_id, function ($query, $student_id) {
                return $query->where('student_id', $student_id);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        $assignments = Assignment::where('status', 'Published')->get();

        return view('submissions.index', compact('submissions', 'assignments'));
    }

    /**
     * Show the form for creating a new submission (Student view).
     */
    public function create(Request $request)
    {
        $assignment_id = $request->get('assignment_id');

        if (!$assignment_id) {
            return redirect()->route('assignments.index')
                ->with('error', 'Please select an assignment first.');
        }

        $assignment = Assignment::with('course')->findOrFail($assignment_id);

        // Check if student is enrolled in the course
        $isEnrolled = Enrollment::where('student_id', Auth::user()->user_id)
            ->where('course_id', $assignment->course_id)
            ->exists();

        if (!$isEnrolled) {
            return redirect()->route('assignments.index')
                ->with('error', 'You are not enrolled in this course.');
        }

        // Check deadline
        $deadlinePassed = Carbon::now()->gt($assignment->due_date);
        if ($deadlinePassed && !$assignment->allow_late_submission) {
            return redirect()->route('assignments.show', $assignment_id)
                ->with('error', 'Submission deadline has passed and late submissions are not allowed.');
        }

        // Check if already submitted
        $existingSubmission = Submission::where('assignment_id', $assignment_id)
            ->where('student_id', Auth::user()->user_id)
            ->first();

        return view('submissions.create', compact('assignment', 'existingSubmission'));
    }

    /**
     * Store a newly created submission.
     */
    public function store(Request $request)
    {
        $request->validate([
            'assignment_id' => 'required|exists:assignments,assignment_id',
            'submission_text' => 'nullable|string',
            'file' => 'nullable|file|max:10240', // 10MB max
        ]);

        $assignment = Assignment::findOrFail($request->assignment_id);

        // Check if student is enrolled
        $isEnrolled = Enrollment::where('student_id', Auth::user()->user_id)
            ->where('course_id', $assignment->course_id)
            ->exists();

        if (!$isEnrolled) {
            return redirect()->route('assignments.index')
                ->with('error', 'You are not enrolled in this course.');
        }

        // Check deadline
        $deadlinePassed = Carbon::now()->gt($assignment->due_date);
        if ($deadlinePassed && !$assignment->allow_late_submission) {
            return redirect()->route('assignments.show', $request->assignment_id)
                ->with('error', 'Submission deadline has passed. Late submissions are not allowed.');
        }

        // Check for existing submission
        $existingSubmission = Submission::where('assignment_id', $request->assignment_id)
            ->where('student_id', Auth::user()->user_id)
            ->first();

        // Determine if submission is late
        $isLate = Carbon::now()->gt($assignment->due_date);

        // Handle file upload
        $filePath = null;
        $uploadedFile = null;

        if ($request->hasFile('file')) {
            $uploadedFile = $request->file('file');
            $fileName = time() . '_' . Auth::user()->user_id . '_' . $uploadedFile->getClientOriginalName();
            $filePath = $uploadedFile->storeAs('submissions/' . $request->assignment_id, $fileName, 'public');
        }

        // Create or update submission
        if ($existingSubmission) {
            // Check if graded before allowing update
            if ($existingSubmission->grade) {
                return redirect()->route('student.submissions.index')
                    ->with('error', 'Cannot update a graded submission.');
            }

            $existingSubmission->update([
                'submission_text' => $request->submission_text,
                'submitted_at' => now(),
                'is_late' => $isLate,
                'status' => 'resubmitted'
            ]);

            if ($filePath && $uploadedFile) {
                SubmissionFile::create([
                    'submission_id' => $existingSubmission->submission_id,
                    'file_path' => $filePath,
                    'file_name' => $uploadedFile->getClientOriginalName(),
                    'file_size' => $uploadedFile->getSize(),
                ]);
            }

            return redirect()->route('student.submissions.index')
                ->with('success', 'Assignment resubmitted successfully.');
        } else {
            $submission = Submission::create([
                'assignment_id' => $request->assignment_id,
                'student_id' => Auth::user()->user_id,
                'submission_text' => $request->submission_text,
                'submitted_at' => now(),
                'is_late' => $isLate,
                'status' => 'submitted'
            ]);

            if ($filePath && $uploadedFile) {
                SubmissionFile::create([
                    'submission_id' => $submission->submission_id,
                    'file_path' => $filePath,
                    'file_name' => $uploadedFile->getClientOriginalName(),
                    'file_size' => $uploadedFile->getSize(),
                ]);
            }

            return redirect()->route('student.submissions.index')
                ->with('success', 'Assignment submitted successfully.');
        }
    }

    /**
     * Display the specified submission.
     */
    public function show($id)
    {
        $submission = Submission::with(['student', 'assignment.course', 'grade'])
            ->findOrFail($id);

        // Check permission
        if (Auth::user()->role_id != 1 &&
            Auth::user()->role_id != 2 &&
            $submission->student_id != Auth::user()->user_id) {
            return redirect()->route('dashboard')
                ->with('error', 'You do not have permission to view this submission.');
        }

        return view('submissions.show', compact('submission'));
    }

    /**
     * Show the form for editing a submission (Student view).
     */
    public function edit($id)
    {
        $submission = Submission::with(['assignment.course'])->findOrFail($id);

        // Only the student who submitted can edit, and only if not graded
        if ($submission->student_id != Auth::user()->user_id) {
            return redirect()->route('dashboard')
                ->with('error', 'You do not have permission to edit this submission.');
        }

        if ($submission->grade) {
            return redirect()->route('student.submissions.index')
                ->with('error', 'Cannot edit a graded submission.');
        }

        return view('submissions.edit', compact('submission'));
    }

/**
 * Update a submission.
 */
public function update(Request $request, $id)
{
    $submission = Submission::findOrFail($id);

    if ($submission->student_id != Auth::user()->user_id) {
        return redirect()->route('dashboard')
            ->with('error', 'You do not have permission to update this submission.');
    }

    $request->validate([
        'submission_text' => 'nullable|string',
        'file' => 'nullable|file|max:10240',
    ]);

    $assignment = $submission->assignment;

    // Check deadline
    $deadlinePassed = Carbon::now()->gt($assignment->due_date);
    if ($deadlinePassed && !$assignment->allow_late_submission) {
        return redirect()->route('student.submissions.index')
            ->with('error', 'Cannot update submission. Deadline has passed.');
    }

    $isLate = Carbon::now()->gt($assignment->due_date);

    $submission->update([
        'submission_text' => $request->submission_text,
        'submitted_at' => now(),
        'is_late' => $isLate,
        'status' => 'Resubmitted'  // Changed from 'updated' to 'Resubmitted'
    ]);

    if ($request->hasFile('file')) {
        $file = $request->file('file');
        $fileName = time() . '_' . Auth::user()->user_id . '_' . $file->getClientOriginalName();
        $filePath = $file->storeAs('submissions/' . $assignment->assignment_id, $fileName, 'public');

        SubmissionFile::create([
            'submission_id' => $submission->submission_id,
            'file_path' => $filePath,
            'file_name' => $file->getClientOriginalName(),
            'file_size' => $file->getSize(),
        ]);
    }

    return redirect()->route('student.submissions.index')
        ->with('success', 'Submission updated successfully.');
}
    /**
     * Delete a submission.
     */
    public function destroy($id)
    {
        $submission = Submission::findOrFail($id);

        if ($submission->student_id != Auth::user()->user_id && Auth::user()->role_id != 1) {
            return redirect()->route('dashboard')
                ->with('error', 'You do not have permission to delete this submission.');
        }

        // Delete associated files
        $files = SubmissionFile::where('submission_id', $submission->submission_id)->get();
        foreach ($files as $file) {
            Storage::disk('public')->delete($file->file_path);
            $file->delete();
        }

        $submission->delete();

        return redirect()->route('student.submissions.index')
            ->with('success', 'Submission deleted successfully.');
    }

    /**
     * Display student's own submissions.
     */
    public function mySubmissions()
    {
        $submissions = Submission::with(['assignment.course', 'grade'])
            ->where('student_id', Auth::user()->user_id)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        // Get statistics
        $totalSubmissions = Submission::where('student_id', Auth::user()->user_id)->count();
        $gradedCount = Submission::where('student_id', Auth::user()->user_id)
            ->whereHas('grade')
            ->count();
        $pendingCount = $totalSubmissions - $gradedCount;

        return view('student.submissions', compact('submissions', 'totalSubmissions', 'gradedCount', 'pendingCount'));
    }

    /**
     * Download submission file.
     */
    public function downloadFile($id)
    {
        $file = SubmissionFile::findOrFail($id);
        $submission = $file->submission;

        // Check permission
        if (Auth::user()->role_id != 1 &&
            Auth::user()->role_id != 2 &&
            $submission->student_id != Auth::user()->user_id) {
            return redirect()->route('dashboard')
                ->with('error', 'You do not have permission to download this file.');
        }

        if (!Storage::disk('public')->exists($file->file_path)) {
            return redirect()->back()
                ->with('error', 'File not found.');
        }

        return Storage::disk('public')->download($file->file_path, $file->file_name);
    }
}
