<!DOCTYPE html>
<html lang="ne" dir="ltr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="robots" content="noindex, nofollow">
    <title>@yield('title', 'ड्यासबोर्ड') - HostelHub Student</title>
    
    <!-- Favicon -->
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">
    
    <!-- Google Fonts for Nepali -->
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Devanagari:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Font Awesome 6.4.0 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
          integrity="sha512-iecdLmaskl7CVkqV0u:YGFkTd73EVdjGN7teJQ8N+2ER5yiJHHIyMI1GAa5I80LzvcpbKjByZcXc9j5QFZUvSJQ=="
          crossorigin="anonymous" referrerpolicy="no-referrer">
    
    <!-- Tailwind CSS with Vite -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <style>
        :root {
            --sidebar-width: 16rem;
            --sidebar-collapsed-width: 4.5rem;
            --transition-speed: 0.3s;
            --header-height: 64px;
        }
        
        body {
            font-family: 'Noto Sans Devanagari', sans-serif;
            background-color: #f8fafc;
            margin: 0;
            padding: 0;
            overflow-x: hidden;
            color: #333333 !important; /* ADDED: Default text color */
        }
        
        /* 🎯 MOBILE-FIRST RESPONSIVE STYLES */
        
        /* Mobile (< 1024px) */
        @media (max-width: 1023px) {
            body {
                overflow-x: hidden !important;
                position: relative !important;
                color: #333333 !important; /* ADDED: Ensure text color */
            }
            
            /* Sidebar - Mobile Off-canvas */
            .sidebar {
                width: 280px !important;
                transform: translateX(-100%) !important;
                position: fixed !important;
                top: 0 !important;
                left: 0 !important;
                height: 100vh !important;
                z-index: 1050 !important;
                box-shadow: 5px 0 15px rgba(0, 0, 0, 0.2) !important;
                transition: transform var(--transition-speed) ease-in-out !important;
            }
            
            .sidebar.mobile-open {
                transform: translateX(0) !important;
            }
            
            /* Mobile overlay when sidebar is open */
            .sidebar-overlay {
                position: fixed !important;
                inset: 0 !important;
                background: rgba(0, 0, 0, 0.5) !important;
                z-index: 1040 !important;
                backdrop-filter: blur(2px) !important;
                display: none !important;
            }
            
            .sidebar-overlay.active {
                display: block !important;
            }
            
            /* Prevent body scroll when sidebar is open */
            body.sidebar-open {
                overflow: hidden !important;
                position: fixed !important;
                width: 100% !important;
            }
            
            /* Main content - Full width on mobile */
            .main-content-area {
                margin-left: 0 !important;
                width: 100vw !important;
                min-height: 100vh !important;
                display: flex !important;
                flex-direction: column !important;
            }
            
            /* Header - Fixed at top on mobile */
            .header-fixed {
                position: fixed !important;
                top: 0 !important;
                left: 0 !important;
                right: 0 !important;
                height: var(--header-height) !important;
                z-index: 1030 !important;
                background: linear-gradient(45deg, #4e73df, #224abe) !important; /* ADDED: Ensure gradient */
            }
            
            /* Header content - ensure white text */
            .header-content {
                color: white !important;
            }
            
            /* Header buttons and icons - ensure visibility */
            .header-content .btn,
            .header-content .notification-button,
            .header-content .dropdown-toggle,
            .header-content i {
                color: white !important;
            }
            
            /* Notification dropdown - ensure visibility */
            .dropdown-menu {
                background-color: white !important;
                color: #333333 !important;
            }
            
            .dropdown-menu a,
            .dropdown-menu p,
            .dropdown-menu h3,
            .dropdown-menu span {
                color: #333333 !important;
            }
            
            /* Main content padding to account for fixed header */
            .page-content {
                padding-top: calc(var(--header-height) + 1rem) !important;
                color: #333333 !important; /* ADDED: Ensure text color */
            }
            
            /* Ensure all text in content area is visible */
            .main-content-area *:not(.header-content *) {
                color: #333333 !important;
            }
            
            /* Card adjustments for mobile */
            .card-mobile {
                margin: 0 !important;
                border-radius: 0.5rem !important;
                box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1) !important;
                background-color: white !important;
                color: #333333 !important;
            }
            
            /* Ensure buttons are visible */
            .btn {
                color: #333333 !important;
            }
            
            .btn-primary {
                color: white !important;
            }
            
            .btn-outline-primary {
                color: #4e73df !important;
                border-color: #4e73df !important;
            }
            
            .btn-outline-primary:hover {
                color: white !important;
                background-color: #4e73df !important;
            }
            
            /* Table responsive fixes */
            .table-responsive-mobile {
                overflow-x: auto !important;
                -webkit-overflow-scrolling: touch !important;
            }
            
            /* Button group mobile optimization */
            .btn-group-mobile {
                flex-wrap: wrap !important;
                gap: 0.5rem !important;
            }
            
            /* Form field spacing */
            .form-field-mobile {
                margin-bottom: 1rem !important;
            }
            
            /* Statistics card grid */
            .stats-grid-mobile {
                grid-template-columns: repeat(1, 1fr) !important;
                gap: 1rem !important;
            }
            
            /* Hide desktop collapse button on mobile */
            #sidebar-collapse {
                display: none !important;
            }
            
            /* Show mobile menu button */
            #mobile-sidebar-toggle {
                display: block !important;
                color: white !important;
            }
            
            /* Ensure user dropdown text is visible */
            .user-dropdown .btn {
                color: white !important;
                border-color: rgba(255, 255, 255, 0.5) !important;
            }
            
            .user-dropdown .btn:hover {
                background-color: rgba(255, 255, 255, 0.1) !important;
            }
            
            /* Student badge on mobile */
            .student-badge {
                background: rgba(255, 255, 255, 0.2) !important;
                color: white !important;
                padding: 4px 8px !important;
                border-radius: 4px !important;
            }
            
            /* Bell icon notification dot */
            .notification-dot {
                background-color: #ff4757 !important;
                border: 2px solid #224abe !important;
            }
            
            /* Alerts visibility */
            .alert {
                color: #333333 !important;
            }
            
            .alert-success {
                background-color: #d4edda !important;
                border-color: #c3e6cb !important;
                color: #155724 !important;
            }
            
            .alert-danger {
                background-color: #f8d7da !important;
                border-color: #f5c6cb !important;
                color: #721c24 !important;
            }
            
            .alert-info {
                background-color: #d1ecf1 !important;
                border-color: #bee5eb !important;
                color: #0c5460 !important;
            }
        }
        
        /* Tablet (768px - 1023px) */
        @media (min-width: 768px) and (max-width: 1023px) {
            .sidebar {
                width: 300px !important;
            }
            
            .stats-grid-mobile {
                grid-template-columns: repeat(2, 1fr) !important;
            }
            
            /* Ensure text visibility on tablet */
            .page-content,
            .page-content *:not(.header-content *) {
                color: #333333 !important;
            }
        }
        
        /* Desktop (≥ 1024px) - Original behavior */
        @media (min-width: 1024px) {
            .sidebar {
                transform: translateX(0) !important;
                position: fixed !important;
            }
            
            .main-content-area {
                margin-left: var(--sidebar-width) !important;
                width: calc(100vw - var(--sidebar-width)) !important;
            }
            
            .sidebar.collapsed ~ .main-content-area {
                margin-left: var(--sidebar-collapsed-width) !important;
                width: calc(100vw - var(--sidebar-collapsed-width)) !important;
            }
            
            .header-fixed {
                position: static !important;
            }
            
            .page-content {
                padding-top: 1rem !important;
            }
            
            /* Hide mobile menu button on desktop */
            #mobile-sidebar-toggle {
                display: none !important;
            }
            
            /* Show desktop collapse button */
            #sidebar-collapse {
                display: block !important;
            }
        }
        
        /* 🎯 EXISTING STYLES (Modified for mobile compatibility) */
        
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
            color: white !important;
        }
        
        .btn-primary:hover {
            background: linear-gradient(45deg, #224abe, #4e73df);
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(78, 115, 223, 0.4);
            color: white !important;
        }
        
        .btn-outline-primary {
            color: #4e73df !important;
            border: 2px solid #4e73df !important;
            background: transparent !important;
        }
        
        .btn-outline-primary:hover {
            background: linear-gradient(45deg, #4e73df, #224abe) !important;
            color: white !important;
        }
        
        .notification-dot {
            position: absolute;
            top: 3px;
            right: 3px;
            width: 10px;
            height: 10px;
            background-color: #ef4444;
            border-radius: 50%;
            z-index: 10;
            border: 2px solid #224abe;
        }
        
        .notification-button {
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white !important;
        }
        
        .notification-button i {
            font-size: 1.25rem;
            color: white !important;
        }
        
        .skip-link {
            position: absolute;
            top: -40px;
            left: 0;
            background: #224abe;
            color: white;
            padding: 8px 16px;
            z-index: 1100;
            transition: top 0.3s;
        }
        
        .skip-link:focus {
            top: 0;
        }
        
        /* Header content */
        .header-content {
            padding-top: 0.75rem !important;
            padding-bottom: 0.75rem !important;
            color: white !important;
        }
        
        .navbar-brand {
            font-size: 1.1rem !important;
            color: white !important;
        }
        
        .notification-button, .dark-mode-toggle {
            padding: 0.4rem !important;
            color: white !important;
        }
        
        .user-dropdown .btn {
            padding: 0.4rem 0.75rem !important;
            color: white !important;
            border: 1px solid rgba(255, 255, 255, 0.5) !important;
        }
        
        .user-dropdown .btn:hover {
            background-color: rgba(255, 255, 255, 0.1) !important;
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

        /* Main content area */
        .main-content-area {
            margin-left: var(--sidebar-width);
            width: calc(100% - var(--sidebar-width));
            transition: all var(--transition-speed);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            background-color: #f8fafc !important;
        }

        .sidebar.collapsed ~ .main-content-area {
            margin-left: var(--sidebar-collapsed-width);
            width: calc(100% - var(--sidebar-collapsed-width));
        }

        /* Ensure content takes full width */
        .main-content-container {
            flex: 1;
            display: flex;
            flex-direction: column;
            background-color: #f8fafc !important;
        }

        /* Fix for page content */
        .page-content {
            flex: 1;
            padding: 1rem;
            width: 100% !important;
            display: block !important;
            background-color: #f8fafc !important;
            color: #333333 !important;
        }

        @media (min-width: 768px) {
            .page-content {
                padding: 1.5rem;
            }
        }

        /* Student specific styles */
        .student-badge {
            background: linear-gradient(45deg, #4e73df, #224abe);
            color: white !important;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 0.8rem;
            font-weight: 600;
        }

        /* Updated border colors */
        .sidebar-border {
            border-color: #2d4fc7 !important;
        }
        
        .hover-sidebar-item:hover {
            background-color: rgba(255, 255, 255, 0.15) !important;
        }

        /* Circular specific styles for student */
        .circular-item {
            border: 1px solid #e2e8f0;
            border-radius: 0.75rem;
            padding: 1rem;
            margin-bottom: 1rem;
            transition: all 0.3s ease;
            background: white;
            color: #333333 !important;
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

        /* Alert styles for circular notifications */
        .alert-nepali {
            font-family: 'Noto Sans Devanagari', sans-serif;
        }
        
        /* Mobile menu button */
        #mobile-sidebar-toggle {
            display: none;
            color: white !important;
        }
        
        /* Ensure all text in content is visible */
        .card, .card * {
            color: #333333 !important;
        }
        
        .text-dark {
            color: #333333 !important;
        }
        
        .text-muted {
            color: #6c757d !important;
        }
        
        /* Dropdown menu visibility */
        .dropdown-menu {
            background-color: white !important;
            color: #333333 !important;
        }
        
        .dropdown-item {
            color: #333333 !important;
        }
        
        .dropdown-item:hover {
            background-color: #f8f9fa !important;
            color: #333333 !important;
        }
        
        /* Headings visibility */
        h1, h2, h3, h4, h5, h6 {
            color: #333333 !important;
        }
        
        p, span, div:not(.header-content *):not(.sidebar *) {
            color: #333333 !important;
        }
        
        /* Form elements visibility */
        .form-control, .form-label, .form-text {
            color: #333333 !important;
        }
        
        /* Table visibility */
        table, th, td {
            color: #333333 !important;
        }
        
        /* Badge visibility */
        .badge {
            color: white !important;
        }
        
        /* Ensure header remains colorful */
        header {
            background: linear-gradient(45deg, #4e73df, #224abe) !important;
        }
    </style>
    
    <!-- Page-specific CSS -->
    @stack('styles')
</head>
<body class="bg-gray-50 font-sans">
    <a href="#main-content" class="skip-link">मुख्य सामग्रीमा जानुहोस्</a>
    
    <div class="flex min-h-screen">
        <!-- Sidebar - STUDENT SPECIFIC -->
        <aside id="sidebar" class="sidebar text-white z-20 flex-shrink-0 transition-all duration-300 ease-in-out flex flex-col h-full">
            <div class="p-4 border-b sidebar-border flex items-center justify-between">
                <a href="{{ url('/student/dashboard') }}" class="logo-container">
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
                        <div class="text-logo">
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
                <a href="{{ route('student.dashboard') }}"
                   class="sidebar-link {{ request()->routeIs('student.dashboard') ? 'active' : '' }}"
                   aria-current="{{ request()->routeIs('student.dashboard') ? 'page' : 'false' }}">
                    <i class="fas fa-tachometer-alt sidebar-icon"></i>
                    <span class="sidebar-text">ड्यासबोर्ड</span>
                </a>
                
                <!-- My Profile -->
                <a href="{{ route('student.profile') }}"
                   class="sidebar-link {{ request()->routeIs('student.profile') ? 'active' : '' }}"
                   aria-current="{{ request()->routeIs('student.profile') ? 'page' : 'false' }}">
                    <i class="fas fa-user sidebar-icon"></i>
                    <span class="sidebar-text">मेरो प्रोफाइल</span>
                </a>
                
                <!-- Rooms -->
                <a href="{{ route('student.rooms.index') }}"
                   class="sidebar-link {{ request()->routeIs('student.rooms.*') ? 'active' : '' }}"
                   aria-current="{{ request()->routeIs('student.rooms.*') ? 'page' : 'false' }}">
                    <i class="fas fa-door-open sidebar-icon"></i>
                    <span class="sidebar-text">कोठाहरू</span>
                </a>
                
                <!-- Meal Menus -->
                <a href="{{ route('student.meal-menus') }}"
                   class="sidebar-link {{ request()->routeIs('student.meal-menus') ? 'active' : '' }}"
                   aria-current="{{ request()->routeIs('student.meal-menus') ? 'page' : 'false' }}">
                    <i class="fas fa-utensils sidebar-icon"></i>
                    <span class="sidebar-text">खानाको योजना</span>
                </a>
                
                <!-- Circulars -->
                <a href="{{ route('student.circulars.index') }}"
                   class="sidebar-link {{ request()->routeIs('student.circulars.*') ? 'active' : '' }}"
                   aria-current="{{ request()->routeIs('student.circulars.*') ? 'page' : 'false' }}">
                    <i class="fas fa-bullhorn sidebar-icon"></i>
                    <span class="sidebar-text">सूचनाहरू</span>
                </a>
                
                <!-- Payments -->
                <a href="{{ route('student.payments.index') }}"
                   class="sidebar-link {{ request()->routeIs('student.payments.*') ? 'active' : '' }}"
                   aria-current="{{ request()->routeIs('student.payments.*') ? 'page' : 'false' }}">
                    <i class="fas fa-money-bill-wave sidebar-icon"></i>
                    <span class="sidebar-text">भुक्तानी</span>
                </a>
                
                <!-- Reviews -->
                <a href="{{ route('student.reviews.index') }}"
                   class="sidebar-link {{ request()->routeIs('student.reviews.*') ? 'active' : '' }}"
                   aria-current="{{ request()->routeIs('student.reviews.*') ? 'page' : 'false' }}">
                    <i class="fas fa-star sidebar-icon"></i>
                    <span class="sidebar-text">समीक्षा</span>
                </a>
                
                <!-- Bookings -->
                <a href="{{ route('student.bookings.index') }}"
                   class="sidebar-link {{ request()->routeIs('student.bookings.*') ? 'active' : '' }}"
                   aria-current="{{ request()->routeIs('student.bookings.*') ? 'page' : 'false' }}">
                    <i class="fas fa-calendar-check sidebar-icon"></i>
                    <span class="sidebar-text">बुकिङहरू</span>
                </a>
                
                <!-- Gallery -->
                <a href="{{ route('student.gallery') }}"
                   class="sidebar-link {{ request()->routeIs('student.gallery') ? 'active' : '' }}"
                   aria-current="{{ request()->routeIs('student.gallery') ? 'page' : 'false' }}">
                    <i class="fas fa-images sidebar-icon"></i>
                    <span class="sidebar-text">ग्यालरी</span>
                </a>
                
                <!-- Events -->
                <a href="{{ route('student.events') }}"
                   class="sidebar-link {{ request()->routeIs('student.events') ? 'active' : '' }}"
                   aria-current="{{ request()->routeIs('student.events') ? 'page' : 'false' }}">
                    <i class="fas fa-calendar-alt sidebar-icon"></i>
                    <span class="sidebar-text">घटनाहरू</span>
                </a>
                
                <!-- Logout Section -->
                <div class="mt-auto pt-4 border-t sidebar-border">
                    <form method="POST" action="{{ route('logout') }}" id="logout-form">
                        @csrf
                        <button type="submit" class="w-full flex items-center px-2 py-2 text-sm rounded-md hover-sidebar-item transition-colors">
                            <i class="fas fa-sign-out-alt sidebar-icon"></i>
                            <span class="sidebar-text">लगआउट</span>
                        </button>
                    </form>
                </div>
            </nav>
        </aside>

        <!-- Mobile Sidebar Overlay -->
        <div class="sidebar-overlay" id="sidebar-overlay"></div>

        <!-- Main Content Area -->
        <div class="main-content-area">
            <!-- Top Navigation -->
            <header class="bg-gradient-primary shadow-sm z-10 header-fixed">
                <div class="flex items-center justify-between px-4 header-content h-full">
                    <div class="flex items-center">
                        <button id="mobile-sidebar-toggle" class="text-white hover:text-gray-200 mr-4" aria-label="मोबाइल साइडबार खोल्नुहोस्">
                            <i class="fas fa-bars text-xl"></i>
                        </button>
                        <!-- Brand with Logo -->
                        <a href="{{ url('/student/dashboard') }}" class="navbar-brand text-white flex items-center">
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
                                <div class="mobile-text-logo mr-2">
                                    HH
                                </div>
                            @endif
                            <span class="hidden md:inline text-sm text-white">होस्टलहब - विद्यार्थी प्यानल</span>
                        </a>
                    </div>
                    
                    <div class="flex items-center space-x-3">
                        <!-- Student Info Badge -->
                        <div class="student-badge hidden md:flex items-center space-x-2">
                            <i class="fas fa-user-graduate text-white"></i>
                            <span class="text-white">विद्यार्थी</span>
                        </div>

                        <!-- Notifications -->
                        <div class="dropdown">
                            <button class="notification-button text-white hover:text-gray-200 p-2 rounded-full hover:bg-blue-700 dropdown-toggle" 
                                    type="button" 
                                    id="notificationsDropdown" 
                                    data-bs-toggle="dropdown" 
                                    aria-expanded="false"
                                    aria-label="सूचनाहरू हेर्नुहोस्">
                                <i class="fas fa-bell text-lg text-white"></i>
                                <span class="notification-dot" aria-hidden="true"></span>
                            </button>
                            <div class="dropdown-menu dropdown-menu-end w-80 bg-white rounded-xl shadow-lg py-1 z-20 max-h-96 overflow-y-auto border border-gray-200" 
                                 aria-labelledby="notificationsDropdown">
                                <div class="px-4 py-2 border-b border-gray-200">
                                    <h3 class="font-semibold text-gray-800">सूचनाहरू</h3>
                                </div>
                                <a href="#" class="flex items-start px-4 py-3 hover:bg-gray-50 border-b border-gray-100 text-gray-800">
                                    <div class="bg-blue-100 p-2 rounded-lg mr-3">
                                        <i class="fas fa-utensils text-blue-600"></i>
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium">नयाँ खानाको योजना सिर्जना गरियो</p>
                                        <p class="text-xs text-gray-500">३० मिनेट अघि</p>
                                    </div>
                                </a>
                                <a href="#" class="flex items-start px-4 py-3 hover:bg-gray-50 border-b border-gray-100 text-gray-800">
                                    <div class="bg-amber-100 p-2 rounded-lg mr-3">
                                        <i class="fas fa-money-bill-wave text-amber-600"></i>
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium">भुक्तानी म्याद नजिकिँदैछ</p>
                                        <p class="text-xs text-gray-500">१ घण्टा अघि</p>
                                    </div>
                                </a>
                                <a href="#" class="flex items-start px-4 py-3 hover:bg-gray-50 text-gray-800">
                                    <div class="bg-indigo-100 p-2 rounded-lg mr-3">
                                        <i class="fas fa-bullhorn text-indigo-600"></i>
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium">नयाँ सूचना प्रकाशित भयो</p>
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
                                <i class="fas fa-user-circle me-2 text-white"></i>
                                <span class="d-none d-md-inline text-white">{{ Auth::user()->name ?? 'विद्यार्थी' }}</span>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end shadow-lg rounded-xl border-0 py-2" aria-labelledby="userDropdown">
                                <li>
                                    <a class="dropdown-item d-flex align-items-center text-dark" href="{{ route('student.profile') }}">
                                        <i class="fas fa-user me-2"></i>प्रोफाइल
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item d-flex align-items-center text-dark" href="{{ route('student.dashboard') }}">
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
                <main id="main-content" class="page-content">
                    <!-- Flash Messages -->
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show mb-4 rounded-xl" role="alert">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-check-circle me-2"></i>
                                <strong class="nepali text-dark">{{ session('success') }}</strong>
                            </div>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show mb-4 rounded-xl" role="alert">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-exclamation-circle me-2"></i>
                                <strong class="nepali text-dark">{{ session('error') }}</strong>
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

    <!-- Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    <!-- Custom JavaScript -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Sidebar collapse functionality
            const sidebar = document.getElementById('sidebar');
            const sidebarCollapse = document.getElementById('sidebar-collapse');
            const mobileSidebarToggle = document.getElementById('mobile-sidebar-toggle');
            const sidebarOverlay = document.getElementById('sidebar-overlay');
            const body = document.body;
            
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
                    const isOpen = !sidebar.classList.contains('mobile-open');
                    
                    // Toggle sidebar
                    sidebar.classList.toggle('mobile-open');
                    
                    // Toggle overlay
                    if (isOpen) {
                        sidebarOverlay.classList.add('active');
                        body.classList.add('sidebar-open');
                    } else {
                        sidebarOverlay.classList.remove('active');
                        body.classList.remove('sidebar-open');
                    }
                    
                    // Update aria-expanded
                    this.setAttribute('aria-expanded', isOpen);
                    this.setAttribute('aria-label', isOpen ? 
                        'मोबाइल साइडबार बन्द गर्नुहोस्' : 'मोबाइल साइडबार खोल्नुहोस्');
                });
            }
            
            // Close sidebar when clicking on overlay
            if (sidebarOverlay) {
                sidebarOverlay.addEventListener('click', function() {
                    sidebar.classList.remove('mobile-open');
                    this.classList.remove('active');
                    body.classList.remove('sidebar-open');
                    
                    // Update mobile toggle button
                    if (mobileSidebarToggle) {
                        mobileSidebarToggle.setAttribute('aria-expanded', 'false');
                        mobileSidebarToggle.setAttribute('aria-label', 'मोबाइल साइडबार खोल्नुहोस्');
                    }
                });
            }
            
            // Close sidebar when clicking on a link (mobile)
            const sidebarLinks = document.querySelectorAll('.sidebar-link');
            sidebarLinks.forEach(link => {
                link.addEventListener('click', function() {
                    if (window.innerWidth < 1024) {
                        sidebar.classList.remove('mobile-open');
                        if (sidebarOverlay) {
                            sidebarOverlay.classList.remove('active');
                        }
                        body.classList.remove('sidebar-open');
                        
                        // Update mobile toggle button
                        if (mobileSidebarToggle) {
                            mobileSidebarToggle.setAttribute('aria-expanded', 'false');
                            mobileSidebarToggle.setAttribute('aria-label', 'मोबाइल साइडबार खोल्नुहोस्');
                        }
                    }
                });
            });
            
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
                // Close sidebar on Escape key (mobile)
                if (e.key === 'Escape' && window.innerWidth < 1024) {
                    if (sidebar.classList.contains('mobile-open')) {
                        sidebar.classList.remove('mobile-open');
                        if (sidebarOverlay) {
                            sidebarOverlay.classList.remove('active');
                        }
                        body.classList.remove('sidebar-open');
                        
                        // Update mobile toggle button
                        if (mobileSidebarToggle) {
                            mobileSidebarToggle.setAttribute('aria-expanded', 'false');
                            mobileSidebarToggle.setAttribute('aria-label', 'मोबाइल साइडबार खोल्नुहोस्');
                        }
                    }
                }
                
                // Close dropdowns on Escape
                if (e.key === 'Escape') {
                    const openDropdowns = document.querySelectorAll('.dropdown-menu.show');
                    openDropdowns.forEach(function(dropdown) {
                        const dropdownInstance = bootstrap.Dropdown.getInstance(dropdown.previousElementSibling);
                        if (dropdownInstance) {
                            dropdownInstance.hide();
                        }
                    });
                }
            });
            
            // Update mobile sidebar state on window resize
            function updateSidebarState() {
                if (window.innerWidth >= 1024) {
                    // Desktop: ensure sidebar is visible and not in mobile mode
                    sidebar.classList.remove('mobile-open');
                    if (sidebarOverlay) {
                        sidebarOverlay.classList.remove('active');
                    }
                    body.classList.remove('sidebar-open');
                } else {
                    // Mobile: ensure sidebar is closed by default
                    if (!sidebar.classList.contains('mobile-open')) {
                        sidebar.classList.remove('mobile-open');
                        if (sidebarOverlay) {
                            sidebarOverlay.classList.remove('active');
                        }
                        body.classList.remove('sidebar-open');
                    }
                }
            }
            
            // Initial state update
            updateSidebarState();
            
            // Update on window resize
            window.addEventListener('resize', updateSidebarState);
        });
    </script>

    <!-- Student circular functionality JavaScript -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Student circular real-time updates
            function updateStudentCirculars() {
                $.ajax({
                    url: '{{ route("student.dashboard") }}?circulars_only=true',
                    method: 'GET',
                    success: function(response) {
                        if (response.circulars) {
                            updateCircularList(response.circulars);
                        }
                    }
                });
            }

            function updateCircularList(circulars) {
                const container = $('#circulars-container');
                if (!container.length) return;

                let html = '';
                
                if (circulars.length > 0) {
                    circulars.forEach(circular => {
                        const isRead = circular.is_read || false;
                        const priorityClass = circular.priority === 'urgent' ? 'border-left-urgent' : 
                                           circular.priority === 'high' ? 'border-left-high' : 'border-left-normal';
                        
                        html += `
                            <div class="circular-item ${isRead ? 'read' : 'unread'} ${priorityClass}">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div class="flex-grow-1">
                                        <h6 class="mb-1 ${isRead ? 'text-muted' : 'font-weight-bold text-dark'}">
                                            ${circular.title}
                                            ${!isRead ? '<span class="badge bg-danger ms-2">नयाँ</span>' : ''}
                                        </h6>
                                        <p class="text-muted small mb-1">${circular.content_preview}</p>
                                        <div class="d-flex align-items-center">
                                            <small class="text-muted me-2">
                                                <i class="fas fa-clock"></i> ${circular.created_at}
                                            </small>
                                            <span class="badge bg-${circular.priority === 'urgent' ? 'danger' : circular.priority === 'high' ? 'warning' : 'info'} me-2">
                                                ${circular.priority_text}
                                            </span>
                                        </div>
                                    </div>
                                    <div class="circular-actions">
                                        <a href="${circular.view_url}" class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        ${!isRead ? `
                                        <button class="btn btn-sm btn-outline-success mark-read-btn" data-circular-id="${circular.id}">
                                            <i class="fas fa-check"></i>
                                        </button>
                                        ` : ''}
                                    </div>
                                </div>
                            </div>
                        `;
                    });
                } else {
                    html = `
                        <div class="text-center py-4">
                            <i class="fas fa-bullhorn text-muted fa-3x mb-3"></i>
                            <p class="text-muted">हाल कुनै सूचना उपलब्ध छैन</p>
                        </div>
                    `;
                }
                
                container.html(html);
                attachCircularEventHandlers();
            }

            function attachCircularEventHandlers() {
                // Mark as read functionality
                $('.mark-read-btn').on('click', function() {
                    const circularId = $(this).data('circular-id');
                    const button = $(this);
                    
                    $.ajax({
                        url: "{{ route('student.circulars.mark-read', ':circularId') }}".replace(':circularId', circularId),
                        method: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            if (response.success) {
                                button.closest('.circular-item').removeClass('unread').addClass('read');
                                button.remove();
                                updateUnreadCount();
                            }
                        }
                    });
                });
            }

            function updateUnreadCount() {
                $.ajax({
                    url: '{{ route("student.dashboard") }}?unread_count=true',
                    method: 'GET',
                    success: function(response) {
                        if (response.unread_count !== undefined) {
                            const badge = $('.notification-dot');
                            if (response.unread_count > 0) {
                                badge.text(response.unread_count);
                                badge.show();
                            } else {
                                badge.hide();
                            }
                        }
                    }
                });
            }

            // Initialize circular updates if on dashboard or circulars page
            if (window.location.pathname.includes('dashboard') || window.location.pathname.includes('circulars')) {
                updateStudentCirculars();
                setInterval(updateStudentCirculars, 60000); // Update every minute
            }

            // Real-time circular notifications for students
            function checkNewCirculars() {
                $.ajax({
                    url: '{{ route("student.circulars.index") }}?check_new=true',
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
                                showStudentAlert('नयाँ सूचना', 'तपाईंसँग १ नयाँ सूचना छ', 'info');
                            } else {
                                showStudentAlert('नयाँ सूचनाहरू', `तपाईंसँग ${response.new_circulars} नयाँ सूचनाहरू छन्`, 'info');
                            }
                        }
                    }
                });
            }

            // Helper function to show alerts for students
            function showStudentAlert(title, message, type) {
                const alertClass = type === 'success' ? 'alert-success' : 
                                 type === 'error' ? 'alert-danger' : 'alert-info';
                const icon = type === 'success' ? 'fa-check-circle' : 
                            type === 'error' ? 'fa-exclamation-circle' : 'fa-info-circle';
                
                const alertHtml = `
                    <div class="alert ${alertClass} alert-dismissible fade show mb-4 rounded-xl alert-nepali" role="alert">
                        <div class="d-flex align-items-center">
                            <i class="fas ${icon} me-2"></i>
                            <strong>${title}:</strong> ${message}
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

            // Check for new circulars every 30 seconds
            setInterval(checkNewCirculars, 30000);

            // Bulk mark as read functionality
            $(document).on('click', '.bulk-mark-read-btn', function() {
                const selectedCirculars = $('.circular-bulk-select:checked');
                const circularIds = selectedCirculars.map(function() {
                    return $(this).val();
                }).get();

                if (circularIds.length === 0) {
                    showStudentAlert('चेतावनी', 'कुनै सूचना चयन गरिएको छैन', 'error');
                    return;
                }

                $.ajax({
                    url: '{{ route("student.circulars.bulk-mark-read") }}',
                    method: 'POST',
                    data: {
                        circular_ids: circularIds,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        if (response.success) {
                            showStudentAlert('सफलता', response.message, 'success');
                            // Update the UI
                            selectedCirculars.each(function() {
                                const circularItem = $(this).closest('.circular-item');
                                circularItem.removeClass('unread').addClass('read');
                                circularItem.find('.mark-read-btn').remove();
                            });
                            updateUnreadCount();
                        }
                    },
                    error: function(xhr) {
                        showStudentAlert('त्रुटि', 'अनुरोध असफल भयो', 'error');
                    }
                });
            });

            // Select all functionality for circulars
            $(document).on('change', '.select-all-circulars', function() {
                const isChecked = $(this).prop('checked');
                $('.circular-bulk-select').prop('checked', isChecked);
            });
        });
    </script>
    
    <!-- Page-specific JavaScript -->
    @stack('scripts')
</body>
</html>