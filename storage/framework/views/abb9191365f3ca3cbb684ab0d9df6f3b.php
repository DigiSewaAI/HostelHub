<?php $__env->startSection('title', 'समीक्षाहरू'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3">समीक्षाहरू</h1>
        <a href="<?php echo e(route('admin.reviews.create')); ?>" class="btn btn-primary">
            <i class="fas fa-plus me-1"></i> नयाँ समीक्षा
        </a>
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
                            <th>नाम</th>
                            <th>पद</th>
                            <th>प्रकार</th>
                            <th>मूल्याङ्कन</th>
                            <th>स्थिति</th>
                            <th>कार्यहरू</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $reviews; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $review): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td><?php echo e($loop->iteration); ?></td>
                            <td><?php echo e($review->name); ?></td>
                            <td><?php echo e($review->position); ?></td>
                            <td>
                                <?php if($review->type == 'testimonial'): ?>
                                    <span class="badge bg-success">प्रशंसापत्र</span>
                                <?php elseif($review->type == 'review'): ?>
                                    <span class="badge bg-primary">समीक्षा</span>
                                <?php else: ?>
                                    <span class="badge bg-info">प्रतिक्रिया</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if($review->rating): ?>
                                    <?php for($i = 1; $i <= 5; $i++): ?>
                                        <span style="color: <?php echo e($i <= $review->rating ? '#fbbf24' : '#d1d5db'); ?>;">★</span>
                                    <?php endfor; ?>
                                    (<?php echo e($review->rating); ?>/5)
                                <?php else: ?>
                                    <span class="text-muted">नभएको</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if($review->status == 'active'): ?>
                                    <span class="badge bg-success">सक्रिय</span>
                                <?php else: ?>
                                    <span class="badge bg-secondary">निष्क्रिय</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <a href="<?php echo e(route('admin.reviews.show', $review)); ?>" class="btn btn-sm btn-info me-1" title="हेर्नुहोस्">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="<?php echo e(route('admin.reviews.edit', $review)); ?>" class="btn btn-sm btn-primary me-1" title="सम्पादन गर्नुहोस्">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="<?php echo e(route('admin.reviews.destroy', $review)); ?>" method="POST" class="d-inline">
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
<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\HostelHub\resources\views\reviews\index.blade.php ENDPATH**/ ?>