/* Classic Theme JavaScript */

(function() {
    'use strict';
    
    // Classical Theme Initialization
    console.log('🏛️ Classic Theme Initialized');
    
    // Fix broken images immediately
    function fixBrokenImages() {
        const images = document.querySelectorAll('.classic-gallery-item img, .classic-logo-main img');
        
        images.forEach(img => {
            // Check for broken images
            if (img.complete && img.naturalHeight === 0) {
                console.warn('Broken image detected:', img.src);
                replaceBrokenImage(img);
            }
            
            // Add error handler for future errors
            img.addEventListener('error', function() {
                replaceBrokenImage(this);
            });
        });
    }
    
    function replaceBrokenImage(img) {
        const defaultImage = '/images/default-room.png';
        
        // Don't replace if already default
        if (img.src.includes('default-room.png')) return;
        
        console.log('Replacing broken image with default');
        img.src = defaultImage;
        img.style.opacity = '0.7';
        img.style.filter = 'sepia(50%)';
        
        // Add broken image indicator
        img.setAttribute('title', 'Image could not be loaded');
        img.setAttribute('alt', 'Default hostel room image');
    }
    
    // Gallery Lightbox System
    function initClassicLightbox() {
        // Create lightbox HTML
        const lightboxHTML = `
            <div class="classic-lightbox" id="classicLightbox" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.95); z-index: 9999; align-items: center; justify-content: center; opacity: 0; transition: opacity 0.3s ease;">
                <div class="lightbox-content" style="max-width: 90vw; max-height: 90vh; position: relative; background: white; border-radius: 10px; overflow: hidden; box-shadow: 0 20px 60px rgba(0,0,0,0.5); transform: scale(0.9); transition: transform 0.3s ease;">
                    <button class="lightbox-close" aria-label="Close lightbox" style="position: absolute; top: 15px; right: 15px; background: var(--deep-red); color: white; border: 2px solid var(--gold-color); width: 40px; height: 40px; border-radius: 50%; font-size: 1.5rem; cursor: pointer; z-index: 10; display: flex; align-items: center; justify-content: center;">&times;</button>
                    <div class="lightbox-navigation" style="position: absolute; top: 50%; transform: translateY(-50%); width: 100%; display: flex; justify-content: space-between; padding: 0 20px; z-index: 5;">
                        <button class="lightbox-prev" aria-label="Previous image" style="background: rgba(212, 175, 55, 0.8); color: white; border: none; width: 50px; height: 50px; border-radius: 50%; font-size: 1.5rem; cursor: pointer; backdrop-filter: blur(5px);">&lt;</button>
                        <button class="lightbox-next" aria-label="Next image" style="background: rgba(212, 175, 55, 0.8); color: white; border: none; width: 50px; height: 50px; border-radius: 50%; font-size: 1.5rem; cursor: pointer; backdrop-filter: blur(5px);">&gt;</button>
                    </div>
                    <div id="lightboxImageContainer" style="width: 100%; height: 70vh; display: flex; align-items: center; justify-content: center;">
                        <img id="lightboxImage" style="max-width: 100%; max-height: 100%; object-fit: contain;" alt="">
                    </div>
                    <div class="lightbox-info" style="padding: 1.5rem; background: linear-gradient(135deg, var(--light-beige) 0%, #FFF8E1 100%); border-top: 3px solid var(--gold-color);">
                        <h3 id="lightboxTitle" style="color: var(--deep-red); margin-bottom: 0.5rem; font-family: 'Georgia', serif;"></h3>
                        <p id="lightboxDescription" style="color: #5C4033; line-height: 1.6;"></p>
                    </div>
                </div>
            </div>
        `;
        
        // Add lightbox to body
        if (!document.getElementById('classicLightbox')) {
            document.body.insertAdjacentHTML('beforeend', lightboxHTML);
        }
        
        const lightbox = document.getElementById('classicLightbox');
        const lightboxImage = document.getElementById('lightboxImage');
        const lightboxTitle = document.getElementById('lightboxTitle');
        const lightboxDescription = document.getElementById('lightboxDescription');
        const lightboxClose = document.querySelector('.lightbox-close');
        const lightboxPrev = document.querySelector('.lightbox-prev');
        const lightboxNext = document.querySelector('.lightbox-next');
        
        let currentGalleryItems = [];
        let currentIndex = 0;
        
        // Open lightbox function
        window.openClassicLightbox = function(galleryItem, index) {
            const img = galleryItem.querySelector('img');
            const title = galleryItem.querySelector('.classic-gallery-overlay-content h4')?.textContent || 'Hostel Image';
            const description = galleryItem.querySelector('.classic-gallery-overlay-content p')?.textContent || '';
            
            // Get all gallery items
            currentGalleryItems = Array.from(document.querySelectorAll('.classic-gallery-item'));
            currentIndex = currentGalleryItems.indexOf(galleryItem);
            
            // Set lightbox content
            lightboxImage.src = img.src;
            lightboxImage.alt = title;
            lightboxTitle.textContent = title;
            lightboxDescription.textContent = description;
            
            // Show lightbox with animation
            lightbox.style.display = 'flex';
            document.body.style.overflow = 'hidden';
            
            setTimeout(() => {
                lightbox.style.opacity = '1';
                document.querySelector('.lightbox-content').style.transform = 'scale(1)';
            }, 10);
            
            // Update navigation visibility
            updateNavigation();
        };
        
        function updateNavigation() {
            if (lightboxPrev && lightboxNext) {
                lightboxPrev.style.display = currentIndex > 0 ? 'flex' : 'none';
                lightboxNext.style.display = currentIndex < currentGalleryItems.length - 1 ? 'flex' : 'none';
            }
        }
        
        // Navigation functions
        function showNextImage() {
            if (currentIndex < currentGalleryItems.length - 1) {
                currentIndex++;
                updateLightboxContent();
            }
        }
        
        function showPrevImage() {
            if (currentIndex > 0) {
                currentIndex--;
                updateLightboxContent();
            }
        }
        
        function updateLightboxContent() {
            const galleryItem = currentGalleryItems[currentIndex];
            const img = galleryItem.querySelector('img');
            const title = galleryItem.querySelector('.classic-gallery-overlay-content h4')?.textContent || 'Hostel Image';
            const description = galleryItem.querySelector('.classic-gallery-overlay-content p')?.textContent || '';
            
            // Add loading effect
            lightboxImage.style.opacity = '0';
            
            setTimeout(() => {
                lightboxImage.src = img.src;
                lightboxImage.alt = title;
                lightboxTitle.textContent = title;
                lightboxDescription.textContent = description;
                lightboxImage.style.opacity = '1';
                updateNavigation();
            }, 200);
        }
        
        // Close lightbox
        function closeLightbox() {
            lightbox.style.opacity = '0';
            document.querySelector('.lightbox-content').style.transform = 'scale(0.9)';
            
            setTimeout(() => {
                lightbox.style.display = 'none';
                document.body.style.overflow = 'auto';
            }, 300);
        }
        
        // Event Listeners
        if (lightboxClose) {
            lightboxClose.addEventListener('click', closeLightbox);
        }
        
        if (lightboxPrev) {
            lightboxPrev.addEventListener('click', showPrevImage);
        }
        
        if (lightboxNext) {
            lightboxNext.addEventListener('click', showNextImage);
        }
        
        lightbox.addEventListener('click', (e) => {
            if (e.target === lightbox) {
                closeLightbox();
            }
        });
        
        // Keyboard navigation
        document.addEventListener('keydown', (e) => {
            if (lightbox.style.display === 'flex') {
                switch(e.key) {
                    case 'Escape':
                        closeLightbox();
                        break;
                    case 'ArrowRight':
                        showNextImage();
                        break;
                    case 'ArrowLeft':
                        showPrevImage();
                        break;
                }
            }
        });
        
        // Add click handlers to gallery items
        document.querySelectorAll('.classic-gallery-item').forEach((item, index) => {
            item.style.cursor = 'pointer';
            item.setAttribute('role', 'button');
            item.setAttribute('tabindex', '0');
            item.setAttribute('aria-label', 'View image details');
            
            item.addEventListener('click', function(e) {
                if (!e.target.closest('a')) { // Don't trigger if clicking a link
                    window.openClassicLightbox(this, index);
                }
            });
            
            item.addEventListener('keydown', function(e) {
                if (e.key === 'Enter' || e.key === ' ') {
                    e.preventDefault();
                    window.openClassicLightbox(this, index);
                }
            });
        });
    }
    
    // Lazy Loading for Images
    function initLazyLoading() {
        if ('IntersectionObserver' in window) {
            const imageObserver = new IntersectionObserver((entries, observer) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        const img = entry.target;
                        
                        // Load image
                        if (img.dataset.src) {
                            img.src = img.dataset.src;
                        }
                        
                        // Add loading animation
                        img.style.opacity = '0';
                        img.style.transition = 'opacity 0.5s ease';
                        
                        // Check if image is already loaded
                        if (img.complete) {
                            img.style.opacity = '1';
                        } else {
                            img.addEventListener('load', function() {
                                this.style.opacity = '1';
                            });
                        }
                        
                        // Stop observing
                        observer.unobserve(img);
                    }
                });
            }, {
                rootMargin: '100px 0px',
                threshold: 0.1
            });
            
            // Observe all gallery images
            document.querySelectorAll('.classic-gallery-item img').forEach(img => {
                // Convert to lazy loading
                if (!img.dataset.src && img.src && !img.src.includes('default-room.png')) {
                    img.dataset.src = img.src;
                    // Create a low-quality placeholder
                    img.src = createClassicPlaceholder(300, 300);
                }
                imageObserver.observe(img);
            });
        }
    }
    
    function createClassicPlaceholder(width, height) {
        // Create a simple placeholder with classic theme colors
        const svg = `<svg xmlns="http://www.w3.org/2000/svg" width="${width}" height="${height}" viewBox="0 0 ${width} ${height}">
            <rect width="100%" height="100%" fill="#F8F4E9"/>
            <rect x="10" y="10" width="${width - 20}" height="${height - 20}" fill="#FFF8E1" stroke="#D4AF37" stroke-width="2"/>
            <text x="50%" y="50%" text-anchor="middle" dy="0.3em" fill="#8B4513" font-family="Georgia" font-size="14">Loading...</text>
        </svg>`;
        
        return `data:image/svg+xml;base64,${btoa(svg)}`;
    }
    
    // Form Validation Enhancement
    function initFormValidation() {
        const contactForm = document.querySelector('.classic-contact-form form');
        if (!contactForm) return;
        
        const inputs = contactForm.querySelectorAll('input, textarea');
        
        inputs.forEach(input => {
            // Add validation styling
            input.addEventListener('invalid', function(e) {
                e.preventDefault();
                this.style.borderColor = '#dc2626';
                this.style.boxShadow = '0 0 0 3px rgba(220, 38, 38, 0.1)';
                
                // Show error message
                let errorMsg = this.nextElementSibling;
                if (!errorMsg || !errorMsg.classList.contains('form-error')) {
                    errorMsg = document.createElement('div');
                    errorMsg.className = 'form-error';
                    errorMsg.style.color = '#dc2626';
                    errorMsg.style.fontSize = '0.875rem';
                    errorMsg.style.marginTop = '-0.5rem';
                    errorMsg.style.marginBottom = '1rem';
                    this.parentNode.insertBefore(errorMsg, this.nextSibling);
                }
                
                if (this.validity.valueMissing) {
                    errorMsg.textContent = 'यो फिल्ड आवश्यक छ';
                } else if (this.validity.typeMismatch) {
                    errorMsg.textContent = 'कृपया सही इमेल ठेगाना लेख्नुहोस्';
                }
            });
            
            // Clear error on input
            input.addEventListener('input', function() {
                this.style.borderColor = '';
                this.style.boxShadow = '';
                
                const errorMsg = this.nextElementSibling;
                if (errorMsg && errorMsg.classList.contains('form-error')) {
                    errorMsg.textContent = '';
                }
            });
        });
        
        // Form submission
        contactForm.addEventListener('submit', function(e) {
            if (!this.checkValidity()) {
                e.preventDefault();
                return;
            }
            
            // Add loading state
            const submitBtn = this.querySelector('.classic-form-button');
            const originalText = submitBtn.textContent;
            
            submitBtn.textContent = 'पठाइदै...';
            submitBtn.disabled = true;
            submitBtn.style.opacity = '0.7';
            
            // Re-enable after 5 seconds (in case of error)
            setTimeout(() => {
                submitBtn.textContent = originalText;
                submitBtn.disabled = false;
                submitBtn.style.opacity = '1';
            }, 5000);
        });
    }
    
    // Statistics Counter Animation
    function animateStatistics() {
        const statNumbers = document.querySelectorAll('.classic-stat-number');
        
        statNumbers.forEach(stat => {
            const finalValue = parseInt(stat.textContent);
            if (isNaN(finalValue)) return;
            
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
        });
    }
    
    // Smooth Scroll for Anchor Links
    function initSmoothScroll() {
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function(e) {
                const href = this.getAttribute('href');
                if (href === '#') return;
                
                const targetElement = document.querySelector(href);
                if (targetElement) {
                    e.preventDefault();
                    targetElement.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                    
                    // Update URL without page reload
                    history.pushState(null, null, href);
                }
            });
        });
    }
    
    // Initialize everything when DOM is ready
    document.addEventListener('DOMContentLoaded', function() {
        console.log('🏛️ Classic Theme - DOM Ready');
        
        // Initialize all features
        fixBrokenImages();
        initClassicLightbox();
        initLazyLoading();
        initFormValidation();
        animateStatistics();
        initSmoothScroll();
        
        // Add loading animation to page
        document.body.style.opacity = '0';
        setTimeout(() => {
            document.body.style.transition = 'opacity 0.5s ease';
            document.body.style.opacity = '1';
        }, 100);
    });
    
    // Export functions for debugging
    window.ClassicTheme = {
        fixBrokenImages,
        initClassicLightbox,
        initLazyLoading,
        initFormValidation,
        animateStatistics,
        initSmoothScroll
    };
})();