

<?php $__env->startSection('page-title', ($hostel->name ?? 'Sanctuary Girls Hostel') . ' - Full Gallery | HostelHub'); ?>

<?php $__env->startSection('page-header', ($hostel->name ?? 'Sanctuary Girls Hostel') . ' - Full Gallery'); ?>
<?php $__env->startSection('page-description', 'हाम्रो होस्टलको सम्पूर्ण सुविधाहरू, कोठाहरू, र भिडियोहरूको दृश्यात्मक अनुभव'); ?>

<?php $__env->startSection('content'); ?>
<?php
    // PERMANENT FIX: Nepali room types
    $nepaliRoomTypes = [
        '1 seater' => '१ सिटर',
        '2 seater' => '२ सिटर', 
        '3 seater' => '३ सिटर',
        '4 seater' => '४ सिटर',
        'other' => 'अन्य (५+ सिटर)'
    ];

    // Get all active gallery items
    $galleries = $hostel->galleries ?? collect();
    $activeGalleries = $galleries->where('is_active', true);
    
    // Count items by category for stats
    $categoryCounts = [
        'rooms' => $activeGalleries->whereIn('category', ['1 seater', '2 seater', '3 seater', '4 seater', 'other'])->count(),
        'kitchen' => $activeGalleries->where('category', 'kitchen')->count(),
        'facilities' => $activeGalleries->whereIn('category', ['bathroom', 'common', 'living room', 'study room'])->count(),
        'video' => $activeGalleries->whereIn('media_type', ['local_video', 'external_video'])->count()
    ];
?>

