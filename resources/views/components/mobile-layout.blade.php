@props(['role' => 'admin', 'title' => 'Dashboard'])

<div x-data="{ 
    mobileSidebarOpen: false,
    sidebarCollapsed: localStorage.getItem('sidebarCollapsed') === 'true',
    darkMode: localStorage.getItem('darkMode') === 'true'
}" 
:class="{ 'dark-mode': darkMode, 'sidebar-open': mobileSidebarOpen }"
class="min-h-screen bg-gray-50 font-sans">
    
    <!-- Skip Link -->
    <a href="#main-content" class="skip-link nepali sr-only focus:not-sr-only">
        मुख्य सामग्रीमा जानुहोस्
    </a>
    
    <!-- Mobile Sidebar Overlay -->
    <div x-show="mobileSidebarOpen" 
         @click="mobileSidebarOpen = false"
         x-transition:enter="transition-opacity ease-linear duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition-opacity ease-linear duration-300"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 bg-black bg-opacity-50 z-40 lg:hidden"
         aria-hidden="true">
    </div>
    
    <!-- Sidebar -->
    <aside id="sidebar"
           :class="{ 
               'translate-x-0': mobileSidebarOpen,
               '-translate-x-full lg:translate-x-0': !mobileSidebarOpen,
               'collapsed': sidebarCollapsed 
           }"
           class="sidebar fixed inset-y-0 left-0 z-50 w-64 bg-gradient-to-b from-blue-800 to-blue-900 text-white transition-all duration-300 ease-in-out transform lg:static lg:transform-none flex flex-col">
        
        <!-- Sidebar content will be injected -->
        {{ $sidebar }}
    </aside>
    
    <!-- Main Content Area -->
    <div class="flex-1 flex flex-col min-h-screen lg:ml-64 transition-all duration-300"
         :class="{ 'lg:ml-16': sidebarCollapsed }">
        
        <!-- Header -->
        <header class="bg-gradient-to-r from-blue-700 to-blue-800 shadow-sm fixed top-0 right-0 left-0 z-30 lg:static lg:shadow-none">
            <div class="flex items-center justify-between px-4 h-16">
                
                <!-- Mobile Menu Button -->
                <button @click="mobileSidebarOpen = !mobileSidebarOpen"
                        class="lg:hidden text-white p-2 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-white"
                        aria-label="मोबाइल साइडबार खोल्नुहोस्"
                        :aria-expanded="mobileSidebarOpen">
                    <i class="fas fa-bars text-xl" x-show="!mobileSidebarOpen"></i>
                    <i class="fas fa-times text-xl" x-show="mobileSidebarOpen"></i>
                </button>
                
                <!-- Logo/Brand -->
                <div class="flex-1 flex items-center justify-center lg:justify-start ml-2 lg:ml-0">
                    {{ $header }}
                </div>
                
                <!-- User Actions -->
                <div class="flex items-center space-x-2">
                    {{ $actions }}
                </div>
            </div>
        </header>
        
        <!-- Main Content -->
        <main id="main-content" 
              class="flex-1 pt-16 lg:pt-0 overflow-y-auto bg-gray-50 dark:bg-gray-800">
            <div class="p-4 md:p-6">
                <!-- Flash Messages -->
                @if(session('success'))
                    <div class="mb-4 p-4 bg-green-50 border border-green-200 text-green-700 rounded-lg flex items-center">
                        <i class="fas fa-check-circle mr-2"></i>
                        <span>{{ session('success') }}</span>
                    </div>
                @endif
                
                @if(session('error'))
                    <div class="mb-4 p-4 bg-red-50 border border-red-200 text-red-700 rounded-lg flex items-center">
                        <i class="fas fa-exclamation-circle mr-2"></i>
                        <span>{{ session('error') }}</span>
                    </div>
                @endif
                
                <!-- Page Content -->
                <div class="bg-white dark:bg-gray-700 rounded-xl shadow-sm p-4 md:p-6">
                    {{ $slot }}
                </div>
            </div>
        </main>
        
        <!-- Footer -->
        <footer class="bg-white dark:bg-gray-700 border-t border-gray-200 dark:border-gray-600 py-4 px-4 md:px-6">
            <div class="flex flex-col md:flex-row justify-between items-center text-sm text-gray-500 dark:text-gray-400">
                <p class="mb-2 md:mb-0">&copy; {{ date('Y') }} HostelHub. सबै अधिकार सुरक्षित।</p>
                <div class="flex space-x-4">
                    <a href="#" class="hover:text-gray-700 dark:hover:text-gray-300">गोपनीयता नीति</a>
                    <a href="#" class="hover:text-gray-700 dark:hover:text-gray-300">सेवा सर्तहरू</a>
                    <span>संस्करण: 1.0.0</span>
                </div>
            </div>
        </footer>
    </div>
</div>