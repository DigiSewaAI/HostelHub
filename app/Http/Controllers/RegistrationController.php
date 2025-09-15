<?php

namespace App\Http\Controllers;

use App\Models\Organization;
use App\Models\Plan;
use App\Models\Subscription;
use App\Models\OnboardingProgress;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;

class RegistrationController extends Controller
{
    /**
     * Show organization registration form
     */
    public function show(Request $request, $plan = null)
    {
        // Use plan from route parameter or fallback to query string
        $planSlug = $plan ?: $request->query('plan', 'starter');
        $plan = Plan::where('slug', $planSlug)->first();

        if (!$plan) {
            $plan = Plan::where('slug', 'starter')->first();

            if (!$plan) {
                $plan = Plan::where('is_active', true)->first();

                if (!$plan) {
                    abort(404, 'कुनै पनि सक्रिय योजना उपलब्ध छैन');
                }
            }
        }

        return view('auth.organization.register', compact('plan'));
    }

    /**
     * Handle registration and organization creation
     */
    public function store(Request $request)
    {
        $request->validate([
            'plan' => 'required|in:starter,pro,enterprise',
            'organization_name' => 'required|string|max:255',
            'owner_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        DB::beginTransaction();

        try {
            // Generate unique slug for organization
            $slug = Str::slug($request->organization_name);
            $originalSlug = $slug;
            $i = 1;
            while (Organization::where('slug', $slug)->exists()) {
                $slug = $originalSlug . '-' . $i;
                $i++;
            }

            // 1️⃣ Create organization
            $organization = Organization::create([
                'name' => $request->organization_name,
                'slug' => $slug,
                'is_ready' => false,
            ]);

            // 2️⃣ Create user
            $user = User::create([
                'name' => $request->owner_name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);

            // 3️⃣ Assign role to user
            $hostelManagerRole = Role::findByName('hostel_manager');
            $user->assignRole($hostelManagerRole);

            // 4️⃣ Link user with organization
            DB::table('organization_user')->insert([
                'organization_id' => $organization->id,
                'user_id' => $user->id,
                'role' => 'owner',
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // 5️⃣ Create subscription
            $plan = Plan::where('slug', $request->plan)->firstOrFail();
            Subscription::create([
                'user_id' => $user->id,
                'organization_id' => $organization->id,
                'plan_id' => $plan->id,
                'status' => 'trial',
                'trial_ends_at' => now()->addDays(7),
                'ends_at' => now()->addMonth(),
            ]);

            // 6️⃣ Create onboarding progress
            OnboardingProgress::create([
                'organization_id' => $organization->id,
                'current_step' => 1,
                'completed' => [],
            ]);

            // 7️⃣ Auto login and session setup
            Auth::login($user);
            session(['current_organization_id' => $organization->id]);

            DB::commit();

            // 8️⃣ Redirect to onboarding page
            return redirect()->route('onboarding.index');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()
                ->withErrors(['error' => 'संस्था दर्ता गर्दा त्रुटि आयो: ' . $e->getMessage()]);
        }
    }
}
