<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['title', 'icon', 'color' => 'blue', 'route' => '#']));

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

foreach (array_filter((['title', 'icon', 'color' => 'blue', 'route' => '#']), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<?php
    $colorClasses = [
        'blue' => ['bg' => 'bg-blue-50', 'hover' => 'hover:bg-blue-100', 'text' => 'text-blue-800', 'icon' => 'text-blue-600'],
        'green' => ['bg' => 'bg-green-50', 'hover' => 'hover:bg-green-100', 'text' => 'text-green-800', 'icon' => 'text-green-600'],
        'indigo' => ['bg' => 'bg-indigo-50', 'hover' => 'hover:bg-indigo-100', 'text' => 'text-indigo-800', 'icon' => 'text-indigo-600'],
        'teal' => ['bg' => 'bg-teal-50', 'hover' => 'hover:bg-teal-100', 'text' => 'text-teal-800', 'icon' => 'text-teal-600'],
        'amber' => ['bg' => 'bg-amber-50', 'hover' => 'hover:bg-amber-100', 'text' => 'text-amber-800', 'icon' => 'text-amber-600'],
        'purple' => ['bg' => 'bg-purple-50', 'hover' => 'hover:bg-purple-100', 'text' => 'text-purple-800', 'icon' => 'text-purple-600'],
    ][$color];
?>

<a href="<?php echo e($route); ?>" class="quick-action block p-4 <?php echo e($colorClasses['bg']); ?> <?php echo e($colorClasses['hover']); ?> rounded-2xl text-center transition-all duration-300 group border border-gray-200">
    <div class="<?php echo e($colorClasses['icon']); ?> text-2xl mb-2 transition-transform duration-300 group-hover:scale-110">
        <i class="fas fa-<?php echo e($icon); ?>"></i>
    </div>
    <div class="font-medium <?php echo e($colorClasses['text']); ?> text-sm"><?php echo e($title); ?></div>
</a><?php /**PATH D:\My Projects\HostelHub\resources\views\components\quick-action.blade.php ENDPATH**/ ?>