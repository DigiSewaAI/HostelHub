<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckPermission
{
    public function handle(Request $request, Closure $next, $permission)
    {
        if (!auth()->check()) {
            return redirect()->route('login')->with('error', 'कृपया लगइन गर्नुहोस्।');
        }

        $user = auth()->user();

        // Admin लाई सबै permission
        if ($user->hasRole('admin')) {
            return $next($request);
        }

        // Check permission
        if (!$user->can($permission)) {
            abort(403, 'तपाईंसँग यो कार्य गर्ने अनुमति छैन।');
        }

        return $next($request);
    }
}
