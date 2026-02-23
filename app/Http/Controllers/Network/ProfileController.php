<?php

namespace App\Http\Controllers\Network;

use App\Http\Controllers\Controller;
use App\Models\OwnerNetworkProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function edit()
    {
        $profile = OwnerNetworkProfile::where('user_id', Auth::id())->firstOrNew();
        return view('network.profile.edit', compact('profile'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'business_name' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            'city' => 'nullable|string|max:100',
            'bio' => 'nullable|string',
            'services' => 'nullable|array',
            'hostel_size' => 'nullable|integer|min:0',
            'pricing_category' => 'nullable|in:budget,mid,premium',
        ]);

        $profile = OwnerNetworkProfile::updateOrCreate(
            ['user_id' => Auth::id(), 'tenant_id' => session('tenant_id')],
            $validated
        );

        return redirect()->route('network.profile.edit')->with('success', 'प्रोफाइल सफलतापूर्वक अद्यावधिक गरियो।');
    }
}
