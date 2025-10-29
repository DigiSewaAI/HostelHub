<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['booking']));

foreach ($attributes->all() as $__key => $__value) {
    if (in_array($__key, $__propNames)) {
        $$__key = $$__key ?? $__value;
    } else {
        $__newAttributes[$__key] = $__value;
    }
}

$attributes = new \Illuminate\View\ComponentAttributeBag($__newAttributes);

unset($__propNames);
unset($__newAttributes);

foreach (array_filter((['booking']), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<div class="card booking-card mb-3">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h6 class="m-0 font-weight-bold">बुकिंग #<?php echo e($booking->id); ?></h6>
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
            <div class="col-md-8">
                <p class="mb-1"><strong>होस्टेल:</strong> <?php echo e($booking->room->hostel->name); ?></p>
                <p class="mb-1"><strong>कोठा:</strong> <?php echo e($booking->room->room_number); ?></p>
                <p class="mb-1"><strong>मिति:</strong> 
                    <?php echo e($booking->check_in_date->format('Y-m-d')); ?> - <?php echo e($booking->check_out_date->format('Y-m-d')); ?>

                </p>
                <p class="mb-0"><strong>उद्देश्य:</strong> <?php echo e(Str::limit($booking->purpose, 100)); ?></p>
            </div>
            <div class="col-md-4 text-right">
                <p class="text-muted small"><?php echo e($booking->created_at->diffForHumans()); ?></p>
                <?php if(isset($showActions) && $showActions): ?>
                <a href="<?php echo e(route('student.bookings.show', $booking->id)); ?>" class="btn btn-sm btn-primary">
                    विवरण हेर्नुहोस्
                </a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div><?php /**PATH D:\My Projects\HostelHub\resources\views\components\booking-card.blade.php ENDPATH**/ ?>