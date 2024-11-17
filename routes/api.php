<?php


use App\Http\Controllers\DynamicDatabaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\PostApiController;
use App\Http\Controllers\FollowController;
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

Route::middleware(['auth:sanctum'])->group(function () {
    // Basic post operations
    
    Route::get('/v/posts', [PostApiController::class,'index']);
    Route::post('/v/posts', [PostApiController::class,'store']);
    Route::get('/v/posts/{id}', [PostApiController::class, 'show']);
    Route::put('/v/posts/{postId}', [PostApiController::class, 'update']);
    Route::delete('/v/posts/{postId}', [PostApiController::class, 'destroy']);

    // Post interactions
    Route::post('/v/posts/{postId}/like', [PostApiController::class, 'like']);
    Route::post('/v/posts/{postId}/share', [PostApiController::class, 'share']);

    // Comment operations
    Route::post('/v/posts/{postId}/comment', [PostApiController::class, 'comment']);
    Route::put('/v/comments/{commentId}', [PostApiController::class, 'commentUpdate']);
    Route::delete('/v/comments/{commentId}', [PostApiController::class, 'commentDestroy']);
});


Route::post('/follow/{user}', [FollowController::class, 'follow']);
Route::delete('/unfollow/{user}', [FollowController::class, 'unfollow']);