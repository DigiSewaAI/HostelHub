@extends('layouts.frontend')

@section('page-title', 'ग्यालरी - HostelHub')

@push('styles')
@vite(['resources/css/gallery.css'])
<style>
/* 🚨 CRITICAL: Remove all extra spacing */
body .gallery-page-wrapper,
body .gallery-content-wrapper {
    margin: 0 !important;
    padding: 0 !important;
}

/* ✅ UPDATED: GALLERY HERO SECTION - CRYSTAL CLEAR BACKGROUND */
.gallery-hero-section {
    position: relative;
    min-height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
    overflow: hidden;
    background: #0a1a44; /* ✅ Dark blue base color for fallback */
    color: white;
    margin: 0 !important;
    padding: 0 !important;
}

/* ✅ FIXED: REMOVED BLUE OVERLAY - NATURAL CLEAR BACKGROUND IMAGE */
.gallery-hero-bg {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-image: url('https://images.unsplash.com/photo-1541339907198-e08756dedf3f?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2070&q=80');
    background-size: cover;
    background-position: center 65%;
    background-repeat: no-repeat;
    opacity: 0.9; /* ✅ High opacity for clear image */
    z-index: 1;
    filter: brightness(1.1) contrast(1.1) saturate(1.05); /* ✅ Natural enhancement */
}

/* ✅ UPDATED: Hero Content - Centered */
.gallery-hero-content {
    position: relative;
    z-index: 2;
    text-align: center;
    padding: 2rem;
    max-width: 900px;
    width: 90%;
    margin-top: 0;
}

/* ✅ FIXED: Main Title - PURE BRIGHT WHITE */
.gallery-hero-title {
    font-size: 4rem;
    font-weight: 900;
    margin-bottom: 1.5rem;
    text-shadow: 
        0 2px 10px rgba(0, 0, 0, 0.8),
        0 4px 20px rgba(0, 0, 0, 0.6),
        0 0 30px rgba(255, 255, 255, 0.3);
    line-height: 1.1;
    color: #ffffff !important; /* ✅ Pure white */
    letter-spacing: 1px;
    text-transform: uppercase;
    background: none !important; /* ✅ Remove all backgrounds */
    -webkit-text-fill-color: #ffffff !important;
    background-clip: initial !important;
}

/* ✅ IMPROVED: Subtitle - Better Readability */
.gallery-hero-subtitle {
    font-size: 1.5rem;
    margin-bottom: 3rem;
    opacity: 0.95;
    line-height: 1.6;
    font-weight: 400;
    text-shadow: 0 2px 15px rgba(0, 0, 0, 0.8);
    color: rgba(255, 255, 255, 0.95);
    background: rgba(0, 0, 0, 0.4); /* ✅ Dark background for readability */
    padding: 1.2rem 2.5rem;
    border-radius: 15px;
    backdrop-filter: blur(8px);
    border: 1px solid rgba(255, 255, 255, 0.1);
    max-width: 800px;
    margin-left: auto;
    margin-right: auto;
}

/* ✅ REMOVED: Stats Grid - As requested */
.gallery-hero-stats {
    display: none !important;
}

/* ✅ MEAL GALLERY BUTTON - HERO SECTION */
.gallery-hero-button {
    display: inline-flex;
    align-items: center;
    gap: 0.75rem;
    background: linear-gradient(135deg, #FF6B35, #FF8B3D);
    color: white;
    font-weight: 800;
    padding: 1.3rem 3.5rem;
    border-radius: 50px;
    text-decoration: none;
    font-size: 1.3rem;
    transition: all 0.3s ease;
    box-shadow: 
        0 15px 35px rgba(255, 107, 53, 0.5),
        0 0 20px rgba(255, 255, 255, 0.2) inset;
    margin-top: 1.5rem;
    border: none;
    cursor: pointer;
    position: relative;
    overflow: hidden;
    z-index: 1;
}

.gallery-hero-button::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.4), transparent);
    transition: left 0.7s ease;
    z-index: -1;
}

.gallery-hero-button:hover {
    transform: translateY(-8px) scale(1.05);
    box-shadow: 
        0 25px 55px rgba(255, 107, 53, 0.8),
        0 0 30px rgba(255, 255, 255, 0.3) inset;
    color: white;
}

.gallery-hero-button:hover::before {
    left: 100%;
}

/* ✅ ADDITIONAL BUTTON FOR MEAL GALLERY */
.gallery-meal-button {
    display: inline-flex;
    align-items: center;
    gap: 0.75rem;
    background: linear-gradient(135deg, #10B981, #34D399);
    color: white;
    font-weight: 800;
    padding: 1.3rem 3.5rem;
    border-radius: 50px;
    text-decoration: none;
    font-size: 1.3rem;
    transition: all 0.3s ease;
    box-shadow: 
        0 15px 35px rgba(16, 185, 129, 0.5),
        0 0 20px rgba(255, 255, 255, 0.2) inset;
    margin-top: 1.5rem;
    margin-left: 1rem;
    border: none;
    cursor: pointer;
    position: relative;
    overflow: hidden;
    z-index: 1;
}

.gallery-meal-button::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.4), transparent);
    transition: left 0.7s ease;
    z-index: -1;
}

.gallery-meal-button:hover {
    transform: translateY(-8px) scale(1.05);
    box-shadow: 
        0 25px 55px rgba(16, 185, 129, 0.8),
        0 0 30px rgba(255, 255, 255, 0.3) inset;
    color: white;
}

.gallery-meal-button:hover::before {
    left: 100%;
}

/* ✅ HERO BUTTONS CONTAINER */
.gallery-hero-buttons {
    display: flex;
    gap: 1.5rem;
    justify-content: center;
    flex-wrap: wrap;
    margin-top: 2rem;
}

/* ✅ UPDATED: Tabs Navigation */
.gallery-tabs-navigation {
    position: absolute;
    bottom: 60px;
    left: 0;
    right: 0;
    background: rgba(10, 26, 68, 0.85); /* ✅ Dark blue matching base color */
    padding: 1.5rem;
    z-index: 10;
    backdrop-filter: blur(15px);
    border-top: 1px solid rgba(255, 255, 255, 0.15);
    border-bottom: 1px solid rgba(255, 255, 255, 0.15);
}

.gallery-tabs-container {
    max-width: 1200px;
    margin: 0 auto;
    width: 95%;
}

.gallery-tabs {
    display: flex;
    gap: 1rem;
    justify-content: center;
    flex-wrap: wrap;
}

/* 🚨 NEW: MEAL GALLERY TAB BUTTON */
.meal-tab-btn {
    padding: 1.2rem 2.5rem;
    background: rgba(16, 185, 129, 0.7); /* Green color for meal gallery */
    backdrop-filter: blur(20px);
    border: 2px solid rgba(255, 255, 255, 0.3);
    border-radius: 50px;
    cursor: pointer;
    transition: all 0.3s ease;
    font-weight: 700;
    color: white;
    font-size: 1.1rem;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 0.75rem;
    min-width: 200px;
    justify-content: center;
    margin-left: 1rem;
}

.meal-tab-btn:hover {
    background: rgba(16, 185, 129, 0.9);
    border-color: rgba(255, 255, 255, 0.5);
    transform: translateY(-5px);
    box-shadow: 0 15px 35px rgba(16, 185, 129, 0.3);
}

.meal-tab-btn.active {
    background: rgba(16, 185, 129, 0.95);
    color: white;
    border-color: rgba(255, 255, 255, 0.8);
    box-shadow: 
        0 15px 35px rgba(16, 185, 129, 0.4),
        0 0 15px rgba(255, 255, 255, 0.2) inset;
}

.tab-btn {
    padding: 1.2rem 2.5rem;
    background: rgba(255, 255, 255, 0.15);
    backdrop-filter: blur(20px);
    border: 2px solid rgba(255, 255, 255, 0.3);
    border-radius: 50px;
    cursor: pointer;
    transition: all 0.3s ease;
    font-weight: 700;
    color: white;
    font-size: 1.1rem;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 0.75rem;
    min-width: 200px;
    justify-content: center;
}

.tab-btn:hover {
    background: rgba(255, 255, 255, 0.25);
    border-color: rgba(255, 255, 255, 0.5);
    transform: translateY(-5px);
    box-shadow: 0 15px 35px rgba(255, 255, 255, 0.15);
}

.tab-btn.active {
    background: rgba(255, 255, 255, 0.95);
    color: #0a1a44;
    border-color: white;
    box-shadow: 
        0 15px 35px rgba(255, 255, 255, 0.3),
        0 0 15px rgba(255, 255, 255, 0.2) inset;
}

.tab-badge {
    background: rgba(255, 255, 255, 0.3);
    color: white;
    padding: 0.4rem 0.9rem;
    border-radius: 20px;
    font-size: 0.95rem;
    font-weight: 800;
    border: 1px solid rgba(255, 255, 255, 0.5);
}

.tab-btn.active .tab-badge {
    background: #0a1a44;
    color: white;
    border-color: #0a1a44;
}

.meal-tab-btn .tab-badge {
    background: rgba(255, 255, 255, 0.4);
    color: white;
}

.meal-tab-btn.active .tab-badge {
    background: rgba(0, 0, 0, 0.3);
    color: white;
}

/* ✅ Rest of the page content spacing */
.gallery-content-wrapper {
    padding-top: 0 !important;
    margin-top: 0 !important;
    min-height: auto;
}

/* ✅ Ensure the rest of the page looks good */
.gallery-filters {
    padding-top: 5rem;
    padding-bottom: 2rem;
    background: #f8fafc;
}

/* 🚨 CRITICAL: Remove any old gallery-header styles */
.gallery-header {
    display: none !important;
}

/* ✅ Responsive Design */
@media (max-width: 1024px) {
    .gallery-hero-title {
        font-size: 3.2rem;
    }
    
    .gallery-hero-subtitle {
        font-size: 1.3rem;
        padding: 1rem 2rem;
        margin-bottom: 2.5rem;
    }
    
    .gallery-hero-button,
    .gallery-meal-button {
        padding: 1.1rem 3rem;
        font-size: 1.2rem;
    }
    
    .tab-btn,
    .meal-tab-btn {
        padding: 1rem 2rem;
        min-width: 180px;
        font-size: 1rem;
    }
    
    .gallery-tabs-navigation {
        bottom: 50px;
        padding: 1.2rem;
    }
}

