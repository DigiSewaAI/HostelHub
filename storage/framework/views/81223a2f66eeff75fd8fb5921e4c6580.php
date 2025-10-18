<?php $__env->startSection('title', 'समीक्षाहरू'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3">समीक्षाहरू</h1>
        <!-- ✅ REMOVED: Create button since owners don't create reviews, they reply to them -->
    </div>

    <?php if(session('success')): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?php echo e(session('success')); ?>

            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>क्र.स.</th>
                            <th>विद्यार्थी</th>
                            <th>मूल्यांकन</th>
                            <th>समीक्षा</th>
                            <th>जवाफ</th>
                            <th>मिति</th>
                            <th>कार्यहरू</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $reviews; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $review): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td><?php echo e($loop->iteration); ?></td>
                            <td>
                                <?php if($review->student): ?>
                                    <?php echo e($review->student->name); ?>

                                <?php else: ?>
                                    <span class="text-muted">विद्यार्थी उपलब्ध छैन</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php for($i = 1; $i <= 5; $i++): ?>
                                    <?php if($i <= $review->rating): ?>
                                        <i class="fas fa-star text-warning"></i>
                                    <?php else: ?>
                                        <i class="far fa-star text-warning"></i>
                                    <?php endif; ?>
                                <?php endfor; ?>
                                <small class="text-muted">(<?php echo e($review->rating); ?>/5)</small>
                            </td>
                            <td><?php echo e(Str::limit($review->comment, 50)); ?></td>
                            <td>
                                <?php if($review->reply): ?>
                                    <span class="badge bg-success">जवाफ दिइयो</span>
                                <?php else: ?>
                                    <span class="badge bg-warning">जवाफ बाकी</span>
                                <?php endif; ?>
                            </td>
                            <td><?php echo e($review->created_at->format('Y-m-d')); ?></td>
                            <td>
                                <a href="<?php echo e(route('owner.reviews.show', $review)); ?>" class="btn btn-sm btn-info me-1" title="हेर्नुहोस्">
                                    <i class="fas fa-eye"></i>
                                </a>
                                
                                <?php if(!$review->reply): ?>
                                <a href="<?php echo e(route('owner.reviews.reply', $review)); ?>" class="btn btn-sm btn-primary me-1" title="जवाफ दिनुहोस्">
                                    <i class="fas fa-reply"></i>
                                </a>
                                <?php endif; ?>
                                
                                <form action="<?php echo e(route('owner.reviews.destroy', $review)); ?>" method="POST" class="d-inline">
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('DELETE'); ?>
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('के तपाईं यो समीक्षा हटाउन चाहनुहुन्छ?')" title="हटाउनुहोस्">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="7" class="text-center">कुनै समीक्षा फेला परेन</td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-center">
                <?php echo e($reviews->links()); ?>

            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.owner', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\My Projects\HostelHub\resources\views/owner/reviews/index.blade.php ENDPATH**/ ?>