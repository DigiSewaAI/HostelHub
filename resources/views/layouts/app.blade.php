<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title') - HostelHub Admin</title>

    <!-- Tailwind CSS with Vite -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Font Awesome for Icons - समाधान: अतिरिक्त स्पेस हटाइयो -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Favicon -->
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">

    <!-- Custom Styles -->
    <style>
        .sidebar-active {
            @apply bg-indigo-50 border-l-4 border-indigo-500;
        }
    </style>

    <!-- Page-specific CSS -->
    @stack('styles')
</head>
<body class="bg-gray-50 font-sans">
    <div class="flex h-screen overflow-hidden">
        <!-- Sidebar -->
        <aside id="sidebar" class="w-64 bg-blue-800 text-white z-20 flex-shrink-0 transition-all duration-300 ease-in-out -translate-x-full lg:translate-x-0">
            <div class="p-4 border-b flex items-center justify-between">
                <h1 class="text-xl font-bold">होस्टल प्रबन्धन</h1>
                <button id="sidebar-toggle" class="lg:hidden text-gray-300 hover:text-white">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            <nav class="mt-5">
                <x-admin-nav-link :href="route('admin.dashboard')">ड्यासबोर्ड</x-admin-nav-link>
                <x-admin-nav-link :href="route('admin.students.index')">विद्यार्थीहरू</x-admin-nav-link>
                <x-admin-nav-link :href="route('admin.rooms.index')">कोठाहरू</x-admin-nav-link>
                <x-admin-nav-link :href="route('admin.meals.index')">भोजन</x-admin-nav-link>
                <x-admin-nav-link :href="route('admin.gallery.index')">ग्यालरी</x-admin-nav-link>
                <x-admin-nav-link :href="route('admin.contacts.index')">सम्पर्क</x-admin-nav-link>
            </nav>
        </aside>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Top Navigation -->
            <header class="bg-white shadow-sm z-10">
                <div class="flex items-center justify-between px-6 py-4">
                    <button id="mobile-sidebar-toggle" class="lg:hidden text-gray-500 hover:text-gray-700 mr-4">
                        <i class="fas fa-bars text-xl"></i>
                    </button>

                    <h1 class="text-2xl font-semibold text-gray-800">@yield('title')</h1>

                    <div class="flex items-center space-x-4">
                        <!-- Notifications -->
                        <div class="relative">
                            <button class="text-gray-500 hover:text-gray-700">
                                <i class="fas fa-bell text-xl"></i>
                                <span class="absolute top-0 right-0 block h-2 w-2 bg-red-500 rounded-full"></span>
                            </button>
                        </div>

                        <!-- User Dropdown -->
                        <div class="relative" x-data="{ open: false }">
                            <!-- समाधान: optional() हेल्पर प्रयोग गरी null सुरक्षा थपियो -->
                            <button @click="open = !open" class="flex items-center space-x-2 focus:outline-none">
                                <div class="h-8 w-8 rounded-full bg-indigo-100 flex items-center justify-center">
                                    <span class="text-indigo-600 font-medium">
                                        {{ substr(optional(auth()->user())->name ?? 'AD', 0, 2) }}
                                    </span>
                                </div>
                                <span class="hidden md:inline text-gray-700">
                                    {{ optional(auth()->user())->name ?? 'प्रशासक' }}
                                </span>
                                <i class="fas fa-chevron-down text-xs text-gray-500"></i>
                            </button>

                            <div x-show="open" @click.away="open = false" class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-20">
                                <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    <i class="fas fa-user mr-2"></i> मेरो प्रोफाइल
                                </a>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                        <i class="fas fa-sign-out-alt mr-2"></i> लग आउट
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Page Content -->
            <main class="flex-1 overflow-y-auto p-6 bg-gray-50">
                <div class="max-w-7xl mx-auto">
                    <!-- Session Messages -->
                    @if (session('success'))
                        <div class="mb-6 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded relative" role="alert">
                            <span class="block sm:inline">{{ session('success') }}</span>
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="mb-6 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded relative" role="alert">
                            <span class="block sm:inline">{{ session('error') }}</span>
                        </div>
                    @endif

                    <!-- Breadcrumbs -->
                    <nav class="flex mb-6" aria-label="Breadcrumb">
                        <ol class="inline-flex items-center space-x-1 md:space-x-3">
                            <li class="inline-flex items-center">
                                <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-indigo-600">
                                    <i class="fas fa-home mr-2"></i> Dashboard
                                </a>
                            </li>
                            @if(request()->routeIs('admin.students.*'))
                                <li aria-current="page">
                                    <div class="flex items-center">
                                        <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
                                        <span class="ml-1 text-sm font-medium text-indigo-600 md:ml-2">विद्यार्थीहरू</span>
                                    </div>
                                </li>
                            @endif
                            <!-- Add other breadcrumb paths as needed -->
                        </ol>
                    </nav>

                    <!-- Page Content -->
                    @yield('content')
                </div>
            </main>
        </div>
    </div>

    <!-- Scripts -->
    @stack('scripts')

    <script>
        // Mobile sidebar toggle
        document.addEventListener('DOMContentLoaded', function() {
            const sidebar = document.getElementById('sidebar');
            const mobileToggle = document.getElementById('mobile-sidebar-toggle');
            const sidebarToggle = document.getElementById('sidebar-toggle');

            mobileToggle.addEventListener('click', function() {
                sidebar.classList.toggle('-translate-x-full');
            });

            sidebarToggle.addEventListener('click', function() {
                sidebar.classList.add('-translate-x-full');
            });

            // Close sidebar when clicking outside on mobile
            document.addEventListener('click', function(event) {
                if (window.innerWidth < 1024 && !sidebar.contains(event.target) && !event.target.closest('#mobile-sidebar-toggle')) {
                    sidebar.classList.add('-translate-x-full');
                }
            });
        });
    </script>
</body>
</html>
