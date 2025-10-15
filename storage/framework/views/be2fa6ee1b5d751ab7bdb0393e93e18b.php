

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <!-- Welcome Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h2 class="mb-1">नमस्ते, <?php echo e($student->user->name); ?>! 👋</h2>
                        <p class="mb-0"><?php echo e($hostel->name); ?>मा तपाईंलाई स्वागत छ</p>
                        </div>
                        <div class="col-md-4 text-end">
                            <div class="badge bg-light text-dark p-2">
                                <i class="fas fa-calendar me-2"></i>
                                <?php echo e(now()->format('F j, Y')); ?>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Left Column - Main Content -->
        <div class="col-lg-8">
            <!-- Quick Stats -->
            <div class="row mb-4">
                <div class="col-md-3">
                    <div class="card card-hover">
                        <div class="card-body text-center">
                            <i class="fas fa-door-open fa-2x text-primary mb-2"></i>
                            <h5>कोठा नं.</h5>
                            <h3 class="text-primary"><?php echo e($student->room->room_number ?? 'N/A'); ?></h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card card-hover">
                        <div class="card-body text-center">
                            <i class="fas fa-utensils fa-2x text-success mb-2"></i>
                            <h5>आजको खाना</h5>
                            <h3 class="text-success"><?php echo e($todayMeal ? 'Available' : 'N/A'); ?></h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card card-hover">
                        <div class="card-body text-center">
                            <i class="fas fa-receipt fa-2x text-warning mb-2"></i>
                            <h5>भुक्तानी</h5>
                            <h3 class="text-warning"><?php echo e($paymentStatus ?? 'Pending'); ?></h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card card-hover">
                        <div class="card-body text-center">
                            <i class="fas fa-bell fa-2x text-info mb-2"></i>
                            <h5>सूचनाहरू</h5>
                            <h3 class="text-info"><?php echo e($notifications->count()); ?></h3>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Room & Payment Information -->
            <div class="row mb-4">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header bg-primary text-white">
                            <h5 class="mb-0"><i class="fas fa-home me-2"></i>कोठा जानकारी</h5>
                        </div>
                        <div class="card-body">
                            <table class="table table-borderless">
                                <tr>
                                    <td><strong>होस्टेल:</strong></td>
                                    <td><?php echo e($hostel->name ?? 'N/A'); ?></td>
                                </tr>
                                <tr>
                                    <td><strong>कोठा नं.:</strong></td>
                                    <td><?php echo e($student->room->room_number ?? 'N/A'); ?></td>
                                </tr>
                                <tr>
                                    <td><strong>कोठा प्रकार:</strong></td>
                                    <td><?php echo e($student->room->type ?? 'N/A'); ?></td>
                                </tr>
                                <tr>
                                    <td><strong>मासिक भुक्तानी:</strong></td>
                                    <td class="text-success"><strong>रु. <?php echo e($student->room->rent ?? 'N/A'); ?></strong></td>
                                </tr>
                            </table>
                            <button class="btn btn-outline-primary btn-sm" data-bs-toggle="modal" data-bs-target="#roomDetailsModal">
                                <i class="fas fa-info-circle me-1"></i>पूर्ण विवरण हेर्नुहोस्
                            </button>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header bg-warning text-dark">
                            <h5 class="mb-0"><i class="fas fa-credit-card me-2"></i>भुक्तानी स्थिति</h5>
                        </div>
                        <div class="card-body">
                            <table class="table table-borderless">
                                <tr>
                                    <td><strong>अन्तिम भुक्तानी:</strong></td>
                                    <td><?php echo e($lastPayment ? $lastPayment->amount : 'कुनै भुक्तानी छैन'); ?></td>
                                </tr>
                                <tr>
                                    <td><strong>अन्तिम मिति:</strong></td>
                                    <td><?php echo e($lastPayment ? $lastPayment->date : '-'); ?></td>
                                </tr>
                                <tr>
                                    <td><strong>स्थिति:</strong></td>
                                    <td>
                                        <span class="badge bg-<?php echo e($paymentStatus == 'Paid' ? 'success' : 'danger'); ?>">
                                            <?php echo e($paymentStatus == 'Paid' ? 'भुक्तानी भएको' : 'बाकी'); ?>

                                        </span>
                                    </td>
                                </tr>
                            </table>
                            <button class="btn btn-warning btn-sm">
                                <i class="fas fa-money-bill me-1"></i>भुक्तानी गर्नुहोस्
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Today's Meal & Notifications -->
            <div class="row mb-4">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header bg-success text-white">
                            <h5 class="mb-0"><i class="fas fa-utensils me-2"></i>आजको खानाको योजना</h5>
                        </div>
                        <div class="card-body">
                            <?php if($todayMeal): ?>
                                <h6 class="text-success"><?php echo e($todayMeal->meal_type); ?></h6>
                                <p class="mb-1"><strong>मुख्य खाना:</strong> <?php echo e($todayMeal->main_dish); ?></p>
                                <p class="mb-1"><strong>साइड डिश:</strong> <?php echo e($todayMeal->side_dish); ?></p>
                                <p class="mb-0"><strong>समय:</strong> <?php echo e($todayMeal->serving_time); ?></p>
                            <?php else: ?>
                                <p class="text-muted">आजको खानाको योजना उपलब्ध छैन</p>
                            <?php endif; ?>
                            <div class="mt-3">
                                <a href="<?php echo e(route('student.meal-menus')); ?>" class="btn btn-outline-success btn-sm">
                                    <i class="fas fa-calendar me-1"></i>सप्ताहिक मेनु हेर्नुहोस्
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header bg-info text-white">
                            <h5 class="mb-0"><i class="fas fa-bell me-2"></i>हालैका सूचनाहरू</h5>
                        </div>
                        <div class="card-body">
                            <?php if($notifications->count() > 0): ?>
                                <div class="list-group list-group-flush">
                                    <?php $__currentLoopData = $notifications->take(3); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $notification): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <div class="list-group-item px-0 py-2">
                                            <small class="text-muted"><?php echo e($notification->created_at->diffForHumans()); ?></small>
                                            <p class="mb-0 small"><?php echo e(Str::limit($notification->message, 50)); ?></p>
                                        </div>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </div>
                                <a href="<?php echo e(route('student.notifications')); ?>" class="btn btn-outline-info btn-sm mt-2">
                                    सबै सूचनाहरू हेर्नुहोस्
                                </a>
                            <?php else: ?>
                                <p class="text-muted">कुनै नयाँ सूचना छैन</p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column - Sidebar -->
        <div class="col-lg-4">
            <!-- Quick Actions -->
            <div class="card mb-4">
                <div class="card-header bg-secondary text-white">
                    <h5 class="mb-0"><i class="fas fa-bolt me-2"></i>द्रुत कार्यहरू</h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="<?php echo e(route('student.profile')); ?>" class="btn btn-outline-primary text-start">
                            <i class="fas fa-user me-2"></i>मेरो प्रोफाइल
                        </a>
                        <a href="<?php echo e(route('student.meal-menus')); ?>" class="btn btn-outline-success text-start">
                            <i class="fas fa-utensils me-2"></i>खानाको योजना
                        </a>
                        <button class="btn btn-outline-warning text-start" data-bs-toggle="modal" data-bs-target="#paymentModal">
                            <i class="fas fa-credit-card me-2"></i>भुक्तानी गर्नुहोस्
                        </button>
                        <a href="<?php echo e(route('student.gallery')); ?>" class="btn btn-outline-info text-start">
                            <i class="fas fa-images me-2"></i>होस्टेल ग्यालेरी
                        </a>
                        <a href="<?php echo e(route('student.reviews')); ?>" class="btn btn-outline-dark text-start">
                            <i class="fas fa-star me-2"></i>समीक्षा लेख्नुहोस्
                        </a>
                        <button class="btn btn-outline-danger text-start" data-bs-toggle="modal" data-bs-target="#maintenanceModal">
                            <i class="fas fa-tools me-2"></i>मर्मत समस्या
                        </button>
                    </div>
                </div>
            </div>

            <!-- Hostel Gallery Preview -->
            <div class="card mb-4">
                <div class="card-header bg-dark text-white">
                    <h5 class="mb-0"><i class="fas fa-images me-2"></i>होस्टेल ग्यालेरी</h5>
                </div>
                <div class="card-body">
                    <div class="row g-2">
                        <?php $__currentLoopData = $galleryImages->take(4); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $image): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="col-6">
                                <img src="<?php echo e(asset('storage/'.$image->path)); ?>" 
                                     class="img-fluid rounded gallery-thumb" 
                                     alt="Hostel Image"
                                     style="height: 80px; object-fit: cover; width: 100%; cursor: pointer;"
                                     onclick="openImageModal('<?php echo e(asset('storage/'.$image->path)); ?>')">
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                    <div class="mt-3">
                        <a href="<?php echo e(route('student.gallery')); ?>" class="btn btn-outline-dark btn-sm w-100">
                            <i class="fas fa-expand me-1"></i>पूर्ण ग्यालेरी हेर्नुहोस्
                        </a>
                    </div>
                </div>
            </div>

            <!-- Upcoming Events -->
            <div class="card">
                <div class="card-header bg-purple text-white">
                    <h5 class="mb-0"><i class="fas fa-calendar-alt me-2"></i>आगामी घटनाहरू</h5>
                </div>
                <div class="card-body">
                    <?php if($upcomingEvents->count() > 0): ?>
                        <div class="list-group list-group-flush">
                            <?php $__currentLoopData = $upcomingEvents->take(3); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $event): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="list-group-item px-0 py-2">
                                    <h6 class="mb-1 text-primary"><?php echo e($event->title); ?></h6>
                                    <small class="text-muted">
                                        <i class="fas fa-clock me-1"></i>
                                        <?php echo e($event->date->format('M j')); ?> at <?php echo e($event->time); ?>

                                    </small>
                                    <p class="mb-0 small text-muted"><?php echo e(Str::limit($event->description, 40)); ?></p>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                        <a href="<?php echo e(route('student.events')); ?>" class="btn btn-outline-purple btn-sm mt-2 w-100">
                            सबै घटनाहरू हेर्नुहोस्
                        </a>
                    <?php else: ?>
                        <p class="text-muted">कुनै आगामी घटना छैन</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modals -->
<?php echo $__env->make('student.modals.room-details', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php echo $__env->make('student.modals.payment', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php echo $__env->make('student.modals.maintenance', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php echo $__env->make('student.modals.gallery-view', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

<style>
.card-hover:hover {
    transform: translateY(-5px);
    transition: all 0.3s ease;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
}
.bg-purple {
    background-color: #6f42c1 !important;
}
.btn-outline-purple {
    color: #6f42c1;
    border-color: #6f42c1;
}
.btn-outline-purple:hover {
    background-color: #6f42c1;
    color: white;
}
.gallery-thumb:hover {
    opacity: 0.8;
    transform: scale(1.05);
    transition: all 0.3s ease;
}
</style>

<script>
function openImageModal(imageUrl) {
    document.getElementById('galleryImage').src = imageUrl;
    new bootstrap.Modal(document.getElementById('galleryViewModal')).show();
}
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.student', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\My Projects\HostelHub\resources\views/student/dashboard.blade.php ENDPATH**/ ?>