<?php


use App\Http\Controllers\DynamicDatabaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\PostApiController;
use App\Http\Controllers\FollowController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;


Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::middleware('auth:sanctum')->group(function () {
    // Dynamic database routes
    Route::get('{table}', [DynamicDatabaseController::class, 'index']);
    Route::get('{table}/{id}', [DynamicDatabaseController::class, 'show']);
    Route::post('{table}', [DynamicDatabaseController::class, 'store']);
    Route::put('{table}/{id}', [DynamicDatabaseController::class, 'update']);
    Route::delete('{table}/{id}', [DynamicDatabaseController::class, 'destroy']);
});

// Public routes
Route::post('/v/register', [AuthController::class, 'register']);
Route::post('/v/login', [AuthController::class, 'login']);

// Protected routes
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', [AuthController::class, 'user']);
    Route::put('/profile', [AuthController::class, 'updateProfile']);
    Route::put('/password', [AuthController::class, 'changePassword']);
});

// <!--masih blm nyambung ke postingan ini gw naro doang-->

// Route::middleware(['auth:sanctum'])->group(function () {
//         // Basic post operations
//         Route::post('/posts', PostController::class)->name('api.posts.store');
//         Route::get('/posts/{id}', [PostController::class, 'show'])->name('api.posts.show');
//         Route::get('/posts/{postId}/edit', [PostController::class, 'edit'])->name('api.posts.edit');
//         Route::patch('/posts/{postId}', [PostController::class, 'update'])->name('api.posts.update');
//         Route::delete('/posts/{postId}', [PostController::class, 'destroy'])->name('api.posts.destroy');

//         // Post interactions
//         Route::post('/posts/{postId}/like', [PostController::class, 'like'])->name('api.posts.like');
//         Route::post('/posts/{postId}/share', [PostController::class, 'share'])->name('api.posts.share');

//         // Comment operations
//         Route::post('/posts/{postId}/comment', [PostController::class, 'comment'])->name('api.posts.comment');
//         Route::get('/comments/{commentId}/edit', [PostController::class, 'commentEdit'])->name('api.comments.edit');
//         Route::patch('/comments/{commentId}', [PostController::class, 'commentUpdate'])->name('api.comments.update');
//         Route::delete('/comments/{commentId}', [PostController::class, 'commentDestroy'])->name('api.comments.destroy');
// });


Route::post('/follow/{user}', [FollowController::class, 'follow']);
Route::delete('/unfollow/{user}', [FollowController::class, 'unfollow']);