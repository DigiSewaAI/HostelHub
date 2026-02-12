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

    /**
     * ✅ FIXED: Post-login redirect with proper student check
     */
    protected function authenticated(Request $request, $user)
    {
        try {
            // Set organization session
            $this->setOrganizationSession($user);

            // 🟢 STUDENT: Check if active + hostel assigned
            if ($user->hasRole('student')) {
                $student = $user->student; // User model बाट student लिने

                if ($student && $student->status === 'active' && $student->hostel_id !== null) {
                    Log::info('LoginController: Student with active hostel → dashboard', [
                        'user_id' => $user->id
                    ]);
                    return redirect()->intended(route('student.dashboard'));
                }

                Log::info('LoginController: Student without active hostel → welcome', [
                    'user_id' => $user->id
                ]);
                return redirect()->intended(route('student.welcome'));
            }

            // 🟠 OWNER / HOSTEL MANAGER
            elseif ($user->hasRole('hostel_manager') || $user->hasRole('owner')) {
                return redirect()->intended(route('owner.dashboard'));
            }

            // 🔴 ADMIN
            elseif ($user->hasRole('admin')) {
                return redirect()->intended(route('admin.dashboard'));
            }

            return redirect()->intended('/');
        } catch (\Exception $e) {
            Log::error('Post-login redirect error: ' . $e->getMessage());
            return redirect('/');
        }
    }

    /**
     * Fallback redirect path (if needed)
     */
    protected function redirectTo()
    {
        try {
            $user = Auth::user();

            $this->setOrganizationSession($user);

            if ($user->hasRole('student')) {
                $student = $user->student;
                if ($student && $student->status === 'active' && $student->hostel_id !== null) {
                    return route('student.dashboard');
                }
                return route('student.welcome');
            } elseif ($user->hasRole('hostel_manager') || $user->hasRole('owner')) {
                return route('owner.dashboard');
            } elseif ($user->hasRole('admin')) {
                return route('admin.dashboard');
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
            if (session('current_organization_id')) {
                return;
            }

            $orgUser = DB::table('organization_user')
                ->where('user_id', $user->id)
                ->first();

            if ($orgUser) {
                session(['current_organization_id' => $orgUser->organization_id]);
                Log::info('Organization session set for user: ' . $user->id);
            }
        } catch (\Exception $e) {
            Log::error('Set organization session error: ' . $e->getMessage());
        }
    }

    /**
     * Logout the user
     */
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
            return redirect('/');
        }
    }
}
