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
    @vite(['resources/css/app.css', 'resources/css/dashboard-mobile.css', 'resources/js/app.js'])
    
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
        
        body {
            font-family: 'Noto Sans Devanagari', sans-serif;
            background-color: #f8fafc;
            margin: 0;
            padding: 0;
            overflow-x: hidden;
        }
        
        .sidebar {
            width: var(--sidebar-width);
            transition: all var(--transition-speed);
            background: linear-gradient(45deg, var(--primary-color), var(--primary-dark)) !important;
            position: fixed;
            left: 0;
            top: 0;
            height: 100vh;
            z-index: 1040;
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
        
        /* ✅ FIXED: Mobile header height and logo sizing */
        .header-content {
            padding: 0.5rem 1rem !important;
            min-height: 56px !important;
        }
        
        .navbar-brand {
            font-size: 0.95rem !important;
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
            height: 32px !important;
            width: auto !important;
            object-fit: contain !important;
        }
        
        .logo-text {
            margin-left: 8px !important;
            color: white !important;
            font-weight: 600 !important;
            font-size: 16px !important;
        }
        
        .mobile-logo {
            height: 28px !important;
            width: auto !important;
        }
        
        /* Component-specific styles */
        .alert-dismissible {
            transition: opacity 0.5s !important;
        }

        /* ✅ MOBILE-FIRST: Main content area */
        .main-content-area {
            width: 100vw !important;
            min-height: 100vh !important;
            display: flex !important;
            flex-direction: column !important;
            transition: all var(--transition-speed) !important;
            margin-left: 0 !important;
        }

        /* Desktop sidebar adjustments */
        @media (min-width: 1024px) {
            .main-content-area {
                margin-left: var(--sidebar-width) !important;
                width: calc(100vw - var(--sidebar-width)) !important;
            }
            
            .sidebar.collapsed ~ .main-content-area {
                margin-left: var(--sidebar-collapsed-width) !important;
                width: calc(100vw - var(--sidebar-collapsed-width)) !important;
            }
            
            .sidebar {
                position: fixed !important;
                transform: translateX(0) !important;
            }
        }

        /* ✅ ENHANCED: Mobile sidebar styles */
        @media (max-width: 1023px) {
            .sidebar {
                transform: translateX(-100%) !important;
                width: 280px !important;
                box-shadow: 5px 0 15px rgba(0, 0, 0, 0.2) !important;
                z-index: 1040 !important;
            }
            
            .sidebar.open {
                transform: translateX(0) !important;
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
            #main-content {
                padding-top: 56px !important;
            }
            
            /* Mobile overlay */
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
            
            /* Mobile-optimized buttons */
            .btn-mobile {
                width: 100% !important;
                margin-bottom: 0.5rem !important;
            }
            
            /* Mobile form inputs */
            .form-control-mobile {
                font-size: 16px !important;
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

        /* Tablet adjustments */
        @media (min-width: 768px) and (max-width: 1023px) {
            .sidebar {
                width: 300px !important;
            }
            
            .stats-grid-mobile {
                grid-template-columns: repeat(2, 1fr) !important;
            }
            
            .header-content {
                padding: 0.5rem 1.5rem !important;
            }
            
            .mobile-logo {
                height: 30px !important;
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

        /* ✅ ADDED: Bulk Actions Specific Styles */
        .bulk-actions-panel {
            background: #f8f9fa !important;
            padding: 1rem !important;
            border-radius: 0.5rem !important;
            border: 1px solid #e3e6f0 !important;
            margin-bottom: 1rem !important;
            transition: all 0.3s ease !important;
        }
        
        .bulk-actions-panel.show {
            display: flex !important;
            align-items: center !important;
            gap: 1rem !important;
        }
        
        .bulk-select-all {
            margin-right: 0.5rem !important;
        }
        
        .hostel-checkbox {
            margin-right: 0.5rem !important;
        }

        /* Fix table responsiveness */
        .table-responsive {
            overflow-x: auto !important;
            -webkit-overflow-scrolling: touch !important;
        }

        /* Ensure table doesn't break layout */
        .dataTables_wrapper {
            width: 100% !important;
        }

        /* Fix card body padding for tables */
        .card-body {
            padding: 1.5rem !important;
            width: 100% !important;
        }

        /* Ensure proper spacing for bulk actions */
        .bulk-actions {
            background: #f8f9fa;
            padding: 0.75rem;
            border-radius: 0.5rem;
            border: 1px solid #e3e6f0;
        }

        /* Fix button group spacing */
        .btn-group {
            flex-wrap: nowrap !important;
        }

        /* 🔥 FIX: Header spacing */
        header.bg-gradient-primary {
            position: relative !important;
            z-index: 10 !important;
        }

        /* 🔥 FIX: Body and html height */
        html, body {
            height: 100% !important;
            margin: 0 !important;
            padding: 0 !important;
            overflow-x: hidden !important;
        }

        /* 🔥 FIX: Card spacing */
        .card {
            margin-bottom: 1.5rem !important;
        }

        .card-body {
            padding: 1.5rem !important;
        }

        /* 🔥 FIX: Statistics cards grid */
        .row.mb-4 {
            margin: 0 -0.75rem !important;
        }

        .row.mb-4 > [class*="col-"] {
            padding: 0 0.75rem !important;
            margin-bottom: 1rem !important;
        }

        /* 🔥 FIX: Bulk actions panel */
        .bulk-actions {
            background: #f8f9fa !important;
            padding: 0.75rem !important;
            border-radius: 0.5rem !important;
            border: 1px solid #e3e6f0 !important;
            margin-bottom: 1rem !important;
        }

        /* 🔥 FIX: Search form layout */
        #searchForm .row {
            margin: 0 -0.5rem !important;
        }

        #searchForm .row > [class*="col-"] {
            padding: 0 0.5rem !important;
        }

        /* 🔥 FIX: Ensure content flows properly */
        .flex-1.overflow-y-auto {
            flex: 1 !important;
            display: flex !important;
            flex-direction: column !important;
        }

        /* 🔥 FIX: Footer stays at bottom */
        footer {
            margin-top: auto !important;
            background: white !important;
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
                padding-top: max(0.5rem, env(safe-area-inset-top));
            }
            
            .safe-area-bottom {
                padding-bottom: max(0.5rem, env(safe-area-inset-bottom));
            }
            
            .safe-area-left {
                padding-left: max(0.5rem, env(safe-area-inset-left));
            }
            
            .safe-area-right {
                padding-right: max(0.5rem, env(safe-area-inset-right));
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
        
        /* ✅ ADDED: User name styling for better fit */
        .user-name {
            font-size: 0.875rem !important;
            max-width: 120px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }
        
        /* ✅ ADDED: Critical layout overflow fixes */
        .overflow-fix {
            overflow: visible !important;
        }
        
        .min-w-full {
            min-width: 100% !important;
        }

        .d-none {
            display: none !important;
        }
        
        .d-flex {
            display: flex !important;
        }
        
        /* 🔥 VITE FALLBACK STYLES */
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
        
        /* Mobile fixes - Override for existing styles */
        @media (max-width: 1023px) {
            .main-content-spacing {
                margin-left: 0 !important;
                width: 100vw !important;
            }
            
            .max-w-7xl.mx-auto {
                padding: 0 0.5rem !important;
                max-width: none !important;
            }
            
            /* ✅ FIXED: Mobile header adjustments */
            .header-content {
                padding-top: 0.4rem !important;
                padding-bottom: 0.4rem !important;
                min-height: 56px !important;
            }
            
            .mobile-logo {
                height: 26px !important;
            }
            
            .navbar-brand span {
                font-size: 0.85rem !important;
            }
            
            /* Mobile table fixes */
            .table-responsive {
                font-size: 0.875rem !important;
            }
            
            .bulk-actions-panel {
                flex-direction: column !important;
                gap: 0.5rem !important;
            }
            
            .btn-group {
                flex-wrap: wrap !important;
                gap: 0.25rem;
            }
            
            .btn-group .btn {
                margin-bottom: 0.25rem;
            }

            /* 🔥 FIX: Mobile responsiveness */
            #main-content {
                padding: 0.75rem !important;
            }
        }
    </style>
    
    <!-- Page-specific CSS -->
    @stack('styles')
</head>

<body class="bg-gray-50 font-sans" 
      x-data="{ 
        darkMode: localStorage.getItem('darkMode') === 'true', 
        sidebarCollapsed: localStorage.getItem('sidebarCollapsed') === 'true',
        mobileSidebarOpen: false 
      }" 
      x-on:keydown.escape="mobileSidebarOpen = false"
      :class="{ 
        'dark-mode': darkMode,
        'sidebar-open': mobileSidebarOpen 
      }">
    <a href="#main-content" class="skip-link nepali">मुख्य सामग्रीमा जानुहोस्</a>
    
    <div class="flex min-h-screen">
        <!-- Sidebar Component -->
        <aside id="sidebar" 
               class="sidebar text-white z-20 flex-shrink-0 transition-all duration-300 ease-in-out flex flex-col h-full"
               :class="{ 
                 'collapsed': sidebarCollapsed,
                 'open': mobileSidebarOpen 
               }">
            <div class="p-4 border-b border-blue-700 flex items-center justify-between">
                <a href="{{ url('/admin/dashboard') }}" class="logo-container">
                    <!-- LOGO WITH FALLBACK -->
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
                    <span class="logo-text sidebar-text" x-show="!sidebarCollapsed">होस्टलहब</span>
                </a>
                <button @click="sidebarCollapsed = !sidebarCollapsed; localStorage.setItem('sidebarCollapsed', sidebarCollapsed)" 
                        class="text-gray-300 hover:text-white sidebar-text desktop-only" 
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
                
                <!-- ✅ NEW: Featured Hostels Menu -->
                <a href="{{ route('admin.hostels.featured') }}"
                   class="sidebar-link {{ request()->routeIs('admin.hostels.featured*') ? 'active' : '' }}"
                   aria-current="{{ request()->routeIs('admin.hostels.featured*') ? 'page' : 'false' }}">
                    <i class="fas fa-star sidebar-icon"></i>
                    <span class="sidebar-text" x-show="!sidebarCollapsed">
                        फिचर्ड होस्टलहरू
                        @php
                            $featuredCount = \App\Models\Hostel::where('is_featured', true)->count();
                        @endphp
                        @if($featuredCount > 0)
                            <span class="badge bg-yellow-500 text-white text-xs ml-2">{{ $featuredCount }}</span>
                        @endif
                    </span>
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

                <!-- Divider -->
