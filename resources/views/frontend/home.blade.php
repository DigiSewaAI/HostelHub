@extends('layouts.frontend')

@section('page-title', 'HostelHub — होस्टल प्रबन्धन प्रणाली | Nepal')
@section('og-title', 'HostelHub — होस्टल व्यवस्थापन सजिलो बनाउने SaaS')
@section('og-description', 'HostelHub — होस्टल व्यवस्थापन सजिलो बनाउने SaaS')

@push('styles')
<!-- FONT AWESOME CDN -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css">

@vite(['resources/css/home.css'])

<!-- 🚨 CRITICAL HERO FIX STYLES -->
<style>
/* 🚨 HERO SECTION PROTECTION - HIGHEST PRIORITY */
.hero {
    min-height: 100vh !important;
    padding: 0 !important;
    margin: 0 !important;
    background: linear-gradient(135deg, #1e3a8a, #0ea5e9) !important;
    position: relative !important;
    overflow: hidden !important;
    z-index: 1 !important;
    display: flex !important;
    align-items: center !important;
    width: 100vw !important;
    max-width: 100vw !important;
    left: 0 !important;
    right: 0 !important;
}

/* 🚨 OVERRIDE ANY GLOBAL STYLES THAT MIGHT HIDE HERO */
main.home-page-main {
    margin-top: 0 !important;
    padding-top: 0 !important;
}

.content-container {
    padding-top: 0 !important;
}

/* 🚨 HERO CONTENT VISIBILITY */
.hero-content {
    display: grid !important;
    grid-template-columns: 1.1fr 0.9fr !important;
    gap: 2.5rem !important;
    align-items: center !important;
    height: 100% !important;
    position: relative !important;
    z-index: 15 !important;
    width: 100% !important;
    max-width: 1200px !important;
    margin: 0 auto !important;
    padding: 2rem 1.5rem !important;
}

.hero-text {
    max-width: 100% !important;
    color: var(--text-light) !important;
    width: 100% !important;
    padding-right: 1rem !important;
}

.hero-title {
    font-size: 2.5rem !important;
    font-weight: 800 !important;
    line-height: 1.1 !important;
    margin-bottom: 1rem !important;
    color: var(--text-light) !important;
    text-shadow: 0 2px 4px rgba(0,0,0,0.1) !important;
    text-align: left !important;
}

.hero-subtitle {
    font-size: 1.1rem !important;
    color: rgba(249, 250, 251, 0.95) !important;
    margin-bottom: 1.5rem !important;
    line-height: 1.5 !important;
    text-align: left !important;
}

/* 🚨 QUICK FIX - Search Form Alignment */
.widget-form {
    align-items: start !important;
}

/* 🚨 HERO FULL WIDTH FIX */
.hero {
    min-height: 100vh !important;
    padding: 0 !important;
    margin: 0 !important;
    background: linear-gradient(135deg, #1e3a8a, #0ea5e9) !important;
    position: relative !important;
    overflow: hidden !important;
    z-index: 1 !important;
    display: flex !important;
    align-items: center !important;
    width: 100vw !important;
    max-width: 100vw !important;
    left: 0 !important;
    right: 0 !important;
    
    /* 🚨 CRITICAL: Remove any left spacing */
    margin-left: calc(-50vw + 50%) !important;
    margin-right: calc(-50vw + 50%) !important;
}

/* 🚨 HERO CONTENT FIX */
.hero-content {
    display: grid !important;
    grid-template-columns: 1.1fr 0.9fr !important;
    gap: 2.5rem !important;
    align-items: center !important;
    height: 100% !important;
    position: relative !important;
    z-index: 15 !important;
    width: 100% !important;
    max-width: 1200px !important;
    margin: 0 auto !important;
    padding: 2rem 1.5rem !important;
}

/* 🚨 REMOVE CONTAINER PADDING IN HERO */
.hero .container {
    padding: 0 !important;
    margin: 0 auto !important;
    width: 100% !important;
    max-width: 1200px !important;
}

/* ==================== COMPACT SEARCH WIDGET STYLES ==================== */

.compact-search-widget {
    background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
    border-radius: 16px;
    box-shadow: 0 15px 35px -12px rgba(0, 0, 0, 0.2), 
                0 0 0 1px rgba(14, 165, 233, 0.1);
    padding: 1.8rem;
    margin: -5rem auto 2rem !important; /* Reduced from -7rem to -5rem */
    position: relative;
    z-index: 100;
    width: 92%;
    max-width: 800px; /* Reduced from 1200px to 800px */
    border: 1px solid rgba(14, 165, 233, 0.15);
    backdrop-filter: blur(10px);
    transform: translateY(0);
    transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
}

.compact-search-widget:hover {
    transform: translateY(-5px);
    box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.3), 
                0 0 0 1px rgba(14, 165, 233, 0.2);
}

.compact-widget-title {
    font-size: 1.4rem;
    font-weight: 700;
    margin-bottom: 1.5rem;
    text-align: center;
    color: var(--primary);
    width: 100%;
    position: relative;
    padding-bottom: 0.8rem;
}

.compact-widget-title::after {
    content: "";
    position: absolute;
    bottom: 0;
    left: 50%;
    transform: translateX(-50%);
    width: 60px;
    height: 3px;
    background: linear-gradient(to right, var(--primary), var(--secondary));
    border-radius: 2px;
}

/* Compact Form Grid Layout */
.compact-form-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 1rem;
    width: 100%;
    margin-bottom: 1.2rem;
}

.compact-form-group {
    margin-bottom: 0;
    position: relative;
}

.compact-form-group label {
    display: block;
    margin-bottom: 0.5rem;
    font-weight: 600;
    color: var(--primary);
    font-size: 0.9rem;
    transition: var(--transition);
}

/* Input with Icon */
.compact-form-group .input-with-icon {
    position: relative;
}

.compact-form-group .input-with-icon i {
    position: absolute;
    left: 0.8rem;
    top: 50%;
    transform: translateY(-50%);
    color: var(--primary);
    font-size: 1rem;
    z-index: 2;
    transition: var(--transition);
}

.compact-form-control {
    width: 100%;
    padding: 0.8rem 0.8rem 0.8rem 2.5rem;
    border: 2px solid #e2e8f0;
    border-radius: 10px;
    font-size: 0.95rem;
    transition: all 0.3s ease;
    background: #ffffff;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.04);
    font-family: 'Noto Sans Devanagari', 'Inter', sans-serif;
    height: 48px;
}

.compact-form-control:focus {
    outline: none;
    border-color: var(--secondary);
    box-shadow: 0 0 0 3px rgba(14, 165, 233, 0.15), 
                0 4px 12px rgba(14, 165, 233, 0.1);
    background: #ffffff;
    transform: translateY(-2px);
}

.compact-form-control:hover {
    border-color: #cbd5e1;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.08);
}

/* Search Button */
.compact-search-button {
    grid-column: 1 / -1;
    display: flex;
    justify-content: center;
    margin-top: 0.5rem;
}

.compact-search-btn {
    background: linear-gradient(135deg, var(--primary), var(--secondary));
    color: white;
    border: none;
    border-radius: 10px;
    padding: 1rem 2rem;
    font-weight: 700;
    font-size: 1rem;
    box-shadow: 0 6px 20px rgba(14, 165, 233, 0.3);
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
    cursor: pointer;
    display: flex;
    align-items: center;
    gap: 0.5rem;
    min-width: 200px;
    justify-content: center;
}

.compact-search-btn:hover {
    transform: translateY(-3px);
    box-shadow: 0 12px 25px rgba(14, 165, 233, 0.4);
    background: linear-gradient(135deg, var(--primary-dark), var(--secondary-dark));
}

.compact-search-btn::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent);
    transition: 0.5s;
}

.compact-search-btn:hover::before {
    left: 100%;
}

/* Quick Info Badges */
.compact-quick-info {
    display: flex;
    justify-content: center;
    gap: 0.8rem;
    flex-wrap: wrap;
    padding-top: 1rem;
    border-top: 1px solid rgba(14, 165, 233, 0.1);
}

