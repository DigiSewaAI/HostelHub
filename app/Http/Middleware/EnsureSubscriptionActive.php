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
            'logout'
        ];

        foreach ($exemptRoutes as $route) {
            if ($request->routeIs($route)) {
                return $next($request);
            }
        }

        // If user is not authenticated or no org context, proceed
        if (!Auth::check() || !$request->attributes->has('organization')) {
            return $next($request);
        }

        $organization = $request->attributes->get('organization');

        // Check subscription status
        $subscription = $organization->subscription;

        $subscriptionActive = $subscription &&
            !($subscription->status === 'cancelled' && now()->greaterThan($subscription->renews_at)) &&
            !($subscription->status === 'past_due' && now()->greaterThan($subscription->renews_at));

        if (!$subscriptionActive) {
            // Allow access to subscription and exempted pages
            if ($request->routeIs('subscription.*')) {
                return $next($request);
            }

            return redirect()->route('subscription.show')
                ->with('error', 'तपाईंको सदस्यता सकिएको छ। कृपया नवीकरण गर्नुहोस्।');
        }

        return $next($request);
    }
}
