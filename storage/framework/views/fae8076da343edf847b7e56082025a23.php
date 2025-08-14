<?php $__env->startSection('content'); ?>
<div class="container mx-auto px-4 py-6">
    <h1 class="text-2xl font-bold mb-6">Add Gallery Item</h1>
    
    <form action="<?php echo e(route('admin.gallery.store')); ?>" method="POST" enctype="multipart/form-data" class="max-w-3xl">
        <?php echo csrf_field(); ?>
        
        <div class="grid gap-6">
            <?php echo $__env->make('admin.gallery._form', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
            
            <div class="flex justify-end space-x-4">
                <a href="<?php echo e(route('admin.gallery.index')); ?>" class="bg-gray-400 hover:bg-gray-500 text-white px-4 py-2 rounded">
                    Cancel
                </a>
                <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded">
                    Save Item
                </button>
            </div>
        </div>
    </form>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\My Projects\HostelHub\resources\views/admin/gallery/create.blade.php ENDPATH**/ ?>