<hr class="sidebar-divider">

<!-- Heading - नेटवर्क कन्ट्रोल -->
@can('view-network-dashboard')
    <div class="sidebar-heading">
        नेटवर्क कन्ट्रोल
    </div>
@endcan

<!-- नेटवर्क ड्यासबोर्ड -->
@can('view-network-dashboard')
    <a href="{{ route('admin.network.dashboard') }}"
       class="sidebar-link {{ request()->routeIs('admin.network.dashboard') ? 'active' : '' }}">
        <i class="fas fa-fw fa-tachometer-alt sidebar-icon"></i>
        <span class="sidebar-text" x-show="!sidebarCollapsed">ड्यासबोर्ड</span>
    </a>
@endcan

<!-- प्रोफाइलहरू -->
@can('view-network-dashboard')
    <a href="{{ route('admin.network.profiles.index') }}"
       class="sidebar-link {{ request()->routeIs('admin.network.profiles.*') ? 'active' : '' }}">
        <i class="fas fa-fw fa-id-card sidebar-icon"></i>
        <span class="sidebar-text" x-show="!sidebarCollapsed">प्रोफाइलहरू</span>
    </a>
@endcan

<!-- ब्रोडकास्ट -->
@can('view-network-dashboard')
    <a href="{{ route('admin.network.broadcasts.index') }}"
       class="sidebar-link {{ request()->routeIs('admin.network.broadcasts.*') ? 'active' : '' }}">
        <i class="fas fa-fw fa-bullhorn sidebar-icon"></i>
        <span class="sidebar-text" x-show="!sidebarCollapsed">ब्रोडकास्ट</span>
    </a>
