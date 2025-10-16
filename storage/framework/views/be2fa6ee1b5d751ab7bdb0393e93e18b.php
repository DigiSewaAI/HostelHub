

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <!-- Welcome Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card bg-gradient-primary text-white shadow-lg">
                <div class="card-body py-4">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h2 class="mb-1 fw-bold">नमस्ते, <?php echo e($student->user->name); ?>! 👋</h2>
                            <p class="mb-0 fs-5"><?php echo e($hostel->name); ?> मा तपाईंलाई स्वागत छ</p>
                            
                            <!-- ✅ ADDED: Circular Alert -->
                            <?php if(($unreadCirculars ?? 0) > 0): ?>
                            <div class="mt-3 alert alert-warning alert-dismissible fade show d-inline-block" role="alert">
                                <strong><i class="fas fa-bell me-2"></i>तपाईंसँग <?php echo e($unreadCirculars); ?> वटा नयाँ सूचनाहरू छन्!</strong>
                                <a href="<?php echo e(route('student.circulars.index')); ?>" class="alert-link ms-2">यहाँ क्लिक गर्नुहोस्</a> तिनीहरूलाई हेर्नको लागि।
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                            <?php endif; ?>
                        </div>
                        <div class="col-md-4 text-end">
                            <div class="badge bg-light text-dark p-3 fs-6">
                                <i class="fas fa-calendar me-2"></i>
                                <?php echo e(now()->format('F j, Y')); ?>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Stats -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card card-hover border-primary shadow-sm">
                <div class="card-body text-center py-4">
                    <i class="fas fa-door-open fa-2x text-primary mb-3"></i>
                    <h5 class="text-dark">कोठा नं.</h5>
                    <h3 class="text-primary fw-bold"><?php echo e($student->room->room_number ?? 'उपलब्ध छैन'); ?></h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card card-hover border-success shadow-sm">
                <div class="card-body text-center py-4">
                    <i class="fas fa-utensils fa-2x text-success mb-3"></i>
                    <h5 class="text-dark">आजको खाना</h5>
                    <h3 class="text-success fw-bold"><?php echo e($todayMeal ? 'उपलब्ध' : 'हाल अपडेट छैन'); ?></h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card card-hover border-warning shadow-sm">
                <div class="card-body text-center py-4">
                    <i class="fas fa-receipt fa-2x text-warning mb-3"></i>
                    <h5 class="text-dark">भुक्तानी</h5>
                    <h3 class="text-warning fw-bold">
                        <?php if($paymentStatus == 'Paid'): ?>
                            भुक्तानी भएको
                        <?php else: ?>
                            बाकी
                        <?php endif; ?>
                    </h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <!-- ✅ UPDATED: Circulars Card -->
            <div class="card card-hover border-info shadow-sm">
                <div class="card-body text-center py-4">
                    <i class="fas fa-bullhorn fa-2x text-info mb-3"></i>
                    <h5 class="text-dark">सूचनाहरू</h5>
                    <h3 class="text-info fw-bold"><?php echo e($unreadCirculars ?? 0); ?></h3>
                    <?php if(($unreadCirculars ?? 0) > 0): ?>
                        <span class="badge bg-danger position-absolute top-0 start-100 translate-middle">
                            नयाँ
                        </span>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Left Column - Main Content -->
        <div class="col-lg-8">
            <!-- Room & Payment Information -->
            <div class="row mb-4">
                <div class="col-md-6">
                    <div class="card shadow-sm border-0 h-100">
                        <div class="card-header bg-primary text-white py-3">
                            <h5 class="mb-0 fw-bold"><i class="fas fa-home me-2"></i>कोठा जानकारी</h5>
                        </div>
                        <div class="card-body">
                            <table class="table table-borderless">
                                <tr>
                                    <td class="text-dark"><strong>होस्टेल:</strong></td>
                                    <td class="text-dark"><?php echo e($hostel->name ?? 'उपलब्ध छैन'); ?></td>
                                </tr>
                                <tr>
                                    <td class="text-dark"><strong>कोठा नं.:</strong></td>
                                    <td class="text-dark"><?php echo e($student->room->room_number ?? 'उपलब्ध छैन'); ?></td>
                                </tr>
                                <tr>
                                    <td class="text-dark"><strong>कोठा प्रकार:</strong></td>
                                    <td class="text-dark"><?php echo e($student->room->type ?? 'उपलब्ध छैन'); ?></td>
                                </tr>
                                <tr>
                                    <td class="text-dark"><strong>मासिक भुक्तानी:</strong></td>
                                    <td class="text-success fw-bold">रु. <?php echo e($student->room->rent ?? 'उपलब्ध छैन'); ?></td>
                                </tr>
                            </table>
                            <button class="btn btn-outline-primary btn-sm" data-bs-toggle="modal" data-bs-target="#roomDetailsModal">
                                <i class="fas fa-info-circle me-1"></i>पूर्ण विवरण हेर्नुहोस्
                            </button>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="card shadow-sm border-0 h-100">
                        <div class="card-header bg-warning text-dark py-3">
                            <h5 class="mb-0 fw-bold"><i class="fas fa-credit-card me-2"></i>भुक्तानी स्थिति</h5>
                        </div>
                        <div class="card-body">
                            <table class="table table-borderless">
                                <tr>
                                    <td class="text-dark"><strong>अन्तिम भुक्तानी:</strong></td>
                                    <td class="text-dark"><?php echo e($lastPayment ? 'रु. ' . $lastPayment->amount : 'कुनै भुक्तानी छैन'); ?></td>
                                </tr>
                                <tr>
                                    <td class="text-dark"><strong>अन्तिम मिति:</strong></td>
                                    <td class="text-dark"><?php echo e($lastPayment ? $lastPayment->created_at->format('Y-m-d') : 'हाल अपडेट छैन'); ?></td>
                                </tr>
                                <tr>
                                    <td class="text-dark"><strong>स्थिति:</strong></td>
                                    <td>
                                        <span class="badge bg-<?php echo e($paymentStatus == 'Paid' ? 'success' : 'danger'); ?> p-2">
                                            <?php if($paymentStatus == 'Paid'): ?>
                                                भुक्तानी भएको
                                            <?php else: ?>
                                                बाकी
                                            <?php endif; ?>
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

            <!-- Today's Meal & Recent Circulars -->
            <div class="row mb-4">
                <div class="col-md-6">
                    <div class="card shadow-sm border-0 h-100">
                        <div class="card-header bg-success text-white py-3">
                            <h5 class="mb-0 fw-bold"><i class="fas fa-utensils me-2"></i>आजको खानाको योजना</h5>
                        </div>
                        <div class="card-body">
                            <?php if($todayMeal): ?>
                                <h6 class="text-success fw-bold"><?php echo e($todayMeal->meal_type); ?></h6>
                                <?php if(is_array($todayMeal->items)): ?>
                                    <?php if(isset($todayMeal->items['breakfast'])): ?>
                                        <p class="mb-2 text-dark"><strong>बिहानको खाना:</strong><br><?php echo e($todayMeal->items['breakfast']); ?></p>
                                    <?php endif; ?>
                                    <?php if(isset($todayMeal->items['lunch'])): ?>
                                        <p class="mb-2 text-dark"><strong>दिउँसोको खाना:</strong><br><?php echo e($todayMeal->items['lunch']); ?></p>
                                    <?php endif; ?>
                                    <?php if(isset($todayMeal->items['dinner'])): ?>
                                        <p class="mb-2 text-dark"><strong>रातिको खाना:</strong><br><?php echo e($todayMeal->items['dinner']); ?></p>
                                    <?php endif; ?>
                                <?php else: ?>
                                    <p class="mb-2 text-dark"><strong>मुख्य खाना:</strong> <?php echo e($todayMeal->main_dish ?? 'उपलब्ध छैन'); ?></p>
                                    <p class="mb-2 text-dark"><strong>साइड डिश:</strong> <?php echo e($todayMeal->side_dish ?? 'उपलब्ध छैन'); ?></p>
                                <?php endif; ?>
                                <p class="mb-0 text-dark"><strong>समय:</strong> <?php echo e($todayMeal->serving_time ?? 'उपलब्ध छैन'); ?></p>
                            <?php else: ?>
                                <div class="text-center py-3">
                                    <i class="fas fa-utensils fa-2x text-muted mb-2"></i>
                                    <p class="text-muted mb-0">आजको खानाको योजना हाल अपडेट छैन</p>
                                </div>
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
                    <!-- ✅ UPDATED: Recent Circulars Section -->
                    <div class="card shadow-sm border-0 h-100">
                        <div class="card-header bg-info text-white py-3">
                            <h5 class="mb-0 fw-bold"><i class="fas fa-bullhorn me-2"></i>हालैका सूचनाहरू</h5>
                        </div>
                        <div class="card-body">
                            <?php if($recentStudentCirculars && $recentStudentCirculars->count() > 0): ?>
                                <div class="list-group list-group-flush">
                                    <?php $__currentLoopData = $recentStudentCirculars->take(3); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $circular): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <div class="list-group-item px-0 py-2 border-0">
                                            <div class="d-flex justify-content-between align-items-start">
                                                <div class="flex-grow-1">
                                                    <small class="text-muted"><?php echo e($circular->created_at->diffForHumans()); ?></small>
                                                    <p class="mb-1 small text-dark fw-bold"><?php echo e(Str::limit($circular->title, 40)); ?></p>
                                                    <p class="mb-0 small text-muted"><?php echo e(Str::limit($circular->content, 50)); ?></p>
                                                </div>
                                                <div class="ms-2">
                                                    <?php if(!$circular->recipients->where('user_id', auth()->id())->first()?->is_read): ?>
                                                        <span class="badge bg-danger">नयाँ</span>
                                                    <?php endif; ?>
                                                    <?php if($circular->priority == 'urgent'): ?>
                                                        <span class="badge bg-warning text-dark">जरुरी</span>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </div>
                                <a href="<?php echo e(route('student.circulars.index')); ?>" class="btn btn-outline-info btn-sm mt-2 w-100">
                                    <i class="fas fa-list me-1"></i>सबै सूचनाहरू हेर्नुहोस्
                                </a>
                            <?php else: ?>
                                <div class="text-center py-3">
                                    <i class="fas fa-bullhorn fa-2x text-muted mb-2"></i>
                                    <p class="text-muted mb-0">कुनै नयाँ सूचना छैन</p>
                                    <a href="<?php echo e(route('student.circulars.index')); ?>" class="btn btn-outline-info btn-sm mt-2">
                                        सबै सूचनाहरू हेर्नुहोस्
                                    </a>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ✅ ADDED: Important Circulars Section -->
            <?php if($importantCirculars && $importantCirculars->count() > 0): ?>
            <div class="row mb-4">
                <div class="col-12">
                    <div class="card shadow-sm border-danger">
                        <div class="card-header bg-danger text-white py-3">
                            <h5 class="mb-0 fw-bold"><i class="fas fa-exclamation-triangle me-2"></i>जरुरी सूचनाहरू</h5>
                        </div>
                        <div class="card-body">
                            <div class="list-group">
                                <?php $__currentLoopData = $importantCirculars->take(2); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $circular): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <a href="<?php echo e(route('student.circulars.show', $circular)); ?>" 
                                       class="list-group-item list-group-item-action border-0 mb-2 rounded">
                                        <div class="d-flex w-100 justify-content-between">
                                            <h6 class="mb-1 fw-bold text-danger"><?php echo e($circular->title); ?></h6>
                                            <small class="text-muted"><?php echo e($circular->created_at->diffForHumans()); ?></small>
                                        </div>
                                        <p class="mb-1"><?php echo e(Str::limit($circular->content, 80)); ?></p>
                                        <?php if(!$circular->recipients->where('user_id', auth()->id())->first()?->is_read): ?>
                                            <span class="badge bg-danger">नयाँ</span>
                                        <?php endif; ?>
                                    </a>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php endif; ?>
        </div>

        <!-- Right Column - Sidebar -->
        <div class="col-lg-4">
            <!-- Quick Actions -->
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-secondary text-white py-3">
                    <h5 class="mb-0 fw-bold"><i class="fas fa-bolt me-2"></i>द्रुत कार्यहरू</h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="<?php echo e(route('student.profile')); ?>" class="btn btn-outline-primary text-start py-2">
                            <i class="fas fa-user me-2"></i>मेरो प्रोफाइल
                        </a>
                        <a href="<?php echo e(route('student.meal-menus')); ?>" class="btn btn-outline-success text-start py-2">
                            <i class="fas fa-utensils me-2"></i>खानाको योजना
                        </a>
                        
                        <!-- ✅ ADDED: Circulars Quick Actions -->
                        <a href="<?php echo e(route('student.circulars.index')); ?>" class="btn btn-outline-info text-start py-2 position-relative">
                            <i class="fas fa-bullhorn me-2"></i>सबै सूचनाहरू
                            <?php if(($unreadCirculars ?? 0) > 0): ?>
                                <span class="badge bg-danger position-absolute top-0 start-100 translate-middle">
                                    <?php echo e($unreadCirculars); ?>

                                </span>
                            <?php endif; ?>
                        </a>

                        <button class="btn btn-outline-warning text-start py-2" data-bs-toggle="modal" data-bs-target="#paymentModal">
                            <i class="fas fa-credit-card me-2"></i>भुक्तानी गर्नुहोस्
                        </button>
                        <a href="<?php echo e(route('student.reviews')); ?>" class="btn btn-outline-dark text-start py-2">
                            <i class="fas fa-star me-2"></i>समीक्षा लेख्नुहोस्
                        </a>
                        <button class="btn btn-outline-danger text-start py-2" data-bs-toggle="modal" data-bs-target="#maintenanceModal">
                            <i class="fas fa-tools me-2"></i>मर्मत समस्या
                        </button>
                    </div>
                </div>
            </div>

            <!-- Upcoming Events -->
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-purple text-white py-3">
                    <h5 class="mb-0 fw-bold"><i class="fas fa-calendar-alt me-2"></i>आगामी घटनाहरू</h5>
                </div>
                <div class="card-body">
                    <?php if($upcomingEvents->count() > 0): ?>
                        <div class="list-group list-group-flush">
                            <?php $__currentLoopData = $upcomingEvents->take(3); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $event): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="list-group-item px-0 py-2 border-0">
                                    <h6 class="mb-1 text-primary fw-bold"><?php echo e($event->title); ?></h6>
                                    <small class="text-muted">
                                        <i class="fas fa-clock me-1"></i>
                                        <?php echo e($event->date->format('M j')); ?> at <?php echo e($event->time); ?>

                                    </small>
                                    <p class="mb-0 small text-dark"><?php echo e(Str::limit($event->description, 40)); ?></p>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                        <a href="<?php echo e(route('student.events')); ?>" class="btn btn-outline-purple btn-sm mt-2 w-100">
                            सबै घटनाहरू हेर्नुहोस्
                        </a>
                    <?php else: ?>
                        <div class="text-center py-3">
                            <i class="fas fa-calendar-times fa-2x text-muted mb-2"></i>
                            <p class="text-muted mb-0">कुनै आगामी घटना छैन</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Hostel Gallery Preview -->
            <div class="card shadow-sm border-0">
                <div class="card-header bg-dark text-white py-3">
                    <h5 class="mb-0 fw-bold"><i class="fas fa-images me-2"></i>होस्टेल ग्यालेरी</h5>
                </div>
                <div class="card-body">
                    <?php if($galleryImages->count() > 0): ?>
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
                    <?php else: ?>
                        <div class="text-center py-3">
                            <i class="fas fa-images fa-2x text-muted mb-2"></i>
                            <p class="text-muted mb-0">ग्यालेरी उपलब्ध छैन</p>
                        </div>
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
    box-shadow: 0 8px 25px rgba(0,0,0,0.15) !important;
}
.bg-purple {
    background-color: #6f42c1 !important;
}
.bg-gradient-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
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
.card {
    border-radius: 12px;
    overflow: hidden;
}
.card-header {
    border-radius: 12px 12px 0 0 !important;
}
.list-group-item {
    border-radius: 8px !important;
}

