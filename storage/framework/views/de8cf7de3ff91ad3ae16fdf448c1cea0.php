

<?php $__env->startSection('title', 'समीक्षा विवरण'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="card">
                <div class="card-header pb-0">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            <i class="fas fa-eye me-2"></i>समीक्षा विवरण
                        </h5>
                        <div>
                            <a href="<?php echo e(route('student.reviews.edit', $review->id)); ?>" class="btn btn-warning btn-sm">
                                <i class="fas fa-edit me-1"></i> सम्पादन गर्नुहोस्
                            </a>
                            <a href="<?php echo e(route('student.reviews.index')); ?>" class="btn btn-secondary btn-sm">
                                <i class="fas fa-list me-1"></i> सबै समीक्षाहरू
                            </a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="text-center">
                                <img src="<?php echo e($review->hostel->images->first()->image_path ?? asset('storage/images/default-hostel.jpg')); ?>" 
                                     alt="<?php echo e($review->hostel->name); ?>" 
                                     class="img-fluid rounded shadow" 
                                     style="max-height: 200px; object-fit: cover;">
                                <h5 class="mt-3"><?php echo e($review->hostel->name); ?></h5>
                                <p class="text-muted"><?php echo e($review->hostel->address); ?>, <?php echo e($review->hostel->city); ?></p>
                            </div>
                        </div>
                        <div class="col-md-8">
                            <div class="mb-4">
                                <h6 class="text-primary">रेटिंग:</h6>
                                <div class="d-flex align-items-center">
                                    <?php for($i = 1; $i <= 5; $i++): ?>
                                        <i class="fas fa-star fa-lg <?php echo e($i <= $review->rating ? 'text-warning' : 'text-secondary'); ?> me-1"></i>
                                    <?php endfor; ?>
                                    <span class="ms-2 fw-bold">(<?php echo e($review->rating); ?>/5)</span>
                                </div>
                            </div>

                            <div class="mb-4">
                                <h6 class="text-primary">समीक्षा:</h6>
                                <div class="border rounded p-3 bg-light">
                                    <?php echo e($review->comment); ?>

                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <h6 class="text-primary">स्थिति:</h6>
                                        <?php if($review->status == 'approved'): ?>
                                            <span class="badge bg-success fs-6">स्वीकृत</span>
                                        <?php elseif($review->status == 'pending'): ?>
                                            <span class="badge bg-warning fs-6">पेन्डिङ</span>
                                        <?php else: ?>
                                            <span class="badge bg-danger fs-6">अस्वीकृत</span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <h6 class="text-primary">समीक्षा मिति:</h6>
                                        <p class="mb-0"><?php echo e($review->created_at->format('Y-m-d H:i:s')); ?></p>
                                    </div>
                                </div>
                            </div>

                            <?php if($review->owner_reply): ?>
                                <div class="mt-4">
                                    <h6 class="text-success">
                                        <i class="fas fa-reply me-1"></i>होस्टेल मालिकको जवाफ:
                                    </h6>
                                    <div class="border-start border-success border-3 ps-3 bg-success bg-opacity-10 p-3 rounded">
                                        <p class="mb-0"><?php echo e($review->owner_reply); ?></p>
                                        <small class="text-muted">
                                            जवाफ मिति: <?php echo e($review->updated_at->format('Y-m-d')); ?>

                                        </small>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.student', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\HostelHub\resources\views\student\reviews\show.blade.php ENDPATH**/ ?>