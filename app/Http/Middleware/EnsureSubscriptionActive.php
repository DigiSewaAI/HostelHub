<?php

namespace App\Http\Middleware;

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

        // Skip for non-authenticated users
        if (!Auth::check()) {
            return $next($request);
        }

        $user = Auth::user();

        // Admin लाई सबै access
        if ($user->hasRole('admin')) {
            return $next($request);
        }

        // Student लाई पनि access दिने (students ले subscription require गर्दैन)
        if ($user->hasRole('student')) {
            return $next($request);
        }

        // Skip for relevant routes (जहाँ subscription required छैन)
        $exemptRoutes = [
            'pricing',
            'register.*',
            'subscription.*',
            'login',
            'logout',
            'password.*',
            'verification.*',
            'owner.subscription.*',
            'payment.*',
            'public.*',
            'home',
            'welcome'
        ];

        foreach ($exemptRoutes as $route) {
            if ($request->routeIs($route)) {
                return $next($request);
            }
        }

        // Owner को लागि मात्र subscription check
        if ($user->hasRole('owner')) {
            $owner = $user->owner;

            // If no owner profile found, redirect to complete profile
            if (!$owner) {
                if (!$request->routeIs('owner.profile.create') && !$request->routeIs('owner.profile.store')) {
                    return redirect()->route('owner.profile.create')
                        ->with('error', 'कृपया पहिले आफ्नो प्रोफाइल पूरा गर्नुहोस्।');
                }
                return $next($request);
            }

            // Check subscription status
            $subscription = $owner->subscription;

            // यदि कुनै subscription नै छैन भने
            if (!$subscription) {
                // Allow access to subscription pages
                if ($request->routeIs('owner.subscription.*') || $request->routeIs('owner.dashboard')) {
                    return $next($request);
                }

                return redirect()->route('owner.subscription.limits')
                    ->with('error', 'तपाईंसँग कुनै सक्रिय सदस्यता छैन। कृपया सदस्यता लिनुहोस्।');
            }

            // Check if subscription is active
            $subscriptionActive = $subscription->status === 'active' || $subscription->status === 'trialing';

            // Check expiration for non-trial subscriptions
            if ($subscription->status === 'active' && $subscription->expires_at) {
                $subscriptionActive = now()->lessThanOrEqualTo($subscription->expires_at);
            }

            // Trial period check
            if ($subscription->status === 'trialing' && $subscription->trial_ends_at) {
                $subscriptionActive = now()->lessThanOrEqualTo($subscription->trial_ends_at);
            }

            if (!$subscriptionActive) {
                // Allow access to subscription and exempted pages
                if (
                    $request->routeIs('owner.subscription.*') ||
                    $request->routeIs('owner.dashboard') ||
                    $request->routeIs('payment.*')
                ) {
                    return $next($request);
                }

                // Determine appropriate message
                if ($subscription->status === 'expired') {
                    $message = 'तपाईंको सदस्यता सकिएको छ। कृपया नवीकरण गर्नुहोस्।';
                } elseif ($subscription->status === 'canceled') {
                    $message = 'तपाईंको सदस्यता रद्द गरिएको छ। कृपया पुनः सक्रिय गर्नुहोस्।';
                } elseif ($subscription->trial_ends_at && now()->greaterThan($subscription->trial_ends_at)) {
                    $message = 'तपाईंको नि:शुल्क परीक्षण अवधि सकिएको छ। कृपया सदस्यता लिनुहोस्।';
                } else {
                    $message = 'तपाईंको सदस्यता सक्रिय छैन। कृपया सदस्यता लिनुहोस्।';
                }

                return redirect()->route('owner.subscription.limits')
                    ->with('error', $message)
                    ->with('subscription_inactive', true);
            }

            // Check for upcoming expiration (7 days warning)
            if ($subscription->expires_at && now()->addDays(7)->greaterThanOrEqualTo($subscription->expires_at)) {
                $daysRemaining = now()->diffInDays($subscription->expires_at);

                if ($daysRemaining > 0) {
                    session()->flash('warning', "तपाईंको सदस्यता {$daysRemaining} दिनमा सकिनेछ। कृपया नवीकरण गर्नुहोस्।");
                }
            }

            // Check for trial ending soon (3 days warning)
            if ($subscription->trial_ends_at && now()->addDays(3)->greaterThanOrEqualTo($subscription->trial_ends_at)) {
                $trialDaysRemaining = now()->diffInDays($subscription->trial_ends_at);

                if ($trialDaysRemaining > 0) {
                    session()->flash('info', "तपाईंको नि:शुल्क परीक्षण {$trialDaysRemaining} दिनमा सकिनेछ। कृपया सदस्यता लिनुहोस्।");
                }
            }
        }

        return $next($request);
    }

    /**
     * Check if the current route should be exempt from subscription check
     */
    private function isExemptRoute(Request $request): bool
    {
        $exemptPatterns = [
            'subscription.*',
            'payment.*',
            'owner.subscription.*',
            'pricing',
            'register.*',
            'login',
            'logout',
            'password.*',
            'verification.*',
            'public.*',
            'home',
            'welcome'
        ];

        foreach ($exemptPatterns as $pattern) {
            if ($request->routeIs($pattern)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Get appropriate redirect route based on subscription status
     */
    private function getRedirectRoute($subscription): string
    {
        if (!$subscription) {
            return 'owner.subscription.plans';
        }

        switch ($subscription->status) {
            case 'expired':
            case 'canceled':
                return 'owner.subscription.renew';
            case 'trialing':
                if ($subscription->trial_ends_at && now()->greaterThan($subscription->trial_ends_at)) {
                    return 'owner.subscription.plans';
                }
                return 'owner.subscription.limits';
            default:
                return 'owner.subscription.limits';
        }
    }

    /**
     * Get appropriate error message based on subscription status
     */
    private function getErrorMessage($subscription): string
    {
        if (!$subscription) {
            return 'तपाईंसँग कुनै सक्रिय सदस्यता छैन। कृपया सदस्यता लिनुहोस्।';
        }

        switch ($subscription->status) {
            case 'expired':
                return 'तपाईंको सदस्यता सकिएको छ। कृपया नवीकरण गर्नुहोस्।';

            case 'canceled':
                return 'तपाईंको सदस्यता रद्द गरिएको छ। कृपया पुनः सक्रिय गर्नुहोस्।';

            case 'trialing':
                if ($subscription->trial_ends_at && now()->greaterThan($subscription->trial_ends_at)) {
                    return 'तपाईंको नि:शुल्क परीक्षण अवधि सकिएको छ। कृपया सदस्यता लिनुहोस्।';
                }
                return 'तपाईंको सदस्यता सक्रिय छैन। कृपया सदस्यता लिनुहोस्।';

            default:
                return 'तपाईंको सदस्यता सक्रिय छैन। कृपया सदस्यता लिनुहोस्।';
        }
    }
}
