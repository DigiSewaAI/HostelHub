<?php

namespace App\Http\Middleware;

use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Owner\DashboardController as OwnerDashboardController;
use App\Http\Controllers\Student\DashboardController as StudentDashboardController;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfAuthenticated
{
    public function handle(Request $request, Closure $next, string ...$guards): Response
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                $user = Auth::guard($guard)->user();

                if ($user->hasRole('admin')) {
                    return redirect()->action([AdminDashboardController::class, 'index']);
                } elseif ($user->hasRole('hostel_manager')) {
                    return redirect()->action([OwnerDashboardController::class, 'index']);
                } elseif ($user->hasRole('student')) {
                    return redirect()->action([StudentDashboardController::class, 'index']);
                }

                return redirect('/home');
            }
        }

        return $next($request);
    }
}
