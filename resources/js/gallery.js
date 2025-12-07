// Enhanced Gallery Functionality - UPDATED VERSION with Videos Placeholder Fix
class GalleryManager {
    constructor() {
        this.currentFilter = 'all';
        this.currentSearch = '';
        this.currentHostelFilter = '';
        this.currentTab = 'photos';
        this.currentVideoCategory = 'all';
        this.visibleItems = 12;
        this.currentMediaIndex = 0;
        this.mediaItems = [];
        this.videoItems = [];
        this.isLoading = false;
        this.videoPage = 1;
        this.hasMoreVideos = true;
        this.isLoadingVideos = false;
        this.hdImagesLoaded = new Set();
        this.videoCategories = {};
        this.currentItems = [];
        
        // ✅ FIXED: Add spinner guard variables
        this.spinnerGuard = false;
        this.spinnerTimeout = null;
        
        // Check if we're on a tab that requires full page reload
        const urlParams = new URLSearchParams(window.location.search);
        const tabParam = urlParams.get('tab');
        if (tabParam && (tabParam === 'videos' || tabParam === 'virtual-tours')) {
            // If server already loaded videos tab, don't use client-side filtering
            this.currentTab = tabParam;
        }
        
        this.init();
    }
    
    init() {
        console.log('Enhanced GalleryManager initialized - Tab:', this.currentTab);
        
        // ✅ FIXED: Immediately hide all spinners before anything else
        this.hideAllSpinnersImmediately();
        
        this.cacheElements();
        this.bindEvents();
        
        // ✅ FIXED: Immediately hide videos placeholder if videos are loaded
        this.hideVideosPlaceholder();
        
        // Only initialize items if we're on photos tab or videos were loaded client-side
        if (this.currentTab === 'photos') {
            this.initMediaItems();
            this.setupIntersectionObserver();
            this.applyFilters();
        } else {
            // For videos/virtual-tours tabs, handle differently
            this.handleVideoTabInitialization();
        }
        
        // ✅ FIXED: Extra check after initialization with spinner guard
        clearTimeout(this.spinnerTimeout);
        this.spinnerTimeout = setTimeout(() => {
            this.hideAllSpinnersImmediately();
            this.hideVideosPlaceholder();
        }, 100);
    }
    
    cacheElements() {
        this.elements = {
            filterButtons: document.querySelectorAll('.filter-btn'),
            galleryItems: document.querySelectorAll('.gallery-item'),
            videoCards: document.querySelectorAll('.video-card'),
            searchInput: document.querySelector('.search-input'),
            hostelFilter: document.getElementById('hostelFilter'),
            loadMoreBtn: document.querySelector('.load-more-btn'),
            galleryModal: document.querySelector('.gallery-modal'),
            modalContent: document.querySelector('.modal-content'),
            modalClose: document.querySelector('.modal-close'),
            modalPrev: document.querySelector('.modal-prev'),
            modalNext: document.querySelector('.modal-next'),
            modalTitle: document.querySelector('.modal-title'),
            modalDescription: document.querySelector('.modal-description'),
            modalCategory: document.querySelector('.modal-category'),
            modalDate: document.querySelector('.modal-date'),
            modalHostel: document.querySelector('.modal-hostel'),
            modalRoom: document.querySelector('.modal-room'),
            modalHostelLink: document.querySelector('.modal-hostel-link'),
            galleryGrid: document.querySelector('.gallery-grid'),
            videosGrid: document.querySelector('.videos-grid'),
            loadingIndicator: document.querySelector('.gallery-loading'),
            videosLoadingIndicator: document.querySelector('.videos-loading'),
            noResults: document.querySelector('.no-results'),
            tabButtons: document.querySelectorAll('.tab-btn'),
            tabContents: document.querySelectorAll('.tab-content'),
            videoCategoryButtons: document.querySelectorAll('.video-category-btn'),
            videoCategoriesContainer: document.querySelector('.video-categories'),
            mealGalleryButton: document.querySelector('.meal-gallery-button'),
            galleryCtaButtons: document.querySelectorAll('.gallery-trial-button, .gallery-demo-button, .gallery-outline-button'),
            videosPlaceholder: document.querySelector('.videos-placeholder')
        };
    }
    
    // ✅ NEW: Method to immediately hide all spinners
    hideAllSpinnersImmediately() {
        if (this.spinnerGuard) return;
        this.spinnerGuard = true;
        
        // Hide main gallery loading indicator
        if (this.elements.loadingIndicator) {
            this.elements.loadingIndicator.style.display = 'none';
            this.elements.loadingIndicator.classList.add('hidden');
        }
        
        // Hide videos loading indicator
        if (this.elements.videosLoadingIndicator) {
            this.elements.videosLoadingIndicator.style.display = 'none';
            this.elements.videosLoadingIndicator.classList.add('hidden');
        }
        
        // Hide any spinner elements in videos placeholder
        const spinners = document.querySelectorAll('.spinner, .loading-spinner, .videos-placeholder .spinner');
        spinners.forEach(spinner => {
            spinner.style.display = 'none';
            spinner.classList.add('hidden');
        });
        
        // Reset load more button
        if (this.elements.loadMoreBtn) {
            this.elements.loadMoreBtn.disabled = false;
            this.elements.loadMoreBtn.textContent = 'थप हेर्नुहोस्';
        }
        
        console.log('All spinners hidden immediately');
    }
    
    // ✅ FIXED: Enhanced function to hide videos placeholder - SIMPLIFIED VERSION
    hideVideosPlaceholder() {
        // Only run if we're on videos or virtual-tours tab
        if (this.currentTab !== 'videos' && this.currentTab !== 'virtual-tours') {
            return;
        }
        
        const placeholder = this.elements.videosPlaceholder;
        const videoCards = this.elements.videoCards;
        
        if (videoCards && videoCards.length > 0 && placeholder) {
            console.log('Hiding videos placeholder - found', videoCards.length, 'videos');
            placeholder.style.display = 'none';
            placeholder.classList.add('hidden');
            placeholder.style.visibility = 'hidden';
            placeholder.style.opacity = '0';
            placeholder.style.height = '0';
            placeholder.style.padding = '0';
            placeholder.style.margin = '0';
            placeholder.style.overflow = 'hidden';
        }
    }
    
