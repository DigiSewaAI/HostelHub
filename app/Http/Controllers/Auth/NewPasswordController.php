<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class NewPasswordController extends Controller
{
    /**
     * Display the password reset view.
     */
    public function create(Request $request): View
    {
        // 🔥 DEBUG: Token लाई manually hash गर्ने
        $token = $request->route('token');
        $email = $request->email;

        \Log::info('Password reset page accessed', [
            'email' => $email,
            'raw_token' => $token,
            'hashed_token' => Hash::make($token)
        ]);

        return view('auth.reset-password', [
            'token' => $token,
            'email' => $email
        ]);
    }

    /**
     * Handle an incoming new password request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'token' => ['required'],
            'email' => ['required', 'email'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        \Log::info('Password reset attempt started', [
            'email' => $request->email,
            'token_raw' => $request->token
        ]);

        // 🔥 CRITICAL FIX: Central DB connection
        config(['database.default' => 'mysql']);

        // 🔥 MANUAL TOKEN VALIDATION - यही मुख्य फिक्स हो!
        $tokenRecord = DB::table('password_reset_tokens')
            ->where('email', $request->email)
            ->first();

        if (!$tokenRecord) {
            \Log::error('Token not found for email', ['email' => $request->email]);
            return back()->withInput($request->only('email'))
                ->withErrors(['email' => 'This password reset token is invalid.']);
        }

        // 🔥 यो छ महत्त्वपूर्ण: Hash::check गर्दा सही तरिका
        $isValid = Hash::check($request->token, $tokenRecord->token);

        \Log::info('Token validation result', [
            'email' => $request->email,
            'raw_token' => $request->token,
            'stored_hash' => $tokenRecord->token,
            'is_valid' => $isValid,
            'hash_check_result' => $isValid ? 'MATCH' : 'NO MATCH'
        ]);

        if (!$isValid) {
            // 🔥 ALTERNATIVE: कहिलेकाहीँ token URL encoded हुन्छ
            $decodedToken = urldecode($request->token);
            $isValid = Hash::check($decodedToken, $tokenRecord->token);

            \Log::info('After URL decode check', [
                'decoded_token' => $decodedToken,
                'is_valid_after_decode' => $isValid
            ]);

            if (!$isValid) {
                \Log::error('Token hash mismatch even after decode', [
                    'email' => $request->email,
                    'raw_token' => $request->token,
                    'decoded_token' => $decodedToken,
                    'stored_hash' => $tokenRecord->token
                ]);
                return back()->withInput($request->only('email'))
                    ->withErrors(['email' => 'This password reset token is invalid.']);
            }
        }

        // Check token expiration (60 minutes)
        $createdAt = \Carbon\Carbon::parse($tokenRecord->created_at);
        $minutesAgo = now()->diffInMinutes($createdAt);

        \Log::info('Token age check', [
            'created_at' => $createdAt,
            'now' => now(),
            'minutes_ago' => $minutesAgo,
            'is_expired' => $minutesAgo > 60
        ]);

        if ($minutesAgo > 60) {
            \Log::error('Token expired', [
                'email' => $request->email,
                'minutes_ago' => $minutesAgo,
                'created_at' => $tokenRecord->created_at
            ]);
            return back()->withInput($request->only('email'))
                ->withErrors(['email' => 'This password reset token has expired.']);
        }

        // 🔥 Find user
        $user = User::on('mysql')->where('email', $request->email)->first();

        if (!$user) {
            \Log::error('User not found in central DB', ['email' => $request->email]);
            return back()->withInput($request->only('email'))
                ->withErrors(['email' => 'We can\'t find a user with that email address.']);
        }

        // Update password
        $user->password = Hash::make($request->password);
        $user->remember_token = Str::random(60);
        $user->save();

        // Delete used token
        DB::table('password_reset_tokens')->where('email', $request->email)->delete();

        event(new PasswordReset($user));

        \Log::info('Password reset SUCCESSFUL', [
            'user_id' => $user->id,
            'email' => $user->email,
            'time' => now()
        ]);

        return redirect()->route('login')->with('status', 'Your password has been reset!');
    }
}
