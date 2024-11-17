<?php


namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
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
            'title' => 'required|string|max:255', // Add validation for title
            'content' => 'required|string|max:500',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'topic' => 'nullable|string|max:100',
            'post_type' => 'nullable|string|max:50' // Add validation for post type
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
                'title' => $request->input('title'), // Create post with title
                'content' => $request->input('content'),
                'image_path' => $imagePath,
                'topic' => $request->input('topic'),
                'post_type' => $request->input('post_type') // Create post with post type
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
        try {
            $share = Share::create([
                'post_id' => $postId,
                'user_id' => Auth::id(),
            ]);
    
            $post = Post::findOrFail($postId);
    
            return response()->json([
                'success' => true,
                'share' => $share,
                'title' => $post->title,
                'text' => $post->description,
                'url' => route('posts.show', $postId)
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
    public function show($id)
    {
        // Find the post by ID with its related user, likes, and comments
        $post = Post::with(['user', 'likes', 'comments.user'])
            ->findOrFail($id);
    
        // Get other posts by the same user
        $posts = Post::where('user_id', $post->user_id)->get();
    
        // Pass the post and other posts to the view
        return view('posts.show', [
            'post' => $post,
            'posts' => $posts, // Pass the user's posts
        ]);
    }

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

        // Check if the remove_image input is set, indicating the image should be removed
        if ($request->has('remove_image') && $imagePath) {
            // Delete the image from storage
            Storage::disk('public')->delete($imagePath);
            $imagePath = null; // Set image path to null for database update
        }

        // Handle image upload if a new image is uploaded
        if ($request->hasFile('image')) {
            try {
                $image = $request->file('image');
                $imageName = time() . '.' . $image->getClientOriginalExtension();
                $newImagePath = $image->storeAs('public/posts', $imageName, 'public');
                $imagePath = str_replace('public/', '', $newImagePath);

                // Delete the old image if a new one is uploaded and if it exists
                if ($post->image_path) {
                    Storage::disk('public')->delete($post->image_path);
                }
            } catch (\Exception $e) {
                \Log::error('Image upload failed: ' . $e->getMessage());
                return redirect()->back()->with('error', 'Image upload failed');
            }
        }

        // Update the post with new content and image path
        $post->update([
            'content' => $request->input('content'),
            'image_path' => $imagePath,
            'topic' => $request->input('topic')
        ]);

        return redirect()->back()->with('success', 'Post updated successfully');
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
        return redirect()->back()->with('comment', $comment);
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

        return redirect()->back()->with('comment', $comment);
    }

    public function commentDestroy($commentId)
    {
        $comment = Comment::findOrFail($commentId);
        // Ensure the user is authorized to delete the comment
        if ($comment->user_id !== Auth::id()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized action.']);
        }

        $comment->delete();

        return redirect()->back();
    }
}


