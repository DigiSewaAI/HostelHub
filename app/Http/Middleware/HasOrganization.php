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

        // ✅ CRITICAL FIX: Allow admin users to bypass organization check
        if ($user->hasRole('admin')) {
            // ✅ Set a default session organization for admin if needed
            if (!session()->has('current_organization_id')) {
                session(['current_organization_id' => null]);
            }
            return $next($request);
        }

        // Check if user has at least one organization
        if (!$user->organizations || $user->organizations->isEmpty()) {
            // Allow access to organization registration without redirect loop
            if ($request->routeIs('register.organization*')) {
                return $next($request);
            }

            // Students without organization should be allowed
            if ($user->hasRole('student')) {
                return $next($request);
            }

            return redirect()->route('register.organization')
                ->with('error', 'कृपया पहिले आफ्नो संस्था सिर्जना गर्नुहोस्।');
        }

        // ✅ PERMANENT FIX: Always set current_organization_id in session
        $firstOrganization = $user->organizations->first();
        if ($firstOrganization) {
            session(['current_organization_id' => $firstOrganization->id]);
        }

        return $next($request);
    }
}
