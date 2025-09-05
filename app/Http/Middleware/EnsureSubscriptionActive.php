<?php

namespace App\Http\Middleware;

use App\Models\Organization;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureSubscriptionActive
{
    public function handle(Request $request, Closure $next): Response
    {
        // Development मा subscription check skip गर्ने
        if (app()->environment('local')) {
            return $next($request);
        }

        // Skip for relevant routes
        $exemptRoutes = [
            'pricing',
            'register.*',
            'subscription.*',
            'login',
            'logout',
            'password.*',
            'verification.*'
        ];

        foreach ($exemptRoutes as $route) {
            if ($request->routeIs($route)) {
                return $next($request);
            }
        }

        // If user is not authenticated, proceed
        if (!Auth::check()) {
            return $next($request);
        }

        $user = Auth::user();

        // Get organization from user relationship
        $organization = $user->organization;

        // If no organization found, redirect to organization registration
        if (!$organization) {
            return redirect()->route('register.organization')
                ->with('error', 'कृपया पहिले आफ्नो होस्टल दर्ता गर्नुहोस्।');
        }

        // Check subscription status
        $subscription = $organization->subscription;

        $subscriptionActive = $subscription &&
            $subscription->status === 'active' &&
            now()->lessThanOrEqualTo($subscription->expires_at);

        if (!$subscriptionActive) {
            // Allow access to subscription and exempted pages
            if ($request->routeIs('subscription.*')) {
                return $next($request);
            }

            return redirect()->route('subscription.show')
                ->with('error', 'तपाईंको सदस्यता सकिएको छ वा सक्रिय छैन। कृपया नवीकरण गर्नुहोस्।');
        }

        return $next($request);
    }
}
