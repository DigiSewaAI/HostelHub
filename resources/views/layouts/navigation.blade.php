<nav x-data="{ open: false, sidebarCollapsed: localStorage.getItem('sidebarCollapsed') === 'true' }" class="bg-white border-b border-gray-100">
    @auth
    <!-- Authenticated Layout with Sidebar and Topbar -->
    <div class="flex h-screen bg-gray-50">
        <!-- Sidebar -->
        <aside class="sidebar bg-blue-800 text-white z-20 flex-shrink-0 transition-all duration-300 ease-in-out flex flex-col h-full fixed left-0 top-0"
              :class="{ 'w-64': !sidebarCollapsed, 'w-16': sidebarCollapsed }"
              style="height: 100vh;">
            <div class="p-4 border-b border-blue-700 flex items-center justify-between">
                <a href="{{ route(Auth::user()->role . '.dashboard') }}" class="logo-container flex items-center">
                    <img src="{{ asset('storage/images/logo.png') }}" alt="HostelHub Logo" class="logo-img h-8">
                    <span class="logo-text ml-3 font-bold text-lg" :class="{ 'hidden': sidebarCollapsed }">होस्टलहब</span>
                </a>
                <button @click="sidebarCollapsed = !sidebarCollapsed; localStorage.setItem('sidebarCollapsed', sidebarCollapsed)" 
                        class="text-gray-300 hover:text-white transition-colors">
                    <svg :class="{ 'rotate-180': sidebarCollapsed }" class="w-4 h-4 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 19l-7-7 7-7m8 14l-7-7 7-7" />
                    </svg>
                </button>
            </div>
            
            <nav class="mt-5 px-2 flex-1 overflow-y-auto">
                @if(Auth::user()->role === 'admin')
                    <!-- Admin Navigation -->
                    <a href="{{ route('admin.dashboard') }}" class="sidebar-link flex items-center px-3 py-3 text-sm font-medium rounded-lg mb-1 transition-colors {{ request()->routeIs('admin.dashboard') ? 'bg-blue-700 text-white' : 'text-blue-100 hover:bg-blue-700' }}">
                        <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                        </svg>
                        <span :class="{ 'hidden': sidebarCollapsed }">ड्यासबोर्ड</span>
                    </a>
                    
                    <a href="{{ route('admin.hostels.index') }}" class="sidebar-link flex items-center px-3 py-3 text-sm font-medium rounded-lg mb-1 transition-colors {{ request()->routeIs('admin.hostels.*') ? 'bg-blue-700 text-white' : 'text-blue-100 hover:bg-blue-700' }}">
                        <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                        </svg>
                        <span :class="{ 'hidden': sidebarCollapsed }">होस्टलहरू</span>
                    </a>

                @elseif(Auth::user()->role === 'owner')
                    <!-- Owner Navigation -->
                    <a href="{{ route('owner.dashboard') }}" class="sidebar-link flex items-center px-3 py-3 text-sm font-medium rounded-lg mb-1 transition-colors {{ request()->routeIs('owner.dashboard') ? 'bg-blue-700 text-white' : 'text-blue-100 hover:bg-blue-700' }}">
                        <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                        </svg>
                        <span :class="{ 'hidden': sidebarCollapsed }">ड्यासबोर्ड</span>
                    </a>
                    
                    <a href="{{ route('owner.meal-menus.index') }}" class="sidebar-link flex items-center px-3 py-3 text-sm font-medium rounded-lg mb-1 transition-colors {{ request()->routeIs('owner.meal-menus.*') ? 'bg-blue-700 text-white' : 'text-blue-100 hover:bg-blue-700' }}">
                        <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7" />
                        </svg>
                        <span :class="{ 'hidden': sidebarCollapsed }">खानाको योजना</span>
                    </a>

                    <!-- ✅ ADDED: Reviews link for owner -->
                    <a href="{{ route('owner.reviews.index') }}" class="sidebar-link flex items-center px-3 py-3 text-sm font-medium rounded-lg mb-1 transition-colors {{ request()->routeIs('owner.reviews.*') ? 'bg-blue-700 text-white' : 'text-blue-100 hover:bg-blue-700' }}">
                        <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
                        </svg>
                        <span :class="{ 'hidden': sidebarCollapsed }">समीक्षाहरू</span>
                    </a>
                @endif
                
                <!-- Profile Link -->
                <a href="{{ route('profile.edit') }}" class="sidebar-link flex items-center px-3 py-3 text-sm font-medium rounded-lg mb-1 transition-colors {{ request()->routeIs('profile.edit') ? 'bg-blue-700 text-white' : 'text-blue-100 hover:bg-blue-700' }}">
                    <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                    <span :class="{ 'hidden': sidebarCollapsed }">प्रोफाइल</span>
                </a>
                
                <!-- Logout Section -->
                <div class="mt-auto pt-4 border-t border-blue-700">
                    <form method="POST" action="{{ route('logout') }}" id="logout-form">
                        @csrf
                        <button type="submit" class="w-full flex items-center px-3 py-3 text-sm font-medium rounded-lg text-blue-100 hover:bg-blue-700 transition-colors">
                            <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                            </svg>
                            <span :class="{ 'hidden': sidebarCollapsed }">लगआउट</span>
                        </button>
                    </form>
                </div>
            </nav>
        </aside>

        <!-- Main Content Area -->
        <div class="flex-1 flex flex-col" :class="{ 'lg:ml-64': !sidebarCollapsed, 'lg:ml-16': sidebarCollapsed }">
            <!-- Topbar -->
            <header class="bg-white shadow-sm z-10 border-b border-gray-200">
                <div class="flex items-center justify-between px-6 py-4">
                    <div class="flex items-center">
                        <button @click="sidebarCollapsed = !sidebarCollapsed" class="lg:hidden text-gray-600 hover:text-gray-900 mr-4">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                            </svg>
                        </button>
                        <h1 class="text-xl font-semibold text-gray-800">
                            @yield('title', 'ड्यासबोर्ड')
                        </h1>
                    </div>
                    
                    <div class="flex items-center space-x-4">
                        <!-- Notifications -->
                        <div class="relative">
                            <button class="notification-button text-gray-600 hover:text-gray-900 p-2 rounded-full hover:bg-gray-100 transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-5 5v-5zM10.24 8.56a5.97 5.97 0 01-2.24-.44 6 6 0 016.48 9.74M12 6v2m0 4h.01" />
                                </svg>
                                <span class="absolute top-0 right-0 block h-2 w-2 rounded-full bg-red-400"></span>
                            </button>
                        </div>
                        
                        <!-- User Menu -->
                        <x-dropdown align="right" width="48">
                            <x-slot name="trigger">
                                <button class="flex items-center space-x-2 text-sm font-medium text-gray-700 hover:text-gray-900 focus:outline-none transition-colors">
                                    <div class="w-8 h-8 bg-blue-600 rounded-full flex items-center justify-center">
                                        <span class="text-white text-sm font-semibold">{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}</span>
                                    </div>
                                    <span class="hidden md:block">{{ Auth::user()->name }}</span>
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                    </svg>
                                </button>
                            </x-slot>

                            <x-slot name="content">
                                <x-dropdown-link :href="route('profile.edit')">
                                    {{ __('प्रोफाइल') }}
                                </x-dropdown-link>

                                <!-- Authentication -->
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <x-dropdown-link :href="route('logout')"
                                            onclick="event.preventDefault();
                                                        this.closest('form').submit();">
                                        {{ __('लगआउट') }}
                                    </x-dropdown-link>
                                </form>
                            </x-slot>
                        </x-dropdown>
                    </div>
                </div>
            </header>

            <!-- Page Content -->
            <main class="flex-1 overflow-y-auto p-6 bg-gray-50">
                {{ $slot ?? '' }}
            </main>
        </div>
    </div>

    @else
    <!-- Guest Navigation (Original Design Improved) -->
