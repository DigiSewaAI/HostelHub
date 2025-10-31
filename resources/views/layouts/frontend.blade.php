<!DOCTYPE html>
<html lang="ne" dir="ltr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="HostelHub — होस्टल व्यवस्थापन सजिलो बनाउने SaaS: कोठा, विद्यार्थी, भुक्तानी र भोजन व्यवस्थापन। ७ दिन निःशुल्क ट्रयाल।">
    <title>@yield('page-title', 'HostelHub — होस्टल प्रबन्धन प्रणाली | Nepal')</title>
    <meta property="og:title" content="@yield('og-title', 'HostelHub — होस्टल प्रबन्धन प्रणाली')">
    <meta property="og:description" content="@yield('og-description', 'HostelHub — होस्टल व्यवस्थापन सजिलो बनाउने SaaS')">
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Devanagari:wght@400;500;600;700&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- CSS Loading Strategy -->
    @env('local')
        <!-- Local Development: Vite Hot Reload -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @else
        <!-- Production: Built Assets -->
        <link rel="stylesheet" href="{{ asset('build/assets/app.css') }}">
        <script type="module" src="{{ asset('build/assets/app.js') }}" defer></script>
    @endenv

    <!-- Main Layout Styles - INLINE FOR RELIABILITY -->
    <style>
        /* CSS styles will be the same as in home.blade.php */
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
            background: var(--secondary);
            border-radius: var(--radius);
            color: var(--text-light);
            font-weight: bold;
            font-size: 18px;
            flex-shrink: 0;
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
        
        /* Main Content Styles */
        main {
            flex: 1;
            padding-top: 0;
        }
        .content-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 2.5rem 1.5rem;
        }
        
        /* Footer Styles */
        footer {
            background-color: var(--primary);
            color: var(--text-light);
            padding: 1rem 0 0.2rem;
        }
        .footer-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.2rem;
            margin-bottom: 1rem;
        }
        .footer-col h3 {
            font-size: 1.3rem;
            margin-bottom: 1rem;
            position: relative;
            padding-bottom: 0.4rem;
            color: var(--text-light);
        }
        .footer-col h3::after {
            content: "";
            position: absolute;
            bottom: 0;
            left: 0;
            width: 50px;
            height: 3px;
            background: var(--secondary);
        }
        .footer-links {
            list-style: none;
            margin: 0;
            padding: 0;
        }
        .footer-links li {
            margin-bottom: 0.6rem;
        }
        .footer-links a {
            color: rgba(249, 250, 251, 0.8);
            text-decoration: none;
            transition: var(--transition);
            display: flex;
            align-items: center;
            font-size: 0.95rem;
        }
        .footer-links a:hover {
            color: var(--secondary);
            transform: translateX(5px);
        }
        .footer-links i {
            margin-right: 10px;
            width: 18px;
            color: var(--secondary);
        }
        .contact-info {
            list-style: none;
            margin: 0;
            padding: 0;
        }
        .contact-info li {
            margin-bottom: 0.8rem;
            display: flex;
            align-items: flex-start;
        }
        .contact-info i {
            margin-right: 12px;
            color: var(--secondary);
            font-size: 1.1rem;
            margin-top: 4px;
            flex-shrink: 0;
        }
        .footer-logo {
            display: flex;
            align-items: center;
            gap: 0.6rem;
            font-size: 1.6rem;
            font-weight: 700;
            margin-bottom: 1rem;
            color: var(--text-light);
            text-decoration: none;
        }
        .footer-logo-icon {
            width: 35px;
            height: 35px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: var(--secondary);
            border-radius: var(--radius);
            color: var(--text-light);
            font-weight: bold;
            font-size: 18px;
        }
        .copyright {
            margin-top: 1.2rem;
            padding-top: 0.8rem;
            border-top: 1px solid rgba(249, 250, 251, 0.1);
            font-size: 1rem;
            color: rgba(249, 250, 251, 0.6);
            text-align: center;
            grid-column: 1 / -1;
        }
        .social-links {
            display: flex;
            gap: 10px;
            margin-top: 12px;
            flex-wrap: wrap;
        }
        .social-links a {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 36px;
            height: 36px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            color: var(--text-light);
            font-size: 1rem;
            transition: var(--transition);
            text-decoration: none;
        }
        .social-links a:hover {
            background: var(--secondary);
            color: var(--text-light);
            transform: translateY(-3px);
        }
        .newsletter-form {
            display: flex;
            gap: 0.4rem;
            margin-top: 0.8rem;
            flex-wrap: wrap;
        }
        .newsletter-form input {
            flex: 1;
            min-width: 200px;
            padding: 0.7rem 0.9rem;
            border: none;
            border-radius: var(--radius);
            font-family: inherit;
            font-size: 0.9rem;
            background: rgba(255, 255, 255, 0.1);
            color: var(--text-light);
        }
        .newsletter-form input::placeholder {
            color: rgba(249, 255, 251, 0.6);
        }
        .newsletter-form button {
            background: var(--secondary);
            color: var(--text-light);
            border: none;
            border-radius: var(--radius);
            padding: 0.7rem 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: var(--transition);
            font-size: 0.9rem;
            white-space: nowrap;
        }
        .newsletter-form button:hover {
            background: var(--secondary-dark);
            transform: translateY(-3px);
        }
        
        /* Smooth transition utility */
        .smooth-transition {
            transition: all 0.3s ease-in-out;
        }
        
        /* Responsive Design */
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
                padding: 1.8rem 1.2rem;
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
                padding: 1.5rem 1rem;
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
        }
    </style>

    <!-- Stack for additional styles from child views -->
    @stack('styles')
