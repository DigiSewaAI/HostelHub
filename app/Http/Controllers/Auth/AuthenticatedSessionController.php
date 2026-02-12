<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class AuthenticatedSessionController extends Controller
{
    public function create()
    {
        return view('auth.login');
    }

    public function store(LoginRequest $request)
    {
        $request->authenticate();
        $request->session()->regenerate();

        $user = Auth::user();
        Log::info('LOGIN REDIRECT CHECK', ['user_id' => $user->id, 'email' => $user->email]);

        switch ($user->role) {
            case 'admin':
                Log::info('Redirect admin to admin.dashboard');
                return redirect()->intended(route('admin.dashboard'));

            case 'student':
                $student = $user->student;
                Log::info('Student record found', ['exists' => !is_null($student)]);

                if ($student && $student->status === 'active' && $student->hostel_id !== null) {
                    Log::info('Active + hostel assigned → student.dashboard');
                    return redirect()->intended(route('student.dashboard'));
                }

                Log::info('No active hostel → student.welcome');
                return redirect()->intended(route('student.welcome'));

            case 'owner':
                Log::info('Redirect owner to owner.dashboard');
                return redirect()->intended(route('owner.dashboard'));

            default:
                Log::info('Fallback redirect to /');
                return redirect()->intended('/');
        }
    }

    public function destroy(Request $request)
    {
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/')->with('status', 'तपाईं सफलतापूर्वक लगआउट भएको छ!');
    }
}
