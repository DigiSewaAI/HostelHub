<!DOCTYPE html>
<html lang="ne" dir="ltr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <meta name="robots" content="noindex, nofollow">
    <title><?php echo $__env->yieldContent('title', 'ड्यासबोर्ड'); ?> - HostelHub Owner</title>
    
    <!-- Favicon -->
    <link rel="shortcut icon" href="<?php echo e(asset('favicon.ico')); ?>" type="image/x-icon">
    
    <!-- Google Fonts for Nepali -->
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Devanagari:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Font Awesome 6.4.0 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
          integrity="sha512-iecdLmaskl7CVskpV0uYGFkTd73EVdjGN7teJQ8N+2ER5yiJHHIyMI1GAa5I80LzvcpbKjByZcXc9j5QFZUvSJQ=="
          crossorigin="anonymous" referrerpolicy="no-referrer">
    
    <!-- Tailwind CSS with Vite -->
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <style>
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

        /* Main content area - FIXED */
        .main-content-area {
    margin-left: var(--sidebar-width);
    width: calc(100% - var(--sidebar-width));
    transition: all var(--transition-speed);
    min-height: 100vh;
    display: flex;
    flex-direction: column;
    overflow-y: auto; /* Add scrolling if content overflows */
}

        .sidebar.collapsed ~ .main-content-area {
            margin-left: var(--sidebar-collapsed-width);
            width: calc(100% - var(--sidebar-collapsed-width));
        }

        /* Mobile sidebar styles */
        @media (max-width: 1023px) {
            .sidebar {
                transform: translateX(-100%);
            }
            
            .sidebar.mobile-open {
                transform: translateX(0);
            }
            
            .main-content-area {
                margin-left: 0 !important;
                width: 100% !important;
            }
            
            .sidebar-overlay {
                position: fixed;
                inset: 0;
                background: rgba(0, 0, 0, 0.5);
                z-index: 999;
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
        }

        @media (min-width: 768px) {
            .page-content {
                padding: 1.5rem;
            }
        }
    </style>
    
    <!-- Page-specific CSS -->
    <?php echo $__env->yieldPushContent('styles'); ?>
</head>
<body class="bg-gray-50 font-sans">
    <a href="#main-content" class="skip-link">मुख्य सामग्रीमा जानुहोस्</a>
    
    <div class="flex min-h-screen">
        <!-- Sidebar -->
        <aside id="sidebar" class="sidebar text-white z-20 flex-shrink-0 transition-all duration-300 ease-in-out flex flex-col h-full">
            <div class="p-4 border-b border-blue-700 flex items-center justify-between">
                <a href="<?php echo e(url('/owner/dashboard')); ?>" class="logo-container">
                    <img src="<?php echo e(asset('storage/images/logo.png')); ?>" alt="HostelHub Logo" class="logo-img">
                    <span class="logo-text sidebar-text">होस्टलहब</span>
                </a>
                <button id="sidebar-collapse" class="text-gray-300 hover:text-white sidebar-text" aria-label="साइडबार सङ्कुचित गर्नुहोस्">
                    <i class="fas fa-bars-staggered"></i>
                </button>
            </div>
            
            <nav class="mt-5 px-2 flex-1 overflow-y-auto">
                <!-- Dashboard -->
                <a href="<?php echo e(route('owner.dashboard')); ?>"
                   class="sidebar-link <?php echo e(request()->routeIs('owner.dashboard') ? 'active' : ''); ?>"
                   aria-current="<?php echo e(request()->routeIs('owner.dashboard') ? 'page' : 'false'); ?>">
                    <i class="fas fa-tachometer-alt sidebar-icon"></i>
                    <span class="sidebar-text">ड्यासबोर्ड</span>
                </a>
                
                <!-- Meal Menus -->
                <a href="<?php echo e(route('owner.meal-menus.index')); ?>"
                   class="sidebar-link <?php echo e(request()->routeIs('owner.meal-menus.*') ? 'active' : ''); ?>"
                   aria-current="<?php echo e(request()->routeIs('owner.meal-menus.*') ? 'page' : 'false'); ?>">
                    <i class="fas fa-utensils sidebar-icon"></i>
                    <span class="sidebar-text">खानाको योजना</span>
                </a>
                
                <!-- Gallery -->
                <a href="<?php echo e(route('owner.galleries.index')); ?>"
                   class="sidebar-link <?php echo e(request()->routeIs('owner.galleries.*') ? 'active' : ''); ?>"
                   aria-current="<?php echo e(request()->routeIs('owner.galleries.*') ? 'page' : 'false'); ?>">
                    <i class="fas fa-images sidebar-icon"></i>
                    <span class="sidebar-text">ग्यालरी</span>
                </a>
                
                <!-- Rooms -->
                <a href="<?php echo e(route('owner.rooms.index')); ?>"
                   class="sidebar-link <?php echo e(request()->routeIs('owner.rooms.*') ? 'active' : ''); ?>"
                   aria-current="<?php echo e(request()->routeIs('owner.rooms.*') ? 'page' : 'false'); ?>">
                    <i class="fas fa-door-open sidebar-icon"></i>
                    <span class="sidebar-text">कोठाहरू</span>
                </a>
                
                <!-- Students -->
                <a href="<?php echo e(route('owner.students.index')); ?>"
                   class="sidebar-link <?php echo e(request()->routeIs('owner.students.*') ? 'active' : ''); ?>"
                   aria-current="<?php echo e(request()->routeIs('owner.students.*') ? 'page' : 'false'); ?>">
                    <i class="fas fa-user-graduate sidebar-icon"></i>
                    <span class="sidebar-text">विद्यार्थीहरू</span>
                </a>
                
                <!-- Payments -->
                <a href="<?php echo e(route('owner.payments.index')); ?>"
                   class="sidebar-link <?php echo e(request()->routeIs('owner.payments.*') ? 'active' : ''); ?>"
                   aria-current="<?php echo e(request()->routeIs('owner.payments.*') ? 'page' : 'false'); ?>">
                    <i class="fas fa-money-bill-wave sidebar-icon"></i>
                    <span class="sidebar-text">भुक्तानी</span>
                </a>
                
                <!-- Reviews -->
                <a href="<?php echo e(route('owner.reviews.index')); ?>"
                   class="sidebar-link <?php echo e(request()->routeIs('owner.reviews.*') ? 'active' : ''); ?>"
                   aria-current="<?php echo e(request()->routeIs('owner.reviews.*') ? 'page' : 'false'); ?>">
                    <i class="fas fa-star sidebar-icon"></i>
                    <span class="sidebar-text">समीक्षाहरू</span>
                </a>
                
                <!-- Hostels -->
                <a href="<?php echo e(route('owner.hostels.index')); ?>"
                   class="sidebar-link <?php echo e(request()->routeIs('owner.hostels.*') ? 'active' : ''); ?>"
                   aria-current="<?php echo e(request()->routeIs('owner.hostels.*') ? 'page' : 'false'); ?>">
                    <i class="fas fa-building sidebar-icon"></i>
                    <span class="sidebar-text">होस्टल</span>
                </a>

                <!-- Documents Management -->
                <a href="<?php echo e(route('owner.documents.index')); ?>"
                   class="sidebar-link <?php echo e(request()->routeIs('owner.documents.*') ? 'active' : ''); ?>"
                   aria-current="<?php echo e(request()->routeIs('owner.documents.*') ? 'page' : 'false'); ?>">
                    <i class="fas fa-file-alt sidebar-icon"></i>
                    <span class="sidebar-text">कागजात व्यवस्थापन</span>
                </a>

                <!-- Public Page Management -->
                <a href="<?php echo e(route('owner.public-page.edit')); ?>"
                   class="sidebar-link <?php echo e(request()->routeIs('owner.public-page.*') ? 'active' : ''); ?>"
                   aria-current="<?php echo e(request()->routeIs('owner.public-page.*') ? 'page' : 'false'); ?>">
                    <i class="fas fa-globe sidebar-icon"></i>
                    <span class="sidebar-text">सार्वजनिक पृष्ठ</span>
                </a>
                
                <!-- Logout Section -->
                <div class="mt-auto pt-4 border-t border-blue-700">
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

        <!-- Main Content Area - FIXED -->
        <div class="main-content-area">
            <!-- Top Navigation -->
            <header class="bg-gradient-primary shadow-sm z-10">
                <div class="flex items-center justify-between px-6 header-content">
                    <div class="flex items-center">
                        <button id="mobile-sidebar-toggle" class="lg:hidden text-white hover:text-gray-200 mr-4" aria-label="मोबाइल साइडबार खोल्नुहोस्">
                            <i class="fas fa-bars text-xl"></i>
                        </button>
                        <!-- Brand with Logo -->
                        <a href="<?php echo e(url('/owner/dashboard')); ?>" class="navbar-brand text-white flex items-center">
                            <img src="<?php echo e(asset('storage/images/logo.png')); ?>" alt="HostelHub Logo" class="mobile-logo mr-2">
                            <span class="hidden md:inline">होस्टलहब - मालिक प्यानल</span>
                        </a>
                    </div>
                    
                    <div class="flex items-center space-x-3">
                        <!-- Notifications -->
                        <div class="dropdown">
                            <button class="notification-button text-white hover:text-gray-200 p-2 rounded-full hover:bg-blue-700 dropdown-toggle" 
                                    type="button" 
                                    id="notificationsDropdown" 
                                    data-bs-toggle="dropdown" 
                                    aria-expanded="false"
                                    aria-label="सूचनाहरू हेर्नुहोस्">
                                <i class="fas fa-bell text-lg"></i>
                                <span class="notification-dot" aria-hidden="true"></span>
                            </button>
                            <div class="dropdown-menu dropdown-menu-end w-80 bg-white rounded-xl shadow-lg py-1 z-20 max-h-96 overflow-y-auto border border-gray-200" 
                                 aria-labelledby="notificationsDropdown">
                                <div class="px-4 py-2 border-b border-gray-200">
                                    <h3 class="font-semibold text-gray-800">सूचनाहरू</h3>
                                </div>
                                <a href="#" class="flex items-start px-4 py-3 hover:bg-gray-50 border-b border-gray-100">
                                    <div class="bg-indigo-100 p-2 rounded-lg mr-3">
                                        <i class="fas fa-utensils text-indigo-600"></i>
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-gray-800">नयाँ खानाको योजना सिर्जना गरियो</p>
                                        <p class="text-xs text-gray-500">३० मिनेट अघि</p>
                                    </div>
                                </a>
                                <a href="#" class="flex items-start px-4 py-3 hover:bg-gray-50 border-b border-gray-100">
                                    <div class="bg-amber-100 p-2 rounded-lg mr-3">
                                        <i class="fas fa-money-bill-wave text-amber-600"></i>
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-gray-800">भुक्तानी प्राप्त भयो</p>
                                        <p class="text-xs text-gray-500">१ घण्टा अघि</p>
                                    </div>
                                </a>
                                <a href="#" class="flex items-start px-4 py-3 hover:bg-gray-50">
                                    <div class="bg-red-100 p-2 rounded-lg mr-3">
                                        <i class="fas fa-star text-red-600"></i>
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-gray-800">नयाँ समीक्षा प्राप्त भयो</p>
                                        <p class="text-xs text-gray-500">२ घण्टा अघि</p>
                                    </div>
                                </a>
                                <div class="px-4 py-2 border-t border-gray-200 text-center">
                                    <a href="#" class="text-indigo-600 text-sm hover:underline">सबै सूचनाहरू हेर्नुहोस्</a>
                                </div>
                            </div>
                        </div>
                        
                        <!-- User Dropdown -->
                        <div class="d-flex align-items-center user-dropdown">
                            <span class="text-white me-3 d-none d-sm-inline"><?php echo e(auth()->user()->name); ?></span>
                            <div class="dropdown">
                                <button class="btn btn-outline-light dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="fas fa-user-circle me-1"></i>
                                    <span>मालिक</span>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end shadow">
                                    <li><a class="dropdown-item" href="#"><i class="fas fa-user me-2"></i>मेरो प्रोफाइल</a></li>
                                    <li><a class="dropdown-item" href="#"><i class="fas fa-cog me-2"></i>सेटिङ्हरू</a></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li>
                                        <form method="POST" action="<?php echo e(route('logout')); ?>" id="logout-form-top">
                                            <?php echo csrf_field(); ?>
                                            <button type="submit" class="dropdown-item" style="border: none; background: none; width: 100%; text-align: left;">
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

            <!-- Main Content Container -->
            <div class="main-content-container flex-1">
                <!-- Page Content -->
                <main id="main-content" class="page-content bg-gray-50 flex-1">
                    <div class="max-w-7xl mx-auto h-full">
                        <!-- ✅ FIXED: Removed duplicate page header section -->
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
                        <div class="bg-white rounded-xl shadow-sm overflow-hidden">
                            <?php echo $__env->yieldContent('content'); ?>
                        </div>
                    </div>
                </main>
                
                <!-- Footer -->
                <footer class="bg-white border-t border-gray-200 py-4 mt-auto">
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
    </div>
    
    <!-- Mobile Sidebar Overlay -->
    <div id="sidebar-overlay" class="sidebar-overlay hidden lg:hidden" aria-hidden="true"></div>
    
    <!-- Scripts -->
    <?php echo $__env->yieldPushContent('scripts'); ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Sidebar functionality
            const sidebar = document.getElementById('sidebar');
            const sidebarCollapse = document.getElementById('sidebar-collapse');
            const mobileSidebarToggle = document.getElementById('mobile-sidebar-toggle');
            const sidebarOverlay = document.getElementById('sidebar-overlay');
            
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
                    sidebar.classList.add('mobile-open');
                    sidebarOverlay.classList.remove('hidden');
                    document.body.style.overflow = 'hidden';
                });
            }
            
            // Close mobile sidebar
            if (sidebarOverlay) {
                sidebarOverlay.addEventListener('click', function() {
                    sidebar.classList.remove('mobile-open');
                    sidebarOverlay.classList.add('hidden');
                    document.body.style.overflow = 'auto';
                });
            }
            
            // Close mobile sidebar when clicking on a link
            const sidebarLinks = document.querySelectorAll('.sidebar-link');
            sidebarLinks.forEach(link => {
                link.addEventListener('click', function() {
                    if (window.innerWidth < 1024) {
                        sidebar.classList.remove('mobile-open');
                        sidebarOverlay.classList.add('hidden');
                        document.body.style.overflow = 'auto';
                    }
                });
            });
            
            // Check saved state
            if (localStorage.getItem('sidebarCollapsed') === 'true') {
                sidebar.classList.add('collapsed');
            }
            
            // Logout confirmation
            const logoutForms = document.querySelectorAll('#logout-form, #logout-form-top');
            logoutForms.forEach(form => {
                form.addEventListener('submit', function(e) {
                    if (!confirm('के तपाईं निश्चित रूपमा लगआउट गर्न चाहनुहुन्छ?')) {
                        e.preventDefault();
                    }
                });
            });

            // Close dropdowns when clicking outside
            document.addEventListener('click', function(event) {
                const dropdowns = document.querySelectorAll('.dropdown');
                dropdowns.forEach(dropdown => {
                    if (!dropdown.contains(event.target)) {
                        const dropdownMenu = dropdown.querySelector('.dropdown-menu');
                        if (dropdownMenu && dropdownMenu.classList.contains('show')) {
                            const dropdownToggle = dropdown.querySelector('.dropdown-toggle');
                            if (dropdownToggle) {
                                bootstrap.Dropdown.getInstance(dropdownToggle)?.hide();
                            }
                        }
                    }
                });
            });
        });

        // Handle window resize
        window.addEventListener('resize', function() {
            const sidebar = document.getElementById('sidebar');
            const sidebarOverlay = document.getElementById('sidebar-overlay');
            
            if (window.innerWidth >= 1024) {
                sidebar.classList.remove('mobile-open');
                sidebarOverlay.classList.add('hidden');
                document.body.style.overflow = 'auto';
            }
        });
    </script>
</body>
</html><?php /**PATH D:\My Projects\HostelHub\resources\views/layouts/owner.blade.php ENDPATH**/ ?>