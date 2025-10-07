<?php

namespace App\Http\Controllers;

use App\Models\Organization;
use App\Models\Plan;
use App\Models\Subscription;
use App\Models\Hostel;
use App\Models\Student;
use App\Models\Room;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class SubscriptionController extends Controller
{
    public function show()
    {
        // Get the current organization from session (consistent with other controllers)
        $organizationId = session('selected_organization_id');

        if (!$organizationId) {
            // Fallback: get the first organization associated with the user
            $user = Auth::user();
            $organization = $user->organizations()->first();

            if ($organization) {
                $organizationId = $organization->id;
                session(['selected_organization_id' => $organizationId]);
            } else {
                return redirect()->route('organizations.create')
                    ->with('error', 'कृपया पहिले संस्था दर्ता गर्नुहोस्');
            }
        }

        $organization = Organization::with(['currentSubscription.plan'])->find($organizationId);

        if (!$organization) {
            return redirect()->route('organizations.create')
                ->with('error', 'संस्था भेटिएन। कृपया पहिले संस्था दर्ता गर्नुहोस्');
        }

        $currentSubscription = $organization->currentSubscription();
        $currentPlan = $currentSubscription ? $currentSubscription->plan : null;

        $availablePlans = Plan::where('is_active', true)
            ->where('id', '!=', $currentPlan?->id)
            ->orderBy('sort_order')
            ->get();

        // Calculate usage statistics
        $hostelsCount = Hostel::where('organization_id', $organizationId)->count();
        $studentsCount = Student::where('organization_id', $organizationId)->count();
        $roomsCount = Room::where('organization_id', $organizationId)->count();

        $remainingHostelSlots = $currentSubscription ? $currentSubscription->getRemainingHostelSlots() : 0;
        $remainingStudentSlots = $currentSubscription ? max(0, $currentSubscription->plan->max_students - $studentsCount) : 0;
        $remainingRoomSlots = $currentSubscription ? max(0, $currentSubscription->plan->max_rooms - $roomsCount) : 0;

        return view('subscription.show', compact(
            'organization',
            'currentSubscription',
            'currentPlan',
            'availablePlans',
            'hostelsCount',
            'studentsCount',
            'roomsCount',
            'remainingHostelSlots',
            'remainingStudentSlots',
            'remainingRoomSlots'
        ));
    }

    public function upgrade(Request $request)
    {
        // Get the current organization from session
        $organizationId = session('selected_organization_id');

        if (!$organizationId) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'संस्था भेटिएन। कृपया पहिले संस्था दर्ता गर्नुहोस्'
                ], 422);
            }

            return redirect()->route('organizations.create')
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

            return redirect()->route('organizations.create')
                ->with('error', 'संस्था भेटिएन। कृपया पहिले संस्था दर्ता गर्नुहोस्');
        }

        $request->validate([
            'plan_id' => 'required|exists:plans,id'
        ]);

        $newPlan = Plan::find($request->plan_id);

        if (!$newPlan) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'अमान्य योजना'
                ], 422);
            }

            return back()->with('error', 'अमान्य योजना');
        }

        // Check if user already has this plan
        $currentSubscription = $organization->currentSubscription();
        if ($currentSubscription && $currentSubscription->plan_id == $newPlan->id) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'तपाईं पहिले नै ' . $newPlan->name . ' योजनामा हुनुहुन्छ।'
                ], 422);
            }

            return back()->with('error', 'तपाईं पहिले नै ' . $newPlan->name . ' योजनामा हुनुहुन्छ।');
        }

        // Check if user is on trial and trying to upgrade
        if ($currentSubscription && $currentSubscription->status == 'trial') {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'तपाईं निःशुल्क परीक्षण अवधिमा हुनुहुन्छ। परीक्षण समाप्त भएपछि मात्र योजना परिवर्तन गर्न सक्नुहुन्छ।'
                ], 422);
            }

            return back()->with('error', 'तपाईं निःशुल्क परीक्षण अवधिमा हुनुहुन्छ। परीक्षण समाप्त भएपछि मात्र योजना परिवर्तन गर्न सक्नुहुन्छ।');
        }

        // Create payment record for plan upgrade
        $payment = Payment::create([
            'organization_id' => $organization->id,
            'user_id' => Auth::id(),
            'amount' => $newPlan->price,
            'payment_method' => 'khalti',
            'status' => 'pending',
            'purpose' => 'subscription',
            'metadata' => [
                'plan_id' => $newPlan->id,
                'plan_name' => $newPlan->name,
                'upgrade_from' => $currentSubscription ? $currentSubscription->plan_id : null
            ]
        ]);

        // Redirect to payment gateway
        return redirect()->route('payment.initiate', [
            'amount' => $newPlan->price,
            'purchase_type' => 'subscription',
            'payment_id' => $payment->id
        ]);
    }

    public function startTrial(Request $request)
    {
        // Get the current organization from session
        $organizationId = session('selected_organization_id');

        if (!$organizationId) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'संस्था भेटिएन। कृपया पहिले संस्था दर्ता गर्नुहोस्'
                ], 422);
            }

            return redirect()->route('organizations.create')
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

            return redirect()->route('organizations.create')
                ->with('error', 'संस्था भेटिएन। कृपया पहिले संस्था दर्ता गर्नुहोस्');
        }

        DB::beginTransaction();

        try {
            // Check if subscription already exists
            if ($organization->currentSubscription()) {
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
                'organization_id' => $organization->id,
                'plan_id' => $plan->id,
                'status' => 'trial',
                'trial_ends_at' => now()->addDays(7),
                'ends_at' => now()->addDays(7),
                'renews_at' => now()->addDays(7)
            ]);

            DB::commit();

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'निःशुल्क परीक्षण सफलतापूर्वक सुरु गरियो!',
                    'redirect' => route('owner.dashboard')
                ]);
            }

            return redirect()->route('owner.dashboard')
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
        $organizationId = session('selected_organization_id');

        if (!$organizationId) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'संस्था भेटिएन।'
                ], 422);
            }
            return redirect()->route('organizations.create')
                ->with('error', 'संस्था भेटिएन।');
        }

        $organization = Organization::find($organizationId);
        $subscription = $organization->currentSubscription();

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
            'quantity' => 'required|integer|min:1|max:10'
        ]);

        $organizationId = session('selected_organization_id');

        if (!$organizationId) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'संस्था भेटिएन।'
                ], 422);
            }
            return back()->with('error', 'संस्था भेटिएन।');
        }

        $organization = Organization::find($organizationId);
        $subscription = $organization->currentSubscription();

        if (!$subscription) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'कुनै सक्रिय सदस्यता भेटिएन।'
                ], 422);
            }
            return back()->with('error', 'कुनै सक्रिय सदस्यता भेटिएन।');
        }

        // Check if plan supports extra hostels
        if (!$subscription->plan->supports_extra_hostels) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'योजनाले अतिरिक्त होस्टल सपोर्ट गर्दैन।'
                ], 422);
            }
            return back()->with('error', 'योजनाले अतिरिक्त होस्टल सपोर्ट गर्दैन।');
        }

        // Calculate additional cost
        $additionalCost = $subscription->plan->extra_hostel_price * $request->quantity;

        // Create payment record for extra hostels
        $payment = Payment::create([
            'organization_id' => $organization->id,
            'user_id' => Auth::id(),
            'amount' => $additionalCost,
            'payment_method' => 'khalti',
            'status' => 'pending',
            'purpose' => 'extra_hostel',
            'metadata' => [
                'quantity' => $request->quantity,
                'extra_hostel_price' => $subscription->plan->extra_hostel_price,
                'subscription_id' => $subscription->id
            ]
        ]);

        // Redirect to payment gateway
        return redirect()->route('payment.initiate', [
            'amount' => $additionalCost,
            'purchase_type' => 'extra_hostel',
            'payment_id' => $payment->id
        ]);
    }

    /**
     * Show subscription limits and usage
     */
    public function showLimits()
    {
        $organizationId = session('selected_organization_id');

        if (!$organizationId) {
            return redirect()->route('organizations.create')
                ->with('error', 'कृपया पहिले संस्था दर्ता गर्नुहोस्');
        }

        $organization = Organization::find($organizationId);
        $subscription = $organization->currentSubscription();

        if (!$subscription) {
            return redirect()->route('subscription.show')->with('error', 'कुनै सक्रिय सदस्यता फेला परेन।');
        }

        $hostelsCount = Hostel::where('organization_id', $organizationId)->count();
        $studentsCount = Student::where('organization_id', $organizationId)->count();
        $roomsCount = Room::where('organization_id', $organizationId)->count();

        $remainingHostelSlots = $subscription->getRemainingHostelSlots();
        $remainingStudentSlots = max(0, $subscription->plan->max_students - $studentsCount);
        $remainingRoomSlots = max(0, $subscription->plan->max_rooms - $roomsCount);

        return view('subscription.limits', compact(
            'subscription',
            'hostelsCount',
            'studentsCount',
            'roomsCount',
            'remainingHostelSlots',
            'remainingStudentSlots',
            'remainingRoomSlots'
        ));
    }

    /**
     * Check if organization can create more hostels
     */
    public function canCreateMoreHostels()
    {
        $organizationId = session('selected_organization_id');

        if (!$organizationId) {
            if (request()->ajax() || request()->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'संस्था भेटिएन'
                ], 422);
            }
            return false;
        }

        $organization = Organization::find($organizationId);
        $subscription = $organization->currentSubscription();

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
                'max_allowed' => $subscription->plan->max_hostels + ($subscription->extra_hostels ?? 0)
            ]);
        }

        return $canCreateMore;
    }

    /**
     * Get subscription usage statistics
     */
    public function getUsageStats()
    {
        $organizationId = session('selected_organization_id');

        if (!$organizationId) {
            return response()->json([
                'success' => false,
                'message' => 'संस्था भेटिएन'
            ], 422);
        }

        $organization = Organization::find($organizationId);
        $subscription = $organization->currentSubscription();

        if (!$subscription) {
            return response()->json([
                'success' => false,
                'message' => 'कुनै सक्रिय सदस्यता फेला परेन'
            ], 422);
        }

        $hostelsCount = Hostel::where('organization_id', $organizationId)->count();
        $studentsCount = Student::where('organization_id', $organizationId)->count();
        $roomsCount = Room::where('organization_id', $organizationId)->count();

        $maxHostels = $subscription->plan->max_hostels + ($subscription->extra_hostels ?? 0);
        $remainingHostelSlots = max(0, $maxHostels - $hostelsCount);
        $remainingStudentSlots = max(0, $subscription->plan->max_students - $studentsCount);
        $remainingRoomSlots = max(0, $subscription->plan->max_rooms - $roomsCount);

        return response()->json([
            'success' => true,
            'data' => [
                'hostels' => [
                    'current' => $hostelsCount,
                    'max_allowed' => $maxHostels,
                    'remaining' => $remainingHostelSlots,
                    'usage_percentage' => $maxHostels > 0
                        ? round(($hostelsCount / $maxHostels) * 100)
                        : 0
                ],
                'students' => [
                    'current' => $studentsCount,
                    'max_allowed' => $subscription->plan->max_students,
                    'remaining' => $remainingStudentSlots,
                    'usage_percentage' => $subscription->plan->max_students > 0
                        ? round(($studentsCount / $subscription->plan->max_students) * 100)
                        : 0
                ],
                'rooms' => [
                    'current' => $roomsCount,
                    'max_allowed' => $subscription->plan->max_rooms,
                    'remaining' => $remainingRoomSlots,
                    'usage_percentage' => $subscription->plan->max_rooms > 0
                        ? round(($roomsCount / $subscription->plan->max_rooms) * 100)
                        : 0
                ]
            ]
        ]);
    }

    /**
     * Process successful payment and update subscription
     */
    public function processPaymentSuccess($paymentId)
    {
        DB::beginTransaction();

        try {
            $payment = Payment::findOrFail($paymentId);

            if ($payment->status !== 'completed') {
                throw new \Exception('भुक्तानी अझै पूरा भएको छैन');
            }

            $organization = $payment->organization;
            $metadata = $payment->metadata;

            switch ($payment->purpose) {
                case 'subscription':
                    $plan = Plan::find($metadata['plan_id']);
                    $currentSubscription = $organization->currentSubscription();

                    if ($currentSubscription) {
                        // Upgrade existing subscription
                        $currentSubscription->update([
                            'plan_id' => $plan->id,
                            'status' => 'active',
                            'ends_at' => now()->addMonth(),
                            'renews_at' => now()->addMonth()
                        ]);
                    } else {
                        // Create new subscription
                        Subscription::create([
                            'organization_id' => $organization->id,
                            'plan_id' => $plan->id,
                            'status' => 'active',
                            'ends_at' => now()->addMonth(),
                            'renews_at' => now()->addMonth()
                        ]);
                    }
                    break;

                case 'extra_hostel':
                    $subscription = $organization->currentSubscription();
                    $quantity = $metadata['quantity'];

                    // Add extra hostels to subscription
                    $subscription->update([
                        'extra_hostels' => ($subscription->extra_hostels ?? 0) + $quantity
                    ]);
                    break;
            }

            DB::commit();

            return redirect()->route('subscription.show')
                ->with('success', 'भुक्तानी सफल भयो! तपाईंको सदस्यता अपडेट गरियो।');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('subscription.show')
                ->with('error', 'भुक्तानी प्रक्रिया गर्दा त्रुटि: ' . $e->getMessage());
        }
    }
}
