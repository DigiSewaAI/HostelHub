<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, string ...$guards): Response
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                $user = Auth::guard($guard)->user();

                // Redirect according to user role
                if ($user->hasRole('admin')) {
                    return redirect()->route('admin.dashboard');
                }

                if ($user->hasRole('hostel_manager') || $user->hasRole('owner')) {
                    return redirect()->route('owner.dashboard');
                }

                if ($user->hasRole('student')) {
                    // ✅ FIXED: Check if student is connected to hostel
                    if ($user->hostel_id || $user->organization_id) {
                        return redirect()->route('student.dashboard');
                    } else {
                        return redirect()->route('student.welcome');
                    }
                }

                // Default fallback
                return redirect()->route('home');
            }
        }

        return $next($request);
    }
}
