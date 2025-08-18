<?php $__env->startSection('content'); ?>
<div class="container">
    <h2>मेरो बुकिङहरू</h2>

    <?php if($bookings->isEmpty()): ?>
        <div class="alert alert-info">
            तपाईंसँग कुनै बुकिङ छैन।
        </div>
    <?php else: ?>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>होस्टल</th>
                    <th>कोठा</th>
                    <th>चेक-इन</th>
                    <th>चेक-आउट</th>
                    <th>स्थिति</th>
                </tr>
            </thead>
            <tbody>
                <?php $__currentLoopData = $bookings; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $booking): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>
                    <td><?php echo e($booking->hostel->name); ?></td>
                    <td><?php echo e($booking->room->room_number); ?></td>
                    <td><?php echo e($booking->check_in_date->format('Y-m-d')); ?></td>
                    <td><?php echo e($booking->check_out_date->format('Y-m-d')); ?></td>
                    <td><?php echo e($booking->status); ?></td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\My Projects\HostelHub\resources\views\bookings\my-bookings.blade.php ENDPATH**/ ?>