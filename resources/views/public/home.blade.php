<!DOCTYPE html>
<html lang="ne" dir="ltr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="HostelHub — होस्टल व्यवस्थापन सजिलो बनाउने SaaS: कोठा, विद्यार्थी, भुक्तानी र भोजन व्यवस्थापन। ७ दिन निःशुल्क ट्रायल।">
    <title>HostelHub — होस्टल प्रबन्धन प्रणाली | Nepal</title>
    <meta property="og:title" content="HostelHub — होस्टल प्रबन्धन प्रणाली">
    <meta property="og:description" content="HostelHub — होस्टल व्यवस्थापन सजिलो बनाउने SaaS">
    <meta property="og:image" content="https://images.unsplash.com/photo-1560448204-e02f11c3d0e2?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80">
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Devanagari:wght@400;500;600;700&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
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
            overflow-x: hidden;
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
            background-color: transparent;
            color: var(--primary);
            border: 2px solid var(--primary);
        }
        .btn-outline:hover {
            background-color: var(--primary);
            color: var(--text-light);
            transform: translateY(-3px);
            border-color: var(--primary-dark);
        }
        .section {
            padding: 5rem 0;
        }
        .section-title {
            font-size: 2.25rem;
            font-weight: 800;
            margin-bottom: 1.5rem;
            color: var(--primary);
            text-align: center;
            position: relative;
            display: inline-block;
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
        /* Header Styles */
        #site-header {
            position: fixed;
            top: 0;
            width: 100%;
            z-index: 1000;
            background: var(--primary);
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
        }
        .header-scrolled {
            padding: 0.75rem 0;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
        }
        .header-inner {
            padding: 1.25rem 0;
            transition: all 0.3s ease;
        }
        .navbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .logo {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            text-decoration: none;
            font-weight: 700;
            font-size: 1.5rem;
            color: var(--text-light);
        }
        .logo-icon {
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: var(--secondary);
            border-radius: var(--radius);
            color: var(--text-light);
        }
        .nav-links {
            display: flex;
            align-items: center;
            gap: 2.25rem;
        }
        .nav-links a {
            text-decoration: none;
            color: rgba(255, 255, 255, 0.85);
            font-weight: 500;
            transition: var(--transition);
            position: relative;
            padding: 0.5rem 0;
            font-size: 1.05rem;
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
            gap: 1rem;
            align-items: center;
        }
        .mobile-menu-btn {
            display: none;
            background: none;
            border: none;
            font-size: 1.5rem;
            cursor: pointer;
            color: var(--text-light);
        }
        /* Hero Section */
        .hero {
            padding: 12rem 0 6rem;
            background: linear-gradient(135deg, #1e3a8a, #0ea5e9);
            position: relative;
            overflow: hidden;
            z-index: 1;
        }
        .hero::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: url('https://images.unsplash.com/photo-1560448204-e02f11c3d0e2?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80') no-repeat center center;
            background-size: cover;
            z-index: -1;
            opacity: 0.35;
        }
        .hero-content {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 3rem;
            align-items: center;
        }
        .hero-text {
            max-width: 600px;
            color: var(--text-light);
            z-index: 15;
            position: relative;
        }
        .hero-title {
            font-size: 3.5rem;
            font-weight: 800;
            line-height: 1.2;
            margin-bottom: 1.5rem;
            color: var(--text-light);
            text-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .hero-subtitle {
            font-size: 1.25rem;
            color: rgba(249, 250, 251, 0.9);
            margin-bottom: 2rem;
            line-height: 1.7;
        }
        .hero-cta {
            display: flex;
            gap: 1.25rem;
            margin-bottom: 2.5rem;
        }
        .hero-stats {
            display: flex;
            gap: 2.5rem;
            margin-top: 2rem;
        }
        .stat-item {
            text-align: center;
        }
        .stat-number {
            font-size: 2.75rem;
            font-weight: 800;
            color: var(--secondary);
            background: rgba(14, 165, 233, 0.1);
            padding: 0.75rem 1.25rem;
            border-radius: var(--radius);
            min-width: 140px;
            transition: var(--transition);
        }
        .stat-number:hover {
            transform: translateY(-5px);
            background: rgba(14, 165, 233, 0.2);
        }
        .stat-label {
            font-size: 1rem;
            margin-top: 0.75rem;
            color: var(--text-light);
            font-weight: 600;
        }
        .hero-image {
            position: relative;
            border-radius: var(--radius);
            overflow: hidden;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.2);
            transform: perspective(1000px) rotateY(-5deg);
            border: 1px solid rgba(255, 255, 255, 0.2);
            animation: float 6s ease-in-out infinite;
            z-index: 15;
            margin-top: -100px;
        }
        .hero-image img {
            width: 100%;
            height: auto;
            display: block;
            transition: transform 0.5s ease;
        }
        .hero-image:hover img {
            transform: scale(1.03);
        }
        /* Search Widget */
        .search-widget {
            background: var(--bg-light);
            border-radius: var(--radius);
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1);
            padding: 2rem;
            margin-top: -5rem;
            position: relative;
            z-index: 20;
        }
        .widget-title {
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 1.5rem;
            text-align: center;
            color: var(--primary);
        }
        .widget-form {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1.25rem;
        }
        .form-group {
            margin-bottom: 1rem;
            position: relative;
        }
        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 500;
            color: var(--primary);
        }
        .form-control {
            width: 100%;
            padding: 0.85rem 1.25rem;
            border: 1px solid var(--border);
            border-radius: var(--radius);
            font-family: inherit;
            font-size: 1rem;
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
            font-size: 0.85rem;
            margin-top: 0.25rem;
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
            padding: 4rem 0;
        }
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 2rem;
        }
        .stat-card {
            background: var(--light-bg);
            border-radius: var(--radius);
            padding: 2rem;
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
            width: 70px;
            height: 70px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: rgba(14, 165, 233, 0.1);
            border-radius: 50%;
            margin: 0 auto 1.5rem;
            color: var(--secondary);
            font-size: 1.75rem;
        }
        .stat-count {
            font-size: 2.5rem;
            font-weight: 800;
            color: var(--secondary);
            margin-bottom: 0.5rem;
        }
        .stat-description {
            color: var(--text-dark);
            font-weight: 600;
        }
        /* Features Section */
        .features {
            background-color: var(--light-bg);
        }
        .features-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 2rem;
        }
        .feature-card {
            background: var(--light-bg);
            border-radius: var(--radius);
            padding: 2.25rem;
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
            width: 70px;
            height: 70px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: rgba(14, 165, 233, 0.1);
            border-radius: 50%;
            margin-bottom: 1.5rem;
            color: var(--secondary);
            font-size: 1.75rem;
        }
        .feature-title {
            font-size: 1.4rem;
            font-weight: 700;
            margin-bottom: 0.75rem;
            color: var(--primary);
        }
        .feature-desc {
            color: var(--text-dark);
            line-height: 1.7;
        }
        /* How It Works */
        .how-it-works {
            background-color: var(--bg-light);
        }
        .steps {
            display: flex;
            justify-content: center;
            gap: 3rem;
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
            padding: 2.5rem 2rem 2rem;
            background: var(--light-bg);
            border-radius: var(--radius);
            box-shadow: var(--shadow);
            border: 1px solid var(--border);
            transition: var(--transition);
            z-index: 1;
            text-align: center;
            flex: 1;
            max-width: 300px;
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
            font-size: 1.3rem;
            font-weight: 700;
            margin-bottom: 1rem;
            color: var(--primary);
        }
        .step-desc {
            color: var(--text-dark);
            line-height: 1.7;
        }
        /* Gallery */
        .gallery {
            background-color: var(--light-bg);
        }
        .gallery-filters {
            display: flex;
            justify-content: center;
            gap: 1.25rem;
            margin-bottom: 2.5rem;
            flex-wrap: wrap;
        }
        .filter-btn {
            padding: 0.65rem 1.75rem;
            border-radius: 2rem;
            background: var(--light-bg);
            border: 1px solid var(--border);
            color: var(--text-dark);
            cursor: pointer;
            transition: var(--transition);
            font-weight: 500;
        }
        .filter-btn.active, .filter-btn:hover {
            background: var(--secondary);
            color: var(--text-light);
            border-color: var(--secondary);
        }
        .gallery-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 1.75rem;
        }
        .gallery-item {
            border-radius: var(--radius);
            overflow: hidden;
            position: relative;
            height: 220px;
            cursor: pointer;
            box-shadow: var(--shadow);
            transition: var(--transition);
            border: 1px solid var(--border);
        }
        .gallery-item:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.15);
            border-color: var(--secondary);
        }
        .gallery-item img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: var(--transition);
            filter: brightness(1);
        }
        .gallery-item:hover img {
            transform: scale(1.05);
            filter: brightness(1.1);
        }
        .gallery-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(14, 165, 233, 0.15);
            opacity: 0;
            transition: var(--transition);
        }
        .gallery-item:hover .gallery-overlay {
            opacity: 1;
        }
        .gallery-caption {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            background: rgba(255, 255, 255, 0.95);
            color: var(--text-dark);
            padding: 1rem;
            font-weight: 500;
            z-index: 2;
            transform: translateY(5px);
            transition: var(--transition);
            border-top: 1px solid var(--border);
        }
        .gallery-item:hover .gallery-caption {
            transform: translateY(0);
        }
        /* Lightbox */
        .lightbox {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.9);
            z-index: 1000;
            justify-content: center;
            align-items: center;
            opacity: 0;
            transition: opacity 0.3s ease-in-out;
        }
        .lightbox.active {
            display: flex;
            opacity: 1;
        }
        .lightbox-content {
            max-width: 90%;
            max-height: 90%;
            border-radius: 8px;
            box-shadow: 0 0 25px rgba(14, 165, 233, 0.3);
        }
        .lightbox-caption {
            position: absolute;
            bottom: 20px;
            left: 50%;
            transform: translateX(-50%);
            color: white;
            background: rgba(0, 0, 0, 0.6);
            padding: 8px 16px;
            border-radius: 4px;
            font-size: 1.1rem;
            max-width: 80%;
            text-align: center;
        }
        .close-btn {
            position: absolute;
            top: 20px;
            right: 30px;
            color: white;
            font-size: 40px;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        .close-btn:hover {
            color: var(--secondary);
            transform: scale(1.1);
        }
        /* Testimonials */
        .testimonials {
            background-color: var(--primary);
            color: var(--text-light);
        }
        .testimonials-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
            gap: 2.5rem;
        }
        .testimonial-card {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(5px);
            border-radius: var(--radius);
            padding: 2.5rem;
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
            margin-bottom: 1.75rem;
            position: relative;
            z-index: 1;
            line-height: 1.8;
            font-size: 1.05rem;
        }
        .testimonial-author {
            display: flex;
            align-items: center;
            gap: 1.25rem;
        }
        .author-avatar {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            background: rgba(14, 165, 233, 0.2);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--secondary);
            font-weight: 700;
            font-size: 1.4rem;
            flex-shrink: 0;
        }
        .author-info h4 {
            font-weight: 700;
            margin-bottom: 0.25rem;
            color: var(--text-light);
        }
        .author-info p {
            color: var(--secondary);
            font-size: 0.95rem;
            font-weight: 600;
        }
        /* Pricing */
        .pricing {
            background-color: var(--bg-light);
        }
        .pricing-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
            gap: 2.5rem;
        }
        .pricing-card {
            background: var(--light-bg);
            border-radius: var(--radius);
            padding: 2.75rem 2.25rem;
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
            margin-bottom: 1.75rem;
        }
        .pricing-title {
            font-size: 1.6rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
            color: var(--primary);
        }
        .pricing-price {
            font-size: 2.75rem;
            font-weight: 800;
            color: var(--secondary);
            margin-bottom: 0.5rem;
        }
        .pricing-price span {
            font-size: 1.25rem;
            color: #4B5563;
            font-weight: 400;
        }
        .pricing-features {
            list-style: none;
            margin: 2rem 0;
            text-align: left;
        }
        .pricing-features li {
            padding: 0.85rem 0;
            border-bottom: 1px solid var(--border);
            display: flex;
            align-items: center;
            gap: 0.75rem;
            justify-content: flex-start;
            padding-left: 5px;
        }
        .pricing-features li:last-child {
            border-bottom: none;
        }
        .pricing-features li i {
            color: var(--secondary);
            font-size: 1.1rem;
        }
        /* Free Trial Section */
        .free-trial {
            background: linear-gradient(to right, var(--primary), #1e40af, var(--secondary));
            padding: 4rem 0;
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
            font-size: 2.5rem;
            font-weight: 800;
            margin-bottom: 1.5rem;
            color: var(--text-light);
        }
        .trial-subtitle {
            font-size: 1.3rem;
            color: rgba(249, 250, 251, 0.9);
            margin-bottom: 2.5rem;
            line-height: 1.7;
        }
        .trial-highlight {
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(5px);
            border-radius: var(--radius);
            padding: 1.5rem;
            margin: 2rem auto;
            max-width: 600px;
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        .trial-highlight-text {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--text-light);
        }
        .trial-cta {
            display: flex;
            justify-content: center;
            gap: 1.5rem;
            flex-wrap: wrap;
        }
        /* Footer */
        footer {
            background-color: var(--primary);
            color: var(--text-light);
            padding: 1.5rem 0 0.5rem;
        }
        .footer-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
            margin-bottom: 1.5rem;
        }
        .footer-col h3 {
            font-size: 1.4rem;
            margin-bottom: 1.25rem;
            position: relative;
            padding-bottom: 0.5rem;
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
        }
        .footer-links li {
            margin-bottom: 0.75rem;
        }
        .footer-links a {
            color: rgba(249, 250, 251, 0.8);
            text-decoration: none;
            transition: var(--transition);
            display: flex;
            align-items: center;
            font-size: 1.05rem;
        }
        .footer-links a:hover {
            color: var(--secondary);
            transform: translateX(5px);
        }
        .footer-links i {
            margin-right: 12px;
            width: 20px;
            color: var(--secondary);
        }
        .contact-info {
            list-style: none;
        }
        .contact-info li {
            margin-bottom: 1rem;
            display: flex;
            align-items: flex-start;
        }
        .contact-info i {
            margin-right: 15px;
            color: var(--secondary);
            font-size: 1.2rem;
            margin-top: 5px;
            flex-shrink: 0;
        }
        .footer-logo {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            font-size: 1.8rem;
            font-weight: 700;
            margin-bottom: 1.25rem;
            color: var(--text-light);
        }
        .footer-logo-icon {
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: var(--secondary);
            border-radius: var(--radius);
            color: var(--text-light);
        }
        .copyright {
            margin-top: 1.5rem;
            padding-top: 1rem;
            border-top: 1px solid rgba(249, 250, 251, 0.1);
            font-size: 1.1rem;
            color: rgba(249, 250, 251, 0.6);
            text-align: center;
            grid-column: 1 / -1;
        }
        .social-links {
            display: flex;
            gap: 12px;
            margin-top: 15px;
        }
        .social-links a {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            color: var(--text-light);
            font-size: 1.1rem;
            transition: var(--transition);
        }
        .social-links a:hover {
            background: var(--secondary);
            color: var(--text-light);
            transform: translateY(-3px);
        }
        .newsletter-form {
            display: flex;
            gap: 0.5rem;
            margin-top: 1rem;
        }
        .newsletter-form input {
            flex: 1;
            padding: 0.75rem 1rem;
            border: none;
            border-radius: var(--radius);
            font-family: inherit;
            font-size: 0.95rem;
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
            padding: 0 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: var(--transition);
            font-size: 0.95rem;
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
                font-size: 2.8rem;
            }
            .hero-content {
                grid-template-columns: 1fr;
            }
            .hero-image {
                max-width: 600px;
                margin: 2.5rem auto 0;
                transform: none;
            }
            .steps::before {
                display: none;
            }
            .steps {
                flex-direction: column;
                align-items: center;
                gap: 2rem;
            }
        }
        @media (max-width: 768px) {
            .section {
                padding: 3.5rem 0;
            }
            .hero {
                padding: 8rem 0 2rem;
            }
            .hero-title {
                font-size: 2.3rem;
            }
            .hero-cta {
                flex-direction: column;
            }
            .hero-stats {
                flex-direction: column;
                gap: 1.25rem;
            }
            .search-widget {
                margin-top: 1.5rem;
                padding: 1.5rem;
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
                padding: 1.5rem;
                box-shadow: 0 10px 15px rgba(0,0,0,0.1);
                border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            }
            .nav-links.show {
                display: flex;
            }
            .header-cta {
                display: none;
            }
        }
        @media (max-width: 480px) {
            .hero-title {
                font-size: 2rem;
            }
            .section-title {
                font-size: 1.8rem;
            }
            .section-subtitle {
                font-size: 1.1rem;
            }
            .btn {
                padding: 0.75rem 1.5rem;
                font-size: 0.95rem;
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
                    <a href="/" class="logo">
                        <div class="logo-icon">
                            <i class="fas fa-home"></i>
                        </div>
                        <div class="logo-text">
                            <h1>HostelHub</h1>
                            <span class="nepali">होस्टल प्रबन्धन प्रणाली</span>
                        </div>
                    </a>
                    <div class="nav-links" id="main-nav">
                        <a href="#features" class="nepali">सुविधाहरू</a>
                        <a href="#how-it-works" class="nepali">कसरी काम गर्छ</a>
                        <a href="#gallery" class="nepali">ग्यालरी</a>
                        <a href="#pricing" class="nepali">मूल्य</a>
                        <a href="#testimonials" class="nepali">समीक्षाहरू</a>
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

    <!-- Lightbox -->
    <div id="lightbox" class="lightbox" role="dialog" aria-modal="true" aria-hidden="true">
        <span class="close-btn" aria-label="बन्द गर्नुहोस्">&times;</span>
        <img class="lightbox-content" id="lightbox-img" alt="Enlarged hostel image">
        <div id="caption" class="lightbox-caption nepali"></div>
    </div>

    <!-- Main Content -->
    <main id="main">
        <!-- Hero Section -->
        <section class="hero">
            <div class="container">
                <div class="hero-content">
                    <div class="hero-text">
                        <h1 class="hero-title nepali">HostelHub — तपाइँको होस्टल व्यवस्थापन अब सजिलो, द्रुत र भरपर्दो</h1>
                        <p class="hero-subtitle nepali">विद्यार्थी व्यवस्थापन, कोठा आवंटन, भुक्तानी र भोजन प्रणाली—एकै प्लेटफर्मबाट चलाउनुहोस्। ७ दिन निःशुल्क।</p>
                        <div class="hero-cta">
                            <a href="/register" class="btn btn-primary nepali">डेमो माग्नुहोस्</a>
                            <a href="#gallery" class="btn btn-outline nepali">खोजी सुरु गर्नुहोस्</a>
                        </div>
                        <div class="hero-stats">
                            <div class="stat-item">
                                <div class="stat-number count-up" id="students-counter" aria-live="polite">{{ $totalStudents ?? 125 }}</div>
                                <div class="stat-label nepali">कुल विद्यार्थीहरू</div>
                            </div>
                            <div class="stat-item">
                                <div class="stat-number count-up" id="hostels-counter" aria-live="polite">{{ $totalHostels ?? 24 }}</div>
                                <div class="stat-label nepali">सहयोगी होस्टल</div>
                            </div>
                            <div class="stat-item">
                                <div class="stat-number count-up" id="cities-counter" aria-live="polite">{{ $totalCities ?? 5 }}</div>
                                <div class="stat-label nepali">शहरहरू</div>
                            </div>
                        </div>
                    </div>
                    <div class="hero-image floating">
                        <img src="https://images.unsplash.com/photo-1560448204-e02f11c3d0e2?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80" alt="HostelHub ड्यासबोर्डको पूर्वावलोकन" loading="lazy">
                        <div class="gallery-overlay"></div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Search Widget -->
        <section class="container">
            <div class="search-widget">
                <h3 class="widget-title nepali">कोठा खोजी / रिजर्भ गर्नुहोस्</h3>
                <form class="widget-form" id="booking-form" action="/search" method="GET">
                    <div class="form-group">
                        <label class="nepali" for="location">स्थान / City</label>
                        <select class="form-control" name="location" id="location" required aria-required="true">
                            <option value="">{{ $locationPlaceholder ?? 'काठमाडौं' }}</option>
                            <option value="pokhara">{{ $pokharaPlaceholder ?? 'पोखरा' }}</option>
                            <option value="chitwan">{{ $chitwanPlaceholder ?? 'चितवन' }}</option>
                            <option value="biratnagar">{{ $biratnagarPlaceholder ?? 'बिराटनगर' }}</option>
                        </select>
                        <div class="error-message nepali">स्थान चयन गर्नुहोस्</div>
                    </div>
                    <div class="form-group">
                        <label class="nepali" for="hostel">होस्टल / Hostel</label>
                        <select class="form-control" name="hostel" id="hostel" required aria-required="true">
                            <option value="">{{ $allHostelsPlaceholder ?? 'सबै होस्टल' }}</option>
                            <option value="college">{{ $collegeHostelPlaceholder ?? 'कलेज होस्टल' }}</option>
                            <option value="girls">{{ $girlsHostelPlaceholder ?? 'बालिका होस्टल' }}</option>
                            <option value="community">{{ $communityHostelPlaceholder ?? 'सामुदायिक होस्टल' }}</option>
                        </select>
                        <div class="error-message nepali">होस्टल चयन गर्नुहोस्</div>
                    </div>
                    <div class="form-group">
                        <label class="nepali" for="checkin-date">चेक-इन मिति</label>
                        <input type="date" class="form-control" name="checkin" id="checkin-date" required aria-required="true">
                        <div class="error-message nepali">चेक-इन मिति आवश्यक छ</div>
                    </div>
                    <div class="form-group">
                        <label class="nepali" for="checkout-date">चेक-आउट मिति</label>
                        <input type="date" class="form-control" name="checkout" id="checkout-date" required aria-required="true">
                        <div class="error-message nepali">चेक-आउट मिति आवश्यक छ</div>
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn btn-primary nepali" style="width: 100%; margin-top: 0.85rem;">खोज्नुहोस्</button>
                    </div>
                </form>
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
                        <div class="stat-count count-up" id="students-counter-stat" aria-live="polite">{{ $totalStudents ?? 125 }}</div>
                        <div class="stat-description nepali">खुसी विद्यार्थीहरू</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon">
                            <i class="fas fa-building" aria-hidden="true"></i>
                        </div>
                        <div class="stat-count count-up" id="hostels-counter-stat" aria-live="polite">{{ $totalHostels ?? 24 }}</div>
                        <div class="stat-description nepali">सहयोगी होस्टल</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon">
                            <i class="fas fa-map-marker-alt" aria-hidden="true"></i>
                        </div>
                        <div class="stat-count count-up" id="cities-counter-stat" aria-live="polite">{{ $totalCities ?? 5 }}</div>
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
                        <p class="feature-desc nepali">मेनु योजना बनाउनुहोस्, भोजन आदेश ट्र्याक गर्नुहोस्, र खानेकुराको इन्भेन्टरी प्रबन्धन गर्नुहोस्</p>
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

        <!-- Gallery -->
        <section class="section gallery" id="gallery">
            <div class="container">
                <h2 class="section-title nepali">हाम्रा होस्टलहरूको ग्यालरी</h2>
                <p class="section-subtitle nepali">हाम्रा विभिन्न होस्टलहरूको कोठा र सुविधाहरूको झलक</p>

                <!-- Gallery Filter Buttons with updated room types -->
                <div class="flex gap-3 mb-5 justify-center flex-wrap">
                    @foreach(['all' => 'सबै', '1-seater' => '१ सिटर', '2-seater' => '२ सिटर', '3-seater' => '३ सिटर', '4-seater' => '४ सिटर'] as $key => $label)
                        <button class="filter-btn {{ ($activeFilter ?? 'all') === $key ? 'active' : '' }} nepali" data-filter="{{ $key }}">
                            {{ $label }}
                        </button>
                    @endforeach
                </div>

                <!-- Gallery Grid with fallback images -->
                <div class="gallery-grid">
                    @if(isset($galleryImages) && count($galleryImages) > 0)
                        @foreach($galleryImages as $image)
                            <div class="gallery-item" data-type="{{ $image->type ?? 'all' }}">
                                <img src="{{ asset('storage/' . ($image->path ?? 'default.jpg')) }}"
                                     alt="{{ $image->title ?? 'होस्टल कोठा' }}"
                                     class="w-full h-full object-cover lazy"
                                     loading="lazy">
                                <div class="gallery-overlay"></div>
                                <div class="gallery-caption nepali">{{ $image->title ?? 'होस्टल कोठा' }}</div>
                            </div>
                        @endforeach
                    @else
                        <!-- Fallback hardcoded images -->
                        <div class="gallery-item" data-type="1-seater">
                            <img src="https://images.unsplash.com/photo-1554995207-c18c203602cb?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80"
                                 alt="१ सिटर कोठा"
                                 class="w-full h-full object-cover lazy"
                                 loading="lazy">
                            <div class="gallery-overlay"></div>
                            <div class="gallery-caption nepali">१ सिटर कोठा</div>
                        </div>
                        <div class="gallery-item" data-type="2-seater">
                            <img src="https://images.unsplash.com/photo-1584622650111-993a426fbf0a?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80"
                                 alt="२ सिटर कोठा"
                                 class="w-full h-full object-cover lazy"
                                 loading="lazy">
                            <div class="gallery-overlay"></div>
                            <div class="gallery-caption nepali">२ सिटर कोठा</div>
                        </div>
                        <div class="gallery-item" data-type="3-seater">
                            <img src="https://images.unsplash.com/photo-1540518614846-7eded433c457?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80"
                                 alt="३ सिटर कोठा"
                                 class="w-full h-full object-cover lazy"
                                 loading="lazy">
                            <div class="gallery-overlay"></div>
                            <div class="gallery-caption nepali">३ सिटर कोठा</div>
                        </div>
                        <div class="gallery-item" data-type="4-seater">
                            <img src="https://images.unsplash.com/photo-1513694203232-719a280e022f?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80"
                                 alt="४ सिटर कोठा"
                                 class="w-full h-full object-cover lazy"
                                 loading="lazy">
                            <div class="gallery-overlay"></div>
                            <div class="gallery-caption nepali">४ सिटर कोठा</div>
                        </div>
                        <div class="gallery-item" data-type="common">
                            <img src="https://images.unsplash.com/photo-1512917774080-9991f1c4c750?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80"
                                 alt="लिभिङ रूम"
                                 class="w-full h-full object-cover lazy"
                                 loading="lazy">
                            <div class="gallery-overlay"></div>
                            <div class="gallery-caption nepali">लिभिङ रूम</div>
                        </div>
                        <div class="gallery-item" data-type="common">
                            <img src="https://images.unsplash.com/photo-1584036561566-baf9516e802f?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80"
                                 alt="बाथरूम"
                                 class="w-full h-full object-cover lazy"
                                 loading="lazy">
                            <div class="gallery-overlay"></div>
                            <div class="gallery-caption nepali">बाथरूम</div>
                        </div>
                    @endif
                </div>
            </div>
        </section>

        <!-- Testimonials -->
        <section class="section testimonials" id="testimonials">
            <div class="container">
                <h2 class="section-title nepali" style="color: var(--text-light);">ग्राहकहरूको समीक्षा</h2>
                <p class="section-subtitle" style="color: rgba(249, 250, 251, 0.9);">HostelHub प्रयोग गर्ने हाम्रा ग्राहकहरूले के भन्छन्</p>
                <div class="testimonials-grid">
                    <div class="testimonial-card">
                        <p class="testimonial-text nepali">"HostelHub ले हाम्रो होस्टल व्यवस्थापनलाई पूर्ण रूपमा बदल्यो। सबै कार्यहरू अब स्वचालित भएकाले समय बचत भएको छ र त्रुटि घटेको छ।"</p>
                        <div class="testimonial-author">
                            <div class="author-avatar">RS</div>
                            <div class="author-info">
                                <h4>राम श्रेष्ठ</h4>
                                <p>प्रबन्धक, काठमाडौं होस्टल</p>
                            </div>
                        </div>
                    </div>
                    <div class="testimonial-card">
                        <p class="testimonial-text">"The payment tracking and room allocation features have saved us countless hours. The mobile access allows me to manage everything on the go. Highly recommended!"</p>
                        <div class="testimonial-author">
                            <div class="author-avatar">SP</div>
                            <div class="author-info">
                                <h4>सुनिता पौडेल</h4>
                                <p>मालिक, पोखरा स्टुडेन्ट होम</p>
                            </div>
                        </div>
                    </div>
                    <div class="testimonial-card">
                        <p class="testimonial-text nepali">"विद्यार्थी व्यवस्थापन र भोजन प्रणालीको एकीकरणले हामीलाई धेरै फाइदा गरायो। यो प्रणाली नेपाली होस्टलहरूको लागि उत्तम छ।"</p>
                        <div class="testimonial-author">
                            <div class="author-avatar">AK</div>
                            <div class="author-info">
                                <h4>अमित कुमार</h4>
                                <p>प्रशासक, बिराटनगर कलेज होस्टल</p>
                            </div>
                        </div>
                    </div>
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
                            <div class="pricing-price">रु. {{ $basicPlanPrice ?? '२,९९९' }}<span class="nepali">/महिना</span></div>
                        </div>
                        <ul class="pricing-features">
                            <li><i class="fas fa-check"></i> <span class="nepali">{{ $basicPlanStudents ?? '५०' }} विद्यार्थी सम्म</span></li>
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
                            <div class="pricing-price">रु. {{ $proPlanPrice ?? '४,९९९' }}<span class="nepali">/महिना</span></div>
                        </div>
                        <ul class="pricing-features">
                            <li><i class="fas fa-check"></i> <span class="nepali">{{ $proPlanStudents ?? '२००' }} विद्यार्थी सम्म</span></li>
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
                            <div class="pricing-price">रु. {{ $enterprisePlanPrice ?? '८,९९९' }}<span class="nepali">/महिना</span></div>
                        </div>
                        <ul class="pricing-features">
                            <li><i class="fas fa-check"></i> <span class="nepali">असीमित विद्यार्थी</span></li>
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
                        <a href="/demo" class="btn btn-outline nepali" style="background: white; color: var(--primary);">डेमो हेर्नुहोस्</a>
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
                    <a href="/" class="footer-logo">
                        <div class="footer-logo-icon">
                            <i class="fas fa-home"></i>
                        </div>
                        <span>HostelHub</span>
                    </a>
                    <p class="nepali" style="color: rgba(249, 250, 251, 0.8); margin-top: 15px; line-height: 1.7;">
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
                        <li><a href="#features"><i class="fas fa-chevron-right"></i> <span class="nepali">सुविधाहरू</span></a></li>
                        <li><a href="#how-it-works"><i class="fas fa-chevron-right"></i> <span class="nepali">कसरी काम गर्छ</span></a></li>
                        <li><a href="#gallery"><i class="fas fa-chevron-right"></i> <span class="nepali">ग्यालरी</span></a></li>
                        <li><a href="#pricing"><i class="fas fa-chevron-right"></i> <span class="nepali">मूल्य</span></a></li>
                        <li><a href="#testimonials"><i class="fas fa-chevron-right"></i> <span class="nepali">समीक्षाहरू</span></a></li>
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
                    <p class="nepali" style="color: rgba(249, 250, 251, 0.8); margin-bottom: 15px; line-height: 1.7;">
                        हाम्रो नवीनतम अपडेटहरू प्राप्त गर्न तपाईंको इमेल दर्ता गर्नुहोस्
                    </p>
                    <form class="newsletter-form" action="/subscribe" method="POST">
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

    <!-- Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-XXXXXXXXXX"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());
        gtag('config', 'G-XXXXXXXXXX');
    </script>

    <!-- Main JavaScript -->
    <script>
        // Header scroll behavior
        window.addEventListener('scroll', () => {
            const headerInner = document.querySelector('.header-inner');
            const siteHeader = document.getElementById('site-header');
            if (window.scrollY > 40) {
                siteHeader.classList.add('header-scrolled');
            } else {
                siteHeader.classList.remove('header-scrolled');
            }
        });

        // Initialize when DOM is loaded
        document.addEventListener('DOMContentLoaded', () => {
            // Mobile menu toggle with accessibility
            const menuBtn = document.querySelector('.mobile-menu-btn');
            const navLinks = document.querySelector('.nav-links');

            if (menuBtn && navLinks) {
                menuBtn.addEventListener('click', () => {
                    const expanded = menuBtn.getAttribute('aria-expanded') === 'true' || false;
                    menuBtn.setAttribute('aria-expanded', !expanded);
                    navLinks.classList.toggle('show');
                });

                // Close mobile menu when clicking outside
                document.addEventListener('click', (event) => {
                    if (!navLinks.contains(event.target) && !menuBtn.contains(event.target) && navLinks.classList.contains('show')) {
                        navLinks.classList.remove('show');
                        menuBtn.setAttribute('aria-expanded', 'false');
                    }
                });
            }

            // Gallery filter
            const filterButtons = document.querySelectorAll('.filter-btn');
            filterButtons.forEach(button => {
                button.addEventListener('click', () => {
                    filterButtons.forEach(btn => btn.classList.remove('active'));
                    button.classList.add('active');

                    const filter = button.getAttribute('data-filter');
                    const galleryItems = document.querySelectorAll('.gallery-item');

                    galleryItems.forEach(item => {
                        if (filter === 'all' || item.getAttribute('data-type') === filter) {
                            item.style.display = 'block';
                        } else {
                            item.style.display = 'none';
                        }
                    });
                });
            });

            // Gallery lightbox with accessibility improvements
            const lightbox = document.getElementById('lightbox');
            const lightboxImg = document.getElementById('lightbox-img');
            const lightboxCaption = document.getElementById('caption');
            const closeBtn = document.querySelector('.close-btn');
            const galleryItems = document.querySelectorAll('.gallery-item');
            const body = document.body;

            // Focus trap for modal
            function trapFocus(event) {
                const focusableElements = lightbox.querySelectorAll('button, [href], input, select, textarea, [tabindex]:not([tabindex="-1"])');
                const firstFocusableElement = focusableElements[0];
                const lastFocusableElement = focusableElements[focusableElements.length - 1];

                if (event.key === 'Tab') {
                    if (event.shiftKey) {
                        if (document.activeElement === firstFocusableElement) {
                            lastFocusableElement.focus();
                            event.preventDefault();
                        }
                    } else {
                        if (document.activeElement === lastFocusableElement) {
                            firstFocusableElement.focus();
                            event.preventDefault();
                        }
                    }
                }
            }

            // Open lightbox
            galleryItems.forEach(item => {
                item.addEventListener('click', () => {
                    const img = item.querySelector('img');
                    const imgSrc = img.src;
                    const imgAlt = img.alt;
                    const caption = item.getAttribute('data-caption') || img.alt;

                    lightboxImg.src = imgSrc;
                    lightboxImg.alt = imgAlt;
                    lightboxCaption.textContent = caption;
                    lightbox.classList.add('active');
                    lightbox.setAttribute('aria-hidden', 'false');
                    closeBtn.focus();
                    body.style.overflow = 'hidden';
                });
            });

            // Close lightbox
            function closeLightbox() {
                lightbox.classList.remove('active');
                lightbox.setAttribute('aria-hidden', 'true');
                body.style.overflow = '';
            }

            closeBtn.addEventListener('click', closeLightbox);

            lightbox.addEventListener('click', (e) => {
                if (e.target === lightbox) {
                    closeLightbox();
                }
            });

            // Close with ESC key
            document.addEventListener('keydown', (e) => {
                if (e.key === 'Escape' && lightbox.classList.contains('active')) {
                    closeLightbox();
                }
                if (e.key === 'Tab' && lightbox.classList.contains('active')) {
                    trapFocus(e);
                }
            });

            // Booking form validation
            const bookingForm = document.getElementById('booking-form');
            const checkinDate = document.getElementById('checkin-date');
            const checkoutDate = document.getElementById('checkout-date');

            // Set min date for check-in to today
            const today = new Date().toISOString().split('T')[0];
            if (checkinDate) checkinDate.setAttribute('min', today);

            function validateDates() {
                if (!checkinDate.value || !checkoutDate.value) return true;
                const checkin = new Date(checkinDate.value);
                const checkout = new Date(checkoutDate.value);
                return checkin < checkout;
            }

            bookingForm?.addEventListener('submit', (e) => {
                let isValid = true;
                // Validate required fields
                const requiredFields = bookingForm.querySelectorAll('[required]');
                requiredFields.forEach(field => {
                    const formGroup = field.closest('.form-group');
                    if (!field.value) {
                        formGroup.classList.add('error');
                        isValid = false;
                    } else {
                        formGroup.classList.remove('error');
                    }
                });

                // Validate dates
                if (!validateDates()) {
                    const formGroup = checkoutDate.closest('.form-group');
                    formGroup.classList.add('error');
                    const errorElement = formGroup.querySelector('.error-message');
                    if (errorElement) {
                        errorElement.textContent = 'चेक-आउट मिति चेक-इन मिति भन्दा पछि हुनुपर्छ';
                    }
                    isValid = false;
                }

                if (!isValid) {
                    e.preventDefault();
                }
            });

            // Date validation on input
            [checkinDate, checkoutDate].forEach(dateInput => {
                dateInput?.addEventListener('change', () => {
                    if (checkinDate.value && checkoutDate.value) {
                        const formGroup = checkoutDate.closest('.form-group');
                        if (!validateDates()) {
                            formGroup.classList.add('error');
                            const errorElement = formGroup.querySelector('.error-message');
                            if (errorElement) {
                                errorElement.textContent = 'चेक-आउट मिति चेक-इन मिति भन्दा पछि हुनुपर्छ';
                            }
                        } else {
                            formGroup.classList.remove('error');
                        }
                    }
                });
            });

            // Counter animation
            function animateCounter(elementId, finalValue, duration = 2000) {
                const element = document.getElementById(elementId);
                if (!element) return;

                // Extract numeric value from element text (handles Nepali numbers)
                let currentValue = parseInt(element.textContent.replace(/[^\d]/g, '')) || 0;
                finalValue = parseInt(finalValue) || 0;

                let start = currentValue;
                const increment = Math.ceil(finalValue / (duration / 16)); // 16ms per frame ≈ 60fps

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

            // Initialize counters when they come into view
            const counterObserver = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        animateCounter('students-counter', {{ $totalStudents ?? 125 }});
                        animateCounter('hostels-counter', {{ $totalHostels ?? 24 }});
                        animateCounter('cities-counter', {{ $totalCities ?? 5 }});
                        animateCounter('students-counter-stat', {{ $totalStudents ?? 125 }});
                        animateCounter('hostels-counter-stat', {{ $totalHostels ?? 24 }});
                        animateCounter('cities-counter-stat', {{ $totalCities ?? 5 }});
                        counterObserver.disconnect();
                    }
                });
            }, { threshold: 0.5 });

            const statsSection = document.querySelector('.hero-stats, .stats-section');
            if (statsSection) {
                counterObserver.observe(statsSection);
            }

            // Scroll animation for elements
            const animateOnScroll = () => {
                const elements = document.querySelectorAll(
                    '.feature-card, .step, .gallery-item, .testimonial-card, .pricing-card'
                );
                elements.forEach(el => {
                    el.style.opacity = 0;
                    el.style.transform = 'translateY(30px)';
                });

                const observer = new IntersectionObserver((entries) => {
                    entries.forEach(entry => {
                        if (entry.isIntersecting) {
                            entry.target.style.transition = 'opacity 0.8s ease, transform 0.8s ease';
                            entry.target.style.opacity = 1;
                            entry.target.style.transform = 'translateY(0)';
                        }
                    });
                }, { threshold: 0.1 });

                elements.forEach(el => observer.observe(el));
            };

            // Initialize animations
            animateOnScroll();

            // Set current date for date pickers
            const now = new Date();
            const tomorrow = new Date(now);
            tomorrow.setDate(now.getDate() + 1);

            const formatDate = (date) => {
                // Format date as dd/mm/yyyy for Nepal
                const day = String(date.getDate()).padStart(2, '0');
                const month = String(date.getMonth() + 1).padStart(2, '0');
                const year = date.getFullYear();
                return `${day}/${month}/${year}`;
            };

            // Format date for HTML date input (ISO format)
            const formatISODate = (date) => {
                return date.toISOString().split('T')[0];
            };

            // Set the date inputs with ISO format
            if (checkinDate) checkinDate.value = formatISODate(now);
            if (checkoutDate) checkoutDate.value = formatISODate(tomorrow);

            // Add date validation for Nepali date format
            document.querySelectorAll('.form-control[type="date"]').forEach(input => {
                input.addEventListener('focus', function() {
                    this.type = 'date';
                });
                input.addEventListener('blur', function() {
                    if (this.value === '') {
                        this.type = 'text';
                        this.placeholder = 'दिनांक छान्नुहोस्';
                    }
                });
                // Initialize placeholder
                if (input.value === '') {
                    input.type = 'text';
                    input.placeholder = 'दिनांक छान्नुहोस्';
                }
            });
        });
    </script>

    <!-- Error Monitoring -->
    <script>
        // Basic error monitoring
        window.onerror = function(message, source, lineno, colno, error) {
            console.error('Error:', message, 'at', source, 'line', lineno, 'column', colno);
            // In production, you would send this to a service like Sentry
            return true; // Prevent default error handling
        };
    </script>
</body>
</html>
