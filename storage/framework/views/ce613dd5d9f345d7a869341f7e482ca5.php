

<?php $__env->startSection('title', 'ग्यालरी व्यवस्थापन'); ?>

<?php $__env->startSection('content'); ?>
<div class="bg-white rounded-2xl shadow-sm p-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800 nepali">ग्यालरी व्यवस्थापन</h1>
        <a href="<?php echo e(route('owner.galleries.create')); ?>" 
           class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-3 rounded-xl font-medium no-underline transition-colors">
            <i class="fas fa-plus mr-2"></i>नयाँ ग्यालरी थप्नुहोस्
        </a>
    </div>

    <!-- Category Filter -->
    <div class="mb-6 bg-gray-50 p-4 rounded-xl border border-gray-200">
        <form method="GET" action="<?php echo e(route('owner.galleries.index')); ?>" class="flex flex-wrap gap-4 items-center">
            <label for="category" class="text-sm font-medium text-gray-700 nepali">श्रेणी छान्नुहोस्:</label>
            <select name="category" id="category" onchange="this.form.submit()" 
                    class="px-4 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($key); ?>" <?php echo e($selectedCategory == $key ? 'selected' : ''); ?>><?php echo e($value); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
        </form>
    </div>

    <!-- Stats Overview -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-blue-50 p-4 rounded-xl border border-blue-200">
            <div class="flex justify-between items-center">
                <div>
                    <p class="text-blue-600 text-sm font-medium nepali">कुल ग्यालरी</p>
                    <p class="text-2xl font-bold text-blue-800"><?php echo e($galleries->total()); ?></p>
                </div>
                <div class="bg-blue-600 text-white p-3 rounded-lg">
                    <i class="fas fa-images"></i>
                </div>
            </div>
        </div>

        <div class="bg-green-50 p-4 rounded-xl border border-green-200">
            <div class="flex justify-between items-center">
                <div>
                    <p class="text-green-600 text-sm font-medium nepali">फिचर्ड</p>
                    <p class="text-2xl font-bold text-green-800"><?php echo e($galleries->where('is_featured', true)->count()); ?></p>
                </div>
                <div class="bg-green-600 text-white p-3 rounded-lg">
                    <i class="fas fa-star"></i>
                </div>
            </div>
        </div>

        <div class="bg-purple-50 p-4 rounded-xl border border-purple-200">
            <div class="flex justify-between items-center">
                <div>
                    <p class="text-purple-600 text-sm font-medium nepali">तस्बिरहरू</p>
                    <p class="text-2xl font-bold text-purple-800"><?php echo e($galleries->where('media_type', 'photo')->count()); ?></p>
                </div>
                <div class="bg-purple-600 text-white p-3 rounded-lg">
                    <i class="fas fa-camera"></i>
                </div>
            </div>
        </div>

        <div class="bg-orange-50 p-4 rounded-xl border border-orange-200">
            <div class="flex justify-between items-center">
                <div>
                    <p class="text-orange-600 text-sm font-medium nepali">भिडियोहरू</p>
                    <p class="text-2xl font-bold text-orange-800"><?php echo e($galleries->whereIn('media_type', ['local_video', 'external_video'])->count()); ?></p>
                </div>
                <div class="bg-orange-600 text-white p-3 rounded-lg">
                    <i class="fas fa-video"></i>
                </div>
            </div>
        </div>
    </div>

    <?php if($galleries->count() > 0): ?>
        <!-- Gallery Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            <?php $__currentLoopData = $galleries; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $gallery): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="bg-white rounded-xl border border-gray-200 overflow-hidden transition-all duration-300 hover:shadow-lg hover:border-blue-300 gallery-card group">
                <!-- Media Preview -->
                <div class="relative aspect-video bg-gray-100 overflow-hidden">
                    <?php if($gallery->media_type === 'photo'): ?>
                        <!-- Image Display -->
                        <div class="w-full h-full cursor-pointer gallery-item" data-type="image" data-url="<?php echo e($gallery->media_url); ?>">
                            <img src="<?php echo e($gallery->thumbnail_url); ?>" 
                                 alt="<?php echo e($gallery->title); ?>"
                                 class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-105">
                        </div>

                    <?php elseif($gallery->media_type === 'external_video'): ?>
                        <!-- YouTube Video -->
                        <div class="w-full h-full cursor-pointer gallery-item" data-type="youtube" data-url="<?php echo e($gallery->external_link); ?>">
                            <img src="<?php echo e($gallery->thumbnail_url); ?>" 
                                 alt="<?php echo e($gallery->title); ?>"
                                 class="w-full h-full object-cover">
                            <div class="absolute inset-0 bg-black bg-opacity-30 flex items-center justify-center">
                                <div class="bg-red-600 rounded-full p-3 transform group-hover:scale-110 transition-transform duration-300">
                                    <i class="fab fa-youtube text-white text-2xl"></i>
                                </div>
                            </div>
                        </div>

                    <?php elseif($gallery->media_type === 'local_video'): ?>
                        <!-- Local Video - FIXED DISPLAY -->
                        <div class="w-full h-full cursor-pointer gallery-item" data-type="video" data-url="<?php echo e($gallery->media_url); ?>">
                            <?php if($gallery->thumbnail && Storage::disk('public')->exists($gallery->thumbnail)): ?>
                                <img src="<?php echo e(asset('storage/' . $gallery->thumbnail)); ?>" 
                                     alt="<?php echo e($gallery->title); ?>"
                                     class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-105">
                            <?php elseif($gallery->file_path && Storage::disk('public')->exists($gallery->file_path)): ?>
                                <!-- Fallback: Use video file if thumbnail doesn't exist -->
                                <div class="w-full h-full bg-gradient-to-br from-blue-500 to-blue-700 flex items-center justify-center">
                                    <i class="fas fa-video text-white text-4xl"></i>
                                </div>
                            <?php else: ?>
                                <!-- Final fallback -->
                                <div class="w-full h-full bg-gradient-to-br from-gray-500 to-gray-700 flex items-center justify-center">
                                    <i class="fas fa-video text-white text-4xl"></i>
                                </div>
                            <?php endif; ?>
                            <div class="absolute inset-0 bg-black bg-opacity-30 flex items-center justify-center">
                                <div class="bg-blue-600 rounded-full p-3 transform group-hover:scale-110 transition-transform duration-300">
                                    <i class="fas fa-play text-white text-xl ml-1"></i>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>

                    <!-- Status Badges -->
                    <div class="absolute top-2 left-2 flex flex-col gap-1 z-10">
                        <?php if($gallery->is_featured): ?>
                            <span class="bg-yellow-500 text-white text-xs px-2 py-1 rounded-full nepali shadow-md flex items-center">
                                <i class="fas fa-star mr-1 text-xs"></i> फिचर्ड
                            </span>
                        <?php endif; ?>
                        <?php if(!$gallery->is_active): ?>
                            <span class="bg-red-500 text-white text-xs px-2 py-1 rounded-full nepali shadow-md flex items-center">
                                <i class="fas fa-eye-slash mr-1 text-xs"></i> निष्क्रिय
                            </span>
                        <?php endif; ?>
                    </div>

                    <!-- Media Type Badge -->
                    <div class="absolute top-2 right-2 z-10">
                        <span class="bg-gray-800 text-white text-xs px-2 py-1 rounded-full nepali shadow-md flex items-center">
                            <?php if($gallery->media_type === 'photo'): ?>
                                <i class="fas fa-camera mr-1 text-xs"></i> तस्बिर
                            <?php elseif($gallery->media_type === 'local_video'): ?>
                                <i class="fas fa-video mr-1 text-xs"></i> भिडियो
                            <?php else: ?>
                                <i class="fab fa-youtube mr-1 text-xs"></i> यूट्युब
                            <?php endif; ?>
                        </span>
                    </div>

                    <!-- Category Badge -->
                    <div class="absolute bottom-2 left-2 z-10">
                        <span class="bg-blue-600 text-white text-xs px-2 py-1 rounded-full nepali shadow-md">
                            <?php echo e($gallery->category_nepali); ?>

                        </span>
                    </div>
                </div>

                <!-- Content Section -->
                <div class="p-4">
                    <h3 class="font-semibold text-gray-800 text-sm mb-2 nepali leading-tight line-clamp-2"><?php echo e($gallery->title); ?></h3>
                    
                    <?php if($gallery->description): ?>
                        <p class="text-gray-600 text-xs mb-3 nepali leading-relaxed line-clamp-2"><?php echo e($gallery->description); ?></p>
                    <?php endif; ?>

                    <div class="flex justify-between items-center text-xs text-gray-500 mb-3">
                        <span class="nepali flex items-center">
                            <i class="fas fa-calendar mr-1"></i> <?php echo e($gallery->created_at->format('Y-m-d')); ?>

                        </span>
                        <span class="flex items-center <?php echo e($gallery->is_active ? 'text-green-600' : 'text-red-600'); ?>">
                            <i class="fas fa-circle text-xs mr-1"></i>
                            <?php echo e($gallery->is_active ? 'सक्रिय' : 'निष्क्रिय'); ?>

                        </span>
                    </div>

                    <!-- Action Buttons - FIXED: WELL SPACED AND PROPER SIZE -->
                    <div class="flex justify-between items-center pt-3 border-t border-gray-100 action-buttons">
                        <div class="flex space-x-1">
                            <!-- Featured Toggle -->
                            <form action="<?php echo e(route('owner.galleries.toggle-featured', $gallery)); ?>" method="POST" class="inline">
                                <?php echo csrf_field(); ?>
                                <?php echo method_field('PATCH'); ?>
                                <button type="submit" 
                                        class="text-white <?php echo e($gallery->is_featured ? 'bg-yellow-500 hover:bg-yellow-600' : 'bg-gray-400 hover:bg-gray-500'); ?> transition-colors duration-200 p-1.5 rounded-lg text-sm"
                                        title="<?php echo e($gallery->is_featured ? 'फिचर्ड हटाउनुहोस्' : 'फिचर्ड बनाउनुहोस्'); ?>">
                                    <i class="fas fa-star"></i>
                                </button>
                            </form>

                            <!-- Active Toggle -->
                            <form action="<?php echo e(route('owner.galleries.toggle-active', $gallery)); ?>" method="POST" class="inline">
                                <?php echo csrf_field(); ?>
                                <?php echo method_field('PATCH'); ?>
                                <button type="submit" 
                                        class="text-white <?php echo e($gallery->is_active ? 'bg-green-500 hover:bg-green-600' : 'bg-gray-400 hover:bg-gray-500'); ?> transition-colors duration-200 p-1.5 rounded-lg text-sm"
                                        title="<?php echo e($gallery->is_active ? 'निष्क्रिय बनाउनुहोस्' : 'सक्रिय बनाउनुहोस्'); ?>">
                                    <i class="fas fa-<?php echo e($gallery->is_active ? 'eye' : 'eye-slash'); ?>"></i>
                                </button>
                            </form>
                        </div>

                        <div class="flex space-x-1">
                            <!-- Show Button -->
                            <a href="<?php echo e(route('owner.galleries.show', $gallery)); ?>" 
                               class="text-white bg-blue-500 hover:bg-blue-600 transition-colors duration-200 p-1.5 rounded-lg text-sm"
                               title="हेर्नुहोस्">
                                <i class="fas fa-eye"></i>
                            </a>

                            <!-- Edit Button -->
                            <a href="<?php echo e(route('owner.galleries.edit', $gallery)); ?>" 
                               class="text-white bg-green-500 hover:bg-green-600 transition-colors duration-200 p-1.5 rounded-lg text-sm"
                               title="सम्पादन गर्नुहोस्">
                                <i class="fas fa-edit"></i>
                            </a>

                            <!-- Delete Button -->
                            <form action="<?php echo e(route('owner.galleries.destroy', $gallery)); ?>" method="POST" class="inline" 
                                  onsubmit="return confirm('के तपाईं यो ग्यालरी आइटम मेटाउन निश्चित हुनुहुन्छ?')">
                                <?php echo csrf_field(); ?>
                                <?php echo method_field('DELETE'); ?>
                                <button type="submit" 
                                        class="text-white bg-red-500 hover:bg-red-600 transition-colors duration-200 p-1.5 rounded-lg text-sm"
                                        title="मेटाउनुहोस्">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>

        <!-- Pagination -->
        <div class="mt-6">
            <?php echo e($galleries->links()); ?>

        </div>
    <?php else: ?>
        <!-- Empty State -->
        <div class="text-center py-16">
            <div class="w-24 h-24 bg-blue-50 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-images text-blue-400 text-4xl"></i>
            </div>
            <h3 class="text-xl font-bold text-gray-600 nepali mb-2">कुनै ग्यालरी सामग्री छैन</h3>
            <p class="text-gray-500 nepali mb-6">आफ्नो होस्टलको ग्यालरी सुरु गर्नुहोस्</p>
            <a href="<?php echo e(route('owner.galleries.create')); ?>" 
               class="bg-blue-600 hover:bg-blue-700 text-white px-8 py-4 rounded-xl font-medium no-underline transition-colors shadow-lg hover:shadow-xl">
                <i class="fas fa-plus mr-2"></i>पहिलो ग्यालरी थप्नुहोस्
            </a>
        </div>
    <?php endif; ?>
