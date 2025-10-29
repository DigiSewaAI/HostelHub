<?php

namespace App\Http\Controllers;

use App\Models\Organization;
use App\Models\Plan;
use App\Models\Subscription;
use App\Models\OnboardingProgress;
use App\Models\User;
use App\Models\Hostel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log; // ✅ ADD THIS
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RegistrationController extends Controller
{
    /**
     * Show organization registration form
     */
    public function show(Request $request, $plan = null)
    {
        try {
            // Use plan from route parameter or fallback to query string
            $planSlug = $plan ?: $request->query('plan', 'starter');
            $plan = Plan::where('slug', $planSlug)->first();

            if (!$plan) {
                $plan = Plan::where('slug', 'starter')->first();

                if (!$plan) {
                    $plan = Plan::where('is_active', true)->first();

                    if (!$plan) {
                        // Return empty data instead of aborting
                        Log::warning('No active plans found, using fallback');
                        $plan = null;
                    }
                }
            }

            return view('auth.organization.register', compact('plan'));
        } catch (\Exception $e) {
            Log::error('Organization registration form error: ' . $e->getMessage());
            // Emergency fallback - return form without plan data
            return view('auth.organization.register', ['plan' => null]);
        }
    }

    /**
     * Handle registration and organization creation
     */
    public function store(Request $request)
    {
        try {
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
                    'is_ready' => true,
                ]);

                // 2️⃣ Create user WITH ALL REQUIRED FIELDS
                $userData = [
                    'name' => $request->owner_name,
                    'email' => $request->email,
                    'password' => Hash::make($request->password),
                    'organization_id' => $organization->id,
                    'role_id' => 3,
                    'payment_verified' => false,
                ];

                // Check if other fields exist in database and add them
                if (\Schema::hasColumn('users', 'phone')) {
                    $userData['phone'] = null;
                }
                if (\Schema::hasColumn('users', 'address')) {
                    $userData['address'] = null;
                }
                if (\Schema::hasColumn('users', 'student_id')) {
                    $userData['student_id'] = null;
                }
                if (\Schema::hasColumn('users', 'hostel_id')) {
                    $userData['hostel_id'] = null; // Temporary set to null, will update after hostel creation
                }

                $user = User::create($userData);

                // 3️⃣ CRITICAL FIX: Ensure hostel_manager role has required permissions
                $this->setupHostelManagerPermissions();

                // Assign role to user using Spatie Permission
                $hostelManagerRole = Role::findByName('hostel_manager');
                $user->assignRole($hostelManagerRole);

                // 4️⃣ Link user with organization (using relationship method)
                $organization->users()->attach($user->id, [
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

                // 6️⃣ Create default hostel for the organization
                $hostelSlug = Str::slug($request->organization_name . ' होस्टेल');
                $originalHostelSlug = $hostelSlug;
                $j = 1;
                while (Hostel::where('slug', $hostelSlug)->exists()) {
                    $hostelSlug = $originalHostelSlug . '-' . $j;
                    $j++;
                }

                $hostel = Hostel::create([
                    'name' => $request->organization_name . ' होस्टेल',
                    'slug' => $hostelSlug,
                    'address' => 'थप गर्नुपर्ने',
                    'city' => 'काठमाडौं',
                    'contact_person' => $request->owner_name,
                    'contact_phone' => '9800000000',
                    'contact_email' => $request->email,
                    'description' => $request->organization_name . ' को मुख्य होस्टेल',
                    'total_rooms' => 0,
                    'available_rooms' => 0,
                    'status' => 'active',
                    'facilities' => json_encode(['WiFi', 'पानी', 'बिजुली']),
                    'owner_id' => $user->id,
                    'organization_id' => $organization->id,
                ]);

                // 🔥🔥🔥 CRITICAL FIX: Update user's hostel_id after hostel creation
                $user->hostel_id = $hostel->id;
                $user->save();

                // 7️⃣ Create onboarding progress
                OnboardingProgress::create([
                    'organization_id' => $organization->id,
                    'current_step' => 2,
                    'completed' => json_encode(['step1' => true]),
                ]);

                // 8️⃣ Auto login and session setup
                Auth::login($user);
                session(['current_organization_id' => $organization->id]);

                DB::commit();

                // 9️⃣ Redirect to dashboard with PLAN-SPECIFIC success message ✅
                $successMessage = $this->getPlanSpecificMessage($request->plan);

                return redirect()->route('owner.dashboard')
                    ->with('success', $successMessage);
            } catch (\Exception $e) {
                DB::rollBack();
                Log::error('Registration transaction error: ' . $e->getMessage());
                Log::error('Registration error trace: ' . $e->getTraceAsString());

                return back()->withInput()
                    ->withErrors(['error' => 'संस्था दर्ता गर्दा त्रुटि आयो: ' . $e->getMessage()]);
            }
        } catch (\Exception $e) {
            Log::error('Organization registration error: ' . $e->getMessage());

            return back()->withInput()
                ->withErrors(['error' => 'Registration failed. Please try again.']);
        }
    }

    /**
     * CRITICAL FIX: Ensure hostel_manager role has all required permissions
     * This solves the 403 unauthorized error for owner dashboard
     */
    private function setupHostelManagerPermissions()
    {
        try {
            $hostelManagerRole = Role::findByName('hostel_manager');

            // Define all required permissions for hostel_manager to access owner dashboard
            $requiredPermissions = [
                'view-owner-dashboard',
                'view-admin-dashboard', // if needed
                'manage-hostels',
                'manage-rooms',
                'manage-students',
                'manage-bookings',
                'view-payments',
                'manage-meals',
                'view-reports'
            ];

            foreach ($requiredPermissions as $permissionName) {
                // Create permission if it doesn't exist
                $permission = Permission::firstOrCreate(['name' => $permissionName]);

                // Assign permission to role if not already assigned
                if (!$hostelManagerRole->hasPermissionTo($permission)) {
                    $hostelManagerRole->givePermissionTo($permission);
                }
            }

            Log::info('Hostel manager permissions setup completed successfully');
        } catch (\Exception $e) {
            Log::error('Permission setup failed: ' . $e->getMessage());
            // Don't throw exception - continue with registration
        }
    }

    /**
     * Get plan-specific success message
     */
    private function getPlanSpecificMessage($planSlug)
    {
        $messages = [
            'starter' => 'तपाईंको दर्ता सफल भयो! तपाईंको होस्टेल सिर्जना गरियो। (सुरुवाती योजना: १ होस्टेल मात्र)',
            'pro' => 'तपाईंको दर्ता सफल भयो! तपाईंको होस्टेल सिर्जना गरियो। (प्रो योजना: १ होस्टेल मात्र)',
            'enterprise' => 'तपाईंको दर्ता सफल भयो! पहिलो होस्टेल सिर्जना गरियो। (एन्टरप्राइज योजना: बहु-होस्टेल सुविधा)'
        ];

        return $messages[$planSlug] ?? 'तपाईंको दर्ता सफल भयो!';
    }
}
