<?php

namespace App\Http\Controllers;

use App\Models\Feedback;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FeedbackController extends Controller
{

    // Store new feedback
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'nullable|string|max:255',
            'message' => 'required|string',
        ]);

        $feedback = Feedback::create([
            'user_id' => Auth::id(),
            'title' => $request->title,
            'message' => $request->message,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Feedback submitted successfully',
            'data' => $feedback,
        ]);
    }

    // Optional: list feedback of the authenticated user
    public function index()
    {
        $feedbacks = Feedback::where('user_id', Auth::id())->latest()->get();

        return response()->json([
            'success' => true,
            'data' => $feedbacks,
        ]);
    }
}
