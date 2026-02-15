<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\PasswordReset;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendResetCodeMail;
use App\Mail\SendRegisterOtpMail;
use Illuminate\Support\Facades\Cache;


class AuthController extends Controller
{

public function requestRegisterOTP(Request $request)
    {
        // Validate input
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'min:2', 'max:255', 'regex:/^[\pL\s\-]+$/u'],
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

        // Generate OTP
        $otp = rand(100000, 999999);

        // Store data in cache for 10 minutes
        $cacheKey = 'register_otp_' . $request->email;

        Cache::put($cacheKey, [
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => $request->password, // Store plain, will hash after verification
            'otp' => $otp,
        ], now()->addMinutes(10));

        // Send OTP email
        Mail::to($request->email)->send(new SendRegisterOtpMail($otp));

        return response()->json([
            'success' => true,
            'message' => 'OTP sent to your email. Please verify to complete registration.'
        ]);
    }

    public function verifyRegisterOTP(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email',
            'otp' => 'required|string|size:6',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $cacheKey = 'register_otp_' . $request->email;

         // Check if OTP exists
        if (!Cache::has($cacheKey)) {
            return response()->json([
                'success' => false,
                'message' => 'OTP expired or not found, please request again.'
            ], 400);
        }

        $data = Cache::get($cacheKey);

        // Validate OTP
        if ($request->otp != $data['otp']) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid OTP.'
            ], 400);
        }

        // Create new user with model user
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'phone' => $data['phone'],
            'password' => Hash::make($data['password']),
        ]);

        // Generate token
        $token = $user->createToken('auth_token')->plainTextToken;

        // Clear cache
        Cache::forget($cacheKey);

        return response()->json([
            'success' => true,
            'message' => 'Registration completed successfully.',
            'data' => [
                'user' => $user,
                'token' => $token,
                'token_type' => 'Bearer',
            ],
        ], 201);
    }


// In your Laravel AuthController.php login method
public function login(Request $request)
{
    // Validate the request data
    $validator = Validator::make($request->all(), [
        'email' => 'required|email',
        'password' => 'required|string|min:8',
    ]);

    if ($validator->fails()) {
        return response()->json([
            'message' => 'Validation failed',
            'errors' => $validator->errors(),
        ], 422);
    }

    // Find user by email
    $user = User::where('email', $request->email)->first();

    // Check if user exists
    if (!$user) {
        return response()->json(['message' => 'Email not registered'], 404);
    }

    // Check if password is correct
    if (!Hash::check($request->password, $user->password)) {
        return response()->json(['message' => 'Incorrect password'], 401);
    }

    // Create token
    $token = $user->createToken('auth_token')->plainTextToken;

    return response()->json([
        'token' => $token,
        'user' => $user,
        'message' => 'Login successful',
    ]);
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

public function update(Request $request)
{
    $user = $request->user();

    $validator = Validator::make($request->all(), [
        'name' => 'sometimes|string|max:255',
        'email' => 'sometimes|email|unique:users,email,' . $user->id,
        'phone' => 'sometimes|string|max:20|unique:users,phone,' . $user->id,
        'password' => 'nullable|string|min:6|confirmed',
        'old_password' => 'required_with:password|string',
    ]);

    if ($validator->fails()) {
        return response()->json([
            'success' => false,
            'errors' => $validator->errors(),
        ], 422);
    }

    // Handle password update
    if ($request->filled('password')) {
        if (!Hash::check($request->old_password, $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Old password is incorrect.'
            ], 403);
        }

        $user->password = Hash::make($request->password);
    }

    // Handle name/email/phone updates
    if ($request->filled('name')) {
        $user->name = $request->name;
    }

    if ($request->filled('email')) {
        $user->email = $request->email;
    }

    if ($request->filled('phone')) {
        $user->phone = $request->phone;
    }

    $user->save();

    return response()->json([
        'success' => true,
        'data' => $user->only(['id', 'name', 'email', 'phone'])
    ]);
}

public function updatePhoto(Request $request)
{
    $user = $request->user();

    $request->validate([
        'photo' => 'required|image|mimes:jpg,jpeg,png|max:2048',
    ]);

    if ($request->hasFile('photo')) {
        $path = $request->file('photo')->store('profile_images', 'public');
        $user->profile_image = $path;
        $user->save();
    }

    return response()->json(['message' => 'Profile photo updated successfully', 'user' => $user]);
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
    Mail::to($request->email)->send(new SendResetCodeMail($code));

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