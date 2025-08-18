<?php

namespace App\Http\Controllers;

use App\Models\Organization;
use App\Models\OrganizationUser;
use App\Models\Plan;
use App\Models\Subscription;
use App\Models\OnboardingProgress;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class RegistrationController extends Controller
{
    public function show(Request $request)
    {
        $planSlug = $request->query('plan', 'starter');
        $plan = Plan::where('slug', $planSlug)->first();

        if (!$plan) {
            $plan = Plan::where('name', 'Starter')->first();
        }

        return view('auth.organization.register', compact('plan'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'organization_name' => 'required|string|max:255',
            'owner_name'       => 'required|string|max:255',
            'email'            => 'required|string|email|max:255|unique:users',
            'password'         => 'required|string|min:8|confirmed',
            'plan_slug'        => 'required|string|exists:plans,slug',
        ]);

        // Check for slug uniqueness to avoid duplicate errors
        $slug = Str::slug($request->organization_name);
        $originalSlug = $slug;
        $i = 1;
        while (Organization::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $i;
            $i++;
        }

        $organization = Organization::create([
            'name'     => $request->organization_name,
            'slug'     => $slug,
            'is_ready' => false,
        ]);

        $user = User::create([
            'name'     => $request->owner_name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role_id'  => 2, // hostel manager role
        ]);

        OrganizationUser::create([
            'org_id' => $organization->id,
            'user_id'         => $user->id,
            'role'            => 'owner',
        ]);

        $plan = Plan::where('slug', $request->plan_slug)->firstOrFail();

        Subscription::create([
            'org_id' => $organization->id,
            'plan_id'         => $plan->id,
            'status'          => 'trial',
            'trial_ends_at'   => now()->addDays(7),
            'renews_at'       => now()->addDays(7),
        ]);

        OnboardingProgress::create([
            'org_id' => $organization->id,
            'current_step'    => 1,
        ]);

        Auth::login($user);
        $request->session()->regenerate();
        session()->forget(['_old_input', 'errors']);
        session(['current_org_id' => $organization->id]);

        return redirect()->route('onboarding.index');
    }
}
