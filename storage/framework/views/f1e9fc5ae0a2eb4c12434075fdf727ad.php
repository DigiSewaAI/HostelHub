

<?php $__env->startSection('content'); ?>
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h3 class="mb-0">होस्टलहबमा स्वागत छ, <?php echo e(Auth::user()->name); ?>!</h3>
                </div>
                
                <div class="card-body">
                    <div class="mb-4">
                        <div class="progress" style="height: 10px;">
                            <div class="progress-bar" role="progressbar" 
                                 style="width: <?php echo e(($onboarding->current_step - 1) * 20); ?>%;" 
                                 aria-valuenow="<?php echo e(($onboarding->current_step - 1) * 20); ?>" 
                                 aria-valuemin="0" 
                                 aria-valuemax="100"></div>
                        </div>
                        <div class="d-flex justify-content-between mt-2">
                            <span class="<?php echo e($onboarding->current_step >= 1 ? 'text-primary' : 'text-muted'); ?>">१. संस्था प्रोफाइल</span>
                            <span class="<?php echo e($onboarding->current_step >= 2 ? 'text-primary' : 'text-muted'); ?>">२. होस्टल सिर्जना गर्नुहोस्</span>
                            <span class="<?php echo e($onboarding->current_step >= 3 ? 'text-primary' : 'text-muted'); ?>">३. कोठा प्रकार</span>
                            <span class="<?php echo e($onboarding->current_step >= 4 ? 'text-primary' : 'text-muted'); ?>">४. शुल्क र भुक्तानी</span>
                            <span class="<?php echo e($onboarding->current_step >= 5 ? 'text-primary' : 'text-muted'); ?>">५. विद्यार्थी थप्नुहोस्</span>
                        </div>
                    </div>
                    
                    <?php if($onboarding->current_step == 1): ?>
                    <?php echo $__env->make('onboarding.steps.step1', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                    <?php elseif($onboarding->current_step == 2): ?>
                    <?php echo $__env->make('onboarding.steps.step2', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                    <?php elseif($onboarding->current_step == 3): ?>
                    <?php echo $__env->make('onboarding.steps.step3', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                    <?php elseif($onboarding->current_step == 4): ?>
                    <?php echo $__env->make('onboarding.steps.step4', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                    <?php elseif($onboarding->current_step == 5): ?>
                    <?php echo $__env->make('onboarding.steps.step5', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.backend', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\My Projects\HostelHub\resources\views\onboarding\index.blade.php ENDPATH**/ ?>