@media (max-width: 768px) {
    .gallery-hero-section {
        min-height: 90vh;
    }
    
    .gallery-hero-content {
        padding: 1.5rem;
    }
    
    .gallery-hero-title {
        font-size: 2.5rem;
        margin-bottom: 1rem;
    }
    
    .gallery-hero-subtitle {
        font-size: 1.1rem;
        margin-bottom: 2rem;
        padding: 0.9rem 1.5rem;
    }
    
    .gallery-hero-buttons {
        flex-direction: column;
        align-items: center;
        gap: 1rem;
    }
    
    .gallery-hero-button,
    .gallery-meal-button {
        padding: 1rem 2.5rem;
        font-size: 1.1rem;
        margin-top: 0;
        margin-left: 0;
        width: 100%;
        max-width: 300px;
    }
    
    .gallery-tabs-navigation {
        bottom: 40px;
        padding: 1rem;
    }
    
    .gallery-tabs {
        gap: 0.5rem;
    }
    
    .tab-btn,
    .meal-tab-btn {
        padding: 0.9rem 1.5rem;
        min-width: 160px;
        font-size: 0.95rem;
        gap: 0.5rem;
        margin-left: 0;
    }
    
    .tab-badge {
        font-size: 0.85rem;
        padding: 0.3rem 0.7rem;
    }
}

@media (max-width: 480px) {
    .gallery-hero-section {
        min-height: 85vh;
    }
    
    .gallery-hero-content {
        padding: 1rem;
    }
    
    .gallery-hero-title {
        font-size: 2rem;
        margin-bottom: 0.8rem;
    }
    
    .gallery-hero-subtitle {
        font-size: 1rem;
        margin-bottom: 1.5rem;
        padding: 0.8rem 1rem;
    }
    
    .gallery-hero-button,
    .gallery-meal-button {
        padding: 0.9rem 2rem;
        font-size: 1rem;
        max-width: 280px;
    }
    
    .gallery-tabs-navigation {
        bottom: 30px;
        padding: 0.8rem;
    }
    
    .gallery-tabs {
        flex-direction: column;
        align-items: center;
    }
    
    .tab-btn,
    .meal-tab-btn {
        width: 90%;
        max-width: 250px;
        justify-content: center;
        padding: 0.8rem 1rem;
        min-width: auto;
    }
}

/* ✅ FIXED: No Results Message - ALWAYS HIDDEN FOR VIDEOS TAB */
#videos-tab .no-results {
    display: none !important;
    visibility: hidden !important;
    opacity: 0 !important;
}

/* ✅ FIXED: Pagination hidden for videos if less than 2 pages */
#videos-tab .pagination-container:empty,
#videos-tab .pagination-container:has(ul.pagination:only-child li:only-child) {
    display: none !important;
}

/* The rest of the existing CSS remains exactly the same */

/* ✅ Ensure filters section has proper spacing */
.gallery-filters {
    padding-top: 5rem;
    background: linear-gradient(to bottom, transparent, #f8fafc 100px);
}

/* ✅ Existing gallery styles (keep them) */
.gallery-page-main {
    margin: 0 !important;
    padding: 0 !important;
    min-height: 0 !important;
}

/* 🚨 CRITICAL: Force hide all spinners by default */
.gallery-loading,
.videos-loading,
.videos-placeholder .spinner,
.loading-spinner {
    display: none !important;
    visibility: hidden !important;
    opacity: 0 !important;
}

/* Only show when explicitly triggered */
.gallery-loading:not(.hidden),
.videos-loading:not(.hidden) {
    display: block;
    visibility: visible;
    opacity: 1;
}

/* Ensure videos placeholder is hidden when videos exist */
.videos-grid:has(.video-card) .videos-placeholder {
    display: none !important;
}

/* FIX: Make sure videos grid shows properly */
.videos-grid {
    position: relative;
    min-height: 200px;
}

/* FIX: Smooth transitions for placeholder */
.videos-placeholder {
    transition: opacity 0.3s ease, height 0.3s ease;
}

/* Mobile fix */
@media (max-width: 768px) {
    .gallery-header {
        margin: 0 auto 1rem auto !important;
        padding: 1.5rem 1rem !important;
    }
}

/* 🚨 CRITICAL: Gallery page specific fixes */
.gallery-page-main {
    padding-top: 0 !important;
    margin-top: 0 !important;
}

.gallery-content-wrapper {
    padding: 0;
    margin: 0;
    min-height: calc(100vh - 200px);
    display: flex;
    flex-direction: column;
}

/* Remove any duplicate header protection */
.page-header {
    display: none !important;
}

/* Video Card Specific Styles */
.video-card {
    position: relative;
    border-radius: var(--gallery-radius);
    overflow: hidden;
    background: white;
    box-shadow: var(--gallery-shadow);
    transition: var(--gallery-transition);
    cursor: pointer;
}

.video-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 20px 25px rgba(0, 0, 0, 0.15);
}

.video-thumbnail {
    position: relative;
    width: 100%;
    padding-bottom: 56.25%; /* 16:9 Aspect Ratio for videos */
    overflow: hidden;
    background: #000;
}

.video-thumbnail img {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.3s ease;
}

.video-card:hover .video-thumbnail img {
    transform: scale(1.05);
}

.play-button {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    width: 60px;
    height: 60px;
    background: rgba(255, 255, 255, 0.9);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    color: var(--gallery-primary);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
    transition: all 0.3s ease;
    z-index: 2;
}

.video-card:hover .play-button {
    background: white;
    transform: translate(-50%, -50%) scale(1.1);
    box-shadow: 0 6px 20px rgba(0, 0, 0, 0.4);
}

.video-duration {
    position: absolute;
    bottom: 10px;
    right: 10px;
    background: rgba(0, 0, 0, 0.8);
    color: white;
    padding: 0.3rem 0.6rem;
    border-radius: 4px;
    font-size: 0.8rem;
    font-weight: 600;
    z-index: 2;
}

.video-badge-360 {
    position: absolute;
    top: 10px;
    left: 10px;
    background: rgba(14, 165, 233, 0.9);
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

.video-info {
    padding: 1rem;
}

.video-info h3 {
    font-size: 1.1rem;
    font-weight: 600;
    margin-bottom: 0.5rem;
    color: var(--gallery-dark);
    line-height: 1.3;
}

.video-meta {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 0.8rem;
    font-size: 0.85rem;
    color: #666;
}

.video-hostel-link {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    color: var(--gallery-primary);
    text-decoration: none;
    font-weight: 600;
    font-size: 0.8rem;
    transition: all 0.3s ease;
    padding: 0.3rem 0;
}

.video-hostel-link:hover {
    color: var(--gallery-secondary);
    gap: 0.7rem;
}

.video-resolution {
    background: var(--gallery-light);
    color: var(--gallery-dark);
    padding: 0.2rem 0.5rem;
    border-radius: 4px;
    font-size: 0.75rem;
    font-weight: 600;
}

/* Video Categories Filter */
.video-categories {
    display: flex;
    flex-wrap: wrap;
    gap: 0.5rem;
    margin-bottom: 1.5rem;
}

.video-category-btn {
    padding: 0.5rem 1rem;
    background: #f3f4f6;
    border: 1px solid #e5e7eb;
    border-radius: 50px;
    cursor: pointer;
    transition: all 0.3s ease;
    font-size: 0.9rem;
    color: #4b5563;
}

.video-category-btn:hover {
    background: #e5e7eb;
    border-color: #d1d5db;
}

.video-category-btn.active {
    background: var(--gallery-primary);
    color: white;
    border-color: var(--gallery-primary);
}

/* HD Image Indicator */
.hd-badge {
    position: absolute;
    top: 10px;
    right: 10px;
    background: rgba(0, 0, 0, 0.8);
    color: #10b981;
    padding: 0.3rem 0.6rem;
    border-radius: 4px;
    font-size: 0.7rem;
    font-weight: 600;
    z-index: 2;
    display: flex;
    align-items: center;
    gap: 0.3rem;
}

/* Enhanced Hostel Link */
.hostel-link-enhanced {
    position: absolute;
    top: 10px;
    left: 10px;
    background: rgba(30, 58, 138, 0.9);
    color: white;
    padding: 0.4rem 0.8rem;
    border-radius: 4px;
    font-size: 0.8rem;
    font-weight: 600;
    z-index: 10;
    display: flex;
    align-items: center;
    gap: 0.4rem;
    text-decoration: none;
    transition: all 0.3s ease;
    backdrop-filter: blur(4px);
    white-space: normal;
    max-width: none;
}

.hostel-link-enhanced:hover {
    background: rgba(30, 58, 138, 1);
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(30, 58, 138, 0.3);
    color: white;
}

/* Quick View Button */
.quick-view-btn {
    background: rgba(255, 255, 255, 0.9);
    color: var(--gallery-dark);
    border: 1px solid rgba(0, 0, 0, 0.1);
    padding: 0.25rem 0.6rem;
    border-radius: 4px;
    font-size: 0.65rem;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.2s ease;
    display: inline-flex;
    align-items: center;
    gap: 0.3rem;
    text-decoration: none;
    margin-top: 0.25rem;
    position: absolute;
    bottom: 8px;
    left: 50%;
    transform: translateX(-50%);
    white-space: nowrap;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    z-index: 5;
    backdrop-filter: blur(4px);
}

.quick-view-btn:hover {
    background: white;
    transform: translateX(-50%) translateY(-1px);
    box-shadow: 0 3px 6px rgba(0, 0, 0, 0.15);
    color: var(--gallery-primary);
    border-color: var(--gallery-primary);
}

/* Media overlay */
.media-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(to top, rgba(0, 0, 0, 0.7) 0%, rgba(0, 0, 0, 0.4) 40%, transparent 70%);
    opacity: 0;
    transition: opacity 0.3s ease;
    display: flex;
    flex-direction: column;
    justify-content: flex-end;
    padding: 0.75rem;
    z-index: 4;
}

.gallery-item:hover .media-overlay {
    opacity: 1;
}

.media-title {
    font-size: 0.85rem;
    font-weight: 600;
    color: white;
    margin-bottom: 0.2rem;
    text-shadow: 0 1px 3px rgba(0, 0, 0, 0.7);
    line-height: 1.2;
}

