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
    
    <!-- Tailwind CSS with Vite -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.tailwindcss.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.tailwindcss.min.css">
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
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
            display: none;
        }
        
        .sidebar.collapsed .sidebar-icon {
            margin: 0 auto;
        }
        
        .dark-mode {
            background-color: #1e293b;
            color: #f1f5f9;
        }
        
        .dark-mode .main-content {
            background-color: #1e293b;
        }
        
        .dark-mode .sidebar {
            background-color: #1e293b;
        }
        
        .dark-mode .dropdown-menu {
            background-color: #334155;
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
            display: flex;
            align-items: center;
            padding: 0.8rem 1rem;
            border-radius: 0.375rem;
            color: #ffffff;
            transition: all 0.3s;
            margin-bottom: 0.25rem;
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
        
        /* साइडबार स्क्रोलबार स्टाइल */
        .sidebar::-webkit-scrollbar {
            width: 6px;
        }
        
        .sidebar::-webkit-scrollbar-track {
            background: rgba(0, 0, 0, 0.1);
        }
        
        .sidebar::-webkit-scrollbar-thumb {
            background: rgba(0, 0, 0, 0.3);
            border-radius: 3px;
        }
        
        .bg-gradient-primary {
            background: linear-gradient(45deg, var(--primary-color), var(--primary-dark)) !important;
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
            background: linear-gradient(45deg, var(--primary-color), var(--primary-dark));
            border: none;
            box-shadow: 0 2px 5px rgba(78, 115, 223, 0.3);
        }
        
        .btn-primary:hover {
            background: linear-gradient(45deg, var(--primary-dark), var(--primary-color));
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(78, 115, 223, 0.4);
        }
        
        .btn-success {
            background: linear-gradient(45deg, var(--accent-color), var(--accent-dark));
            border: none;
            box-shadow: 0 2px 5px rgba(28, 200, 138, 0.3);
        }
        
        .btn-success:hover {
            background: linear-gradient(45deg, var(--accent-dark), var(--accent-color));
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(28, 200, 138, 0.4);
        }
        
        .dropdown-item {
            padding: 0.5rem 1rem;
            border-radius: 0.35rem;
            margin: 0.1rem 0.25rem;
            width: auto;
            display: flex;
            align-items: center;
        }
        
        .dropdown-item:hover {
            background-color: #f8f9fc;
        }
        
        .dropdown-menu {
            border: none;
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
            border-radius: 0.5rem;
        }
        
        .nepali {
            font-family: 'Noto Sans Devanagari', sans-serif;
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
        }
        
        /* Component-specific styles */
        .alert-dismissible {
            transition: opacity 0.5s;
        }
    </style>
    
    <!-- Page-specific CSS -->
    @stack('styles')
</head>

<body class="bg-gray-50 font-sans" x-data="{ darkMode: localStorage.getItem('darkMode') === 'true', sidebarCollapsed: localStorage.getItem('sidebarCollapsed') === 'true' }" :class="{ 'dark-mode': darkMode }">
    <a href="#main-content" class="skip-link nepali">मुख्य सामग्रीमा जानुहोस्</a>
    
    <div class="flex h-screen overflow-hidden">
        <!-- Sidebar Component -->
        <aside id="sidebar" 
               class="sidebar text-white z-20 flex-shrink-0 transition-all duration-300 ease-in-out flex flex-col h-full"
               :class="{ 'collapsed': sidebarCollapsed }">
            <div class="p-4 border-b border-blue-700 flex items-center justify-between">
                <a href="{{ url('/admin/dashboard') }}" class="logo-container">
                    <img src="{{ asset('storage/images/logo.png') }}" alt="HostelHub Logo" class="logo-img">
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
                <!-- 🔥 CRITICAL FIX: Changed route from admin.settings to admin.settings.index -->
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
        <div class="flex-1 flex flex-col overflow-hidden" :class="{ 'ml-16': sidebarCollapsed, 'ml-64': !sidebarCollapsed }">
            <!-- Top Navigation -->
            <header class="bg-gradient-primary shadow-sm z-10">
                <div class="flex items-center justify-between px-6 header-content">
                    <div class="flex items-center">
                        <button id="mobile-sidebar-toggle" class="lg:hidden text-white hover:text-gray-200 mr-4" aria-label="मोबाइल साइडबार खोल्नुहोस्">
                            <i class="fas fa-bars text-xl"></i>
                        </button>
                        <!-- Brand with Logo -->
                        <a href="{{ url('/admin/dashboard') }}" class="navbar-brand text-white flex items-center">
                            <img src="{{ asset('storage/images/logo.png') }}" alt="HostelHub Logo" class="mobile-logo mr-2">
                            <span class="hidden md:inline">होस्टलहब - प्रशासक प्यानल</span>
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
                        <div class="d-flex align-items-center user-dropdown">
                            <span class="text-white me-3 nepali" x-show="!sidebarCollapsed || window.innerWidth >= 1024">पराशर रेग्मी</span>
                            <div class="dropdown">
                                <button class="btn btn-outline-light dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="fas fa-user-circle me-1"></i>
                                    <span class="nepali">प्रशासक</span>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end shadow dark:bg-gray-700">
                                    <li><a class="dropdown-item nepali dark:text-white dark:hover:bg-gray-600" href="#"><i class="fas fa-user me-2"></i>मेरो प्रोफाइल</a></li>
                                    <li><a class="dropdown-item nepali dark:text-white dark:hover:bg-gray-600" href="{{ route('admin.settings.index') }}"><i class="fas fa-cog me-2"></i>सेटिङ्हरू</a></li>
                                    <li><hr class="dropdown-divider dark:border-gray-600"></li>
                                    <li>
                                        <form method="POST" action="{{ route('logout') }}" id="logout-form-top">
                                            @csrf
                                            <button type="submit" class="dropdown-item nepali dark:text-white dark:hover:bg-gray-600" style="border: none; background: none; width: 100%; text-align: left;">
                                                <i class="fas fa-sign-out-alt me-2"></i>लगआउट
                                            </button>
                                        </form>
                                    </li>
                                </ul>
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
    <div id="sidebar-overlay" class="fixed inset-0 bg-black bg-opacity-50 z-10 hidden lg:hidden" aria-hidden="true"></div>

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
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Mobile sidebar functionality
            const sidebar = document.getElementById('sidebar');
            const mobileSidebarToggle = document.getElementById('mobile-sidebar-toggle');
            const sidebarOverlay = document.getElementById('sidebar-overlay');
            
            // Mobile sidebar toggle
            if (mobileSidebarToggle) {
                mobileSidebarToggle.addEventListener('click', function() {
                    sidebar.classList.remove('-translate-x-full');
                    sidebarOverlay.classList.remove('hidden');
                    // For accessibility, trap focus inside the sidebar
                    const sidebarLinks = sidebar.querySelectorAll('a, button');
                    if (sidebarLinks.length > 0) {
                        sidebarLinks[0].focus();
                    }
                });
            }
            
            // Close mobile sidebar
            if (sidebarOverlay) {
                sidebarOverlay.addEventListener('click', function() {
                    sidebar.classList.add('-translate-x-full');
                    sidebarOverlay.classList.add('hidden');
                    // Return focus to the toggle button for accessibility
                    if (mobileSidebarToggle) {
                        mobileSidebarToggle.focus();
                    }
                });
            }

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

            // Add focus trap for mobile sidebar
            if (sidebar && sidebarOverlay) {
                sidebar.addEventListener('keydown', function(e) {
                    const focusableElements = sidebar.querySelectorAll('a, button, input, select, textarea');
                    const firstFocusable = focusableElements[0];
                    const lastFocusable = focusableElements[focusableElements.length - 1];
                    
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
        });
    </script>
</body>
</html>