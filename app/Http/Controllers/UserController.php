<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function index()
    {
        // Cek jika pengguna yang login adalah 'Admin'
        if (Auth::user()->name !== 'Admin') {
            return redirect()->route('dashboard')->with('error', 'Access denied');
        }

        $users = User::all();
        return view('users.index', compact('users'));
    }

    public function create()
    {
        // Cek jika pengguna yang login adalah 'Admin'
        if (Auth::user()->name !== 'Admin') {
            return redirect()->route('dashboard')->with('error', 'Access denied');
        }

        return view('users.create');
    }

    public function store(Request $request)
    {
        // Cek jika pengguna yang login adalah 'Admin'
        if (Auth::user()->name !== 'Admin') {
            return redirect()->route('dashboard')->with('error', 'Access denied');
        }

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', 'min:8'],
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('users.index')->with('success', 'User created successfully');
    }

    public function edit($id)
    {
        // Cek jika pengguna yang login adalah 'Admin'
        if (Auth::user()->name !== 'Admin') {
            return redirect()->route('dashboard')->with('error', 'Access denied');
        }

        $user = User::findOrFail($id);
        return view('users.edit', compact('user'));
    }

    public function update(Request $request, $id)
    {
        // Cek jika pengguna yang login adalah 'Admin'
        if (Auth::user()->name !== 'Admin') {
            return redirect()->route('dashboard')->with('error', 'Access denied');
        }

        $user = User::findOrFail($id);

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'password' => ['nullable', 'confirmed', 'min:8'],
        ]);

        $user->name = $request->name;
        $user->email = $request->email;
        if ($request->password) {
            $user->password = Hash::make($request->password);
        }
        $user->save();

        return redirect()->route('users.index')->with('success', 'User updated successfully');
    }

    public function destroy($id)
    {
        // Cek jika pengguna yang login adalah 'Admin'
        if (Auth::user()->name !== 'Admin') {
            return redirect()->route('dashboard')->with('error', 'Access denied');
        }

        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->route('users.index')->with('success', 'User deleted successfully');
    }
}
