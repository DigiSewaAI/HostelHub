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
    /**
     * Show the login form.
     *
     * @return \Illuminate\View\View
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Handle login request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
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

            // Redirect to dashboard after successful login
            return $this->handleResponse($request, [
                'message' => 'Login successful'
            ], 200, 'dashboard');
        }

        $error = ['email' => 'Invalid credentials'];
        return $this->handleResponse($request, $error, 401, 'login');
    }

    /**
     * Show the registration form.
     *
     * @return \Illuminate\View\View
     */
    public function showRegistrationForm()
    {
        return view('auth.register_user');
    }

    /**
     * Handle registration request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
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

        // Redirect to dashboard after registration
        return $this->handleResponse($request, [
            'message' => 'Registration successful'
        ], 201, 'dashboard');
    }

    /**
     * Handle logout request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return $this->handleResponse($request, [
            'message' => 'Logged out successfully'
        ], 200, 'login');
    }

    /**
     * Show forgot password form.
     *
     * @return \Illuminate\View\View
     */
    public function showForgotPasswordForm()
    {
        return view('auth.forgot-password');
    }

    /**
     * Send password reset link.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
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

    /**
     * Show password reset form.
     *
     * @param  string  $token
     * @return \Illuminate\View\View
     */
    public function showResetPasswordForm($token)
    {
        return view('auth.reset-password', ['token' => $token]);
    }

    /**
     * Reset user password.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
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

    /**
     * Show password confirmation form.
     *
     * @return \Illuminate\View\View
     */
    public function showConfirmPasswordForm()
    {
        return view('auth.confirm-password');
    }

    /**
     * Confirm user password.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function confirmPassword(Request $request)
    {
        if (! Hash::check($request->password, $request->user()->password)) {
            return back()->withErrors([
                'password' => __('The provided password does not match our records.')
            ]);
        }

        $request->session()->passwordConfirmed();
        return redirect()->intended(route('dashboard'));
    }

    /**
     * Handle response formatting for JSON and web requests.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  array  $data
     * @param  int  $statusCode
     * @param  string|null  $route
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    private function handleResponse(Request $request, $data, $statusCode, $route = null)
    {
        if ($request->expectsJson()) {
            return response()->json($data, $statusCode);
        }

        if ($statusCode >= 400) {
            return back()->withErrors($data)->withInput();
        }

        // Handle success responses with proper redirect
        if ($route) {
            return redirect()->route($route)->with('success', $data['message'] ?? 'Operation completed successfully');
        }

        return redirect()->back()->with('success', $data['message'] ?? 'Operation completed successfully');
    }
}
