

<?php $__env->startSection('title', 'कोठा विवरण'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">कोठा विवरण</h3>
                </div>

                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-bordered">
                                <tr>
                                    <th style="width: 30%;">कोठा नम्बर:</th>
                                    <td><?php echo e($room->room_number); ?></td>
                                </tr>
                                <tr>
                                    <th>होस्टल:</th>
                                    <td><?php echo e($room->hostel->name ?? 'N/A'); ?></td>
                                </tr>
                                <tr>
                                    <th>प्रकार:</th>
                                    <td>
                                        <?php if($room->type == 'single'): ?>
                                            एकल
                                        <?php elseif($room->type == 'double'): ?>
                                            डबल
                                        <?php else: ?>
                                            साझा
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <tr>
                                    <th>क्षमता:</th>
                                    <td><?php echo e($room->capacity); ?> जना</td>
                                </tr>
                                <tr>
                                    <th>मूल्य:</th>
                                    <td>रु. <?php echo e(number_format($room->price, 2)); ?></td>
                                </tr>
                                <tr>
                                    <th>स्थिति:</th>
                                    <td>
                                        <?php if($room->status == 'available' || $room->status == 'उपलब्ध'): ?>
                                            <span class="badge bg-success">उपलब्ध</span>
                                        <?php elseif($room->status == 'occupied' || $room->status == 'बुक भएको'): ?>
                                            <span class="badge bg-danger">व्यस्त</span>
                                        <?php else: ?>
                                            <span class="badge bg-warning">मर्मत सम्भार</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            </table>
                        </div>

                        <div class="col-md-6">
                            <h5>विवरण:</h5>
                            <div class="border rounded p-3 bg-light">
                                <p class="mb-0"><?php echo e($room->description ?? 'कुनै विवरण उपलब्ध छैन'); ?></p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card-footer">
                    <a href="<?php echo e(route('owner.rooms.edit', $room)); ?>" class="btn btn-primary">
                        <i class="fas fa-edit"></i> सम्पादन गर्नुहोस्
                    </a>
                    <a href="<?php echo e(route('owner.rooms.index')); ?>" class="btn btn-default">
                        <i class="fas fa-arrow-left"></i> कोठा सूचीमा फर्कनुहोस्
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.owner', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\My Projects\HostelHub\resources\views/owner/rooms/show.blade.php ENDPATH**/ ?>