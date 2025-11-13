<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;

class ImpersonationController extends Controller
{
    /**
     * Impersonate a user - SECURITY FIXED: Authorization Bypass Prevention
     */
    public function impersonate($userId)
    {
        $originalUser = Auth::user();

        // CRITICAL SECURITY FIX: Only allow admins to impersonate
        if (!$originalUser->hasRole('admin')) {
            // Log suspicious activity
            Log::warning('Unauthorized impersonation attempt', [
                'user_id' => $originalUser->id,
                'attempted_impersonation_target' => $userId,
                'ip' => request()->ip()
            ]);
            abort(403, 'तपाईंसँग यो कार्य गर्ने अनुमति छैन');
        }

        $impersonatedUser = User::findOrFail($userId);

        // सुनिश्चित गर्नुहोस् कि तपाईंले impersonate गर्न खोजिरहनुभएको user hostel_manager वा student हो
        if (!$impersonatedUser->hasRole('hostel_manager') && !$impersonatedUser->hasRole('student')) {
            // Log attempted impersonation of unauthorized roles
            Log::warning('Attempt to impersonate unauthorized role', [
                'admin_id' => $originalUser->id,
                'target_user_id' => $userId,
                'target_roles' => $impersonatedUser->getRoleNames()->toArray()
            ]);
            abort(403, 'तपाईंले यो प्रयोगकर्तालाई impersonate गर्न सक्नुहुन्न');
        }

        // SECURITY FIX: Prevent self-impersonation
        if ($originalUser->id == $impersonatedUser->id) {
            abort(403, 'तपाईं आफैलाई impersonate गर्न सक्नुहुन्न');
        }

        // SECURITY FIX: Log the impersonation activity for audit trail
        Log::info('Admin started impersonation', [
            'admin_id' => $originalUser->id,
            'admin_name' => $originalUser->name,
            'impersonated_user_id' => $impersonatedUser->id,
            'impersonated_user_name' => $impersonatedUser->name,
            'impersonated_user_roles' => $impersonatedUser->getRoleNames()->toArray(),
            'ip_address' => request()->ip(),
            'timestamp' => now()->toISOString()
        ]);

        // मूल user को ID session मा सुरक्षित गर्नुहोस्
        Session::put('original_user_id', $originalUser->id);
        Session::put('original_user_name', $originalUser->name);
        Session::put('impersonation_start_time', now()->toISOString());

        // नयाँ user को रूपमा impersonate गर्नुहोस्
        Auth::loginUsingId($impersonatedUser->id);

        return redirect()->route($impersonatedUser->hasRole('hostel_manager') ? 'owner.dashboard' : 'student.dashboard')
            ->with('success', 'तपाईंले अब ' . $impersonatedUser->name . ' को रूपमा काम गर्दै हुनुहुन्छ');
    }

    /**
     * Leave impersonation - SECURITY FIXED: Enhanced Safety Checks
     */
    public function leave()
    {
        $originalUserId = Session::get('original_user_id');

        // SECURITY FIX: Enhanced safety check for suspicious activity
        if (!$originalUserId) {
            // Log suspicious leave attempt
            Log::warning('Impersonation leave attempted without original user ID', [
                'current_user_id' => Auth::id(),
                'current_user_name' => Auth::user()->name,
                'ip' => request()->ip(),
                'session_data' => Session::all()
            ]);

            // Clear any potential session issues and redirect to admin dashboard
            Auth::logout();
            Session::flush();
            return redirect()->route('login')->with('error', 'Session त्रुटि। कृपया पुनः लगइन गर्नुहोस्।');
        }

        // SECURITY FIX: Verify the original user still exists and is an admin
        $originalUser = User::find($originalUserId);
        if (!$originalUser) {
            Log::error('Original user not found during impersonation leave', [
                'original_user_id' => $originalUserId,
                'current_user_id' => Auth::id()
            ]);

            Auth::logout();
            Session::flush();
            return redirect()->route('login')->with('error', 'मूल प्रयोगकर्ता फेला परेन। कृपया पुनः लगइन गर्नुहोस्।');
        }

        // Get current impersonated user info for logging
        $impersonatedUser = Auth::user();
        $impersonationStartTime = Session::get('impersonation_start_time');

        // SECURITY FIX: Log the end of impersonation for audit trail
        Log::info('Admin ended impersonation', [
            'admin_id' => $originalUser->id,
            'admin_name' => $originalUser->name,
            'impersonated_user_id' => $impersonatedUser->id,
            'impersonated_user_name' => $impersonatedUser->name,
            'impersonation_duration' => $impersonationStartTime ? now()->diffInMinutes($impersonationStartTime) . ' minutes' : 'unknown',
            'ip_address' => request()->ip(),
            'timestamp' => now()->toISOString()
        ]);

        // मूल user मा फर्कनुहोस्
        Auth::loginUsingId($originalUserId);

        // SECURITY FIX: Clear all impersonation session data
        Session::forget('original_user_id');
        Session::forget('original_user_name');
        Session::forget('impersonation_start_time');

        return redirect()->route('admin.dashboard')
            ->with('success', 'तपाईं मूल खातामा फर्कनुभयो');
    }

    /**
     * SECURITY FIX: Check if current session is impersonated
     */
    public function isImpersonating()
    {
        return response()->json([
            'impersonating' => Session::has('original_user_id'),
            'original_user_id' => Session::get('original_user_id'),
            'original_user_name' => Session::get('original_user_name')
        ]);
    }

    /**
     * SECURITY FIX: Get impersonation status for UI
     */
    public function getImpersonationStatus()
    {
        if (!Session::has('original_user_id')) {
            return null;
        }

        return [
            'is_impersonating' => true,
            'original_user' => [
                'id' => Session::get('original_user_id'),
                'name' => Session::get('original_user_name')
            ],
            'current_user' => [
                'id' => Auth::id(),
                'name' => Auth::user()->name
            ],
            'started_at' => Session::get('impersonation_start_time')
        ];
    }
}
