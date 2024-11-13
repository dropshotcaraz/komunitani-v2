<?php


namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\Post;
use App\Models\Like;
use App\Models\Comment;
use App\Models\Share;
use App\Http\Controllers\Controller;

class PostController extends Controller
{
    public function __invoke(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'content' => 'required|string|max:500',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'topic' => 'nullable|string|max:100'
        ]);
    
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
    
        $imagePath = null;
        if ($request->hasFile('image')) {
            try {
                $image = $request->file('image');
                $imageName = time() . '.' . $image->getClientOriginalExtension();
                $imagePath = $image->storeAs('public/posts', $imageName, 'public');
                $imagePath = str_replace('public/', '', $imagePath);
            } catch (\Exception $e) {
                \Log::error('Image upload failed: ' . $e->getMessage());
                return redirect()->back()->with('error', 'Image upload failed');
            }
        }
    
        try {
            $post = Post::create([
                'user_id' => Auth::id(),
                'content' => $request->input('content'),
                'image_path' => $imagePath,
                'topic' => $request->input('topic')
            ]);
    
            return redirect()->back()->with('success', 'Post created successfully');
        } catch (\Exception $e) {
            \Log::error('Post creation failed: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to create post');
        }
    }
    public function like(Request $request, $postId)
    {
        $like = Like::where('post_id', $postId)->where('user_id', Auth::id())->first();

        if ($like) {
            // If the user already liked the post, remove the like
            $like->delete();
            $likeCount = Like::where('post_id', $postId)->count();
            return response()->json(['success' => true, 'liked' => false, 'likeCount' => $likeCount]);
        } else {
            // Otherwise, create a new like
            $like = Like::create([
                'post_id' => $postId,
                'user_id' => Auth::id(),
            ]);
            $likeCount = Like::where('post_id', $postId)->count();
            
            return response()->json(['success' => true, 'liked' => true, 'likeCount' => $likeCount]);
        }
    }

    public function comment(Request $request, $postId)
    {
        $request->validate([
            'content' => 'required|string|max:255',
        ]);

        $comment = Comment::create([
            'post_id' => $postId,
            'user_id' => Auth::id(),
            'content' => $request->content,
        ]);

        return redirect()->back()->with('success', 'Comment created successfully');
    }

    public function share(Request $request, $postId)
    {
        $share = Share::create([
            'post_id' => $postId,
            'user_id' => Auth::id(),
        ]);

        return response()->json(['success' => true, 'share' => $share]);
    }

    // app/Http/Controllers/PostController.php

    public function edit(Request $request, $postId)
    {
        $post = Post::findOrFail($postId);
        // Ensure the user is authorized to edit the post
        if ($post->user_id !== Auth::id()) {
            return redirect()->back()->with('error', 'Unauthorized action.');
        }

        return view('posts.edit', compact('post'));
    }

    public function update(Request $request, $postId)
    {
        $request->validate([
            'content' => 'required|string|max:500',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'topic' => 'nullable|string|max:100'
        ]);

        $post = Post::findOrFail($postId);
        // Ensure the user is authorized to update the post
        if ($post->user_id !== Auth::id()) {
            return redirect()->back()->with('error', 'Unauthorized action.');
        }

        $imagePath = $post->image_path; // Keep the existing image path

        if ($request->hasFile('image')) {
            try {
                $image = $request->file('image');
                $imageName = time() . '.' . $image->getClientOriginalExtension();
                $imagePath = $image->storeAs('public/posts', $imageName, 'public');
                $imagePath = str_replace('public/', '', $imagePath);
            } catch (\Exception $e) {
                \Log::error('Image upload failed: ' . $e->getMessage());
                return redirect()->back()->with('error', 'Image upload failed');
            }
        }

        $post->update([
            'content' => $request->input('content'),
            'image_path' => $imagePath,
            'topic' => $request->input('topic')
        ]);

        return redirect()->route('dashboard')->with('success', 'Post updated successfully');
    }

    public function destroy($postId)
    {
        $post = Post::findOrFail($postId);
        // Ensure the user is authorized to delete the post
        if ($post->user_id !== Auth::id()) {
            return redirect()->back()->with('error', 'Unauthorized action.');
        }

        $post->delete();

        return redirect()->route('posts.index')->with('success', 'Post deleted successfully');
    }

    public function commentEdit(Request $request, $commentId)
    {
        $comment = Comment::findOrFail($commentId);
        // Ensure the user is authorized to edit the comment
        if ($comment->user_id !== Auth::id()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized action.']);
        }
        return response()->json(['success' => true, 'comment' => $comment]);
    }

    public function commentUpdate(Request $request, $commentId)
    {
        $request->validate([
            'content' => 'required|string|max:255',
        ]);

        $comment = Comment::findOrFail($commentId);
        // Ensure the user is authorized to update the comment
        if ($comment->user_id !== Auth::id()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized action.']);
        }
        $comment->update(['content' => $request->content]);

        return response()->json(['success' => true, 'comment' => $comment]);
    }

    public function commentDestroy($commentId)
    {
        $comment = Comment::findOrFail($commentId);
        // Ensure the user is authorized to delete the comment
        if ($comment->user_id !== Auth::id()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized action.']);
        }

        $comment->delete();

        return response()->json(['success' => true]);
    }
}


