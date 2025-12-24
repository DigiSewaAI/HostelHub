<!DOCTYPE html>
<html lang="ne" dir="ltr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="robots" content="noindex, nofollow">
    <title>@yield('title', 'ड्यासबोर्ड') - HostelHub Owner</title>

    <link rel="stylesheet" href="{{ asset('css/mobile-only.css') }}">

    <!-- Favicon -->
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">
    
    <!-- Google Fonts for Nepali -->
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Devanagari:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Font Awesome 6.4.0 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
          integrity="sha512-iecdLmaskl7CVskpV0uYGFkTd73EVdjGN7teJQ8N+2ER5yiJHHIyMI1GAa5I80LzvcpbKjByZcXc9j5QFZUvSJQ=="
          crossorigin="anonymous" referrerpolicy="no-referrer">
    
    <!-- Vite CSS Assets -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- 🚨 EMERGENCY CSS FIX FOR VITE MANIFEST ISSUES -->
    <style>
        /* 🚨 CRITICAL: Ensure sidebar and main content display even if Vite fails */
        :root {
            --sidebar-width: 16rem;
            --sidebar-collapsed-width: 4.5rem;
            --transition-speed: 0.3s;
        }
        
        body {
            font-family: 'Noto Sans Devanagari', sans-serif;
            background-color: #f8fafc;
            margin: 0;
            padding: 0;
            overflow-x: hidden;
        }
        
        .sidebar {
            width: var(--sidebar-width);
            transition: width var(--transition-speed);
            background: linear-gradient(45deg, #4e73df, #224abe) !important;
            position: fixed;
            left: 0;
            top: 0;
            height: 100vh;
            z-index: 1000;
            transform: translateX(0);
        }
        
        .sidebar.collapsed {
            width: var(--sidebar-collapsed-width);
        }
        
        .sidebar-link.active {
            background-color: rgba(255, 255, 255, 0.15) !important;
            color: #ffffff !important;
            border-left: 4px solid #ffffff;
            font-weight: 600;
        }
        
        .sidebar.collapsed .sidebar-text {
            display: none;
        }
        
        .sidebar.collapsed .sidebar-icon {
            margin: 0 auto;
        }
        
        .sidebar-link {
            display: flex;
            align-items: center;
            padding: 0.8rem 1rem;
            border-radius: 0.375rem;
            color: #ffffff;
            transition: all 0.3s;
            margin-bottom: 0.25rem;
            text-decoration: none;
        }
        
        .sidebar-link:hover {
            background-color: rgba(255, 255, 255, 0.15) !important;
            transform: translateX(3px);
            color: white;
        }
        
        .sidebar-link i {
            width: 1.5rem;
            text-align: center;
            margin-right: 0.75rem;
            font-size: 1.1rem;
        }
        
        .bg-gradient-primary {
            background: linear-gradient(45deg, #4e73df, #224abe) !important;
        }
        
        .btn {
            border-radius: 0.5rem;
            font-weight: 600;
            padding: 0.5rem 1rem;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s;
        }
        
        .btn-primary {
            background: linear-gradient(45deg, #4e73df, #224abe);
            border: none;
            box-shadow: 0 2px 5px rgba(78, 115, 223, 0.3);
        }
        
        .btn-primary:hover {
            background: linear-gradient(45deg, #224abe, #4e73df);
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(78, 115, 223, 0.4);
        }
        
        .notification-dot {
            position: absolute;
            top: 3px;
            right: 3px;
            width: 8px;
            height: 8px;
            background-color: #ef4444;
            border-radius: 50%;
            z-index: 10;
        }
        
        .notification-button {
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .notification-button i {
            font-size: 1.25rem;
        }
        
        .skip-link {
            position: absolute;
            top: -40px;
            left: 0;
            background: #1e40af;
            color: white;
            padding: 8px 16px;
            z-index: 100;
            transition: top 0.3s;
        }
        
        .skip-link:focus {
            top: 0;
        }
        
        /* Reduced header height by 20% */
        .header-content {
            padding-top: 0.75rem !important;
            padding-bottom: 0.75rem !important;
        }
        
        .navbar-brand {
            font-size: 1.1rem !important;
        }
        
        .notification-button, .dark-mode-toggle {
            padding: 0.4rem !important;
        }
        
        .user-dropdown .btn {
            padding: 0.4rem 0.75rem !important;
        }
        
        /* Logo Styles */
        .logo-container {
            display: flex;
            align-items: center;
            text-decoration: none;
        }
        .logo-img {
            height: 40px;
            width: auto;
            object-fit: contain;
            background: white;
            padding: 3px;
            border-radius: 6px;
        }
        .logo-text {
            margin-left: 10px;
            color: white;
            font-weight: bold;
            font-size: 18px;
        }
        .mobile-logo {
            height: 32px;
            width: auto;
            background: white;
            padding: 2px;
            border-radius: 4px;
        }
        .text-logo {
            display: flex;
            align-items: center;
            justify-content: center;
            background: white;
            border-radius: 6px;
            padding: 5px;
            height: 40px;
            width: 40px;
            font-weight: bold;
            color: #4e73df;
            font-size: 16px;
            border: 2px solid white;
        }
        .mobile-text-logo {
            height: 32px;
            width: 32px;
            font-size: 14px;
            padding: 4px;
        }

        /* 🚨 CRITICAL FIX: Main content area - FIXED for Vite */
        .main-content-area {
            margin-left: var(--sidebar-width);
            width: calc(100% - var(--sidebar-width));
            transition: all var(--transition-speed);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .sidebar.collapsed ~ .main-content-area {
            margin-left: var(--sidebar-collapsed-width);
            width: calc(100% - var(--sidebar-collapsed-width));
        }

        /* Mobile sidebar styles */
        @media (max-width: 1023px) {
            .sidebar {
                transform: translateX(-100%);
            }
            
            .sidebar.mobile-open {
                transform: translateX(0);
            }
            
            .main-content-area {
    margin-left: 0 !important;
    width: 100vw !important;
}
            
            .sidebar-overlay {
                position: fixed;
                inset: 0;
                background: rgba(0, 0, 0, 0.5);
                z-index: 999;
            }
        }

        /* Ensure content takes full width */
        .main-content-container {
            flex: 1;
            display: flex;
            flex-direction: column;
        }

        /* Fix for page content */
        .page-content {
            flex: 1;
            padding: 1rem;
        }

        @media (min-width: 768px) {
            .page-content {
                padding: 1.5rem;
            }
        }

        /* Owner specific styles */
        .owner-badge {
            background: linear-gradient(45deg, #4e73df, #224abe);
            color: white;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 0.8rem;
            font-weight: 600;
        }

        /* Logo fallback styles */
        .logo-fallback {
            display: flex;
            align-items: center;
            justify-content: center;
            background: white;
            border-radius: 6px;
            padding: 5px;
            height: 40px;
            width: 40px;
            font-weight: bold;
            color: #4e73df;
            font-size: 16px;
            border: 2px solid white;
        }
        .mobile-logo-fallback {
            height: 32px;
            width: 32px;
            font-size: 14px;
            padding: 4px;
        }

        /* 🚨 EMERGENCY FALLBACK STYLES - Applied if Vite fails */
        .vite-fallback {
            display: none;
        }
        
        /* Show fallback message if Vite manifest fails */
        .vite-error-message {
            background: #fef2f2;
            border: 1px solid #fecaca;
            color: #dc2626;
            padding: 1rem;
            border-radius: 0.5rem;
            margin-bottom: 1rem;
            display: none;
        }

        /* ✅ ADDED: Circular specific styles */
        .circular-item {
            border: 1px solid #e2e8f0;
            border-radius: 0.75rem;
            padding: 1rem;
            margin-bottom: 1rem;
            transition: all 0.3s ease;
            background: white;
        }
        
        .circular-item.unread {
            background: #f0f9ff;
            border-left: 4px solid #3b82f6;
        }
        
        .circular-item.read {
            background: #f8fafc;
            opacity: 0.8;
        }
        
        .border-left-urgent {
            border-left: 4px solid #ef4444 !important;
        }
        
        .border-left-high {
            border-left: 4px solid #f59e0b !important;
        }
        
        .border-left-normal {
            border-left: 4px solid #10b981 !important;
        }
        
        .circular-actions {
            display: flex;
            gap: 0.5rem;
        }
    </style>
    
    <!-- Page-specific CSS -->
    @stack('styles')
</head>
<body class="bg-gray-50 font-sans">
    <!-- 🚨 Vite Manifest Error Message (Hidden by default) -->
    <div class="vite-error-message" id="viteError">
        <strong>⚠️ Vite Asset Loading Issue</strong>
        <p>Please run: <code>npm run build</code> to generate frontend assets.</p>
    </div>

    <a href="#main-content" class="skip-link">मुख्य सामग्रीमा जानुहोस्</a>
    
    <div class="flex min-h-screen">
        <!-- Sidebar -->
        <aside id="sidebar" class="sidebar text-white z-20 flex-shrink-0 transition-all duration-300 ease-in-out flex flex-col h-full">
            <div class="p-4 border-b border-blue-700 flex items-center justify-between">
                <a href="{{ url('/owner/dashboard') }}" class="logo-container">
                    <!-- FIXED LOGO WITH MULTIPLE FALLBACKS -->
                    @php
                        $logoPaths = [
                            'images/logo.png',
                            'storage/images/logo.png',
                            'assets/images/logo.png',
                            'public/images/logo.png'
                        ];
                        $logoFound = false;
                    @endphp
                    
                    @foreach($logoPaths as $logoPath)
                        @if(file_exists(public_path($logoPath)) && !$logoFound)
                            <img src="{{ asset($logoPath) }}" alt="HostelHub Logo" class="logo-img">
                            @php $logoFound = true; @endphp
                        @endif
                    @endforeach
                    
                    @if(!$logoFound)
                        <!-- FALLBACK TEXT LOGO -->
                        <div class="logo-fallback">
                            HH
                        </div>
                    @endif
                    <span class="logo-text sidebar-text">होस्टलहब</span>
                </a>
                <button id="sidebar-collapse" class="text-gray-300 hover:text-white sidebar-text" aria-label="साइडबार सङ्कुचित गर्नुहोस्">
                    <i class="fas fa-bars-staggered"></i>
                </button>
            </div>
            
            <nav class="mt-5 px-2 flex-1 overflow-y-auto">
                <!-- Dashboard -->
                <a href="{{ route('owner.dashboard') }}"
                   class="sidebar-link {{ request()->routeIs('owner.dashboard') ? 'active' : '' }}"
                   aria-current="{{ request()->routeIs('owner.dashboard') ? 'page' : 'false' }}">
                    <i class="fas fa-tachometer-alt sidebar-icon"></i>
                    <span class="sidebar-text">ड्यासबोर्ड</span>
                </a>
                
                <!-- Meal Menus -->
                <a href="{{ route('owner.meal-menus.index') }}"
                   class="sidebar-link {{ request()->routeIs('owner.meal-menus.*') ? 'active' : '' }}"
                   aria-current="{{ request()->routeIs('owner.meal-menus.*') ? 'page' : 'false' }}">
                    <i class="fas fa-utensils sidebar-icon"></i>
                    <span class="sidebar-text">खानाको योजना</span>
                </a>
                
                <!-- Gallery -->
                <a href="{{ route('owner.galleries.index') }}"
                   class="sidebar-link {{ request()->routeIs('owner.galleries.*') ? 'active' : '' }}"
                   aria-current="{{ request()->routeIs('owner.galleries.*') ? 'page' : 'false' }}">
                    <i class="fas fa-images sidebar-icon"></i>
                    <span class="sidebar-text">ग्यालरी</span>
                </a>
                
                <!-- Rooms -->
                <a href="{{ route('owner.rooms.index') }}"
                   class="sidebar-link {{ request()->routeIs('owner.rooms.*') ? 'active' : '' }}"
                   aria-current="{{ request()->routeIs('owner.rooms.*') ? 'page' : 'false' }}">
                    <i class="fas fa-door-open sidebar-icon"></i>
                    <span class="sidebar-text">कोठाहरू</span>
                </a>
                
                <!-- Students -->
                <a href="{{ route('owner.students.index') }}"
                   class="sidebar-link {{ request()->routeIs('owner.students.*') ? 'active' : '' }}"
                   aria-current="{{ request()->routeIs('owner.students.*') ? 'page' : 'false' }}">
                    <i class="fas fa-user-graduate sidebar-icon"></i>
                    <span class="sidebar-text">विद्यार्थीहरू</span>
                </a>
                
                <!-- Payments -->
                <a href="{{ route('owner.payments.index') }}"
                   class="sidebar-link {{ request()->routeIs('owner.payments.*') ? 'active' : '' }}"
                   aria-current="{{ request()->routeIs('owner.payments.*') ? 'page' : 'false' }}">
                    <i class="fas fa-money-bill-wave sidebar-icon"></i>
                    <span class="sidebar-text">भुक्तानी</span>
                </a>
                
                <!-- Reviews -->
                <a href="{{ route('owner.reviews.index') }}"
                   class="sidebar-link {{ request()->routeIs('owner.reviews.*') ? 'active' : '' }}"
                   aria-current="{{ request()->routeIs('owner.reviews.*') ? 'page' : 'false' }}">
                    <i class="fas fa-star sidebar-icon"></i>
                    <span class="sidebar-text">समीक्षाहरू</span>
                </a>
                
                <!-- Hostels -->
                <a href="{{ route('owner.hostels.index') }}"
                   class="sidebar-link {{ request()->routeIs('owner.hostels.*') ? 'active' : '' }}"
                   aria-current="{{ request()->routeIs('owner.hostels.*') ? 'page' : 'false' }}">
                    <i class="fas fa-building sidebar-icon"></i>
                    <span class="sidebar-text">होस्टल</span>
                </a>

                <!-- Documents Management -->
                <a href="{{ route('owner.documents.index') }}"
                   class="sidebar-link {{ request()->routeIs('owner.documents.*') ? 'active' : '' }}"
                   aria-current="{{ request()->routeIs('owner.documents.*') ? 'page' : 'false' }}">
                    <i class="fas fa-file-alt sidebar-icon"></i>
                    <span class="sidebar-text">कागजात व्यवस्थापन</span>
                </a>

                <!-- Public Page Management -->
                <a href="{{ route('owner.public-page.edit') }}"
                   class="sidebar-link {{ request()->routeIs('owner.public-page.*') ? 'active' : '' }}"
                   aria-current="{{ request()->routeIs('owner.public-page.*') ? 'page' : 'false' }}">
                    <i class="fas fa-globe sidebar-icon"></i>
                    <span class="sidebar-text">सार्वजनिक पृष्ठ</span>
                </a>

                <!-- Circulars -->
                <a href="{{ route('owner.circulars.index') }}"
                   class="sidebar-link {{ request()->routeIs('owner.circulars.*') ? 'active' : '' }}"
                   aria-current="{{ request()->routeIs('owner.circulars.*') ? 'page' : 'false' }}">
                    <i class="fas fa-bullhorn sidebar-icon"></i>
                    <span class="sidebar-text">सूचनाहरू</span>
                </a>
                
                <!-- Logout Section -->
                <div class="mt-auto pt-4 border-t border-blue-700">
                    <form method="POST" action="{{ route('logout') }}" id="logout-form">
                        @csrf
                        <button type="submit" class="w-full flex items-center px-2 py-2 text-sm rounded-md hover:bg-blue-700 transition-colors">
                            <i class="fas fa-sign-out-alt sidebar-icon"></i>
                            <span class="sidebar-text">लगआउट</span>
                        </button>
                    </form>
                </div>
            </nav>
        </aside>

        <!-- Main Content Area - FIXED -->
        <div class="main-content-area" style="margin-left: 16rem !important; width: calc(100vw - 16rem) !important; min-height: 100vh !important; display: flex !important; flex-direction: column !important;">
            <!-- Top Navigation -->
            <header class="bg-gradient-primary shadow-sm z-10">
                <div class="flex items-center justify-between px-6 header-content">
                    <div class="flex items-center">
                        <button id="mobile-sidebar-toggle" class="lg:hidden text-white hover:text-gray-200 mr-4" aria-label="मोबाइल साइडबार खोल्नुहोस्">
                            <i class="fas fa-bars text-xl"></i>
                        </button>
                        <!-- Brand with Logo - FIXED -->
                        <a href="{{ url('/owner/dashboard') }}" class="navbar-brand text-white flex items-center">
                            <!-- FIXED MOBILE LOGO WITH FALLBACK -->
                            @php
                                $mobileLogoFound = false;
                            @endphp
                            
                            @foreach($logoPaths as $logoPath)
                                @if(file_exists(public_path($logoPath)) && !$mobileLogoFound)
                                    <img src="{{ asset($logoPath) }}" alt="HostelHub Logo" class="mobile-logo mr-2">
                                    @php $mobileLogoFound = true; @endphp
                                @endif
                            @endforeach
                            
                            @if(!$mobileLogoFound)
                                <!-- FALLBACK MOBILE TEXT LOGO -->
                                <div class="mobile-logo-fallback mr-2">
                                    HH
                                </div>
                            @endif
                            <span class="hidden md:inline">होस्टलहब - मालिक प्यानल</span>
                        </a>
                    </div>
                    
                    <div class="flex items-center space-x-3">
                        <!-- Owner Info Badge -->
                        <div class="owner-badge hidden md:flex items-center space-x-2">
                            <i class="fas fa-crown"></i>
                            <span>मालिक</span>
                        </div>

                        <!-- 🔔 NOTIFICATION BELL - ADDED -->
                        <div class="dropdown">
                            <button class="notification-button text-white hover:text-gray-200 p-2 rounded-full hover:bg-blue-700 dropdown-toggle" 
                                    type="button" 
                                    id="notificationsDropdown" 
                                    data-bs-toggle="dropdown" 
                                    aria-expanded="false"
                                    aria-label="सूचनाहरू हेर्नुहोस्">
                                <i class="fas fa-bell text-lg"></i>
                                <span class="notification-dot" aria-hidden="true"></span>
                            </button>
                            <div class="dropdown-menu dropdown-menu-end w-80 bg-white rounded-xl shadow-lg py-1 z-20 max-h-96 overflow-y-auto border border-gray-200" 
                                 aria-labelledby="notificationsDropdown">
                                <div class="px-4 py-2 border-b border-gray-200">
                                    <h3 class="font-semibold text-gray-800">सूचनाहरू</h3>
                                </div>
                                <a href="#" class="flex items-start px-4 py-3 hover:bg-gray-50 border-b border-gray-100">
                                    <div class="bg-blue-100 p-2 rounded-lg mr-3">
                                        <i class="fas fa-money-bill-wave text-blue-600"></i>
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-gray-800">नयाँ भुक्तानी प्राप्त भयो</p>
                                        <p class="text-xs text-gray-500">३० मिनेट अघि</p>
                                    </div>
                                </a>
                                <a href="#" class="flex items-start px-4 py-3 hover:bg-gray-50 border-b border-gray-100">
                                    <div class="bg-green-100 p-2 rounded-lg mr-3">
                                        <i class="fas fa-star text-green-600"></i>
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-gray-800">नयाँ समीक्षा प्राप्त भयो</p>
                                        <p class="text-xs text-gray-500">१ घण्टा अघि</p>
                                    </div>
                                </a>
                                <a href="#" class="flex items-start px-4 py-3 hover:bg-gray-50">
                                    <div class="bg-amber-100 p-2 rounded-lg mr-3">
                                        <i class="fas fa-user-plus text-amber-600"></i>
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-gray-800">नयाँ विद्यार्थी दर्ता भयो</p>
                                        <p class="text-xs text-gray-500">२ घण्टा अघि</p>
                                    </div>
                                </a>
                                <div class="px-4 py-2 border-t border-gray-200 text-center">
                                    <a href="#" class="text-sm text-blue-600 hover:text-blue-800 font-medium">सबै सूचनाहरू हेर्नुहोस्</a>
                                </div>
                            </div>
                        </div>
                        
                        <!-- User Profile Dropdown -->
                        <div class="dropdown user-dropdown">
                            <button class="btn btn-outline-light dropdown-toggle d-flex align-items-center" 
                                    type="button" 
                                    id="userDropdown" 
                                    data-bs-toggle="dropdown" 
                                    aria-expanded="false"
                                    aria-label="प्रयोगकर्ता मेनु">
                                <i class="fas fa-user-circle me-2"></i>
                                <span class="d-none d-md-inline">{{ Auth::user()->name ?? 'प्रयोगकर्ता' }}</span>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end shadow-lg rounded-xl border-0 py-2" aria-labelledby="userDropdown">
                                <li>
                                    <a class="dropdown-item d-flex align-items-center" href="{{ route('owner.profile') }}">
                                        <i class="fas fa-user me-2"></i>प्रोफाइल
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item d-flex align-items-center" href="{{ route('owner.dashboard') }}">
                                        <i class="fas fa-cog me-2"></i>सेटिङहरू
                                    </a>
                                </li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form method="POST" action="{{ route('logout') }}" class="mb-0">
                                        @csrf
                                        <button type="submit" class="dropdown-item d-flex align-items-center text-danger">
                                            <i class="fas fa-sign-out-alt me-2"></i>लगआउट
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </header>

<!-- Mobile Header Space - Mobile मा मात्र देखिने -->
<div class="mobile-header-space d-block d-lg-none"></div>

            <!-- Main Content Container -->
            <div class="main-content-container">
                <!-- Page Content -->
                <main id="main-content" class="page-content" style="width: 100% !important; padding: 1rem !important; flex: 1 !important; display: block !important;">
                    <!-- Flash Messages -->
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show mb-4 rounded-xl" role="alert">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-check-circle me-2"></i>
                                <strong class="nepali">{{ session('success') }}</strong>
                            </div>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show mb-4 rounded-xl" role="alert">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-exclamation-circle me-2"></i>
                                <strong class="nepali">{{ session('error') }}</strong>
                            </div>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <!-- Page Content -->
                    @yield('content')
                </main>
            </div>
        </div>
    </div>

    <!-- Mobile Only JavaScript -->
    <script src="{{ asset('js/mobile-only.js') }}"></script>

    <!-- Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    <!-- Vite Manifest Fallback Detection -->
    <script>
        // Check if Vite assets loaded properly
        document.addEventListener('DOMContentLoaded', function() {
            // Detect if Vite CSS failed to load
            setTimeout(function() {
                const viteLinks = document.querySelectorAll('link[href*="/build/assets/"]');
                let viteLoaded = false;
                
                viteLinks.forEach(link => {
                    if (link.sheet && link.sheet.cssRules.length > 0) {
                        viteLoaded = true;
                    }
                });
                
                // If no Vite CSS loaded and we're not in development, show error
                if (!viteLoaded && !window.location.hostname.includes('localhost')) {
                    document.getElementById('viteError').style.display = 'block';
                    console.error('Vite assets not loaded. Run: npm run build');
                }
            }, 1000);
        });
    </script>

    <!-- Custom JavaScript -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Sidebar collapse functionality
            const sidebar = document.getElementById('sidebar');
            const sidebarCollapse = document.getElementById('sidebar-collapse');
            const mobileSidebarToggle = document.getElementById('mobile-sidebar-toggle');
            const mainContentArea = document.querySelector('.main-content-area');
            
            // Desktop sidebar collapse
            if (sidebarCollapse) {
                sidebarCollapse.addEventListener('click', function() {
                    sidebar.classList.toggle('collapsed');
                    
                    // Update aria-expanded
                    const isCollapsed = sidebar.classList.contains('collapsed');
                    this.setAttribute('aria-expanded', !isCollapsed);
                    this.setAttribute('aria-label', isCollapsed ? 
                        'साइडबार विस्तार गर्नुहोस्' : 'साइडबार सङ्कुचित गर्नुहोस्');
                });
            }
            
            // Mobile sidebar toggle
            if (mobileSidebarToggle) {
                mobileSidebarToggle.addEventListener('click', function() {
                    sidebar.classList.toggle('mobile-open');
                    
                    // Create overlay when mobile sidebar opens
                    if (sidebar.classList.contains('mobile-open')) {
                        const overlay = document.createElement('div');
                        overlay.className = 'sidebar-overlay';
                        overlay.addEventListener('click', function() {
                            sidebar.classList.remove('mobile-open');
                            document.body.removeChild(overlay);
                        });
                        document.body.appendChild(overlay);
                    } else {
                        const overlay = document.querySelector('.sidebar-overlay');
                        if (overlay) {
                            document.body.removeChild(overlay);
                        }
                    }
                   
                    

                    // Update aria-expanded
                    const isOpen = sidebar.classList.contains('mobile-open');
                    this.setAttribute('aria-expanded', isOpen);
                    this.setAttribute('aria-label', isOpen ? 
                        'मोबाइल साइडबार बन्द गर्नुहोस्' : 'मोबाइल साइडबार खोल्नुहोस्');
                });
            }
            
            // Initialize tooltips
            const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            const tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });
            
            // Auto-dismiss alerts after 5 seconds
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(function(alert) {
                setTimeout(function() {
                    if (alert && alert.classList.contains('show')) {
                        const bsAlert = new bootstrap.Alert(alert);
                        bsAlert.close();
                    }
                }, 5000);
            });
            
            // Add smooth scrolling for skip link
            const skipLink = document.querySelector('.skip-link');
            const mainContent = document.getElementById('main-content');
            
            if (skipLink && mainContent) {
                skipLink.addEventListener('click', function(e) {
                    e.preventDefault();
                    mainContent.scrollIntoView({ behavior: 'smooth' });
                    mainContent.setAttribute('tabindex', '-1');
                    mainContent.focus();
                });
            }
            
            // Keyboard navigation improvements
            document.addEventListener('keydown', function(e) {
                // Close dropdowns on Escape
                if (e.key === 'Escape') {
                    const openDropdowns = document.querySelectorAll('.dropdown-menu.show');
                    openDropdowns.forEach(function(dropdown) {
                        const dropdownInstance = bootstrap.Dropdown.getInstance(dropdown.previousElementSibling);
                        if (dropdownInstance) {
                            dropdownInstance.hide();
                        }
                    });
                    
                    // Close mobile sidebar
                    if (sidebar && sidebar.classList.contains('mobile-open')) {
                        sidebar.classList.remove('mobile-open');
                        const overlay = document.querySelector('.sidebar-overlay');
                        if (overlay) {
                            document.body.removeChild(overlay);
                        }
                    }
                }
            });
        });
        
        // Handle window resize
        window.addEventListener('resize', function() {
            const sidebar = document.getElementById('sidebar');
            
            // Close mobile sidebar on desktop
            if (window.innerWidth >= 1024 && sidebar) {
                sidebar.classList.remove('mobile-open');
                const overlay = document.querySelector('.sidebar-overlay');
                if (overlay) {
                    document.body.removeChild(overlay);
                }
            }
        });
    </script>

    <!-- ✅ ADDED: Enhanced form reset and circular functionality JavaScript -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // ✅ FIXED: Enhanced form reset functionality for circular creation
            @if(session('clear_form'))
                // Clear all form inputs
                const forms = document.querySelectorAll('form');
                forms.forEach(form => {
                    form.reset();
                });
                
                // Clear specific circular form fields
                const circularTitle = document.querySelector('input[name="title"]');
                const circularContent = document.querySelector('textarea[name="content"]');
                const targetAudience = document.querySelector('select[name="target_audience[]"]');
                
                if (circularTitle) circularTitle.value = '';
                if (circularContent) circularContent.value = '';
                if (targetAudience) {
                    targetAudience.selectedIndex = -1;
                    // Trigger change event for any dependent fields
                    targetAudience.dispatchEvent(new Event('change'));
                }
                
                // Clear any file inputs
                const fileInputs = document.querySelectorAll('input[type="file"]');
                fileInputs.forEach(input => {
                    input.value = '';
                });
                
                console.log('Form reset completed for circular creation');
            @endif

            // ✅ FIXED: AJAX form submission handling for circulars
            $(document).on('submit', 'form[data-ajax-form="true"]', function(e) {
                e.preventDefault();
                
                const form = $(this);
                const submitBtn = form.find('button[type="submit"]');
                const originalText = submitBtn.html();
                
                // Show loading state
                submitBtn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> प्रक्रिया हुदैछ...');
                
                $.ajax({
                    url: form.attr('action'),
                    method: form.attr('method'),
                    data: new FormData(this),
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        if (response.success) {
                            // Show success message
                            showAlert('सफलता', response.message, 'success');
                            
                            // Reset form if needed
                            if (response.clear_form) {
                                form[0].reset();
                            }
                            
                            // Redirect if specified
                            if (response.redirect) {
                                setTimeout(() => {
                                    window.location.href = response.redirect;
                                }, 1500);
                            }
                        }
                    },
                    error: function(xhr) {
                        let errorMessage = 'अनुरोध असफल भयो';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMessage = xhr.responseJSON.message;
                        }
                        showAlert('त्रुटि', errorMessage, 'error');
                    },
                    complete: function() {
                        submitBtn.prop('disabled', false).html(originalText);
                    }
                });
            });

            // Helper function to show alerts
            function showAlert(title, message, type) {
                const alertClass = type === 'success' ? 'alert-success' : 'alert-danger';
                const icon = type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle';
                
                const alertHtml = `
                    <div class="alert ${alertClass} alert-dismissible fade show mb-4 rounded-xl" role="alert">
                        <div class="d-flex align-items-center">
                            <i class="fas ${icon} me-2"></i>
                            <strong class="nepali">${title}:</strong> ${message}
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                `;
                
                // Prepend alert to main content
                $('#main-content').prepend(alertHtml);
                
                // Auto remove after 5 seconds
                setTimeout(() => {
                    $('.alert').alert('close');
                }, 5000);
            }

            // ✅ FIXED: Real-time circular notifications
            function checkNewCirculars() {
                $.ajax({
                    url: '{{ route("owner.circulars.index") }}?check_new=true',
                    method: 'GET',
                    success: function(response) {
                        if (response.new_circulars && response.new_circulars > 0) {
                            // Update notification badge
                            const badge = $('.notification-dot');
                            if (badge.length) {
                                badge.text(response.new_circulars);
                                badge.show();
                            }
                            
                            // Show notification
                            if (response.new_circulars === 1) {
                                showAlert('नयाँ सूचना', 'तपाईंसँग १ नयाँ सूचना छ', 'info');
                            } else {
                                showAlert('नयाँ सूचनाहरू', `तपाईंसँग ${response.new_circulars} नयाँ सूचनाहरू छन्`, 'info');
                            }
                        }
                    }
                });
            }

            // Check for new circulars every 30 seconds
            setInterval(checkNewCirculars, 30000);

            // ✅ ADDED: Circular publish functionality
            $(document).on('click', '.publish-circular-btn', function() {
                const circularId = $(this).data('circular-id');
                const button = $(this);
                
                if (!confirm('के तपाईं यो सूचना प्रकाशित गर्न चाहनुहुन्छ?')) {
                    return;
                }
                
                button.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> प्रकाशन हुदैछ...');
                
                $.ajax({
                    url: `{{ route('owner.circulars.publish', ':circularId') }}`.replace(':circularId', circularId),
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        if (response.success) {
                            showAlert('सफलता', response.message, 'success');
                            button.replaceWith('<span class="badge bg-success">प्रकाशित</span>');
                            
                            // Reload the page after 2 seconds to reflect changes
                            setTimeout(() => {
                                window.location.reload();
                            }, 2000);
                        }
                    },
                    error: function(xhr) {
                        let errorMessage = 'प्रकाशन असफल भयो';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMessage = xhr.responseJSON.message;
                        }
                        showAlert('त्रुटि', errorMessage, 'error');
                        button.prop('disabled', false).html('<i class="fas fa-paper-plane"></i> प्रकाशन गर्नुहोस्');
                    }
                });
            });

            // ✅ ADDED: Circular delete confirmation
            $(document).on('click', '.delete-circular-btn', function(e) {
                e.preventDefault();
                
                const form = $(this).closest('form');
                const circularTitle = $(this).data('circular-title') || 'यो सूचना';
                
                if (confirm(`के तपाईं ${circularTitle} लाई मेट्न चाहनुहुन्छ? यो कार्य पूर्ववत गर्न सकिँदैन।`)) {
                    form.submit();
                }
            });

            // ✅ ADDED: Contact message delete functionality
            $(document).on('submit', 'form[action*="contacts"][method="DELETE"]', function(e) {
                if (!confirm('के तपाईं यो सन्देश मेटाउन निश्चित हुनुहुन्छ? यो कार्य पूर्ववत गर्न सकिँदैन।')) {
                    e.preventDefault();
                    return false;
                }
                
                // Show loading state
                const button = $(this).find('button[type="submit"]');
                const originalHtml = button.html();
                button.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> मेट्दै...');
                
                return true;
            });

            // ✅ ADDED: Bulk actions for circulars
            $(document).on('change', '.circular-bulk-select', function() {
                const checkedCount = $('.circular-bulk-select:checked').length;
                const bulkActions = $('.circular-bulk-actions');
                
                if (checkedCount > 0) {
                    bulkActions.fadeIn();
                    $('.bulk-action-count').text(checkedCount);
                } else {
                    bulkActions.fadeOut();
                }
            });

            $(document).on('click', '.bulk-select-all', function() {
                $('.circular-bulk-select').prop('checked', this.checked);
                $('.circular-bulk-select').trigger('change');
            });

            // ✅ ADDED: Circular analytics chart initialization
            function initializeCircularAnalytics() {
                const analyticsChart = document.getElementById('circularAnalyticsChart');
                
                if (analyticsChart) {
                    // Initialize chart here (you can use Chart.js or any other library)
                    console.log('Initializing circular analytics chart...');
                    
                    // Example chart initialization (replace with actual implementation)
                    const ctx = analyticsChart.getContext('2d');
                    // Add your chart initialization code here
                }
            }

            // Initialize analytics when DOM is ready
            initializeCircularAnalytics();
        });
    </script>
    
    <!-- Page-specific JavaScript -->
    @stack('scripts')
</body>
</html>