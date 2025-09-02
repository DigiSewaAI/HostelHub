<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfAuthenticated
{

    public function handle(Request $request, Closure $next, ...$guards)
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                $user = Auth::guard($guard)->user();

                // Use Spatie's role checking
                if ($user->hasRole('admin')) {
                    return redirect('/admin/dashboard');
                } elseif ($user->hasRole('hostel_manager')) {
                    return redirect('/owner/dashboard');
                } elseif ($user->hasRole('student')) {
                    return redirect('/student/dashboard');
                }

                return redirect('/home');
            }
        }

        return $next($request);
    }
}
