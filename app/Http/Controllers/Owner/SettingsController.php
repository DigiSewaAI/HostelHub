<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Organization;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class SettingsController extends Controller
{
    /**
     * Constructor - Apply owner authorization checks
     */
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            // Check if user has owner role
            if (!Auth::user()->hasRole('hostel_manager')) {
                abort(403, 'तपाईंसँग यो पृष्ठ एक्सेस गर्ने अनुमति छैन');
            }
            return $next($request);
        });
    }

    /**
     * Show the owner settings page
     */
    public function index()
    {
        $user = Auth::user();

        // Verify user is owner before proceeding
        $this->checkOwnerAccess($user->id);

        $organization = $user->organizations()->first();

        return view('owner.settings.index', compact('user', 'organization'));
    }

    /**
     * Update general settings
     */
    public function updateGeneral(Request $request)
    {
        $user = Auth::user();

        // Verify user is owner before proceeding
        $this->checkOwnerAccess($user->id);

        $organization = $user->organizations()->first();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|' . Rule::unique('users')->ignore($user->id),
            'phone' => 'nullable|string|max:20',
            'organization_name' => 'required|string|max:255',
        ]);

        // Update user
        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
        ]);

        // Update organization - with owner verification
        if ($organization) {
            // Additional check to ensure owner owns this organization
            if (!$user->organizations()->where('id', $organization->id)->exists()) {
                abort(403, 'तपाईंसँग यो संस्था सम्पादन गर्ने अनुमति छैन');
            }

            $organization->update([
                'name' => $request->organization_name,
            ]);
        }

        return redirect()->route('owner.settings.index')
            ->with('success', 'General settings updated successfully.');
    }

    /**
     * Update payment settings - Organizations table बाट settings column use गर्ने
     */
    public function updatePayment(Request $request)
    {
        $request->validate([
            'bank_name' => 'nullable|string|max:255',
            'account_number' => 'nullable|string|max:50',
            'account_holder' => 'nullable|string|max:255',
            'esewa_merchant_id' => 'nullable|string|max:255',
            'khalti_merchant_id' => 'nullable|string|max:255',
        ]);

        $user = Auth::user();

        // Verify user is owner before proceeding
        $this->checkOwnerAccess($user->id);

        $organization = $user->organizations()->first();

        if ($organization) {
            // Additional ownership verification
            if (!$user->organizations()->where('id', $organization->id)->exists()) {
                abort(403, 'तपाईंसँग यो संस्था सम्पादन गर्ने अनुमति छैन');
            }

            // Organizations table को settings column मा payment settings store गर्ने
            $settings = $organization->settings ?? [];

            $settings['payment'] = [
                'bank_name' => $request->bank_name,
                'account_number' => $request->account_number,
                'account_holder' => $request->account_holder,
                'esewa_merchant_id' => $request->esewa_merchant_id,
                'khalti_merchant_id' => $request->khalti_merchant_id,
            ];

            $organization->update([
                'settings' => $settings
            ]);
        }

        return redirect()->route('owner.settings.index')
            ->with('success', 'Payment settings updated successfully.');
    }

    /**
     * Update notification settings - Users table मा store गर्ने
     */
    public function updateNotification(Request $request)
    {
        $request->validate([
            'email_notifications' => 'boolean',
            'sms_notifications' => 'boolean',
            'booking_alerts' => 'boolean',
            'payment_alerts' => 'boolean',
        ]);

        $user = Auth::user();

        // Verify user is owner before proceeding
        $this->checkOwnerAccess($user->id);

        $user->update([
            'email_notifications' => $request->boolean('email_notifications'),
            'sms_notifications' => $request->boolean('sms_notifications'),
            'booking_alerts' => $request->boolean('booking_alerts'),
            'payment_alerts' => $request->boolean('payment_alerts'),
        ]);

        return redirect()->route('owner.settings.index')
            ->with('success', 'Notification settings updated successfully.');
    }

    /**
     * Update security settings
     */
    public function updateSecurity(Request $request)
    {
        $request->validate([
            'current_password' => 'required|current_password',
            'new_password' => 'required|string|min:8|confirmed',
        ]);

        $user = Auth::user();

        // Verify user is owner before proceeding
        $this->checkOwnerAccess($user->id);

        $user->update([
            'password' => Hash::make($request->new_password),
        ]);

        return redirect()->route('owner.settings.index')
            ->with('success', 'Password updated successfully.');
    }

    /**
     * Get payment settings from organizations table
     */
    private function getPaymentSettings($organization)
    {
        if (!$organization || !isset($organization->settings['payment'])) {
            return [
                'bank_name' => '',
                'account_number' => '',
                'account_holder' => '',
                'esewa_merchant_id' => '',
                'khalti_merchant_id' => '',
            ];
        }

        return $organization->settings['payment'];
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
            abort(403, 'तपाईंसँग यो होस्टल एक्सेस गर्ने अनुमति छैन');
        }
        return true;
    }

    /**
     * OWNER DATA SCOPING
     * Scope queries for owner-specific data
     */
    private function scopeForOwner($query, $ownerId)
    {
        return $query->whereHas('hostel', function ($q) use ($ownerId) {
            $q->where('owner_id', $ownerId);
        });
    }
}
