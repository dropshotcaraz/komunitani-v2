<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Post;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function search(Request $request)
    {
        // Validate the input
        $request->validate([
            'query' => 'required|string|min:1',
        ]);

        // Get the search query from the request
        $query = $request->input('query');

        // Use the search scopes in the models
        $users = User::searchByName($query)->get();
        $posts = Post::searchByContent($query)->get();

        // Return the results as JSON
        return response()->json([
            'users' => $users,
            'posts' => $posts,
        ]);
    }

    public function showSearchPage()
    {
        return view('search'); // Make sure to create this view as `resources/views/search.blade.php`
    }

}