<!-- Primary Navigation Menu -->
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="flex justify-between h-16">
        <div class="flex">
            <!-- Logo -->
            <div class="shrink-0 flex items-center">
                <a href="{{ route('home') }}" class="flex items-center">
                    <img src="{{ asset('storage/images/logo.png') }}" alt="HostelHub Logo" class="block h-9 w-auto object-contain">
                    <span class="ml-2 text-xl font-bold text-gray-900 hidden sm:block">होस्टलहब</span>
                </a>
            </div>

            <!-- Navigation Links -->
            <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                <x-nav-link :href="route('features')" :active="request()->routeIs('features')" class="text-gray-700 hover:text-gray-900 transition-colors">
                    {{ __('सुविधाहरू') }}
                </x-nav-link>
                <x-nav-link :href="route('how-it-works')" :active="request()->routeIs('how-it-works')" class="text-gray-700 hover:text-gray-900 transition-colors">
                    {{ __('कसरी काम गर्छ') }}
                </x-nav-link>
                <x-nav-link :href="route('pricing')" :active="request()->routeIs('pricing')" class="text-gray-700 hover:text-gray-900 transition-colors">
                    {{ __('मूल्य') }}
                </x-nav-link>
                <x-nav-link :href="route('gallery.public')" :active="request()->routeIs('gallery.public')" class="text-gray-700 hover:text-gray-900 transition-colors">
                    {{ __('ग्यालरी') }}
                </x-nav-link>
                <x-nav-link :href="route('reviews')" :active="request()->routeIs('reviews')" class="text-gray-700 hover:text-gray-900 transition-colors">
                    {{ __('समीक्षाहरू') }}
                </x-nav-link>
            </div>
        </div>

        <!-- Auth Toggle Links -->