@endcan

<!-- मार्केटप्लेस -->
@can('view-network-dashboard')
    <a href="{{ route('admin.network.marketplace.index') }}"
       class="sidebar-link {{ request()->routeIs('admin.network.marketplace.*') ? 'active' : '' }}">
        <i class="fas fa-fw fa-store sidebar-icon"></i>
        <span class="sidebar-text" x-show="!sidebarCollapsed">मार्केटप्लेस</span>
    </a>
@endcan

<!-- सन्देशहरू -->
@can('view-network-dashboard')
    <a href="{{ route('admin.network.messages.index') }}"
       class="sidebar-link {{ request()->routeIs('admin.network.messages.*') ? 'active' : '' }}">
        <i class="fas fa-fw fa-envelope sidebar-icon"></i>
        <span class="sidebar-text" x-show="!sidebarCollapsed">सन्देशहरू</span>
    </a>
@endcan

<!-- रिपोर्टहरू -->
@can('view-network-dashboard')
    <a href="{{ route('admin.network.reports.index') }}"
       class="sidebar-link {{ request()->routeIs('admin.network.reports.*') ? 'active' : '' }}">
        <i class="fas fa-fw fa-flag sidebar-icon"></i>
        <span class="sidebar-text" x-show="!sidebarCollapsed">रिपोर्टहरू</span>
    </a>