    bindEvents() {
        // Bind filter buttons for ALL tabs
        this.elements.filterButtons.forEach(btn => {
            btn.addEventListener('click', (e) => this.handleFilter(e));
        });
        
        // Search input (works for all tabs)
        if (this.elements.searchInput) {
            this.elements.searchInput.addEventListener('input', (e) => this.handleSearch(e));
            this.elements.searchInput.addEventListener('keypress', (e) => {
                if (e.key === 'Enter') {
                    this.handleSearch(e);
                }
            });
        }
        
        // Hostel filter (works for all tabs)
        if (this.elements.hostelFilter) {
            this.elements.hostelFilter.addEventListener('change', (e) => this.handleHostelFilter(e));
        }
        
        // Load more (works for all tabs)
        if (this.elements.loadMoreBtn) {
            this.elements.loadMoreBtn.addEventListener('click', () => this.loadMoreItems());
        }
        
        // Gallery item clicks (only for photos tab)
        if (this.currentTab === 'photos') {
            this.elements.galleryItems.forEach((item, index) => {
                item.addEventListener('click', (e) => {
                    if (!e.target.closest('.hostel-link-enhanced, .quick-view-btn')) {
                        this.openModal(index, 'photo');
                    }
                });
            });
        }
        
        // Modal events (always bind these)
        if (this.elements.modalClose) {
            this.elements.modalClose.addEventListener('click', () => this.closeModal());
        }
        
        if (this.elements.modalPrev) {
            this.elements.modalPrev.addEventListener('click', () => this.navigateModal(-1));
        }
        
        if (this.elements.modalNext) {
            this.elements.modalNext.addEventListener('click', () => this.navigateModal(1));
        }
        
        // Video card clicks - ✅ FIXED: Simplified approach
        this.attachVideoCardEvents();
        
        // Tab switching - ✅ FIXED: Use server-side navigation for videos/virtual-tours
        if (this.elements.tabButtons) {
            this.elements.tabButtons.forEach(btn => {
                btn.addEventListener('click', (e) => {
                    const tab = btn.getAttribute('href').includes('videos') ? 'videos' : 
                               btn.getAttribute('href').includes('virtual-tours') ? 'virtual-tours' : 'photos';
                    
                    // For videos and virtual-tours, let the server handle it
                    if (tab === 'videos' || tab === 'virtual-tours') {
                        e.preventDefault();
                        window.location.href = btn.getAttribute('href');
                        return;
                    }
                    
                    // For photos, handle client-side
                    if (btn.classList.contains('active')) {
                        e.preventDefault();
                        return;
                    }
                    this.handleTabSwitch(e);
                });
            });
        }
        
        // Video category filtering - ✅ FIXED: Use server-side filtering
        if (this.elements.videoCategoryButtons && this.currentTab === 'videos') {
            this.elements.videoCategoryButtons.forEach(btn => {
                btn.addEventListener('click', (e) => {
                    const category = btn.dataset.category;
                    if (category && category !== this.currentVideoCategory) {
                        e.preventDefault();
                        const url = new URL(window.location);
                        url.searchParams.set('video_category', category);
                        window.location.href = url.toString();
                    }
                });
            });
        }
        
        // Meal gallery button
        if (this.elements.mealGalleryButton) {
            this.elements.mealGalleryButton.addEventListener('click', (e) => {
                e.preventDefault();
                window.location.href = this.elements.mealGalleryButton.href;
            });
        }
        
        // CTA buttons
        if (this.elements.galleryCtaButtons) {
            this.elements.galleryCtaButtons.forEach(btn => {
                btn.addEventListener('click', (e) => {
                    if (btn.disabled) {
                        e.preventDefault();
                        return;
                    }
                });
            });
        }
        
        // Keyboard navigation
        document.addEventListener('keydown', (e) => this.handleKeyboard(e));
        
        // Close modal on outside click
        if (this.elements.galleryModal) {
            this.elements.galleryModal.addEventListener('click', (e) => {
                if (e.target === this.elements.galleryModal) {
                    this.closeModal();
                }
            });
        }
        
        // HD image click handlers
        this.setupHdImageLoading();
    }
    
    // ✅ FIXED: Handle video tab initialization with better placeholder handling
    handleVideoTabInitialization() {
        console.log('Initializing video tab functionality for:', this.currentTab);
        
        // ✅ FIXED: Hide spinners immediately
        this.hideAllSpinnersImmediately();
        
        // Initialize video items
        this.videoItems = Array.from(this.elements.videoCards || []);
        console.log(`Found ${this.videoItems.length} video items`);
        
        // ✅ FIXED: Hide videos placeholder immediately when videos are loaded
        this.hideVideosPlaceholder();
        
        // Set up intersection observer for videos
        if (this.videoItems.length > 0) {
            this.setupIntersectionObserver();
        }
        
        // Apply filters if we have video items
        if (this.videoItems.length > 0) {
            this.applyFilters();
        }
        
        // ✅ FIXED: Additional check after content is fully loaded
        clearTimeout(this.spinnerTimeout);
        this.spinnerTimeout = setTimeout(() => {
            this.hideAllSpinnersImmediately();
            this.hideVideosPlaceholder();
        }, 500);
    }
    
    // ✅ FIXED: Simplified video card events
    attachVideoCardEvents() {
        const videoCards = document.querySelectorAll('.video-card');
        videoCards.forEach((card, index) => {
            // Add fresh event listener with proper event delegation
            card.addEventListener('click', (e) => {
                // Prevent click if clicked on hostel link or video hostel link
                if (e.target.closest('.hostel-link-enhanced, .video-hostel-link')) {
                    return;
                }
                
                // Find the index in the current videoItems array
                const currentIndex = Array.from(videoCards).indexOf(card);
                if (currentIndex !== -1) {
                    this.openModal(currentIndex, 'video');
                }
            });
        });
        
        // Update video items reference
        this.videoItems = Array.from(videoCards);
    }
    
