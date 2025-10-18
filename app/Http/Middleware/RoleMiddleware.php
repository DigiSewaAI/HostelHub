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

        // Admin has access to everything
        if ($user->hasRole('admin')) {
            return $next($request);
        }

        // Check for multiple roles
        foreach ($roles as $role) {
            if ($user->hasRole($role)) {
                return $next($request);
            }
        }

        // Special case: hostel_manager should access owner routes
        if ($user->hasRole('hostel_manager') && in_array('owner', $roles)) {
            return $next($request);
        }

        // Special case: owner should access hostel_manager routes  
        if ($user->hasRole('owner') && in_array('hostel_manager', $roles)) {
            return $next($request);
        }

        // Better error message for debugging
        abort(403, 'Unauthorized access. Your roles: ' . $user->getRoleNames()->implode(', ') . ' | Required: ' . implode(', ', $roles));
    }
}
