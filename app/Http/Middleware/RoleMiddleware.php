<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, string $role): Response
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        // DIRECT QUERY - NO RELATIONS
        $hasRole = DB::table('users')
            ->where('id', Auth::id())
            ->where('role_id', $this->getRoleId($role))
            ->exists();

        if (!$hasRole) {
            abort(403, 'Unauthorized access. Required role: ' . $role);
        }

        return $next($request);
    }

    private function getRoleId(string $role): int
    {
        return match ($role) {
            'admin' => 1,
            'hostel_manager' => 2,
            'student' => 3,
            default => 0
        };
    }
}
