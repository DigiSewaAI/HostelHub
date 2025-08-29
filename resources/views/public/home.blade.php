<!DOCTYPE html>
<html lang="ne" dir="ltr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="HostelHub — होस्टल व्यवस्थापन सजिलो बनाउने SaaS: कोठा, विद्यार्थी, भुक्तानी र भोजन व्यवस्थापन। ७ दिन निःशुल्क ट्रयाल।">
    <title>HostelHub — होस्टल प्रबन्धन प्रणाली | Nepal</title>
    <meta property="og:title" content="HostelHub — होस्टल प्रबन्धन प्रणाली">
    <meta property="og:description" content="HostelHub — होस्टल व्यवस्थापन सजिलो बनाउने SaaS">
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Devanagari:wght@400;500;600;700&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css">
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.12.0/dist/cdn.min.js" defer></script>
    <style>
        :root {
            --primary: #1e3a8a;       /* Navy Royal Blue */
            --primary-dark: #1e40af;   /* Darker Navy for hover */
            --secondary: #0ea5e9;      /* Sky Blue */
            --secondary-dark: #0284c7; /* Darker Sky Blue */
            --accent: #10b981;         /* Accent Green */
            --accent-dark: #059669;    /* Darker Green */
            --text-dark: #1f2937;      /* Almost black text */
            --text-light: #f9fafb;     /* Light text for dark backgrounds */
            --bg-light: #f0f9ff;       /* Light Blue Background */
            --light-bg: #FFFFFF;       /* Cards background */
            --border: #e5e7eb;         /* Borders */
            --shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            --transition: all 0.3s ease-in-out;
            --radius: 0.75rem;
            --glow: 0 8px 30px rgba(14, 165, 233, 0.25);
            --header-height: 70px;     /* Reduced header height */
        }
        
        /* Prevent horizontal overflow on mobile */
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
            background-color: var(--primary);
            color: var(--text-light);
            border: 2px solid var(--primary);
            transition: all 0.3s ease;
        }
        .btn-outline:hover {
            background-color: var(--primary-dark);
            color: var(--text-light);
            transform: translateY(-3px);
            border-color: var(--primary-dark);
            box-shadow: 0 0 15px rgba(30, 58, 138, 0.4);
        }
        .section {
            padding: 4rem 0; /* Reduced section padding */
        }
        .section-title {
            font-size: 2.25rem;
            font-weight: 800;
            margin-bottom: 1.5rem;
            color: var(--primary);
            text-align: center;
            position: relative;
            display: inline-block;
            width: 100%;
        }
        .section-title::after {
            content: "";
            position: absolute;
            bottom: -10px;
            left: 50%;
            transform: translateX(-50%);
            width: 80px;
            height: 4px;
            background: var(--secondary);
            border-radius: 2px;
        }
        .section-subtitle {
            font-size: 1.25rem;
            color: #4B5563;
            text-align: center;
            max-width: 700px;
            margin: 0 auto 3rem;
            line-height: 1.7;
        }
        /* Header Styles - REDUCED */
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
            padding: 0.5rem 0; /* Reduced padding */
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
        }
        .header-inner {
            padding: 0.5rem 0; /* Reduced padding */
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
            min-width: 0; /* Allow flex items to shrink */
        }
        /* Logo styles - UPDATED */
        .logo {
            display: flex;
            align-items: center;
            gap: 0.5rem; /* Reduced gap */
            text-decoration: none;
            font-weight: 700;
            font-size: 1.3rem; /* Reduced font size */
            color: var(--text-light);
            flex-shrink: 1;
            min-width: 0; /* Allow logo to shrink */
        }
        header .logo img {
            width: 75px; /* Reduced logo size */
            height: 75px; /* Reduced logo size */
        }
        .logo-image {
            width: 60px; /* Reduced size */
            height: 60px; /* Reduced size */
            display: flex;
            align-items: center;
            justify-content: center;
            background: var(--secondary);
            border-radius: var(--radius);
            color: var(--text-light);
            font-weight: bold;
            font-size: 18px; /* Reduced font size */
            flex-shrink: 0;
        }
        .logo-text {
            display: flex;
            flex-direction: column;
        }
        .logo-text h1 {
            font-size: 1.3rem; /* Reduced font size */
            line-height: 1.2;
            margin: 0;
            white-space: nowrap;
        }
        .logo-text span {
            font-size: 0.7rem; /* Reduced font size */
            line-height: 1;
            opacity: 0.9;
        }
        .nav-links {
            display: flex;
            align-items: center;
            gap: 1.5rem; /* Reduced gap */
        }
        .nav-links a {
            text-decoration: none;
            color: rgba(255, 255, 255, 0.85);
            font-weight: 500;
            transition: var(--transition);
            position: relative;
            padding: 0.4rem 0; /* Reduced padding */
            font-size: 0.95rem; /* Reduced font size */
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
            gap: 0.8rem; /* Reduced gap */
            align-items: center;
        }
        .mobile-menu-btn {
            display: none;
            background: none;
            border: none;
            font-size: 1.3rem; /* Reduced font size */
            cursor: pointer;
            color: var(--text-light);
            flex-shrink: 0; /* Prevent button from shrinking */
        }
        /* Hero Section - IMPROVED */
        .hero {
            min-height: 100vh;
            padding: var(--header-height) 0 0; /* Account for header height */
            background: linear-gradient(135deg, #1e3a8a, #0ea5e9);
            position: relative;
            overflow: hidden;
            z-index: 1;
            display: flex;
            align-items: center;
        }
        .hero-video {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
            z-index: -1;
            opacity: 0.8;
        }
        .hero-content {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 2rem; /* Reduced gap */
            align-items: center;
            height: 100%;
            position: relative;
            z-index: 15;
            padding: 2rem 0; /* Added padding */
        }
        .hero-text {
            max-width: 600px;
            color: var(--text-light);
        }
        .hero-title {
            font-size: 2.8rem; /* Reduced font size */
            font-weight: 800;
            line-height: 1.2;
            margin-bottom: 1.2rem; /* Reduced margin */
            color: var(--text-light);
            text-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .hero-subtitle {
            font-size: 1.1rem; /* Reduced font size */
            color: rgba(249, 250, 251, 0.9);
            margin-bottom: 1.8rem; /* Reduced margin */
            line-height: 1.6;
        }
        .hero-cta {
            display: flex;
            gap: 1rem; /* Reduced gap */
            margin-bottom: 2rem; /* Reduced margin */
        }
        .hero-stats {
            display: flex;
            gap: 2rem; /* Reduced gap */
            margin-top: 1.5rem; /* Reduced margin */
        }
        .stat-item {
            text-align: center;
        }
        .stat-number {
            font-size: 2.2rem; /* Reduced font size */
            font-weight: 800;
            color: var(--secondary);
            background: rgba(14, 165, 233, 0.1);
            padding: 0.6rem 1rem; /* Reduced padding */
            border-radius: var(--radius);
            min-width: 110px; /* Reduced width */
            transition: var(--transition);
        }
        .stat-number:hover {
            transform: translateY(-5px);
            background: rgba(14, 165, 233, 0.2);
        }
        .stat-label {
            font-size: 0.9rem; /* Reduced font size */
            margin-top: 0.6rem; /* Reduced margin */
            color: var(--text-light);
            font-weight: 600;
        }
        .hero-slideshow {
            position: relative;
            border-radius: var(--radius);
            overflow: hidden;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.2);
            border: 1px solid rgba(255, 255, 255, 0.2);
            height: 100%;
            width: 100%;
            margin-top: 0;
        }
        .hero-slideshow .swiper-slide img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
            filter: brightness(1.05) saturate(1.03);
        }
        /* Search Widget */
        .search-widget {
            background: var(--bg-light);
            border-radius: var(--radius);
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1);
            padding: 1.8rem; /* Reduced padding */
            margin-top: 1.5rem;
            position: relative;
            z-index: 20;
        }
        .widget-title {
            font-size: 1.4rem; /* Reduced font size */
            font-weight: 700;
            margin-bottom: 1.2rem; /* Reduced margin */
            text-align: center;
            color: var(--primary);
        }
        .widget-form {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem; /* Reduced gap */
        }
        .form-group {
            margin-bottom: 0.9rem; /* Reduced margin */
            position: relative;
            min-height: 85px; /* Ensure consistent height for error messages */
        }
        .form-group label {
            display: block;
            margin-bottom: 0.4rem; /* Reduced margin */
            font-weight: 500;
            color: var(--primary);
        }
        .form-control {
            width: 100%;
            padding: 0.75rem 1.1rem; /* Reduced padding */
            border: 1px solid var(--border);
            border-radius: var(--radius);
            font-family: inherit;
            font-size: 0.95rem; /* Reduced font size */
            transition: var(--transition);
            background-color: var(--light-bg);
        }
        .form-control:focus {
            outline: none;
            border-color: var(--secondary);
            box-shadow: 0 0 0 3px rgba(14, 165, 233, 0.3);
        }
        .error-message {
            display: none;
            color: #e53e3e;
            font-size: 0.8rem; /* Reduced font size */
            margin-top: 0.2rem; /* Reduced margin */
        }
        .error .form-control {
            border-color: #e53e3e;
        }
        .error .error-message {
            display: block;
        }
        /* Statistics Section */
        .stats-section {
            background-color: var(--bg-light);
            padding: 3rem 0; /* Reduced padding */
        }
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 1.5rem; /* Reduced gap */
        }
        .stat-card {
            background: var(--light-bg);
            border-radius: var(--radius);
            padding: 1.8rem; /* Reduced padding */
            text-align: center;
            box-shadow: var(--shadow);
            transition: var(--transition);
            border: 1px solid var(--border);
        }
        .stat-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
            border-color: var(--secondary);
        }
        .stat-icon {
            width: 60px; /* Reduced size */
            height: 60px; /* Reduced size */
            display: flex;
            align-items: center;
            justify-content: center;
            background: rgba(14, 165, 233, 0.1);
            border-radius: 50%;
            margin: 0 auto 1.2rem; /* Reduced margin */
            color: var(--secondary);
            font-size: 1.5rem; /* Reduced font size */
        }
        .stat-count {
            font-size: 2.2rem; /* Reduced font size */
            font-weight: 800;
            color: var(--secondary);
            margin-bottom: 0.4rem; /* Reduced margin */
        }
        .stat-description {
            color: var(--text-dark);
            font-weight: 600;
            font-size: 0.95rem; /* Reduced font size */
        }
        /* Features Section */
        .features {
            background-color: var(--light-bg);
        }
        .features-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 1.8rem; /* Reduced gap */
        }
        .feature-card {
            background: var(--light-bg);
            border-radius: var(--radius);
            padding: 2rem; /* Reduced padding */
            box-shadow: var(--shadow);
            transition: var(--transition);
            border: 1px solid var(--border);
        }
        .feature-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.15);
            border-color: var(--secondary);
        }
        .feature-icon {
            width: 60px; /* Reduced size */
            height: 60px; /* Reduced size */
            display: flex;
            align-items: center;
            justify-content: center;
            background: rgba(14, 165, 233, 0.1);
            border-radius: 50%;
            margin-bottom: 1.2rem; /* Reduced margin */
            color: var(--secondary);
            font-size: 1.5rem; /* Reduced font size */
        }
        .feature-title {
            font-size: 1.3rem; /* Reduced font size */
            font-weight: 700;
            margin-bottom: 0.6rem; /* Reduced margin */
            color: var(--primary);
        }
        .feature-desc {
            color: var(--text-dark);
            line-height: 1.6;
            font-size: 0.95rem; /* Reduced font size */
        }
        /* How It Works */
        .how-it-works {
            background-color: var(--bg-light);
        }
        .steps {
            display: flex;
            justify-content: center;
            gap: 2.5rem; /* Reduced gap */
            position: relative;
        }
        .steps::before {
            content: "";
            position: absolute;
            top: 40px;
            left: 10%;
            right: 10%;
            height: 3px;
            background: linear-gradient(to right, var(--primary), #93c5fd);
            z-index: 0;
        }
        .step {
            position: relative;
            padding: 2.2rem 1.8rem 1.8rem; /* Reduced padding */
            background: var(--light-bg);
            border-radius: var(--radius);
            box-shadow: var(--shadow);
            border: 1px solid var(--border);
            transition: var(--transition);
            z-index: 1;
            text-align: center;
            flex: 1;
            max-width: 280px; /* Reduced max-width */
        }
        .step:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.15);
        }
        .step-number {
            position: absolute;
            top: -18px;
            left: 50%;
            transform: translateX(-50%);
            width: 36px;
            height: 36px;
            background: var(--secondary);
            color: var(--text-light);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 1.1rem;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }
        .step-title {
            font-size: 1.2rem; /* Reduced font size */
            font-weight: 700;
            margin-bottom: 0.8rem; /* Reduced margin */
            color: var(--primary);
        }
        .step-desc {
            color: var(--text-dark);
            line-height: 1.6;
            font-size: 0.95rem; /* Reduced font size */
        }
        /* Gallery */
        .gallery {
            background-color: var(--light-bg);
        }
        .gallery-swiper {
            width: 100%;
            height: 320px; /* Slightly reduced height */
            margin-bottom: 1.8rem; /* Reduced margin */
        }
        .gallery-swiper .swiper-slide {
            text-align: center;
            font-size: 18px;
            background: #fff;
            display: flex;
            justify-content: center;
            align-items: center;
            border-radius: var(--radius);
            overflow: hidden;
            box-shadow: var(--shadow);
            position: relative;
        }
        .gallery-swiper .swiper-slide img, 
        .gallery-swiper .swiper-slide video {
            display: block;
            width: 100%;
            height: 100%;
            object-fit: cover;
            filter: brightness(1.05) saturate(1.03);
        }
        .video-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            background: rgba(0, 0, 0, 0.3);
            opacity: 0;
            transition: opacity 0.3s ease;
        }
        .video-play-icon {
            width: 60px;
            height: 60px;
            background: rgba(255, 255, 255, 0.7);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--primary);
            font-size: 24px;
        }
        .gallery-swiper .swiper-slide:hover .video-overlay {
            opacity: 1;
        }
        .gallery-button {
            display: flex;
            justify-content: center;
            margin-top: 1.8rem; /* Reduced margin */
        }
        .view-gallery-btn {
            background: linear-gradient(to right, var(--primary), var(--secondary));
            color: white;
            padding: 0.9rem 2.2rem; /* Reduced padding */
            border-radius: 50px;
            font-weight: 600;
            font-size: 1rem; /* Reduced font size */
            text-decoration: none;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
            transition: all 0.3s ease;
            border: none;
            cursor: pointer;
        }
        .view-gallery-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.3);
            background: linear-gradient(to right, var(--primary-dark), var(--secondary-dark));
        }
        /* Testimonials */
        .testimonials {
            background-color: var(--primary);
            color: var(--text-light);
        }
        .testimonials-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
            gap: 2.2rem; /* Reduced gap */
        }
        .testimonial-card {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(5px);
            border-radius: var(--radius);
            padding: 2.2rem; /* Reduced padding */
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            position: relative;
            transition: var(--transition);
        }
        .testimonial-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 15px rgba(0, 0, 0, 0.15);
            background: rgba(255, 255, 255, 0.15);
        }
        .testimonial-card::before {
            content: """;
            position: absolute;
            top: 1.5rem;
            left: 1.5rem;
            font-size: 4.5rem;
            color: rgba(14, 165, 233, 0.2);
            font-family: Georgia, serif;
            line-height: 1;
        }
        .testimonial-text {
            margin-bottom: 1.5rem; /* Reduced margin */
            position: relative;
            z-index: 1;
            line-height: 1.7;
            font-size: 1rem; /* Reduced font size */
        }
        .testimonial-author {
            display: flex;
            align-items: center;
            gap: 1rem; /* Reduced gap */
            position: relative;
            z-index: 1;
        }
        .author-avatar {
            width: 55px; /* Reduced size */
            height: 55px; /* Reduced size */
            border-radius: 50%;
            background: rgba(14, 165, 233, 0.2);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--secondary);
            font-weight: 700;
            font-size: 1.3rem; /* Reduced font size */
            flex-shrink: 0;
        }
        .author-info h4 {
            font-weight: 700;
            margin-bottom: 0.2rem; /* Reduced margin */
            color: var(--text-light);
            font-size: 1.1rem; /* Reduced font size */
        }
        .author-info p {
            color: var(--secondary);
            font-size: 0.9rem; /* Reduced font size */
            font-weight: 600;
        }
        /* Pricing */
        .pricing {
            background-color: var(--bg-light);
        }
        .pricing-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
            gap: 2.2rem; /* Reduced gap */
        }
        .pricing-card {
            background: var(--light-bg);
            border-radius: var(--radius);
            padding: 2.5rem 2rem; /* Reduced padding */
            box-shadow: var(--shadow);
            text-align: center;
            border: 1px solid var(--border);
            position: relative;
            transition: var(--transition);
        }
        .pricing-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
        }
        .pricing-card.popular {
            border: 2px solid var(--secondary);
            transform: scale(1.03);
            z-index: 1;
        }
        .popular-badge {
            position: absolute;
            top: 15px;
            right: -35px;
            background: var(--secondary);
            color: var(--text-light);
            padding: 6px 35px;
            font-size: 0.85rem;
            font-weight: 700;
            transform: rotate(45deg);
            z-index: 2;
            box-shadow: 0 3px 10px rgba(0,0,0,0.1);
        }
        .pricing-header {
            margin-bottom: 1.5rem; /* Reduced margin */
        }
        .pricing-title {
            font-size: 1.5rem; /* Reduced font size */
            font-weight: 700;
            margin-bottom: 0.4rem; /* Reduced margin */
            color: var(--primary);
        }
        .pricing-price {
            font-size: 2.5rem; /* Reduced font size */
            font-weight: 800;
            color: var(--secondary);
            margin-bottom: 0.4rem; /* Reduced margin */
        }
        .pricing-price span {
            font-size: 1.1rem; /* Reduced font size */
            color: #4B5563;
            font-weight: 400;
        }
        .pricing-features {
            list-style: none;
            margin: 1.8rem 0; /* Reduced margin */
            text-align: left;
        }
        .pricing-features li {
            padding: 0.7rem 0; /* Reduced padding */
            border-bottom: 1px solid var(--border);
            display: flex;
            align-items: center;
            gap: 0.6rem; /* Reduced gap */
            justify-content: flex-start;
            padding-left: 5px;
            font-size: 0.95rem; /* Reduced font size */
        }
        .pricing-features li:last-child {
            border-bottom: none;
        }
        .pricing-features li i {
            color: var(--secondary);
            font-size: 1rem; /* Reduced font size */
        }
        /* Free Trial Section */
        .free-trial {
            background: linear-gradient(to right, var(--primary), #1e40af, var(--secondary));
            padding: 3.5rem 0; /* Reduced padding */
            text-align: center;
            position: relative;
            overflow: hidden;
        }
        .trial-content {
            max-width: 700px;
            margin: 0 auto;
            position: relative;
            z-index: 2;
        }
        .trial-title {
            font-size: 2.2rem; /* Reduced font size */
            font-weight: 800;
            margin-bottom: 1.2rem; /* Reduced margin */
            color: var(--text-light);
            position: relative;
            z-index: 1;
        }
        .trial-subtitle {
            font-size: 1.2rem; /* Reduced font size */
            color: rgba(249, 250, 251, 0.9);
            margin-bottom: 2.2rem; /* Reduced margin */
            line-height: 1.6;
            position: relative;
            z-index: 1;
        }
        .trial-highlight {
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(5px);
            border-radius: var(--radius);
            padding: 1.3rem; /* Reduced padding */
            margin: 1.8rem auto; /* Reduced margin */
            max-width: 600px;
            border: 1px solid rgba(255, 255, 255, 0.2);
            position: relative;
            z-index: 1;
        }
        .trial-highlight-text {
            font-size: 1.3rem; /* Reduced font size */
            font-weight: 700;
            color: var(--text-light);
            font-family: 'Noto Sans Devanagari', sans-serif;
        }
        .trial-cta {
            display: flex;
            justify-content: center;
            gap: 1.2rem; /* Reduced gap */
            flex-wrap: wrap;
            position: relative;
            z-index: 1;
        }
        /* Footer - REDUCED HEIGHT */
        footer {
            background-color: var(--primary);
            color: var(--text-light);
            padding: 1rem 0 0.2rem; /* Further reduced vertical padding */
        }
        .footer-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.2rem; /* Reduced gap */
            margin-bottom: 1rem; /* Reduced margin */
        }
        .footer-col h3 {
            font-size: 1.3rem; /* Reduced font size */
            margin-bottom: 1rem; /* Reduced margin */
            position: relative;
            padding-bottom: 0.4rem; /* Reduced padding */
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
            margin-bottom: 0.6rem; /* Reduced margin */
        }
        .footer-links a {
            color: rgba(249, 250, 251, 0.8);
            text-decoration: none;
            transition: var(--transition);
            display: flex;
            align-items: center;
            font-size: 0.95rem; /* Reduced font size */
        }
        .footer-links a:hover {
            color: var(--secondary);
            transform: translateX(5px);
        }
        .footer-links i {
            margin-right: 10px; /* Reduced margin */
            width: 18px; /* Reduced width */
            color: var(--secondary);
        }
        .contact-info {
            list-style: none;
            margin: 0;
            padding: 0;
        }
        .contact-info li {
            margin-bottom: 0.8rem; /* Reduced margin */
            display: flex;
            align-items: flex-start;
        }
        .contact-info i {
            margin-right: 12px; /* Reduced margin */
            color: var(--secondary);
            font-size: 1.1rem; /* Reduced font size */
            margin-top: 4px; /* Reduced margin */
            flex-shrink: 0;
        }
        .footer-logo {
            display: flex;
            align-items: center;
            gap: 0.6rem; /* Reduced gap */
            font-size: 1.6rem; /* Reduced font size */
            font-weight: 700;
            margin-bottom: 1rem; /* Reduced margin */
            color: var(--text-light);
        }
        .footer-logo-icon {
            width: 35px; /* Reduced size */
            height: 35px; /* Reduced size */
            display: flex;
            align-items: center;
            justify-content: center;
            background: var(--secondary);
            border-radius: var(--radius);
            color: var(--text-light);
            font-weight: bold;
            font-size: 18px; /* Reduced font size */
        }
        .copyright {
            margin-top: 1.2rem; /* Reduced margin */
            padding-top: 0.8rem; /* Reduced padding */
            border-top: 1px solid rgba(249, 250, 251, 0.1);
            font-size: 1rem; /* Reduced font size */
            color: rgba(249, 250, 251, 0.6);
            text-align: center;
            grid-column: 1 / -1;
        }
        .social-links {
            display: flex;
            gap: 10px; /* Reduced gap */
            margin-top: 12px; /* Reduced margin */
            flex-wrap: wrap;
        }
        .social-links a {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 36px; /* Reduced size */
            height: 36px; /* Reduced size */
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            color: var(--text-light);
            font-size: 1rem; /* Reduced font size */
            transition: var(--transition);
        }
        .social-links a:hover {
            background: var(--secondary);
            color: var(--text-light);
            transform: translateY(-3px);
        }
        .newsletter-form {
            display: flex;
            gap: 0.4rem; /* Reduced gap */
            margin-top: 0.8rem; /* Reduced margin */
        }
        .newsletter-form input {
            flex: 1;
            padding: 0.7rem 0.9rem; /* Reduced padding */
            border: none;
            border-radius: var(--radius);
            font-family: inherit;
            font-size: 0.9rem; /* Reduced font size */
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
            padding: 0 0.9rem; /* Reduced padding */
            font-weight: 600;
            cursor: pointer;
            transition: var(--transition);
            font-size: 0.9rem; /* Reduced font size */
        }
        .newsletter-form button:hover {
            background: var(--secondary-dark);
            transform: translateY(-3px);
        }
        /* Animation */
        @keyframes float {
            0% { transform: translateY(0px) rotateY(-5deg); }
            50% { transform: translateY(-15px) rotateY(-5deg); }
            100% { transform: translateY(0px) rotateY(-5deg); }
        }
        .floating {
            animation: float 6s ease-in-out infinite;
        }
        @keyframes countUp {
            from { transform: translateY(20px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }
        .count-up {
            animation: countUp 1s ease-out forwards;
            opacity: 0;
        }
        /* Responsive Design */
        @media (max-width: 1024px) {
            .hero-title {
                font-size: 2.5rem; /* Adjusted for tablet */
            }
            .hero-content {
                grid-template-columns: 1fr;
                padding-top: 70px; /* Adjusted for tablet */
            }
            .hero-slideshow {
                max-width: 600px;
                margin: 2rem auto 0; /* Reduced margin */
                transform: none;
            }
            .steps::before {
                display: none;
            }
            .steps {
                flex-direction: column;
                align-items: center;
                gap: 1.8rem; /* Reduced gap */
            }
            .gallery-swiper {
                height: 280px; /* Adjusted height */
            }
        }
        @media (max-width: 768px) {
            .section {
                padding: 3rem 0; /* Reduced padding */
            }
            .hero {
                padding: 0;
            }
            .hero-title {
                font-size: 2.1rem; /* Adjusted for mobile */
            }
            .hero-content {
                padding-top: 60px; /* Adjusted for mobile */
            }
            .hero-cta {
                flex-direction: column;
            }
            .hero-stats {
                flex-direction: column;
                gap: 1rem; /* Reduced gap */
            }
            .search-widget {
                margin-top: 1.2rem; /* Reduced margin */
                padding: 1.3rem; /* Reduced padding */
            }
            .widget-form {
                grid-template-columns: 1fr;
            }
            .pricing-card.popular::before {
                right: -30px;
                padding: 5px 30px;
            }
            .trial-cta {
                flex-direction: column;
            }
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
                padding: 1.2rem; /* Reduced padding */
                box-shadow: 0 10px 15px rgba(0,0,0,0.1);
                border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            }
            .nav-links.show {
                display: flex;
            }
            .header-cta {
                display: none;
            }
            .gallery-swiper {
                height: 220px; /* Adjusted height */
            }
        }
        @media (max-width: 480px) {
            .container {
                padding: 0 1rem; /* Added container padding adjustment for mobile */
            }
            .hero-title {
                font-size: 1.8rem; /* Adjusted for small mobile */
            }
            .hero-content {
                padding-top: 50px; /* Adjusted for small mobile */
            }
            .section-title {
                font-size: 1.6rem; /* Adjusted for small mobile */
            }
            .section-subtitle {
                font-size: 1rem; /* Adjusted for small mobile */
            }
            .btn {
                padding: 0.7rem 1.3rem; /* Reduced padding */
                font-size: 0.9rem; /* Reduced font size */
            }
            .gallery-swiper {
                height: 180px; /* Adjusted height */
            }
            :root {
                --header-height: 60px; /* Further reduced header height for mobile */
            }
            header .logo img {
                width: 60px; /* Further reduced logo size for mobile */
                height: 60px; /* Further reduced logo size for mobile */
            }
        }
        @media (max-width: 360px) {
            .container {
                padding: 0 0.5rem; /* Further reduced padding for very small devices */
            }
            header .logo img {
                width: 50px; /* Further reduced logo size for very small devices */
                height: 50px; /* Further reduced logo size for very small devices */
            }
            .logo-text h1 {
                font-size: 1rem; /* Reduced font size for very small devices */
            }
            .logo-text span {
                font-size: 0.6rem; /* Reduced font size for very small devices */
            }
        }
        /* Accessibility Focus Styles */
        :focus {
            outline: 3px solid var(--secondary);
            outline-offset: 2px;
        }
        /* Skip to Content Link */
        .skip-link {
            position: absolute;
            top: -40px;
            left: 0;
            background: var(--primary);
            color: white;
            padding: 8px;
            z-index: 100;
            transition: top 0.3s;
        }
        .skip-link:focus {
            top: 0;
        }
    </style>
</head>
<body>
    <!-- Skip to content link for accessibility -->
    <a href="#main" class="skip-link nepali">सामग्रीमा जानुहोस्</a>
    <!-- Fixed Header -->
    <header id="site-header">
        <div class="header-inner">
            <div class="container">
                <div class="navbar">
                    <!-- Header Logo -->
                    <a href="/" class="logo">
                        <!-- Real Logo Image -->
                        <div class="logo-image">
                            <img src="{{ asset('storage/images/logo.png') }}" alt="HostelHub Logo" style="height: 50px; width: auto;">
                        </div>
                        <div class="logo-text">
                            <h1>HostelHub</h1>
                            <span class="nepali">होस्टल प्रबन्धन</span>
                        </div>
                    </a>
                    <div class="nav-links" id="main-nav">
                        <a href="{{ route('features') }}" class="nepali">सुविधाहरू</a>
                        <a href="{{ route('how-it-works') }}" class="nepali">कसरी काम गर्छ</a>
                        <a href="{{ route('gallery.public') }}" class="nepali">ग्यालरी</a>
                        <a href="{{ route('pricing') }}" class="nepali">मूल्य</a>
                        <a href="{{ route('reviews') }}" class="nepali">समीक्षाहरू</a>
                        <a href="/login" class="nepali">लगइन</a>
                    </div>
                    <div class="header-cta">
                        <a href="/register" class="btn btn-primary nepali">साइन अप</a>
                    </div>
                    <button class="mobile-menu-btn" aria-label="मेनु खोल्नुहोस्" aria-expanded="false" aria-controls="main-nav">
                        <i class="fas fa-bars"></i>
                    </button>
                </div>
            </div>
        </div>
    </header>
    <!-- Main Content -->
    <main id="main">
        <!-- Hero Section -->
        <section class="hero">
            <video autoplay muted loop playsinline preload="metadata" class="hero-video">
                <source src="https://assets.mixkit.co/videos/preview/mixkit-student-studying-in-a-dorm-room-44475-large.mp4" type="video/mp4">
                Your browser does not support the video tag.
            </video>
            <div class="container">
                <div class="hero-content">
                    <div class="hero-text">
                        <h1 class="hero-title nepali">HostelHub — तपाइँको होस्टल व्यवस्थापन अब सजिलो, द्रुत र भरपर्दो</h1>
                        <p class="hero-subtitle nepali">विद्यार्थी व्यवस्थापन, कोठा आवंटन, भुक्तानी र भोजन प्रणाली—एकै प्लेटफर्मबाट चलाउनुहोस्। ७ दिन निःशुल्क।</p>
                        <div class="hero-cta">
                            <a href="{{ route('demo') }}" class="btn btn-primary nepali">डेमो हेर्नुहोस्</a>
                            <a href="#gallery" class="btn btn-outline nepali">खोजी सुरु गर्नुहोस्</a>
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

                    <div class="hero-slideshow">
                        <div class="swiper hero-slider">
                            <div class="swiper-wrapper">
                                @foreach($heroSliderItems as $item)
                                <div class="swiper-slide">
                                    @if($item['media_type'] === 'image')
                                        <img src="{{ $item['thumbnail_url'] }}" alt="{{ $item['title'] }}" loading="lazy">
                                    @else
                                        <!-- For videos, use a fallback thumbnail if default doesn't load -->
                                        <img src="{{ $item['thumbnail_url'] }}" alt="{{ $item['title'] }}" loading="lazy" onerror="this.onerror=null;this.src='data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iODAwIiBoZWlnaHQ9IjQ1MCIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj48cmVjdCB3aWR0aD0iODAwIiBoZWlnaHQ9IjQ1MCIgZmlsbD0iIzFlM2E4YSI+PC9yZWN0Pjx0ZXh0IHg9IjQwMCIgeT0iMjI1IiBkb21pbmFudC1iYXNlbGluZT0ibWlkZGxlIiB0ZXh0LWFuY2hvcj0ibWlkZGxlIiBmb250LWZhbWlseT0iQXJpYWwsIHNhbnMtc2VyaWYiIGZvbnQtc2l6ZT0iMjQiIGZpbGw9IiNmZmYiPlZpZGVvIFRodW1ibmFpbDwvdGV4dD48L3N2Zz4=';">
                                    @endif
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- Search Widget -->
        <section class="container">
            <div class="search-widget">
                <h3 class="widget-title nepali">कोठा खोजी / रिजर्भ गर्नुहोस्</h3>
                <form class="widget-form" id="booking-form" action="{{ route('search') }}" method="GET">
                    @csrf
                    <div class="form-group">
                        <label class="nepali" for="city">स्थान / City</label>
                        <select class="form-control" name="city" id="city" required aria-required="true">
                            <option value="">स्थान चयन गर्नुहोस्</option>
                            @foreach($cities as $city)
                                <option value="{{ $city }}">{{ $city }}</option>
                            @endforeach
                        </select>
                        <div class="error-message nepali">स्थान चयन गर्नुहोस्</div>
                    </div>
                    <div class="form-group">
                        <label class="nepali" for="hostel_id">होस्टल / Hostel</label>
                        <select class="form-control" name="hostel_id" id="hostel_id">
                            <option value="">सबै होस्टल</option>
                            @foreach($hostels as $hostel)
                                <option value="{{ $hostel->id }}">{{ $hostel->name }}</option>
                            @endforeach
                        </select>
                        <div class="error-message nepali">होस्टल चयन गर्नुहोस्</div>
                    </div>
                    <div class="form-group">
                        <label class="nepali" for="check_in">चेक-इन मिति</label>
                        <input type="date" class="form-control" name="check_in" id="check_in" required aria-required="true" min="{{ date('Y-m-d') }}">
                        <div class="error-message nepali">चेक-इन मिति आवश्यक छ</div>
                    </div>
                    <div class="form-group">
                        <label class="nepali" for="check_out">चेक-आउट मिति</label>
                        <input type="date" class="form-control" name="check_out" id="check_out" required aria-required="true" min="{{ date('Y-m-d', strtotime('+1 day')) }}">
                        <div class="error-message nepali">चेक-आउट मिति आवश्यक छ</div>
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn btn-primary nepali" style="width: 100%; margin-top: 0.85rem;">खोज्नुहोस्</button>
                    </div>
                </form>
            </div>
        </section>
        <!-- Gallery Section -->
        <section class="section gallery" id="gallery">
            <div class="container">
                <h2 class="section-title nepali">हाम्रो ग्यालरी</h2>
                <p class="section-subtitle nepali">हाम्रा होस्टलहरूको फोटो र भिडियोहरू हेर्नुहोस्</p>
                <div class="gallery-swiper swiper">
                    <div class="swiper-wrapper">
                        @foreach($galleryItems as $item)
                        <div class="swiper-slide">
                            @if($item['media_type'] === 'image')
                                <img src="{{ $item['thumbnail_url'] }}" alt="{{ $item['title'] }}" loading="lazy" onerror="this.onerror=null;this.src='data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iODAwIiBoZWlnaHQ9IjQ1MCIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj48cmVjdCB3aWR0aD0iODAwIiBoZWlnaHQ9IjQ1MCIgZmlsbD0iI2YwZjlmZiI+PC9yZWN0Pjx0ZXh0IHg9IjQwMCIgeT0iMjI1IiBkb21pbmFudC1iYXNlbGluZT0ibWlkZGxlIiB0ZXh0LWFuY2hvcj0ibWlkZGxlIiBmb250LWZhbWlseT0iQXJpYWwsIHNhbnMtc2VyaWYiIGZvbnQtc2l6ZT0iMjQiIGZpbGw9IiMxZjI5MzciPkltYWdlIFRodW1ibmFpbDwvdGV4dD48L3N2Zz4=';">
                            @else
                                <!-- For videos, use a fallback thumbnail if default doesn't load -->
                                <img src="{{ $item['thumbnail_url'] }}" alt="{{ $item['title'] }}" loading="lazy" class="youtube-thumbnail" data-youtube-id="{{ $item['youtube_id'] ?? '' }}" onerror="this.onerror=null;this.src='data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iODAwIiBoZWlnaHQ9IjQ1MCIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj48cmVjdCB3aWR0aD0iODAwIiBoZWlnaHQ9IjQ1MCIgZmlsbD0iIzFlM2E4YSI+PC9yZWN0Pjx0ZXh0IHg9IjQwMCIgeT0iMjI1IiBkb21pbmFudC1iYXNlbGluZT0ibWlkZGxlIiB0ZXh0LWFuY2hvcj0ibWlkZGxlIiBmb250LWZhbWlseT0iQXJpYWwsIHNhbnMtc2VyaWYiIGZvbnQtc2l6ZT0iMjQiIGZpbGw9IiNmZmYiPlZpZGVvIFRodW1ibmFpbDwvdGV4dD48L3N2Zz4=';">
                                <div class="video-overlay">
                                    <div class="video-play-icon">
                                        <i class="fas fa-play"></i>
                                    </div>
                                </div>
                            @endif
                        </div>
                        @endforeach
                    </div>
                </div>
                <div class="gallery-button">
                    <a href="{{ route('gallery.public') }}" class="view-gallery-btn nepali">पूरै ग्यालरी हेर्नुहोस्</a>
                </div>
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
                        <p class="step-desc nepali">हाम्रा उन्नत सुविधाहरू प्रयोग गरेर आफ्नो होस्टल व्यवसायलाई बढाउनुहोस्</p>
                    </div>
                </div>
            </div>
        </section>
        <!-- Testimonials -->
        <section class="section testimonials" id="testimonials">
            <div class="container">
                <h2 class="section-title nepali" style="color: var(--text-light);">ग्राहकहरूको समीक्षा</h2>
                <p class="section-subtitle" style="color: rgba(249, 250, 251, 0.9);">HostelHub प्रयोग गर्ने हाम्रा ग्राहकहरूले के भन्छन्</p>
                <div class="testimonials-grid">
                    @foreach($reviews as $review)
                    <div class="testimonial-card">
                        <p class="testimonial-text nepali">{{ $review->content }}</p>
                        <div class="testimonial-author">
                            <div class="author-avatar">{{ substr($review->author, 0, 2) }}</div>
                            <div class="author-info">
                                <h4>{{ $review->author }}</h4>
                                <p>{{ $review->position }}</p>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </section>
        <!-- Pricing -->
        <section class="section pricing" id="pricing">
            <div class="container">
                <h2 class="section-title nepali">सस्तो मूल्य, ठूलो मूल्य</h2>
                <p class="section-subtitle nepali">तपाईंको आवश्यकताको लागि उपयुक्त योजना चयन गर्नुहोस्</p>
                <div class="pricing-grid">
                    <div class="pricing-card">
                        <div class="pricing-header">
                            <h3 class="pricing-title nepali">सुरुवाती</h3>
                            <div class="pricing-price">रु. २,९९९<span class="nepali">/महिना</span></div>
                        </div>
                        <ul class="pricing-features">
                            <li><i class="fas fa-check"></i> <span class="nepali">५० विद्यार्थी सम्म</span></li>
                            <li><i class="fas fa-check"></i> <span class="nepali">मूल विद्यार्थी व्यवस्थापन</span></li>
                            <li><i class="fas fa-check"></i> <span class="nepali">कोठा आवंटन</span></li>
                            <li><i class="fas fa-check"></i> <span class="nepali">भुक्तानी ट्र्याकिंग</span></li>
                            <li><i class="fas fa-times"></i> <span class="nepali">भोजन व्यवस्थापन</span></li>
                            <li><i class="fas fa-times"></i> <span class="nepali">मोबाइल एप्प</span></li>
                        </ul>
                        <a href="/register" class="btn btn-outline nepali">सुरु गर्नुहोस्</a>
                    </div>
                    <div class="pricing-card popular">
                        <div class="popular-badge nepali">लोकप्रिय</div>
                        <div class="pricing-header">
                            <h3 class="pricing-title nepali">प्रो</h3>
                            <div class="pricing-price">रु. ४,९९९<span class="nepali">/महिना</span></div>
                        </div>
                        <ul class="pricing-features">
                            <li><i class="fas fa-check"></i> <span class="nepali">२०० विद्यार्थी सम्म</span></li>
                            <li><i class="fas fa-check"></i> <span class="nepali">पूर्ण विद्यार्थी व्यवस्थापन</span></li>
                            <li><i class="fas fa-check"></i> <span class="nepali">अग्रिम कोठा बुकिंग</span></li>
                            <li><i class="fas fa-check"></i> <span class="nepali">भुक्तानी ट्र्याकिंग</span></li>
                            <li><i class="fas fa-check"></i> <span class="nepali">भोजन व्यवस्थापन</span></li>
                            <li><i class="fas fa-check"></i> <span class="nepali">मोबाइल एप्प</span></li>
                        </ul>
                        <a href="/register" class="btn btn-primary nepali">लोकप्रिय चयन</a>
                    </div>
                    <div class="pricing-card">
                        <div class="pricing-header">
                            <h3 class="pricing-title nepali">एन्टरप्राइज</h3>
                            <div class="pricing-price">रु. ८,९९९<span class="nepali">/महिना</span></div>
                        </div>
                        <ul class="pricing-features">
                            <li><i class="fas fa-check"></i> <span class="nepali">असीमित विद्यार्थी </span></li>
                            <li><i class="fas fa-check"></i> <span class="nepali">पूर्ण विद्यार्थी व्यवस्थापन</span></li>
                            <li><i class="fas fa-check"></i> <span class="nepali">बहु-होस्टल व्यवस्थापन</span></li>
                            <li><i class="fas fa-check"></i> <span class="nepali">कस्टम भुक्तानी प्रणाली</span></li>
                            <li><i class="fas fa-check"></i> <span class="nepali">विस्तृत विवरण र विश्लेषण</span></li>
                            <li><i class="fas fa-check"></i> <span class="nepali">२४/७ समर्थन</span></li>
                        </ul>
                        <a href="/contact" class="btn btn-outline nepali">सम्पर्क गर्नुहोस्</a>
                    </div>
                </div>
            </div>
        </section>
        <!-- Free Trial Section -->
        <section class="free-trial">
            <div class="container">
                <div class="trial-content">
                    <h2 class="trial-title nepali">७ दिनको निःशुल्क परीक्षण</h2>
                    <p class="trial-subtitle nepali">हाम्रो प्रणालीको सबै सुविधाहरू निःशुल्क परीक्षण गर्नुहोस्, कुनै पनि बाध्यता बिना</p>
                    <div class="trial-highlight">
                        <p class="trial-highlight-text nepali">७ दिन निःशुल्क • कुनै क्रेडिट कार्ड आवश्यक छैन • कुनै पनि प्रतिबद्धता छैन</p>
                    </div>
                    <div class="trial-cta">
                        <a href="/register" class="btn btn-primary nepali">निःशुल्क साइन अप गर्नुहोस्</a>
                        <a href="{{ route('demo') }}" class="btn btn-outline nepali" style="background: white; color: var(--primary);">डेमो हेर्नुहोस्</a>
                    </div>
                </div>
            </div>
        </section>
    </main>
    <!-- Footer -->
    <footer>
        <div class="container">
            <div class="footer-grid">
                <div class="footer-col">
                    <!-- Footer Logo -->
                    <a href="/" class="footer-logo">
                        <img src="{{ asset('storage/images/logo.png') }}" alt="HostelHub Logo" style="height: 146px; width: auto;">
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
                        <li><a href="#"><i class="fas fa-chevron-right"></i> <span class="nepali">होम</span></a></li>
                        <li><a href="{{ route('features') }}"><i class="fas fa-chevron-right"></i> <span class="nepali">सुविधाहरू</span></a></li>
                        <li><a href="{{ route('how-it-works') }}"><i class="fas fa-chevron-right"></i> <span class="nepali">कसरी काम गर्छ</span></a></li>
                        <li><a href="{{ route('gallery.public') }}"><i class="fas fa-chevron-right"></i> <span class="nepali">ग्यालरी</span></a></li>
                        <li><a href="{{ route('pricing') }}"><i class="fas fa-chevron-right"></i> <span class="nepali">मूल्य</span></a></li>
                        <li><a href="{{ route('reviews') }}"><i class="fas fa-chevron-right"></i> <span class="nepali">समीक्षाहरू</span></a></li>
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
                            <div>+९७७ ९८०१२३४५६७</div>
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
                    <form class="newsletter-form" action="/subscribe" method="POST">
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
    <script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>
    <script>
        // Initialize Swiper sliders
        document.addEventListener('DOMContentLoaded', function() {
            // Hero Slideshow
            const heroSwiper = new Swiper('.hero-slider', {
                loop: true,
                autoplay: {
                    delay: 3000,
                    disableOnInteraction: false,
                },
                effect: 'fade',
                fadeEffect: {
                    crossFade: true
                },
                speed: 1000
            });
            
            // Initialize gallery slider after all resources are loaded
            window.addEventListener('load', function() {
                // Initialize gallery Swiper
                const gallerySwiper = new Swiper('.gallery-swiper', {
                    slidesPerView: 1,
                    spaceBetween: 20,
                    loop: true,
                    autoplay: {
                        delay: 5000, // 5 seconds
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
                    observer: true,  // Watch for DOM changes
                    observeParents: true  // Watch parents for DOM changes
                });
            });
            
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
        });
    </script>
</body>
</html>