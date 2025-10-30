<div class="rounded-lg overflow-hidden shadow-lg bg-white transition-transform duration-300 hover:shadow-xl hover:-translate-y-1">
    <?php if($item->type === 'photo'): ?>
        <img 
            src="<?php echo e(asset('storage/'.$item->file_path)); ?>" 
            alt="<?php echo e($item->title); ?>" 
            class="w-full h-48 object-cover"
        >
    <?php elseif($item->type === 'local_video'): ?>
        <video controls class="w-full h-48 object-cover">
            <source src="<?php echo e(asset('storage/'.$item->file_path)); ?>" type="video/mp4">
            Your browser does not support the video tag.
        </video>
    <?php else: ?>
        <iframe 
            src="https://www.youtube.com/embed/<?php echo e(getYoutubeId($item->external_link)); ?>" 
            class="w-full h-48" 
            frameborder="0" 
            allowfullscreen
        ></iframe>
    <?php endif; ?>
    
    <div class="p-4">
        <h3 class="font-bold text-lg mb-1"><?php echo e($item->title); ?></h3>
        <p class="text-gray-600 text-sm"><?php echo e(Str::limit($item->description, 80)); ?></p>
        
        <?php if($item->is_featured): ?>
            <span class="inline-block mt-2 px-2 py-1 text-xs bg-purple-100 text-purple-800 rounded-full">
                Featured
            </span>
        <?php endif; ?>
    </div>
</div><?php /**PATH C:\laragon\www\HostelHub\resources\views\frontend\partials\gallery-item.blade.php ENDPATH**/ ?>