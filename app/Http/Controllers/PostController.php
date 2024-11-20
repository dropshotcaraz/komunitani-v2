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
use Illuminate\Validation\ValidationException;

class PostController extends Controller
{
    public function __invoke(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'content' => 'required|string|max:500',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'topic' => 'nullable|string|max:100',
            'post_type' => 'nullable|string|max:50'
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
                'title' => $request->input('title'),
                'content' => $request->input('content'),
                'image_path' => $imagePath,
                'topic' => $request->input('topic'),
                'post_type' => $request->input('post_type')
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
            $like->delete();
            $likeCount = Like::where('post_id', $postId)->count();
            return response()->json(['success' => true, 'liked' => false, 'likeCount' => $likeCount]);
        } else {
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
        if ($post->user_id !== Auth::id() && Auth::user()->name !== 'Admin') {
            return redirect()->back()->with('error', 'Unauthorized action.');
        }

        return view('posts.edit', compact('post'));
    }


    public function update(Request $request, $postId)
    {
        try {
            // Validate request
            $validated = $request->validate([
                'title' => 'nullable|string|max:255',
                'content' => 'nullable|string|max:500',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
                'topic' => 'nullable|string|max:100',
                'post_type' => 'nullable|string|in:Informasi,Tanya Jawab,Diskusi,Berita',
                'remove_image' => 'nullable|boolean'
            ]);
    
            // Find post and check authorization
            $post = Post::findOrFail($postId);
            if ($post->user_id !== Auth::id() && !Auth::user()->hasRole('Admin')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized action.'
                ], 403);
            }
    
            // Handle image processing
            $imagePath = $post->image_path;
    
            // Handle image removal
            if ($request->input('remove_image') == 'true' && $imagePath) {
                Storage::disk('public')->delete($imagePath);
                $imagePath = null;
            }
    
            // Handle new image upload
            if ($request->hasFile('image')) {
                try {
                    // Delete old image if exists
                    if ($post->image_path) {
                        Storage::disk('public')->delete($post->image_path);
                    }
    
                    // Upload new image
                    $image = $request->file('image');
                    $imageName = time() . '_' . $image->getClientOriginalName();
                    $newImagePath = $image->storeAs('posts', $imageName, 'public');
                    $imagePath = $newImagePath;
    
                } catch (\Exception $e) {
                    \Log::error('Image upload failed: ' . $e->getMessage());
                    return response()->json([
                        'success' => false,
                        'message' => 'Image upload failed: ' . $e->getMessage()
                    ], 500);
                }
            }
    
            // Update post
            $post->update([
                'title' => $request->input('title'),
                'content' => $request->input('content'),
                'image_path' => $imagePath,
                'topic' => $request->input('topic'),
                'post_type' => $request->input('post_type')
            ]);
    
            return response()->json([
                'success' => true,
                'message' => 'Post updated successfully',
                'post' => $post
            ]);
    
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
    
        } catch (\Exception $e) {
            \Log::error('Post update failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while updating the post'
            ], 500);
        }
    }


    public function destroy($postId)
    {
        $post = Post::findOrFail($postId);
        if ($post->user_id !== Auth::id() && Auth::user()->name !== 'Admin') {
            return redirect()->back()->with('error', 'Unauthorized action.');
        }

        $post->delete();

        return redirect()->route('posts.index')->with('success', 'Post deleted successfully');
    }

    public function commentEdit(Request $request, $commentId)
    {
        $comment = Comment::findOrFail($commentId);

        // Memastikan user yang login adalah author komentar atau Admin
        if ($comment->user_id !== Auth::id() && Auth::user()->name !== 'Admin') {
            return response()->json(['success' => false, 'message' => 'Unauthorized action.']);
        }

        // Redirect ke halaman edit komentar dengan membawa data komentar
        return view('comments.edit', compact('comment'));
    }


    public function commentUpdate(Request $request, $commentId)
    {
        $request->validate([
            'content' => 'required|string|max:255',
        ]);

        $comment = Comment::findOrFail($commentId);
        if ($comment->user_id !== Auth::id() && Auth::user()->name !== 'Admin') {
            return response()->json(['success' => false, 'message' => 'Unauthorized action.']);
        }
        $comment->update(['content' => $request->content]);

        return redirect()->back()->with('comment', $comment);
    }

    public function commentDestroy($commentId)
    {
        $comment = Comment::findOrFail($commentId);
        if ($comment->user_id !== Auth::id() && Auth::user()->name !== 'Admin') {
            return response()->json(['success' => false, 'message' => 'Unauthorized action.']);
        }

        $comment->delete();

        return redirect()->back();
    }
}
