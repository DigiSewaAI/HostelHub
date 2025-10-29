<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class LoginController extends Controller
{
    /**
     * Show the application's login form.
     */
    public function showLoginForm(): View
    {
        try {
            return view('auth.login');
        } catch (\Exception $e) {
            Log::error('Login form error: ' . $e->getMessage());
            // Emergency fallback - return simple login form
            return view('auth.login', ['error' => 'Temporary issue loading login form']);
        }
    }

    /**
     * Handle a login request to the application.
     */
    public function login(Request $request)
    {
        try {
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
        } catch (\Exception $e) {
            Log::error('Login process error: ' . $e->getMessage());

            return back()->withErrors([
                'email' => 'Login failed due to system error. Please try again.',
            ])->onlyInput('email');
        }
    }

    // ✅ FIXED: Add authenticated method for post-login redirect with intended
    protected function authenticated(Request $request, $user)
    {
        try {
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
        } catch (\Exception $e) {
            Log::error('Post-login redirect error: ' . $e->getMessage());
            return redirect('/');
        }
    }

    protected function redirectPath()
    {
        try {
            return $this->redirectTo();
        } catch (\Exception $e) {
            Log::error('Redirect path error: ' . $e->getMessage());
            return '/';
        }
    }

    // FIXED: Set organization session and proper role checking
    protected function redirectTo()
    {
        try {
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
        } catch (\Exception $e) {
            Log::error('Redirect to error: ' . $e->getMessage());
            return '/';
        }
    }

    /**
     * Set organization session for the user
     */
    protected function setOrganizationSession($user)
    {
        try {
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
                Log::info('Organization session set for user: ' . $user->id);
            }
        } catch (\Exception $e) {
            Log::error('Set organization session error: ' . $e->getMessage());
            // Continue without organization session - don't break login
        }
    }

    // ✅ FIXED: Simplified logout method with error handling
    public function logout(Request $request)
    {
        try {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            Log::info('User logged out successfully');
            return redirect('/');
        } catch (\Exception $e) {
            Log::error('Logout error: ' . $e->getMessage());
            // Even if logout fails, try to redirect
            return redirect('/');
        }
    }
}
