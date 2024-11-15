<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class FollowController extends Controller
{
    // Method to show user profile
    public function show($id)
    {
        // Load the user with followers and following relationships
        $user = User::with(['followers', 'follows'])->findOrFail($id);
        $posts = $user->posts; // Assuming you want to show the user's posts
        $loggedInUser  = Auth::user(); // Get the logged-in user

        return view('profile.show', compact('user', 'posts', 'loggedInUser '));
    }

    // Method to follow a user
    public function follow(Request $request, $id)
    {
        $userToFollow = User::findOrFail($id); // Find the user to follow
        $loggedInUser  = Auth::id(); // Get the ID of the logged-in user

        // Check if the user is already following
        if (DB::table('followers')->where('follower_id', $loggedInUser )->where('user_id', $userToFollow->id)->exists()) {
            return redirect()->back()->with('status', 'You are already following this user.');
        }

        // Insert the follow relationship
        DB::table('followers')->insert([
            'follower_id' => $loggedInUser , // The user who is following
            'user_id' => $userToFollow->id, // The user being followed
        ]);

        return redirect()->back()->with('status', 'You are now following ' . $userToFollow->name);
    }

    // Method to unfollow a user (optional, but often needed)
    public function unfollow(Request $request, $id)
    {
        $userToUnfollow = User::findOrFail($id); // Find the user to unfollow
        $loggedInUser  = Auth::id(); // Get the ID of the logged-in user

        // Check if the user is following
        if (!DB::table('followers')->where('follower_id', $loggedInUser )->where('user_id', $userToUnfollow->id)->exists()) {
            return redirect()->back()->with('status', 'You are not following this user.');
        }

        // Delete the follow relationship
        DB::table('followers')->where('follower_id', $loggedInUser )->where('user_id', $userToUnfollow->id)->delete();

        return redirect()->back()->with('status', 'You have unfollowed ' . $userToUnfollow->name);
    }
}