<div class="hidden sm:flex sm:items-center sm:ms-6">
    @auth
        <!-- Dashboard Link for Authenticated Users -->
        @if(Auth::user()->hasRole('admin'))
            <a href="{{ route('admin.dashboard') }}" class="text-sm font-semibold bg-blue-600 text-white hover:bg-blue-700 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 px-4 py-2 transition-colors mr-3">
                {{ __('ड्यासबोर्ड') }}
            </a>
        @elseif(Auth::user()->hasRole('owner') || Auth::user()->hasRole('hostel_manager'))
            <a href="{{ route('owner.dashboard') }}" class="text-sm font-semibold bg-blue-600 text-white hover:bg-blue-700 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 px-4 py-2 transition-colors mr-3">
                {{ __('ड्यासबोर्ड') }}
            </a>
        @elseif(Auth::user()->hasRole('student'))
            <a href="{{ route('student.dashboard') }}" class="text-sm font-semibold bg-blue-600 text-white hover:bg-blue-700 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 px-4 py-2 transition-colors mr-3">
                {{ __('ड्यासबोर्ड') }}
            </a>
        @endif

        <!-- Logout Form -->
        <form method="POST" action="{{ route('logout') }}" class="inline">
            @csrf
            <button type="submit" class="text-sm font-semibold text-gray-700 hover:text-gray-900 hover:bg-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 px-4 py-2 transition-colors border border-gray-300">
                {{ __('लगआउट') }}
            </button>
        </form>
    @else
        <!-- Guest Links -->
        <a href="{{ route('login') }}" class="text-sm font-semibold text-gray-700 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 px-4 py-2 transition-colors">
            {{ __('लगइन') }}
        </a>
        @if (Route::has('register'))
            <a href="{{ route('register') }}" class="ml-4 text-sm font-semibold bg-blue-600 text-white hover:bg-blue-700 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 px-4 py-2 transition-colors">
                {{ __('साइन अप') }}
            </a>
        @endif
    @endauth
</div>

        <!-- Hamburger -->
        <div class="-me-2 flex items-center sm:hidden">
            <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                    <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
    </div>
</div>

<!-- Responsive Navigation Menu -->
<div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
    <div class="pt-2 pb-3 space-y-1">
        <x-responsive-nav-link :href="route('features')" :active="request()->routeIs('features')">
            {{ __('सुविधाहरू') }}
        </x-responsive-nav-link>
        <x-responsive-nav-link :href="route('how-it-works')" :active="request()->routeIs('how-it-works')">
            {{ __('कसरी काम गर्छ') }}
        </x-responsive-nav-link>
        <x-responsive-nav-link :href="route('pricing')" :active="request()->routeIs('pricing')">
            {{ __('मूल्य') }}
        </x-responsive-nav-link>
        <x-responsive-nav-link :href="route('gallery.public')" :active="request()->routeIs('gallery.public')">
            {{ __('ग्यालरी') }}
        </x-responsive-nav-link>
        <x-responsive-nav-link :href="route('reviews')" :active="request()->routeIs('reviews')">
            {{ __('समीक्षाहरू') }}
        </x-responsive-nav-link>
        
        @auth
            <!-- Authenticated User Mobile Menu -->
            @if(Auth::user()->hasRole('admin'))
                <x-responsive-nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.dashboard')">
                    {{ __('ड्यासबोर्ड') }}
                </x-responsive-nav-link>
            @elseif(Auth::user()->hasRole('owner') || Auth::user()->hasRole('hostel_manager'))
                <x-responsive-nav-link :href="route('owner.dashboard')" :active="request()->routeIs('owner.dashboard')">
                    {{ __('ड्यासबोर्ड') }}
                </x-responsive-nav-link>
            @elseif(Auth::user()->hasRole('student'))
                <x-responsive-nav-link :href="route('student.dashboard')" :active="request()->routeIs('student.dashboard')">
                    {{ __('ड्यासबोर्ड') }}
                </x-responsive-nav-link>
            @endif

            <!-- Logout Form for Mobile -->
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <x-responsive-nav-link :href="route('logout')"
                        onclick="event.preventDefault();
                                    this.closest('form').submit();">
                    {{ __('लगआउट') }}
                </x-responsive-nav-link>
            </form>
        @else
            <!-- Guest Mobile Menu -->
            <x-responsive-nav-link :href="route('login')" :active="request()->routeIs('login')">
                {{ __('लगइन') }}
            </x-responsive-nav-link>
            @if (Route::has('register'))
                <x-responsive-nav-link :href="route('register')" :active="request()->routeIs('register')">
                    {{ __('साइन अप') }}
                </x-responsive-nav-link>
            @endif
        @endauth
    </div>
</div>
</nav>

<style>
.sidebar {
    transition: all 0.3s ease;
}

.sidebar-link {
    transition: all 0.2s ease;
}

.sidebar-link:hover {
    transform: translateX(2px);
}

@media (max-width: 1024px) {
    .sidebar {
        transform: translateX(-100%);
    }
    
    .sidebar.mobile-open {
        transform: translateX(0);
    }
}
</style>