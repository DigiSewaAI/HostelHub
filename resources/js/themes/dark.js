// public/js/themes/dark.js - COMPLETE CYBERPUNK DARK THEME

(function() {
    'use strict';
    
    // Matrix Effect Enhancement
    function initMatrixEffect() {
        const matrixCode = document.querySelector('.matrix-code');
        if (matrixCode) {
            let hue = 0;
            
            setInterval(() => {
                hue = (hue + 1) % 360;
                
                matrixCode.style.background = 
                    `repeating-linear-gradient(0deg, transparent, transparent 2px, hsla(${hue}, 100%, 50%, 0.03) 2px, hsla(${hue}, 100%, 50%, 0.03) 4px),
                     repeating-linear-gradient(90deg, transparent, transparent 2px, hsla(${(hue + 120) % 360}, 100%, 50%, 0.03) 2px, hsla(${(hue + 120) % 360}, 100%, 50%, 0.03) 4px)`;
            }, 100);
        }
    }
    
    // Reviews Carousel
    function initReviewsCarousel() {
        const track = document.getElementById('cyberReviewsTrack');
        if (!track) return;
        
        const slides = document.querySelectorAll('.cyber-review');
        if (slides.length <= 1) return;
        
        let currentSlide = 0;
        const totalSlides = slides.length;
        
        function updateCarousel() {
            track.style.transform = `translateX(-${currentSlide * 100}%)`;
        }
        
        // Auto slide every 6 seconds
        const slideInterval = setInterval(() => {
            currentSlide = (currentSlide + 1) % totalSlides;
            updateCarousel();
        }, 6000);
        
        // Button controls
        const prevBtn = document.querySelector('.prev-cyber-review');
        const nextBtn = document.querySelector('.next-cyber-review');
        
        if (nextBtn) {
            nextBtn.addEventListener('click', () => {
                currentSlide = (currentSlide + 1) % totalSlides;
                updateCarousel();
                clearInterval(slideInterval);
                setTimeout(() => initReviewsCarousel(), 6000);
            });
        }
        
        if (prevBtn) {
            prevBtn.addEventListener('click', () => {
                currentSlide = (currentSlide - 1 + totalSlides) % totalSlides;
                updateCarousel();
                clearInterval(slideInterval);
                setTimeout(() => initReviewsCarousel(), 6000);
            });
        }
    }
    
    // Cyber Gallery Modal
    function initGalleryModal() {
        // Create modal element
        const modalHTML = `
            <div class="cyber-modal" id="cyberModal">
                <div class="cyber-modal-content">
                    <button class="cyber-modal-close" aria-label="Close modal">&times;</button>
                    <div id="cyberModalImage"></div>
                    <div class="cyber-modal-info" style="padding: 1.5rem; background: rgba(0,0,0,0.8);">
                        <h3 id="cyberModalTitle" style="color: var(--neon-cyan); margin-bottom: 0.5rem;"></h3>
                        <p id="cyberModalDescription" style="color: var(--text-secondary);"></p>
                    </div>
                </div>
            </div>
        `;
        
        // Add modal to body
        document.body.insertAdjacentHTML('beforeend', modalHTML);
        
        const modal = document.getElementById('cyberModal');
        const modalImage = document.getElementById('cyberModalImage');
        const modalTitle = document.getElementById('cyberModalTitle');
        const modalDescription = document.getElementById('cyberModalDescription');
        const modalClose = document.querySelector('.cyber-modal-close');
        
        // Open modal function
        window.openCyberModal = function(imgElement) {
            const img = imgElement.querySelector('img');
            const title = imgElement.querySelector('h4')?.textContent || 'Image';
            const description = imgElement.querySelector('p')?.textContent || '';
            
            modalImage.innerHTML = `<img src="${img.src}" alt="${title}" style="max-width: 100%; max-height: 70vh; object-fit: contain; display: block; margin: 0 auto;">`;
            modalTitle.textContent = title;
            modalDescription.textContent = description;
            
            modal.style.display = 'flex';
            document.body.style.overflow = 'hidden';
        };
        
        // Close modal
        if (modalClose) {
            modalClose.addEventListener('click', () => {
                modal.style.display = 'none';
                document.body.style.overflow = 'auto';
            });
        }
        
        // Close modal on background click
        modal.addEventListener('click', (e) => {
            if (e.target === modal) {
                modal.style.display = 'none';
                document.body.style.overflow = 'auto';
            }
        });
        
        // Close modal with Escape key
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && modal.style.display === 'flex') {
                modal.style.display = 'none';
                document.body.style.overflow = 'auto';
            }
        });
    }
    
    // Image Lazy Loading for Dark Theme
    function initLazyLoading() {
        if ('IntersectionObserver' in window) {
            const imageObserver = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        const img = entry.target;
                        if (img.dataset.src) {
                            img.src = img.dataset.src;
                        }
                        if (img.dataset.srcset) {
                            img.srcset = img.dataset.srcset;
                        }
                        
                        // Add cyber loading effect
                        img.style.opacity = '0';
                        setTimeout(() => {
                            img.style.opacity = '1';
                            img.style.transition = 'opacity 0.5s ease';
                        }, 100);
                        
                        imageObserver.unobserve(img);
                    }
                });
            }, {
                rootMargin: '50px 0px',
                threshold: 0.1
            });
            
            // Observe all gallery images
            document.querySelectorAll('.cyber-gallery-item img').forEach(img => {
                if (!img.dataset.src && img.src) {
                    img.dataset.src = img.src;
                    img.src = 'data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMzAwIiBoZWlnaHQ9IjMwMCIgdmlld0JveD0iMCAwIDMwMCAzMDAiPjxyZWN0IHdpZHRoPSIxMDAlIiBoZWlnaHQ9IjEwMCUiIGZpbGw9IiMwQTBBNTAiLz48L3N2Zz4=';
                }
                imageObserver.observe(img);
            });
        }
    }
    
    // Fix broken images
    function fixBrokenImages() {
        document.querySelectorAll('.cyber-gallery-item img').forEach(img => {
            if (img.src.includes('undefined') || img.src.includes('null') || !img.src || img.src.includes('base64')) {
                return;
            }
            
            img.addEventListener('error', function() {
                this.src = '/images/default-room.png';
                this.style.opacity = '0.7';
                this.style.filter = 'grayscale(50%)';
            });
        });
    }
    
    // Add click handlers to gallery items
    function initGalleryClickHandlers() {
        document.querySelectorAll('.cyber-gallery-item').forEach(item => {
            item.style.cursor = 'pointer';
            item.setAttribute('role', 'button');
            item.setAttribute('tabindex', '0');
            item.setAttribute('aria-label', 'View image details');
            
            item.addEventListener('click', function() {
                if (typeof window.openCyberModal === 'function') {
                    window.openCyberModal(this);
                }
            });
            
            item.addEventListener('keydown', function(e) {
                if (e.key === 'Enter' || e.key === ' ') {
                    e.preventDefault();
                    if (typeof window.openCyberModal === 'function') {
                        window.openCyberModal(this);
                    }
                }
            });
        });
    }
    
    // Stats counter animation
    function initStatsCounter() {
        const stats = document.querySelectorAll('.cyber-number');
        stats.forEach((stat) => {
            const finalValue = parseInt(stat.textContent) || 0;
            if (finalValue > 0) {
                let currentValue = 0;
                const increment = finalValue / 30;
                const timer = setInterval(() => {
                    currentValue += increment;
                    if (currentValue >= finalValue) {
                        currentValue = finalValue;
                        clearInterval(timer);
                    }
                    stat.textContent = Math.floor(currentValue);
                }, 50);
            }
        });
    }
    
    // Add cyberpunk hover effects
    function initHoverEffects() {
        // Add scanline effect to buttons on hover
        document.querySelectorAll('.cyber-btn').forEach(btn => {
            btn.addEventListener('mouseenter', function() {
                this.style.boxShadow = '0 0 30px rgba(0, 212, 255, 0.7)';
            });
            
            btn.addEventListener('mouseleave', function() {
                this.style.boxShadow = '';
            });
        });
    }
    
    // Initialize everything when DOM is ready
    document.addEventListener('DOMContentLoaded', function() {
        console.log('🌌 Cyber Dark Theme Initialized');
        
        // Initialize all features
        initMatrixEffect();
        initReviewsCarousel();
        initGalleryModal();
        initLazyLoading();
        fixBrokenImages();
        initGalleryClickHandlers();
        initStatsCounter();
        initHoverEffects();
        
        // Add loading state to images
        document.querySelectorAll('img').forEach(img => {
            if (!img.complete) {
                img.classList.add('img-loading');
                img.addEventListener('load', function() {
                    this.classList.remove('img-loading');
                });
            }
        });
    });
    
    // Export for debugging
    window.CyberDarkTheme = {
        initMatrixEffect,
        initReviewsCarousel,
        initGalleryModal,
        initLazyLoading,
        fixBrokenImages,
        initGalleryClickHandlers,
        initStatsCounter,
        initHoverEffects
    };
    
    // Error handling
    window.addEventListener('error', function(e) {
        console.error('Cyber Dark Theme Error:', e.error);
    });
})();