.quick-badge {
    background: rgba(14, 165, 233, 0.1);
    color: var(--primary);
    padding: 0.4rem 0.8rem;
    border-radius: 20px;
    font-size: 0.8rem;
    font-weight: 600;
    border: 1px solid rgba(14, 165, 233, 0.2);
    transition: all 0.3s ease;
}

.quick-badge:hover {
    background: rgba(14, 165, 233, 0.2);
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(14, 165, 233, 0.15);
}

/* ==================== HERO SLIDER STYLES FOR HOSTELS ==================== */
.slide-link {
    display: block;
    position: relative;
    height: 100%;
    border-radius: 12px;
    overflow: hidden;
    text-decoration: none;
    transition: transform 0.3s ease;
}

.slide-link:hover {
    transform: scale(1.02);
}

.slide-overlay {
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    background: linear-gradient(transparent, rgba(0,0,0,0.7));
    color: white;
    padding: 1.5rem;
    transition: all 0.3s ease;
}

.slide-link:hover .slide-overlay {
    background: linear-gradient(transparent, rgba(0,0,0,0.9));
}

.slide-content {
    text-align: center;
}

.hostel-name {
    font-size: 1.3rem;
    font-weight: 700;
    margin-bottom: 0.5rem;
    color: white;
    text-shadow: 0 2px 4px rgba(0,0,0,0.5);
}

.hostel-location {
    font-size: 0.9rem;
    color: rgba(255,255,255,0.9);
    margin-bottom: 0.5rem;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.3rem;
}

.view-hostel-btn {
    display: inline-block;
    background: var(--primary);
    color: white;
    padding: 0.4rem 0.8rem;
    border-radius: 20px;
    font-size: 0.8rem;
    font-weight: 600;
    margin-top: 0.5rem;
    transition: all 0.3s ease;
}

.slide-link:hover .view-hostel-btn {
    background: var(--secondary);
    transform: translateY(-2px);
}

/* ==================== FIXED TESTIMONIAL CAROUSEL STYLES - BLUE CARDS ==================== */
.testimonials-carousel-section {
    width: 100%;
    background: #f8fafc; /* Light background to match the page */
    padding: 80px 0;
    position: relative;
}

.testimonials-carousel-container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 20px;
}

.testimonials-carousel-title {
    text-align: center;
    color: var(--primary); /* Blue color like "योजना अनुसारका मूल्यहरू" */
    font-size: 2.2rem;
    margin-bottom: 15px;
    font-weight: 700;
}

.testimonials-carousel-subtitle {
    text-align: center;
    color: #666;
    font-size: 1.1rem;
    margin-bottom: 50px;
}

.testimonials-swiper {
    padding: 20px 0 60px;
}

/* BLUE TESTIMONIAL CARD - MATCHING WITH PRICING SECTION & SIGN UP BUTTON */
.testimonial-carousel-card {
    background: linear-gradient(135deg, var(--primary), var(--secondary)); /* Blue gradient like sign up button */
    border-radius: 12px;
    padding: 40px 30px;
    border: none;
    height: auto;
    min-height: 320px;
    display: flex;
    flex-direction: column;
    transition: all 0.3s ease;
    box-shadow: 0 10px 30px rgba(30, 58, 138, 0.2); /* Blue shadow */
}

.testimonial-carousel-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 15px 40px rgba(30, 58, 138, 0.3);
}

.testimonial-quote {
    font-size: 2.5rem;
    color: rgba(255, 255, 255, 0.8); /* White with transparency */
    line-height: 1;
    margin-bottom: 15px;
}

.testimonial-text {
    color: white; /* White text for contrast */
    font-size: 1.1rem;
    line-height: 1.6;
    flex-grow: 1;
    margin-bottom: 25px;
    text-align: center;
}

.testimonial-author {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 10px;
    text-align: center;
}

.testimonial-author-avatar {
    width: 60px;
    height: 60px;
    background: rgba(255, 255, 255, 0.2); /* Light white background */
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.4rem;
    font-weight: bold;
    color: white;
    flex-shrink: 0;
    border: 2px solid rgba(255, 255, 255, 0.3);
}

.testimonial-author-info h4 {
    color: white; /* White text */
    font-size: 1.2rem;
    margin: 0 0 5px 0;
    font-weight: 600;
}

.testimonial-author-info p {
    color: rgba(255, 255, 255, 0.9); /* Slightly transparent white */
    font-size: 0.9rem;
    margin: 0;
}

/* Swiper navigation for testimonials */
.testimonials-swiper .swiper-pagination {
    bottom: 10px !important;
}

.testimonials-swiper .swiper-pagination-bullet {
    background: rgba(255, 255, 255, 0.5);
    width: 10px;
    height: 10px;
    margin: 0 5px !important;
    opacity: 0.7;
}

.testimonials-swiper .swiper-pagination-bullet-active {
    background: white;
    opacity: 1;
    transform: scale(1.2);
}

.testimonials-swiper .swiper-button-next,
.testimonials-swiper .swiper-button-prev {
    color: var(--primary);
    background: white;
    width: 45px;
    height: 45px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.15);
    border: 2px solid var(--primary);
}

.testimonials-swiper .swiper-button-next:after,
.testimonials-swiper .swiper-button-prev:after {
    font-size: 1.2rem;
    font-weight: bold;
    color: var(--primary);
}

.testimonials-swiper .swiper-button-next:hover,
.testimonials-swiper .swiper-button-prev:hover {
    background: var(--primary);
    color: white;
    transform: scale(1.1);
}

.testimonials-swiper .swiper-button-next:hover:after,
.testimonials-swiper .swiper-button-prev:hover:after {
    color: white;
}

/* ==================== UPDATED CTA SECTION STYLES ==================== */
.free-trial-section {
    width: 100%;
    padding: 80px 20px;
    background: linear-gradient(135deg, var(--primary), var(--secondary));
    color: white;
    text-align: center;
    margin-bottom: 15px !important;
}

.trial-content {
    max-width: 1200px;
    margin: 0 auto;
}

.trial-title {
    font-size: 2.2rem;
    margin-bottom: 20px;
    color: white;
    font-weight: 700;
}

.trial-subtitle {
    font-size: 1.2rem;
    margin-bottom: 10px;
    opacity: 0.9;
}

.trial-highlight {
    margin-bottom: 40px;
}

.trial-highlight-text {
    font-size: 1rem;
    opacity: 0.8;
}

/* 3-Button CTA Container */
.trial-cta-buttons {
    display: flex;
    gap: 20px;
    justify-content: center;
    align-items: center;
    margin-top: 40px;
    flex-wrap: wrap;
}

/* CTA Button Styles */
.cta-button {
    padding: 15px 30px;
    border-radius: 8px;
    font-weight: 600;
    font-size: 1.1rem;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    min-width: 200px;
    transition: all 0.3s ease;
    border: 2px solid transparent;
    cursor: pointer;
}

.cta-button-primary {
    background: #e67e22;
    color: white;
    border-color: #e67e22;
}

.cta-button-primary:hover {
    background: #d35400;
    border-color: #d35400;
    transform: translateY(-3px);
    box-shadow: 0 10px 20px rgba(230, 126, 34, 0.3);
}

.cta-button-outline {
    background: transparent;
    color: white;
    border-color: white;
}

.cta-button-outline:hover {
    background: white;
    color: var(--primary);
    transform: translateY(-3px);
    box-shadow: 0 10px 20px rgba(255, 255, 255, 0.2);
}

.cta-button:disabled {
    opacity: 0.6;
    cursor: not-allowed;
    transform: none !important;
    box-shadow: none !important;
}

/* ==================== UPDATED ENHANCED GALLERY STYLES ==================== */
/* Enhanced Gallery Styles */
.gallery-stats {
    display: flex;
    justify-content: center;
    gap: 15px;
    margin-bottom: 25px;
    flex-wrap: wrap;
}

