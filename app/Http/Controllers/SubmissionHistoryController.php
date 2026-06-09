<?php

namespace App\Http\Controllers;

use App\Models\Submission;
use App\Models\SubmissionHistory;
use Illuminate\Http\Request;

class SubmissionHistoryController extends Controller
{
    public function index(Request $request)
    {
        $submission_id = $request->get('submission_id');

        $histories = SubmissionHistory::with('submission', 'performer')
            ->when($submission_id, function ($query, $submission_id) {
                return $query->where('submission_id', $submission_id);
            })
            ->orderBy('performed_at', 'desc')
            ->paginate(20);

        return view('submission-history.index', compact('histories'));
    }

    public function show($id)
    {
        $history = SubmissionHistory::with('submission', 'performer')->findOrFail($id);
        return view('submission-history.show', compact('history'));
    }

    public static function log($submissionId, $action, $userId, $details = null)
    {
        SubmissionHistory::create([
            'submission_id' => $submissionId,
            'action' => $action,
            'performed_by' => $userId,
            'performed_at' => now(),
            'details' => $details
        ]);
    }
}
