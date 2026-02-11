<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Student;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    public function create(): View
    {
        try {
            return view('auth.register_user');
        } catch (\Exception $e) {
            Log::error('Student registration form error: ' . $e->getMessage());
            return view('auth.register_user', ['error' => 'Temporary issue loading form']);
        }
    }

    public function store(Request $request): RedirectResponse
    {
        try {
            config(['database.default' => 'mysql']);

            $request->validate([
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'lowercase', 'email', 'max:255'],
                'password' => ['required', 'confirmed', Rules\Password::defaults()],
            ]);

            $existingUser = User::where('email', $request->email)->first();
            $existingStudent = Student::where('email', $request->email)->first();

            \Log::info('Registration attempt', [
                'email' => $request->email,
                'existing_user' => $existingUser ? 'yes' : 'no',
                'user_password' => $existingUser ? ($existingUser->password ? 'has_password' : 'NULL/empty') : 'no_user'
            ]);

            // 🚨 **FIXED: Check if user exists AND has password**
            if ($existingUser) {
                // Check if password exists and is not empty
                if ($existingUser->password && trim($existingUser->password) !== '') {
                    \Log::warning('User already registered with password', [
                        'email' => $request->email,
                        'user_id' => $existingUser->id
                    ]);

                    return back()->withErrors([
                        'email' => 'This email is already registered. Please login instead.'
                    ]);
                }

                // 🎯 **User exists but password is NULL/empty (owner-created)**
                \Log::info('Updating owner-created user with password', [
                    'email' => $request->email,
                    'user_id' => $existingUser->id
                ]);

                $existingUser->update([
                    'name' => $request->name,
                    'password' => Hash::make($request->password),
                    'email_verified_at' => now(),
                    'role_id' => 3,
                ]);

                // Assign student role
                if (!$existingUser->hasRole('student')) {
                    $existingUser->assignRole('student');
                }

                // Link to student record
                if ($existingStudent) {
                    $existingUser->student_id = $existingStudent->id;
                    $existingUser->hostel_id = $existingStudent->hostel_id;
                    $existingUser->save();

                    $existingStudent->user_id = $existingUser->id;
                    $existingStudent->save();

                    \Log::info('Linked user to student record', [
                        'user_id' => $existingUser->id,
                        'student_id' => $existingStudent->id
                    ]);
                }

                Auth::login($existingUser);

                \Log::info('Student registration completed (owner-created)', [
                    'user_id' => $existingUser->id,
                    'email' => $existingUser->email
                ]);

                return redirect()->route('student.dashboard')
                    ->with('success', 'Registration completed successfully! Welcome to HostelHub.');
            } else {
                // 🎯 **New registration**
                \Log::info('New user registration', ['email' => $request->email]);

                $request->validate([
                    'email' => ['unique:' . User::class],
                ]);

                $user = User::create([
                    'name' => $request->name,
                    'email' => $request->email,
                    'password' => Hash::make($request->password),
                    'organization_id' => null,
                    'role_id' => 3,
                    'email_verified_at' => now(),
                ]);

                $user->assignRole('student');

                // Link to existing student if any
                if ($existingStudent) {
                    $user->student_id = $existingStudent->id;
                    $user->hostel_id = $existingStudent->hostel_id;
                    $user->save();

                    $existingStudent->user_id = $user->id;
                    $existingStudent->save();

                    \Log::info('Linked new user to student record', [
                        'user_id' => $user->id,
                        'student_id' => $existingStudent->id
                    ]);
                }

                Auth::login($user);

                \Log::info('New student registered successfully', [
                    'user_id' => $user->id,
                    'email' => $user->email
                ]);

                return redirect()->route('student.dashboard')
                    ->with('success', 'Registration successful! Welcome to HostelHub.');
            }
        } catch (\Exception $e) {
            \Log::error('Student registration error: ' . $e->getMessage());

            return back()->withInput()
                ->withErrors(['error' => 'Registration failed: ' . $e->getMessage()]);
        }
    }
}
