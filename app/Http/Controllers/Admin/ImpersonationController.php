<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class ImpersonationController extends Controller
{
    public function impersonate($userId)
    {
        $originalUser = Auth::user();
        $impersonatedUser = User::findOrFail($userId);

        // सुनिश्चित गर्नुहोस् कि तपाईंले impersonate गर्न खोजिरहनुभएको user hostel_manager वा student हो
        if (!$impersonatedUser->hasRole('hostel_manager') && !$impersonatedUser->hasRole('student')) {
            abort(403, 'तपाईंले यो प्रयोगकर्तालाई impersonate गर्न सक्नुहुन्न');
        }

        // मूल user को ID session मा सुरक्षित गर्नुहोस्
        Session::put('original_user_id', $originalUser->id);

        // नयाँ user को रूपमा impersonate गर्नुहोस्
        Auth::loginUsingId($impersonatedUser->id);

        return redirect()->route($impersonatedUser->hasRole('hostel_manager') ? 'owner.dashboard' : 'student.dashboard')
            ->with('success', 'तपाईंले अब ' . $impersonatedUser->name . ' को रूपमा काम गर्दै हुनुहुन्छ');
    }

    public function leave()
    {
        $originalUserId = Session::get('original_user_id');

        if (!$originalUserId) {
            return redirect()->route('admin.dashboard');
        }

        // मूल user मा फर्कनुहोस्
        Auth::loginUsingId($originalUserId);
        Session::forget('original_user_id');

        return redirect()->route('admin.dashboard')
            ->with('success', 'तपाईं मूल खातामा फर्कनुभयो');
    }
}
