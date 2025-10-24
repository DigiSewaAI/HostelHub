// Homepage-specific JavaScript - OPTIMIZED
document.addEventListener('DOMContentLoaded', function() {
    // Initialize Hero Slider with Horizontal Layout
    const heroSwiper = new Swiper('.hero-slider', {
        loop: true,
        autoplay: {
            delay: 4000,
            disableOnInteraction: false,
        },
        effect: 'slide',
        speed: 600,
        grabCursor: true,
        navigation: {
            nextEl: '.swiper-button-next',
            prevEl: '.swiper-button-prev',
        },
        slidesPerView: 1,
        spaceBetween: 0,
    });

    // Hero image optimization
    const heroImages = document.querySelectorAll('.hero-slideshow img');
    heroImages.forEach(img => {
        if (img.complete) {
            optimizeHeroImage(img);
        } else {
            img.addEventListener('load', function() {
                optimizeHeroImage(this);
            });
        }
    });
    
    function optimizeHeroImage(img) {
        img.style.objectFit = 'cover';
        img.style.width = '100%';
        img.style.height = '100%';
    }
    
    // Initialize gallery slider
    const gallerySwiper = new Swiper('.gallery-swiper', {
        slidesPerView: 1,
        spaceBetween: 20,
        loop: true,
        autoplay: {
            delay: 5000,
            disableOnInteraction: false,
        },
        speed: 1000,
        breakpoints: {
            640: {
                slidesPerView: 2,
            },
            1024: {
                slidesPerView: 3,
            },
        },
        observer: true,
        observeParents: true
    });
    
    // Form validation
    const bookingForm = document.getElementById('booking-form');
    if (bookingForm) {
        bookingForm.addEventListener('submit', function(e) {
            let isValid = true;
            
            // Validate city
            const citySelect = document.getElementById('city');
            if (!citySelect.value) {
                citySelect.parentElement.classList.add('error');
                isValid = false;
            } else {
                citySelect.parentElement.classList.remove('error');
            }
            
            // Validate check-in date
            const checkIn = document.getElementById('check_in');
            if (!checkIn.value) {
                checkIn.parentElement.classList.add('error');
                isValid = false;
            } else {
                checkIn.parentElement.classList.remove('error');
            }
            
            // Validate check-out date
            const checkOut = document.getElementById('check_out');
            if (!checkOut.value) {
                checkOut.parentElement.classList.add('error');
                isValid = false;
            } else if (checkIn.value && checkOut.value <= checkIn.value) {
                checkOut.parentElement.classList.add('error');
                checkOut.parentElement.querySelector('.error-message').textContent = 'चेक-आउट मिति चेक-इन भन्दा पछि हुनुपर्छ';
                isValid = false;
            } else {
                checkOut.parentElement.classList.remove('error');
            }
            
            if (!isValid) {
                e.preventDefault();
            }
        });
    }
    
    // Counter animation
    function animateCounter(elementId, finalValue, duration = 2000) {
        const element = document.getElementById(elementId);
        if (!element) return;
        let currentValue = parseInt(element.textContent.replace(/[^\d]/g, '')) || 0;
        finalValue = parseInt(finalValue) || 0;
        let start = currentValue;
        const increment = Math.ceil(finalValue / (duration / 16));
        const timer = setInterval(() => {
            start += increment;
            if (start >= finalValue) {
                element.textContent = finalValue.toLocaleString('ne');
                clearInterval(timer);
            } else {
                element.textContent = start.toLocaleString('ne');
            }
        }, 16);
    }
    
    // Initialize counters
    const counterObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                animateCounter('students-counter', 125);
                animateCounter('hostels-counter', 24);
                animateCounter('cities-counter', 5);
                animateCounter('students-counter-stat', 125);
                animateCounter('hostels-counter-stat', 24);
                animateCounter('cities-counter-stat', 5);
                counterObserver.disconnect();
            }
        });
    }, { threshold: 0.5 });
    
    const statsSection = document.querySelector('.hero-stats, .stats-section');
    if (statsSection) {
        counterObserver.observe(statsSection);
    }
    
    // YouTube video handling
    document.querySelectorAll('.youtube-thumbnail').forEach(thumb => {
        thumb.addEventListener('click', function() {
            const youtubeId = this.getAttribute('data-youtube-id');
            if (youtubeId) {
                window.open(`https://www.youtube.com/watch?v=${youtubeId}`, '_blank');
            }
        });
    });

    // Force hero section to display properly
    setTimeout(() => {
        const hero = document.querySelector('.hero');
        if (hero) {
            hero.style.display = 'none';
            hero.offsetHeight; // Trigger reflow
            hero.style.display = 'flex';
        }
    }, 100);
});