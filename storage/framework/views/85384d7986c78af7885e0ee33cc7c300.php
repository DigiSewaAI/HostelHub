<!DOCTYPE html>
<html lang="ne">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>लगइन</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="min-h-screen flex items-center justify-center">
        <div class="bg-white p-8 rounded-lg shadow-md w-full max-w-md">
            <h2 class="text-2xl font-bold mb-6 text-center">लगइन</h2>

            <?php if(session('status')): ?>
                <script>
                    window.location.href = <?php echo json_encode(auth()->user()->isAdmin() ? 
                        route('admin.dashboard') : 
                        (auth()->user()->isHostelManager() ? 
                        route('owner.dashboard') : 
                        route('student.dashboard')), 15, 512) ?>;
                </script>
            <?php endif; ?>

            <?php if($errors->any()): ?>
                <div class="mb-4 p-3 bg-red-100 text-red-700 rounded">
                    <ul>
                        <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <li><?php echo e($error); ?></li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </ul>
                </div>
            <?php endif; ?>

            <form method="POST" action="<?php echo e(route('login')); ?>" autocomplete="off">
                <?php echo csrf_field(); ?>
                
                <input type="text" name="fakeusernameremembered" autocomplete="username" style="display:none">
                <input type="password" name="fakepasswordremembered" autocomplete="current-password" style="display:none">

                <div class="mb-4">
                    <label for="email" class="block text-gray-700 mb-2">इमेल</label>
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
                    <label for="password" class="block text-gray-700 mb-2">पासवर्ड</label>
                    <input
                        type="password"
                        name="password"
                        id="password"
                        class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                        required
                        autocomplete="current-password"
                    >
                    <?php $__errorArgs = ['password'];
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

                <div class="mb-4 flex items-center">
                    <input
                        type="checkbox"
                        name="remember"
                        id="remember"
                        class="mr-2"
                    >
                    <label for="remember" class="text-gray-700">मलाई सम्झनुहोस्</label>
                </div>

                <div class="mb-4">
                    <button type="submit" class="w-full bg-blue-500 text-white py-2 rounded-lg hover:bg-blue-600 transition">
                        लगइन
                    </button>
                </div>

                <div class="text-center">
                    <a href="<?php echo e(route('password.request')); ?>" class="text-blue-500 hover:underline">
                        पासवर्ड बिर्सनुभयो?
                    </a>
                    <div class="mt-3">
                        <a href="<?php echo e(route('register')); ?>" class="text-blue-500 hover:underline">
                            नयाँ खाता खोल्नुहोस्
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</body>
</html><?php /**PATH C:\laragon\www\HostelHub\resources\views\auth\login.blade.php ENDPATH**/ ?>