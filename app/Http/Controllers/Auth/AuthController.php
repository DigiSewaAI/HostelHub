<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Events\PasswordReset;

class AuthController extends Controller
{
    // Show Login Form
    public function showLoginForm()
    {
        return view('auth.login');
    }

    // Handle Login Request
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->handleResponse($request, $validator->errors(), 422, 'login');
        }

        $credentials = $request->only('email', 'password');
        $remember = $request->filled('remember');

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();
            return $this->handleResponse($request, [
                'message' => 'Login successful'
            ], 200, 'home'); // Redirect to home after login
        }

        $error = ['email' => 'Invalid credentials'];
        return $this->handleResponse($request, $error, 401, 'login');
    }

    // Show Registration Form
    public function showRegistrationForm()
    {
        return view('auth.register_user');
    }

    // Handle Registration Request
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return $this->handleResponse($request, $validator->errors(), 422, 'register');
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'email_verified_at' => now(), // Mark as verified immediately
        ]);

        event(new Registered($user));
        Auth::login($user);

        return $this->handleResponse($request, [
            'message' => 'Registration successful'
        ], 201, 'home'); // Redirect directly to home
    }

    // Handle Logout
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return $this->handleResponse($request, [
            'message' => 'Logged out successfully'
        ], 200, 'home');
    }

    // Password Reset Methods
    public function showForgotPasswordForm()
    {
        return view('auth.forgot-password');
    }

    public function sendResetLink(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $status = Password::sendResetLink(
            $request->only('email')
        );

        return $status === Password::RESET_LINK_SENT
            ? $this->handleResponse($request, [
                'message' => __('We have emailed your password reset link!')
            ], 200, 'login')
            : $this->handleResponse($request, [
                'email' => [__('We couldn\'t find a user with that email address.')]
            ], 422, 'password.request');
    }

    public function showResetPasswordForm($token)
    {
        return view('auth.reset-password', ['token' => $token]);
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:8|confirmed',
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password)
                ])->setRememberToken(Str::random(60));

                $user->save();
                event(new PasswordReset($user));
            }
        );

        return $status === Password::PASSWORD_RESET
            ? $this->handleResponse($request, [
                'message' => __('Your password has been reset!')
            ], 200, 'login')
            : $this->handleResponse($request, [
                'email' => [__('We couldn\'t reset your password. Please try again.')]
            ], 422, 'password.reset');
    }

    // Password Confirmation Methods
    public function showConfirmPasswordForm()
    {
        return view('auth.confirm-password');
    }

    public function confirmPassword(Request $request)
    {
        if (! Hash::check($request->password, $request->user()->password)) {
            return back()->withErrors([
                'password' => ['The provided password does not match our records.']
            ]);
        }

        $request->session()->passwordConfirmed();
        return redirect()->intended(route('dashboard'));
    }

    // Response Handler - UPDATED WITH SUCCESS MESSAGES
    private function handleResponse(Request $request, $data, $statusCode, $route = null)
    {
        if ($request->expectsJson()) {
            return response()->json($data, $statusCode);
        }

        if ($statusCode >= 400) {
            return back()->withErrors($data)->withInput();
        }

        // Add success message for web requests (200-299 status codes)
        if ($statusCode >= 200 && $statusCode < 300) {
            return redirect()->route($route)->with('success', $data['message'] ?? 'Operation completed successfully');
        }

        return redirect()->route($route);
    }
}
