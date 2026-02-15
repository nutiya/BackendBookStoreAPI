<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\FeedbackController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\PublisherController;
use App\Http\Controllers\Admin\LanguageController;
use App\Http\Controllers\Admin\AuthorController;
use App\Http\Controllers\Admin\BookController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\DashboardController;


Route::prefix('admin')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('admin.dashboard');
    Route::resource('categories', CategoryController::class);
    Route::resource('books', BookController::class);
    Route::resource('authors', AuthorController::class);
    Route::resource('languages', LanguageController::class);
    Route::resource('publishers', PublisherController::class);
    Route::resource('users', UserController::class);
    Route::resource('orders', OrderController::class)->only(['index', 'show']);
    Route::resource('feedback', FeedbackController::class)->only(['index', 'show', 'destroy']);
});


