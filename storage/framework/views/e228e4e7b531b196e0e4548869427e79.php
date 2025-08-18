<?php $__env->startSection('content'); ?>
<div class="container mx-auto px-4 py-6">
    <h1 class="text-2xl font-bold mb-6"><?php echo e($gallery->title); ?></h1>
    
    <div class="bg-white p-6 rounded-lg shadow">
        <?php if($gallery->type === 'photo'): ?>
            <img src="<?php echo e(asset('storage/'.$gallery->file_path)); ?>" alt="<?php echo e($gallery->title); ?>" class="mb-4 max-w-full">
        <?php elseif($gallery->type === 'local_video'): ?>
            <video controls class="mb-4 w-full">
                <source src="<?php echo e(asset('storage/'.$gallery->file_path)); ?>" type="video/mp4">
            </video>
        <?php else: ?>
            <iframe src="https://www.youtube.com/embed/<?php echo e(getYoutubeId($gallery->external_link)); ?>" 
                    class="w-full h-96 mb-4"></iframe>
        <?php endif; ?>

        <div class="prose max-w-none">
            <?php echo $gallery->description; ?>

        </div>

        <div class="mt-4 flex space-x-4">
            <span class="px-3 py-1 rounded-full text-sm 
                  <?php echo e($gallery->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'); ?>">
                <?php echo e($gallery->is_active ? 'Active' : 'Inactive'); ?>

            </span>
            <?php if($gallery->is_featured): ?>
                <span class="px-3 py-1 rounded-full text-sm bg-purple-100 text-purple-800">
                    Featured
                </span>
            <?php endif; ?>
        </div>
    </div>

    <div class="mt-6">
        <a href="<?php echo e(route('admin.gallery.index')); ?>" class="bg-gray-400 hover:bg-gray-500 text-white px-4 py-2 rounded">
            Back to List
        </a>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\My Projects\HostelHub\resources\views\admin\gallery\show.blade.php ENDPATH**/ ?>