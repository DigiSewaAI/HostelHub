

<?php $__env->startSection('title', $hostel->name . ' - विवरण'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><?php echo e($hostel->name); ?> को विवरण</h5>
                    <div>
                        <a href="<?php echo e(route('owner.hostels.index')); ?>" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> होस्टल सूची
                        </a>
                        <a href="<?php echo e(route('owner.hostels.edit', $hostel)); ?>" class="btn btn-primary">
                            <i class="fas fa-edit"></i> सम्पादन गर्नुहोस्
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-8">
                            <div class="row">
                                <div class="col-md-6">
                                    <h6 class="text-muted">होस्टलको नाम</h6>
                                    <p class="fs-5"><?php echo e($hostel->name); ?></p>
                                </div>
                                <div class="col-md-6">
                                    <h6 class="text-muted">स्थिति</h6>
                                    <span class="badge bg-<?php echo e($hostel->status == 'active' ? 'success' : 'danger'); ?>">
                                        <?php echo e($hostel->status == 'active' ? 'सक्रिय' : 'निष्क्रिय'); ?>

                                    </span>
                                </div>
                            </div>

                            <div class="row mt-3">
                                <div class="col-12">
                                    <h6 class="text-muted">ठेगाना</h6>
                                    <p><?php echo e($hostel->address); ?>, <?php echo e($hostel->city); ?></p>
                                </div>
                            </div>

                            <div class="row mt-3">
                                <div class="col-md-6">
                                    <h6 class="text-muted">सम्पर्क व्यक्ति</h6>
                                    <p><?php echo e($hostel->contact_person); ?></p>
                                </div>
                                <div class="col-md-6">
                                    <h6 class="text-muted">सम्पर्क फोन</h6>
                                    <p><?php echo e($hostel->contact_phone); ?></p>
                                </div>
                            </div>

                            <?php if($hostel->contact_email): ?>
                            <div class="row mt-3">
                                <div class="col-12">
                                    <h6 class="text-muted">इमेल</h6>
                                    <p><?php echo e($hostel->contact_email); ?></p>
                                </div>
                            </div>
                            <?php endif; ?>

                            <!-- ✅ NEW: Financial Information Section -->
                            <div class="row mt-3">
                                <div class="col-md-6">
                                    <h6 class="text-muted">मासिक भाडा</h6>
                                    <p class="fs-5 text-success">रु <?php echo e(number_format($hostel->monthly_rent ?? 0)); ?></p>
                                </div>
                                <div class="col-md-6">
                                    <h6 class="text-muted">सुरक्षा जमानत</h6>
                                    <p class="fs-5 text-info">रु <?php echo e(number_format($hostel->security_deposit ?? 0)); ?></p>
                                </div>
                            </div>

                            <?php if($hostel->description): ?>
                            <div class="row mt-3">
                                <div class="col-12">
                                    <h6 class="text-muted">विवरण</h6>
                                    <p><?php echo e($hostel->description); ?></p>
                                </div>
                            </div>
                            <?php endif; ?>

                            <?php if($hostel->facilities): ?>
                            <div class="row mt-3">
                                <div class="col-12">
                                    <h6 class="text-muted">सुविधाहरू</h6>
                                    <div class="d-flex flex-wrap gap-2">
                                        <?php $__currentLoopData = json_decode($hostel->facilities); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $facility): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <span class="badge bg-info"><?php echo e($facility); ?></span>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </div>
                                </div>
                            </div>
                            <?php endif; ?>
                        </div>

                        <div class="col-md-4">
                            <?php if($hostel->image): ?>
                                <div class="text-center mb-3">
                                    <img src="<?php echo e(asset('storage/' . $hostel->image)); ?>" alt="<?php echo e($hostel->name); ?>" 
                                         class="img-fluid rounded shadow-sm" style="max-height: 300px; width: 100%; object-fit: cover;">
                                    <small class="text-muted mt-2 d-block">होस्टलको तस्बिर</small>
                                </div>
                            <?php else: ?>
                                <div class="text-center text-muted py-5 border rounded bg-light">
                                    <i class="fas fa-image fa-3x mb-3 text-muted"></i>
                                    <p class="mb-0">कुनै छवि उपलब्ध छैन</p>
                                    <small class="text-muted">होस्टल सम्पादन गर्दा तस्बिर थप्नुहोस्</small>
                                </div>
                            <?php endif; ?>

                            <!-- ✅ NEW: Quick Financial Summary -->
                            <div class="card mt-4 border-0 bg-light">
                                <div class="card-body">
                                    <h6 class="card-title text-muted mb-3">वित्तिय सारांश</h6>
                                    <div class="row text-center">
                                        <div class="col-6">
                                            <small class="text-muted d-block">मासिक आय</small>
                                            <strong class="text-success">रु <?php echo e(number_format($hostel->monthly_rent ?? 0)); ?></strong>
                                        </div>
                                        <div class="col-6">
                                            <small class="text-muted d-block">जमानत रकम</small>
                                            <strong class="text-primary">रु <?php echo e(number_format($hostel->security_deposit ?? 0)); ?></strong>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Cards - UPDATED with Maintenance Rooms -->
    <div class="row mt-4">
        <div class="col-md-3">
            <div class="card bg-primary text-white shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4 class="mb-0"><?php echo e($hostel->rooms_count ?? $hostel->rooms->count()); ?></h4>
                            <p class="mb-0">कुल कोठाहरू</p>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-door-open fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-success text-white shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4 class="mb-0"><?php echo e($availableRooms); ?></h4>
                            <p class="mb-0">खाली कोठाहरू</p>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-bed fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-warning text-white shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4 class="mb-0"><?php echo e($occupiedRooms); ?></h4>
                            <p class="mb-0">व्यस्त कोठाहरू</p>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-users fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-secondary text-white shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4 class="mb-0"><?php echo e($maintenanceRooms ?? 2); ?></h4>
                            <p class="mb-0">मर्मतमा कोठा</p>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-tools fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Additional Stats Row -->
    <div class="row mt-4">
        <div class="col-md-4 offset-md-4">
            <div class="card bg-info text-white shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4 class="mb-0"><?php echo e($totalStudents); ?></h4>
                            <p class="mb-0">कुल विद्यार्थी</p>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-user-graduate fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- ✅ NEW: Financial Performance Cards -->
    <div class="row mt-4">
        <div class="col-md-4">
            <div class="card border-left-success shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="text-success text-uppercase mb-1">सम्भावित मासिक आय</h6>
                            <h4 class="mb-0 text-success">रु <?php echo e(number_format(($hostel->monthly_rent ?? 0) * $occupiedRooms)); ?></h4>
                            <small class="text-muted">(<?php echo e($occupiedRooms); ?> कोठा × रु <?php echo e(number_format($hostel->monthly_rent ?? 0)); ?>)</small>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-chart-line fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-left-primary shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="text-primary text-uppercase mb-1">सम्भावित जमानत आय</h6>
                            <h4 class="mb-0 text-primary">रु <?php echo e(number_format(($hostel->security_deposit ?? 0) * $occupiedRooms)); ?></h4>
                            <small class="text-muted">(<?php echo e($occupiedRooms); ?> कोठा × रु <?php echo e(number_format($hostel->security_deposit ?? 0)); ?>)</small>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-shield-alt fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-left-warning shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="text-warning text-uppercase mb-1">ओक्युपेन्सी दर</h6>
                            <h4 class="mb-0 text-warning">
                                <?php
                                    $totalRooms = $hostel->rooms_count ?? $hostel->rooms->count();
                                    $occupancyRate = $totalRooms > 0 ? round(($occupiedRooms / $totalRooms) * 100, 2) : 0;
                                ?>
                                <?php echo e($occupancyRate); ?>%
                            </h4>
                            <small class="text-muted"><?php echo e($occupiedRooms); ?> / <?php echo e($totalRooms); ?> कोठा</small>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-percentage fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Action Buttons -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex gap-2 flex-wrap">
                        <a href="<?php echo e(route('owner.hostels.edit', $hostel)); ?>" class="btn btn-primary">
                            <i class="fas fa-edit"></i> होस्टल सम्पादन गर्नुहोस्
                        </a>
                        
                        
                        <?php if(Route::has('owner.rooms.index')): ?>
                        <a href="<?php echo e(route('owner.rooms.index', ['hostel' => $hostel->id])); ?>" class="btn btn-success">
                            <i class="fas fa-door-open"></i> कोठाहरू व्यवस्थापन गर्नुहोस्
                        </a>
                        <?php endif; ?>

                        
                        <?php if(Route::has('owner.students.index')): ?>
                        <a href="<?php echo e(route('owner.students.index', ['hostel' => $hostel->id])); ?>" class="btn btn-info">
                            <i class="fas fa-users"></i> विद्यार्थी व्यवस्थापन गर्नुहोस्
                        </a>
                        <?php endif; ?>

                        
                        <?php if(Route::has('owner.payments.index')): ?>
                        <a href="<?php echo e(route('owner.payments.index')); ?>" class="btn btn-warning">
                            <i class="fas fa-money-bill-wave"></i> भुक्तानीहरू हेर्नुहोस्
                        </a>
                        <?php endif; ?>

                        
<?php if(Route::has('owner.hostels.toggle-status')): ?>
<form action="<?php echo e(route('owner.hostels.toggle-status', $hostel)); ?>" method="POST" class="d-inline">
    <?php echo csrf_field(); ?>
    <?php echo method_field('PATCH'); ?>
    <button type="submit" class="btn btn-<?php echo e($hostel->status == 'active' ? 'secondary' : 'success'); ?>">
        <i class="fas fa-<?php echo e($hostel->status == 'active' ? 'pause' : 'play'); ?>"></i>
        <?php echo e($hostel->status == 'active' ? 'निष्क्रिय गर्नुहोस्' : 'सक्रिय गर्नुहोस्'); ?>

    </button>
</form>
<?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.owner', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\My Projects\HostelHub\resources\views/owner/hostels/show.blade.php ENDPATH**/ ?>