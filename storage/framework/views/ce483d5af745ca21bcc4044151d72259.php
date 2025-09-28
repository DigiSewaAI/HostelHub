<?php $__env->startSection('title', 'My Hostel'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-12">
            <h2 class="mb-0">My Hostel</h2>
        </div>
    </div>

    <?php if(session('success')): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?php echo e(session('success')); ?>

            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <div class="row">
        <div class="col-md-12">
            <div class="card shadow">
                <div class="card-header bg-white py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Hostel Details</h6>
                </div>
                <div class="card-body">
                    <?php if($hostel): ?>
                        <div class="row">
                            <div class="col-md-4">
                                <?php if($hostel->image): ?>
                                    <img src="<?php echo e(asset('storage/'.$hostel->image)); ?>"
                                         class="img-fluid rounded mb-3"
                                         style="max-height: 250px; object-fit: cover; width: 100%;">
                                <?php else: ?>
                                    <div class="bg-light rounded mb-3 d-flex align-items-center justify-content-center"
                                         style="height: 250px; width: 100%;">
                                        <span class="text-muted"><i class="fas fa-building fa-3x"></i></span>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <div class="col-md-8">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label fw-bold">Hostel Name</label>
                                            <p><?php echo e($hostel->name); ?></p>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label fw-bold">Status</label>
                                            <p>
                                                <span class="badge <?php echo e($hostel->status === 'active' ? 'bg-success' : ($hostel->status === 'inactive' ? 'bg-secondary' : 'bg-warning')); ?>">
                                                    <?php echo e(ucfirst(str_replace('_', ' ', $hostel->status))); ?>

                                                </span>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label fw-bold">Total Rooms</label>
                                            <p><?php echo e($hostel->total_rooms); ?></p>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label fw-bold">Available Rooms</label>
                                            <p class="text-success"><?php echo e($hostel->available_rooms); ?></p>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Address</label>
                                    <p><?php echo e($hostel->address); ?>, <?php echo e($hostel->city); ?></p>
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label fw-bold">Contact Person</label>
                                            <p><?php echo e($hostel->contact_person); ?></p>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label fw-bold">Contact Phone</label>
                                            <p><?php echo e($hostel->contact_phone); ?></p>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                    <a href="<?php echo e(route('owner.hostels.edit', $hostel)); ?>" class="btn btn-primary">
                                        <i class="fas fa-edit me-1"></i> Edit Hostel
                                    </a>
                                </div>
                            </div>
                        </div>
                    <?php else: ?>
                        <div class="alert alert-info">
                            No hostel assigned to you.
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.owner', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\My Projects\HostelHub\resources\views/owner/hostels/index.blade.php ENDPATH**/ ?>