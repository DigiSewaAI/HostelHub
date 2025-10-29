

<?php $__env->startSection('title', 'मेरा बुकिंगहरू'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">मेरा बुकिंगहरू</h1>
        <a href="<?php echo e(route('student.bookings.create')); ?>" class="btn btn-primary btn-sm">
            <i class="fas fa-plus"></i> नयाँ बुकिंग
        </a>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">बुकिंग इतिहास</h6>
                </div>
                <div class="card-body">
                    <?php if($bookings->count() > 0): ?>
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>बुकिंग ID</th>
                                    <th>होस्टेल</th>
                                    <th>कोठा</th>
                                    <th>चेक-इन</th>
                                    <th>चेक-आउट</th>
                                    <th>स्थिति</th>
                                    <th>कार्यहरू</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__currentLoopData = $bookings; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $booking): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td>#<?php echo e($booking->id); ?></td>
                                    <td><?php echo e($booking->room->hostel->name); ?></td>
                                    <td><?php echo e($booking->room->room_number); ?></td>
                                    <td><?php echo e($booking->check_in_date->format('Y-m-d')); ?></td>
                                    <td><?php echo e($booking->check_out_date->format('Y-m-d')); ?></td>
                                    <td>
                                        <?php if (isset($component)) { $__componentOriginal8c81617a70e11bcf247c4db924ab1b62 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal8c81617a70e11bcf247c4db924ab1b62 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.status-badge','data' => ['status' => $booking->status]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('status-badge'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['status' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($booking->status)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal8c81617a70e11bcf247c4db924ab1b62)): ?>
<?php $attributes = $__attributesOriginal8c81617a70e11bcf247c4db924ab1b62; ?>
<?php unset($__attributesOriginal8c81617a70e11bcf247c4db924ab1b62); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal8c81617a70e11bcf247c4db924ab1b62)): ?>
<?php $component = $__componentOriginal8c81617a70e11bcf247c4db924ab1b62; ?>
<?php unset($__componentOriginal8c81617a70e11bcf247c4db924ab1b62); ?>
<?php endif; ?>
                                    </td>
                                    <td>
                                        <a href="<?php echo e(route('student.bookings.show', $booking->id)); ?>" class="btn btn-info btn-sm">
                                            <i class="fas fa-eye"></i> हेर्नुहोस्
                                        </a>
                                    </td>
                                </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="d-flex justify-content-center">
                        <?php echo e($bookings->links()); ?>

                    </div>
                    <?php else: ?>
                    <div class="text-center py-5">
                        <i class="fas fa-calendar-times fa-3x text-gray-300 mb-3"></i>
                        <h4 class="text-gray-500">कुनै बुकिंग छैन</h4>
                        <p class="text-muted">तपाईंले अहिले सम्म कुनै होस्टेल बुक गर्नुभएको छैन।</p>
                        <a href="<?php echo e(route('student.bookings.create')); ?>" class="btn btn-primary">
                            <i class="fas fa-plus"></i> पहिलो बुकिंग गर्नुहोस्
                        </a>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.student', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\My Projects\HostelHub\resources\views\student\bookings\index.blade.php ENDPATH**/ ?>