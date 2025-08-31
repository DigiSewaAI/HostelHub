<!DOCTYPE html>
<html lang="ne">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>खानाको ग्यालरी - HostelHub</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Devanagari:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-color: #3b82f6;
            --secondary-color: #2563eb;
            --accent-color: #f59e0b;
            --light-bg: #f8fafc;
            --dark-text: #1e293b;
        }
        
        body {
            font-family: 'Noto Sans Devanagari', sans-serif;
            background-color: var(--light-bg);
            color: var(--dark-text);
            padding-top: 80px;
        }
        
        .navbar {
            background: linear-gradient(90deg, var(--primary-color), var(--secondary-color));
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }
        
        .hero-section {
            background: linear-gradient(rgba(0, 0, 0, 0.7), rgba(0, 0, 0, 0.7)), url('https://images.unsplash.com/photo-1504754524776-8f4f37790ca0?ixlib=rb-4.0.3') center/cover;
            padding: 80px 0;
            color: white;
            border-radius: 0 0 20px 20px;
            margin-bottom: 40px;
        }
        
        .meal-card {
            border-radius: 12px;
            overflow: hidden;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            border: none;
            height: 100%;
        }
        
        .meal-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 12px 20px rgba(0, 0, 0, 0.15);
        }
        
        .meal-image {
            height: 220px;
            object-fit: cover;
            width: 100%;
        }
        
        .meal-type-badge {
            position: absolute;
            top: 15px;
            right: 15px;
            background: var(--accent-color);
            color: white;
            padding: 5px 12px;
            border-radius: 20px;
            font-weight: 600;
            font-size: 0.85rem;
        }
        
        .day-badge {
            background: var(--primary-color);
            color: white;
            padding: 4px 10px;
            border-radius: 6px;
            font-size: 0.8rem;
            display: inline-block;
            margin-bottom: 10px;
        }
        
        .filter-buttons {
            margin-bottom: 30px;
        }
        
        .filter-btn {
            border: 2px solid var(--primary-color);
            background: transparent;
            color: var(--primary-color);
            padding: 8px 20px;
            border-radius: 30px;
            margin: 0 5px 10px;
            transition: all 0.3s ease;
        }
        
        .filter-btn:hover, .filter-btn.active {
            background: var(--primary-color);
            color: white;
        }
        
        .footer {
            background: #1e293b;
            color: white;
            padding: 50px 0 20px;
            margin-top: 80px;
        }
        
        .social-icons a {
            color: white;
            font-size: 1.2rem;
            margin: 0 10px;
            transition: color 0.3s ease;
        }
        
        .social-icons a:hover {
            color: var(--accent-color);
        }
        
        .hostel-name {
            color: var(--primary-color);
            font-weight: 600;
        }
        
        .no-meals {
            padding: 60px 20px;
            text-align: center;
            background: white;
            border-radius: 12px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
        }
        
        .no-meals i {
            font-size: 4rem;
            color: #cbd5e1;
            margin-bottom: 20px;
        }
        
        .search-box {
            max-width: 500px;
            margin: 0 auto 30px;
        }
        
        .nutrition-info {
            display: flex;
            justify-content: space-between;
            margin-top: 15px;
            font-size: 0.85rem;
        }
        
        .nutrition-item {
            text-align: center;
            flex: 1;
        }
        
        .nutrition-value {
            font-weight: 600;
            color: var(--primary-color);
        }
        
        .rating {
            color: #f59e0b;
            margin-bottom: 10px;
        }
        
        @media (max-width: 768px) {
            .hero-section {
                padding: 60px 0;
            }
            
            .meal-card {
                margin-bottom: 20px;
            }
        }
    </style>
