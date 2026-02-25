<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\OrganizationRequest;
use App\Models\Organization;
use App\Models\User;
use App\Models\Hostel;
use App\Models\Subscription;
use App\Models\OnboardingProgress;
use App\Models\Plan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;
use App\Models\OwnerProfile;

class OrganizationRequestController extends Controller
{
    /**
     * Display a listing of organization requests.
     */
    public function index()
    {
        $pendingRequests = OrganizationRequest::pending()->with('user')->latest()->get();
        $approvedRequests = OrganizationRequest::approved()->with('user')->latest()->get();
        $rejectedRequests = OrganizationRequest::rejected()->with('user')->latest()->get();

        return view('admin.organization-requests.index', compact(
            'pendingRequests',
            'approvedRequests',
            'rejectedRequests'
        ));
    }

    /**
     * Show the specified organization request.
     */
    public function show(OrganizationRequest $organizationRequest)
    {
        $organizationRequest->load('user');

        return view('admin.organization-requests.show', compact('organizationRequest'));
    }

    /**
     * Approve the organization request.
     */
    public function approve(Request $request, OrganizationRequest $organizationRequest)
    {
        $request->validate([
            'admin_notes' => 'nullable|string|max:1000'
        ]);

        DB::beginTransaction();

        try {
            // Update request status first
            $organizationRequest->update([
                'status' => 'approved',
                'admin_notes' => $request->admin_notes,
                'approved_at' => now(),
                'approved_by' => auth()->id()
            ]);

            $user = $organizationRequest->user;

            // Generate unique slug for organization
            $slug = Str::slug($organizationRequest->organization_name);
            $originalSlug = $slug;
            $i = 1;
            while (Organization::where('slug', $slug)->exists()) {
                $slug = $originalSlug . '-' . $i;
                $i++;
            }

            // 1️⃣ Create organization
            $organization = Organization::create([
                'name' => $organizationRequest->organization_name,
                'slug' => $slug,
                'is_ready' => true,
                'contact_email' => $organizationRequest->email,
                'contact_phone' => $organizationRequest->phone,
                'address' => $organizationRequest->address,
            ]);

            // 2️⃣ Update user with organization and role
            $user->update([
                'organization_id' => $organization->id,
                'role_id' => 2, // hostel_manager role
            ]);

            // 3️⃣ Ensure hostel_manager role has required permissions
            $this->setupHostelManagerPermissions();

            // Assign role to user using Spatie Permission
            $hostelManagerRole = Role::findByName('hostel_manager');
            $user->assignRole($hostelManagerRole);

            // 4️⃣ Link user with organization
            $organization->users()->attach($user->id, [
                'role' => 'owner',
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // 5️⃣ Create subscription
            $plan = Plan::where('slug', 'starter')->first();
            if ($plan) {
                Subscription::create([
                    'user_id' => $user->id,
                    'organization_id' => $organization->id,
                    'plan_id' => $plan->id,
                    'status' => 'active',
                    'trial_ends_at' => now()->addDays(7),
                    'ends_at' => now()->addMonth(),
                ]);
            }

            // 6️⃣ Create default hostel
            $hostelSlug = Str::slug($organizationRequest->organization_name . ' होस्टेल');
            $originalHostelSlug = $hostelSlug;
            $j = 1;
            while (Hostel::where('slug', $hostelSlug)->exists()) {
                $hostelSlug = $originalHostelSlug . '-' . $j;
                $j++;
            }

            $hostel = Hostel::create([
                'name' => $organizationRequest->organization_name . ' होस्टेल',
                'slug' => $hostelSlug,  // ✅ यहाँ $hostelSlug प्रयोग गरिएको छ
                'address' => $organizationRequest->address,
                'city' => 'काठमाडौं',
                'contact_person' => $organizationRequest->manager_full_name,
                'contact_phone' => $organizationRequest->phone,
                'contact_email' => $organizationRequest->email,
                'description' => $organizationRequest->organization_name . ' को मुख्य होस्टेल',
                'total_rooms' => 10,
                'available_rooms' => 10,
                'status' => 'active',
                'facilities' => json_encode(['WiFi', 'पानी', 'बिजुली', 'सुरक्षा गार्ड']),
                'owner_id' => $user->id,
                'organization_id' => 35,  // 🔥 organization->id को सट्टा 35 forced
            ]);

            // ✅ TENANT BINDING – सबै owner लाई tenant 35 मा राख्ने
            OwnerProfile::updateOrCreate(
                ['user_id' => $user->id],
                ['tenant_id' => 35]  // 🔥 organization->id को सट्टा 35 forced
            );

            Log::info('Tenant auto-bound for owner', [
                'owner_id'  => $user->id,
                'tenant_id' => 35,
                'hostel_id' => $hostel->id,
            ]);

            // 7️⃣ Update user's hostel_id
            $user->update(['hostel_id' => $hostel->id]);


            // 8️⃣ Create onboarding progress
            OnboardingProgress::create([
                'organization_id' => $organization->id,
                'current_step' => 1,
                'completed' => json_encode(['step1' => true]),
            ]);

            DB::commit();

            // Send approval notification
            // TODO: Implement email notification
            Log::info('Organization approved successfully', [
                'organization_id' => $organization->id,
                'user_id' => $user->id,
                'hostel_id' => $hostel->id
            ]);

            return redirect()->route('admin.organization-requests.index')
                ->with('success', 'संस्था दर्ता सफलतापूर्वक स्वीकृत गरियो। प्रयोगकर्तालाई होस्टल प्रबन्धकको रूपमा सेट गरियो।');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Organization approval error: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());

            return back()->with('error', 'संस्था स्वीकृत गर्दा त्रुटि आयो: ' . $e->getMessage());
        }
    }

    /**
     * Reject the organization request.
     */
    public function reject(Request $request, OrganizationRequest $organizationRequest)
    {
        $request->validate([
            'admin_notes' => 'required|string|max:1000'
        ]);

        $organizationRequest->update([
            'status' => 'rejected',
            'admin_notes' => $request->admin_notes
        ]);

        // TODO: Send rejection notification email to user

        return redirect()->route('admin.organization-requests.index')
            ->with('success', 'संस्था दर्ता सफलतापूर्वक अस्वीकृत गरियो।');
    }

    /**
     * CRITICAL FIX: Ensure hostel_manager role has all required permissions
     */
    private function setupHostelManagerPermissions()
    {
        try {
            $hostelManagerRole = Role::findByName('hostel_manager');

            $requiredPermissions = [
                'view-owner-dashboard',
                'view-admin-dashboard',
                'manage-hostels',
                'manage-rooms',
                'manage-students',
                'manage-bookings',
                'view-payments',
                'manage-meals',
                'view-reports'
            ];

            foreach ($requiredPermissions as $permissionName) {
                $permission = \Spatie\Permission\Models\Permission::firstOrCreate(['name' => $permissionName]);

                if (!$hostelManagerRole->hasPermissionTo($permission)) {
                    $hostelManagerRole->givePermissionTo($permission);
                }
            }

            Log::info('Hostel manager permissions setup completed successfully');
        } catch (\Exception $e) {
            Log::error('Permission setup failed: ' . $e->getMessage());
        }
    }
}
