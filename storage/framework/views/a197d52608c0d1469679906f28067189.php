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
                    <form action="<?php echo e(route('meals.store')); ?>" method="POST">
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

                            <!-- बाँकी form fields -->
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-1"></i> सुरक्षित गर्नुहोस्
                            </button>
                            <a href="<?php echo e(route('meals.index')); ?>" class="btn btn-secondary">
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
<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\HostelHub\resources\views\admin\meals\create.blade.php ENDPATH**/ ?>