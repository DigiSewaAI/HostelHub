<!DOCTYPE html>
<html lang="ne">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HostelHub - Owner Dashboard</title>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Noto Sans Devanagari Font -->
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Devanagari:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Alpine.js for dropdowns and interactions -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <!-- Tailwind Configuration -->
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: {
                            50: '#eff6ff',
                            100: '#dbeafe',
                            500: '#3b82f6',
                            600: '#2563eb',
                            700: '#1d4ed8',
                            800: '#1e40af',
                            900: '#1e3a8a'
                        },
                        secondary: {
                            50: '#f8fafc',
                            100: '#f1f5f9',
                            200: '#e2e8f0',
                            300: '#cbd5e1',
                            400: '#94a3b8',
                            500: '#64748b',
                            600: '#475569',
                            700: '#334155',
                            800: '#1e293b',
                            900: '#0f172a'
                        }
                    },
                    fontFamily: {
                        'sans': ['Noto Sans Devanagari', 'system-ui', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    
    <style>
        body {
            font-family: 'Noto Sans Devanagari', sans-serif;
            background-color: #f8fafc;
        }
        .sidebar {
            transition: all 0.3s ease;
        }
        .nav-item {
            transition: all 0.2s ease;
        }
        .nav-item:hover {
            background-color: #e0f2fe;
        }
        .nav-item.active {
            background-color: #dbeafe;
            border-left: 4px solid #3b82f6;
        }
    </style>
</head>
<body class="font-sans antialiased bg-gray-50">
    <div x-data="{ sidebarOpen: true, userDropdownOpen: false }" class="flex h-screen bg-gray-50">
        <!-- Sidebar -->
        <div :class="sidebarOpen ? 'w-64' : 'w-20'" class="bg-gray-800 text-white min-h-screen flex-shrink-0 shadow-lg transition-all duration-300">
            <div class="p-4 flex items-center justify-between">
                <div class="flex items-center">
                    <div class="bg-primary-100 p-2 rounded-lg">
                        <i class="fas fa-home text-primary-600 text-xl"></i>
                    </div>
                    <h1 x-show="sidebarOpen" class="ml-3 text-xl font-bold whitespace-nowrap">HostelHub</h1>
                </div>
                <button @click="sidebarOpen = !sidebarOpen" class="text-gray-300 hover:text-white focus:outline-none">
                    <i :class="sidebarOpen ? 'fas fa-arrow-left' : 'fas fa-arrow-right'" class="text-lg"></i>
                </button>
            </div>
            
            <nav class="mt-5 px-2">
                <a href="{{ route('owner.dashboard') }}" class="nav-item flex items-center px-4 py-3 rounded-lg mb-1 hover:bg-primary-600 hover:text-white transition-colors duration-200 {{ request()->routeIs('owner.dashboard') ? 'bg-primary-600 text-white active' : 'text-gray-300' }}">
                    <i class="fas fa-tachometer-alt mr-3 text-lg"></i>
                    <span x-show="sidebarOpen" class="whitespace-nowrap">ड्यासबोर्ड</span>
                </a>
                
                <a href="{{ route('owner.meal-menus.index') }}" class="nav-item flex items-center px-4 py-3 rounded-lg mb-1 hover:bg-primary-600 hover:text-white transition-colors duration-200 {{ request()->routeIs('owner.meal-menus.*') ? 'bg-primary-600 text-white active' : 'text-gray-300' }}">
                    <i class="fas fa-utensils mr-3 text-lg"></i>
                    <span x-show="sidebarOpen" class="whitespace-nowrap">खानाको योजना</span>
                </a>
                
                <a href="{{ route('owner.galleries.index') }}" class="nav-item flex items-center px-4 py-3 rounded-lg mb-1 hover:bg-primary-600 hover:text-white transition-colors duration-200 {{ request()->routeIs('owner.galleries.*') ? 'bg-primary-600 text-white active' : 'text-gray-300' }}">
                    <i class="fas fa-images mr-3 text-lg"></i>
                    <span x-show="sidebarOpen" class="whitespace-nowrap">ग्यालरी</span>
                </a>
                
                <a href="{{ route('owner.rooms.index') }}" class="nav-item flex items-center px-4 py-3 rounded-lg mb-1 hover:bg-primary-600 hover:text-white transition-colors duration-200 {{ request()->routeIs('owner.rooms.*') ? 'bg-primary-600 text-white active' : 'text-gray-300' }}">
                    <i class="fas fa-door-open mr-3 text-lg"></i>
                    <span x-show="sidebarOpen" class="whitespace-nowrap">कोठाहरू</span>
                </a>
                
                <a href="{{ route('owner.students.index') }}" class="nav-item flex items-center px-4 py-3 rounded-lg mb-1 hover:bg-primary-600 hover:text-white transition-colors duration-200 {{ request()->routeIs('owner.students.*') ? 'bg-primary-600 text-white active' : 'text-gray-300' }}">
                    <i class="fas fa-user-graduate mr-3 text-lg"></i>
                    <span x-show="sidebarOpen" class="whitespace-nowrap">विद्यार्थीहरू</span>
                </a>
                
                <a href="{{ route('owner.payments.index') }}" class="nav-item flex items-center px-4 py-3 rounded-lg mb-1 hover:bg-primary-600 hover:text-white transition-colors duration-200 {{ request()->routeIs('owner.payments.*') ? 'bg-primary-600 text-white active' : 'text-gray-300' }}">
                    <i class="fas fa-money-bill-wave mr-3 text-lg"></i>
                    <span x-show="sidebarOpen" class="whitespace-nowrap">भुक्तानी</span>
                </a>
                
                <a href="{{ route('owner.reviews.index') }}" class="nav-item flex items-center px-4 py-3 rounded-lg mb-1 hover:bg-primary-600 hover:text-white transition-colors duration-200 {{ request()->routeIs('owner.reviews.*') ? 'bg-primary-600 text-white active' : 'text-gray-300' }}">
                    <i class="fas fa-star mr-3 text-lg"></i>
                    <span x-show="sidebarOpen" class="whitespace-nowrap">समीक्षाहरू</span>
                </a>
                
                <a href="{{ route('owner.hostels.index') }}" class="nav-item flex items-center px-4 py-3 rounded-lg mb-1 hover:bg-primary-600 hover:text-white transition-colors duration-200 {{ request()->routeIs('owner.hostels.*') ? 'bg-primary-600 text-white active' : 'text-gray-300' }}">
                    <i class="fas fa-building mr-3 text-lg"></i>
                    <span x-show="sidebarOpen" class="whitespace-nowrap">होस्टल</span>
                </a>
            </nav>
        </div>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Topbar -->
            <header class="bg-white shadow-sm z-10">
                <div class="flex items-center justify-between px-6 py-4">
                    <div class="flex items-center">
                        <button @click="sidebarOpen = !sidebarOpen" class="text-gray-500 hover:text-gray-700 focus:outline-none mr-4 lg:hidden">
                            <i class="fas fa-bars text-xl"></i>
                        </button>
                        <h1 class="text-xl font-bold text-gray-800">@yield('title', 'ड्यासबोर्ड')</h1>
                    </div>
                    
                    <div class="flex items-center space-x-4">
                        <!-- Notifications -->
                        <div class="relative">
                            <button class="p-2 rounded-full hover:bg-gray-100">
                                <i class="fas fa-bell text-gray-600"></i>
                                <span class="absolute top-0 right-0 h-3 w-3 bg-red-500 rounded-full"></span>
                            </button>
                        </div>
                        
                        <!-- User Profile -->
                        <div class="relative" x-data="{ dropdownOpen: false }">
                            <button @click="dropdownOpen = !dropdownOpen" class="flex items-center space-x-2">
                                <div class="h-10 w-10 rounded-full bg-primary-100 flex items-center justify-center">
                                    <span class="text-primary-800 font-semibold">{{ substr(auth()->user()->name, 0, 2) }}</span>
                                </div>
                                <span class="text-gray-700 hidden md:block">{{ auth()->user()->name }}</span>
                                <i class="fas fa-chevron-down text-xs text-gray-500 hidden md:block"></i>
                            </button>
                            
                            <!-- Dropdown Menu -->
                            <div x-show="dropdownOpen" @click.away="dropdownOpen = false" class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-20" style="display: none;">
                                <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">प्रोफाइल</a>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">लग आउट</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Page Content -->
            <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-50 py-6">
                <div class="container mx-auto px-4 sm:px-6 lg:px-8">
                    @if(session('success'))
                        <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                            <span class="block sm:inline">{{ session('success') }}</span>
                        </div>
                    @endif
                    
                    @if(session('error'))
                        <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                            <span class="block sm:inline">{{ session('error') }}</span>
                        </div>
                    @endif
                    
                    @yield('content')
                </div>
            </main>
        </div>
    </div>

    <!-- Flash Messages Script -->
    @if(session('success'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                alert("{{ session('success') }}");
            });
        </script>
    @endif
</body>
</html>