<!DOCTYPE html>
<html lang="ne" dir="ltr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <meta name="robots" content="noindex, nofollow">

    <title><?php echo $__env->yieldContent('title', 'ड्यासबोर्ड'); ?> - HostelHub</title>

    <!-- Favicon -->
    <link rel="shortcut icon" href="<?php echo e(asset('favicon.ico')); ?>" type="image/x-icon">

    <!-- Google Fonts for Nepali -->
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Devanagari:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Tailwind CSS with Vite -->
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>

    <!-- Font Awesome 6.4.0 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
          integrity="sha512-iecdLmaskl7CVskpV0uYGFkTd73EVdjGN7teJQ8N+2ER5yiJHHIyMI1GAa5I80LzvcpbKjByZcXcC9j5QFZUvSJQ=="
          crossorigin="anonymous" referrerpolicy="no-referrer">

    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.tailwindcss.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.tailwindcss.min.css">

    <!-- Custom Styles -->
    <style>
        :root {
            --sidebar-width: 16rem;
            --sidebar-collapsed-width: 4.5rem;
            --transition-speed: 0.3s;
        }

        .sidebar {
            width: var(--sidebar-width);
            transition: width var(--transition-speed);
        }

        .sidebar.collapsed {
            width: var(--sidebar-collapsed-width);
        }

        .sidebar-link.active {
            background-color: #e0f2fe;
            color: #1d4ed8;
            border-left: 4px solid #3b82f6;
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
            padding: 0.75rem 1rem;
            border-radius: 0.375rem;
            color: #e2e8f0;
            transition: all 0.2s;
            margin-bottom: 0.25rem;
        }

        .sidebar-link:hover {
            background-color: #2563eb;
            color: white;
        }

        .sidebar-link i {
            width: 1.5rem;
            text-align: center;
            margin-right: 0.75rem;
        }

        .sidebar-link.active {
            background-color: #e0f2fe;
            color: #1d4ed8;
            border-left: 4px solid #3b82f6;
            font-weight: 600;
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
            top: 4px;
            right: 4px;
            width: 8px;
            height: 8px;
            background-color: #ef4444;
            border-radius: 50%;
        }
    </style>

    <!-- Page-specific CSS -->
    <?php echo $__env->yieldPushContent('styles'); ?>
