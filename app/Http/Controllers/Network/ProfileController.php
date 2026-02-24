<?php

namespace App\Http\Controllers\Network;

use App\Http\Controllers\Controller;
use App\Models\Hostel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class ProfileController extends Controller
{
    /**
     * Show the auto-synced network profile of the logged-in owner's hostel.
     */
    public function show()
    {
        $user = Auth::user();

        // 1️⃣ Get all **eligible** hostels of the user (active & published)
        $eligibleHostels = $user->hostels()
            ->where('status', 'active')
            ->where('is_published', true)
            ->get();

        if ($eligibleHostels->isEmpty()) {
            return redirect()->route('owner.dashboard')
                ->with('error', 'तपाईंसँग नेटवर्कको लागि योग्य होस्टल छैन।');
        }

        // 2️⃣ Use the first eligible hostel (you could later allow choosing)
        $hostel = $eligibleHostels->first();

        // 3️⃣ Ensure the network profile exists (sync if missing)
        if (!$hostel->networkProfile) {
            app(\App\Services\NetworkProfileSyncService::class)->syncForHostel($hostel);
            $hostel->load('networkProfile');
        }

        $profile = $hostel->networkProfile;

        // 4️⃣ Authorize (the policy checks ownership)
        if (Gate::denies('view', $profile)) {
            abort(403, 'यो प्रोफाइल हेर्ने अनुमति छैन।');
        }

        return view('network.profile.show', compact('hostel', 'profile'));
    }
}