@endcan
                
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
                        <button type="submit" class="w-full flex items-center px-2 py-2 text-sm rounded-md hover:bg-blue-700 transition-colors tap-target">
                            <i class="fas fa-sign-out-alt sidebar-icon"></i>
                            <span class="sidebar-text" x-show="!sidebarCollapsed">लगआउट</span>
                        </button>
                    </form>
                </div>
            </nav>
        </aside>

        <!-- Main Content Area - MOBILE FIRST -->
        <div class="main-content-area">
            <!-- Top Navigation -->
            <header class="bg-gradient-primary shadow-sm">
                <div class="flex items-center justify-between px-4 header-content safe-area-top safe-area-left safe-area-right">
                    <div class="flex items-center">
                        <!-- ✅ UPDATED: Mobile sidebar toggle button -->
                        <button @click="mobileSidebarOpen = !mobileSidebarOpen" 
                                class="text-white hover:text-gray-200 mr-3 tap-target mobile-only" 
                                aria-label="मोबाइल साइडबार खोल्नुहोस्"
                                :aria-expanded="mobileSidebarOpen">
                            <i class="fas fa-bars text-xl" x-show="!mobileSidebarOpen"></i>
                            <i class="fas fa-times text-xl" x-show="mobileSidebarOpen"></i>
                        </button>
                        
                        <!-- Brand with Logo -->
                        <a href="{{ url('/admin/dashboard') }}" class="navbar-brand text-white flex items-center">
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
                            <span class="hidden md:inline text-sm">होस्टलहब - प्रशासक प्यानल</span>
                            <span class="md:hidden text-xs ml-2">प्रशासक प्यानल</span>
                        </a>
                    </div>
                    
                    <div class="flex items-center space-x-2">
                        <!-- Dark Mode Toggle -->
                        <button @click="darkMode = !darkMode; localStorage.setItem('darkMode', darkMode)" 
                                class="text-white hover:text-gray-200 dark-mode-toggle p-2 rounded-full hover:bg-blue-700 tap-target" 
                                aria-label="डार्क मोड टगल गर्नुहोस्">
                            <i class="fas fa-moon" x-show="!darkMode"></i>
                            <i class="fas fa-sun" x-show="darkMode"></i>
                        </button>
                        
                        <!-- Notifications -->
                        {{-- नोटिफिकेसन ड्रपडाउन (Alpine.js) --}}
