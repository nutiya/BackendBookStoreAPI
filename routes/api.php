<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\WishlistController;
use App\Models\Slide;
use App\Http\Controllers\CartController;
use App\Http\Controllers\FeedbackController;


Route::get('/slides', function () {return Slide::all();});

// 🔓 Public Routes
Route::post('/register', [AuthController::class, 'requestRegisterOTP']);
Route::post('/verify-register-otp', [AuthController::class, 'verifyRegisterOTP']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/forgot-password', [AuthController::class, 'forgotPassword']);
Route::post('/reset-password', [AuthController::class, 'resetPassword']);
Route::post('/verify-reset-code', [AuthController::class, 'verifyResetCode']);


Route::get('/categories', [CategoryController::class, 'index']);
Route::get('/books/new-releases', [BookController::class, 'newReleases']);
Route::get('/books/trending', [BookController::class, 'trending']);
Route::get('/books', [BookController::class, 'index']);

// 🔐 Authenticated Routes (Require Sanctum only, no email verification)
Route::middleware(['auth:sanctum'])->group(function () {

    // Auth
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', [AuthController::class, 'user']);
    Route::put('/user', [AuthController::class, 'update']);
    Route::post('/user/photo', [AuthController::class, 'updatePhoto']);
    Route::post('/wishlist', [WishlistController::class, 'add']);
    Route::delete('/wishlist/{book_id}', [WishlistController::class, 'remove']);
    Route::get('/wishlist', [WishlistController::class, 'list']);
    Route::get('/cart', [CartController::class, 'index']);
    Route::post('/cart', [CartController::class, 'add']);
    Route::put('/cart/{book_id}', [CartController::class, 'update']);
    Route::delete('/cart/{book_id}', [CartController::class, 'remove']);
    Route::post('/orders', [OrderController::class, 'placeOrder']);
    Route::get('/orders', [OrderController::class, 'getOrderHistory']);
    Route::post('/feedback', [FeedbackController::class, 'store']);

});
