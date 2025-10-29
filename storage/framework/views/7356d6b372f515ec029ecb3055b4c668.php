<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['title', 'value', 'icon', 'color' => 'blue', 'description' => '']));

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

foreach (array_filter((['title', 'value', 'icon', 'color' => 'blue', 'description' => '']), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<?php
    $colorClasses = [
        'blue' => ['bg' => 'from-blue-50 to-blue-100', 'border' => 'border-blue-500', 'icon' => 'bg-blue-500'],
        'green' => ['bg' => 'from-green-50 to-green-100', 'border' => 'border-green-500', 'icon' => 'bg-green-500'],
        'amber' => ['bg' => 'from-amber-50 to-amber-100', 'border' => 'border-amber-500', 'icon' => 'bg-amber-500'],
        'indigo' => ['bg' => 'from-indigo-50 to-indigo-100', 'border' => 'border-indigo-500', 'icon' => 'bg-indigo-500'],
        'purple' => ['bg' => 'from-purple-50 to-purple-100', 'border' => 'border-purple-500', 'icon' => 'bg-purple-500'],
    ][$color];
?>

<div class="stat-card bg-gradient-to-r <?php echo e($colorClasses['bg']); ?> border-l-4 <?php echo e($colorClasses['border']); ?> p-6 rounded-2xl shadow-sm transition-all duration-300 hover:shadow-md">
    <div class="flex justify-between items-start">
        <div class="flex-1">
            <h3 class="text-lg font-bold text-gray-800"><?php echo e($title); ?></h3>
            <p class="text-3xl font-bold mt-2 text-gray-900"><?php echo e($value); ?></p>
            <?php if($description): ?>
                <p class="text-sm text-gray-600 mt-3"><?php echo e($description); ?></p>
            <?php endif; ?>
        </div>
        <div class="<?php echo e($colorClasses['icon']); ?> text-white p-3 rounded-xl">
            <i class="fas fa-<?php echo e($icon); ?> text-xl"></i>
        </div>
    </div>
</div><?php /**PATH D:\My Projects\HostelHub\resources\views\components\stat-card.blade.php ENDPATH**/ ?>