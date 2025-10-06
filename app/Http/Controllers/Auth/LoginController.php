<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class LoginController extends Controller
{
    protected function authenticated(Request $request, $user)
    {
        // Set organization session before redirecting
        $this->setOrganizationSession($user);

        return redirect()->intended($this->redirectPath());
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
        } elseif ($user->hasRole('hostel_manager')) {
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

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}
