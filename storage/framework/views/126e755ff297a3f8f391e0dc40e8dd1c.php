<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['status']));

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

foreach (array_filter((['status']), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<?php
    $statusConfig = [
        'pending' => ['class' => 'warning', 'text' => 'पेन्डिङ'],
        'approved' => ['class' => 'success', 'text' => 'स्वीकृत'],
        'rejected' => ['class' => 'danger', 'text' => 'अस्वीकृत'],
        'cancelled' => ['class' => 'secondary', 'text' => 'रद्द भएको'],
        'completed' => ['class' => 'info', 'text' => 'पूर्ण भएको']
    ];
    
    $config = $statusConfig[$status] ?? ['class' => 'secondary', 'text' => $status];
?>

<span class="badge badge-<?php echo e($config['class']); ?>"><?php echo e($config['text']); ?></span><?php /**PATH C:\laragon\www\HostelHub\resources\views\components\status-badge.blade.php ENDPATH**/ ?>