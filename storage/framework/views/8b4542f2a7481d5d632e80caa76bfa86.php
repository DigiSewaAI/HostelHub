<!-- gallery.blade.php -->


<?php $__env->startSection('page-title', 'Sanctuary Girls Hostel - Premium Gallery | HostelHub'); ?>

<?php $__env->startSection('page-header', 'Sanctuary Girls Hostel Premium Gallery'); ?>
<?php $__env->startSection('page-description', 'हाम्रो होस्टलको विश्वस्तरीय सुविधाहरू, आधुनिक कोठाहरू, र रमाइलो विद्यार्थी जीवनको immersive experience'); ?>

<?php $__env->startSection('content'); ?>
<style>
    /* Gallery Specific Styles */
    .gallery-hero {
        background: linear-gradient(rgba(30, 58, 138, 0.85), rgba(14, 165, 233, 0.85)), url('https://images.unsplash.com/photo-1555854877-bab0e564b8d5?ixlib=rb-1.2.1&auto=format&fit=crop&w=1350&q=80');
        background-size: cover;
        background-position: center;
        color: white;
        padding: 80px 0 60px;
        text-align: center;
        margin-bottom: 50px;
    }
    
    .gallery-stats {
        display: flex;
        justify-content: center;
        gap: 40px;
        margin-top: 40px;
        flex-wrap: wrap;
    }
    
    .stat-item {
        text-align: center;
    }
    
    .stat-number {
        font-size: 2.2rem;
        font-weight: 700;
        margin-bottom: 5px;
    }
    
    .stat-label {
        font-size: 1rem;
        opacity: 0.9;
    }
    
    .gallery-section {
        padding: 30px 0 60px;
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
    }
    
    .features-section {
        background: var(--bg-light);
        padding: 70px 0;
        border-radius: var(--radius);
        margin: 50px 0;
    }
    
    .features-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 30px;
    }
    
    .feature-card {
        text-align: center;
        padding: 40px 25px;
        border-radius: var(--radius);
        background: white;
        box-shadow: var(--shadow);
        transition: transform 0.3s, box-shadow 0.3s;
    }
    
    .feature-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 15px 30px rgba(0, 0, 0, 0.1);
    }
    
    .feature-icon {
        font-size: 3rem;
        margin-bottom: 20px;
        color: var(--primary);
    }
    
    .feature-title {
        font-size: 1.4rem;
        margin-bottom: 15px;
        color: var(--text-dark);
        font-weight: 600;
    }
    
    .feature-description {
        color: var(--text-dark);
        opacity: 0.8;
        line-height: 1.6;
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
    
    /* Responsive Design */
    @media (max-width: 768px) {
        .gallery-hero {
            padding: 60px 0 40px;
        }
        
        .gallery-stats {
            gap: 25px;
        }
        
        .stat-number {
            font-size: 1.8rem;
        }
        
        .gallery-grid {
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 20px;
        }
        
        .gallery-item {
            height: 240px;
        }
        
        .features-grid {
            grid-template-columns: 1fr;
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
            padding: 8px 16px;
            font-size: 0.9rem;
        }
    }
</style>

<!-- Gallery Hero Section -->
<section class="gallery-hero">
    <div class="container">
        <h1 class="nepali">Sanctuary Girls Hostel Premium Gallery Experience</h1>
        <p class="nepali" style="max-width: 800px; margin: 0 auto 30px; font-size: 1.1rem;">
            हाम्रो होस्टलको विश्वस्तरीय सुविधाहरू, आधुनिक कोठाहरू, र रमाइलो विद्यार्थी जीवनको immersive experience को साथ हेर्नुहोस्
        </p>
        
        <div class="gallery-stats">
            <div class="stat-item">
                <div class="stat-number">🎓 500+</div>
                <div class="stat-label nepali">विद्यार्थीहरू</div>
            </div>
            <div class="stat-item">
                <div class="stat-number">⭐ 98%</div>
                <div class="stat-label nepali">सन्तुष्टि दर</div>
            </div>
            <div class="stat-item">
                <div class="stat-number">🏙️ 5+</div>
                <div class="stat-label nepali">शहरहरू</div>
            </div>
        </div>
        
        <div style="margin-top: 40px;">
            <a href="#gallery" class="btn btn-primary nepali" style="margin-right: 15px;">ग्यालरी हेर्नुहोस्</a>
            <a href="<?php echo e(route('contact')); ?>" class="btn btn-outline nepali" style="background: transparent; color: white; border-color: white;">अहिले बुक गर्नुहोस्</a>
        </div>
    </div>
</section>

<!-- Gallery Section -->
<section class="gallery-section" id="gallery">
    <div class="container">
        <h2 class="section-title nepali">Premium Gallery</h2>
        <p style="text-align: center; margin-bottom: 40px; color: var(--text-dark); opacity: 0.8; max-width: 700px; margin-left: auto; margin-right: auto;" class="nepali">
            हाम्रो होस्टलको विशेषताहरूको immersive tour
        </p>
        
        <div class="gallery-filters">
            <button class="filter-btn active nepali" data-filter="all">सबै</button>
            <button class="filter-btn nepali" data-filter="1-seater">१ सिटर कोठा</button>
            <button class="filter-btn nepali" data-filter="2-seater">२ सिटर कोठा</button>
            <button class="filter-btn nepali" data-filter="4-seater">४ सिटर कोठा</button>
            <button class="filter-btn nepali" data-filter="video">भिडियो</button>
            <button class="filter-btn nepali" data-filter="facilities">सुविधाहरू</button>
        </div>
        
        <div class="gallery-grid">
            <!-- 1 Seater Room -->
            <div class="gallery-item" data-category="1-seater">
                <img src="https://images.unsplash.com/photo-1586023492125-27b2c045efd7?ixlib=rb-1.2.1&auto=format&fit=crop&w=1350&q=80" alt="आधुनिक १ सिटर कोठा">
                <div class="featured-badge nepali">Featured</div>
                <div class="gallery-overlay">
                    <h3 class="gallery-title nepali">आधुनिक १ सिटर कोठा</h3>
                    <p class="nepali">पूर्ण सुसज्जित आधुनिक १ सिटर कोठा</p>
                    <button class="btn btn-primary" style="margin-top: 12px; padding: 8px 16px; font-size: 0.9rem;" onclick="openModal('image1')">विस्तृत हेर्नुहोस्</button>
                </div>
            </div>
            
            <!-- 2 Seater Room -->
            <div class="gallery-item" data-category="2-seater">
                <img src="https://images.unsplash.com/photo-1555854877-bab0e564b8d5?ixlib=rb-1.2.1&auto=format&fit=crop&w=1350&q=80" alt="२ सिटर कोठा">
                <div class="gallery-overlay">
                    <h3 class="gallery-title nepali">२ सिटर कोठा</h3>
                    <p class="nepali">ठूलो २ सिटर कोठा</p>
                    <button class="btn btn-primary" style="margin-top: 12px; padding: 8px 16px; font-size: 0.9rem;" onclick="openModal('image2')">विस्तृत हेर्नुहोस्</button>
                </div>
            </div>
            
            <!-- Video Tour -->
            <div class="gallery-item" data-category="video">
                <div style="width:100%; height:100%; background: #000; display: flex; align-items: center; justify-content: center; position: relative;">
                    <i class="fas fa-play-circle" style="font-size: 4rem; color: white; position: absolute; z-index: 1;"></i>
                    <img src="https://images.unsplash.com/photo-1566073771259-6a8506099945?ixlib=rb-1.2.1&auto=format&fit=crop&w=1350&q=80" alt="होस्टल टुर भिडियो" style="opacity: 0.7;">
                </div>
                <div class="featured-badge nepali">Featured</div>
                <div class="gallery-overlay">
                    <h3 class="gallery-title nepali">होस्टल टुर भिडियो</h3>
                    <p class="nepali">होस्टलको पूर्ण टुर</p>
                    <button class="btn btn-primary" style="margin-top: 12px; padding: 8px 16px; font-size: 0.9rem;" onclick="openModal('video1')">भिडियो हेर्नुहोस्</button>
                </div>
            </div>
            
            <!-- 4 Seater Room -->
            <div class="gallery-item" data-category="4-seater">
                <img src="https://images.unsplash.com/photo-1595428774223-ef52624120d2?ixlib=rb-1.2.1&auto=format&fit=crop&w=1350&q=80" alt="४ सिटर कोठा">
                <div class="gallery-overlay">
                    <h3 class="gallery-title nepali">४ सिटर कोठा</h3>
                    <p class="nepali">व्यापक ४ सिटर कोठा</p>
                    <button class="btn btn-primary" style="margin-top: 12px; padding: 8px 16px; font-size: 0.9rem;" onclick="openModal('image3')">विस्तृत हेर्नुहोस्</button>
                </div>
            </div>
            
            <!-- Kitchen -->
            <div class="gallery-item" data-category="facilities">
                <img src="https://images.unsplash.com/photo-1556909114-f6e7ad7d3136?ixlib=rb-1.2.1&auto=format&fit=crop&w=1350&q=80" alt="किचन">
                <div class="gallery-overlay">
                    <h3 class="gallery-title nepali">प्रिमियम किचन</h3>
                    <p class="nepali">आधुनिक किचन सुविधा</p>
                    <button class="btn btn-primary" style="margin-top: 12px; padding: 8px 16px; font-size: 0.9rem;" onclick="openModal('image4')">विस्तृत हेर्नुहोस्</button>
                </div>
            </div>
            
            <!-- Study Area -->
            <div class="gallery-item" data-category="facilities">
                <img src="https://images.unsplash.com/photo-1497366754035-f200968a6e72?ixlib=rb-1.2.1&auto=format&fit=crop&w=1350&q=80" alt="अध्ययन क्षेत्र">
                <div class="gallery-overlay">
                    <h3 class="gallery-title nepali">अध्ययन क्षेत्र</h3>
                    <p class="nepali">शान्त अध्ययन वातावरण</p>
                    <button class="btn btn-primary" style="margin-top: 12px; padding: 8px 16px; font-size: 0.9rem;" onclick="openModal('image5')">विस्तृत हेर्नुहोस्</button>
                </div>
            </div>
        </div>
        
        <div class="view-more">
            <button class="btn btn-outline nepali" style="border-color: var(--primary); color: var(--primary);">थप ग्यालरी हेर्नुहोस्</button>
        </div>
    </div>
</section>

<!-- Features Section -->
<section class="features-section">
    <div class="container">
        <h2 class="section-title nepali">हाम्रो Premium Features</h2>
        <p style="text-align: center; margin-bottom: 50px; color: var(--text-dark); opacity: 0.8; max-width: 700px; margin-left: auto; margin-right: auto;" class="nepali">
            विद्यार्थीहरूको लागि विशेष सुविधाहरू
        </p>
        
        <div class="features-grid">
            <div class="feature-card">
                <div class="feature-icon">🔒</div>
                <h3 class="feature-title nepali">Advanced Security</h3>
                <p class="feature-description nepali">२४/७ सुरक्षा गार्ड, CCTV, biometric access र AI-based monitoring</p>
            </div>
            
            <div class="feature-card">
                <div class="feature-icon">🚀</div>
                <h3 class="feature-title nepali">High-Speed Internet</h3>
                <p class="feature-description nepali">1Gbps fiber internet, dedicated study line, र gaming-optimized connection</p>
            </div>
            
            <div class="feature-card">
                <div class="feature-icon">🍳</div>
                <h3 class="feature-title nepali">Premium Kitchen</h3>
                <p class="feature-description nepali">Modern appliances, weekly cleaning, र professional maintenance</p>
            </div>
            
            <div class="feature-card">
                <div class="feature-icon">💪</div>
                <h3 class="feature-title nepali">Fitness Center</h3>
                <p class="feature-description nepali">Fully equipped gym, yoga studio, र personal trainer availability</p>
            </div>
        </div>
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
            <source src="#" type="video/mp4">
            तपाईंको ब्राउजरले भिडियो सपोर्ट गर्दैन।
        </video>
        <div class="modal-caption">
            <h3 id="videoTitle" class="nepali"></h3>
            <p id="videoDescription" class="nepali"></p>
        </div>
    </div>
</div>

<script>
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
    
    // Modal Functionality
    function openModal(type) {
        if (type.includes('image')) {
            document.getElementById('imageModal').style.display = 'flex';
            // In a real implementation, you would set the src to the actual image
            document.getElementById('modalImage').src = "https://images.unsplash.com/photo-1586023492125-27b2c045efd7?ixlib=rb-1.2.1&auto=format&fit=crop&w=1350&q=80";
            document.getElementById('modalTitle').textContent = "आधुनिक १ सिटर कोठा";
            document.getElementById('modalDescription').textContent = "पूर्ण सुसज्जित आधुनिक १ सिटर कोठा";
        } else if (type.includes('video')) {
            document.getElementById('videoModal').style.display = 'flex';
            // In a real implementation, you would set the src to the actual video
            document.getElementById('modalVideo').src = "#";
            document.getElementById('videoTitle').textContent = "होस्टल टुर भिडियो";
            document.getElementById('videoDescription').textContent = "होस्टलको पूर्ण टुर";
        }
    }
    
    function closeModal() {
        document.getElementById('imageModal').style.display = 'none';
        document.getElementById('videoModal').style.display = 'none';
        
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
        
        if (event.target === imageModal) {
            imageModal.style.display = 'none';
        }
        
        if (event.target === videoModal) {
            videoModal.style.display = 'none';
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
<?php echo $__env->make('layouts.frontend', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\My Projects\HostelHub\resources\views/public/hostels/gallery.blade.php ENDPATH**/ ?>