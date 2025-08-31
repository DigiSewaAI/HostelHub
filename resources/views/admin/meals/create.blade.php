<!DOCTYPE html>
<html lang="ne">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>खाना व्यवस्थापन - HostelHub</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Devanagari:wght@300;400;500;600;700&display=swap" rel="stylesheet">
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
            border-left: 4px solid #3b82f6;
        }
        
        .nav-item.active {
            background-color: #e0f2fe;
            border-left: 4px solid #3b82f6;
            font-weight: 600;
        }
        
        .card {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1);
        }
        
        .table-row:hover {
            background-color: #f1f5f9;
        }
        
        .stat-card {
            transition: all 0.3s ease;
        }
        
        .stat-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }
        
        .btn-primary {
            transition: all 0.2s ease;
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 6px -1px rgba(59, 130, 246, 0.5);
        }
        
        .form-input:focus {
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.2);
        }
        
        .animation-pulse {
            animation: pulse 2s infinite;
        }
        
        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.05); }
            100% { transform: scale(1); }
        }

        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="bg-gray-50">
    <div class="flex h-screen">
        <!-- Sidebar -->
        <div class="sidebar w-64 bg-primary-900 text-white flex flex-col">
            <!-- Logo -->
            <div class="p-5 border-b border-primary-700 flex items-center">
                <i class="fas fa-university text-2xl mr-3"></i>
                <h1 class="text-xl font-bold">होस्टल हब</h1>
            </div>
            
            <!-- Navigation -->
            <nav class="flex-1 overflow-y-auto py-4">
                <ul class="space-y-1 px-3">
                    <li class="nav-item">
                        <a href="#" class="flex items-center px-4 py-3 rounded-md">
                            <i class="fas fa-tachometer-alt w-5 mr-3 text-center"></i>
                            <span>ड्यासबोर्ड</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="#" class="flex items-center px-4 py-3 rounded-md">
                            <i class="fas fa-building w-5 mr-3 text-center"></i>
                            <span>होस्टलहरू</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="#" class="flex items-center px-4 py-3 rounded-md">
                            <i class="fas fa-door-open w-5 mr-3 text-center"></i>
                            <span>कोठाहरू</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="#" class="flex items-center px-4 py-3 rounded-md">
                            <i class="fas fa-users w-5 mr-3 text-center"></i>
                            <span>विद्यार्थीहरू</span>
                        </a>
                    </li>
                    <li class="nav-item active">
                        <a href="#" class="flex items-center px-4 py-3 rounded-md">
                            <i class="fas fa-utensils w-5 mr-3 text-center"></i>
                            <span>खाना व्यवस्थापन</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="#" class="flex items-center px-4 py-3 rounded-md">
                            <i class="fas fa-money-bill-wave w-5 mr-3 text-center"></i>
                            <span>भुक्तानीहरू</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="#" class="flex items-center px-4 py-3 rounded-md">
                            <i class="fas fa-chart-bar w-5 mr-3 text-center"></i>
                            <span>रिपोर्टहरू</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="#" class="flex items-center px-4 py-3 rounded-md">
                            <i class="fas fa-cog w-5 mr-3 text-center"></i>
                            <span>सेटिङ्गहरू</span>
                        </a>
                    </li>
                </ul>
                
                <div class="mt-10 px-3">
                    <div class="text-xs uppercase text-primary-300 mb-2 px-4">समर्थन</div>
                    <ul class="space-y-1">
                        <li class="nav-item">
                            <a href="#" class="flex items-center px-4 py-3 rounded-md">
                                <i class="fas fa-question-circle w-5 mr-3 text-center"></i>
                                <span>सहयोग</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="flex items-center px-4 py-3 rounded-md">
                                <i class="fas fa-sign-out-alt w-5 mr-3 text-center"></i>
                                <span>लगआउट</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </nav>
        </div>
        
        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Header -->
            <header class="bg-white shadow-sm">
                <div class="flex items-center justify-between px-6 py-4">
                    <div class="flex items-center">
                        <h2 class="text-xl font-semibold text-gray-800">खाना व्यवस्थापन</h2>
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
                        <div class="relative">
                            <button class="flex items-center space-x-2" @click="userDropdownOpen = !userDropdownOpen">
                                <div class="h-10 w-10 rounded-full bg-primary-100 flex items-center justify-center">
                                    <span class="text-primary-800 font-semibold">PA</span>
                                </div>
                                <span class="text-gray-700">Parashar Regmi</span>
                                <i class="fas fa-chevron-down text-xs text-gray-500"></i>
                            </button>
                            
                            <!-- User Dropdown -->
                            <div x-show="userDropdownOpen" @click.away="userDropdownOpen = false" class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-10" style="display: none;">
                                <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">प्रोफाइल</a>
                                <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">सेटिङ्गहरू</a>
                                <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">लगआउट</a>
                            </div>
                        </div>
                    </div>
                </div>
            </header>
            
            <!-- Main Content Area -->
            <main class="flex-1 overflow-y-auto p-6 bg-gray-50" x-data="mealManagement()">
                <!-- Stats Section -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                    <div class="stat-card bg-white rounded-xl shadow-sm p-6 border-l-4 border-blue-500">
                        <div class="flex items-center">
                            <div class="p-3 rounded-lg bg-blue-100 mr-4">
                                <i class="fas fa-utensils text-blue-600 text-xl"></i>
                            </div>
                            <div>
                                <h3 class="text-2xl font-bold text-gray-800">१,२४८</h3>
                                <p class="text-gray-600">आजको खाना</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="stat-card bg-white rounded-xl shadow-sm p-6 border-l-4 border-green-500">
                        <div class="flex items-center">
                            <div class="p-3 rounded-lg bg-green-100 mr-4">
                                <i class="fas fa-users text-green-600 text-xl"></i>
                            </div>
                            <div>
                                <h3 class="text-2xl font-bold text-gray-800">३५६</h3>
                                <p class="text-gray-600">विद्यार्थीहरू</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="stat-card bg-white rounded-xl shadow-sm p-6 border-l-4 border-yellow-500">
                        <div class="flex items-center">
                            <div class="p-3 rounded-lg bg-yellow-100 mr-4">
                                <i class="fas fa-building text-yellow-600 text-xl"></i>
                            </div>
                            <div>
                                <h3 class="text-2xl font-bold text-gray-800">१२</h3>
                                <p class="text-gray-600">होस्टलहरू</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="stat-card bg-white rounded-xl shadow-sm p-6 border-l-4 border-red-500">
                        <div class="flex items-center">
                            <div class="p-3 rounded-lg bg-red-100 mr-4">
                                <i class="fas fa-utensil-spoon text-red-600 text-xl"></i>
                            </div>
                            <div>
                                <h3 class="text-2xl font-bold text-gray-800">२८</h3>
                                <p class="text-gray-600">अनुपस्थित</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Form Section -->
                <div class="bg-white rounded-xl shadow-sm overflow-hidden mb-8">
                    <div class="px-6 py-4 border-b border-gray-100">
                        <h3 class="text-lg font-semibold text-gray-800 flex items-center">
                            <i class="fas fa-plus-circle mr-2 text-primary-600"></i>
                            नयाँ खाना थप्नुहोस्
                        </h3>
                    </div>
                    
                    <div class="p-6">
                        <form>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                                <!-- Hostel Dropdown -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">होस्टल</label>
                                    <div class="relative" x-data="{ open: false }">
                                        <button type="button" 
                                                class="form-input block w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 text-left flex justify-between items-center"
                                                @click="open = !open"
                                                @click.away="open = false">
                                            <span x-text="selectedHostel ? selectedHostel.name : 'होस्टल छान्नुहोस्'"></span>
                                            <i class="fas" :class="open ? 'fa-chevron-up' : 'fa-chevron-down'"></i>
                                        </button>
                                        
                                        <div x-show="open" class="absolute z-10 mt-1 w-full bg-white shadow-lg rounded-md border border-gray-300 max-h-60 overflow-auto" style="display: none;">
                                            <div class="p-2 sticky top-0 bg-white">
                                                <input type="text" 
                                                       placeholder="खोज्नुहोस्..." 
                                                       class="w-full px-3 py-2 border border-gray-300 rounded-md"
                                                       x-model="hostelSearch"
                                                       @click.stop>
                                            </div>
                                            <ul class="py-1">
                                                <template x-for="hostel in filteredHostels" :key="hostel.id">
                                                    <li>
                                                        <button type="button" 
                                                                class="w-full text-left px-4 py-2 hover:bg-gray-100"
                                                                @click="selectHostel(hostel); open = false">
                                                            <span x-text="hostel.name"></span>
                                                        </button>
                                                    </li>
                                                </template>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Student Dropdown (depends on hostel selection) -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">विद्यार्थी</label>
                                    <div class="relative" x-data="{ open: false }">
                                        <button type="button" 
                                                class="form-input block w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 text-left flex justify-between items-center"
                                                :class="{'text-gray-400': !selectedHostel}"
                                                :disabled="!selectedHostel"
                                                @click="if(selectedHostel) open = !open"
                                                @click.away="open = false">
                                            <span x-text="selectedStudent ? selectedStudent.name + ' (' + selectedStudent.hostel_name + ')' : 'विद्यार्थी छान्नुहोस्'"></span>
                                            <i class="fas" :class="open ? 'fa-chevron-up' : 'fa-chevron-down'"></i>
                                        </button>
                                        
                                        <div x-show="open && selectedHostel" class="absolute z-10 mt-1 w-full bg-white shadow-lg rounded-md border border-gray-300 max-h-60 overflow-auto" style="display: none;">
                                            <div class="p-2 sticky top-0 bg-white">
                                                <input type="text" 
                                                       placeholder="खोज्नुहोस्..." 
                                                       class="w-full px-3 py-2 border border-gray-300 rounded-md"
                                                       x-model="studentSearch"
                                                       @click.stop>
                                            </div>
                                            <ul class="py-1">
                                                <template x-for="student in filteredStudents" :key="student.id">
                                                    <li>
                                                        <button type="button" 
                                                                class="w-full text-left px-4 py-2 hover:bg-gray-100"
                                                                @click="selectStudent(student); open = false">
                                                            <span x-text="student.name + ' (' + student.hostel_name + ')'"></span>
                                                        </button>
                                                    </li>
                                                </template>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">खानाको प्रकार</label>
                                    <div class="relative">
                                        <select class="form-input block w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                                            <option>बिहानको खाना</option>
                                            <option>दिउँसोको खाना</option>
                                            <option>बेलुकाको खाना</option>
                                        </select>
                                        <div class="absolute inset-y-0 right-0 flex items-center px-2 pointer-events-none">
                                            <i class="fas fa-chevron-down text-gray-400"></i>
                                        </div>
                                    </div>
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">मिति</label>
                                    <div class="relative">
                                        <input type="date" class="form-input block w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500" value="2023-11-15">
                                        <div class="absolute inset-y-0 right-0 flex items-center px-2 pointer-events-none">
                                            <i class="fas fa-calendar-alt text-gray-400"></i>
                                        </div>
                                    </div>
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">अवस्था</label>
                                    <div class="relative">
                                        <select class="form-input block w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                                            <option>उपस्थित</option>
                                            <option>अनुपस्थित</option>
                                        </select>
                                        <div class="absolute inset-y-0 right-0 flex items-center px-2 pointer-events-none">
                                            <i class="fas fa-chevron-down text-gray-400"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="flex gap-3">
                                <button type="submit" class="btn-primary inline-flex items-center px-5 py-3 bg-primary-600 text-white rounded-lg font-semibold hover:bg-primary-700">
                                    <i class="fas fa-save mr-2"></i>
                                    सुरक्षित गर्नुहोस्
                                </button>
                                
                                <button type="button" class="inline-flex items-center px-5 py-3 bg-gray-100 text-gray-700 rounded-lg font-semibold hover:bg-gray-200">
                                    <i class="fas fa-times mr-2"></i>
                                    रद्द गर्नुहोस्
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
                
                <!-- Meal Tracking Table -->
                <div class="bg-white rounded-xl shadow-sm overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                        <h3 class="text-lg font-semibold text-gray-800 flex items-center">
                            <i class="fas fa-clipboard-list mr-2 text-primary-600"></i>
                            खानाको ट्र्याकिंग
                        </h3>
                        
                        <div class="flex items-center space-x-3">
                            <div class="relative">
                                <input type="text" placeholder="खोज्नुहोस्..." class="pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                                <div class="absolute inset-y-0 left-0 flex items-center pl-3">
                                    <i class="fas fa-search text-gray-400"></i>
                                </div>
                            </div>
                            
                            <button class="p-2 text-gray-600 hover:text-gray-800">
                                <i class="fas fa-filter"></i>
                            </button>
                        </div>
                    </div>
                    
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">विद्यार्थी</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">होस्टल</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">खानाको प्रकार</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">मिति</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">अवस्था</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">कार्य</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                <tr class="table-row">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="h-10 w-10 flex-shrink-0 bg-blue-100 rounded-full flex items-center justify-center">
                                                <span class="text-blue-800 font-medium">RB</span>
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-gray-900">राम बहादुर</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">काठमाडौं होस्टल</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">बिहानको खाना</span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">२०८०/०८/०१</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">उपस्थित</span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <div class="flex items-center space-x-3">
                                            <a href="#" class="text-blue-600 hover:text-blue-900">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <a href="#" class="text-red-600 hover:text-red-900">
                                                <i class="fas fa-trash"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                
                                <tr class="table-row">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="h-10 w-10 flex-shrink-0 bg-green-100 rounded-full flex items-center justify-center">
                                                <span class="text-green-800 font-medium">SM</span>
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-gray-900">सीता महर्जन</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">पोखरा होस्टल</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">दिउँसोको खाना</span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">२०८०/०८/०१</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">अनुपस्थित</span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <div class="flex items-center space-x-3">
                                            <a href="#" class="text-blue-600 hover:text-blue-900">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <a href="#" class="text-red-600 hover:text-red-900">
                                                <i class="fas fa-trash"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                
                                <tr class="table-row">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="h-10 w-10 flex-shrink-0 bg-purple-100 rounded-full flex items-center justify-center">
                                                <span class="text-purple-800 font-medium">HP</span>
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-gray-900">हरि प्रसाद</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">ललितपुर होस्टल</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-indigo-100 text-indigo-800">बेलुकाको खाना</span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">२०८०/०८/०१</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">उपस्थित</span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <div class="flex items-center space-x-3">
                                            <a href="#" class="text-blue-600 hover:text-blue-900">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <a href="#" class="text-red-600 hover:text-red-900">
                                                <i class="fas fa-trash"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="px-6 py-4 border-t border-gray-200 flex items-center justify-between">
                        <div class="text-sm text-gray-700">
                            देखाइएको <span class="font-medium">१</span> देखि <span class="font-medium">३</span> सम्म <span class="font-medium">३</span> नतिजा
                        </div>
                        
                        <div class="flex space-x-2">
                            <button class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-md hover:bg-gray-200">
                                अघिल्लो
                            </button>
                            <button class="px-4 py-2 text-sm font-medium text-white bg-primary-600 rounded-md hover:bg-primary-700">
                                १
                            </button>
                            <button class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-md hover:bg-gray-200">
                                अर्को
                            </button>
                        </div>
                    </div>
                </div>
            </main>
            
            <!-- Footer -->
            <footer class="bg-white border-t border-gray-200 py-4 px-6">
                <div class="flex flex-col md:flex-row justify-between items-center">
                    <p class="text-sm text-gray-600">© २०२३ HostelHub. सबै अधिकार सुरक्षित।</p>
                    
                    <div class="flex items-center space-x-4 mt-3 md:mt-0">
                        <a href="#" class="text-gray-400 hover:text-gray-500">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                        <a href="#" class="text-gray-400 hover:text-gray-500">
                            <i class="fab fa-twitter"></i>
                        </a>
                        <a href="#" class="text-gray-400 hover:text-gray-500">
                            <i class="fab fa-instagram"></i>
                        </a>
                        <a href="#" class="text-gray-400 hover:text-gray-500">
                            <i class="fab fa-linkedin-in"></i>
                        </a>
                    </div>
                </div>
            </footer>
        </div>
    </div>

    <script>
        // User role simulation (can be changed to test different scenarios)
        const userRole = 'super_admin'; // 'super_admin' or 'hostel_owner'
        const userHostelId = userRole === 'hostel_owner' ? 2 : null; // If hostel owner, set their hostel ID

        // Sample data
        const hostels = [
            { id: 1, name: 'काठमाडौं होस्टल' },
            { id: 2, name: 'पोखरा होस्टल' },
            { id: 3, name: 'ललितपुर होस्टल' },
            { id: 4, name: 'भक्तपुर होस्टल' },
            { id: 5, name: 'बिराटनगर होस्टल' },
            { id: 6, name: 'बुटवल होस्टल' }
        ];

        const students = [
            { id: 1, name: 'राम बहादुर', hostel_id: 1, hostel_name: 'काठमाडौं होस्टल' },
            { id: 2, name: 'सीता महर्जन', hostel_id: 2, hostel_name: 'पोखरा होस्टल' },
            { id: 3, name: 'हरि प्रसाद', hostel_id: 3, hostel_name: 'ललितपुर होस्टल' },
            { id: 4, name: 'गीता शर्मा', hostel_id: 1, hostel_name: 'काठमाडौं होस्टल' },
            { id: 5, name: 'श्याम कुमार', hostel_id: 2, hostel_name: 'पोखरा होस्टल' },
            { id: 6, name: 'राधिका थापा', hostel_id: 3, hostel_name: 'ललितपुर होस्टल' },
            { id: 7, name: 'कृष्ण गुरुङ', hostel_id: 4, hostel_name: 'भक्तपुर होस्टल' },
            { id: 8, name: 'सरस्वती पाण्डे', hostel_id: 5, hostel_name: 'बिराटनगर होस्टल' },
            { id: 9, name: 'विजय राज', hostel_id: 6, hostel_name: 'बुटवल होस्टल' }
        ];

        function mealManagement() {
            return {
                userDropdownOpen: false,
                selectedHostel: null,
                selectedStudent: null,
                hostelSearch: '',
                studentSearch: '',
                
                // Filter hostels based on user role
                get availableHostels() {
                    if (userRole === 'super_admin') {
                        return hostels;
                    } else if (userRole === 'hostel_owner') {
                        return hostels.filter(hostel => hostel.id === userHostelId);
                    }
                    return [];
                },
                
                // Get filtered hostels based on search
                get filteredHostels() {
                    if (this.hostelSearch === '') {
                        return this.availableHostels;
                    }
                    return this.availableHostels.filter(hostel => 
                        hostel.name.toLowerCase().includes(this.hostelSearch.toLowerCase())
                    );
                },
                
                // Get students based on selected hostel
                get availableStudents() {
                    if (!this.selectedHostel) {
                        if (userRole === 'super_admin') {
                            return students;
                        } else if (userRole === 'hostel_owner') {
                            return students.filter(student => student.hostel_id === userHostelId);
                        }
                        return [];
                    }
                    
                    return students.filter(student => student.hostel_id === this.selectedHostel.id);
                },
                
                // Get filtered students based on search
                get filteredStudents() {
                    if (this.studentSearch === '') {
                        return this.availableStudents;
                    }
                    return this.availableStudents.filter(student => 
                        student.name.toLowerCase().includes(this.studentSearch.toLowerCase()) ||
                        student.hostel_name.toLowerCase().includes(this.studentSearch.toLowerCase())
                    );
                },
                
                // Select a hostel
                selectHostel(hostel) {
                    this.selectedHostel = hostel;
                    this.selectedStudent = null; // Reset student selection when hostel changes
                    this.studentSearch = ''; // Reset student search
                },
                
                // Select a student
                selectStudent(student) {
                    this.selectedStudent = student;
                }
            };
        }

        // Simple JavaScript for interactive elements
        document.addEventListener('DOMContentLoaded', function() {
            // Date picker enhancement
            const dateInputs = document.querySelectorAll('input[type="date"]');
            dateInputs.forEach(input => {
                input.addEventListener('focus', function() {
                    this.showPicker();
                });
            });
            
            // Form validation
            const forms = document.querySelectorAll('form');
            forms.forEach(form => {
                form.addEventListener('submit', function(e) {
                    e.preventDefault();
                    
                    // Simple validation
                    let isValid = true;
                    const requiredFields = form.querySelectorAll('[required]');
                    
                    requiredFields.forEach(field => {
                        if (!field.value.trim()) {
                            isValid = false;
                            field.classList.add('border-red-500');
                        } else {
                            field.classList.remove('border-red-500');
                        }
                    });
                    
                    if (isValid) {
                        // Show success message
                        alert('फर्म सफलतापूर्वक पेश गरियो!');
                        form.reset();
                    }
                });
            });
        });
    </script>
</body>
</html>