<div class="relative" x-data="{ open: false }">
    {{-- नोटिफिकेसन बटन --}}
    <button @click="open = !open" class="relative p-2 text-white hover:text-gray-200 focus:outline-none" aria-label="सूचनाहरू">
        <i class="fas fa-bell text-xl"></i>
        @if(isset($unreadCount) && $unreadCount > 0)
            <span class="absolute top-0 right-0 inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-white transform translate-x-1/2 -translate-y-1/2 bg-red-500 rounded-full">{{ $unreadCount }}</span>
        @endif
    </button>

    {{-- ड्रपडाउन मेनु --}}
    <div x-show="open" @click.away="open = false"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 transform scale-95"
         x-transition:enter-end="opacity-100 transform scale-100"
         x-transition:leave="transition ease-in duration-75"
         x-transition:leave-start="opacity-100 transform scale-100"
         x-transition:leave-end="opacity-0 transform scale-95"
         class="absolute right-0 mt-2 w-80 bg-white rounded-md shadow-lg overflow-hidden z-20 border border-gray-200">

        <div class="px-4 py-2 bg-gray-100 border-b border-gray-200">
            <h3 class="font-semibold text-gray-700">सूचनाहरू</h3>
        </div>

        <div class="max-h-96 overflow-y-auto">
            @forelse($notifications ?? [] as $notification)
                @php
                    $data = $notification->data;
                    $url = $data['url'] ?? '#';
                    $message = $data['message'] ?? 'नयाँ सूचना';
                    $isUnread = is_null($notification->read_at);
                @endphp
                <a href="{{ $url }}"
                   class="flex items-start px-4 py-3 hover:bg-gray-50 border-b border-gray-100 {{ $isUnread ? 'bg-blue-50' : '' }}"
                   onclick="event.preventDefault(); markNotificationAsRead('{{ $notification->id }}', '{{ $url }}');">
                    <div class="flex-shrink-0 mr-3">
                        <div class="bg-indigo-100 rounded-full p-2">
                            <i class="fas fa-star text-indigo-600"></i>
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
            <a href="{{ route('admin.notifications.index') }}" class="text-indigo-600 text-sm hover:underline">सबै सूचनाहरू हेर्नुहोस्</a>
        </div>
    </div>
