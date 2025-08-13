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
    public function handle(Request $request, Closure $next, ...$guards): Response
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                $user = Auth::guard($guard)->user();

                // भूमिका आधारित पुनर्निर्देशन (spatie बिना)
                return match ($user->role) {
                    'admin' => redirect('/admin/dashboard'),
                    'hostel_manager' => redirect('/hostel/dashboard'),
                    'student' => redirect('/student/dashboard'),
                    default => redirect('/dashboard'),
                };
            }
        }

        return $next($request);
    }
}