.media-description {
    font-size: 0.7rem;
    color: rgba(255, 255, 255, 0.85);
    margin-bottom: 0.3rem;
    text-shadow: 0 1px 2px rgba(0, 0, 0, 0.7);
    line-height: 1.2;
}

.media-meta {
    display: flex;
    flex-wrap: wrap;
    gap: 0.4rem;
    margin-bottom: 0.4rem;
    font-size: 0.65rem;
}

.media-category {
    background: rgba(30, 58, 138, 0.8);
    color: white;
    padding: 0.15rem 0.4rem;
    border-radius: 3px;
    font-weight: 600;
}

.media-date {
    background: rgba(0, 0, 0, 0.6);
    color: rgba(255, 255, 255, 0.9);
    padding: 0.15rem 0.4rem;
    border-radius: 3px;
}

/* Enhanced Loading State for Videos */
.videos-placeholder {
    text-align: center;
    padding: 3rem;
    grid-column: 1 / -1;
}

.videos-placeholder .spinner {
    width: 40px;
    height: 40px;
    border: 4px solid #f3f4f6;
    border-top: 4px solid var(--gallery-primary);
    border-radius: 50%;
    animation: spin 1s linear infinite;
    margin: 0 auto 1rem;
}

.videos-placeholder.hidden {
    display: none !important;
}

/* 🚨 UPDATED GALLERY CTA SECTION */
.gallery-cta-wrapper {
    width: 100%;
    display: flex;
    justify-content: center;
    padding: 1.25rem 1.5rem 1.75rem 1.5rem;
    margin-top: 0.75rem;
}

.gallery-cta-section {
    text-align: center;
    background: linear-gradient(135deg, var(--primary), var(--secondary));
    color: white;
    padding: 2rem 1.75rem;
    border-radius: 1rem;
    box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.2);
    max-width: 800px;
    width: 100%;
    margin: 0 auto;
}

.gallery-cta-section h2 {
    font-size: 1.75rem;
    font-weight: bold;
    margin-bottom: 0.75rem;
    color: white;
}

.gallery-cta-section p {
    font-size: 1.125rem;
    margin-bottom: 1.5rem;
    opacity: 0.9;
}

/* DEMO BUTTON (Orange Gradient with play icon) */
.gallery-demo-button {
    background: linear-gradient(135deg, #FF6B6B, #FF8E53);
    color: white;
    font-weight: 600;
    padding: 0.75rem 2rem;
    border-radius: 0.5rem;
    text-decoration: none;
    min-width: 180px;
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
    transition: all 0.3s ease;
    border: none;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
    font-size: 1rem;
    text-align: center;
}

.gallery-demo-button:hover {
    background: linear-gradient(135deg, #FF5252, #FF7A3D);
    transform: translateY(-2px);
    box-shadow: 0 10px 20px rgba(255, 107, 107, 0.3);
    color: white;
}

/* PRIMARY TRIAL BUTTON (White Background) */
.gallery-trial-button {
    background-color: white;
    color: #001F5B;
    font-weight: 600;
    padding: 0.75rem 2rem;
    border-radius: 0.5rem;
    text-decoration: none;
    min-width: 180px;
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
    transition: all 0.3s ease;
    border: none;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
    font-size: 1rem;
    text-align: center;
}

.gallery-trial-button:hover {
    background-color: #f3f4f6;
    transform: translateY(-2px);
    color: #001F5B;
}

/* OUTLINE PRICING BUTTON */
.gallery-outline-button {
    background: transparent;
    border: 2px solid white;
    color: white;
    font-weight: 600;
    padding: 0.75rem 2rem;
    border-radius: 0.5rem;
    text-decoration: none;
    min-width: 180px;
    transition: all 0.3s ease;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
    font-size: 1rem;
    text-align: center;
}

.gallery-outline-button:hover {
    background: white;
    color: #001F5B;
    transform: translateY(-2px);
}

/* MEAL GALLERY BUTTON IN CTA */
.gallery-meal-cta-button {
    background: linear-gradient(135deg, #10B981, #34D399);
    color: white;
    font-weight: 600;
    padding: 0.75rem 2rem;
    border-radius: 0.5rem;
    text-decoration: none;
    min-width: 180px;
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
    transition: all 0.3s ease;
    border: none;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
    font-size: 1rem;
    text-align: center;
}

.gallery-meal-cta-button:hover {
    background: linear-gradient(135deg, #059669, #10B981);
    transform: translateY(-2px);
    box-shadow: 0 10px 20px rgba(16, 185, 129, 0.3);
    color: white;
}

.gallery-cta-buttons-container {
    display: flex;
    gap: 1rem;
    align-items: center;
    justify-content: center;
    margin-top: 1rem;
    width: 100%;
    flex-wrap: wrap;
}

/* Loading states */
.gallery-outline-button.loading,
.gallery-trial-button.loading,
.gallery-demo-button.loading,
.gallery-meal-cta-button.loading {
    position: relative;
    color: transparent;
}

.gallery-outline-button.loading::after,
.gallery-trial-button.loading::after,
.gallery-demo-button.loading::after,
.gallery-meal-cta-button.loading::after {
    content: '';
    position: absolute;
    width: 20px;
    height: 20px;
    top: 50%;
    left: 50%;
    margin: -10px 0 0 -10px;
    border: 2px solid rgba(255,255,255,0.3);
    border-radius: 50%;
    border-top-color: white;
    animation: spin 1s ease-in-out infinite;
}

.gallery-trial-button.loading::after {
    border: 2px solid rgba(0,31,91,0.3);
    border-top-color: #001F5B;
}

.gallery-demo-button.loading::after {
    border: 2px solid rgba(255,255,255,0.3);
    border-top-color: white;
}

.gallery-meal-cta-button.loading::after {
    border: 2px solid rgba(255,255,255,0.3);
    border-top-color: white;
}

/* Animation */
@keyframes fadeIn {
    from { opacity: 0; transform: translateY(10px); }
    to { opacity: 1; transform: translateY(0); }
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

/* Mobile adjustments */
@media (max-width: 768px) {
    .gallery-tabs {
        gap: 0.3rem;
        justify-content: center;
    }

    .tab-btn {
        padding: 0.5rem 0.9rem;
        font-size: 0.85rem;
    }

    .video-categories {
        justify-content: center;
    }

    .video-category-btn {
        padding: 0.4rem 0.8rem;
        font-size: 0.8rem;
    }

    .play-button {
        width: 50px;
        height: 50px;
        font-size: 1.2rem;
    }

    .video-info h3 {
        font-size: 1rem;
    }
    
    /* CTA Mobile adjustments */
    .gallery-cta-wrapper {
        padding: 1rem 1rem 1.25rem 1rem;
    }
    
    .gallery-cta-section {
        padding: 1.75rem 1.25rem;
    }
    
    .gallery-cta-section h2 {
        font-size: 1.5rem;
    }
    
    .gallery-cta-section p {
        font-size: 1rem;
        margin-bottom: 1.25rem;
    }
    
    .gallery-cta-buttons-container {
        margin-top: 0.75rem;
        flex-direction: column;
        gap: 0.75rem;
    }

    .gallery-demo-button,
    .gallery-trial-button,
    .gallery-outline-button,
    .gallery-meal-cta-button {
        padding: 0.6rem 1.5rem;
        font-size: 0.9rem;
        min-width: 160px;
        width: 100%;
        max-width: 250px;
    }
    
    /* Mobile badge visibility fix */
    .tab-badge {
        background: rgba(0, 0, 0, 0.8) !important;
        color: white !important;
    }
    
    .tab-btn.active .tab-badge {
        background: rgba(255, 255, 255, 0.95) !important;
        color: #1e293b !important;
    }

    /* 🚨 MOBILE: Quick view button */
    .quick-view-btn {
        padding: 0.2rem 0.5rem;
        font-size: 0.6rem;
        bottom: 6px;
    }
}

@media (max-width: 480px) {
    .gallery-cta-wrapper {
        padding: 0.75rem 1rem 1rem 1rem;
    }
    
    .gallery-cta-section {
        padding: 1.5rem 1rem;
    }
    
    .gallery-cta-section h2 {
        font-size: 1.3rem;
    }
    
    .gallery-cta-section p {
        font-size: 0.9rem;
    }
}

/* 🚨 NEW: Active filter indicator */
select[data-filter-active="true"] {
    border: 2px solid var(--gallery-primary) !important;
    background-color: rgba(30, 58, 138, 0.05) !important;
    box-shadow: 0 0 0 3px rgba(30, 58, 138, 0.1) !important;
}

/* 🚨 NEW: Boys/Girls filter specific styles */
#hostelFilter option[value="boys"] {
    color: #3b82f6;
    font-weight: 600;
}

#hostelFilter option[value="girls"] {
    color: #ec4899;
    font-weight: 600;
}

#hostelFilter option[value="boys"]:checked,
#hostelFilter option[value="boys"]:selected {
    background-color: #3b82f6;
    color: white;
}

#hostelFilter option[value="girls"]:checked,
#hostelFilter option[value="girls"]:selected {
    background-color: #ec4899;
    color: white;
}

/* 🚨 NEW: Gallery Button Styles */
.gallery-button {
    background: rgba(255, 107, 53, 0.9);
    color: white;
    padding: 0.4rem 0.8rem;
    border-radius: 4px;
    font-size: 0.7rem;
    font-weight: 600;
    z-index: 2;
    display: inline-flex;
    align-items: center;
    gap: 0.3rem;
    text-decoration: none;
    transition: all 0.3s ease;
    border: 1px solid rgba(255, 255, 255, 0.3);
    backdrop-filter: blur(4px);
}

.gallery-button:hover {
    background: rgba(255, 107, 53, 1);
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(255, 107, 53, 0.3);
    color: white;
}

/* Modal gallery button */
.modal-gallery-link {
    background: rgba(255, 107, 53, 0.9);
    color: white;
    padding: 0.5rem 1rem;
    border-radius: 4px;
    font-size: 0.8rem;
    font-weight: 600;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    transition: all 0.3s ease;
    border: 1px solid rgba(255, 255, 255, 0.3);
    margin-left: 1rem;
}

.modal-gallery-link:hover {
    background: rgba(255, 107, 53, 1);
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(255, 107, 53, 0.3);
    color: white;
}

/* 🚨 NEW: Gallery Modal Styles */
.gallery-modal {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.9);
    z-index: 9999;
    opacity: 0;
    transition: opacity 0.3s ease;
}

