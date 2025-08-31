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
            box-shadow: 0 8px16px rgba(0, 0, 0, 0.1);
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
            height:40px;
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
        @media (max-width: 768px) {
            .gallery-filter-btn {
                font-size: 0.75rem;
                padding: 0.5rem 0.75rem;
            }
            .stat-number {
                font-size: 1.75rem;
            }
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
                    <img src="{{ asset('storage/images/logo.png') }}" alt="HostelHub Logo" class="logo h-10">
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
        <div class="flex flex-wrap gap-3 mb-8 justify-center animate-fade-in">
            @foreach($categories as $key => $name)
                <button class="gallery-filter-btn px-5 py-2 bg-indigo-100 text-indigo-800 rounded-lg {{ $selectedCategory === $key ? 'active' : '' }}" 
                        data-filter="{{ $key }}">{{ $name }}</button>
            @endforeach
        </div>
        <!-- Search Box -->
        <div class="mb-10 flex justify-center animate-fade-in">
            <div class="search-box w-full max-w-2xl">
                <input type="text" placeholder="कोठा, सुविधा वा स्थान खोज्नुहोस्..." class="search-input">
                <i class="fas fa-search search-icon"></i>
            </div>
        </div>
        <!-- Gallery Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mb-12">
            @foreach($galleryItems as $item)
                <div class="gallery-item animate-fade-in" data-category="{{ $item['category'] }}">
                    @if($item['media_type'] === 'external_video' || $item['media_type'] === 'local_video')
                        <div class="video-thumbnail">
                            <!-- Use thumbnail if available, otherwise use file_path for local videos or default for external -->
                            @if($item['media_type'] === 'local_video' && !empty($item['thumbnail_url']))
                                <img src="{{ $item['thumbnail_url'] }}" alt="{{ $item['title'] }}" class="w-full h-full object-cover">
                            @elseif($item['media_type'] === 'local_video' && !empty($item['file_url']))
                                <img src="{{ $item['file_url'] }}" alt="{{ $item['title'] }}" class="w-full h-full object-cover">
                            @elseif(!empty($item['thumbnail_url']))
                                <img src="{{ $item['thumbnail_url'] }}" alt="{{ $item['title'] }}" class="w-full h-full object-cover">
                            @else
                                <!-- Default video thumbnail -->
                                <img src="{{ asset('images/default-video-thumbnail.jpg') }}" alt="{{ $item['title'] }}" class="w-full h-full object-cover">
                            @endif
                            <div class="play-icon">
                                <i class="fas fa-play-circle"></i>
                            </div>
                        </div>
                        <div class="p-5 flex-grow">
                            <span class="category-badge bg-blue-100 text-blue-800">
                                @switch($item['category'])
                                    @case('video') भिडियो टुर @break
                                    @case('1 seater') १ सिटर कोठा @break
                                    @case('2 seater') २ सिटर कोठा @break
                                    @case('3 seater') ३ सिटर कोठा @break
                                    @case('4 seater') ४ सिटर कोठा @break
                                    @case('common') लिभिङ रूम @break
                                    @case('bathroom') बाथरूम @break
                                    @case('kitchen') भान्सा @break
                                    @case('study room') अध्ययन कोठा @break
                                    @case('event') कार्यक्रम @break
                                    @default {{ $item['category'] }}
                                @endswitch
                            </span>
                            <h3 class="font-bold text-lg mb-2">{{ $item['title'] }}</h3>
                            <p class="text-gray-600 text-sm mb-4">{{ $item['description'] }}</p>
                            <div class="flex justify-between items-center text-sm text-gray-500 mt-auto">
                                <span><i class="far fa-calendar-alt mr-1"></i> {{ $item['created_at'] }}</span>
                                <button class="text-indigo-600 hover:text-indigo-800 font-medium play-video" 
                                        data-video="{{ $item['media_type'] === 'local_video' ? $item['file_url'] : $item['external_link'] }}" 
                                        data-title="{{ $item['title'] }}"
                                        data-type="{{ $item['media_type'] }}">
                                    <i class="fas fa-play mr-1"></i> हेर्नुहोस्
                                </button>
                            </div>
                        </div>
                    @else
                        <div class="gallery-img-container">
                            <!-- Use thumbnail if available, otherwise use file_path -->
                            @if(!empty($item['thumbnail_url']))
                                <img src="{{ $item['thumbnail_url'] }}" alt="{{ $item['title'] }}" class="gallery-img">
                            @elseif(!empty($item['file_url']))
                                <img src="{{ $item['file_url'] }}" alt="{{ $item['title'] }}" class="gallery-img">
                            @else
                                <img src="{{ asset('images/default-image.jpg') }}" alt="{{ $item['title'] }}" class="gallery-img">
                            @endif
                            <div class="gallery-overlay">
                                <button class="text-white font-medium view-image" 
                                        data-image="{{ $item['file_url'] }}" 
                                        data-title="{{ $item['title'] }}">
                                    <i class="fas fa-expand mr-1"></i> ठूलो हेर्नुहोस्
                                </button>
                            </div>
                        </div>
                        <div class="p-5 flex-grow">
                            <span class="category-badge 
                                @switch($item['category'])
                                    @case('1 seater') bg-purple-100 text-purple-800 @break
                                    @case('2 seater') bg-green-100 text-green-800 @break
                                    @case('3 seater') bg-yellow-100 text-yellow-800 @break
                                    @case('4 seater') bg-yellow-100 text-yellow-800 @break
                                    @case('common') bg-red-100 text-red-800 @break
                                    @case('bathroom') bg-blue-100 text-blue-800 @break
                                    @case('kitchen') bg-blue-100 text-blue-800 @break
                                    @case('study room') bg-blue-100 text-blue-800 @break
                                    @case('event') bg-blue-100 text-blue-800 @break
                                    @default bg-gray-100 text-gray-800
                                @endswitch
                            ">
                                @switch($item['category'])
                                    @case('video') भिडियो टुर @break
                                    @case('1 seater') १ सिटर कोठा @break
                                    @case('2 seater') २ सिटर कोठा @break
                                    @case('3 seater') ३ सिटर कोठा @break
                                    @case('4 seater') ४ सिटर कोठा @break
                                    @case('common') लिभिङ रूम @break
                                    @case('bathroom') बाथरूम @break
                                    @case('kitchen') भान्सा @break
                                    @case('study room') अध्ययन कोठा @break
                                    @case('event') कार्यक्रम @break
                                    @default {{ $item['category'] }}
                                @endswitch
                            </span>
                            <h3 class="font-bold text-lg mb-2">{{ $item['title'] }}</h3>
                            <p class="text-gray-600 text-sm mb-4">{{ $item['description'] }}</p>
                            <div class="flex justify-between items-center text-sm text-gray-500 mt-auto">
                                <span><i class="far fa-calendar-alt mr-1"></i> {{ $item['created_at'] }}</span>
                                <button class="text-indigo-600 hover:text-indigo-800 font-medium view-image" 
                                        data-image="{{ $item['file_url'] }}" 
                                        data-title="{{ $item['title'] }}">
                                    <i class="fas fa-expand mr-1"></i> हेर्नुहोस्
                                </button>
                            </div>
                        </div>
                    @endif
                </div>
            @endforeach
        </div>
        <!-- Stats Section -->
        <div class="bg-gradient-to-r from-indigo-50 to-purple-50 rounded-2xl p-8 mb-12">
            <h2 class="text-2xl font-bold text-center text-gray-800 mb-8">हाम्रो प्रगति</h2>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-6 text-center">
                <div class="stat-card">
                    <div class="stat-number">{{ $stats['total_students'] }}+</div>
                    <div class="text-gray-700 font-medium">खुसी विद्यार्थीहरू</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number">{{ $stats['total_hostels'] }}</div>
                    <div class="text-gray-700 font-medium">सहयोगी होस्टल</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number">{{ $stats['cities_available'] }}</div>
                    <div class="text-gray-700 font-medium">शहरहरूमा उपलब्ध</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number">{{ $stats['satisfaction_rate'] }}</div>
                    <div class="text-gray-700 font-medium">सन्तुष्टि दर</div>
                </div>
            </div>
        </div>
        <!-- CTA Section -->
        <div class="cta-section p-10 text-center text-white mb-12">
            <h2 class="text-3xl font-bold mb-4 relative z-10">तपाईंको होस्टललाई HostelHub संग जोड्नुहोस्</h2>
            <p class="mb-6 max-w-2xl mx-auto relative z-10">७ दिन निःशुल्क परीक्षण गर्नुहोस् र होस्टल व्यवस्थापनलाई सजिलो, द्रुत र भरपर्दो बनाउनुहोस्</p>
            <div class="flex flex-col sm:flex-row justify-center gap-4 relative z-10">
                <a href="#" class="px-8 py-3 bg-white text-indigo-900 font-medium rounded-lg hover:bg-gray-100 transition-colors duration-300 shadow-lg">निःशुल्क साइन अप गर्नुहोस्</a>
<a href="{{ route('demo') }}" class="px-8 py-3 border border-white text-white font-medium rounded-lg hover:bg-white hover:text-indigo-900 transition-colors duration-300">डेमो हेर्नुहोस्</a>
</div>

        </div>
    </main>
    <!-- Footer -->
    <footer class="footer text-white py-12">
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                <!-- Logo and description -->
                <div>
                    <img src="{{ asset('storage/images/logo.png') }}" alt="HostelHub Logo" class="h-10 mb-4">
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
        <div class="modal-content w-full max-w-4xl mx-4">
            <div class="flex justify-between items-center p-4 bg-gray-900 text-white rounded-t-lg">
                <h3 class="text-lg font-semibold" id="imageModalTitle"></h3>
                <button id="closeImageModal" class="text-white hover:text-gray-300 text-2xl">
                    <i class="fas fa-times"></i>
                </button>
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
                <video id="modalVideo" controls class="w-full h-auto" style="max-height: 70vh;">
                    Your browser does not support the video tag.
                </video>
                <div id="videoLoading" class="absolute inset-0 flex items-center justify-center hidden">
                    <div class="spinner"></div>
                </div>
            </div>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Map Nepali filter keys to English category values
            const categoryMap = {
                'all': 'all',
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

            // Gallery filter functionality
            const filterButtons = document.querySelectorAll('.gallery-filter-btn');
            const galleryItems = document.querySelectorAll('.gallery-item');
            
            filterButtons.forEach(button => {
                button.addEventListener('click', () => {
                    const filterValue = button.getAttribute('data-filter');
                    const englishCategory = categoryMap[filterValue];
                    
                    // Update URL parameter
                    const url = new URL(window.location);
                    url.searchParams.set('category', filterValue);
                    window.history.pushState({}, '', url);
                    
                    filterButtons.forEach(btn => btn.classList.remove('active'));
                    button.classList.add('active');
                    
                    if (filterValue === 'all') {
                        galleryItems.forEach(item => {
                            item.style.display = 'flex';
                            setTimeout(() => item.classList.add('animate-fade-in'), 10);
                        });
                    } else {
                        galleryItems.forEach(item => {
                            const itemCategory = item.getAttribute('data-category');
                            if (itemCategory === englishCategory) {
                                item.style.display = 'flex';
                                setTimeout(() => item.classList.add('animate-fade-in'), 10);
                            } else {
                                item.style.display = 'none';
                                item.classList.remove('animate-fade-in');
                            }
                        });
                    }
                });
            });

            // Search functionality
            const searchInput = document.querySelector('.search-input');
            searchInput.addEventListener('input', () => {
                const searchTerm = searchInput.value.toLowerCase();
                galleryItems.forEach(item => {
                    const title = item.querySelector('h3').textContent.toLowerCase();
                    const description = item.querySelector('p').textContent.toLowerCase();
                    const category = item.querySelector('.category-badge').textContent.toLowerCase();
                    
                    if (title.includes(searchTerm) || description.includes(searchTerm) || category.includes(searchTerm)) {
                        item.style.display = 'flex';
                        setTimeout(() => item.classList.add('animate-fade-in'), 10);
                    } else {
                        item.style.display = 'none';
                        item.classList.remove('animate-fade-in');
                    }
                });
            });

            // Image modal functionality
            const imageModal = document.getElementById('imageModal');
            const modalImage = document.getElementById('modalImage');
            const imageModalTitle = document.getElementById('imageModalTitle');
            const closeImageModal = document.getElementById('closeImageModal');
            
            document.addEventListener('click', function(e) {
                if (e.target.classList.contains('view-image')) {
                    modalImage.src = e.target.getAttribute('data-image');
                    imageModalTitle.textContent = e.target.getAttribute('data-title');
                    imageModal.classList.remove('hidden');
                    setTimeout(() => imageModal.classList.add('open'), 10);
                }
            });

            closeImageModal.addEventListener('click', () => {
                imageModal.classList.remove('open');
                setTimeout(() => imageModal.classList.add('hidden'), 300);
            });

            imageModal.addEventListener('click', (e) => {
                if (e.target === imageModal) {
                    imageModal.classList.remove('open');
                    setTimeout(() => imageModal.classList.add('hidden'), 300);
                }
            });

            // Video modal functionality
const videoModal = document.getElementById('videoModal');
const modalVideo = document.getElementById('modalVideo');
const videoModalTitle = document.getElementById('videoModalTitle');
const closeVideoModal = document.getElementById('closeVideoModal');
const videoLoading = document.getElementById('videoLoading');

// Add cache busting to video URLs
function addCacheBuster(url) {
    const separator = url.includes('?') ? '&' : '?';
    return url + separator + 't=' + Date.now();
}

// Close video modal
function closeVideoModalFunc() {
    videoModal.classList.remove('open');
    modalVideo.pause();
    modalVideo.currentTime = 0;
    modalVideo.removeAttribute('src');
    modalVideo.load();
    setTimeout(() => {
        videoModal.classList.add('hidden');
    }, 300);
}

// Preload videos to avoid loading issues
function preloadVideo(url) {
    return new Promise((resolve, reject) => {
        const xhr = new XMLHttpRequest();
        xhr.open('GET', url, true);
        xhr.responseType = 'blob';
        
        xhr.onload = function() {
            if (this.status === 200) {
                const blob = this.response;
                const blobUrl = URL.createObjectURL(blob);
                resolve(blobUrl);
            } else {
                reject(new Error('Video loading failed'));
            }
        };
        
        xhr.onerror = function() {
            reject(new Error('Network error'));
        };
        
        xhr.send();
    });
}

document.addEventListener('click', function(e) {
    if (e.target.classList.contains('play-video')) {
        let videoSrc = e.target.getAttribute('data-video');
        const videoType = e.target.getAttribute('data-type');
        videoModalTitle.textContent = e.target.getAttribute('data-title');
        videoModal.classList.remove('hidden');
        setTimeout(() => videoModal.classList.add('open'), 10);
        
        videoLoading.classList.remove('hidden');
        
        // Add cache buster to prevent browser caching issues
        if (videoType === 'local_video') {
            videoSrc = addCacheBuster(videoSrc);
        }
        
        // Clear previous source
        modalVideo.innerHTML = '';
        
        // For external videos (YouTube), use iframe
        if (videoType === 'external_video') {
            modalVideo.style.display = 'none';
            const iframe = document.createElement('iframe');
            iframe.src = videoSrc;
            iframe.width = '100%';
            iframe.height = '100%';
            iframe.style.minHeight = '70vh';
            iframe.setAttribute('frameborder', '0');
            iframe.setAttribute('allowfullscreen', 'true');
            modalVideo.parentNode.appendChild(iframe);
            
            iframe.onload = function() {
                videoLoading.classList.add('hidden');
            };
            
            iframe.onerror = function() {
                videoLoading.classList.add('hidden');
                videoModalTitle.textContent = 'भिडियो लोड गर्न असमर्थ';
            };
        } else {
            // For local videos
            modalVideo.style.display = 'block';
            
            // Try to preload the video first
            preloadVideo(videoSrc)
                .then(blobUrl => {
                    const source = document.createElement('source');
                    source.src = blobUrl;
                    
                    if (videoSrc.endsWith('.mp4')) {
                        source.type = 'video/mp4';
                    } else if (videoSrc.endsWith('.webm')) {
                        source.type = 'video/webm';
                    } else if (videoSrc.endsWith('.ogg') || videoSrc.endsWith('.ogv')) {
                        source.type = 'video/ogg';
                    } else {
                        source.type = 'video/mp4'; // Default
                    }
                    
                    modalVideo.appendChild(source);
                    modalVideo.load();
                    
                    modalVideo.addEventListener('canplay', function canPlayHandler() {
                        videoLoading.classList.add('hidden');
                        modalVideo.play().catch(e => {
                            console.error('Video play failed:', e);
                            videoLoading.classList.add('hidden');
                        });
                    }, { once: true });
                    
                    modalVideo.addEventListener('error', function errorHandler() {
                        videoLoading.classList.add('hidden');
                        videoModalTitle.textContent = 'भिडियो लोड गर्न असमर्थ';
                        
                        // Try direct source as fallback
                        setTimeout(() => {
                            modalVideo.innerHTML = '';
                            const fallbackSource = document.createElement('source');
                            fallbackSource.src = videoSrc;
                            fallbackSource.type = 'video/mp4';
                            modalVideo.appendChild(fallbackSource);
                            modalVideo.load();
                        }, 1000);
                    }, { once: true });
                })
                .catch(error => {
                    console.error('Preloading failed:', error);
                    // Fallback to direct loading
                    const source = document.createElement('source');
                    source.src = videoSrc;
                    source.type = 'video/mp4';
                    modalVideo.appendChild(source);
                    modalVideo.load();
                    
                    modalVideo.addEventListener('canplay', function canPlayHandler() {
                        videoLoading.classList.add('hidden');
                        modalVideo.play().catch(e => {
                            console.error('Video play failed:', e);
                            videoLoading.classList.add('hidden');
                        });
                    }, { once: true });
                    
                    modalVideo.addEventListener('error', function errorHandler() {
                        videoLoading.classList.add('hidden');
                        videoModalTitle.textContent = 'भिडियो लोड गर्न असमर्थ';
                    }, { once: true });
                });
        }
    }
});

closeVideoModal.addEventListener('click', closeVideoModalFunc);

videoModal.addEventListener('click', (e) => {
    if (e.target === videoModal) {
        closeVideoModalFunc();
        // Remove any iframe that might have been added
        const iframe = modalVideo.parentNode.querySelector('iframe');
        if (iframe) {
            iframe.parentNode.removeChild(iframe);
        }
    }
});

// Keyboard events for modals
document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape') {
        if (videoModal.classList.contains('open')) {
            closeVideoModalFunc();
            // Remove any iframe that might have been added
            const iframe = modalVideo.parentNode.querySelector('iframe');
            if (iframe) {
                iframe.parentNode.removeChild(iframe);
            }
        }
    }
});

            // Initialize animations
            setTimeout(() => {
                document.querySelectorAll('.animate-fade-in').forEach((el, index) => {
                    el.style.animationDelay = `${index * 0.1}s`;
                });
            }, 100);

            // Apply initial filter if category is selected
            const urlParams = new URLSearchParams(window.location.search);
            const initialCategory = urlParams.get('category');
            if (initialCategory && initialCategory !== 'all') {
                const initialFilterButton = document.querySelector(`.gallery-filter-btn[data-filter="${initialCategory}"]`);
                if (initialFilterButton) {
                    initialFilterButton.click();
                }
            }
        });
    </script>
</body>
</html>