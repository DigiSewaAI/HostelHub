// Advanced Gallery Functionality
class GalleryManager {
    constructor() {
        this.currentFilter = 'all';
        this.currentSearch = '';
        this.currentHostelFilter = '';
        this.visibleItems = 12;
        this.currentMediaIndex = 0;
        this.mediaItems = [];
        this.isLoading = false;
        
        this.init();
    }
    
    init() {
        console.log('GalleryManager initialized');
        this.cacheElements();
        this.bindEvents();
        this.initMediaItems();
        this.applyFilters();
    }
    
    cacheElements() {
        this.elements = {
            filterButtons: document.querySelectorAll('.filter-btn'),
            galleryItems: document.querySelectorAll('.gallery-item'),
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
            galleryGrid: document.querySelector('.gallery-grid'),
            loadingIndicator: document.querySelector('.gallery-loading'),
            noResults: document.querySelector('.no-results')
        };
    }
    
    bindEvents() {
        // Filter buttons
        this.elements.filterButtons.forEach(btn => {
            btn.addEventListener('click', (e) => this.handleFilter(e));
        });
        
        // Search input
        if (this.elements.searchInput) {
            this.elements.searchInput.addEventListener('input', (e) => this.handleSearch(e));
        }
        
        // Hostel filter
        if (this.elements.hostelFilter) {
            this.elements.hostelFilter.addEventListener('change', (e) => this.handleHostelFilter(e));
        }
        
        // Load more
        if (this.elements.loadMoreBtn) {
            this.elements.loadMoreBtn.addEventListener('click', () => this.loadMoreItems());
        }
        
        // Modal events
        if (this.elements.modalClose) {
            this.elements.modalClose.addEventListener('click', () => this.closeModal());
        }
        
        if (this.elements.modalPrev) {
            this.elements.modalPrev.addEventListener('click', () => this.navigateModal(-1));
        }
        
        if (this.elements.modalNext) {
            this.elements.modalNext.addEventListener('click', () => this.navigateModal(1));
        }
        
        // Gallery item clicks
        this.elements.galleryItems.forEach((item, index) => {
            item.addEventListener('click', () => this.openModal(index));
        });
        
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
    }
    
    initMediaItems() {
        this.mediaItems = Array.from(this.elements.galleryItems);
        console.log(`Initialized ${this.mediaItems.length} media items`);
    }
    
    handleFilter(e) {
        const button = e.currentTarget;
        const filter = button.dataset.filter;
        
        // Update active button
        this.elements.filterButtons.forEach(btn => btn.classList.remove('active'));
        button.classList.add('active');
        
        this.currentFilter = filter;
        this.visibleItems = 12; // Reset visible items
        this.applyFilters();
    }
    
    handleSearch(e) {
        this.currentSearch = e.target.value.toLowerCase().trim();
        this.visibleItems = 12; // Reset visible items
        this.applyFilters();
    }
    
    handleHostelFilter(e) {
        this.currentHostelFilter = e.target.value;
        this.visibleItems = 12; // Reset visible items
        this.applyFilters();
    }
    
    applyFilters() {
        let visibleCount = 0;
        
        this.mediaItems.forEach((item, index) => {
            const matchesFilter = this.currentFilter === 'all' || item.dataset.category === this.currentFilter;
            const matchesSearch = this.currentSearch === '' || 
                                item.dataset.title.toLowerCase().includes(this.currentSearch) ||
                                (item.dataset.description && item.dataset.description.toLowerCase().includes(this.currentSearch)) ||
                                (item.dataset.hostel && item.dataset.hostel.toLowerCase().includes(this.currentSearch)) ||
                                (item.dataset.roomNumber && item.dataset.roomNumber.toLowerCase().includes(this.currentSearch));
            
            const matchesHostel = this.currentHostelFilter === '' || item.dataset.hostelId === this.currentHostelFilter;
            
            if (matchesFilter && matchesSearch && matchesHostel) {
                item.style.display = 'block';
                visibleCount++;
                
                // Show/hide based on visible items limit
                if (visibleCount <= this.visibleItems) {
                    item.style.opacity = '1';
                    item.style.transform = 'scale(1)';
                    item.style.animationDelay = `${(index % 12) * 0.1}s`;
                } else {
                    item.style.opacity = '0';
                    item.style.transform = 'scale(0.8)';
                }
            } else {
                item.style.display = 'none';
            }
        });
        
        this.updateLoadMoreButton();
        this.checkNoResults();
    }
    
