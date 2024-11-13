<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\PostApiResource;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class PostApiController extends Controller
{
    // Get All Posts
    public function index()
    {
        $posts = Post::with('user')
            ->latest()
            ->paginate(10);
        
        return PostApiResource::collection($posts);
    }

    // Get Single Post
    public function show($id)
    {
        $post = Post::with('user')->findOrFail($id);
        return new PostApiResource($post);
    }

    // Create Post via API
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'content' => 'required|string|max:500',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'topic' => 'nullable|string|max:100'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 422);
        }

        $imagePath = null;
        if ($request->hasFile('image')) {
            try {
                $image = $request->file('image');
                $imageName = time() . '.' . $image->getClientOriginalExtension();
                $imagePath = $image->storeAs('public/posts', $imageName, 'public');
                $imagePath = str_replace('public/', '', $imagePath);
            } catch (\Exception $e) {
                return response()->json([
                    'message' => 'Image upload failed',
                    'error' => $e->getMessage()
                ], 500);
            }
        }

        try {
            $post = Post::create([
                'user_id' => Auth::id(),
                'content' => $request->input('content'),
                'image_path' => $imagePath,
                'topic' => $request->input('topic')
            ]);

            return new PostApiResource($post);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to create post',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // Update Post
    public function update(Request $request, $id)
    {
        $post = Post::findOrFail($id);

        // Optional: Add authorization check
        if ($post->user_id !== Auth::id()) {
            return response()->json([
                'message' => 'Unauthorized'
            ], 403);
        }

        $validator = Validator::make($request->all(), [
            'content' => 'sometimes|required|string|max:500',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'topic' => 'nullable|string|max:100'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 422);
        }

        $imagePath = $post->image_path;
        if ($request->hasFile('image')) {
            // Remove old image if exists
            if ($imagePath) {
                \Storage::disk('public')->delete('posts/' . $imagePath);
            }

            $image = $request->file('image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $imagePath = $image->storeAs('public/posts', $imageName, 'public');
            $imagePath = str_replace('public/', '', $imagePath);
        }

        $post->update([
            'content' => $request->input('content', $post->content),
            'image_path' => $imagePath,
            'topic' => $request->input('topic', $post->topic)
        ]);

        return new PostApiResource($post);
    }

    // Delete Post
    public function destroy($id)
    {
        $post = Post::findOrFail($id);

        // Optional: Add authorization check
        if ($post->user_id !== Auth::id()) {
            return response()->json([
                'message' => 'Unauthorized'
            ], 403);
        }

        // Delete associated image
        if ($post->image_path) {
            \Storage::disk('public')->delete('posts/' . $post->image_path);
        }

        $post->delete();

        return response()->json([
            'message' => 'Post deleted successfully'
        ]);
    }

    // Search Posts
    public function search(Request $request)
    {
        $query = Post::query();

        // Search by topic
        if ($request->has('topic')) {
            $query->where('topic', 'like', '%' . $request->input('topic') . '%');
        }

        // Search by content
        if ($request->has('content')) {
            $query->where('content', 'like', '%' . $request->input('content') . '%');
        }

        $posts = $query->with('user')
            ->latest()
            ->paginate(10);

        return PostApiResource::collection($posts);
    }
}
