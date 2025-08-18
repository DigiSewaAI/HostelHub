<?php $__env->startSection('title', 'Hostel Details: ' . $hostel->name); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-12">
            <a href="<?php echo e(route('admin.hostels.index')); ?>" class="btn btn-outline-primary mb-3">
                <i class="fas fa-arrow-left me-1"></i> Back to Hostels
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-4">
            <div class="card shadow mb-4">
                <div class="card-header bg-white py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Hostel Information</h6>
                </div>
                <div class="card-body">
                    <?php if($hostel->image): ?>
                        <img src="<?php echo e(asset('storage/'.$hostel->image)); ?>"
                             class="img-fluid rounded mb-3"
                             style="max-height: 250px; object-fit: cover; width: 100%;">
                    <?php else: ?>
                        <div class="bg-light rounded mb-3"
                             style="height: 250px; width: 100%; display: flex; align-items: center; justify-content: center;">
                            <span class="text-muted">No image available</span>
                        </div>
                    <?php endif; ?>

                    <ul class="list-group list-group-flush">
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <strong>Status</strong>
                            <span class="badge <?php echo e($hostel->status === 'active' ? 'bg-success' : ($hostel->status === 'maintenance' ? 'bg-warning' : 'bg-secondary')); ?>">
                                <?php echo e(ucfirst($hostel->status)); ?>

                            </span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <strong>Total Rooms</strong>
                            <span><?php echo e($hostel->total_rooms); ?></span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <strong>Available Rooms</strong>
                            <span class="text-success"><?php echo e($hostel->rooms()->available()->count()); ?></span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <strong>Occupied Rooms</strong>
                            <span class="text-danger"><?php echo e($hostel->rooms()->occupied()->count()); ?></span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <strong>Maintenance Rooms</strong>
                            <span class="text-warning"><?php echo e($hostel->rooms()->maintenance()->count()); ?></span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <strong>Occupancy Rate</strong>
                            <span class="fw-bold"><?php echo e($hostel->getOccupancyRate()); ?>%</span>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="card shadow">
                <div class="card-header bg-white py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Actions</h6>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="<?php echo e(route('admin.hostels.edit', $hostel)); ?>" class="btn btn-warning">
                            <i class="fas fa-edit me-1"></i> Edit Hostel
                        </a>
                        <a href="<?php echo e(route('admin.hostels.availability', $hostel)); ?>" class="btn btn-info">
                            <i class="fas fa-calendar-check me-1"></i> Manage Availability
                        </a>
                        <form action="<?php echo e(route('admin.hostels.destroy', $hostel)); ?>" method="POST" class="mt-2">
                            <?php echo csrf_field(); ?>
                            <?php echo method_field('DELETE'); ?>
                            <button type="submit" class="btn btn-danger w-100"
                                    onclick="return confirm('Are you sure you want to delete this hostel? All rooms and associated data will be removed.')">
                                <i class="fas fa-trash me-1"></i> Delete Hostel
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-8">
            <div class="card shadow">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">Rooms in <?php echo e($hostel->name); ?></h6>
                    <a href="<?php echo e(route('admin.rooms.create', ['hostel_id' => $hostel->id])); ?>" class="btn btn-sm btn-primary">
                        <i class="fas fa-plus me-1"></i> Add Room
                    </a>
                </div>
                <div class="card-body">
                    <?php if($hostel->rooms->isEmpty()): ?>
                        <div class="alert alert-info">
                            No rooms added to this hostel yet.
                        </div>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>Room #</th>
                                        <th>Type</th>
                                        <th>Capacity</th>
                                        <th>Price</th>
                                        <th>Status</th>
                                        <th>Occupancy</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $__currentLoopData = $hostel->rooms; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $room): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        <td><?php echo e($room->room_number); ?></td>
                                        <td><?php echo e(ucfirst($room->type)); ?></td>
                                        <td><?php echo e($room->capacity); ?> beds</td>
                                        <td>रु <?php echo e(number_format($room->price, 2)); ?></td>
                                        <td>
                                            <span class="badge <?php echo e($room->status === 'available' ? 'bg-success' : ($room->status === 'occupied' ? 'bg-danger' : 'bg-warning')); ?>">
                                                <?php echo e(ucfirst($room->status)); ?>

                                            </span>
                                        </td>
                                        <td>
                                            <?php echo e($room->students->count()); ?>/<?php echo e($room->capacity); ?>

                                            (<?php echo e($room->occupancy); ?>%)
                                        </td>
                                        <td>
                                            <div class="d-flex gap-2">
                                                <a href="<?php echo e(route('admin.rooms.show', $room)); ?>" class="btn btn-sm btn-info" title="View">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="<?php echo e(route('admin.rooms.edit', $room)); ?>" class="btn btn-sm btn-warning" title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\My Projects\HostelHub\resources\views\admin\hostels\show.blade.php ENDPATH**/ ?>