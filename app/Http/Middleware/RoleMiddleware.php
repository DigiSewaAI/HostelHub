<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, $role): Response
    {
        if (!Auth::check()) {
            return redirect('login');
        }

        $user = Auth::user();

        // hasRole() मेथड प्रयोग गर्ने
        if ($user->hasRole($role)) {
            return $next($request);
        }

        abort(403, 'तपाईंसँग यो पृष्ठ हेर्न अनुमति छैन।');
    }
}
