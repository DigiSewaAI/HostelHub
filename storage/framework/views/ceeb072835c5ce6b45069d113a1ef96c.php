<?php $__env->startSection('page-title', 'ग्यालरी - HostelHub'); ?>
<?php $__env->startSection('page-header', 'हाम्रो ग्यालरी'); ?>
<?php $__env->startSection('page-description', 'हाम्रा विभिन्न होस्टलहरूको कोठा, सुविधा र आवासीय क्षेत्रहरूको वास्तविक झलकहरू अन्वेषण गर्नुहोस्'); ?>

<?php $__env->startPush('styles'); ?>
<link rel="stylesheet" href="<?php echo e(asset('css/gallery.css')); ?>">
<style>
    .hostel-badge {
        position: absolute;
        top: 10px;
        right: 10px;
        background: rgba(0, 31, 91, 0.9);
        color: white;
        padding: 4px 8px;
        border-radius: 4px;
        font-size: 0.8rem;
        font-weight: 500;
        z-index: 10;
        backdrop-filter: blur(4px);
        display: flex;
        align-items: center;
        gap: 4px;
    }

    .hostel-badge i {
        font-size: 0.7rem;
    }

    .hostel-filter {
        background: #f8f9fa;
        border-radius: 8px;
        padding: 1rem;
        margin-bottom: 1.5rem;
    }

    .gallery-item {
        position: relative;
        cursor: pointer;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .gallery-item:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(0,0,0,0.15);
    }

    .room-number-badge {
        position: absolute;
        top: 10px;
        left: 10px;
        background: rgba(34, 197, 94, 0.9);
        color: white;
        padding: 4px 8px;
        border-radius: 4px;
        font-size: 0.7rem;
        font-weight: 500;
        z-index: 10;
        backdrop-filter: blur(4px);
    }

    .video-badge {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        background: rgba(0, 0, 0, 0.7);
        color: white;
        padding: 8px 16px;
        border-radius: 20px;
        font-size: 0.8rem;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .hidden {
        display: none !important;
    }

    @media (max-width: 768px) {
        .hostel-badge {
            font-size: 0.7rem;
            padding: 3px 6px;
        }
        
        .room-number-badge {
            font-size: 0.6rem;
            padding: 2px 6px;
        }

        .video-badge {
            padding: 6px 12px;
            font-size: 0.7rem;
        }
    }

    /* Modal Styles */
    .gallery-modal {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.9);
        z-index: 9999;
        display: none;
        align-items: center;
        justify-content: center;
    }

    .gallery-modal.active {
        display: flex;
    }

    .modal-close {
        position: absolute;
        top: 20px;
        right: 20px;
        background: rgba(255, 255, 255, 0.1);
        border: none;
        color: white;
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        font-size: 1.2rem;
        backdrop-filter: blur(10px);
    }

    .modal-nav {
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        background: rgba(255, 255, 255, 0.1);
        border: none;
        color: white;
        width: 50px;
        height: 50px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        font-size: 1.2rem;
        backdrop-filter: blur(10px);
    }

    .modal-prev {
        left: 20px;
    }

    .modal-next {
        right: 20px;
    }

    .modal-content {
        max-width: 90%;
        max-height: 80%;
        background: white;
        border-radius: 8px;
        overflow: hidden;
    }

    .modal-info {
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        background: rgba(0, 0, 0, 0.8);
        color: white;
        padding: 1rem;
        transform: translateY(100%);
        transition: transform 0.3s ease;
    }

    .gallery-modal.active .modal-info {
        transform: translateY(0);
    }

    .loading-spinner {
        border: 4px solid #f3f3f3;
        border-top: 4px solid #001f5b;
        border-radius: 50%;
        width: 40px;
        height: 40px;
        animation: spin 1s linear infinite;
        margin: 0 auto;
    }

    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>

