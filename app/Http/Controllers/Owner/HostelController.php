<?php

namespace App\Http\Controllers\Owner;

use App\Models\Hostel;
use App\Models\Organization;
use App\Services\PlanLimitService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Owner\UpdateHostelRequest;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class HostelController extends Controller
{
    protected $planLimitService;

    public function __construct(PlanLimitService $planLimitService)
    {
        $this->planLimitService = $planLimitService;
    }

    public function index()
    {
        // ✅ Organization-based logic
        $user = auth()->user();
        $organization = $user->organizations()
            ->wherePivot('role', 'owner')
            ->first();

        if (!$organization) {
            abort(403, 'तपाईंसँग कुनै संस्था छैन');
        }

        // Organization को सबै hostels देखाउने
        $hostels = $organization->hostels;

        // Single hostel view को लागि first hostel लिने
        $hostel = $hostels->first();

        // Plan usage overview
        $usageOverview = $this->planLimitService->getUsageOverview($organization);

        return view('owner.hostels.index', compact('hostels', 'organization', 'hostel', 'usageOverview'));
    }

    public function create()
    {
        // ✅ Organization owner लाई hostel create गर्न दिने
        $user = auth()->user();
        $organization = $user->organizations()
            ->wherePivot('role', 'owner')
            ->first();

        if (!$organization) {
            abort(403, 'तपाईंसँग कुनै संस्था छैन');
        }

        // Plan limit check using service
        if (!$this->planLimitService->canAddHostel($organization)) {
            $hostelUsage = $this->planLimitService->getHostelUsage($organization);
            $plan = $organization->subscription ? $organization->subscription->plan : null;

            if ($plan) {
                $message = "तपाईंको {$plan->name} योजनामा {$plan->max_hostels} होस्टेल मात्र सिर्जना गर्न सकिन्छ। (हाल {$hostelUsage['current']} होस्टेल छन्)";
            } else {
                $message = "तपाईंसँग कुनै सक्रिय योजना छैन। होस्टेल सिर्जना गर्न योजना सक्रिय गर्नुहोस्।";
            }

            return redirect()->route('owner.hostels.index')->with('error', $message);
        }

        return view('owner.hostels.create', compact('organization'));
    }

    public function store(Request $request)
    {
        // ✅ Organization owner लाई hostel create गर्न दिने
        $user = auth()->user();
        $organization = $user->organizations()
            ->wherePivot('role', 'owner')
            ->first();

        if (!$organization) {
            abort(403, 'तपाईंसँग कुनै संस्था छैन');
        }

        // Double check plan limits (in case someone bypasses the frontend)
        if (!$this->planLimitService->canAddHostel($organization)) {
            $hostelUsage = $this->planLimitService->getHostelUsage($organization);
            $plan = $organization->subscription ? $organization->subscription->plan : null;

            if ($plan) {
                $message = "तपाईंको {$plan->name} योजनामा {$plan->max_hostels} होस्टेल मात्र सिर्जना गर्न सकिन्छ। (हाल {$hostelUsage['current']} होस्टेल छन्)";
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
        ]);

        $hostelData = $request->all();
        $hostelData['organization_id'] = $organization->id;
        $hostelData['owner_id'] = $user->id;
        $hostelData['status'] = 'active';
        $hostelData['slug'] = Str::slug($request->name);

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

        Hostel::create($hostelData);

        // Update usage overview for success message
        $hostelUsage = $this->planLimitService->getHostelUsage($organization);
        $remainingHostels = $this->planLimitService->getRemainingHostels($organization);

        $successMessage = 'होस्टल सफलतापूर्वक सिर्जना गरियो!';
        if ($remainingHostels < PHP_INT_MAX) {
            $successMessage .= " ({$remainingHostels} होस्टेल थप सिर्जना गर्न सकिन्छ)";
        }

        return redirect()->route('owner.hostels.index')
            ->with('success', $successMessage);
    }

    public function show(Hostel $hostel)
    {
        // ✅ Organization-based permission check
        $user = auth()->user();
        $organization = $user->organizations()
            ->wherePivot('role', 'owner')
            ->first();

        if (!$organization || $hostel->organization_id != $organization->id) {
            abort(403, 'तपाईंसँग यो होस्टल हेर्ने अनुमति छैन');
        }

        return view('owner.hostels.show', compact('hostel', 'organization'));
    }

    public function edit(Hostel $hostel)
    {
        // ✅ Organization-based permission check
        $user = auth()->user();
        $organization = $user->organizations()
            ->wherePivot('role', 'owner')
            ->first();

        if (!$organization || $hostel->organization_id != $organization->id) {
            abort(403, 'तपाईंसँग यो होस्टल सम्पादन गर्ने अनुमति छैन');
        }

        return view('owner.hostels.edit', compact('hostel', 'organization'));
    }

    public function update(UpdateHostelRequest $request, Hostel $hostel)
    {
        // ✅ Organization-based permission check
        $user = auth()->user();
        $organization = $user->organizations()
            ->wherePivot('role', 'owner')
            ->first();

        if (!$organization || $hostel->organization_id != $organization->id) {
            abort(403, 'तपाईंसँग यो होस्टल सम्पादन गर्ने अनुमति छैन');
        }

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

        $hostel->update($validated);

        return redirect()->route('owner.hostels.index')
            ->with('success', 'होस्टल विवरण सफलतापूर्वक अद्यावधिक गरियो!');
    }

    public function destroy(Hostel $hostel)
    {
        // ✅ Organization-based permission check
        $user = auth()->user();
        $organization = $user->organizations()
            ->wherePivot('role', 'owner')
            ->first();

        if (!$organization || $hostel->organization_id != $organization->id) {
            abort(403, 'तपाईंसँग यो होस्टल हटाउने अनुमति छैन');
        }

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

        return redirect()->route('owner.hostels.index')
            ->with('success', 'होस्टल सफलतापूर्वक मेटाइयो!');
    }
}
