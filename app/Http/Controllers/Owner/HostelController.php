<?php

namespace App\Http\Controllers\Owner;

use App\Models\Hostel;
use App\Models\Organization;
use App\Models\Subscription;
use App\Services\PlanLimitService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Owner\UpdateHostelRequest;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class HostelController extends Controller
{
    protected $planLimitService;

    public function __construct(PlanLimitService $planLimitService)
    {
        $this->planLimitService = $planLimitService;
    }

    public function index()
    {
        // ✅ Organization-based logic (updated to use session)
        $organizationId = session('selected_organization_id');

        if (!$organizationId) {
            return redirect()->route('organizations.select')
                ->with('error', 'कृपया पहिले संस्था चयन गर्नुहोस्');
        }

        $organization = Organization::find($organizationId);

        if (!$organization) {
            abort(403, 'तपाईंसँग कुनै संस्था छैन');
        }

        // Organization को सबै hostels देखाउने
        $hostels = $organization->hostels()->withCount(['rooms', 'students'])->get();

        // Single hostel view को लागि first hostel लिने
        $hostel = $hostels->first();

        // Plan usage overview
        $usageOverview = $this->planLimitService->getUsageOverview($organization);

        // Subscription status check
        $subscription = $organization->currentSubscription();
        $canCreateMoreHostels = $subscription ? $this->planLimitService->canAddHostel($organization) : false;

        return view('owner.hostels.index', compact(
            'hostels',
            'organization',
            'hostel',
            'usageOverview',
            'subscription',
            'canCreateMoreHostels'
        ));
    }

    public function create()
    {
        // ✅ Organization owner लाई hostel create गर्न दिने
        $organizationId = session('selected_organization_id');

        if (!$organizationId) {
            return redirect()->route('organizations.select')
                ->with('error', 'कृपया पहिले संस्था चयन गर्नुहोस्');
        }

        $organization = Organization::find($organizationId);

        if (!$organization) {
            abort(403, 'तपाईंसँग कुनै संस्था छैन');
        }

        // Subscription check
        $subscription = $organization->currentSubscription();

        if (!$subscription) {
            return redirect()->route('subscription.plans')
                ->with('error', 'होस्टेल सिर्जना गर्न सदस्यता योजना आवश्यक छ।');
        }

        // Plan limit check using service
        if (!$this->planLimitService->canAddHostel($organization)) {
            $hostelUsage = $this->planLimitService->getHostelUsage($organization);
            $plan = $subscription->plan;

            if ($plan) {
                $maxHostels = $plan->max_hostels + ($subscription->extra_hostels ?? 0);
                $message = "तपाईंको {$plan->name} योजनामा {$maxHostels} होस्टेल मात्र सिर्जना गर्न सकिन्छ। (हाल {$hostelUsage['current']} होस्टेल छन्)";

                // Check if they can purchase extra hostels
                if ($plan->supports_extra_hostels) {
                    $message .= " <a href='" . route('subscription.limits') . "' class='text-primary'>अतिरिक्त होस्टेल किन्नुहोस्</a>";
                }
            } else {
                $message = "तपाईंसँग कुनै सक्रिय योजना छैन। होस्टेल सिर्जना गर्न योजना सक्रिय गर्नुहोस्।";
            }

            return redirect()->route('owner.hostels.index')->with('error', $message);
        }

        return view('owner.hostels.create', compact('organization', 'subscription'));
    }

    public function store(Request $request)
    {
        // ✅ Organization owner लाई hostel create गर्न दिने
        $organizationId = session('selected_organization_id');

        if (!$organizationId) {
            return redirect()->route('organizations.select')
                ->with('error', 'कृपया पहिले संस्था चयन गर्नुहोस्');
        }

        $organization = Organization::find($organizationId);
        $user = auth()->user();

        if (!$organization) {
            abort(403, 'तपाईंसँग कुनै संस्था छैन');
        }

        // Double check plan limits (in case someone bypasses the frontend)
        if (!$this->planLimitService->canAddHostel($organization)) {
            $hostelUsage = $this->planLimitService->getHostelUsage($organization);
            $subscription = $organization->currentSubscription();
            $plan = $subscription ? $subscription->plan : null;

            if ($plan) {
                $maxHostels = $plan->max_hostels + ($subscription->extra_hostels ?? 0);
                $message = "तपाईंको {$plan->name} योजनामा {$maxHostels} होस्टेल मात्र सिर्जना गर्न सकिन्छ। (हाल {$hostelUsage['current']} होस्टेल छन्)";
            } else {
                $message = "तपाईंसँग कुनै सक्रिय योजना छैन। होस्टेल सिर्जना गर्न योजना सक्रिय गर्नुहोस्।";
            }

            return redirect()->route('owner.hostels.index')->with('error', $message);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'contact_person' => 'required|string|max:255',
            'contact_phone' => 'required|string|max:15',
            'contact_email' => 'nullable|email|max:255',
            'description' => 'nullable|string',
            'total_rooms' => 'required|integer|min:1',
            'available_rooms' => 'required|integer|min:0|max:' . $request->total_rooms,
            'facilities' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'monthly_rent' => 'required|numeric|min:0',
            'security_deposit' => 'required|numeric|min:0',
        ]);

        DB::beginTransaction();

        try {
            $hostelData = $request->all();
            $hostelData['organization_id'] = $organization->id;
            $hostelData['owner_id'] = $user->id;
            $hostelData['status'] = 'active';
            $hostelData['slug'] = $this->generateUniqueSlug($request->name, $organization->id);

            // Handle facilities field
            if ($request->has('facilities') && !empty($request->facilities)) {
                $hostelData['facilities'] = json_encode(explode(',', $request->facilities));
            } else {
                $hostelData['facilities'] = null;
            }

            // Handle image upload
            if ($request->hasFile('image')) {
                $hostelData['image'] = $request->file('image')->store('hostels', 'public');
            }

            $hostel = Hostel::create($hostelData);

            DB::commit();

            // Update usage overview for success message
            $hostelUsage = $this->planLimitService->getHostelUsage($organization);
            $remainingHostels = $this->planLimitService->getRemainingHostels($organization);

            $successMessage = 'होस्टल सफलतापूर्वक सिर्जना गरियो!';
            if ($remainingHostels > 0) {
                $successMessage .= " ({$remainingHostels} होस्टेल थप सिर्जना गर्न सकिन्छ)";
            } else {
                $successMessage .= " (होस्टेल सीमा पुग्यो)";
            }

            return redirect()->route('owner.hostels.index')
                ->with('success', $successMessage);
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'होस्टल सिर्जना गर्दा त्रुटि: ' . $e->getMessage());
        }
    }

    public function show(Hostel $hostel)
    {
        // ✅ Organization-based permission check
        $organizationId = session('selected_organization_id');

        if (!$organizationId || $hostel->organization_id != $organizationId) {
            abort(403, 'तपाईंसँग यो होस्टल हेर्ने अनुमति छैन');
        }

        $organization = Organization::find($organizationId);
        $hostel->load(['rooms', 'students.user', 'owner']);

        // Load statistics
        $occupiedRooms = $hostel->rooms()->where('is_available', false)->count();
        $availableRooms = $hostel->rooms()->where('is_available', true)->count();
        $totalStudents = $hostel->students()->count();

        return view('owner.hostels.show', compact(
            'hostel',
            'organization',
            'occupiedRooms',
            'availableRooms',
            'totalStudents'
        ));
    }

    public function edit(Hostel $hostel)
    {
        // ✅ Organization-based permission check
        $organizationId = session('selected_organization_id');

        if (!$organizationId || $hostel->organization_id != $organizationId) {
            abort(403, 'तपाईंसँग यो होस्टल सम्पादन गर्ने अनुमति छैन');
        }

        $organization = Organization::find($organizationId);

        return view('owner.hostels.edit', compact('hostel', 'organization'));
    }

    public function update(UpdateHostelRequest $request, Hostel $hostel)
    {
        // ✅ Organization-based permission check
        $organizationId = session('selected_organization_id');

        if (!$organizationId || $hostel->organization_id != $organizationId) {
            abort(403, 'तपाईंसँग यो होस्टल सम्पादन गर्ने अनुमति छैन');
        }

        DB::beginTransaction();

        try {
            $validated = $request->validated();

            // Handle facilities field
            if ($request->has('facilities') && !empty($request->facilities)) {
                $validated['facilities'] = json_encode(explode(',', $request->facilities));
            } else {
                $validated['facilities'] = null;
            }

            // Handle remove_image checkbox
            if ($request->has('remove_image') && $request->remove_image == '1') {
                // Delete old image if exists
                if ($hostel->image) {
                    Storage::disk('public')->delete($hostel->image);
                }
                $validated['image'] = null;
            }

            // Handle image upload
            if ($request->hasFile('image')) {
                // Delete old image if exists
                if ($hostel->image) {
                    Storage::disk('public')->delete($hostel->image);
                }
                $validated['image'] = $request->file('image')->store('hostels', 'public');
            }

            // Update slug if name changed
            if ($request->has('name') && $request->name != $hostel->name) {
                $validated['slug'] = $this->generateUniqueSlug($request->name, $hostel->organization_id, $hostel->id);
            }

            $hostel->update($validated);

            DB::commit();

            return redirect()->route('owner.hostels.index')
                ->with('success', 'होस्टल विवरण सफलतापूर्वक अद्यावधिक गरियो!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'होस्टल अद्यावधिक गर्दा त्रुटि: ' . $e->getMessage());
        }
    }

    public function destroy(Hostel $hostel)
    {
        // ✅ Organization-based permission check
        $organizationId = session('selected_organization_id');

        if (!$organizationId || $hostel->organization_id != $organizationId) {
            abort(403, 'तपाईंसँग यो होस्टल हटाउने अनुमति छैन');
        }

        DB::beginTransaction();

        try {
            // Prevent deletion if hostel has rooms or students
            if ($hostel->rooms()->count() > 0) {
                return redirect()->back()
                    ->with('error', 'यो होस्टलमा कोठाहरू छन्। पहिले सबै कोठाहरू हटाउनुहोस्।');
            }

            if ($hostel->students()->count() > 0) {
                return redirect()->back()
                    ->with('error', 'यो होस्टलमा विद्यार्थीहरू छन्। पहिले सबै विद्यार्थीहरू हटाउनुहोस्।');
            }

            // Delete image if exists
            if ($hostel->image) {
                Storage::disk('public')->delete($hostel->image);
            }

            $hostel->delete();

            DB::commit();

            return redirect()->route('owner.hostels.index')
                ->with('success', 'होस्टल सफलतापूर्वक मेटाइयो!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'होस्टल मेटाउँदा त्रुटि: ' . $e->getMessage());
        }
    }

    /**
     * Generate unique slug for hostel
     */
    private function generateUniqueSlug($name, $organizationId, $excludeId = null)
    {
        $slug = Str::slug($name);
        $originalSlug = $slug;
        $count = 1;

        $query = Hostel::where('organization_id', $organizationId)
            ->where('slug', $slug);

        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }

        while ($query->exists()) {
            $slug = $originalSlug . '-' . $count;
            $count++;

            $query = Hostel::where('organization_id', $organizationId)
                ->where('slug', $slug);

            if ($excludeId) {
                $query->where('id', '!=', $excludeId);
            }
        }

        return $slug;
    }

    /**
     * Toggle hostel status (active/inactive)
     */
    public function toggleStatus(Hostel $hostel)
    {
        $organizationId = session('selected_organization_id');

        if (!$organizationId || $hostel->organization_id != $organizationId) {
            abort(403, 'तपाईंसँग यो होस्टलको स्थिति परिवर्तन गर्ने अनुमति छैन');
        }

        $newStatus = $hostel->status == 'active' ? 'inactive' : 'active';
        $hostel->update(['status' => $newStatus]);

        $statusText = $newStatus == 'active' ? 'सक्रिय' : 'निष्क्रिय';

        return back()->with('success', "होस्टल {$statusText} गरियो।");
    }

    /**
     * Get hostel statistics for dashboard
     */
    public function getStatistics()
    {
        $organizationId = session('selected_organization_id');

        if (!$organizationId) {
            return response()->json(['error' => 'संस्था भेटिएन'], 404);
        }

        $organization = Organization::find($organizationId);
        $totalHostels = $organization->hostels()->count();
        $activeHostels = $organization->hostels()->where('status', 'active')->count();
        $totalRooms = $organization->rooms()->count();
        $occupiedRooms = $organization->rooms()->where('is_available', false)->count();

        return response()->json([
            'total_hostels' => $totalHostels,
            'active_hostels' => $activeHostels,
            'total_rooms' => $totalRooms,
            'occupied_rooms' => $occupiedRooms,
            'vacant_rooms' => $totalRooms - $occupiedRooms
        ]);
    }
}
