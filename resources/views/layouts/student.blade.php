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
            color: #333333 !important;
        }
        
        /* 🎯 MOBILE-FIRST RESPONSIVE STYLES */
        
        /* Mobile (< 1024px) */
        @media (max-width: 1023px) {
            body {
                overflow-x: hidden !important;
                position: relative !important;
                color: #333333 !important;
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
                background: linear-gradient(45deg, #4e73df, #224abe) !important;
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
                color: #333333 !important;
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

        /* =========================================== */
        /* 🎯 STUDENT DASHBOARD MOBILE FIXES - SCOPED */
        /* =========================================== */

        /* 🔹 MOBILE HEADER HEIGHT FIX (max-width: 768px) */
        @media (max-width: 768px) {
          .student-dashboard {
            /* Reset any problematic body styles */
            overflow-x: hidden !important;
          }
          
          /* FIX 1: Header height reduction */
          .student-dashboard .header-fixed {
            height: 56px !important;
            min-height: 56px !important;
          }
          
          .student-dashboard .header-content {
            height: 56px !important;
            padding-top: 0 !important;
            padding-bottom: 0 !important;
          }
          
          /* FIX 2: Header content alignment */
          .student-dashboard .header-content > div:first-child {
                flex: 1 1 auto;
                min-width: 0;
          }
          
          .student-dashboard .header-content > div:last-child {
                flex-shrink: 0;
          }
          
          /* FIX 3: Mobile menu button visibility */
          .student-dashboard #mobile-sidebar-toggle {
                width: 44px;
                height: 44px;
                display: flex !important;
                align-items: center;
                justify-content: center;
                padding: 0 !important;
                margin-right: 8px !important;
                background: rgba(255, 255, 255, 0.1);
                border-radius: 8px;
          }
          
          /* FIX 4: Logo/brand adjustment */
          .student-dashboard .navbar-brand {
                font-size: 14px !important;
                white-space: nowrap;
                overflow: hidden;
                text-overflow: ellipsis;
                max-width: 120px;
          }
          
          .student-dashboard .navbar-brand img,
          .student-dashboard .navbar-brand .mobile-text-logo {
                width: 32px !important;
                height: 32px !important;
                margin-right: 8px !important;
          }
          
          /* FIX 5: Header buttons container */
          .student-dashboard .header-content .flex.items-center.space-x-3 {
                gap: 4px !important;
          }
          
          /* FIX 6: Student badge visibility */
          .student-dashboard .student-badge {
                padding: 4px 8px !important;
                font-size: 12px !important;
                margin-right: 4px !important;
                display: none;
          }
          
          /* FIX 7: Notification button */
          .student-dashboard .notification-button {
                width: 40px !important;
                height: 40px !important;
                padding: 0 !important;
                display: flex;
                align-items: center;
                justify-content: center;
          }
          
          .student-dashboard .notification-button i {
                font-size: 18px !important;
          }
          
          /* FIX 8: User dropdown button */
          .student-dashboard .user-dropdown .btn {
                padding: 6px 10px !important;
                font-size: 13px !important;
                height: 40px !important;
          }
          
          .student-dashboard .user-dropdown .btn i {
                margin-right: 4px !important;
          }
          
          .student-dashboard .user-dropdown .btn span {
                display: none;
          }
          
          /* FIX 9: Dropdown menus visibility */
          .student-dashboard .dropdown-menu {
                font-size: 14px !important;
                min-width: 200px !important;
          }
          
          /* FIX 10: Ensure text contrast */
          .student-dashboard .header-content * {
                color: white !important;
          }
        }

        /* 🔹 CONTENT SPACING FIX */
        @media (max-width: 768px) {
          /* FIX 1: Main content offset */
          .student-dashboard .page-content {
                padding-top: calc(56px + 16px) !important;
          }
          
          /* FIX 2: Dashboard content spacing */
          .student-dashboard .page-content > .bg-blue-800.rounded-2xl {
                margin-top: 8px !important;
          }
          
          /* FIX 3: Remove any top margins that cause overlap */
          .student-dashboard .page-content > *:first-child {
                margin-top: 0 !important;
          }
          
          /* FIX 4: Safe scrolling area */
          .student-dashboard .main-content-container {
                min-height: calc(100vh - 56px) !important;
          }
        }

        /* 🔹 VISIBILITY FIXES */
        @media (max-width: 768px) {
          /* FIX 1: Card contrast */
          .student-dashboard .bg-white,
          .student-dashboard .card,
          .student-dashboard .circular-item {
                background-color: white !important;
                color: #333333 !important;
                border: 1px solid #e5e7eb !important;
          }
          
          /* FIX 2: Text readability */
          .student-dashboard h1, 
          .student-dashboard h2, 
          .student-dashboard h3 {
                color: #1f2937 !important;
                line-height: 1.3 !important;
          }
          
          .student-dashboard p,
          .student-dashboard span:not(.header-content *) {
                color: #4b5563 !important;
                line-height: 1.5 !important;
          }
          
          /* FIX 3: Button visibility */
          .student-dashboard .btn {
                min-height: 44px !important;
                padding: 10px 16px !important;
                font-size: 14px !important;
                font-weight: 500 !important;
          }
          
          .student-dashboard .btn-primary {
                background-color: #3b82f6 !important;
                color: white !important;
          }
          
          .student-dashboard .btn-outline-primary {
                color: #3b82f6 !important;
                border-color: #3b82f6 !important;
          }
          
          /* FIX 4: Icon visibility */
          .student-dashboard i:not(.header-content *) {
                color: #6b7280 !important;
          }
          
          .student-dashboard .bg-blue-100 i,
          .student-dashboard .bg-green-100 i,
          .student-dashboard .bg-amber-100 i,
          .student-dashboard .bg-indigo-100 i {
                color: inherit !important;
          }
          
          /* FIX 5: Table/cell visibility */
          .student-dashboard table * {
                font-size: 13px !important;
          }
          
          .student-dashboard th,
          .student-dashboard td {
                padding: 8px !important;
          }
        }

        /* 🔹 MOBILE ENHANCEMENTS (VERY LIMITED) */
        @media (max-width: 768px) {
          /* ENH 1: Card spacing */
          .student-dashboard .bg-white.rounded-2xl {
                margin-bottom: 16px !important;
                padding: 16px !important;
          }
          
          /* ENH 2: Grid spacing */
          .student-dashboard .grid {
                gap: 12px !important;
          }
          
          /* ENH 3: Section spacing */
          .student-dashboard .space-y-6 > * + * {
                margin-top: 20px !important;
          }
          
          /* ENH 4: Dashboard stats grid - 2 columns */
          .student-dashboard .grid.grid-cols-2 {
                grid-template-columns: repeat(2, 1fr) !important;
          }
          
          .student-dashboard .grid.grid-cols-2 > * {
                min-height: 100px !important;
          }
          
          /* ENH 5: Quick actions grid - 3 columns */
          .student-dashboard .grid.grid-cols-2.gap-3 {
                grid-template-columns: repeat(3, 1fr) !important;
                gap: 8px !important;
          }
          
          /* ENH 6: Welcome section improvement */
          .student-dashboard .bg-blue-800 .text-white {
                font-size: 15px !important;
          }
          
          .student-dashboard .bg-blue-800 h2 {
                font-size: 18px !important;
                margin-bottom: 4px !important;
          }
        }

        /* 🔹 EXTRA SMALL DEVICES (max-width: 576px) */
        @media (max-width: 576px) {
          .student-dashboard .navbar-brand span {
                display: none;
          }
          
          .student-dashboard .notification-button {
                width: 36px !important;
                height: 36px !important;
          }
          
          .student-dashboard .user-dropdown .btn {
                padding: 6px !important;
                min-width: 36px !important;
          }
          
          .student-dashboard .grid.grid-cols-2 {
                grid-template-columns: 1fr !important;
          }
          
          .student-dashboard .grid.grid-cols-2.gap-3 {
                grid-template-columns: repeat(2, 1fr) !important;
          }
        }

        /* =========================================== */
        /* 🎯 CRITICAL MOBILE FIXES - SCOPE: STUDENT DASHBOARD ONLY */
        /* =========================================== */

        /* 🔹 1. SIDEBAR TEXT COLOR FIX (Desktop + Mobile) */
        .student-dashboard .sidebar,
        .student-dashboard .sidebar *:not(.dropdown-menu *) {
            color: #ffffff !important;
        }

        .student-dashboard .sidebar a,
        .student-dashboard .sidebar span,
        .student-dashboard .sidebar i,
        .student-dashboard .sidebar .logo-text,
        .student-dashboard .sidebar .sidebar-text,
        .student-dashboard .sidebar .sidebar-link {
            color: #ffffff !important;
        }

        .student-dashboard .sidebar .sidebar-link.active {
            color: #ffffff !important;
            background-color: rgba(255, 255, 255, 0.15) !important;
        }

        /* 🔹 2. ICON COLOR FIXES (Mobile) */
        @media (max-width: 768px) {
            .student-dashboard .page-content i:not(.header-content i) {
                color: inherit !important;
            }
            
            .student-dashboard .page-content .fa-utensils,
            .student-dashboard .page-content .fa-money-bill-wave,
            .student-dashboard .page-content .fa-bullhorn,
            .student-dashboard .page-content .fa-door-open,
            .student-dashboard .page-content .fa-star,
            .student-dashboard .page-content .fa-calendar-check,
            .student-dashboard .page-content .fa-images,
            .student-dashboard .page-content .fa-calendar-alt {
                color: #4e73df !important;
            }
            
            .student-dashboard .page-content .bg-blue-100 i,
            .student-dashboard .page-content .bg-green-100 i,
            .student-dashboard .page-content .bg-amber-100 i,
            .student-dashboard .page-content .bg-indigo-100 i {
                color: inherit !important;
            }
        }

        /* 🔹 3. BUTTON COLOR FIXES (Mobile) - EXACT DESKTOP STYLES */
        @media (max-width: 768px) {
            .student-dashboard .page-content .btn:not(.btn-primary):not(.btn-outline-primary) {
                background-color: #f8fafc !important;
                color: #333333 !important;
                border: 1px solid #e5e7eb !important;
            }
            
            /* EXACT DESKTOP PRIMARY BUTTON */
            .student-dashboard .page-content .btn-primary {
                background: linear-gradient(45deg, #4e73df, #224abe) !important;
                border: none !important;
                box-shadow: 0 2px 5px rgba(78, 115, 223, 0.3) !important;
                color: white !important;
                border-radius: 0.5rem !important;
                font-weight: 600 !important;
                padding: 0.5rem 1rem !important;
                display: inline-flex !important;
                align-items: center !important;
                justify-content: center !important;
                transition: all 0.3s !important;
            }
            
            .student-dashboard .page-content .btn-primary:hover {
                background: linear-gradient(45deg, #224abe, #4e73df) !important;
                transform: translateY(-2px) !important;
                box-shadow: 0 4px 8px rgba(78, 115, 223, 0.4) !important;
                color: white !important;
            }
            
            /* EXACT DESKTOP OUTLINE PRIMARY */
            .student-dashboard .page-content .btn-outline-primary {
                color: #4e73df !important;
                border: 2px solid #4e73df !important;
                background: transparent !important;
                border-radius: 0.5rem !important;
                font-weight: 600 !important;
                padding: 0.5rem 1rem !important;
                display: inline-flex !important;
                align-items: center !important;
                justify-content: center !important;
                transition: all 0.3s !important;
            }
            
            .student-dashboard .page-content .btn-outline-primary:hover {
                background: linear-gradient(45deg, #4e73df, #224abe) !important;
                color: white !important;
            }
            
            /* SUCCESS BUTTON - Desktop Style */
            .student-dashboard .page-content .btn-success {
                background: linear-gradient(45deg, #1cc88a, #13855c) !important;
                border: none !important;
                box-shadow: 0 2px 5px rgba(28, 200, 138, 0.3) !important;
                color: white !important;
            }
            
            .student-dashboard .page-content .btn-success:hover {
                background: linear-gradient(45deg, #13855c, #1cc88a) !important;
                transform: translateY(-2px) !important;
                box-shadow: 0 4px 8px rgba(28, 200, 138, 0.4) !important;
            }
            
            /* WARNING BUTTON - Desktop Style */
            .student-dashboard .page-content .btn-warning {
                background: linear-gradient(45deg, #f6c23e, #dda20a) !important;
                border: none !important;
                box-shadow: 0 2px 5px rgba(246, 194, 62, 0.3) !important;
                color: #333333 !important;
            }
            
            .student-dashboard .page-content .btn-warning:hover {
                background: linear-gradient(45deg, #dda20a, #f6c23e) !important;
                transform: translateY(-2px) !important;
                box-shadow: 0 4px 8px rgba(246, 194, 62, 0.4) !important;
            }
            
            /* DANGER BUTTON - Desktop Style */
            .student-dashboard .page-content .btn-danger {
                background: linear-gradient(45deg, #e74a3b, #be2617) !important;
                border: none !important;
                box-shadow: 0 2px 5px rgba(231, 74, 59, 0.3) !important;
                color: white !important;
            }
            
            .student-dashboard .page-content .btn-danger:hover {
                background: linear-gradient(45deg, #be2617, #e74a3b) !important;
                transform: translateY(-2px) !important;
                box-shadow: 0 4px 8px rgba(231, 74, 59, 0.4) !important;
            }
        }

        /* 🔹 4. HEADER VISIBILITY & OVERLAP FIX */
        @media (max-width: 768px) {
            .student-dashboard .header-fixed {
                position: fixed !important;
                top: 0 !important;
                left: 0 !important;
                right: 0 !important;
                z-index: 1030 !important;
                background: linear-gradient(45deg, #4e73df, #224abe) !important;
                height: 56px !important;
                min-height: 56px !important;
            }
            
            .student-dashboard .header-content {
                height: 56px !important;
                padding-top: 0 !important;
                padding-bottom: 0 !important;
                display: flex !important;
                align-items: center !important;
                justify-content: space-between !important;
            }
            
            .student-dashboard .page-content {
                padding-top: calc(56px + 16px) !important;
            }
            
            .student-dashboard .main-content-container {
                min-height: calc(100vh - 56px) !important;
            }
            
            /* Fix header content alignment */
            .student-dashboard .header-content > div:first-child {
                flex: 1 1 auto;
                min-width: 0;
                display: flex;
                align-items: center;
            }
            
            .student-dashboard .header-content > div:last-child {
                flex-shrink: 0;
                display: flex;
                align-items: center;
            }
        }

        /* 🔹 5. NOTIFICATION BELL FIX - EXACT DESKTOP STYLES */
        @media (max-width: 768px) {
            .student-dashboard .notification-button {
                position: relative !important;
                display: flex !important;
                align-items: center !important;
                justify-content: center !important;
                width: 40px !important;
                height: 40px !important;
                color: white !important;
                padding: 0 !important;
                background: transparent !important;
                border: none !important;
                border-radius: 50% !important;
                transition: all 0.3s !important;
            }
            
            .student-dashboard .notification-button:hover {
                background: rgba(255, 255, 255, 0.15) !important;
                transform: translateY(-1px) !important;
            }
            
            .student-dashboard .notification-button i {
                color: white !important;
                font-size: 1.25rem !important;
            }
            
            .student-dashboard .notification-dot {
                position: absolute !important;
                top: 3px !important;
                right: 3px !important;
                width: 10px !important;
                height: 10px !important;
                background-color: #ef4444 !important;
                border: 2px solid #224abe !important;
                border-radius: 50% !important;
                z-index: 10 !important;
            }
            
            /* NOTIFICATION DROPDOWN - EXACT DESKTOP STYLES */
            .student-dashboard .dropdown-menu {
                position: absolute !important;
                right: 0 !important;
                left: auto !important;
                margin-top: 8px !important;
                background-color: white !important;
                color: #333333 !important;
                border: 1px solid #e5e7eb !important;
                box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06) !important;
                border-radius: 0.75rem !important;
                min-width: 320px !important;
                z-index: 9999 !important;
                opacity: 1 !important;
                visibility: visible !important;
                transform: translateY(0) !important;
                animation: dropdownSlide 0.2s ease !important;
            }
            
            @keyframes dropdownSlide {
                from {
                    opacity: 0;
                    transform: translateY(-10px);
                }
                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }
            
            .student-dashboard .dropdown-menu.show {
                display: block !important;
            }
            
            .student-dashboard .dropdown-menu .dropdown-item {
                padding: 12px 20px !important;
                color: #333333 !important;
                border-bottom: 1px solid #f1f5f9 !important;
                transition: all 0.2s !important;
                display: flex !important;
                align-items: flex-start !important;
            }
            
            .student-dashboard .dropdown-menu .dropdown-item:hover {
                background: #f8fafc !important;
                color: #4e73df !important;
            }
            
            .student-dashboard .dropdown-menu .px-4.py-2 {
                padding: 12px 20px !important;
            }
            
            .student-dashboard .dropdown-menu .px-4.py-2 h3 {
                color: #333333 !important;
                font-weight: 600 !important;
                font-size: 1rem !important;
            }
            
            .student-dashboard .dropdown-menu .flex.items-start {
                display: flex !important;
                align-items: flex-start !important;
            }
            
            .student-dashboard .dropdown-menu .bg-blue-100 {
                background-color: #dbeafe !important;
                padding: 10px !important;
                border-radius: 0.5rem !important;
                margin-right: 12px !important;
            }
            
            .student-dashboard .dropdown-menu .bg-blue-100 i {
                color: #3b82f6 !important;
                font-size: 1rem !important;
            }
            
            .student-dashboard .dropdown-menu .text-sm.font-medium {
                color: #333333 !important;
                font-weight: 500 !important;
                font-size: 0.875rem !important;
                line-height: 1.25rem !important;
            }
            
            .student-dashboard .dropdown-menu .text-xs.text-gray-500 {
                color: #6b7280 !important;
                font-size: 0.75rem !important;
                line-height: 1rem !important;
                margin-top: 2px !important;
            }
            
            .student-dashboard .dropdown-menu .text-center {
                text-align: center !important;
                padding: 12px 20px !important;
            }
            
            .student-dashboard .dropdown-menu .text-blue-600 {
                color: #4e73df !important;
                font-weight: 500 !important;
                font-size: 0.875rem !important;
            }
            
            .student-dashboard .dropdown-menu .text-blue-600:hover {
                color: #224abe !important;
            }
        }

        /* 🔹 6. HERO/WELCOME SECTION BACKGROUND FIX */
        @media (max-width: 768px) {
            .student-dashboard .bg-blue-800,
            .student-dashboard .bg-blue-800 * {
                background: linear-gradient(45deg, #4e73df, #224abe) !important;
                color: white !important;
            }
            
            .student-dashboard .bg-blue-800 .text-white {
                color: white !important;
            }
            
            .student-dashboard .bg-blue-800 .text-blue-200 {
                color: #bfdbfe !important;
            }
            
            .student-dashboard .bg-blue-800 .btn {
                background-color: white !important;
                color: #4e73df !important;
                border: none !important;
                box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1) !important;
            }
            
            .student-dashboard .bg-blue-800 .btn:hover {
                background-color: #f8fafc !important;
                color: #224abe !important;
                transform: translateY(-1px) !important;
                box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1) !important;
            }
        }

        /* 🔹 7. CARD & CONTENT VISIBILITY FIXES */
        @media (max-width: 768px) {
            .student-dashboard .page-content .card,
            .student-dashboard .page-content .bg-white {
                background-color: white !important;
                color: #333333 !important;
                border: 1px solid #e5e7eb !important;
                border-radius: 0.75rem !important;
                box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1) !important;
            }
            
            .student-dashboard .page-content .card *:not(.btn):not(i.fas):not(i.far):not(i.fab),
            .student-dashboard .page-content .bg-white *:not(.btn):not(i.fas):not(i.far):not(i.fab) {
                color: #333333 !important;
            }
            
            .student-dashboard .page-content h1,
            .student-dashboard .page-content h2,
            .student-dashboard .page-content h3,
            .student-dashboard .page-content h4,
            .student-dashboard .page-content h5,
            .student-dashboard .page-content h6 {
                color: #1f2937 !important;
            }
            
            .student-dashboard .page-content p,
            .student-dashboard .page-content span:not(.header-content *):not(.sidebar *),
            .student-dashboard .page-content div:not(.header-content *):not(.sidebar *) {
                color: #4b5563 !important;
            }
        }

        /* 🔹 8. FORM ELEMENTS VISIBILITY */
        @media (max-width: 768px) {
            .student-dashboard .page-content .form-control,
            .student-dashboard .page-content .form-label,
            .student-dashboard .page-content .form-text,
            .student-dashboard .page-content .form-select {
                color: #333333 !important;
            }
            
            .student-dashboard .page-content .form-control::placeholder {
                color: #9ca3af !important;
            }
        }

        /* 🔹 9. TABLE VISIBILITY FIX */
        @media (max-width: 768px) {
            .student-dashboard .page-content table,
            .student-dashboard .page-content th,
            .student-dashboard .page-content td {
                color: #333333 !important;
            }
            
            .student-dashboard .page-content .table-striped tbody tr:nth-of-type(odd) {
                background-color: #f9fafb !important;
            }
        }

        /* 🔹 10. BADGE & LABEL VISIBILITY */
        @media (max-width: 768px) {
            .student-dashboard .page-content .badge {
                color: white !important;
            }
            
            .student-dashboard .page-content .badge.bg-blue-500 {
                background-color: #3b82f6 !important;
            }
            
            .student-dashboard .page-content .badge.bg-green-500 {
                background-color: #10b981 !important;
            }
            
            .student-dashboard .page-content .badge.bg-amber-500 {
                background-color: #f59e0b !important;
            }
            
            .student-dashboard .page-content .badge.bg-red-500 {
                background-color: #ef4444 !important;
            }
            
            .student-dashboard .page-content .badge.bg-indigo-500 {
                background-color: #6366f1 !important;
            }
        }

        /* 🔹 11. SPECIFIC DASHBOARD COMPONENT FIXES */
        @media (max-width: 768px) {
            /* Quick actions */
            .student-dashboard .page-content .bg-blue-50,
            .student-dashboard .page-content .bg-green-50,
            .student-dashboard .page-content .bg-amber-50,
            .student-dashboard .page-content .bg-indigo-50 {
                background-color: #eff6ff !important;
                color: #333333 !important;
            }
            
            .student-dashboard .page-content .bg-blue-50 *,
            .student-dashboard .page-content .bg-green-50 *,
            .student-dashboard .page-content .bg-amber-50 *,
            .student-dashboard .page-content .bg-indigo-50 * {
                color: #333333 !important;
            }
            
            /* Circular items */
            .student-dashboard .page-content .circular-item {
                background-color: white !important;
                color: #333333 !important;
                border: 1px solid #e5e7eb !important;
            }
            
            .student-dashboard .page-content .circular-item.unread {
                background-color: #f0f9ff !important;
                border-left: 4px solid #3b82f6 !important;
            }
            
            .student-dashboard .page-content .circular-item.read {
                background-color: #f8fafc !important;
                opacity: 0.9 !important;
            }
        }

        /* 🔹 12. USER DROPDOWN VISIBILITY FIXES */
        @media (max-width: 768px) {
            .student-dashboard .user-dropdown .dropdown-menu {
                background-color: white !important;
                color: #333333 !important;
                border: 1px solid #e5e7eb !important;
                box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06) !important;
                border-radius: 0.75rem !important;
                min-width: 200px !important;
                padding: 8px 0 !important;
            }
            
            .student-dashboard .user-dropdown .dropdown-item {
                color: #333333 !important;
                padding: 10px 16px !important;
                font-size: 0.875rem !important;
                display: flex !important;
                align-items: center !important;
                transition: all 0.2s !important;
            }
            
            .student-dashboard .user-dropdown .dropdown-item:hover {
                background-color: #f8f9fa !important;
                color: #4e73df !important;
            }
            
            .student-dashboard .user-dropdown .dropdown-item.text-danger {
                color: #ef4444 !important;
            }
            
            .student-dashboard .user-dropdown .dropdown-item.text-danger:hover {
                background-color: #fef2f2 !important;
                color: #dc2626 !important;
            }
        }

        /* 🔹 13. ALERT VISIBILITY FIXES */
        @media (max-width: 768px) {
            .student-dashboard .alert {
                color: #333333 !important;
                border: 1px solid transparent !important;
                border-radius: 0.75rem !important;
                padding: 1rem !important;
                margin-bottom: 1rem !important;
            }
            
            .student-dashboard .alert-success {
                background-color: #d4edda !important;
                border-color: #c3e6cb !important;
                color: #155724 !important;
            }
            
            .student-dashboard .alert-danger {
                background-color: #f8d7da !important;
                border-color: #f5c6cb !important;
                color: #721c24 !important;
            }
            
            .student-dashboard .alert-info {
                background-color: #d1ecf1 !important;
                border-color: #bee5eb !important;
                color: #0c5460 !important;
            }
            
            .student-dashboard .alert-warning {
                background-color: #fff3cd !important;
                border-color: #ffeeba !important;
                color: #856404 !important;
            }
        }

        /* 🔹 14. PROGRESS BARS & STATS VISIBILITY */
        @media (max-width: 768px) {
            .student-dashboard .page-content .progress {
                background-color: #e5e7eb !important;
                height: 0.75rem !important;
                border-radius: 0.375rem !important;
                overflow: hidden !important;
            }
            
            .student-dashboard .page-content .progress-bar {
                background-color: #4e73df !important;
                color: white !important;
                border-radius: 0.375rem !important;
            }
            
            .student-dashboard .page-content .bg-blue-100 {
                background-color: #dbeafe !important;
                color: #1e40af !important;
            }
            
            .student-dashboard .page-content .bg-green-100 {
                background-color: #d1fae5 !important;
                color: #065f46 !important;
            }
            
            .student-dashboard .page-content .bg-amber-100 {
                background-color: #fef3c7 !important;
                color: #92400e !important;
            }
            
            .student-dashboard .page-content .bg-red-100 {
                background-color: #fee2e2 !important;
                color: #991b1b !important;
            }
        }

        /* 🔹 MOBILE UI POLISH ENHANCEMENTS (NEW ADDITION) */
        @media (max-width: 768px) {
            /* CARD POLISH - Restore desktop-like shine */
            .student-dashboard .page-content .bg-white,
            .student-dashboard .page-content .card,
            .student-dashboard .page-content .circular-item {
                background-color: white !important;
                color: #333333 !important;
                border: 1px solid #e5e7eb !important;
                border-radius: 0.75rem !important;
                box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 
                            0 2px 4px -1px rgba(0, 0, 0, 0.06) !important;
                transition: all 0.3s ease !important;
            }
            
            .student-dashboard .page-content .bg-white:hover,
            .student-dashboard .page-content .card:hover {
                box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 
                            0 4px 6px -2px rgba(0, 0, 0, 0.05) !important;
                transform: translateY(-2px) !important;
            }
            
            /* ICON POLISH */
            .student-dashboard .page-content i:not(.header-content i) {
                text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1) !important;
            }
            
            .student-dashboard .page-content .fa-utensils,
            .student-dashboard .page-content .fa-money-bill-wave,
            .student-dashboard .page-content .fa-bullhorn,
            .student-dashboard .page-content .fa-door-open,
            .student-dashboard .page-content .fa-star {
                color: #4e73df !important;
                filter: drop-shadow(0 2px 2px rgba(78, 115, 223, 0.3)) !important;
            }
            
            /* HERO/WELCOME SECTION POLISH */
            .student-dashboard .bg-blue-800 {
                background: linear-gradient(45deg, #4e73df, #224abe) !important;
                color: white !important;
                border-radius: 1rem !important;
                box-shadow: 0 10px 25px -5px rgba(78, 115, 223, 0.3) !important;
                padding: 1.5rem !important;
            }
            
            /* STATS CARD POLISH */
            .student-dashboard .page-content .bg-blue-100,
            .student-dashboard .page-content .bg-green-100,
            .student-dashboard .page-content .bg-amber-100,
            .student-dashboard .page-content .bg-indigo-100 {
                border-radius: 0.75rem !important;
                box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05) !important;
                padding: 1rem !important;
                transition: all 0.3s ease !important;
            }
            
            .student-dashboard .page-content .bg-blue-100:hover,
            .student-dashboard .page-content .bg-green-100:hover,
            .student-dashboard .page-content .bg-amber-100:hover,
            .student-dashboard .page-content .bg-indigo-100:hover {
                box-shadow: 0 4px 6px rgba(0, 0, 0, 0.07) !important;
                transform: translateY(-2px) !important;
            }
        }

        /* 🔹 DROPDOWN FIX - HIDE BY DEFAULT, SHOW WITH JS */
        .student-dashboard .dropdown-menu {
            display: none !important;
        }
        
        .student-dashboard .dropdown-menu.show {
            display: block !important;
        }

        /* =========================================== */
        /* 🎯 FINAL MOBILE POLISH - STUDENT DASHBOARD */
        /* =========================================== */

        /* 🔹 1. DATE CARD POLISH */
        @media (max-width: 768px) {
            .student-dashboard .page-content .bg-blue-50.text-blue-800,
            .student-dashboard .page-content .bg-blue-50 {
                background-color: white !important;
                color: #1f2937 !important;
                border-radius: 0.75rem !important;
                box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 
                            0 2px 4px -1px rgba(0, 0, 0, 0.06) !important;
                padding: 1rem !important;
                border: 1px solid #e5e7eb !important;
            }
            
            .student-dashboard .page-content .bg-blue-50 i {
                color: #3b82f6 !important;
                filter: drop-shadow(0 2px 2px rgba(59, 130, 246, 0.3)) !important;
            }
            
            .student-dashboard .page-content .bg-blue-50 .text-blue-800 {
                color: #1f2937 !important;
                font-weight: 600 !important;
            }
        }

        /* 🔹 2. "मुख्य पृष्ठ" BUTTON POLISH */
        @media (max-width: 768px) {
            .student-dashboard .page-content .btn-success {
                background: linear-gradient(45deg, #1cc88a, #13855c) !important;
                border: none !important;
                box-shadow: 0 4px 6px rgba(28, 200, 138, 0.25) !important;
                color: white !important;
                border-radius: 0.75rem !important;
                font-weight: 600 !important;
                padding: 0.75rem 1.5rem !important;
                transition: all 0.3s ease !important;
                text-shadow: 0 1px 2px rgba(0, 0, 0, 0.1) !important;
            }
            
            .student-dashboard .page-content .btn-success:hover {
                background: linear-gradient(45deg, #13855c, #1cc88a) !important;
                transform: translateY(-3px) !important;
                box-shadow: 0 6px 12px rgba(28, 200, 138, 0.3) !important;
            }
            
            .student-dashboard .page-content .btn-success:active {
                transform: translateY(-1px) !important;
                box-shadow: 0 3px 6px rgba(28, 200, 138, 0.2) !important;
            }
        }

        /* 🔹 3. NOTIFICATION BELL ICON COLOR */
        @media (max-width: 768px) {
            .student-dashboard .page-content .fa-bell {
                color: #3b82f6 !important;
                filter: drop-shadow(0 2px 3px rgba(59, 130, 246, 0.3)) !important;
            }
            
            .student-dashboard .page-content .bg-blue-100 .fa-bell {
                background: linear-gradient(45deg, #3b82f6, #1d4ed8) !important;
                -webkit-background-clip: text !important;
                background-clip: text !important;
                color: transparent !important;
            }
        }

        /* 🔹 4. STAR RATING COLORS */
        @media (max-width: 768px) {
            .student-dashboard .page-content .star-rating i.fa-star {
                color: #eab308 !important;
                text-shadow: 0 2px 4px rgba(234, 179, 8, 0.3) !important;
            }
            
            .student-dashboard .page-content .star-rating i.fa-star:first-child {
                color: #ea580c !important;
                filter: drop-shadow(0 2px 3px rgba(234, 88, 12, 0.4)) !important;
            }
            
            .student-dashboard .page-content .star-rating i.fa-star.filled {
                color: #eab308 !important;
                filter: drop-shadow(0 2px 3px rgba(234, 179, 8, 0.4)) !important;
            }
        }

        /* 🔹 5. CTA BUTTONS UNIFIED STYLE */
        @media (max-width: 768px) {
            .student-dashboard .page-content .btn-primary,
            .student-dashboard .page-content .btn-info,
            .student-dashboard .page-content .btn-purple {
                background: linear-gradient(45deg, #4e73df, #224abe) !important;
                border: none !important;
                box-shadow: 0 4px 6px rgba(78, 115, 223, 0.25) !important;
                color: white !important;
                border-radius: 0.75rem !important;
                font-weight: 600 !important;
                padding: 0.75rem 1.5rem !important;
                transition: all 0.3s ease !important;
                text-shadow: 0 1px 2px rgba(0, 0, 0, 0.1) !important;
            }
            
            .student-dashboard .page-content .btn-primary:hover,
            .student-dashboard .page-content .btn-info:hover,
            .student-dashboard .page-content .btn-purple:hover {
                background: linear-gradient(45deg, #224abe, #4e73df) !important;
                transform: translateY(-3px) !important;
                box-shadow: 0 6px 12px rgba(78, 115, 223, 0.3) !important;
            }
            
            .student-dashboard .page-content .btn-primary:active,
            .student-dashboard .page-content .btn-info:active,
            .student-dashboard .page-content .btn-purple:active {
                transform: translateY(-1px) !important;
                box-shadow: 0 3px 6px rgba(78, 115, 223, 0.2) !important;
            }
            
            /* Specific button colors */
            .student-dashboard .page-content .btn-info {
                background: linear-gradient(45deg, #36b9cc, #258391) !important;
            }
            
            .student-dashboard .page-content .btn-info:hover {
                background: linear-gradient(45deg, #258391, #36b9cc) !important;
                box-shadow: 0 6px 12px rgba(54, 185, 204, 0.3) !important;
            }
        }

        /* 🔹 6. QUICK ACTIONS SECTION - CARDS */
        @media (max-width: 768px) {
            .student-dashboard .page-content .quick-action-card {
                background: linear-gradient(135deg, #ffffff, #f8fafc) !important;
                border: 1px solid #e5e7eb !important;
                border-radius: 1rem !important;
                box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 
                            0 2px 4px -1px rgba(0, 0, 0, 0.06) !important;
                padding: 1.25rem !important;
                transition: all 0.3s ease !important;
                display: flex !important;
                flex-direction: column !important;
                align-items: center !important;
                justify-content: center !important;
                text-align: center !important;
                color: #333333 !important;
                min-height: 120px !important;
            }
            
            .student-dashboard .page-content .quick-action-card:hover {
                transform: translateY(-5px) !important;
                box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 
                            0 4px 6px -2px rgba(0, 0, 0, 0.05) !important;
                border-color: #4e73df !important;
            }
            
            .student-dashboard .page-content .quick-action-card i {
                font-size: 1.75rem !important;
                margin-bottom: 0.75rem !important;
                color: #4e73df !important;
                filter: drop-shadow(0 2px 3px rgba(78, 115, 223, 0.3)) !important;
            }
            
            .student-dashboard .page-content .quick-action-card span {
                font-weight: 600 !important;
                color: #1f2937 !important;
                font-size: 0.9rem !important;
                line-height: 1.3 !important;
            }
            
            /* Specific quick action colors */
            .student-dashboard .page-content .quick-action-card.bg-blue-50 i {
                color: #3b82f6 !important;
            }
            
            .student-dashboard .page-content .quick-action-card.bg-green-50 i {
                color: #10b981 !important;
            }
            
            .student-dashboard .page-content .quick-action-card.bg-amber-50 i {
                color: #f59e0b !important;
            }
            
            .student-dashboard .page-content .quick-action-card.bg-indigo-50 i {
                color: #6366f1 !important;
            }
            
            .student-dashboard .page-content .quick-action-card.bg-purple-50 i {
                color: #8b5cf6 !important;
            }
            
            .student-dashboard .page-content .quick-action-card.bg-pink-50 i {
                color: #ec4899 !important;
            }
            
            .student-dashboard .page-content .quick-action-card.bg-teal-50 i {
                color: #14b8a6 !important;
            }
        }

        /* 🔹 7. "आगामी घटनाहरू" ICON COLOR */
        @media (max-width: 768px) {
            .student-dashboard .page-content .fa-calendar-alt,
            .student-dashboard .page-content .fa-calendar-day,
            .student-dashboard .page-content .fa-calendar-check {
                color: #ea580c !important;
                filter: drop-shadow(0 2px 3px rgba(234, 88, 12, 0.3)) !important;
            }
            
            .student-dashboard .page-content .bg-orange-100 i.fa-calendar-alt {
                background: linear-gradient(45deg, #ea580c, #c2410c) !important;
                -webkit-background-clip: text !important;
                background-clip: text !important;
                color: transparent !important;
            }
        }

        /* 🔹 8. ENHANCED CARD VISUAL DEPTH */
        @media (max-width: 768px) {
            .student-dashboard .page-content .card,
            .student-dashboard .page-content .bg-white.rounded-2xl,
            .student-dashboard .page-content .bg-white.rounded-xl {
                background: linear-gradient(135deg, #ffffff, #fafafa) !important;
                border: 1px solid #e5e7eb !important;
                border-radius: 1rem !important;
                box-shadow: 0 6px 12px -2px rgba(0, 0, 0, 0.1), 
                            0 4px 8px -2px rgba(0, 0, 0, 0.05) !important;
                transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1) !important;
                position: relative !important;
                overflow: hidden !important;
            }
            
            .student-dashboard .page-content .card:hover,
            .student-dashboard .page-content .bg-white.rounded-2xl:hover,
            .student-dashboard .page-content .bg-white.rounded-xl:hover {
                transform: translateY(-4px) !important;
                box-shadow: 0 15px 25px -5px rgba(0, 0, 0, 0.15), 
                            0 10px 15px -5px rgba(0, 0, 0, 0.08) !important;
                border-color: #d1d5db !important;
            }
            
            /* Card subtle shine effect */
            .student-dashboard .page-content .card::before,
            .student-dashboard .page-content .bg-white.rounded-2xl::before {
                content: '' !important;
                position: absolute !important;
                top: 0 !important;
                left: -100% !important;
                width: 50% !important;
                height: 100% !important;
                background: linear-gradient(
                    90deg,
                    transparent,
                    rgba(255, 255, 255, 0.4),
                    transparent
                ) !important;
                transition: left 0.7s ease !important;
            }
            
            .student-dashboard .page-content .card:hover::before,
            .student-dashboard .page-content .bg-white.rounded-2xl:hover::before {
                left: 150% !important;
            }
        }

        /* 🔹 9. ENHANCED ICON VISUALS */
        @media (max-width: 768px) {
            .student-dashboard .page-content i.fas,
            .student-dashboard .page-content i.far,
            .student-dashboard .page-content i.fab {
                filter: drop-shadow(0 2px 3px rgba(0, 0, 0, 0.1)) !important;
                transition: all 0.3s ease !important;
            }
            
            /* Colored icons with gradient */
            .student-dashboard .page-content .fa-utensils {
                background: linear-gradient(45deg, #3b82f6, #1d4ed8) !important;
                -webkit-background-clip: text !important;
                background-clip: text !important;
                color: transparent !important;
            }
            
            .student-dashboard .page-content .fa-money-bill-wave {
                background: linear-gradient(45deg, #10b981, #047857) !important;
                -webkit-background-clip: text !important;
                background-clip: text !important;
                color: transparent !important;
            }
            
            .student-dashboard .page-content .fa-bullhorn {
                background: linear-gradient(45deg, #8b5cf6, #7c3aed) !important;
                -webkit-background-clip: text !important;
                background-clip: text !important;
                color: transparent !important;
            }
            
            .student-dashboard .page-content .fa-door-open {
                background: linear-gradient(45deg, #f59e0b, #d97706) !important;
                -webkit-background-clip: text !important;
                background-clip: text !important;
                color: transparent !important;
            }
            
            .student-dashboard .page-content .fa-star {
                background: linear-gradient(45deg, #eab308, #ca8a04) !important;
                -webkit-background-clip: text !important;
                background-clip: text !important;
                color: transparent !important;
            }
        }

        /* 🔹 10. STATS CARD COLOR ENHANCEMENT */
        @media (max-width: 768px) {
            .student-dashboard .page-content .bg-blue-100,
            .student-dashboard .page-content .bg-green-100,
            .student-dashboard .page-content .bg-amber-100,
            .student-dashboard .page-content .bg-indigo-100,
            .student-dashboard .page-content .bg-purple-100,
            .student-dashboard .page-content .bg-pink-100,
            .student-dashboard .page-content .bg-teal-100 {
                border-radius: 1rem !important;
                box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1) !important;
                padding: 1.25rem !important;
                transition: all 0.3s ease !important;
                border: 1px solid transparent !important;
            }
            
            .student-dashboard .page-content .bg-blue-100 {
                background: linear-gradient(135deg, #dbeafe, #bfdbfe) !important;
                border-color: #93c5fd !important;
            }
            
            .student-dashboard .page-content .bg-green-100 {
                background: linear-gradient(135deg, #d1fae5, #a7f3d0) !important;
                border-color: #6ee7b7 !important;
            }
            
            .student-dashboard .page-content .bg-amber-100 {
                background: linear-gradient(135deg, #fef3c7, #fde68a) !important;
                border-color: #fcd34d !important;
            }
            
            .student-dashboard .page-content .bg-indigo-100 {
                background: linear-gradient(135deg, #e0e7ff, #c7d2fe) !important;
                border-color: #a5b4fc !important;
            }
            
            .student-dashboard .page-content .bg-purple-100 {
                background: linear-gradient(135deg, #f3e8ff, #e9d5ff) !important;
                border-color: #d8b4fe !important;
            }
            
            .student-dashboard .page-content .bg-pink-100 {
                background: linear-gradient(135deg, #fce7f3, #fbcfe8) !important;
                border-color: #f9a8d4 !important;
            }
            
            .student-dashboard .page-content .bg-teal-100 {
                background: linear-gradient(135deg, #ccfbf1, #99f6e4) !important;
                border-color: #5eead4 !important;
            }
            
            /* Hover effects */
            .student-dashboard .page-content .bg-blue-100:hover {
                transform: translateY(-3px) !important;
                box-shadow: 0 8px 12px -2px rgba(59, 130, 246, 0.2) !important;
            }
            
            .student-dashboard .page-content .bg-green-100:hover {
                transform: translateY(-3px) !important;
                box-shadow: 0 8px 12px -2px rgba(16, 185, 129, 0.2) !important;
            }
            
            .student-dashboard .page-content .bg-amber-100:hover {
                transform: translateY(-3px) !important;
                box-shadow: 0 8px 12px -2px rgba(245, 158, 11, 0.2) !important;
            }
            
            .student-dashboard .page-content .bg-indigo-100:hover {
                transform: translateY(-3px) !important;
                box-shadow: 0 8px 12px -2px rgba(99, 102, 241, 0.2) !important;
            }
        }

        /* 🔹 11. BADGE ENHANCEMENT */
        @media (max-width: 768px) {
            .student-dashboard .page-content .badge {
                font-weight: 600 !important;
                padding: 0.35rem 0.75rem !important;
                border-radius: 9999px !important;
                box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1) !important;
                text-shadow: 0 1px 1px rgba(0, 0, 0, 0.1) !important;
            }
            
            .student-dashboard .page-content .badge.bg-blue-500 {
                background: linear-gradient(45deg, #3b82f6, #1d4ed8) !important;
                color: white !important;
            }
            
            .student-dashboard .page-content .badge.bg-green-500 {
                background: linear-gradient(45deg, #10b981, #047857) !important;
                color: white !important;
            }
            
            .student-dashboard .page-content .badge.bg-amber-500 {
                background: linear-gradient(45deg, #f59e0b, #d97706) !important;
                color: white !important;
            }
            
            .student-dashboard .page-content .badge.bg-red-500 {
                background: linear-gradient(45deg, #ef4444, #dc2626) !important;
                color: white !important;
            }
            
            .student-dashboard .page-content .badge.bg-purple-500 {
                background: linear-gradient(45deg, #8b5cf6, #7c3aed) !important;
                color: white !important;
            }
        }

        /* 🔹 12. WELCOME SECTION PREMIUM POLISH */
        @media (max-width: 768px) {
            .student-dashboard .bg-blue-800 {
                background: linear-gradient(135deg, #4e73df, #224abe) !important;
                color: white !important;
                border-radius: 1.5rem !important;
                box-shadow: 0 15px 30px -10px rgba(78, 115, 223, 0.4) !important;
                padding: 1.5rem !important;
                position: relative !important;
                overflow: hidden !important;
            }
            
            .student-dashboard .bg-blue-800::before {
                content: '' !important;
                position: absolute !important;
                top: -50% !important;
                right: -50% !important;
                width: 200% !important;
                height: 200% !important;
                background: radial-gradient(
                    circle at 30% 30%,
                    rgba(255, 255, 255, 0.1) 0%,
                    transparent 50%
                ) !important;
            }
            
            .student-dashboard .bg-blue-800 h2 {
                color: white !important;
                text-shadow: 0 2px 4px rgba(0, 0, 0, 0.2) !important;
                position: relative !important;
                z-index: 1 !important;
            }
            
            .student-dashboard .bg-blue-800 .text-blue-200 {
                color: rgba(255, 255, 255, 0.9) !important;
                text-shadow: 0 1px 2px rgba(0, 0, 0, 0.1) !important;
                position: relative !important;
                z-index: 1 !important;
            }
            
            .student-dashboard .bg-blue-800 .btn {
                background: linear-gradient(45deg, #ffffff, #f0f9ff) !important;
                color: #4e73df !important;
                border: none !important;
                box-shadow: 0 4px 6px rgba(255, 255, 255, 0.2) !important;
                font-weight: 600 !important;
                border-radius: 0.75rem !important;
                padding: 0.75rem 1.5rem !important;
                transition: all 0.3s ease !important;
                position: relative !important;
                z-index: 1 !important;
            }
            
            .student-dashboard .bg-blue-800 .btn:hover {
                transform: translateY(-3px) !important;
                box-shadow: 0 8px 12px rgba(255, 255, 255, 0.25) !important;
                color: #224abe !important;
            }
        }

        /* 🔹 13. TABLE ENHANCEMENT */
        @media (max-width: 768px) {
            .student-dashboard .page-content table {
                border-collapse: separate !important;
                border-spacing: 0 !important;
                border-radius: 0.75rem !important;
                overflow: hidden !important;
                box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1) !important;
            }
            
            .student-dashboard .page-content thead {
                background: linear-gradient(45deg, #4e73df, #224abe) !important;
                color: white !important;
            }
            
            .student-dashboard .page-content thead th {
                color: white !important;
                font-weight: 600 !important;
                border: none !important;
                padding: 0.75rem 1rem !important;
            }
            
            .student-dashboard .page-content tbody tr {
                transition: all 0.2s ease !important;
            }
            
            .student-dashboard .page-content tbody tr:hover {
                background-color: #f8fafc !important;
                transform: translateX(2px) !important;
            }
            
            .student-dashboard .page-content tbody td {
                padding: 0.75rem 1rem !important;
                border-bottom: 1px solid #f1f5f9 !important;
                color: #4b5563 !important;
            }
        }

        /* 🔹 14. PROGRESS BAR ENHANCEMENT */
        @media (max-width: 768px) {
            .student-dashboard .page-content .progress {
                height: 0.75rem !important;
                border-radius: 9999px !important;
                background-color: #e5e7eb !important;
                overflow: hidden !important;
                box-shadow: inset 0 2px 4px rgba(0, 0, 0, 0.1) !important;
            }
            
            .student-dashboard .page-content .progress-bar {
                border-radius: 9999px !important;
                background: linear-gradient(90deg, #4e73df, #224abe) !important;
                box-shadow: 0 2px 4px rgba(78, 115, 223, 0.3) !important;
                position: relative !important;
                overflow: hidden !important;
            }
            
            .student-dashboard .page-content .progress-bar::after {
                content: '' !important;
                position: absolute !important;
                top: 0 !important;
                left: 0 !important;
                right: 0 !important;
                bottom: 0 !important;
                background: linear-gradient(
                    90deg,
                    transparent,
                    rgba(255, 255, 255, 0.3),
                    transparent
                ) !important;
                animation: shimmer 2s infinite !important;
            }
            
            @keyframes shimmer {
                0% {
                    transform: translateX(-100%) !important;
                }
                100% {
                    transform: translateX(100%) !important;
                }
            }
        }

        /* 🔹 15. FINAL TOUCH: GLOBAL TEXT LEGIBILITY */
        @media (max-width: 768px) {
            .student-dashboard .page-content h1,
            .student-dashboard .page-content h2,
            .student-dashboard .page-content h3,
            .student-dashboard .page-content h4,
            .student-dashboard .page-content h5,
            .student-dashboard .page-content h6 {
                font-weight: 600 !important;
                line-height: 1.3 !important;
                text-shadow: 0 1px 2px rgba(0, 0, 0, 0.05) !important;
            }
            
            .student-dashboard .page-content h1 {
                color: #1f2937 !important;
                font-size: 1.75rem !important;
            }
            
            .student-dashboard .page-content h2 {
                color: #1f2937 !important;
                font-size: 1.5rem !important;
            }
            
            .student-dashboard .page-content h3 {
                color: #1f2937 !important;
                font-size: 1.25rem !important;
            }
            
            .student-dashboard .page-content p,
            .student-dashboard .page-content span {
                line-height: 1.6 !important;
                color: #4b5563 !important;
            }
            
            .student-dashboard .page-content .text-muted {
                color: #6b7280 !important;
                opacity: 0.9 !important;
            }
        }
    </style>
    
    <!-- Page-specific CSS -->
    @stack('styles')
</head>
<body class="student-dashboard">
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
                
                <!-- My Room -->
<a href="{{ route('student.my-room') }}"
   class="sidebar-link {{ request()->routeIs('student.my-room') ? 'active' : '' }}"
   aria-current="{{ request()->routeIs('student.my-room') ? 'page' : 'false' }}">
    <i class="fas fa-door-open sidebar-icon"></i>
    <span class="sidebar-text">मेरो कोठा</span>
</a>
                
                <!-- Meal Menu -->
                <a href="{{ route('student.meal-menus') }}"
                   class="sidebar-link {{ request()->routeIs('student.meal-menus') ? 'active' : '' }}"
                   aria-current="{{ request()->routeIs('student.meal-menus') ? 'page' : 'false' }}">
                    <i class="fas fa-utensils sidebar-icon"></i>
                    <span class="sidebar-text">खानाको मेनु</span>
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
                
                <!-- My Booking -->
                <a href="{{ route('student.bookings.index') }}"
                   class="sidebar-link {{ request()->routeIs('student.bookings.*') ? 'active' : '' }}"
                   aria-current="{{ request()->routeIs('student.bookings.*') ? 'page' : 'false' }}">
                    <i class="fas fa-calendar-check sidebar-icon"></i>
                    <span class="sidebar-text">मेरो बुकिङ</span>
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
                        <div class="relative">
                            <button class="notification-button text-white hover:text-gray-200 p-2 rounded-full hover:bg-blue-700" 
                                    type="button" 
                                    id="notificationsDropdown"
                                    aria-label="सूचनाहरू हेर्नुहोस्">
                                <i class="fas fa-bell text-lg text-white"></i>
                                <span class="notification-dot" aria-hidden="true"></span>
                            </button>
                            <div class="dropdown-menu dropdown-menu-end w-80 bg-white rounded-xl shadow-lg py-1 z-20 max-h-96 overflow-y-auto border border-gray-200 absolute right-0 mt-2" 
                                 id="notificationsMenu">
                                <div class="px-4 py-2 border-b border-gray-200">
                                    <h3 class="font-semibold text-gray-800">सूचनाहरू</h3>
                                </div>
                                <a href="#" class="flex items-start px-4 py-3 hover:bg-gray-50 border-b border-gray-100 text-gray-800">
                                    <div class="bg-blue-100 p-2 rounded-lg mr-3">
                                        <i class="fas fa-utensils text-blue-600"></i>
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium">नयाँ खानाको मेनु सिर्जना गरियो</p>
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
                        <div class="relative">
                            <button class="btn btn-outline-light dropdown-toggle d-flex align-items-center" 
                                    type="button" 
                                    id="userDropdown"
                                    aria-label="प्रयोगकर्ता मेनु">
                                <i class="fas fa-user-circle me-2 text-white"></i>
                                <span class="d-none d-md-inline text-white">{{ Auth::user()->name ?? 'विद्यार्थी' }}</span>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end shadow-lg rounded-xl border-0 py-2 absolute right-0 mt-2" 
                                id="userMenu">
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
            console.log('DOM loaded - initializing dropdowns');
            
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
                    document.querySelectorAll('.dropdown-menu').forEach(menu => {
                        menu.classList.remove('show');
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

            // ===========================================
            // SIMPLE DROPDOWN TOGGLE - NO BOOTSTRAP INTERFERENCE
            // ===========================================
            
            // Get dropdown elements
            const notificationsBtn = document.getElementById('notificationsDropdown');
            const notificationsMenu = document.getElementById('notificationsMenu');
            const userBtn = document.getElementById('userDropdown');
            const userMenu = document.getElementById('userMenu');
            
            console.log('Dropdown elements found:', {
                notificationsBtn: !!notificationsBtn,
                notificationsMenu: !!notificationsMenu,
                userBtn: !!userBtn,
                userMenu: !!userMenu
            });
            
            // Ensure dropdowns are hidden on page load
            if (notificationsMenu) notificationsMenu.classList.remove('show');
            if (userMenu) userMenu.classList.remove('show');
            
            // Function to toggle dropdown
            function toggleDropdown(button, menu) {
                if (!button || !menu) return;
                
                button.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    
                    console.log('Button clicked:', button.id);
                    
                    // Close all other dropdowns
                    document.querySelectorAll('.dropdown-menu').forEach(m => {
                        if (m !== menu && m.classList.contains('show')) {
                            m.classList.remove('show');
                        }
                    });
                    
                    // Toggle current dropdown
                    menu.classList.toggle('show');
                    
                    console.log('Menu state:', menu.classList.contains('show') ? 'open' : 'closed');
                });
            }
            
            // Initialize dropdowns
            toggleDropdown(notificationsBtn, notificationsMenu);
            toggleDropdown(userBtn, userMenu);
            
            // Close dropdowns when clicking outside
            document.addEventListener('click', function(e) {
                // Check if click is inside a dropdown button or menu
                const isDropdownButton = e.target.closest('#notificationsDropdown') || 
                                       e.target.closest('#userDropdown');
                const isDropdownMenu = e.target.closest('.dropdown-menu');
                
                if (!isDropdownButton && !isDropdownMenu) {
                    // Clicked outside, close all dropdowns
                    document.querySelectorAll('.dropdown-menu').forEach(menu => {
                        menu.classList.remove('show');
                    });
                }
            });
            
            // Close dropdowns when clicking on dropdown items (links)
            document.querySelectorAll('.dropdown-menu a').forEach(link => {
                link.addEventListener('click', function() {
                    this.closest('.dropdown-menu')?.classList.remove('show');
                });
            });

            // Real-time notification updates
            function checkNewNotifications() {
                fetch('/student/notifications/unread-count')
                    .then(response => response.json())
                    .then(data => {
                        if (data.count > 0) {
                            // Update notification dot
                            const dot = document.querySelector('.notification-dot');
                            if (dot) {
                                dot.style.display = 'block';
                                dot.textContent = data.count > 9 ? '9+' : data.count;
                            }
                        }
                    })
                    .catch(error => console.error('Error fetching notifications:', error));
            }

            // Check for new notifications every 30 seconds
            setInterval(checkNewNotifications, 30000);
            
            // Initial check
            checkNewNotifications();
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