</div>
                        
                        <!-- User Dropdown -->
                        <div class="flex items-center space-x-2 user-dropdown">
                            <span class="text-white nepali user-name" x-show="!sidebarCollapsed || window.innerWidth >= 1024">{{ Auth::user()->name ?? 'प्रशासक' }}</span>
                            <div class="relative" x-data="{ userDropdownOpen: false }">
                                <button @click="userDropdownOpen = !userDropdownOpen" class="bg-white bg-opacity-20 hover:bg-opacity-30 text-white px-3 py-2 rounded-lg flex items-center space-x-2 transition-all tap-target">
                                    <i class="fas fa-user-circle"></i>
                                    <span class="nepali text-sm hidden sm:inline">प्रशासक</span>
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
            <main id="main-content" class="flex-1 overflow-y-auto p-4 md:p-6 bg-gray-50 dark:bg-gray-800 safe-area-left safe-area-right safe-area-bottom">
                <div class="h-full">
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
         class="fixed inset-0 bg-black bg-opacity-50 z-30 lg:hidden sidebar-overlay" 
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
    
    <!-- Alpine.js -->
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    <!-- Enhanced Mobile-First JavaScript -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Vite Asset Detection
            setTimeout(function() {
                const viteStyles = document.querySelector('link[href*="build/assets/app"]');
                if (!viteStyles) {
                    document.body.classList.add('no-vite');
                    console.warn('Vite assets not detected, using fallback assets');
                }
            }, 1000);

            // ✅ ENHANCED: Mobile sidebar functionality
            const sidebar = document.getElementById('sidebar');
            const mobileSidebarToggle = document.querySelector('[x-on\\:click*="mobileSidebarOpen"]');
            const body = document.body;
            
            // Close sidebar when clicking outside on mobile
            document.addEventListener('click', function(event) {
                if (window.innerWidth < 1024 && 
                    sidebar.classList.contains('open') &&
                    !sidebar.contains(event.target) && 
                    event.target !== mobileSidebarToggle &&
                    !mobileSidebarToggle.contains(event.target)) {
                    
                    // Close sidebar using Alpine.js
                    const alpineElement = document.querySelector('[x-data]');
                    if (alpineElement && alpineElement.__x) {
                        alpineElement.__x.$data.mobileSidebarOpen = false;
                    }
                }
            });
            
            // Close sidebar on Escape key
            document.addEventListener('keydown', function(event) {
                if (event.key === 'Escape') {
                    if (window.innerWidth < 1024 && sidebar.classList.contains('open')) {
                        const alpineElement = document.querySelector('[x-data]');
                        if (alpineElement && alpineElement.__x) {
                            alpineElement.__x.$data.mobileSidebarOpen = false;
                        }
                    }
                }
            });
            
            // Close sidebar when window is resized to desktop
            window.addEventListener('resize', function() {
                if (window.innerWidth >= 1024) {
                    const alpineElement = document.querySelector('[x-data]');
                    if (alpineElement && alpineElement.__x) {
                        alpineElement.__x.$data.mobileSidebarOpen = false;
                    }
                }
            });

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
                        modalVideoPlayer.load();
                        if (videoModal) {
                            videoModal.classList.remove('hidden');
                            // Prevent body scroll when modal is open on mobile
                            if (window.innerWidth < 768) {
                                body.style.overflow = 'hidden';
                            }
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
                    // Restore body scroll
                    body.style.overflow = '';
                });
                
                videoModal.addEventListener('click', function(e) {
                    if (e.target === videoModal) {
                        videoModal.classList.add('hidden');
                        modalVideoPlayer.pause();
                        modalVideoPlayer.currentTime = 0;
                        body.style.overflow = '';
                    }
                });
                
                document.addEventListener('keydown', function(e) {
                    if (e.key === 'Escape' && !videoModal.classList.contains('hidden')) {
                        videoModal.classList.add('hidden');
                        modalVideoPlayer.pause();
                        modalVideoPlayer.currentTime = 0;
                        body.style.overflow = '';
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

            // Form submission handling with mobile optimization
            const forms = document.querySelectorAll('form');
            forms.forEach(form => {
                form.addEventListener('submit', function() {
                    const submitBtn = form.querySelector('button[type="submit"], input[type="submit"]');
                    if (submitBtn) {
                        const originalText = submitBtn.innerHTML;
                        submitBtn.disabled = true;
                        
                        // Show spinner only if not already showing
                        if (!submitBtn.querySelector('.spinner-border')) {
                            if (window.innerWidth < 768) {
                                submitBtn.innerHTML = `
                                    <span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>
                                    प्रक्रिया गर्दै...
                                `;
                            } else {
                                submitBtn.innerHTML = `
                                    <span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>
                                    प्रक्रिया गर्दै...
                                `;
                            }
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
            
            // Mobile loading functions
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
            
            // Mobile form input handling
            if (window.innerWidth < 768) {
                // Focus form inputs with better UX
                const formInputs = document.querySelectorAll('input, textarea, select');
                formInputs.forEach(input => {
                    input.addEventListener('focus', function() {
                        this.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    });
                });
            }
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
                    targetAudience.dispatchEvent(new Event('change'));
                }
                
                // Clear any file inputs
                const fileInputs = document.querySelectorAll('input[type="file"]');
                fileInputs.forEach(input => {
                    input.value = '';
                });
                
                // Mobile success feedback
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
                                
                                // Mobile-specific feedback
                                if (window.innerWidth < 768 && navigator.vibrate) {
                                    navigator.vibrate([100, 50, 100]);
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
                                if (sidebar && sidebar.classList.contains('open')) {
                                    const alpineElement = document.querySelector('[x-data]');
                                    if (alpineElement && alpineElement.__x) {
                                        alpineElement.__x.$data.mobileSidebarOpen = false;
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
                        showAdminAlert('त्रुटि', errorMessage, 'error');
                        
                        // Mobile-specific error handling
                        if (window.innerWidth < 768) {
                            window.scrollTo({ top: 0, behavior: 'smooth' });
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

            // Helper function to show alerts for admin (mobile optimized)
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
                        <button type="button" class="btn-close tap-target" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                `;
                
                // Prepend alert to main content
                const mainContent = document.getElementById('main-content');
                if (mainContent) {
                    const existingAlerts = mainContent.querySelectorAll('.alert');
                    existingAlerts.forEach(alert => alert.remove());
                    
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

            // ✅ ENHANCED: Real-time circular notifications for admin (mobile optimized)
            function checkNewCirculars() {
                // Only check if user is active (not on mobile with screen off)
                if (document.hidden) return;
                
                $.ajax({
                    url: '{{ route("admin.circulars.index") }}?check_new=true',
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
                            if (!window.location.pathname.includes('/admin/circulars')) {
                                if (response.new_circulars === 1) {
                                    showAdminAlert('नयाँ सूचना', 'तपाईंसँग १ नयाँ सूचना छ', 'info');
                                } else {
                                    showAdminAlert('नयाँ सूचनाहरू', `तपाईंसँग ${response.new_circulars} नयाँ सूचनाहरू छन्`, 'info');
                                }
                                
                                // Mobile notification sound/vibration
                                if (window.innerWidth < 768) {
                                    try {
                                        const audio = new Audio('{{ asset("sounds/notification.mp3") }}');
                                        audio.volume = 0.3;
                                        audio.play();
                                    } catch (e) {
                                        console.log('Audio notification failed');
                                    }
                                    
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
                    checkNewCirculars();
                }
            });
            
            // Start the checker
            startCircularChecker();

            // ✅ ENHANCED: Circular publish functionality for admin (mobile friendly)
            $(document).on('click', '.publish-circular-btn', function() {
                const circularId = $(this).data('circular-id');
                const button = $(this);
                
                // Mobile-friendly confirmation
                const confirmMessage = 'के तपाईं यो सूचना प्रकाशित गर्न चाहनुहुन्छ?';
                
                if (window.innerWidth < 768) {
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
                        url: "{{ route('admin.circulars.publish', ['circular' => 'CIRCULAR_ID_PLACEHOLDER']) }}".replace('CIRCULAR_ID_PLACEHOLDER', circularId),
                        method: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            if (response.success) {
                                showAdminAlert('सफलता', response.message, 'success');
                                button.replaceWith('<span class="badge bg-success">प्रकाशित</span>');
                                
                                // Mobile success feedback
                                if (window.innerWidth < 768 && navigator.vibrate) {
                                    navigator.vibrate([100]);
                                }
                                
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
                        },
                        complete: function() {
                            if (window.innerWidth < 768) {
                                window.hideMobileLoading();
                            }
                        }
                    });
                }
            });

            // ✅ ENHANCED: Circular delete confirmation for admin (mobile friendly)
            $(document).on('click', '.delete-circular-btn', function(e) {
                e.preventDefault();
                
                const form = $(this).closest('form');
                const circularTitle = $(this).data('circular-title') || 'यो सूचना';
                const confirmMessage = `के तपाईं ${circularTitle} लाई मेट्न चाहनुहुन्छ? यो कार्य पूर्ववत गर्न सकिँदैन।`;
                
                if (window.innerWidth < 768) {
                    const mobileConfirm = confirm(confirmMessage);
                    if (mobileConfirm) {
                        window.showMobileLoading();
                        form.submit();
                    }
                } else {
                    if (confirm(confirmMessage)) {
                        form.submit();
                    }
                }
            });

            // ✅ FIXED: Bulk actions for circulars in admin - NO CONFLICT VERSION
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

            $(document).on('click', '.bulk-select-all-circulars', function() {
                $('.circular-bulk-select').prop('checked', this.checked);
                $('.circular-bulk-select').trigger('change');
            });

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

        // ✅ ADDED: Generic Bulk Actions JavaScript for Hostels and Other Modules
        document.addEventListener('DOMContentLoaded', function() {
            // Bulk selection functionality for hostels and other modules
            const selectAll = document.getElementById('selectAll');
            const itemCheckboxes = document.querySelectorAll('.item-checkbox, .hostel-checkbox');
            const bulkActionsPanel = document.getElementById('bulkActionsPanel');
            const bulkActionSelect = document.getElementById('bulkActionSelect');
            const applyBulkAction = document.getElementById('applyBulkAction');
            const cancelBulkAction = document.getElementById('cancelBulkAction');

            // Select All functionality
            if (selectAll) {
                selectAll.addEventListener('change', function() {
                    const isChecked = this.checked;
                    itemCheckboxes.forEach(checkbox => {
                        checkbox.checked = isChecked;
                    });
                    toggleBulkActionsPanel();
                });
            }

            // Individual checkbox functionality
            itemCheckboxes.forEach(checkbox => {
                checkbox.addEventListener('change', toggleBulkActionsPanel);
            });

            // Toggle bulk actions panel
            function toggleBulkActionsPanel() {
                const checkedCount = document.querySelectorAll('.item-checkbox:checked, .hostel-checkbox:checked').length;
                if (checkedCount > 0 && bulkActionsPanel) {
                    bulkActionsPanel.classList.remove('d-none');
                    bulkActionsPanel.classList.add('d-flex');
                    if (selectAll) {
                        selectAll.checked = checkedCount === itemCheckboxes.length;
                    }
                } else if (bulkActionsPanel) {
                    bulkActionsPanel.classList.add('d-none');
                    bulkActionsPanel.classList.remove('d-flex');
                    if (selectAll) {
                        selectAll.checked = false;
                    }
                }
            }

            // Apply bulk action - SIMPLIFIED VERSION
            if (applyBulkAction) {
                applyBulkAction.addEventListener('click', function() {
                    const action = bulkActionSelect ? bulkActionSelect.value : '';
                    if (!action) {
                        alert('कृपया बल्क कार्य छान्नुहोस्');
                        return;
                    }

                    const selectedIds = Array.from(document.querySelectorAll('.item-checkbox:checked, .hostel-checkbox:checked'))
                        .map(checkbox => checkbox.value);

                    if (selectedIds.length === 0) {
                        alert('कुनै आइटम चयन गरिएको छैन');
                        return;
                    }

                    // Simple confirmation
                    if (!confirm(`के तपाइँ ${selectedIds.length} आइटमहरूमा यो कार्य गर्न चाहनुहुन्छ?`)) {
                        return;
                    }

                    // Simple form submission
                    const form = document.createElement('form');
                    form.method = 'POST';
                    
                    // Determine the appropriate URL based on current page
                    const currentPath = window.location.pathname;
                    if (currentPath.includes('hostels')) {
                        form.action = '{{ route("admin.hostels.bulk-operations") }}';
                    } else if (currentPath.includes('circulars')) {
                        form.action = '{{ route("admin.circulars.bulk-operations") }}';
                    } else if (currentPath.includes('students')) {
                        form.action = '{{ route("admin.students.bulk-operations") }}';
                    } else {
                        form.action = '{{ route("admin.bulk-operations") }}';
                    }
                    
                    const csrfToken = document.createElement('input');
                    csrfToken.type = 'hidden';
                    csrfToken.name = '_token';
                    csrfToken.value = '{{ csrf_token() }}';
                    form.appendChild(csrfToken);

                    const actionInput = document.createElement('input');
                    actionInput.type = 'hidden';
                    actionInput.name = 'action';
                    actionInput.value = action;
                    form.appendChild(actionInput);

                    selectedIds.forEach(id => {
                        const input = document.createElement('input');
                        input.type = 'hidden';
                        input.name = 'item_ids[]';
                        input.value = id;
                        form.appendChild(input);
                    });

                    document.body.appendChild(form);
                    form.submit();
                });
            }

            // Cancel bulk action
            if (cancelBulkAction) {
                cancelBulkAction.addEventListener('click', function() {
                    itemCheckboxes.forEach(checkbox => {
                        checkbox.checked = false;
                    });
                    toggleBulkActionsPanel();
                    if (bulkActionSelect) {
                        bulkActionSelect.value = '';
                    }
                });
            }

            // Initialize bulk actions on page load
            toggleBulkActionsPanel();
        });

        // ✅ FIXED: Window resize handler for mobile/desktop transitions
        window.addEventListener('resize', function() {
            const alpineElement = document.querySelector('[x-data]');
            if (!alpineElement || !alpineElement.__x) return;
            
            // Close mobile sidebar when switching to desktop
            if (window.innerWidth >= 1024) {
                alpineElement.__x.$data.mobileSidebarOpen = false;
            }
            
            // Update sidebar collapsed state for mobile
            if (window.innerWidth < 1024) {
                alpineElement.__x.$data.sidebarCollapsed = false;
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
            const alpineElement = document.querySelector('[x-data]');
            
            if (!alpineElement || !alpineElement.__x) return;
            
            // Swipe right to open sidebar (only from left edge on mobile)
            if (swipeDistance > swipeThreshold && touchStartX < 50 && window.innerWidth < 1024) {
                if (!alpineElement.__x.$data.mobileSidebarOpen) {
                    alpineElement.__x.$data.mobileSidebarOpen = true;
                }
            }
            // Swipe left to close sidebar
            else if (swipeDistance < -swipeThreshold && window.innerWidth < 1024) {
                if (alpineElement.__x.$data.mobileSidebarOpen) {
                    alpineElement.__x.$data.mobileSidebarOpen = false;
                }
            }
        }
    </script>

    <script>
    function markNotificationAsRead(notificationId, url) {
        fetch('/notifications/' + notificationId + '/mark-as-read', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json',
            },
        }).then(response => {
            if (response.ok) {
                // सफल भयो भने redirect
                window.location.href = url;
            } else {
                // भएन भने पनि redirect
                window.location.href = url;
            }
        }).catch(error => {
            console.error('Error:', error);
            window.location.href = url;
        });
    }
</script>
</body>
</html>