.gallery-modal.active {
    display: block;
    opacity: 1;
}

.gallery-modal .modal-close {
    position: absolute;
    top: 20px;
    right: 20px;
    background: rgba(255, 255, 255, 0.2);
    border: none;
    color: white;
    font-size: 24px;
    width: 40px;
    height: 40px;
    border-radius: 50%;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 10001;
    transition: background 0.3s ease;
}

.gallery-modal .modal-close:hover {
    background: rgba(255, 255, 255, 0.3);
}

.gallery-modal .modal-nav {
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    background: rgba(255, 255, 255, 0.2);
    border: none;
    color: white;
    font-size: 24px;
    width: 50px;
    height: 50px;
    border-radius: 50%;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 10001;
    transition: background 0.3s ease;
}

.gallery-modal .modal-nav:hover {
    background: rgba(255, 255, 255, 0.3);
}

.gallery-modal .modal-prev {
    left: 20px;
}

.gallery-modal .modal-next {
    right: 20px;
}

.gallery-modal .modal-content {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    max-width: 90%;
    max-height: 90%;
    width: auto;
    height: auto;
}

.gallery-modal .modal-content img,
.gallery-modal .modal-content video,
.gallery-modal .modal-content iframe {
    max-width: 100%;
    max-height: 80vh;
    border-radius: 10px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.5);
}

.gallery-modal .modal-info {
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    background: rgba(0, 0, 0, 0.8);
    color: white;
    padding: 20px;
    border-top: 1px solid rgba(255, 255, 255, 0.1);
}

.gallery-modal .modal-title {
    font-size: 1.5rem;
    font-weight: 600;
    margin-bottom: 10px;
}

.gallery-modal .modal-description {
    font-size: 1rem;
    margin-bottom: 15px;
    opacity: 0.9;
}

.gallery-modal .modal-meta {
    display: flex;
    flex-wrap: wrap;
    gap: 15px;
    margin-bottom: 15px;
    font-size: 0.9rem;
    opacity: 0.8;
}

.gallery-modal .modal-actions {
    display: flex;
    gap: 10px;
    flex-wrap: wrap;
}
</style>
@endpush

@section('content')


<!-- ✅ UPDATED: GALLERY HERO SECTION - CRYSTAL CLEAR BACKGROUND -->
<section class="gallery-hero-section">
    <!-- ✅ FIXED: NATURAL CLEAR BACKGROUND IMAGE (NO BLUE OVERLAY) -->
    <div class="gallery-hero-bg"></div>
    
    <!-- ✅ Hero Content -->
    <div class="gallery-hero-content">
        <h1 class="gallery-hero-title nepali">हाम्रो ग्यालरी</h1>
        
        <p class="gallery-hero-subtitle nepali">
            हाम्रा विभिन्न होस्टलहरूको कोठा, सुविधा र आवासीय क्षेत्रहरूको वास्तविक झलकहरू अन्वेषण गर्नुहोस्
        </p>
        
        <!-- ✅ RESTORED: Hero Buttons Container -->
        <div class="gallery-hero-buttons">
            <!-- Main Gallery Button -->
            <a href="{{ route('gallery.index') }}" class="gallery-hero-button nepali">
                <i class="fas fa-images"></i>
                ग्यालरी हेर्नुहोस्
            </a>
            
            <!-- 🚨 RESTORED: Meal Gallery Button - GREEN COLOR -->
            <a href="{{ route('menu-gallery') }}" class="gallery-meal-button nepali">
                <i class="fas fa-utensils"></i>
                🍛 खानाको ग्यालरी हेर्नुहोस्
            </a>
        </div>
    </div>
    
    <!-- ✅ Tabs Navigation -->
    <div class="gallery-tabs-navigation">
        <div class="gallery-tabs-container">
            <div class="gallery-tabs">
                <a href="{{ route('gallery.index', ['tab' => 'photos']) }}" 
                   class="tab-btn nepali {{ $tab === 'photos' ? 'active' : '' }}"
                   onclick="return handleTabClick(event, 'photos')">
                    <i class="fas fa-images"></i>
                    तस्बिरहरू
                    <span class="tab-badge">{{ $metrics['total_photos'] ?? '150+' }}</span>
                </a>
                <a href="{{ route('gallery.index', ['tab' => 'videos']) }}" 
                   class="tab-btn nepali {{ $tab === 'videos' ? 'active' : '' }}"
                   onclick="return handleTabClick(event, 'videos')">
                    <i class="fas fa-video"></i>
                    भिडियोहरू
                    <span class="tab-badge">{{ $metrics['total_videos'] ?? '25+' }}</span>
                </a>
                <a href="{{ route('gallery.index', ['tab' => 'virtual-tours']) }}" 
                   class="tab-btn nepali {{ $tab === 'virtual-tours' ? 'active' : '' }}"
                   onclick="return handleTabClick(event, 'virtual-tours')">
                    <i class="fas fa-360-degrees"></i>
                    भर्चुअल टुर
                </a>
                
                <!-- 🚨 NEW: MEAL GALLERY TAB BUTTON -->
                <a href="{{ route('menu-gallery') }}" 
                   class="meal-tab-btn nepali">
                    <i class="fas fa-utensils"></i>
                    खानाको ग्यालरी
                    <span class="tab-badge">🍛</span>
                </a>
            </div>
        </div>
    </div>
</section>

