<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\NotIn;
use App\Models\User;
use App\Models\Post;
class ProfileApiController extends Controller
{
    /**
     * Get the user's profile information.
     */
    public function show(): JsonResponse
    {
        $user = Auth::user();
        
        $posts = $user->posts()->latest()->get();
        $likedPosts = Post::whereHas('likes', function($query) use ($user) {
            $query->where('user_id', $user->id);
        })->latest()->get();

        return response()->json([
            'user' => $user,
            'posts' => $posts,
            'likedPosts' => $likedPosts
        ]);
    }

    /**
     * View another user's profile
     */
    public function viewProfile($id): JsonResponse
    {
        if (Auth::id() == $id) {
            return response()->json([
                'message' => 'Please use /api/profile endpoint for viewing own profile'
            ], 400);
        }

        $user = User::findOrFail($id);
        
        $posts = $user->posts()->latest()->get();
        $likedPosts = Post::whereHas('likes', function($query) use ($user) {
            $query->where('user_id', $user->id);
        })->latest()->get();

        return response()->json([
            'user' => $user,
            'posts' => $posts,
            'likedPosts' => $likedPosts
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(Request $request): JsonResponse
    {
        $user = $request->user();

        $validatedData = $request->validate([
            'name' => ['required', 'string', 'max:255', new NotIn(['admin', 'Admin', 'ADMIN'])],
            'email' => [
                'required', 
                'string', 
                'email', 
                'max:255', 
                Rule::unique('users')->ignore($user->id)
            ],
            'bio' => ['nullable', 'string', 'max:500'],
            'profile_picture' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
            'cover_photo' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
        ]);

        $user->fill([
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'bio' => $validatedData['bio'] ?? null,
        ]);

        if ($request->hasFile('profile_picture')) {
            if ($user->profile_picture) {
                Storage::disk('public')->delete($user->profile_picture);
            }
            $profilePicPath = $request->file('profile_picture')->store('profile_pictures', 'public');
            $user->profile_picture = $profilePicPath;
        }

        if ($request->hasFile('cover_photo')) {
            if ($user->cover_photo) {
                Storage::disk('public')->delete($user->cover_photo);
            }
            $coverPhotoPath = $request->file('cover_photo')->store('cover_photos', 'public');
            $user->cover_photo = $coverPhotoPath;
        }

        $user->save();

        return response()->json([
            'message' => 'Profile updated successfully',
            'user' => $user
        ]);
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): JsonResponse
    {
        $request->validate([
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        if ($user->profile_picture) {
            Storage::disk('public')->delete($user->profile_picture);
        }
        if ($user->cover_photo) {
            Storage::disk('public')->delete($user->cover_photo);
        }

        Auth::logout();
        $user->delete();

        return response()->json([
            'message' => 'User account deleted successfully'
        ]);
    }
}