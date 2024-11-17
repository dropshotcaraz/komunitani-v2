<?php

use App\Http\Controllers\ProfileController;

use App\Http\Controllers\PostController;
use App\Http\Controllers\TimelineController;
use App\Http\Controllers\MessagesController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ChatbotController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\FollowController;

Route::get('/chatbot', [ChatbotController::class, 'view'])->name('chatbot.index');
Route::post('/question', [ChatbotController::class, 'index']);

Route::get('/', function () {
    return view('welcome');
});

// For 'ProfileController' 
Route::middleware(['auth', 'verified'])->group(function () {
    Route::prefix('profile')->group(function () {
        Route::get('/', [ProfileController::class, 'show'])->name('profile.show');
        Route::get('/edit', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('/', [ProfileController::class, 'destroy'])->name('profile.destroy');
    });
    Route::get('/profile/view/{id}', [ProfileController::class, 'viewProfile'])
        ->name('profile.view');
});

// For 'PostController' as an invokable controller
Route::get('/posts', PostController::class)->name('posts.index');
Route::post('/posts', PostController::class)->name('post.store');
Route::get('/posts/{postId}/edit', [PostController::class, 'edit'])->name('posts.edit');
Route::put('/posts/{postId}', [PostController::class, 'update'])->name('posts.update');
Route::delete('/posts/{postId}', [PostController::class, 'destroy'])->name('posts.destroy');
Route::get('/posts/{post}', [PostController::class, 'show'])->name('posts.show');

Route::get('/symlink', function () {
    Artisan::call('storage:link');
});

// For 'PostController' as a resource controller
Route::post('/posts/{post}/like', [PostController::class, 'like'])->name('posts.like');
Route::post('/posts/{post}/comment', [PostController::class, 'comment'])->name('posts.comment');
Route::post('/posts/{post}/share', [PostController::class, 'share'])->name('posts.share');

    // Comment routes
    Route::get('/comments/{commentId}/edit', [PostController::class, 'commentEdit'])->name('comments.edit');
    Route::put('/comments/{commentId}', [PostController::class, 'commentUpdate'])->name('comments.update');
    Route::delete('/comments/{commentId}', [PostController::class, 'commentDestroy'])->name('comments.destroy');

// For 'TimelineController' as an invokable controller
Route::get('/dashboard', TimelineController::class)->name('dashboard');

// For 'FollowController' as a controller with multiple methods
Route::post('follow/{user}', [FollowController::class, 'follow'])->name('follow');
Route::delete('/unfollow/{user}', [FollowController::class, 'unfollow'])->name('unfollow');

Route::controller(SearchController::class)->group(function () {
    // Main search route with multiple parameter support
    Route::get('/search', 'index')
        ->name('search')
        ->middleware(['web', 'throttle:60,1']); // Optional rate limiting
});

// For 'MessagesController' as an invokable controller
Route::get('/messages', MessagesController::class)->name('messages');

require __DIR__.'/auth.php';
