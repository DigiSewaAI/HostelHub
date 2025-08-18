<?php $__env->startSection('title', 'Manage Room Availability: ' . $hostel->name); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-12">
            <a href="<?php echo e(route('admin.hostels.show', $hostel)); ?>" class="btn btn-outline-primary mb-3">
                <i class="fas fa-arrow-left me-1"></i> Back to Hostel Details
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="card shadow">
                <div class="card-header bg-white py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Manage Room Availability for <?php echo e($hostel->name); ?></h6>
                </div>
                <div class="card-body">
                    <?php if(session('success')): ?>
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <?php echo e(session('success')); ?>

                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    <?php endif; ?>

                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-1"></i>
                        Here you can update the availability status of all rooms in this hostel at once.
                    </div>

                    <form action="<?php echo e(route('admin.hostels.availability', $hostel)); ?>" method="POST">
                        <?php echo csrf_field(); ?>
                        <div class="table-responsive">
                            <table class="table table-hover" id="roomsTable">
                                <thead class="table-light">
                                    <tr>
                                        <th>Room #</th>
                                        <th>Type</th>
                                        <th>Capacity</th>
                                        <th>Current Status</th>
                                        <th>New Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $__currentLoopData = $hostel->rooms; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $room): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        <td><?php echo e($room->room_number); ?></td>
                                        <td><?php echo e(ucfirst($room->type)); ?></td>
                                        <td><?php echo e($room->capacity); ?> beds</td>
                                        <td>
                                            <span class="badge <?php echo e($room->status === 'available' ? 'bg-success' : ($room->status === 'occupied' ? 'bg-danger' : 'bg-warning')); ?>">
                                                <?php echo e(ucfirst($room->status)); ?>

                                            </span>
                                        </td>
                                        <td>
                                            <select name="rooms[<?php echo e($room->id); ?>]" class="form-select">
                                                <option value="available" <?php echo e($room->status === 'available' ? 'selected' : ''); ?>>Available</option>
                                                <option value="occupied" <?php echo e($room->status === 'occupied' ? 'selected' : ''); ?>>Occupied</option>
                                                <option value="maintenance" <?php echo e($room->status === 'maintenance' ? 'selected' : ''); ?>>Under Maintenance</option>
                                            </select>
                                        </td>
                                    </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                            </table>
                        </div>

                        <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-4">
                            <a href="<?php echo e(route('admin.hostels.show', $hostel)); ?>" class="btn btn-secondary me-md-2">
                                <i class="fas fa-arrow-left me-1"></i> Cancel
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-1"></i> Update All Room Status
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Add bulk action functionality
        const selectAll = document.getElementById('selectAll');
        const statusSelects = document.querySelectorAll('select[name^="rooms["]');

        if (selectAll) {
            selectAll.addEventListener('change', function() {
                const selectedValue = this.value;
                statusSelects.forEach(select => {
                    select.value = selectedValue;
                });
            });
        }
    });
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\My Projects\HostelHub\resources\views\admin\hostels\availability.blade.php ENDPATH**/ ?>