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
use Illuminate\Support\Facades\Log; // ✅ ADD THIS
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        try {
            return view('auth.register_user');
        } catch (\Exception $e) {
            Log::error('Student registration form error: ' . $e->getMessage());
            // Emergency fallback - return simple form
            return view('auth.register_user', ['error' => 'Temporary issue loading form']);
        }
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        try {
            // Set central database connection (Railway fix)
            config(['database.default' => 'mysql']);

            $request->validate([
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
                'password' => ['required', 'confirmed', Rules\Password::defaults()],
            ]);

            // Check if user already exists (created by owner without password)
            $existingUser = User::where('email', $request->email)->first();

            // ✅ Check if student record exists for this email (created by hostel manager)
            $existingStudent = Student::where('email', $request->email)->first();

            if ($existingUser) {
                // Update existing user with password and ensure student role
                $existingUser->update([
                    'name' => $request->name,
                    'password' => Hash::make($request->password),
                    'role_id' => 3, // Student role
                    'email_verified_at' => now(), // ✅ Auto verify email (Temporary fix)
                ]);

                $user = $existingUser;

                // ✅ Link to existing student record if found
                if ($existingStudent) {
                    $user->student_id = $existingStudent->id;
                    $user->hostel_id = $existingStudent->hostel_id;
                    $user->save();

                    // Also update student record with user_id
                    $existingStudent->user_id = $user->id;
                    $existingStudent->save();
                }

                // Ensure student role is assigned
                if (!$user->hasRole('student')) {
                    $user->assignRole('student');
                }
            } else {
                // Create new student user with auto-verified email
                $user = User::create([
                    'name' => $request->name,
                    'email' => $request->email,
                    'password' => Hash::make($request->password),
                    'organization_id' => null, // Students don't have organization initially
                    'role_id' => 3, // Student role
                    'email_verified_at' => now(), // ✅ Auto verify email (Temporary fix)
                ]);

                // ✅ Link to existing student record if found
                if ($existingStudent) {
                    $user->student_id = $existingStudent->id;
                    $user->hostel_id = $existingStudent->hostel_id;
                    $user->save();

                    // Also update student record with user_id
                    $existingStudent->user_id = $user->id;
                    $existingStudent->save();
                }

                // Assign student role using Spatie Permission
                $user->assignRole('student');
            }

            // ✅ COMMENTED OUT: Temporarily disable Registered event to avoid email verification issues
            // event(new Registered($user));

            Auth::login($user);

            Log::info('User registered successfully', [
                'user_id' => $user->id,
                'email' => $user->email,
                'hostel_id' => $user->hostel_id,
                'is_student' => $user->isStudent()
            ]);

            // ✅ FIXED: Simplified redirect logic - check only hostel_id for students
            if ($user->isStudent() && $user->hostel_id) {
                // Student is connected to a hostel - redirect to dashboard
                return redirect()->route('student.dashboard')
                    ->with('success', 'Registration successful! Welcome to HostelHub.');
            } else {
                // New student without hostel - redirect to setup/welcome page
                return redirect()->route('student.welcome')
                    ->with('success', 'Registration successful! Please complete your profile.');
            }
        } catch (\Exception $e) {
            Log::error('Student registration error: ' . $e->getMessage());
            Log::error('Registration error trace: ' . $e->getTraceAsString());

            // Check if it's a database connection error
            if (str_contains($e->getMessage(), 'SQLSTATE')) {
                return back()->withInput()
                    ->withErrors([
                        'error' => 'Database connection issue. Please try again or contact support.'
                    ]);
            }

            return back()->withInput()
                ->withErrors(['error' => 'Registration failed. Please try again.']);
        }
    }
}
