<?php $__env->startPush('head'); ?>
<style>
    :root {
        --theme-color: <?php echo e($hostel->theme_color ?? '#6366F1'); ?>;
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

    /* Vibrant Theme - Completely Different */
    .vibrant-body {
        background: linear-gradient(135deg, #0F172A 0%, #1E293B 50%, #334155 100%);
        font-family: 'Poppins', 'Inter', sans-serif;
        color: #F1F5F9;
        min-height: 100vh;
        overflow-x: hidden;
    }

    /* UPDATED: More Shiny Stats Numbers */
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

    .vibrant-stats {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
        gap: 1.5rem;
        margin: 2rem 0;
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

    .vibrant-gallery {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 1.5rem;
        margin-top: 2rem;
    }

    .gallery-item {
        position: relative;
        border-radius: 16px;
        overflow: hidden;
        aspect-ratio: 1;
        transition: all 0.3s ease;
        border: 2px solid transparent;
        background: linear-gradient(135deg, var(--vibrant-pink), var(--vibrant-blue), var(--vibrant-purple));
        background-clip: padding-box;
    }

    .gallery-item img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.3s ease;
    }

    .gallery-item:hover {
        transform: translateY(-10px) scale(1.05);
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.4);
    }

    .gallery-item:hover img {
        transform: scale(1.1);
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
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<div class="vibrant-body">
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
            <?php if(isset($preview) && $preview): ?>
            <div class="glass-container" style="margin-bottom: 2rem;">
                <div style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 1rem;">
                    <div style="display: flex; align-items: center; gap: 1rem;">
                        <div style="width: 40px; height: 40px; background: linear-gradient(135deg, var(--vibrant-orange), #EA580C); border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                            <i class="fas fa-eye" style="color: white; font-size: 1rem;"></i>
                        </div>
                        <span style="color: white; font-weight: 700; font-size: 1.1rem;" class="nepali-font">यो पूर्वावलोकन मोडमा हो</span>
                    </div>
                    <a href="<?php echo e(route('owner.public-page.edit')); ?>" class="vibrant-btn" style="background: linear-gradient(135deg, var(--vibrant-orange), #EA580C);">
                        <i class="fas fa-edit"></i>
                        <span class="nepali-font">सम्पादन गर्नुहोस्</span>
                    </a>
                </div>
            </div>
            <?php endif; ?>

            <!-- Logo and Title -->
            <div style="text-align: center; margin-bottom: 2rem;">
                <div class="vibrant-logo">
                    <?php if($logo): ?>
                        <img src="<?php echo e($logo); ?>" alt="<?php echo e($hostel->name); ?>">
                    <?php else: ?>
                        <div style="width: 100%; height: 100%; background: linear-gradient(135deg, var(--vibrant-pink), var(--vibrant-blue), var(--vibrant-purple)); display: flex; align-items: center; justify-content: center;">
                            <i class="fas fa-building" style="color: white; font-size: 2.5rem;"></i>
                        </div>
                    <?php endif; ?>
                </div>
                
                <h1 class="vibrant-title nepali-font"><?php echo e($hostel->name); ?></h1>
                <p class="vibrant-subtitle nepali-font">
                    <?php if($hostel->city): ?>
                    <i class="fas fa-map-marker-alt" style="color: var(--vibrant-blue); margin-right: 0.5rem;"></i><?php echo e($hostel->city); ?>

                    <?php endif; ?>
                    
                    <?php if($reviewCount > 0 && $avgRating > 0): ?>
                    <span style="margin-left: 1rem;">
                        <i class="fas fa-star" style="color: var(--vibrant-orange);"></i>
                        <?php echo e(number_format($avgRating, 1)); ?> (<?php echo e($reviewCount); ?> समीक्षा)
                    </span>
                    <?php endif; ?>
                </p>
            </div>

            <!-- UPDATED: Stats with Shiny Numbers -->
            <div class="vibrant-stats">
                <div class="vibrant-stat">
                    <span class="stat-number"><?php echo e($hostel->total_rooms ?? 0); ?></span>
                    <span class="stat-label nepali-font">कुल कोठा</span>
                </div>
                <div class="vibrant-stat">
                    <span class="stat-number"><?php echo e($hostel->available_rooms ?? 0); ?></span>
                    <span class="stat-label nepali-font">उपलब्ध कोठा</span>
                </div>
                <div class="vibrant-stat">
                    <span class="stat-number"><?php echo e($studentCount); ?></span>
                    <span class="stat-label nepali-font">विद्यार्थी</span>
                </div>
                <div class="vibrant-stat">
                    <span class="stat-number"><?php echo e($reviewCount); ?></span>
                    <span class="stat-label nepali-font">समीक्षा</span>
                </div>
            </div>

            <!-- Social Media -->
            <div class="vibrant-social">
                <?php if($hostel->facebook_url): ?>
                    <a href="<?php echo e($hostel->facebook_url); ?>" target="_blank" class="social-icon">
                        <i class="fab fa-facebook-f"></i>
                    </a>
                <?php endif; ?>
                <?php if($hostel->instagram_url): ?>
                    <a href="<?php echo e($hostel->instagram_url); ?>" target="_blank" class="social-icon">
                        <i class="fab fa-instagram"></i>
                    </a>
                <?php endif; ?>
                <?php if($hostel->whatsapp_number): ?>
                    <a href="https://wa.me/<?php echo e($hostel->whatsapp_number); ?>" target="_blank" class="social-icon">
                        <i class="fab fa-whatsapp"></i>
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <div class="container" style="max-width: 1200px; margin: 0 auto; padding: 0 20px;">
        <!-- Action Buttons -->
        <div class="action-buttons">
            <?php if($hostel->contact_phone): ?>
            <a href="tel:<?php echo e($hostel->contact_phone); ?>" class="vibrant-btn btn-phone">
                <i class="fas fa-phone"></i>
                <span class="nepali-font">फोन गर्नुहोस्</span>
            </a>
            <?php endif; ?>
            <a href="<?php echo e(route('hostels.index')); ?>" class="vibrant-btn btn-secondary">
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
                <?php if($hostel->description): ?>
                    <?php echo e($hostel->description); ?>

                <?php else: ?>
                    <div style="text-align: center; padding: 3rem; color: #94A3B8;">
                        <div style="width: 80px; height: 80px; background: var(--glass-bg); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1.5rem; border: 2px solid var(--glass-border);">
                            <i class="fas fa-file-alt" style="font-size: 2rem; color: var(--vibrant-blue);"></i>
                        </div>
                        <p style="font-size: 1.2rem; font-style: italic;">यस होस्टलको बारेमा विवरण उपलब्ध छैन।</p>
                    </div>
                <?php endif; ?>
            </div>
        </section>

        <!-- Dynamic Gallery Section -->
        <section class="glass-container">
            <div class="section-header">
                <h2 class="section-title nepali-font">ग्यालरी</h2>
                <div class="section-divider"></div>
            </div>
            <div class="vibrant-gallery">
                <?php
                    $galleries = $hostel->activeGalleries ?? collect();
                ?>
                
                <?php if($galleries->count() > 0): ?>
                    <?php $__currentLoopData = $galleries; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $gallery): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="gallery-item">
                        <?php if($gallery->media_type === 'image'): ?>
                            <img src="<?php echo e($gallery->thumbnail_url); ?>" 
                                 alt="<?php echo e($gallery->title); ?>"
                                 style="width: 100%; height: 100%; object-fit: cover;">
                        <?php elseif($gallery->media_type === 'external_video'): ?>
                            <div style="width: 100%; height: 100%; background: linear-gradient(135deg, var(--vibrant-purple), var(--vibrant-pink)); display: flex; align-items: center; justify-content: center;">
                                <i class="fab fa-youtube text-white text-3xl"></i>
                            </div>
                        <?php else: ?>
                            <div style="width: 100%; height: 100%; background: linear-gradient(135deg, var(--vibrant-blue), var(--vibrant-green)); display: flex; align-items: center; justify-content: center;">
                                <i class="fas fa-video text-white text-3xl"></i>
                            </div>
                        <?php endif; ?>
                        
                        <div class="gallery-overlay">
                            <div class="gallery-overlay-content">
                                <h4 class="nepali-font" style="font-size: 1.1rem; font-weight: bold; margin-bottom: 0.5rem;"><?php echo e($gallery->title); ?></h4>
                                <?php if($gallery->description): ?>
                                    <p class="nepali-font" style="font-size: 0.9rem; opacity: 0.9;"><?php echo e(Str::limit($gallery->description, 60)); ?></p>
                                <?php endif; ?>
                                <?php if($gallery->is_featured): ?>
                                    <span class="nepali-font" style="display: inline-block; background: var(--vibrant-yellow); color: var(--dark-bg); font-size: 0.8rem; padding: 0.2rem 0.5rem; border-radius: 1rem; margin-top: 0.5rem; font-weight: bold;">फिचर्ड</span>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <?php else: ?>
                    <div style="grid-column: 1 / -1; text-align: center; padding: 3rem; color: #94A3B8;">
                        <div style="width: 100px; height: 100px; background: var(--glass-bg); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1.5rem; border: 2px solid var(--glass-border);">
                            <i class="fas fa-images" style="font-size: 2.5rem; color: var(--vibrant-purple);"></i>
                        </div>
                        <h3 style="font-size: 1.5rem; color: white; margin-bottom: 0.8rem;" class="nepali-font">कुनै तस्बिर उपलब्ध छैन</h3>
                        <p style="font-size: 1.1rem;" class="nepali-font">यस होस्टलको ग्यालरी तस्बिरहरू चाँहि उपलब्ध छैनन्।</p>
                    </div>
                <?php endif; ?>
            </div>
        </section>

        <!-- Facilities Section -->
        <?php if(!empty($facilities) && count($facilities) > 0): ?>
        <section class="glass-container">
            <div class="section-header">
                <h2 class="section-title nepali-font">सुविधाहरू</h2>
                <div class="section-divider"></div>
            </div>
            <div class="facilities-grid">
                <?php $__currentLoopData = $facilities; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $facility): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php if(trim($facility)): ?>
                    <div class="facility-card">
                        <div class="facility-icon">
                            <i class="fas fa-check"></i>
                        </div>
                        <span class="nepali-font" style="color: #E2E8F0; font-weight: 500;"><?php echo e(trim($facility)); ?></span>
                    </div>
                    <?php endif; ?>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </section>
        <?php endif; ?>

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
                        <?php if($hostel->address): ?>
                            <p style="color: #E2E8F0; line-height: 1.6; margin-bottom: 1.5rem;" class="nepali-font"><?php echo e($hostel->address); ?></p>
                        <?php endif; ?>
                        
                        <?php if($hostel->contact_phone): ?>
                        <div style="display: flex; align-items: center; gap: 1rem; margin-bottom: 1rem;">
                            <div style="width: 40px; height: 40px; background: linear-gradient(135deg, var(--vibrant-green), #059669); border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                                <i class="fas fa-phone" style="color: white; font-size: 1rem;"></i>
                            </div>
                            <div>
                                <span style="color: #94A3B8; font-size: 0.9rem;" class="nepali-font">फोन नम्बर</span>
                                <br>
                                <a href="tel:<?php echo e($hostel->contact_phone); ?>" style="color: white; text-decoration: none; font-weight: 600;"><?php echo e($hostel->contact_phone); ?></a>
                            </div>
                        </div>
                        <?php endif; ?>

                        <div style="margin-top: 2rem;">
                            <a href="https://www.google.com/maps/search/?api=1&query=<?php echo e(urlencode($hostel->address)); ?>" 
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
                        <a href="https://www.google.com/maps/search/?api=1&query=<?php echo e(urlencode($hostel->address)); ?>" 
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
            
            <?php if($reviewCount > 0): ?>
                <div class="reviews-slider-container">
                    <div class="reviews-slider" id="reviewsSlider">
                        <?php $__currentLoopData = $reviews; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $review): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="review-slide">
                            <div class="review-header">
                                <div class="reviewer-name nepali-font"><?php echo e($review->student->user->name ?? 'अज्ञात विद्यार्थी'); ?></div>
                                <div class="review-date"><?php echo e($review->created_at->format('Y-m-d')); ?></div>
                            </div>
                            <div class="review-rating">
                                <?php for($i = 1; $i <= 5; $i++): ?>
                                    <i class="fas fa-star <?php echo e($i <= $review->rating ? '' : 'far'); ?>"></i>
                                <?php endfor; ?>
                                <span style="margin-left: 0.5rem; color: var(--vibrant-orange); font-weight: 700;"><?php echo e($review->rating); ?>/5</span>
                            </div>
                            <div class="review-content nepali-font"><?php echo e($review->comment); ?></div>
                            
                            <?php if($review->reply): ?>
                            <div style="background: rgba(236, 72, 153, 0.1); border-left: 4px solid var(--vibrant-pink); padding: 1rem; margin-top: 1rem; border-radius: 8px;">
                                <div style="display: flex; align-items: start; gap: 0.8rem;">
                                    <i class="fas fa-reply" style="color: var(--vibrant-pink); margin-top: 0.2rem;"></i>
                                    <div>
                                        <strong style="color: var(--vibrant-pink); font-size: 0.9rem;" class="nepali-font">होस्टलको जवाफ:</strong>
                                        <p style="color: #E2E8F0; margin-top: 0.5rem; font-size: 0.9rem;" class="nepali-font"><?php echo e($review->reply); ?></p>
                                    </div>
                                </div>
                            </div>
                            <?php endif; ?>
                        </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>

                    <?php if($reviewCount > 1): ?>
                    <div class="slider-controls">
                        <button class="slider-btn prev-btn">
                            <i class="fas fa-chevron-left"></i>
                        </button>
                        <button class="slider-btn next-btn">
                            <i class="fas fa-chevron-right"></i>
                        </button>
                    </div>
                    
                    <div class="slider-dots" id="sliderDots">
                        <?php for($i = 0; $i < $reviewCount; $i++): ?>
                            <div class="slider-dot <?php echo e($i === 0 ? 'active' : ''); ?>" data-slide="<?php echo e($i); ?>"></div>
                        <?php endfor; ?>
                    </div>
                    <?php endif; ?>
                </div>
            <?php else: ?>
                <div style="text-align: center; padding: 3rem;">
                    <div style="width: 100px; height: 100px; background: var(--glass-bg); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1.5rem; border: 2px solid var(--glass-border);">
                        <i class="fas fa-comment-slash" style="font-size: 2.5rem; color: var(--vibrant-blue);"></i>
                    </div>
                    <h3 style="font-size: 1.5rem; color: white; margin-bottom: 0.8rem;" class="nepali-font">अहिलेसम्म कुनै समीक्षा छैन</h3>
                    <p style="font-size: 1.1rem; color: #94A3B8;" class="nepali-font">यो होस्टलको पहिलो समीक्षा दिनुहोस्!</p>
                </div>
            <?php endif; ?>
        </section>

        <!-- Contact Information -->
        <section class="glass-container">
            <div class="section-header">
                <h2 class="section-title nepali-font">सम्पर्क जानकारी</h2>
                <div class="section-divider"></div>
            </div>
            <div class="contact-single-line">
                <?php if($hostel->contact_person): ?>
                <div class="contact-item">
                    <div class="contact-icon-small">
                        <i class="fas fa-user"></i>
                    </div>
                    <div class="contact-info">
                        <span class="contact-label nepali-font">सम्पर्क व्यक्ति</span>
                        <span class="contact-value"><?php echo e($hostel->contact_person); ?></span>
                    </div>
                </div>
                <?php endif; ?>
                
                <?php if($hostel->contact_phone): ?>
                <div class="contact-item">
                    <div class="contact-icon-small">
                        <i class="fas fa-phone"></i>
                    </div>
                    <div class="contact-info">
                        <span class="contact-label nepali-font">फोन नम्बर</span>
                        <a href="tel:<?php echo e($hostel->contact_phone); ?>" class="contact-value" style="text-decoration: none; color: inherit;">
                            <?php echo e($hostel->contact_phone); ?>

                        </a>
                    </div>
                </div>
                <?php endif; ?>
                
                <?php if($hostel->contact_email): ?>
                <div class="contact-item">
                    <div class="contact-icon-small">
                        <i class="fas fa-envelope"></i>
                    </div>
                    <div class="contact-info">
                        <span class="contact-label nepali-font">इमेल</span>
                        <a href="mailto:<?php echo e($hostel->contact_email); ?>" class="contact-value" style="text-decoration: none; color: inherit;">
                            <?php echo e($hostel->contact_email); ?>

                        </a>
                    </div>
                </div>
                <?php endif; ?>
                
                <?php if($hostel->address): ?>
                <div class="contact-item">
                    <div class="contact-icon-small">
                        <i class="fas fa-map-marker-alt"></i>
                    </div>
                    <div class="contact-info">
                        <span class="contact-label nepali-font">ठेगाना</span>
                        <span class="contact-value nepali-font"><?php echo e($hostel->address); ?></span>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </section>

        <!-- Contact Form -->
        <section class="glass-container">
            <div class="section-header">
                <h2 class="section-title nepali-font">सम्पर्क फर्म</h2>
                <div class="section-divider"></div>
            </div>
            <div class="contact-form">
                <form action="<?php echo e(route('hostel.contact', $hostel->id)); ?>" method="POST">
                    <?php echo csrf_field(); ?>
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
<?php if($hostel->contact_phone): ?>
    <a href="tel:<?php echo e($hostel->contact_phone); ?>" class="fixed-phone-btn" aria-label="फोन गर्नुहोस्">
        <i class="fas fa-phone"></i>
    </a>
