<?php

namespace App\Http\Controllers;

use App\Models\Organization;
use App\Models\Plan;
use Illuminate\Http\Request;

class SubscriptionController extends Controller
{
    public function show()
    {
        $organization = request()->attributes->get('organization');
        $currentPlan = $organization->subscription ? $organization->subscription->plan : null;
        $availablePlans = Plan::where('is_active', true)
            ->where('id', '!=', $currentPlan?->id)
            ->orderBy('sort_order')
            ->get();

        return view('subscription.show', compact('organization', 'currentPlan', 'availablePlans'));
    }

    public function upgrade(Request $request)
    {
        $organization = request()->attributes->get('organization');
        $request->validate([
            'plan_id' => 'required|exists:plans,id'
        ]);

        $newPlan = Plan::find($request->plan_id);

        // Update subscription
        $organization->subscription()->update([
            'plan_id' => $newPlan->id,
            'status' => 'active',
            'trial_ends_at' => null,
            'renews_at' => now()->addMonth()
        ]);

        return redirect()->route('subscription.show')
            ->with('success', 'तपाईंको योजना सफलतापूर्वक अपग्रेड गरियो!');
    }
}