<style>
    /* Gallery Specific Styles */
    .gallery-section {
        padding: 80px 0 60px;
    }
    
    .section-title {
        text-align: center;
        margin-bottom: 40px;
        font-size: 2.2rem;
        color: var(--text-dark);
        position: relative;
    }
    
    .section-title::after {
        content: '';
        position: absolute;
        bottom: -10px;
        left: 50%;
        transform: translateX(-50%);
        width: 80px;
        height: 3px;
        background: var(--secondary);
    }
    
    .gallery-filters {
        display: flex;
        justify-content: center;
        flex-wrap: wrap;
        gap: 12px;
        margin-bottom: 40px;
    }
    
    .filter-btn {
        padding: 10px 24px;
        background: white;
        border: 2px solid var(--border);
        border-radius: 30px;
        cursor: pointer;
        transition: all 0.3s;
        font-weight: 500;
        color: var(--text-dark);
    }
    
    .filter-btn.active, .filter-btn:hover {
        background: var(--primary);
        color: white;
        border-color: var(--primary);
    }
    
    .gallery-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
        gap: 25px;
        margin-bottom: 50px;
    }
    
    .gallery-item {
        position: relative;
        border-radius: var(--radius);
        overflow: hidden;
        box-shadow: var(--shadow);
        transition: transform 0.3s, box-shadow 0.3s;
        height: 280px;
        background: var(--light-bg);
    }
    
    .gallery-item:hover {
        transform: translateY(-8px);
        box-shadow: 0 15px 30px rgba(0, 0, 0, 0.15);
    }
    
    .gallery-item img, .gallery-item video {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.5s;
    }
    
    .gallery-item:hover img, .gallery-item:hover video {
        transform: scale(1.05);
    }
    
    .gallery-overlay {
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        background: linear-gradient(transparent, rgba(0, 0, 0, 0.8));
        color: white;
        padding: 25px 20px;
        transform: translateY(100%);
        transition: transform 0.3s;
    }
    
    .gallery-item:hover .gallery-overlay {
        transform: translateY(0);
    }
    
    .gallery-title {
        font-size: 1.3rem;
        margin-bottom: 8px;
        font-weight: 600;
    }
    
    .featured-badge {
        position: absolute;
        top: 15px;
        right: 15px;
        background: var(--accent);
        color: white;
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 600;
        z-index: 2;
    }
    
    .view-more {
        text-align: center;
        margin-top: 40px;
        display: flex;
        justify-content: center;
        gap: 20px;
        flex-wrap: wrap;
    }
    
    /* Gallery Categories - Fixed Top Alignment */
    .gallery-categories {
        background: var(--bg-light);
        padding: 60px 0 80px;
        margin-top: 0;
        min-height: auto;
        display: block;
    }
    
    .main-description {
        text-align: center;
        margin: 0 auto 60px;
        color: var(--text-dark);
        font-size: 2rem;
        font-weight: 700;
        line-height: 1.3;
        max-width: 900px;
        padding: 40px 20px 0;
    }
    
    .category-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 25px;
        margin-top: 0;
        align-items: stretch;
    }
    
    .category-card {
        background: white;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
        transition: transform 0.3s, box-shadow 0.3s;
        text-align: center;
        padding: 30px 20px;
        height: 100%;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
    }
    
    .category-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
    }
    
    .category-icon {
        font-size: 2.5rem;
        margin-bottom: 20px;
        color: var(--primary);
    }
    
    .category-title {
        font-size: 1.3rem;
        margin-bottom: 12px;
        color: var(--text-dark);
        font-weight: 600;
    }
    
    .category-count {
        background: var(--primary);
        color: white;
        padding: 6px 15px;
        border-radius: 20px;
        font-size: 0.9rem;
        font-weight: 600;
        display: inline-block;
        margin-top: 12px;
    }
    
    /* Modal Styles */
    .gallery-modal {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.95);
        z-index: 1100;
        justify-content: center;
        align-items: center;
        padding: 20px;
    }
    
    .modal-content {
        max-width: 90%;
        max-height: 90%;
        position: relative;
        border-radius: var(--radius);
        overflow: hidden;
        background: black;
    }
    
    .modal-content img, .modal-content video {
        width: 100%;
        height: auto;
        display: block;
    }
    
    .close-modal {
        position: absolute;
        top: 15px;
        right: 15px;
        color: white;
        font-size: 2rem;
        cursor: pointer;
        background: rgba(0, 0, 0, 0.7);
        width: 45px;
        height: 45px;
        border-radius: 50%;
        display: flex;
        justify-content: center;
        align-items: center;
        z-index: 10;
        transition: background 0.3s;
    }
    
    .close-modal:hover {
        background: rgba(0, 0, 0, 0.9);
    }
    
    .modal-caption {
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        background: rgba(0, 0, 0, 0.8);
        color: white;
        padding: 20px;
        transform: translateY(100%);
        transition: transform 0.3s;
    }
    
    .modal-content:hover .modal-caption {
        transform: translateY(0);
    }

    /* Hidden items for view more functionality */
    .gallery-item.hidden-item {
        display: none;
    }

    .view-more-items .gallery-item {
        display: block !important;
    }
    
    /* Responsive Design */
    @media (max-width: 1200px) {
        .category-grid {
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
        }
    }
    
    @media (max-width: 768px) {
        .gallery-grid {
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 20px;
        }
        
        .gallery-item {
            height: 240px;
        }
        
        .category-grid {
            grid-template-columns: 1fr;
            gap: 20px;
        }
        
        .gallery-section {
            padding: 60px 0 40px;
        }
        
        .gallery-categories {
            padding: 40px 0 60px;
        }
        
        .view-more {
            flex-direction: column;
            align-items: center;
        }
        
        .main-description {
            font-size: 1.6rem;
            margin-bottom: 40px;
            padding: 20px 15px 0;
        }
        
        .category-icon {
            font-size: 2.2rem;
        }
        
        .category-title {
            font-size: 1.2rem;
        }
    }
    
    @media (max-width: 480px) {
        .gallery-grid {
            grid-template-columns: 1fr;
        }
        
        .gallery-item {
            height: 220px;
        }
        
        .gallery-filters {
            gap: 8px;
        }
        
        .filter-btn {
            padding: 8px 12px;
            font-size: 0.8rem;
        }
        
        .main-description {
            font-size: 1.4rem;
            padding: 15px 10px 0;
        }
        
        .gallery-categories {
            padding: 30px 0 40px;
        }
        
        .category-card {
            padding: 25px 15px;
        }
        
        .category-icon {
            font-size: 2rem;
        }
    }
