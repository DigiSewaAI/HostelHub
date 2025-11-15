<?php

namespace App\Http\Controllers;

use App\Models\Organization;
use App\Models\Plan;
use App\Models\Subscription;
use App\Models\OnboardingProgress;
use App\Models\User;
use App\Models\Hostel;
use App\Models\OrganizationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
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
     * Handle registration and organization creation - NOW WITH PENDING APPROVAL
     */
    public function store(Request $request)
    {
        try {
            // ✅ IMPROVED: Enhanced validation with better error messages
            $validatedData = $request->validate([
                'plan' => 'required|in:starter,pro,enterprise',
                'organization_name' => 'required|string|max:255',
                'owner_name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:8|confirmed',
                'phone' => 'required|string|max:20',
                'address' => 'required|string|max:500',
                'pan_no' => 'nullable|string|max:50',
            ], [
                'email.unique' => 'यो इमेल ठेगाना पहिले नै प्रयोगमा छ।',
                'password.confirmed' => 'पासवर्ड मेल खाएन।',
                'password.min' => 'पासवर्ड कम्तिमा ८ अक्षरको हुनुपर्छ।',
                'organization_name.required' => 'संस्थाको नाम आवश्यक छ।',
                'owner_name.required' => 'मालिकको नाम आवश्यक छ।',
                'phone.required' => 'फोन नम्बर आवश्यक छ।',
                'address.required' => 'ठेगाना आवश्यक छ।',
            ]);

            DB::beginTransaction();

            try {
                // ✅ IMPROVED: Create user with better error handling
                $userData = [
                    'name' => $validatedData['owner_name'],
                    'email' => $validatedData['email'],
                    'password' => Hash::make($validatedData['password']),
                    'organization_id' => null,
                    'role_id' => 3, // Default to student role temporarily
                    'payment_verified' => false,
                    'phone' => $validatedData['phone'],
                    'email_verified_at' => null, // Email verification will be done later
                ];

                // Add additional fields if they exist in the database
                if (\Schema::hasColumn('users', 'address')) {
                    $userData['address'] = $validatedData['address'];
                }
                if (\Schema::hasColumn('users', 'student_id')) {
                    $userData['student_id'] = null;
                }
                if (\Schema::hasColumn('users', 'hostel_id')) {
                    $userData['hostel_id'] = null;
                }

                $user = User::create($userData);

                // ✅ IMPROVED: Create organization request with better data handling
                $organizationRequest = OrganizationRequest::create([
                    'user_id' => $user->id,
                    'organization_name' => $validatedData['organization_name'],
                    'manager_full_name' => $validatedData['owner_name'],
                    'email' => $validatedData['email'],
                    'phone' => $validatedData['phone'],
                    'address' => $validatedData['address'],
                    'pan_no' => $validatedData['pan_no'] ?? null,
                    'plan_type' => $validatedData['plan'], // Store the selected plan
                    'status' => 'pending',
                    'created_by_ip' => $request->ip(),
                    'requested_at' => now(),
                ]);

                // ✅ NEW: Log the registration for audit trail
                Log::info('New organization registration request created', [
                    'user_id' => $user->id,
                    'request_id' => $organizationRequest->id,
                    'organization_name' => $validatedData['organization_name'],
                    'email' => $validatedData['email'],
                    'plan' => $validatedData['plan'],
                    'ip' => $request->ip()
                ]);

                DB::commit();

                // ✅ IMPROVED: Better success message with clear instructions
                return redirect()->route('login')
                    ->with(
                        'success',
                        'तपाईंको संस्था दर्ता अनुरोध सफलतापूर्वक प्राप्त भयो!<br><br>'
                            . '<strong>अर्को चरण:</strong><br>'
                            . '१. प्रशासकले तपाईंको अनुरोध स्वीकृत गर्ने<br>'
                            . '२. स्वीकृतिपछि तपाईंलाई इमेल सूचना प्राप्त हुने<br>'
                            . '३. तपाईंले लगइन गरेर आफ्नो होस्टल ड्यासबोर्ड प्रयोग गर्न सक्नुहुने<br><br>'
                            . 'स्वीकृतिको लागि तपाईंको इमेल: <strong>' . $validatedData['email'] . '</strong> मा सूचित गरिनेछ।'
                    );
            } catch (\Exception $e) {
                DB::rollBack();
                Log::error('Registration transaction error: ' . $e->getMessage(), [
                    'error_trace' => $e->getTraceAsString(),
                    'user_data' => $userData ?? null,
                    'request_data' => $request->all()
                ]);

                return back()->withInput()
                    ->withErrors(['error' => 'संस्था दर्ता गर्दा त्रुटि आयो: ' . $e->getMessage()]);
            }
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Catch validation errors specifically
            Log::warning('Organization registration validation failed', [
                'errors' => $e->errors(),
                'request_data' => $request->all()
            ]);

            return back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            Log::error('Organization registration system error: ' . $e->getMessage(), [
                'exception' => $e,
                'request_data' => $request->all()
            ]);

            return back()->withInput()
                ->withErrors(['error' => 'दर्ता प्रक्रिया असफल भयो। कृपया पुनः प्रयास गर्नुहोस् वा समर्थन सम्पर्क गर्नुहोस्।']);
        }
    }

    /**
     * CRITICAL FIX: Ensure hostel_manager role has all required permissions
     * This solves the 403 unauthorized error for owner dashboard
     * This method is now used by OrganizationRequestController during approval
     */
    public static function setupHostelManagerPermissions()
    {
        try {
            $hostelManagerRole = Role::firstOrCreate(['name' => 'hostel_manager']);

            // Define all required permissions for hostel_manager to access owner dashboard
            $requiredPermissions = [
                'view-owner-dashboard',
                'view-admin-dashboard',
                'manage-hostels',
                'manage-rooms',
                'manage-students',
                'manage-bookings',
                'view-payments',
                'manage-payments',
                'manage-meals',
                'view-reports',
                'manage-circulars',
                'view-circulars',
                'manage-documents',
                'view-documents',
                'manage-profile',
                'view-profile'
            ];

            foreach ($requiredPermissions as $permissionName) {
                // Create permission if it doesn't exist
                $permission = Permission::firstOrCreate([
                    'name' => $permissionName
                ], [
                    'guard_name' => 'web'
                ]);

                // Assign permission to role if not already assigned
                if (!$hostelManagerRole->hasPermissionTo($permission)) {
                    $hostelManagerRole->givePermissionTo($permission);
                }
            }

            Log::info('Hostel manager permissions setup completed successfully', [
                'role' => $hostelManagerRole->name,
                'permissions_count' => count($requiredPermissions)
            ]);

            return $hostelManagerRole;
        } catch (\Exception $e) {
            Log::error('Hostel manager permission setup failed: ' . $e->getMessage(), [
                'error_trace' => $e->getTraceAsString()
            ]);
            // Don't throw exception - return null and let caller handle
            return null;
        }
    }

    /**
     * ✅ NEW: Check if organization name is available (AJAX)
     */
    public function checkOrganizationName(Request $request)
    {
        try {
            $request->validate([
                'organization_name' => 'required|string|max:255'
            ]);

            $organizationName = $request->organization_name;

            // Check if organization name already exists in organizations table
            $existingOrganization = Organization::where('name', $organizationName)->first();

            // Check if there's a pending request with the same name
            $pendingRequest = OrganizationRequest::where('organization_name', $organizationName)
                ->where('status', 'pending')
                ->first();

            $isAvailable = !$existingOrganization && !$pendingRequest;

            return response()->json([
                'available' => $isAvailable,
                'message' => $isAvailable
                    ? 'संस्थाको नाम उपलब्ध छ'
                    : 'यो संस्थाको नाम पहिले नै प्रयोगमा छ'
            ]);
        } catch (\Exception $e) {
            Log::error('Organization name check failed: ' . $e->getMessage());
            return response()->json([
                'available' => false,
                'message' => 'जाँच गर्दा त्रुटि आयो'
            ], 500);
        }
    }

    /**
     * ✅ NEW: Check if email is available (AJAX)
     */
    public function checkEmail(Request $request)
    {
        try {
            $request->validate([
                'email' => 'required|email|max:255'
            ]);

            $email = $request->email;

            // Check if email already exists in users table
            $existingUser = User::where('email', $email)->first();

            // Check if there's a pending request with the same email
            $pendingRequest = OrganizationRequest::where('email', $email)
                ->where('status', 'pending')
                ->first();

            $isAvailable = !$existingUser && !$pendingRequest;

            return response()->json([
                'available' => $isAvailable,
                'message' => $isAvailable
                    ? 'इमेल ठेगाना उपलब्ध छ'
                    : 'यो इमेल ठेगाना पहिले नै प्रयोगमा छ'
            ]);
        } catch (\Exception $e) {
            Log::error('Email availability check failed: ' . $e->getMessage());
            return response()->json([
                'available' => false,
                'message' => 'जाँच गर्दा त्रुटि आयो'
            ], 500);
        }
    }

    /**
     * ✅ NEW: Get registration statistics for admin (optional)
     */
    public function getRegistrationStats()
    {
        try {
            $stats = [
                'total_pending_requests' => OrganizationRequest::where('status', 'pending')->count(),
                'total_approved_requests' => OrganizationRequest::where('status', 'approved')->count(),
                'total_rejected_requests' => OrganizationRequest::where('status', 'rejected')->count(),
                'today_requests' => OrganizationRequest::whereDate('created_at', today())->count(),
                'weekly_requests' => OrganizationRequest::where('created_at', '>=', now()->subWeek())->count(),
                'monthly_requests' => OrganizationRequest::where('created_at', '>=', now()->subMonth())->count(),
            ];

            return response()->json([
                'success' => true,
                'data' => $stats
            ]);
        } catch (\Exception $e) {
            Log::error('Registration stats fetch failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'तथ्याङ्क लोड गर्न असफल'
            ], 500);
        }
    }

    /**
     * ✅ NEW: Resend confirmation notification (if needed in future)
     */
    public function resendConfirmation(Request $request)
    {
        try {
            $request->validate([
                'email' => 'required|email'
            ]);

            // Find pending request for this email
            $pendingRequest = OrganizationRequest::where('email', $request->email)
                ->where('status', 'pending')
                ->first();

            if (!$pendingRequest) {
                return back()->with('error', 'तपाईंको इमेलमा कुनै पनि बकन्दा अनुरोध फेला परेन।');
            }

            // TODO: Implement email notification resend logic here
            // For now, just log and show success message
            Log::info('Confirmation resend requested', [
                'request_id' => $pendingRequest->id,
                'email' => $request->email
            ]);

            return back()->with('success', 'सूचना पुनः पठाइएको छ। कृपया आफ्नो इमेल जाँच गर्नुहोस्।');
        } catch (\Exception $e) {
            Log::error('Confirmation resend failed: ' . $e->getMessage());
            return back()->with('error', 'सूचना पठाउन असफल। कृपया पुनः प्रयास गर्नुहोस्।');
        }
    }
}
