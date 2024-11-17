<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use App\Models\Post;
use App\Models\Like;
use App\Models\Comment;
use App\Models\Share;

class PostApiController extends Controller
{
    public function index()
    {
        $posts = Post::with(['user', 'likes', 'comments.user'])
            ->latest()
            ->paginate(10);
            
        return response()->json(['success' => true, 'data' => $posts]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'content' => 'required|string|max:500',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'topic' => 'nullable|string|max:100',
            'post_type' => 'nullable|string|max:50'
        ]);
    
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
    
        $imagePath = null;
        if ($request->hasFile('image')) {
            try {
                $image = $request->file('image');
                $imageName = time() . '.' . $image->getClientOriginalExtension();
                $imagePath = $image->storeAs('public/posts', $imageName, 'public');
                $imagePath = str_replace('public/', '', $imagePath);
            } catch (\Exception $e) {
                return response()->json(['error' => 'Image upload failed'], 500);
            }
        }
    
        try {
            $post = Post::create([
                'user_id' => Auth::id(),
                'title' => $request->input('title'),
                'content' => $request->input('content'),
                'image_path' => $imagePath,
                'topic' => $request->input('topic'),
                'post_type' => $request->input('post_type')
            ]);
    
            return response()->json(['success' => true, 'data' => $post], 201);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to create post'], 500);
        }
    }

    public function show($id)
    {
        $post = Post::with(['user', 'likes', 'comments.user'])
            ->findOrFail($id);
        
        $userPosts = Post::where('user_id', $post->user_id)
            ->where('id', '!=', $id)
            ->limit(5)
            ->get();
    
        return response()->json([
            'success' => true, 
            'data' => [
                'post' => $post,
                'user_posts' => $userPosts
            ]
        ]);
    }

    public function update(Request $request, $postId)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'content' => 'required|string|max:500',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'topic' => 'nullable|string|max:100',
            'post_type' => 'nullable|string|max:50'
        ]);
    
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
    
        $post = Post::findOrFail($postId);
    
        if ($post->user_id !== Auth::id()) {
            return response()->json(['error' => 'Unauthorized action'], 403);
        }
    
        $imagePath = $post->image_path;
    
        if ($request->has('remove_image') && $imagePath) {
            Storage::disk('public')->delete($imagePath);
            $imagePath = null;
        }
    
        if ($request->hasFile('image')) {
            try {
                $image = $request->file('image');
                $imageName = time() . '.' . $image->getClientOriginalExtension();
                $newImagePath = $image->storeAs('public/posts', $imageName, 'public');
                $imagePath = str_replace('public/', '', $newImagePath);
    
                if ($post->image_path) {
                    Storage::disk('public')->delete($post->image_path);
                }
            } catch (\Exception $e) {
                return response()->json(['error' => 'Image upload failed'], 500);
            }
        }
    
        $post->update([
            'title' => $request->input('title'),
            'content' => $request->input('content'),
            'image_path' => $imagePath,
            'topic' => $request->input('topic'),
            'post_type' => $request->input('post_type')
        ]);
    
        return response()->json(['success' => true, 'data' => $post]);
    }

    public function destroy($postId)
    {
        $post = Post::findOrFail($postId);
        
        if ($post->user_id !== Auth::id()) {
            return response()->json(['error' => 'Unauthorized action'], 403);
        }

        if ($post->image_path) {
            Storage::disk('public')->delete($post->image_path);
        }

        $post->delete();

        return response()->json(['success' => true, 'message' => 'Post deleted successfully']);
    }

    public function like($postId)
    {
        $like = Like::where('post_id', $postId)
            ->where('user_id', Auth::id())
            ->first();

        if ($like) {
            $like->delete();
            $likeCount = Like::where('post_id', $postId)->count();
            return response()->json([
                'success' => true, 
                'liked' => false, 
                'like_count' => $likeCount
            ]);
        }

        Like::create([
            'post_id' => $postId,
            'user_id' => Auth::id(),
        ]);
        
        $likeCount = Like::where('post_id', $postId)->count();
        return response()->json([
            'success' => true, 
            'liked' => true, 
            'like_count' => $likeCount
        ]);
    }

    public function comment(Request $request, $postId)
    {
        $validator = Validator::make($request->all(), [
            'content' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $comment = Comment::create([
            'post_id' => $postId,
            'user_id' => Auth::id(),
            'content' => $request->content,
        ]);

        $comment->load('user');

        return response()->json(['success' => true, 'data' => $comment], 201);
    }

    public function commentUpdate(Request $request, $commentId)
    {
        $validator = Validator::make($request->all(), [
            'content' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $comment = Comment::findOrFail($commentId);
        
        if ($comment->user_id !== Auth::id()) {
            return response()->json(['error' => 'Unauthorized action'], 403);
        }

        $comment->update(['content' => $request->content]);
        $comment->load('user');

        return response()->json(['success' => true, 'data' => $comment]);
    }

    public function commentDestroy($commentId)
    {
        $comment = Comment::findOrFail($commentId);
        
        if ($comment->user_id !== Auth::id()) {
            return response()->json(['error' => 'Unauthorized action'], 403);
        }

        $comment->delete();

        return response()->json(['success' => true, 'message' => 'Comment deleted successfully']);
    }

    public function share($postId)
    {
        try {
            $share = Share::create([
                'post_id' => $postId,
                'user_id' => Auth::id(),
            ]);
    
            $post = Post::findOrFail($postId);
    
            return response()->json([
                'success' => true,
                'data' => [
                    'share' => $share,
                    'post' => [
                        'title' => $post->title,
                        'description' => $post->content,
                        'url' => route('api.posts.show', $postId)
                    ]
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Share failed'], 500);
        }
    }
}