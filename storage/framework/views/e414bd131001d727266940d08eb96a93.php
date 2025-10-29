<!DOCTYPE html>
<html lang="ne" dir="ltr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>हाम्रो खानाको ग्यालरी - HostelHub</title>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Devanagari:wght@400;500;600;700&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
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
        
        /* Header Styles */
        #site-header {
            position: sticky;
            top: 0;
            width: 100%;
            z-index: 1000;
            background: var(--primary);
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            height: var(--header-height);
        }
        
        .header-inner {
            padding: 0.5rem 0;
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
        }
        
        .logo {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            text-decoration: none;
            font-weight: 700;
            font-size: 1.3rem;
            color: var(--text-light);
        }
        
        .logo img {
            width: 50px;
            height: 50px;
            border-radius: 8px;
        }
        
        .logo-text {
            display: flex;
            flex-direction: column;
        }
        
        .logo-text h1 {
            font-size: 1.3rem;
            line-height: 1.2;
            margin: 0;
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
        
        .nav-links a:hover {
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
        
        .nav-links a:hover::after {
            width: 100%;
        }
        
        /* Hero Section */
        .hero {
            background: linear-gradient(rgba(0, 0, 0, 0.7), rgba(0, 0, 0, 0.7)), url('https://images.unsplash.com/photo-1504754524776-8f4f37790ca0?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1400&q=80');
            background-size: cover;
            background-position: center;
            padding: 5rem 0;
            text-align: center;
            color: white;
        }
        
        .hero h1 {
            font-size: 2.5rem;
            margin-bottom: 1rem;
            text-shadow: 0 2px 4px rgba(0,0,0,0.5);
        }
        
        .hero p {
            font-size: 1.2rem;
            max-width: 700px;
            margin: 0 auto 2rem;
            opacity: 0.9;
        }
        
        /* Search Bar */
        .search-bar {
            max-width: 600px;
            margin: 0 auto;
            position: relative;
        }
        
        .search-bar input {
            width: 100%;
            padding: 1rem 1.5rem;
            border-radius: 50px;
            border: none;
            font-size: 1rem;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }
        
        .search-bar button {
            position: absolute;
            right: 8px;
            top: 8px;
            background: var(--secondary);
            border: none;
            color: white;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            cursor: pointer;
            transition: var(--transition);
        }
        
        .search-bar button:hover {
            background: var(--secondary-dark);
        }
        
        /* Filters */
        .filters {
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
            gap: 1rem;
            margin: 2rem 0;
            padding: 0 1rem;
        }
        
        .filter-btn {
            padding: 0.7rem 1.5rem;
            border: 2px solid var(--primary);
            background: white;
            color: var(--primary);
            border-radius: 50px;
            cursor: pointer;
            transition: var(--transition);
            font-weight: 600;
        }
        
        .filter-btn:hover, .filter-btn.active {
            background: var(--primary);
            color: white;
        }
        
        /* Food Gallery */
        .food-gallery {
            padding: 3rem 0;
        }
        
        .food-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 2rem;
            padding: 1rem;
        }
        
        .food-card {
            background: white;
            border-radius: var(--radius);
            overflow: hidden;
            box-shadow: var(--shadow);
            transition: var(--transition);
        }
        
        .food-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
        }
        
        .food-image {
            height: 200px;
            overflow: hidden;
            position: relative;
        }
        
        .food-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.5s ease;
        }
        
        .food-card:hover .food-image img {
            transform: scale(1.05);
        }
        
        .food-badge {
            position: absolute;
            top: 1rem;
            left: 1rem;
            background: var(--accent);
            color: white;
            padding: 0.3rem 0.8rem;
            border-radius: 50px;
            font-size: 0.8rem;
            font-weight: 600;
        }
        
        .food-content {
            padding: 1.5rem;
        }
        
        .food-title {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 0.5rem;
        }
        
        .food-title h3 {
            font-size: 1.3rem;
            color: var(--primary);
        }
        
        .food-hostel {
            color: var(--secondary);
            font-weight: 600;
            margin-bottom: 0.5rem;
            display: block;
        }
        
        .food-day {
            color: #6b7280;
            margin-bottom: 1rem;
            display: block;
        }
        
        .food-description {
            color: var(--text-dark);
            margin-bottom: 1.5rem;
            line-height: 1.6;
        }
        
        .food-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .food-rating {
            display: flex;
            align-items: center;
            gap: 0.3rem;
            color: #f59e0b;
            font-weight: 600;
        }
        
        .food-time {
            color: #6b7280;
            font-size: 0.9rem;
        }
        
        /* Footer */
        footer {
            background-color: var(--primary);
            color: var(--text-light);
            padding: 3rem 0 1rem;
            margin-top: 3rem;
        }
        
        .footer-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 2rem;
            margin-bottom: 2rem;
        }
        
        .footer-col h3 {
            font-size: 1.3rem;
            margin-bottom: 1rem;
            position: relative;
            padding-bottom: 0.5rem;
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
        
        .footer-logo {
            display: flex;
            align-items: center;
            gap: 0.6rem;
            font-size: 1.6rem;
            font-weight: 700;
            margin-bottom: 1rem;
            color: var(--text-light);
        }
        
        .footer-logo img {
            width: 40px;
            height: 40px;
            border-radius: 8px;
        }
        
        .footer-about {
            color: rgba(249, 250, 251, 0.8);
            line-height: 1.6;
            margin-bottom: 1.5rem;
        }
        
        .social-links {
            display: flex;
            gap: 0.8rem;
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
            transform: translateY(-3px);
        }
        
        .footer-links {
            list-style: none;
        }
        
        .footer-links li {
            margin-bottom: 0.8rem;
        }
        
        .footer-links a {
            color: rgba(249, 250, 251, 0.8);
            text-decoration: none;
            transition: var(--transition);
            display: flex;
            align-items: center;
        }
        
        .footer-links a:hover {
            color: var(--secondary);
            transform: translateX(5px);
        }
        
        .footer-links i {
            margin-right: 10px;
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
            margin-right: 12px;
            color: var(--secondary);
            font-size: 1.1rem;
            margin-top: 4px;
        }
        
        .newsletter-form {
            display: flex;
            gap: 0.5rem;
            margin-top: 1rem;
        }
        
        .newsletter-form input {
            flex: 1;
            padding: 0.7rem 1rem;
            border: none;
            border-radius: var(--radius);
            font-family: inherit;
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
        }
        
        .newsletter-form button:hover {
            background: var(--secondary-dark);
        }
        
        .copyright {
            text-align: center;
            padding-top: 1.5rem;
            border-top: 1px solid rgba(249, 250, 251, 0.1);
            color: rgba(249, 250, 251, 0.6);
        }
        
        /* Responsive Design */
        @media (max-width: 768px) {
            .nav-links {
                display: none;
            }
            
            .hero h1 {
                font-size: 2rem;
            }
            
            .hero p {
                font-size: 1rem;
            }
            
            .filters {
                overflow-x: auto;
                justify-content: flex-start;
                padding-bottom: 0.5rem;
            }
            
            .food-grid {
                grid-template-columns: 1fr;
            }
            
            .footer-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <!-- Header -->
    <header id="site-header">
        <div class="header-inner">
            <div class="container">
                <div class="navbar">
                    <a href="/" class="logo">
                        <!-- Real Logo Image -->
                        <img src="<?php echo e(asset('storage/images/logo.png')); ?>" alt="HostelHub Logo" style="height: 50px; width: auto;">
                        <div class="logo-text">
                            <h1>HostelHub</h1>
                            <span class="nepali">होस्टल प्रबन्धन</span>
                        </div>
                    </a>
                    <div class="nav-links">
                        <a href="<?php echo e(route('features')); ?>" class="nepali">सुविधाहरू</a>
                        <a href="<?php echo e(route('how-it-works')); ?>" class="nepali">कसरी काम गर्छ</a>
                        <a href="<?php echo e(route('gallery.public')); ?>" class="nepali">ग्यालरी</a>
                        <a href="<?php echo e(route('pricing')); ?>" class="nepali">मूल्य</a>
                        <a href="<?php echo e(route('reviews')); ?>" class="nepali">समीक्षाहरू</a>
                        <a href="/login" class="nepali">लगइन</a>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- Hero Section -->
    <section class="hero">
        <div class="container">
            <h1 class="nepali">हाम्रो खानाको ग्यालरी</h1>
            <p class="nepali">हाम्रा होस्टलहरूले ताजा, स्वस्थ र स्वादिष्ट खाना तयार गर्छन्</p>
            <div class="search-bar">
                <input type="text" placeholder="खाना वा होस्टलको नामले खोज्नुहोस्..." class="nepali">
                <button><i class="fas fa-search"></i></button>
            </div>
        </div>
    </section>

    <!-- Filters -->
    <div class="container">
        <div class="filters">
            <button class="filter-btn active nepali">सबै</button>
            <button class="filter-btn nepali">विहानको खाना</button>
            <button class="filter-btn nepali">दिउसोको खाना</button>
            <button class="filter-btn nepali">बेलुकाको खाना</button>
            <button class="filter-btn nepali">साप्ताहिक विशेष</button>
        </div>
    </div>

    <!-- Food Gallery -->
    <section class="food-gallery">
        <div class="container">
            <div class="food-grid">
                <!-- Food Card 1 -->
                <div class="food-card">
                    <div class="food-image">
                        <span class="food-badge nepali">बेलुकाको खाना</span>
                        <img src="https://images.unsplash.com/photo-1603894584373-5ac82b2ae398?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=600&q=80" alt="मोमो">
                    </div>
                    <div class="food-content">
                        <div class="food-title">
                            <h3 class="nepali">मोमो</h3>
                            <div class="food-rating">
                                <i class="fas fa-star"></i>
                                <span>4.5</span>
                            </div>
                        </div>
                        <span class="food-hostel nepali">काठमाडौं होस्टल</span>
                        <span class="food-day nepali">सोमबार</span>
                        <p class="food-description nepali">पकौडा, अचार, सुप, चिया</p>
                        <div class="food-footer">
                            <span class="food-time nepali">६:०० - ८:०० बजे</span>
                        </div>
                    </div>
                </div>

                <!-- Food Card 2 -->
                <div class="food-card">
                    <div class="food-image">
                        <span class="food-badge nepali">दिउसोको खाना</span>
                        <img src="https://images.unsplash.com/photo-1513104890138-7c749659a591?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=600&q=80" alt="पिज्जा">
                    </div>
                    <div class="food-content">
                        <div class="food-title">
                            <h3 class="nepali">पिज्जा</h3>
                            <div class="food-rating">
                                <i class="fas fa-star"></i>
                                <span>4.0</span>
                            </div>
                        </div>
                        <span class="food-hostel nepali">पोखरा होस्टल</span>
                        <span class="food-day nepali">मंगलबार</span>
                        <p class="food-description nepali">मकै, कैप्सिकम, प्याज, चिज</p>
                        <div class="food-footer">
                            <span class="food-time nepali">१२:०० - २:०० बजे</span>
                        </div>
                    </div>
                </div>

                <!-- Food Card 3 -->
                <div class="food-card">
                    <div class="food-image">
                        <span class="food-badge nepali">विहानको खाना</span>
                        <img src="https://images.unsplash.com/photo-1631515243349-e0cb75fb8d3a?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=600&q=80" alt="थुक्पा">
                    </div>
                    <div class="food-content">
                        <div class="food-title">
                            <h3 class="nepali">थुक्पा</h3>
                            <div class="food-rating">
                                <i class="fas fa-star"></i>
                                <span>4.8</span>
                            </div>
                        </div>
                        <span class="food-hostel nepali">ललितपुर होस्टल</span>
                        <span class="food-day nepali">बुधबार</span>
                        <p class="food-description nepali">चाउचाउ, मासु, सब्जी, सुप</p>
                        <div class="food-footer">
                            <span class="food-time nepali">७:०० - ९:०० बजे</span>
                        </div>
                    </div>
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
                        <!-- Real Logo Image -->
                        <img src="<?php echo e(asset('storage/images/logo.png')); ?>" alt="HostelHub Logo" style="height: 146px; width: auto;">
                        <span>HostelHub</span>
                    </div>
                    <p class="footer-about nepali">नेपालको नम्बर १ होस्टल प्रबन्धन प्रणाली। हामी होस्टल व्यवस्थापनलाई सहज, दक्ष र विश्वसनीय बनाउँछौं।</p>
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
                        <li><a href="/"><i class="fas fa-chevron-right"></i> <span class="nepali">होम</span></a></li>
                        <li><a href="<?php echo e(route('features')); ?>"><i class="fas fa-chevron-right"></i> <span class="nepali">सुविधाहरू</span></a></li>
                        <li><a href="<?php echo e(route('how-it-works')); ?>"><i class="fas fa-chevron-right"></i> <span class="nepali">कसरी काम गर्छ</span></a></li>
                        <li><a href="<?php echo e(route('gallery.public')); ?>"><i class="fas fa-chevron-right"></i> <span class="nepali">ग्यालरी</span></a></li>
                        <li><a href="<?php echo e(route('pricing')); ?>"><i class="fas fa-chevron-right"></i> <span class="nepali">मूल्य</span></a></li>
                        <li><a href="<?php echo e(route('reviews')); ?>"><i class="fas fa-chevron-right"></i> <span class="nepali">समीक्षाहरू</span></a></li>
                    </ul>
                </div>
                <div class="footer-col">
                    <h3 class="nepali">सम्पर्क जानकारी</h3>
                    <ul class="contact-info">
                        <li>
                            <i class="fas fa-map-marker-alt" aria-hidden="true"></i>
                            <span class="nepali">कमलपोखरी, काठमाडौं, नेपाल</span>
                        </li>
                        <li>
                            <i class="fas fa-phone-alt" aria-hidden="true"></i>
                            <span>+९७७ ९८०१२३४५६७</span>
                        </li>
                        <li>
                            <i class="fas fa-envelope" aria-hidden="true"></i>
                            <span>info@hostelhub.com</span>
                        </li>
                        <li>
                            <i class="fas fa-clock" aria-hidden="true"></i>
                            <span class="nepali">सोम-शुक्र: ९:०० बिहान - ५:०० बेलुका</span>
                        </li>
                    </ul>
                </div>
                <div class="footer-col">
                    <h3 class="nepali">समाचारपत्र</h3>
                    <p class="nepali">हाम्रो नवीनतम अपडेटहरू प्राप्त गर्न तपाईंको इमेल दर्ता गर्नुहोस्</p>
                    <form class="newsletter-form" action="/subscribe" method="POST">
                        <?php echo csrf_field(); ?>
                        <input type="email" name="email" placeholder="तपाईंको इमेल" required aria-label="इमेल ठेगाना" class="nepali">
                        <input type="text" name="honeypot" style="display:none" aria-hidden="true">
                        <button type="submit" class="nepali">दर्ता गर्नुहोस्</button>
                    </form>
                </div>
            </div>
            <div class="copyright">
                <p class="nepali">© २०२५ HostelHub. सबै अधिकार सुरक्षित।</p>
            </div>
        </div>
    </footer>

    <script>
        // Filter functionality
        document.addEventListener('DOMContentLoaded', function() {
            const filterButtons = document.querySelectorAll('.filter-btn');
            
            filterButtons.forEach(button => {
                button.addEventListener('click', function() {
                    // Remove active class from all buttons
                    filterButtons.forEach(btn => btn.classList.remove('active'));
                    
                    // Add active class to clicked button
                    this.classList.add('active');
                    
                    // Here you would filter the food items based on the selected category
                    // This is just a placeholder for the actual filtering logic
                    console.log('Filtering by: ' + this.textContent);
                });
            });
        });
    </script>
</body>
</html><?php /**PATH D:\My Projects\HostelHub\resources\views\frontend\pages\meal-gallery.blade.php ENDPATH**/ ?>