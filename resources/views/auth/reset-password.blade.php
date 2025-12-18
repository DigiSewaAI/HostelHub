<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100">
<div class="min-h-screen flex items-center justify-center">
    <div class="bg-white p-8 rounded-lg shadow-md w-full max-w-md">
        <h2 class="text-2xl font-bold mb-6 text-center">Reset Password</h2>

        @if ($errors->any())
            <div class="mb-4 p-3 bg-red-100 text-red-700 rounded">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- ✅ CORRECT ROUTE + METHOD -->
        <form method="POST" action="{{ route('password.reset.store') }}">
    @csrf

    <input type="hidden" name="token" value="{{ $token }}">
    <input type="hidden" name="email" value="{{ $email ?? old('email') }}">

    <div class="mb-4">
        <label>Email</label>
        <input type="email"
               value="{{ $email ?? old('email') }}"
               readonly
               class="w-full px-4 py-2 border bg-gray-100">
    </div>

    <div class="mb-4">
        <label>New Password</label>
        <input type="password" name="password" required class="w-full px-4 py-2 border">
    </div>

    <div class="mb-6">
        <label>Confirm Password</label>
        <input type="password" name="password_confirmation" required class="w-full px-4 py-2 border">
    </div>

    <button type="submit"
            class="w-full bg-blue-600 text-white py-2 rounded">
        Reset Password
    </button>
</form>


        <!-- Optional debug (remove in production) -->
        <div class="mt-4 p-2 bg-gray-100 text-xs text-gray-600 rounded">
            <p>Token: {{ isset($token) ? substr($token, 0, 20).'...' : 'N/A' }}</p>
            <p>Email: {{ $email ?? 'N/A' }}</p>
        </div>

    </div>
</div>
</body>
</html>
