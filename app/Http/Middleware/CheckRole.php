<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        $user = Auth::user();

        // Check if user is authenticated
        if (!$user) {
            return redirect()->route('login');
        }

        // Role mapping
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

        // Check if user has the required role
        if (!$user->hasRole($requiredRole)) {
            abort(403, 'Unauthorized action.');
        }

        return $next($request);
    }
}
