<header id="site-header" class="fixed top-0 left-0 right-0 z-50 bg-indigo-900 shadow-md h-20">
    <div class="h-full flex items-center">
        <div class="container mx-auto px-4">
            <div class="flex items-center justify-between">
                <!-- Logo -->
                <a href="/" class="flex items-center">
                    <img src="{{ asset('storage/images/logo.png') }}" alt="HostelHub Logo" class="w-32 h-10 object-contain">
                </a>
                
                <!-- Desktop Navigation -->
                <div class="hidden md:flex items-center space-x-8">
                    <a href="{{ route('features') }}" class="text-white hover:text-blue-200 transition-colors {{ request()->routeIs('features') ? 'font-bold' : '' }}">सुविधाहरू</a>
                    <a href="{{ route('how-it-works') }}" class="text-white hover:text-blue-200 transition-colors {{ request()->routeIs('how-it-works') ? 'font-bold' : '' }}">कसरी काम गर्छ</a>
                    <a href="{{ route('pricing') }}" class="text-white hover:text-blue-200 transition-colors {{ request()->routeIs('pricing') ? 'font-bold' : '' }}">मूल्य</a>
                    <a href="{{ route('gallery.public') }}" class="text-white hover:text-blue-200 transition-colors {{ request()->routeIs('gallery.public') ? 'font-bold' : '' }}">ग्यालरी</a>
                    <a href="{{ route('testimonials') }}" class="text-white hover:text-blue-200 transition-colors {{ request()->routeIs('testimonials') ? 'font-bold' : '' }}">प्रशंसापत्रहरू</a> {{-- Updated --}}
                </div>
                
                <!-- Auth Buttons -->
                <div class="hidden md:flex items-center space-x-4">
                    @auth
                        <a href="{{ route('dashboard') }}" class="px-4 py-2 border border-white text-white rounded-lg hover:bg-white hover:text-indigo-900 transition-colors">ड्यासबोर्ड</a>
                        <form method="POST" action="{{ route('logout') }}" class="inline">
                            @csrf
                            <button type="submit" class="px-4 py-2 bg-white text-indigo-900 rounded-lg hover:bg-blue-100 transition-colors">लगआउट</button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="px-4 py-2 border border-white text-white rounded-lg hover:bg-white hover:text-indigo-900 transition-colors">लगइन</a>
                        <a href="{{ route('register') }}" class="px-4 py-2 bg-white text-indigo-900 rounded-lg hover:bg-blue-100 transition-colors">साइन अप</a>
                    @endauth
                </div>
                
                <!-- Mobile Menu Button -->
                <button class="md:hidden text-white text-2xl" id="mobile-menu-toggle">
                    <i class="fas fa-bars"></i>
                </button>
            </div>
        </div>
    </div>

    <!-- Mobile Menu -->
    <div class="md:hidden bg-indigo-800 hidden" id="mobile-menu">
        <div class="container mx-auto px-4 py-4 space-y-4">
            <a href="{{ route('features') }}" class="block text-white hover:text-blue-200 {{ request()->routeIs('features') ? 'font-bold' : '' }}">सुविधाहरू</a>
            <a href="{{ route('how-it-works') }}" class="block text-white hover:text-blue-200 {{ request()->routeIs('how-it-works') ? 'font-bold' : '' }}">कसरी काम गर्छ</a>
            <a href="{{ route('pricing') }}" class="block text-white hover:text-blue-200 {{ request()->routeIs('pricing') ? 'font-bold' : '' }}">मूल्य</a>
            <a href="{{ route('gallery.public') }}" class="block text-white hover:text-blue-200 {{ request()->routeIs('gallery.public') ? 'font-bold' : '' }}">ग्यालरी</a>
            <a href="{{ route('testimonials') }}" class="block text-white hover:text-blue-200 {{ request()->routeIs('testimonials') ? 'font-bold' : '' }}">प्रशंसापत्रहरू</a> {{-- Updated --}}
            
            <div class="pt-4 border-t border-indigo-700 space-y-4">
                @auth
                    <a href="{{ route('dashboard') }}" class="block px-4 py-2 border border-white text-white rounded-lg text-center hover:bg-white hover:text-indigo-900 transition-colors">ड्यासबोर्ड</a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="w-full px-4 py-2 bg-white text-indigo-900 rounded-lg hover:bg-blue-100 transition-colors">लगआउट</button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="block px-4 py-2 border border-white text-white rounded-lg text-center hover:bg-white hover:text-indigo-900 transition-colors">लगइन</a>
                    <a href="{{ route('register') }}" class="block px-4 py-2 bg-white text-indigo-900 rounded-lg text-center hover:bg-blue-100 transition-colors">साइन अप</a>
                @endauth
            </div>
        </div>
    </div>
</header>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const mobileMenuToggle = document.getElementById('mobile-menu-toggle');
        const mobileMenu = document.getElementById('mobile-menu');
        
        if (mobileMenuToggle && mobileMenu) {
            mobileMenuToggle.addEventListener('click', function() {
                mobileMenu.classList.toggle('hidden');
            });
            
            // Close menu when clicking outside
            document.addEventListener('click', function(event) {
                if (!mobileMenu.contains(event.target) && !mobileMenuToggle.contains(event.target)) {
                    mobileMenu.classList.add('hidden');
                }
            });
        }
    });
</script>