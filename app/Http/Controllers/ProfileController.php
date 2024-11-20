<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Validation\Rule;
use App\Models\User;
use App\Models\Post;
use Illuminate\View\View;
use Illuminate\Validation\Rules\NotIn;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(Request $request): RedirectResponse
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

        // Update basic user information
        $user->fill([
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'bio' => $validatedData['bio'] ?? null,
        ]);

        // Handle profile picture upload
        if ($request->hasFile('profile_picture')) {
            // Delete old profile picture
            if ($user->profile_picture) {
                Storage::disk('public')->delete($user->profile_picture);
            }
            
            // Store new profile picture
            $profilePicPath = $request->file('profile_picture')->store('profile_pictures', 'public');
            $user->profile_picture = $profilePicPath;
        }

        // Handle cover photo upload
        if ($request->hasFile('cover_photo')) {
            // Delete old cover photo
            if ($user->cover_photo) {
                Storage::disk('public')->delete($user->cover_photo);
            }
            
            // Store new cover photo
            $coverPhotoPath = $request->file('cover_photo')->store('cover_photos', 'public');
            $user->cover_photo = $coverPhotoPath;
        }

        // Save changes
        $user->save();

        return Redirect::route('profile.edit')
            ->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        // Logout the user
        Auth::logout();

        // Delete user's profile pictures and cover photos
        if ($user->profile_picture) {
            Storage::disk('public')->delete($user->profile_picture);
        }
        if ($user->cover_photo) {
            Storage::disk('public')->delete($user->cover_photo);
        }

        // Delete the user
        $user->delete();

        // Invalidate the session
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }

    /**
     * Display the logged-in user's own profile.
     */
    public function show(): View
    {
        $user = Auth::user();
        
        // Get user's posts
        $posts = $user->posts()->latest()->get();
        
        // Get posts the user has liked
        $likedPosts = Post::whereHas('likes', function($query) use ($user) {
            $query->where('user_id', $user->id);
        })->latest()->get();

        return view('profile.show', [
            'user' => $user,
            'posts' => $posts,
            'likedPosts' => $likedPosts,
            'isCurrentUser' => true
        ]);
    }

    /**
     * View another user's profile
     */
    public function viewProfile($id): View
    {
        // Prevent viewing own profile through this method
        if (Auth::id() == $id) {
            return redirect()->route('profile.show');
        }

        // Find the user or fail
        $user = User::findOrFail($id);
        
        // Get user's posts
        $posts = $user->posts()->latest()->get();
        
        // Get posts the user has liked
        $likedPosts = Post::whereHas('likes', function($query) use ($user) {
            $query->where('user_id', $user->id);
        })->latest()->get();

        // Return view for another user's profile
        return view('profile.show', [
            'user' => $user,
            'posts' => $posts,
            'likedPosts' => $likedPosts,
            'isCurrentUser' => false
        ]);
    }
}