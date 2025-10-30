

<?php $__env->startSection('content'); ?>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h3 class="text-center mb-0">सेटिङ्हरू व्यवस्थापन</h3>
                </div>

                <div class="card-body">
                    <?php if(session('status')): ?>
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fas fa-check-circle"></i> <?php echo e(session('status')); ?>

                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    <?php endif; ?>

                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h5 class="mb-0">सेटिङ्हरूको सूची</h5>
                        <a href="<?php echo e(route('admin.settings.create')); ?>" class="btn btn-success btn-sm">
                            <i class="fas fa-plus"></i> नयाँ सेटिङ्ग थप्नुहोस्
                        </a>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-bordered table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th class="text-center" style="width: 5%;">क्रम संख्या</th>
                                    <th>कि (Key)</th>
                                    <th>मान</th>
                                    <th>समूह</th>
                                    <th>प्रकार</th>
                                    <th>विवरण</th>
                                    <th class="text-center" style="width: 20%;">क्रियाहरू</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__empty_1 = true; $__currentLoopData = $settings; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $group => $groupSettings): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <?php $__currentLoopData = $groupSettings; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $setting): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        <td class="text-center"><?php echo e($loop->iteration); ?></td>
                                        <td><strong><?php echo e($setting->key); ?></strong></td>
                                        <td><?php echo e(Str::limit($setting->value, 30)); ?></td>
                                        <td><?php echo e($setting->group); ?></td>
                                        <td><?php echo e($setting->type); ?></td>
                                        <td><?php echo e(Str::limit($setting->description, 50)); ?></td>
                                        <td class="text-center">
                                            <a href="<?php echo e(route('admin.settings.show', $setting->id)); ?>" class="btn btn-info btn-sm me-1" title="हेर्नुहोस्">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="<?php echo e(route('admin.settings.edit', $setting->id)); ?>" class="btn btn-primary btn-sm me-1" title="सम्पादन गर्नुहोस्">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="<?php echo e(route('admin.settings.destroy', $setting->id)); ?>" method="POST" class="d-inline">
                                                <?php echo csrf_field(); ?>
                                                <?php echo method_field('DELETE'); ?>
                                                <button type="submit" class="btn btn-danger btn-sm" title="मेटाउनुहोस्"
                                                    onclick="return confirm('के तपाइँ यो सेटिङ्ग मेटाउन निश्चित हुनुहुन्छ?')">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <tr>
                                    <td colspan="7" class="text-center py-4">
                                        <i class="fas fa-exclamation-circle text-muted me-2"></i>
                                        कुनै सेटिङ्हरू फेला परेनन्
                                    </td>
                                </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>

                    <?php if($settings->hasPages()): ?>
                    <div class="d-flex justify-content-center mt-4">
                        <?php echo e($settings->links()); ?>

                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\HostelHub\resources\views\admin\settings\index.blade.php ENDPATH**/ ?>