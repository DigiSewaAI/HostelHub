<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckRoleOrPermission
{
    public function handle(Request $request, Closure $next, ...$guards)
    {
        $user = auth()->user();

        if (!$user) {
            return redirect()->route('login')->with('error', 'कृपया लगइन गर्नुहोस्।');
        }

        // Admin लाई सबै access
        if ($user->hasRole('admin')) {
            return $next($request);
        }

        foreach ($guards as $guard) {
            if ($user->hasRole($guard) || $user->can($guard)) {
                return $next($request);
            }
        }

        abort(403, 'तपाईंसँग यो स्रोतमा पहुँच गर्ने अनुमति छैन।');
    }
}