</style>

<!-- Gallery Categories -->
<section class="gallery-categories">
    <div class="container">
        <div class="main-description nepali">
            हाम्रो होस्टलको सुन्दर वातावरण र उत्कृष्ट सुविधाहरूको दृश्यात्मक अनुभव
        </div>
        
        <div class="category-grid">
            <div class="category-card">
                <div class="category-icon">🛏️</div>
                <h3 class="category-title nepali">कोठाहरू</h3>
                <p class="nepali">१, २, ३, ४ र अन्य सिटर कोठाहरू</p>
                <span class="category-count nepali"><?php echo e($categoryCounts['rooms']); ?> फोटोहरू</span>
            </div>
            
            <div class="category-card">
                <div class="category-icon">🍳</div>
                <h3 class="category-title nepali">किचन</h3>
                <p class="nepali">आधुनिक किचन सुविधा</p>
                <span class="category-count nepali"><?php echo e($categoryCounts['kitchen']); ?> फोटोहरू</span>
            </div>
            
            <div class="category-card">
                <div class="category-icon">🚽</div>
                <h3 class="category-title nepali">अन्य सुविधाहरू</h3>
                <p class="nepali">अध्ययन क्षेत्र, शौचालय, र अन्य</p>
                <span class="category-count nepali"><?php echo e($categoryCounts['facilities']); ?> फोटोहरू</span>
            </div>
            
            <div class="category-card">
                <div class="category-icon">🎬</div>
                <h3 class="category-title nepali">भिडियो टुर</h3>
                <p class="nepali">होस्टलको पूर्ण टुर</p>
                <span class="category-count nepali"><?php echo e($categoryCounts['video']); ?> भिडियोहरू</span>
            </div>
        </div>
    </div>
</section>