</head>
<body class="bg-gray-50 font-sans" x-data="{ darkMode: false }">
    <a href="#main-content" class="skip-link nepali">मुख्य सामग्रीमा जानुहोस्</a>

    <div class="flex h-screen overflow-hidden">
        <!-- Sidebar -->
        <aside id="sidebar" class="sidebar bg-blue-800 text-white z-20 flex-shrink-0 transition-all duration-300 ease-in-out">
            <div class="p-4 border-b border-blue-700 flex items-center justify-between">
                <div class="flex items-center">
                    <i class="fas fa-university text-2xl mr-2"></i>
                    <h1 class="text-xl font-bold sidebar-text">होस्टल प्रबन्धन</h1>
                </div>
                <button id="sidebar-collapse" class="text-gray-300 hover:text-white sidebar-text" aria-label="साइडबार सङ्कुचित गर्नुहोस्">
                    <i class="fas fa-bars-staggered"></i>
                </button>
            </div>
            <nav class="mt-5 px-2">
                <!-- Dashboard -->
                <a href="<?php echo e(route('dashboard')); ?>"
                   class="sidebar-link <?php echo e(request()->routeIs('dashboard') ? 'active' : ''); ?>"
                   aria-current="<?php echo e(request()->routeIs('dashboard') ? 'page' : 'false'); ?>">
                    <i class="fas fa-tachometer-alt sidebar-icon"></i>
                    <span class="sidebar-text">ड्यासबोर्ड</span>
                </a>

                <!-- Conditional Menu Items -->
                <?php if(auth()->user()->isAdmin()): ?>
                    <!-- Admin Links -->
                    <?php if(Route::has('admin.hostels.index')): ?>
                        <a href="<?php echo e(route('admin.hostels.index')); ?>"
                           class="sidebar-link <?php echo e(request()->routeIs('admin.hostels.*') ? 'active' : ''); ?>"
                           aria-current="<?php echo e(request()->routeIs('admin.hostels.*') ? 'page' : 'false'); ?>">
                            <i class="fas fa-building sidebar-icon"></i>
                            <span class="sidebar-text">होस्टलहरू</span>
                        </a>
                    <?php else: ?>
                        <a href="#"
                           class="sidebar-link opacity-50 cursor-not-allowed"
                           aria-disabled="true">
                            <i class="fas fa-building sidebar-icon"></i>
                            <span class="sidebar-text">होस्टलहरू (प्रावधिक)</span>
                        </a>
                    <?php endif; ?>

                    <?php if(Route::has('admin.rooms.index')): ?>
                        <a href="<?php echo e(route('admin.rooms.index')); ?>"
                           class="sidebar-link <?php echo e(request()->routeIs('admin.rooms.*') ? 'active' : ''); ?>"
                           aria-current="<?php echo e(request()->routeIs('admin.rooms.*') ? 'page' : 'false'); ?>">
                            <i class="fas fa-door-open sidebar-icon"></i>
                            <span class="sidebar-text">कोठाहरू</span>
                        </a>
                    <?php else: ?>
                        <a href="#"
                           class="sidebar-link opacity-50 cursor-not-allowed"
                           aria-disabled="true">
                            <i class="fas fa-door-open sidebar-icon"></i>
                            <span class="sidebar-text">कोठाहरू (प्रावधिक)</span>
                        </a>
                    <?php endif; ?>

                    <?php if(Route::has('admin.students.index')): ?>
                        <a href="<?php echo e(route('admin.students.index')); ?>"
                           class="sidebar-link <?php echo e(request()->routeIs('admin.students.*') ? 'active' : ''); ?>"
                           aria-current="<?php echo e(request()->routeIs('admin.students.*') ? 'page' : 'false'); ?>">
                            <i class="fas fa-users sidebar-icon"></i>
                            <span class="sidebar-text">विद्यार्थीहरू</span>
                        </a>
                    <?php else: ?>
                        <a href="#"
                           class="sidebar-link opacity-50 cursor-not-allowed"
                           aria-disabled="true">
                            <i class="fas fa-users sidebar-icon"></i>
                            <span class="sidebar-text">विद्यार्थीहरू (प्रावधिक)</span>
                        </a>
                    <?php endif; ?>

                <?php elseif(auth()->user()->isHostelManager()): ?>
                    <!-- Owner Links -->
                    <?php if(Route::has('owner.hostels.index')): ?>
                        <a href="<?php echo e(route('owner.hostels.index')); ?>"
                           class="sidebar-link <?php echo e(request()->routeIs('owner.hostels.*') ? 'active' : ''); ?>"
                           aria-current="<?php echo e(request()->routeIs('owner.hostels.*') ? 'page' : 'false'); ?>">
                            <i class="fas fa-building sidebar-icon"></i>
                            <span class="sidebar-text">मेरा होस्टलहरू</span>
                        </a>
                    <?php else: ?>
                        <a href="#"
                           class="sidebar-link opacity-50 cursor-not-allowed"
                           aria-disabled="true">
                            <i class="fas fa-building sidebar-icon"></i>
                            <span class="sidebar-text">होस्टलहरू (प्रावधिक)</span>
                        </a>
                    <?php endif; ?>

                    <?php if(Route::has('owner.rooms.index')): ?>
                        <a href="<?php echo e(route('owner.rooms.index')); ?>"
                           class="sidebar-link <?php echo e(request()->routeIs('owner.rooms.*') ? 'active' : ''); ?>"
                           aria-current="<?php echo e(request()->routeIs('owner.rooms.*') ? 'page' : 'false'); ?>">
                            <i class="fas fa-door-open sidebar-icon"></i>
                            <span class="sidebar-text">मेरा कोठाहरू</span>
                        </a>
                    <?php else: ?>
                        <a href="#"
                           class="sidebar-link opacity-50 cursor-not-allowed"
                           aria-disabled="true">
                            <i class="fas fa-door-open sidebar-icon"></i>
                            <span class="sidebar-text">कोठाहरू (प्रावधिक)</span>
                        </a>
                    <?php endif; ?>

                <?php elseif(auth()->user()->isStudent()): ?>
                    <!-- Student Links -->
                    <?php if(Route::has('student.rooms.index')): ?>
                        <a href="<?php echo e(route('student.rooms.index')); ?>"
                           class="sidebar-link <?php echo e(request()->routeIs('student.rooms.*') ? 'active' : ''); ?>"
                           aria-current="<?php echo e(request()->routeIs('student.rooms.*') ? 'page' : 'false'); ?>">
                            <i class="fas fa-search sidebar-icon"></i>
                            <span class="sidebar-text">कोठा खोजी</span>
                        </a>
                    <?php else: ?>
                        <a href="#"
                           class="sidebar-link opacity-50 cursor-not-allowed"
                           aria-disabled="true">
                            <i class="fas fa-search sidebar-icon"></i>
                            <span class="sidebar-text">खोजी (प्रावधिक)</span>
                        </a>
                    <?php endif; ?>

                    <?php if(Route::has('student.bookings')): ?>
                        <a href="<?php echo e(route('student.bookings')); ?>"
                           class="sidebar-link <?php echo e(request()->routeIs('student.bookings') ? 'active' : ''); ?>"
                           aria-current="<?php echo e(request()->routeIs('student.bookings') ? 'page' : 'false'); ?>">
                            <i class="fas fa-calendar-check sidebar-icon"></i>
                            <span class="sidebar-text">मेरो बुकिङहरू</span>
                        </a>
                    <?php else: ?>
                        <a href="#"
                           class="sidebar-link opacity-50 cursor-not-allowed"
                           aria-disabled="true">
                            <i class="fas fa-calendar-check sidebar-icon"></i>
                            <span class="sidebar-text">बुकिङहरू (प्रावधिक)</span>
                        </a>
                    <?php endif; ?>
                <?php endif; ?>

                <!-- Common Links -->
                <?php if(Route::has('settings')): ?>
                    <a href="<?php echo e(route('settings')); ?>"
                       class="sidebar-link <?php echo e(request()->routeIs('settings') ? 'active' : ''); ?>"
                       aria-current="<?php echo e(request()->routeIs('settings') ? 'page' : 'false'); ?>">
                        <i class="fas fa-cog sidebar-icon"></i>
                        <span class="sidebar-text">सेटिङ्हरू</span>
                    </a>
                <?php else: ?>
                    <a href="#"
                       class="sidebar-link opacity-50 cursor-not-allowed"
                       aria-disabled="true">
                        <i class="fas fa-cog sidebar-icon"></i>
                        <span class="sidebar-text">सेटिङ्हरू (प्रावधिक)</span>
                    </a>
                <?php endif; ?>

                <!-- Gallery -->
                <?php if(Route::has('gallery.public')): ?>
                    <a href="<?php echo e(route('gallery.public')); ?>"
                       class="sidebar-link <?php echo e(request()->routeIs('gallery.*') ? 'active' : ''); ?>"
                       aria-current="<?php echo e(request()->routeIs('gallery.*') ? 'page' : 'false'); ?>">
                        <i class="fas fa-images sidebar-icon"></i>
                        <span class="sidebar-text">ग्यालरी</span>
                    </a>
                <?php else: ?>
                    <a href="#"
                       class="sidebar-link opacity-50 cursor-not-allowed"
                       aria-disabled="true">
                        <i class="fas fa-images sidebar-icon"></i>
                        <span class="sidebar-text">ग्यालरी (प्रावधिक)</span>
                    </a>
                <?php endif; ?>

                <div class="mt-6 pt-4 border-t border-blue-700">
                    <form method="POST" action="<?php echo e(route('logout')); ?>" id="logout-form">
                        <?php echo csrf_field(); ?>
                        <button type="submit" class="w-full flex items-center px-2 py-2 text-sm rounded-md hover:bg-blue-700 transition-colors">
                            <i class="fas fa-sign-out-alt sidebar-icon"></i>
                            <span class="sidebar-text">लगआउट</span>
                        </button>
                    </form>
                </div>
            </nav>
        </aside>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Top Navigation -->
            <header class="bg-white shadow-sm z-10">
                <div class="flex items-center justify-between px-6 py-4">
                    <div class="flex items-center">
                        <button id="mobile-sidebar-toggle" class="lg:hidden text-gray-500 hover:text-gray-700 mr-4" aria-label="मोबाइल साइडबार खोल्नुहोस्">
                            <i class="fas fa-bars text-xl"></i>
                        </button>

                        <!-- Breadcrumb -->
                        <nav class="hidden md:flex" aria-label="Breadcrumb">
                            <ol class="flex items-center space-x-2 text-sm">
                                <li>
                                    <a href="<?php echo e(route('dashboard')); ?>" class="text-gray-500 hover:text-indigo-600">
                                        <i class="fas fa-home"></i>
                                    </a>
                                </li>
                                <?php if(request()->routeIs('dashboard')): ?>
                                    <li class="text-gray-500">
                                        <i class="fas fa-chevron-right text-xs mx-2"></i>
                                        <span>ड्यासबोर्ड</span>
                                    </li>
                                <?php elseif(request()->routeIs('admin.hostels.*') || request()->routeIs('owner.hostels.*')): ?>
                                    <li class="text-gray-500">
                                        <i class="fas fa-chevron-right text-xs mx-2"></i>
                                        <a href="<?php echo e(auth()->user()->isAdmin() ? route('admin.hostels.index') : 
                                            route('owner.hostels.index')); ?>" class="hover:text-indigo-600">
                                            होस्टलहरू
                                        </a>
                                    </li>
                                    <?php if(request()->routeIs('admin.hostels.create') || request()->routeIs('owner.hostels.create')): ?>
                                        <li class="text-gray-500">
                                            <i class="fas fa-chevron-right text-xs mx-2"></i>
                                            <span>थप्नुहोस्</span>
                                        </li>
                                    <?php elseif(request()->routeIs('admin.hostels.edit') || request()->routeIs('owner.hostels.edit')): ?>
                                        <li class="text-gray-500">
                                            <i class="fas fa-chevron-right text-xs mx-2"></i>
                                            <span>सम्पादन गर्नुहोस्</span>
                                        </li>
                                    <?php endif; ?>
                                <?php elseif(request()->routeIs('admin.rooms.*') || request()->routeIs('owner.rooms.*')): ?>
                                    <li class="text-gray-500">
                                        <i class="fas fa-chevron-right text-xs mx-2"></i>
                                        <a href="<?php echo e(auth()->user()->isAdmin() ? route('admin.rooms.index') : 
                                            route('owner.rooms.index')); ?>" class="hover:text-indigo-600">कोठाहरू</a>
                                    </li>
                                <?php elseif(request()->routeIs('student.rooms.*')): ?>
                                    <li class="text-gray-500">
                                        <i class="fas fa-chevron-right text-xs mx-2"></i>
                                        <a href="<?php echo e(route('student.rooms.index')); ?>" class="hover:text-indigo-600">कोठा खोजी</a>
                                    </li>
                                <?php elseif(request()->routeIs('gallery.*')): ?>
                                    <li class="text-gray-500">
                                        <i class="fas fa-chevron-right text-xs mx-2"></i>
                                        <a href="<?php echo e(route('gallery.public')); ?>" class="hover:text-indigo-600">ग्यालरी</a>
                                    </li>
                                <?php endif; ?>
                            </ol>
                        </nav>
                    </div>

                    <h1 class="text-xl md:text-2xl font-semibold text-gray-800 hidden md:block"><?php echo $__env->yieldContent('title'); ?></h1>

                    <div class="flex items-center space-x-3">
                        <!-- Dark Mode Toggle -->
                        <button id="dark-mode-toggle" class="text-gray-500 hover:text-gray-700 p-2 rounded-full hover:bg-gray-100" aria-label="डार्क मोड टगल गर्नुहोस्">
                            <i class="fas fa-moon hidden dark-icon"></i>
                            <i class="fas fa-sun dark-icon"></i>
                        </button>

                        <!-- Notifications -->
                        <div class="relative" x-data="{ open: false }">
                            <button @click="open = !open" class="text-gray-500 hover:text-gray-700 p-2 rounded-full hover:bg-gray-100" aria-label="सूचनाहरू हेर्नुहोस्">
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
                                 class="absolute right-0 mt-2 w-80 bg-white rounded-xl shadow-lg py-1 z-20 max-h-96 overflow-y-auto border border-gray-200"
                                 role="menu"
                                 aria-orientation="vertical"
                                 aria-labelledby="notifications-button">
                                <div class="px-4 py-2 border-b border-gray-200">
                                    <h3 class="font-semibold text-gray-800">सूचनाहरू</h3>
                                </div>
                                <a href="#" class="flex items-start px-4 py-3 hover:bg-gray-50 border-b border-gray-100" role="menuitem">
                                    <div class="bg-indigo-100 p-2 rounded-lg mr-3">
                                        <i class="fas fa-user-plus text-indigo-600"></i>
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-gray-800">नयाँ विद्यार्थी दर्ता</p>
                                        <p class="text-xs text-gray-500">३० मिनेट अघि</p>
                                    </div>
                                </a>
                                <a href="#" class="flex items-start px-4 py-3 hover:bg-gray-50 border-b border-gray-100" role="menuitem">
                                    <div class="bg-amber-100 p-2 rounded-lg mr-3">
                                        <i class="fas fa-money-bill-wave text-amber-600"></i>
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-gray-800">भुक्तानी समाप्ति</p>
                                        <p class="text-xs text-gray-500">१ घण्टा अघि</p>
                                    </div>
                                </a>
                                <a href="#" class="flex items-start px-4 py-3 hover:bg-gray-50" role="menuitem">
                                    <div class="bg-red-100 p-2 rounded-lg mr-3">
                                        <i class="fas fa-exclamation-triangle text-red-600"></i>
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-gray-800">कोठा उपलब्धता</p>
                                        <p class="text-xs text-gray-500">२ घण्टा अघि</p>
                                    </div>
                                </a>
                                <div class="px-4 py-2 border-t border-gray-200 text-center">
                                    <a href="#" class="text-indigo-600 text-sm hover:underline">सबै सूचनाहरू हेर्नुहोस्</a>
                                </div>
                            </div>
                        </div>

                        <!-- User Dropdown -->
                        <div class="relative" x-data="{ open: false }">
                            <button @click="open = !open" class="flex items-center space-x-2 focus:outline-none" aria-expanded="false" aria-haspopup="true">
                                <div class="h-9 w-9 rounded-full bg-indigo-100 flex items-center justify-center">
                                    <span class="text-indigo-600 font-medium">
                                        <?php echo e(strtoupper(substr(optional(auth()->user())->name ?? 'U', 0, 2))); ?>

                                    </span>
                                </div>
                                <span class="hidden md:inline text-gray-700 font-medium">
                                    <?php echo e(optional(auth()->user())->name ?? 'प्रयोगकर्ता'); ?>

                                </span>
                                <i class="fas fa-chevron-down text-xs text-gray-500"></i>
                            </button>

                            <div x-show="open" @click.away="open = false"
                                 x-transition:enter="transition ease-out duration-200"
                                 x-transition:enter-start="opacity-0 transform scale-95"
                                 x-transition:enter-end="opacity-100 transform scale-100"
                                 x-transition:leave="transition ease-in duration-75"
                                 x-transition:leave-start="opacity-100 transform scale-100"
                                 x-transition:leave-end="opacity-0 transform scale-95"
                                 class="absolute right-0 mt-2 w-56 bg-white rounded-xl shadow-lg py-1 z-20 border border-gray-200"
                                 role="menu"
                                 aria-orientation="vertical"
                                 aria-labelledby="user-menu">
                                <a href="<?php echo e(route('profile.edit')); ?>" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-50" role="menuitem">
                                    <i class="fas fa-user mr-3 text-gray-400"></i>
                                    <span>मेरो प्रोफाइल</span>
                                </a>
                                <?php if(Route::has('settings')): ?>
                                    <a href="<?php echo e(route('settings')); ?>" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-50" role="menuitem">
                                        <i class="fas fa-cog mr-3 text-gray-400"></i>
                                        <span>सेटिङ्हरू</span>
                                    </a>
                                <?php endif; ?>
                                <hr class="my-1 border-gray-200">
                                <form method="POST" action="<?php echo e(route('logout')); ?>" id="logout-form">
                                    <?php echo csrf_field(); ?>
                                    <button type="submit" class="w-full flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-50" role="menuitem">
                                        <i class="fas fa-sign-out-alt mr-3 text-gray-400"></i>
                                        <span>लगआउट</span>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Page Content -->
            <main id="main-content" class="flex-1 overflow-y-auto p-4 md:p-6 bg-gray-50">
                <div class="max-w-7xl mx-auto">
                    <!-- Page Header -->
                    <div class="mb-6">
                        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                            <div>
                                <h1 class="text-2xl md:text-3xl font-bold text-gray-800 mb-1 md:block"><?php echo $__env->yieldContent('title'); ?></h1>
                                <?php if(View::hasSection('page-description')): ?>
                                    <p class="text-gray-600 text-sm"><?php echo $__env->yieldContent('page-description'); ?></p>
                                <?php endif; ?>
                            </div>
                            <div>
                                <?php echo $__env->yieldContent('header-buttons'); ?>
                            </div>
                        </div>
                    </div>

                    <!-- Session Messages -->
                    <?php if(session('success')): ?>
                        <div class="mb-6 p-4 bg-green-50 border border-green-200 text-green-700 rounded-lg flex items-center">
                            <i class="fas fa-check-circle mr-2"></i>
                            <span><?php echo e(session('success')); ?></span>
                        </div>
                    <?php endif; ?>

                    <?php if(session('error')): ?>
                        <div class="mb-6 p-4 bg-red-50 border border-red-200 text-red-700 rounded-lg flex items-center">
                            <i class="fas fa-exclamation-circle mr-2"></i>
                            <span><?php echo e(session('error')); ?></span>
                        </div>
                    <?php endif; ?>

                    <?php if($errors->any()): ?>
                        <div class="mb-6 p-4 bg-yellow-50 border border-yellow-200 text-yellow-700 rounded-lg">
                            <div class="flex items-center mb-2">
                                <i class="fas fa-exclamation-triangle mr-2"></i>
                                <strong class="font-medium">त्रुटिहरू पत्ता लाग्यो:</strong>
                            </div>
                            <ul class="list-disc pl-5 space-y-1">
                                <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <li><?php echo e($error); ?></li>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </ul>
                        </div>
                    <?php endif; ?>

                    <!-- Page Content -->
                    <div class="main-content bg-white rounded-xl shadow-sm overflow-hidden">
                        <?php echo $__env->yieldContent('content'); ?>
                    </div>
                </div>
            </main>

            <!-- Footer -->
            <footer class="bg-white border-t border-gray-200 py-4">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="flex flex-col md:flex-row justify-between items-center text-sm text-gray-500">
                        <p class="mb-2 md:mb-0">&copy; <?php echo e(date('Y')); ?> HostelHub. सबै अधिकार सुरक्षित।</p>
                        <div class="flex space-x-4">
                            <a href="#" class="hover:text-gray-700">गोपनीयता नीति</a>
                            <a href="#" class="hover:text-gray-700">सेवा सर्तहरू</a>
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
    <?php echo $__env->yieldPushContent('scripts'); ?>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Sidebar functionality
            const sidebar = document.getElementById('sidebar');
            const sidebarCollapse = document.getElementById('sidebar-collapse');
            const mobileSidebarToggle = document.getElementById('mobile-sidebar-toggle');
            const sidebarOverlay = document.getElementById('sidebar-overlay');
            const mainContent = document.getElementById('main-content');

            // Collapse/Expand sidebar
            if (sidebarCollapse) {
                sidebarCollapse.addEventListener('click', function() {
                    sidebar.classList.toggle('collapsed');
                    const isCollapsed = sidebar.classList.contains('collapsed');
                    localStorage.setItem('sidebarCollapsed', isCollapsed);
                });
            }

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

            // Check saved state
            if (localStorage.getItem('sidebarCollapsed') === 'true') {
                sidebar.classList.add('collapsed');
            }

            // Dark mode toggle
            const darkModeToggle = document.getElementById('dark-mode-toggle');
            const darkIcon = document.querySelector('.dark-icon');

            if (darkModeToggle && darkIcon) {
                const darkMode = localStorage.getItem('darkMode') === 'true';

                if (darkMode) {
                    document.body.classList.add('dark-mode');
                    darkIcon.classList.remove('fa-sun');
                    darkIcon.classList.add('fa-moon');
                }

                darkModeToggle.addEventListener('click', function() {
                    const isDarkMode = document.body.classList.toggle('dark-mode');
                    localStorage.setItem('darkMode', isDarkMode);

                    if (isDarkMode) {
                        darkIcon.classList.remove('fa-sun');
                        darkIcon.classList.add('fa-moon');
                    } else {
                        darkIcon.classList.remove('fa-moon');
                        darkIcon.classList.add('fa-sun');
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
            const logoutForm = document.getElementById('logout-form');
            if (logoutForm) {
                logoutForm.addEventListener('submit', function(e) {
                    if (!confirm('के तपाईं निश्चित रूपमा लगआउट गर्न चाहनुहुन्छ?')) {
                        e.preventDefault();
                    }
                });
            }

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
</html><?php /**PATH D:\My Projects\HostelHub\resources\views/layouts/app.blade.php ENDPATH**/ ?>