<!-- Enhanced Filters Section with Hostel Filter -->
<section class="gallery-filters">
    <div class="filter-container">
        <div class="filter-header">
            <h2 class="nepali">ग्यालरी फिल्टर गर्नुहोस्</h2>
            <p class="nepali">तपाईंले हेर्न चाहनुभएको विशेष प्रकारको मिडिया वा होस्टल चयन गर्नुहोस्</p>
        </div>
        
        <!-- Hostel Filter -->
        <div class="hostel-filter">
            <label class="nepali" style="display: block; margin-bottom: 0.5rem; font-weight: 600;">
                <i class="fas fa-building"></i> होस्टल छान्नुहोस्:
            </label>
            <select id="hostelFilter" class="form-control">
                <option value="">सबै होस्टलहरू</option>
                <?php $__currentLoopData = $hostels; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $hostel): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($hostel->id); ?>"><?php echo e($hostel->name); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
        </div>

        <div class="filter-controls">
            <div class="filter-categories">
                <button class="filter-btn active nepali" data-filter="all">सबै</button>
                <button class="filter-btn nepali" data-filter="१ सिटर कोठा">१ सिटर कोठा</button>
                <button class="filter-btn nepali" data-filter="२ सिटर कोठा">२ सिटर कोठा</button>
                <button class="filter-btn nepali" data-filter="३ सिटर कोठा">३ सिटर कोठा</button>
                <button class="filter-btn nepali" data-filter="४ सिटर कोठा">४ सिटर कोठा</button>
                <button class="filter-btn nepali" data-filter="लिभिङ रूम">लिभिङ रूम</button>
                <button class="filter-btn nepali" data-filter="बाथरूम">बाथरूम</button>
                <button class="filter-btn nepali" data-filter="भान्सा">भान्सा</button>
                <button class="filter-btn nepali" data-filter="अध्ययन कोठा">अध्ययन कोठा</button>
                <button class="filter-btn nepali" data-filter="कार्यक्रम">कार्यक्रम</button>
                <button class="filter-btn nepali" data-filter="भिडियो टुर">भिडियो टुर</button>
            </div>
            
            <div class="search-box">
                <i class="fas fa-search search-icon"></i>
                <input type="text" class="search-input nepali" placeholder="कोठा, सुविधा, होस्टल वा स्थान खोज्नुहोस्...">
            </div>
        </div>
    </div>
</section>