<!-- Gallery Section -->
<section class="gallery-section" id="gallery">
    <div class="container">
        <h2 class="section-title nepali">हाम्रो होस्टल ग्यालरी</h2>
        <p style="text-align: center; margin-bottom: 40px; color: var(--text-dark); opacity: 0.8; max-width: 700px; margin-left: auto; margin-right: auto;" class="nepali">
            विभिन्न कोठा र सुविधाहरूको विस्तृत दृश्यहरू
        </p>
        
        <!-- PERMANENT FIX: Updated filters with all room types -->
        <div class="gallery-filters">
            <button class="filter-btn active nepali" data-filter="all">सबै</button>
            <button class="filter-btn nepali" data-filter="1-seater">१ सिटर</button>
            <button class="filter-btn nepali" data-filter="2-seater">२ सिटर</button>
            <button class="filter-btn nepali" data-filter="3-seater">३ सिटर</button>
            <button class="filter-btn nepali" data-filter="4-seater">४ सिटर</button>
            <button class="filter-btn nepali" data-filter="other">अन्य</button>
            <button class="filter-btn nepali" data-filter="video">भिडियो</button>
            <button class="filter-btn nepali" data-filter="facilities">सुविधाहरू</button>
        </div>
        
        <div class="gallery-grid" id="mainGallery">
            <?php
                $displayedItems = 0;
                $maxInitialDisplay = 8;
            ?>
            
            <?php $__currentLoopData = $activeGalleries; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $gallery): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php
                    // PERMANENT FIX: Determine category for filtering with all room types
                    $filterCategory = '';
                    if (in_array($gallery->category, ['1 seater', '2 seater', '3 seater', '4 seater', 'other'])) {
                        $filterCategory = str_replace(' ', '-', $gallery->category);
                    } elseif (in_array($gallery->media_type, ['local_video', 'external_video'])) {
                        $filterCategory = 'video';
                    } else {
                        $filterCategory = 'facilities';
                    }

                    $displayedItems++;
                    $isHidden = $displayedItems > $maxInitialDisplay;
                ?>

                <div class="gallery-item <?php echo e($isHidden ? 'hidden-item' : ''); ?>" 
                     data-category="<?php echo e($filterCategory); ?>"
                     data-gallery-id="<?php echo e($gallery->id); ?>">
                    
                    <?php if($gallery->media_type === 'photo'): ?>
                        <img src="<?php echo e($gallery->thumbnail_url ?? $gallery->media_url); ?>" alt="<?php echo e($gallery->title); ?>">
                    <?php elseif($gallery->media_type === 'local_video'): ?>
                        <img src="<?php echo e($gallery->thumbnail_url ?? asset('images/video-default.jpg')); ?>" alt="<?php echo e($gallery->title); ?>">
                    <?php elseif($gallery->media_type === 'external_video'): ?>
                        <img src="<?php echo e($gallery->thumbnail_url ?? asset('images/video-default.jpg')); ?>" alt="<?php echo e($gallery->title); ?>">
                    <?php endif; ?>

                    <?php if($gallery->is_featured): ?>
                        <div class="featured-badge nepali">Featured</div>
                    <?php endif; ?>

                    <div class="gallery-overlay">
                        <h3 class="gallery-title nepali"><?php echo e($gallery->title); ?></h3>
                        <p class="nepali"><?php echo e($gallery->description); ?></p>
                        <button class="btn btn-primary" 
                                style="margin-top: 12px; padding: 8px 16px; font-size: 0.9rem;" 
                                onclick="openModal('<?php echo e($gallery->id); ?>', '<?php echo e($gallery->media_type); ?>')">
                            विस्तृत हेर्नुहोस्
                        </button>
                    </div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

            <?php if($activeGalleries->count() === 0): ?>
                <div class="text-center py-12 col-span-full">
                    <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-images text-gray-400 text-3xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-600 nepali mb-2">कुनै ग्यालरी सामग्री छैन</h3>
                    <p class="text-gray-500 nepali">यस होस्टलको ग्यालरी चाँहि उपलब्ध छैन।</p>
                </div>
            <?php endif; ?>
        </div>
        
        <?php if($activeGalleries->count() > $maxInitialDisplay): ?>
            <div class="view-more">
                <button class="btn btn-outline nepali" 
                        style="border-color: var(--primary); color: var(--primary);"
                        onclick="showMoreGallery()">
                    थप ग्यालरी हेर्नुहोस्
                </button>
                <a href="<?php echo e(route('contact')); ?>" class="btn btn-primary nepali">सम्पर्क गर्नुहोस्</a>
            </div>
        <?php endif; ?>
    </div>
</section>

<!-- Modals -->
<div class="gallery-modal" id="imageModal">
    <div class="modal-content">
        <span class="close-modal" onclick="closeModal()">&times;</span>
        <img id="modalImage" src="" alt="">
        <div class="modal-caption">
            <h3 id="modalTitle" class="nepali"></h3>
            <p id="modalDescription" class="nepali"></p>
        </div>
    </div>
</div>

<div class="gallery-modal" id="videoModal">
    <div class="modal-content">
        <span class="close-modal" onclick="closeModal()">&times;</span>
        <video id="modalVideo" controls>
            <source src="" type="video/mp4">
            तपाईंको ब्राउजरले भिडियो सपोर्ट गर्दैन।
        </video>
        <div class="modal-caption">
            <h3 id="videoTitle" class="nepali"></h3>
            <p id="videoDescription" class="nepali"></p>
        </div>
    </div>
</div>

<div class="gallery-modal" id="youtubeModal">
    <div class="modal-content">
        <span class="close-modal" onclick="closeModal()">&times;</span>
        <iframe id="modalYouTube" width="100%" height="400" frameborder="0" allowfullscreen></iframe>
        <div class="modal-caption">
            <h3 id="youtubeTitle" class="nepali"></h3>
            <p id="youtubeDescription" class="nepali"></p>
        </div>
    </div>
