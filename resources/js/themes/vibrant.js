// public/js/themes/vibrant.js
(function() {
    'use strict';
    
    // Page Loading
    function initPageLoading() {
        const pageLoadOverlay = document.getElementById('pageLoadOverlay');
        const mainContent = document.getElementById('mainContent');
        
        if (pageLoadOverlay && mainContent) {
            setTimeout(() => {
                pageLoadOverlay.classList.add('hidden');
                mainContent.style.opacity = '1';
                
                // Animate elements after page loads
                animateElements();
            }, 300);
        }
    }
    
    // Gallery Modal
    function initGalleryModal() {
        const modal = document.getElementById('vibrantModal');
        const modalClose = document.getElementById('modalClose');
        
        window.openVibrantMediaModal = function(element) {
            const mediaType = element.getAttribute('data-media-type');
            const mediaUrl = element.getAttribute('data-media-url');
            const title = element.getAttribute('data-title');
            const description = element.getAttribute('data-description');
            
            // Set modal content
            const modalMediaContainer = document.getElementById('modalMediaContainer');
            const modalTitle = document.getElementById('modalTitle');
            const modalDescription = document.getElementById('modalDescription');
            
            modalMediaContainer.innerHTML = '';
            modalTitle.textContent = title;
            modalDescription.textContent = description;
            
            if (mediaType === 'image') {
                const img = document.createElement('img');
                img.src = mediaUrl;
                img.alt = title;
                img.className = 'modal-media';
                modalMediaContainer.appendChild(img);
            } else if (mediaType === 'external_video') {
                const videoLink = document.createElement('a');
                videoLink.href = mediaUrl;
                videoLink.target = '_blank';
                videoLink.className = 'external-video-link';
                videoLink.innerHTML = `
                    <i class="fab fa-youtube" style="font-size: 4rem; color: #FF0000; margin-bottom: 1rem;"></i>
                    <h3 style="color: white; margin-bottom: 1rem;">YouTube भिडियो</h3>
                    <p style="color: #E2E8F0;">यो भिडियो YouTube मा हेर्न क्लिक गर्नुहोस्</p>
                `;
                modalMediaContainer.appendChild(videoLink);
            } else if (mediaType === 'video') {
                const video = document.createElement('video');
                video.src = mediaUrl;
                video.controls = true;
                video.autoplay = true;
                video.className = 'modal-video';
                modalMediaContainer.appendChild(video);
            }
            
            // Show modal
            modal.classList.add('active');
            document.body.style.overflow = 'hidden';
        };
        
        // Close modal
        if (modalClose) {
            modalClose.addEventListener('click', function() {
                modal.classList.remove('active');
                document.body.style.overflow = 'auto';
            });
        }
        
        // Close modal when clicking outside
        if (modal) {
            modal.addEventListener('click', function(e) {
                if (e.target === modal) {
                    modal.classList.remove('active');
                    document.body.style.overflow = 'auto';
                }
            });
        }
        
        // Close modal with Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && modal && modal.classList.contains('active')) {
                modal.classList.remove('active');
                document.body.style.overflow = 'auto';
            }
        });
    }
    
    // Reviews Slider
    function initReviewsSlider() {
        const slider = document.getElementById('reviewsSlider');
        if (!slider) return;
        
        const slides = document.querySelectorAll('.review-slide');
        if (slides.length <= 1) return;
        
        let currentSlide = 0;
        const totalSlides = slides.length;
        
        function updateSlider() {
            slider.style.transform = `translateX(-${currentSlide * 100}%)`;
            
            // Update dots
            const dots = document.querySelectorAll('.slider-dot');
            dots.forEach((dot, index) => {
                dot.classList.toggle('active', index === currentSlide);
            });
        }
        
        // Auto slide
        const slideInterval = setInterval(() => {
            currentSlide = (currentSlide + 1) % totalSlides;
            updateSlider();
        }, 5000);
        
        // Button controls
        const prevBtn = document.querySelector('.prev-btn');
        const nextBtn = document.querySelector('.next-btn');
        
        if (nextBtn) {
            nextBtn.addEventListener('click', () => {
                currentSlide = (currentSlide + 1) % totalSlides;
                updateSlider();
                clearInterval(slideInterval);
            });
        }
        
        if (prevBtn) {
            prevBtn.addEventListener('click', () => {
                currentSlide = (currentSlide - 1 + totalSlides) % totalSlides;
                updateSlider();
                clearInterval(slideInterval);
            });
        }
        
        // Dot navigation
        document.querySelectorAll('.slider-dot').forEach((dot, index) => {
            dot.addEventListener('click', () => {
                currentSlide = index;
                updateSlider();
                clearInterval(slideInterval);
            });
        });
        
        // Pause on hover
        slider.addEventListener('mouseenter', () => {
            clearInterval(slideInterval);
        });
        
        slider.addEventListener('mouseleave', () => {
            clearInterval(slideInterval);
            setInterval(() => {
                currentSlide = (currentSlide + 1) % totalSlides;
                updateSlider();
            }, 5000);
        });
    }
    
    // Image Lazy Loading
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
                        imageObserver.unobserve(img);
                    }
                });
            }, {
                rootMargin: '50px 0px',
                threshold: 0.1
            });
            
            document.querySelectorAll('.lazyload').forEach(img => {
                imageObserver.observe(img);
            });
        } else {
            // Fallback for older browsers
            document.querySelectorAll('.lazyload').forEach(img => {
                if (img.dataset.src) {
                    img.src = img.dataset.src;
                }
            });
        }
    }
    
    // Fix broken images
    function fixBrokenImages() {
        document.querySelectorAll('img').forEach(img => {
            if (img.src.includes('undefined') || img.src.includes('null') || !img.src) {
                img.src = '/images/default-room.png';
                img.style.opacity = '0.7';
            }
            
            img.addEventListener('error', function() {
                this.src = '/images/default-room.png';
                this.style.opacity = '0.7';
            });
        });
    }
    
    // Animate elements
    function animateElements() {
        const stats = document.getElementById('vibrantStats');
        const gallery = document.getElementById('vibrantGallery');
        
        if (stats) setTimeout(() => stats.classList.add('loaded'), 200);
        if (gallery) setTimeout(() => gallery.classList.add('loaded'), 400);
        
        // Animate floating circles
        const circles = document.querySelectorAll('.floating-circle');
        circles.forEach(circle => {
            circle.style.animationPlayState = 'running';
        });
    }
    
    // Particle Background
    function initParticles() {
        if (!document.querySelector('.vibrant-body')) return;
        
        const canvas = document.createElement('canvas');
        canvas.id = 'particle-canvas';
        canvas.style.position = 'fixed';
        canvas.style.top = '0';
        canvas.style.left = '0';
        canvas.style.width = '100%';
        canvas.style.height = '100%';
        canvas.style.zIndex = '-2';
        canvas.style.pointerEvents = 'none';
        
        document.querySelector('.vibrant-body').prepend(canvas);
        
        const ctx = canvas.getContext('2d');
        canvas.width = window.innerWidth;
        canvas.height = window.innerHeight;
        
        const particles = [];
        const colors = ['#EC4899', '#8B5CF6', '#06B6D4', '#10B981'];
        
        // Create particles
        for (let i = 0; i < 50; i++) {
            particles.push({
                x: Math.random() * canvas.width,
                y: Math.random() * canvas.height,
                size: Math.random() * 3 + 1,
                color: colors[Math.floor(Math.random() * colors.length)],
                speedX: Math.random() * 0.5 - 0.25,
                speedY: Math.random() * 0.5 - 0.25
            });
        }
        
        function animate() {
            ctx.clearRect(0, 0, canvas.width, canvas.height);
            
            particles.forEach(particle => {
                particle.x += particle.speedX;
                particle.y += particle.speedY;
                
                // Bounce off edges
                if (particle.x < 0 || particle.x > canvas.width) particle.speedX *= -1;
                if (particle.y < 0 || particle.y > canvas.height) particle.speedY *= -1;
                
                ctx.beginPath();
                ctx.arc(particle.x, particle.y, particle.size, 0, Math.PI * 2);
                ctx.fillStyle = particle.color;
                ctx.fill();
                
                // Draw connections
                particles.forEach(other => {
                    const dx = particle.x - other.x;
                    const dy = particle.y - other.y;
                    const distance = Math.sqrt(dx * dx + dy * dy);
                    
                    if (distance < 100) {
                        ctx.beginPath();
                        ctx.strokeStyle = particle.color + '20';
                        ctx.lineWidth = 0.5;
                        ctx.moveTo(particle.x, particle.y);
                        ctx.lineTo(other.x, other.y);
                        ctx.stroke();
                    }
                });
            });
            
            requestAnimationFrame(animate);
        }
        
        animate();
        
        // Resize handler
        window.addEventListener('resize', () => {
            canvas.width = window.innerWidth;
            canvas.height = window.innerHeight;
        });
    }
    
    // Smooth scrolling for anchor links
    function initSmoothScrolling() {
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                const href = this.getAttribute('href');
                
                if (href !== '#') {
                    e.preventDefault();
                    const target = document.querySelector(href);
                    
                    if (target) {
                        window.scrollTo({
                            top: target.offsetTop - 80,
                            behavior: 'smooth'
                        });
                    }
                }
            });
        });
    }
    
    // Initialize everything when DOM is ready
    document.addEventListener('DOMContentLoaded', function() {
        initPageLoading();
        initGalleryModal();
        initReviewsSlider();
        initLazyLoading();
        fixBrokenImages();
        initSmoothScrolling();
        
        // Initialize particles if enabled
        if (document.querySelector('.vibrant-body')) {
            initParticles();
        }
    });
    
    // Export for debugging
    window.VibrantTheme = {
        initPageLoading,
        initGalleryModal,
        initReviewsSlider,
        initLazyLoading,
        fixBrokenImages,
        initParticles,
        initSmoothScrolling
    };
})();