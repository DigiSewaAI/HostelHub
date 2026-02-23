<!DOCTYPE html>
<html lang="ne" dir="ltr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="robots" content="noindex, nofollow">
    <title>@yield('title', 'ड्यासबोर्ड') - HostelHub Owner</title>
    
    <!-- Favicon -->
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">
    
    <!-- Google Fonts for Nepali -->
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Devanagari:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Font Awesome 6.4.0 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
      crossorigin="anonymous" referrerpolicy="no-referrer">

    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
<meta http-equiv="Pragma" content="no-cache">
<meta http-equiv="Expires" content="0">
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
        
        /* ✅ UPDATED: Mobile-optimized header height */
        .header-content {
            padding: 0.75rem 1rem !important;
            min-height: 64px;
        }
        
        .navbar-brand {
            font-size: 1rem !important;
        }
        
        .notification-button, .dark-mode-toggle {
            padding: 0.5rem !important;
        }
        
        .user-dropdown .btn {
            padding: 0.5rem 0.75rem !important;
            font-size: 0.875rem;
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

        /* ✅ UPDATED: Main content area - Mobile first approach */
        .main-content-area {
            width: 100% !important;
            min-height: 100vh !important;
            display: flex !important;
            flex-direction: column !important;
            transition: all var(--transition-speed) !important;
        }

        /* Desktop sidebar adjustments */
        @media (min-width: 1024px) {
            .main-content-area {
                margin-left: var(--sidebar-width) !important;
                width: calc(100% - var(--sidebar-width)) !important;
            }
            
            .sidebar.collapsed ~ .main-content-area {
                margin-left: var(--sidebar-collapsed-width) !important;
                width: calc(100% - var(--sidebar-collapsed-width)) !important;
            }
        }

        /* ✅ UPDATED: Enhanced mobile sidebar styles */
        @media (max-width: 1023px) {
            .sidebar {
                transform: translateX(-100%) !important;
                width: 280px !important;
                box-shadow: 5px 0 15px rgba(0, 0, 0, 0.2) !important;
                z-index: 1040 !important;
            }
            
            .sidebar.mobile-open {
                transform: translateX(0) !important;
            }
            
            .main-content-area {
                margin-left: 0 !important;
                width: 100vw !important;
            }
            
            /* Fixed header for mobile */
            header.bg-gradient-primary {
                position: fixed !important;
                top: 0 !important;
                left: 0 !important;
                right: 0 !important;
                z-index: 1030 !important;
                box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1) !important;
            }
            
            /* Space for fixed header */
            .page-content {
                padding-top: 64px !important;
            }
            
            /* Mobile overlay with blur effect */
            .sidebar-overlay {
                position: fixed !important;
                inset: 0 !important;
                background: rgba(0, 0, 0, 0.5) !important;
                z-index: 1039 !important;
                backdrop-filter: blur(2px) !important;
            }
            
            /* Prevent body scroll when sidebar is open */
            body.sidebar-open {
                overflow: hidden !important;
            }
            
            /* Mobile-optimized buttons and forms */
            .btn-mobile {
                width: 100% !important;
                margin-bottom: 0.5rem !important;
            }
            
            .form-control-mobile {
                font-size: 16px !important; /* Prevents iOS zoom */
                padding: 0.75rem !important;
            }
            
            /* Mobile table adjustments */
            .table-responsive-mobile {
                overflow-x: auto !important;
                -webkit-overflow-scrolling: touch !important;
            }
            
            /* Mobile grid adjustments */
            .stats-grid-mobile {
                display: grid !important;
                grid-template-columns: repeat(1, 1fr) !important;
                gap: 1rem !important;
            }
        }

        /* ✅ ADDED: Dropdown navigation styles for Payments */
.nav-dropdown-container {
    position: relative;
}

.nav-dropdown-toggle {
    display: flex !important;
    align-items: center;
    cursor: pointer;
    text-decoration: none !important;
}

.dropdown-arrow {
    font-size: 0.8rem;
    transition: transform 0.3s ease;
    margin-left: auto;
}

.nav-dropdown-container.active .dropdown-arrow {
    transform: rotate(90deg);
}

.nav-dropdown-menu {
    display: none;
    padding-left: 1.5rem;
    background: rgba(255, 255, 255, 0.05);
    border-radius: 0.25rem;
    margin: 0.25rem 1rem 0.25rem 0;
    overflow: hidden;
}

.nav-dropdown-menu.show {
    display: block;
}

.nav-dropdown-item {
    display: flex;
    align-items: center;
    padding: 0.6rem 1rem;
    color: rgba(255, 255, 255, 0.8);
    text-decoration: none;
    border-radius: 0.25rem;
    margin-bottom: 0.125rem;
    font-size: 0.9rem;
    transition: all 0.2s;
}

.nav-dropdown-item:hover {
    background: rgba(255, 255, 255, 0.1);
    color: white;
}

.nav-dropdown-item.active {
    background: rgba(255, 255, 255, 0.15);
    color: white;
    font-weight: 600;
}

