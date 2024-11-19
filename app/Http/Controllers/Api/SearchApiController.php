<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;

class SearchApiController extends Controller
{
    public function index(Request $request)
    {
        // Validate input
        $validator = Validator::make($request->all(), [
            'query' => 'nullable|string|max:255',
            'topic' => 'nullable|string|max:100',
            'post_type' => 'nullable|string|max:100',
            'date_filter' => 'nullable|in:today,week,month',
            'page' => 'nullable|integer|min:1',
            'per_page' => 'nullable|integer|min:1|max:100'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Start with base query
        $query = Post::with('user');

        // Search by query (content, title, or topic)
        if ($request->filled('query')) {
            $searchTerm = $request->input('query');
            $query->where(function($q) use ($searchTerm) {
                $q->where('content', 'LIKE', "%{$searchTerm}%")
                  ->orWhere('title', 'LIKE', "%{$searchTerm}%")
                  ->orWhere('topic', 'LIKE', "%{$searchTerm}%");
            });
        }

        // Filter by specific topic
        if ($request->filled('topic')) {
            $query->where('topic', $request->input('topic'));
        }

        // Filter by specific post type
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
        $posts = $query->latest()->get();

        // Get available filters
        $availableTopics = Post::select('topic')
            ->distinct()
            ->whereNotNull('topic')
            ->pluck('topic');

        $availablePostTypes = Post::select('post_type')
            ->distinct()
            ->whereNotNull('post_type')
            ->pluck('post_type');

        return response()->json([
            'success' => true,
            'data' => [
                'posts' => $posts->map(function ($post) {
                    return [
                        'id' => $post->id,
                        'title' => $post->title,
                        'content' => $post->content,
                        'topic' => $post->topic,
                        'post_type' => $post->post_type,
                        'user' => [
                            'id' => $post->user->id,
                            'name' => $post->user->name,
                        ],
                        'image_url' => $post->image_path 
                            ? asset('storage/' . $post->image_path) 
                            : null,
                        'created_at' => $post->created_at,
                        'created_at_formatted' => $post->created_at->diffForHumans(),
                    ];
                }),
                'filters' => [
                    'available_topics' => $availableTopics,
                    'available_post_types' => $availablePostTypes,
                    'date_filters' => [
                        'today' => 'Today',
                        'week' => 'This Week',
                        'month' => 'This Month'
                    ]
                ],
                'meta' => [
                    'applied_filters' => [
                        'query' => $request->input('query'),
                        'topic' => $request->input('topic'),
                        'post_type' => $request->input('post_type'),
                        'date_filter' => $request->input('date_filter')
                    ]
                ]
            ]
        ]);
    }

    public function suggestions(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'query' => 'required|string|min:2|max:255'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $searchTerm = $request->input('query');

        $suggestions = Post::where('title', 'LIKE', "%{$searchTerm}%")
            ->orWhere('content', 'LIKE', "%{$searchTerm}%")
            ->orWhere('topic', 'LIKE', "%{$searchTerm}%")
            ->select('id', 'title', 'topic')
            ->limit(5)
            ->get()
            ->map(function ($post) {
                return [
                    'id' => $post->id,
                    'title' => $post->title,
                    'topic' => $post->topic
                ];
            });

        return response()->json([
            'success' => true,
            'data' => $suggestions
        ]);
    }
}