<?php endif; ?>

<!-- Add Font Awesome -->
<script src="https://kit.fontawesome.com/your-fontawesome-kit.js" crossorigin="anonymous"></script>

<!-- Add Poppins Font -->
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">

<!-- UPDATED: JavaScript for Reviews Slider -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const slider = document.getElementById('reviewsSlider');
    const slides = document.querySelectorAll('.review-slide');
    const dots = document.querySelectorAll('.slider-dot');
    const prevBtn = document.querySelector('.prev-btn');
    const nextBtn = document.querySelector('.next-btn');
    
    if (slides.length > 1) {
        let currentSlide = 0;
        const totalSlides = slides.length;

        function updateSlider() {
            slider.style.transform = `translateX(-${currentSlide * 100}%)`;
            
            // Update dots
            dots.forEach((dot, index) => {
                dot.classList.toggle('active', index === currentSlide);
            });
        }

        // Next button
        nextBtn.addEventListener('click', function() {
            currentSlide = (currentSlide + 1) % totalSlides;
            updateSlider();
        });

        // Previous button
        prevBtn.addEventListener('click', function() {
            currentSlide = (currentSlide - 1 + totalSlides) % totalSlides;
            updateSlider();
        });

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
});
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.public', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\HostelHub\resources\views\public\hostels\themes\vibrant.blade.php ENDPATH**/ ?>