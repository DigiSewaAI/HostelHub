

<?php $__env->startSection('page-title', 'Gallery Unavailable | HostelHub'); ?>

<?php $__env->startSection('content'); ?>
<div class="container mx-auto py-20 px-4 text-center">
    <div class="max-w-md mx-auto">
        <svg class="w-24 h-24 mx-auto mb-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
        </svg>
        
        <h1 class="text-3xl font-bold text-gray-800 mb-4 nepali">ग्यालरी उपलब्ध छैन</h1>
        <p class="text-gray-600 mb-8 nepali">
            यो ग्यालरी हाल उपलब्ध छैन। कृपया पछि फेरि प्रयास गर्नुहोस् वा होस्टल प्रबन्धकसँग सम्पर्क गर्नुहोस्।
        </p>
        
        <div class="space-y-4">
            <a href="<?php echo e(url('/')); ?>" class="bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl px-6 py-3 nepali inline-block">
                होमपेजमा फर्कनुहोस्
            </a>
            <br>
            <a href="javascript:history.back()" class="border border-gray-300 text-gray-700 rounded-xl px-6 py-3 nepali inline-block hover:bg-gray-50">
                पछाडि जानुहोस्
            </a>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.frontend', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\HostelHub\resources\views\errors\gallery-unavailable.blade.php ENDPATH**/ ?>