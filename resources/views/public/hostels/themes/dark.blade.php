{{-- resources/views/public/hostels/themes/dark.blade.php --}}

@extends('layouts.public')

@push('head')
@vite(['resources/css/public-themes.css'])
<style>
    :root {
        --theme-color: {{ $hostel->theme_color ?? '#00D4FF' }};
        --neon-cyan: #00D4FF;
        --neon-pink: #FF00FF;
        --neon-green: #00FF88;
        --neon-purple: #9D00FF;
        --neon-orange: #FF6B00;
        --dark-1: #0A0A0A;
        --dark-2: #111111;
        --dark-3: #1A1A1A;
        --dark-4: #222222;
        --text-primary: #FFFFFF;
        --text-secondary: #B0B0B0;
    }

    /* Dark Theme - Completely Unique & Futuristic */
    .dark-body {
        background: linear-gradient(135deg, var(--dark-1) 0%, var(--dark-2) 50%, var(--dark-3) 100%);
        font-family: 'Orbitron', 'Rajdhani', sans-serif;
        color: var(--text-primary);
        min-height: 100vh;
        overflow-x: hidden;
        position: relative;
    }

    /* Matrix Background Effect */
    .matrix-bg {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: 
            radial-gradient(circle at 20% 80%, rgba(0, 212, 255, 0.1) 0%, transparent 50%),
            radial-gradient(circle at 80% 20%, rgba(255, 0, 255, 0.1) 0%, transparent 50%),
            radial-gradient(circle at 40% 40%, rgba(0, 255, 136, 0.05) 0%, transparent 50%);
        z-index: -1;
    }

    .matrix-code {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: 
            repeating-linear-gradient(0deg, transparent, transparent 2px, rgba(0, 212, 255, 0.03) 2px, rgba(0, 212, 255, 0.03) 4px),
            repeating-linear-gradient(90deg, transparent, transparent 2px, rgba(255, 0, 255, 0.03) 2px, rgba(255, 0, 255, 0.03) 4px);
        z-index: -1;
        animation: matrixMove 20s linear infinite;
    }

    @keyframes matrixMove {
        0% { transform: translateY(0) translateX(0); }
        100% { transform: translateY(-100px) translateX(-50px); }
    }

    /* Cyber Container */
    .cyber-container {
        background: rgba(10, 10, 10, 0.8);
        border: 1px solid var(--neon-cyan);
        border-radius: 0;
        position: relative;
        margin-bottom: 3rem;
        backdrop-filter: blur(10px);
        box-shadow: 
            0 0 20px rgba(0, 212, 255, 0.3),
            inset 0 0 20px rgba(0, 212, 255, 0.1);
        overflow: hidden;
    }

    .cyber-container::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 2px;
        background: linear-gradient(90deg, 
            transparent, 
            var(--neon-cyan), 
            var(--neon-pink), 
            var(--neon-green), 
            transparent);
        animation: scanline 3s linear infinite;
    }

    .cyber-container::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        height: 2px;
        background: linear-gradient(90deg, 
            transparent, 
            var(--neon-green), 
            var(--neon-pink), 
            var(--neon-cyan), 
            transparent);
        animation: scanline 3s linear infinite reverse;
    }

    @keyframes scanline {
        0% { transform: translateX(-100%); }
        100% { transform: translateX(100%); }
    }

    /* Cyber Header */
    .cyber-header {
        background: linear-gradient(135deg, 
            rgba(0, 212, 255, 0.1) 0%, 
            rgba(157, 0, 255, 0.1) 50%, 
            rgba(255, 0, 255, 0.1) 100%);
        padding: 4rem 0;
        position: relative;
        overflow: hidden;
        margin-bottom: 3rem;
        border-bottom: 3px solid var(--neon-cyan);
    }

    .cyber-header::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: 
            radial-gradient(circle at 30% 20%, rgba(0, 212, 255, 0.2) 0%, transparent 50%),
            radial-gradient(circle at 70% 80%, rgba(255, 0, 255, 0.2) 0%, transparent 50%);
        animation: pulse 4s ease-in-out infinite alternate;
    }

    @keyframes pulse {
        0% { opacity: 0.3; }
        100% { opacity: 0.7; }
    }

    /* Cyber Logo */
    .cyber-logo {
        width: 150px;
        height: 150px;
        border: 3px solid var(--neon-cyan);
        border-radius: 50%;
        overflow: hidden;
        background: var(--dark-2);
        margin: 0 auto 2rem;
        position: relative;
        box-shadow: 
            0 0 30px rgba(0, 212, 255, 0.5),
            inset 0 0 30px rgba(0, 212, 255, 0.2);
        animation: logoGlow 2s ease-in-out infinite alternate;
    }

    @keyframes logoGlow {
        0% { 
            box-shadow: 
                0 0 20px rgba(0, 212, 255, 0.5),
                inset 0 0 20px rgba(0, 212, 255, 0.2);
        }
        100% { 
            box-shadow: 
                0 0 40px rgba(0, 212, 255, 0.8),
                0 0 60px rgba(255, 0, 255, 0.4),
                inset 0 0 30px rgba(0, 212, 255, 0.3);
        }
    }

    .cyber-logo img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        border-radius: 50%;
        filter: brightness(1.2) contrast(1.1);
    }

    /* Cyber Typography */
    .cyber-title {
        font-size: 4rem;
        font-weight: 900;
        text-align: center;
        margin-bottom: 1rem;
        background: linear-gradient(135deg, var(--neon-cyan), var(--neon-pink), var(--neon-green));
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        text-shadow: 0 0 30px rgba(0, 212, 255, 0.5);
        letter-spacing: 2px;
        text-transform: uppercase;
    }

    .cyber-subtitle {
        font-size: 1.4rem;
        color: var(--text-secondary);
        text-align: center;
        margin-bottom: 2rem;
        letter-spacing: 1px;
    }

    /* Cyber Stats */
    .cyber-stats {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 2rem;
        margin: 3rem 0;
    }

    .cyber-stat {
        background: rgba(17, 17, 17, 0.9);
        border: 1px solid var(--neon-green);
        padding: 2rem;
        text-align: center;
        position: relative;
        overflow: hidden;
        transition: all 0.3s ease;
    }

    .cyber-stat::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(0, 255, 136, 0.2), transparent);
        transition: 0.5s;
    }

    .cyber-stat:hover::before {
        left: 100%;
    }

    .cyber-stat:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 30px rgba(0, 255, 136, 0.3);
    }

    .cyber-number {
        font-size: 3.5rem;
        font-weight: 900;
        background: linear-gradient(135deg, var(--neon-cyan), var(--neon-green));
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        display: block;
        margin-bottom: 0.5rem;
        text-shadow: 0 0 20px rgba(0, 212, 255, 0.5);
    }

    .cyber-label {
        font-size: 1rem;
        color: var(--text-secondary);
        text-transform: uppercase;
        letter-spacing: 2px;
        font-weight: 600;
    }

    /* Cyber Buttons */
    .cyber-btn {
        background: transparent;
        color: var(--neon-cyan);
        border: 2px solid var(--neon-cyan);
        padding: 1.2rem 2.5rem;
        font-size: 1.1rem;
        font-weight: 700;
        text-decoration: none;
        text-transform: uppercase;
        letter-spacing: 2px;
        position: relative;
        overflow: hidden;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 1rem;
        margin: 0.5rem;
    }

    .cyber-btn::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(0, 212, 255, 0.3), transparent);
        transition: 0.5s;
    }

    .cyber-btn:hover::before {
        left: 100%;
    }

    .cyber-btn:hover {
        background: rgba(0, 212, 255, 0.1);
        box-shadow: 
            0 0 20px rgba(0, 212, 255, 0.5),
            inset 0 0 20px rgba(0, 212, 255, 0.1);
        color: var(--text-primary);
        transform: translateY(-2px);
    }

    .btn-cyber-pink {
        border-color: var(--neon-pink);
        color: var(--neon-pink);
    }

    .btn-cyber-pink:hover {
        background: rgba(255, 0, 255, 0.1);
        box-shadow: 
            0 0 20px rgba(255, 0, 255, 0.5),
            inset 0 0 20px rgba(255, 0, 255, 0.1);
    }

    .btn-cyber-green {
        border-color: var(--neon-green);
        color: var(--neon-green);
    }

    .btn-cyber-green:hover {
        background: rgba(0, 255, 136, 0.1);
        box-shadow: 
            0 0 20px rgba(0, 255, 136, 0.5),
            inset 0 0 20px rgba(0, 255, 136, 0.1);
    }

    /* Cyber Social */
    .cyber-social {
        display: flex;
        justify-content: center;
        gap: 1.5rem;
        margin: 2rem 0;
    }

    .cyber-social-icon {
        width: 60px;
        height: 60px;
        border: 2px solid var(--neon-purple);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--neon-purple);
        text-decoration: none;
        font-size: 1.4rem;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }

    .cyber-social-icon::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: conic-gradient(from 0deg, var(--neon-cyan), var(--neon-pink), var(--neon-green), var(--neon-cyan));
        opacity: 0;
        transition: opacity 0.3s ease;
    }

    .cyber-social-icon:hover::before {
        opacity: 1;
    }

    .cyber-social-icon:hover {
        transform: scale(1.1) rotate(10deg);
        box-shadow: 0 0 20px rgba(157, 0, 255, 0.5);
    }

    .cyber-social-icon i {
        position: relative;
        z-index: 1;
    }

    /* Section Headers */
    .cyber-section-header {
        text-align: center;
        margin-bottom: 3rem;
        position: relative;
        padding: 2rem 0;
    }

    .cyber-section-title {
        font-size: 3rem;
        font-weight: 800;
        background: linear-gradient(135deg, var(--neon-cyan), var(--neon-pink));
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        margin-bottom: 1rem;
        text-transform: uppercase;
        letter-spacing: 3px;
        position: relative;
        display: inline-block;
    }

    .cyber-section-title::after {
        content: '';
        position: absolute;
        bottom: -10px;
        left: 50%;
        transform: translateX(-50%);
        width: 100px;
        height: 3px;
        background: linear-gradient(90deg, var(--neon-cyan), var(--neon-pink));
    }

    /* About Section */
    .cyber-about-content {
        font-size: 1.2rem;
        line-height: 1.8;
        color: var(--text-secondary);
        text-align: justify;
        padding: 2rem;
    }

    /* Cyber Gallery */
    .cyber-gallery {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 2rem;
        margin-top: 2rem;
    }

    .cyber-gallery-item {
        position: relative;
        border: 2px solid var(--neon-cyan);
        overflow: hidden;
        aspect-ratio: 1;
        transition: all 0.3s ease;
        background: var(--dark-2);
    }

    .cyber-gallery-item::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: linear-gradient(45deg, rgba(0, 212, 255, 0.2), rgba(255, 0, 255, 0.2));
        opacity: 0;
        transition: opacity 0.3s ease;
        z-index: 1;
    }

    .cyber-gallery-item:hover::before {
        opacity: 1;
    }

    .cyber-gallery-item img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.3s ease;
    }

    .cyber-gallery-item:hover {
        transform: translateY(-10px) scale(1.05);
        box-shadow: 
            0 10px 30px rgba(0, 212, 255, 0.5),
            0 0 50px rgba(255, 0, 255, 0.3);
    }

    .cyber-gallery-item:hover img {
        transform: scale(1.1);
    }

    /* Cyber Facilities */
    .cyber-facilities {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
        gap: 2rem;
        margin-top: 2rem;
    }

    .cyber-facility {
        background: rgba(17, 17, 17, 0.9);
        border: 1px solid var(--neon-green);
        padding: 2rem;
        display: flex;
        align-items: center;
        gap: 1.5rem;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }

    .cyber-facility::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 4px;
        height: 100%;
        background: linear-gradient(135deg, var(--neon-green), var(--neon-cyan));
    }

    .cyber-facility:hover {
        transform: translateX(10px);
        box-shadow: 0 10px 30px rgba(0, 255, 136, 0.3);
    }

    .cyber-facility-icon {
        width: 60px;
        height: 60px;
        background: linear-gradient(135deg, var(--neon-green), var(--neon-cyan));
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--dark-1);
        font-size: 1.5rem;
        flex-shrink: 0;
    }

    /* Cyber Reviews Carousel */
    .cyber-reviews-container {
        position: relative;
        margin: 3rem 0;
        overflow: hidden;
    }

    .cyber-reviews-track {
        display: flex;
        transition: transform 0.5s ease-in-out;
        gap: 2rem;
    }

    .cyber-review {
        min-width: 100%;
        background: rgba(17, 17, 17, 0.9);
        border: 1px solid var(--neon-purple);
        padding: 3rem;
        position: relative;
        transition: all 0.3s ease;
    }

    .cyber-review::before {
        content: '"';
        position: absolute;
        top: -20px;
        left: 30px;
        font-size: 6rem;
        color: var(--neon-purple);
        opacity: 0.3;
    }

    .cyber-review-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 2rem;
    }

    .cyber-reviewer {
        font-size: 1.4rem;
        font-weight: 700;
        color: var(--text-primary);
    }

    .cyber-review-date {
        color: var(--text-secondary);
        font-size: 1rem;
    }

    .cyber-review-rating {
        color: var(--neon-orange);
        margin: 1rem 0;
        font-size: 1.2rem;
    }

    .cyber-review-content {
        color: var(--text-secondary);
        line-height: 1.8;
        font-size: 1.1rem;
    }

    .cyber-review-controls {
        display: flex;
        justify-content: center;
        gap: 2rem;
        margin-top: 2rem;
    }

    /* Cyber Contact */
    .cyber-contact-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 2rem;
        margin-top: 2rem;
    }

    .cyber-contact-card {
        background: rgba(17, 17, 17, 0.9);
        border: 1px solid var(--neon-cyan);
        padding: 2.5rem;
        text-align: center;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }

    .cyber-contact-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 30px rgba(0, 212, 255, 0.3);
    }

    .cyber-contact-icon {
        width: 80px;
        height: 80px;
        background: linear-gradient(135deg, var(--neon-cyan), var(--neon-purple));
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--dark-1);
        font-size: 2rem;
        margin: 0 auto 1.5rem;
        box-shadow: 0 0 20px rgba(0, 212, 255, 0.5);
    }

    /* Cyber Form */
    .cyber-form {
        background: rgba(17, 17, 17, 0.9);
        border: 1px solid var(--neon-pink);
        padding: 3rem;
        margin-top: 2rem;
    }

    .cyber-form-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 2rem;
        margin-bottom: 2rem;
    }

    .cyber-form-input {
        background: transparent;
        border: 1px solid var(--neon-cyan);
        border-radius: 0;
        padding: 1.2rem;
        color: var(--text-primary);
        font-size: 1.1rem;
        transition: all 0.3s ease;
        font-family: 'Orbitron', sans-serif;
    }

    .cyber-form-input:focus {
        outline: none;
        border-color: var(--neon-pink);
        box-shadow: 0 0 20px rgba(255, 0, 255, 0.3);
    }

    .cyber-form-textarea {
        background: transparent;
        border: 1px solid var(--neon-cyan);
        border-radius: 0;
        padding: 1.2rem;
        color: var(--text-primary);
        font-size: 1.1rem;
        width: 100%;
        min-height: 150px;
        resize: vertical;
        margin-bottom: 2rem;
        transition: all 0.3s ease;
        font-family: 'Orbitron', sans-serif;
    }

    .cyber-form-textarea:focus {
        outline: none;
        border-color: var(--neon-pink);
        box-shadow: 0 0 20px rgba(255, 0, 255, 0.3);
    }

    /* Action Buttons */
    .cyber-action-buttons {
        display: flex;
        justify-content: center;
        gap: 2rem;
        margin: 4rem 0;
        flex-wrap: wrap;
    }

    /* WhatsApp Floating Button */
    .cyber-whatsapp-btn {
        position: fixed;
        bottom: 30px;
        right: 30px;
        z-index: 1000;
        width: 70px;
        height: 70px;
        background: linear-gradient(135deg, #25D366, #128C7E);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--dark-1);
        text-decoration: none;
        box-shadow: 
            0 0 20px rgba(37, 211, 102, 0.5),
            0 0 40px rgba(18, 140, 126, 0.3);
        transition: all 0.3s ease;
        border: 2px solid var(--dark-1);
        animation: whatsappPulse 2s ease-in-out infinite;
    }

    @keyframes whatsappPulse {
        0%, 100% { 
            box-shadow: 
                0 0 20px rgba(37, 211, 102, 0.5),
                0 0 40px rgba(18, 140, 126, 0.3);
        }
        50% { 
            box-shadow: 
                0 0 30px rgba(37, 211, 102, 0.8),
                0 0 60px rgba(18, 140, 126, 0.5);
        }
    }

    .cyber-whatsapp-btn:hover {
        transform: scale(1.1) rotate(10deg);
    }

    .cyber-whatsapp-btn i {
        font-size: 1.8rem;
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .cyber-title {
            font-size: 2.5rem;
        }
        
        .cyber-stats {
            grid-template-columns: repeat(2, 1fr);
        }
        
        .cyber-form-grid {
            grid-template-columns: 1fr;
        }
        
        .cyber-action-buttons {
            flex-direction: column;
            align-items: center;
        }
        
        .cyber-btn {
            width: 100%;
            max-width: 300px;
            justify-content: center;
        }
        
        .cyber-gallery {
            grid-template-columns: 1fr;
        }

        .cyber-facilities {
            grid-template-columns: 1fr;
        }

        .cyber-contact-grid {
            grid-template-columns: 1fr;
        }

        .cyber-whatsapp-btn {
            width: 60px;
            height: 60px;
            bottom: 20px;
            right: 20px;
        }
    }

    .nepali-font {
        font-family: 'Mangal', 'Arial', sans-serif;
        line-height: 1.6;
        color: var(--text-primary);
    }

    /* Custom Scrollbar */
    ::-webkit-scrollbar {
        width: 12px;
    }

    ::-webkit-scrollbar-track {
        background: var(--dark-2);
    }

    ::-webkit-scrollbar-thumb {
        background: linear-gradient(135deg, var(--neon-cyan), var(--neon-pink));
        border-radius: 0;
    }

    ::-webkit-scrollbar-thumb:hover {
        background: linear-gradient(135deg, var(--neon-green), var(--neon-purple));
    }
</style>

<!-- Add Cyber Fonts -->
<link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;500;600;700;800;900&family=Rajdhani:wght@300;400;500;600;700&display=swap" rel="stylesheet">
@endpush

@section('content')
<div class="dark-body">
    <!-- Matrix Background -->
    <div class="matrix-bg"></div>
    <div class="matrix-code"></div>

    <!-- Cyber Header -->
    <header class="cyber-header">
        <div class="container" style="max-width: 1200px; margin: 0 auto; padding: 0 20px; position: relative; z-index: 2;">
            <!-- Preview Alert -->
            @if(isset($preview) && $preview)
            <div class="cyber-container" style="margin-bottom: 2rem;">
                <div style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 1rem; padding: 1.5rem;">
                    <div style="display: flex; align-items: center; gap: 1rem;">
                        <div style="width: 40px; height: 40px; background: linear-gradient(135deg, var(--neon-orange), #FF8C00); border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                            <i class="fas fa-eye" style="color: var(--dark-1); font-size: 1rem;"></i>
                        </div>
                        <span style="color: var(--text-primary); font-weight: 700; font-size: 1.1rem;" class="nepali-font">यो पूर्वावलोकन मोडमा हो</span>
                    </div>
                    <a href="{{ route('owner.public-page.edit') }}" class="cyber-btn btn-cyber-pink">
                        <i class="fas fa-edit"></i>
                        <span class="nepali-font">सम्पादन गर्नुहोस्</span>
                    </a>
                </div>
            </div>
            @endif

            <!-- Logo and Title -->
            <div style="text-align: center; margin-bottom: 2rem;">
                <div class="cyber-logo">
                    @if($logo)
                        <img src="{{ $logo }}" alt="{{ $hostel->name }}">
                    @else
                        <div style="width: 100%; height: 100%; background: linear-gradient(135deg, var(--neon-cyan), var(--neon-pink), var(--neon-purple)); display: flex; align-items: center; justify-content: center;">
                            <i class="fas fa-building" style="color: var(--dark-1); font-size: 3rem;"></i>
                        </div>
                    @endif
                </div>
                
                <h1 class="cyber-title nepali-font">{{ $hostel->name }}</h1>
                <p class="cyber-subtitle nepali-font">
                    @if($hostel->city)
                    <i class="fas fa-map-marker-alt" style="color: var(--neon-cyan); margin-right: 0.5rem;"></i>{{ $hostel->city }}
                    @endif
                    
                    @if($reviewCount > 0 && $avgRating > 0)
                    <span style="margin-left: 1rem;">
                        <i class="fas fa-star" style="color: var(--neon-orange);"></i>
                        {{ number_format($avgRating, 1) }} ({{ $reviewCount }} समीक्षा)
                    </span>
                    @endif
                </p>
            </div>

            <!-- Cyber Stats -->
            <div class="cyber-stats">
                <div class="cyber-stat">
                    <span class="cyber-number">{{ $hostel->total_rooms ?? 0 }}</span>
                    <span class="cyber-label nepali-font">कुल कोठा</span>
                </div>
                <div class="cyber-stat">
                    <span class="cyber-number">{{ $hostel->available_rooms ?? 0 }}</span>
                    <span class="cyber-label nepali-font">उपलब्ध कोठा</span>
                </div>
                <div class="cyber-stat">
                    <span class="cyber-number">{{ $studentCount }}</span>
                    <span class="cyber-label nepali-font">विद्यार्थी</span>
                </div>
                <div class="cyber-stat">
                    <span class="cyber-number">{{ $reviewCount }}</span>
                    <span class="cyber-label nepali-font">समीक्षा</span>
                </div>
            </div>

            <!-- Cyber Social -->
            <div class="cyber-social">
                @if($hostel->facebook_url)
                    <a href="{{ $hostel->facebook_url }}" target="_blank" class="cyber-social-icon">
                        <i class="fab fa-facebook-f"></i>
                    </a>
                @endif
                @if($hostel->instagram_url)
                    <a href="{{ $hostel->instagram_url }}" target="_blank" class="cyber-social-icon">
                        <i class="fab fa-instagram"></i>
                    </a>
                @endif
                @if($hostel->whatsapp_number)
                    <a href="https://wa.me/{{ $hostel->whatsapp_number }}" target="_blank" class="cyber-social-icon">
                        <i class="fab fa-whatsapp"></i>
                    </a>
                @endif
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <div class="container" style="max-width: 1200px; margin: 0 auto; padding: 0 20px;">
        <!-- Action Buttons -->
        <div class="cyber-action-buttons">
            @if($hostel->contact_phone)
            <a href="tel:{{ $hostel->contact_phone }}" class="cyber-btn btn-cyber-green">
                <i class="fas fa-phone"></i>
                <span class="nepali-font">फोन गर्नुहोस्</span>
            </a>
            @endif
            <a href="{{ route('hostels.index') }}" class="cyber-btn">
                <i class="fas fa-building"></i>
                <span class="nepali-font">हाम्रा अन्य होस्टलहरू</span>
            </a>
            <a href="#reviews" class="cyber-btn btn-cyber-pink">
                <i class="fas fa-star"></i>
                <span class="nepali-font">समीक्षा लेख्नुहोस्</span>
            </a>
        </div>

        <!-- About Section -->
        <section class="cyber-container">
            <div class="cyber-section-header">
                <h2 class="cyber-section-title nepali-font">हाम्रो बारेमा</h2>
            </div>
            <div class="cyber-about-content nepali-font">
                @if($hostel->description)
                    {{ $hostel->description }}
                @else
                    <div style="text-align: center; padding: 3rem; color: var(--text-secondary);">
                        <div style="width: 100px; height: 100px; background: var(--dark-3); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1.5rem; border: 2px solid var(--neon-cyan);">
                            <i class="fas fa-file-alt" style="font-size: 2.5rem; color: var(--neon-cyan);"></i>
                        </div>
                        <p style="font-size: 1.2rem; font-style: italic;">यस होस्टलको बारेमा विवरण उपलब्ध छैन।</p>
                    </div>
                @endif
            </div>
        </section>

        <!-- Gallery Section -->
        <section class="cyber-container">
            <div class="cyber-section-header">
                <h2 class="cyber-section-title nepali-font">हाम्रो ग्यालरी</h2>
                <p class="cyber-subtitle nepali-font" style="color: var(--text-secondary);">हाम्रो होस्टलको सुन्दर तस्बिरहरू र भिडियोहरू हेर्नुहोस्</p>
            </div>
            
            <div class="cyber-gallery">
                @php
                    $galleries = $hostel->activeGalleries ?? collect();
                @endphp
                
                @if($galleries->count() > 0)
                    @foreach($galleries as $gallery)
                    <div class="cyber-gallery-item group">
                        @if($gallery->media_type === 'image')
                            <img src="{{ $gallery->thumbnail_url }}" 
                                 alt="{{ $gallery->title }}"
                                 class="w-full h-full object-cover">
                        @elseif($gallery->media_type === 'external_video')
                            <div class="w-full h-full bg-gradient-to-br from-purple-500 to-pink-500 flex items-center justify-center relative">
                                <i class="fab fa-youtube text-white text-3xl"></i>
                            </div>
                        @else
                            <div class="w-full h-full bg-gradient-to-br from-blue-500 to-cyan-500 flex items-center justify-center relative">
                                <i class="fas fa-video text-white text-3xl"></i>
                            </div>
                        @endif
                        
                        <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-70 transition-all duration-300 flex items-center justify-center p-4">
                            <div class="text-white text-center transform translate-y-4 group-hover:translate-y-0 transition-transform duration-300">
                                <h4 class="font-semibold text-sm mb-1 nepali-font">{{ $gallery->title }}</h4>
                                @if($gallery->description)
                                    <p class="text-xs opacity-90 nepali-font">{{ Str::limit($gallery->description, 60) }}</p>
                                @endif
                                @if($gallery->is_featured)
                                    <span class="inline-block bg-yellow-500 text-white text-xs px-2 py-1 rounded-full mt-2 nepali-font">फिचर्ड</span>
                                @endif
                            </div>
                        </div>
                    </div>
                    @endforeach
                @else
                    <!-- Placeholder for empty gallery -->
                    <div class="cyber-gallery-item">
                        <div style="width: 100%; height: 100%; background: var(--dark-3); border: 2px solid var(--neon-purple); display: flex; flex-direction: column; align-items: center; justify-content: center; color: var(--neon-purple);">
                            <i class="fas fa-images" style="font-size: 3rem; margin-bottom: 1rem;"></i>
                            <span class="nepali-font" style="font-size: 0.9rem;">तस्बिरहरू थपिने...</span>
                        </div>
                    </div>
                    <div class="cyber-gallery-item">
                        <div style="width: 100%; height: 100%; background: var(--dark-3); border: 2px solid var(--neon-cyan); display: flex; flex-direction: column; align-items: center; justify-content: center; color: var(--neon-cyan);">
                            <i class="fas fa-video" style="font-size: 3rem; margin-bottom: 1rem;"></i>
                            <span class="nepali-font" style="font-size: 0.9rem;">भिडियोहरू थपिने...</span>
                        </div>
                    </div>
                @endif
            </div>
        </section>

        <!-- Facilities Section -->
        @if(!empty($facilities) && count($facilities) > 0)
        <section class="cyber-container">
            <div class="cyber-section-header">
                <h2 class="cyber-section-title nepali-font">सुविधाहरू</h2>
            </div>
            <div class="cyber-facilities">
                @foreach($facilities as $facility)
                    @if(trim($facility))
                    <div class="cyber-facility">
                        <div class="cyber-facility-icon">
                            <i class="fas fa-check"></i>
                        </div>
                        <span class="nepali-font" style="color: var(--text-primary); font-weight: 500; font-size: 1.1rem;">{{ trim($facility) }}</span>
                    </div>
                    @endif
                @endforeach
            </div>
        </section>
        @endif

        <!-- Location Section -->
        <section class="cyber-container">
            <div class="cyber-section-header">
                <h2 class="cyber-section-title nepali-font">हाम्रो स्थान</h2>
            </div>
            <div style="padding: 2rem;">
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 3rem; align-items: start;">
                    <div>
                        <h3 style="color: var(--text-primary); margin-bottom: 1.5rem; font-size: 1.5rem;" class="nepali-font">ठेगाना विवरण</h3>
                        @if($hostel->address)
                            <p style="color: var(--text-secondary); line-height: 1.8; margin-bottom: 2rem; font-size: 1.1rem;" class="nepali-font">{{ $hostel->address }}</p>
                        @endif
                        
                        <div style="display: flex; flex-direction: column; gap: 1rem;">
                            <a href="https://www.google.com/maps/search/?api=1&query={{ urlencode($hostel->address) }}" 
                               target="_blank" 
                               class="cyber-btn btn-cyber-green"
                               style="text-align: center;">
                                <i class="fas fa-directions"></i>
                                <span class="nepali-font">नक्सामा दिशा निर्देशन</span>
                            </a>
                            
                            <a href="https://www.google.com/maps/search/?api=1&query={{ urlencode($hostel->address) }}" 
                               target="_blank" 
                               class="cyber-btn"
                               style="text-align: center;">
                                <i class="fas fa-external-link-alt"></i>
                                <span class="nepali-font">Google Map मा खोल्नुहोस्</span>
                            </a>
                        </div>
                    </div>
                    
                    <!-- UPDATED: Actual Google Map Embed -->
                    <div style="background: var(--dark-3); border: 2px solid var(--neon-cyan); padding: 0; overflow: hidden; height: 400px; position: relative;">
                        <iframe 
                            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3532.8340378072015!2d85.3171482753358!3d27.69389037618937!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x39eb1965f5ec93a7%3A0xf2a74108721b8b9e!2sKalikasthan%20Mandir!5e0!3m2!1sen!2snp!4v1699876543210!5m2!1sen!2snp" 
                            width="100%" 
                            height="100%" 
                            style="border:0; filter: invert(90%) hue-rotate(180deg) contrast(85%);" 
                            allowfullscreen="" 
                            loading="lazy" 
                            referrerpolicy="no-referrer-when-downgrade"
                            title="Kalikasthan Mandir Location Map">
                        </iframe>
                        
                        <!-- Cyber overlay effect -->
                        <div style="position: absolute; top: 0; left: 0; right: 0; bottom: 0; pointer-events: none; background: linear-gradient(45deg, rgba(0,212,255,0.1) 0%, rgba(255,0,255,0.1) 100%);"></div>
                        
                        <!-- Scan line effect -->
                        <div style="position: absolute; top: 0; left: 0; right: 0; height: 2px; background: linear-gradient(90deg, transparent, var(--neon-cyan), transparent); animation: scanline 3s linear infinite;"></div>
                    </div>
                </div>
                
                <!-- Additional Location Info -->
                <div style="margin-top: 3rem; padding: 2rem; background: rgba(17, 17, 17, 0.6); border: 1px solid var(--neon-green);">
                    <h4 style="color: var(--text-primary); margin-bottom: 1rem; font-size: 1.3rem;" class="nepali-font">स्थानको बारेमा</h4>
                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1.5rem;">
                        <div style="display: flex; align-items: center; gap: 1rem;">
                            <div style="width: 40px; height: 40px; background: linear-gradient(135deg, var(--neon-cyan), var(--neon-purple)); border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                                <i class="fas fa-landmark" style="color: var(--dark-1); font-size: 1rem;"></i>
                            </div>
                            <div>
                                <div style="color: var(--neon-cyan); font-size: 0.9rem; font-weight: 600;" class="nepali-font">नजिकैको मन्दिर</div>
                                <div style="color: var(--text-secondary); font-size: 0.9rem;" class="nepali-font">कालिकास्थान मन्दिर</div>
                            </div>
                        </div>
                        
                        <div style="display: flex; align-items: center; gap: 1rem;">
                            <div style="width: 40px; height: 40px; background: linear-gradient(135deg, var(--neon-green), var(--neon-cyan)); border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                                <i class="fas fa-bus" style="color: var(--dark-1); font-size: 1rem;"></i>
                            </div>
                            <div>
                                <div style="color: var(--neon-green); font-size: 0.9rem; font-weight: 600;" class="nepali-font">यातायात</div>
                                <div style="color: var(--text-secondary); font-size: 0.9rem;" class="nepali-font">सजिलो पहुँच</div>
                            </div>
                        </div>
                        
                        <div style="display: flex; align-items: center; gap: 1rem;">
                            <div style="width: 40px; height: 40px; background: linear-gradient(135deg, var(--neon-pink), var(--neon-purple)); border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                                <i class="fas fa-university" style="color: var(--dark-1); font-size: 1rem;"></i>
                            </div>
                            <div>
                                <div style="color: var(--neon-pink); font-size: 0.9rem; font-weight: 600;" class="nepali-font">क्षेत्र</div>
                                <div style="color: var(--text-secondary); font-size: 0.9rem;" class="nepali-font">दिल्लीबजार, काठमाडौं</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Reviews Section -->
        <section class="cyber-container" id="reviews">
            <div class="cyber-section-header">
                <h2 class="cyber-section-title nepali-font">विद्यार्थी समीक्षाहरू</h2>
            </div>
            
            @if($reviewCount > 0)
                <div class="cyber-reviews-container">
                    <div class="cyber-reviews-track" id="cyberReviewsTrack">
                        @foreach($reviews as $review)
                        <div class="cyber-review">
                            <div class="cyber-review-header">
                                <div class="cyber-reviewer nepali-font">{{ $review->student->user->name ?? 'अज्ञात विद्यार्थी' }}</div>
                                <div class="cyber-review-date">{{ $review->created_at->format('Y-m-d') }}</div>
                            </div>
                            <div class="cyber-review-rating">
                                @for($i = 1; $i <= 5; $i++)
                                    <i class="fas fa-star {{ $i <= $review->rating ? '' : 'far' }}"></i>
                                @endfor
                                <span style="margin-left: 1rem; color: var(--neon-orange); font-weight: 700;">{{ $review->rating }}/5</span>
                            </div>
                            <div class="cyber-review-content nepali-font">{{ $review->comment }}</div>
                            
                            @if($review->reply)
                            <div style="background: rgba(157, 0, 255, 0.1); border-left: 4px solid var(--neon-purple); padding: 1.5rem; margin-top: 1.5rem;">
                                <div style="display: flex; align-items: start; gap: 1rem;">
                                    <i class="fas fa-reply" style="color: var(--neon-purple); margin-top: 0.3rem;"></i>
                                    <div>
                                        <strong style="color: var(--neon-purple); font-size: 1rem;" class="nepali-font">होस्टलको जवाफ:</strong>
                                        <p style="color: var(--text-secondary); margin-top: 0.8rem; font-size: 1rem;" class="nepali-font">{{ $review->reply }}</p>
                                    </div>
                                </div>
                            </div>
                            @endif
                        </div>
                        @endforeach
                    </div>

                    @if($reviewCount > 1)
                    <div class="cyber-review-controls">
                        <button class="cyber-btn prev-cyber-review">
                            <i class="fas fa-chevron-left"></i>
                            <span>अघिल्लो</span>
                        </button>
                        <button class="cyber-btn next-cyber-review">
                            <span>अर्को</span>
                            <i class="fas fa-chevron-right"></i>
                        </button>
                    </div>
                    @endif
                </div>
            @else
                <div style="text-align: center; padding: 3rem;">
                    <div style="width: 120px; height: 120px; background: var(--dark-3); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1.5rem; border: 2px solid var(--neon-cyan);">
                        <i class="fas fa-comment-slash" style="font-size: 3rem; color: var(--neon-cyan);"></i>
                    </div>
                    <h3 style="font-size: 1.5rem; color: var(--text-primary); margin-bottom: 0.8rem;" class="nepali-font">अहिलेसम्म कुनै समीक्षा छैन</h3>
                    <p style="font-size: 1.1rem; color: var(--text-secondary);" class="nepali-font">यो होस्टलको पहिलो समीक्षा दिनुहोस्!</p>
                </div>
            @endif
        </section>

        <!-- Contact Information -->
        <section class="cyber-container">
            <div class="cyber-section-header">
                <h2 class="cyber-section-title nepali-font">सम्पर्क जानकारी</h2>
            </div>
            <div class="cyber-contact-grid">
                @if($hostel->contact_person)
                <div class="cyber-contact-card">
                    <div class="cyber-contact-icon">
                        <i class="fas fa-user"></i>
                    </div>
                    <h3 style="color: var(--text-primary); margin-bottom: 0.5rem; font-size: 1.2rem;" class="nepali-font">सम्पर्क व्यक्ति</h3>
                    <p style="color: var(--text-secondary); font-weight: 500; font-size: 1.1rem;">{{ $hostel->contact_person }}</p>
                </div>
                @endif
                
                @if($hostel->contact_phone)
                <div class="cyber-contact-card">
                    <div class="cyber-contact-icon">
                        <i class="fas fa-phone"></i>
                    </div>
                    <h3 style="color: var(--text-primary); margin-bottom: 0.5rem; font-size: 1.2rem;" class="nepali-font">फोन नम्बर</h3>
                    <a href="tel:{{ $hostel->contact_phone }}" style="color: var(--text-secondary); font-weight: 500; font-size: 1.1rem; text-decoration: none;">
                        {{ $hostel->contact_phone }}
                    </a>
                </div>
                @endif
                
                @if($hostel->contact_email)
                <div class="cyber-contact-card">
                    <div class="cyber-contact-icon">
                        <i class="fas fa-envelope"></i>
                    </div>
                    <h3 style="color: var(--text-primary); margin-bottom: 0.5rem; font-size: 1.2rem;" class="nepali-font">इमेल</h3>
                    <a href="mailto:{{ $hostel->contact_email }}" style="color: var(--text-secondary); font-weight: 500; font-size: 1.1rem; text-decoration: none;">
                        {{ $hostel->contact_email }}
                    </a>
                </div>
                @endif
                
                @if($hostel->address)
                <div class="cyber-contact-card">
                    <div class="cyber-contact-icon">
                        <i class="fas fa-map-marker-alt"></i>
                    </div>
                    <h3 style="color: var(--text-primary); margin-bottom: 0.5rem; font-size: 1.2rem;" class="nepali-font">ठेगाना</h3>
                    <p style="color: var(--text-secondary); font-weight: 500; font-size: 1.1rem;" class="nepali-font">{{ $hostel->address }}</p>
                </div>
                @endif
            </div>
        </section>

        <!-- Contact Form -->
        <section class="cyber-container">
            <div class="cyber-section-header">
                <h2 class="cyber-section-title nepali-font">सम्पर्क फर्म</h2>
            </div>
            <div class="cyber-form">
                <form action="{{ route('hostel.contact', $hostel->id) }}" method="POST">
                    @csrf
                    <div class="cyber-form-grid">
                        <input type="text" name="name" required placeholder="तपाईंको नाम" class="cyber-form-input nepali-font">
                        <input type="email" name="email" required placeholder="इमेल ठेगाना" class="cyber-form-input">
                    </div>
                    <textarea name="message" required placeholder="तपाईंको सन्देश यहाँ लेख्नुहोस्..." class="cyber-form-textarea nepali-font"></textarea>
                    <div style="text-align: center;">
                        <button type="submit" class="cyber-btn btn-cyber-green">
                            <i class="fas fa-paper-plane"></i>
                            <span class="nepali-font">सन्देश पठाउनुहोस्</span>
                        </button>
                    </div>
                </form>
            </div>
        </section>
    </div>
</div>

<!-- WhatsApp Floating Button -->
@if($hostel->whatsapp_number)
    <a href="https://wa.me/{{ $hostel->whatsapp_number }}" target="_blank" class="cyber-whatsapp-btn" aria-label="WhatsApp मा सन्देश पठाउनुहोस्">
        <i class="fab fa-whatsapp"></i>
    </a>
@endif

@push('scripts')
@vite(['resources/js/app.js'])
<!-- Cyber JavaScript -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Reviews Carousel
    const track = document.getElementById('cyberReviewsTrack');
    const slides = document.querySelectorAll('.cyber-review');
    const prevBtn = document.querySelector('.prev-cyber-review');
    const nextBtn = document.querySelector('.next-cyber-review');
    
    if (slides.length > 1 && track) {
        let currentSlide = 0;

        function updateCarousel() {
            track.style.transform = `translateX(-${currentSlide * 100}%)`;
        }

        if (nextBtn) {
            nextBtn.addEventListener('click', function() {
                currentSlide = (currentSlide + 1) % slides.length;
                updateCarousel();
            });
        }

        if (prevBtn) {
            prevBtn.addEventListener('click', function() {
                currentSlide = (currentSlide - 1 + slides.length) % slides.length;
                updateCarousel();
            });
        }

        // Auto slide
        setInterval(function() {
            currentSlide = (currentSlide + 1) % slides.length;
            updateCarousel();
        }, 6000);
    }

    // Matrix effect enhancement
    const matrixCode = document.querySelector('.matrix-code');
    if (matrixCode) {
        setInterval(() => {
            matrixCode.style.background = 
                `repeating-linear-gradient(0deg, transparent, transparent 2px, rgba(${Math.random() * 255}, ${Math.random() * 255}, ${Math.random() * 255}, 0.03) 2px, rgba(${Math.random() * 255}, ${Math.random() * 255}, ${Math.random() * 255}, 0.03) 4px),
                repeating-linear-gradient(90deg, transparent, transparent 2px, rgba(${Math.random() * 255}, ${Math.random() * 255}, ${Math.random() * 255}, 0.03) 2px, rgba(${Math.random() * 255}, ${Math.random() * 255}, ${Math.random() * 255}, 0.03) 4px)`;
        }, 3000);
    }
});
</script>
@endpush

<!-- External Dependencies -->
<link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;500;600;700;800;900&family=Rajdhani:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
@endsection