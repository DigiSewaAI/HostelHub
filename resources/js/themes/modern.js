// resources/js/themes/modern.js
(function() {
    'use strict';
    
    console.log('🎨 Modern Theme Initialized');
    
    // Make the lightbox function globally available
    window.openModernLightbox = function(element, index) {
        console.log('Opening lightbox for gallery item at index:', index);
        
        // Simple implementation - opens image in new tab
        const img = element.querySelector('.gallery-item-image');
        const imgUrl = img?.dataset?.src || img?.src;
        
        if (imgUrl) {
            window.open(imgUrl, '_blank');
        } else {
            console.error('No image found for lightbox');
        }
    };
    
    // Performance monitoring
    window.addEventListener('load', function() {
        // Core Web Vitals monitoring
        if ('PerformanceObserver' in window) {
            const observer = new PerformanceObserver((list) => {
                for (const entry of list.getEntries()) {
                    console.log('Modern Theme - Performance Entry:', entry);
                    
                    // Send to analytics if gtag is available
                    if (typeof gtag !== 'undefined') {
                        gtag('event', 'performance', {
                            'event_category': 'Web Vitals',
                            'event_label': entry.name,
                            'value': Math.round(entry.startTime + entry.duration),
                            'non_interaction': true
                        });
                    }
                }
            });
            
            observer.observe({ 
                entryTypes: ['paint', 'largest-contentful-paint', 'layout-shift'] 
            });
        }
        
        // Image loading performance tracking
        const images = document.images;
        let loadedImages = 0;
        
        Array.from(images).forEach(img => {
            if (img.complete) {
                loadedImages++;
            } else {
                img.addEventListener('load', () => {
                    loadedImages++;
                    checkAllImagesLoaded();
                });
                
                img.addEventListener('error', () => {
                    loadedImages++;
                    checkAllImagesLoaded();
                });
            }
        });
        
        function checkAllImagesLoaded() {
            if (loadedImages === images.length) {
                console.log('Modern Theme - All images loaded');
                
                if (typeof gtag !== 'undefined') {
                    gtag('event', 'images_loaded', {
                        'event_category': 'Performance',
                        'event_label': 'Modern Theme',
                        'value': images.length
                    });
                }
            }
        }
        
        // Check if already all loaded
        if (images.length === 0 || loadedImages === images.length) {
            checkAllImagesLoaded();
        }
    });
    
    // Initialize when DOM is ready
    document.addEventListener('DOMContentLoaded', function() {
        console.log('Modern Theme - DOM Ready');
        console.log('Hostel Config:', window.MODERN_THEME_CONFIG);
        
        // Add any other initialization code here
    });
})();