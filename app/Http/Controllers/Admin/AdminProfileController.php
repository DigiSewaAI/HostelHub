<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\OwnerNetworkProfile;
use App\Models\Hostel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminProfileController extends Controller
{
    public function index(Request $request)
    {
        $query = OwnerNetworkProfile::with('hostel');

        if ($request->filled('trust')) {
            $query->where('trust_level', $request->trust);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('hostel', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('city', 'like', "%{$search}%");
            });
        }

        $profiles = $query->paginate(15)->withQueryString();

        return view('admin.network.profiles.index', compact('profiles'));
    }

    public function show(OwnerNetworkProfile $profile)
    {
        $profile->load('hostel.owner');
        return view('admin.network.profiles.show', compact('profile'));
    }

    public function updateTrust(Request $request, OwnerNetworkProfile $profile)
    {
        $request->validate([
            'trust_level' => 'required|in:normal,verified,trusted,suspended',
            'suspend_reason' => 'required_if:trust_level,suspended|nullable|string|max:500',
        ]);

        DB::transaction(function () use ($request, $profile) {
            $oldLevel = $profile->trust_level;
            $profile->trust_level = $request->trust_level;

            if ($request->trust_level === 'suspended') {
                $profile->suspended_at = now();
            } else {
                $profile->suspended_at = null;
                // ✅ यदि verified वा trusted हो भने verified_at सेट गर्नुहोस्
                if (in_array($request->trust_level, ['verified', 'trusted']) && !$profile->verified_at) {
                    $profile->verified_at = now();
                }
            }

            $profile->save();

            // Log activity or send notification to owner
        });

        return redirect()->route('admin.network.profiles.index')
            ->with('success', 'Trust level updated successfully.');
    }

    public function removeFromNetwork(OwnerNetworkProfile $profile)
    {
        // This actually deletes the profile, effectively removing the hostel from network
        $profile->delete();

        return redirect()->route('admin.network.profiles.index')
            ->with('success', 'Hostel removed from network.');
    }
}
