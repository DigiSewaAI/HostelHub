<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    public function handle($request, Closure $next, $role)
    {
        if (!Auth::check()) {
            abort(403, 'Unauthorized');
        }

        // Handle multiple roles (admin,hostel_manager,student)
        $roles = explode(',', $role);

        if (!in_array(Auth::user()->getRoleName(), $roles)) {
            abort(403, 'Unauthorized');
        }

        return $next($request);
    }
}
