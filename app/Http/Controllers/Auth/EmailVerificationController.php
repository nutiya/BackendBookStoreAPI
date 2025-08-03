<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Foundation\Auth\EmailVerificationRequest;

class EmailVerificationController extends Controller
{
    // Called when user clicks verification link
    public function verify(EmailVerificationRequest $request)
    {
        $request->fulfill(); // sets email_verified_at
        return response()->json(['success' => true, 'message' => 'Email verified']);
    }

    // API to resend email if user didn’t get it
    public function resend(Request $request)
    {
        if ($request->user()->hasVerifiedEmail()) {
            return response()->json(['success' => false, 'message' => 'Email already verified'], 400);
        }

        $request->user()->sendEmailVerificationNotification();

        return response()->json(['success' => true, 'message' => 'Verification email resent']);
    }
}

