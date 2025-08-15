<?php

namespace App\Http\Controllers;

use App\Http\Requests\OnboardingStepRequest;
use App\Models\Hostel;
use App\Models\Organization;
use App\Models\OnboardingProgress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OnboardingController extends Controller
{
    /**
     * Show the onboarding wizard progress.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $organization = request()->attributes->get('organization');
        $onboarding = $organization->onboardingProgress;

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
        $organization = request()->attributes->get('organization');
        $onboarding = $organization->onboardingProgress;

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
            \Log::error('Onboarding step failed: ' . $e->getMessage());
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
        $organization = request()->attributes->get('organization');
        $onboarding = $organization->onboardingProgress;

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
            \Log::error('Onboarding step skip failed: ' . $e->getMessage());
            return redirect()->route('onboarding.index')
                ->with('error', 'चरण छोड्दा त्रुटि आयो। कृपया पुन: प्रयास गर्नुहोस्।');
        }
    }

    /**
     * Process step 1: Organization profile & branding
     */
    private function processStep1(Organization $organization, Request $request)
    {
        $organization->update([
            'name' => $request->name,
            'settings' => array_merge((array)$organization->settings, [
                'city' => $request->city,
                'address' => $request->address,
                'contact_phone' => $request->contact_phone,
                'logo' => $request->hasFile('logo') ? $request->file('logo')->store('logos', 'public') : ($organization->settings['logo'] ?? null)
            ])
        ]);
    }

    /**
     * Process step 2: Create Hostel
     */
    private function processStep2(Organization $organization, Request $request)
    {
        $hostelData = [
            'org_id' => $organization->id,
            'name' => $request->hostel_name,
            'city' => $request->hostel_city,
            'address' => $request->hostel_address,
            'contact_phone' => $request->contact_phone,
        ];

        // Check if hostel already exists
        $existingHostel = Hostel::where('org_id', $organization->id)
            ->where('name', $request->hostel_name)
            ->first();

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
        // Assuming you have a RoomType model
        $roomTypeData = [
            'org_id' => $organization->id,
            'name' => $request->room_type,
            'capacity' => $request->capacity,
            'price' => $request->price,
            'amenities' => $request->has('amenities') ? json_encode($request->amenities) : null,
            'description' => $request->description ?? '',
        ];

        // Check if room type already exists
        $existingRoomType = \App\Models\RoomType::where('org_id', $organization->id)
            ->where('name', $request->room_type)
            ->first();

        if ($existingRoomType) {
            $existingRoomType->update($roomTypeData);
        } else {
            \App\Models\RoomType::create($roomTypeData);
        }

        // Create actual rooms based on capacity and room type
        for ($i = 1; $i <= $request->room_count; $i++) {
            \App\Models\Room::create([
                'org_id' => $organization->id,
                'hostel_id' => Hostel::where('org_id', $organization->id)->first()->id,
                'room_type_id' => $existingRoomType ? $existingRoomType->id : \App\Models\RoomType::latest()->first()->id,
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
        $organization->settings = array_merge((array)$organization->settings, [
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
            'org_id' => $organization->id,
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
        $existingStudent = \App\Models\Student::where('org_id', $organization->id)
            ->where('email', $request->student_email)
            ->first();

        if ($existingStudent) {
            $existingStudent->update($studentData);
        } else {
            $student = \App\Models\Student::create($studentData);

            // Create booking
            \App\Models\Booking::create([
                'org_id' => $organization->id,
                'student_id' => $student->id,
                'hostel_id' => Hostel::where('org_id', $organization->id)->first()->id,
                'room_id' => \App\Models\Room::where('org_id', $organization->id)->first()->id,
                'check_in' => $request->check_in,
                'check_out' => $request->check_out,
                'status' => 'confirmed',
                'amount' => $request->monthly_fee,
                'payment_status' => 'pending',
            ]);
        }

        // Create staff user if provided
        if ($request->has('staff_name')) {
            $staffData = [
                'name' => $request->staff_name,
                'email' => $request->staff_email,
                'phone' => $request->staff_phone,
                'password' => bcrypt($request->staff_password),
            ];

            $existingStaff = \App\Models\User::where('email', $request->staff_email)->first();

            if (!$existingStaff) {
                $staff = \App\Models\User::create($staffData);

                // Attach to organization with manager role
                \App\Models\OrganizationUser::create([
                    'org_id' => $organization->id,
                    'user_id' => $staff->id,
                    'role' => 'manager',
                ]);
            }
        }
    }
}
