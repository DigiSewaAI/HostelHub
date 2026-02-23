<?php

namespace App\Http\Controllers\Network;

use App\Http\Controllers\Controller;
use App\Models\OwnerNetworkProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    /**
     * प्रोफाइल सम्पादन पृष्ठ देखाउने
     */
    public function edit()
    {
        // प्रयोगकर्ताको network प्रोफाइल ल्याउने (यदि छैन भने नयाँ बनाउने)
        $profile = OwnerNetworkProfile::where('user_id', Auth::id())->firstOrNew();

        return view('network.profile.edit', compact('profile'));
    }

    /**
     * प्रोफाइल अद्यावधिक गर्ने
     */
    public function update(Request $request)
    {
        $validated = $request->validate([
            'business_name' => 'nullable|string|max:255',
            'phone'         => 'nullable|string|max:20',
            'city'          => 'nullable|string|max:100',
            'bio'           => 'nullable|string',
            'services'      => 'nullable|array',
            'hostel_size'   => 'nullable|integer|min:0',
            'pricing_category' => 'nullable|in:budget,mid,premium',
        ]);

        $user = Auth::user();

        // ✅ Tenant ID प्राप्त गर्ने (तपाईंको डाटाबेस संरचना अनुसार)
        $tenantId = $user->ownerProfile->tenant_id ?? null;

        // यदि tenant ID छैन भने error फर्काउने
        if (!$tenantId) {
            return back()->with('error', 'Tenant जानकारी फेला परेन। कृपया पहिले आफ्नो मालिक प्रोफाइल पूरा गर्नुहोस्।');
        }

        // प्रोफाइल सिर्जना वा अद्यावधिक गर्ने
        $profile = OwnerNetworkProfile::updateOrCreate(
            [
                'user_id'   => $user->id,
                'tenant_id' => $tenantId,   // ✅ सही tenant_id प्रयोग गरियो
            ],
            $validated
        );

        return redirect()->route('network.profile.edit')
            ->with('success', 'प्रोफाइल सफलतापूर्वक अद्यावधिक गरियो।');
    }
}
