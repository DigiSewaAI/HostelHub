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

        // Get the first hostel managed by this user (assuming one owner may have multiple, we take the first)
        // Adjust if you have a specific logic (e.g., if user is owner_id in hostels table)
        $hostel = $user->hostels()->first();

        if (!$hostel) {
            return redirect()->route('owner.dashboard')
                ->with('error', 'तपाईंसँग कुनै होस्टल छैन।');
        }

        // Ensure the network profile exists (should be auto-created by observer)
        $profile = $hostel->networkProfile;

        // Authorize using the policy
        if (Gate::denies('view', $profile)) {
            abort(403, 'यो प्रोफाइल हेर्ने अनुमति छैन।');
        }

        return view('network.profile.show', compact('hostel', 'profile'));
    }
}
