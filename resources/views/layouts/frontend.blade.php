<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HostelHub - होस्टल प्रबन्धन प्रणाली</title>
    @vite('resources/css/app.css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .hero-section {
            background: linear-gradient(rgba(0, 0, 0, 0.7), rgba(0, 0, 0, 0.7)), url('{{ asset('images/hero-bg.jpg') }}');
            background-size: cover;
            background-position: center;
        }
    </style>
    @stack('styles')
</head>
<body class="font-sans antialiased">
    <!-- नेभिगेसन बार -->
    <nav class="bg-white shadow-md">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex">
                    <!-- लोगो -->
                    <div class="flex-shrink-0 flex items-center">
                        <a href="/" class="flex items-center">
                            <span class="text-2xl font-bold text-blue-600">HostelHub</span>
                        </a>
                    </div>
                    
                    <!-- मेनु आइटमहरू -->
                    <div class="hidden sm:ml-6 sm:flex sm:space-x-8">
                        <a href="/" class="border-blue-500 text-gray-900 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                            होम
                        </a>
                        <a href="/#features" class="border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                            सुविधाहरू
                        </a>
                        <a href="/#how-it-works" class="border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                            कसरी काम गर्छ
                        </a>
                        <a href="/pricing" class="border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                            मूल्य
                        </a>
                        <a href="/#gallery" class="border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                            ग्यालरी
                        </a>
                        <a href="/#reviews" class="border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                            समीक्षाहरू
                        </a>
                    </div>
                </div>
                
                <div class="hidden sm:ml-6 sm:flex sm:items-center">
                    @guest
                        <a href="{{ route('login') }}" class="text-gray-500 hover:text-gray-900 px-3 py-2 rounded-md text-sm font-medium">
                            लगइन
                        </a>
                        <a href="{{ route('register.organization') }}" class="bg-blue-600 text-white hover:bg-blue-700 px-4 py-2 rounded-md text-sm font-medium">
                            साइन अप
                        </a>
                    @else
                        <a href="{{ route('dashboard') }}" class="bg-blue-600 text-white hover:bg-blue-700 px-4 py-2 rounded-md text-sm font-medium">
                            ड्यासबोर्ड
                        </a>
                    @endguest
                </div>
                
                <!-- मोबाइल मेनु बटन -->
                <div class="-mr-2 flex items-center sm:hidden">
                    <button type="button" class="bg-white inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-indigo-500" aria-controls="mobile-menu" aria-expanded="false">
                        <span class="sr-only">मेनु खोल्नुहोस्</span>
                        <!-- मेनु आइकन -->
                        <svg class="block h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>
        
        <!-- मोबाइल मेनु -->
        <div class="sm:hidden" id="mobile-menu">
            <div class="pt-2 pb-3 space-y-1">
                <a href="/" class="bg-blue-50 border-blue-500 text-blue-700 block pl-3 pr-4 py-2 border-l-4 text-base font-medium">
                    होम
                </a>
                <a href="/#features" class="border-transparent text-gray-500 hover:bg-gray-50 hover:border-gray-300 hover:text-gray-700 block pl-3 pr-4 py-2 border-l-4 text-base font-medium">
                    सुविधाहरू
                </a>
                <a href="/#how-it-works" class="border-transparent text-gray-500 hover:bg-gray-50 hover:border-gray-300 hover:text-gray-700 block pl-3 pr-4 py-2 border-l-4 text-base font-medium">
                    कसरी काम गर्छ
                </a>
                <a href="/pricing" class="border-transparent text-gray-500 hover:bg-gray-50 hover:border-gray-300 hover:text-gray-700 block pl-3 pr-4 py-2 border-l-4 text-base font-medium">
                    मूल्य
                </a>
                <a href="/#gallery" class="border-transparent text-gray-500 hover:bg-gray-50 hover:border-gray-300 hover:text-gray-700 block pl-3 pr-4 py-2 border-l-4 text-base font-medium">
                    ग्यालरी
                </a>
                <a href="/#reviews" class="border-transparent text-gray-500 hover:bg-gray-50 hover:border-gray-300 hover:text-gray-700 block pl-3 pr-4 py-2 border-l-4 text-base font-medium">
                    समीक्षाहरू
                </a>
            </div>
            <div class="pt-4 pb-3 border-t border-gray-200">
                <div class="flex items-center px-4">
                    @guest
                        <a href="{{ route('login') }}" class="text-base font-medium text-gray-500 hover:text-gray-900">
                            लगइन
                        </a>
                        <a href="{{ route('register.organization') }}" class="ml-8 bg-blue-600 text-white hover:bg-blue-700 px-4 py-2 rounded-md text-base font-medium">
                            साइन अप
                        </a>
                    @else
                        <a href="{{ route('dashboard') }}" class="ml-8 bg-blue-600 text-white hover:bg-blue-700 px-4 py-2 rounded-md text-base font-medium">
                            ड्यासबोर्ड
                        </a>
                    @endguest
                </div>
            </div>
        </div>
    </nav>

    <!-- मुख्य कन्टेन्ट -->
    <main>
        @yield('content')
    </main>

    <!-- फुटर -->
    <footer class="bg-gray-800 text-white py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <div>
                    <h3 class="text-xl font-bold mb-4">HostelHub</h3>
                    <p class="text-gray-400">नेपालको नम्बर १ होस्टल प्रबन्धन प्रणाली। हामी होस्टल व्यवस्थापनलाई सहज, दक्ष र विश्वसनीय बनाउँछौं।</p>
                </div>
                
                <div>
                    <h4 class="text-lg font-semibold mb-4">तिब्र लिङ्कहरू</h4>
                    <ul class="space-y-2">
                        <li><a href="/" class="text-gray-400 hover:text-white">होम</a></li>
                        <li><a href="/#features" class="text-gray-400 hover:text-white">सुविधाहरू</a></li>
                        <li><a href="/#how-it-works" class="text-gray-400 hover:text-white">कसरी काम गर्छ</a></li>
                        <li><a href="/pricing" class="text-gray-400 hover:text-white">मूल्य</a></li>
                        <li><a href="/#gallery" class="text-gray-400 hover:text-white">ग्यालरी</a></li>
                    </ul>
                </div>
                
                <div>
                    <h4 class="text-lg font-semibold mb-4">सम्पर्क जानकारी</h4>
                    <ul class="space-y-2">
                        <li class="flex items-start">
                            <i class="fas fa-map-marker-alt mt-1 mr-2 text-blue-400"></i>
                            <span class="text-gray-400">कमलपोखरी, काठमाडौं, नेपाल</span>
                        </li>
                        <li class="flex items-start">
                            <i class="fas fa-phone mt-1 mr-2 text-blue-400"></i>
                            <span class="text-gray-400">+९७७ ९८०१२३४५६७</span>
                        </li>
                        <li class="flex items-start">
                            <i class="fas fa-envelope mt-1 mr-2 text-blue-400"></i>
                            <span class="text-gray-400">info@hostelhub.com</span>
                        </li>
                        <li class="flex items-start">
                            <i class="fas fa-clock mt-1 mr-2 text-blue-400"></i>
                            <span class="text-gray-400">सोम-शुक्र: ९:०० बिहान - ५:०० बेलुका</span>
                        </li>
                    </ul>
                </div>
                
                <div>
                    <h4 class="text-lg font-semibold mb-4">समाचारपत्र</h4>
                    <p class="text-gray-400 mb-4">हाम्रो नवीनतम अपडेटहरू प्राप्त गर्न तपाईंको इमेल दर्ता गर्नुहोस्</p>
                    <form class="flex">
                        <input type="email" placeholder="तपाईंको इमेल" class="px-4 py-2 rounded-l-md w-full focus:outline-none">
                        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-r-md hover:bg-blue-700">
                            <i class="fas fa-paper-plane"></i>
                        </button>
                    </form>
                </div>
            </div>
            
            <div class="mt-8 pt-8 border-t border-gray-700 text-center text-gray-400">
                <p>© 2025 HostelHub. सबै अधिकार सुरक्षित।</p>
            </div>
        </div>
    </footer>

    @stack('scripts')
</body>
</html>