<?php if(isset($hostel->images) && count($hostel->images) > 0): ?>
<section class="gallery-section mt-8">
    <h2 class="text-2xl font-bold text-gray-900 nepali mb-6">ग्यालरी</h2>
    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
        <?php $__currentLoopData = $hostel->images; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $image): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div class="aspect-square rounded-lg overflow-hidden border border-gray-200">
            <img src="<?php echo e(asset('storage/' . $image)); ?>" 
                 alt="<?php echo e($hostel->name); ?> - Image <?php echo e($loop->iteration); ?>"
                 class="w-full h-full object-cover hover:scale-105 transition-transform duration-300">
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
</section>
<?php endif; ?><?php /**PATH D:\My Projects\HostelHub\resources\views/public/hostels/partials/gallery.blade.php ENDPATH**/ ?>