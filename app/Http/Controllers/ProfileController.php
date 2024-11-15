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

class ProfileController extends Controller
{
    // Other methods...

    /**
     * Display the logged-in user's own profile.
     */
    public function show(): View
    {
        $user = Auth::user();
        $loggedInUser  = $user; // This is the logged-in user
    
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
            'isCurrentUser' => true, // No trailing space
            'loggedInUser' => $loggedInUser  // Pass the logged-in user
        ]);
    }
    
    public function viewProfile($id): View
    {
        // Prevent viewing own profile through this method
        if (Auth::id() == $id) {
            return redirect()->route('profile.show');
        }
    
        // Find the user or fail
        $user = User::findOrFail($id);
        $loggedInUser  = Auth::user(); // Get the logged-in user
    
        // Get user's posts
        $posts = $user->posts()->latest()->get();
        
        // Get posts the user has liked
        $likedPosts = Post::whereHas('likes', function($query) use ($user) {
            $query->where('user_id', $user->id);
        })->latest()->get();
    
        // Assuming you have methods to get followers and following counts
        $followersCount = $user->followers()->count();
        $followingCount = $user->follows()->count();
    
        // Check if the logged-in user is following this user
        $isFollowing = $loggedInUser ->follows()
            ->where('user_id', $user->id) // The user being followed
            ->exists();
    
        // Return view for another user's profile
        return view('profile.show', [
            'user' => $user,
            'posts' => $posts,
            'likedPosts' => $likedPosts,
            'isCurrentUser' => false, // No trailing space
            'loggedInUser' => $loggedInUser , // Pass the logged-in user
            'followersCount' => $followersCount,
            'followingCount' => $followingCount,
            'isFollowing' => $isFollowing,
        ]);
    }
};