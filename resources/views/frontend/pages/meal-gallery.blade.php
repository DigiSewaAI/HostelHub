<!DOCTYPE html>
<html lang="ne" dir="ltr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>हाम्रो खानाको ग्यालरी - HostelHub</title>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Devanagari:wght@400;500;600;700&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Vite CSS Integration -->
    @vite(['resources/css/app.css', 'resources/css/gallery.css', 'resources/css/public-themes.css'])
</head>
<body>
    <!-- Header -->
    <header id="site-header">
        <div class="header-inner">
            <div class="container">
                <div class="navbar">
                    <a href="/" class="logo">
                        <!-- Real Logo Image -->
                        <img src="{{ asset('storage/images/logo.png') }}" alt="HostelHub Logo" style="height: 50px; width: auto;">
                        <div class="logo-text">
                            <h1>HostelHub</h1>
                            <span class="nepali">होस्टल प्रबन्धन</span>
                        </div>
                    </a>
                    <div class="nav-links">
                        <a href="{{ route('features') }}" class="nepali">सुविधाहरू</a>
                        <a href="{{ route('how-it-works') }}" class="nepali">कसरी काम गर्छ</a>
                        <a href="{{ route('gallery.public') }}" class="nepali">ग्यालरी</a>
                        <a href="{{ route('pricing') }}" class="nepali">मूल्य</a>
                        <a href="{{ route('reviews') }}" class="nepali">समीक्षाहरू</a>
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
                        <img src="{{ asset('storage/images/logo.png') }}" alt="HostelHub Logo" style="height: 146px; width: auto;">
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
                        @csrf
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

    <!-- Vite JS Integration -->
    @vite(['resources/js/app.js'])
    
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
</html>