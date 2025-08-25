<header id="site-header" class="fixed top-0 left-0 right-0 z-50 bg-white shadow-md h-20 border-b border-gray-200">
    <div class="h-full flex items-center">
        <div class="container mx-auto px-4">
            <div class="flex items-center justify-between">
                <!-- Logo -->
                <a href="/" class="flex items-center">
                    <img src="{{ asset('storage/images/logo.png') }}" alt="HostelHub Logo" class="w-32 h-10 object-contain">
                </a>
                
                <!-- Desktop Navigation -->
                <div class="hidden md:flex items-center space-x-8">
                    <a href="{{ route('features') }}" class="text-gray-700 hover:text-indigo-600 transition-colors {{ request()->routeIs('features') ? 'font-bold' : '' }}">सुविधाहरू</a>
                    <a href="{{ route('how-it-works') }}" class="text-gray-700 hover:text-indigo-600 transition-colors {{ request()->routeIs('how-it-works') ? 'font-bold' : '' }}">कसरी काम गर्छ</a>
                    <a href="{{ route('pricing') }}" class="text-gray-700 hover:text-indigo-600 transition-colors {{ request()->routeIs('pricing') ? 'font-bold' : '' }}">मूल्य</a>
                    <a href="{{ route('gallery.public') }}" class="text-gray-700 hover:text-indigo-600 transition-colors {{ request()->routeIs('gallery.public') ? 'font-bold' : '' }}">ग्यालरी</a>
                    <a href="{{ route('reviews') }}" class="text-gray-700 hover:text-indigo-600 transition-colors {{ request()->routeIs('reviews') ? 'font-bold' : '' }}">समीक्षाहरू</a>
                </div>
                
                <!-- Auth Buttons -->
                <div class="hidden md:flex items-center space-x-4">
                    @auth
                        <a href="{{ route('dashboard') }}" class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-100 transition-colors">ड्यासबोर्ड</a>
                        <form method="POST" action="{{ route('logout') }}" class="inline">
                            @csrf
                            <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors">लगआउट</button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-100 transition-colors">लगइन</a>
                        <a href="{{ route('register') }}" class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors">साइन अप</a>
                    @endauth
                </div>
                
                <!-- Mobile Menu Button -->
                <button class="md:hidden text-gray-700 text-2xl" id="mobile-menu-toggle">
                    <i class="fas fa-bars"></i>
                </button>
            </div>
        </div>
    </div>

    <!-- Mobile Menu -->
    <div class="md:hidden bg-white border-b border-gray-200 shadow-md hidden" id="mobile-menu">
        <div class="container mx-auto px-4 py-4 space-y-4">
            <a href="{{ route('features') }}" class="block text-gray-700 hover:text-indigo-600 {{ request()->routeIs('features') ? 'font-bold' : '' }}">सुविधाहरू</a>
            <a href="{{ route('how-it-works') }}" class="block text-gray-700 hover:text-indigo-600 {{ request()->routeIs('how-it-works') ? 'font-bold' : '' }}">कसरी काम गर्छ</a>
            <a href="{{ route('pricing') }}" class="block text-gray-700 hover:text-indigo-600 {{ request()->routeIs('pricing') ? 'font-bold' : '' }}">मूल्य</a>
            <a href="{{ route('gallery.public') }}" class="block text-gray-700 hover:text-indigo-600 {{ request()->routeIs('gallery.public') ? 'font-bold' : '' }}">ग्यालरी</a>
            <a href="{{ route('reviews') }}" class="block text-gray-700 hover:text-indigo-600 {{ request()->routeIs('reviews') ? 'font-bold' : '' }}">समीक्षाहरू</a>
            
            <div class="pt-4 border-t border-gray-200 space-y-4">
                @auth
                    <a href="{{ route('dashboard') }}" class="block px-4 py-2 border border-gray-300 text-gray-700 rounded-lg text-center hover:bg-gray-100 transition-colors">ड्यासबोर्ड</a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="w-full px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors">लगआउट</button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="block px-4 py-2 border border-gray-300 text-gray-700 rounded-lg text-center hover:bg-gray-100 transition-colors">लगइन</a>
                    <a href="{{ route('register') }}" class="block px-4 py-2 bg-indigo-600 text-white rounded-lg text-center hover:bg-indigo-700 transition-colors">साइन अप</a>
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