</div>

<!-- Enhanced Modal for All Media Types -->
<div id="galleryModal" class="fixed inset-0 bg-black bg-opacity-90 flex items-center justify-center z-50 hidden">
    <div class="relative max-w-4xl w-full mx-4">
        <button id="closeGalleryModal" class="absolute -top-12 right-0 text-white text-3xl hover:text-gray-300 transition-colors z-10">
            <i class="fas fa-times"></i>
        </button>
        <div class="bg-transparent rounded-lg overflow-hidden">
            <!-- Image Content -->
            <div id="modalImageContent" class="hidden">
                <img id="modalImage" src="" class="w-full h-auto max-h-[80vh] object-contain rounded-lg" alt="">
            </div>
            
            <!-- Video Content -->
            <div id="modalVideoContent" class="hidden">
                <video id="modalVideo" controls class="w-full h-auto max-h-[80vh] object-contain rounded-lg">
                    तपाईंको ब्राउजरले भिडियो सपोर्ट गर्दैन।
                </video>
            </div>
            
            <!-- YouTube Content -->
            <div id="modalYouTubeContent" class="hidden">
                <div class="aspect-video bg-black rounded-lg overflow-hidden w-full">
                    <iframe id="modalYouTube" class="w-full h-full rounded-lg" 
                            frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen>
                    </iframe>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.aspect-video {
    position: relative;
    padding-bottom: 56.25%; /* 16:9 Aspect Ratio */
    height: 0;
    overflow: hidden;
}

