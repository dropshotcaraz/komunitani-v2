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
        if (request()->routeIs('dashboard')) {
            // Menampilkan semua posts untuk dashboard
            $posts = Post::latest()->get();
        } else {
            // Untuk halaman 'followingpage'
            if (auth()->check()) {
                $user = auth()->user();
                // Ambil id pengguna yang diikuti dan tambahkan id pengguna yang sedang login
                $followingUserIds = $user->follows()->pluck('user_id')->toArray();
                $followingUserIds[] = $user->id; // Tambahkan id pengguna yang sedang login

                // Tampilkan post dari pengguna yang diikuti dan pengguna yang login
                $posts = Post::whereIn('user_id', $followingUserIds)->latest()->get();
            } else {
                // Jika pengguna belum login, kembalikan koleksi kosong
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
