<?php

namespace App\Http\Controllers;

use App\Models\Submission;
use App\Models\Feedback;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FeedbackController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of feedback (Teacher/Admin view).
     */
    public function index(Request $request)
    {
        $submission_id = $request->get('submission_id');

        $feedbacks = Feedback::with('submission.student', 'submission.assignment', 'teacher')
            ->when($submission_id, function ($query, $submission_id) {
                return $query->where('submission_id', $submission_id);
            })
            ->when(Auth::user()->role_id == 2, function ($query) {
                // Teachers only see feedback for their own courses
                return $query->whereHas('submission.assignment.course', function ($q) {
                    $q->where('created_by', Auth::user()->user_id);
                });
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('feedbacks.index', compact('feedbacks'));
    }

    /**
     * Show the form for creating a new feedback.
     */
    public function create(Request $request)
    {
        $submission_id = $request->get('submission_id');

        if (!$submission_id) {
            return redirect()->route('submissions.index')
                ->with('error', 'Please select a submission first.');
        }

        $submission = Submission::with('student', 'assignment.course')->findOrFail($submission_id);

        // Check if teacher owns the course
        if (Auth::user()->role_id == 2) {
            $courseTeacher = $submission->assignment->course->created_by ?? null;
            if ($courseTeacher != Auth::user()->user_id) {
                return redirect()->route('submissions.index')
                    ->with('error', 'You can only provide feedback for submissions in your own courses.');
            }
        }

        // Check if feedback already exists
        $existingFeedback = Feedback::where('submission_id', $submission_id)->first();
        if ($existingFeedback) {
            return redirect()->route('feedbacks.edit', $existingFeedback->feedback_id)
                ->with('info', 'Feedback already exists. You can edit it.');
        }

        return view('feedbacks.create', compact('submission'));
    }

    /**
     * Store a newly created feedback.
     */
    public function store(Request $request)
    {
        $request->validate([
            'submission_id' => 'required|exists:submissions,submission_id',
            'comment' => 'required|string|min:3'
        ]);

        // Check if feedback already exists
        $exists = Feedback::where('submission_id', $request->submission_id)->exists();
        if ($exists) {
            return redirect()->back()
                ->with('error', 'Feedback already exists for this submission.');
        }

        // Verify teacher owns the course
        $submission = Submission::with('assignment.course')->find($request->submission_id);
        if (Auth::user()->role_id == 2) {
            $courseTeacher = $submission->assignment->course->created_by ?? null;
            if ($courseTeacher != Auth::user()->user_id) {
                return redirect()->back()
                    ->with('error', 'You can only provide feedback for submissions in your own courses.');
            }
        }

        Feedback::create([
            'submission_id' => $request->submission_id,
            'teacher_id' => Auth::user()->user_id,
            'comment' => $request->comment
        ]);

        return redirect()->route('feedbacks.index')
            ->with('success', 'Feedback added successfully.');
    }

    /**
     * Display the specified feedback.
     */
    public function show($id)
    {
        $feedback = Feedback::with('submission.student', 'submission.assignment.course', 'teacher')
            ->findOrFail($id);

        // Check permission
        if (Auth::user()->role_id != 1 && Auth::user()->role_id != 2) {
            if ($feedback->submission->student_id != Auth::user()->user_id) {
                return redirect()->route('dashboard')
                    ->with('error', 'You do not have permission to view this feedback.');
            }
        }

        return view('feedbacks.show', compact('feedback'));
    }

    /**
     * Show the form for editing feedback.
     */
    public function edit($id)
    {
        $feedback = Feedback::findOrFail($id);

        // Check permission (only the teacher who created or admin can edit)
        if (Auth::user()->role_id != 1 && $feedback->teacher_id != Auth::user()->user_id) {
            return redirect()->route('feedbacks.index')
                ->with('error', 'You do not have permission to edit this feedback.');
        }

        return view('feedbacks.edit', compact('feedback'));
    }

    /**
     * Update the specified feedback.
     */
    public function update(Request $request, $id)
    {
        $feedback = Feedback::findOrFail($id);

        // Check permission
        if (Auth::user()->role_id != 1 && $feedback->teacher_id != Auth::user()->user_id) {
            return redirect()->route('feedbacks.index')
                ->with('error', 'You do not have permission to update this feedback.');
        }

        $request->validate([
            'comment' => 'required|string|min:3'
        ]);

        $feedback->update(['comment' => $request->comment]);

        return redirect()->route('feedbacks.index')
            ->with('success', 'Feedback updated successfully.');
    }

    /**
     * Delete the specified feedback.
     */
    public function destroy($id)
    {
        $feedback = Feedback::findOrFail($id);

        // Check permission
        if (Auth::user()->role_id != 1 && $feedback->teacher_id != Auth::user()->user_id) {
            return redirect()->route('feedbacks.index')
                ->with('error', 'You do not have permission to delete this feedback.');
        }

        $feedback->delete();

        return redirect()->route('feedbacks.index')
            ->with('success', 'Feedback deleted successfully.');
    }

    /**
     * Display student's own feedback.
     */
    public function myFeedback()
    {
        $studentId = Auth::user()->user_id;

        $feedbacks = Feedback::with(['submission.assignment.course', 'teacher'])
            ->whereHas('submission', function ($query) use ($studentId) {
                $query->where('student_id', $studentId);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('student.feedback', compact('feedbacks'));
    }

    /**
     * Display feedback for a specific submission (Teacher view).
     */
    public function bySubmission($submission_id)
    {
        $submission = Submission::with('student', 'assignment.course')->findOrFail($submission_id);

        // Check permission for teacher
        if (Auth::user()->role_id == 2) {
            $courseTeacher = $submission->assignment->course->created_by ?? null;
            if ($courseTeacher != Auth::user()->user_id) {
                return redirect()->route('submissions.index')
                    ->with('error', 'You do not have permission to view feedback for this submission.');
            }
        }

        $feedbacks = Feedback::where('submission_id', $submission_id)
            ->with('teacher')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('feedbacks.by-submission', compact('submission', 'feedbacks'));
    }
}
