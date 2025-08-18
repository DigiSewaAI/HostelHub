<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    public function handle(Request $request, Closure $next, string $role): Response
    {
        if (!$request->user()) {
            return redirect()->route('login');
        }

        $user = $request->user();

        // FIXED: Role mapping for consistency
        $roleMap = [
            'admin' => 'admin',
            'owner' => 'hostel_manager',
            'student' => 'student'
        ];

        $requiredRole = $roleMap[$role] ?? $role;

        // Allow admin to access any route
        if ($user->isAdmin()) {
            return $next($request);
        }

        // For non-admin users
        if (!$user->hasRole($requiredRole)) {
            abort(403, 'Unauthorized action.');
        }

        return $next($request);
    }
}