<!-- Main Gallery Section WITH HOSTEL BADGES -->
<section class="gallery-main">
    <div class="gallery-container">
        <!-- Gallery Grid -->
        <div class="gallery-grid">
            <?php $__empty_1 = true; $__currentLoopData = $galleries; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $gallery): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <div class="gallery-item <?php echo e($gallery->media_type); ?>" 
                 data-category="<?php echo e($gallery->category_nepali); ?>"
                 data-title="<?php echo e($gallery->title); ?>"
                 data-description="<?php echo e($gallery->description); ?>"
                 data-date="<?php echo e($gallery->created_at->format('Y-m-d')); ?>"
                 data-hostel="<?php echo e($gallery->hostel_name); ?>"
                 data-hostel-id="<?php echo e($gallery->hostel_id); ?>"
                 data-room-number="<?php echo e($gallery->room ? $gallery->room->room_number : ''); ?>"
                 data-media-type="<?php echo e($gallery->media_type); ?>"
                 <?php if($gallery->media_type === 'external_video' && $gallery->youtube_embed_url): ?>
                 data-youtube-embed="<?php echo e($gallery->youtube_embed_url); ?>"
                 <?php endif; ?>>

                <!-- Hostel Badge -->
                <div class="hostel-badge" title="<?php echo e($gallery->hostel_name); ?>">
                    <i class="fas fa-building"></i>
                    <span class="nepali"><?php echo e(Str::limit($gallery->hostel_name, 20)); ?></span>
                </div>

                <!-- Room Number Badge -->
                <?php if($gallery->room && $gallery->room->room_number): ?>
                <div class="room-number-badge" title="कोठा नम्बर: <?php echo e($gallery->room->room_number); ?>">
                    <i class="fas fa-door-open"></i>
                    <span class="nepali">कोठा <?php echo e($gallery->room->room_number); ?></span>
                </div>
                <?php endif; ?>

                <?php if($gallery->media_type === 'photo'): ?>
                    <img src="<?php echo e($gallery->thumbnail_url ?? $gallery->media_url); ?>" alt="<?php echo e($gallery->title); ?>" class="gallery-media" loading="lazy">
                <?php elseif($gallery->media_type === 'external_video'): ?>
                    <img src="<?php echo e($gallery->thumbnail_url); ?>" alt="<?php echo e($gallery->title); ?>" class="gallery-media" loading="lazy">
                    <div class="video-badge">
                        <i class="fas fa-play"></i>
                        <span>भिडियो</span>
                    </div>
                <?php elseif($gallery->media_type === 'local_video'): ?>
                    <img src="<?php echo e($gallery->thumbnail_url); ?>" alt="<?php echo e($gallery->title); ?>" class="gallery-media" loading="lazy">
                    <div class="video-badge">
                        <i class="fas fa-play"></i>
                        <span>भिडियो</span>
                    </div>
                <?php endif; ?>

                <div class="media-overlay">
                    <div class="media-title nepali"><?php echo e($gallery->title); ?></div>
                    <div class="media-description nepali"><?php echo e($gallery->description); ?></div>
                    <div class="media-meta">
                        <span class="media-category nepali"><?php echo e($gallery->category_nepali); ?></span>
                        <span class="media-date"><?php echo e($gallery->created_at->format('Y-m-d')); ?></span>
                        <?php if($gallery->room && $gallery->room->room_number): ?>
                        <span class="room-number nepali">कोठा: <?php echo e($gallery->room->room_number); ?></span>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <div class="no-results">
                <i class="fas fa-images" style="font-size: 3rem; color: #ccc; margin-bottom: 1rem;"></i>
                <h3 class="nepali">कुनै ग्यालरी आइटम फेला परेन</h3>
                <p class="nepali">हाल कुनै ग्यालरी आइटम उपलब्ध छैन। कृपया पछि फर्कनुहोस्।</p>
            </div>
            <?php endif; ?>
        </div>

        <!-- Pagination -->
        <?php if($galleries->count() > 0): ?>
        <div class="pagination-container">
            <?php echo e($galleries->links()); ?>

        </div>
        <?php endif; ?>

        <!-- No Results Message -->
        <div class="no-results hidden">
            <i class="fas fa-search" style="font-size: 3rem; color: #ccc; margin-bottom: 1rem;"></i>
            <h3 class="nepali">कुनै परिणाम फेला परेन</h3>
            <p class="nepali">तपाईंको खोजसँग मिल्ने कुनै ग्यालरी आइटम फेला परेन। कृपया अर्को खोज प्रयास गर्नुहोस्।</p>
        </div>

        <!-- Loading Indicator -->
        <div class="gallery-loading hidden">
            <div class="loading-spinner"></div>
            <p class="nepali">ग्यालरी आइटमहरू लोड हुँदैछ...</p>
        </div>
    </div>
</section>

<!-- Stats Section -->
<section class="gallery-stats">
    <div class="stats-grid">
        <div class="stat-item">
            <div class="stat-number"><?php echo e($metrics['total_students'] ?? '500+'); ?></div>
            <div class="stat-label nepali">खुसी विद्यार्थीहरू</div>
        </div>
        <div class="stat-item">
            <div class="stat-number"><?php echo e($metrics['total_hostels'] ?? '25'); ?></div>
            <div class="stat-label nepali">सहयोगी होस्टल</div>
        </div>
        <div class="stat-item">
            <div class="stat-number"><?php echo e($cities->count() ?? '5'); ?></div>
            <div class="stat-label nepali">शहरहरूमा उपलब्ध</div>
        </div>
        <div class="stat-item">
            <div class="stat-number">98%</div>
            <div class="stat-label nepali">सन्तुष्टि दर</div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="gallery-cta">
    <div class="cta-content">
        <h2 class="nepali">तपाईंको होस्टललाई HostelHub संग जोड्नुहोस्</h2>
        <p class="nepali">७ दिन निःशुल्क परीक्षण गर्नुहोस् र होस्टल व्यवस्थापनलाई सजिलो, द्रुत र भरपर्दो बनाउनुहोस्</p>
        <div class="cta-buttons">
            <a href="<?php echo e(route('register')); ?>" class="cta-btn primary nepali">निःशुल्क साइन अप गर्नुहोस्</a>
            <a href="<?php echo e(route('demo')); ?>" class="cta-btn secondary nepali">डेमो हेर्नुहोस्</a>
        </div>
    </div>
