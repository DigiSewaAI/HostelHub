<?php $__env->startSection('title', 'मेरो होस्टल'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <h2 class="mb-0">मेरो होस्टल</h2>
                <?php if(!$hostel): ?>
                    <a href="<?php echo e(route('owner.hostels.create')); ?>" class="btn btn-primary">
                        <i class="fas fa-plus me-1"></i> नयाँ होस्टल थप्नुहोस्
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <?php if(session('success')): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>
            <?php echo e(session('success')); ?>

            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <?php if(session('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i>
            <?php echo e(session('error')); ?>

            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <div class="row">
        <div class="col-md-12">
            <div class="card shadow">
                <div class="card-header bg-white py-3">
                    <h6 class="m-0 font-weight-bold text-primary">होस्टल विवरण</h6>
                </div>
                <div class="card-body">
                    <?php if($hostel): ?>
                        <div class="row">
                            <div class="col-md-4">
                                <?php if($hostel->image): ?>
                                    <img src="<?php echo e(asset('storage/'.$hostel->image)); ?>"
                                         class="img-fluid rounded mb-3"
                                         style="max-height: 250px; object-fit: cover; width: 100%;"
                                         alt="<?php echo e($hostel->name); ?>">
                                <?php else: ?>
                                    <div class="bg-light rounded mb-3 d-flex align-items-center justify-content-center"
                                         style="height: 250px; width: 100%;">
                                        <span class="text-muted">
                                            <i class="fas fa-building fa-3x"></i>
                                            <div class="mt-2">कुनै तस्बिर छैन</div>
                                        </span>
                                    </div>
                                <?php endif; ?>
                                
                                <!-- Quick Stats -->
                                <div class="card bg-light">
                                    <div class="card-body">
                                        <h6 class="card-title">त्वरित तथ्याङ्क</h6>
                                        <div class="row text-center">
                                            <div class="col-6">
                                                <div class="border-end">
                                                    <h5 class="text-primary mb-1"><?php echo e($hostel->total_rooms); ?></h5>
                                                    <small class="text-muted">कुल कोठा</small>
                                                </div>
                                            </div>
                                            <div class="col-6">
                                                <h5 class="text-success mb-1"><?php echo e($hostel->available_rooms); ?></h5>
                                                <small class="text-muted">उपलब्ध कोठा</small>
                                            </div>
                                        </div>
                                        <?php if($hostel->rooms && $hostel->rooms->count() > 0): ?>
                                        <div class="mt-3">
                                            <small class="text-muted">कब्जा दर:</small>
                                            <div class="progress mt-1" style="height: 8px;">
                                                <?php
                                                    $occupancyRate = (($hostel->total_rooms - $hostel->available_rooms) / $hostel->total_rooms) * 100;
                                                ?>
                                                <div class="progress-bar bg-success" 
                                                     role="progressbar" 
                                                     style="width: <?php echo e($occupancyRate); ?>%"
                                                     aria-valuenow="<?php echo e($occupancyRate); ?>" 
                                                     aria-valuemin="0" 
                                                     aria-valuemax="100">
                                                </div>
                                            </div>
                                            <small class="text-muted"><?php echo e(number_format($occupancyRate, 1)); ?>% कोठा भएको</small>
                                        </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-8">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label fw-bold">होस्टलको नाम</label>
                                            <p class="fs-5"><?php echo e($hostel->name); ?></p>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label fw-bold">स्थिति</label>
                                            <p>
                                                <span class="badge <?php echo e($hostel->status === 'active' ? 'bg-success' : ($hostel->status === 'inactive' ? 'bg-secondary' : 'bg-warning')); ?> fs-6">
                                                    <?php echo e(ucfirst(str_replace('_', ' ', $hostel->status))); ?>

                                                </span>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label fw-bold">कुल कोठाहरू</label>
                                            <p class="fs-5"><?php echo e($hostel->total_rooms); ?></p>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label fw-bold">उपलब्ध कोठाहरू</label>
                                            <p class="fs-5 text-success"><?php echo e($hostel->available_rooms); ?></p>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="mb-3">
                                    <label class="form-label fw-bold">ठेगाना</label>
                                    <p class="fs-6">
                                        <i class="fas fa-map-marker-alt text-danger me-2"></i>
                                        <?php echo e($hostel->address); ?>, <?php echo e($hostel->city); ?>

                                    </p>
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label fw-bold">सम्पर्क व्यक्ति</label>
                                            <p class="fs-6">
                                                <i class="fas fa-user text-primary me-2"></i>
                                                <?php echo e($hostel->contact_person); ?>

                                            </p>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label fw-bold">सम्पर्क फोन</label>
                                            <p class="fs-6">
                                                <i class="fas fa-phone text-success me-2"></i>
                                                <?php echo e($hostel->contact_phone); ?>

                                            </p>
                                        </div>
                                    </div>
                                </div>

                                <?php if($hostel->contact_email): ?>
                                <div class="mb-3">
                                    <label class="form-label fw-bold">सम्पर्क इमेल</label>
                                    <p class="fs-6">
                                        <i class="fas fa-envelope text-info me-2"></i>
                                        <?php echo e($hostel->contact_email); ?>

                                    </p>
                                </div>
                                <?php endif; ?>

                                <?php if($hostel->description): ?>
                                <div class="mb-3">
                                    <label class="form-label fw-bold">विवरण</label>
                                    <p class="fs-6"><?php echo e($hostel->description); ?></p>
                                </div>
                                <?php endif; ?>

                                <?php if($hostel->facilities): ?>
                                <div class="mb-4">
                                    <label class="form-label fw-bold">सुविधाहरू</label>
                                    <div class="d-flex flex-wrap gap-2">
                                        <?php
                                            $facilities = json_decode($hostel->facilities, true) ?? [];
                                        ?>
                                        <?php if(is_array($facilities) && count($facilities) > 0): ?>
                                            <?php $__currentLoopData = $facilities; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $facility): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <span class="badge bg-light text-dark border">
                                                    <i class="fas fa-check text-success me-1"></i>
                                                    <?php echo e(trim($facility)); ?>

                                                </span>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        <?php else: ?>
                                            <span class="text-muted">कुनै सुविधा उल्लेख छैन</span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <?php endif; ?>
                                
                                <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-4">
                                    <a href="<?php echo e(route('owner.rooms.index')); ?>" class="btn btn-outline-primary me-2">
                                        <i class="fas fa-door-open me-1"></i> कोठाहरू हेर्नुहोस्
                                    </a>
                                    <a href="<?php echo e(route('owner.hostels.edit', $hostel)); ?>" class="btn btn-primary">
                                        <i class="fas fa-edit me-1"></i> होस्टल सम्पादन गर्नुहोस्
                                    </a>
                                </div>
                            </div>
                        </div>
                    <?php else: ?>
                        <div class="text-center py-5">
                            <div class="mb-4">
                                <i class="fas fa-building fa-4x text-muted"></i>
                            </div>
                            <h4 class="text-muted mb-3">कुनै होस्टल छैन</h4>
                            <p class="text-muted mb-4">तपाईंसँग अहिले कुनै होस्टल छैन। नयाँ होस्टल सिर्जना गर्नुहोस्।</p>
                            <a href="<?php echo e(route('owner.hostels.create')); ?>" class="btn btn-primary btn-lg">
                                <i class="fas fa-plus me-2"></i> नयाँ होस्टल सिर्जना गर्नुहोस्
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Additional Information Card -->
    <?php if($hostel): ?>
    <div class="row mt-4">
        <div class="col-md-6">
            <div class="card shadow">
                <div class="card-header bg-white py-3">
                    <h6 class="m-0 font-weight-bold text-primary">हालको कोठा स्थिति</h6>
                </div>
                <div class="card-body">
                    <?php if($hostel->rooms && $hostel->rooms->count() > 0): ?>
                        <div class="row text-center">
                            <div class="col-4">
                                <div class="border-end">
                                    <h5 class="text-success"><?php echo e($hostel->rooms->where('status', 'available')->count()); ?></h5>
                                    <small class="text-muted">उपलब्ध</small>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="border-end">
                                    <h5 class="text-danger"><?php echo e($hostel->rooms->where('status', 'occupied')->count()); ?></h5>
                                    <small class="text-muted">कब्जामा</small>
                                </div>
                            </div>
                            <div class="col-4">
                                <h5 class="text-warning"><?php echo e($hostel->rooms->where('status', 'maintenance')->count()); ?></h5>
                                <small class="text-muted">मर्मतमा</small>
                            </div>
                        </div>
                    <?php else: ?>
                        <p class="text-muted text-center">कुनै कोठा थपिएको छैन</p>
                        <div class="text-center">
                            <a href="<?php echo e(route('owner.rooms.create')); ?>" class="btn btn-sm btn-outline-primary">
                                पहिलो कोठा थप्नुहोस्
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card shadow">
                <div class="card-header bg-white py-3">
                    <h6 class="m-0 font-weight-bold text-primary">त्वरित कार्यहरू</h6>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="<?php echo e(route('owner.rooms.create')); ?>" class="btn btn-outline-primary text-start">
                            <i class="fas fa-plus me-2"></i> नयाँ कोठा थप्नुहोस्
                        </a>
                        <a href="<?php echo e(route('owner.students.index')); ?>" class="btn btn-outline-success text-start">
                            <i class="fas fa-users me-2"></i> विद्यार्थीहरू व्यवस्थापन गर्नुहोस्
                        </a>
                        <a href="<?php echo e(route('owner.payments.index')); ?>" class="btn btn-outline-info text-start">
                            <i class="fas fa-money-bill-wave me-2"></i> भुक्तानीहरू हेर्नुहोस्
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('styles'); ?>
<style>
    .progress {
        background-color: #e9ecef;
    }
    .badge {
        font-size: 0.75em;
    }
</style>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.owner', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\My Projects\HostelHub\resources\views/owner/hostels/index.blade.php ENDPATH**/ ?>