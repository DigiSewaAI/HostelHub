<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
  'title' => 'Plan',
  'price' => 'रु. 0',
  'period' => '/महिना',
  'features' => [],
  'cta' => '#',
  'popular' => false,
  'limit' => null,
  'badgeText' => 'लोकप्रिय',
]));

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

foreach (array_filter(([
  'title' => 'Plan',
  'price' => 'रु. 0',
  'period' => '/महिना',
  'features' => [],
  'cta' => '#',
  'popular' => false,
  'limit' => null,
  'badgeText' => 'लोकप्रिय',
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<div class="relative bg-white rounded-2xl shadow-md p-6 border hover:shadow-lg overflow-visible">
  <?php if($popular): ?>
    <span class="absolute -top-3 left-1/2 -translate-x-1/2 bg-rose-600 text-white text-xs font-semibold px-3 py-1 rounded-full shadow">
      <?php echo e($badgeText); ?>

    </span>
  <?php endif; ?>

  <h3 class="text-xl font-bold text-gray-900 text-center"><?php echo e($title); ?></h3>

  <div class="mt-2 text-center">
    <div class="text-3xl font-extrabold"><?php echo e($price); ?></div>
    <div class="text-gray-500"><?php echo e($period); ?></div>
    <?php if($limit): ?>
      <div class="mt-1 text-sm text-gray-600"><?php echo e($limit); ?></div>
    <?php endif; ?>
  </div>

  <ul class="mt-5 space-y-2 text-sm text-gray-700">
    <?php $__currentLoopData = $features; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $f): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
      <li class="flex items-start gap-2">
        <span class="mt-1">✔</span>
        <span><?php echo e($f); ?></span>
      </li>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
  </ul>

  <a href="<?php echo e($cta); ?>"
     class="mt-6 inline-flex justify-center w-full rounded-xl bg-gray-900 text-white py-2.5 font-medium hover:bg-black">
     योजना छान्नुहोस्
  </a>
</div><?php /**PATH D:\My Projects\HostelHub\resources\views\components\plan-card.blade.php ENDPATH**/ ?>