</section>

<!-- Modal -->
<div class="gallery-modal">
    <button class="modal-close">
        <i class="fas fa-times"></i>
    </button>
    <button class="modal-nav modal-prev">
        <i class="fas fa-chevron-left"></i>
    </button>
    <button class="modal-nav modal-next">
        <i class="fas fa-chevron-right"></i>
    </button>
    
    <div class="modal-content">
        <!-- Media will be inserted here by JavaScript -->
    </div>
    
    <div class="modal-info">
        <div class="modal-title nepali"></div>
        <div class="modal-description nepali"></div>
        <div class="modal-meta">
            <span class="modal-category nepali"></span>
            <span class="modal-date"></span>
            <span class="modal-hostel nepali"></span>
            <span class="modal-room nepali"></span>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script src="<?php echo e(asset('js/gallery.js')); ?>"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Hostel filter functionality
    const hostelFilter = document.getElementById('hostelFilter');
    const galleryItems = document.querySelectorAll('.gallery-item');
    const noResults = document.querySelector('.no-results');
    let currentIndex = 0;
    let visibleItems = Array.from(galleryItems);
    
    function filterGalleries() {
        const selectedHostelId = hostelFilter.value;
        const searchTerm = document.querySelector('.search-input').value.toLowerCase();
        const activeCategory = document.querySelector('.filter-btn.active').getAttribute('data-filter');
        
        let visibleCount = 0;
        
        galleryItems.forEach(item => {
            const itemHostelId = item.getAttribute('data-hostel-id');
            const title = item.getAttribute('data-title').toLowerCase();
            const description = item.getAttribute('data-description').toLowerCase();
            const category = item.getAttribute('data-category');
            const hostel = item.getAttribute('data-hostel').toLowerCase();
            const roomNumber = item.getAttribute('data-room-number').toLowerCase();
            
            // Hostel filter
            const hostelMatch = !selectedHostelId || itemHostelId === selectedHostelId;
            
            // Search filter
            const searchMatch = !searchTerm || 
                title.includes(searchTerm) || 
                description.includes(searchTerm) || 
                category.includes(searchTerm) ||
                hostel.includes(searchTerm) ||
                roomNumber.includes(searchTerm);
            
            // Category filter
            const categoryMatch = activeCategory === 'all' || category === activeCategory;
            
            if (hostelMatch && searchMatch && categoryMatch) {
                item.style.display = 'block';
                visibleCount++;
            } else {
                item.style.display = 'none';
            }
        });
        
        // Update visibleItems array with currently visible items
        visibleItems = Array.from(galleryItems).filter(item => item.style.display !== 'none');
        
        // Show/hide no results message
        const noResultsElement = document.querySelector('.no-results.hidden');
        if (visibleCount === 0) {
            noResultsElement.classList.remove('hidden');
        } else {
            noResultsElement.classList.add('hidden');
        }
    }
    
    // Hostel filter event
    hostelFilter.addEventListener('change', filterGalleries);
    
    // Search input event
    const searchInput = document.querySelector('.search-input');
    searchInput.addEventListener('input', filterGalleries);
    
    // Category filter events
    const filterButtons = document.querySelectorAll('.filter-btn');
    filterButtons.forEach(button => {
        button.addEventListener('click', function() {
            // Remove active class from all buttons
            filterButtons.forEach(btn => btn.classList.remove('active'));
            // Add active class to clicked button
            this.classList.add('active');
            // Apply filters
            filterGalleries();
        });
    });

    // Enhanced modal functionality with hostel info
    const modal = document.querySelector('.gallery-modal');
    const modalTitle = modal.querySelector('.modal-title');
    const modalDescription = modal.querySelector('.modal-description');
    const modalCategory = modal.querySelector('.modal-category');
    const modalDate = modal.querySelector('.modal-date');
    const modalHostel = modal.querySelector('.modal-hostel');
    const modalRoom = modal.querySelector('.modal-room');
    const modalContent = modal.querySelector('.modal-content');
    
    // Gallery item click event
    galleryItems.forEach((item, index) => {
        item.addEventListener('click', function() {
            currentIndex = visibleItems.indexOf(this);
            openModal(this);
        });
    });
    
    function openModal(item) {
        const title = item.getAttribute('data-title');
        const description = item.getAttribute('data-description');
        const category = item.getAttribute('data-category');
        const date = item.getAttribute('data-date');
        const hostel = item.getAttribute('data-hostel');
        const roomNumber = item.getAttribute('data-room-number');
        const mediaType = item.getAttribute('data-media-type');
        const youtubeEmbed = item.getAttribute('data-youtube-embed');
        
        // Set modal content
        modalTitle.textContent = title;
        modalDescription.textContent = description;
        modalCategory.textContent = category;
        modalDate.textContent = date;
        modalHostel.textContent = hostel;
        modalRoom.textContent = roomNumber ? `कोठा: ${roomNumber}` : '';
        
        // Clear previous content
        modalContent.innerHTML = '';
        
        // Add media based on type
        if (mediaType === 'photo') {
            const img = document.createElement('img');
            img.src = item.querySelector('img').src;
            img.alt = title;
            img.style.width = '100%';
            img.style.height = 'auto';
            img.style.display = 'block';
            modalContent.appendChild(img);
        } else if (mediaType === 'external_video' && youtubeEmbed) {
            const iframe = document.createElement('iframe');
            iframe.src = youtubeEmbed;
            iframe.width = '100%';
            iframe.height = '400';
            iframe.allowFullscreen = true;
            iframe.style.border = 'none';
            modalContent.appendChild(iframe);
        } else if (mediaType === 'local_video') {
            const video = document.createElement('video');
            video.src = item.querySelector('source')?.src || '#';
            video.controls = true;
            video.style.width = '100%';
            video.style.height = 'auto';
            modalContent.appendChild(video);
        }
        
        // Show modal
        modal.classList.add('active');
        document.body.style.overflow = 'hidden';
    }
    
    // Close modal
    modal.querySelector('.modal-close').addEventListener('click', function() {
        modal.classList.remove('active');
        document.body.style.overflow = 'auto';
    });
    
    // Close modal when clicking outside content
    modal.addEventListener('click', function(e) {
        if (e.target === modal) {
            modal.classList.remove('active');
            document.body.style.overflow = 'auto';
        }
    });
    
    // Navigation between gallery items in modal
    modal.querySelector('.modal-next').addEventListener('click', function() {
        if (currentIndex < visibleItems.length - 1) {
            currentIndex++;
            openModal(visibleItems[currentIndex]);
        } else {
            currentIndex = 0;
            openModal(visibleItems[currentIndex]);
        }
    });
    
    modal.querySelector('.modal-prev').addEventListener('click', function() {
        if (currentIndex > 0) {
            currentIndex--;
            openModal(visibleItems[currentIndex]);
        } else {
            currentIndex = visibleItems.length - 1;
            openModal(visibleItems[currentIndex]);
        }
    });
    
    // Keyboard navigation
    document.addEventListener('keydown', function(e) {
        if (modal.classList.contains('active')) {
            if (e.key === 'ArrowRight') {
                modal.querySelector('.modal-next').click();
            } else if (e.key === 'ArrowLeft') {
                modal.querySelector('.modal-prev').click();
            } else if (e.key === 'Escape') {
                modal.classList.remove('active');
                document.body.style.overflow = 'auto';
            }
        }
    });

    // Initialize filters
    filterGalleries();
});
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.frontend', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\My Projects\HostelHub\resources\views\frontend\gallery\index.blade.php ENDPATH**/ ?>