<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();

        // Admin लाई सबैमा access दिने
        if ($user->hasRole('admin')) {
            return $next($request);
        }

        // Multiple roles check गर्ने
        foreach ($roles as $role) {
            if ($user->hasRole($role)) {
                return $next($request);
            }
        }

        // ✅ Better error message for debugging
        abort(403, 'Unauthorized access. Your roles: ' . $user->getRoleNames()->implode(', ') . ' | Required: ' . implode(', ', $roles));
    }
}
