<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /**
     * Handle the authenticated user.
     */
    protected function authenticated(Request $request, $user)
    {
        return redirect()->intended($this->redirectPath());
    }

    /**
     * Get the post login redirect path based on user role.
     */
    protected function redirectPath()
    {
        return $this->redirectTo();
    }

    /**
     * Determine where to redirect users after login.
     */
    protected function redirectTo()
    {
        $user = Auth::user(); // वा $user = auth()->user();

        // भूमिका अनुसार पुनर्निर्देशन (साधारण role कलम प्रयोग गरी)
        return match ($user->role) {
            'admin' => '/admin/dashboard',
            'hostel_manager' => '/hostel/dashboard',
            'student' => '/student/dashboard',
            default => '/dashboard',
        };
    }

    /**
     * Log the user out of the application.
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