    showVisibleItems() {
        let shown = 0;
        this.mediaItems.forEach(item => {
            if (item.style.display !== 'none') {
                if (shown < this.visibleItems) {
                    item.style.display = 'block';
                    // Add animation delay for staggered effect
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
            this.hideLoading();
            this.isLoading = false;
            
            // Smooth scroll to newly loaded items
            this.smoothScrollToNewItems();
        }, 800);
    }
    
    smoothScrollToNewItems() {
        const newItems = Array.from(this.elements.galleryItems)
            .filter(item => item.style.display === 'block')
            .slice(-12);
            
        if (newItems.length > 0) {
            newItems[0].scrollIntoView({ 
                behavior: 'smooth', 
                block: 'center' 
            });
        }
    }
    
    showLoading() {
        if (this.elements.loadingIndicator) {
            this.elements.loadingIndicator.style.display = 'block';
        }
        if (this.elements.loadMoreBtn) {
            this.elements.loadMoreBtn.disabled = true;
            this.elements.loadMoreBtn.textContent = 'लोड हुँदैछ...';
        }
    }
    
    hideLoading() {
        if (this.elements.loadingIndicator) {
            this.elements.loadingIndicator.style.display = 'none';
        }
        this.updateLoadMoreButton();
    }
    
    updateLoadMoreButton() {
        if (!this.elements.loadMoreBtn) return;
        
        const visibleCount = this.mediaItems.filter(item => 
            item.style.display !== 'none'
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
        
        const hasVisibleItems = this.mediaItems.some(item => 
            item.style.display !== 'none'
        );
        
        if (hasVisibleItems) {
            this.elements.noResults.style.display = 'none';
        } else {
            this.elements.noResults.style.display = 'block';
        }
    }
    
    openModal(index) {
        this.currentMediaIndex = index;
        const media = this.mediaItems[index];
        
        // Set modal content
        this.elements.modalTitle.textContent = media.dataset.title || '';
        this.elements.modalDescription.textContent = media.dataset.description || '';
        this.elements.modalCategory.textContent = media.dataset.category || '';
        this.elements.modalDate.textContent = media.dataset.date || '';
        this.elements.modalHostel.textContent = media.dataset.hostel || '';
        
        const roomNumber = media.dataset.roomNumber;
        this.elements.modalRoom.textContent = roomNumber ? `कोठा: ${roomNumber}` : '';
        
        // Clear previous content
        this.elements.modalContent.innerHTML = '';
        
        const mediaType = media.dataset.mediaType;
        const youtubeEmbed = media.dataset.youtubeEmbed;
        
        // Add media based on type
        if (mediaType === 'photo') {
            const img = document.createElement('img');
            img.src = media.querySelector('img').src;
            img.alt = media.dataset.title || 'Gallery Image';
            img.style.width = '100%';
            img.style.height = 'auto';
            img.style.display = 'block';
            img.className = 'modal-image';
            this.elements.modalContent.appendChild(img);
        } else if ((mediaType === 'external_video' || mediaType === 'local_video') && youtubeEmbed) {
            const iframe = document.createElement('iframe');
            iframe.src = youtubeEmbed + (youtubeEmbed.includes('?') ? '&' : '?') + 'autoplay=1&rel=0';
            iframe.width = '100%';
            iframe.height = '400';
            iframe.allowFullscreen = true;
            iframe.style.border = 'none';
            iframe.className = 'modal-video';
            iframe.allow = 'accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture';
            this.elements.modalContent.appendChild(iframe);
        } else if (mediaType === 'local_video') {
            const video = document.createElement('video');
            video.src = media.querySelector('source')?.src || '#';
            video.controls = true;
            video.style.width = '100%';
            video.style.height = 'auto';
            video.className = 'modal-video';
            this.elements.modalContent.appendChild(video);
        } else {
            // Fallback for unknown media types
            const img = document.createElement('img');
            img.src = media.querySelector('img')?.src || '';
            img.alt = media.dataset.title || 'Gallery Image';
            img.style.width = '100%';
            img.style.height = 'auto';
            img.style.display = 'block';
            this.elements.modalContent.appendChild(img);
        }
        
        this.elements.galleryModal.classList.add('active');
        document.body.style.overflow = 'hidden';
        
        // Add animation
        this.elements.modalContent.style.animation = 'modalAppear 0.3s ease-out';
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
    }
    
    navigateModal(direction) {
        // Get currently visible items
        const visibleItems = this.mediaItems.filter(item => 
            item.style.display !== 'none'
        );
        
        if (visibleItems.length === 0) return;
        
        // Find current index in visible items
        const currentVisibleIndex = visibleItems.findIndex(item => 
            item === this.mediaItems[this.currentMediaIndex]
        );
        
        let newVisibleIndex = currentVisibleIndex + direction;
        
        // Wrap around
        if (newVisibleIndex < 0) {
            newVisibleIndex = visibleItems.length - 1;
        } else if (newVisibleIndex >= visibleItems.length) {
            newVisibleIndex = 0;
        }
        
        const newIndex = this.mediaItems.indexOf(visibleItems[newVisibleIndex]);
        
        this.closeModal();
        setTimeout(() => this.openModal(newIndex), 50);
    }
    
    handleKeyboard(e) {
        if (!this.elements.galleryModal.classList.contains('active')) return;
        
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
        }
    }
}

// Initialize gallery when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM loaded, initializing GalleryManager');
    new GalleryManager();
});

// Add CSS animation for modal and gallery items
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
    
    .gallery-item {
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
    }
    
    .modal-video {
        width: 100%;
        height: 400px;
        max-height: 80vh;
    }
    
    @media (max-width: 768px) {
        .modal-video {
            height: 300px;
        }
    }
    
    @media (max-width: 480px) {
        .modal-video {
            height: 250px;
        }
    }
`;
document.head.appendChild(style);