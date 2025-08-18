<?php $__env->startSection('title', 'उपलब्ध कोठाहरू'); ?>

<?php $__env->startSection('content'); ?>
<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold text-center mb-8">हाम्रो उपलब्ध कोठाहरू</h1>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        <?php $__currentLoopData = $rooms; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $room): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div class="bg-white rounded-2xl overflow-hidden shadow-xl hover:shadow-2xl transition-shadow border border-gray-100">
            <div class="p-6 bg-gradient-to-r from-blue-500 to-indigo-600">
                <div class="text-2xl font-bold text-white mb-2">कोठा नं. <?php echo e($room->room_number); ?></div>
                <p class="text-blue-100">भुक्तानी: रु <?php echo e(number_format($room->price)); ?></p>
            </div>
            <div class="p-6">
                <div class="flex justify-between items-center mb-4">
                    <span class="bg-green-100 text-green-800 text-xs font-semibold px-2.5 py-0.5 rounded">
                        <?php echo e($room->capacity); ?> जनासम्म बस्न सकिने
                    </span>
                    <span class="text-sm text-gray-500">
                        <?php echo e($room->students_count); ?>/<?php echo e($room->capacity); ?> भरिएको
                    </span>
                </div>
                <p class="text-gray-600 mb-6"><?php echo e($room->description); ?></p>
                <a href="#" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-4 rounded-xl transition-colors text-center block">
                    अहिले बुक गर्नुहोस्
                </a>
            </div>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>

    <div class="mt-8">
        <?php echo e($rooms->links()); ?>

    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\My Projects\HostelHub\resources\views\public\rooms.blade.php ENDPATH**/ ?>