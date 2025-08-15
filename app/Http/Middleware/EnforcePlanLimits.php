<?php

namespace App\Http\Middleware;

use App\Models\Organization;
use App\Services\PlanLimitService;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnforcePlanLimits
{
    protected $planLimitService;

    public function __construct(PlanLimitService $planLimitService)
    {
        $this->planLimitService = $planLimitService;
    }

    public function handle(Request $request, Closure $next): Response
    {
        // Skip for non-create/update routes
        if (!$request->isMethod('post') && !$request->isMethod('put') && !$request->isMethod('patch')) {
            return $next($request);
        }

        // If user is not authenticated or no org context, let other middleware handle
        if (!Auth::check() || !$request->attributes->has('organization')) {
            return $next($request);
        }

        $organization = $request->attributes->get('organization');

        // Check students limit
        if ($request->routeIs('students.store') || $request->routeIs('students.update')) {
            if (!$this->planLimitService->canAddStudent($organization)) {
                return redirect()->back()->with(
                    'error',
                    'तपाईंको योजनाले अधिकतम ' . $organization->subscription->plan->max_students . ' विद्यार्थीहरू मात्र समर्थन गर्दछ। कृपया योजना अपग्रेड गर्नुहोस्।'
                );
            }
        }

        // Check hostels limit
        if ($request->routeIs('hostels.store') || $request->routeIs('hostels.update')) {
            if (!$this->planLimitService->canAddHostel($organization)) {
                return redirect()->back()->with(
                    'error',
                    'तपाईंको योजनाले अधिकतम ' . $organization->subscription->plan->max_hostels . ' होस्टलहरू मात्र समर्थन गर्दछ। कृपया योजना अपग्रेड गर्नुहोस्।'
                );
            }
        }

        return $next($request);
    }
}
