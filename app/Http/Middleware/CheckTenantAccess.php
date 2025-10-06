<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class CheckTenantAccess
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Skip for public routes
        if ($this->shouldSkip($request)) {
            return $next($request);
        }

        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login');
        }

        // Set organization session if not set
        if (!session('current_organization_id')) {
            $orgUser = DB::table('organization_user')
                ->where('user_id', $user->id)
                ->first();

            if ($orgUser) {
                session(['current_organization_id' => $orgUser->organization_id]);
            } else {
                // If no organization found and user is not admin, redirect to organization registration
                if (!$user->hasRole('admin')) {
                    return redirect()->route('register.organization');
                }
            }
        }

        return $next($request);
    }

    /**
     * Determine if the middleware should be skipped for the current request.
     */
    protected function shouldSkip(Request $request): bool
    {
        $publicRoutes = [
            'home',
            'about',
            'features',
            'how-it-works',
            'gallery',
            'pricing',
            'reviews',
            'testimonials',
            'privacy',
            'terms',
            'cookies',
            'contact',
            'rooms.search',
            'demo',
            'newsletter.subscribe',
            'login',
            'register',
            'password.request',
            'password.email',
            'password.reset',
            'verification.notice',
            'verification.verify',
            'verification.send'
        ];

        return in_array($request->route()->getName(), $publicRoutes) ||
            $request->routeIs('register.organization*') ||
            $request->is('api/*');
    }
}
