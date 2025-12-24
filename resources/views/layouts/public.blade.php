<!DOCTYPE html>
<html lang="ne">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('page-title', 'HostelHub')</title>
    <meta name="description" content="@yield('page-description', 'HostelHub - होस्टल प्रबन्धन प्रणाली')">
    
    <!-- Theme variable -->
    @stack('head')
    
    <!-- Fonts & Icons -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Devanagari:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Vite Assets -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- Dynamic Theme CSS Loading -->
    @if(isset($theme) && $theme)
        <link rel="stylesheet" href="{{ asset('css/themes/' . $theme . '.css') }}">
    @endif
    
    <!-- Global Styles -->
    <style>
        :root {
            --primary: #1e3a8a;
            --primary-dark: #1e40af;
            --secondary: #0ea5e9;
            --secondary-dark: #0284c7;
            --accent: #10b981;
            --accent-dark: #059669;
            --text-dark: #1f2937;
            --text-light: #f9fafb;
            --bg-light: #f0f9ff;
            --light-bg: #FFFFFF;
            --border: #e5e7eb;
            --shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            --transition: all 0.3s ease-in-out;
            --radius: 0.75rem;
            --glow: 0 8px 30px rgba(14, 165, 233, 0.25);
            --header-height: 70px;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            color: var(--text-dark);
            background-color: #ffffff;
            font-family: 'Inter', sans-serif;
            line-height: 1.6;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }
        
        .nepali {
            font-family: 'Noto Sans Devanagari', sans-serif;
        }
        
        .container {
            width: 100%;
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 1.5rem;
        }
        
        /* Skip link for accessibility */
        .skip-link {
            position: absolute;
            top: -40px;
            left: 6px;
            background: var(--primary);
            color: white;
            padding: 8px;
            text-decoration: none;
            z-index: 10000;
            border-radius: 0 0 4px 4px;
            transition: top 0.3s;
        }
        .skip-link:focus {
            top: 0;
        }
        
        /* Main Content */
        main {
            flex: 1;
            width: 100%;
        }
        
        /* Ensure content flows properly */
        main#main-content {
            padding-top: 80px;
            padding-bottom: 40px;
        }
        
        /* Utility Classes */
        .smooth-transition {
            transition: all 0.3s ease-in-out;
        }
        
        /* Consistent spacing classes */
        .section-spacing {
            margin-top: 3rem;
            margin-bottom: 3rem;
            padding-top: 1.5rem;
            padding-bottom: 1.5rem;
        }
        
        .gallery-spacing {
            margin-top: 2.5rem;
            margin-bottom: 2.5rem;
        }
        
        /* Fix invisible text issue */
        .availability-text {
            color: #1A1A1A !important;
            font-weight: 600 !important;
        }
        
        .room-count-text {
            color: #374151 !important;
            font-weight: 500 !important;
        }
        
        /* 🚨 FOOTER STYLES - IDENTICAL TO FRONTEND.BLADE.PHP */
        /* Footer Styles - UPDATED WITH FIXED COLUMNS */
        footer {
            background-color: var(--primary);
            color: var(--text-light);
            padding: 2rem 0 1rem !important;
            width: 100vw !important;
            margin: 0 !important;
        }
        
        footer > .container {
            max-width: 1200px !important;
            width: 100% !important;
            margin: 0 auto !important;
            padding: 0 1.5rem !important;
        }
        
        .footer-grid {
            display: grid !important;
            grid-template-columns: 1.5fr 1.2fr 1.5fr 1.2fr !important;
            gap: 2.5rem !important;
            align-items: start !important;
            margin-bottom: 1.5rem !important;
            width: 100% !important;
        }
        
        .footer-col {
            display: flex !important;
            flex-direction: column !important;
            width: 100% !important;
        }
        
        /* 🚨 UPDATED: Column spacing with visual separation */
        .footer-col:nth-child(2) { /* Quick Links */
            padding-right: 1.5rem !important;
            border-right: 1px solid rgba(255, 255, 255, 0.1) !important;
        }
        
        .footer-col:nth-child(3) { /* Contact Info */
            padding-left: 1.5rem !important;
            padding-right: 1.5rem !important;
        }
        
        .footer-col:nth-child(4) { /* Newsletter */
            padding-left: 1.5rem !important;
            border-left: 1px solid rgba(255, 255, 255, 0.1) !important;
        }
        
        .footer-logo-wrapper {
            display: flex !important;
            flex-direction: column !important;
            align-items: flex-start !important;
            margin-bottom: 1rem !important;
            width: 100% !important;
        }
        
        /* 🚨 CRITICAL FIX: Make footer logo clickable */
        .footer-logo {
            display: block !important;
            text-decoration: none !important;
            margin-bottom: 1rem !important;
            width: auto !important;
            cursor: pointer !important;
            position: relative;
            z-index: 10;
        }
        
        .footer-logo:hover {
            opacity: 0.9;
        }
        
        .footer-logo img {
            width: 120px !important;
            height: 120px !important;
            object-fit: contain !important;
            display: block !important;
            margin: 0 0 1rem 0 !important;
        }
        
        .footer-logo-wrapper span {
            font-size: 1.8rem !important;
            font-weight: 700 !important;
            color: white !important;
            display: block !important;
            margin-bottom: 0.5rem !important;
            text-align: left !important;
            font-family: 'Inter', sans-serif !important;
        }
        
        /* 🚨 FIX: Nepali text alignment */
        .footer-logo-wrapper .nepali {
            color: rgba(249, 250, 251, 0.8) !important;
            line-height: 1.6 !important;
            margin-bottom: 1rem !important;
            width: 100% !important;
            font-family: 'Noto Sans Devanagari', sans-serif !important;
            display: block !important;
            text-align: left !important;
            padding-right: 0 !important;
            margin-right: 0 !important;
        }
        
        .social-links {
            display: flex !important;
            gap: 10px !important;
            margin-top: 1rem !important;
            flex-wrap: wrap !important;
            justify-content: flex-start !important;
            width: 100% !important;
        }
        
        .social-links a {
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            width: 36px !important;
            height: 36px !important;
            background: rgba(255, 255, 255, 0.1) !important;
            border-radius: 50% !important;
            color: #f9fafb !important;
            font-size: 1rem !important;
            text-decoration: none !important;
            cursor: pointer !important;
        }
        
        .social-links a:hover {
            background: #0ea5e9 !important;
            color: #f9fafb !important;
            transform: translateY(-3px) !important;
        }
        
        .footer-col h3 {
            font-size: 1.2rem !important;
            margin-bottom: 1rem !important;
            position: relative !important;
            padding-bottom: 0.3rem !important;
            color: #f9fafb !important;
            text-align: left !important;
            width: 100% !important;
            display: block !important;
            font-family: 'Noto Sans Devanagari', sans-serif !important;
            font-weight: bold !important;
        }
        
        .footer-col h3::after {
            content: "" !important;
            position: absolute !important;
            bottom: 0 !important;
            left: 0 !important;
            width: 40px !important;
            height: 2px !important;
            background: #0ea5e9 !important;
            display: block !important;
        }
        
        .footer-links {
            list-style: none !important;
            margin: 0 !important;
            padding: 0 !important;
            width: 100% !important;
            display: block !important;
        }
        
        .footer-links li {
            margin-bottom: 0.5rem !important;
            width: 100% !important;
            display: block !important;
        }
        
        .footer-links a {
            color: rgba(249, 250, 251, 0.8) !important;
            text-decoration: none !important;
            display: flex !important;
            align-items: center !important;
            font-size: 0.9rem !important;
            gap: 0.5rem !important;
            width: 100% !important;
            cursor: pointer !important;
            font-family: 'Noto Sans Devanagari', sans-serif !important;
        }
        
        .footer-links a:hover {
            color: #0ea5e9 !important;
            transform: translateX(3px) !important;
        }
        
        .footer-links i {
            color: #0ea5e9 !important;
            font-size: 0.8rem !important;
            width: 16px !important;
            flex-shrink: 0 !important;
            font-family: 'Font Awesome 6 Free' !important;
            font-weight: 900 !important;
        }
        
        .contact-info {
            list-style: none !important;
            margin: 0 !important;
            padding: 0 !important;
            width: 100% !important;
            display: block !important;
        }
        
        .contact-info li {
            margin-bottom: 0.8rem !important;
            display: flex !important;
            align-items: flex-start !important;
            gap: 12px !important;
            width: 100% !important;
            padding-right: 1rem !important;
        }
        
        .contact-info i {
            color: #0ea5e9 !important;
            font-size: 1rem !important;
            margin-top: 2px !important;
            flex-shrink: 0 !important;
            min-width: 18px !important;
            font-family: 'Font Awesome 6 Free' !important;
            font-weight: 900 !important;
        }
        
        .contact-info div {
            color: rgba(249, 250, 251, 0.8) !important;
            line-height: 1.5 !important;
            width: 100% !important;
            display: block !important;
            font-family: 'Noto Sans Devanagari', sans-serif !important;
            font-size: 0.9rem !important;
        }
        
        .newsletter-form {
            display: flex !important;
            flex-direction: column !important;
            gap: 0.8rem !important;
            margin-top: 0.5rem !important;
            width: 100% !important;
        }
        
        .newsletter-form input {
            padding: 0.8rem 1rem !important;
            border: none !important;
            border-radius: 0.75rem !important;
            font-family: 'Inter', sans-serif !important;
            font-size: 0.95rem !important;
            background: rgba(255, 255, 255, 0.1) !important;
            color: #f9fafb !important;
            width: 100% !important;
        }
        
        .newsletter-form input::placeholder {
            color: rgba(249, 250, 251, 0.6) !important;
        }
        
        .newsletter-form button {
            background: #0ea5e9 !important;
            color: #f9fafb !important;
            border: none !important;
            border-radius: 0.75rem !important;
            padding: 0.8rem 1.5rem !important;
            font-weight: 600 !important;
            cursor: pointer !important;
            font-size: 0.95rem !important;
            width: 100% !important;
            text-align: center !important;
            font-family: 'Noto Sans Devanagari', sans-serif !important;
        }
        
        .newsletter-form button:hover {
            background: #0284c7 !important;
            transform: translateY(-2px) !important;
        }
        
        .copyright {
            margin-top: 2rem !important;
            padding-top: 1rem !important;
            border-top: 1px solid rgba(249, 250, 251, 0.1) !important;
            font-size: 0.9rem !important;
            color: rgba(249, 250, 251, 0.6) !important;
            text-align: center !important;
            width: 100% !important;
            display: block !important;
            font-family: 'Noto Sans Devanagari', sans-serif !important;
        }
        
        /* Responsive Design */
        @media (max-width: 1024px) {
            .footer-grid {
                grid-template-columns: repeat(2, 1fr) !important;
                gap: 2rem !important;
            }
            
            /* Remove borders and padding on medium screens */
            .footer-col:nth-child(2),
            .footer-col:nth-child(3),
            .footer-col:nth-child(4) {
                padding-left: 0 !important;
                padding-right: 0 !important;
                border-left: none !important;
                border-right: none !important;
            }
        }
        
        @media (max-width: 768px) {
    .footer-grid {
        grid-template-columns: 1fr !important;
        gap: 1.5rem !important;
    }
    
    .footer-logo-wrapper {
        align-items: center !important;
        text-align: center !important;
    }
    
    .footer-logo-wrapper span {
        text-align: center !important;
    }
    
    .footer-logo-wrapper .nepali {
        text-align: center !important;
    }
    
    .footer-col h3 {
        text-align: center !important;
    }
    
    .footer-col h3::after {
        left: 50% !important;
        transform: translateX(-50%) !important;
    }
    
    .social-links {
        justify-content: center !important;
    }
    
    /* ===== Quick Links as buttons on mobile only ===== */
    .footer-links a {
        justify-content: center !important;
        display: flex !important;
        align-items: center !important;
        width: 100% !important;
        min-height: 44px !important;
        padding: 0.75rem 1rem !important;
        background: rgba(255, 255, 255, 0.1) !important;
        border-radius: 0.75rem !important;
        margin-bottom: 0.5rem !important;
        color: rgba(249, 250, 251, 0.9) !important;
        font-weight: 500 !important;
        transition: all 0.3s ease !important;
        border: 1px solid rgba(255, 255, 255, 0.15) !important;
    }

    .footer-links a:hover {
        background: rgba(255, 255, 255, 0.2) !important;
        transform: translateY(-2px) !important;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15) !important;
    }

    /* ===== Contact Info centering on mobile only ===== */
    .footer-col:nth-child(3) .contact-info li {
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
        text-align: center !important;
        margin: 0.5rem 0 !important;
        padding: 0 !important;
        width: 100% !important;
        gap: 4px !important;
        max-width: 280px !important;
        margin-left: auto !important;
        margin-right: auto !important;
    }
    
    .footer-col:nth-child(3) .contact-info i {
        margin-right: 0 !important;
        margin-top: 0 !important;
        min-width: 16px !important;
        font-size: 0.9rem !important;
        color: #0ea5e9 !important;
        flex-shrink: 0 !important;
        text-align: center !important;
    }
    
    .footer-col:nth-child(3) .contact-info div {
        text-align: center !important;
        line-height: 1.4 !important;
        font-size: 0.9rem !important;
        flex: 1 !important;
        max-width: calc(100% - 20px) !important;
    }
    
    .footer-col:nth-child(3) .contact-info {
        display: flex !important;
        flex-direction: column !important;
        align-items: center !important;
        justify-content: center !important;
        width: 100% !important;
        padding: 0 !important;
    }
      
    .newsletter-form input,
    .newsletter-form button {
        max-width: 300px !important;
        margin: 0 auto !important;
    }
    
    /* 🚨 FIX: Smaller logo on mobile */
    .footer-logo img {
        width: 80px !important;
        height: 80px !important;
    }
}
        
        @media (max-width: 480px) {
    main#main-content {
        padding-top: 60px;
        padding-bottom: 20px;
    }
    
    .section-spacing {
        margin-top: 1.5rem;
        margin-bottom: 1.5rem;
    }
    
    /* 🚨 FIX: Even smaller logo on small mobile */
    .footer-logo img {
        width: 70px !important;
        height: 70px !important;
    }
    
    /* Even tighter spacing for contact info on very small screens */
    .footer-col:nth-child(3) .contact-info li {
        gap: 3px !important;
        margin: 0.4rem 0 !important;
        max-width: 260px !important;
    }
    
    .footer-col:nth-child(3) .contact-info i {
        min-width: 14px !important;
        font-size: 0.85rem !important;
    }
    
    .footer-col:nth-child(3) .contact-info div {
        font-size: 0.85rem !important;
        max-width: calc(100% - 17px) !important;
    }
}