.aspect-video > div {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
}

.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.gallery-card {
    transition: all 0.3s ease;
}

/* Smooth modal transitions */
#galleryModal {
    transition: opacity 0.3s ease;
}

/* Button hover effects */
.view-gallery-item:hover,
.gallery-item:hover {
    transform: scale(1.02);
}

/* Mobile responsive improvements */
@media (max-width: 768px) {
    .grid {
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    }
    
    .action-buttons {
        flex-wrap: wrap;
        gap: 0.5rem;
    }
}

/* Ensure proper image display */
.gallery-item img {
    object-fit: cover;
    width: 100%;
    height: 100%;
}

/* Modal video sizing */
#modalVideo {
    max-width: 100%;
    max-height: 80vh;
}

/* FIX: Make sure gallery items are clickable */
.gallery-item {
    cursor: pointer !important;
    position: relative;
}

.gallery-item:hover {
    transform: scale(1.02);
    transition: transform 0.2s ease;
}

/* FIX: Modal improvements */
#galleryModal {
    z-index: 9999 !important;
}

#galleryModal .hidden {
    display: none !important;
}

#galleryModal:not(.hidden) {
    display: flex !important;
}

/* FIX: Button container spacing */
.flex.space-x-1 > * {
    margin-right: 0.25rem;
}

