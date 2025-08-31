<?php $__env->startSection('content'); ?>
<div class="container mx-auto px-4 py-6">
    <h1 class="text-2xl font-bold text-gray-800 mb-6">📸 ग्यालेरी आइटम थप्नुहोस्</h1>

    <?php if(session('success')): ?>
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
            <?php echo e(session('success')); ?>

        </div>
    <?php endif; ?>

    <form action="<?php echo e(route('admin.gallery.store')); ?>" method="POST" enctype="multipart/form-data" class="max-w-3xl bg-white p-6 rounded-lg shadow">
        <?php echo csrf_field(); ?>

        <?php echo $__env->make('admin.gallery._form', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

        <!-- Save & Cancel Buttons -->
        <div class="flex justify-end space-x-4 mt-8">
            <a href="<?php echo e(route('admin.gallery.index')); ?>" 
               class="bg-gray-400 hover:bg-gray-500 text-white px-5 py-2 rounded transition duration-200">
                Cancel
            </a>
            <button type="submit" 
                    class="bg-blue-500 hover:bg-blue-600 text-white px-5 py-2 rounded transition duration-200 shadow">
                💾 Save Item
            </button>
        </div>
    </form>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
<script>
// Media type based field toggling
document.addEventListener('DOMContentLoaded', function () {
    const mediaType = document.getElementById('media_type');
    const imageField = document.getElementById('image-field');
    const localVideoField = document.getElementById('local-video-field');
    const externalLinkField = document.getElementById('external-link-field');

    function toggleFields() {
        // Hide all
        imageField.style.display = 'none';
        localVideoField.style.display = 'none';
        externalLinkField.style.display = 'none';

        // Show relevant
        if (mediaType.value === 'photo') {
            imageField.style.display = 'block';
        } else if (mediaType.value === 'local_video') {
            localVideoField.style.display = 'block';
        } else if (mediaType.value === 'external_video') {
            externalLinkField.style.display = 'block';
        }
    }

    mediaType.addEventListener('change', toggleFields);
    toggleFields(); // Initialize on load
});
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\My Projects\HostelHub\resources\views/admin/gallery/create.blade.php ENDPATH**/ ?>