@media (max-width: 360px) {
    .footer-logo img {
        width: 60px !important;
        height: 60px !important;
    }
    
    /* Ultra small screens - minimal spacing */
    .footer-col:nth-child(3) .contact-info li {
        gap: 2px !important;
        margin: 0.3rem 0 !important;
        max-width: 240px !important;
    }
    
    .footer-col:nth-child(3) .contact-info i {
        min-width: 12px !important;
        font-size: 0.8rem !important;
    }
    
    .footer-col:nth-child(3) .contact-info div {
        font-size: 0.8rem !important;
        max-width: calc(100% - 14px) !important;
    }
}
    
    


    </style>
    
    @stack('styles')
</head>
<body class="@if(isset($theme)){{ $theme }}-theme @endif">
    <!-- Skip Link -->
    <a href="#main-content" class="skip-link nepali">सामग्रीमा जानुहोस्</a>
    
    <!-- Global Header -->
    @if(isset($hostel) && ($hostel->show_hostelhub_branding ?? true))
        @include('components.header')
    @endif
    
    <!-- Main Content Area -->
    <main id="main-content">
        @yield('content')
    </main>
    
    <!-- 🚨 FOOTER - IDENTICAL TO FRONTEND.BLADE.PHP -->
    <footer>
        <div class="container">
            <div class="footer-grid">
                <!-- Column 1: Logo & Description -->
                <div class="footer-col">
                    <div class="footer-logo-wrapper">
                        <a href="{{ url('/') }}" class="footer-logo">
                            <img src="{{ asset('images/logo.png') }}" alt="HostelHub Logo" 
                                 onerror="this.style.display='none'">
                        </a>
                        <span>HostelHub</span>
                        <p class="nepali">
                            नेपालको नम्बर १ होस्टल प्रबन्धन प्रणाली। हामी होस्टल व्यवस्थापनलाई सहज, दक्ष र विश्वसनीय बनाउँछौं।
                        </p>
                    </div>
                    <div class="social-links">
                        <a href="https://www.facebook.com/HostelHubNepal" aria-label="फेसबुक" target="_blank" rel="noopener noreferrer">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                        <a href="#" aria-label="ट्विटर"><i class="fab fa-twitter"></i></a>
                        <a href="#" aria-label="इन्स्टाग्राम"><i class="fab fa-instagram"></i></a>
                        <a href="#" aria-label="लिङ्क्डइन"><i class="fab fa-linkedin-in"></i></a>
                    </div>
                </div>
                
                <!-- Column 2: Quick Links -->
                <div class="footer-col">
                    <h3 class="nepali">तिब्र लिङ्कहरू</h3>
                    <ul class="footer-links">
                        <li><a href="{{ route('home') }}"><i class="fas fa-chevron-right"></i> <span class="nepali">होम</span></a></li>
                        <li><a href="{{ route('features') }}"><i class="fas fa-chevron-right"></i> <span class="nepali">सुविधाहरू</span></a></li>
                        <li><a href="{{ route('how-it-works') }}"><i class="fas fa-chevron-right"></i> <span class="nepali">कसरी काम गर्छ</span></a></li>
                        <li><a href="{{ route('gallery.index') }}"><i class="fas fa-chevron-right"></i> <span class="nepali">ग्यालरी</span></a></li>
                        <li><a href="{{ route('pricing') }}"><i class="fas fa-chevron-right"></i> <span class="nepali">मूल्य</span></a></li>
                        <li><a href="{{ route('testimonials') }}"><i class="fas fa-chevron-right"></i> <span class="nepali">प्रशंसापत्रहरू</span></a></li>
                        <li><a href="{{ route('about') }}"><i class="fas fa-chevron-right"></i> <span class="nepali">हाम्रो बारेमा</span></a></li>
                        <li><a href="{{ route('privacy') }}"><i class="fas fa-chevron-right"></i> <span class="nepali">गोप्यता नीति</span></a></li>
                        <li><a href="{{ route('terms') }}"><i class="fas fa-chevron-right"></i> <span class="nepali">सेवा सर्तहरू</span></a></li>
                    </ul>
                </div>
                
                <!-- Column 3: Contact Info -->
                <div class="footer-col">
                    <h3 class="nepali">सम्पर्क जानकारी</h3>
                    <ul class="contact-info">
                        <li>
                            <i class="fas fa-map-marker-alt"></i>
                            <div class="nepali">कमलपोखरी, काठमाडौं, नेपाल</div>
                        </li>
                        <li>
                            <i class="fas fa-phone-alt"></i>
                            <div>+९७७ ९७६१७६२०३६</div>
                        </li>
                        <li>
                            <i class="fas fa-envelope"></i>
                            <div>info@hostelhub.com</div>
                        </li>
                        <li>
                            <i class="fas fa-clock"></i>
                            <div class="nepali">सोम-शुक्र: ९:०० बिहान - ५:०० बेलुका</div>
                        </li>
                    </ul>
                </div>
                
                <!-- Column 4: Newsletter -->
                <div class="footer-col">
                    <h3 class="nepali">समाचारपत्र</h3>
                    <p class="nepali">
                        हाम्रो नवीनतम अपडेटहरू प्राप्त गर्न तपाईंको इमेल दर्ता गर्नुहोस्
                    </p>
                    <form class="newsletter-form" action="{{ route('newsletter.subscribe') }}" method="POST">
                        @csrf
                        <input type="email" name="email" placeholder="तपाईंको इमेल" required aria-label="इमेल ठेगाना">
                        <input type="text" name="honeypot" style="display:none" aria-hidden="true">
                        <button type="submit" class="nepali">दर्ता गर्नुहोस्</button>
                    </form>
                </div>
            </div>
            
            <!-- Copyright -->
            <div class="copyright">
                <p class="nepali">© 2025 HostelHub. सबै अधिकार सुरक्षित।</p>
            </div>
        </div>
    </footer>

    @stack('scripts')
    
    <script>
        // Footer logo click fix
        document.addEventListener('DOMContentLoaded', function() {
            const footerLogo = document.querySelector('.footer-logo');
            if (footerLogo) {
                footerLogo.style.cursor = 'pointer';
                footerLogo.style.textDecoration = 'none';
                
                // Backup click handler
                footerLogo.addEventListener('click', function(e) {
                    console.log('Footer logo clicked, navigating to home page');
                });
            }
        });
    </script>
</body>
</html>