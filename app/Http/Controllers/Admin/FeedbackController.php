<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Feedback;
use Illuminate\Http\Request;

class FeedbackController extends Controller
{
    // Show all feedback for admin
public function index(Request $request)
{
    $query = Feedback::with('user')->latest();

    if ($request->filled('date')) {
        // Filter by a single date (day)
        $date = $request->input('date');
        $query->whereDate('created_at', $date);
    }

    if ($request->filled('date_from') && $request->filled('date_to')) {
        // Filter by date range (between two days)
        $dateFrom = $request->input('date_from');
        $dateTo = $request->input('date_to');
        $query->whereBetween('created_at', [$dateFrom . ' 00:00:00', $dateTo . ' 23:59:59']);
    }

    $feedbacks = $query->paginate(10)->appends($request->all());

    return view('admin.feedback.index', compact('feedbacks'));
}


    // Show single feedback details
    public function show($id)
    {
        $feedback = Feedback::with('user')->findOrFail($id);
        return view('admin.feedback.show', compact('feedback'));
    }

    // Optional: Delete feedback
    public function destroy($id)
    {
        $feedback = Feedback::findOrFail($id);
        $feedback->delete();

        return redirect()->route('feedback.index')->with('success', 'Feedback deleted successfully.');
    }
}
