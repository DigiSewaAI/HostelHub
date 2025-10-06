<?php

namespace App\Http\Controllers;

use App\Models\Organization;
use App\Models\Plan;
use App\Models\Subscription;
use App\Models\Hostel;
use App\Models\Student;
use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class SubscriptionController extends Controller
{
    public function show()
    {
        // Get the current organization from the session or user relationship
        $organizationId = session('current_organization_id');

        if (!$organizationId) {
            // Fallback: get the first organization associated with the user
            $user = Auth::user();
            $organizationUser = DB::table('organization_user')
                ->where('user_id', $user->id)
                ->first();

            if ($organizationUser) {
                $organizationId = $organizationUser->organization_id;
                session(['current_organization_id' => $organizationId]);
            } else {
                return redirect()->route('register.organization')
                    ->with('error', 'कृपया पहिले संस्था दर्ता गर्नुहोस्');
            }
        }

        $organization = Organization::with('subscription.plan')->find($organizationId);

        if (!$organization) {
            return redirect()->route('register.organization')
                ->with('error', 'संस्था भेटिएन। कृपया पहिले संस्था दर्ता गर्नुहोस्');
        }

        $currentPlan = $organization->subscription ? $organization->subscription->plan : null;
        $availablePlans = Plan::where('is_active', true)
            ->where('id', '!=', $currentPlan?->id)
            ->orderBy('sort_order')
            ->get();

        // Calculate hostel limits for the view
        $hostelsCount = Hostel::where('organization_id', $organizationId)->count();
        $remainingSlots = $organization->subscription ? $organization->subscription->getRemainingHostelSlots() : 0;

        return view('subscription.show', compact(
            'organization',
            'currentPlan',
            'availablePlans',
            'hostelsCount',
            'remainingSlots'
        ));
    }

    public function upgrade(Request $request)
    {
        // Get the current organization from the session
        $organizationId = session('current_organization_id');

        if (!$organizationId) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'संस्था भेटिएन। कृपया पहिले संस्था दर्ता गर्नुहोस्'
                ], 422);
            }

            return redirect()->route('register.organization')
                ->with('error', 'संस्था भेटिएन। कृपया पहिले संस्था दर्ता गर्नुहोस्');
        }

        $organization = Organization::find($organizationId);

        if (!$organization) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'संस्था भेटिएन। कृपया पहिले संस्था दर्ता गर्नुहोस्'
                ], 422);
            }

            return redirect()->route('register.organization')
                ->with('error', 'संस्था भेटिएन। कृपया पहिले संस्था दर्ता गर्नुहोस्');
        }

        $request->validate([
            'plan' => 'required|in:starter,pro,enterprise'
        ]);

        $newPlan = Plan::where('slug', $request->plan)->first();

        if (!$newPlan) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'अमान्य योजना'
                ], 422);
            }

            return back()->with('error', 'अमान्य योजना');
        }

        // ✅ NEW VALIDATION: Check if user already has this plan
        $currentSubscription = $organization->subscription;
        if ($currentSubscription && $currentSubscription->plan_id == $newPlan->id) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'तपाईं पहिले नै ' . $newPlan->name . ' योजनामा हुनुहुन्छ।'
                ], 422);
            }

            return back()->with('error', 'तपाईं पहिले नै ' . $newPlan->name . ' योजनामा हुनुहुन्छ।');
        }

        // ✅ NEW VALIDATION: Check if user is on trial and trying to upgrade
        if ($currentSubscription && $currentSubscription->status == 'trial') {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'तपाईं निःशुल्क परीक्षण अवधिमा हुनुहुन्छ। परीक्षण समाप्त भएपछि मात्र योजना परिवर्तन गर्न सक्नुहुन्छ।'
                ], 422);
            }

            return back()->with('error', 'तपाईं निःशुल्क परीक्षण अवधिमा हुनुहुन्छ। परीक्षण समाप्त भएपछि मात्र योजना परिवर्तन गर्न सक्नुहुन्छ।');
        }

        DB::beginTransaction();

        try {
            // Update subscription
            $subscription = $organization->subscription;

            if ($subscription) {
                $subscription->update([
                    'plan_id' => $newPlan->id,
                    'status' => 'active',
                    'trial_ends_at' => null,
                    'renews_at' => now()->addMonth()
                ]);
            } else {
                // Create new subscription if it doesn't exist
                Subscription::create([
                    'user_id' => auth()->id(),
                    'organization_id' => $organization->id,
                    'plan_id' => $newPlan->id,
                    'status' => 'active',
                    'trial_ends_at' => null,
                    'ends_at' => now()->addMonth(),
                    'renews_at' => now()->addMonth()
                ]);
            }

            DB::commit();

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'तपाईंको योजना सफलतापूर्वक अपग्रेड गरियो!',
                    'redirect' => route('subscription.show')
                ]);
            }

            return redirect()->route('subscription.show')
                ->with('success', 'तपाईंको योजना सफलतापूर्वक अपग्रेड गरियो!');
        } catch (\Exception $e) {
            DB::rollBack();

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'योजना अपग्रेड गर्दा त्रुटि: ' . $e->getMessage()
                ], 500);
            }

            return back()->with('error', 'योजना अपग्रेड गर्दा त्रुटि: ' . $e->getMessage());
        }
    }

    public function startTrial(Request $request)
    {
        // Get the current organization from the session
        $organizationId = session('current_organization_id');

        if (!$organizationId) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'संस्था भेटिएन। कृपया पहिले संस्था दर्ता गर्नुहोस्'
                ], 422);
            }

            return redirect()->route('register.organization')
                ->with('error', 'संस्था भेटिएन। कृपया पहिले संस्था दर्ता गर्नुहोस्');
        }

        $organization = Organization::find($organizationId);

        if (!$organization) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'संस्था भेटिएन। कृपया पहिले संस्था दर्ता गर्नुहोस्'
                ], 422);
            }

            return redirect()->route('register.organization')
                ->with('error', 'संस्था भेटिएन। कृपया पहिले संस्था दर्ता गर्नुहोस्');
        }

        DB::beginTransaction();

        try {
            // Check if subscription already exists
            if ($organization->subscription) {
                if ($request->ajax() || $request->wantsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'तपाईंसँग पहिले नै सदस्यता छ'
                    ], 422);
                }

                return back()->with('error', 'तपाईंसँग पहिले नै सदस्यता छ');
            }

            // Get starter plan
            $plan = Plan::where('slug', 'starter')->first();

            if (!$plan) {
                if ($request->ajax() || $request->wantsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'स्टार्टर योजना भेटिएन'
                    ], 422);
                }

                return back()->with('error', 'स्टार्टर योजना भेटिएन');
            }

            // Create trial subscription
            Subscription::create([
                'user_id' => auth()->id(),
                'organization_id' => $organization->id,
                'plan_id' => $plan->id,
                'status' => 'trial',
                'trial_ends_at' => now()->addDays(7),
                'ends_at' => now()->addMonth(),
                'renews_at' => now()->addMonth()
            ]);

            DB::commit();

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'निःशुल्क परीक्षण सफलतापूर्वक सुरु गरियो!',
                    'redirect' => route('dashboard')
                ]);
            }

            return redirect()->route('dashboard')
                ->with('success', 'निःशुल्क परीक्षण सफलतापूर्वक सुरु गरियो!');
        } catch (\Exception $e) {
            DB::rollBack();

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'परीक्षण सुरु गर्दा त्रुटि: ' . $e->getMessage()
                ], 500);
            }

            return back()->with('error', 'परीक्षण सुरु गर्दा त्रुटि: ' . $e->getMessage());
        }
    }

    /**
     * Cancel subscription
     */
    public function cancel(Request $request)
    {
        $organizationId = session('current_organization_id');

        if (!$organizationId) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'संस्था भेटिएन।'
                ], 422);
            }
            return redirect()->route('register.organization')
                ->with('error', 'संस्था भेटिएन।');
        }

        $organization = Organization::find($organizationId);
        $subscription = $organization->subscription;

        if (!$subscription) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'कुनै सक्रिय सदस्यता भेटिएन।'
                ], 422);
            }
            return back()->with('error', 'कुनै सक्रिय सदस्यता भेटिएन।');
        }

        DB::beginTransaction();

        try {
            // Update subscription status to cancelled
            $subscription->update([
                'status' => 'cancelled',
                'ends_at' => now()
            ]);

            DB::commit();

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'सदस्यता सफलतापूर्वक रद्द गरियो।',
                    'redirect' => route('subscription.show')
                ]);
            }

            return redirect()->route('subscription.show')
                ->with('success', 'सदस्यता सफलतापूर्वक रद्द गरियो।');
        } catch (\Exception $e) {
            DB::rollBack();

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'सदस्यता रद्द गर्दा त्रुटि: ' . $e->getMessage()
                ], 500);
            }

            return back()->with('error', 'सदस्यता रद्द गर्दा त्रुटि: ' . $e->getMessage());
        }
    }

    /**
     * Purchase extra hostel add-on
     */
    public function purchaseExtraHostel(Request $request)
    {
        $request->validate([
            'subscription_id' => 'required|exists:subscriptions,id',
            'quantity' => 'required|integer|min:1|max:10'
        ]);

        $subscription = Subscription::findOrFail($request->subscription_id);

        // Check if subscription belongs to user's organization
        if ($subscription->organization_id !== session('current_organization_id')) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'अमान्य सदस्यता।'
                ], 422);
            }
            return back()->with('error', 'अमान्य सदस्यता।');
        }

        // Check if plan supports extra hostels (only Enterprise)
        if (!$subscription->plan || $subscription->plan->slug !== 'enterprise') {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'योजनाले अतिरिक्त होस्टल सपोर्ट गर्दैन। केवल एन्टरप्राइज योजनाले मात्र अतिरिक्त होस्टल सपोर्ट गर्छ।'
                ], 422);
            }
            return back()->with('error', 'योजनाले अतिरिक्त होस्टल सपोर्ट गर्दैन। केवल एन्टरप्राइज योजनाले मात्र अतिरिक्त होस्टल सपोर्ट गर्छ।');
        }

        // Calculate additional cost
        $additionalCost = $subscription->plan->getExtraHostelPrice() * $request->quantity;

        DB::beginTransaction();

        try {
            // Add extra hostels to subscription
            $subscription->addExtraHostels($request->quantity);

            DB::commit();

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => "{$request->quantity} अतिरिक्त होस्टल स्लट सफलतापूर्वक थपियो।",
                    'additional_cost' => $additionalCost
                ]);
            }

            return back()->with('success', "{$request->quantity} अतिरिक्त होस्टल स्लट सफलतापूर्वक थपियो। अतिरिक्त शुल्क: रु. " . number_format($additionalCost));
        } catch (\Exception $e) {
            DB::rollBack();

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'अतिरिक्त होस्टल थप्दा त्रुटि: ' . $e->getMessage()
                ], 500);
            }

            return back()->with('error', 'अतिरिक्त होस्टल थप्दा त्रुटि: ' . $e->getMessage());
        }
    }

    /**
     * Show subscription limits and usage
     */
    public function showLimits()
    {
        $organizationId = session('current_organization_id');

        if (!$organizationId) {
            return redirect()->route('register.organization')
                ->with('error', 'कृपया पहिले संस्था दर्ता गर्नुहोस्');
        }

        $subscription = Subscription::where('organization_id', $organizationId)
            ->with('plan')
            ->first();

        if (!$subscription) {
            return redirect()->route('subscription.show')->with('error', 'कुनै सक्रिय सदस्यता फेला परेन।');
        }

        $hostelsCount = Hostel::where('organization_id', $organizationId)->count();
        $remainingSlots = $subscription->getRemainingHostelSlots();

        // Get other usage statistics
        $studentsCount = Student::where('organization_id', $organizationId)->count();
        $roomsCount = Room::whereHas('hostel', function ($query) use ($organizationId) {
            $query->where('organization_id', $organizationId);
        })->count();

        return view('subscription.limits', compact(
            'subscription',
            'hostelsCount',
            'remainingSlots',
            'studentsCount',
            'roomsCount'
        ));
    }

    /**
     * Check if organization can create more hostels
     */
    public function canCreateMoreHostels()
    {
        $organizationId = session('current_organization_id');

        if (!$organizationId) {
            if (request()->ajax() || request()->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'संस्था भेटिएन'
                ], 422);
            }
            return false;
        }

        $subscription = Subscription::where('organization_id', $organizationId)
            ->with('plan')
            ->first();

        if (!$subscription) {
            if (request()->ajax() || request()->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'कुनै सक्रिय सदस्यता फेला परेन'
                ], 422);
            }
            return false;
        }

        $hostelsCount = Hostel::where('organization_id', $organizationId)->count();
        $canCreateMore = $subscription->canAddMoreHostels();

        if (request()->ajax() || request()->wantsJson()) {
            return response()->json([
                'success' => true,
                'can_create_more' => $canCreateMore,
                'current_count' => $hostelsCount,
                'remaining_slots' => $subscription->getRemainingHostelSlots(),
                'max_allowed' => $subscription->plan->getMaxHostelsWithAddons($subscription->extra_hostels ?? 0)
            ]);
        }

        return $canCreateMore;
    }

    /**
     * Get subscription usage statistics
     */
    public function getUsageStats()
    {
        $organizationId = session('current_organization_id');

        if (!$organizationId) {
            return response()->json([
                'success' => false,
                'message' => 'संस्था भेटिएन'
            ], 422);
        }

        $subscription = Subscription::where('organization_id', $organizationId)
            ->with('plan')
            ->first();

        if (!$subscription) {
            return response()->json([
                'success' => false,
                'message' => 'कुनै सक्रिय सदस्यता फेला परेन'
            ], 422);
        }

        $hostelsCount = Hostel::where('organization_id', $organizationId)->count();
        $studentsCount = Student::where('organization_id', $organizationId)->count();
        $roomsCount = Room::whereHas('hostel', function ($query) use ($organizationId) {
            $query->where('organization_id', $organizationId);
        })->count();

        $remainingHostelSlots = $subscription->getRemainingHostelSlots();
        $remainingStudentSlots = $subscription->plan->max_students - $studentsCount;
        $remainingRoomSlots = $subscription->plan->max_rooms - $roomsCount;

        return response()->json([
            'success' => true,
            'data' => [
                'hostels' => [
                    'current' => $hostelsCount,
                    'max_allowed' => $subscription->plan->getMaxHostelsWithAddons($subscription->extra_hostels ?? 0),
                    'remaining' => $remainingHostelSlots,
                    'usage_percentage' => $subscription->plan->getMaxHostelsWithAddons($subscription->extra_hostels ?? 0) > 0
                        ? round(($hostelsCount / $subscription->plan->getMaxHostelsWithAddons($subscription->extra_hostels ?? 0)) * 100)
                        : 0
                ],
                'students' => [
                    'current' => $studentsCount,
                    'max_allowed' => $subscription->plan->max_students,
                    'remaining' => max(0, $remainingStudentSlots),
                    'usage_percentage' => $subscription->plan->max_students > 0
                        ? round(($studentsCount / $subscription->plan->max_students) * 100)
                        : 0
                ],
                'rooms' => [
                    'current' => $roomsCount,
                    'max_allowed' => $subscription->plan->max_rooms,
                    'remaining' => max(0, $remainingRoomSlots),
                    'usage_percentage' => $subscription->plan->max_rooms > 0
                        ? round(($roomsCount / $subscription->plan->max_rooms) * 100)
                        : 0
                ]
            ]
        ]);
    }
}
