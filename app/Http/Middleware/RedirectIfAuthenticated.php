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

                // ✅ FIXED: Simplified role-based redirect
                if ($user->hasRole('admin')) {
                    return redirect()->route('admin.dashboard');
                } elseif ($user->hasRole('hostel_manager') || $user->hasRole('owner')) {
                    return redirect()->route('owner.dashboard');
                } elseif ($user->hasRole('student')) {
                    return redirect()->route('student.dashboard');
                }

                // Default fallback
                return redirect()->route('home');
            }
        }

        return $next($request);
    }
}
