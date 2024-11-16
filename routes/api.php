<?php


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\PostApiController;
use App\Http\Controllers\FollowController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::middleware('auth:sanctum')->group(function () {
    // Post Routes
    Route::get('/posts', [PostApiController::class, 'index']);
    Route::get('/posts/{id}', [PostApiController::class, 'show']);
    Route::post('/posts', [PostApiController::class, 'store']);
    Route::put('/posts/{id}', [PostApiController::class, 'update']);
    Route::delete('/posts/{id}', [PostApiController::class, 'destroy']);
    Route::get('/posts/search', [PostApiController::class, 'search']);
});

Route::post('/follow/{user}', [FollowController::class, 'follow']);
Route::delete('/unfollow/{user}', [FollowController::class, 'unfollow']);