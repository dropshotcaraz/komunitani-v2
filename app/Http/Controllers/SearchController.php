<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class SearchController extends Controller
{
    public function index(Request $request)
    {
        // Validate input
        $validatedData = $request->validate([
            'query' => 'nullable|string|max:255',
            'topic' => 'nullable|string|max:100',
            'date_filter' => 'nullable|in:today,week,month',
            'page' => 'nullable|integer|min:1'
        ]);

        // Start with base query
        $query = Post::with('user');

        // Search by query (content or topic)
        if ($request->filled('query')) {
            $searchTerm = $request->input('query');
            $query->where(function($q) use ($searchTerm) {
                $q->where('content', 'LIKE', "%{$searchTerm}%")
                  ->orWhere('topic', 'LIKE', "%{$searchTerm}%");
            });
        }

        // Filter by specific topic if provided
        if ($request->filled('topic')) {
            $query->where('topic', $request->input('topic'));
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

        // Prepare topics for filter dropdown
        $availableTopics = Post::select('topic')
            ->distinct()
            ->whereNotNull('topic')
            ->pluck('topic');

        // AJAX response
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'posts' => $posts->map(function ($post) {
                    return [
                        'id' => $post->id,
                        'content' => $post->content,
                        'topic' => $post->topic,
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
                'available_topics' => $availableTopics
            ]);
        }

        // Regular view response
        return view('search', [
            'posts' => $posts,
            'searchTerm' => $request->input('query'),
            'selectedTopic' => $request->input('topic'),
            'availableTopics' => $availableTopics,
            'dateFilter' => $request->input('date_filter')
        ]);
    }
}