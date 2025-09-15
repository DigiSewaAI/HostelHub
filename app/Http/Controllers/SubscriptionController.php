<?php

namespace App\Http\Controllers;

use App\Models\Organization;
use App\Models\Plan;
use App\Models\Subscription;
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

        return view('subscription.show', compact('organization', 'currentPlan', 'availablePlans'));
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
}
