

<?php $__env->startSection('title', 'अस्वीकृत बुकिंगहरू'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">अस्वीकृत बुकिंगहरू</h1>
        <span class="badge badge-danger badge-pill"><?php echo e($rejectedBookings->total()); ?> अस्वीकृत</span>
    </div>

    <?php if($rejectedBookings->count() > 0): ?>
    <div class="row">
        <?php $__currentLoopData = $rejectedBookings; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $booking): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div class="col-lg-6 mb-4">
            <div class="card border-left-danger shadow h-100">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-danger">बुकिंग #<?php echo e($booking->id); ?></h6>
                    <small><?php echo e($booking->updated_at->diffForHumans()); ?></small>
                </div>
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                विद्यार्थी: <?php echo e($booking->student->user->name); ?>

                            </div>
                            <div class="text-xs text-muted mb-1">
                                कोठा: <?php echo e($booking->room->room_number); ?> (<?php echo e($booking->room->hostel->name); ?>)
                            </div>
                            <div class="text-xs text-muted mb-1">
                                मिति: <?php echo e($booking->check_in_date->format('Y-m-d')); ?> बाट <?php echo e($booking->check_out_date->format('Y-m-d')); ?> सम्म
                            </div>
                            <div class="text-xs text-muted mb-3">
                                उद्देश्य: <?php echo e($booking->purpose); ?>

                            </div>
                            <?php if($booking->rejection_reason): ?>
                            <div class="text-xs font-weight-bold text-danger mb-2">
                                अस्वीकृतको कारण: <?php echo e($booking->rejection_reason); ?>

                            </div>
                            <?php endif; ?>
                            <div class="text-xs text-muted">
                                अस्वीकृत मिति: <?php echo e($booking->updated_at->format('Y-m-d H:i')); ?>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>

    <div class="d-flex justify-content-center">
        <?php echo e($rejectedBookings->links()); ?>

    </div>
    <?php else: ?>
    <div class="text-center py-5">
        <i class="fas fa-times-circle fa-3x text-gray-300 mb-3"></i>
        <h4 class="text-gray-500">कुनै अस्वीकृत बुकिंग छैन</h4>
        <p class="text-muted">अहिले सम्म कुनै बुकिंग अस्वीकृत भएको छैन।</p>
    </div>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.owner', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\My Projects\HostelHub\resources\views\owner\bookings\rejected.blade.php ENDPATH**/ ?>