<!-- The rest of the code remains exactly the same -->
<div class="gallery-content-wrapper">
    <!-- Enhanced Filters Section with Hostel Filter -->
    <section class="gallery-filters">
        <div class="filter-container">
            <div class="filter-header">
                <h2 class="nepali">ग्यालरी फिल्टर गर्नुहोस्</h2>
                <p class="nepali">तपाईंले हेर्न चाहनुभएको विशेष प्रकारको मिडिया वा होस्टल चयन गर्नुहोस्</p>
            </div>
            
            <!-- Hostel Filter -->
            <div class="hostel-filter">
                <label class="nepali" style="display: block; margin-bottom: 0.5rem; font-weight: 600;">
                    <i class="fas fa-building"></i> होस्टल छान्नुहोस्:
                </label>
                <select id="hostelFilter" class="form-control" onchange="handleHostelFilterChange()">
                    <option value="">सबै होस्टलहरू</option>
                    <option value="boys" {{ request('hostel_gender') == 'boys' ? 'selected' : '' }}>
                        ब्वाइज होस्टलहरू
                    </option>
                    <option value="girls" {{ request('hostel_gender') == 'girls' ? 'selected' : '' }}>
                        गर्ल्स होस्टलहरू
                    </option>
                    <optgroup label="विशेष होस्टलहरू">
                        @foreach($hostels as $hostel)
                            <option value="{{ $hostel->id }}" 
                                    {{ request('hostel_id') == $hostel->id ? 'selected' : '' }}>
                                {{ $hostel->name }}
                            </option>
                        @endforeach
                    </optgroup>
                </select>
            </div>

            <!-- Video Categories Filter (Only for videos tab) -->
            @if($tab === 'videos')
                <div class="video-categories-filter">
                    <label class="nepali" style="display: block; margin-bottom: 0.5rem; font-weight: 600;">
                        <i class="fas fa-filter"></i> भिडियो प्रकार:
                    </label>
                    <div class="video-categories">
                        <button class="video-category-btn {{ !request('video_category') || request('video_category') == 'all' ? 'active' : '' }}" 
                                data-category="all"
                                onclick="handleVideoCategoryClick('all')">
                            सबै
                        </button>
                        @foreach($videoCategories as $key => $name)
                            @if($key !== 'all')
                                <button class="video-category-btn {{ request('video_category') == $key ? 'active' : '' }}" 
                                        data-category="{{ $key }}"
                                        onclick="handleVideoCategoryClick('{{ $key }}')">
                                    {{ $name }}
                                </button>
                            @endif
                        @endforeach
                    </div>
                </div>
            @endif

            <div class="filter-controls">
                <div class="filter-categories">
                    @if($tab === 'photos')
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
                    @elseif($tab === 'videos')
                        <button class="filter-btn active nepali" data-filter="all">सबै भिडियो</button>
                        <button class="filter-btn nepali" data-filter="hostel_tour">होस्टल टुर</button>
                        <button class="filter-btn nepali" data-filter="room_tour">कोठा टुर</button>
                        <button class="filter-btn nepali" data-filter="student_life">विद्यार्थी जीवन</button>
                        <button class="filter-btn nepali" data-filter="virtual_tour">भर्चुअल टुर</button>
                        <button class="filter-btn nepali" data-filter="student_experience">विद्यार्थी अनुभव</button>
                        <button class="filter-btn nepali" data-filter="facilities">सुविधाहरू</button>
                    @endif
                </div>
                
                <div class="search-box">
                    <i class="fas fa-search search-icon"></i>
                    <input type="text" class="search-input nepali" placeholder="कोठा, सुविधा, होस्टल वा स्थान खोज्नुहोस्...">
                </div>
            </div>
        </div>
    </section>

    <!-- ✅ FIXED: Main Gallery Section with Tabs Content -->
    <section class="gallery-main">
        <div class="gallery-container">
            <!-- Photos Tab Content -->
            <div class="tab-content {{ $tab === 'photos' ? 'active' : '' }}" id="photos-tab">
                <div class="gallery-grid">
                    @forelse($galleries as $gallery)
                        @php
                            if (!is_object($gallery) && !is_array($gallery)) continue;
                            
                            $isArray = is_array($gallery);
                            $mediaType = $isArray ? ($gallery['media_type'] ?? null) : ($gallery->media_type ?? null);
                            
                            if (!$mediaType || $mediaType !== 'photo') continue;
                            
                            $categoryNepali = $isArray ? ($gallery['category_nepali'] ?? '') : ($gallery->category_nepali ?? '');
                            $title = $isArray ? ($gallery['title'] ?? '') : ($gallery->title ?? '');
                            $description = $isArray ? ($gallery['description'] ?? '') : ($gallery->description ?? '');
                            $createdAt = $isArray ? \Carbon\Carbon::parse($gallery['created_at'] ?? now()) : ($gallery->created_at ?? now());
                            $hostelName = $isArray ? ($gallery['hostel_name'] ?? '') : ($gallery->hostel_name ?? '');
                            $hostelId = $isArray ? ($gallery['hostel_id'] ?? '') : ($gallery->hostel_id ?? '');
                            $hostelSlug = $isArray ? ($gallery['hostel_slug'] ?? '') : ($gallery->hostel_slug ?? '');
                            $room = $isArray ? ($gallery['room'] ?? null) : ($gallery->room ?? null);
                            $roomNumber = $room ? ($isArray ? ($room['room_number'] ?? '') : ($room->room_number ?? '')) : '';
                            
                            // Get raw paths
                            $rawMediaPath = $isArray ? ($gallery['file_path'] ?? '') : ($gallery->file_path ?? '');
                            $rawThumbnailPath = $isArray ? ($gallery['thumbnail_path'] ?? $rawMediaPath) : ($gallery->thumbnail_path ?? $rawMediaPath);
                            $rawHdPath = $isArray ? ($gallery['hd_path'] ?? $rawMediaPath) : ($gallery->hd_path ?? $rawMediaPath);
                            
                            // Convert to Railway URLs
                            $thumbnailUrl = railway_media_url($rawThumbnailPath);
                            $mediaUrl = railway_media_url($rawMediaPath);
                            $hdUrl = railway_media_url($rawHdPath);
                            
                            $hdAvailable = $isArray ? ($gallery['hd_available'] ?? false) : ($gallery->hd_available ?? false);
                            
                            $hostelGender = $isArray ? 
                                ($gallery['hostel_gender'] ?? 'mixed') : 
                                ($gallery->hostel_gender ?? 'mixed');
                                
                            // Generate hostel gallery URL
                            $hostelGalleryUrl = $hostelSlug ? url('/hostel/' . $hostelSlug . '/gallery') : '#';
                        @endphp
                        
                        <div class="gallery-item" 
                             data-category="{{ $categoryNepali }}"
                             data-title="{{ $title }}"
                             data-description="{{ $description }}"
                             data-date="{{ $createdAt->format('Y-m-d') }}"
                             data-hostel="{{ $hostelName }}"
                             data-hostel-id="{{ $hostelId }}"
                             data-hostel-slug="{{ $hostelSlug }}"
                             data-hostel-name="{{ strtolower($hostelName) }}"
                             data-hostel-gender="{{ $hostelGender }}"
                             data-room-number="{{ $roomNumber }}"
                             data-media-type="{{ $mediaType }}"
                             data-media-url="{{ $mediaUrl }}"
                             data-hd-url="{{ $hdUrl }}"
                             data-hd-available="{{ $hdAvailable ? 'true' : 'false' }}"
                             data-gallery-url="{{ $hostelGalleryUrl }}"
                             data-id="{{ $isArray ? ($gallery['id'] ?? '') : ($gallery->id ?? '') }}">

                            <div class="gallery-media-container">
                                @if($hostelSlug)
                                <a href="{{ route('hostels.show', $hostelSlug) }}" 
                                   class="hostel-link-enhanced" 
                                   title="{{ $hostelName }} मा जानुहोस्">
                                    <i class="fas fa-external-link-alt"></i>
                                    <span class="nepali">{{ $hostelName }}</span>
                                </a>
                                
                                <!-- 🚨 NEW: Gallery Button on Image -->
                                <a href="{{ $hostelGalleryUrl }}" 
                                   class="gallery-button" 
                                   style="position: absolute; top: 10px; right: 10px;"
                                   title="{{ $hostelName }} को ग्यालरी हेर्नुहोस्">
                                    <i class="fas fa-images"></i>
                                    <span class="nepali">ग्यालरी</span>
                                </a>
                                @endif

                                @if($hdAvailable)
                                <div class="hd-badge" title="HD उपलब्ध">
                                    <i class="fas fa-hd"></i>
                                    <span>HD</span>
                                </div>
                                @endif

                                <img src="{{ $thumbnailUrl }}" 
                                     alt="{{ $title }}" 
                                     class="gallery-media" 
                                     loading="lazy"
                                     data-src="{{ $mediaUrl }}"
                                     data-hd-src="{{ $hdUrl }}">

                                <div class="media-overlay">
                                    <div class="media-title nepali">{{ $title }}</div>
                                    <div class="media-description nepali">{{ Str::limit($description, 60) }}</div>
                                    <div class="media-meta">
                                        <span class="media-category nepali">{{ $categoryNepali }}</span>
                                        <span class="media-date">{{ $createdAt->format('Y-m-d') }}</span>
                                    </div>
                                    @if($hostelSlug)
                                    <div style="display: flex; gap: 5px; justify-content: center; margin-top: 5px;">
                                        <a href="{{ route('hostels.show', $hostelSlug) }}" 
                                           class="quick-view-btn nepali">
                                            <i class="fas fa-info-circle"></i>
                                            होस्टल विवरण
                                        </a>
                                        <a href="{{ $hostelGalleryUrl }}" 
                                           class="quick-view-btn nepali" style="background: rgba(255, 107, 53, 0.9); color: white;">
                                            <i class="fas fa-images"></i>
                                            ग्यालरी हेर्नुहोस्
                                        </a>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @empty
                    <div class="no-results">
                        <i class="fas fa-images"></i>
                        <h3 class="nepali">कुनै तस्बिर फेला परेन</h3>
                        <p class="nepali">हाल कुनै तस्बिर उपलब्ध छैन। कृपया पछि फर्कनुहोस्।</p>
                    </div>
                    @endforelse
                </div>
            </div>

            <!-- ✅ FIXED: Videos Tab Content - SERVER-SIDE ONLY -->
            <div class="tab-content {{ $tab === 'videos' ? 'active' : '' }}" id="videos-tab">
                <div class="gallery-grid videos-grid">
                    @if($tab === 'videos')
                        @php
                            $hasVideos = false;
                            $videoCount = 0;
                        @endphp
                        
                        @foreach($galleries as $gallery)
                            @php
                                if (!is_object($gallery) && !is_array($gallery)) continue;
                                
                                $isArray = is_array($gallery);
                                $mediaType = $isArray ? ($gallery['media_type'] ?? null) : ($gallery->media_type ?? null);
                                
                                if (!$mediaType || !in_array($mediaType, ['external_video', 'local_video'])) {
                                    continue;
                                }
                                
                                $hasVideos = true;
                                $videoCount++;
                                
                                if ($isArray) {
                                    $id = $gallery['id'] ?? '';
                                    $category = $gallery['category'] ?? '';
                                    $categoryNepali = $gallery['category_nepali'] ?? $category;
                                    $title = $gallery['title'] ?? '';
                                    $description = $gallery['description'] ?? '';
                                    $createdAt = \Carbon\Carbon::parse($gallery['created_at'] ?? now());
                                    $hostelName = $gallery['hostel_name'] ?? '';
                                    $hostelId = $gallery['hostel_id'] ?? '';
                                    $hostelSlug = $gallery['hostel_slug'] ?? '';
                                    
                                    // Get raw thumbnail path
                                    $rawThumbnailPath = $gallery['thumbnail_path'] ?? $gallery['file_path'] ?? '';
                                    
                                    // Convert to Railway URL
                                    $thumbnailUrl = $rawThumbnailPath ? railway_media_url($rawThumbnailPath) : asset('images/video-thumbnail.jpg');
                                    
                                    $mediaUrl = $gallery['media_url'] ?? '';
                                    $youtubeEmbedUrl = $gallery['youtube_embed_url'] ?? '';
                                    $videoDuration = $gallery['video_duration'] ?? '';
                                    $videoResolution = $gallery['video_resolution'] ?? '';
                                    $is360Video = $gallery['is_360_video'] ?? false;
                                    $hostelGender = $gallery['hostel_gender'] ?? 'mixed';
                                } else {
                                    $id = $gallery->id ?? '';
                                    $category = $gallery->category ?? '';
                                    $categoryNepali = $gallery->category_nepali ?? $category;
                                    $title = $gallery->title ?? '';
                                    $description = $gallery->description ?? '';
                                    $createdAt = $gallery->created_at;
                                    $hostelName = $gallery->hostel_name ?? '';
                                    $hostelId = $gallery->hostel_id ?? '';
                                    $hostelSlug = $gallery->hostel_slug ?? '';
                                    
                                    // Get raw thumbnail path
                                    $rawThumbnailPath = $gallery->thumbnail_path ?? $gallery->file_path ?? '';
                                    
                                    // Convert to Railway URL
                                    $thumbnailUrl = $rawThumbnailPath ? railway_media_url($rawThumbnailPath) : asset('images/video-thumbnail.jpg');
                                    
                                    $mediaUrl = $gallery->media_url ?? '';
                                    $youtubeEmbedUrl = $gallery->youtube_embed_url ?? '';
                                    $videoDuration = $gallery->video_duration_formatted ?? '';
                                    $videoResolution = $gallery->video_resolution ?? '';
                                    $is360Video = $gallery->is_360_video ?? false;
                                    $hostelGender = $gallery->hostel_gender ?? 'mixed';
                                }
                                
                                $videoModalUrl = '';
                                if ($mediaType === 'external_video' && $youtubeEmbedUrl) {
                                    $videoModalUrl = $youtubeEmbedUrl . '?autoplay=1&rel=0&controls=1&showinfo=0';
                                } elseif ($mediaType === 'local_video' && $mediaUrl) {
                                    $videoModalUrl = $mediaUrl;
                                }
                                
                                // Generate hostel gallery URL
                                $hostelGalleryUrl = $hostelSlug ? url('/hostel/' . $hostelSlug . '/gallery') : '#';
                            @endphp
                            
                            <div class="video-card server-loaded" 
                                 data-category="{{ $category }}"
                                 data-title="{{ $title }}"
                                 data-description="{{ $description }}"
                                 data-date="{{ $createdAt->format('Y-m-d') }}"
                                 data-hostel="{{ $hostelName }}"
                                 data-hostel-id="{{ $hostelId }}"
                                 data-hostel-slug="{{ $hostelSlug }}"
                                 data-hostel-name="{{ strtolower($hostelName) }}"
                                 data-hostel-gender="{{ $hostelGender }}"
                                 data-room-number="N/A"
                                 data-media-type="{{ $mediaType }}"
                                 data-video-modal-url="{{ $videoModalUrl }}"
                                 data-video-duration="{{ $videoDuration }}"
                                 data-video-resolution="{{ $videoResolution }}"
                                 data-gallery-url="{{ $hostelGalleryUrl }}"
                                 data-is-360="{{ $is360Video ? 'true' : 'false' }}"
                                 data-id="{{ $id }}">

                                <div class="video-thumbnail">
                                    <img src="{{ $thumbnailUrl }}" alt="{{ $title }}" loading="lazy">
                                    <div class="play-button">
                                        <i class="fas fa-play"></i>
                                    </div>
                                    
                                    @if($videoDuration)
                                    <div class="video-duration">{{ $videoDuration }}</div>
                                    @endif

                                    @if($is360Video)
                                    <div class="video-badge-360">
                                        <i class="fas fa-360-degrees"></i>
                                        360°
                                    </div>
                                    @endif

                                    @if($hostelSlug)
                                    <a href="{{ route('hostels.show', $hostelSlug) }}" 
                                       class="hostel-link-enhanced" 
                                       style="top: auto; bottom: 10px; left: 10px;"
                                       title="{{ $hostelName }} मा जानुहोस्">
                                        <i class="fas fa-external-link-alt"></i>
                                        <span class="nepali">{{ $hostelName }}</span>
                                    </a>
                                    
                                    <!-- 🚨 NEW: Gallery Button on Video Thumbnail -->
                                    <a href="{{ $hostelGalleryUrl }}" 
                                       class="gallery-button" 
                                       style="position: absolute; top: 10px; right: 10px;"
                                       title="{{ $hostelName }} को ग्यालरी हेर्नुहोस्">
                                        <i class="fas fa-images"></i>
                                        <span class="nepali">ग्यालरी</span>
                                    </a>
                                    @endif
                                </div>

                                <div class="video-info">
                                    <h3 class="nepali">{{ $title }}</h3>
                                    <div class="video-meta">
                                        <span class="nepali">{{ $categoryNepali }}</span>
                                        @if($videoResolution)
                                        <span class="video-resolution">{{ $videoResolution }}</span>
                                        @endif
                                    </div>
                                    <p class="video-description nepali">{{ Str::limit($description, 80) }}</p>
                                    @if($hostelSlug)
                                    <div style="display: flex; gap: 10px; margin-top: 0.5rem;">
                                        <a href="{{ route('hostels.show', $hostelSlug) }}" class="video-hostel-link nepali">
                                            <i class="fas fa-building"></i>
                                            होस्टल विवरण
                                        </a>
                                        <a href="{{ $hostelGalleryUrl }}" class="video-hostel-link nepali" style="color: #FF6B35;">
                                            <i class="fas fa-images"></i>
                                            ग्यालरी हेर्नुहोस्
                                        </a>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                        
                        @if(!$hasVideos)
                        <div class="no-results">
                            <i class="fas fa-video"></i>
                            <h3 class="nepali">कुनै भिडियो फेला परेन</h3>
                            <p class="nepali">हाल कुनै भिडियो उपलब्ध छैन। कृपया पछि फर्कनुहोस्।</p>
                        </div>
                        @endif
                        
                        <div id="videos-count" data-count="{{ $videoCount }}" style="display: none;"></div>
                        
                    @else
                        <div class="videos-placeholder">
                            <div class="spinner"></div>
                            <p class="nepali">भिडियोहरू लोड हुँदैछ...</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Pagination -->
            @if(count($galleries) > 0 && method_exists($galleries, 'links'))
            <div class="pagination-container">
                {{ $galleries->links() }}
            </div>
            @endif

            <!-- No Results Message -->
            <div class="no-results hidden">
                <i class="fas fa-search"></i>
                <h3 class="nepali">कुनै परिणाम फेला परेन</h3>
                <p class="nepali">तपाईंको खोजसँग मिल्ने कुनै ग्यालरी आइटम फेला परेन। कृपया अर्को खोज प्रयास गर्नुहोस्।</p>
            </div>

            <!-- Loading Indicator -->
            <div class="gallery-loading hidden">
                <div class="loading-spinner"></div>
                <p class="nepali">ग्यालरी आइटमहरू लोड हुँदैछ...</p>
            </div>
        </div>
    </section>

    <!-- Stats Section -->
    <section class="gallery-stats">
        <div class="stats-grid">
            <div class="stat-item">
                <div class="stat-number">{{ $metrics['total_students'] ?? '500+' }}</div>
                <div class="stat-label nepali">खुसी विद्यार्थीहरू</div>
            </div>
            <div class="stat-item">
                <div class="stat-number">{{ $metrics['total_hostels'] ?? '25' }}</div>
                <div class="stat-label nepali">सहयोगी होस्टल</div>
            </div>
            <div class="stat-item">
                <div class="stat-number">{{ $metrics['total_videos'] ?? '25+' }}</div>
                <div class="stat-label nepali">भिडियो टुरहरू</div>
            </div>
            <div class="stat-item">
                <div class="stat-number">{{ $metrics['total_photos'] ?? '150+' }}</div>
                <div class="stat-label nepali">HD तस्बिरहरू</div>
            </div>
        </div>
    </section>

    <!-- 🚨 UPDATED GALLERY CTA SECTION -->
    <div class="gallery-cta-wrapper">
        <section class="gallery-cta-section">
            <h2 class="nepali">तपाईंको होस्टललाई HostelHub संग जोड्नुहोस्</h2>
            <p class="nepali">७ दिन निःशुल्क परीक्षण गर्नुहोस् र होस्टल व्यवस्थापनलाई सजिलो, द्रुत र भरपर्दो बनाउनुहोस्</p>
            
            <div class="gallery-cta-buttons-container">
                <!-- BUTTON 1: DEMO (Orange Gradient with play icon) -->
                <a href="{{ route('demo') }}" class="gallery-demo-button nepali">
                    <i class="fas fa-play-circle"></i> डेमो हेर्नुहोस्
                </a>
                
                <!-- BUTTON 2: FREE TRIAL (White Background with rocket icon) -->
                @auth
                    @php
                        $organizationId = session('current_organization_id');
                        $hasSubscription = false;
                        
                        if ($organizationId) {
                            try {
                                $organization = \App\Models\Organization::with('subscription')->find($organizationId);
                                $hasSubscription = $organization->subscription ?? false;
                            } catch (Exception $e) {
                                $hasSubscription = false;
                            }
                        }
                    @endphp
                    
                    @if($hasSubscription)
                        <button class="gallery-outline-button nepali" disabled>
                            <i class="fas fa-check-circle"></i> पहिले नै सदस्यता
                        </button>
                    @else
                        <form action="{{ route('subscription.start-trial') }}" method="POST" style="display: inline;">
                            @csrf
                            <button type="submit" class="gallery-trial-button nepali">
                                <i class="fas fa-rocket"></i> निःशुल्क परीक्षण
                            </button>
                        </form>
                    @endif
                @else
                    <a href="{{ route('register.organization', ['plan' => 'starter']) }}" 
                       class="gallery-trial-button nepali">
                        <i class="fas fa-rocket"></i> निःशुल्क परीक्षण सुरु गर्नुहोस्
                    </a>
                @endauth
                
                <!-- BUTTON 3: PRICING (Outline with tags icon) -->
                @php
                    $pricingRoute = null;
                    
                    if (Route::has('pricing')) {
                        $pricingRoute = route('pricing');
                    } elseif (Route::has('pricing.index')) {
                        $pricingRoute = route('pricing.index');
                    } elseif (Route::has('frontend.pricing')) {
                        $pricingRoute = route('frontend.pricing');
                    } elseif (Route::has('plans')) {
                        $pricingRoute = route('plans');
                    } else {
                        $pricingRoute = url('/pricing');
                    }
                @endphp
                
                <a href="{{ $pricingRoute }}" 
                   class="gallery-outline-button nepali">
                    <i class="fas fa-tags"></i> योजनाहरू हेर्नुहोस्
                </a>
                
                <!-- 🚨 ADDED: BUTTON 4: MEAL GALLERY (Green with utensils icon) -->
                <a href="{{ route('menu-gallery') }}" 
                   class="gallery-meal-cta-button nepali">
                    <i class="fas fa-utensils"></i>
                    🍛 खानाको ग्यालरी
                </a>
            </div>
        </section>
    </div>
