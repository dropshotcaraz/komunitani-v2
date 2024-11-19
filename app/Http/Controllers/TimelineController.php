<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\User;
use Illuminate\Contracts\View\View;

class TimelineController extends Controller
{
    public function __invoke(): View
    {

        if (request()->routeIs('dashboard')) {
            $posts = Post::latest()->get();
        } else {

            if (auth()->check()) {
                $user = auth()->user();
                $followingUserIds = $user->follows()->pluck('user_id');

                $posts = Post::whereIn('user_id', $followingUserIds)->latest()->get();
            } else {
                $posts = collect();
            }
        }

        return view('dashboard', [
            'posts' => $posts
        ]);
    }
}



/**
 * Display a listing of the resource.
 */
    // /**
    //  * Store a newly created resource in storage.
    //  */
    // public function store(Request $request)
    // {
    //     //
    // }

    // /**
    //  * Display the specified resource.
    //  */
    // public function show(string $id)
    // {
    //     //
    // }

    // /**
    //  * Update the specified resource in storage.
    //  */
    // public function update(Request $request, string $id)
    // {
    //     //
    // }

    // /**
    //  * Remove the specified resource from storage.
    //  */
    // public function destroy(string $id)
    // {
    //     //
    // }
