<!DOCTYPE html>
<html lang="ne" dir="ltr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="HostelHub — होस्टल व्यवस्थापन सजिलो बनाउने SaaS: कोठा, विद्यार्थी, भुक्तानी र भोजन व्यवस्थापन। ७ दिन निःशुल्क ट्रयाल।">
    <title>@yield('page-title', 'HostelHub — होस्टल प्रबन्धन प्रणाली | Nepal')</title>
    <meta property="og:title" content="@yield('og-title', 'HostelHub — होस्टल प्रबन्धन प्रणाली')">
    <meta property="og:description" content="@yield('og-description', 'HostelHub — होस्टल व्यवस्थापन सजिलो बनाउने SaaS')">
    
    <!-- 🚨 IMPORTANT: Ensure FontAwesome loads properly -->
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Devanagari:wght@400;500;600;700&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])

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
        
        html, body {
            overflow-x: hidden;
            width: 100%;
            max-width: 100vw;
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
        
        .btn {
            display: inline-block;
            padding: 0.85rem 1.85rem;
            border-radius: var(--radius);
            font-weight: 600;
            text-align: center;
            text-decoration: none;
            transition: var(--transition);
            cursor: pointer;
            font-size: 1rem;
            border: none;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }
        .btn-primary {
            background: linear-gradient(to right, var(--primary), var(--secondary));
            color: var(--text-light);
            position: relative;
            overflow: hidden;
            z-index: 1;
        }
        .btn-primary:hover {
            background: linear-gradient(to right, var(--primary-dark), var(--secondary-dark));
            transform: translateY(-3px);
            box-shadow: var(--glow);
        }
        .btn-primary::after {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
            transition: 0.5s;
            z-index: -1;
        }
        .btn-primary:hover::after {
            left: 100%;
        }
        .btn-outline {
            background-color: transparent;
            color: var(--text-light);
            border: 2px solid var(--text-light);
            transition: all 0.3s ease;
        }
        .btn-outline:hover {
            background-color: var(--text-light);
            color: var(--primary);
            transform: translateY(-3px);
        }
        
        /* Header Styles */
        #site-header {
            position: fixed;
            top: 0;
            width: 100%;
            z-index: 1000;
            background: var(--primary);
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
            height: var(--header-height);
            left: 0;
            right: 0;
        }
        .header-scrolled {
            padding: 0.5rem 0;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
        }
        .header-inner {
            padding: 0.5rem 0;
            transition: all 0.3s ease;
            height: 100%;
            display: flex;
            align-items: center;
        }
        .navbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            width: 100%;
            height: 100%;
            min-width: 0;
        }
        /* Logo styles */
        .logo {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            text-decoration: none;
            font-weight: 700;
            font-size: 1.3rem;
            color: var(--text-light);
            flex-shrink: 1;
            min-width: 0;
        }
        header .logo img {
            width: 75px;
            height: 75px;
            object-fit: contain;
        }
        .logo-image {
            width: 60px;
            height: 60px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: transparent;
            border-radius: var(--radius);
            color: var(--text-light);
            font-weight: bold;
            font-size: 18px;
            flex-shrink: 0;
            border: 2px solid var(--secondary);
            padding: 4px;
        }
        .logo-text {
            display: flex;
            flex-direction: column;
        }
        .logo-text h1 {
            font-size: 1.3rem;
            line-height: 1.2;
            margin: 0;
            white-space: nowrap;
        }
        .logo-text span {
            font-size: 0.7rem;
            line-height: 1;
            opacity: 0.9;
        }
        .nav-links {
            display: flex;
            align-items: center;
            gap: 1.5rem;
        }
        .nav-links a {
            text-decoration: none;
            color: rgba(255, 255, 255, 0.85);
            font-weight: 500;
            transition: var(--transition);
            position: relative;
            padding: 0.4rem 0;
            font-size: 0.95rem;
        }
        .nav-links a.active, .nav-links a:hover {
            color: var(--text-light);
        }
        .nav-links a::after {
            content: "";
            position: absolute;
            bottom: 0;
            left: 0;
            width: 0;
            height: 2px;
            background: var(--secondary);
            transition: var(--transition);
        }
        .nav-links a.active::after, .nav-links a:hover::after {
            width: 100%;
        }
        .header-cta {
            display: flex;
            gap: 0.8rem;
            align-items: center;
        }
        .mobile-menu-btn {
            display: none;
            background: none;
            border: none;
            font-size: 1.3rem;
            cursor: pointer;
            color: var(--text-light);
            flex-shrink: 0;
        }
        
        /* Page Header Styles */
        .page-header {
            background: linear-gradient(to right, var(--primary), var(--secondary));
            color: var(--text-light);
            padding: 2.5rem 0;
            margin-top: var(--header-height);
            text-align: center;
        }
        .page-header h1 {
            font-size: 2.2rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }
        .page-header p {
            font-size: 1.1rem;
            max-width: 700px;
            margin: 0 auto;
            opacity: 0.9;
        }
        
        /* 🚨 CRITICAL FIX: Main Content Styles - HOME PAGE SPECIFIC */
        main {
            flex: 1;
        }
        
        /* Home page should have no top padding/margin */
        .home-page-main {
            padding-top: 0 !important;
            margin-top: 0 !important;
        }
        
        /* Other pages should have proper spacing */
        .other-page-main {
            padding-top: 2rem !important;
            margin-top: var(--header-height) !important;
        }
        
        .content-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 1.5rem;
        }
        
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
            /* 🚨 IMPORTANT: Ensure FontAwesome icons display */
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
            /* 🚨 IMPORTANT: Ensure FontAwesome icons display */
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
        
        /* Smooth transition utility */
        .smooth-transition {
            transition: all 0.3s ease-in-out;
        }
        
        /* 🚨 CRITICAL FIX: Remove any duplicate header */
        header.fixed.top-0.left-0.right-0.z-50.bg-indigo-900,
        .fixed.top-0.left-0.right-0.z-50.bg-indigo-900 {
            display: none !important;
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
            .mobile-menu-btn {
                display: block;
            }
            .nav-links {
                display: none;
                position: absolute;
                top: 100%;
                left: 0;
                right: 0;
                background: var(--primary);
                flex-direction: column;
                padding: 1.2rem;
                box-shadow: 0 10px 15px rgba(0,0,0,0.1);
                border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            }
            .nav-links.show {
                display: flex;
            }
            .header-cta {
                display: none;
            }
            .newsletter-form {
                flex-direction: column;
            }
            .newsletter-form input {
                min-width: 100%;
            }
            .page-header {
                padding: 1.8rem 0;
            }
            .page-header h1 {
                font-size: 1.8rem;
            }
            .page-header p {
                font-size: 1rem;
            }
            .content-container {
                padding: 0 1.2rem;
            }
            
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
            
            .footer-links a {
                justify-content: center !important;
            }
            
            .contact-info li {
                justify-content: center !important;
                text-align: center !important;
                padding-right: 0 !important;
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
            :root {
                --header-height: 60px;
            }
            header .logo img {
                width: 60px;
                height: 60px;
            }
            .page-header {
                padding: 1.5rem 0;
            }
            .page-header h1 {
                font-size: 1.6rem;
            }
            .content-container {
                padding: 0 1rem;
            }
            
            /* 🚨 FIX: Even smaller logo on small mobile */
            .footer-logo img {
                width: 70px !important;
                height: 70px !important;
            }
        }
        
        @media (max-width: 360px) {
            header .logo img {
                width: 50px;
                height: 50px;
            }
            .logo-text h1 {
                font-size: 1rem;
            }
            .logo-text span {
                font-size: 0.6rem;
            }
            .page-header h1 {
                font-size: 1.4rem;
            }
            
            .footer-logo img {
                width: 60px !important;
                height: 60px !important;
            }
        }
    </style>

    @stack('styles')
</head>
<body>
    <!-- Skip to content link for accessibility -->
    <a href="#main" class="skip-link nepali">सामग्रीमा जानुहोस्</a>
    
    <!-- Fixed Header - ONLY ONE HEADER -->
    <header id="site-header">
        <div class="header-inner">
            <div class="container">
                <div class="navbar">
                    <!-- Header Logo -->
                    <a href="{{ route('home') }}" class="logo" style="margin-right: auto;">
                        <div class="logo-image">
                            <img src="{{ asset('images/logo.png') }}" alt="HostelHub Logo" style="height: 50px; width: auto;" onerror="this.style.display='none'">
                        </div>
                        <div class="logo-text">
                            <h1>HostelHub</h1>
                            <span class="nepali">होस्टल प्रबन्धन</span>
                        </div>
                    </a>
                    
                    <!-- Navigation Links with Auth Support -->
                    <div class="nav-links" id="main-nav">
                        <a href="{{ route('features') }}" class="nepali">सुविधाहरू</a>
                        <a href="{{ route('how-it-works') }}" class="nepali">कसरी काम गर्छ</a>
                        <a href="{{ route('gallery') }}" class="nepali">ग्यालरी</a>
                        <a href="{{ route('pricing') }}" class="nepali">मूल्य</a>
                        <a href="{{ route('testimonials') }}" class="nepali">प्रशंसापत्रहरू</a>
                        <a href="{{ route('about') }}" class="nepali">हाम्रो बारेमा</a>
                        <a href="{{ route('privacy') }}" class="nepali">गोप्यता नीति</a>
                        <a href="{{ route('terms') }}" class="nepali">सेवा सर्तहरू</a>
                    </div>

                    <!-- Header CTA with Auth Support -->
                    <div class="header-cta" style="margin-left: 2rem;">
                        @auth
                            @if(Auth::user()->hasRole('admin'))
                                <a href="{{ route('admin.dashboard') }}" class="nepali dashboard-link" style="color: white !important; text-decoration: none; font-weight: 600; padding: 0.5rem 1.5rem; border: 2px solid white; border-radius: var(--radius); transition: var(--transition); margin-right: 0.8rem;">
                                    ड्यासबोर्ड
                                </a>
                            @elseif(Auth::user()->hasRole('owner') || Auth::user()->hasRole('hostel_manager'))
                                <a href="{{ route('owner.dashboard') }}" class="nepali dashboard-link" style="color: white !important; text-decoration: none; font-weight: 600; padding: 0.5rem 1.5rem; border: 2px solid white; border-radius: var(--radius); transition: var(--transition); margin-right: 0.8rem;">
                                    ड्यासबोर्ड
                                </a>
                            @elseif(Auth::user()->hasRole('student'))
                                <a href="{{ route('student.dashboard') }}" class="nepali dashboard-link" style="color: white !important; text-decoration: none; font-weight: 600; padding: 0.5rem 1.5rem; border: 2px solid white; border-radius: var(--radius); transition: var(--transition); margin-right: 0.8rem;">
                                    ड्यासबोर्ड
                                </a>
                            @endif
                            
                            <form method="POST" action="{{ route('logout') }}" class="inline" style="margin: 0;">
                                @csrf
                                <button type="submit" class="nepali logout-btn" style="color: white !important; text-decoration: none; font-weight: 600; padding: 0.5rem 1.5rem; border: 2px solid white; border-radius: var(--radius); transition: var(--transition); background: transparent; cursor: pointer;">
                                    लगआउट
                                </button>
                            </form>
                        @else
                            <a href="{{ route('login') }}" class="btn btn-outline nepali">लगइन</a>
                            <a href="{{ route('register') }}" class="btn btn-primary nepali">साइन अप</a>
                        @endauth
                    </div>
                    
                    <button class="mobile-menu-btn" aria-label="मेनु खोल्नुहोस्" aria-expanded="false" aria-controls="main-nav">
                        <i class="fas fa-bars"></i>
                    </button>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main id="main" class="main-content-global @if(Request::route()->getName() == 'home')home-page-main @else other-page-main @endif">
        <div class="content-container">
            @yield('content')
        </div>
    </main>

    <!-- FOOTER - ULTIMATE FIX WITH PROPER LOGO SIZE AND CLICKABLE -->
    <footer>
        <div class="container">
            <div class="footer-grid">
                <!-- Column 1: Logo & Description -->
                <div class="footer-col">
                    <div class="footer-logo-wrapper">
                        <!-- 🚨 ULTIMATE FIX: Simple clickable logo without inline styles -->
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
                        <li><a href="{{ route('gallery') }}"><i class="fas fa-chevron-right"></i> <span class="nepali">ग्यालरी</span></a></li>
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

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Header scroll behavior
            window.addEventListener('scroll', () => {
                const siteHeader = document.getElementById('site-header');
                if (siteHeader) {
                    if (window.scrollY > 40) {
                        siteHeader.classList.add('header-scrolled');
                    } else {
                        siteHeader.classList.remove('header-scrolled');
                    }
                }
            });
            
            // Mobile menu toggle
            const menuBtn = document.querySelector('.mobile-menu-btn');
            const navLinks = document.getElementById('main-nav');
            if (menuBtn && navLinks) {
                menuBtn.addEventListener('click', () => {
                    const expanded = menuBtn.getAttribute('aria-expanded') === 'true' || false;
                    menuBtn.setAttribute('aria-expanded', !expanded);
                    navLinks.classList.toggle('show');
                    
                    const icon = menuBtn.querySelector('i');
                    if (icon) {
                        if (!expanded) {
                            icon.className = 'fas fa-times';
                        } else {
                            icon.className = 'fas fa-bars';
                        }
                    }
                });
            }

            // Close mobile menu when clicking on a link
            document.querySelectorAll('.nav-links a').forEach(link => {
                link.addEventListener('click', () => {
                    if (navLinks && navLinks.classList.contains('show')) {
                        navLinks.classList.remove('show');
                        if (menuBtn) {
                            menuBtn.setAttribute('aria-expanded', 'false');
                            const icon = menuBtn.querySelector('i');
                            if (icon) {
                                icon.className = 'fas fa-bars';
                            }
                        }
                    }
                });
            });

            // Close mobile menu when clicking outside
            document.addEventListener('click', (event) => {
                const isClickInsideNav = navLinks?.contains(event.target);
                const isClickOnMenuBtn = menuBtn?.contains(event.target);
                
                if (navLinks && navLinks.classList.contains('show') && !isClickInsideNav && !isClickOnMenuBtn) {
                    navLinks.classList.remove('show');
                    if (menuBtn) {
                        menuBtn.setAttribute('aria-expanded', 'false');
                        const icon = menuBtn.querySelector('i');
                        if (icon) {
                            icon.className = 'fas fa-bars';
                        }
                    }
                }
            });

            // 🚨 ULTIMATE FOOTER LOGO CLICK FIX
            const footerLogo = document.querySelector('.footer-logo');
            if (footerLogo) {
                // Make sure the logo is clickable
                footerLogo.style.cursor = 'pointer';
                footerLogo.style.textDecoration = 'none';
                
                // Add click event as backup
                footerLogo.addEventListener('click', function(e) {
                    // Let the anchor tag do its default behavior
                    // This is just a backup in case CSS is blocking it
                });
                
                // Debug: Log when logo is clicked
                footerLogo.addEventListener('click', function() {
                    console.log('Footer logo clicked, navigating to home page');
                });
            }
            
            // 🚨 NUCLEAR OPTION: If still not working, force redirect
            setTimeout(() => {
                const logoAnchor = document.querySelector('.footer-logo');
                if (logoAnchor) {
                    // Ensure it's really an anchor with href
                    logoAnchor.setAttribute('href', '{{ url("/") }}');
                    logoAnchor.setAttribute('title', 'Go to Home Page');
                    
                    // Force remove any blocking styles
                    logoAnchor.style.pointerEvents = 'auto';
                    logoAnchor.style.zIndex = '1000';
                    logoAnchor.style.position = 'relative';
                }
            }, 500);
        });
    </script>

    @stack('scripts')
</body>
</html>