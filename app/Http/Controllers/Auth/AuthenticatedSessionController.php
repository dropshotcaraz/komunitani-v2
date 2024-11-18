<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View|RedirectResponse
    {
        // If user is already logged in, redirect to dashboard
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }

        // Store the current URL in the session if it's not the login page
        if (url()->current() !== route('login')) {
            session(['url.intended' => url()->current()]);
        }

        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        // Get the intended URL from session, default to dashboard if none exists
        $redirectTo = session('url.intended', route('dashboard'));
        
        // Clear the intended URL from session
        session()->forget('url.intended');

        return redirect($redirectTo);
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }

    /**
     * Check if user is authenticated for protected pages.
     * Add this to any protected route method.
     */
    protected function checkAuth(): ?RedirectResponse
    {
        if (!Auth::check()) {
            // Store the current URL before redirecting
            session(['url.intended' => url()->current()]);
            return redirect('/');
        }
        return null;
    }
}