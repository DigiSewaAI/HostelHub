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
        // Skip for pricing and registration pages
        if ($request->routeIs('pricing') || $request->routeIs('register.*')) {
            return $next($request);
        }

        // If user is not authenticated or no org context, let other middleware handle
        if (!Auth::check() || !$request->attributes->has('organization')) {
            return $next($request);
        }

        $organization = $request->attributes->get('organization');

        // Check subscription status
        $subscription = $organization->subscription;

        if (
            !$subscription ||
            ($subscription->status === 'cancelled' && now()->greaterThan($subscription->renews_at)) ||
            ($subscription->status === 'past_due' && now()->greaterThan($subscription->renews_at))
        ) {

            // Allow access to subscription page
            if ($request->routeIs('subscription.*')) {
                return $next($request);
            }

            return redirect()->route('subscription.show')
                ->with('error', 'तपाईंको सदस्यता सकिएको छ। कृपया नवीकरण गर्नुहोस्।');
        }

        return $next($request);
    }
}
