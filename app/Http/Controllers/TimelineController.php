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
        // Cek apakah ini untuk halaman 'dashboard' atau 'followingpage'
        // Jika untuk halaman dashboard, tampilkan semua posts
        if (request()->routeIs('dashboard')) {
            $posts = Post::latest()->get();  // Menampilkan semua posts
        } else {
            // Jika untuk halaman followingpage, tampilkan posts dari pengguna yang diikuti
            if (auth()->check()) {
                $user = auth()->user();
                $followingUserIds = $user->follows()->pluck('user_id'); // Ambil id pengguna yang diikuti oleh pengguna yang sedang login

                // Tampilkan post hanya dari pengguna yang diikuti
                $posts = Post::whereIn('user_id', $followingUserIds)->latest()->get();
            } else {
                $posts = collect();  // Jika pengguna belum login, kembalikan koleksi kosong
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
