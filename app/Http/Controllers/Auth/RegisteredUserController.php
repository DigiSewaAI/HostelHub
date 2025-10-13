<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register_user');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        // Check if user already exists (created by owner without password)
        $existingUser = User::where('email', $request->email)->first();

        if ($existingUser) {
            // Update existing user with password and ensure student role
            $existingUser->update([
                'name' => $request->name,
                'password' => Hash::make($request->password),
                'role_id' => 3, // Student role
            ]);

            $user = $existingUser;

            // Ensure student role is assigned
            if (!$user->hasRole('student')) {
                $user->assignRole('student');
            }
        } else {
            // Create new student user
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'organization_id' => null, // Students don't have organization initially
                'role_id' => 3, // Student role
            ]);

            // Assign student role using Spatie Permission
            $user->assignRole('student');
        }

        event(new Registered($user));

        Auth::login($user);

        // ✅ FIXED: Redirect based on student's hostel connection status
        if ($user->hostel_id || $user->organization_id) {
            // Student is connected to a hostel - redirect to dashboard
            return redirect()->route('student.dashboard');
        } else {
            // New student without hostel - redirect to setup/welcome page
            return redirect()->route('student.welcome');
        }
    }
}