</div>

<!-- Enhanced Modal with Video Support -->
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
        <div class="modal-title nepali"></div>
        <div class="modal-description nepali"></div>
        <div class="modal-meta">
            <span class="modal-category nepali"></span>
            <span class="modal-date"></span>
            <span class="modal-hostel nepali"></span>
            <span class="modal-room nepali"></span>
        </div>
        <div class="modal-actions">
            <a href="#" class="modal-hostel-link nepali" style="display: none;">
                <i class="fas fa-external-link-alt"></i>
                होस्टल विवरण हेर्नुहोस्
            </a>
            <a href="#" class="modal-gallery-link nepali" style="display: none;">
                <i class="fas fa-images"></i>
                ग्यालरी हेर्नुहोस्
            </a>
        </div>
    </div>
</div>
@endsection

@push('scripts')
@vite(['resources/js/gallery.js'])
<script>
// ✅ GLOBAL VARIABLES for modal navigation
let currentModalIndex = 0;
let currentModalItems = [];
let currentModalType = null; // 'photo' or 'video'

// ✅ FIXED: Photo filter click handler - PHOTOS लागि मात्र
function handlePhotoFilterClick(filter) {
    console.log('Photo filter clicked:', filter);
    
    const currentUrl = window.location.href;
    const url = new URL(currentUrl);
    const params = url.searchParams;
    
    // ALWAYS SET TAB TO PHOTOS
    params.set('tab', 'photos');
    
    // Clear other photo filters and page
    params.delete('page');
    params.delete('category'); // यो server-side parameter हो तस्बिर फिल्टरको लागि
    
    if (filter !== 'all') {
        params.set('category', filter);
    }
    
    console.log('Redirecting to:', url.toString());
    window.location.href = url.toString();
    return false;
}

