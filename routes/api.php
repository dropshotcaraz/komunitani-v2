<?php


use App\Http\Controllers\DynamicDatabaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FollowController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Api\ProfileApiController;
use App\Http\Controllers\Api\SearchApiController;
use App\Http\Controllers\Api\PostApiController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// login/register wajib pk email password
Route::post('/v/register', [AuthController::class, 'register']);
Route::post('/v/login', [AuthController::class, 'login']);

// routing autentikasi pk api
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', [AuthController::class, 'user']);
    Route::put('/profile', [AuthController::class, 'updateProfile']);
    Route::put('/password', [AuthController::class, 'changePassword']);
});

// routing postingan pk api
Route::middleware(['auth:sanctum'])->group(function () {
    // Basic post operations
    Route::get('/v/posts', [PostApiController::class,'index']);
    Route::post('/v/posts', [PostApiController::class,'store']);
    Route::get('/v/posts/{id}', [PostApiController::class, 'show']);
    Route::put('/v/posts/{postId}', [PostApiController::class, 'update']);
    Route::delete('/v/posts/{postId}', [PostApiController::class, 'destroy']);

    // like n shere
    Route::post('/v/posts/{postId}/like', [PostApiController::class, 'like']);
    Route::post('/v/posts/{postId}/share', [PostApiController::class, 'share']);

    // komen pk api
    Route::post('/v/posts/{postId}/comment', [PostApiController::class, 'comment']);
    Route::put('/v/comments/{commentId}', [PostApiController::class, 'commentUpdate']);
    Route::delete('/v/comments/{commentId}', [PostApiController::class, 'commentDestroy']);
});

// routing profil pk api
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/v/profile', [ProfileApiController::class, 'show']);
    Route::get('/v/profile/{id}', [ProfileApiController::class, 'viewProfile']);
    Route::post('/v/profile', [ProfileApiController::class, 'update']);
    Route::delete('/v/profile', [ProfileApiController::class, 'destroy']);
});

// routing tabel dinamis pk api
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/d/{table}', [DynamicDatabaseController::class, 'index']);
    Route::get('/d/{table}/{id}', [DynamicDatabaseController::class, 'show']);
    Route::post('/d/{table}', [DynamicDatabaseController::class, 'store']);
    Route::put('/d/{table}/{id}', [DynamicDatabaseController::class, 'update']);
    Route::delete('/d/{table}/{id}', [DynamicDatabaseController::class, 'destroy']);
});

Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/v/search', [SearchApiController::class, 'index']);
    Route::get('/v/search/suggestions', [SearchApiController::class, 'suggestions']);
});


Route::post('/follow/{user}', [FollowController::class, 'follow']);
Route::delete('/unfollow/{user}', [FollowController::class, 'unfollow']);