<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="min-h-screen flex items-center justify-center">
        <div class="bg-white p-8 rounded-lg shadow-md w-full max-w-md">
            <h2 class="text-2xl font-bold mb-2 text-center">Forgot Password?</h2>
            <p class="mb-6 text-gray-600 text-center">Enter your email to reset your password</p>

            <?php if(session('status')): ?>
                <div class="mb-4 p-3 bg-green-100 text-green-700 rounded">
                    <?php echo e(session('status')); ?>

                </div>
            <?php endif; ?>

            <form method="POST" action="<?php echo e(route('password.email')); ?>">
                <?php echo csrf_field(); ?>

                <div class="mb-4">
                    <label for="email" class="block text-gray-700 mb-2">Email Address</label>
                    <input
                        type="email"
                        name="email"
                        id="email"
                        value="<?php echo e(old('email')); ?>"
                        class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                        required
                        autofocus
                    >
                    <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <p class="text-red-500 text-sm mt-1"><?php echo e($message); ?></p>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <div class="mb-4">
                    <button type="submit" class="w-full bg-blue-500 text-white py-2 rounded-lg hover:bg-blue-600 transition">
                        Send Reset Link
                    </button>
                </div>

                <div class="text-center">
                    <a href="<?php echo e(route('login')); ?>" class="text-blue-500 hover:underline">
                        Back to Login
                    </a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
<?php /**PATH C:\laragon\www\HostelHub\resources\views\auth\forgot-password.blade.php ENDPATH**/ ?>