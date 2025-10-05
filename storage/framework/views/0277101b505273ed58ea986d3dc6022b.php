<?php $__env->startSection('title', 'खानाको ट्र्याकिंग'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h3><i class="fas fa-clipboard-check me-2"></i> खानाको ट्र्याकिंग</h3>
                <?php if (\Illuminate\Support\Facades\Blade::check('role', 'admin|owner')): ?>
                <a href="<?php echo e(route('meals.create')); ?>" class="btn btn-primary">
                    <i class="fas fa-plus me-1"></i> थप्नुहोस्
                </a>
                <?php endif; ?>
            </div>

            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>विद्यार्थी</th>
                                    <th>होस्टल</th>
                                    <th>खानाको प्रकार</th>
                                    <th>मिति</th>
                                    <th>अवस्था</th>
                                    <?php if (\Illuminate\Support\Facades\Blade::check('role', 'admin|owner')): ?>
                                    <th>कार्य</th>
                                    <?php endif; ?>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__currentLoopData = $meals; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $meal): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td><?php echo e($meal->student->name ?? 'N/A'); ?></td>
                                    <td><?php echo e($meal->hostel->name ?? 'N/A'); ?></td>
                                    <td><span class="badge bg-primary"><?php echo e(ucfirst($meal->meal_type)); ?></span></td>
                                    <td><?php echo e($meal->date); ?></td>
                                    <td>
                                        <span class="badge bg-<?php echo e($meal->status == 'present' ? 'success' : 'danger'); ?>">
                                            <?php echo e($meal->status == 'present' ? 'उपस्थित' : 'अनुपस्थित'); ?>

                                        </span>
                                    </td>
                                    <?php if (\Illuminate\Support\Facades\Blade::check('role', 'admin|owner')): ?>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="<?php echo e(route('meals.edit', $meal)); ?>" class="btn btn-sm btn-warning">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="<?php echo e(route('meals.destroy', $meal)); ?>" method="POST" class="d-inline">
                                                <?php echo csrf_field(); ?>
                                                <?php echo method_field('DELETE'); ?>
                                                <button class="btn btn-sm btn-danger" onclick="return confirm('हटाउन निश्चित हुनुहुन्छ?')">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                    <?php endif; ?>
                                </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\My Projects\HostelHub\resources\views/admin/meals/index.blade.php ENDPATH**/ ?>