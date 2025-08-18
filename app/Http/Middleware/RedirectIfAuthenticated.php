<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfAuthenticated
{
    public function handle(Request $request, Closure $next, ...$guards): Response
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                $user = Auth::guard($guard)->user();

                // Use direct URLs to prevent route name issues
                switch ($user->role->name) {
                    case 'admin':
                        return redirect('/admin/dashboard');
                    case 'hostel_manager':
                        return redirect('/owner/dashboard');
                    case 'student':
                        return redirect('/student/dashboard');
                    default:
                        return redirect('/');
                }
            }
        }

        return $next($request);
    }
}
