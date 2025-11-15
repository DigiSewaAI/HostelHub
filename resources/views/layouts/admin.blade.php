<!DOCTYPE html>
<html lang="ne" dir="ltr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="robots" content="noindex, nofollow">
    <title>@yield('title', 'ड्यासबोर्ड') - HostelHub Admin</title>
    
    <!-- Favicon -->
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">
    
    <!-- Google Fonts for Nepali -->
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Devanagari:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Font Awesome 6.4.0 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
          integrity="sha512-iecdLmaskl7CVskpV0uYGFkTd73EVdjGN7teJQ8N+2ER5yiJHHIyMI1GAa5I80LzvcpbKjByZcXc9j5QFZUvSJQ=="
          crossorigin="anonymous" referrerpolicy="no-referrer">

    <!-- 🔥 CRITICAL: Vite Asset Loading - Fixed -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- 🔥 BACKUP: Manual CSS Load if Vite fails -->
    @production
        @if(file_exists(public_path('build/assets/app.css')))
            <link rel="stylesheet" href="{{ asset('build/assets/app.css') }}">
        @elseif(file_exists(public_path('build/assets/app-DHdFXIum.css')))
            <link rel="stylesheet" href="{{ asset('build/assets/app-DHdFXIum.css') }}">
        @endif
    @endproduction

    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.tailwindcss.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.tailwindcss.min.css">
    
    <!-- Custom Styles -->
    <style>
        :root {
            --sidebar-width: 16rem;
            --sidebar-collapsed-width: 4.5rem;
            --transition-speed: 0.3s;
            --primary-color: #4e73df;
            --primary-dark: #224abe;
            --accent-color: #1cc88a;
            --accent-dark: #13855c;
            --background-color: #f9fafb;
        }
        
        .sidebar {
            width: var(--sidebar-width);
            transition: width var(--transition-speed);
            background: linear-gradient(45deg, var(--primary-color), var(--primary-dark)) !important;
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
            display: none !important;
        }
        
        .sidebar.collapsed .sidebar-icon {
            margin: 0 auto !important;
        }
        
        .dark-mode {
            background-color: #1e293b !important;
            color: #f1f5f9 !important;
        }
        
        .dark-mode .main-content {
            background-color: #1e293b !important;
        }
        
        .dark-mode .sidebar {
            background-color: #1e293b !important;
        }
        
        .dark-mode .dropdown-menu {
            background-color: #334155 !important;
        }
        
        .dark-mode .text-gray-700 {
            color: #f1f5f9 !important;
        }
        
        .dark-mode .bg-white {
            background-color: #334155 !important;
        }
        
        .dark-mode .border-gray-200 {
            border-color: #475569 !important;
        }
        
        .dark-mode .text-gray-500 {
            color: #94a3b8 !important;
        }
        
        .sidebar-link {
            display: flex !important;
            align-items: center !important;
            padding: 0.8rem 1rem !important;
            border-radius: 0.375rem !important;
            color: #ffffff !important;
            transition: all 0.3s !important;
            margin-bottom: 0.25rem !important;
            text-decoration: none !important;
        }
        
        .sidebar-link:hover {
            background-color: rgba(255, 255, 255, 0.15) !important;
            transform: translateX(3px);
            color: white !important;
        }
        
        .sidebar-link i {
            width: 1.5rem !important;
            text-align: center !important;
            margin-right: 0.75rem !important;
            font-size: 1.1rem !important;
        }
        
        .skip-link {
            position: absolute !important;
            top: -40px !important;
            left: 0 !important;
            background: #1e40af !important;
            color: white !important;
            padding: 8px 16px !important;
            z-index: 100 !important;
            transition: top 0.3s !important;
        }
        
        .skip-link:focus {
            top: 0 !important;
        }
        
        .notification-dot {
            position: absolute !important;
            top: 3px !important;
            right: 3px !important;
            width: 8px !important;
            height: 8px !important;
            background-color: #ef4444 !important;
            border-radius: 50% !important;
            z-index: 10 !important;
        }
        
        .notification-button {
            position: relative !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
        }
        
        .notification-button i {
            font-size: 1.25rem !important;
        }
        
        /* साइडबार स्क्रोलबार स्टाइल */
        .sidebar::-webkit-scrollbar {
            width: 6px !important;
        }
        
        .sidebar::-webkit-scrollbar-track {
            background: rgba(0, 0, 0, 0.1) !important;
        }
        
        .sidebar::-webkit-scrollbar-thumb {
            background: rgba(0, 0, 0, 0.3) !important;
            border-radius: 3px !important;
        }
        
        .bg-gradient-primary {
            background: linear-gradient(45deg, var(--primary-color), var(--primary-dark)) !important;
        }
        
        .btn {
            border-radius: 0.5rem !important;
            font-weight: 600 !important;
            padding: 0.5rem 1rem !important;
            display: inline-flex !important;
            align-items: center !important;
            justify-content: center !important;
            transition: all 0.3s !important;
        }
        
        .btn-primary {
            background: linear-gradient(45deg, var(--primary-color), var(--primary-dark)) !important;
            border: none !important;
            box-shadow: 0 2px 5px rgba(78, 115, 223, 0.3) !important;
        }
        
        .btn-primary:hover {
            background: linear-gradient(45deg, var(--primary-dark), var(--primary-color)) !important;
            transform: translateY(-2px) !important;
            box-shadow: 0 4px 8px rgba(78, 115, 223, 0.4) !important;
        }
        
        .btn-success {
            background: linear-gradient(45deg, var(--accent-color), var(--accent-dark)) !important;
            border: none !important;
            box-shadow: 0 2px 5px rgba(28, 200, 138, 0.3) !important;
        }
        
        .btn-success:hover {
            background: linear-gradient(45deg, var(--accent-dark), var(--accent-color)) !important;
            transform: translateY(-2px) !important;
            box-shadow: 0 4px 8px rgba(28, 200, 138, 0.4) !important;
        }
        
        .dropdown-item {
            padding: 0.5rem 1rem !important;
            border-radius: 0.35rem !important;
            margin: 0.1rem 0.25rem !important;
            width: auto !important;
            display: flex !important;
            align-items: center !important;
        }
        
        .dropdown-item:hover {
            background-color: #f8f9fc !important;
        }
        
        .dropdown-menu {
            border: none !important;
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15) !important;
            border-radius: 0.5rem !important;
        }
        
        .nepali {
            font-family: 'Noto Sans Devanagari', sans-serif !important;
        }
        
        /* ✅ FIXED: Proper header height and logo sizing */
        .header-content {
            padding-top: 0.5rem !important;
            padding-bottom: 0.5rem !important;
            min-height: 60px !important;
        }
        
        .navbar-brand {
            font-size: 1rem !important;
        }
        
        .notification-button, .dark-mode-toggle {
            padding: 0.35rem !important;
        }
        
        .user-dropdown .btn {
            padding: 0.35rem 0.65rem !important;
            font-size: 0.875rem !important;
        }
        
        /* ✅ FIXED: Logo Styles - Proper sizing */
        .logo-container {
            display: flex !important;
            align-items: center !important;
            text-decoration: none !important;
            padding: 0.25rem 0 !important;
        }
        
        .logo-img {
            height: 32px !important; /* Reduced from 40px */
            width: auto !important;
            object-fit: contain !important;
        }
        
        .logo-text {
            margin-left: 8px !important;
            color: white !important;
            font-weight: 600 !important;
            font-size: 16px !important; /* Reduced from 18px */
        }
        
        .mobile-logo {
            height: 28px !important; /* Reduced from 32px */
            width: auto !important;
        }
        
        /* Component-specific styles */
        .alert-dismissible {
            transition: opacity 0.5s !important;
        }

        /* 🔥 FIX: Mobile sidebar classes */
        .sidebar-mobile {
            transform: translateX(-100%) !important;
        }
        
        .sidebar-mobile.open {
            transform: translateX(0) !important;
        }

        /* 🔥 CRITICAL: Force Tailwind utilities */
        .flex { display: flex !important; }
        .hidden { display: none !important; }
        .block { display: block !important; }
        .lg\:hidden { 
            display: none !important; 
        }
        
        @media (min-width: 1024px) {
            .lg\:hidden { 
                display: none !important; 
            }
            .lg\:block { 
                display: block !important; 
            }
            .lg\:flex {
                display: flex !important;
            }
            .lg\:relative {
                position: relative !important;
            }
        }

        /* 🚨 EMERGENCY WHITE SPACE FIX - CRITICAL */
        .main-content-spacing {
            margin-left: 16rem !important;
            width: calc(100vw - 16rem) !important;
            max-width: none !important;
            transition: margin-left 0.3s !important;
        }
        
        .main-content-spacing.collapsed {
            margin-left: 4.5rem !important;
            width: calc(100vw - 4.5rem) !important;
        }

        .max-w-7xl {
            max-width: none !important;
            width: 100% !important;
            margin: 0 !important;
            padding: 0 1rem !important;
        }

        main#main-content {
            padding: 1rem !important;
            margin: 0 !important;
            width: 100% !important;
        }

        .bg-white.rounded-xl {
            border-radius: 0.5rem !important;
            margin: 0 !important;
            width: 100% !important;
        }

        /* Fix main content container */
        .main-content {
            margin-left: 0 !important;
            width: 100% !important;
            max-width: none !important;
        }

        /* Remove any extra padding/margin from main content */
        #main-content {
            padding: 0 !important;
            margin: 0 !important;
        }

        /* Fix the main content area */
        .flex-1.overflow-y-auto {
            width: 100% !important;
            margin: 0 !important;
            padding: 0 !important;
        }

        /* Ensure content uses full available space */
        .bg-white.dark\:bg-gray-700.rounded-xl {
            border-radius: 0.5rem !important;
            margin: 0 !important;
            min-height: calc(100vh - 8rem) !important;
        }

        /* ✅ ADDED: Circular specific styles for admin */
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
        
        .circular-stats-card {
            background: linear-gradient(45deg, #4e73df, #224abe);
            color: white;
            border-radius: 0.75rem;
            padding: 1.5rem;
        }
        
        .circular-analytics-chart {
            background: white;
            border-radius: 0.75rem;
            padding: 1.5rem;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }

        /* Mobile fixes */
        @media (max-width: 1023px) {
            .sidebar-mobile {
                transform: translateX(-100%) !important;
            }
            .sidebar-mobile.open {
                transform: translateX(0) !important;
            }
            .main-content-spacing {
                margin-left: 0 !important;
                width: 100vw !important;
            }
            
            .max-w-7xl.mx-auto {
                padding: 0 0.5rem !important;
            }
            
            /* ✅ FIXED: Mobile header adjustments */
            .header-content {
                padding-top: 0.4rem !important;
                padding-bottom: 0.4rem !important;
                min-height: 55px !important;
            }
            
            .mobile-logo {
                height: 24px !important;
            }
            
            .navbar-brand span {
                font-size: 0.9rem !important;
            }
        }

        /* 🔥 VITE FALLBACK STYLES - Critical for when Vite fails */
        .vite-fallback {
            display: none;
        }
        
        /* Show fallback only when Vite fails */
        .no-vite .vite-fallback {
            display: block;
        }
        
        .no-vite .vite-asset {
            display: none;
        }
        
        /* ✅ ADDED: User name styling for better fit */
        .user-name {
            font-size: 0.875rem !important;
            max-width: 120px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }
    </style>
    
    <!-- Page-specific CSS -->
    @stack('styles')
</head>

<body class="bg-gray-50 font-sans" x-data="{ darkMode: localStorage.getItem('darkMode') === 'true', sidebarCollapsed: localStorage.getItem('sidebarCollapsed') === 'true', mobileSidebarOpen: false }" :class="{ 'dark-mode': darkMode }">
    <a href="#main-content" class="skip-link nepali">मुख्य सामग्रीमा जानुहोस्</a>
    
    <div class="flex h-screen overflow-hidden">
        <!-- Sidebar Component -->
        <aside id="sidebar" 
               class="sidebar text-white z-20 flex-shrink-0 transition-all duration-300 ease-in-out flex flex-col h-full fixed lg:relative sidebar-mobile lg:sidebar-mobile-open"
               :class="{ 
                 'collapsed': sidebarCollapsed,
                 'open': mobileSidebarOpen 
               }">
            <div class="p-4 border-b border-blue-700 flex items-center justify-between">
                <a href="{{ url('/admin/dashboard') }}" class="logo-container">
                    <img src="{{ asset('images/logo.png') }}" alt="HostelHub Logo" class="logo-img" onerror="this.src='{{ asset('build/assets/logo.png') }}'">
                    <span class="logo-text sidebar-text" x-show="!sidebarCollapsed">होस्टलहब</span>
                </a>
                <button @click="sidebarCollapsed = !sidebarCollapsed; localStorage.setItem('sidebarCollapsed', sidebarCollapsed)" 
                        class="text-gray-300 hover:text-white sidebar-text" 
                        aria-label="साइडबार सङ्कुचित गर्नुहोस्"
                        x-show="!sidebarCollapsed">
                    <i class="fas fa-bars-staggered"></i>
                </button>
            </div>
            
            <nav class="mt-5 px-2 flex-1 overflow-y-auto">
                <!-- Dashboard -->
                <a href="{{ route('admin.dashboard') }}"
                   class="sidebar-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}"
                   aria-current="{{ request()->routeIs('admin.dashboard') ? 'page' : 'false' }}">
                    <i class="fas fa-tachometer-alt sidebar-icon"></i>
                    <span class="sidebar-text" x-show="!sidebarCollapsed">ड्यासबोर्ड</span>
                </a>
                
                <!-- Hostels -->
                <a href="{{ route('admin.hostels.index') }}"
                   class="sidebar-link {{ request()->routeIs('admin.hostels.*') ? 'active' : '' }}"
                   aria-current="{{ request()->routeIs('admin.hostels.*') ? 'page' : 'false' }}">
                    <i class="fas fa-building sidebar-icon"></i>
                    <span class="sidebar-text" x-show="!sidebarCollapsed">होस्टलहरू</span>
                </a>
                
                <!-- Rooms -->
                <a href="{{ route('admin.rooms.index') }}"
                   class="sidebar-link {{ request()->routeIs('admin.rooms.*') ? 'active' : '' }}"
                   aria-current="{{ request()->routeIs('admin.rooms.*') ? 'page' : 'false' }}">
                    <i class="fas fa-door-open sidebar-icon"></i>
                    <span class="sidebar-text" x-show="!sidebarCollapsed">कोठाहरू</span>
                </a>
                
                <!-- Students -->
                <a href="{{ route('admin.students.index') }}"
                   class="sidebar-link {{ request()->routeIs('admin.students.*') ? 'active' : '' }}"
                   aria-current="{{ request()->routeIs('admin.students.*') ? 'page' : 'false' }}">
                    <i class="fas fa-users sidebar-icon"></i>
                    <span class="sidebar-text" x-show="!sidebarCollapsed">विद्यार्थीहरू</span>
                </a>
                
                <!-- Payments -->
                <a href="{{ route('admin.payments.index') }}"
                   class="sidebar-link {{ request()->routeIs('admin.payments.*') ? 'active' : '' }}"
                   aria-current="{{ request()->routeIs('admin.payments.*') ? 'page' : 'false' }}">
                    <i class="fas fa-credit-card sidebar-icon"></i>
                    <span class="sidebar-text" x-show="!sidebarCollapsed">भुक्तानीहरू</span>
                </a>
                
                <!-- Meals -->
                <a href="{{ route('admin.meals.index') }}"
                   class="sidebar-link {{ request()->routeIs('admin.meals.*') ? 'active' : '' }}"
                   aria-current="{{ request()->routeIs('admin.meals.*') ? 'page' : 'false' }}">
                    <i class="fas fa-utensils sidebar-icon"></i>
                    <span class="sidebar-text" x-show="!sidebarCollapsed">भोजन</span>
                </a>
                
                <!-- Gallery -->
                <a href="{{ route('admin.galleries.index') }}"
                   class="sidebar-link {{ request()->routeIs('admin.galleries.*') ? 'active' : '' }}"
                   aria-current="{{ request()->routeIs('admin.galleries.*') ? 'page' : 'false' }}">
                    <i class="fas fa-image sidebar-icon"></i>
                    <span class="sidebar-text" x-show="!sidebarCollapsed">ग्यालरी</span>
                </a>
                
                <!-- Contacts -->
                <a href="{{ route('admin.contacts.index') }}"
                   class="sidebar-link {{ request()->routeIs('admin.contacts.*') ? 'active' : '' }}"
                   aria-current="{{ request()->routeIs('admin.contacts.*') ? 'page' : 'false' }}">
                    <i class="fas fa-address-book sidebar-icon"></i>
                    <span class="sidebar-text" x-show="!sidebarCollapsed">सम्पर्क</span>
                </a>
                
                <!-- Reports -->
                <a href="{{ route('admin.reports.index') }}"
                   class="sidebar-link {{ request()->routeIs('admin.reports.*') ? 'active' : '' }}"
                   aria-current="{{ request()->routeIs('admin.reports.*') ? 'page' : 'false' }}">
                    <i class="fas fa-chart-bar sidebar-icon"></i>
                    <span class="sidebar-text" x-show="!sidebarCollapsed">प्रतिवेदनहरू</span>
                </a>
                
                <!-- Documents -->
                <a href="{{ route('admin.documents.index') }}"
                   class="sidebar-link {{ request()->routeIs('admin.documents.*') ? 'active' : '' }}"
                   aria-current="{{ request()->routeIs('admin.documents.*') ? 'page' : 'false' }}">
                    <i class="fas fa-file-alt sidebar-icon"></i>
                    <span class="sidebar-text" x-show="!sidebarCollapsed">कागजातहरू</span>
                </a>

                <!-- Circulars -->
                <a href="{{ route('admin.circulars.index') }}"
                   class="sidebar-link {{ request()->routeIs('admin.circulars.*') ? 'active' : '' }}"
                   aria-current="{{ request()->routeIs('admin.circulars.*') ? 'page' : 'false' }}">
                    <i class="fas fa-bullhorn sidebar-icon"></i>
                    <span class="sidebar-text" x-show="!sidebarCollapsed">सूचनाहरू</span>
                </a>
                
                <!-- Settings -->
                <a href="{{ route('admin.settings.index') }}"
                   class="sidebar-link {{ request()->routeIs('admin.settings.*') ? 'active' : '' }}"
                   aria-current="{{ request()->routeIs('admin.settings.*') ? 'page' : 'false' }}">
                    <i class="fas fa-cogs sidebar-icon"></i>
                    <span class="sidebar-text" x-show="!sidebarCollapsed">सेटिङ्हरू</span>
                </a>
                
                <!-- Logout Section -->
                <div class="mt-auto pt-4 border-t border-blue-700">
                    <form method="POST" action="{{ route('logout') }}" id="logout-form">
                        @csrf
                        <button type="submit" class="w-full flex items-center px-2 py-2 text-sm rounded-md hover:bg-blue-700 transition-colors">
                            <i class="fas fa-sign-out-alt sidebar-icon"></i>
                            <span class="sidebar-text" x-show="!sidebarCollapsed">लगआउट</span>
                        </button>
                    </form>
                </div>
            </nav>
        </aside>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden main-content-spacing transition-all duration-300" :class="{ 'collapsed': sidebarCollapsed }">
            <!-- Top Navigation -->
            <header class="bg-gradient-primary shadow-sm z-10">
                <div class="flex items-center justify-between px-6 header-content">
                    <div class="flex items-center">
                        <button @click="mobileSidebarOpen = !mobileSidebarOpen" class="lg:hidden text-white hover:text-gray-200 mr-4" aria-label="मोबाइल साइडबार खोल्नुहोस्">
                            <i class="fas fa-bars text-xl"></i>
                        </button>
                        <!-- Brand with Logo -->
                        <a href="{{ url('/admin/dashboard') }}" class="navbar-brand text-white flex items-center">
                            <img src="{{ asset('images/logo.png') }}" alt="HostelHub Logo" class="mobile-logo mr-2" onerror="this.src='{{ asset('build/assets/logo.png') }}'">
                            <span class="hidden md:inline text-sm">होस्टलहब - प्रशासक प्यानल</span>
                        </a>
                    </div>
                    
                    <div class="flex items-center space-x-3">
                        <!-- Dark Mode Toggle -->
                        <button @click="darkMode = !darkMode; localStorage.setItem('darkMode', darkMode)" 
                                class="text-white hover:text-gray-200 dark-mode-toggle p-2 rounded-full hover:bg-blue-700" 
                                aria-label="डार्क मोड टगल गर्नुहोस्">
                            <i class="fas fa-moon" x-show="!darkMode"></i>
                            <i class="fas fa-sun" x-show="darkMode"></i>
                        </button>
                        
                        <!-- Notifications -->
                        <div class="relative" x-data="{ open: false }">
                            <button @click="open = !open" class="notification-button text-white hover:text-gray-200 p-2 rounded-full hover:bg-blue-700" aria-label="सूचनाहरू हेर्नुहोस्">
                                <i class="fas fa-bell text-lg"></i>
                                <span class="notification-dot" aria-hidden="true"></span>
                            </button>
                            
                            <div x-show="open" @click.away="open = false"
                                 x-transition:enter="transition ease-out duration-200"
                                 x-transition:enter-start="opacity-0 transform scale-95"
                                 x-transition:enter-end="opacity-100 transform scale-100"
                                 x-transition:leave="transition ease-in duration-75"
                                 x-transition:leave-start="opacity-100 transform scale-100"
                                 x-transition:leave-end="opacity-0 transform scale-95"
                                 class="absolute right-0 mt-2 w-80 bg-white rounded-xl shadow-lg py-1 z-20 max-h-96 overflow-y-auto border border-gray-200 dark:bg-gray-700 dark:border-gray-600"
                                 role="menu"
                                 aria-orientation="vertical"
                                 aria-labelledby="notifications-button">
                                <div class="px-4 py-2 border-b border-gray-200 dark:border-gray-600">
                                    <h3 class="font-semibold text-gray-800 dark:text-white">सूचनाहरू</h3>
                                </div>
                                <a href="#" class="flex items-start px-4 py-3 hover:bg-gray-50 dark:hover:bg-gray-600 border-b border-gray-100 dark:border-gray-600" role="menuitem">
                                    <div class="bg-indigo-100 p-2 rounded-lg mr-3">
                                        <i class="fas fa-user-plus text-indigo-600"></i>
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-gray-800 dark:text-white">नयाँ विद्यार्थी दर्ता</p>
                                        <p class="text-xs text-gray-500 dark:text-gray-300">३० मिनेट अघि</p>
                                    </div>
                                </a>
                                <a href="#" class="flex items-start px-4 py-3 hover:bg-gray-50 dark:hover:bg-gray-600 border-b border-gray-100 dark:border-gray-600" role="menuitem">
                                    <div class="bg-amber-100 p-2 rounded-lg mr-3">
                                        <i class="fas fa-money-bill-wave text-amber-600"></i>
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-gray-800 dark:text-white">भुक्तानी समाप्ति</p>
                                        <p class="text-xs text-gray-500 dark:text-gray-300">१ घण्टा अघि</p>
                                    </div>
                                </a>
                                <a href="#" class="flex items-start px-4 py-3 hover:bg-gray-50 dark:hover:bg-gray-600" role="menuitem">
                                    <div class="bg-red-100 p-2 rounded-lg mr-3">
                                        <i class="fas fa-exclamation-triangle text-red-600"></i>
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-gray-800 dark:text-white">कोठा उपलब्धता</p>
                                        <p class="text-xs text-gray-500 dark:text-gray-300">२ घण्टा अघि</p>
                                    </div>
                                </a>
                                <div class="px-4 py-2 border-t border-gray-200 dark:border-gray-600 text-center">
                                    <a href="#" class="text-indigo-600 dark:text-indigo-400 text-sm hover:underline">सबै सूचनाहरू हेर्नुहोस्</a>
                                </div>
                            </div>
                        </div>
                        
                        <!-- User Dropdown -->
                        <div class="flex items-center space-x-2 user-dropdown">
                            <span class="text-white nepali user-name" x-show="!sidebarCollapsed || window.innerWidth >= 1024">पराशर रेग्मी</span>
                            <div class="relative" x-data="{ userDropdownOpen: false }">
                                <button @click="userDropdownOpen = !userDropdownOpen" class="bg-white bg-opacity-20 hover:bg-opacity-30 text-white px-3 py-2 rounded-lg flex items-center space-x-2 transition-all">
                                    <i class="fas fa-user-circle"></i>
                                    <span class="nepali text-sm">प्रशासक</span>
                                    <i class="fas fa-chevron-down text-xs"></i>
                                </button>
                                
                                <div x-show="userDropdownOpen" 
                                     @click.away="userDropdownOpen = false"
                                     x-transition:enter="transition ease-out duration-200"
                                     x-transition:enter-start="opacity-0 transform scale-95"
                                     x-transition:enter-end="opacity-100 transform scale-100"
                                     x-transition:leave="transition ease-in duration-75"
                                     x-transition:leave-start="opacity-100 transform scale-100"
                                     x-transition:leave-end="opacity-0 transform scale-95"
                                     class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg py-1 z-20 border border-gray-200 dark:bg-gray-700 dark:border-gray-600">
                                    <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:text-white dark:hover:bg-gray-600">
                                        <i class="fas fa-user mr-2"></i>मेरो प्रोफाइल
                                    </a>
                                    <a href="{{ route('admin.settings.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:text-white dark:hover:bg-gray-600">
                                        <i class="fas fa-cog mr-2"></i>सेटिङ्हरू
                                    </a>
                                    <div class="border-t border-gray-200 dark:border-gray-600 my-1"></div>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:text-white dark:hover:bg-gray-600">
                                            <i class="fas fa-sign-out-alt mr-2"></i>लगआउट
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Page Content -->
            <main id="main-content" class="flex-1 overflow-y-auto p-4 md:p-6 bg-gray-50 dark:bg-gray-800">
                <div class="max-w-7xl mx-auto">
                    <!-- Page Header -->
                    <div class="mb-6">
                        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                            <div>
                                <h1 class="text-2xl md:text-3xl font-bold text-gray-800 dark:text-white mb-1 md:block">@yield('title')</h1>
                                @if(View::hasSection('page-description'))
                                    <p class="text-gray-600 dark:text-gray-300 text-sm">@yield('page-description')</p>
                                @endif
                            </div>
                            <div>
                                @yield('header-buttons')
                            </div>
                        </div>
                    </div>

                    <!-- Session Messages -->
                    @if (session('success'))
                        <div class="mb-6 p-4 bg-green-50 border border-green-200 text-green-700 rounded-lg flex items-center dark:bg-green-900 dark:border-green-700 dark:text-green-300">
                            <i class="fas fa-check-circle mr-2"></i>
                            <span>{{ session('success') }}</span>
                        </div>
                    @endif
                    
                    @if (session('error'))
                        <div class="mb-6 p-4 bg-red-50 border border-red-200 text-red-700 rounded-lg flex items-center dark:bg-red-900 dark:border-red-700 dark:text-red-300">
                            <i class="fas fa-exclamation-circle mr-2"></i>
                            <span>{{ session('error') }}</span>
                        </div>
                    @endif
                    
                    @if ($errors->any())
                        <div class="mb-6 p-4 bg-yellow-50 border border-yellow-200 text-yellow-700 rounded-lg dark:bg-yellow-900 dark:border-yellow-700 dark:text-yellow-300">
                            <div class="flex items-center mb-2">
                                <i class="fas fa-exclamation-triangle mr-2"></i>
                                <strong class="font-medium">त्रुटिहरू पत्ता लाग्यो:</strong>
                            </div>
                            <ul class="list-disc pl-5 space-y-1">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <!-- Page Content -->
                    <div class="main-content bg-white dark:bg-gray-700 rounded-xl shadow-sm overflow-hidden">
                        @hasSection('विस्तार')
                            @yield('विस्तार')
                        @else
                            @yield('content')
                        @endif
                    </div>
                </div>
            </main>

            <!-- Footer -->
            <footer class="bg-white dark:bg-gray-700 border-t border-gray-200 dark:border-gray-600 py-4">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="flex flex-col md:flex-row justify-between items-center text-sm text-gray-500 dark:text-gray-400">
                        <p class="mb-2 md:mb-0">&copy; {{ date('Y') }} HostelHub. सबै अधिकार सुरक्षित।</p>
                        <div class="flex space-x-4">
                            <a href="#" class="hover:text-gray-700 dark:hover:text-gray-300">गोपनीयता नीति</a>
                            <a href="#" class="hover:text-gray-700 dark:hover:text-gray-300">सेवा सर्तहरू</a>
                            <span>संस्करण: 1.0.0</span>
                        </div>
                    </div>
                </div>
            </footer>
        </div>
    </div>

    <!-- Mobile Sidebar Overlay -->
    <div x-show="mobileSidebarOpen" 
         @click="mobileSidebarOpen = false"
         class="fixed inset-0 bg-black bg-opacity-50 z-10 lg:hidden" 
         aria-hidden="true"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0">
    </div>

    <!-- Video Modal -->
    <div id="video-modal" class="fixed inset-0 bg-black bg-opacity-90 z-50 hidden flex items-center justify-center p-4">
        <div class="relative w-full max-w-4xl">
            <button id="close-video-modal" class="absolute -top-12 right-0 text-white text-xl hover:text-gray-300 transition-colors" aria-label="भिडियो मोडल बन्द गर्नुहोस्">
                बन्द गर्नुहोस् ×
            </button>
            <div class="bg-black rounded-xl overflow-hidden">
                <div class="relative pb-[56.25%] h-0">
                    <video id="modal-video-player" class="absolute inset-0 w-full h-full" controls>
                        <source src="" type="video/mp4">
                        तपाईंको ब्राउजरले भिडियो समर्थन गर्दैन
                    </video>
                </div>
                <div class="p-4 bg-gray-900">
                    <h3 id="video-title" class="text-white font-bold text-xl"></h3>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    @stack('scripts')
    
    <!-- 🔥 BACKUP: Manual JS Load if Vite fails -->
    @production
        @if(file_exists(public_path('build/assets/app.js')))
            <script src="{{ asset('build/assets/app.js') }}" defer></script>
        @elseif(file_exists(public_path('build/assets/app-B5qYSx8J.js')))
            <script src="{{ asset('build/assets/app-B5qYSx8J.js') }}" defer></script>
        @endif
    @endproduction
    
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    
    <!-- ✅ ADDED: jQuery for circular functionality -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Vite Asset Detection - Add fallback class if Vite fails
            setTimeout(function() {
                const viteStyles = document.querySelector('link[href*="build/assets/app"]');
                if (!viteStyles) {
                    document.body.classList.add('no-vite');
                    console.warn('Vite assets not detected, using fallback assets');
                }
            }, 1000);

            // Video Modal Functionality
            const playVideoBtns = document.querySelectorAll('.play-video-btn');
            const videoModal = document.getElementById('video-modal');
            const modalVideoPlayer = document.getElementById('modal-video-player');
            const videoTitle = document.getElementById('video-title');
            const closeVideoModal = document.getElementById('close-video-modal');
            
            playVideoBtns.forEach(btn => {
                btn.addEventListener('click', function() {
                    const videoUrl = this.getAttribute('data-video');
                    const title = this.getAttribute('data-title');
                    if (videoUrl && modalVideoPlayer && videoTitle) {
                        modalVideoPlayer.querySelector('source').src = videoUrl;
                        videoTitle.textContent = title;
                        // Reload video to ensure it loads the new source
                        modalVideoPlayer.load();
                        // Show modal
                        if (videoModal) {
                            videoModal.classList.remove('hidden');
                        }
                    }
                });
            });
            
            // Close video modal
            if (closeVideoModal && videoModal && modalVideoPlayer) {
                closeVideoModal.addEventListener('click', function() {
                    videoModal.classList.add('hidden');
                    modalVideoPlayer.pause();
                    modalVideoPlayer.currentTime = 0;
                });
                
                // Close modal when clicking outside video
                videoModal.addEventListener('click', function(e) {
                    if (e.target === videoModal) {
                        videoModal.classList.add('hidden');
                        modalVideoPlayer.pause();
                        modalVideoPlayer.currentTime = 0;
                    }
                });
                
                // Close modal on escape key
                document.addEventListener('keydown', function(e) {
                    if (e.key === 'Escape' && !videoModal.classList.contains('hidden')) {
                        videoModal.classList.add('hidden');
                        modalVideoPlayer.pause();
                        modalVideoPlayer.currentTime = 0;
                    }
                });
            }

            // Auto-dismiss alerts after 5 seconds
            setTimeout(function() {
                const alerts = document.querySelectorAll('.alert-dismissible');
                alerts.forEach(alert => {
                    alert.style.transition = 'opacity 0.5s';
                    alert.style.opacity = '0';
                    setTimeout(() => alert.remove(), 500);
                });
            }, 5000);

            // Initialize DataTables if present
            if (typeof $ !== 'undefined' && typeof $.fn.DataTable !== 'undefined') {
                $('table.data-table').each(function() {
                    if (!$.fn.DataTable.isDataTable(this)) {
                        $(this).DataTable({
                            responsive: true,
                            language: {
                                url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/np.json'
                            },
                            pageLength: 10,
                            lengthChange: false,
                            autoWidth: false,
                            columnDefs: [
                                { responsivePriority: 1, targets: 0 },
                                { responsivePriority: 2, targets: -1 }
                            ]
                        });
                    }
                });
            }

            // Form submission handling
            const forms = document.querySelectorAll('form');
            forms.forEach(form => {
                form.addEventListener('submit', function() {
                    // Disable submit button to prevent multiple submissions
                    const submitBtn = form.querySelector('button[type="submit"], input[type="submit"]');
                    if (submitBtn) {
                        const originalText = submitBtn.innerHTML;
                        submitBtn.disabled = true;
                        // Check if we're already showing a spinner
                        if (!submitBtn.querySelector('.spinner-border')) {
                            submitBtn.innerHTML = `
                                <span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>
                                प्रक्रिया गर्दै...
                            `;
                        }
                        // Reset button after 3 seconds if form doesn't submit
                        setTimeout(() => {
                            if (submitBtn.disabled) {
                                submitBtn.disabled = false;
                                submitBtn.innerHTML = originalText;
                            }
                        }, 3000);
                    }
                });
            });

            // Logout confirmation
            const logoutForms = document.querySelectorAll('#logout-form, #logout-form-top');
            logoutForms.forEach(form => {
                form.addEventListener('submit', function(e) {
                    if (!confirm('के तपाईं निश्चित रूपमा लगआउट गर्न चाहनुहुन्छ?')) {
                        e.preventDefault();
                    }
                });
            });
        });

        // ✅ ADDED: Admin circular functionality JavaScript
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
                            showAdminAlert('सफलता', response.message, 'success');
                            
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
                        showAdminAlert('त्रुटि', errorMessage, 'error');
                    },
                    complete: function() {
                        submitBtn.prop('disabled', false).html(originalText);
                    }
                });
            });

            // Helper function to show alerts for admin
            function showAdminAlert(title, message, type) {
                const alertClass = type === 'success' ? 'alert-success' : 
                                 type === 'error' ? 'alert-danger' : 'alert-info';
                const icon = type === 'success' ? 'fa-check-circle' : 
                            type === 'error' ? 'fa-exclamation-circle' : 'fa-info-circle';
                
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

            // ✅ FIXED: Real-time circular notifications for admin
            function checkNewCirculars() {
                $.ajax({
                    url: '{{ route("admin.circulars.index") }}?check_new=true',
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
                                showAdminAlert('नयाँ सूचना', 'तपाईंसँग १ नयाँ सूचना छ', 'info');
                            } else {
                                showAdminAlert('नयाँ सूचनाहरू', `तपाईंसँग ${response.new_circulars} नयाँ सूचनाहरू छन्`, 'info');
                            }
                        }
                    }
                });
            }

            // Check for new circulars every 30 seconds
            setInterval(checkNewCirculars, 30000);

            // ✅ ADDED: Circular publish functionality for admin
            $(document).on('click', '.publish-circular-btn', function() {
                const circularId = $(this).data('circular-id');
                const button = $(this);
                
                if (!confirm('के तपाईं यो सूचना प्रकाशित गर्न चाहनुहुन्छ?')) {
                    return;
                }
                
                button.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> प्रकाशन हुदैछ...');
                
                $.ajax({
                    url: "{{ route('admin.circulars.publish', ['circular' => 'CIRCULAR_ID_PLACEHOLDER']) }}".replace('CIRCULAR_ID_PLACEHOLDER', circularId),
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        if (response.success) {
                            showAdminAlert('सफलता', response.message, 'success');
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
                        showAdminAlert('त्रुटि', errorMessage, 'error');
                        button.prop('disabled', false).html('<i class="fas fa-paper-plane"></i> प्रकाशन गर्नुहोस्');
                    }
                });
            });

            // ✅ ADDED: Circular delete confirmation for admin
            $(document).on('click', '.delete-circular-btn', function(e) {
                e.preventDefault();
                
                const form = $(this).closest('form');
                const circularTitle = $(this).data('circular-title') || 'यो सूचना';
                
                if (confirm(`के तपाईं ${circularTitle} लाई मेट्न चाहनुहुन्छ? यो कार्य पूर्ववत गर्न सकिँदैन।`)) {
                    form.submit();
                }
            });

            // ✅ ADDED: Bulk actions for circulars in admin
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

            // ✅ ADDED: Circular analytics chart initialization for admin
            function initializeCircularAnalytics() {
                const analyticsChart = document.getElementById('circularAnalyticsChart');
                
                if (analyticsChart) {
                    // Initialize chart here (you can use Chart.js or any other library)
                    console.log('Initializing circular analytics chart for admin...');
                    
                    // Example chart initialization (replace with actual implementation)
                    const ctx = analyticsChart.getContext('2d');
                    // Add your chart initialization code here
                }
            }

            // Initialize analytics when DOM is ready
            initializeCircularAnalytics();

            // ✅ ADDED: Global circular sending functionality for admin
            $(document).on('click', '.send-global-circular-btn', function() {
                if (!confirm('के तपाईं यो सूचना सबै होस्टलहरूमा पठाउन चाहनुहुन्छ?')) {
                    return;
                }
                
                const button = $(this);
                button.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> पठाइँदै...');
                
                $.ajax({
                    url: '{{ route("admin.circulars.send-global") }}',
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        circular_id: button.data('circular-id')
                    },
                    success: function(response) {
                        if (response.success) {
                            showAdminAlert('सफलता', response.message, 'success');
                            button.replaceWith('<span class="badge bg-success">सबैलाई पठाइयो</span>');
                        }
                    },
                    error: function(xhr) {
                        let errorMessage = 'सूचना पठाउन असफल भयो';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMessage = xhr.responseJSON.message;
                        }
                        showAdminAlert('त्रुटि', errorMessage, 'error');
                        button.prop('disabled', false).html('<i class="fas fa-globe"></i> सबैलाई पठाउनुहोस्');
                    }
                });
            });
        });
    </script>
</body>
</html>