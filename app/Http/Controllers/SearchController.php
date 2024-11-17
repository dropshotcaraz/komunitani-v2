<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Carbon\Carbon;

class SearchController extends Controller
{
    public function index(Request $request)
    {
        // Validate input
        $validatedData = $request->validate([
            'query' => 'nullable|string|max:255',
            'topic' => 'nullable|string|max:100',
            'post_type' => 'nullable|string|max:100', // Validation rule for post type
            'date_filter' => 'nullable|in:today,week,month',
            'page' => 'nullable|integer|min:1'
        ]);

        // Start with base query
        $query = Post::with('user');

        // Search by query (content, title, or topic)
        if ($request->filled('query')) {
            $searchTerm = $request->input('query');
            $query->where(function($q) use ($searchTerm) {
                $q->where('content', 'LIKE', "%{$searchTerm}%")
                  ->orWhere('title', 'LIKE', "%{$searchTerm}%") // Include title in the search
                  ->orWhere('topic', 'LIKE', "%{$searchTerm}%");
            });
        }

        // Filter by specific topic if provided
        if ($request->filled('topic')) {
            $query->where('topic', $request->input('topic'));
        }

        // Filter by specific post type if provided
        if ($request->filled('post_type')) {
            $query->where('post_type', $request->input('post_type'));
        }

        // Date filtering
        if ($request->filled('date_filter')) {
            $query->when($request->input('date_filter') === 'today', function ($q) {
                return $q->whereDate('created_at', Carbon::today());
            })
            ->when($request->input('date_filter') === 'week', function ($q) {
                return $q->whereBetween('created_at', [
                    Carbon::now()->startOfWeek(), 
                    Carbon::now()->endOfWeek()
                ]);
            })
            ->when($request->input('date_filter') === 'month', function ($q) {
                return $q->whereBetween('created_at', [
                    Carbon::now()->startOfMonth(), 
                    Carbon::now()->endOfMonth()
                ]);
            });
        }

        // Fetch paginated results
        $posts = $query->latest()
            ->paginate(9)
            ->withQueryString();

        // Prepare topics and post types for filter dropdowns
        $availableTopics = Post::select('topic')
            ->distinct()
            ->whereNotNull('topic')
            ->pluck('topic');

        $availablePostTypes = Post::select('post_type')
            ->distinct()
            ->whereNotNull('post_type')
            ->pluck('post_type');

        // AJAX response
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'posts' => $posts->map(function ($post) {
                    return [
                        'id' => $post->id,
                        'title' => $post->title, // Include title in the response
                        'content' => $post->content,
                        'topic' => $post->topic,
                        'post_type' => $post->post_type, // Include post type in the response
                        'user' => $post->user->name,
                        'image' => $post->image_path 
                            ? asset('storage/' . $post->image_path) 
                            : null,
                        'formatted_date' => $post->created_at->diffForHumans(),
                    ];
                }),
                'pagination' => [
                    'current_page' => $posts->currentPage(),
                    'last_page' => $posts->lastPage(),
                    'total' => $posts->total(),
                ],
                'available_topics' => $availableTopics,
                'available_post_types' => $availablePostTypes // Include available post types in the response
            ]);
        }

        // Regular view response
        return view('search', [
            'posts' => $posts,
            'searchTerm' => $request->input('query'),
            'selectedTopic' => $request->input('topic'),
            'selectedPostType' => $request->input('post_type'), // Pass selected post type to the view
            'availableTopics' => $availableTopics,
            'availablePostTypes' => $availablePostTypes, // Pass available post types to the view
            'dateFilter' => $request->input('date_filter')
        ]);
    }
}