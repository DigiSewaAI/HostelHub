<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    protected function authenticated(Request $request, $user)
    {
        return redirect()->intended($this->redirectPath());
    }

    protected function redirectPath()
    {
        return $this->redirectTo();
    }

    // FIXED: Correct role checking using relationship
    protected function redirectTo()
    {
        $user = Auth::user();

        // Use role relationship properly
        if ($user->isAdmin()) {
            return '/admin/dashboard';
        } elseif ($user->isHostelManager()) {
            return '/owner/dashboard';
        } elseif ($user->isStudent()) {
            return '/student/dashboard';
        }

        return '/';
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}
