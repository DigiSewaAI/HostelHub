<?php

namespace App\Http\Controllers;

use App\Http\Requests\OnboardingStepRequest;
use App\Models\Hostel;
use App\Models\Organization;
use App\Models\OnboardingProgress;
use App\Models\RoomType;
use App\Models\Room;
use App\Models\Student;
use App\Models\Booking;
use App\Models\User;
use App\Models\OrganizationUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class OnboardingController extends Controller
{
    /**
     * Show the onboarding wizard progress.
     *
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function index()
    {
        $user = Auth::user();

        // Get organization through user relationship
        $organization = $user->organization;

        // Redirect if no organization exists
        if (!$organization) {
            return redirect()->route('register.organization')
                ->with('error', 'कृपया पहिले आफ्नो संस्था सिर्जना गर्नुहोस्।');
        }

        $onboarding = $organization->onboardingProgress;

        // If onboarding progress doesn't exist, create it
        if (!$onboarding) {
            $onboarding = OnboardingProgress::create([
                'organization_id' => $organization->id,
                'current_step'    => 1,
                'completed'       => [],
            ]);
        }

        // If organization is already ready, redirect to dashboard
        if ($organization->is_ready) {
            return redirect()->route('dashboard');
        }

        return view('onboarding.index', compact('organization', 'onboarding'));
    }

    /**
     * Process the current onboarding step.
     *
     * @param  \App\Http\Requests\OnboardingStepRequest  $request
     * @param  int  $step
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(OnboardingStepRequest $request, $step)
    {
        $user = Auth::user();
        $organization = $user->organization;

        if (!$organization) {
            return redirect()->route('register.organization')
                ->with('error', 'कृपया पहिले आफ्नो संस्था सिर्जना गर्नुहोस्।');
        }

        $onboarding = $organization->onboardingProgress;

        // If onboarding progress doesn't exist, create it
        if (!$onboarding) {
            $onboarding = OnboardingProgress::create([
                'organization_id' => $organization->id,
                'current_step'    => 1,
                'completed'       => [],
            ]);
        }

        // Validate step
        if ($step < 1 || $step > 5 || $onboarding->current_step != $step) {
            return redirect()->route('onboarding.index')
                ->with('error', 'अमान्य चरण नम्बर। कृपया पूर्व चरण पूरा गर्नुहोस्।');
        }

        DB::beginTransaction();

        try {
            // Process step data
            switch ($step) {
                case 1:
                    $this->processStep1($organization, $request);
                    break;

                case 2:
                    $this->processStep2($organization, $request);
                    break;

                case 3:
                    $this->processStep3($organization, $request);
                    break;

                case 4:
                    $this->processStep4($organization, $request);
                    break;

                case 5:
                    $this->processStep5($organization, $request);
                    break;
            }

            // Mark step as complete
            $onboarding->markStepComplete($step);

            // Check if onboarding is complete
            if ($onboarding->isCompleted()) {
                $organization->update(['is_ready' => true]);
                DB::commit();
                return redirect()->route('dashboard')
                    ->with('success', 'स्वागत छ! तपाईंको होस्टल प्रबन्धन प्रणाली तयार छ।');
            }

            DB::commit();
            return redirect()->route('onboarding.index')
                ->with('success', 'चरण सफलतापूर्वक पूरा गरियो।');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Onboarding step failed: ' . $e->getMessage());
            return redirect()->back()
                ->withInput()
                ->with('error', 'चरण प्रक्रिया गर्दा त्रुटि आयो। कृपया पुन: प्रयास गर्नुहोस्।');
        }
    }

    /**
     * Skip the current onboarding step.
     *
     * @param  int  $step
     * @return \Illuminate\Http\RedirectResponse
     */
    public function skip($step)
    {
        $user = Auth::user();
        $organization = $user->organization;

        if (!$organization) {
            return redirect()->route('register.organization')
                ->with('error', 'कृपया पहिले आफ्नो संस्था सिर्जना गर्नुहोस्।');
        }

        $onboarding = $organization->onboardingProgress;

        // If onboarding progress doesn't exist, create it
        if (!$onboarding) {
            $onboarding = OnboardingProgress::create([
                'organization_id' => $organization->id,
                'current_step'    => 1,
                'completed'       => [],
            ]);
        }

        // Validate step
        if ($step < 1 || $step > 5 || $onboarding->current_step != $step) {
            return redirect()->route('onboarding.index')
                ->with('error', 'अमान्य चरण नम्बर। कृपया पूर्व चरण पूरा गर्नुहोस्।');
        }

        DB::beginTransaction();

        try {
            // Skip step
            $onboarding->skipStep($step);

            // Check if onboarding is complete
            if ($onboarding->isCompleted()) {
                $organization->update(['is_ready' => true]);
                DB::commit();
                return redirect()->route('dashboard')
                    ->with('success', 'स्वागत छ! तपाईंको होस्टल प्रबन्धन प्रणाली तयार छ।');
            }

            DB::commit();
            return redirect()->route('onboarding.index')
                ->with('info', 'चरण छोडियो। तपाईं यसलाई पछि पनि पूरा गर्न सक्नुहुन्छ।');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Onboarding step skip failed: ' . $e->getMessage());
            return redirect()->route('onboarding.index')
                ->with('error', 'चरण छोड्दा त्रुटि आयो। कृपया पुन: प्रयास गर्नुहोस्।');
        }
    }

    /**
     * Process step 1: Organization profile & branding
     */
    private function processStep1(Organization $organization, Request $request)
    {
        $settings = (array) $organization->settings;

        $organization->update([
            'name' => $request->name,
            'settings' => array_merge($settings, [
                'city' => $request->city,
                'address' => $request->address,
                'contact_phone' => $request->contact_phone,
                'logo' => $request->hasFile('logo') ? $request->file('logo')->store('logos', 'public') : ($settings['logo'] ?? null)
            ])
        ]);
    }

    /**
     * Process step 2: Create Hostel
     */
    private function processStep2(Organization $organization, Request $request)
    {
        $hostelData = [
            'organization_id' => $organization->id,
            'name' => $request->hostel_name,
            'city' => $request->hostel_city,
            'address' => $request->hostel_address,
            'contact_phone' => $request->contact_phone,
        ];

        // Check if hostel already exists
        $existingHostel = Hostel::where('organization_id', $organization->id)->first();

        if ($existingHostel) {
            $existingHostel->update($hostelData);
        } else {
            Hostel::create($hostelData);
        }
    }

    /**
     * Process step 3: Room Types & Rooms
     */
    private function processStep3(Organization $organization, Request $request)
    {
        // Room Type
        $roomTypeData = [
            'organization_id' => $organization->id,
            'name' => $request->room_type,
            'capacity' => $request->capacity,
            'price' => $request->price,
            'amenities' => $request->has('amenities') ? json_encode($request->amenities) : null,
            'description' => $request->description ?? '',
        ];

        // Check if room type already exists
        $existingRoomType = RoomType::where('organization_id', $organization->id)->first();

        if ($existingRoomType) {
            $existingRoomType->update($roomTypeData);
        } else {
            $existingRoomType = RoomType::create($roomTypeData);
        }

        // Get the first hostel for this organization
        $hostel = Hostel::where('organization_id', $organization->id)->first();

        if (!$hostel) {
            throw new \Exception('कृपया पहिले होस्टल सिर्जना गर्नुहोस्।');
        }

        // Create actual rooms
        for ($i = 1; $i <= $request->room_count; $i++) {
            Room::create([
                'organization_id' => $organization->id,
                'hostel_id' => $hostel->id,
                'room_type_id' => $existingRoomType->id,
                'room_number' => $request->room_prefix . $i,
                'status' => 'available',
                'description' => $request->room_description ?? '',
            ]);
        }
    }

    /**
     * Process step 4: Fees & Billing template
     */
    private function processStep4(Organization $organization, Request $request)
    {
        $settings = (array) $organization->settings;

        $organization->settings = array_merge($settings, [
            'monthly_fee' => $request->monthly_fee,
            'deposit' => $request->deposit,
            'meal_plan' => $request->has('meal_plan'),
            'meal_price' => $request->has('meal_plan') ? $request->meal_price : null,
            'payment_methods' => $request->has('payment_methods') ? json_encode($request->payment_methods) : null,
            'invoice_template' => $request->invoice_template ?? '',
            'late_fee' => $request->late_fee ?? 0,
            'late_fee_grace_days' => $request->late_fee_grace_days ?? 3,
        ]);

        $organization->save();
    }

    /**
     * Process step 5: Add Students
     */
    private function processStep5(Organization $organization, Request $request)
    {
        // Create student
        $studentData = [
            'organization_id' => $organization->id,
            'name' => $request->student_name,
            'email' => $request->student_email,
            'phone' => $request->student_phone,
            'address' => $request->student_address ?? '',
            'college' => $request->college ?? '',
            'course' => $request->course ?? '',
            'check_in_date' => $request->check_in,
            'check_out_date' => $request->check_out,
            'status' => 'active',
        ];

        // Check if student already exists
        $existingStudent = Student::where('organization_id', $organization->id)
            ->where('email', $request->student_email)
            ->first();

        if ($existingStudent) {
            $existingStudent->update($studentData);
            $student = $existingStudent;
        } else {
            $student = Student::create($studentData);
        }

        // Get the first hostel and room for this organization
        $hostel = Hostel::where('organization_id', $organization->id)->first();
        $room = Room::where('organization_id', $organization->id)->first();

        if (!$hostel || !$room) {
            throw new \Exception('कृपया पहिले होस्टल र कोठा सिर्जना गर्नुहोस्।');
        }

        // Create booking
        Booking::create([
            'organization_id' => $organization->id,
            'student_id' => $student->id,
            'hostel_id' => $hostel->id,
            'room_id' => $room->id,
            'check_in' => $request->check_in,
            'check_out' => $request->check_out,
            'status' => 'confirmed',
            'amount' => $request->monthly_fee,
            'payment_status' => 'pending',
        ]);

        // Create staff user if provided
        if ($request->has('staff_name')) {
            $staffData = [
                'name' => $request->staff_name,
                'email' => $request->staff_email,
                'phone' => $request->staff_phone,
                'password' => bcrypt($request->staff_password),
                'organization_id' => $organization->id,
            ];

            $existingStaff = User::where('email', $request->staff_email)->first();

            if (!$existingStaff) {
                $staff = User::create($staffData);
                $staff->assignRole('hostel_manager');

                // Attach to organization with manager role
                OrganizationUser::create([
                    'organization_id' => $organization->id,
                    'user_id' => $staff->id,
                    'role' => 'manager',
                ]);
            }
        }
    }
}
