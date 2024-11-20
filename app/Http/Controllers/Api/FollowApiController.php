<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class FollowApiController extends Controller
{
    /**
     * Get user profile with followers and following
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        try {
            $user = User::with(['followers', 'follows'])->findOrFail($id);
            
            return response()->json([
                'success' => true,
                'data' => [
                    'user' => $user,
                    'followers_count' => $user->followers->count(),
                    'following_count' => $user->follows->count(),
                ]
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'User not found'
            ], 404);
        }
    }

    /**
     * Follow a user
     *
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function follow(Request $request, $id)
    {
        try {
            $userToFollow = User::findOrFail($id);
            $loggedInUser = Auth::id();

            // Check if user is trying to follow themselves
            if ($loggedInUser === $userToFollow->id) {
                return response()->json([
                    'success' => false,
                    'message' => 'You cannot follow yourself'
                ], 400);
            }

            // Check if already following
            if (DB::table('followers')
                ->where('follower_id', $loggedInUser)
                ->where('user_id', $userToFollow->id)
                ->exists()) {
                return response()->json([
                    'success' => false,
                    'message' => 'You are already following this user'
                ], 400);
            }

            // Create follow relationship
            DB::table('followers')->insert([
                'follower_id' => $loggedInUser,
                'user_id' => $userToFollow->id,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Successfully followed ' . $userToFollow->name
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to follow user'
            ], 500);
        }
    }

    /**
     * Unfollow a user
     *
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function unfollow(Request $request, $id)
    {
        try {
            $userToUnfollow = User::findOrFail($id);
            $loggedInUser = Auth::id();

            // Check if following
            if (!DB::table('followers')
                ->where('follower_id', $loggedInUser)
                ->where('user_id', $userToUnfollow->id)
                ->exists()) {
                return response()->json([
                    'success' => false,
                    'message' => 'You are not following this user'
                ], 400);
            }

            // Remove follow relationship
            DB::table('followers')
                ->where('follower_id', $loggedInUser)
                ->where('user_id', $userToUnfollow->id)
                ->delete();

            return response()->json([
                'success' => true,
                'message' => 'Successfully unfollowed ' . $userToUnfollow->name
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to unfollow user'
            ], 500);
        }
    }
}