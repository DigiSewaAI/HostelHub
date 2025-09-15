<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class HasOrganization
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login');
        }

        // Check if user has at least one organization
        if (!$user->organizations || $user->organizations->isEmpty()) {
            // Allow access to organization registration without redirect loop
            if ($request->routeIs('register.organization*')) {
                return $next($request);
            }

            return redirect()->route('register.organization')
                ->with('error', 'कृपया पहिले आफ्नो संस्था सिर्जना गर्नुहोस्।');
        }

        return $next($request);
    }
}
