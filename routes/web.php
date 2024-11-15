<?php

use App\Http\Controllers\ProfileController;

use App\Http\Controllers\PostController;
use App\Http\Controllers\TimelineController;
use App\Http\Controllers\MessagesController;
use App\Http\Controllers\SearchController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ChatbotController;
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

// For 'MessagesController' as an invokable controller
Route::get('/messages', MessagesController::class)->name('messages');

// For 'SearchController' as an invokable controller
Route::get('/search', [SearchController::class, 'showSearchPage'])->name('search.page');

Route::post('follow/{user}', [FollowController::class, 'follow'])->name('follow');
Route::delete('/unfollow/{user}', [FollowController::class, 'unfollow'])->name('unfollow');
Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
Route::get('/profile/{id}', [ProfileController::class, 'viewProfile'])->name('profile.view');

require __DIR__.'/auth.php';