// ✅ FIXED: Video filter click handler - VIDEOS लागि मात्र
function handleVideoFilterClick(filter) {
    console.log('Video filter clicked:', filter);
    
    const currentUrl = window.location.href;
    const url = new URL(currentUrl);
    const params = url.searchParams;
    
    // ALWAYS SET TAB TO VIDEOS
    params.set('tab', 'videos');
    
    // Clear other video filters
    params.delete('page');
    params.delete('video_filter');
    
    if (filter !== 'all') {
        params.set('video_filter', filter);
    }
    
    console.log('Redirecting to:', url.toString());
    window.location.href = url.toString();
    return false;
}

// ✅ FIXED: Video modal function with proper event handling
function openVideoModal(videoCard) {
    const modal = document.querySelector('.gallery-modal');
    const modalContent = modal.querySelector('.modal-content');
    const modalTitle = modal.querySelector('.modal-title');
    const modalDescription = modal.querySelector('.modal-description');
    const modalCategory = modal.querySelector('.modal-category');
    const modalDate = modal.querySelector('.modal-date');
    const modalHostel = modal.querySelector('.modal-hostel');
    const modalHostelLink = modal.querySelector('.modal-hostel-link');
    const modalGalleryLink = modal.querySelector('.modal-gallery-link');
    
    // Get video data from data attributes
    const videoModalUrl = videoCard.getAttribute('data-video-modal-url');
    const title = videoCard.getAttribute('data-title');
    const description = videoCard.getAttribute('data-description');
    const category = videoCard.getAttribute('data-category');
    const date = videoCard.getAttribute('data-date');
    const hostel = videoCard.getAttribute('data-hostel');
    const hostelSlug = videoCard.getAttribute('data-hostel-slug');
    const galleryUrl = videoCard.getAttribute('data-gallery-url');
    
    console.log('Opening video modal with URL:', videoModalUrl);
    console.log('Video data:', { title, description, category, date, hostel, hostelSlug, galleryUrl });
    
    // Set modal content
    modalTitle.textContent = title || '';
    modalDescription.textContent = description || '';
    modalCategory.textContent = category || '';
    modalDate.textContent = date || '';
    modalHostel.textContent = hostel || '';
    
    // Set hostel link if available
    if (hostelSlug) {
        modalHostelLink.href = `/hostels/${hostelSlug}`;
        modalHostelLink.style.display = 'inline-flex';
    } else {
        modalHostelLink.style.display = 'none';
    }
    
    // Set gallery link if available
    if (galleryUrl && galleryUrl !== '#') {
        modalGalleryLink.href = galleryUrl;
        modalGalleryLink.style.display = 'inline-flex';
    } else {
        modalGalleryLink.style.display = 'none';
    }
    
    // Clear and add video
    modalContent.innerHTML = '';
    
    if (videoModalUrl && videoModalUrl.includes('youtube.com') || videoModalUrl.includes('youtu.be')) {
        // Fix YouTube URL
        let embedUrl = videoModalUrl;
        if (embedUrl.includes('youtu.be/')) {
            // Convert youtu.be to youtube.com/embed
            const videoId = embedUrl.split('youtu.be/')[1].split('?')[0];
            embedUrl = `https://www.youtube.com/embed/${videoId}?autoplay=1&rel=0&controls=1&showinfo=0`;
        } else if (!embedUrl.includes('embed')) {
            // Convert watch URL to embed URL
            embedUrl = embedUrl.replace('watch?v=', 'embed/');
            embedUrl = embedUrl.split('?')[0] + '?autoplay=1&rel=0&controls=1&showinfo=0';
        }
        
        console.log('Creating YouTube iframe with URL:', embedUrl);
        
        const iframe = document.createElement('iframe');
        iframe.src = embedUrl;
        iframe.width = '100%';
        iframe.height = '500';
        iframe.allow = 'autoplay; encrypted-media; picture-in-picture';
        iframe.allowFullscreen = true;
        iframe.style.border = 'none';
        iframe.style.borderRadius = '10px';
        modalContent.appendChild(iframe);
    } else if (videoModalUrl) {
        console.log('Creating video element with URL:', videoModalUrl);
        
        const video = document.createElement('video');
        video.src = videoModalUrl;
        video.controls = true;
        video.autoplay = true;
        video.style.width = '100%';
        video.style.borderRadius = '10px';
        modalContent.appendChild(video);
    } else {
        console.error('No video URL found');
        modalContent.innerHTML = '<p class="nepali" style="padding: 2rem; text-align: center;">भिडियो लोड गर्न सकिएन। कृपया पछि प्रयास गर्नुहोस्।</p>';
    }
    
    // Set modal type and items for navigation
    currentModalType = 'video';
    currentModalItems = Array.from(document.querySelectorAll('.video-card'));
    currentModalIndex = currentModalItems.indexOf(videoCard);
    
    console.log(`Modal navigation: ${currentModalItems.length} items, current index: ${currentModalIndex}`);
    
    // Show modal
    modal.classList.add('active');
    document.body.style.overflow = 'hidden';
}

// ✅ NEW: Photo modal function
function openPhotoModal(galleryItem) {
    const modal = document.querySelector('.gallery-modal');
    const modalContent = modal.querySelector('.modal-content');
    const modalTitle = modal.querySelector('.modal-title');
    const modalDescription = modal.querySelector('.modal-description');
    const modalCategory = modal.querySelector('.modal-category');
    const modalDate = modal.querySelector('.modal-date');
    const modalHostel = modal.querySelector('.modal-hostel');
    const modalHostelLink = modal.querySelector('.modal-hostel-link');
    const modalGalleryLink = modal.querySelector('.modal-gallery-link');

    // Get data from the gallery item
    const mediaUrl = galleryItem.getAttribute('data-media-url');
    const hdUrl = galleryItem.getAttribute('data-hd-url');
    const title = galleryItem.getAttribute('data-title');
    const description = galleryItem.getAttribute('data-description');
    const category = galleryItem.getAttribute('data-category');
    const date = galleryItem.getAttribute('data-date');
    const hostel = galleryItem.getAttribute('data-hostel');
    const hostelSlug = galleryItem.getAttribute('data-hostel-slug');
    const galleryUrl = galleryItem.getAttribute('data-gallery-url');

    // Set modal info
    modalTitle.textContent = title || '';
    modalDescription.textContent = description || '';
    modalCategory.textContent = category || '';
    modalDate.textContent = date || '';
    modalHostel.textContent = hostel || '';

    // Set hostel link if available
    if (hostelSlug) {
        modalHostelLink.href = `/hostels/${hostelSlug}`;
        modalHostelLink.style.display = 'inline-flex';
    } else {
        modalHostelLink.style.display = 'none';
    }
    
    // Set gallery link if available
    if (galleryUrl && galleryUrl !== '#') {
        modalGalleryLink.href = galleryUrl;
        modalGalleryLink.style.display = 'inline-flex';
    } else {
        modalGalleryLink.style.display = 'none';
    }

    // Clear modal content and add image
    modalContent.innerHTML = '';
    const img = document.createElement('img');
    img.src = hdUrl || mediaUrl;
    img.alt = title;
    img.style.width = '100%';
    img.style.borderRadius = '10px';
    img.style.maxHeight = '80vh';
    img.style.objectFit = 'contain';
    modalContent.appendChild(img);

    // Set modal type and items for navigation
    currentModalType = 'photo';
    currentModalItems = Array.from(document.querySelectorAll('.gallery-item'));
    currentModalIndex = currentModalItems.indexOf(galleryItem);
    
    console.log(`Modal navigation: ${currentModalItems.length} items, current index: ${currentModalIndex}`);

    // Show modal
    modal.classList.add('active');
    document.body.style.overflow = 'hidden';
}

// ✅ NEW: Update modal with next/previous item
function updateModal() {
    if (currentModalItems.length === 0 || currentModalIndex < 0 || currentModalIndex >= currentModalItems.length) {
        console.error('Invalid modal state');
        return;
    }
    
    const currentItem = currentModalItems[currentModalIndex];
    
    if (currentModalType === 'photo') {
        openPhotoModal(currentItem);
    } else if (currentModalType === 'video') {
        openVideoModal(currentItem);
    }
}

// ✅ NEW: Navigate to next item
function navigateNext() {
    if (currentModalItems.length === 0) return;
    
    currentModalIndex++;
    if (currentModalIndex >= currentModalItems.length) {
        currentModalIndex = 0; // Loop back to first
    }
    
    updateModal();
}

// ✅ NEW: Navigate to previous item
function navigatePrev() {
    if (currentModalItems.length === 0) return;
    
    currentModalIndex--;
    if (currentModalIndex < 0) {
        currentModalIndex = currentModalItems.length - 1; // Loop to last
    }
    
    updateModal();
}

// ✅ NEW: Close modal
function closeModal() {
    const modal = document.querySelector('.gallery-modal');
    const modalContent = modal.querySelector('.modal-content');
    
    // Clear modal content
    modalContent.innerHTML = '';
    
    // Hide modal
    modal.classList.remove('active');
    document.body.style.overflow = 'auto';
    
    // Reset navigation variables
    currentModalItems = [];
    currentModalIndex = 0;
    currentModalType = null;
}

// ✅ NEW: Gallery item click handler
function handleGalleryItemClick(event) {
    // Don't open modal if clicking on links inside the item
    if (event.target.closest('a') || event.target.tagName === 'A') {
        return;
    }
    console.log('Gallery item clicked');
    openPhotoModal(this);
}

// ✅ NEW: Initialize gallery item events
function initGalleryItemEvents() {
    const galleryItems = document.querySelectorAll('.gallery-item');
    console.log(`Found ${galleryItems.length} gallery items`);
    galleryItems.forEach(item => {
        // Remove existing event listeners to avoid duplicates
        item.removeEventListener('click', handleGalleryItemClick);
        // Add new event listener
        item.addEventListener('click', handleGalleryItemClick);
    });
}

