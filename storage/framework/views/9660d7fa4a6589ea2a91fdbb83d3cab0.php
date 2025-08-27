<?php $__env->startSection('content'); ?>
<div class="container mx-auto px-4 py-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">Gallery Management</h1>
        <a href="<?php echo e(route('admin.gallery.create')); ?>" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded">
            + Add New
        </a>
    </div>

    <div class="bg-white rounded-lg shadow overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left">Thumbnail</th>
                    <th class="px-6 py-3 text-left">Title</th>
                    <th class="px-6 py-3 text-left">Type</th>
                    <th class="px-6 py-3 text-left">Status</th>
                    <th class="px-6 py-3 text-left">Featured</th>
                    <th class="px-6 py-3 text-left">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                <?php $__currentLoopData = $galleries; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>
                    <td class="px-6 py-4">
                        <?php if($item->media_type === 'photo' && $item->file_path): ?>
                            <img src="<?php echo e(asset('storage/'.$item->file_path)); ?>" alt="Thumb" class="w-16 h-16 object-cover rounded">
                        <?php elseif($item->media_type === 'local_video' && $item->file_path): ?>
                            <video width="64" height="64" class="rounded" controls>
                                <source src="<?php echo e(asset('storage/'.$item->file_path)); ?>" type="video/mp4">
                                Your browser does not support the video tag.
                            </video>
                        <?php elseif($item->media_type === 'youtube' && $item->external_link): ?>
                            <?php
                                // Extract YouTube ID from URL
                                $youtubeId = '';
                                $pattern = '/(?:youtube\.com\/(?:[^\/\n\s]+\/\S+\/|(?:v|e(?:mbed)?)\/|\S*?[?&]v=)|youtu\.be\/)([a-zA-Z0-9_-]{11})/';
                                preg_match($pattern, $item->external_link, $matches);
                                $youtubeId = $matches[1] ?? '';
                            ?>
                            <?php if($youtubeId): ?>
                                <iframe 
                                    src="https://www.youtube.com/embed/<?php echo e($youtubeId); ?>"
                                    width="64" 
                                    height="64" 
                                    frameborder="0" 
                                    allowfullscreen
                                    class="rounded">
                                </iframe>
                            <?php else: ?>
                                <div class="bg-gray-200 border-2 border-dashed rounded-xl w-16 h-16 flex items-center justify-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-gray-500" viewBox="0 0 20 20" fill="currentColor">
                                        <path d="M2 6a2 2 0 012-2h6a2 2 0 012 2v8a2 2 0 01-2 2H4a2 2 0 01-2-2V6zM14.553 7.106A1 1 0 0014 8v4a1 1 0 00.553.894l2 1A1 1 0 0018 13V7a1 1 0 00-1.447-.894l-2 1z" />
                                    </svg>
                                </div>
                            <?php endif; ?>
                        <?php else: ?>
                            <div class="bg-gray-200 border-2 border-dashed rounded-xl w-16 h-16"></div>
                        <?php endif; ?>
                    </td>
                    <td class="px-6 py-4"><?php echo e($item->title); ?></td>
                    <td class="px-6 py-4 capitalize"><?php echo e($item->media_type); ?></td>
                    <td class="px-6 py-4">
                        <form id="toggle-status-<?php echo e($item->id); ?>" action="<?php echo e(route('admin.gallery.toggle-status', $item)); ?>" method="POST">
                            <?php echo csrf_field(); ?>
                            <?php echo method_field('PUT'); ?>
                            <button type="button" 
                                    onclick="toggleStatus(<?php echo e($item->id); ?>)"
                                    class="px-3 py-1 rounded-full text-xs font-medium 
                                            <?php echo e($item->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'); ?>">
                                <?php echo e($item->is_active ? 'Active' : 'Inactive'); ?>

                            </button>
                        </form>
                    </td>
                    <td class="px-6 py-4">
                        <form id="toggle-featured-<?php echo e($item->id); ?>" action="<?php echo e(route('admin.gallery.toggle-featured', $item)); ?>" method="POST">
                            <?php echo csrf_field(); ?>
                            <?php echo method_field('PUT'); ?>
                            <button type="button" 
                                    onclick="toggleFeatured(<?php echo e($item->id); ?>)"
                                    class="px-3 py-1 rounded-full text-xs font-medium 
                                            <?php echo e($item->is_featured ? 'bg-purple-100 text-purple-800' : 'bg-gray-100 text-gray-800'); ?>">
                                <?php echo e($item->is_featured ? 'Yes' : 'No'); ?>

                            </button>
                        </form>
                    </td>
                    <td class="px-6 py-4 flex space-x-2">
                        <a href="<?php echo e(route('admin.gallery.edit', $item)); ?>" class="text-blue-500 hover:text-blue-700">
                            Edit
                        </a>
                        <form action="<?php echo e(route('admin.gallery.destroy', $item)); ?>" method="POST">
                            <?php echo csrf_field(); ?>
                            <?php echo method_field('DELETE'); ?>
                            <button type="submit" class="text-red-500 hover:text-red-700" 
                                onclick="return confirm('Are you sure?')">Delete</button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>
    </div>
    
    <div class="mt-4">
        <?php echo e($galleries->links()); ?>

    </div>
</div>

<script>
    function toggleStatus(itemId) {
        if (confirm('Are you sure you want to change the status?')) {
            document.getElementById(`toggle-status-${itemId}`).submit();
        }
    }
    
    function toggleFeatured(itemId) {
        if (confirm('Are you sure you want to change the featured status?')) {
            document.getElementById(`toggle-featured-${itemId}`).submit();
        }
    }
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\My Projects\HostelHub\resources\views/admin/gallery/index.blade.php ENDPATH**/ ?>