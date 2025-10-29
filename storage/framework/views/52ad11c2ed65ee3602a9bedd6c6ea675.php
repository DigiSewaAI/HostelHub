

<?php $__env->startSection('title', 'पेन्डिङ बुकिंगहरू'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">पेन्डिङ बुकिंगहरू</h1>
        <span class="badge badge-primary badge-pill"><?php echo e($pendingBookings->total()); ?> पेन्डिङ</span>
    </div>

    <?php if($pendingBookings->count() > 0): ?>
    <div class="row">
        <?php $__currentLoopData = $pendingBookings; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $booking): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div class="col-lg-6 mb-4">
            <div class="card border-left-warning shadow h-100">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-warning">बुकिंग #<?php echo e($booking->id); ?></h6>
                    <small><?php echo e($booking->created_at->diffForHumans()); ?></small>
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
                            
                            <div class="btn-group" role="group">
                                <form action="<?php echo e(route('owner.bookings.approve', $booking->id)); ?>" method="POST" class="d-inline">
                                    <?php echo csrf_field(); ?>
                                    <button type="submit" class="btn btn-success btn-sm">
                                        <i class="fas fa-check"></i> स्वीकृत गर्नुहोस्
                                    </button>
                                </form>
                                <form action="<?php echo e(route('owner.bookings.reject', $booking->id)); ?>" method="POST" class="d-inline">
                                    <?php echo csrf_field(); ?>
                                    <button type="submit" class="btn btn-danger btn-sm ml-2">
                                        <i class="fas fa-times"></i> अस्वीकृत गर्नुहोस्
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>

    <div class="d-flex justify-content-center">
        <?php echo e($pendingBookings->links()); ?>

    </div>
    <?php else: ?>
    <div class="text-center py-5">
        <i class="fas fa-inbox fa-3x text-gray-300 mb-3"></i>
        <h4 class="text-gray-500">कुनै पेन्डिङ बुकिंग छैन</h4>
        <p class="text-muted">अहिले कुनै नयाँ बुकिंग अनुरोध छैन।</p>
    </div>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.owner', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\My Projects\HostelHub\resources\views\owner\bookings\pending.blade.php ENDPATH**/ ?>