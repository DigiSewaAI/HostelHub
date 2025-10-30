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

    <!-- Critical CSS Fix -->
    <style>
        :root {
            --sidebar-width: 16rem;
            --sidebar-collapsed-width: 4.5rem;
            --transition-speed: 0.3s;
        }

        body {
            margin: 0;
            padding-top: 5rem !important;
            padding-bottom: 4rem !important;
            font-family: 'Noto Sans Devanagari', sans-serif;
            background-color: #f9fafb;
        }

        header {
            position: fixed;
            top: 0;
            width: 100%;
            z-index: 1000;
            height: 5rem;
            background-color: #ffffff !important;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }

        footer {
            position: fixed;
            bottom: 0;
            width: 100%;
            z-index: 1000;
            height: 4rem;
            background-color: #ffffff !important;
            box-shadow: 0 -4px 6px -1px rgba(0, 0, 0, 0.1);
        }

        .main-content {
            min-height: calc(100vh - 9rem);
        }

        .sidebar {
            width: var(--sidebar-width);
            transition: width var(--transition-speed);
            background-color: #1e40af !important;
            z-index: 900;
        }

        .sidebar.collapsed {
            width: var(--sidebar-collapsed-width);
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

        .sidebar-link.active {
            background-color: #e0f2fe;
            color: #1d4ed8;
            border-left: 4px solid #3b82f6;
            font-weight: 600;
        }

        .sidebar-link i {
            width: 1.5rem;
            text-align: center;
            margin-right: 0.75rem;
        }

        .sidebar.collapsed .sidebar-text {
            display: none;
        }

        .sidebar.collapsed .sidebar-icon {
            margin: 0 auto;
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
    </style>

    <?php echo $__env->yieldPushContent('styles'); ?>
</head>
<body class="bg-gray-50 font-sans" x-data="{ darkMode: false }">
    <a href="#main-content" class="skip-link">मुख्य सामग्रीमा जानुहोस्</a>

    <!-- Include Header -->
    <?php echo $__env->make('components.header', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <div class="flex h-screen overflow-hidden mt-20">
        <!-- Sidebar -->
        <aside id="sidebar" class="sidebar text-white flex-shrink-0 transition-all duration-300 ease-in-out">
            <div class="p-4 border-b border-blue-700 flex items-center justify-between">
                <div class="flex items-center">
                    <!-- यदि तपाईंले sidebar मा लोगो नचाहनुहुन्छ भने, यो हटाउनुहोस् -->
                    <!-- <img src="<?php echo e(asset('storage/images/logo.png')); ?>" alt="HostelHub Logo" class="w-32 h-10 object-contain logo"> -->
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
                <?php if(auth()->guard()->check()): ?>
                    <?php if(auth()->user()->isAdmin()): ?>
                        <?php if(Route::has('admin.hostels.index')): ?>
                            <a href="<?php echo e(route('admin.hostels.index')); ?>"
                               class="sidebar-link <?php echo e(request()->routeIs('admin.hostels.*') ? 'active' : ''); ?>"
                               aria-current="<?php echo e(request()->routeIs('admin.hostels.*') ? 'page' : 'false'); ?>">
                                <i class="fas fa-building sidebar-icon"></i>
                                <span class="sidebar-text">होस्टलहरू</span>
                            </a>
                        <?php else: ?>
                            <a href="#" class="sidebar-link opacity-50 cursor-not-allowed" aria-disabled="true">
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
                            <a href="#" class="sidebar-link opacity-50 cursor-not-allowed" aria-disabled="true">
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
                            <a href="#" class="sidebar-link opacity-50 cursor-not-allowed" aria-disabled="true">
                                <i class="fas fa-users sidebar-icon"></i>
                                <span class="sidebar-text">विद्यार्थीहरू (प्रावधिक)</span>
                            </a>
                        <?php endif; ?>

                    <?php elseif(auth()->user()->isHostelManager()): ?>
                        <?php if(Route::has('owner.hostels.index')): ?>
                            <a href="<?php echo e(route('owner.hostels.index')); ?>"
                               class="sidebar-link <?php echo e(request()->routeIs('owner.hostels.*') ? 'active' : ''); ?>"
                               aria-current="<?php echo e(request()->routeIs('owner.hostels.*') ? 'page' : 'false'); ?>">
                                <i class="fas fa-building sidebar-icon"></i>
                                <span class="sidebar-text">मेरा होस्टलहरू</span>
                            </a>
                        <?php else: ?>
                            <a href="#" class="sidebar-link opacity-50 cursor-not-allowed" aria-disabled="true">
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
                            <a href="#" class="sidebar-link opacity-50 cursor-not-allowed" aria-disabled="true">
                                <i class="fas fa-door-open sidebar-icon"></i>
                                <span class="sidebar-text">कोठाहरू (प्रावधिक)</span>
                            </a>
                        <?php endif; ?>

                    <?php elseif(auth()->user()->isStudent()): ?>
                        <?php if(Route::has('student.rooms.index')): ?>
                            <a href="<?php echo e(route('student.rooms.index')); ?>"
                               class="sidebar-link <?php echo e(request()->routeIs('student.rooms.*') ? 'active' : ''); ?>"
                               aria-current="<?php echo e(request()->routeIs('student.rooms.*') ? 'page' : 'false'); ?>">
                                <i class="fas fa-search sidebar-icon"></i>
                                <span class="sidebar-text">कोठा खोजी</span>
                            </a>
                        <?php else: ?>
                            <a href="#" class="sidebar-link opacity-50 cursor-not-allowed" aria-disabled="true">
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
                            <a href="#" class="sidebar-link opacity-50 cursor-not-allowed" aria-disabled="true">
                                <i class="fas fa-calendar-check sidebar-icon"></i>
                                <span class="sidebar-text">बुकिङहरू (प्रावधिक)</span>
                            </a>
                        <?php endif; ?>
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
                    <a href="#" class="sidebar-link opacity-50 cursor-not-allowed" aria-disabled="true">
                        <i class="fas fa-cog sidebar-icon"></i>
                        <span class="sidebar-text">सेटिङ्हरू (प्रावधिक)</span>
                    </a>
                <?php endif; ?>

                <?php if(Route::has('gallery.public')): ?>
                    <a href="<?php echo e(route('gallery.public')); ?>"
                       class="sidebar-link <?php echo e(request()->routeIs('gallery.*') ? 'active' : ''); ?>"
                       aria-current="<?php echo e(request()->routeIs('gallery.*') ? 'page' : 'false'); ?>">
                        <i class="fas fa-images sidebar-icon"></i>
                        <span class="sidebar-text">ग्यालरी</span>
                    </a>
                <?php else: ?>
                    <a href="#" class="sidebar-link opacity-50 cursor-not-allowed" aria-disabled="true">
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
            <main id="main-content" class="flex-1 overflow-y-auto p-4 md:p-6 bg-gray-50 relative z-10">
                <div class="max-w-7xl mx-auto">
                    <!-- Page Header -->
                    <div class="mb-6">
                        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                            <div>
                                <h1 class="text-2xl md:text-3xl font-bold text-gray-800 mb-1"><?php echo $__env->yieldContent('title'); ?></h1>
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
        </div>
    </div>

    <!-- Include Footer -->
    <?php echo $__env->make('components.footer', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <!-- Mobile Sidebar Overlay -->
    <div id="sidebar-overlay" class="fixed inset-0 bg-black bg-opacity-50 z-10 hidden lg:hidden" aria-hidden="true"></div>

    <!-- Video Modal -->
    <div id="video-modal" class="fixed inset-0 bg-black bg-opacity-90 z-50 hidden flex items-center justify-center p-4">
        <div class="relative w-full max-w-4xl">
            <button id="close-video-modal" class="absolute -top-12 right-0 text-white text-xl hover:text-gray-300 transition-colors">
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
            const sidebar = document.getElementById('sidebar');
            const sidebarCollapse = document.getElementById('sidebar-collapse');
            const sidebarOverlay = document.getElementById('sidebar-overlay');

            // Collapse/Expand sidebar
            if (sidebarCollapse) {
                sidebarCollapse.addEventListener('click', function() {
                    sidebar.classList.toggle('collapsed');
                    localStorage.setItem('sidebarCollapsed', sidebar.classList.contains('collapsed'));
                });
            }

            // Check saved state
            if (localStorage.getItem('sidebarCollapsed') === 'true') {
                sidebar.classList.add('collapsed');
            }

            // Mobile sidebar toggle handled in header
        });
    </script>
</body>
</html><?php /**PATH C:\laragon\www\HostelHub\resources\views\layouts\app.blade.php ENDPATH**/ ?>