.flex.space-x-1 > *:last-child {
    margin-right: 0;
}

/* FIX: Ensure proper image display in modal */
#modalImage {
    max-width: 90vw;
    max-height: 80vh;
    object-fit: contain;
}

/* FIX: Video player sizing */
#modalVideo {
    width: 80vw;
    height: auto;
    max-height: 80vh;
}

/* FIX: YouTube iframe sizing */
#modalYouTube {
    width: 80vw;
    height: 80vh;
    max-width: 1200px;
}

/* FIX: Make action buttons WELL SPACED AND PROPER SIZE */
.action-buttons {
    padding-top: 10px;
    border-top: 1px solid #e5e7eb;
}

.action-buttons button,
.action-buttons a {
    opacity: 1 !important;
    visibility: visible !important;
    display: inline-flex !important;
    align-items: center;
    justify-content: center;
    min-width: 32px;
    min-height: 32px;
    border: none;
    font-size: 14px;
    font-weight: 500;
    text-decoration: none;
    box-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
    transition: all 0.2s ease;
}

.action-buttons button:hover,
.action-buttons a:hover {
    transform: translateY(-1px);
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
}

/* Specific button colors for better visibility */
.action-buttons .bg-blue-500 { background-color: #3b82f6 !important; }
.action-buttons .bg-green-500 { background-color: #10b981 !important; }
.action-buttons .bg-red-500 { background-color: #ef4444 !important; }
.action-buttons .bg-yellow-500 { background-color: #f59e0b !important; }
.action-buttons .bg-gray-400 { background-color: #9ca3af !important; }

.action-buttons .bg-blue-500:hover { background-color: #2563eb !important; }
.action-buttons .bg-green-500:hover { background-color: #059669 !important; }
.action-buttons .bg-red-500:hover { background-color: #dc2626 !important; }
.action-buttons .bg-yellow-500:hover { background-color: #d97706 !important; }
.action-buttons .bg-gray-400:hover { background-color: #6b7280 !important; }

/* Make sure buttons are properly sized and spaced */
.action-buttons .flex.space-x-1 {
    gap: 4px;
}

/* Ensure text is white and visible */
.action-buttons .text-white {
    color: white !important;
}

/* Better spacing between left and right button groups */
.action-buttons {
    gap: 8px;
}

/* Make buttons more compact but still clickable */
.action-buttons .p-1\.5 {
    padding: 6px;
}

/* Ensure icons are properly sized */
.action-buttons .text-sm {
    font-size: 14px;
}
</style>

<script>
// Enhanced Modal System with Better UX
document.addEventListener('DOMContentLoaded', function() {
    const galleryModal = document.getElementById('galleryModal');
    const closeGalleryModal = document.getElementById('closeGalleryModal');
    const modalImageContent = document.getElementById('modalImageContent');
    const modalVideoContent = document.getElementById('modalVideoContent');
    const modalYouTubeContent = document.getElementById('modalYouTubeContent');
    const modalImage = document.getElementById('modalImage');
    const modalVideo = document.getElementById('modalVideo');
    const modalYouTube = document.getElementById('modalYouTube');

    console.log('Gallery modal system initialized');

    // Gallery item click handlers - FIXED SELECTOR
    document.querySelectorAll('.gallery-item').forEach(item => {
        item.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            const type = this.getAttribute('data-type');
            const url = this.getAttribute('data-url');
            console.log('Clicked gallery item:', type, url);
            
            openGalleryModal(type, url);
        });
    });

    function openGalleryModal(type, url) {
        console.log('Opening modal:', type, url);
        
        // Hide all content first
        modalImageContent.classList.add('hidden');
        modalVideoContent.classList.add('hidden');
        modalYouTubeContent.classList.add('hidden');

        // Show appropriate content
        if (type === 'image') {
            modalImage.src = url;
            modalImage.onload = function() {
                modalImageContent.classList.remove('hidden');
            };
            modalImage.onerror = function() {
                console.error('Failed to load image:', url);
                modalImageContent.classList.remove('hidden'); // Still show even if error
            };
        } else if (type === 'video') {
            modalVideo.src = url;
            modalVideo.load(); // Important: reload video source
            modalVideoContent.classList.remove('hidden');
        } else if (type === 'youtube') {
            // Ensure proper YouTube embed URL
            let embedUrl = url;
            if (!url.includes('embed')) {
                const videoId = getYouTubeIdFromUrl(url);
                if (videoId) {
                    embedUrl = `https://www.youtube.com/embed/${videoId}?autoplay=1`;
                }
            }
            modalYouTube.src = embedUrl;
            modalYouTubeContent.classList.remove('hidden');
        } else {
            console.error('Unknown media type:', type);
        }

        galleryModal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    // Close modal
    closeGalleryModal.addEventListener('click', closeModal);

    // Close on background click
    galleryModal.addEventListener('click', function(e) {
        if (e.target === galleryModal) {
            closeModal();
        }
    });

    // Close with Escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeModal();
        }
    });

    function closeModal() {
        galleryModal.classList.add('hidden');
        document.body.style.overflow = 'auto';
        
        // Reset media
        if (modalVideo) {
            modalVideo.pause();
            modalVideo.currentTime = 0;
        }
        if (modalYouTube) {
            // Stop YouTube video
            modalYouTube.src = '';
        }
    }

    // YouTube URL parser function
    function getYouTubeIdFromUrl(url) {
        if (!url) return null;
        const patterns = [
            /(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/i,
            /^https?:\/\/(?:www\.)?youtube\.com\/watch\?v=([\w-]{11})/,
            /^https?:\/\/youtu\.be\/([\w-]{11})/
        ];

        for (const pattern of patterns) {
            const match = url.match(pattern);
            if (match) return match[1];
        }
        return null;
    }

    // Add loading states for better UX
    document.querySelectorAll('.gallery-item img').forEach(img => {
        img.addEventListener('load', function() {
            this.style.opacity = '1';
        });
        img.addEventListener('error', function() {
            console.error('Image failed to load:', this.src);
            this.src = '/images/default-image.jpg';
        });
    });

    // Debug: Log all gallery items
    console.log('Total gallery items found:', document.querySelectorAll('.gallery-item').length);
});
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.owner', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\HostelHub\resources\views/owner/galleries/index.blade.php ENDPATH**/ ?>