    setupIntersectionObserver() {
        // Lazy load images and videos when they enter viewport
        this.observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const img = entry.target.querySelector('img');
                    if (img && img.dataset.src) {
                        img.src = img.dataset.src;
                        img.removeAttribute('data-src');
                    }
                    
                    // Handle HD image preloading
                    if (entry.target.classList.contains('gallery-item')) {
                        const hdAvailable = entry.target.dataset.hdAvailable === 'true';
                        const mediaId = entry.target.dataset.id;
                        if (hdAvailable && !this.hdImagesLoaded.has(mediaId)) {
                            this.preloadHdImage(mediaId, entry.target);
                        }
                    }
                }
            });
        }, {
            rootMargin: '100px',
            threshold: 0.1
        });
        
        // Observe all gallery items and video cards
        const itemsToObserve = this.currentTab === 'photos' ? 
            document.querySelectorAll('.gallery-item') : 
            document.querySelectorAll('.video-card');
            
        itemsToObserve.forEach(item => {
            this.observer.observe(item);
        });
    }
    
    initMediaItems() {
        this.mediaItems = Array.from(this.elements.galleryItems);
        console.log(`Initialized ${this.mediaItems.length} photos`);
    }
    
    handleTabSwitch(e) {
        e.preventDefault();
        const button = e.currentTarget;
        const tab = button.getAttribute('href').includes('videos') ? 'videos' : 
                   button.getAttribute('href').includes('virtual-tours') ? 'virtual-tours' : 'photos';
        
        // Update active tab
        this.elements.tabButtons.forEach(btn => btn.classList.remove('active'));
        button.classList.add('active');
        
        // Update tab content
        this.elements.tabContents.forEach(content => content.classList.remove('active'));
        document.getElementById(`${tab}-tab`).classList.add('active');
        
        // Update current tab
        this.currentTab = tab;
        
        // Reset filters and load appropriate content
        this.visibleItems = 12;
        this.currentFilter = 'all';
        this.currentSearch = '';
        this.currentHostelFilter = '';
        this.currentVideoCategory = 'all';
        
        // Reset filter buttons
        if (this.elements.filterButtons) {
            this.elements.filterButtons.forEach(btn => btn.classList.remove('active'));
            const allFilterBtn = Array.from(this.elements.filterButtons).find(btn => btn.dataset.filter === 'all');
            if (allFilterBtn) allFilterBtn.classList.add('active');
        }
        
        // Reset search input
        if (this.elements.searchInput) {
            this.elements.searchInput.value = '';
        }
        
        // Reset hostel filter
        if (this.elements.hostelFilter) {
            this.elements.hostelFilter.value = '';
        }
        
        // ✅ FIXED: Hide spinners when switching tabs
        this.hideAllSpinnersImmediately();
        
        // Re-initialize based on tab
        if (tab === 'photos') {
            this.initMediaItems();
            this.setupIntersectionObserver();
            this.applyFilters();
        } else {
            // For videos/virtual-tours, redirect to server-side version
            window.location.href = button.getAttribute('href');
        }
    }
    
    handleFilter(e) {
        const button = e.currentTarget;
        const filter = button.dataset.filter;
        
        // Update active button
        this.elements.filterButtons.forEach(btn => btn.classList.remove('active'));
        button.classList.add('active');
        
        this.currentFilter = filter;
        this.visibleItems = 12;
        this.applyFilters();
    }
    
    handleSearch(e) {
        this.currentSearch = e.target.value.toLowerCase().trim();
        this.visibleItems = 12;
        this.applyFilters();
    }
    
    handleHostelFilter(e) {
        const value = e.target.value;
        
        // ✅ FIXED: Simplified gender detection
        if (value === 'boys' || value === 'girls') {
            this.currentHostelFilter = value;
        } else if (value) {
            this.currentHostelFilter = value; // Specific hostel ID
        } else {
            this.currentHostelFilter = '';
        }
        
        this.visibleItems = 12;
        this.applyFilters();
    }
    
    applyFilters() {
        console.log('Applying filters for tab:', this.currentTab, {
            filter: this.currentFilter,
            search: this.currentSearch,
            hostelFilter: this.currentHostelFilter
        });
        
        let visibleCount = 0;
        let itemsToFilter = [];
        
        // Choose which items to filter based on current tab
        if (this.currentTab === 'photos') {
            itemsToFilter = this.mediaItems;
        } else if (this.currentTab === 'videos' || this.currentTab === 'virtual-tours') {
            itemsToFilter = this.videoItems;
        } else {
            return; // Unknown tab
        }
        
        itemsToFilter.forEach((item, index) => {
            // Skip if item doesn't exist
            if (!item) return;
            
            // ✅ FIXED: SEPARATE CATEGORY FILTER LOGIC FOR EACH TAB
            let matchesFilter = true;
            
            if (this.currentTab === 'photos') {
                // Photos tab: Use Nepali category matching
                matchesFilter = this.currentFilter === 'all' || 
                    (item.dataset.category && item.dataset.category === this.currentFilter) ||
                    (item.getAttribute('data-category') && item.getAttribute('data-category') === this.currentFilter);
            } else if (this.currentTab === 'videos') {
                // Videos tab: Use English category matching
                matchesFilter = this.currentFilter === 'all' || 
                    (item.dataset.category && item.dataset.category === this.currentFilter) ||
                    (item.getAttribute('data-category') && item.getAttribute('data-category') === this.currentFilter);
            }
            // For virtual-tours tab, no category filter
            
            // Search filter logic (common for all tabs)
            const matchesSearch = this.currentSearch === '' || 
                (item.dataset.title && item.dataset.title.toLowerCase().includes(this.currentSearch)) ||
                (item.getAttribute('data-title') && item.getAttribute('data-title').toLowerCase().includes(this.currentSearch)) ||
                (item.dataset.description && item.dataset.description.toLowerCase().includes(this.currentSearch)) ||
                (item.getAttribute('data-description') && item.getAttribute('data-description').toLowerCase().includes(this.currentSearch)) ||
                (item.dataset.hostel && item.dataset.hostel.toLowerCase().includes(this.currentSearch)) ||
                (item.getAttribute('data-hostel') && item.getAttribute('data-hostel').toLowerCase().includes(this.currentSearch)) ||
                (item.dataset.roomNumber && item.dataset.roomNumber.toLowerCase().includes(this.currentSearch)) ||
                (item.getAttribute('data-room-number') && item.getAttribute('data-room-number').toLowerCase().includes(this.currentSearch));
            
            // ✅ FIXED: SIMPLIFIED Gender filter logic
            let matchesHostel = true;
            if (this.currentHostelFilter) {
                if (this.currentHostelFilter === 'boys' || this.currentHostelFilter === 'girls' || this.currentHostelFilter === 'mixed') {
                    // Get the normalized gender value from data attribute
                    const hostelGender = item.getAttribute('data-hostel-gender') || item.dataset.hostelGender;
                    matchesHostel = hostelGender === this.currentHostelFilter;
                } else {
                    // Specific hostel by ID
                    const hostelId = item.getAttribute('data-hostel-id') || item.dataset.hostelId;
                    matchesHostel = hostelId == this.currentHostelFilter;
                }
            }
            
            // ✅ FIXED: Virtual tour filter logic
            let matchesVirtualTour = true;
            if (this.currentTab === 'virtual-tours') {
                const is360 = item.getAttribute('data-is-360') || item.dataset.is360;
                matchesVirtualTour = is360 === 'true' || is360 === '1' || is360 === true;
            }
            
            // ✅ FIXED: Apply filters correctly based on tab
            let shouldShow = matchesSearch && matchesHostel;
            
            if (this.currentTab === 'photos' || this.currentTab === 'videos') {
                shouldShow = shouldShow && matchesFilter;
            }
            
            if (this.currentTab === 'virtual-tours') {
                shouldShow = shouldShow && matchesVirtualTour;
            }
            
            if (shouldShow) {
                item.style.display = 'block';
                item.style.opacity = '1';
                item.style.transform = 'scale(1)';
                item.style.animationDelay = `${(index % 12) * 0.1}s`;
                visibleCount++;
                
                // Show/hide based on visible items limit
                if (visibleCount > this.visibleItems) {
                    item.style.display = 'none';
                }
            } else {
                item.style.display = 'none';
            }
        });
        
        // ✅ FIXED: Hide videos placeholder when videos are loaded
        if (this.currentTab === 'videos' || this.currentTab === 'virtual-tours') {
            this.hideVideosPlaceholder();
        }
        
        // ✅ FIXED: Hide spinners after filtering
        this.hideAllSpinnersImmediately();
        
        this.updateLoadMoreButton();
        this.checkNoResults();
        
        // Smooth animation for appearing items
        setTimeout(() => {
            itemsToFilter.forEach(item => {
                if (item && item.style.display === 'block') {
                    item.classList.add('visible');
                }
            });
        }, 50);
    }
    
    showVisibleItems() {
        let shown = 0;
        const itemsToShow = this.currentTab === 'photos' ? this.mediaItems : this.videoItems;
        
        itemsToShow.forEach(item => {
            if (item && item.style.display !== 'none') {
                if (shown < this.visibleItems) {
                    item.style.display = 'block';
                    item.style.animationDelay = `${shown * 0.1}s`;
                    shown++;
                } else {
                    item.style.display = 'none';
                }
            }
        });
    }
    
    loadMoreItems() {
        if (this.isLoading) return;
        
        this.isLoading = true;
        this.showLoading();
        
        // Simulate loading delay
        setTimeout(() => {
            this.visibleItems += 12;
            this.applyFilters();
            
            // ✅ FIXED: Hide loading after operation
            this.hideAllSpinnersImmediately();
            
            this.isLoading = false;
            
            // Smooth scroll to newly loaded items
            this.smoothScrollToNewItems();
        }, 800);
    }
    
    smoothScrollToNewItems() {
        const items = this.currentTab === 'photos' ? this.mediaItems : this.videoItems;
        const newItems = Array.from(items)
            .filter(item => item && item.style.display === 'block')
            .slice(-12);
            
        if (newItems.length > 0) {
            newItems[0].scrollIntoView({ 
                behavior: 'smooth', 
                block: 'center' 
            });
        }
    }
    
    showLoading() {
        // ✅ FIXED: Only show loading if not already shown
        if (this.spinnerGuard) return;
        
        if (this.elements.loadingIndicator) {
            this.elements.loadingIndicator.style.display = 'block';
            this.elements.loadingIndicator.classList.remove('hidden');
        }
        if (this.elements.loadMoreBtn) {
            this.elements.loadMoreBtn.disabled = true;
            this.elements.loadMoreBtn.textContent = 'लोड हुँदैछ...';
        }
    }
    
    hideLoading() {
        // ✅ FIXED: Use the new spinner hiding method
        this.hideAllSpinnersImmediately();
    }
    
    updateLoadMoreButton() {
        if (!this.elements.loadMoreBtn) return;
        
        const items = this.currentTab === 'photos' ? this.mediaItems : this.videoItems;
        const visibleCount = items.filter(item => 
            item && item.style.display !== 'none'
        ).length;
        
        if (visibleCount <= this.visibleItems) {
            this.elements.loadMoreBtn.disabled = true;
            this.elements.loadMoreBtn.textContent = 'सबै हेर्नुहोस्';
        } else {
            this.elements.loadMoreBtn.disabled = false;
            this.elements.loadMoreBtn.textContent = 'थप हेर्नुहोस्';
        }
    }
    
    checkNoResults() {
        if (!this.elements.noResults) return;
        
        const items = this.currentTab === 'photos' ? this.mediaItems : this.videoItems;
        const hasVisibleItems = items.some(item => 
            item && item.style.display !== 'none'
        );
        
        if (hasVisibleItems) {
            this.elements.noResults.style.display = 'none';
        } else {
            this.elements.noResults.style.display = 'block';
        }
    }
    
    setupHdImageLoading() {
        // Add click handler for HD images
        document.addEventListener('click', (e) => {
            const hdBadge = e.target.closest('.hd-badge');
            if (hdBadge) {
                const galleryItem = hdBadge.closest('.gallery-item');
                if (galleryItem) {
                    this.loadHdImageForModal(galleryItem);
                }
            }
        });
    }
    
    async loadHdImageForModal(galleryItem) {
        const mediaId = galleryItem.dataset.id;
        const hdUrl = galleryItem.dataset.hdUrl;
        
        if (!mediaId || !hdUrl) return;
        
        try {
            // Show loading state
            const modalImg = this.elements.modalContent.querySelector('img');
            if (modalImg) {
                modalImg.classList.add('hd-loading');
            }
            
            // Load HD image
            const response = await fetch(`/api/gallery/${mediaId}/hd`);
            const data = await response.json();
            
            if (data.success && data.hd_url) {
                // Replace with HD image
                if (modalImg) {
                    modalImg.src = data.hd_url;
                    modalImg.classList.remove('hd-loading');
                    modalImg.classList.add('hd-loaded');
                    
                    // Add HD badge to modal
                    const existingBadge = this.elements.modalContent.querySelector('.modal-hd-badge');
                    if (!existingBadge) {
                        const hdBadge = document.createElement('div');
                        hdBadge.className = 'modal-hd-badge';
                        hdBadge.innerHTML = '<i class="fas fa-hd"></i> HD';
                        this.elements.modalContent.appendChild(hdBadge);
                    }
                }
                
                // Mark as loaded
                this.hdImagesLoaded.add(mediaId);
            }
        } catch (error) {
            console.error('Error loading HD image:', error);
            const modalImg = this.elements.modalContent.querySelector('img');
            if (modalImg) {
                modalImg.classList.remove('hd-loading');
            }
        }
    }
    
    preloadHdImage(mediaId, galleryItem) {
        const hdUrl = galleryItem.dataset.hdUrl;
        if (!hdUrl || this.hdImagesLoaded.has(mediaId)) return;
        
        // Preload in background
        const img = new Image();
        img.src = hdUrl;
        img.onload = () => {
            this.hdImagesLoaded.add(mediaId);
            galleryItem.dataset.hdLoaded = 'true';
        };
    }
    
    openModal(index, type = 'photo') {
        console.log('Opening modal for:', { index, type });
        
        let item;
        if (type === 'photo') {
            item = this.mediaItems[index];
        } else {
            item = this.videoItems[index];
        }
        
        if (!item) {
            console.error('Item not found at index:', index);
            return;
        }
        
        this.currentMediaIndex = index;
        
        // Set modal content based on item data
        const title = item.dataset.title || item.getAttribute('data-title') || '';
        const description = item.dataset.description || item.getAttribute('data-description') || '';
        const category = item.dataset.category || item.getAttribute('data-category') || '';
        const date = item.dataset.date || item.getAttribute('data-date') || '';
        const hostel = item.dataset.hostel || item.getAttribute('data-hostel') || '';
        const roomNumber = item.dataset.roomNumber || item.getAttribute('data-room-number') || '';
        const hostelSlug = item.dataset.hostelSlug || item.getAttribute('data-hostel-slug') || '';
        const mediaType = item.dataset.mediaType || item.getAttribute('data-media-type') || 'photo';
        const youtubeEmbed = item.dataset.youtubeEmbed || item.getAttribute('data-youtube-embed') || '';
        const videoUrl = item.dataset.videoUrl || item.getAttribute('data-video-url') || '';
        const is360Video = item.dataset.is360 === 'true' || item.getAttribute('data-is-360') === 'true';
        const hdAvailable = item.dataset.hdAvailable === 'true' || item.getAttribute('data-hd-available') === 'true';
        const hdUrl = item.dataset.hdUrl || item.getAttribute('data-hd-url') || '';
        const mediaId = item.dataset.id || item.getAttribute('data-id') || '';
        
        // Set modal text content
        this.elements.modalTitle.textContent = title;
        this.elements.modalDescription.textContent = description;
        this.elements.modalCategory.textContent = category;
        this.elements.modalDate.textContent = date;
        this.elements.modalHostel.textContent = hostel;
        this.elements.modalRoom.textContent = roomNumber ? `कोठा: ${roomNumber}` : '';
        
        // Set hostel link
        if (this.elements.modalHostelLink && hostelSlug) {
            this.elements.modalHostelLink.href = `/hostels/${hostelSlug}`;
            this.elements.modalHostelLink.style.display = 'inline-flex';
        } else if (this.elements.modalHostelLink) {
            this.elements.modalHostelLink.style.display = 'none';
        }
        
        // Clear previous content
        this.elements.modalContent.innerHTML = '';
        
        // Add media based on type - ✅ FIXED: Better video handling
        if (mediaType === 'photo') {
            const img = document.createElement('img');
            const imgSrc = item.querySelector('img')?.src || '';
            img.src = imgSrc;
            img.alt = title;
            img.className = 'modal-image';
            img.style.width = '100%';
            img.style.height = 'auto';
            img.style.display = 'block';
            
            // Set HD data attributes
            if (hdAvailable && hdUrl) {
                img.dataset.hdAvailable = 'true';
                img.dataset.hdUrl = hdUrl;
                img.dataset.mediaId = mediaId;
            }
            
            this.elements.modalContent.appendChild(img);
            
            // Add HD badge if available
            if (hdAvailable) {
                const hdBadge = document.createElement('div');
                hdBadge.className = 'modal-hd-badge';
                hdBadge.innerHTML = '<i class="fas fa-hd"></i> HD';
                hdBadge.style.position = 'absolute';
                hdBadge.style.top = '10px';
                hdBadge.style.right = '10px';
                hdBadge.style.background = 'rgba(0,0,0,0.7)';
                hdBadge.style.color = '#10b981';
                hdBadge.style.padding = '0.5rem 0.8rem';
                hdBadge.style.borderRadius = '4px';
                hdBadge.style.fontSize = '0.9rem';
                hdBadge.style.fontWeight = '600';
                hdBadge.style.zIndex = '10';
                hdBadge.style.cursor = 'pointer';
                hdBadge.title = 'HD संस्करण लोड गर्नुहोस्';
                hdBadge.addEventListener('click', () => this.loadHdImageForModal(item));
                this.elements.modalContent.appendChild(hdBadge);
            }
            
        } else if (mediaType === 'external_video' && youtubeEmbed) {
            const iframe = document.createElement('iframe');
            iframe.src = youtubeEmbed + (youtubeEmbed.includes('?') ? '&' : '?') + 
                        'autoplay=1&rel=0&controls=1&showinfo=0';
            iframe.width = '100%';
            iframe.height = '500';
            iframe.allowFullscreen = true;
            iframe.style.border = 'none';
            iframe.className = 'modal-video';
            iframe.allow = 'accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture';
            
            this.elements.modalContent.appendChild(iframe);
            
            // Add 360 badge if applicable
            if (is360Video) {
                const badge = document.createElement('div');
                badge.className = 'modal-360-badge';
                badge.innerHTML = '<i class="fas fa-360-degrees"></i> 360° VIEW';
                badge.style.position = 'absolute';
                badge.style.top = '10px';
                badge.style.left = '10px';
                badge.style.background = 'rgba(14, 165, 233, 0.9)';
                badge.style.color = 'white';
                badge.style.padding = '0.5rem 0.8rem';
                badge.style.borderRadius = '4px';
                badge.style.fontSize = '0.9rem';
                badge.style.fontWeight = '600';
                badge.style.zIndex = '10';
                this.elements.modalContent.appendChild(badge);
            }
            
        } else if (mediaType === 'local_video' && videoUrl) {
            const videoContainer = document.createElement('div');
            videoContainer.className = 'modal-local-video';
            videoContainer.style.position = 'relative';
            videoContainer.style.width = '100%';
            videoContainer.style.maxHeight = '70vh';
            videoContainer.style.overflow = 'hidden';
            
            const video = document.createElement('video');
            video.src = videoUrl;
            video.controls = true;
            video.autoplay = true;
            video.style.width = '100%';
            video.style.height = 'auto';
            video.style.maxHeight = '70vh';
            video.style.display = 'block';
            
            videoContainer.appendChild(video);
            this.elements.modalContent.appendChild(videoContainer);
        } else {
            // Fallback for unknown media types
            const img = document.createElement('img');
            img.src = item.querySelector('img')?.src || '';
            img.alt = title;
            img.style.width = '100%';
            img.style.height = 'auto';
            img.style.display = 'block';
            this.elements.modalContent.appendChild(img);
        }
        
        // Show modal with animation
        this.elements.galleryModal.classList.add('active');
        document.body.style.overflow = 'hidden';
        
        // Add fade-in animation
        this.elements.modalContent.style.animation = 'modalAppear 0.3s ease-out';
        
        // Update current items array for navigation
        this.updateCurrentItemsForNavigation();
    }
    
    updateCurrentItemsForNavigation() {
        if (this.currentTab === 'photos') {
            this.currentItems = Array.from(this.mediaItems).filter(item => 
                item && item.style.display !== 'none'
            );
        } else if (this.currentTab === 'videos' || this.currentTab === 'virtual-tours') {
            this.currentItems = Array.from(this.videoItems).filter(item => 
                item && item.style.display !== 'none'
            );
        }
    }
    
    closeModal() {
        this.elements.galleryModal.classList.remove('active');
        document.body.style.overflow = 'auto';
        
        // Stop video playback
        const iframe = this.elements.modalContent.querySelector('iframe');
        if (iframe) {
            iframe.src = '';
        }
        
        const video = this.elements.modalContent.querySelector('video');
        if (video) {
            video.pause();
        }
        
        // Clear modal content
        this.elements.modalContent.innerHTML = '';
    }
    
    navigateModal(direction) {
        this.updateCurrentItemsForNavigation();
        
        if (!this.currentItems || this.currentItems.length === 0) return;
        
        let newIndex = this.currentMediaIndex + direction;
        
        // Find the current item in filtered items
        const currentItem = this.currentTab === 'photos' ? 
            this.mediaItems[this.currentMediaIndex] : 
            this.videoItems[this.currentMediaIndex];
        
        const currentVisibleIndex = this.currentItems.findIndex(item => 
            item === currentItem
        );
        
        if (currentVisibleIndex === -1) return;
        
        newIndex = currentVisibleIndex + direction;
        
        // Wrap around
        if (newIndex < 0) {
            newIndex = this.currentItems.length - 1;
        } else if (newIndex >= this.currentItems.length) {
            newIndex = 0;
        }
        
        const nextItem = this.currentItems[newIndex];
        
        // Find the original index
        let originalIndex;
        if (this.currentTab === 'photos') {
            originalIndex = this.mediaItems.findIndex(item => item === nextItem);
        } else {
            originalIndex = this.videoItems.findIndex(item => item === nextItem);
        }
        
        if (originalIndex === -1) return;
        
        this.closeModal();
        setTimeout(() => {
            this.openModal(originalIndex, this.currentTab === 'photos' ? 'photo' : 'video');
        }, 50);
    }
    
    handleKeyboard(e) {
        if (!this.elements.galleryModal || !this.elements.galleryModal.classList.contains('active')) return;
        
        switch(e.key) {
            case 'Escape':
                this.closeModal();
                break;
            case 'ArrowLeft':
                this.navigateModal(-1);
                break;
            case 'ArrowRight':
                this.navigateModal(1);
                break;
            case 'h':
            case 'H':
                // Load HD version on 'H' key press
                const modalImg = this.elements.modalContent.querySelector('img[data-hd-available="true"]');
                if (modalImg) {
                    this.loadHdImageForModal(modalImg.closest('.gallery-item') || { dataset: { id: modalImg.dataset.mediaId } });
                }
                break;
        }
    }
}