/* ✅ FIXED: Proper spacing for sidebar cards */
.col-lg-4 {
    display: flex;
    flex-direction: column;
    gap: 1.5rem; /* This ensures consistent spacing between cards */
}

.col-lg-4 .card {
    margin-bottom: 0 !important; /* Remove any existing margins */
}

/* ✅ FIXED: Ensure proper height distribution */
.col-lg-4 .card {
    flex: 0 0 auto; /* Don't grow or shrink, use auto height */
}

/* ✅ FIXED: Mobile responsive spacing */
@media (max-width: 991.98px) {
    .col-lg-4 {
        margin-top: 2rem;
        gap: 1rem;
    }
}

/* ✅ FIXED: Consistent card heights in left columns */
.col-lg-8 .card {
    height: 100%;
    display: flex;
    flex-direction: column;
}

.col-lg-8 .card-body {
    flex: 1;
}
</style>

<script>
function openImageModal(imageUrl) {
    document.getElementById('galleryImage').src = imageUrl;
    new bootstrap.Modal(document.getElementById('galleryViewModal')).show();
}

// ✅ ADDED: Ensure proper layout after page load
document.addEventListener('DOMContentLoaded', function() {
    // Force reflow to fix any layout issues
    setTimeout(function() {
        document.body.classList.add('loaded');
    }, 100);
});
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.student', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\My Projects\HostelHub\resources\views/student/dashboard.blade.php ENDPATH**/ ?>