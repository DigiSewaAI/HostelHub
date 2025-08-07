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
            --primary: #1D3557;       /* Primary background */
            --accent: #F4A261;        /* Buttons, highlights, icons */
            --secondary-accent: #2A9D8F; /* Secondary CTAs, accents */
            --base-bg: #F8F9FA;       /* Section background */
            --text-dark: #1E1E1E;     /* Default text */
            --text-light: #FFFFFF;    /* On dark backgrounds */
            --accent-hover: #E76F51;  /* Button hover */
            --light-bg: #FFFFFF;      /* Cards background */
            --border: #E0E0E0;        /* Borders */
            --shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            --transition: all 0.3s ease-in-out;
            --radius: 0.5rem;
            --glow: 0 8px 30px rgba(42, 157, 143, 0.15);
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
            padding: 0.8rem 1.8rem;
            border-radius: var(--radius);
            font-weight: 600;
            text-align: center;
            text-decoration: none;
            transition: var(--transition);
            cursor: pointer;
            font-size: 1rem;
            border: none;
        }

        .btn-primary {
            background: var(--accent);
            color: var(--text-light);
            position: relative;
            overflow: hidden;
            z-index: 1;
            box-shadow: var(--glow);
        }

        .btn-primary:hover {
            background: var(--accent-hover);
            transform: translateY(-3px);
            box-shadow: 0 12px 40px rgba(231, 111, 81, 0.25);
        }

        .btn-secondary {
            background-color: transparent;
            color: var(--primary);
            border: 2px solid var(--primary);
        }

        .btn-secondary:hover {
            background-color: var(--primary);
            color: var(--text-light);
            transform: translateY(-3px);
        }

        .btn-accent {
            background: var(--secondary-accent);
            color: var(--text-light);
            box-shadow: var(--glow);
        }

        .btn-accent:hover {
            background: #259586;
            transform: translateY(-3px);
            box-shadow: 0 12px 40px rgba(42, 157, 143, 0.25);
        }

        .section {
            padding: 5rem 0;
        }

        .section-title {
            font-size: 2.25rem;
            font-weight: 800;
            margin-bottom: 1rem;
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
            background: var(--secondary-accent);
            border-radius: 2px;
        }

        .section-subtitle {
            font-size: 1.25rem;
            color: #4B5563;
            text-align: center;
            max-width: 700px;
            margin: 2rem auto 3rem;
            line-height: 1.7;
        }

        /* Fixed Header with z-index fix */
        #site-header {
            position: fixed;
            top: 0;
            width: 100%;
            z-index: 1000;
            background: var(--primary);
            box-shadow: 0 2px 15px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
        }

        .header-inner {
            padding: 1rem 0;
            transition: all 0.3s ease;
        }

        /* Compact header on scroll */
        .header-compact {
            padding: 0.5rem 0 !important;
        }

        .navbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.5rem 0;
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
            background: var(--accent);
            border-radius: 0.5rem;
            color: var(--text-light);
            box-shadow: var(--glow);
        }

        .nav-links {
            display: flex;
            align-items: center;
            gap: 2rem;
        }

        .nav-links a {
            text-decoration: none;
            color: rgba(255, 255, 255, 0.85);
            font-weight: 500;
            transition: var(--transition);
            position: relative;
            padding: 0.5rem 0;
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
            background: var(--accent);
            transition: var(--transition);
        }

        .nav-links a.active::after, .nav-links a:hover::after {
            width: 100%;
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
            padding: 12rem 0 6rem; /* Adjusted for fixed header */
            background: linear-gradient(135deg, #1D3557, #2A9D8F);
            position: relative;
            overflow: hidden;
            clip-path: polygon(0 0, 100% 0, 100% 95%, 0 100%);
            z-index: 10; /* Set z-index for stacking */
        }

        .hero::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100" preserveAspectRatio="none"><path d="M0,0 L100,100 L0,100 Z" fill="rgba(255,255,255,0.1)"></path></svg>');
            background-size: 200px;
            opacity: 0.3;
            z-index: 5; /* Set z-index lower than header */
            pointer-events: none; /* Allow clicks through */
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
            z-index: 15; /* Ensure above overlay */
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
            color: rgba(255, 255, 255, 0.9);
            margin-bottom: 2rem;
            line-height: 1.7;
        }

        .hero-cta {
            display: flex;
            gap: 1rem;
            margin-bottom: 2rem;
        }

        .hero-stats {
            display: flex;
            gap: 2rem;
            margin-top: 2rem;
        }

        .stat-item {
            text-align: center;
        }

        .stat-number {
            font-size: 2.5rem;
            font-weight: 800;
            color: var(--text-light);
            background: rgba(255, 255, 255, 0.15);
            padding: 0.5rem 1rem;
            border-radius: var(--radius);
            min-width: 120px;
        }

        .stat-label {
            font-size: 0.9rem;
            margin-top: 0.5rem;
            color: rgba(255, 255, 255, 0.9);
        }

        .hero-image {
            position: relative;
            border-radius: var(--radius);
            overflow: hidden;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
            transform: perspective(1000px) rotateY(-5deg);
            border: 1px solid rgba(255, 255, 255, 0.3);
            animation: float 6s ease-in-out infinite;
            z-index: 15; /* Ensure above overlay */
        }

        .hero-image img {
            width: 100%;
            height: auto;
            display: block;
        }

        /* Reservation Widget */
        .reservation-widget {
            background: var(--light-bg);
            border-radius: var(--radius);
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1);
            padding: 2rem;
            margin-top: -5rem;
            position: relative;
            z-index: 30; /* Set between header and hero */
            border: 1px solid var(--border);
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
            gap: 1rem;
        }

        .form-group {
            margin-bottom: 1rem;
            position: relative;
        }

        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 500;
            color: var(--text-dark);
        }

        .form-control {
            width: 100%;
            padding: 0.75rem 1rem;
            border: 1px solid #E5E7EB;
            border-radius: var(--radius);
            font-family: inherit;
            font-size: 1rem;
            transition: var(--transition);
        }

        .form-control:focus {
            outline: none;
            border-color: var(--secondary-accent);
            box-shadow: 0 0 0 3px rgba(42, 157, 143, 0.3);
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

        /* Features Section */
        .features {
            background-color: var(--base-bg);
        }

        .features-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 2rem;
        }

        .feature-card {
            background: var(--light-bg);
            border-radius: var(--radius);
            padding: 2rem;
            box-shadow: var(--shadow);
            transition: var(--transition);
            text-align: center;
            border: 1px solid var(--border);
        }

        .feature-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.15);
            border-color: var(--secondary-accent);
        }

        .feature-icon {
            width: 80px;
            height: 80px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: var(--secondary-accent);
            border-radius: 50%;
            margin: 0 auto 1.5rem;
            color: var(--text-light);
            font-size: 2rem;
            transition: var(--transition);
            box-shadow: var(--glow);
        }

        .feature-card:hover .feature-icon {
            transform: scale(1.1);
            box-shadow: 0 0 20px rgba(42, 157, 143, 0.5);
        }

        .feature-title {
            font-size: 1.25rem;
            font-weight: 700;
            margin-bottom: 0.75rem;
            color: var(--primary);
        }

        .feature-desc {
            color: var(--text-dark);
            font-size: 0.95rem;
            line-height: 1.6;
        }

        /* How It Works */
        .how-it-works {
            background-color: var(--light-bg);
            position: relative;
            overflow: hidden;
        }

        .how-it-works::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100" preserveAspectRatio="none" opacity="0.05"><path d="M0,0 L100,0 L50,100 Z" fill="%232A9D8F"></path></svg>');
            background-size: 300px;
            opacity: 0.3;
        }

        .steps {
            display: flex;
            justify-content: center;
            gap: 3rem;
            position: relative;
            counter-reset: step-counter;
        }

        .steps::before {
            content: "";
            position: absolute;
            top: 50%;
            left: 10%;
            right: 10%;
            height: 4px;
            background: linear-gradient(to right, var(--secondary-accent), #1D3557);
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

        .step::before {
            counter-increment: step-counter;
            content: counter(step-counter);
            position: absolute;
            top: -1.5rem;
            left: 50%;
            transform: translateX(-50%);
            width: 3rem;
            height: 3rem;
            background: var(--secondary-accent);
            color: var(--text-light);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
            font-weight: 700;
            box-shadow: var(--glow);
        }

        .step-title {
            font-size: 1.25rem;
            font-weight: 700;
            margin-bottom: 1rem;
            color: var(--primary);
        }

        .step-desc {
            color: var(--text-dark);
            margin-bottom: 1rem;
            line-height: 1.7;
        }

        /* Gallery */
        .gallery {
            background-color: var(--base-bg);
        }

        .gallery-filters {
            display: flex;
            justify-content: center;
            gap: 1rem;
            margin-bottom: 2rem;
            flex-wrap: wrap;
        }

        .filter-btn {
            padding: 0.5rem 1.5rem;
            border-radius: 2rem;
            background: var(--light-bg);
            border: 1px solid var(--border);
            color: var(--text-dark);
            cursor: pointer;
            transition: var(--transition);
        }

        .filter-btn.active, .filter-btn:hover {
            background: var(--secondary-accent);
            color: var(--text-light);
            border-color: transparent;
        }

        .gallery-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
        }

        .gallery-item {
            border-radius: var(--radius);
            overflow: hidden;
            position: relative;
            height: 250px;
            cursor: pointer;
            box-shadow: var(--shadow);
            transition: var(--transition);
        }

        .gallery-item:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.15);
        }

        .gallery-item img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: var(--transition);
        }

        .gallery-item:hover img {
            transform: scale(1.05);
        }

        .gallery-caption {
            position: absolute;
            bottom: -100%;
            left: 0;
            right: 0;
            background: rgba(29, 53, 87, 0.8);
            color: white;
            padding: 1rem;
            font-weight: 500;
            z-index: 2;
            transition: var(--transition);
            backdrop-filter: blur(5px);
        }

        .gallery-item:hover .gallery-caption {
            bottom: 0;
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
            box-shadow: 0 0 25px rgba(42, 157, 143, 0.3);
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
            color: var(--accent);
            transform: scale(1.1);
        }

        /* Testimonials */
        .testimonials {
            background-color: var(--light-bg);
            position: relative;
        }

        .testimonials::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100" preserveAspectRatio="none" opacity="0.05"><circle cx="50" cy="50" r="40" fill="%232A9D8F"></circle></svg>');
            background-size: 200px;
            opacity: 0.1;
        }

        .testimonials-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 2rem;
        }

        .testimonial-card {
            background: var(--light-bg);
            border-radius: var(--radius);
            padding: 2.5rem 2rem 2rem;
            box-shadow: var(--shadow);
            border: 1px solid var(--border);
            position: relative;
            transition: var(--transition);
        }

        .testimonial-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
        }

        .testimonial-card::before {
            content: """;
            position: absolute;
            top: 1rem;
            left: 1.5rem;
            font-size: 5rem;
            color: var(--secondary-accent);
            font-family: Georgia, serif;
            line-height: 1;
            opacity: 0.3;
        }

        .testimonial-text {
            margin-bottom: 1.5rem;
            color: var(--text-dark);
            font-style: italic;
            position: relative;
            z-index: 1;
            line-height: 1.8;
        }

        .testimonial-author {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .author-avatar {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background: var(--secondary-accent);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--text-light);
            font-weight: 700;
            font-size: 1.25rem;
            flex-shrink: 0;
        }

        .author-info h4 {
            font-weight: 700;
            margin-bottom: 0.25rem;
            color: var(--primary);
        }

        .author-info p {
            color: var(--secondary-accent);
            font-size: 0.9rem;
            font-weight: 600;
        }

        /* Pricing */
        .pricing {
            background: linear-gradient(to bottom, var(--base-bg), var(--light-bg));
        }

        .pricing-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 2rem;
        }

        .pricing-card {
            background: var(--light-bg);
            border-radius: var(--radius);
            padding: 2.5rem 2rem;
            box-shadow: var(--shadow);
            text-align: center;
            border: 1px solid var(--border);
            position: relative;
            transition: var(--transition);
        }

        .pricing-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
        }

        .pricing-card.popular {
            border: 2px solid var(--secondary-accent);
            box-shadow: var(--glow);
            transform: scale(1.03);
            z-index: 1;
        }

        .pricing-card.popular::before {
            content: "लोकप्रिय चयन";
            position: absolute;
            top: 15px;
            right: -35px;
            background: var(--secondary-accent);
            color: var(--text-light);
            padding: 5px 35px;
            font-size: 0.8rem;
            font-weight: 700;
            transform: rotate(45deg);
            z-index: 2;
            box-shadow: 0 3px 10px rgba(0,0,0,0.1);
        }

        .pricing-header {
            margin-bottom: 1.5rem;
        }

        .pricing-title {
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
            color: var(--primary);
        }

        .pricing-price {
            font-size: 2.5rem;
            font-weight: 800;
            color: var(--primary);
            margin-bottom: 0.5rem;
        }

        .pricing-price span {
            font-size: 1.2rem;
            color: #4B5563;
            font-weight: 400;
        }

        .pricing-features {
            list-style: none;
            margin: 2rem 0;
            text-align: left;
        }

        .pricing-features li {
            padding: 0.75rem 0;
            border-bottom: 1px solid var(--border);
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .pricing-features li:last-child {
            border-bottom: none;
        }

        .pricing-features li i {
            color: var(--secondary-accent);
        }

        /* CTA Section */
        .cta {
            background: linear-gradient(105deg, var(--primary), var(--secondary-accent));
            color: var(--text-light);
            padding: 6rem 0;
            text-align: center;
            position: relative;
            overflow: hidden;
            clip-path: polygon(0 10%, 100% 0, 100% 100%, 0 100%);
        }

        .cta::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100" preserveAspectRatio="none"><path d="M0,0 L100,100 L0,100 Z" fill="rgba(255,255,255,0.1)"></path></svg>');
            background-size: 200px;
            opacity: 0.2;
        }

        .cta-content {
            position: relative;
            z-index: 2;
            max-width: 900px;
            margin: 0 auto;
        }

        .cta h2 {
            font-size: 2.8rem;
            margin-bottom: 1.5rem;
            font-weight: 800;
            color: var(--text-light);
            text-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .cta p {
            font-size: 1.3rem;
            max-width: 700px;
            margin: 0 auto 2.5rem;
            line-height: 1.7;
            color: rgba(255, 255, 255, 0.9);
        }

        .trial-info {
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(5px);
            border-radius: 20px;
            padding: 20px;
            margin: 2rem auto;
            max-width: 600px;
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        /* Footer */
        footer {
            background-color: var(--primary);
            color: var(--text-light);
            padding: 4rem 0 2rem;
        }

        .footer-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 3rem;
            margin-bottom: 3rem;
        }

        .footer-col h3 {
            font-size: 1.4rem;
            margin-bottom: 1.5rem;
            position: relative;
            padding-bottom: 0.75rem;
            color: var(--accent);
        }

        .footer-col h3::after {
            content: "";
            position: absolute;
            bottom: 0;
            left: 0;
            width: 50px;
            height: 3px;
            background: var(--accent);
        }

        .footer-links {
            list-style: none;
        }

        .footer-links li {
            margin-bottom: 0.75rem;
        }

        .footer-links a {
            color: rgba(255, 255, 255, 0.8);
            text-decoration: none;
            transition: var(--transition);
            display: flex;
            align-items: center;
        }

        .footer-links a:hover {
            color: var(--accent);
            transform: translateX(5px);
        }

        .footer-links i {
            margin-right: 10px;
            width: 20px;
            color: var(--accent);
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
            color: var(--accent);
            font-size: 1.2rem;
            margin-top: 5px;
            flex-shrink: 0;
        }

        .footer-logo {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 1.5rem;
            color: var(--text-light);
            display: flex;
            align-items: center;
        }

        .footer-logo i {
            color: var(--accent);
            margin-right: 12px;
        }

        .copyright {
            margin-top: 5rem;
            padding-top: 2rem;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            font-size: 1.1rem;
            color: rgba(255, 255, 255, 0.7);
            text-align: center;
            grid-column: 1 / -1;
        }

        .social-links {
            display: flex;
            gap: 15px;
            margin-top: 25px;
        }

        .social-links a {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 45px;
            height: 45px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            color: white;
            font-size: 1.2rem;
            transition: var(--transition);
        }

        .social-links a:hover {
            background: var(--accent);
            color: var(--text-light);
            transform: translateY(-5px);
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
            font-size: 1rem;
            background: rgba(255, 255, 255, 0.1);
            color: white;
        }

        .newsletter-form input::placeholder {
            color: rgba(255, 255, 255, 0.6);
        }

        .newsletter-form button {
            background: var(--accent);
            color: var(--text-light);
            border: none;
            border-radius: var(--radius);
            padding: 0 1.5rem;
            font-weight: 600;
            cursor: pointer;
            transition: var(--transition);
        }

        .newsletter-form button:hover {
            background: var(--accent-hover);
            transform: translateY(-3px);
        }

        /* Animation */
        @keyframes float {
            0% { transform: translateY(0px); }
            50% { transform: translateY(-15px); }
            100% { transform: translateY(0px); }
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
        @media (max-width: 992px) {
            .hero-content {
                grid-template-columns: 1fr;
            }

            .hero-image {
                max-width: 600px;
                margin: 2rem auto 0;
                transform: none;
            }

            .steps {
                flex-direction: column;
                align-items: center;
            }

            .steps::before {
                top: 0;
                left: 50%;
                bottom: 0;
                width: 4px;
                height: 100%;
                transform: translateX(-50%);
            }

            .nav-links {
                display: none;
                position: absolute;
                top: 100%;
                left: 0;
                right: 0;
                background: var(--primary);
                flex-direction: column;
                padding: 2rem;
                box-shadow: 0 10px 15px rgba(0,0,0,0.1);
            }

            .nav-links.show {
                display: flex;
            }

            .mobile-menu-btn {
                display: block;
            }
        }

        @media (max-width: 768px) {
            .section {
                padding: 3rem 0;
            }

            .hero {
                padding: 8rem 0 2rem;
                clip-path: polygon(0 0, 100% 0, 100% 97%, 0 100%);
            }

            .hero-title {
                font-size: 2.5rem;
            }

            .hero-cta {
                flex-direction: column;
            }

            .hero-stats {
                flex-direction: column;
                gap: 1rem;
            }

            .reservation-widget {
                margin-top: 2rem;
            }

            .pricing-card.popular::before {
                right: -30px;
                padding: 5px 30px;
            }

            .widget-form {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <!-- Fixed Header -->
    <header id="site-header">
        <div class="header-inner">
            <div class="container">
                <div class="navbar">
                    <a href="/" class="logo">
                        <div class="logo-icon floating">
                            <i class="fas fa-home"></i>
                        </div>
                        <div class="logo-text">
                            <h1>HostelHub</h1>
                            <span class="nepali">होस्टल प्रबन्धन प्रणाली</span>
                        </div>
                    </a>

                    <div class="nav-links">
                        <a href="#features" class="nepali">सुविधाहरू</a>
                        <a href="#how-it-works" class="nepali">कसरी काम गर्छ</a>
                        <a href="#gallery" class="nepali">ग्यालरी</a>
                        <a href="#pricing" class="nepali">मूल्य</a>
                        <a href="#testimonials" class="nepali">समीक्षाहरू</a>
                        <a href="/login" class="nepali">लगइन</a>
                        <a href="/signup" class="btn btn-primary nepali">साइन अप</a>
                    </div>

                    <button class="mobile-menu-btn" aria-label="मेनु खोल्नुहोस्">
                        <i class="fas fa-bars"></i>
                    </button>
                </div>
            </div>
        </div>
    </header>

    <!-- Lightbox -->
    <div id="lightbox" class="lightbox">
        <span class="close-btn" aria-label="बन्द गर्नुहोस्">&times;</span>
        <img class="lightbox-content" id="lightbox-img" alt="Enlarged hostel image">
        <div id="caption" class="lightbox-caption nepali"></div>
    </div>

    <!-- Hero Section -->
    <section class="hero">
        <div class="container">
            <div class="hero-content">
                <div class="hero-text">
                    <h1 class="hero-title nepali">HostelHub — तपाइँको होस्टल व्यवस्थापन अब सजिलो, द्रुत र भरपर्दो</h1>
                    <p class="hero-subtitle nepali">विद्यार्थी व्यवस्थापन, कोठा आवंटन, भुक्तानी र भोजन प्रणाली—एकै प्लेटफर्मबाट चलाउनुहोस्। ७ दिन निःशुल्क।</p>

                    <div class="hero-cta">
                        <a href="/signup" class="btn btn-primary nepali">डेमो माग्नुहोस्</a>
                        <a href="#gallery" class="btn btn-secondary nepali">खोजी सुरु गर्नुहोस्</a>
                    </div>

                    <div class="hero-stats">
                        <div class="stat-item">
                            <div class="stat-number count-up" id="students-counter">125</div>
                            <div class="stat-label nepali">कुल विद्यार्थीहरू</div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-number count-up" id="hostels-counter">24</div>
                            <div class="stat-label nepali">सहयोगी होस्टल</div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-number count-up" id="cities-counter">5</div>
                            <div class="stat-label nepali">शहरहरू</div>
                        </div>
                    </div>
                </div>

                <div class="hero-image floating">
                    <img src="https://images.unsplash.com/photo-1560448204-e02f11c3d0e2?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80" alt="HostelHub ड्यासबोर्डको पूर्वावलोकन">
                </div>
            </div>
        </div>
    </section>

    <!-- Reservation Widget -->
    <section class="container">
        <div class="reservation-widget">
            <h3 class="widget-title nepali">कोठा खोजी / रिजर्भ गर्नुहोस्</h3>
            <form class="widget-form" id="booking-form">
                <div class="form-group">
                    <label class="nepali">स्थान / City</label>
                    <select class="form-control" required aria-required="true">
                        <option value="">काठमाडौं</option>
                        <option value="">पोखरा</option>
                        <option value="">चितवन</option>
                        <option value="">बिराटनगर</option>
                    </select>
                    <div class="error-message nepali">स्थान चयन गर्नुहोस्</div>
                </div>

                <div class="form-group">
                    <label class="nepali">होस्टल / Hostel</label>
                    <select class="form-control" required aria-required="true">
                        <option value="">सबै होस्टल</option>
                        <option value="">कलेज होस्टल</option>
                        <option value="">बालिका होस्टल</option>
                        <option value="">सामुदायिक होस्टल</option>
                    </select>
                    <div class="error-message nepali">होस्टल चयन गर्नुहोस्</div>
                </div>

                <div class="form-group">
                    <label class="nepali">चेक-इन मिति</label>
                    <input type="date" class="form-control" id="checkin-date" required aria-required="true">
                    <div class="error-message nepali">चेक-इन मिति आवश्यक छ</div>
                </div>

                <div class="form-group">
                    <label class="nepali">चेक-आउट मिति</label>
                    <input type="date" class="form-control" id="checkout-date" required aria-required="true">
                    <div class="error-message nepali">चेक-आउट मिति आवश्यक छ</div>
                </div>

                <div class="form-group">
                    <button type="submit" class="btn btn-accent nepali" style="width: 100%; margin-top: 1.8rem;">खोज्नुहोस्</button>
                </div>
            </form>
        </div>
    </section>

    <!-- Features Section -->
    <section class="section features" id="features">
        <div class="container">
            <h2 class="section-title nepali">हाम्रा प्रमुख सुविधाहरू</h2>
            <p class="section-subtitle nepali">HostelHub ले प्रदान गर्ने विशेष सुविधाहरू जसले तपाईंको होस्टल व्यवस्थापनलाई सजिलो बनाउँछ</p>

            <div class="features-grid">
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-users"></i>
                    </div>
                    <h3 class="feature-title nepali">विद्यार्थी व्यवस्थापन</h3>
                    <p class="feature-desc nepali">सबै विवरण एउटै ठाउँमा प्रबन्धन गर्नुहोस्</p>
                </div>

                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-bed"></i>
                    </div>
                    <h3 class="feature-title nepali">कोठा उपलब्धता</h3>
                    <p class="feature-desc nepali">रियल-टाइम कोठा उपलब्धता र रिपोर्ट</p>
                </div>

                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-credit-card"></i>
                    </div>
                    <h3 class="feature-title nepali">भुक्तानी प्रणाली</h3>
                    <p class="feature-desc nepali">भुक्तानी र बिलिङ सजिलै प्रबन्धन गर्नुहोस्</p>
                </div>

                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-utensils"></i>
                    </div>
                    <h3 class="feature-title nepali">भोजन व्यवस्थापन</h3>
                    <p class="feature-desc nepali">मेनु र भोजन व्यवस्थापनको सम्पूर्ण समाधान</p>
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
                    <h3 class="step-title nepali">खाता सिर्जना गर्नुहोस्</h3>
                    <p class="step-desc nepali">निःशुल्क खाताको लागि साइन अप गर्नुहोस् र आफ्नो होस्टल विवरणहरू थप्नुहोस्</p>
                </div>

                <div class="step">
                    <h3 class="step-title nepali">व्यवस्थापन सुरु गर्नुहोस्</h3>
                    <p class="step-desc nepali">विद्यार्थीहरू थप्नुहोस्, कोठा आवंटन गर्नुहोस्, र भुक्तानीहरू ट्र्याक गर्नुहोस्</p>
                </div>

                <div class="step">
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

            <div class="gallery-filters">
                <button class="filter-btn active nepali">सबै</button>
                <button class="filter-btn nepali">एकल कोठा</button>
                <button class="filter-btn nepali">दुई ब्यक्ति कोठा</button>
                <button class="filter-btn nepali">सामान्य क्षेत्र</button>
            </div>

            <div class="gallery-grid">
                <div class="gallery-item">
                    <img src="https://images.unsplash.com/photo-1554995207-c18c203602cb?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80" alt="एकल कोठा" data-caption="एकल कोठा">
                    <div class="gallery-caption nepali">एकल कोठा</div>
                </div>

                <div class="gallery-item">
                    <img src="https://images.unsplash.com/photo-1584622650111-993a426fbf0a?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80" alt="दुई ब्यक्ति कोठा" data-caption="दुई ब्यक्ति कोठा">
                    <div class="gallery-caption nepali">दुई ब्यक्ति कोठा</div>
                </div>

                <div class="gallery-item">
                    <img src="https://images.unsplash.com/photo-1540518614846-7eded433c457?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80" alt="अध्ययन क्षेत्र" data-caption="अध्ययन क्षेत्र">
                    <div class="gallery-caption nepali">अध्ययन क्षेत्र</div>
                </div>

                <div class="gallery-item">
                    <img src="https://images.unsplash.com/photo-1513694203232-719a280e022f?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80" alt="भोजन कक्ष" data-caption="भोजन कक्ष">
                    <div class="gallery-caption nepali">भोजन कक्ष</div>
                </div>
            </div>
        </div>
    </section>

    <!-- Testimonials -->
    <section class="section testimonials" id="testimonials">
        <div class="container">
            <h2 class="section-title nepali">ग्राहकहरूको समीक्षा</h2>
            <p class="section-subtitle nepali">HostelHub प्रयोग गर्ने हाम्रा ग्राहकहरूले के भन्छन्</p>

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
                        <div class="pricing-price">रु. २,९९९<span class="nepali">/महिना</span></div>
                    </div>

                    <ul class="pricing-features">
                        <li><i class="fas fa-check"></i> <span class="nepali">५० विद्यार्थी सम्म</span></li>
                        <li><i class="fas fa-check"></i> <span class="nepali">मूल विद्यार्थी व्यवस्थापन</span></li>
                        <li><i class="fas fa-check"></i> <span class="nepali">कोठा आवंटन</span></li>
                        <li><i class="fas fa-check"></i> <span class="nepali">भुक्तानी ट्र्याकिंग</span></li>
                        <li><i class="fas fa-times"></i> <span class="nepali">भोजन व्यवस्थापन</span></li>
                    </ul>

                    <a href="/signup" class="btn btn-secondary nepali">सुरु गर्नुहोस्</a>
                </div>

                <div class="pricing-card popular">
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
                    </ul>

                    <a href="/signup" class="btn btn-primary nepali">लोकप्रिय चयन</a>
                </div>

                <div class="pricing-card">
                    <div class="pricing-header">
                        <h3 class="pricing-title nepali">एन्टरप्राइज</h3>
                        <div class="pricing-price">रु. ८,९९९<span class="nepali">/महिना</span></div>
                    </div>

                    <ul class="pricing-features">
                        <li><i class="fas fa-check"></i> <span class="nepali">असीमित विद्यार्थी</span></li>
                        <li><i class="fas fa-check"></i> <span class="nepali">पूर्ण विद्यार्थी व्यवस्थापन</span></li>
                        <li><i class="fas fa-check"></i> <span class="nepali">बहु-होस्टल व्यवस्थापन</span></li>
                        <li><i class="fas fa-check"></i> <span class="nepali">कस्टम भुक्तानी प्रणाली</span></li>
                        <li><i class="fas fa-check"></i> <span class="nepali">विस्तृत विवरण र विश्लेषण</span></li>
                    </ul>

                    <a href="/contact" class="btn btn-secondary nepali">सम्पर्क गर्नुहोस्</a>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="cta">
        <div class="container">
            <div class="cta-content">
                <h2 class="nepali">निःशुल्क परीक्षण सुरु गर्नुहोस्</h2>

                <div class="trial-info">
                    <p class="nepali">७ दिनको निःशुल्क परीक्षण अवधिमा सबै सुविधाहरू अनलिमिटेड रूपमा प्रयोग गर्नुहोस्</p>
                    <p>Enjoy all features unlimited during 7 days free trial period</p>
                </div>

                <div class="hero-cta">
                    <a href="/login" class="btn btn-secondary nepali">लगइन गर्नुहोस्</a>
                    <a href="/signup" class="btn btn-primary nepali">निःशुल्क साइन अप गर्नुहोस्</a>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer>
        <div class="container">
            <div class="footer-grid">
                <div class="footer-col">
                    <div class="footer-logo">
                        <i class="fas fa-home"></i>
                        <span>HostelHub</span>
                    </div>
                    <p class="nepali" style="color: rgba(255,255,255,0.8); margin-top: 20px; line-height: 1.7;">
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
                        <li><a href="#pricing"><i class="fas fa-chevron-right"></i> <span class="nepali">मूल्य</span></a></li>
                        <li><a href="#testimonials"><i class="fas fa-chevron-right"></i> <span class="nepali">समीक्षाहरू</span></a></li>
                    </ul>
                </div>

                <div class="footer-col">
                    <h3 class="nepali">सम्पर्क जानकारी</h3>
                    <ul class="contact-info">
                        <li>
                            <i class="fas fa-map-marker-alt"></i>
                            <div class="nepali">कमलपोखरी, काठमाडौं, नेपाल</div>
                        </li>
                        <li>
                            <i class="fas fa-phone-alt"></i>
                            <div>+९७७ ९८०१२३४५६७</div>
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

                <div class="footer-col">
                    <h3 class="nepali">समाचारपत्र</h3>
                    <p class="nepali" style="color: rgba(255,255,255,0.8); margin-bottom: 20px; line-height: 1.7;">
                        हाम्रो नवीनतम अपडेटहरू प्राप्त गर्न तपाईंको इमेल दर्ता गर्नुहोस्
                    </p>
                    <form class="newsletter-form">
                        <input type="email" placeholder="तपाईंको इमेल" required aria-label="इमेल ठेगाना">
                        <button type="submit" class="nepali">दर्ता गर्नुहोस्</button>
                    </form>
                </div>

                <div class="copyright">
                    <p class="nepali">© 2025 HostelHub. सबै अधिकार सुरक्षित।</p>
                </div>
            </div>
        </div>
    </footer>

    <script>
        // Header scroll behavior
        window.addEventListener('scroll', () => {
            const headerInner = document.querySelector('.header-inner');
            if (window.scrollY > 40) {
                headerInner.classList.add('header-compact');
            } else {
                headerInner.classList.remove('header-compact');
            }
        });

        // Ensure header is visible on load
        document.addEventListener('DOMContentLoaded', () => {
            const header = document.getElementById('site-header');
            header.style.opacity = 1;
            header.style.visibility = 'visible';
            header.style.transform = 'none';

            // Animation on scroll
            const elements = document.querySelectorAll('.feature-card, .step, .gallery-item, .testimonial-card, .pricing-card');

            // Add initial styles for animation
            elements.forEach(el => {
                el.style.opacity = 0;
                el.style.transform = 'translateY(30px)';
                el.style.transition = 'opacity 0.8s ease, transform 0.8s ease';
            });

            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.style.opacity = 1;
                        entry.target.style.transform = 'translateY(0)';
                    }
                });
            }, { threshold: 0.1 });

            elements.forEach(el => observer.observe(el));

            // Floating animation for elements
            const floatingElements = document.querySelectorAll('.floating');
            floatingElements.forEach(el => {
                el.style.animation = 'float 6s ease-in-out infinite';
            });

            // Mobile menu toggle
            const menuBtn = document.querySelector('.mobile-menu-btn');
            const navLinks = document.querySelector('.nav-links');

            menuBtn.addEventListener('click', () => {
                navLinks.classList.toggle('show');
            });

            // Animated counters
            function animateCounter(elementId, finalValue, duration = 2000) {
                const element = document.getElementById(elementId);
                let start = 0;
                const increment = Math.ceil(finalValue / (duration / 16)); // 16ms per frame ≈ 60fps

                const timer = setInterval(() => {
                    start += increment;
                    if (start >= finalValue) {
                        element.textContent = finalValue;
                        clearInterval(timer);
                    } else {
                        element.textContent = start;
                    }
                }, 16);
            }

            // Initialize counters when they come into view
            const counterObserver = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        animateCounter('students-counter', 125);
                        animateCounter('hostels-counter', 24);
                        animateCounter('cities-counter', 5);
                        counterObserver.disconnect();
                    }
                });
            }, { threshold: 0.5 });

            counterObserver.observe(document.querySelector('.hero-stats'));

            // Gallery filter
            const filterButtons = document.querySelectorAll('.filter-btn');
            filterButtons.forEach(button => {
                button.addEventListener('click', () => {
                    filterButtons.forEach(btn => btn.classList.remove('active'));
                    button.classList.add('active');
                });
            });

            // Gallery lightbox
            const lightbox = document.getElementById('lightbox');
            const lightboxImg = document.getElementById('lightbox-img');
            const lightboxCaption = document.getElementById('caption');
            const closeBtn = document.querySelector('.close-btn');
            const galleryItems = document.querySelectorAll('.gallery-item');

            galleryItems.forEach(item => {
                item.addEventListener('click', () => {
                    const imgSrc = item.querySelector('img').src;
                    const imgAlt = item.querySelector('img').alt;
                    const caption = item.querySelector('.gallery-caption').textContent;

                    lightboxImg.src = imgSrc;
                    lightboxImg.alt = imgAlt;
                    lightboxCaption.textContent = caption;
                    lightbox.classList.add('active');
                    document.body.style.overflow = 'hidden';
                });
            });

            closeBtn.addEventListener('click', () => {
                lightbox.classList.remove('active');
                document.body.style.overflow = '';
            });

            lightbox.addEventListener('click', (e) => {
                if (e.target === lightbox) {
                    lightbox.classList.remove('active');
                    document.body.style.overflow = '';
                }
            });

            // Booking form validation
            const bookingForm = document.getElementById('booking-form');
            const checkinDate = document.getElementById('checkin-date');
            const checkoutDate = document.getElementById('checkout-date');

            function validateDates() {
                if (!checkinDate.value || !checkoutDate.value) return true;

                const checkin = new Date(checkinDate.value);
                const checkout = new Date(checkoutDate.value);

                return checkin < checkout;
            }

            bookingForm.addEventListener('submit', (e) => {
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
                    formGroup.querySelector('.error-message').textContent =
                        'चेक-आउट मिति चेक-इन मिति भन्दा पछि हुनुपर्छ';
                    isValid = false;
                }

                if (!isValid) {
                    e.preventDefault();
                }
            });

            // Date validation on input
            [checkinDate, checkoutDate].forEach(dateInput => {
                dateInput.addEventListener('change', () => {
                    if (checkinDate.value && checkoutDate.value) {
                        const formGroup = checkoutDate.closest('.form-group');
                        if (!validateDates()) {
                            formGroup.classList.add('error');
                            formGroup.querySelector('.error-message').textContent =
                                'चेक-आउट मिति चेक-इन मिति भन्दा पछि हुनुपर्छ';
                        } else {
                            formGroup.classList.remove('error');
                        }
                    }
                });
            });
        });
    </script>
</body>
</html>
