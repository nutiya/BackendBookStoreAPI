<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\PasswordReset;
use Illuminate\Auth\Events\Registered;
use Carbon\Carbon;

class AuthController extends Controller
{
public function register(Request $request)
{
    $validator = Validator::make($request->all(), [
        'name' => 'required|string|max:255',
        'email' => 'required|string|email|max:255|unique:users',
        'phone' => 'required|string|max:20|unique:users',
        'password' => 'required|string|min:8|confirmed',
    ]);

    if ($validator->fails()) {
        return response()->json([
            'success' => false,
            'message' => 'Validation errors',
            'errors' => $validator->errors()
        ], 422);
    }

    $user = User::create([
        'name' => $request->name,
        'email' => $request->email,
        'phone' => $request->phone,
        'password' => Hash::make($request->password),
    ]);

    // Send verification email
    event(new Registered($user));


    $token = $user->createToken('auth_token')->plainTextToken;

    return response()->json([
        'success' => true,
        'message' => 'User registered successfully',
        'data' => [
            'user' => $user,
            'token' => $token,
            'token_type' => 'Bearer'
        ]
    ], 201);
}


// In your Laravel AuthController.php login method
public function login(Request $request)
{
    $user = User::where('email', $request->email)->first();

    if (!$user || !Hash::check($request->password, $user->password)) {
        return response()->json(['message' => 'Invalid credentials'], 401);
    }

    if (!$user->hasVerifiedEmail()) {
        return response()->json(['message' => 'Please verify your email before logging in'], 403);
    }

    $token = $user->createToken('auth_token')->plainTextToken;

    return response()->json(['token' => $token, 'user' => $user]);
}


    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'success' => true,
            'message' => 'Logged out successfully'
        ]);
    }

    public function user(Request $request)
    {
        return response()->json([
            'success' => true,
            'data' => $request->user()
        ]);
    }



// Add these methods to your existing AuthController class

public function forgotPassword(Request $request)
{
    $validator = Validator::make($request->all(), [
        'email' => 'required|string|email|exists:users,email',
    ]);

    if ($validator->fails()) {
        return response()->json([
            'success' => false,
            'message' => 'Validation errors',
            'errors' => $validator->errors()
        ], 422);
    }

    // Delete previous reset codes for this email
    PasswordReset::where('email', $request->email)->delete();

    // Generate OTP code
    $code = rand(100000, 999999);
    $expiresAt = Carbon::now()->addMinutes(15);

    // Save reset request
    PasswordReset::create([
        'email' => $request->email,
        'code' => $code,
        'expires_at' => $expiresAt,
    ]);

    // TODO: Send the $code to the user via email
    // e.g., Mail::to($request->email)->send(new SendResetCodeMail($code));

    return response()->json([
        'success' => true,
        'message' => 'OTP sent to your email address',
    ]);
}

public function verifyResetCode(Request $request)
{
    $validator = Validator::make($request->all(), [
        'email' => 'required|email',
        'code' => 'required|string|size:6',
    ]);

    if ($validator->fails()) {
        return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
    }

    $record = PasswordReset::where('email', $request->email)
                            ->where('code', $request->code)
                            ->first();

    if (!$record) {
        return response()->json(['success' => false, 'message' => 'Invalid code'], 400);
    }

    if ($record->isExpired()) {
        $record->delete();
        return response()->json(['success' => false, 'message' => 'Code expired'], 400);
    }

    return response()->json(['success' => true, 'message' => 'Code verified']);
}
public function resetPassword(Request $request)
{
    $validator = Validator::make($request->all(), [
        'email' => 'required|email',
        'code' => 'required|string|size:6',
        'password' => 'required|string|min:8|confirmed',
    ]);

    if ($validator->fails()) {
        return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
    }

    $reset = PasswordReset::where('email', $request->email)
                          ->where('code', $request->code)
                          ->first();

    if (!$reset || $reset->isExpired()) {
        $reset?->delete();
        return response()->json(['success' => false, 'message' => 'Invalid or expired code'], 400);
    }

    $user = User::where('email', $request->email)->first();
    $user->update(['password' => Hash::make($request->password)]);

    $reset->delete();
    $user->tokens()->delete(); // Logout all devices

    return response()->json(['success' => true, 'message' => 'Password reset successfully']);
}
}