<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthenticatedSessionController extends Controller
{
    /**
     * Show the login view.
     */
    public function create()
    {
        // resources/views/auth/login.blade.php file load हुन्छ
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request)
    {
        // Validate and authenticate user
        $request->authenticate();

        // Prevent session fixation
        $request->session()->regenerate();

        // Redirect to intended page or homepage if none
        return redirect()->intended(url('/'));
    }

    /**
     * Destroy an authenticated session (Logout).
     */
    public function destroy(Request $request)
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // सुरक्षित logout पछि login page वा home page मा पठाउने
        return redirect('/login');
    }
}
