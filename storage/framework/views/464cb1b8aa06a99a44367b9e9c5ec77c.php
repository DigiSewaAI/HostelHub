<!DOCTYPE html>
<html lang="ne" dir="ltr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <meta name="robots" content="noindex, nofollow">
    <title><?php echo $__env->yieldContent('title', 'ड्यासबोर्ड'); ?> - HostelHub Student</title>
    
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
        body {
            font-family: 'Noto Sans Devanagari', sans-serif;
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
        
        /* Enhanced Navbar Styles matching admin theme */
        .nav-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.75rem 1.5rem;
            background: linear-gradient(45deg, #4e73df, #224abe) !important;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        .nav-links {
            display: flex;
            gap: 1rem;
            align-items: center;
        }
        
        .nav-link {
            color: #ffffff !important;
            font-weight: 500;
            padding: 0.5rem 1rem;
            border-radius: 0.5rem;
            transition: all 0.3s;
            text-decoration: none;
            display: flex;
            align-items: center;
        }
        
        .nav-link:hover {
            background-color: rgba(255, 255, 255, 0.15) !important;
            transform: translateY(-1px);
            color: #ffffff !important;
        }
        
        .nav-link.active {
            background-color: rgba(255, 255, 255, 0.15) !important;
            border-left: 3px solid #ffffff;
        }
        
        .nav-link i {
            margin-right: 0.5rem;
            font-size: 1rem;
        }
        
        /* Logo Styles matching admin */
        .logo-img {
            height: 70px;
            width: auto;
            object-fit: contain;
        }
        
        .logo-text {
            margin-left: 10px;
            color: white;
            font-weight: bold;
            font-size: 26px;
        }
        
        /* Enhanced Button Styles */
        .btn {
            border-radius: 0.5rem;
            font-weight: 600;
            padding: 0.75rem 1.5rem;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s;
            text-decoration: none;
            border: none;
            cursor: pointer;
            margin: 0.5rem;
        }
        
        .btn-primary {
            background: linear-gradient(45deg, #4e73df, #224abe);
            color: white;
            box-shadow: 0 2px 5px rgba(78, 115, 223, 0.3);
        }
        
        .btn-primary:hover {
            background: linear-gradient(45deg, #224abe, #4e73df);
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(78, 115, 223, 0.4);
            color: white;
        }
        
        .btn-success {
            background: linear-gradient(45deg, #1cc88a, #13855c);
            color: white;
            box-shadow: 0 2px 5px rgba(28, 200, 138, 0.3);
        }
        
        .btn-success:hover {
            background: linear-gradient(45deg, #13855c, #1cc88a);
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(28, 200, 138, 0.4);
            color: white;
        }
        
        .btn-outline-light {
            background: transparent;
            border: 1px solid rgba(255, 255, 255, 0.3);
            color: white;
        }
        
        .btn-outline-light:hover {
            background: rgba(255, 255, 255, 0.1);
            color: white;
        }
        
        /* Button container spacing */
        .button-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 1rem;
            margin: 2rem 0;
        }
        
        /* Main content spacing */
        .main-content-container {
            padding: 2rem 0;
        }
        
        /* User dropdown matching admin */
        .user-dropdown {
            display: flex;
            align-items: center;
        }
        
        .user-dropdown .dropdown-menu {
            border: none;
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
            border-radius: 0.5rem;
        }
        
        .dropdown-item {
            padding: 0.5rem 1rem;
            border-radius: 0.35rem;
            margin: 0.1rem 0.25rem;
            display: flex;
            align-items: center;
        }
        
        .dropdown-item:hover {
            background-color: #f8f9fc;
        }
        
        /* Mobile responsive */
        @media (max-width: 768px) {
            .nav-container {
                flex-direction: column;
                padding: 1rem;
            }
            
            .nav-links {
                flex-wrap: wrap;
                justify-content: center;
                margin-top: 1rem;
                gap: 0.5rem;
            }
            
            .nav-link {
                padding: 0.4rem 0.8rem;
                font-size: 0.9rem;
            }
            
            .button-container {
                flex-direction: column;
                align-items: center;
            }
            
            .btn {
                width: 100%;
                max-width: 300px;
                margin: 0.25rem 0;
            }
        }
    </style>
    
    <!-- Page-specific CSS -->
    <?php echo $__env->yieldPushContent('styles'); ?>
</head>
<body class="bg-gray-50 font-sans">
    <a href="#main-content" class="skip-link">मुख्य सामग्रीमा जानुहोस्</a>
    
    <!-- ENHANCED NAVBAR - MATCHING ADMIN THEME -->
    <nav class="nav-container">
        <div class="flex items-center">
            <a href="<?php echo e(route('student.dashboard')); ?>" class="flex items-center text-decoration-none">
                <img src="<?php echo e(asset('storage/images/logo.png')); ?>" alt="HostelHub Logo" class="logo-img">
                <span class="logo-text">होस्टलहब - विद्यार्थी</span>
            </a>
        </div>
        
        <div class="nav-links">
            <a href="<?php echo e(route('student.dashboard')); ?>" 
               class="nav-link <?php echo e(request()->routeIs('student.dashboard') ? 'active' : ''); ?>">
                <i class="fas fa-tachometer-alt"></i>ड्यासबोर्ड
            </a>
            <a href="<?php echo e(route('student.profile')); ?>" 
               class="nav-link <?php echo e(request()->routeIs('student.profile') ? 'active' : ''); ?>">
                <i class="fas fa-user"></i>प्रोफाइल
            </a>
            <a href="<?php echo e(route('student.payments.index')); ?>" 
               class="nav-link <?php echo e(request()->routeIs('student.payments.*') ? 'active' : ''); ?>">
                <i class="fas fa-credit-card"></i>भुक्तानी
            </a>
            <a href="<?php echo e(route('student.meal-menus.index')); ?>" 
               class="nav-link <?php echo e(request()->routeIs('student.meal-menus.*') ? 'active' : ''); ?>">
                <i class="fas fa-utensils"></i>खानाको योजना
            </a>
            
            <!-- User Dropdown matching admin style -->
            <div class="d-flex align-items-center user-dropdown">
                <span class="text-white me-3 nepali">विद्यार्थी प्रोफाइल</span>
                <div class="dropdown">
                    <button class="btn btn-outline-light dropdown-toggle d-flex align-items-center" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fas fa-user-circle me-1"></i>
                        <span class="nepali">मेरो खाता</span>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end shadow">
                        <li><a class="dropdown-item nepali" href="<?php echo e(route('student.profile')); ?>"><i class="fas fa-user me-2"></i>मेरो प्रोफाइल</a></li>
                        <li><a class="dropdown-item nepali" href="<?php echo e(route('student.payments.index')); ?>"><i class="fas fa-credit-card me-2"></i>भुक्तानीहरू</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <form method="POST" action="<?php echo e(route('logout')); ?>" id="logout-form">
                                <?php echo csrf_field(); ?>
                                <button type="submit" class="dropdown-item nepali" style="border: none; background: none; width: 100%; text-align: left;">
                                    <i class="fas fa-sign-out-alt me-2"></i>लगआउट
                                </button>
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>

    <div class="min-h-screen bg-gray-100">
        <div class="py-6 main-content-container">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
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
                
                <main id="main-content">
                    <?php echo $__env->yieldContent('content'); ?>
                    
                    <!-- Enhanced Button Section with Correct Routes -->
                    <?php if (! empty(trim($__env->yieldContent('action-buttons')))): ?>
                        <?php echo $__env->yieldContent('action-buttons'); ?>
                    <?php else: ?>
                        <!-- Default action buttons for student dashboard -->
                        <div class="button-container">
                            <a href="<?php echo e(route('student.hostel.search')); ?>" class="btn btn-primary">
                                <i class="fas fa-search me-2"></i>होस्टल खोज्नुहोस्
                            </a>
                            <a href="<?php echo e(route('student.hostel.join')); ?>" class="btn btn-success">
                                <i class="fas fa-code me-2"></i>होस्टल कोड प्रयोग गर्नुहोस्
                            </a>
                            <a href="<?php echo e(route('student.dashboard')); ?>" class="btn btn-primary">
                                <i class="fas fa-tachometer-alt me-2"></i>ड्यासबोर्डमा जानुहोस्
                            </a>
                            <a href="<?php echo e(route('contact')); ?>" class="btn btn-success">
                                <i class="fas fa-phone me-2"></i>सम्पर्क गर्नुहोस्
                            </a>
                        </div>
                    <?php endif; ?>
                </main>
            </div>
        </div>
        
        <!-- Footer matching admin style -->
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
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Auto-dismiss alerts after 5 seconds
            setTimeout(function() {
                const alerts = document.querySelectorAll('.bg-green-50, .bg-red-50, .bg-yellow-50');
                alerts.forEach(alert => {
                    alert.style.transition = 'opacity 0.5s';
                    alert.style.opacity = '0';
                    setTimeout(() => alert.remove(), 500);
                });
            }, 5000);
            
            // Logout confirmation
            const logoutForm = document.getElementById('logout-form');
            if (logoutForm) {
                logoutForm.addEventListener('submit', function(e) {
                    if (!confirm('के तपाईं निश्चित रूपमा लगआउट गर्न चाहनुहुन्छ?')) {
                        e.preventDefault();
                    }
                });
            }
        });
    </script>
    
    <?php echo $__env->yieldPushContent('scripts'); ?>
</body>
</html><?php /**PATH D:\My Projects\HostelHub\resources\views/layouts/student.blade.php ENDPATH**/ ?>