.nav-dropdown-item i {
    width: 1.25rem;
    text-align: center;
}
        /* Tablet adjustments */
        @media (min-width: 768px) and (max-width: 1023px) {
            .sidebar {
                width: 300px !important;
            }
            
            .stats-grid-mobile {
                grid-template-columns: repeat(2, 1fr) !important;
            }
            
            .header-content {
                padding: 0.75rem 1.5rem !important;
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
            width: 100% !important;
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

        /* ✅ ADDED: Mobile-specific utility classes */
        .mobile-only {
            display: block !important;
        }
        
        .desktop-only {
            display: none !important;
        }
        
        @media (min-width: 1024px) {
            .mobile-only {
                display: none !important;
            }
            
            .desktop-only {
                display: block !important;
            }
        }
        
        /* ✅ ADDED: Touch-friendly tap targets */
        .tap-target {
            min-height: 44px;
            min-width: 44px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        /* ✅ ADDED: Safe area insets for modern mobile devices */
        @supports (padding: max(0px)) {
            .safe-area-top {
                padding-top: max(0.75rem, env(safe-area-inset-top));
            }
            
            .safe-area-bottom {
                padding-bottom: max(0.75rem, env(safe-area-inset-bottom));
            }
            
            .safe-area-left {
                padding-left: max(0.75rem, env(safe-area-inset-left));
            }
            
            .safe-area-right {
                padding-right: max(0.75rem, env(safe-area-inset-right));
            }
        }
        
        /* ✅ ADDED: Loading states for mobile */
        .loading-mobile {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(255, 255, 255, 0.9);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 9999;
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
                <button id="sidebar-collapse" class="text-gray-300 hover:text-white sidebar-text desktop-only" aria-label="साइडबार सङ्कुचित गर्नुहोस्">
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
                
                <!-- Payments - Updated with Dropdown -->
<div class="nav-dropdown-container">
    <a href="javascript:void(0)" 
       class="sidebar-link nav-dropdown-toggle {{ request()->routeIs('owner.payments.*') || request()->routeIs('owner.payment-methods.*') ? 'active' : '' }}">
        <i class="fas fa-money-bill-wave sidebar-icon"></i>
        <span class="sidebar-text">भुक्तानी</span>
        <i class="fas fa-chevron-right dropdown-arrow ms-auto"></i>
    </a>
    <div class="nav-dropdown-menu {{ request()->routeIs('owner.payments.*') || request()->routeIs('owner.payment-methods.*') ? 'show' : '' }}">
        <a href="{{ route('owner.payments.index') }}"
           class="nav-dropdown-item {{ request()->routeIs('owner.payments.index') ? 'active' : '' }}">
            <i class="fas fa-list me-2"></i>
            <span>सबै भुक्तानी</span>
        </a>
        <a href="{{ route('owner.payment-methods.index') }}"
           class="nav-dropdown-item {{ request()->routeIs('owner.payment-methods.*') ? 'active' : '' }}">
            <i class="fas fa-credit-card me-2"></i>
            <span>भुक्तानी विधिहरू</span>
        </a>
    </div>
</div>
                
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
                
                <!-- Room Issues -->
<a href="{{ route('owner.room-issues.index') }}"
   class="sidebar-link {{ request()->routeIs('owner.room-issues.*') ? 'active' : '' }}"
   aria-current="{{ request()->routeIs('owner.room-issues.*') ? 'page' : 'false' }}">
    <i class="fas fa-exclamation-triangle sidebar-icon"></i>
    <span class="sidebar-text">रूम समस्याहरू</span>
</a>

<!-- Hostel Network Dropdown -->
<div class="nav-dropdown-container">
    <a href="javascript:void(0)" 
       class="sidebar-link nav-dropdown-toggle {{ request()->routeIs('network.*') ? 'active' : '' }}">
        <i class="fas fa-network-wired sidebar-icon"></i>
        <span class="sidebar-text">होस्टल नेटवर्क</span>
        <i class="fas fa-chevron-right dropdown-arrow ms-auto"></i>
    </a>
    <div class="nav-dropdown-menu {{ request()->routeIs('network.*') ? 'show' : '' }}">
        <a href="{{ route('network.messages.index') }}"
           class="nav-dropdown-item {{ request()->routeIs('network.messages.index') ? 'active' : '' }}">
            <i class="fas fa-envelope me-2"></i>
            <span>इनबक्स</span>
            @if(isset($unreadCount) && $unreadCount > 0)
                <span class="badge bg-danger ms-auto">{{ $unreadCount }}</span>
            @endif
        </a>
        <a href="{{ route('network.broadcast.index') }}"
           class="nav-dropdown-item {{ request()->routeIs('network.broadcast.*') ? 'active' : '' }}">
            <i class="fas fa-bullhorn me-2"></i>
            <span>प्रसारण</span>
        </a>
        <a href="{{ route('network.marketplace.index') }}"
           class="nav-dropdown-item {{ request()->routeIs('network.marketplace.*') ? 'active' : '' }}">
            <i class="fas fa-store me-2"></i>
            <span>बजार</span>
        </a>
        <a href="{{ route('network.directory.index') }}"
           class="nav-dropdown-item {{ request()->routeIs('network.directory.*') ? 'active' : '' }}">
            <i class="fas fa-address-book me-2"></i>
            <span>निर्देशिका</span>
        </a>
        <a href="{{ route('network.profile.edit') }}"
           class="nav-dropdown-item {{ request()->routeIs('network.profile.edit') ? 'active' : '' }}">
            <i class="fas fa-user me-2"></i>
            <span>प्रोफाइल</span>
        </a>
    </div>
</div>

                <!-- Logout Section -->
                <div class="mt-auto pt-4 border-t border-blue-700">
                    <form method="POST" action="{{ route('logout') }}" id="logout-form">
                        @csrf
                        <button type="submit" class="w-full flex items-center px-2 py-2 text-sm rounded-md hover:bg-blue-700 transition-colors tap-target">
                            <i class="fas fa-sign-out-alt sidebar-icon"></i>
                            <span class="sidebar-text">लगआउट</span>
                        </button>
                    </form>
                </div>
            </nav>
        </aside>

        <!-- Main Content Area - MOBILE FIRST -->
        <div class="main-content-area">
            <!-- Top Navigation -->
            <header class="bg-gradient-primary shadow-sm z-10">
                <div class="flex items-center justify-between px-4 header-content safe-area-top safe-area-left safe-area-right">
                    <div class="flex items-center">
                        <!-- ✅ UPDATED: Mobile sidebar toggle button -->
                        <button id="mobile-sidebar-toggle" class="text-white hover:text-gray-200 mr-3 tap-target mobile-only" 
                                aria-label="मोबाइल साइडबार खोल्नुहोस्">
                            <i class="fas fa-bars text-xl"></i>
                        </button>
                        
                        <!-- Brand with Logo -->
                        <a href="{{ url('/owner/dashboard') }}" class="navbar-brand text-white flex items-center">
                            <!-- MOBILE LOGO WITH FALLBACK -->
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
                            <span class="hidden md:inline text-sm">होस्टलहब - मालिक प्यानल</span>
                            <span class="md:hidden text-xs ml-2">मालिक प्यानल</span>
                        </a>
                    </div>
                    
                    <div class="flex items-center space-x-2">
                        <!-- Owner Info Badge -->
                        <div class="owner-badge hidden md:flex items-center space-x-2">
                            <i class="fas fa-crown"></i>
                            <span>मालिक</span>
                        </div>

                        <!-- Notifications Dropdown (Bootstrap) -->
<div class="dropdown">
    <button class="btn dropdown-toggle notification-button tap-target p-2 text-white hover:text-gray-200 focus:outline-none"
            type="button"
            id="notificationDropdown"
            data-bs-toggle="dropdown"
            aria-expanded="false"
            aria-label="सूचनाहरू">
        <i class="fas fa-bell text-xl"></i>
        @if($unreadCount > 0)
            <span class="absolute top-0 right-0 inline-flex items-center justify-center px-1 py-1 text-xs font-bold leading-none text-white transform translate-x-1/2 -translate-y-1/2 bg-red-500 rounded-full min-w-[18px] h-[18px]">{{ $unreadCount }}</span>
        @endif
    </button>

    <div class="dropdown-menu dropdown-menu-end shadow-lg rounded-xl border-0 py-2"
         aria-labelledby="notificationDropdown"
         style="width: 320px;">

        <div class="px-4 py-2 bg-gray-100 border-b border-gray-200">
            <h3 class="font-semibold text-gray-700">सूचनाहरू</h3>
        </div>

        <div class="max-h-96 overflow-y-auto">
            @forelse($notifications as $notification)
                @php
                    $data = $notification->data;
                    $url = $data['url'] ?? '#';
                    $message = $data['message'] ?? 'नयाँ सूचना';
                    $isUnread = is_null($notification->read_at);
                    $icon = $data['type'] ?? 'bell';
                @endphp
                <a href="{{ $url }}"
                   class="dropdown-item flex items-start px-4 py-3 hover:bg-gray-50 border-b border-gray-100 {{ $isUnread ? 'bg-blue-50' : '' }}"
                   onclick="event.preventDefault(); markNotificationAsRead('{{ $notification->id }}', '{{ $url }}');">
                    <div class="flex-shrink-0 mr-3">
                        <div class="bg-indigo-100 rounded-full p-2">
                            <i class="fas fa-{{ $icon }} text-indigo-600"></i>
                        </div>
                    </div>
                    <div class="flex-1">
                        <p class="text-sm text-gray-800 {{ $isUnread ? 'font-semibold' : '' }}">{{ $message }}</p>
                        <p class="text-xs text-gray-500 mt-1">{{ $notification->created_at->diffForHumans() }}</p>
                    </div>
                </a>
            @empty
                <div class="px-4 py-6 text-center text-gray-500">
                    कुनै सूचना छैन।
                </div>
            @endforelse
        </div>

        <div class="px-4 py-2 bg-gray-50 border-t border-gray-200 text-center">
            <a href="{{ route('owner.notifications.index') }}" class="text-indigo-600 text-sm hover:underline">
                सबै सूचनाहरू हेर्नुहोस्
            </a>
        </div>
    </div>
</div>
                        
                        <!-- User Profile Dropdown -->
                        <div class="dropdown user-dropdown">
                            <button class="btn btn-outline-light dropdown-toggle d-flex align-items-center tap-target" 
                                    type="button" 
                                    id="userDropdown" 
                                    data-bs-toggle="dropdown" 
                                    aria-expanded="false"
                                    aria-label="प्रयोगकर्ता मेनु">
                                <i class="fas fa-user-circle me-2"></i>
                                <span class="d-none d-md-inline">{{ Auth::user()->name ?? 'प्रयोगकर्ता' }}</span>
                                <span class="d-md-none"><i class="fas fa-chevron-down"></i></span>
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

            <!-- Main Content Container -->
            <div class="main-content-container">
                <!-- Page Content -->
                <main id="main-content" class="page-content safe-area-left safe-area-right safe-area-bottom">
                    <!-- Flash Messages -->
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show mb-4 rounded-xl" role="alert">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-check-circle me-2"></i>
                                <strong class="nepali">{{ session('success') }}</strong>
                            </div>
                            <button type="button" class="btn-close tap-target" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show mb-4 rounded-xl" role="alert">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-exclamation-circle me-2"></i>
                                <strong class="nepali">{{ session('error') }}</strong>
                            </div>
                            <button type="button" class="btn-close tap-target" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

<!-- Breadcrumbs -->
@if(View::hasSection('breadcrumbs'))
    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb">
            @yield('breadcrumbs')
        </ol>
    </nav>
@endif

                    <!-- Page Content -->
                    @yield('content')
                </main>
            </div>
        </div>
    </div>

    <!-- Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    <!-- Vite Manifest Fallback Detection -->
    <script>
        // Check if Vite assets loaded properly
        document.addEventListener('DOMContentLoaded', function() {

            // ✅ ADDED: Dropdown navigation functionality for Payments
const dropdownToggles = document.querySelectorAll('.nav-dropdown-toggle');
dropdownToggles.forEach(toggle => {
    toggle.addEventListener('click', function(e) {
        if (window.innerWidth >= 1024) { // Desktop मा मात्र
            e.preventDefault();
            const container = this.closest('.nav-dropdown-container');
            const menu = container.querySelector('.nav-dropdown-menu');
            
            // Toggle current dropdown
            menu.classList.toggle('show');
            container.classList.toggle('active');
            
            // Close other dropdowns
            document.querySelectorAll('.nav-dropdown-container').forEach(otherContainer => {
                if (otherContainer !== container) {
                    otherContainer.classList.remove('active');
                    const otherMenu = otherContainer.querySelector('.nav-dropdown-menu');
                    if (otherMenu) otherMenu.classList.remove('show');
                }
            });
        }
    });
});

// Close dropdowns when clicking outside (desktop only)
document.addEventListener('click', function(e) {
    if (window.innerWidth >= 1024) {
        const dropdowns = document.querySelectorAll('.nav-dropdown-container');
        dropdowns.forEach(container => {
            if (!container.contains(e.target)) {
                container.classList.remove('active');
                const menu = container.querySelector('.nav-dropdown-menu');
                if (menu) menu.classList.remove('show');
            }
        });
    }
});

// Mobile मा auto open गर्ने (यदि active भएमा)
if (window.innerWidth < 1024) {
    const activeDropdowns = document.querySelectorAll('.nav-dropdown-container.active');
    activeDropdowns.forEach(container => {
        const menu = container.querySelector('.nav-dropdown-menu');
        if (menu) menu.classList.add('show');
    });
}
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

    <!-- ✅ UPDATED: Enhanced JavaScript with mobile-first approach -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Sidebar elements
            const sidebar = document.getElementById('sidebar');
            const sidebarCollapse = document.getElementById('sidebar-collapse');
            const mobileSidebarToggle = document.getElementById('mobile-sidebar-toggle');
            const body = document.body;
            
            // ✅ ENHANCED: Desktop sidebar collapse
            if (sidebarCollapse) {
                sidebarCollapse.addEventListener('click', function() {
                    sidebar.classList.toggle('collapsed');
                    
                    // Update aria-expanded
                    const isCollapsed = sidebar.classList.contains('collapsed');
                    this.setAttribute('aria-expanded', !isCollapsed);
                    this.setAttribute('aria-label', isCollapsed ? 
                        'साइडबार विस्तार गर्नुहोस्' : 'साइडबार सङ्कुचित गर्नुहोस्');
                    
                    // Save preference
                    localStorage.setItem('sidebarCollapsed', isCollapsed);
                });
                
                // Load saved preference
                const savedCollapsed = localStorage.getItem('sidebarCollapsed') === 'true';
                if (savedCollapsed) {
                    sidebar.classList.add('collapsed');
                    sidebarCollapse.setAttribute('aria-expanded', false);
                    sidebarCollapse.setAttribute('aria-label', 'साइडबार विस्तार गर्नुहोस्');
                }
            }
            
            // ✅ ENHANCED: Mobile sidebar toggle with improved UX
            if (mobileSidebarToggle) {
                mobileSidebarToggle.addEventListener('click', function(e) {
                    e.stopPropagation();
                    const isOpening = !sidebar.classList.contains('mobile-open');
                    
                    sidebar.classList.toggle('mobile-open');
                    body.classList.toggle('sidebar-open', isOpening);
                    
                    // Create or remove overlay
                    if (isOpening) {
                        const overlay = document.createElement('div');
                        overlay.className = 'sidebar-overlay';
                        overlay.setAttribute('aria-hidden', 'true');
                        overlay.addEventListener('click', function() {
                            sidebar.classList.remove('mobile-open');
                            body.classList.remove('sidebar-open');
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
                    this.setAttribute('aria-expanded', isOpening);
                    this.setAttribute('aria-label', isOpening ? 
                        'मोबाइल साइडबार बन्द गर्नुहोस्' : 'मोबाइल साइडबार खोल्नुहोस्');
                });
            }
            
            // ✅ ADDED: Close mobile sidebar when clicking outside on mobile
            document.addEventListener('click', function(e) {
                if (window.innerWidth < 1024 && 
                    sidebar.classList.contains('mobile-open') &&
                    !sidebar.contains(e.target) && 
                    e.target.id !== 'mobile-sidebar-toggle' &&
                    !e.target.closest('#mobile-sidebar-toggle')) {
                    
                    sidebar.classList.remove('mobile-open');
                    body.classList.remove('sidebar-open');
                    const overlay = document.querySelector('.sidebar-overlay');
                    if (overlay) {
                        document.body.removeChild(overlay);
                    }
                    
                    if (mobileSidebarToggle) {
                        mobileSidebarToggle.setAttribute('aria-expanded', false);
                        mobileSidebarToggle.setAttribute('aria-label', 'मोबाइल साइडबार खोल्नुहोस्');
                    }
                }
            });
            
            // Initialize tooltips
            const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            const tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });
            
            // ✅ ENHANCED: Auto-dismiss alerts after 5 seconds (mobile friendly)
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(function(alert) {
                setTimeout(function() {
                    if (alert && alert.classList.contains('show')) {
                        const bsAlert = new bootstrap.Alert(alert);
                        bsAlert.close();
                    }
                }, 5000);
            });
            
            // ✅ ENHANCED: Smooth scrolling for skip link
            const skipLink = document.querySelector('.skip-link');
            const mainContent = document.getElementById('main-content');
            
            if (skipLink && mainContent) {
                skipLink.addEventListener('click', function(e) {
                    e.preventDefault();
                    mainContent.scrollIntoView({ behavior: 'smooth' });
                    mainContent.setAttribute('tabindex', '-1');
                    mainContent.focus();
                    
                    // Remove tabindex after focus
                    setTimeout(() => {
                        mainContent.removeAttribute('tabindex');
                    }, 1000);
                });
            }
            
            // ✅ ENHANCED: Keyboard navigation improvements
            document.addEventListener('keydown', function(e) {
                // Close mobile sidebar on Escape
                if (e.key === 'Escape') {
                    if (window.innerWidth < 1024 && sidebar.classList.contains('mobile-open')) {
                        sidebar.classList.remove('mobile-open');
                        body.classList.remove('sidebar-open');
                        const overlay = document.querySelector('.sidebar-overlay');
                        if (overlay) {
                            document.body.removeChild(overlay);
                        }
                        
                        if (mobileSidebarToggle) {
                            mobileSidebarToggle.setAttribute('aria-expanded', false);
                            mobileSidebarToggle.setAttribute('aria-label', 'मोबाइल साइडबार खोल्नुहोस्');
                        }
                    }
                    
                    // Close dropdowns on Escape
                    const openDropdowns = document.querySelectorAll('.dropdown-menu.show');
                    openDropdowns.forEach(function(dropdown) {
                        const dropdownInstance = bootstrap.Dropdown.getInstance(dropdown.previousElementSibling);
                        if (dropdownInstance) {
                            dropdownInstance.hide();
                        }
                    });
                }
                
                // Handle tab navigation in mobile sidebar
                if (e.key === 'Tab' && sidebar.classList.contains('mobile-open')) {
                    const focusableElements = sidebar.querySelectorAll('button, a, input, select, textarea, [tabindex]:not([tabindex="-1"])');
                    const firstElement = focusableElements[0];
                    const lastElement = focusableElements[focusableElements.length - 1];
                    
                    if (e.shiftKey && document.activeElement === firstElement) {
                        e.preventDefault();
                        lastElement.focus();
                    } else if (!e.shiftKey && document.activeElement === lastElement) {
                        e.preventDefault();
                        firstElement.focus();
                    }
                }
            });
            
            // ✅ ADDED: Touch gesture support for mobile sidebar
            let touchStartX = 0;
            let touchEndX = 0;
            
            document.addEventListener('touchstart', function(e) {
                touchStartX = e.changedTouches[0].screenX;
            }, { passive: true });
            
            document.addEventListener('touchend', function(e) {
                touchEndX = e.changedTouches[0].screenX;
                handleSwipe();
            }, { passive: true });
            
            function handleSwipe() {
                const swipeThreshold = 50;
                const swipeDistance = touchEndX - touchStartX;
                
                // Swipe right to open sidebar (only from left edge on mobile)
                if (swipeDistance > swipeThreshold && touchStartX < 50 && window.innerWidth < 1024) {
                    if (!sidebar.classList.contains('mobile-open')) {
                        mobileSidebarToggle.click();
                    }
                }
                // Swipe left to close sidebar
                else if (swipeDistance < -swipeThreshold && window.innerWidth < 1024) {
                    if (sidebar.classList.contains('mobile-open')) {
                        sidebar.classList.remove('mobile-open');
                        body.classList.remove('sidebar-open');
                        const overlay = document.querySelector('.sidebar-overlay');
                        if (overlay) {
                            document.body.removeChild(overlay);
                        }
                    }
                }
            }
            
            // ✅ ADDED: Focus trap for mobile sidebar
            function trapFocus(element) {
                const focusableElements = element.querySelectorAll('button, a, input, select, textarea, [tabindex]:not([tabindex="-1"])');
                const firstFocusable = focusableElements[0];
                const lastFocusable = focusableElements[focusableElements.length - 1];
                
                element.addEventListener('keydown', function(e) {
                    if (e.key === 'Tab') {
                        if (e.shiftKey && document.activeElement === firstFocusable) {
                            e.preventDefault();
                            lastFocusable.focus();
                        } else if (!e.shiftKey && document.activeElement === lastFocusable) {
                            e.preventDefault();
                            firstFocusable.focus();
                        }
                    }
                });
            }
            
            // Apply focus trap when mobile sidebar opens
            const observer = new MutationObserver(function(mutations) {
                mutations.forEach(function(mutation) {
                    if (mutation.attributeName === 'class') {
                        if (sidebar.classList.contains('mobile-open')) {
                            trapFocus(sidebar);
                        }
                    }
                });
            });
            
            observer.observe(sidebar, { attributes: true });
            
            // ✅ ADDED: Mobile loading state handler
            window.showMobileLoading = function() {
                const loadingDiv = document.createElement('div');
                loadingDiv.className = 'loading-mobile';
                loadingDiv.innerHTML = `
                    <div class="text-center">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">लोड हुदैछ...</span>
                        </div>
                        <p class="mt-2 text-gray-600">लोड हुदैछ...</p>
                    </div>
                `;
                document.body.appendChild(loadingDiv);
            };
            
            window.hideMobileLoading = function() {
                const loadingDiv = document.querySelector('.loading-mobile');
                if (loadingDiv) {
                    document.body.removeChild(loadingDiv);
                }
            };
            
            // ✅ ADDED: Mobile form submission handling
            document.addEventListener('submit', function(e) {
                const form = e.target;
                if (form.method === 'post' || form.method === 'POST') {
                    const submitButton = form.querySelector('button[type="submit"]');
                    if (submitButton && window.innerWidth < 768) {
                        submitButton.disabled = true;
                        submitButton.innerHTML = '<i class="fas fa-spinner fa-spin"></i> प्रक्रिया हुदैछ...';
                    }
                }
            });
        });
        
        // ✅ ENHANCED: Handle window resize with debouncing
        let resizeTimeout;
        window.addEventListener('resize', function() {
            clearTimeout(resizeTimeout);
            resizeTimeout = setTimeout(function() {
                const sidebar = document.getElementById('sidebar');
                const body = document.body;
                
                // Close mobile sidebar on desktop
                if (window.innerWidth >= 1024 && sidebar) {
                    sidebar.classList.remove('mobile-open');
                    body.classList.remove('sidebar-open');
                    const overlay = document.querySelector('.sidebar-overlay');
                    if (overlay) {
                        document.body.removeChild(overlay);
                    }
                    
                    // Reset mobile sidebar toggle state
                    const mobileSidebarToggle = document.getElementById('mobile-sidebar-toggle');
                    if (mobileSidebarToggle) {
                        mobileSidebarToggle.setAttribute('aria-expanded', false);
                        mobileSidebarToggle.setAttribute('aria-label', 'मोबाइल साइडबार खोल्नुहोस्');
                    }
                }
                
                // Show/hide elements based on screen size
                const mobileOnlyElements = document.querySelectorAll('.mobile-only');
                const desktopOnlyElements = document.querySelectorAll('.desktop-only');
                
                if (window.innerWidth >= 1024) {
                    mobileOnlyElements.forEach(el => el.style.display = 'none');
                    desktopOnlyElements.forEach(el => el.style.display = 'block');
                } else {
                    mobileOnlyElements.forEach(el => el.style.display = 'block');
                    desktopOnlyElements.forEach(el => el.style.display = 'none');
                }
            }, 250);
        });
        
        // Initial check for screen size
        window.dispatchEvent(new Event('resize'));
    </script>

    <!-- ✅ ADDED: Enhanced mobile form reset and circular functionality JavaScript -->
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
                
                // Show success message for mobile
                if (window.innerWidth < 768) {
                    const alertDiv = document.createElement('div');
                    alertDiv.className = 'alert alert-success alert-dismissible fade show mb-4 rounded-xl';
                    alertDiv.innerHTML = `
                        <div class="d-flex align-items-center">
                            <i class="fas fa-check-circle me-2"></i>
                            <strong class="nepali">सफलता:</strong> फारम सफलतापूर्वक रिसेट गरियो
                        </div>
                        <button type="button" class="btn-close tap-target" data-bs-dismiss="alert" aria-label="Close"></button>
                    `;
                    const mainContent = document.getElementById('main-content');
                    if (mainContent) {
                        mainContent.insertBefore(alertDiv, mainContent.firstChild);
                    }
                }
                
                console.log('Form reset completed for circular creation');
            @endif

            // ✅ ENHANCED: AJAX form submission handling for circulars (mobile optimized)
            $(document).on('submit', 'form[data-ajax-form="true"]', function(e) {
                e.preventDefault();
                
                const form = $(this);
                const submitBtn = form.find('button[type="submit"]');
                const originalText = submitBtn.html();
                
                // Show mobile loading if on small screen
                if (window.innerWidth < 768) {
                    window.showMobileLoading();
                }
                
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
                                
                                // Mobile-specific feedback
                                if (window.innerWidth < 768) {
                                    // Add haptic feedback if available
                                    if (navigator.vibrate) {
                                        navigator.vibrate([100, 50, 100]);
                                    }
                                }
                            }
                            
                            // Redirect if specified
                            if (response.redirect) {
                                setTimeout(() => {
                                    window.location.href = response.redirect;
                                }, 1500);
                            }
                            
                            // Close mobile sidebar if open
                            if (window.innerWidth < 1024) {
                                const sidebar = document.getElementById('sidebar');
                                if (sidebar && sidebar.classList.contains('mobile-open')) {
                                    sidebar.classList.remove('mobile-open');
                                    document.body.classList.remove('sidebar-open');
                                    const overlay = document.querySelector('.sidebar-overlay');
                                    if (overlay) {
                                        document.body.removeChild(overlay);
                                    }
                                }
                            }
                        }
                    },
                    error: function(xhr) {
                        let errorMessage = 'अनुरोध असफल भयो';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMessage = xhr.responseJSON.message;
                        }
                        showAlert('त्रुटि', errorMessage, 'error');
                        
                        // Mobile-specific error handling
                        if (window.innerWidth < 768) {
                            // Scroll to top to show error
                            window.scrollTo({ top: 0, behavior: 'smooth' });
                            
                            // Add haptic feedback for error
                            if (navigator.vibrate) {
                                navigator.vibrate([200, 100, 200]);
                            }
                        }
                    },
                    complete: function() {
                        submitBtn.prop('disabled', false).html(originalText);
                        
                        // Hide mobile loading
                        if (window.innerWidth < 768) {
                            window.hideMobileLoading();
                        }
                    }
                });
            });

            // Helper function to show alerts (mobile optimized)
            function showAlert(title, message, type) {
                const alertClass = type === 'success' ? 'alert-success' : 'alert-danger';
                const icon = type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle';
                
                const alertHtml = `
                    <div class="alert ${alertClass} alert-dismissible fade show mb-4 rounded-xl" role="alert">
                        <div class="d-flex align-items-center">
                            <i class="fas ${icon} me-2"></i>
                            <strong class="nepali">${title}:</strong> ${message}
                        </div>
                        <button type="button" class="btn-close tap-target" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                `;
                
                // Prepend alert to main content
                const mainContent = document.getElementById('main-content');
                if (mainContent) {
                    // Remove any existing alerts
                    const existingAlerts = mainContent.querySelectorAll('.alert');
                    existingAlerts.forEach(alert => alert.remove());
                    
                    // Add new alert
                    mainContent.insertAdjacentHTML('afterbegin', alertHtml);
                    
                    // Scroll to top on mobile to show alert
                    if (window.innerWidth < 768) {
                        window.scrollTo({ top: 0, behavior: 'smooth' });
                    }
                }
                
                // Auto remove after 5 seconds
                setTimeout(() => {
                    const alert = document.querySelector('.alert');
                    if (alert) {
                        const bsAlert = new bootstrap.Alert(alert);
                        bsAlert.close();
                    }
                }, 5000);
            }

            // ✅ ENHANCED: Real-time circular notifications (mobile optimized)
            function checkNewCirculars() {
                // Only check if user is active (not on mobile with screen off)
                if (document.hidden) return;
                
                $.ajax({
                    url: '{{ route("owner.circulars.index") }}?check_new=true',
                    method: 'GET',
                    success: function(response) {
                        if (response.new_circulars && response.new_circulars > 0) {
                            // Update notification badge
                            const badge = $('.notification-dot');
                            if (badge.length) {
                                badge.text(response.new_circulars);
                                badge.css({
                                    'width': '12px',
                                    'height': '12px',
                                    'display': 'flex',
                                    'align-items': 'center',
                                    'justify-content': 'center',
                                    'font-size': '8px',
                                    'color': 'white'
                                });
                                badge.show();
                            }
                            
                            // Show notification only if not on the circulars page
                            if (!window.location.pathname.includes('/owner/circulars')) {
                                if (response.new_circulars === 1) {
                                    showAlert('नयाँ सूचना', 'तपाईंसँग १ नयाँ सूचना छ', 'info');
                                } else {
                                    showAlert('नयाँ सूचनाहरू', `तपाईंसँग ${response.new_circulars} नयाँ सूचनाहरू छन्`, 'info');
                                }
                                
                                // Mobile notification sound/vibration
                                if (window.innerWidth < 768) {
                                    // Play notification sound if allowed
                                    try {
                                        const audio = new Audio('{{ asset("sounds/notification.mp3") }}');
                                        audio.volume = 0.3;
                                        audio.play();
                                    } catch (e) {
                                        console.log('Audio notification failed');
                                    }
                                    
                                    // Vibrate if available
                                    if (navigator.vibrate) {
                                        navigator.vibrate([100, 50, 100]);
                                    }
                                }
                            }
                        }
                    }
                });
            }

            // Check for new circulars every 30 seconds, but only when page is visible
            let circularCheckInterval;
            
            function startCircularChecker() {
                if (!circularCheckInterval) {
                    circularCheckInterval = setInterval(checkNewCirculars, 30000);
                }
            }
            
            function stopCircularChecker() {
                if (circularCheckInterval) {
                    clearInterval(circularCheckInterval);
                    circularCheckInterval = null;
                }
            }
            
            // Start/stop based on page visibility
            document.addEventListener('visibilitychange', function() {
                if (document.hidden) {
                    stopCircularChecker();
                } else {
                    startCircularChecker();
                    checkNewCirculars(); // Check immediately when page becomes visible
                }
            });
            
            // Start the checker
            startCircularChecker();

            // ✅ ENHANCED: Circular publish functionality (mobile friendly)
            $(document).on('click', '.publish-circular-btn', function() {
                const circularId = $(this).data('circular-id');
                const button = $(this);
                
                // Mobile-friendly confirmation
                const confirmMessage = 'के तपाईं यो सूचना प्रकाशित गर्न चाहनुहुन्छ?';
                
                if (window.innerWidth < 768) {
                    // Use custom modal for mobile
                    if (confirm(confirmMessage)) {
                        proceedWithPublish();
                    }
                } else {
                    if (confirm(confirmMessage)) {
                        proceedWithPublish();
                    }
                }
                
                function proceedWithPublish() {
                    button.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> प्रकाशन हुदैछ...');
                    
                    // Show loading on mobile
                    if (window.innerWidth < 768) {
                        window.showMobileLoading();
                    }
                    
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
                                
                                // Mobile success feedback
                                if (window.innerWidth < 768) {
                                    if (navigator.vibrate) {
                                        navigator.vibrate([100]);
                                    }
                                }
                                
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
                        },
                        complete: function() {
                            if (window.innerWidth < 768) {
                                window.hideMobileLoading();
                            }
                        }
                    });
                }
            });

            // ✅ ENHANCED: Circular delete confirmation (mobile friendly)
            $(document).on('click', '.delete-circular-btn', function(e) {
                e.preventDefault();
                
                const form = $(this).closest('form');
                const circularTitle = $(this).data('circular-title') || 'यो सूचना';
                const confirmMessage = `के तपाईं ${circularTitle} लाई मेट्न चाहनुहुन्छ? यो कार्य पूर्ववत गर्न सकिँदैन।`;
                
                if (window.innerWidth < 768) {
                    // Mobile-friendly confirmation with custom styling
                    const mobileConfirm = confirm(confirmMessage);
                    if (mobileConfirm) {
                        // Show loading on mobile
                        window.showMobileLoading();
                        form.submit();
                    }
                } else {
                    if (confirm(confirmMessage)) {
                        form.submit();
                    }
                }
            });

            // ✅ ADDED: Contact message delete functionality (mobile optimized)
            $(document).on('submit', 'form[action*="contacts"][method="DELETE"]', function(e) {
                const confirmMessage = 'के तपाईं यो सन्देश मेटाउन निश्चित हुनुहुन्छ? यो कार्य पूर्ववत गर्न सकिँदैन।';
                
                if (!confirm(confirmMessage)) {
                    e.preventDefault();
                    return false;
                }
                
                // Show loading state with mobile optimization
                const button = $(this).find('button[type="submit"]');
                const originalHtml = button.html();
                
                if (window.innerWidth < 768) {
                    button.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> मेट्दै...');
                    window.showMobileLoading();
                } else {
                    button.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> मेट्दै...');
                }
                
                return true;
            });

            // ✅ ADDED: Bulk actions for circulars (mobile optimized)
            $(document).on('change', '.circular-bulk-select', function() {
                const checkedCount = $('.circular-bulk-select:checked').length;
                const bulkActions = $('.circular-bulk-actions');
                
                if (checkedCount > 0) {
                    bulkActions.fadeIn();
                    $('.bulk-action-count').text(checkedCount);
                    
                    // On mobile, scroll to bulk actions
                    if (window.innerWidth < 768) {
                        bulkActions[0].scrollIntoView({ behavior: 'smooth', block: 'nearest' });
                    }
                } else {
                    bulkActions.fadeOut();
                }
            });

            $(document).on('click', '.bulk-select-all', function() {
                $('.circular-bulk-select').prop('checked', this.checked);
                $('.circular-bulk-select').trigger('change');
            });

            // ✅ ADDED: Mobile swipe actions for circular items
            let touchStartY = 0;
            let touchEndY = 0;
            
            $(document).on('touchstart', '.circular-item', function(e) {
                touchStartY = e.originalEvent.touches[0].clientY;
            });
            
            $(document).on('touchmove', '.circular-item', function(e) {
                if (window.innerWidth < 768) {
                    e.preventDefault();
                }
            });
            
            $(document).on('touchend', '.circular-item', function(e) {
                touchEndY = e.originalEvent.changedTouches[0].clientY;
                const swipeDistance = touchEndY - touchStartY;
                
                // Only trigger on significant vertical swipe
                if (Math.abs(swipeDistance) > 100) {
                    $(this).css('transform', swipeDistance > 0 ? 'translateY(100%)' : 'translateY(-100%)');
                    setTimeout(() => {
                        $(this).remove();
                    }, 300);
                }
            });

            // ✅ ADDED: Mobile offline detection
            window.addEventListener('online', function() {
                showAlert('सफलता', 'इन्टरनेट जडान पुनः स्थापित भयो', 'success');
            });
            
            window.addEventListener('offline', function() {
                showAlert('चेतावनी', 'इन्टरनेट जडान हराइरहेको छ', 'warning');
            });

            // ✅ ADDED: Mobile back button handling
            if (window.innerWidth < 768) {
                let backButtonPressed = false;
                
                // Detect back/forward button
                window.addEventListener('popstate', function() {
                    backButtonPressed = true;
                    
                    // Close mobile sidebar if open
                    const sidebar = document.getElementById('sidebar');
                    if (sidebar && sidebar.classList.contains('mobile-open')) {
                        sidebar.classList.remove('mobile-open');
                        document.body.classList.remove('sidebar-open');
                        const overlay = document.querySelector('.sidebar-overlay');
                        if (overlay) {
                            document.body.removeChild(overlay);
                        }
                    }
                });
                
                // Add history entry for mobile pages
                if (!window.history.state) {
                    window.history.replaceState({ page: 'dashboard' }, '', window.location.href);
                }
            }
        });
    </script>
    
    <!-- Page-specific JavaScript -->
    @stack('scripts')
    <div id="toast-container" class="toast-container position-fixed bottom-0 end-0 p-3" style="z-index: 11"></div>
