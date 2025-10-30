<?php $__env->startSection('title', 'खाना थप्नुहोस्'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="mb-0"><i class="fas fa-utensils me-2"></i> नयाँ खाना थप्नुहोस्</h3>
                </div>
                <div class="card-body">
                    <form action="<?php echo e(route('admin.meals.store')); ?>" method="POST">
                        <?php echo csrf_field(); ?>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">विद्यार्थी</label>
                                <select name="student_id" class="form-control" required>
                                    <option value="">विद्यार्थी छान्नुहोस्</option>
                                    <?php $__currentLoopData = $students; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $student): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($student->id); ?>">
                                            <?php echo e($student->name); ?> (<?php echo e($student->hostel->name ?? 'N/A'); ?>)
                                        </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">होस्टल</label>
                                <select name="hostel_id" class="form-control" required>
                                    <option value="">होस्टल छान्नुहोस्</option>
                                    <?php $__currentLoopData = $hostels; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $hostel): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($hostel->id); ?>"><?php echo e($hostel->name); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">खानाको प्रकार</label>
                                <select name="meal_type" class="form-control" required>
                                    <option value="breakfast">बिहानको खाना</option>
                                    <option value="lunch">दिउँसोको खाना</option>
                                    <option value="dinner">बेलुकाको खाना</option>
                                </select>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">मिति</label>
                                <input type="date" name="date" class="form-control" value="<?php echo e(now()->format('Y-m-d')); ?>" required>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">अवस्था</label>
                                <select name="status" class="form-control">
                                    <option value="present">उपस्थित</option>
                                    <option value="absent">अनुपस्थित</option>
                                </select>
                            </div>
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-1"></i> सुरक्षित गर्नुहोस्
                            </button>
                            <a href="<?php echo e(route('admin.meals.index')); ?>" class="btn btn-secondary">
                                <i class="fas fa-times me-1"></i> रद्द गर्नुहोस्
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\HostelHub\resources\views\admin\meals\edit.blade.php ENDPATH**/ ?>