</div>

<script>
    // Gallery data from backend
    const galleryData = {
        <?php $__currentLoopData = $activeGalleries; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $gallery): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        '<?php echo e($gallery->id); ?>': {
            title: '<?php echo e($gallery->title); ?>',
            description: '<?php echo e($gallery->description); ?>',
            media_type: '<?php echo e($gallery->media_type); ?>',
            media_url: '<?php echo e($gallery->media_type === 'external_video' ? $gallery->external_link : $gallery->media_url); ?>',
            thumbnail_url: '<?php echo e($gallery->thumbnail_url); ?>',
            youtube_embed_url: '<?php echo e($gallery->youtube_embed_url); ?>'
        },
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    };

    // Gallery Filter Functionality
    document.addEventListener('DOMContentLoaded', function() {
        const filterButtons = document.querySelectorAll('.filter-btn');
        const galleryItems = document.querySelectorAll('.gallery-item');
        
        filterButtons.forEach(button => {
            button.addEventListener('click', () => {
                // Remove active class from all buttons
                filterButtons.forEach(btn => btn.classList.remove('active'));
                // Add active class to clicked button
                button.classList.add('active');
                
                const filterValue = button.getAttribute('data-filter');
                
                galleryItems.forEach(item => {
                    if (filterValue === 'all' || item.getAttribute('data-category') === filterValue) {
                        item.style.display = 'block';
                    } else {
                        item.style.display = 'none';
                    }
                });
            });
        });
    });
    
    // Show More Gallery Functionality
    function showMoreGallery() {
        const hiddenItems = document.querySelectorAll('.gallery-item.hidden-item');
        hiddenItems.forEach(item => {
            item.classList.remove('hidden-item');
        });
        
        // Hide the show more button
        document.querySelector('.view-more').style.display = 'none';
    }
    
    // Modal Functionality with Dynamic Content
    function openModal(galleryId, mediaType) {
        const gallery = galleryData[galleryId];
        if (!gallery) return;

        if (mediaType === 'photo') {
            document.getElementById('imageModal').style.display = 'flex';
            document.getElementById('modalImage').src = gallery.media_url;
            document.getElementById('modalTitle').textContent = gallery.title;
            document.getElementById('modalDescription').textContent = gallery.description;
        } else if (mediaType === 'local_video') {
            document.getElementById('videoModal').style.display = 'flex';
            document.getElementById('modalVideo').src = gallery.media_url;
            document.getElementById('videoTitle').textContent = gallery.title;
            document.getElementById('videoDescription').textContent = gallery.description;
        } else if (mediaType === 'external_video') {
            document.getElementById('youtubeModal').style.display = 'flex';
            document.getElementById('modalYouTube').src = gallery.youtube_embed_url || gallery.media_url;
            document.getElementById('youtubeTitle').textContent = gallery.title;
            document.getElementById('youtubeDescription').textContent = gallery.description;
        }
    }
    
    function closeModal() {
        document.getElementById('imageModal').style.display = 'none';
        document.getElementById('videoModal').style.display = 'none';
        document.getElementById('youtubeModal').style.display = 'none';
        
        // Pause video when closing modal
        const video = document.getElementById('modalVideo');
        if (video) {
            video.pause();
        }
    }
    
    // Close modal when clicking outside the content
    window.addEventListener('click', function(event) {
        const imageModal = document.getElementById('imageModal');
        const videoModal = document.getElementById('videoModal');
        const youtubeModal = document.getElementById('youtubeModal');
        
        if (event.target === imageModal) {
            imageModal.style.display = 'none';
        }
        
        if (event.target === videoModal) {
            videoModal.style.display = 'none';
        }

        if (event.target === youtubeModal) {
            youtubeModal.style.display = 'none';
        }
    });
    
    // Close modal with Escape key
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            closeModal();
        }
    });
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.frontend', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\HostelHub\resources\views\public\hostels\full-gallery.blade.php ENDPATH**/ ?>