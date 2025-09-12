<!DOCTYPE html>
<html lang="ne">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>होस्टल ग्यालरी - HostelHub</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Noto+Sans+Devanagari:wght@400;500;600;700&display=swap');
        
        :root {
            --primary: #3730a3;
            --primary-dark: #312e81;
            --primary-light: #4f46e5;
            --secondary: #f97316;
            --dark: #1f2937;
            --light: #f8fafc;
        }
        
        body {
            font-family: 'Noto Sans Devanagari', sans-serif;
            background-color: #f8fafc;
            scroll-behavior: smooth;
        }
        
        .header {
            background: linear-gradient(to right, var(--primary-dark), var(--primary));
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
            position: sticky;
            top: 0;
            z-index: 100;
        }
        
        .logo {
            height: 45px;
            width: auto;
            transition: all 0.3s ease;
        }
        
        .logo:hover {
            transform: scale(1.05);
        }
        
        .nav-link {
            position: relative;
            padding: 0.5rem 0;
            font-weight: 500;
            transition: all 0.3s ease;
        }
        
        .nav-link::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 0;
            height: 2px;
            background-color: white;
            transition: width 0.3s ease;
        }
        
        .nav-link:hover::after, .nav-link.active::after {
            width: 100%;
        }
        
        .gallery-filter-btn {
            transition: all 0.3s ease;
            border: 1px solid #e5e7eb;
            font-weight: 500;
        }
        
        .gallery-filter-btn.active, .gallery-filter-btn:hover {
            background-color: var(--primary);
            color: white;
            border-color: var(--primary);
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(55, 48, 163, 0.2);
        }
        
        .gallery-item {
            transition: all 0.3s ease;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
            background: white;
            height: 100%;
            display: flex;
            flex-direction: column;
        }
        
        .gallery-item:hover {
            transform: translateY(-8px);
            box-shadow: 0 12px 20px rgba(0, 0, 0, 0.15);
        }
        
        .gallery-img-container {
            position: relative;
            overflow: hidden;
            height: 220px;
        }
        
        .gallery-img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.5s ease;
        }
        
        .gallery-item:hover .gallery-img {
            transform: scale(1.05);
        }
        
        .gallery-overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(to top, rgba(0,0,0,0.7) 0%, rgba(0,0,0,0) 60%);
            opacity: 0;
            transition: opacity 0.3s ease;
            display: flex;
            align-items: flex-end;
            padding: 16px;
        }
        
        .gallery-item:hover .gallery-overlay {
            opacity: 1;
        }
        
        .video-thumbnail {
            position: relative;
            cursor: pointer;
            height: 220px;
            overflow: hidden;
        }
        
        .video-thumbnail::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.3);
            opacity: 0;
            transition: opacity 0.3s ease;
        }
        
        .video-thumbnail:hover::after {
            opacity: 1;
        }
        
        .play-icon {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            color: white;
            font-size: 3.5rem;
            opacity: 0.9;
            z-index: 2;
            transition: all 0.3s ease;
            pointer-events: none;
        }
        
        .video-thumbnail:hover .play-icon {
            transform: translate(-50%, -50%) scale(1.1);
        }
        
        .category-badge {
            font-size: 0.75rem;
            font-weight: 600;
            border-radius: 9999px;
            padding: 0.25rem 0.75rem;
            display: inline-block;
            margin-bottom: 0.5rem;
        }
        
        .stat-card {
            background: white;
            border-radius: 12px;
            padding: 1.5rem;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.06);
            transition: all 0.3s ease;
            text-align: center;
        }
        
        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1);
        }
        
        .stat-number {
            font-size: 2.5rem;
            font-weight: 700;
            color: var(--primary);
            margin-bottom: 0.5rem;
            background: linear-gradient(to right, var(--primary), var(--primary-light));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        
        .cta-section {
            background: linear-gradient(to right, var(--primary-dark), var(--primary));
            border-radius: 16px;
            overflow: hidden;
            position: relative;
        }
        
        .cta-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100" width="100" height="100" opacity="0.05"><text x="50%" y="50%" font-size="16" text-anchor="middle" dominant-baseline="middle" fill="white">HostelHub</text></svg>');
            background-repeat: repeat;
        }
        
        .footer {
            background: linear-gradient(to right, #1e1b4b, var(--primary-dark));
        }
        
        .footer-links a {
            transition: all 0.3s ease;
        }
        
        .footer-links a:hover {
            color: white;
            transform: translateX(5px);
        }
        
        .search-box {
            position: relative;
            max-width: 2xl;
        }
        
        .search-input {
            width: 100%;
            padding: 0.75rem 1rem;
            border: 1px solid #e5e7eb;
            border-radius: 10px;
            transition: all 0.3s ease;
            padding-right: 3rem;
        }
        
        .search-input:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.2);
        }
        
        .search-icon {
            position: absolute;
            right: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: #9ca3af;
        }
        
        .modal {
            transition: opacity 0.3s ease;
            display: flex;
            opacity: 0;
            pointer-events: none;
        }
        
        .modal-content {
            transform: scale(0.9);
            transition: transform 0.3s ease;
        }
        
        .modal.open {
            opacity: 1;
            pointer-events: auto;
        }
        
        .modal.open .modal-content {
            transform: scale(1);
        }
        
        .spinner {
            width: 40px;
            height: 40px;
            border: 4px solid rgba(255, 255, 255, 0.3);
            border-radius: 50%;
            border-top-color: white;
            animation: spin 1s ease-in-out infinite;
        }
        
        @keyframes spin {
            to { transform: rotate(360deg); }
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .animate-fade-in {
            animation: fadeIn 0.6s ease forwards;
        }
        
        .image-modal-close {
            position: absolute;
            top: 20px;
            right: 20px;
            z-index: 1000;
            background: rgba(0, 0, 0, 0.7);
            border-radius: 50%;
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.5rem;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .image-modal-close:hover {
            background: rgba(0, 0, 0, 0.9);
            transform: scale(1.1);
        }
        
        .video-error {
            display: none;
            background: rgba(255, 0, 0, 0.1);
            border: 1px solid rgba(255, 0, 0, 0.2);
            border-radius: 8px;
            padding: 10px;
            margin-top: 10px;
            color: #e53e3e;
            font-size: 0.875rem;
        }
        
        .video-troubleshoot-btn {
            color: #4299e1;
            text-decoration: underline;
            cursor: pointer;
            margin-top: 5px;
            display: inline-block;
        }
        
        .troubleshoot-tips {
            display: none;
            background: #f7fafc;
            border-radius: 8px;
            padding: 15px;
            margin-top: 10px;
            border-left: 4px solid #4299e1;
        }
        
        @media (max-width: 768px) {
            .gallery-filter-btn {
                font-size: 0.75rem;
                padding: 0.5rem 0.75rem;
            }
            
            .stat-number {
                font-size: 1.75rem;
            }
            
            .image-modal-close {
                top: 10px;
                right: 10px;
                width: 35px;
                height: 35px;
                font-size: 1.25rem;
            }
        }
        
        .loading-indicator {
            display: none;
            text-align: center;
            padding: 2rem;
        }
        
        .loading-spinner {
            border: 4px solid #f3f3f3;
            border-top: 4px solid #3730a3;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            animation: spin 1s linear infinite;
            margin: 0 auto;
        }
    </style>
</head>
<body class="bg-gray-50">
    <!-- Header -->
    <header class="header">
        <div class="container mx-auto px-4 py-3">
            <div class="flex items-center justify-between">
                <!-- Logo -->
                <a href="/" class="flex items-center">
                    <div class="logo h-10 w-10 bg-indigo-600 rounded-lg flex items-center justify-center text-white font-bold text-xl">HH</div>
                    <span class="ml-2 text-white font-semibold text-lg">HostelHub</span>
                </a>
                
                <!-- Desktop Navigation -->
                <nav class="hidden md:flex items-center space-x-8">
                    <a href="#" class="nav-link text-white">सुविधाहरू</a>
                    <a href="#" class="nav-link text-white">कसरी काम गर्छ</a>
                    <a href="#" class="nav-link text-white">मूल्य</a>
                    <a href="#" class="nav-link active text-white font-semibold">ग्यालरी</a>
                    <a href="#" class="nav-link text-white">समीक्षाहरू</a>
                </nav>
                
                <!-- Auth Buttons -->
                <div class="hidden md:flex items-center space-x-4">
                    <a href="#" class="px-4 py-2 border border-white rounded-lg text-white hover:bg-white hover:text-indigo-900 transition-colors duration-300">लगइन</a>
                    <a href="#" class="px-4 py-2 bg-white text-indigo-900 rounded-lg font-medium hover:bg-indigo-100 transition-colors duration-300">साइन अप</a>
                </div>
                
                <!-- Mobile Menu Button -->
                <button class="md:hidden text-white text-2xl">
                    <i class="fas fa-bars"></i>
                </button>
            </div>
        </div>
    </header>
    
    <!-- Main Content -->
    <main class="container mx-auto px-4 py-8">
        <!-- Page Header -->
        <div class="text-center mb-12 animate-fade-in">
            <h1 class="text-4xl font-bold text-gray-800 mb-4">होस्टल ग्यालरी - HostelHub</h1>
            <p class="text-gray-600 max-w-3xl mx-auto text-lg">हाम्रा विभिन्न होस्टलहरूको कोठा, सुविधा र आवासीय क्षेत्रहरूको वास्तविक झलकहरू अन्वेषण गर्नुहोस्</p>
        </div>
        
        <!-- Filter Buttons -->
        <div class="flex flex-wrap gap-3 mb-8 justify-center animate-fade-in" id="categoryFilters">
            <!-- Categories will be loaded dynamically -->
        </div>
        
        <!-- Search Box -->
        <div class="mb-10 flex justify-center animate-fade-in">
            <div class="search-box w-full max-w-2xl">
                <input type="text" placeholder="कोठा, सुविधा वा स्थान खोज्नुहोस्..." class="search-input" id="searchInput">
                <i class="fas fa-search search-icon"></i>
            </div>
        </div>
        
        <!-- Gallery Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mb-12" id="galleryGrid">
            <!-- Content will be loaded dynamically -->
        </div>
        
        <!-- Loading Indicator -->
        <div class="loading-indicator" id="loadingIndicator">
            <div class="loading-spinner"></div>
            <p class="mt-4 text-gray-600">ग्यालरी आइटमहरू लोड हुँदैछ...</p>
        </div>
        
        <!-- Stats Section -->
        <div class="bg-gradient-to-r from-indigo-50 to-purple-50 rounded-2xl p-8 mb-12">
            <h2 class="text-2xl font-bold text-center text-gray-800 mb-8">हाम्रो प्रगति</h2>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-6 text-center" id="statsContainer">
                <!-- Stats will be loaded dynamically -->
            </div>
        </div>
        
        <!-- CTA Section -->
        <div class="cta-section p-10 text-center text-white mb-12">
            <h2 class="text-3xl font-bold mb-4 relative z-10">तपाईंको होस्टललाई HostelHub संग जोड्नुहोस्</h2>
            <p class="mb-6 max-w-2xl mx-auto relative z-10">७ दिन निःशुल्क परीक्षण गर्नुहोस् र होस्टल व्यवस्थापनलाई सजिलो, द्रुत र भरपर्दो बनाउनुहोस्</p>
            <div class="flex flex-col sm:flex-row justify-center gap-4 relative z-10">
                <a href="#" class="px-8 py-3 bg-white text-indigo-900 font-medium rounded-lg hover:bg-gray-100 transition-colors duration-300 shadow-lg">निःशुल्क साइन अप गर्नुहोस्</a>
                <a href="#" class="px-8 py-3 border border-white text-white font-medium rounded-lg hover:bg-white hover:text-indigo-900 transition-colors duration-300">डेमो हेर्नुहोस्</a>
            </div>
        </div>
    </main>
    
    <!-- Footer -->
    <footer class="footer text-white py-12">
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                <!-- Logo and description -->
                <div>
                    <div class="h-10 w-10 bg-white text-indigo-900 rounded-lg flex items-center justify-center font-bold text-xl mb-4">HH</div>
                    <p class="text-indigo-200 mb-4">नेपालको नम्बर १ होस्टल प्रबन्धन प्रणाली। हामी होस्टल व्यवस्थापनलाई सहज, द्रुत र विश्वसनीय बनाउँछौं。</p>
                    <div class="flex space-x-4">
                        <a href="#" class="text-indigo-200 hover:text-white"><i class="fab fa-facebook-f"></i></a>
                        <a href="#" class="text-indigo-200 hover:text-white"><i class="fab fa-twitter"></i></a>
                        <a href="#" class="text-indigo-200 hover:text-white"><i class="fab fa-instagram"></i></a>
                        <a href="#" class="text-indigo-200 hover:text-white"><i class="fab fa-linkedin-in"></i></a>
                    </div>
                </div>
                
                <!-- Quick Links -->
                <div>
                    <h3 class="text-lg font-semibold mb-4">तिब्र लिङ्कहरू</h3>
                    <ul class="space-y-3 footer-links">
                        <li><a href="#" class="text-indigo-200"><i class="fas fa-chevron-right text-xs mr-2"></i> होम</a></li>
                        <li><a href="#" class="text-indigo-200"><i class="fas fa-chevron-right text-xs mr-2"></i> सुविधाहरू</a></li>
                        <li><a href="#" class="text-indigo-200"><i class="fas fa-chevron-right text-xs mr-2"></i> कसरी काम गर्छ</a></li>
                        <li><a href="#" class="text-indigo-200"><i class="fas fa-chevron-right text-xs mr-2"></i> ग्यालरी</a></li>
                        <li><a href="#" class="text-indigo-200"><i class="fas fa-chevron-right text-xs mr-2"></i> मूल्य</a></li>
                        <li><a href="#" class="text-indigo-200"><i class="fas fa-chevron-right text-xs mr-2"></i> समीक्षाहरू</a></li>
                    </ul>
                </div>
                
                <!-- Contact Info -->
                <div>
                    <h3 class="text-lg font-semibold mb-4">सम्पर्क जानकारी</h3>
                    <p class="text-indigo-200 mb-2"><i class="fas fa-map-marker-alt mr-3"></i> कमलपोखरी, काठमाडौं, नेपाल</p>
                    <p class="text-indigo-200 mb-2"><i class="fas fa-phone-alt mr-3"></i> +९७७ ९८०१२३४५६७</p>
                    <p class="text-indigo-200 mb-2"><i class="fas fa-envelope mr-3"></i> info@hostelhub.com</p>
                    <p class="text-indigo-200"><i class="fas fa-clock mr-3"></i> सोम-शुक्र: ९:०० बिहान - ५:०० बेलुका</p>
                </div>
                
                <!-- Newsletter -->
                <div>
                    <h3 class="text-lg font-semibold mb-4">समाचारपत्र</h3>
                    <p class="mb-4 text-indigo-200">हाम्रो नवीनतम अपडेटहरू प्राप्त गर्न तपाईंको इमेल दर्ता गर्नुहोस्</p>
                    <form class="flex flex-col gap-3">
                        <input type="email" placeholder="तपाईंको इमेल" class="px-4 py-2 rounded-lg text-gray-800">
                        <button type="submit" class="px-4 py-2 bg-white text-indigo-900 rounded-lg hover:bg-indigo-100 transition-colors font-medium">दर्ता गर्नुहोस्</button>
                    </form>
                </div>
            </div>
            
            <!-- Bottom Copyright -->
            <div class="border-t border-indigo-800 mt-10 pt-6 text-center">
                <p class="text-indigo-300">© 2025 HostelHub. सबै अधिकार सुरक्षित。</p>
                <div class="flex justify-center space-x-6 mt-2">
                    <a href="#" class="text-indigo-200 hover:text-white">गोपनीयता नीति</a>
                    <a href="#" class="text-indigo-200 hover:text-white">सेवा सर्तहरू</a>
                    <a href="#" class="text-indigo-200 hover:text-white">कुकी सेटिङ</a>
                </div>
                <p class="mt-2 text-indigo-300 text-sm">संस्करण: 1.0.0</p>
            </div>
        </div>
    </footer>
    
    <!-- Image Modal -->
    <div id="imageModal" class="fixed inset-0 bg-black bg-opacity-90 flex items-center justify-center z-50 hidden modal">
        <div class="modal-content w-full max-w-4xl mx-4 relative">
            <!-- Close button positioned absolutely at the top right -->
            <button id="closeImageModal" class="image-modal-close">
                <i class="fas fa-times"></i>
            </button>
            
            <div class="bg-gray-900 text-white rounded-t-lg p-4">
                <h3 class="text-lg font-semibold" id="imageModalTitle"></h3>
            </div>
            <div class="bg-gray-800 p-2 rounded-b-lg">
                <img id="modalImage" src="" alt="" class="w-full h-auto max-h-screen object-contain rounded">
            </div>
        </div>
    </div>
    
    <!-- Video Modal -->
    <div id="videoModal" class="fixed inset-0 bg-black bg-opacity-90 flex items-center justify-center z-50 hidden modal">
        <div class="modal-content w-full max-w-4xl mx-4">
            <div class="flex justify-between items-center p-4 bg-gray-900 text-white rounded-t-lg">
                <h3 class="text-lg font-semibold" id="videoModalTitle"></h3>
                <button id="closeVideoModal" class="text-white hover:text-gray-300 text-2xl">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="bg-gray-800 p-2 rounded-b-lg relative">
                <div id="videoPlayerContainer">
                    <video id="modalVideo" controls class="w-full h-auto" style="max-height: 70vh;">
                        Your browser does not support the video tag.
                    </video>
                </div>
                <div id="videoLoading" class="absolute inset-0 flex items-center justify-center hidden">
                    <div class="spinner"></div>
                </div>
                <!-- Video error message in modal -->
                <div id="videoModalError" class="hidden p-4 bg-red-100 border border-red-400 text-red-700 rounded mt-2">
                    <p><i class="fas fa-exclamation-triangle mr-2"></i> भिडियोमा समस्या: केवल आवाज मात्र सुनिन्छ</p>
                    <p class="text-sm mt-1">कृपया पृष्ठलाई रिफ्रेस गर्नुहोस् वा अर्को ब्राउजरमा प्रयास गर्नुहोस्।</p>
                </div>
            </div>
        </div>
    </div>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // API endpoints
            const API_BASE = window.location.origin;
            const GALLERY_API = `/api/gallery/data`;
            const CATEGORIES_API = `/api/gallery/categories`;
            const STATS_API = `/api/gallery/stats`;
            
            // DOM elements
            const galleryGrid = document.getElementById('galleryGrid');
            const categoryFilters = document.getElementById('categoryFilters');
            const statsContainer = document.getElementById('statsContainer');
            const loadingIndicator = document.getElementById('loadingIndicator');
            const searchInput = document.getElementById('searchInput');
            
            // State
            let galleryItems = [];
            let categories = {};
            let stats = {};
            let selectedCategory = 'all';
            
            // Initialize the page
            async function initializePage() {
                showLoading();
                
                try {
                    // Load categories, stats, and gallery items in parallel
                    await Promise.all([
                        loadCategories(),
                        loadStats(),
                        loadGalleryItems()
                    ]);
                    
                    renderCategories();
                    renderStats();
                    renderGalleryItems();
                    
                    hideLoading();
                } catch (error) {
                    console.error('Error initializing page:', error);
                    hideLoading();
                    showError('डाटा लोड गर्न असमर्थ। कृपया पृष्ठ रिफ्रेस गर्नुहोस्।');
                }
            }
            
            // Show loading indicator
            function showLoading() {
                loadingIndicator.style.display = 'block';
                galleryGrid.innerHTML = '';
            }
            
            // Hide loading indicator
            function hideLoading() {
                loadingIndicator.style.display = 'none';
            }
            
            // Show error message
            function showError(message) {
                galleryGrid.innerHTML = `
                    <div class="col-span-full text-center py-10">
                        <div class="text-red-500 text-xl mb-2">
                            <i class="fas fa-exclamation-circle"></i>
                        </div>
                        <p class="text-gray-600">${message}</p>
                        <button class="mt-4 px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700" onclick="initializePage()">
                            पुनः प्रयास गर्नुहोस्
                        </button>
                    </div>
                `;
            }
            
            // Load categories from API
            async function loadCategories() {
                try {
                    const response = await fetch(CATEGORIES_API);
                    if (!response.ok) throw new Error('Categories API error');
                    categories = await response.json();
                } catch (error) {
                    // Fallback categories
                    categories = {
                        'all': 'सबै',
                        'single': '१ सिटर कोठा',
                        'double': '२ सिटर कोठा',
                        'triple': '३ सिटर कोठा',
                        'quad': '४ सिटर कोठा',
                        'common': 'लिभिङ रूम',
                        'bathroom': 'बाथरूम',
                        'kitchen': 'भान्सा',
                        'study': 'अध्ययन कोठा',
                        'event': 'कार्यक्रम',
                        'video': 'भिडियो टुर'
                    };
                }
            }
            
            // Load stats from API
            async function loadStats() {
                try {
                    const response = await fetch(STATS_API);
                    if (!response.ok) throw new Error('Stats API error');
                    stats = await response.json();
                } catch (error) {
                    // Fallback stats
                    stats = {
                        'total_students': 500,
                        'total_hostels': 25,
                        'cities_available': 5,
                        'satisfaction_rate': '98%'
                    };
                }
            }
            
            // Load gallery items from API
            async function loadGalleryItems() {
                try {
                    const response = await fetch(GALLERY_API);
                    if (!response.ok) throw new Error('Gallery API error');
                    galleryItems = await response.json();
                } catch (error) {
                    // Fallback to empty array
                    galleryItems = [];
                }
            }
            
            // Render category filters
            function renderCategories() {
                let html = '';
                
                for (const [key, name] of Object.entries(categories)) {
                    const isActive = selectedCategory === key;
                    html += `
                        <button class="gallery-filter-btn px-5 py-2 rounded-lg ${isActive ? 'bg-indigo-600 text-white active' : 'bg-indigo-100 text-indigo-800'}" 
                                data-filter="${key}">${name}</button>
                    `;
                }
                
                categoryFilters.innerHTML = html;
                
                // Add event listeners to filter buttons
                document.querySelectorAll('.gallery-filter-btn').forEach(button => {
                    button.addEventListener('click', function() {
                        selectedCategory = this.getAttribute('data-filter');
                        renderCategories();
                        renderGalleryItems();
                    });
                });
            }
            
            // Render stats
            function renderStats() {
                statsContainer.innerHTML = `
                    <div class="stat-card">
                        <div class="stat-number">${stats.total_students}+</div>
                        <div class="text-gray-700 font-medium">खुसी विद्यार्थीहरू</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-number">${stats.total_hostels}</div>
                        <div class="text-gray-700 font-medium">सहयोगी होस्टल</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-number">${stats.cities_available}</div>
                        <div class="text-gray-700 font-medium">शहरहरूमा उपलब्ध</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-number">${stats.satisfaction_rate}</div>
                        <div class="text-gray-700 font-medium">सन्तुष्टि दर</div>
                    </div>
                `;
            }
            
            // Render gallery items
            function renderGalleryItems() {
                let html = '';
                
                // Filter items by selected category
                const filteredItems = selectedCategory === 'all' 
                    ? galleryItems 
                    : galleryItems.filter(item => {
                          // Map frontend category keys to backend category values
                          const categoryMap = {
                              'single': '1 seater',
                              'double': '2 seater',
                              'triple': '3 seater',
                              'quad': '4 seater',
                              'common': 'common',
                              'bathroom': 'bathroom',
                              'kitchen': 'kitchen',
                              'study': 'study room',
                              'event': 'event',
                              'video': 'video'
                          };
                          
                          const backendCategory = categoryMap[selectedCategory];
                          return backendCategory ? item.category === backendCategory : false;
                      });
                
                if (filteredItems.length === 0) {
                    html = `
                        <div class="col-span-full text-center py-10">
                            <div class="text-gray-400 text-5xl mb-4">
                                <i class="fas fa-image"></i>
                            </div>
                            <h3 class="text-xl font-semibold text-gray-600 mb-2">कुनै आइटम फेला परेन</h3>
                            <p class="text-gray-500">चयन गरिएको श्रेणीमा कुनै ग्यालरी आइटम छैनन्।</p>
                        </div>
                    `;
                } else {
                    filteredItems.forEach(item => {
                        if (item.media_type === 'external_video' || item.media_type === 'local_video') {
                            html += renderVideoItem(item);
                        } else {
                            html += renderImageItem(item);
                        }
                    });
                }
                
                galleryGrid.innerHTML = html;
                
                // Add event listeners to gallery items
                initializeGalleryInteractions();
            }
            
            // Render a video item
            function renderVideoItem(item) {
                const categoryNames = {
                    'video': 'भिडियो टुर',
                    '1 seater': '१ सिटर कोठा',
                    '2 seater': '२ सिटर कोठा',
                    '3 seater': '३ सिटर कोठा',
                    '4 seater': '४ सिटर कोठा',
                    'common': 'लिभिङ रूम',
                    'bathroom': 'बाथरूम',
                    'kitchen': 'भान्सा',
                    'study room': 'अध्ययन कोठा',
                    'event': 'कार्यक्रम'
                };
                
                const categoryName = categoryNames[item.category] || item.category;
                
                return `
                    <div class="gallery-item animate-fade-in" data-category="${item.category}" data-id="${item.id}">
                        <div class="video-thumbnail">
                            ${item.thumbnail_url ? `
                                <img src="${item.thumbnail_url}" alt="${item.title}" class="w-full h-full object-cover">
                            ` : `
                                <div class="w-full h-full bg-gray-200 flex items-center justify-center">
                                    <i class="fas fa-video text-gray-400 text-4xl"></i>
                                </div>
                            `}
                            <div class="play-icon">
                                <i class="fas fa-play-circle"></i>
                            </div>
                        </div>
                        <div class="p-5 flex-grow">
                            <span class="category-badge bg-blue-100 text-blue-800">${categoryName}</span>
                            <h3 class="font-bold text-lg mb-2">${item.title}</h3>
                            <p class="text-gray-600 text-sm mb-4">${item.description || ''}</p>
                            <div class="flex justify-between items-center text-sm text-gray-500 mt-auto">
                                <span><i class="far fa-calendar-alt mr-1"></i> ${item.created_at}</span>
                                <button class="text-indigo-600 hover:text-indigo-800 font-medium play-video" 
                                        data-video="${item.media_type === 'local_video' ? item.file_url : item.external_link}" 
                                        data-title="${item.title}"
                                        data-type="${item.media_type}">
                                    <i class="fas fa-play mr-1"></i> हेर्नुहोस्
                                </button>
                            </div>
                            <!-- Video error message -->
                            <div class="video-error" id="error-${item.id}">
                                <p><i class="fas fa-exclamation-triangle mr-1"></i> भिडियोमा समस्या: केवल आवाज मात्र सुनिन्छ</p>
                                <div class="video-troubleshoot-btn" onclick="showTroubleshootTips('tips-${item.id}')">
                                    <i class="fas fa-wrench mr-1"></i> समस्या समाधान
                                </div>
                                <div class="troubleshoot-tips" id="tips-${item.id}">
                                    <p class="font-semibold mb-2">समाधानका उपायहरू:</p>
                                    <ul class="list-disc pl-5 space-y-1">
                                        <li>ब्राउजरलाई रिफ्रेस गर्नुहोस्</li>
                                        <li>अर्को ब्राउजरमा प्रयास गर्नुहोस्</li>
                                        <li>इन्टरनेटको गति जाँच गर्नुहोस्</li>
                                        <li>भिडियो फॉर्मेट समर्थित छ/छैन जाँच गर्नुहोस्</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
            }
            
            // Render an image item
            function renderImageItem(item) {
                const categoryNames = {
                    'video': 'भिडियो टुर',
                    '1 seater': '१ सिटर कोठा',
                    '2 seater': '२ सिटर कोठा',
                    '3 seater': '३ सिटर कोठा',
                    '4 seater': '४ सिटर कोठा',
                    'common': 'लिभिङ रूम',
                    'bathroom': 'बाथरूम',
                    'kitchen': 'भान्सा',
                    'study room': 'अध्ययन कोठा',
                    'event': 'कार्यक्रम'
                };
                
                const categoryName = categoryNames[item.category] || item.category;
                const badgeColor = getBadgeColor(item.category);
                
                return `
                    <div class="gallery-item animate-fade-in" data-category="${item.category}" data-id="${item.id}">
                        <div class="gallery-img-container">
                            ${item.thumbnail_url ? `
                                <img src="${item.thumbnail_url}" alt="${item.title}" class="gallery-img">
                            ` : `
                                <div class="w-full h-full bg-gray-200 flex items-center justify-center">
                                    <i class="fas fa-image text-gray-400 text-4xl"></i>
                                </div>
                            `}
                            <div class="gallery-overlay">
                                <button class="text-white font-medium view-image" 
                                        data-image="${item.file_url}" 
                                        data-title="${item.title}">
                                    <i class="fas fa-expand mr-1"></i> ठूलो हेर्नुहोस्
                                </button>
                            </div>
                        </div>
                        <div class="p-5 flex-grow">
                            <span class="category-badge ${badgeColor}">${categoryName}</span>
                            <h3 class="font-bold text-lg mb-2">${item.title}</h3>
                            <p class="text-gray-600 text-sm mb-4">${item.description || ''}</p>
                            <div class="flex justify-between items-center text-sm text-gray-500 mt-auto">
                                <span><i class="far fa-calendar-alt mr-1"></i> ${item.created_at}</span>
                                <button class="text-indigo-600 hover:text-indigo-800 font-medium view-image" 
                                        data-image="${item.file_url}" 
                                        data-title="${item.title}">
                                    <i class="fas fa-expand mr-1"></i> हेर्नुहोस्
                                </button>
                            </div>
                        </div>
                    </div>
                `;
            }
            
            // Get badge color based on category
            function getBadgeColor(category) {
                switch(category) {
                    case '1 seater': return 'bg-purple-100 text-purple-800';
                    case '2 seater': return 'bg-green-100 text-green-800';
                    case '3 seater': return 'bg-yellow-100 text-yellow-800';
                    case '4 seater': return 'bg-yellow-100 text-yellow-800';
                    case 'common': return 'bg-red-100 text-red-800';
                    case 'bathroom': return 'bg-blue-100 text-blue-800';
                    case 'kitchen': return 'bg-blue-100 text-blue-800';
                    case 'study room': return 'bg-blue-100 text-blue-800';
                    case 'event': return 'bg-blue-100 text-blue-800';
                    case 'video': return 'bg-blue-100 text-blue-800';
                    default: return 'bg-gray-100 text-gray-800';
                }
            }
            
            // Initialize gallery interactions
            function initializeGalleryInteractions() {
                // Image modal functionality
                const imageModal = document.getElementById('imageModal');
                const modalImage = document.getElementById('modalImage');
                const imageModalTitle = document.getElementById('imageModalTitle');
                const closeImageModal = document.getElementById('closeImageModal');
                
                // Function to close image modal
                function closeImageModalFunc() {
                    imageModal.classList.remove('open');
                    setTimeout(() => {
                        imageModal.classList.add('hidden');
                        modalImage.src = '';
                    }, 300);
                }
                
                document.addEventListener('click', function(e) {
                    if (e.target.classList.contains('view-image')) {
                        const imageSrc = e.target.getAttribute('data-image');
                        const imageTitle = e.target.getAttribute('data-title');
                        
                        modalImage.src = '';
                        imageModalTitle.textContent = 'लोड हुँदैछ...';
                        
                        imageModal.classList.remove('hidden');
                        setTimeout(() => imageModal.classList.add('open'), 10);
                        
                        // Load image with error handling
                        const img = new Image();
                        img.onload = function() {
                            modalImage.src = imageSrc;
                            imageModalTitle.textContent = imageTitle;
                        };
                        img.onerror = function() {
                            imageModalTitle.textContent = 'तस्वीर लोड गर्न असमर्थ';
                            modalImage.src = 'data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMjAwIiBoZWlnaHQ9IjIwMCIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj48cmVjdCB3aWR0aD0iMTAwJSIgaGVpZ2h0PSIxMDAlIiBmaWxsPSIjZGRkIi8+PHRleHQgeD0iNTAlIiB5PSI1MCUiIGZvbnQtc2l6ZT0iMTgiIHRleHQtYW5jaG9yPSJtaWRkbGUiIGR5PSIuMzVlbSIgZmlsbD0iIzk5OSI+SW1hZ2UgTm90IEZvdW5kPC90ZXh0Pjwvc3ZnPg==';
                        };
                        img.src = imageSrc;
                    }
                });

                closeImageModal.addEventListener('click', closeImageModalFunc);

                imageModal.addEventListener('click', (e) => {
                    if (e.target === imageModal) {
                        closeImageModalFunc();
                    }
                });

                // Video modal functionality
                const videoModal = document.getElementById('videoModal');
                const modalVideo = document.getElementById('modalVideo');
                const videoModalTitle = document.getElementById('videoModalTitle');
                const closeVideoModal = document.getElementById('closeVideoModal');
                const videoLoading = document.getElementById('videoLoading');
                const videoModalError = document.getElementById('videoModalError');
                const videoPlayerContainer = document.getElementById('videoPlayerContainer');
                
                // Close video modal
                function closeVideoModalFunc() {
                    videoModal.classList.remove('open');
                    modalVideo.pause();
                    modalVideo.currentTime = 0;
                    modalVideo.removeAttribute('src');
                    modalVideo.load();
                    videoModalError.classList.add('hidden');
                    
                    // Remove any iframe that might have been added
                    const iframe = videoPlayerContainer.querySelector('iframe');
                    if (iframe) {
                        iframe.parentNode.removeChild(iframe);
                    }
                    
                    // Show the video element again
                    modalVideo.style.display = 'block';
                    
                    setTimeout(() => {
                        videoModal.classList.add('hidden');
                    }, 300);
                }
                
                document.addEventListener('click', function(e) {
                    if (e.target.classList.contains('play-video')) {
                        const videoSrc = e.target.getAttribute('data-video');
                        const videoType = e.target.getAttribute('data-type');
                        const itemId = e.target.closest('.gallery-item').getAttribute('data-id');
                        videoModalTitle.textContent = e.target.getAttribute('data-title');
                        videoModal.classList.remove('hidden');
                        setTimeout(() => videoModal.classList.add('open'), 10);
                        
                        videoLoading.classList.remove('hidden');
                        videoModalError.classList.add('hidden');
                        
                        // Clear previous content
                        modalVideo.style.display = 'none';
                        modalVideo.innerHTML = '';
                        
                        // Remove any existing iframe
                        const existingIframe = videoPlayerContainer.querySelector('iframe');
                        if (existingIframe) {
                            existingIframe.parentNode.removeChild(existingIframe);
                        }
                        
                        if (videoType === 'external_video') {
                            // For external videos (YouTube), use iframe
                            const iframe = document.createElement('iframe');
                            iframe.src = videoSrc;
                            iframe.width = '100%';
                            iframe.height = '100%';
                            iframe.style.minHeight = '70vh';
                            iframe.setAttribute('frameborder', '0');
                            iframe.setAttribute('allowfullscreen', 'true');
                            iframe.setAttribute('allow', 'accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture');
                            videoPlayerContainer.appendChild(iframe);
                            
                            iframe.onload = function() {
                                videoLoading.classList.add('hidden');
                            };
                            
                            iframe.onerror = function() {
                                videoLoading.classList.add('hidden');
                                videoModalTitle.textContent = 'भिडियो लोड गर्न असमर्थ';
                                showVideoError(itemId);
                            };
                        } else {
                            // For local videos
                            modalVideo.style.display = 'block';
                            
                            const source = document.createElement('source');
                            source.src = videoSrc;
                            
                            if (videoSrc.endsWith('.mp4')) {
                                source.type = 'video/mp4';
                            } else if (videoSrc.endsWith('.webm')) {
                                source.type = 'video/webm';
                            } else if (videoSrc.endsWith('.ogg') || videoSrc.endsWith('.ogv')) {
                                source.type = 'video/ogg';
                            } else {
                                source.type = 'video/mp4';
                            }
                            
                            modalVideo.appendChild(source);
                            modalVideo.load();
                            
                            modalVideo.addEventListener('loadeddata', function() {
                                videoLoading.classList.add('hidden');
                                // Check if video has valid dimensions (not 0x0 which indicates decoding issue)
                                if (modalVideo.videoWidth === 0 && modalVideo.videoHeight === 0) {
                                    showVideoError(itemId);
                                } else {
                                    // Video loaded successfully with visual content
                                    modalVideo.play().catch(e => {
                                        console.error('Autoplay prevented:', e);
                                    });
                                }
                            });
                            
                            modalVideo.addEventListener('error', function() {
                                videoLoading.classList.add('hidden');
                                videoModalTitle.textContent = 'भिडियो लोड गर्न असमर्थ';
                                showVideoError(itemId);
                            });
                            
                            // Set timeout to detect video playback issues
                            setTimeout(function() {
                                if (modalVideo.readyState < 2) { // HAVE_CURRENT_DATA
                                    showVideoError(itemId);
                                    videoLoading.classList.add('hidden');
                                }
                            }, 10000); // 10 seconds timeout
                        }
                    }
                });

                closeVideoModal.addEventListener('click', closeVideoModalFunc);

                videoModal.addEventListener('click', (e) => {
                    if (e.target === videoModal) {
                        closeVideoModalFunc();
                    }
                });

                // Keyboard events for modals
                document.addEventListener('keydown', (e) => {
                    if (e.key === 'Escape') {
                        if (videoModal.classList.contains('open')) {
                            closeVideoModalFunc();
                        }
                        if (imageModal.classList.contains('open')) {
                            closeImageModalFunc();
                        }
                    }
                });
            }
            
            // Function to show video error
            function showVideoError(itemId) {
                const errorElement = document.getElementById(`error-${itemId}`);
                if (errorElement) {
                    errorElement.style.display = 'block';
                }
                videoModalError.classList.remove('hidden');
            }
            
            // Function to show troubleshoot tips
            window.showTroubleshootTips = function(tipsId) {
                const tipsElement = document.getElementById(tipsId);
                if (tipsElement) {
                    tipsElement.style.display = tipsElement.style.display === 'block' ? 'none' : 'block';
                }
            };
            
            // Search functionality
            searchInput.addEventListener('input', () => {
                const searchTerm = searchInput.value.toLowerCase();
                const items = galleryGrid.querySelectorAll('.gallery-item');
                
                items.forEach(item => {
                    const title = item.querySelector('h3').textContent.toLowerCase();
                    const description = item.querySelector('p').textContent.toLowerCase();
                    const category = item.querySelector('.category-badge').textContent.toLowerCase();
                    
                    if (title.includes(searchTerm) || description.includes(searchTerm) || category.includes(searchTerm)) {
                        item.style.display = 'flex';
                    } else {
                        item.style.display = 'none';
                    }
                });
            });
            
            // Initialize the page
            initializePage();
        });
    </script>
</body>
</html><?php /**PATH D:\My Projects\HostelHub\resources\views/frontend/gallery/index.blade.php ENDPATH**/ ?>