// ✅ FIXED: Videos placeholder hide logic
document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM loaded - initializing gallery');
    
    function hideAllSpinners() {
        const placeholder = document.querySelector('.videos-placeholder');
        if (placeholder) {
            placeholder.style.display = 'none';
            placeholder.classList.add('hidden');
        }
        
        const spinners = document.querySelectorAll('.spinner, .gallery-loading');
        spinners.forEach(spinner => {
            spinner.style.display = 'none';
            spinner.classList.add('hidden');
        });
    }
    
    const urlParams = new URLSearchParams(window.location.search);
    const tabParam = urlParams.get('tab');
    
    if (tabParam === 'videos') {
        console.log('On videos tab - checking videos');
        
        const videosCountElement = document.getElementById('videos-count');
        const serverVideoCount = videosCountElement ? parseInt(videosCountElement.getAttribute('data-count')) : 0;
        const clientVideoCount = document.querySelectorAll('.video-card').length;
        
        const hasVideos = serverVideoCount > 0 || clientVideoCount > 0;
        
        console.log(`Found ${serverVideoCount} server videos and ${clientVideoCount} client video cards`);
        
        if (hasVideos) {
            console.log('Hiding videos placeholder immediately');
            hideAllSpinners();
        } else {
            hideAllSpinners();
        }
    } else {
        hideAllSpinners();
    }
    
    setTimeout(function() {
        hideAllSpinners();
    }, 100);
    
    // ✅ FIXED: Initialize all event listeners when DOM loads
    console.log('Initializing event listeners...');
    
    // 1. Video card click events
    function initVideoCardEvents() {
        const videoCards = document.querySelectorAll('.video-card');
        console.log(`Found ${videoCards.length} video cards`);
        
        videoCards.forEach(card => {
            // Remove existing event listeners to avoid duplicates
            card.removeEventListener('click', handleVideoCardClick);
            
            // Add new event listener
            card.addEventListener('click', handleVideoCardClick);
        });
    }
    
    // Video card click handler
    function handleVideoCardClick(event) {
        // Don't open modal if clicking on links inside the card
        if (event.target.closest('a') || event.target.tagName === 'A') {
            return;
        }
        
        console.log('Video card clicked');
        openVideoModal(this);
    }
    
    // 2. Modal close button
    const modalClose = document.querySelector('.modal-close');
    if (modalClose) {
        modalClose.addEventListener('click', closeModal);
    }
    
    // 3. Modal navigation buttons
    const modalPrev = document.querySelector('.modal-prev');
    const modalNext = document.querySelector('.modal-next');
    
    if (modalPrev) {
        modalPrev.addEventListener('click', navigatePrev);
    }
    
    if (modalNext) {
        modalNext.addEventListener('click', navigateNext);
    }
    
    // 4. Close modal when clicking on backdrop
    const modal = document.querySelector('.gallery-modal');
    if (modal) {
        modal.addEventListener('click', function(event) {
            if (event.target === this) {
                closeModal();
            }
        });
    }
    
    // 5. Close modal with Escape key
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            closeModal();
        }
        // Arrow key navigation
        else if (event.key === 'ArrowLeft') {
            navigatePrev();
        }
        else if (event.key === 'ArrowRight') {
            navigateNext();
        }
    });
    
    // 6. CORRECTED: Filter buttons event listeners - PROPER SEPARATION
    const currentTab = tabParam || 'photos';
    const filterButtons = document.querySelectorAll('.filter-controls .filter-btn[data-filter]');
    
    filterButtons.forEach(btn => {
        // Remove any existing event listeners
        btn.removeEventListener('click', handlePhotoFilterClick);
        btn.removeEventListener('click', handleVideoFilterClick);
        
        if (currentTab === 'videos') {
            // Videos tab ma videos filter matra
            btn.addEventListener('click', function(e) {
                const filter = this.getAttribute('data-filter');
                handleVideoFilterClick(filter);
            });
        } else {
            // Photos tab ma photos filter matra
            btn.addEventListener('click', function(e) {
                const filter = this.getAttribute('data-filter');
                handlePhotoFilterClick(filter);
            });
        }
    });
    
    // 7. Initialize video card events
    initVideoCardEvents();
    
    // 8. Initialize gallery item events for photos
    initGalleryItemEvents();
    
    // 9. Re-initialize events after tab switch
    const tabButtons = document.querySelectorAll('.tab-btn');
    tabButtons.forEach(btn => {
        btn.addEventListener('click', function() {
            setTimeout(function() {
                initVideoCardEvents();
                initGalleryItemEvents();
            }, 300);
        });
    });
    
    // 10. URL parameter check and tab activation
    const currentTabFromURL = urlParams.get('tab') || 'photos';
    
    console.log('Current tab from URL:', currentTabFromURL);
    
    // Ensure the correct tab is active
    const tabButtonsAll = document.querySelectorAll('.tab-btn');
    tabButtonsAll.forEach(btn => {
        btn.classList.remove('active');
        if (btn.href.includes(`tab=${currentTabFromURL}`)) {
            btn.classList.add('active');
        }
    });
    
    // Ensure the correct tab content is shown
    const tabContents = document.querySelectorAll('.tab-content');
    tabContents.forEach(content => {
        content.classList.remove('active');
        if (content.id === `${currentTabFromURL}-tab`) {
            content.classList.add('active');
        }
    });
    
    // 11. Initialize events after a short delay (for dynamic content)
    setTimeout(function() {
        initVideoCardEvents();
        initGalleryItemEvents();
    }, 500);
});

window.addEventListener('load', function() {
    console.log('Window loaded - final check for videos placeholder');
    
    const placeholder = document.querySelector('.videos-placeholder');
    if (placeholder) {
        placeholder.style.display = 'none';
        placeholder.classList.add('hidden');
    }
    
    const spinners = document.querySelectorAll('.spinner, .gallery-loading');
    spinners.forEach(spinner => {
        spinner.style.display = 'none';
        spinner.classList.add('hidden');
    });
});

// ✅ FIXED: Tab click handler - FIXED URL ISSUE
function handleTabClick(event, tabName) {
    event.preventDefault();
    
    const currentUrl = window.location.href;
    const url = new URL(currentUrl);
    const params = url.searchParams;
    
    params.set('tab', tabName);
    params.delete('page');
    
    // Clear video-specific filters when switching tabs
    if (tabName !== 'videos') {
        params.delete('video_category');
        params.delete('video_filter');
    }
    
    console.log('Redirecting to:', url.toString());
    window.location.href = url.toString();
    return false;
}

// ✅ FIXED: SIMPLE AND RELIABLE SERVER-SIDE HOSTEL FILTER FIX
window.handleHostelFilterChange = function() {
    console.log('Hostel filter changed');
    const hostelFilter = document.getElementById('hostelFilter');
    if (!hostelFilter) {
        console.error('Hostel filter not found');
        return false;
    }
    
    const selectedValue = hostelFilter.value;
    console.log('Selected value:', selectedValue);
    
    const currentUrl = window.location.href;
    const url = new URL(currentUrl);
    const params = url.searchParams;
    
    params.delete('page');
    params.delete('hostel_gender');
    params.delete('hostel_id');
    
    if (selectedValue === 'boys' || selectedValue === 'girls') {
        params.set('hostel_gender', selectedValue);
        console.log('Setting hostel_gender to:', selectedValue);
    } else if (selectedValue && selectedValue !== '') {
        params.set('hostel_id', selectedValue);
        console.log('Setting hostel_id to:', selectedValue);
    }
    
    // Keep current tab, if not set default to photos
    if (!params.has('tab')) {
        params.set('tab', 'photos');
    }
    
    console.log('Redirecting to:', url.toString());
    window.location.href = url.toString();
    return false;
};

// ✅ FIXED: Set initial value from URL when DOM loads
document.addEventListener('DOMContentLoaded', function() {
    const hostelFilter = document.getElementById('hostelFilter');
    if (hostelFilter) {
        const urlParams = new URLSearchParams(window.location.search);
        const hostelGender = urlParams.get('hostel_gender');
        const hostelId = urlParams.get('hostel_id');
        
        console.log('Current URL params:', { hostelGender, hostelId });
        
        if (hostelGender === 'boys' || hostelGender === 'girls') {
            hostelFilter.value = hostelGender;
        } else if (hostelId) {
            hostelFilter.value = hostelId;
        }
    }
    
    // ✅ FIXED: Hide the global no-results message for videos tab
    const urlParams = new URLSearchParams(window.location.search);
    const tabParam = urlParams.get('tab');
    
    if (tabParam === 'videos') {
        const noResults = document.querySelector('.no-results');
        if (noResults) {
            noResults.classList.add('hidden');
            noResults.style.display = 'none';
        }
        
        // Also hide the pagination if only one page
        const pagination = document.querySelector('.pagination-container');
        if (pagination) {
            const paginationItems = pagination.querySelectorAll('ul.pagination li');
            if (paginationItems.length <= 3) { // Only showing 1 page
                pagination.style.display = 'none';
            }
        }
    }
});

// ✅ FIXED: Video category click handler - ALWAYS SETS TAB TO VIDEOS
function handleVideoCategoryClick(category) {
    console.log('Video category clicked:', category);
    
    const currentUrl = window.location.href;
    const url = new URL(currentUrl);
    const params = url.searchParams;
    
    // ✅ FIX: Always set tab to videos when clicking video category
    params.set('tab', 'videos');
    
    // Clear other video filters
    params.delete('page');
    
    if (category === 'all') {
        params.delete('video_category');
    } else {
        params.set('video_category', category);
    }
    
    console.log('Redirecting to:', url.toString());
    window.location.href = url.toString();
    return false;
}

// ✅ FIXED: Re-initialize video cards when videos tab becomes active
document.addEventListener('visibilitychange', function() {
    if (!document.hidden) {
        setTimeout(function() {
            const videoCards = document.querySelectorAll('.video-card');
            videoCards.forEach(card => {
                card.removeEventListener('click', handleVideoCardClick);
                card.addEventListener('click', handleVideoCardClick);
            });
            
            const galleryItems = document.querySelectorAll('.gallery-item');
            galleryItems.forEach(item => {
                item.removeEventListener('click', handleGalleryItemClick);
                item.addEventListener('click', handleGalleryItemClick);
            });
        }, 100);
    }
});

// ✅ FIXED: Handle video card click helper function
function handleVideoCardClick(event) {
    // Don't open modal if clicking on links inside the card
    if (event.target.closest('a') || event.target.tagName === 'A') {
        return;
    }
    
    console.log('Video card clicked');
    openVideoModal(this);
}
</script>
@endpush