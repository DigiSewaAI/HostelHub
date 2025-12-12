{{-- resources/views/public/hostels/themes/vibrant.blade.php --}}

@extends('layouts.public')

@php
    use Illuminate\Support\Facades\Storage;
    use Illuminate\Support\Str;
@endphp

@push('head')
@vite(['resources/css/public-themes.css'])
<style>
    :root {
        --theme-color: {{ $hostel->theme_color ?? '#6366F1' }};
        --vibrant-pink: #EC4899;
        --vibrant-purple: #8B5CF6;
        --vibrant-blue: #06B6D4;
        --vibrant-green: #10B981;
        --vibrant-orange: #F59E0B;
        --vibrant-yellow: #FCD34D;
        --dark-bg: #0F172A;
        --glass-bg: rgba(255, 255, 255, 0.1);
        --glass-border: rgba(255, 255, 255, 0.2);
    }

    /* ✅ FIXED: Improved Page Loading - Remove initial spinner */
    .vibrant-body {
        background: linear-gradient(135deg, #0F172A 0%, #1E293B 50%, #334155 100%);
        font-family: 'Poppins', 'Inter', sans-serif;
        color: #F1F5F9;
        min-height: 100vh;
        overflow-x: hidden;
        opacity: 1 !important;
        visibility: visible !important;
        display: block !important;
    }

    /* ✅ FIXED: Page Load Animation */
    .page-load-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: linear-gradient(135deg, #0F172A 0%, #1E293B 100%);
        display: flex;
        justify-content: center;
        align-items: center;
        z-index: 9999;
        transition: opacity 0.5s ease, visibility 0.5s ease;
    }

    .page-load-overlay.hidden {
        opacity: 0;
        visibility: hidden;
    }

    .loading-spinner {
        width: 60px;
        height: 60px;
        border: 4px solid rgba(255, 255, 255, 0.1);
        border-top: 4px solid var(--vibrant-pink);
        border-radius: 50%;
        animation: spin 1s linear infinite;
    }

    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }

    /* ✅ FIXED: Modal for Image/Video Viewing */
    .vibrant-modal {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.95);
        z-index: 9998;
        align-items: center;
        justify-content: center;
        padding: 2rem;
        opacity: 0;
        transition: opacity 0.3s ease;
    }

    .vibrant-modal.active {
        display: flex;
        opacity: 1;
    }

    .modal-content {
        max-width: 90%;
        max-height: 90%;
        position: relative;
        background: var(--dark-bg);
        border-radius: 20px;
        overflow: hidden;
        border: 2px solid var(--vibrant-pink);
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.8);
        animation: modalAppear 0.3s ease;
    }

    @keyframes modalAppear {
        from {
            transform: scale(0.9);
            opacity: 0;
        }
        to {
            transform: scale(1);
            opacity: 1;
        }
    }

    .modal-media {
        width: 100%;
        height: auto;
        max-height: 70vh;
        object-fit: contain;
        display: block;
    }

    .modal-video {
        width: 100%;
        height: 70vh;
        border: none;
    }

    .modal-close {
        position: absolute;
        top: 15px;
        right: 15px;
        background: var(--vibrant-pink);
        color: white;
        border: none;
        width: 40px;
        height: 40px;
        border-radius: 50%;
        font-size: 1.2rem;
        cursor: pointer;
        z-index: 10;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s ease;
    }

    .modal-close:hover {
        background: var(--vibrant-purple);
        transform: rotate(90deg);
    }

    .modal-info {
        padding: 1.5rem;
        background: rgba(0, 0, 0, 0.8);
        color: white;
    }

    .modal-title {
        font-size: 1.5rem;
        margin-bottom: 0.5rem;
        color: white;
    }

    .modal-description {
        color: #E2E8F0;
        font-size: 1rem;
        line-height: 1.5;
    }

    /* ✅ FIXED: Video Player Styles */
    .video-player-container {
        width: 100%;
        height: 400px;
        background: #000;
        position: relative;
    }

    .external-video-link {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        padding: 3rem;
        text-align: center;
        color: white;
        text-decoration: none;
        height: 100%;
    }

    .external-video-link:hover {
        color: var(--vibrant-pink);
    }

    /* ✅ FIXED: Gallery Clickable Area */
    .gallery-item {
        cursor: pointer;
        position: relative;
        border-radius: 16px;
        overflow: hidden;
        aspect-ratio: 1;
        transition: all 0.3s ease;
        border: 2px solid transparent;
        background: linear-gradient(135deg, var(--vibrant-pink), var(--vibrant-blue), var(--vibrant-purple));
        background-clip: padding-box;
        height: 280px;
    }

    .gallery-item:hover {
        transform: translateY(-10px) scale(1.05);
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.4);
    }

    .gallery-item img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.3s ease;
        display: block !important;
        opacity: 1 !important;
        visibility: visible !important;
        position: absolute;
        top: 0;
        left: 0;
    }

    .gallery-item:hover img {
        transform: scale(1.1);
    }

    /* Fix broken images */
    img[src*="undefined"],
    img[src*="null"],
    img[src=""],
    img:not([src]) {
        content: url('{{ asset("images/default-room.png") }}') !important;
        opacity: 0.7 !important;
    }

    /* Video Icon Styles */
    .video-icon-overlay {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        z-index: 1;
        background: rgba(0, 0, 0, 0.8);
        width: 60px;
        height: 60px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 3px solid var(--vibrant-pink);
    }

    .video-icon-overlay i {
        color: white;
        font-size: 1.5rem;
    }

    .gallery-item.youtube .video-icon-overlay {
        background: rgba(255, 0, 0, 0.8);
        border-color: #FF0000;
    }

    .gallery-item.video .video-icon-overlay {
        background: rgba(6, 182, 212, 0.8);
        border-color: var(--vibrant-blue);
    }

    /* Gallery Overlay Styles */
    .gallery-overlay {
        position: absolute;
        inset: 0;
        background: rgba(0, 0, 0, 0.7);
        display: flex;
        align-items: center;
        justify-content: center;
        opacity: 0;
        transition: all 0.3s ease;
        padding: 1rem;
        z-index: 2;
    }

    .gallery-item:hover .gallery-overlay {
        opacity: 1;
    }

    .gallery-overlay-content {
        color: white;
        text-align: center;
        transform: translateY(1rem);
        transition: transform 0.3s ease;
    }

    .gallery-item:hover .gallery-overlay-content {
        transform: translateY(0);
    }

    /* Rest of the existing CSS remains the same */
    .vibrant-bg-animation {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        z-index: -1;
        opacity: 0.3;
    }

    .floating-circle {
        position: absolute;
        border-radius: 50%;
        background: radial-gradient(circle, var(--vibrant-pink) 0%, transparent 70%);
        animation: float 6s ease-in-out infinite;
    }

    .circle-1 { width: 200px; height: 200px; top: 10%; left: 10%; animation-delay: 0s; }
    .circle-2 { width: 150px; height: 150px; top: 60%; left: 80%; animation-delay: 2s; background: radial-gradient(circle, var(--vibrant-blue) 0%, transparent 70%); }
    .circle-3 { width: 100px; height: 100px; top: 80%; left: 20%; animation-delay: 4s; background: radial-gradient(circle, var(--vibrant-purple) 0%, transparent 70%); }

    @keyframes float {
        0%, 100% { transform: translateY(0px) rotate(0deg); }
        50% { transform: translateY(-20px) rotate(180deg); }
    }

    .glass-container {
        background: var(--glass-bg);
        backdrop-filter: blur(20px);
        border: 1px solid var(--glass-border);
        border-radius: 24px;
        padding: 2rem;
        margin-bottom: 2rem;
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
        position: relative;
        overflow: hidden;
    }

    .glass-container::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255,255,255,0.1), transparent);
        transition: 0.5s;
    }

    .glass-container:hover::before {
        left: 100%;
    }

    .vibrant-header {
        background: linear-gradient(135deg, var(--vibrant-purple) 0%, var(--vibrant-pink) 100%);
        padding: 4rem 0;
        position: relative;
        overflow: hidden;
        margin-bottom: 3rem;
    }

    .vibrant-header::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100" fill="%23ffffff20"><polygon points="50,0 100,50 50,100 0,50"/></svg>');
        background-size: 80px;
        animation: slide 20s linear infinite;
    }

    @keyframes slide {
        0% { transform: translateX(0) translateY(0); }
        100% { transform: translateX(-80px) translateY(-80px); }
    }

    .vibrant-logo {
        width: 120px;
        height: 120px;
        border-radius: 50%;
        overflow: hidden;
        border: 3px solid transparent;
        background: linear-gradient(135deg, var(--vibrant-pink), var(--vibrant-blue), var(--vibrant-purple));
        background-clip: padding-box;
        box-shadow: 0 0 20px rgba(236, 72, 153, 0.5);
        animation: glow 2s ease-in-out infinite alternate;
        margin: 0 auto;
    }

    @keyframes glow {
        from { box-shadow: 0 0 20px rgba(236, 72, 153, 0.5); }
        to { box-shadow: 0 0 30px rgba(139, 92, 246, 0.8), 0 0 40px rgba(6, 182, 212, 0.6); }
    }

    .vibrant-logo img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        border-radius: 50%;
    }

    .vibrant-title {
        font-size: 3.5rem;
        font-weight: 800;
        background: linear-gradient(135deg, #FFFFFF 0%, #F1F5F9 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        text-align: center;
        margin-bottom: 1rem;
        text-shadow: 0 0 30px rgba(255, 255, 255, 0.5);
    }

    .vibrant-subtitle {
        font-size: 1.3rem;
        color: #E2E8F0;
        text-align: center;
        margin-bottom: 2rem;
    }

    /* ✅ FIXED: Stats with lazy loading */
    .vibrant-stats {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
        gap: 1.5rem;
        margin: 2rem 0;
        opacity: 0;
        transform: translateY(20px);
        transition: all 0.5s ease;
    }

    .vibrant-stats.loaded {
        opacity: 1;
        transform: translateY(0);
    }

    .vibrant-stat {
        background: var(--glass-bg);
        backdrop-filter: blur(10px);
        border: 1px solid var(--glass-border);
        border-radius: 16px;
        padding: 1.5rem;
        text-align: center;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }

    .vibrant-stat::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 4px;
        background: linear-gradient(90deg, var(--vibrant-pink), var(--vibrant-blue), var(--vibrant-purple));
    }

    .vibrant-stat:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.4);
    }

    .stat-number {
        font-size: 2.8rem;
        font-weight: 900;
        background: linear-gradient(135deg, #FF0080, #FF8C00, #40E0D0, #FF0080);
        background-size: 300% 300%;
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        display: block;
        margin-bottom: 0.5rem;
        animation: shimmer 3s ease-in-out infinite;
        text-shadow: 0 0 30px rgba(255, 255, 255, 0.5);
    }

    @keyframes shimmer {
        0%, 100% { background-position: 0% 50%; }
        50% { background-position: 100% 50%; }
    }

    .stat-label {
        font-size: 0.9rem;
        color: #E2E8F0;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    .vibrant-btn {
        background: linear-gradient(135deg, var(--vibrant-pink), var(--vibrant-purple));
        color: white;
        border: none;
        border-radius: 50px;
        padding: 1rem 2rem;
        font-weight: 700;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.8rem;
        transition: all 0.3s ease;
        box-shadow: 0 4px 15px rgba(236, 72, 153, 0.4);
        position: relative;
        overflow: hidden;
    }

    .vibrant-btn::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
        transition: 0.5s;
    }

    .vibrant-btn:hover::before {
        left: 100%;
    }

    .vibrant-btn:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 25px rgba(236, 72, 153, 0.6);
        color: white;
    }

    .btn-phone {
        background: linear-gradient(135deg, var(--vibrant-green), #059669);
    }

    .btn-secondary {
        background: linear-gradient(135deg, var(--vibrant-blue), #0369A1);
    }

    .vibrant-social {
        display: flex;
        justify-content: center;
        gap: 1rem;
        margin: 2rem 0;
    }

    .social-icon {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        text-decoration: none;
        font-size: 1.2rem;
        transition: all 0.3s ease;
        background: var(--glass-bg);
        backdrop-filter: blur(10px);
        border: 1px solid var(--glass-border);
        position: relative;
        overflow: hidden;
    }

    .social-icon::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: conic-gradient(from 0deg, var(--vibrant-pink), var(--vibrant-blue), var(--vibrant-purple), var(--vibrant-pink));
        opacity: 0;
        transition: opacity 0.3s ease;
    }

    .social-icon:hover::before {
        opacity: 1;
    }

    .social-icon i {
        position: relative;
        z-index: 1;
    }

    .section-header {
        text-align: center;
        margin-bottom: 3rem;
        position: relative;
    }

    .section-title {
        font-size: 2.5rem;
        font-weight: 800;
        background: linear-gradient(135deg, var(--vibrant-pink), var(--vibrant-blue));
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        margin-bottom: 1rem;
    }

    .section-divider {
        width: 100px;
        height: 4px;
        background: linear-gradient(90deg, var(--vibrant-pink), var(--vibrant-blue), var(--vibrant-purple));
        margin: 0 auto;
        border-radius: 2px;
    }

    .about-content {
        font-size: 1.1rem;
        line-height: 1.8;
        color: #E2E8F0;
        text-align: justify;
    }

    /* ✅ FIXED: Gallery with lazy loading */
    .vibrant-gallery {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 1.5rem;
        margin-top: 2rem;
        opacity: 0;
        transform: translateY(20px);
        transition: all 0.5s ease;
    }

    .vibrant-gallery.loaded {
        opacity: 1;
        transform: translateY(0);
    }

    .facilities-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 1.5rem;
        margin-top: 2rem;
    }

    .facility-card {
        background: var(--glass-bg);
        backdrop-filter: blur(10px);
        border: 1px solid var(--glass-border);
        border-radius: 16px;
        padding: 1.5rem;
        display: flex;
        align-items: center;
        gap: 1rem;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }

    .facility-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 4px;
        height: 100%;
        background: linear-gradient(135deg, var(--vibrant-pink), var(--vibrant-blue));
    }

    .facility-card:hover {
        transform: translateX(10px);
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
    }

    .facility-icon {
        width: 50px;
        height: 50px;
        background: linear-gradient(135deg, var(--vibrant-pink), var(--vibrant-purple));
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 1.2rem;
        flex-shrink: 0;
    }

    /* UPDATED: Reviews Slider System */
    .reviews-slider-container {
        position: relative;
        margin: 2rem 0;
        overflow: hidden;
        border-radius: 20px;
    }

    .reviews-slider {
        display: flex;
        transition: transform 0.5s ease-in-out;
        gap: 2rem;
    }

    .review-slide {
        min-width: 100%;
        background: var(--glass-bg);
        backdrop-filter: blur(10px);
        border: 1px solid var(--glass-border);
        border-radius: 20px;
        padding: 2rem;
        position: relative;
        transition: all 0.3s ease;
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
    }

    .review-slide::before {
        content: '"';
        position: absolute;
        top: -20px;
        left: 20px;
        font-size: 4rem;
        color: var(--vibrant-pink);
        opacity: 0.3;
    }

    .slider-controls {
        display: flex;
        justify-content: center;
        gap: 1rem;
        margin-top: 2rem;
    }

    .slider-btn {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        background: linear-gradient(135deg, var(--vibrant-pink), var(--vibrant-purple));
        border: none;
        color: white;
        font-size: 1.2rem;
        cursor: pointer;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 4px 15px rgba(236, 72, 153, 0.4);
    }

    .slider-btn:hover {
        transform: scale(1.1);
        box-shadow: 0 6px 20px rgba(236, 72, 153, 0.6);
    }

    .slider-dots {
        display: flex;
        justify-content: center;
        gap: 0.5rem;
        margin-top: 1rem;
    }

    .slider-dot {
        width: 12px;
        height: 12px;
        border-radius: 50%;
        background: var(--glass-border);
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .slider-dot.active {
        background: linear-gradient(135deg, var(--vibrant-pink), var(--vibrant-purple));
        transform: scale(1.2);
    }

    /* UPDATED: Google Map Alternative */
    .location-container {
        background: var(--glass-bg);
        backdrop-filter: blur(10px);
        border: 1px solid var(--glass-border);
        border-radius: 20px;
        padding: 2rem;
        margin-top: 2rem;
        position: relative;
        overflow: hidden;
    }

    .location-container::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 4px;
        background: linear-gradient(90deg, var(--vibrant-pink), var(--vibrant-blue), var(--vibrant-purple));
    }

    .location-info {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 2rem;
        align-items: start;
    }

    .location-details {
        padding: 1.5rem;
    }

    .location-map-placeholder {
        background: linear-gradient(135deg, var(--vibrant-purple), var(--vibrant-pink));
        border-radius: 12px;
        height: 300px;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        color: white;
        text-align: center;
        position: relative;
        overflow: hidden;
    }

    .location-map-placeholder::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100" fill="%23ffffff30"><path d="M50,10 C70,10 90,30 90,50 C90,70 70,90 50,90 C30,90 10,70 10,50 C10,30 30,10 50,10 Z M50,30 C60,30 70,40 70,50 C70,60 60,70 50,70 C40,70 30,60 30,50 C30,40 40,30 50,30 Z"/></svg>');
        background-size: 200px;
    }

    .map-icon {
        font-size: 3rem;
        margin-bottom: 1rem;
        z-index: 1;
    }

    .contact-single-line {
        display: flex;
        justify-content: space-between;
        align-items: center;
        background: var(--glass-bg);
        backdrop-filter: blur(10px);
        border: 1px solid var(--glass-border);
        border-radius: 16px;
        padding: 2rem;
        margin-top: 2rem;
        position: relative;
        overflow: hidden;
    }

    .contact-single-line::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 4px;
        background: linear-gradient(90deg, var(--vibrant-pink), var(--vibrant-blue), var(--vibrant-purple));
    }

    .contact-item {
        display: flex;
        align-items: center;
        gap: 1rem;
        flex: 1;
        justify-content: center;
        padding: 0 1rem;
        position: relative;
    }

    .contact-item:not(:last-child)::after {
        content: '';
        position: absolute;
        right: 0;
        top: 50%;
        transform: translateY(-50%);
        width: 2px;
        height: 40px;
        background: var(--glass-border);
    }

    .contact-icon-small {
        width: 45px;
        height: 45px;
        background: linear-gradient(135deg, var(--vibrant-pink), var(--vibrant-purple));
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 1.1rem;
        flex-shrink: 0;
    }

    .contact-info {
        display: flex;
        flex-direction: column;
    }

    .contact-label {
        font-size: 0.8rem;
        color: #94A3B8;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 0.2rem;
    }

    .contact-value {
        font-size: 1rem;
        color: #E2E8F0;
        font-weight: 600;
    }

    /* FIXED: Contact Form Styles with Visible Placeholders */
    .contact-form {
        background: var(--glass-bg);
        backdrop-filter: blur(10px);
        border: 1px solid var(--glass-border);
        border-radius: 20px;
        padding: 2rem;
        margin-top: 2rem;
    }

    .form-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1.5rem;
        margin-bottom: 1.5rem;
    }

    .form-input {
        background: rgba(255, 255, 255, 0.1);
        border: 1px solid var(--glass-border);
        border-radius: 12px;
        padding: 1rem;
        color: white;
        font-size: 1rem;
        transition: all 0.3s ease;
    }

    .form-input::placeholder {
        color: rgba(255, 255, 255, 0.7);
        opacity: 1;
    }

    .form-input:focus {
        outline: none;
        border-color: var(--vibrant-pink);
        box-shadow: 0 0 0 3px rgba(236, 72, 153, 0.2);
    }

    .form-textarea {
        background: rgba(255, 255, 255, 0.1);
        border: 1px solid var(--glass-border);
        border-radius: 12px;
        padding: 1rem;
        color: white;
        font-size: 1rem;
        width: 100%;
        min-height: 120px;
        resize: vertical;
        margin-bottom: 1.5rem;
        transition: all 0.3s ease;
    }

    .form-textarea::placeholder {
        color: rgba(255, 255, 255, 0.7);
        opacity: 1;
    }

    .form-textarea:focus {
        outline: none;
        border-color: var(--vibrant-pink);
        box-shadow: 0 0 0 3px rgba(236, 72, 153, 0.2);
    }

    .action-buttons {
        display: flex;
        justify-content: center;
        gap: 1.5rem;
        margin: 3rem 0;
        flex-wrap: wrap;
    }

    .fixed-phone-btn {
        position: fixed;
        bottom: 25px;
        right: 25px;
        z-index: 1000;
        width: 60px;
        height: 60px;
        background: linear-gradient(135deg, var(--vibrant-green), #059669);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        text-decoration: none;
        box-shadow: 0 6px 20px rgba(16, 185, 129, 0.4);
        transition: all 0.3s ease;
        border: 2px solid white;
    }

    .fixed-phone-btn:hover {
        transform: scale(1.1);
        box-shadow: 0 8px 25px rgba(16, 185, 129, 0.6);
        color: white;
    }

    .fixed-phone-btn i {
        font-size: 1.3rem;
    }

    @media (max-width: 768px) {
        .vibrant-title {
            font-size: 2.5rem;
        }
        
        .vibrant-stats {
            grid-template-columns: repeat(2, 1fr);
        }
        
        .form-grid {
            grid-template-columns: 1fr;
        }
        
        .action-buttons {
            flex-direction: column;
            align-items: center;
        }
        
        .vibrant-btn {
            width: 100%;
            max-width: 300px;
            justify-content: center;
        }
        
        .vibrant-gallery {
            grid-template-columns: 1fr;
        }

        .contact-single-line {
            flex-direction: column;
            gap: 1.5rem;
            padding: 1.5rem;
        }

        .contact-item:not(:last-child)::after {
            display: none;
        }

        .contact-item {
            padding: 0;
            justify-content: flex-start;
            width: 100%;
        }

        .fixed-phone-btn {
            width: 55px;
            height: 55px;
            bottom: 20px;
            right: 20px;
        }

        .location-info {
            grid-template-columns: 1fr;
        }

        .reviews-slider {
            gap: 1rem;
        }

        /* Mobile modal */
        .modal-content {
            max-width: 95%;
            max-height: 80%;
        }

        .modal-media {
            max-height: 50vh;
        }

        .modal-video {
            height: 50vh;
        }
    }

    .nepali-font {
        font-family: 'Mangal', 'Arial', sans-serif;
        line-height: 1.6;
        color: #F1F5F9;
    }

    ::-webkit-scrollbar {
        width: 8px;
    }

    ::-webkit-scrollbar-track {
        background: #1E293B;
    }

    ::-webkit-scrollbar-thumb {
        background: linear-gradient(135deg, var(--vibrant-pink), var(--vibrant-purple));
        border-radius: 4px;
    }

    ::-webkit-scrollbar-thumb:hover {
        background: linear-gradient(135deg, var(--vibrant-blue), var(--vibrant-pink));
    }
</style>

<!-- Add Poppins Font for Vibrant Theme -->
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet>
@endpush

@section('content')
<!-- ✅ FIXED: Page Load Overlay -->
<div class="page-load-overlay" id="pageLoadOverlay">
    <div class="loading-spinner"></div>
</div>

<!-- ✅ FIXED: Modal for Image/Video Viewing -->
<div class="vibrant-modal" id="vibrantModal">
    <div class="modal-content">
        <button class="modal-close" id="modalClose">&times;</button>
        <div id="modalMediaContainer">
            <!-- Content will be inserted here by JavaScript -->
        </div>
        <div class="modal-info">
            <h3 class="modal-title nepali-font" id="modalTitle"></h3>
            <p class="modal-description nepali-font" id="modalDescription"></p>
        </div>
    </div>
</div>

<div class="vibrant-body" id="mainContent" style="opacity: 0; transition: opacity 0.5s ease;">
    <!-- Animated Background -->
    <div class="vibrant-bg-animation">
        <div class="floating-circle circle-1"></div>
        <div class="floating-circle circle-2"></div>
        <div class="floating-circle circle-3"></div>
    </div>

    <!-- Vibrant Header -->
    <header class="vibrant-header">
        <div class="container" style="max-width: 1200px; margin: 0 auto; padding: 0 20px; position: relative; z-index: 2;">
            <!-- Preview Alert -->
            @if(isset($preview) && $preview)
            <div class="glass-container" style="margin-bottom: 2rem;">
                <div style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 1rem;">
                    <div style="display: flex; align-items: center; gap: 1rem;">
                        <div style="width: 40px; height: 40px; background: linear-gradient(135deg, var(--vibrant-orange), #EA580C); border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                            <i class="fas fa-eye" style="color: white; font-size: 1rem;"></i>
                        </div>
                        <span style="color: white; font-weight: 700; font-size: 1.1rem;" class="nepali-font">यो पूर्वावलोकन मोडमा हो</span>
                    </div>
                    <a href="{{ route('owner.public-page.edit') }}" class="vibrant-btn" style="background: linear-gradient(135deg, var(--vibrant-orange), #EA580C);">
                        <i class="fas fa-edit"></i>
                        <span class="nepali-font">सम्पादन गर्नुहोस्</span>
                    </a>
                </div>
            </div>
            @endif

            <!-- Logo and Title -->
            <div style="text-align: center; margin-bottom: 2rem;">
                <div class="vibrant-logo">
                    @if($logo)
                        <img src="{{ $logo }}" alt="{{ $hostel->name }}" loading="lazy">
                    @else
                        <div style="width: 100%; height: 100%; background: linear-gradient(135deg, var(--vibrant-pink), var(--vibrant-blue), var(--vibrant-purple)); display: flex; align-items: center; justify-content: center;">
                            <i class="fas fa-building" style="color: white; font-size: 2.5rem;"></i>
                        </div>
                    @endif
                </div>
                
                <h1 class="vibrant-title nepali-font">{{ $hostel->name }}</h1>
                <p class="vibrant-subtitle nepali-font">
                    @if($hostel->city)
                    <i class="fas fa-map-marker-alt" style="color: var(--vibrant-blue); margin-right: 0.5rem;"></i>{{ $hostel->city }}
                    @endif
                    
                    @if($reviewCount > 0 && $avgRating > 0)
                    <span style="margin-left: 1rem;">
                        <i class="fas fa-star" style="color: var(--vibrant-orange);"></i>
                        {{ number_format($avgRating, 1) }} ({{ $reviewCount }} समीक्षा)
                    </span>
                    @endif
                </p>
            </div>

            <!-- UPDATED: Stats with Shiny Numbers -->
            <div class="vibrant-stats" id="vibrantStats">
                <div class="vibrant-stat">
                    <span class="stat-number">{{ $hostel->total_rooms ?? 0 }}</span>
                    <span class="stat-label nepali-font">कुल कोठा</span>
                </div>
                <div class="vibrant-stat">
                    <span class="stat-number">{{ $hostel->available_rooms ?? 0 }}</span>
                    <span class="stat-label nepali-font">उपलब्ध कोठा</span>
                </div>
                <div class="vibrant-stat">
                    <span class="stat-number">{{ $studentCount }}</span>
                    <span class="stat-label nepali-font">विद्यार्थी</span>
                </div>
                <div class="vibrant-stat">
                    <span class="stat-number">{{ $reviewCount }}</span>
                    <span class="stat-label nepali-font">समीक्षा</span>
                </div>
            </div>

            <!-- Social Media -->
            <div class="vibrant-social">
                @if($hostel->facebook_url)
                    <a href="{{ $hostel->facebook_url }}" target="_blank" class="social-icon">
                        <i class="fab fa-facebook-f"></i>
                    </a>
                @endif
                @if($hostel->instagram_url)
                    <a href="{{ $hostel->instagram_url }}" target="_blank" class="social-icon">
                        <i class="fab fa-instagram"></i>
                    </a>
                @endif
                @if($hostel->whatsapp_number)
                    <a href="https://wa.me/{{ $hostel->whatsapp_number }}" target="_blank" class="social-icon">
                        <i class="fab fa-whatsapp"></i>
                    </a>
                @endif
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <div class="container" style="max-width: 1200px; margin: 0 auto; padding: 0 20px;">
        <!-- Action Buttons -->
        <div class="action-buttons">
            @if($hostel->contact_phone)
            <a href="tel:{{ $hostel->contact_phone }}" class="vibrant-btn btn-phone">
                <i class="fas fa-phone"></i>
                <span class="nepali-font">फोन गर्नुहोस्</span>
            </a>
            @endif
            <a href="{{ route('hostels.index') }}" class="vibrant-btn btn-secondary">
                <i class="fas fa-building"></i>
                <span class="nepali-font">हाम्रा अन्य होस्टलहरू</span>
            </a>
            <a href="#reviews" class="vibrant-btn">
                <i class="fas fa-star"></i>
                <span class="nepali-font">समीक्षा लेख्नुहोस्</span>
            </a>
        </div>

        <!-- About Section -->
        <section class="glass-container">
            <div class="section-header">
                <h2 class="section-title nepali-font">हाम्रो बारेमा</h2>
                <div class="section-divider"></div>
            </div>
            <div class="about-content nepali-font">
                @if($hostel->description)
                    {{ $hostel->description }}
                @else
                    <div style="text-align: center; padding: 3rem; color: #94A3B8;">
                        <div style="width: 80px; height: 80px; background: var(--glass-bg); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1.5rem; border: 2px solid var(--glass-border);">
                            <i class="fas fa-file-alt" style="font-size: 2rem; color: var(--vibrant-blue);"></i>
                        </div>
                        <p style="font-size: 1.2rem; font-style: italic;">यस होस्टलको बारेमा विवरण उपलब्ध छैन।</p>
                    </div>
                @endif
            </div>
        </section>

        <!-- ✅ FIXED: Dynamic Gallery Section with Click Functionality -->
        <section class="glass-container">
            <div class="section-header">
                <h2 class="section-title nepali-font">ग्यालरी</h2>
                <div class="section-divider"></div>
            </div>
            
            @php
                // ✅ BULLETPROOF GALLERY FETCHING
                $galleries = collect();
                
                // Method 1: Try Modern theme's working method
                if(method_exists($hostel, 'galleries')) {
                    $galleries = $hostel->galleries()->where('is_active', 1)->get();
                }
                
                // Method 2: If empty, try direct database query
                if($galleries->isEmpty() && isset($hostel->id)) {
                    $galleries = \App\Models\Gallery::where('hostel_id', $hostel->id)
                                                    ->where('is_active', 1)
                                                    ->get();
                }
                
                // Method 3: Last resort - any galleries regardless of status
                if($galleries->isEmpty() && isset($hostel->id)) {
                    $galleries = \App\Models\Gallery::where('hostel_id', $hostel->id)->get();
                }
                
                // Limit to 12 for display
                $displayGalleries = $galleries->take(12);
            @endphp
            
            @if($displayGalleries->count() > 0)
                <div class="vibrant-gallery" id="vibrantGallery">
                    @foreach($displayGalleries as $gallery)
                        @php
                            // ✅ BULLETPROOF IMAGE URL RESOLUTION
                            $imageUrl = asset('images/default-room.png');
                            $mediaUrl = $imageUrl;
                            
                            // Priority 1: media_url from database
                            if(!empty($gallery->media_url)) {
                                $imageUrl = $gallery->media_url;
                                $mediaUrl = $gallery->media_url;
                            }
                            // Priority 2: Build from media_path
                            elseif(!empty($gallery->media_path)) {
                                // Check if it's already a full URL
                                if(filter_var($gallery->media_path, FILTER_VALIDATE_URL)) {
                                    $imageUrl = $gallery->media_path;
                                    $mediaUrl = $gallery->media_path;
                                }
                                // Build storage URL
                                else {
                                    $imageUrl = Storage::disk('public')->url($gallery->media_path);
                                    $mediaUrl = Storage::disk('public')->url($gallery->media_path);
                                }
                            }
                            // Priority 3: thumbnail_url as last resort
                            elseif(!empty($gallery->thumbnail_url)) {
                                $imageUrl = $gallery->thumbnail_url;
                                $mediaUrl = $gallery->thumbnail_url;
                            }
                        @endphp
                        
                        <div class="gallery-item 
                            @if($gallery->media_type === 'external_video') youtube 
                            @elseif($gallery->media_type === 'video') video 
                            @endif"
                            data-media-type="{{ $gallery->media_type }}"
                            data-media-url="{{ $mediaUrl }}"
                            data-title="{{ $gallery->title }}"
                            data-description="{{ $gallery->description ?? '' }}"
                            onclick="openVibrantMediaModal(this)">
                            
                            <img src="{{ $imageUrl }}" 
                                 alt="{{ $gallery->title }}"
                                 loading="lazy"
                                 onerror="this.src='{{ asset('images/default-room.png') }}'; this.style.opacity='0.7';">
                            
                            @if($gallery->media_type === 'external_video')
                                <div class="video-icon-overlay">
                                    <i class="fab fa-youtube"></i>
                                </div>
                            @elseif($gallery->media_type === 'video')
                                <div class="video-icon-overlay">
                                    <i class="fas fa-video"></i>
                                </div>
                            @endif
                            
                            <div class="gallery-overlay">
                                <div class="gallery-overlay-content">
                                    <h4 class="nepali-font" style="font-size: 1.1rem; font-weight: bold; margin-bottom: 0.5rem;">{{ $gallery->title }}</h4>
                                    @if($gallery->description)
                                        <p class="nepali-font" style="font-size: 0.9rem; opacity: 0.9;">{{ Str::limit($gallery->description, 60) }}</p>
                                    @endif
                                    @if($gallery->is_featured)
                                        <span class="nepali-font" style="display: inline-block; background: var(--vibrant-yellow); color: var(--dark-bg); font-size: 0.8rem; padding: 0.2rem 0.5rem; border-radius: 1rem; margin-top: 0.5rem; font-weight: bold;">फिचर्ड</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div style="grid-column: 1 / -1; text-align: center; padding: 3rem; color: #94A3B8;">
                    <div style="width: 100px; height: 100px; background: var(--glass-bg); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1.5rem; border: 2px solid var(--glass-border);">
                        <i class="fas fa-images" style="font-size: 2.5rem; color: var(--vibrant-purple);"></i>
                    </div>
                    <h3 style="font-size: 1.5rem; color: white; margin-bottom: 0.8rem;" class="nepali-font">कुनै तस्बिर उपलब्ध छैन</h3>
                    <p style="font-size: 1.1rem;" class="nepali-font">यस होस्टलको ग्यालरी तस्बिरहरू चाँहि उपलब्ध छैनन्।</p>
                </div>
            @endif
        </section>

        <!-- Facilities Section -->
        @if(!empty($facilities) && count($facilities) > 0)
        <section class="glass-container">
            <div class="section-header">
                <h2 class="section-title nepali-font">सुविधाहरू</h2>
                <div class="section-divider"></div>
            </div>
            <div class="facilities-grid">
                @foreach($facilities as $facility)
                    @if(trim($facility))
                    <div class="facility-card">
                        <div class="facility-icon">
                            <i class="fas fa-check"></i>
                        </div>
                        <span class="nepali-font" style="color: #E2E8F0; font-weight: 500;">{{ trim($facility) }}</span>
                    </div>
                    @endif
                @endforeach
            </div>
        </section>
        @endif

        <!-- UPDATED: Location Section without Google Maps API -->
        <section class="glass-container">
            <div class="section-header">
                <h2 class="section-title nepali-font">हाम्रो स्थान</h2>
                <div class="section-divider"></div>
            </div>
            <div class="location-container">
                <div class="location-info">
                    <div class="location-details">
                        <h3 style="color: white; margin-bottom: 1rem; font-size: 1.5rem;" class="nepali-font">ठेगाना विवरण</h3>
                        @if($hostel->address)
                            <p style="color: #E2E8F0; line-height: 1.6; margin-bottom: 1.5rem;" class="nepali-font">{{ $hostel->address }}</p>
                        @endif
                        
                        @if($hostel->contact_phone)
                        <div style="display: flex; align-items: center; gap: 1rem; margin-bottom: 1rem;">
                            <div style="width: 40px; height: 40px; background: linear-gradient(135deg, var(--vibrant-green), #059669); border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                                <i class="fas fa-phone" style="color: white; font-size: 1rem;"></i>
                            </div>
                            <div>
                                <span style="color: #94A3B8; font-size: 0.9rem;" class="nepali-font">फोन नम्बर</span>
                                <br>
                                <a href="tel:{{ $hostel->contact_phone }}" style="color: white; text-decoration: none; font-weight: 600;">{{ $hostel->contact_phone }}</a>
                            </div>
                        </div>
                        @endif

                        <div style="margin-top: 2rem;">
                            <a href="https://www.google.com/maps/search/?api=1&query={{ urlencode($hostel->address) }}" 
                               target="_blank" 
                               class="vibrant-btn" 
                               style="background: linear-gradient(135deg, var(--vibrant-pink), var(--vibrant-purple));">
                                <i class="fas fa-map-marker-alt"></i>
                                <span class="nepali-font">Google Map मा हेर्नुहोस्</span>
                            </a>
                        </div>
                    </div>
                    
                    <div class="location-map-placeholder">
                        <i class="fas fa-map-marked-alt map-icon"></i>
                        <h4 style="color: white; margin-bottom: 0.5rem; z-index: 1;" class="nepali-font">स्थान</h4>
                        <p style="color: rgba(255,255,255,0.9); z-index: 1;" class="nepali-font">Google Map लोड गर्न क्लिक गर्नुहोस्</p>
                        <a href="https://www.google.com/maps/search/?api=1&query={{ urlencode($hostel->address) }}" 
                           target="_blank" 
                           style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; z-index: 2;"></a>
                    </div>
                </div>
            </div>
        </section>

        <!-- UPDATED: Reviews Section with Slider -->
        <section class="glass-container" id="reviews">
            <div class="section-header">
                <h2 class="section-title nepali-font">विद्यार्थी समीक्षाहरू</h2>
                <div class="section-divider"></div>
            </div>
            
            @if($reviewCount > 0)
                <div class="reviews-slider-container">
                    <div class="reviews-slider" id="reviewsSlider">
                        @foreach($reviews as $review)
                        <div class="review-slide">
                            <div class="review-header">
                                <div class="reviewer-name nepali-font">{{ $review->student->user->name ?? 'अज्ञात विद्यार्थी' }}</div>
                                <div class="review-date">{{ $review->created_at->format('Y-m-d') }}</div>
                            </div>
                            <div class="review-rating">
                                @for($i = 1; $i <= 5; $i++)
                                    <i class="fas fa-star {{ $i <= $review->rating ? '' : 'far' }}"></i>
                                @endfor
                                <span style="margin-left: 0.5rem; color: var(--vibrant-orange); font-weight: 700;">{{ $review->rating }}/5</span>
                            </div>
                            <div class="review-content nepali-font">{{ $review->comment }}</div>
                            
                            @if($review->reply)
                            <div style="background: rgba(236, 72, 153, 0.1); border-left: 4px solid var(--vibrant-pink); padding: 1rem; margin-top: 1rem; border-radius: 8px;">
                                <div style="display: flex; align-items: start; gap: 0.8rem;">
                                    <i class="fas fa-reply" style="color: var(--vibrant-pink); margin-top: 0.2rem;"></i>
                                    <div>
                                        <strong style="color: var(--vibrant-pink); font-size: 0.9rem;" class="nepali-font">होस्टलको जवाफ:</strong>
                                        <p style="color: #E2E8F0; margin-top: 0.5rem; font-size: 0.9rem;" class="nepali-font">{{ $review->reply }}</p>
                                    </div>
                                </div>
                            </div>
                            @endif
                        </div>
                        @endforeach
                    </div>

                    @if($reviewCount > 1)
                    <div class="slider-controls">
                        <button class="slider-btn prev-btn">
                            <i class="fas fa-chevron-left"></i>
                        </button>
                        <button class="slider-btn next-btn">
                            <i class="fas fa-chevron-right"></i>
                        </button>
                    </div>
                    
                    <div class="slider-dots" id="sliderDots">
                        @for($i = 0; $i < $reviewCount; $i++)
                            <div class="slider-dot {{ $i === 0 ? 'active' : '' }}" data-slide="{{ $i }}"></div>
                        @endfor
                    </div>
                    @endif
                </div>
            @else
                <div style="text-align: center; padding: 3rem;">
                    <div style="width: 100px; height: 100px; background: var(--glass-bg); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1.5rem; border: 2px solid var(--glass-border);">
                        <i class="fas fa-comment-slash" style="font-size: 2.5rem; color: var(--vibrant-blue);"></i>
                    </div>
                    <h3 style="font-size: 1.5rem; color: white; margin-bottom: 0.8rem;" class="nepali-font">अहिलेसम्म कुनै समीक्षा छैन</h3>
                    <p style="font-size: 1.1rem; color: #94A3B8;" class="nepali-font">यो होस्टलको पहिलो समीक्षा दिनुहोस्!</p>
                </div>
            @endif
        </section>

        <!-- Contact Information -->
        <section class="glass-container">
            <div class="section-header">
                <h2 class="section-title nepali-font">सम्पर्क जानकारी</h2>
                <div class="section-divider"></div>
            </div>
            <div class="contact-single-line">
                @if($hostel->contact_person)
                <div class="contact-item">
                    <div class="contact-icon-small">
                        <i class="fas fa-user"></i>
                    </div>
                    <div class="contact-info">
                        <span class="contact-label nepali-font">सम्पर्क व्यक्ति</span>
                        <span class="contact-value">{{ $hostel->contact_person }}</span>
                    </div>
                </div>
                @endif
                
                @if($hostel->contact_phone)
                <div class="contact-item">
                    <div class="contact-icon-small">
                        <i class="fas fa-phone"></i>
                    </div>
                    <div class="contact-info">
                        <span class="contact-label nepali-font">फोन नम्बर</span>
                        <a href="tel:{{ $hostel->contact_phone }}" class="contact-value" style="text-decoration: none; color: inherit;">
                            {{ $hostel->contact_phone }}
                        </a>
                    </div>
                </div>
                @endif
                
                @if($hostel->contact_email)
                <div class="contact-item">
                    <div class="contact-icon-small">
                        <i class="fas fa-envelope"></i>
                    </div>
                    <div class="contact-info">
                        <span class="contact-label nepali-font">इमेल</span>
                        <a href="mailto:{{ $hostel->contact_email }}" class="contact-value" style="text-decoration: none; color: inherit;">
                            {{ $hostel->contact_email }}
                        </a>
                    </div>
                </div>
                @endif
                
                @if($hostel->address)
                <div class="contact-item">
                    <div class="contact-icon-small">
                        <i class="fas fa-map-marker-alt"></i>
                    </div>
                    <div class="contact-info">
                        <span class="contact-label nepali-font">ठेगाना</span>
                        <span class="contact-value nepali-font">{{ $hostel->address }}</span>
                    </div>
                </div>
                @endif
            </div>
        </section>

        <!-- Contact Form -->
        <section class="glass-container">
            <div class="section-header">
                <h2 class="section-title nepali-font">सम्पर्क फर्म</h2>
                <div class="section-divider"></div>
            </div>
            <div class="contact-form">
                <form action="{{ route('hostel.contact', $hostel->id) }}" method="POST">
                    @csrf
                    <div class="form-grid">
                        <input type="text" name="name" required placeholder="तपाईंको नाम" class="form-input nepali-font">
                        <input type="email" name="email" required placeholder="इमेल ठेगाना" class="form-input">
                    </div>
                    <textarea name="message" required placeholder="तपाईंको सन्देश यहाँ लेख्नुहोस्..." class="form-textarea nepali-font"></textarea>
                    <div style="text-align: center;">
                        <button type="submit" class="vibrant-btn" style="background: linear-gradient(135deg, var(--vibrant-green), #059669);">
                            <i class="fas fa-paper-plane"></i>
                            <span class="nepali-font">सन्देश पठाउनुहोस्</span>
                        </button>
                    </div>
                </form>
            </div>
        </section>
    </div>
</div>

<!-- Fixed Phone Button -->
@if($hostel->contact_phone)
    <a href="tel:{{ $hostel->contact_phone }}" class="fixed-phone-btn" aria-label="फोन गर्नुहोस्">
        <i class="fas fa-phone"></i>
    </a>
@endif

@push('scripts')
@vite(['resources/js/app.js'])
<!-- ✅ FIXED: Comprehensive JavaScript Solution -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    console.log('Vibrant Theme Initialized');
    
    // ✅ FIXED: Page Loading Optimization
    const pageLoadOverlay = document.getElementById('pageLoadOverlay');
    const mainContent = document.getElementById('mainContent');
    
    // Hide loader and show content immediately
    setTimeout(() => {
        if (pageLoadOverlay) {
            pageLoadOverlay.classList.add('hidden');
        }
        if (mainContent) {
            mainContent.style.opacity = '1';
        }
        
        // Animate stats and gallery after page loads
        animateElements();
    }, 300); // Reduced from 500ms to 300ms
    
    // ✅ FIXED: Animate elements with staggered delay
    function animateElements() {
        const stats = document.getElementById('vibrantStats');
        const gallery = document.getElementById('vibrantGallery');
        
        if (stats) {
            setTimeout(() => {
                stats.classList.add('loaded');
            }, 200);
        }
        
        if (gallery) {
            setTimeout(() => {
                gallery.classList.add('loaded');
            }, 400);
        }
    }
    
    // ✅ FIXED: Reviews Slider
    const slider = document.getElementById('reviewsSlider');
    const slides = document.querySelectorAll('.review-slide');
    const dots = document.querySelectorAll('.slider-dot');
    const prevBtn = document.querySelector('.prev-btn');
    const nextBtn = document.querySelector('.next-btn');
    
    if (slides.length > 1) {
        let currentSlide = 0;
        const totalSlides = slides.length;

        function updateSlider() {
            if (slider) {
                slider.style.transform = `translateX(-${currentSlide * 100}%)`;
            }
            
            // Update dots
            dots.forEach((dot, index) => {
                dot.classList.toggle('active', index === currentSlide);
            });
        }

        // Next button
        if (nextBtn) {
            nextBtn.addEventListener('click', function() {
                currentSlide = (currentSlide + 1) % totalSlides;
                updateSlider();
            });
        }

        // Previous button
        if (prevBtn) {
            prevBtn.addEventListener('click', function() {
                currentSlide = (currentSlide - 1 + totalSlides) % totalSlides;
                updateSlider();
            });
        }

        // Dot navigation
        dots.forEach((dot, index) => {
            dot.addEventListener('click', function() {
                currentSlide = index;
                updateSlider();
            });
        });

        // Auto slide every 5 seconds
        setInterval(function() {
            currentSlide = (currentSlide + 1) % totalSlides;
            updateSlider();
        }, 5000);
    }

    // ✅ FIXED: Image Modal Functionality
    const modal = document.getElementById('vibrantModal');
    const modalClose = document.getElementById('modalClose');
    const modalMediaContainer = document.getElementById('modalMediaContainer');
    const modalTitle = document.getElementById('modalTitle');
    const modalDescription = document.getElementById('modalDescription');
    
    // Function to open modal with media
    window.openVibrantMediaModal = function(element) {
        const mediaType = element.getAttribute('data-media-type');
        const mediaUrl = element.getAttribute('data-media-url');
        const title = element.getAttribute('data-title');
        const description = element.getAttribute('data-description');
        
        // Clear previous content
        modalMediaContainer.innerHTML = '';
        
        // Set title and description
        if (modalTitle) modalTitle.textContent = title;
        if (modalDescription) modalDescription.textContent = description;
        
        // Create media element based on type
        if (mediaType === 'image') {
            const img = document.createElement('img');
            img.src = mediaUrl;
            img.alt = title;
            img.className = 'modal-media';
            img.loading = 'eager';
            modalMediaContainer.appendChild(img);
        } 
        else if (mediaType === 'external_video') {
            // For YouTube videos
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
        }
        else if (mediaType === 'video') {
            // For uploaded videos
            const video = document.createElement('video');
            video.src = mediaUrl;
            video.controls = true;
            video.autoplay = true;
            video.className = 'modal-video';
            modalMediaContainer.appendChild(video);
        }
        
        // Show modal
        if (modal) {
            modal.classList.add('active');
            document.body.style.overflow = 'hidden';
        }
    };
    
    // Close modal
    if (modalClose) {
        modalClose.addEventListener('click', function() {
            if (modal) {
                modal.classList.remove('active');
                document.body.style.overflow = 'auto';
            }
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
    
    // ✅ FIXED: Fix gallery images
    document.querySelectorAll('.gallery-item img').forEach(img => {
        if (img.src.includes('undefined') || img.src.includes('null') || !img.src) {
            img.src = '{{ asset("images/default-room.png") }}';
            img.style.opacity = '0.7';
        }
    });
    
    // ✅ FIXED: Lazy loading for images
    const lazyImages = document.querySelectorAll('img[loading="lazy"]');
    
    if ('IntersectionObserver' in window) {
        const imageObserver = new IntersectionObserver((entries) => {
            entries.forEach((entry) => {
                if (entry.isIntersecting) {
                    const img = entry.target;
                    img.src = img.dataset.src || img.src;
                    imageObserver.unobserve(img);
                }
            });
        });
        
        lazyImages.forEach((img) => imageObserver.observe(img));
    }
    
    // ✅ FIXED: Smooth scrolling for anchor links
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
    
    // ✅ FIXED: Performance optimization - defer non-critical tasks
    setTimeout(() => {
        // Load background animations
        const circles = document.querySelectorAll('.floating-circle');
        circles.forEach(circle => {
            circle.style.animationPlayState = 'running';
        });
    }, 1000);
});
</script>
@endpush

<!-- External Dependencies -->
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
@endsection