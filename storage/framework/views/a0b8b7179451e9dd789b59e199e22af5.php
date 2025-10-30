

<?php $__env->startSection('content'); ?>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="text-center">सेटिङ्ग विवरण</h3>
                </div>

                <div class="card-body">
                    <div class="mb-3 row">
                        <label class="col-sm-4 col-form-label fw-bold">सेटिङ्ग कि (Key):</label>
                        <div class="col-sm-8">
                            <p class="form-control-plaintext"><?php echo e($setting->key); ?></p>
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label class="col-sm-4 col-form-label fw-bold">मान:</label>
                        <div class="col-sm-8">
                            <p class="form-control-plaintext"><?php echo e($setting->value); ?></p>
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label class="col-sm-4 col-form-label fw-bold">समूह:</label>
                        <div class="col-sm-8">
                            <p class="form-control-plaintext"><?php echo e($setting->group); ?></p>
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label class="col-sm-4 col-form-label fw-bold">प्रकार:</label>
                        <div class="col-sm-8">
                            <p class="form-control-plaintext"><?php echo e($setting->type); ?></p>
                        </div>
                    </div>

                    <?php if($setting->options): ?>
                    <div class="mb-3 row">
                        <label class="col-sm-4 col-form-label fw-bold">विकल्पहरू:</label>
                        <div class="col-sm-8">
                            <pre class="form-control-plaintext bg-light p-2"><?php echo e($setting->options); ?></pre>
                        </div>
                    </div>
                    <?php endif; ?>

                    <div class="mb-3 row">
                        <label class="col-sm-4 col-form-label fw-bold">विवरण:</label>
                        <div class="col-sm-8">
                            <p class="form-control-plaintext"><?php echo e($setting->description ?? 'कुनै विवरण उपलब्ध छैन'); ?></p>
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label class="col-sm-4 col-form-label fw-bold">सिस्टम सेटिङ्ग:</label>
                        <div class="col-sm-8">
                            <p class="form-control-plaintext"><?php echo e($setting->is_system ? 'हो' : 'होइन'); ?></p>
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label class="col-sm-4 col-form-label fw-bold">सिर्जना मिति:</label>
                        <div class="col-sm-8">
                            <p class="form-control-plaintext"><?php echo e($setting->created_at->format('Y-m-d H:i:s')); ?></p>
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label class="col-sm-4 col-form-label fw-bold">अद्यावधिक मिति:</label>
                        <div class="col-sm-8">
                            <p class="form-control-plaintext"><?php echo e($setting->updated_at->format('Y-m-d H:i:s')); ?></p>
                        </div>
                    </div>

                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                        <a href="<?php echo e(route('admin.settings.edit', $setting->id)); ?>" class="btn btn-primary me-md-2">
                            <i class="fas fa-edit"></i> सम्पादन गर्नुहोस्
                        </a>
                        <a href="<?php echo e(route('admin.settings.index')); ?>" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> पछाडि जानुहोस्
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\HostelHub\resources\views\admin\settings\show.blade.php ENDPATH**/ ?>