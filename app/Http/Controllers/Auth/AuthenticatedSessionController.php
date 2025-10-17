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

        $user = Auth::user();

        switch ($user->role) {
            case 'admin':
                return redirect()->intended(route('admin.dashboard'));

            case 'student':
                return redirect()->intended(route('student.dashboard'));

            case 'owner':
                return redirect()->intended(route('owner.dashboard'));
                // यदि तिमी owner लाई register/organization मा पठाउन चाहन्छौ भने:
                // return redirect()->intended('/register/organization');

            default:
                return redirect()->intended('/');
        }
    }

    /**
     * Destroy an authenticated session (Logout).
     */
    public function destroy(Request $request)
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // ✅ सुरक्षित logout पछि मुख्य पृष्ठमा पठाउने
        return redirect('/')->with('status', 'तपाईं सफलतापूर्वक लगआउट भएको छ!');
    }
}