</head>
<body>
    <!-- Navigation Bar -->
    <nav class="navbar navbar-expand-lg navbar-dark fixed-top">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center" href="#">
                <i class="fas fa-utensils me-2"></i>
                <span class="fw-bold">HostelHub</span>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link active" href="#">होम</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">सुविधाहरू</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">कसरी काम गर्छ</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">ग्यालरी</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">समीक्षाहरू</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link btn btn-light text-primary btn-sm ms-2" href="#">लगइन</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <div class="hero-section">
        <div class="container text-center">
            <h1 class="display-4 fw-bold mb-3">हाम्रो खानाको ग्यालरी</h1>
            <p class="lead mb-4">हाम्रा होस्टलहरूले ताजा, स्वस्थ र स्वादिष्ट खाना तयार गर्छन्</p>
            <div class="search-box">
                <div class="input-group">
                    <input type="text" class="form-control" placeholder="खाना वा होस्टलको नामले खोज्नुहोस्...">
                    <button class="btn btn-primary">
                        <i class="fas fa-search me-1"></i> खोज्नुहोस्
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="container mb-5">
        <!-- Filter Buttons -->
        <div class="filter-buttons text-center">
            <button class="filter-btn active">सबै</button>
            <button class="filter-btn">विहानको खाना</button>
            <button class="filter-btn">दिउसोको खाना</button>
            <button class="filter-btn">बेलुकाको खाना</button>
            <button class="filter-btn">साप्ताहिक विशेष</button>
        </div>

        <!-- Meal Gallery -->
        <div class="row">
            <!-- Example Meal Cards (would be dynamically generated in real application) -->
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="meal-card card">
                    <div class="position-relative">
                        <img src="https://images.unsplash.com/photo-1555939594-58d7cb561ad1?ixlib=rb-4.0.3" class="meal-image" alt="मोमो">
                        <span class="meal-type-badge">बेलुकाको खाना</span>
                    </div>
                    <div class="card-body">
                        <span class="day-badge">सोमबार</span>
                        <h5 class="card-title">काठमाडौं होस्टल</h5>
                        <p class="card-text">
                            <strong>मोमो:</strong><br>
                            पकौडा, अचार, सुप, चिया
                        </p>
                        <div class="nutrition-info">
                            <div class="nutrition-item">
                                <div class="nutrition-value">३५०</div>
                                <div>क्यालोरी</div>
                            </div>
                            <div class="nutrition-item">
                                <div class="nutrition-value">१८g</div>
                                <div>प्रोटिन</div>
                            </div>
                            <div class="nutrition-item">
                                <div class="nutrition-value">४५g</div>
                                <div>कार्बोहाइड्रेट</div>
                            </div>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mt-3">
                            <div class="rating">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star-half-alt"></i>
                                <span class="ms-1">(४.५)</span>
                            </div>
                            <small class="text-muted"><i class="fas fa-clock me-1"></i> ६:०० - ८:०० बजे</small>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 col-md-6 mb-4">
                <div class="meal-card card">
                    <div class="position-relative">
                        <img src="https://images.unsplash.com/photo-1565299624946-b28f40a0ae38?ixlib=rb-4.0.3" class="meal-image" alt="पिज्जा">
                        <span class="meal-type-badge">दिउसोको खाना</span>
                    </div>
                    <div class="card-body">
                        <span class="day-badge">मंगलबार</span>
                        <h5 class="card-title">पोखरा होस्टल</h5>
                        <p class="card-text">
                            <strong>पिज्जा:</strong><br>
                            मकै, कैप्सिकम, प्याज, चिज
                        </p>
                        <div class="nutrition-info">
                            <div class="nutrition-item">
                                <div class="nutrition-value">४२०</div>
                                <div>क्यालोरी</div>
                            </div>
                            <div class="nutrition-item">
                                <div class="nutrition-value">२२g</div>
                                <div>प्रोटिन</div>
                            </div>
                            <div class="nutrition-item">
                                <div class="nutrition-value">५०g</div>
                                <div>कार्बोहाइड्रेट</div>
                            </div>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mt-3">
                            <div class="rating">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="far fa-star"></i>
                                <span class="ms-1">(४.०)</span>
                            </div>
                            <small class="text-muted"><i class="fas fa-clock me-1"></i> १२:०० - २:०० बजे</small>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 col-md-6 mb-4">
                <div class="meal-card card">
                    <div class="position-relative">
                        <img src="https://images.unsplash.com/photo-1563245372-f21724e3856d?ixlib=rb-4.0.3" class="meal-image" alt="थुक्पा">
                        <span class="meal-type-badge">विहानको खाना</span>
                    </div>
                    <div class="card-body">
                        <span class="day-badge">बुधबार</span>
                        <h5 class="card-title">ललितपुर होस्टल</h5>
                        <p class="card-text">
                            <strong>थुक्पा:</strong><br>
                            चाउचाउ, मासु, सब्जी, सुप
                        </p>
                        <div class="nutrition-info">
                            <div class="nutrition-item">
                                <div class="nutrition-value">३८०</div>
                                <div>क्यालोरी</div>
                            </div>
                            <div class="nutrition-item">
                                <div class="nutrition-value">२०g</div>
                                <div>प्रोटिन</div>
                            </div>
                            <div class="nutrition-item">
                                <div class="nutrition-value">४२g</div>
                                <div>कार्बोहाइड्रेट</div>
                            </div>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mt-3">
                            <div class="rating">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <span class="ms-1">(४.८)</span>
                            </div>
                            <small class="text-muted"><i class="fas fa-clock me-1"></i> ७:०० - ९:०० बजे</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- View More Button -->
        <div class="text-center mt-4">
            <button class="btn btn-primary btn-lg">
                थप खानाहरू हेर्नुहोस् <i class="fas fa-arrow-right ms-2"></i>
            </button>
        </div>
    </div>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="row">
                <div class="col-lg-4 col-md-6 mb-4">
                    <h5 class="mb-4">HostelHub</h5>
                    <p>नेपालको नम्बर १ होस्टल प्रबन्धन प्रणाली। हामी होस्टल व्यवस्थापनलाई सहज, दक्ष र विश्वसनीय बनाउँछौं।</p>
                    <div class="social-icons mt-4">
                        <a href="#"><i class="fab fa-facebook-f"></i></a>
                        <a href="#"><i class="fab fa-instagram"></i></a>
                        <a href="#"><i class="fab fa-twitter"></i></a>
                        <a href="#"><i class="fab fa-youtube"></i></a>
                    </div>
                </div>
                <div class="col-lg-2 col-md-6 mb-4">
                    <h5 class="mb-4">तिब्र लिङ्कहरू</h5>
                    <ul class="list-unstyled">
                        <li class="mb-2"><a href="#" class="text-white text-decoration-none">होम</a></li>
                        <li class="mb-2"><a href="#" class="text-white text-decoration-none">सुविधाहरू</a></li>
                        <li class="mb-2"><a href="#" class="text-white text-decoration-none">कसरी काम गर्छ</a></li>
                        <li class="mb-2"><a href="#" class="text-white text-decoration-none">ग्यालरी</a></li>
                    </ul>
                </div>
                <div class="col-lg-3 col-md-6 mb-4">
                    <h5 class="mb-4">सम्पर्क जानकारी</h5>
                    <ul class="list-unstyled">
                        <li class="mb-2"><i class="fas fa-map-marker-alt me-2"></i> कमलपोखरी, काठमाडौं, नेपाल</li>
                        <li class="mb-2"><i class="fas fa-phone me-2"></i> +९७७ ९८०१२३४५६७</li>
                        <li class="mb-2"><i class="fas fa-envelope me-2"></i> info@hostelhub.com</li>
                        <li class="mb-2"><i class="fas fa-clock me-2"></i> सोम-शुक्र: ९:०० बिहान - ५:०० बेलुका</li>
                    </ul>
                </div>
                <div class="col-lg-3 col-md-6 mb-4">
                    <h5 class="mb-4">समाचारपत्र</h5>
                    <p>हाम्रो नवीनतम अपडेटहरू प्राप्त गर्न तपाईंको इमेल दर्ता गर्नुहोस्</p>
                    <div class="input-group mb-3">
                        <input type="email" class="form-control" placeholder="तपाईंको इमेल">
                        <button class="btn btn-primary">पठाउनुहोस्</button>
                    </div>
                </div>
            </div>
            <hr class="mt-4 mb-4">
            <div class="text-center">
                <p>© २०२५ HostelHub. सबै अधिकार सुरक्षित।</p>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
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
                    
                    // In a real application, this would filter the meal cards
                    console.log('Filtering by: ' + this.textContent);
                });
            });
            
            // Search functionality
            const searchInput = document.querySelector('.search-box input');
            const searchButton = document.querySelector('.search-box button');
            
            searchButton.addEventListener('click', function() {
                if (searchInput.value.trim() !== '') {
                    console.log('Searching for: ' + searchInput.value);
                }
            });
            
            searchInput.addEventListener('keypress', function(e) {
                if (e.key === 'Enter' && this.value.trim() !== '') {
                    console.log('Searching for: ' + this.value);
                }
            });
        });
    </script>
</body>
</html>