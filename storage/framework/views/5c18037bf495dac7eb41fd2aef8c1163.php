

<?php $__env->startSection('title', 'बुकिंग विवरण #' . $booking->id); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">बुकिंग विवरण #<?php echo e($booking->id); ?></h1>
        <a href="<?php echo e(route('student.bookings.index')); ?>" class="btn btn-secondary btn-sm">
            <i class="fas fa-arrow-left"></i> बुकिंगहरूमा फर्कनुहोस्
        </a>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">बुकिंग जानकारी</h6>
                    <?php if (isset($component)) { $__componentOriginal8c81617a70e11bcf247c4db924ab1b62 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal8c81617a70e11bcf247c4db924ab1b62 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.status-badge','data' => ['status' => $booking->status]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('status-badge'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['status' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($booking->status)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal8c81617a70e11bcf247c4db924ab1b62)): ?>
<?php $attributes = $__attributesOriginal8c81617a70e11bcf247c4db924ab1b62; ?>
<?php unset($__attributesOriginal8c81617a70e11bcf247c4db924ab1b62); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal8c81617a70e11bcf247c4db924ab1b62)): ?>
<?php $component = $__componentOriginal8c81617a70e11bcf247c4db924ab1b62; ?>
<?php unset($__componentOriginal8c81617a70e11bcf247c4db924ab1b62); ?>
<?php endif; ?>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="font-weight-bold">होस्टेल विवरण</h6>
                            <p><strong>नाम:</strong> <?php echo e($booking->room->hostel->name); ?></p>
                            <p><strong>ठेगाना:</strong> <?php echo e($booking->room->hostel->location); ?></p>
                            <p><strong>कोठा नं.:</strong> <?php echo e($booking->room->room_number); ?></p>
                        </div>
                        <div class="col-md-6">
                            <h6 class="font-weight-bold">बुकिंग अवधि</h6>
                            <p><strong>चेक-इन:</strong> <?php echo e($booking->check_in_date->format('Y-m-d')); ?></p>
                            <p><strong>चेक-आउट:</strong> <?php echo e($booking->check_out_date->format('Y-m-d')); ?></p>
                            <p><strong>अवधि:</strong> <?php echo e($booking->check_in_date->diffInDays($booking->check_out_date)); ?> दिन</p>
                        </div>
                    </div>

                    <hr>

                    <div class="row">
                        <div class="col-12">
                            <h6 class="font-weight-bold">बुकिंग उद्देश्य</h6>
                            <p><?php echo e($booking->purpose); ?></p>
                        </div>
                    </div>

                    <?php if($booking->status === 'approved'): ?>
                    <div class="alert alert-success mt-3">
                        <i class="fas fa-check-circle"></i>
                        <strong>बुकिंग स्वीकृत भएको छ!</strong> 
                        कृपया भुक्तानी गर्नुहोस् र होस्टेल प्रबन्धकलाई सम्पर्क गर्नुहोस्।
                    </div>
                    <?php elseif($booking->status === 'rejected'): ?>
                    <div class="alert alert-danger mt-3">
                        <i class="fas fa-times-circle"></i>
                        <strong>बुकिंग अस्वीकृत भएको छ</strong>
                        <?php if($booking->rejection_reason): ?>
                        <br>कारण: <?php echo e($booking->rejection_reason); ?>

                        <?php endif; ?>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">कार्यहरू</h6>
                </div>
                <div class="card-body">
                    <?php if($booking->status === 'approved'): ?>
                    <a href="<?php echo e(route('student.payments.create', $booking->id)); ?>" class="btn btn-success btn-block mb-2">
                        <i class="fas fa-credit-card"></i> भुक्तानी गर्नुहोस्
                    </a>
                    <?php endif; ?>
                    
                    <?php if(in_array($booking->status, ['pending', 'approved'])): ?>
                    <form action="<?php echo e(route('student.bookings.cancel', $booking->id)); ?>" method="POST" class="d-inline">
                        <?php echo csrf_field(); ?>
                        <?php echo method_field('DELETE'); ?>
                        <button type="submit" class="btn btn-danger btn-block" 
                                onclick="return confirm('के तपाईं यो बुकिंग रद्द गर्न चाहनुहुन्छ?')">
                            <i class="fas fa-times"></i> बुकिंग रद्द गर्नुहोस्
                        </button>
                    </form>
                    <?php endif; ?>
                </div>
            </div>

            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">समयरेखा</h6>
                </div>
                <div class="card-body">
                    <div class="timeline">
                        <div class="timeline-item <?php echo e($booking->created_at ? 'active' : ''); ?>">
                            <small><?php echo e($booking->created_at->format('Y-m-d H:i')); ?></small>
                            <p>बुकिंग सिर्जना गरियो</p>
                        </div>
                        <?php if($booking->status === 'approved' || $booking->status === 'rejected'): ?>
                        <div class="timeline-item active">
                            <small><?php echo e($booking->updated_at->format('Y-m-d H:i')); ?></small>
                            <p>बुकिंग <?php echo e($booking->status === 'approved' ? 'स्वीकृत' : 'अस्वीकृत'); ?> गरियो</p>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.student', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\My Projects\HostelHub\resources\views\student\bookings\show.blade.php ENDPATH**/ ?>