// ✅ FIXED: Initialize gallery when DOM is loaded with better videos placeholder handling
document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM loaded, initializing Enhanced GalleryManager');
    
    // Check URL parameters for tab
    const urlParams = new URLSearchParams(window.location.search);
    const tabParam = urlParams.get('tab');
    
    // ✅ FIXED: Hide all spinners immediately
    const hideSpinnersImmediately = () => {
        const spinners = document.querySelectorAll('.spinner, .loading-spinner, .gallery-loading, .videos-loading');
        spinners.forEach(spinner => {
            spinner.style.display = 'none';
            spinner.classList.add('hidden');
        });
        
        // Hide videos placeholder if videos exist
        const videoCards = document.querySelectorAll('.video-card');
        const placeholder = document.querySelector('.videos-placeholder');
        if (videoCards.length > 0 && placeholder) {
            placeholder.style.display = 'none';
            placeholder.classList.add('hidden');
        }
    };
    
    // Initial hide
    hideSpinnersImmediately();
    
    // Initialize gallery manager
    const galleryManager = new GalleryManager();
    
    // If tab parameter exists and we're not already on that tab, switch to it
    if (tabParam && document.querySelector(`.tab-btn[href*="${tabParam}"]`)) {
        const tabBtn = document.querySelector(`.tab-btn[href*="${tabParam}"]`);
        if (!tabBtn.classList.contains('active')) {
            // If it's photos and we're on a different tab, switch client-side
            if (tabParam === 'photos' && galleryManager.currentTab !== 'photos') {
                setTimeout(() => {
                    tabBtn.click();
                }, 100);
            }
        }
    }
    
    // ✅ FIXED: Additional check to hide videos placeholder with timeout cleanup
    setTimeout(() => {
        hideSpinnersImmediately();
        galleryManager.hideAllSpinnersImmediately();
        galleryManager.hideVideosPlaceholder();
        
        if (tabParam === 'videos' || tabParam === 'virtual-tours') {
            console.log('Checking videos placeholder after timeout...');
            galleryManager.hideVideosPlaceholder();
        }
    }, 300);
    
    // ✅ FIXED: Also hide on window load with proper cleanup
    window.addEventListener('load', () => {
        console.log('Window loaded, final spinner cleanup...');
        
        setTimeout(() => {
            hideSpinnersImmediately();
            galleryManager.hideAllSpinnersImmediately();
            galleryManager.hideVideosPlaceholder();
            
            // Extra check for videos tab
            if (tabParam === 'videos' || tabParam === 'virtual-tours') {
                setTimeout(() => {
                    galleryManager.hideAllSpinnersImmediately();
                    galleryManager.hideVideosPlaceholder();
                }, 500);
            }
        }, 500);
    });
    
    // ✅ FIXED: Extra safety checks for videos placeholder
    setTimeout(() => {
        hideSpinnersImmediately();
        galleryManager.hideAllSpinnersImmediately();
        
        if (tabParam === 'videos' || tabParam === 'virtual-tours') {
            console.log('Final check for videos placeholder...');
            galleryManager.hideVideosPlaceholder();
            
            // Force check by querying all video cards
            const videoCards = document.querySelectorAll('.video-card');
            const placeholder = document.querySelector('.videos-placeholder');
            
            if (videoCards.length > 0 && placeholder) {
                console.log('Force hiding videos placeholder');
                placeholder.style.display = 'none';
                placeholder.classList.add('hidden');
                placeholder.style.visibility = 'hidden';
                placeholder.style.opacity = '0';
                placeholder.style.height = '0';
            }
        }
    }, 1000);
});