.stat-badge {
    background: linear-gradient(135deg, #f8fafc, #e2e8f0);
    border: 2px solid #e2e8f0;
    border-radius: 10px;
    padding: 10px 15px;
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 0.9rem;
    font-weight: 600;
    color: #1e293b;
}

.stat-badge i {
    font-size: 1.1rem;
}

.featured-icon { color: #f59e0b; }
.regular-icon { color: #3b82f6; }
.rotate-icon { color: #10b981; }

.gallery-image {
    width: 100%;
    height: 200px;
    object-fit: cover;
    border-radius: 8px;
    transition: transform 0.3s ease;
}

.gallery-slide-container:hover .gallery-image {
    transform: scale(1.05);
}

.gallery-slide-container {
    position: relative;
    border-radius: 8px;
    overflow: hidden;
    height: 100%;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    background: #f8f9fa;
}

.featured-badge {
    background: linear-gradient(135deg, rgba(245, 158, 11, 0.95), rgba(217, 119, 6, 0.95));
}

.regular-badge {
    background: linear-gradient(135deg, rgba(59, 130, 246, 0.95), rgba(29, 78, 216, 0.95));
}

.fallback-badge {
    background: linear-gradient(135deg, rgba(139, 92, 246, 0.95), rgba(124, 58, 237, 0.95));
}

.hostel-badge-sm {
    position: absolute;
    top: 8px;
    right: 8px;
    color: white;
    padding: 3px 6px;
    border-radius: 4px;
    font-size: 0.7rem;
    font-weight: 500;
    z-index: 10;
    backdrop-filter: blur(4px);
    display: flex;
    align-items: center;
    gap: 3px;
    max-width: 120px;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.hostel-badge-sm i {
    font-size: 0.6rem;
    flex-shrink: 0;
}

.room-badge {
    position: absolute;
    top: 8px;
    left: 8px;
    background: rgba(34, 197, 94, 0.9);
    color: white;
    padding: 3px 6px;
    border-radius: 4px;
    font-size: 0.7rem;
    font-weight: 500;
    z-index: 10;
    backdrop-filter: blur(4px);
    display: flex;
    align-items: center;
    gap: 3px;
}

.city-badge {
    position: absolute;
    bottom: 40px;
    left: 8px;
    background: rgba(0, 0, 0, 0.7);
    color: white;
    padding: 4px 8px;
    border-radius: 4px;
    font-size: 0.7rem;
    font-weight: 500;
    z-index: 10;
    display: flex;
    align-items: center;
    gap: 4px;
    max-width: 120px;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.image-caption {
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    background: linear-gradient(transparent, rgba(0, 0, 0, 0.8));
    color: white;
    padding: 10px 8px;
    font-size: 0.75rem;
    z-index: 9;
}

.gallery-info {
    background: #f0f9ff;
    border: 1px solid #bae6fd;
    border-radius: 10px;
    padding: 15px;
    margin: 20px 0;
    font-size: 0.9rem;
    color: #0369a1;
}

.gallery-info i {
    color: #0ea5e9;
    margin-right: 8px;
}

.gallery-swiper {
    padding: 10px 0 40px;
}

.gallery-next, .gallery-prev {
    color: #1e3a8a;
    background: white;
    width: 40px;
    height: 40px;
    border-radius: 50%;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
}

.gallery-next:after, .gallery-prev:after {
    font-size: 1.2rem;
}

.swiper-pagination-bullet {
    background: #94a3b8;
}

.swiper-pagination-bullet-active {
    background: #1e3a8a;
}

.rotation-info {
    text-align: center;
    font-size: 0.85rem;
    color: #64748b;
    margin-top: 10px;
}

.swiper-slide {
    height: auto;
}

.empty-gallery {
    text-align: center;
    padding: 3rem;
    background: #f8f9fa;
    border-radius: 10px;
    border: 2px dashed #dee2e6;
}

.empty-gallery i {
    color: #6c757d;
    margin-bottom: 1rem;
}

.empty-gallery p {
    color: #6c757d;
    margin-bottom: 1.5rem;
}

/* ==================== RESPONSIVE DESIGN ==================== */

/* Tablet */
@media (max-width: 768px) {
    .compact-search-widget {
        margin: -4rem auto 1.5rem !important;
        padding: 1.5rem;
        width: 95%;
        border-radius: 14px;
    }
    
    .compact-form-grid {
        grid-template-columns: 1fr;
        gap: 0.8rem;
    }
    
    .compact-widget-title {
        font-size: 1.2rem;
        margin-bottom: 1.2rem;
    }
    
    .compact-form-control {
        padding: 0.7rem 0.7rem 0.7rem 2.3rem;
        height: 44px;
        font-size: 0.9rem;
    }
    
    .compact-form-group .input-with-icon i {
        left: 0.7rem;
        font-size: 0.9rem;
    }
    
    .compact-search-btn {
        padding: 0.9rem 1.5rem;
        font-size: 0.95rem;
        min-width: 180px;
    }
    
    .compact-quick-info {
        gap: 0.6rem;
    }
    
    .quick-badge {
        font-size: 0.75rem;
        padding: 0.3rem 0.6rem;
    }

    /* Mobile Hero Slider */
    .hero-slideshow {
        margin-top: 1rem;
    }
    
    .hostel-name {
        font-size: 1.1rem;
    }
    
    .hostel-location {
        font-size: 0.8rem;
    }
    
    /* Testimonials Responsive */
    .testimonials-carousel-section {
        padding: 60px 0;
    }
    
    .testimonials-carousel-title {
        font-size: 1.8rem;
    }
    
    .testimonial-carousel-card {
        padding: 30px 20px;
        min-height: 300px;
    }
    
    .testimonial-text {
        font-size: 1rem;
    }
    
    .testimonials-swiper .swiper-button-next,
    .testimonials-swiper .swiper-button-prev {
        width: 40px;
        height: 40px;
    }
    
    /* CTA Responsive */
    .free-trial-section {
        padding: 60px 20px;
    }
    
    .trial-title {
        font-size: 1.8rem;
    }
    
    .trial-subtitle {
        font-size: 1.1rem;
    }
    
    .trial-cta-buttons {
        flex-direction: column;
        gap: 15px;
    }
    
    .cta-button {
        width: 100%;
        max-width: 300px;
        padding: 12px 25px;
        font-size: 1rem;
    }
    
    /* Gallery Responsive */
    .gallery-swiper .swiper-slide {
        height: 180px;
    }
    
    .gallery-stats {
        flex-direction: column;
        align-items: center;
        gap: 10px;
    }
    
    .stat-badge {
        width: 100%;
        max-width: 300px;
        justify-content: center;
    }
}

/* Mobile */
@media (max-width: 480px) {
    .compact-search-widget {
        margin: -3rem auto 1rem !important;
        padding: 1.2rem;
        border-radius: 12px;
    }
    
    .compact-widget-title {
        font-size: 1.1rem;
        margin-bottom: 1rem;
    }
    
    .compact-form-control {
        padding: 0.6rem 0.6rem 0.6rem 2.2rem;
        height: 42px;
        font-size: 0.85rem;
    }
    
    .compact-form-group .input-with-icon i {
        left: 0.6rem;
        font-size: 0.85rem;
    }
    
    .compact-search-btn {
        padding: 0.8rem 1.2rem;
        font-size: 0.9rem;
        min-width: 160px;
        gap: 0.4rem;
    }
    
    .compact-quick-info {
        gap: 0.4rem;
        padding-top: 0.8rem;
    }
    
    .quick-badge {
        font-size: 0.7rem;
        padding: 0.25rem 0.5rem;
    }
    
    /* Testimonials Mobile */
    .testimonials-carousel-title {
        font-size: 1.6rem;
    }
    
    .testimonials-carousel-subtitle {
        font-size: 1rem;
        margin-bottom: 30px;
    }
    
    .testimonial-carousel-card {
        padding: 25px 15px;
    }
    
    /* CTA Mobile */
    .trial-title {
        font-size: 1.6rem;
    }
    
    .trial-subtitle {
        font-size: 1rem;
    }
    
    /* Gallery Mobile */
    .gallery-swiper .swiper-slide {
        height: 160px;
    }
    
    .gallery-image {
        height: 160px;
    }
}

/* Small Mobile */
@media (max-width: 360px) {
    .compact-search-widget {
        margin: -2.5rem auto 0.8rem !important;
        padding: 1rem;
    }
    
    .compact-widget-title {
        font-size: 1rem;
    }
    
    .compact-form-grid {
        gap: 0.6rem;
    }
    
    .compact-search-btn {
        padding: 0.7rem 1rem;
        font-size: 0.85rem;
        min-width: 140px;
    }
    
    .compact-quick-info {
        flex-direction: column;
        align-items: center;
        gap: 0.3rem;
    }
    
    /* Gallery Small Mobile */
    .gallery-swiper .swiper-slide {
        height: 140px;
    }
    
    .gallery-image {
        height: 140px;
    }
}

/* Animation */
@keyframes compactSlideInUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.compact-search-widget {
    animation: compactSlideInUp 0.6s ease-out;
}
</style>
@endpush

@section('content')
<!-- 🚨 HERO SECTION MOVED HERE - NO SEPARATE SECTION -->
<section class="hero">
    <video autoplay muted loop playsinline preload="metadata" class="hero-video">
        <source src="https://assets.mixkit.co/videos/preview/mixkit-student-studying-in-a-dorm-room-44475-large.mp4" type="video/mp4">
        Your browser does not support the video tag.
    </video>
    <div class="container">
        <div class="hero-content">
            <!-- Text Content - Left Side -->
            <div class="hero-text">
                <h1 class="hero-title nepali">HostelHub — तपाइँको होस्टल व्यवस्थापन अब सजिलो, द्रुत र भरपर्दो</h1>
                <p class="hero-subtitle nepali">विद्यार्थी व्यवस्थापन, कोठा आवंटन, भुक्तानी र भोजन प्रणाली—एकै प्लेटफर्मबाट चलाउनुहोस्। ७ दिन निःशुल्क।</p>
                
                <div class="hero-cta">
                    <a href="{{ route('demo') }}" class="btn btn-primary nepali">डेमो हेर्नुहोस्</a>
                    <a href="{{ route('hostels.index') }}" class="btn btn-outline nepali">सबै होस्टल हेर्नुहोस्</a>
                </div>
                
                <div class="hero-stats">
                    <div class="stat-item">
                        <div class="stat-number count-up" id="students-counter" aria-live="polite">125</div>
                        <div class="stat-label nepali">कुल विद्यार्थीहरू</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-number count-up" id="hostels-counter" aria-live="polite">24</div>
                        <div class="stat-label nepali">सहयोगी होस्टल</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-number count-up" id="cities-counter" aria-live="polite">5</div>
                        <div class="stat-label nepali">शहरहरू</div>
                    </div>
                </div>
            </div>

            <!-- Image Slider - Right Side -->
            <div class="hero-slideshow">
                <div class="swiper hero-slider">
                    <div class="swiper-wrapper">
                        @if(isset($featuredHostels) && count($featuredHostels) > 0)
                            @foreach($featuredHostels as $hostel)
                            <div class="swiper-slide">
                                <a href="{{ $hostel['public_url'] ?? route('hostels.show', $hostel['slug']) }}" 
                                   class="slide-link" 
                                   title="{{ $hostel['name'] }} - {{ $hostel['city'] }}">
                                    <img src="{{ $hostel['cover_image'] }}" 
                                         alt="{{ $hostel['name'] }}" 
                                         loading="lazy"
                                         onerror="this.onerror=null;this.src='{{ asset('images/default-hostel.jpg') }}'">
                                    <div class="slide-overlay">
                                        <div class="slide-content">
                                            <h4 class="hostel-name">{{ $hostel['name'] }}</h4>
                                            <p class="hostel-location">
                                                <i class="fas fa-map-marker-alt"></i>
                                                {{ $hostel['city'] }}
                                            </p>
                                            <span class="view-hostel-btn">होस्टल हेर्नुहोस्</span>
                                        </div>
                                    </div>
                                </a>
                            </div>
                            @endforeach
                        @else
                            <!-- 🚨 FALLBACK SLIDES -->
                            <div class="swiper-slide">
                                <img src="https://images.unsplash.com/photo-1564013799919-ab600027ffc6?w=800&h=450&fit=crop" 
                                     alt="Comfortable Hostel Rooms" 
                                     loading="lazy">
                                <div class="slide-overlay">
                                    <div class="slide-content">
                                        <h4 class="hostel-name">HostelHub होस्टल</h4>
                                        <p class="hostel-location">
                                            <i class="fas fa-map-marker-alt"></i>
                                            काठमाडौं
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="swiper-slide">
                                <img src="https://images.unsplash.com/photo-1586023492125-27b2c045efd7?w=800&h=450&fit=crop" 
                                     alt="Modern Hostel Facilities" 
                                     loading="lazy">
                                <div class="slide-overlay">
                                    <div class="slide-content">
                                        <h4 class="hostel-name">आधुनिक होस्टल</h4>
                                        <p class="hostel-location">
                                            <i class="fas fa-map-marker-alt"></i>
                                            पोखरा
                                        </p>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                    <!-- Navigation arrows -->
                    <div class="swiper-button-next"></div>
                    <div class="swiper-button-prev"></div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- COMPACT SEARCH WIDGET - UPDATED VERSION -->
<div class="container">
    <div class="compact-search-widget">
        <h3 class="compact-widget-title nepali">🔍 कोठा खोजी / रिजर्भ गर्नुहोस्</h3>
        <form class="compact-widget-form" id="booking-form" action="{{ route('search') }}" method="GET">
            
            <div class="compact-form-grid">
                <div class="compact-form-group">
                    <label class="nepali" for="city">📍 स्थान</label>
                    <div class="input-with-icon">
                        <i class="fas fa-map-marker-alt"></i>
                        <select class="compact-form-control" name="city" id="city" required aria-required="true">
                            <option value="">स्थान छान्नुहोस्</option>
                            @foreach($cities as $city)
                                <option value="{{ $city }}">{{ $city }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                
                <div class="compact-form-group">
                    <label class="nepali" for="hostel_id">🏠 होस्टल</label>
                    <div class="input-with-icon">
                        <i class="fas fa-building"></i>
                        <select class="compact-form-control" name="hostel_id" id="hostel_id">
                            <option value="">सबै होस्टल</option>
                            @foreach($hostels as $hostel)
                                <option value="{{ $hostel->id }}">{{ $hostel->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                
                <div class="compact-form-group">
                    <label class="nepali" for="check_in">📅 चेक-इन</label>
                    <div class="input-with-icon">
                        <i class="fas fa-calendar-alt"></i>
                        <input type="date" class="compact-form-control" name="check_in" id="check_in" required 
                               aria-required="true" min="{{ date('Y-m-d') }}">
                    </div>
                </div>
                
                <div class="compact-form-group">
                    <label class="nepali" for="check_out">📅 चेक-आउट</label>
                    <div class="input-with-icon">
                        <i class="fas fa-calendar-check"></i>
                        <input type="date" class="compact-form-control" name="check_out" id="check_out" required 
                               aria-required="true" min="{{ date('Y-m-d', strtotime('+1 day')) }}">
                    </div>
                </div>
                
                <div class="compact-search-button">
                    <button type="submit" class="compact-search-btn nepali">
                        <i class="fas fa-search"></i>
                        कोठा खोज्नुहोस्
                    </button>
                </div>
            </div>
            
            <!-- Quick Info Badges -->
            <div class="compact-quick-info">
                <span class="quick-badge">🎓 विद्यार्थी-अनुकूल</span>
                <span class="quick-badge">🔒 सुरक्षित बुकिंग</span>
                <span class="quick-badge">💰 उचित मूल्य</span>
                <span class="quick-badge">🏠 प्रमाणित होस्टल</span>
            </div>
        </form>
    </div>
</div>

<!-- 🎨 UPDATED GALLERY SECTION - CLEANED VERSION -->
<section class="section gallery" id="gallery">
    <div class="container">
        <h2 class="section-title nepali">🎨 विभिन्न होस्टलहरूका कोठा र दृश्यहरू</h2>
        
        @if($galleryItems->count() > 0)
        <div class="gallery-swiper swiper">
            <div class="swiper-wrapper">
                @foreach($galleryItems as $item)
                <div class="swiper-slide">
                    <div class="gallery-slide-container">
                        <img src="{{ $item['thumbnail_url'] }}" 
                             alt="{{ $item['title'] }}" 
                             loading="lazy" 
                             class="gallery-image"
                             onerror="this.onerror=null;this.src='{{ asset('images/default-hostel.jpg') }}'">
                        
                        <!-- Hostel Badge -->
                        @if($item['is_room_image'])
                        <div class="hostel-badge-sm {{ $item['is_featured_hostel'] ? 'featured-badge' : 'regular-badge' }}">
                            <i class="fas {{ $item['is_featured_hostel'] ? 'fa-star' : 'fa-building' }}"></i>
                            <span class="nepali">{{ $item['hostel_name'] }}</span>
                        </div>
                        @else
                        <div class="hostel-badge-sm fallback-badge">
                            <i class="fas fa-images"></i>
                            <span class="nepali">HostelHub</span>
                        </div>
                        @endif

                        <!-- Room Badge if detected -->
                        @if($item['is_room_image'] && $item['room_number'])
                            <div class="room-badge">
                                <i class="fas fa-door-open"></i>
                                <span class="nepali">कोठा {{ $item['room_number'] }}</span>
                            </div>
                        @elseif($item['is_room_image'])
                            <div class="room-badge">
                                <i class="fas fa-bed"></i>
                                <span class="nepali">कोठा</span>
                            </div>
                        @endif

                        <!-- City/Location -->
                        @if($item['city'])
                        <div class="city-badge">
                            <i class="fas fa-map-marker-alt"></i>
                            <span class="nepali">{{ $item['city'] }}</span>
                        </div>
                        @endif

                        <!-- Caption/Title -->
                        @if($item['caption'])
                        <div class="image-caption">
                            <p class="nepali">{{ Str::limit($item['caption'], 40) }}</p>
                        </div>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
            
            <!-- Navigation -->
            <div class="swiper-button-next gallery-next"></div>
            <div class="swiper-button-prev gallery-prev"></div>
            
            <!-- Pagination -->
            <div class="swiper-pagination"></div>
        </div>
        @else
        <div class="empty-gallery">
            <i class="fas fa-images fa-3x"></i>
            <p class="nepali">अहिलेसम्म कुनै होस्टल छविहरू उपलब्ध छैनन्।</p>
            <a href="{{ route('hostels.index') }}" class="btn btn-primary nepali">
                <i class="fas fa-building"></i> होस्टलहरू हेर्नुहोस्
            </a>
        </div>
        @endif
        
        @if($galleryItems->count() > 0)
        <div class="gallery-button">
            <a href="{{ route('gallery') }}" class="view-gallery-btn nepali">
                <i class="fas fa-images"></i> पूरै ग्यालरी हेर्नुहोस्
            </a>
        </div>
        @endif
    </div>
</section>

<!-- Statistics Section -->
<section class="stats-section" id="stats">
    <div class="container">
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-users" aria-hidden="true"></i>
                </div>
                <div class="stat-count count-up" id="students-counter-stat" aria-live="polite">{{ $metrics['total_students'] ?? 125 }}</div>
                <div class="stat-description nepali">खुसी विद्यार्थीहरू</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-building" aria-hidden="true"></i>
                </div>
                <div class="stat-count count-up" id="hostels-counter-stat" aria-live="polite">{{ $metrics['total_hostels'] ?? 24 }}</div>
                <div class="stat-description nepali">सहयोगी होस्टल</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-map-marker-alt" aria-hidden="true"></i>
                </div>
                <div class="stat-count count-up" id="cities-counter-stat" aria-live="polite">{{ $cities->count() ?? 5 }}</div>
                <div class="stat-description nepali">शहरहरूमा उपलब्ध</div>
            </div>
        </div>
    </div>
</section>

<!-- Features Section -->
<section class="section features" id="features">
    <div class="container">
        <h2 class="section-title nepali">हाम्रा प्रमुख सुविधाहरू</h2>
        <p class="section-subtitle nepali">HostelHub ले प्रदान गर्ने विशेष सुविधाहरू जसले तपाईंको होस्टल व्यवस्थापनलाई सजिलो बनाउँछ</p>
        <div class="features-grid">
            <div class="feature-card" aria-labelledby="feature1-title">
                <div class="feature-icon" aria-hidden="true">
                    <i class="fas fa-users"></i>
                </div>
                <h3 id="feature1-title" class="feature-title nepali">विद्यार्थी व्यवस्थापन</h3>
                <p class="feature-desc nepali">सबै विद्यार्थी विवरण एउटै ठाउँमा प्रबन्धन गर्नुहोस्, अध्ययन स्थिति, सम्पर्क जानकारी र भुक्तानी इतिहास</p>
            </div>
            <div class="feature-card" aria-labelledby="feature2-title">
                <div class="feature-icon" aria-hidden="true">
                    <i class="fas fa-bed"></i>
                </div>
                <h3 id="feature2-title" class="feature-title nepali">कोठा उपलब्धता</h3>
                <p class="feature-desc nepali">रियल-टाइम कोठा उपलब्धता देख्नुहोस्, आवंटन गर्नुहोस् र बुकिंग प्रबन्धन गर्नुहोस्</p>
            </div>
            <div class="feature-card" aria-labelledby="feature3-title">
                <div class="feature-icon" aria-hidden="true">
                    <i class="fas fa-credit-card"></i>
                </div>
                <h3 id="feature3-title" class="feature-title nepali">भुक्तानी प्रणाली</h3>
                <p class="feature-desc nepali">स्वचालित भुक्तानी ट्र्याकिंग, बिल जनरेट गर्नुहोस्, रिमाइन्डर पठाउनुहोस् र वित्तीय विवरण हेर्नुहोस्</p>
            </div>
            <div class="feature-card" aria-labelledby="feature4-title">
                <div class="feature-icon" aria-hidden="true">
                    <i class="fas fa-utensils"></i>
                </div>
                <h3 id="feature4-title" class="feature-title nepali">भोजन व्यवस्थापन</h3>
                <p class="feature-desc nepali">मेनु योजना बनाउनुहोस्, भोजन आदेश ट्र्याक गर्नुहोस् र खानेकुराको इन्भेन्टरी प्रबन्धन गर्नुहोस्</p>
            </div>
            <div class="feature-card" aria-labelledby="feature5-title">
                <div class="feature-icon" aria-hidden="true">
                    <i class="fas fa-chart-line"></i>
                </div>
                <h3 id="feature5-title" class="feature-title nepali">विश्लेषण र रिपोर्ट</h3>
                <p class="feature-desc nepali">होस्टलको प्रदर्शन विश्लेषण गर्नुहोस्, आगामी आवश्यकताहरूको अनुमान गर्नुहोस्</p>
            </div>
            <div class="feature-card" aria-labelledby="feature6-title">
                <div class="feature-icon" aria-hidden="true">
                    <i class="fas fa-mobile-alt"></i>
                </div>
                <h3 id="feature6-title" class="feature-title nepali">मोबाइल एप्प</h3>
                <p class="feature-desc nepali">होस्टल प्रबन्धन गर्नुहोस् वा विद्यार्थीहरूले आफ्नो भुक्तानी, कोठा स्थिति र भोजन अर्डर हेर्न सक्ने</p>
            </div>
        </div>
    </div>
</section>

<!-- How It Works -->
<section class="section how-it-works" id="how-it-works">
    <div class="container">
        <h2 class="section-title nepali">HostelHub कसरी काम गर्छ?</h2>
        <p class="section-subtitle nepali">हाम्रो प्रणाली प्रयोग गर्ने सजिलो ३ चरणहरू</p>
        <div class="steps">
            <div class="step">
                <div class="step-number">1</div>
                <h3 class="step-title nepali">खाता सिर्जना गर्नुहोस्</h3>
                <p class="step-desc nepali">निःशुल्क खाताको लागि साइन अप गर्नुहोस् र आफ्नो होस्टल विवरणहरू थप्नुहोस्</p>
            </div>
            <div class="step">
                <div class="step-number">2</div>
                <h3 class="step-title nepali">व्यवस्थापन सुरु गर्नुहोस्</h3>
                <p class="step-desc nepali">विद्यार्थीहरू थप्नुहोस्, कोठा आवंटन गर्नुहोस्, र भुक्तानीहरू ट्र्याक गर्नुहोस्</p>
            </div>
            <div class="step">
                <div class="step-number">3</div>
                <h3 class="step-title nepali">विस्तार गर्नुहोस्</h3>
                <p class="step-desc nepali">हाम्रा उन्नत सुविधाहरू प्रयोग गरेर आफ्नो होस्टल व्यसायलाई बढाउनुहोस्</p>
            </div>
        </div>
    </div>
</section>

<!-- 🚀 FIX 1: TESTIMONIALS CAROUSEL - BLUE CARDS WITH WHITE TEXT -->
<section class="testimonials-carousel-section" id="testimonials">
    <div class="testimonials-carousel-container">
        <h2 class="testimonials-carousel-title nepali">ग्राहकहरूको प्रशंसापत्रहरू</h2>
        <p class="testimonials-carousel-subtitle nepali">HostelHub प्रयोग गर्ने हाम्रा ग्राहकहरूले के भन्छन्</p>
        
        <!-- Swiper Testimonials Carousel -->
        <div class="swiper testimonials-swiper">
            <div class="swiper-wrapper">
                <!-- Testimonial 1 -->
                <div class="swiper-slide">
                    <div class="testimonial-carousel-card">
                        <div class="testimonial-quote">"</div>
                        <p class="testimonial-text nepali">
                            HostelHub को कोठा बुकिंग प्रणाली धेरै सजिलो छ। मैले आफ्नो कोठा अहिले नै बुक गरेँ र प्रक्रिया धेरै छिटो थियो। होस्टलको सबै विवरण फोटो सहित थियो।
                        </p>
                        <div class="testimonial-author">
                            <div class="testimonial-author-avatar">क</div>
                            <div class="testimonial-author-info">
                                <h4>कल्पना तामाङ</h4>
                                <p>विद्यार्थी</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Testimonial 2 -->
                <div class="swiper-slide">
                    <div class="testimonial-carousel-card">
                        <div class="testimonial-quote">"</div>
                        <p class="testimonial-text nepali">
                            HostelHub ले हाम्रो होस्टल व्यवस्थापन धेरै सजिलो बनायो। विद्यार्थी व्यवस्थापन, भुक्तानी ट्र्याकिंग सबै एउटै ठाउँमा। अब सबै काम मोबाइलबाटै गर्न सक्छौं।
                        </p>
                        <div class="testimonial-author">
                            <div class="testimonial-author-avatar">र</div>
                            <div class="testimonial-author-info">
                                <h4>राम श्रेष्ठ</h4>
                                <p>होस्टल मालिक</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Testimonial 3 -->
                <div class="swiper-slide">
                    <div class="testimonial-carousel-card">
                        <div class="testimonial-quote">"</div>
                        <p class="testimonial-text nepali">
                            खानाको मेनु अग्रिम हेर्न पाउँदा धेरै राम्रो लाग्छ। होस्टलको सबै सुविधाहरूको फोटो पनि ग्यालरीमा छन्। भुक्तानी पनि सजिलो, एक पटकमै बुक गर्न सकिन्छ।
                        </p>
                        <div class="testimonial-author">
                            <div class="testimonial-author-avatar">स</div>
                            <div class="testimonial-author-info">
                                <h4>सरस्वती गौतम</h4>
                                <p>विद्यार्थी</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Navigation buttons -->
            <div class="swiper-button-next"></div>
            <div class="swiper-button-prev"></div>
            
            <!-- Pagination dots -->
            <div class="swiper-pagination"></div>
        </div>
    </div>
</section>

<!-- Pricing Section - FIXED VERSION -->
<section class="section pricing" id="pricing">
    <div class="container">
        <h2 class="section-title nepali">योजना अनुसारका मूल्यहरू</h2>
        <p class="section-subtitle nepali">तपाईंको होस्टल व्यवस्थापन आवश्यकताअनुसार उपयुक्त योजना छान्नुहोस्</p>
        
        <div class="free-trial-note">
            <p class="nepali">७ दिन निःशुल्क परीक्षण | कुनै पनि क्रेडिट कार्ड आवश्यक छैन</p>
        </div>

        <div class="pricing-grid">
            <!-- सुरुवाती Plan -->
            <div class="pricing-card">
                <div class="pricing-header">
                    <h3 class="pricing-title nepali">सुरुवाती</h3>
                    <div class="pricing-price">रु. २,९९९<span class="nepali">/महिना</span></div>
                </div>
                <ul class="pricing-features">
                    <li>
                        <i class="fas fa-check"></i>
                        <span class="feature-text nepali">५० विद्यार्थी सम्म</span>
                    </li>
                    <li>
                        <i class="fas fa-check"></i>
                        <span class="feature-text nepali">१ होस्टल सम्म</span>
                    </li>
                    <li>
                        <i class="fas fa-check"></i>
                        <span class="feature-text nepali">मूल विद्यार्थी व्यवस्थापन</span>
                    </li>
                    <li>
                        <i class="fas fa-check"></i>
                        <span class="feature-text nepali">कोठा आवंटन</span>
                    </li>
                    <li>
                        <i class="fas fa-check"></i>
                        <span class="feature-text nepali">बेसिक अग्रिम कोठा बुकिंग (manual approval)</span>
                    </li>
                    <li>
                        <i class="fas fa-check"></i>
                        <span class="feature-text nepali">भुक्तानी ट्र्याकिंग</span>
                    </li>
                </ul>
                <div class="pricing-button">
                    @auth
                        @php
                            $organizationId = session('current_organization_id');
                            $currentSubscription = null;
                            $currentPlan = null;
                            $isTrial = false;
                            
                            if ($organizationId) {
                                $organization = \App\Models\Organization::with('subscription.plan')->find($organizationId);
                                $currentSubscription = $organization->subscription ?? null;
                                $currentPlan = $currentSubscription->plan ?? null;
                                $isTrial = $currentSubscription && $currentSubscription->status == 'trial';
                            }
                            
                            $isStarterCurrent = $currentPlan && $currentPlan->slug == 'starter';
                        @endphp
                        
                        @if($isTrial)
                            <button class="pricing-btn pricing-btn-outline nepali" disabled>
                                परीक्षण अवधिमा
                            </button>
                        @elseif($isStarterCurrent)
                            <button class="pricing-btn pricing-btn-outline nepali" disabled>
                                सक्रिय योजना
                            </button>
                        @else
                            <form action="{{ route('subscription.upgrade') }}" method="POST" style="display: inline;">
                                @csrf
                                <input type="hidden" name="plan" value="starter">
                                <button type="submit" class="pricing-btn pricing-btn-outline nepali">
                                    योजना छान्नुहोस्
                                </button>
                            </form>
                        @endif
                    @else
                        <a href="{{ route('register.organization', ['plan' => 'starter']) }}" 
                           class="pricing-btn pricing-btn-outline nepali">
                            योजना छान्नुहोस्
                        </a>
                    @endauth
                </div>
            </div>

            <!-- प्रो Plan -->
            <div class="pricing-card popular">
                <div class="popular-badge nepali">लोकप्रिय</div>
                <div class="pricing-header">
                    <h3 class="pricing-title nepali">प्रो</h3>
                    <div class="pricing-price">रु. ४,९९९<span class="nepali">/महिना</span></div>
                </div>
                <ul class="pricing-features">
                    <li>
                        <i class="fas fa-check"></i>
                        <span class="feature-text nepali">२०० विद्यार्थी सम्म</span>
                    </li>
                    <li>
                        <i class="fas fa-check"></i>
                        <span class="feature-text nepali">१ होस्टल सम्म</span>
                    </li>
                    <li>
                        <i class="fas fa-check"></i>
                        <span class="feature-text nepali">पूर्ण विद्यार्थी व्यवस्थापन</span>
                    </li>
                    <li>
                        <i class="fas fa-check"></i>
                        <span class="feature-text nepali">अग्रिम कोठा बुकिंग (auto-confirm, notifications)</span>
                    </li>
                    <li>
                        <i class="fas fa-check"></i>
                        <span class="feature-text nepali">भुक्तानी ट्र्याकिंग</span>
                    </li>
                    <li>
                        <i class="fas fa-check"></i>
                        <span class="feature-text nepali">मोबाइल एप्प</span>
                    </li>
                </ul>
                <div class="pricing-button">
                    @auth
                        @php
                            $organizationId = session('current_organization_id');
                            $currentSubscription = null;
                            $currentPlan = null;
                            $isTrial = false;
                            
                            if ($organizationId) {
                                $organization = \App\Models\Organization::with('subscription.plan')->find($organizationId);
                                $currentSubscription = $organization->subscription ?? null;
                                $currentPlan = $currentSubscription->plan ?? null;
                                $isTrial = $currentSubscription && $currentSubscription->status == 'trial';
                            }
                            
                            $isProCurrent = $currentPlan && $currentPlan->slug == 'pro';
                        @endphp
                        
                        @if($isTrial)
                            <button class="pricing-btn pricing-btn-primary nepali" disabled>
                                परीक्षण अवधिमा
                            </button>
                        @elseif($isProCurrent)
                            <button class="pricing-btn pricing-btn-primary nepali" disabled>
                                सक्रिय योजना
                            </button>
                        @else
                            <form action="{{ route('subscription.upgrade') }}" method="POST" style="display: inline;">
                                @csrf
                                <input type="hidden" name="plan" value="pro">
                                <button type="submit" class="pricing-btn pricing-btn-primary nepali">
                                    योजना छान्नुहोस्
                                </button>
                            </form>
                        @endif
                    @else
                        <a href="{{ route('register.organization', ['plan' => 'pro']) }}" 
                           class="pricing-btn pricing-btn-primary nepali">
                            योजना छान्नुहोस्
                        </a>
                    @endauth
                </div>
            </div>

            <!-- एन्टरप्राइज Plan -->
            <div class="pricing-card">
                <div class="pricing-header">
                    <h3 class="pricing-title nepali">एन्टरप्राइज</h3>
                    <div class="pricing-price">रु. ८,९९९<span class="nepali">/महिना</span></div>
                </div>
                <ul class="pricing-features">
                    <li>
                        <i class="fas fa-check"></i>
                        <span class="feature-text nepali">असीमित विद्यार्थी</span>
                    </li>
                    <li>
                        <i class="fas fa-check"></i>
                        <span class="feature-text nepali">बहु-होस्टल व्यवस्थापन (५ होस्टल सम्म)</span>
                    </li>
                    <li>
                        <i class="fas fa-check"></i>
                        <span class="feature-text nepali">पूर्ण विद्यार्थी व्यवस्थापन</span>
                    </li>
                    <li>
                        <i class="fas fa-check"></i>
                        <span class="feature-text nepali">अग्रिम कोठा बुकिंग (auto-confirm)</span>
                    </li>
                    <li>
                        <i class="fas fa-check"></i>
                        <span class="feature-text nepali">कस्टम भुक्तानी प्रणाली</span>
                    </li>
                    <li>
                        <i class="fas fa-check"></i>
                        <span class="feature-text nepali">२४/७ समर्थन</span>
                    </li>
                    <li>
                        <i class="fas fa-info-circle"></i>
                        <span class="feature-text nepali enterprise-note">अतिरिक्त होस्टल थप्न सकिन्छ: रु. १,०००/महिना प्रति अतिरिक्त होस्टल</span>
                    </li>
                </ul>
                <div class="pricing-button">
                    @auth
                        @php
                            $organizationId = session('current_organization_id');
                            $currentSubscription = null;
                            $currentPlan = null;
                            $isTrial = false;
                            
                            if ($organizationId) {
                                $organization = \App\Models\Organization::with('subscription.plan')->find($organizationId);
                                $currentSubscription = $organization->subscription ?? null;
                                $currentPlan = $currentSubscription->plan ?? null;
                                $isTrial = $currentSubscription && $currentSubscription->status == 'trial';
                            }
                            
                            $isEnterpriseCurrent = $currentPlan && $currentPlan->slug == 'enterprise';
                        @endphp
                        
                        @if($isTrial)
                            <button class="pricing-btn pricing-btn-outline nepali" disabled>
                                परीक्षण अवधिमा
                            </button>
                        @elseif($isEnterpriseCurrent)
                            <button class="pricing-btn pricing-btn-outline nepali" disabled>
                                सक्रिय योजना
                            </button>
                        @else
                            <form action="{{ route('subscription.upgrade') }}" method="POST" style="display: inline;">
                                @csrf
                                <input type="hidden" name="plan" value="enterprise">
                                <button type="submit" class="pricing-btn pricing-btn-outline nepali">
                                    योजना छान्नुहोस्
                                </button>
                            </form>
                        @endif
                    @else
                        <a href="{{ route('register.organization', ['plan' => 'enterprise']) }}" 
                           class="pricing-btn pricing-btn-outline nepali">
                            योजना छान्नुहोस्
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </div>
</section>

<!-- 🚀 FIX 2: BOTTOM CTA WITH 3 PROFESSIONAL BUTTONS -->
<section class="free-trial-section">
    <div class="container">
        <div class="trial-content">
            <h2 class="trial-title nepali">७ दिनको निःशुल्क परीक्षण</h2>
            <p class="trial-subtitle nepali">हाम्रो प्रणालीको सबै सुविधाहरू निःशुल्क परीक्षण गर्नुहोस्, कुनै पनि बाध्यता बिना</p>
            <div class="trial-highlight">
                <p class="trial-highlight-text nepali">७ दिन निःशुल्क • कुनै क्रेडिट कार्ड आवश्यक छैन • कुनै पनि प्रतिबद्धता छैन !</p>
            </div>
            
            <!-- 3-Button Professional CTA -->
            <div class="trial-cta-buttons">
                <!-- Button 1: FREE TRIAL (Primary) -->
                @auth
                    @php
                        $organizationId = session('current_organization_id');
                        $hasSubscription = false;
                        
                        if ($organizationId) {
                            $organization = \App\Models\Organization::with('subscription')->find($organizationId);
                            $hasSubscription = $organization->subscription ?? false;
                        }
                    @endphp
                    
                    @if($hasSubscription)
                        <button class="cta-button cta-button-primary nepali" disabled>
                            <i class="fas fa-rocket"></i> तपाईंसँग पहिले नै सदस्यता छ
                        </button>
                    @else
                        <form action="{{ route('subscription.start-trial') }}" method="POST" style="display: inline;">
                            @csrf
                            <button type="submit" class="cta-button cta-button-primary nepali">
                                <i class="fas fa-rocket"></i> निःशुल्क साइन अप गर्नुहोस्
                            </button>
                        </form>
                    @endif
                @else
                    <a href="{{ route('register.organization', ['plan' => 'starter']) }}" 
                       class="cta-button cta-button-primary nepali">
                        <i class="fas fa-rocket"></i> निःशुल्क साइन अप गर्नुहोस्
                    </a>
                @endauth
                
                <!-- Button 2: DEMO (Outline) -->
                <a href="{{ route('demo') }}" class="cta-button cta-button-outline nepali">
                    <i class="fas fa-play-circle"></i> डेमो हेर्नुहोस्
                </a>
                
                <!-- Button 3: TESTIMONIALS (Outline) - Fixed to link to separate testimonials page -->
                @php
                    // Check if testimonials route exists, otherwise use direct URL
                    $testimonialsRoute = Route::has('testimonials') ? route('testimonials') : 
                                        (Route::has('testimonials.index') ? route('testimonials.index') : 
                                        (Route::has('frontend.testimonials') ? route('frontend.testimonials') : url('/testimonials')));
                @endphp
                <a href="{{ $testimonialsRoute }}" class="cta-button cta-button-outline nepali">
                    <i class="fas fa-comments"></i> प्रशंसापत्रहरू हेर्नुहोस्
                </a>
            </div>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/js/all.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    console.log('🚨 HERO FIXED - DIRECT IN CONTENT');
    
    // Initialize Hero Swiper
    try {
        if (typeof Swiper !== 'undefined') {
            const heroSwiper = new Swiper('.hero-slider', {
                loop: true,
                navigation: {
                    nextEl: '.swiper-button-next',
                    prevEl: '.swiper-button-prev',
                },
                autoplay: {
                    delay: 5000,
                },
            });
            console.log('✅ Hero Swiper initialized');
        }
    } catch (e) {
        console.log('Swiper error:', e);
    }

    // 🚀 FIX 3: INITIALIZE TESTIMONIALS CAROUSEL
    try {
        if (typeof Swiper !== 'undefined') {
            const testimonialsSwiper = new Swiper('.testimonials-swiper', {
                loop: true,
                slidesPerView: 1,
                spaceBetween: 30,
                autoplay: {
                    delay: 5000,
                    disableOnInteraction: false,
                },
                pagination: {
                    el: '.swiper-pagination',
                    clickable: true,
                },
                navigation: {
                    nextEl: '.swiper-button-next',
                    prevEl: '.swiper-button-prev',
                },
                breakpoints: {
                    768: {
                        slidesPerView: 1,
                        spaceBetween: 30,
                    },
                    1024: {
                        slidesPerView: 1,
                        spaceBetween: 40,
                    }
                }
            });
            console.log('✅ Testimonials Swiper initialized with auto-slide');
        }
    } catch (e) {
        console.log('Testimonials Swiper error:', e);
    }

    // 🎨 FIXED: Gallery Swiper with conditional loop to prevent back-and-forth movement
    try {
        if (typeof Swiper !== 'undefined') {
            const gallerySwiperEl = document.querySelector('.gallery-swiper');
            if (gallerySwiperEl) {
                const slides = gallerySwiperEl.querySelectorAll('.swiper-slide');
                const totalSlides = slides.length;
                
                // ✅ FIX: Only enable loop if there are MORE THAN 3 slides
                // This prevents the back-and-forth movement when only 2-3 slides
                const shouldLoop = totalSlides > 3;
                
                const gallerySwiper = new Swiper('.gallery-swiper', {
                    slidesPerView: 2,
                    spaceBetween: 15,
                    loop: shouldLoop,
                    autoplay: shouldLoop ? {
                        delay: 3000,
                        disableOnInteraction: false,
                    } : false,
                    pagination: {
                        el: '.swiper-pagination',
                        clickable: true,
                    },
                    navigation: {
                        nextEl: '.gallery-next',
                        prevEl: '.gallery-prev',
                    },
                    breakpoints: {
                        640: {
                            slidesPerView: Math.min(3, totalSlides),
                            spaceBetween: 15,
                        },
                        768: {
                            slidesPerView: Math.min(4, totalSlides),
                            spaceBetween: 15,
                        },
                        1024: {
                            slidesPerView: Math.min(4, totalSlides),
                            spaceBetween: 20,
                        },
                    },
                    // ✅ Additional fix: Prevent duplicate slides for small slide counts
                    loopAdditionalSlides: shouldLoop ? 1 : 0,
                    loopFillGroupWithBlank: shouldLoop
                });
                
                console.log('✅ Gallery Swiper FIXED: slides=', totalSlides, 'loop=', shouldLoop);
                
                // ✅ If slides are very few (2-3), hide navigation arrows
                if (totalSlides <= 3) {
                    const nextBtn = document.querySelector('.gallery-next');
                    const prevBtn = document.querySelector('.gallery-prev');
                    if (nextBtn) nextBtn.style.display = 'none';
                    if (prevBtn) prevBtn.style.display = 'none';
                }
            }
        }
    } catch (e) {
        console.log('Gallery Swiper error:', e);
    }

    // 🚨 UPDATED: BETTER SEARCH FORM VALIDATION
    const searchForm = document.getElementById('booking-form');
    
    if (searchForm) {
        searchForm.addEventListener('submit', function(e) {
            const city = document.getElementById('city').value;
            const checkIn = document.getElementById('check_in').value;
            const checkOut = document.getElementById('check_out').value;
            
            console.log('🔍 Form submission check:', { city, checkIn, checkOut });
            
            // 🚨 ONLY validate if city is empty, don't prevent for valid cases
            if (!city) {
                e.preventDefault();
                alert('कृपया स्थान (शहर) छान्नुहोस्।');
                return false;
            }
            
            // 🚨 REMOVED strict validation for dates to allow form submission
            // Let the server handle date validation
            
            console.log('✅ Form validation passed - submitting to server');
        });
    }
    
    // Dynamic hostel dropdown based on city
    const citySelect = document.getElementById('city');
    const hostelSelect = document.getElementById('hostel_id');
    
    if (citySelect && hostelSelect) {
        citySelect.addEventListener('change', function() {
            const city = this.value;
            
            // Reset hostel dropdown
            hostelSelect.innerHTML = '<option value="">सबै होस्टल</option>';
            
            if (city) {
                console.log('City selected:', city);
                // AJAX call can be added here later
            }
        });
    }

    // 🚨 DEBUG: Check if routes are working
    console.log('🔍 Search Route:', '{{ route("search") }}');
    console.log('🏠 All Hostels Route:', '{{ route("hostels.index") }}');

    // Handle plan form submissions
    const planForms = document.querySelectorAll('.pricing-button form');
    
    planForms.forEach(form => {
        form.addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const button = this.querySelector('button[type="submit"]');
            const originalText = button.textContent;
            
            // Show loading state
            button.classList.add('loading');
            button.disabled = true;
            
            try {
                const formData = new FormData(this);
                
                const response = await fetch(this.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                });
                
                const data = await response.json();
                
                if (data.success) {
                    if (data.redirect) {
                        window.location.href = data.redirect;
                    } else {
                        // Show success message
                        alert(data.message || 'योजना सफलतापूर्वक अपग्रेड गरियो');
                        window.location.reload();
                    }
                } else {
                    // Show error message from server
                    throw new Error(data.message || 'अज्ञात त्रुटि');
                }
            } catch (error) {
                // Show proper error message
                alert('त्रुटि: ' + error.message);
                button.classList.remove('loading');
                button.textContent = originalText;
                button.disabled = false;
            }
        });
    });

    // Handle trial form submission
    const trialForm = document.querySelector('.free-trial-section form');
    if (trialForm) {
        trialForm.addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const button = this.querySelector('button[type="submit"]');
            const originalText = button.textContent;
            
            // Show loading state
            button.classList.add('loading');
            button.disabled = true;
            
            try {
                const formData = new FormData(this);
                
                const response = await fetch(this.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                });
                
                const data = await response.json();
                
                if (data.success) {
                    if (data.redirect) {
                        window.location.href = data.redirect;
                    } else {
                        // Show success message
                        alert(data.message || 'निःशुल्क परीक्षण सफलतापूर्वक सुरु गरियो');
                        window.location.reload();
                    }
                } else {
                    throw new Error(data.message || 'अज्ञात त्रुटि');
                }
            } catch (error) {
                alert('त्रुटि: ' + error.message);
                button.classList.remove('loading');
                button.textContent = originalText;
                button.disabled = false;
            }
        });
    }

    // Show success/error messages from session
    @if(session('success'))
        alert('{{ session('success') }}');
    @endif

    @if(session('error'))
        alert('{{ session('error') }}');
    @endif
});
</script>

@vite(['resources/js/home.js'])
@endpush