</body>

<!-- Real-time Notification via Pusher (यदि सक्षम छ भने मात्र लोड हुने) -->
@if(config('broadcasting.default') === 'pusher')
<script src="https://js.pusher.com/7.0/pusher.min.js"></script>

<script>
    // Unread count badge अपडेट गर्ने function (पहिले polling बाट हटाइएको)
    function updateUnreadCount() {
        fetch('/owner/notifications/unread-count')
            .then(res => res.json())
            .then(data => {
                const badge = document.querySelector('#notificationDropdown span.absolute');
                const button = document.querySelector('#notificationDropdown');
                if (data.count > 0) {
                    if (badge) {
                        badge.textContent = data.count;
                        badge.style.display = 'inline-flex';
                    } else {
                        const newBadge = document.createElement('span');
                        newBadge.className = 'absolute top-0 right-0 inline-flex items-center justify-center px-1 py-1 text-xs font-bold leading-none text-white transform translate-x-1/2 -translate-y-1/2 bg-red-500 rounded-full min-w-[18px] h-[18px]';
                        newBadge.textContent = data.count;
                        button.appendChild(newBadge);
                    }
                } else {
                    if (badge) badge.style.display = 'none';
                }
            })
            .catch(err => console.error('Failed to fetch unread count:', err));
    }

    document.addEventListener('DOMContentLoaded', function() {
        // पहिलो पटक unread count ल्याउनुहोस्
        updateUnreadCount();

        // Echo चालू छ कि छैन जाँच गर्नुहोस्
        if (typeof Echo !== 'undefined') {
            Echo.private('App.Models.User.' + {{ auth()->id() }})
                .notification((notification) => {
                    // Unread count badge अपडेट गर्नुहोस्
                    updateUnreadCount();

                    // Toast मा सन्देश देखाउनुहोस् (notification.data बाट message लिने)
                    const message = notification.data?.message || notification.message || 'नयाँ सूचना';
                    showNotificationAlert(message);
                });
        }
    });

    // नयाँ notification आउँदा सानो Toast देखाउने
    function showNotificationAlert(message) {
        let toastHtml = `<div class="toast align-items-center text-white bg-primary border-0" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="d-flex">
                <div class="toast-body">${message}</div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
            </div>
        </div>`;
        document.getElementById('toast-container').insertAdjacentHTML('beforeend', toastHtml);
        let toastElement = document.querySelector('.toast:last-child');
        let toast = new bootstrap.Toast(toastElement);
        toast.show();
    }
</script>
@endif
<script>
    function markNotificationAsRead(notificationId, url) {
        // यहाँ URL लाई owner prefix सहित बनाउनुहोस्
        fetch('/owner/notifications/mark-read/' + notificationId, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json',
                'Accept': 'application/json',
            },
        }).finally(() => {
            // सफल होस् वा नहोस्, अन्तमा redirect गर्ने
            window.location.href = url;
        });
    }
</script>
</html>