// Add CSS animation for modal and gallery items with enhanced placeholder fixes
const style = document.createElement('style');
style.textContent = `
    @keyframes modalAppear {
        from {
            opacity: 0;
            transform: scale(0.8) translateY(20px);
        }
        to {
            opacity: 1;
            transform: scale(1) translateY(0);
        }
    }
    
    .gallery-item, .video-card {
        animation: itemAppear 0.6s ease-out both;
    }
    
    @keyframes itemAppear {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    .modal-image {
        max-width: 100%;
        max-height: 80vh;
        object-fit: contain;
        transition: all 0.3s ease;
    }
    
    .modal-image.hd-loaded {
        animation: hdLoad 0.5s ease-out;
    }
    
    @keyframes hdLoad {
        0% { opacity: 0.8; transform: scale(1); }
        50% { opacity: 0.9; transform: scale(1.01); }
        100% { opacity: 1; transform: scale(1); }
    }
    
    .modal-image.hd-loading {
        opacity: 0.7;
        filter: blur(2px);
    }
    
    .modal-video, .modal-local-video video {
        width: 100%;
        height: 500px;
        max-height: 80vh;
    }
    
    .modal-hd-badge {
        position: absolute;
        top: 10px;
        right: 10px;
        background: rgba(0, 0, 0, 0.7);
        color: #10b981;
        padding: 0.5rem 0.8rem;
        border-radius: 4px;
        font-size: 0.9rem;
        font-weight: 600;
        z-index: 10;
        display: flex;
        align-items: center;
        gap: 0.3rem;
        cursor: pointer;
        transition: all 0.3s ease;
    }
    
    .modal-hd-badge:hover {
        background: rgba(0, 0, 0, 0.9);
        transform: scale(1.05);
    }
    
    .modal-360-badge {
        position: absolute;
        top: 10px;
        left: 10px;
        background: rgba(14, 165, 233, 0.9);
        color: white;
        padding: 0.5rem 0.8rem;
        border-radius: 4px;
        font-size: 0.9rem;
        font-weight: 600;
        z-index: 10;
        display: flex;
        align-items: center;
        gap: 0.3rem;
    }
    
    .modal-actions {
        margin-top: 1rem;
        padding-top: 1rem;
        border-top: 1px solid rgba(255, 255, 255, 0.2);
    }
    
    .modal-hostel-link {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        color: white;
        background: rgba(30, 58, 138, 0.8);
        padding: 0.6rem 1.2rem;
        border-radius: 4px;
        text-decoration: none;
        font-weight: 600;
        transition: all 0.3s ease;
    }
    
    .modal-hostel-link:hover {
        background: rgba(30, 58, 138, 1);
        transform: translateY(-2px);
        color: white;
    }
    
    @media (max-width: 768px) {
        .modal-video,
        .modal-local-video video {
            height: 300px;
        }
        
        .gallery-item, .video-card {
            animation-delay: 0s !important;
        }
    }
    
    @media (max-width: 480px) {
        .modal-video,
        .modal-local-video video {
            height: 250px;
        }
    }
    
    /* Video play button animation */
    @keyframes pulse {
        0% { transform: translate(-50%, -50%) scale(1); }
        50% { transform: translate(-50%, -50%) scale(1.1); }
        100% { transform: translate(-50%, -50%) scale(1); }
    }
    
    .video-card:hover .play-button {
        animation: pulse 1.5s infinite;
    }
    
    /* Tab content transitions */
    .tab-content {
        animation: fadeIn 0.3s ease-out;
    }
    
    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    /* Loading spinner */
    .videos-loading .spinner {
        border: 4px solid #f3f3f3;
        border-top: 4px solid var(--gallery-primary);
        border-radius: 50%;
        width: 40px;
        height: 40px;
        animation: spin 1s linear infinite;
        margin: 0 auto 1rem;
    }
    
    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }
    
    /* HD badge animation */
    .hd-badge {
        transition: all 0.3s ease;
    }
    
    .gallery-item:hover .hd-badge {
        background: rgba(0, 0, 0, 0.9);
        color: #34d399;
    }
    
    /* Virtual tour indicator */
    .virtual-tour-indicator {
        position: absolute;
        top: 50px;
        left: 10px;
        background: rgba(139, 92, 246, 0.9);
        color: white;
        padding: 0.3rem 0.6rem;
        border-radius: 4px;
        font-size: 0.7rem;
        font-weight: 600;
        z-index: 2;
        display: flex;
        align-items: center;
        gap: 0.3rem;
    }
    
    /* Responsive adjustments */
    @media (max-width: 768px) {
        .video-categories {
            justify-content: center;
            flex-wrap: wrap;
        }
        
        .video-category-btn {
            flex: 1;
            min-width: 120px;
            text-align: center;
        }
    }
    
    /* Debug console styles */
    .debug-console {
        position: fixed;
        bottom: 10px;
        right: 10px;
        background: rgba(0,0,0,0.9);
        color: #10b981;
        padding: 10px;
        border-radius: 5px;
        font-family: monospace;
        font-size: 12px;
        max-width: 300px;
        max-height: 200px;
        overflow-y: auto;
        z-index: 9999;
        display: none;
    }
    
    /* ✅ FIXED: Better video modal styling */
    .modal-video-container {
        position: relative;
        width: 100%;
        height: 500px;
        max-height: 70vh;
        overflow: hidden;
    }
    
    .modal-video-container iframe {
        width: 100%;
        height: 100%;
        border: none;
    }
    
    /* ✅ FIXED: Better hostel filter styling */
    .hostel-filter select option[data-gender="boys"] {
        color: #3b82f6;
    }
    
    .hostel-filter select option[data-gender="girls"] {
        color: #ec4899;
    }
    
    /* ✅ FIXED: Enhanced styles for videos placeholder fix */
    .videos-placeholder {
        transition: opacity 0.3s ease, height 0.3s ease, padding 0.3s ease, margin 0.3s ease;
        grid-column: 1 / -1;
        text-align: center;
        padding: 3rem;
        display: block;
        visibility: visible;
        opacity: 1;
        height: auto;
    }
    
    .videos-placeholder.hidden {
        display: none !important;
        visibility: hidden !important;
        opacity: 0 !important;
        height: 0 !important;
        padding: 0 !important;
        margin: 0 !important;
        overflow: hidden !important;
        pointer-events: none !important;
    }
    
    /* ✅ CRITICAL: Force hide all spinners by default */
    .gallery-loading.hidden,
    .videos-loading.hidden,
    .videos-placeholder.hidden .spinner,
    .loading-spinner.hidden {
        display: none !important;
        visibility: hidden !important;
        opacity: 0 !important;
    }
    
    /* Ensure videos grid shows properly */
    .videos-grid {
        position: relative;
        min-height: 200px;
    }
    
    .videos-loading {
        transition: opacity 0.3s ease;
    }
    
    .videos-loading.hidden {
        display: none !important;
        visibility: hidden !important;
        opacity: 0 !important;
        height: 0 !important;
        padding: 0 !important;
        margin: 0 !important;
        overflow: hidden !important;
    }
    
    /* ✅ FIXED: Prevent flash of placeholder */
    .tab-content:not(.active) .videos-placeholder {
        display: none !important;
    }
    
    /* ✅ FIXED: Make sure video cards are visible */
    .video-card {
        display: block !important;
        visibility: visible !important;
        opacity: 1 !important;
    }
    
    /* ✅ FIXED: Smooth transition for video grid */
    .videos-grid {
        transition: min-height 0.3s ease;
    }
    
    /* ✅ FIXED: Force videos placeholder to be hidden when not needed */
    [data-tab="videos"] .videos-placeholder.hidden,
    [data-tab="virtual-tours"] .videos-placeholder.hidden {
        display: none !important;
    }
    
    /* ✅ FIXED: Hidden class for all spinners */
    .hidden {
        display: none !important;
        visibility: hidden !important;
        opacity: 0 !important;
        height: 0 !important;
        padding: 0 !important;
        margin: 0 !important;
        overflow: hidden !important;
        pointer-events: none !important;
    }
`;
document.head.appendChild(style);