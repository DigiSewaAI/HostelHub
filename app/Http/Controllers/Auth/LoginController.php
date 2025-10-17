<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class LoginController extends Controller
{
    /**
     * Show the application's login form.
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Handle a login request to the application.
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            // ✅ FIXED: Use the authenticated method for proper redirect
            return $this->authenticated($request, Auth::user());
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    // ✅ FIXED: Add authenticated method for post-login redirect with intended
    protected function authenticated(Request $request, $user)
    {
        // Set organization session first
        $this->setOrganizationSession($user);

        // ✅ FIXED: Use redirect()->intended() for proper redirect handling
        if ($user->hasRole('admin')) {
            return redirect()->intended(route('admin.dashboard'));
        } elseif ($user->hasRole('hostel_manager') || $user->hasRole('owner')) {
            return redirect()->intended(route('owner.dashboard'));
        } elseif ($user->hasRole('student')) {
            return redirect()->intended(route('student.dashboard'));
        }

        return redirect()->intended('/');
    }

    protected function redirectPath()
    {
        return $this->redirectTo();
    }

    // FIXED: Set organization session and proper role checking
    protected function redirectTo()
    {
        $user = Auth::user();

        // Set organization session first
        $this->setOrganizationSession($user);

        // Use role relationship properly
        if ($user->hasRole('admin')) {
            return '/admin/dashboard';
        } elseif ($user->hasRole('hostel_manager') || $user->hasRole('owner')) {
            return '/owner/dashboard';
        } elseif ($user->hasRole('student')) {
            return '/student/dashboard';
        }

        return '/';
    }

    /**
     * Set organization session for the user
     */
    protected function setOrganizationSession($user)
    {
        // If session already has organization, no need to set again
        if (session('current_organization_id')) {
            return;
        }

        // Get user's organization from database
        $orgUser = DB::table('organization_user')
            ->where('user_id', $user->id)
            ->first();

        if ($orgUser) {
            session(['current_organization_id' => $orgUser->organization_id]);
        }
    }

    // ✅ FIXED: Simplified logout method
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}