</head>
<body>
    <!-- Skip to content link for accessibility -->
    <a href="#main" class="skip-link nepali">सामग्रीमा जानुहोस्</a>
    
    <!-- Fixed Header -->
    <header id="site-header">
        <div class="header-inner">
            <div class="container">
                <div class="navbar">
                    <!-- Header Logo - FIXED PATH -->
                    <a href="{{ route('home') }}" class="logo">
                        <!-- Real Logo Image with corrected path -->
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
                        
                        @auth
                            <!-- Dashboard Link for Authenticated Users -->
                            @if(Auth::user()->hasRole('admin'))
                                <a href="{{ route('admin.dashboard') }}" class="nepali">ड्यासबोर्ड</a>
                            @elseif(Auth::user()->hasRole('owner') || Auth::user()->hasRole('hostel_manager'))
                                <a href="{{ route('owner.dashboard') }}" class="nepali">ड्यासबोर्ड</a>
                            @elseif(Auth::user()->hasRole('student'))
                                <a href="{{ route('student.dashboard') }}" class="nepali">ड्यासबोर्ड</a>
                            @endif
                        @else
                            <a href="{{ route('login') }}" class="nepali">लगइन</a>
                        @endauth
                    </div>

                    <!-- Header CTA with Auth Support -->
                    <div class="header-cta">
                        @auth
                            <!-- Logout Button for Authenticated Users -->
                            <form method="POST" action="{{ route('logout') }}" class="inline">
                                @csrf
                                <button type="submit" class="btn btn-outline nepali" style="background: transparent; color: var(--text-light); border: 2px solid var(--text-light); font-weight: 600;">
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

    <!-- Page Header Section -->
    @hasSection('page-header')
        <section class="page-header">
            <div class="container">
                <h1 class="nepali">@yield('page-header')</h1>
                @hasSection('page-description')
                    <p class="nepali">@yield('page-description')</p>
                @endif
            </div>
        </section>
    @endif

    <!-- Main Content -->
    <main id="main">
        <div class="content-container">
            @yield('content')
        </div>
    </main>

    <!-- Footer -->
    <footer>
        <div class="container">
            <div class="footer-grid">
                <div class="footer-col">
                    <!-- Footer Logo with corrected path -->
                    <a href="{{ route('home') }}" class="footer-logo">
                        <img src="{{ asset('images/logo.png') }}" alt="HostelHub Logo" style="height: 40px; width: auto;" onerror="this.style.display='none'">
                        <span>HostelHub</span>
                    </a>
                    <p class="nepali" style="color: rgba(249, 250, 251, 0.8); margin-top: 12px; line-height: 1.6;">
                        नेपालको नम्बर १ होस्टल प्रबन्धन प्रणाली। हामी होस्टल व्यवस्थापनलाई सहज, दक्ष र विश्वसनीय बनाउँछौं।
                    </p>
                    <div class="social-links">
                        <a href="#" aria-label="फेसबुक"><i class="fab fa-facebook-f"></i></a>
                        <a href="#" aria-label="ट्विटर"><i class="fab fa-twitter"></i></a>
                        <a href="#" aria-label="इन्स्टाग्राम"><i class="fab fa-instagram"></i></a>
                        <a href="#" aria-label="लिङ्क्डइन"><i class="fab fa-linkedin-in"></i></a>
                    </div>
                </div>
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
                <div class="footer-col">
                    <h3 class="nepali">सम्पर्क जानकारी</h3>
                    <ul class="contact-info">
                        <li>
                            <i class="fas fa-map-marker-alt" aria-hidden="true"></i>
                            <div class="nepali">कमलपोखरी, काठमाडौं, नेपाल</div>
                        </li>
                        <li>
                            <i class="fas fa-phone-alt" aria-hidden="true"></i>
                            <div>+९७७ ९७६१७६२०३६</div>
                        </li>
                        <li>
                            <i class="fas fa-envelope" aria-hidden="true"></i>
                            <div>info@hostelhub.com</div>
                        </li>
                        <li>
                            <i class="fas fa-clock" aria-hidden="true"></i>
                            <div class="nepali">सोम-शुक्र: ९:०० बिहान - ५:०० बेलुका</div>
                        </li>
                    </ul>
                </div>
                <div class="footer-col">
                    <h3 class="nepali">समाचारपत्र</h3>
                    <p class="nepali" style="color: rgba(249, 250, 251, 0.8); margin-bottom: 12px; line-height: 1.6;">
                        हाम्रो नवीनतम अपडेटहरू प्राप्त गर्न तपाईंको इमेल दर्ता गर्नुहोस्
                    </p>
                    <form class="newsletter-form" action="{{ route('newsletter.subscribe') }}" method="POST">
                        @csrf
                        <input type="email" name="email" placeholder="तपाईंको इमेल" required aria-label="इमेल ठेगाना">
                        <input type="text" name="honeypot" style="display:none" aria-hidden="true">
                        <button type="submit" class="nepali">दर्ता गर्नुहोस्</button>
                    </form>
                </div>
                <div class="copyright">
                    <p class="nepali">© 2025 HostelHub. सबै अधिकार सुरक्षित।</p>
                </div>
            </div>
        </div>
    </footer>

    <!-- JavaScript Loading Strategy -->
    @env('local')
        <!-- Vite will handle this automatically -->
    @else
        <!-- Production JS Fallback -->
        <script>
            // Header scroll behavior
            window.addEventListener('scroll', () => {
                const siteHeader = document.getElementById('site-header');
                if (window.scrollY > 40) {
                    siteHeader.classList.add('header-scrolled');
                } else {
                    siteHeader.classList.remove('header-scrolled');
                }
            });
            
            // Mobile menu toggle
            const menuBtn = document.querySelector('.mobile-menu-btn');
            const navLinks = document.querySelector('.nav-links');
            if (menuBtn && navLinks) {
                menuBtn.addEventListener('click', () => {
                    const expanded = menuBtn.getAttribute('aria-expanded') === 'true' || false;
                    menuBtn.setAttribute('aria-expanded', !expanded);
                    navLinks.classList.toggle('show');
                });
            }

            // Close mobile menu when clicking on a link
            document.querySelectorAll('.nav-links a').forEach(link => {
                link.addEventListener('click', () => {
                    if (navLinks.classList.contains('show')) {
                        navLinks.classList.remove('show');
                        menuBtn.setAttribute('aria-expanded', 'false');
                    }
                });
            });
        </script>
    @endenv
    
    <!-- Stack for additional scripts from child views -->
    @stack('scripts')
</body>
</html>