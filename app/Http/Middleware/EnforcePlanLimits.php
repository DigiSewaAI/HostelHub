<?php

namespace App\Http\Middleware;

use App\Models\Organization;
use App\Models\Subscription;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnforcePlanLimits
{
    public function handle(Request $request, Closure $next, $type = null): Response
    {
        // Skip for non-authenticated users
        if (!Auth::check()) {
            return $next($request);
        }

        $user = Auth::user();
        $organizationId = session('current_organization_id');

        if (!$organizationId) {
            return $next($request);
        }

        $organization = Organization::find($organizationId);
        $subscription = Subscription::where('organization_id', $organizationId)->first();

        if (!$organization || !$subscription) {
            return $next($request);
        }

        // Check hostel limits for create/store routes
        if ($request->routeIs('owner.hostels.create') || $request->routeIs('owner.hostels.store')) {
            if (!$subscription->canAddMoreHostels()) {
                $hostelsCount = \App\Models\Hostel::where('organization_id', $organizationId)->count();
                $remainingSlots = $subscription->getRemainingHostelSlots();
                $plan = $subscription->plan;

                if ($plan) {
                    $maxAllowed = $plan->getMaxHostelsWithAddons($subscription->extra_hostels ?? 0);
                    $message = "तपाईंको {$plan->name} योजनामा {$maxAllowed} होस्टेल मात्र सिर्जना गर्न सकिन्छ। (हाल {$hostelsCount} होस्टेल छन्, {$remainingSlots} स्लट बाँकी छन्)";

                    // Enterprise plan को लागि add-on को विकल्प दिने
                    if ($plan->slug === 'enterprise' && $remainingSlots == 0) {
                        $message .= " अतिरिक्त होस्टल स्लट खरिद गर्न सदस्यता सेटिङ्गमा जानुहोस्।";
                    }
                } else {
                    $message = "तपाईंसँग कुनै सक्रिय योजना छैन। होस्टेल सिर्जना गर्न योजना सक्रिय गर्नुहोस्।";
                }

                if ($request->routeIs('owner.hostels.create')) {
                    return redirect()->route('owner.hostels.index')->with('error', $message);
                } else {
                    return redirect()->back()->with('error', $message);
                }
            }
        }

        // Check student limits for create/store routes
        if ($request->routeIs('owner.students.create') || $request->routeIs('owner.students.store')) {
            $studentsCount = \App\Models\Student::where('organization_id', $organizationId)->count();

            if (!$subscription->plan->canCreateMoreStudents($studentsCount + 1)) {
                $plan = $subscription->plan;

                if ($plan) {
                    $message = "तपाईंको {$plan->name} योजनामा {$plan->max_students} विद्यार्थीहरू मात्र दर्ता गर्न सकिन्छ। (हाल {$studentsCount} विद्यार्थीहरू छन्)";
                } else {
                    $message = "तपाईंसँग कुनै सक्रिय योजना छैन। विद्यार्थी दर्ता गर्न योजना सक्रिय गर्नुहोस्।";
                }

                if ($request->routeIs('owner.students.create')) {
                    return redirect()->route('owner.students.index')->with('error', $message);
                } else {
                    return redirect()->back()->with('error', $message);
                }
            }
        }

        // Check room limits for create/store routes
        if ($request->routeIs('owner.rooms.create') || $request->routeIs('owner.rooms.store')) {
            $roomsCount = \App\Models\Room::whereHas('hostel', function ($query) use ($organizationId) {
                $query->where('organization_id', $organizationId);
            })->count();

            if (!$subscription->plan->canCreateMoreRooms($roomsCount + 1)) {
                $plan = $subscription->plan;

                if ($plan && isset($plan->max_rooms)) {
                    $message = "तपाईंको {$plan->name} योजनामा {$plan->max_rooms} कोठाहरू मात्र सिर्जना गर्न सकिन्छ। (हाल {$roomsCount} कोठाहरू छन्)";
                } else {
                    $message = "तपाईंसँग कुनै सक्रिय योजना छैन वा कोठा सीमा परिभाषित छैन।";
                }

                if ($request->routeIs('owner.rooms.create')) {
                    return redirect()->route('owner.rooms.index')->with('error', $message);
                } else {
                    return redirect()->back()->with('error', $message);
                }
            }
        }

        return $next($request);
    }
}
