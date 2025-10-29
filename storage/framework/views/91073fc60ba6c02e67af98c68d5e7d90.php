<?php $__env->startSection('title', 'ग्यालेरी आइटम थप्नुहोस्'); ?>

<?php $__env->startSection('content'); ?>
<div class="container mx-auto px-4 py-6">
    <h1 class="text-2xl font-bold text-gray-800 mb-6">📸 ग्यालेरी आइटम थप्नुहोस्</h1>

    <?php if($errors->any()): ?>
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
            <ul>
                <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <li><?php echo e($error); ?></li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </ul>
        </div>
    <?php endif; ?>

    <?php if(session('success')): ?>
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
            <?php echo e(session('success')); ?>

        </div>
    <?php endif; ?>

    <form action="<?php echo e(route('admin.galleries.store')); ?>" method="POST" enctype="multipart/form-data">
        <?php echo csrf_field(); ?>

        <div class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
            <?php echo $__env->make('admin.galleries._form', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        </div>

        <!-- Save & Cancel Buttons -->
        <div class="flex justify-end space-x-4 mt-8">
            <a href="<?php echo e(route('admin.galleries.index')); ?>" 
               class="bg-gray-400 hover:bg-gray-500 text-white px-5 py-2 rounded transition duration-200">
                रद्द गर्नुहोस्
            </a>
            <button type="submit" 
                    class="bg-blue-500 hover:bg-blue-600 text-white px-5 py-2 rounded transition duration-200 shadow">
                💾 आइटम सुरक्षित गर्नुहोस्
            </button>
        </div>
    </form>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\My Projects\HostelHub\resources\views\admin\galleries\create.blade.php ENDPATH**/ ?>