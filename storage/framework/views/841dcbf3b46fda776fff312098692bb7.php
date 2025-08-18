<?php $__env->startSection('title', 'Hostel Management'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex align-items-center justify-content-between">
                <h2 class="mb-0">Hostel Management</h2>
                <a href="<?php echo e(route('admin.hostels.create')); ?>" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i>New Hostel
                </a>
            </div>
        </div>
    </div>

    <?php if(session('success')): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?php echo e(session('success')); ?>

            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <div class="card shadow">
        <div class="card-header bg-white py-3">
            <h6 class="m-0 font-weight-bold text-primary">All Hostels</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover" id="dataTable" width="100%" cellspacing="0">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Location</th>
                            <th>Rooms</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = $hostels; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $hostel): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td><?php echo e($hostel->id); ?></td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <?php if($hostel->image): ?>
                                        <img src="<?php echo e(asset('storage/'.$hostel->image)); ?>"
                                             class="rounded me-2"
                                             width="40"
                                             height="40"
                                             style="object-fit: cover;">
                                    <?php else: ?>
                                        <div class="bg-light rounded me-2" style="width: 40px; height: 40px;"></div>
                                    <?php endif; ?>
                                    <span><?php echo e($hostel->name); ?></span>
                                </div>
                            </td>
                            <td><?php echo e($hostel->location); ?></td>
                            <td><?php echo e($hostel->rooms_count); ?></td>
                            <td>
                                <span class="badge <?php echo e($hostel->status === 'active' ? 'bg-success' : 'bg-secondary'); ?>">
                                    <?php echo e(ucfirst($hostel->status)); ?>

                                </span>
                            </td>
                            <td>
                                <div class="d-flex gap-2">
                                    <a href="<?php echo e(route('admin.hostels.show', $hostel)); ?>"
                                       class="btn btn-sm btn-info"
                                       title="View Details">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="<?php echo e(route('admin.hostels.edit', $hostel)); ?>"
                                       class="btn btn-sm btn-warning"
                                       title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="<?php echo e(route('admin.hostels.destroy', $hostel)); ?>"
                                          method="POST"
                                          class="delete-form"
                                          style="display: inline;">
                                        <?php echo csrf_field(); ?>
                                        <?php echo method_field('DELETE'); ?>
                                        <button type="submit"
                                                class="btn btn-sm btn-danger"
                                                title="Delete"
                                                onclick="return confirm('Are you sure you want to delete this hostel?')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Delete confirmation
        const deleteForms = document.querySelectorAll('.delete-form');
        deleteForms.forEach(form => {
            form.addEventListener('submit', function(e) {
                if (!confirm('Are you sure you want to delete this hostel? All rooms and associated data will be removed.')) {
                    e.preventDefault();
                }
            });
        });
    });
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\My Projects\HostelHub\resources\views\admin\hostels\index.blade.php ENDPATH**/ ?>