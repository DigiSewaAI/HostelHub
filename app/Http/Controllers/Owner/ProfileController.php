<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    /**
     * Constructor - Apply owner authorization checks
     */
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            // Check if user has owner role
            if (!Auth::check() || !Auth::user()->hasRole('hostel_manager')) {
                abort(403, 'तपाईंसँग यो पृष्ठ एक्सेस गर्ने अनुमति छैन');
            }
            return $next($request);
        });
    }

    /**
     * Show the owner profile edit page
     */
    public function edit()
    {
        $user = Auth::user();

        // Verify user is owner before proceeding
        $this->checkOwnerAccess($user->id);

        return view('owner.profile.edit', compact('user'));
    }

    /**
     * Update the owner's profile
     */
    public function update(Request $request)
    {
        $user = Auth::user();

        // Verify user is owner before proceeding
        $this->checkOwnerAccess($user->id);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
        ]);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
        ]);

        return redirect()->route('owner.profile.edit')
            ->with('success', 'प्रोफाइल सफलतापूर्वक अद्यावधिक गरियो।');
    }

    /**
     * Update the owner's profile picture
     */
    public function updateAvatar(Request $request)
    {
        $user = Auth::user();

        // Verify user is owner before proceeding
        $this->checkOwnerAccess($user->id);

        $request->validate([
            'avatar' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Handle avatar upload
        if ($request->hasFile('avatar')) {
            $avatarPath = $request->file('avatar')->store('avatars', 'public');

            // Delete old avatar if exists
            if ($user->avatar) {
                Storage::disk('public')->delete($user->avatar);
            }

            $user->update(['avatar' => $avatarPath]);
        }

        return redirect()->route('owner.profile.edit')
            ->with('success', 'प्रोफाइल तस्वीर सफलतापूर्वक अद्यावधिक गरियो।');
    }

    /**
     * OWNER AUTHORIZATION STRENGTHENING
     * Check if user has owner access
     */
    private function checkOwnerAccess($userId)
    {
        $user = Auth::user();

        // Check if user has owner role and is accessing their own data
        if (!$user->hasRole('hostel_manager') || $user->id != $userId) {
            abort(403, 'तपाईंसँग यो प्रोफाइल एक्सेस गर्ने अनुमति छैन');
        }
        return true;
    }

    /**
     * OWNER DATA SCOPING
     * Scope queries for owner-specific data (for future use in relationships)
     */
    private function scopeForOwner($query, $ownerId)
    {
        return $query->whereHas('hostel', function ($q) use ($ownerId) {
            $q->where('owner_id', $ownerId);
        });
    }
}
