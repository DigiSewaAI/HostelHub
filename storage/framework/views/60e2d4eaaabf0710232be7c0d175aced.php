

<?php $__env->startSection('content'); ?>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
<script src="https://cdn.tailwindcss.com"></script>
<style>
    .error-message {
        color: #e53e3e;
        font-size: 0.875rem;
        margin-top: 0.25rem;
    }
</style>

<!-- Registration Form -->
<section class="py-16 bg-gray-50">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white rounded-xl shadow-lg p-8">
            <div class="text-center mb-8">
                <h1 class="text-3xl font-bold text-gray-900 mb-2">होस्टल संचालकको लागि साइन अप गर्नुहोस्</h1>
                <p class="text-gray-600">हाम्रो <?php echo e($plan->name ?? 'योजना'); ?> सुरु गर्नुहोस्</p>
                <div class="mt-4 bg-indigo-50 text-indigo-700 px-4 py-2 rounded-full inline-block">
                    ७ दिन निःशुल्क परीक्षण | कुनै पनि क्रेडिट कार्ड आवश्यक छैन
                </div>
            </div>
            
            <!-- Validation Errors -->
            <?php if($errors->any()): ?>
                <div class="mb-6 bg-red-50 text-red-700 p-4 rounded-lg">
                    <strong class="font-medium">सावधान!</strong>
                    <ul class="mt-1 list-disc list-inside">
                        <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <li><?php echo e($error); ?></li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </ul>
                </div>
            <?php endif; ?>
            
            <form method="POST" action="<?php echo e(route('register.organization.store')); ?>" autocomplete="off">
                <?php echo csrf_field(); ?>
                
                <!-- Hidden fields to prevent autofill -->
                <input type="text" name="fakeusernameremembered" autocomplete="username" style="display:none">
                <input type="password" name="fakepasswordremembered" autocomplete="new-password" style="display:none">
                
                <!-- Use plan slug instead of ID -->
                <input type="hidden" name="plan_slug" value="<?php echo e($plan->slug ?? ''); ?>">
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label for="organization_name" class="block text-sm font-medium text-gray-700 mb-1">होस्टल/संगठनको नाम</label>
                        <input type="text" id="organization_name" name="organization_name" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                            value="<?php echo e(old('organization_name')); ?>">
                        <?php $__errorArgs = ['organization_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <p class="error-message"><?php echo e($message); ?></p>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                    <div>
                        <label for="owner_name" class="block text-sm font-medium text-gray-700 mb-1">प्रबन्धकको नाम</label>
                        <input type="text" id="owner_name" name="owner_name" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                            value="<?php echo e(old('owner_name')); ?>">
                        <?php $__errorArgs = ['owner_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <p class="error-message"><?php echo e($message); ?></p>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                </div>
                
                <div class="mb-6">
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">इमेल ठेगाना</label>
                    <input type="email" id="email" name="email" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                        value="<?php echo e(old('email')); ?>">
                    <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <p class="error-message"><?php echo e($message); ?></p>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-1">पासवर्ड</label>
                        <input type="password" id="password" name="password" required autocomplete="new-password"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                        <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <p class="error-message"><?php echo e($message); ?></p>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">पासवर्ड पुष्टि गर्नुहोस्</label>
                        <input type="password" id="password_confirmation" name="password_confirmation" required autocomplete="new-password"
                            <input type="password" id="password_confirmation" name="password_confirmation" required autocomplete="new-password"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                    </div>
                </div>
                
                <div class="mb-8 p-4 bg-indigo-50 rounded-lg">
                    <h3 class="font-bold text-indigo-800 mb-2"><?php echo e($plan->name ?? 'योजना'); ?></h3>
                    <p class="text-indigo-700 mb-3">मूल्य: रु. <?php echo e(number_format($plan->price_month ?? 0)); ?>/महिना</p>
                    
                    <!-- योजनाका विशेषताहरू सही रूपमा देखाउने -->
                    <h4 class="font-semibold text-indigo-800 mb-2">विशेषताहरू:</h4>
                    <ul class="list-disc pl-5 space-y-1 text-indigo-700">
                        <?php if($plan): ?>
                            <?php
                                $features = [];
                                if(is_string($plan->features)) {
                                    $features = json_decode($plan->features, true) ?? [];
                                } elseif(is_array($plan->features)) {
                                    $features = $plan->features;
                                }
                            ?>
                            
                            <?php if(count($features) > 0): ?>
                                <?php $__currentLoopData = $features; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $feature): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <li><?php echo e($feature); ?></li>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <?php else: ?>
                                <li>विशेषताहरू उपलब्ध छैनन्</li>
                            <?php endif; ?>
                        <?php else: ?>
                            <li>योजना विवरण उपलब्ध छैन</li>
                        <?php endif; ?>
                    </ul>
                </div>
                
                <button type="submit" class="w-full bg-indigo-600 text-white py-3 px-6 rounded-lg font-medium hover:bg-indigo-700 transition-colors shadow-md">
                    <?php echo e($plan->name ?? 'योजना'); ?> सुरु गर्नुहोस्
                </button>
            </form>
            
            <div class="mt-6 text-center">
                <p class="text-gray-600">पहिले नै खाता छ? <a href="<?php echo e(route('login')); ?>" class="text-indigo-600 hover:underline font-medium">लगइन गर्नुहोस्</a></p>
            </div>
        </div>
    </div>
</section>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.frontend', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\My Projects\HostelHub\resources\views/auth/organization/register.blade.php ENDPATH**/ ?>