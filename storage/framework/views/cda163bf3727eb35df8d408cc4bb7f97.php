

<?php $__env->startSection('title', 'समीक्षाको जवाफ दिनुहोस्'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-reply me-2"></i>
                        समीक्षाको जवाफ दिनुहोस्
                    </h5>
                </div>
                <div class="card-body">
                    <?php if($errors->any()): ?>
                        <div class="alert alert-danger">
                            <strong>त्रुटिहरू पत्ता लाग्यो:</strong>
                            <ul>
                                <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <li><?php echo e($error); ?></li>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </ul>
                        </div>
                    <?php endif; ?>

                    <!-- समीक्षाको विवरण -->
                    <div class="mb-4 p-3 bg-light rounded">
                        <h6 class="text-primary">समीक्षाको विवरण:</h6>
                        <div class="row">
                            <div class="col-md-6">
                                <p><strong>विद्यार्थी:</strong> <?php echo e($review->user->name); ?></p>
                                <p><strong>होस्टेल:</strong> <?php echo e($review->hostel->name); ?></p>
                            </div>
                            <div class="col-md-6">
                                <p><strong>रेटिंग:</strong> 
                                    <?php for($i = 1; $i <= 5; $i++): ?>
                                        <i class="fas fa-star <?php echo e($i <= $review->rating ? 'text-warning' : 'text-secondary'); ?>"></i>
                                    <?php endfor; ?>
                                    (<?php echo e($review->rating); ?>/5)
                                </p>
                                <p><strong>मिति:</strong> <?php echo e($review->created_at->format('Y-m-d')); ?></p>
                            </div>
                        </div>
                        <div class="mt-2">
                            <strong>टिप्पणी:</strong>
                            <p class="mb-0"><?php echo e($review->comment); ?></p>
                        </div>
                    </div>

                    <!-- जवाफ दिने फारम -->
                    <form action="<?php echo e(route('owner.reviews.update', $review->id)); ?>" method="POST">
                        <?php echo csrf_field(); ?>
                        <?php echo method_field('PUT'); ?>
                        
                        <input type="hidden" name="action" value="reply">

                        <div class="mb-3">
                            <label for="owner_reply" class="form-label">
                                <strong>तपाईंको जवाफ:</strong>
                            </label>
                            <textarea name="owner_reply" id="owner_reply" rows="5" 
                                      class="form-control <?php $__errorArgs = ['owner_reply'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                      placeholder="विद्यार्थीको समीक्षाको लागि जवाफ दिनुहोस्..."><?php echo e(old('owner_reply', $review->owner_reply)); ?></textarea>
                            <?php $__errorArgs = ['owner_reply'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="invalid-feedback"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        <div class="mb-3">
                            <label for="status" class="form-label">
                                <strong>स्थिति:</strong>
                            </label>
                            <select name="status" id="status" class="form-select">
                                <option value="pending" <?php echo e($review->status == 'pending' ? 'selected' : ''); ?>>पेन्डिङ</option>
                                <option value="approved" <?php echo e($review->status == 'approved' ? 'selected' : ''); ?>>स्वीकृत</option>
                                <option value="rejected" <?php echo e($review->status == 'rejected' ? 'selected' : ''); ?>>अस्वीकृत</option>
                            </select>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="<?php echo e(route('owner.reviews.index')); ?>" class="btn btn-secondary">
                                <i class="fas fa-arrow-left me-1"></i> पछि जानुहोस्
                            </a>
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-check me-1"></i> जवाफ सेभ गर्नुहोस्
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // टेक्स्ट एरिया स्वतः विस्तार गर्ने
        const textarea = document.getElementById('owner_reply');
        textarea.addEventListener('input', function() {
            this.style.height = 'auto';
            this.style.height = (this.scrollHeight) + 'px';
        });
    });
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('owner.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\HostelHub\resources\views\owner\reviews\reply.blade.php ENDPATH**/ ?>