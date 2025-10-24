<?php $__env->startSection('page-title', 'ग्यालरी - HostelHub'); ?>
<?php $__env->startSection('page-header', 'हाम्रो ग्यालरी'); ?>
<?php $__env->startSection('page-description', 'हाम्रा विभिन्न होस्टलहरूको कोठा, सुविधा र आवासीय क्षेत्रहरूको वास्तविक झलकहरू अन्वेषण गर्नुहोस्'); ?>

<?php $__env->startPush('styles'); ?>
<link rel="stylesheet" href="<?php echo e(asset('css/gallery.css')); ?>">
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>

<!-- Filters Section -->
<section class="gallery-filters">
    <div class="filter-container">
        <div class="filter-header">
            <h2 class="nepali">ग्यालरी फिल्टर गर्नुहोस्</h2>
            <p class="nepali">तपाईंले हेर्न चाहनुभएको विशेष प्रकारको मिडिया चयन गर्नुहोस्</p>
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
                <input type="text" class="search-input nepali" placeholder="कोठा, सुविधा वा स्थान खोज्नुहोस्...">
            </div>
        </div>
    </div>
</section>

<!-- Main Gallery Section -->
<section class="gallery-main">
    <div class="gallery-container">
        <!-- Gallery Grid -->
        <div class="gallery-grid">
            <!-- Sample gallery items - Replace with dynamic data from controller -->
            <div class="gallery-item image" 
                 data-category="१ सिटर कोठा"
                 data-title="सफा कोठा"
                 data-description="आरामदायी र सफा कोठा"
                 data-date="2025-10-24">
                <img src="https://images.unsplash.com/photo-1586023492125-27b2c045efd7?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=500&q=80" alt="सफा कोठा" class="gallery-media" loading="lazy">
                <div class="media-overlay">
                    <div class="media-title nepali">सफा कोठा</div>
                    <div class="media-description nepali">आरामदायी र सफा कोठा</div>
                    <div class="media-meta">
                        <span class="media-category nepali">१ सिटर कोठा</span>
                        <span class="media-date">2025-10-24</span>
                    </div>
                </div>
            </div>

            <div class="gallery-item video" 
                 data-category="भिडियो टुर"
                 data-title="होस्टल भिडियो टुर"
                 data-description="हाम्रो होस्टलको भिडियो टुर"
                 data-date="2025-10-24"
                 data-youtube-id="abc123">
                <img src="https://images.unsplash.com/photo-1564013799919-ab600027ffc6?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=500&q=80" alt="होस्टल भिडियो टुर" class="gallery-media" loading="lazy">
                <div class="video-badge">
                    <i class="fas fa-play"></i>
                    <span>भिडियो</span>
                </div>
                <div class="media-overlay">
                    <div class="media-title nepali">होस्टल भिडियो टुर</div>
                    <div class="media-description nepali">हाम्रो होस्टलको भिडियो टुर</div>
                    <div class="media-meta">
                        <span class="media-category nepali">भिडियो टुर</span>
                        <span class="media-date">2025-10-24</span>
                    </div>
                </div>
            </div>

            <!-- Add more sample items or use dynamic data -->
            <div class="gallery-item image" 
                 data-category="२ सिटर कोठा"
                 data-title="आरामदायी कोठा"
                 data-description="ठूलो र आरामदायी २ सिटर कोठा"
                 data-date="2025-10-23">
                <img src="https://images.unsplash.com/photo-1522708323590-d24dbb6b0267?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=500&q=80" alt="आरामदायी कोठा" class="gallery-media" loading="lazy">
                <div class="media-overlay">
                    <div class="media-title nepali">आरामदायी कोठा</div>
                    <div class="media-description nepali">ठूलो र आरामदायी २ सिटर कोठा</div>
                    <div class="media-meta">
                        <span class="media-category nepali">२ सिटर कोठा</span>
                        <span class="media-date">2025-10-23</span>
                    </div>
                </div>
            </div>

            <div class="gallery-item image" 
                 data-category="लिभिङ रूम"
                 data-title="साझा लिभिङ रूम"
                 data-description="आधुनिक साझा लिभिङ रूम"
                 data-date="2025-10-22">
                <img src="https://images.unsplash.com/photo-1583847268964-b28dc8f51f92?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=500&q=80" alt="साझा लिभिङ रूम" class="gallery-media" loading="lazy">
                <div class="media-overlay">
                    <div class="media-title nepali">साझा लिभिङ रूम</div>
                    <div class="media-description nepali">आधुनिक साझा लिभिङ रूम</div>
                    <div class="media-meta">
                        <span class="media-category nepali">लिभिङ रूम</span>
                        <span class="media-date">2025-10-22</span>
                    </div>
                </div>
            </div>

            <div class="gallery-item image" 
                 data-category="भान्सा"
                 data-title="आधुनिक भान्सा"
                 data-description="सफा र आधुनिक भान्सा"
                 data-date="2025-10-21">
                <img src="https://images.unsplash.com/photo-1556909114-f6e7ad7d3136?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=500&q=80" alt="आधुनिक भान्सा" class="gallery-media" loading="lazy">
                <div class="media-overlay">
                    <div class="media-title nepali">आधुनिक भान्सा</div>
                    <div class="media-description nepali">सफा र आधुनिक भान्सा</div>
                    <div class="media-meta">
                        <span class="media-category nepali">भान्सा</span>
                        <span class="media-date">2025-10-21</span>
                    </div>
                </div>
            </div>

            <div class="gallery-item image" 
                 data-category="अध्ययन कोठा"
                 data-title="अध्ययन कोठा"
                 data-description="शान्त अध्ययन कोठा"
                 data-date="2025-10-20">
                <img src="https://images.unsplash.com/photo-1497366754035-f200968a6e72?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=500&q=80" alt="अध्ययन कोठा" class="gallery-media" loading="lazy">
                <div class="media-overlay">
                    <div class="media-title nepali">अध्ययन कोठा</div>
                    <div class="media-description nepali">शान्त अध्ययन कोठा</div>
                    <div class="media-meta">
                        <span class="media-category nepali">अध्ययन कोठा</span>
                        <span class="media-date">2025-10-20</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- No Results Message -->
        <div class="no-results">
            <i class="fas fa-search"></i>
            <h3 class="nepali">कुनै परिणाम फेला परेन</h3>
            <p class="nepali">तपाईंको खोजसँग मिल्ने कुनै ग्यालरी आइटम फेला परेन। कृपया अर्को खोज प्रयास गर्नुहोस्।</p>
        </div>

        <!-- Loading Indicator -->
        <div class="gallery-loading">
            <div class="loading-spinner"></div>
            <p class="nepali">ग्यालरी आइटमहरू लोड हुँदैछ...</p>
        </div>

        <!-- Load More Button -->
        <div class="load-more-section">
            <button class="load-more-btn nepali">थप हेर्नुहोस्</button>
        </div>
    </div>
</section>

<!-- Stats Section -->
<section class="gallery-stats">
    <div class="stats-grid">
        <div class="stat-item">
            <div class="stat-number">500+</div>
            <div class="stat-label nepali">खुसी विद्यार्थीहरू</div>
        </div>
        <div class="stat-item">
            <div class="stat-number">25</div>
            <div class="stat-label nepali">सहयोगी होस्टल</div>
        </div>
        <div class="stat-item">
            <div class="stat-number">5</div>
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
        <div class="modal-title"></div>
        <div class="modal-description"></div>
        <div class="modal-meta">
            <span class="modal-category"></span>
            <span class="modal-date"></span>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script src="<?php echo e(asset('js/gallery.js')); ?>"></script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.frontend', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\My Projects\HostelHub\resources\views/frontend/gallery/index.blade.php ENDPATH**/ ?>