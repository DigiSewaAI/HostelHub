<?php

namespace App\Http\Controllers;

use App\Models\Organization;
use App\Models\OrganizationUser;
use App\Models\Plan;
use App\Models\Subscription;
use App\Models\OnboardingProgress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class RegistrationController extends Controller
{
    public function show(Request $request)
    {
        $planId = $request->query('plan');
        $plan = $planId ? Plan::find($planId) : Plan::where('name', 'Starter')->first();

        return view('auth.organization.register', compact('plan'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'organization_name' => 'required|string|max:255',
            'owner_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'plan_id' => 'required|exists:plans,id'
        ]);

        // Create organization
        $organization = Organization::create([
            'name' => $request->organization_name,
            'slug' => Str::slug($request->organization_name),
            'is_ready' => false
        ]);

        // Create owner user
        $user = \App\Models\User::create([
            'name' => $request->owner_name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // Attach user to organization
        OrganizationUser::create([
            'org_id' => $organization->id,
            'user_id' => $user->id,
            'role' => 'owner'
        ]);

        // Get selected plan
        $plan = Plan::find($request->plan_id);

        // Create subscription
        $subscription = Subscription::create([
            'org_id' => $organization->id,
            'plan_id' => $plan->id,
            'status' => 'trial',
            'trial_ends_at' => now()->addDays(7),
            'renews_at' => now()->addDays(7)
        ]);

        // Create onboarding progress
        OnboardingProgress::create([
            'org_id' => $organization->id,
            'current_step' => 1
        ]);

        // Log in the user
        Auth::login($user);

        // Set current organization in session
        session(['current_org_id' => $organization->id]);

        return redirect()->route('onboarding.index');
    }
}
