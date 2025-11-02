<!doctype html>
<html lang="ne">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>@yield('page-title', $hostel->name ?? 'HostelHub')</title>
  <meta name="description" content="@yield('page-description', Str::limit($hostel->description ?? 'HostelHub', 160))">
  
  <!-- Theme variable FIRST before any CSS -->
  @stack('head')
  
  <!-- ✅ UPDATED: Vite CSS/JS loading -->
  @vite(['resources/css/hostelhub.css', 'resources/js/app.js'])
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
  
  <style>
    :root {
      --theme-color: {{ $hostel->theme_color ?? '#16a34a' }};
    }
    
    body {
      font-family: 'Inter', sans-serif;
    }
    
    .theme-bg { background-color: var(--theme-color); }
    .theme-text { color: var(--theme-color); }
    
    /* Smooth animations */
    .smooth-transition {
      transition: all 0.3s ease;
    }

    /* Nepali font support */
    .nepali {
      font-family: 'Inter', 'Helvetica Neue', Arial, sans-serif;
      line-height: 1.6;
    }

    /* Ensure proper rendering for Nepali text */
    .nepali-text {
      font-feature-settings: "locl";
      font-language-override: "NEP";
    }

    /* Custom scrollbar for better UX */
    ::-webkit-scrollbar {
      width: 8px;
    }

    ::-webkit-scrollbar-track {
      background: #f1f1f1;
    }

    ::-webkit-scrollbar-thumb {
      background: #c1c1c1;
      border-radius: 4px;
    }

    ::-webkit-scrollbar-thumb:hover {
      background: #a8a8a8;
    }

    /* Loading states */
    .loading {
      opacity: 0.7;
      pointer-events: none;
    }

    /* Focus styles for accessibility */
    .focus-visible:focus {
      outline: 2px solid var(--theme-color);
      outline-offset: 2px;
    }

    /* Print styles */
    @media print {
      .no-print {
        display: none !important;
      }
      
      .break-inside-avoid {
        break-inside: avoid;
      }
    }

    /* High contrast mode support */
    @media (prefers-contrast: high) {
      .bg-gray-50 {
        background-color: white;
      }
      
      .text-gray-600 {
        color: black;
      }
    }

    /* Reduced motion support */
    @media (prefers-reduced-motion: reduce) {
      .smooth-transition {
        transition: none;
      }
    }

    /* 🚨 FIX: Contact Card Overlap & Layout Issues */
    .third-column { 
        z-index: 10; 
        position: relative; 
    }

    .gallery-vertical-container { 
        z-index: 5; 
        position: relative; 
    }

    .sidebar-card.sticky { 
        z-index: 15; 
        position: sticky; 
        top: 1rem; 
    }

    /* Layout Fix */
    @media (min-width: 1024px) {
        .third-column { 
            margin-left: 0 !important; 
        }
        
        .gallery-vertical-container { 
            margin-right: 0 !important; 
        }
    }

    /* 🚨 FIX: Floating actions styling */
    .floating-actions {
        position: fixed;
        bottom: 20px;
        right: 20px;
        z-index: 1000;
        display: flex;
        flex-direction: column;
        gap: 8px;
    }

    .floating-btn {
        width: 45px;
        height: 45px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 16px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
        transition: all 0.3s ease;
        text-decoration: none;
    }

    .floating-btn.whatsapp {
        background: #25d366;
    }

    .floating-btn:hover {
        transform: scale(1.1);
    }
  </style>
</head>
<body class="bg-gray-50 text-gray-800">
  <!-- ✅ UPDATED: Conditional Header Inclusion -->
  @if(isset($hostel) && $hostel->show_hostelhub_branding)
    <!-- Clean Header -->
    <header class="bg-white shadow-sm border-b sticky top-0 z-50 no-print">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16">
          <!-- Logo - FIXED SIZE -->
          <a href="{{ url('/') }}" class="flex items-center space-x-3 focus-visible">
            <div class="w-10 h-10 bg-blue-600 rounded-lg flex items-center justify-center smooth-transition hover:bg-blue-700">
              <i class="fas fa-building text-white text-lg"></i>
            </div>
            <span class="text-xl font-bold text-gray-800 smooth-transition hover:text-blue-600">HostelHub</span>
          </a>
          
          <!-- Navigation -->
          <nav class="hidden md:flex items-center space-x-8">
            <a href="{{ route('hostels.index') }}" 
               class="text-gray-600 hover:text-gray-900 smooth-transition text-sm font-medium nepali focus-visible px-3 py-2 rounded-lg hover:bg-gray-100">
              होस्टलहरू खोज्नुहोस्
            </a>
            <a href="{{ url('/contact') }}" 
               class="text-gray-600 hover:text-gray-900 smooth-transition text-sm font-medium nepali focus-visible px-3 py-2 rounded-lg hover:bg-gray-100">
              सम्पर्क गर्नुहोस्
            </a>
          </nav>

          @if(isset($preview) && $preview)
            <div class="flex items-center space-x-4">
              <div class="bg-yellow-100 border border-yellow-300 px-3 py-1 rounded-full">
                <span class="text-yellow-800 text-sm font-medium nepali">पूर्वावलोकन</span>
              </div>
            </div>
          @endif

          <!-- Mobile menu button -->
          <button class="md:hidden p-2 rounded-lg hover:bg-gray-100 smooth-transition focus-visible" 
                  onclick="toggleMobileMenu()">
            <i class="fas fa-bars text-gray-600"></i>
          </button>
        </div>

        <!-- Mobile Navigation -->
        <div id="mobileMenu" class="md:hidden hidden py-4 border-t">
          <div class="flex flex-col space-y-3">
            <a href="{{ route('hostels.index') }}" 
               class="text-gray-600 hover:text-gray-900 smooth-transition text-sm font-medium nepali focus-visible px-3 py-2 rounded-lg hover:bg-gray-100">
              होस्टलहरू खोज्नुहोस्
            </a>
            <a href="{{ url('/contact') }}" 
               class="text-gray-600 hover:text-gray-900 smooth-transition text-sm font-medium nepali focus-visible px-3 py-2 rounded-lg hover:bg-gray-100">
              सम्पर्क गर्नुहोस्
            </a>
          </div>
        </div>
      </div>
    </header>
  @endif

  <main class="min-h-screen">
    @yield('content')
  </main>

  <!-- ✅ UPDATED: Conditional Footer Inclusion -->
  @if(isset($hostel) && $hostel->show_hostelhub_branding)
    <!-- Clean Footer -->
    <footer class="bg-gray-800 text-white mt-20 no-print">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
          <!-- Brand -->
          <div class="space-y-4">
            <div class="flex items-center space-x-3">
              <div class="w-10 h-10 bg-blue-600 rounded-lg flex items-center justify-center">
                <i class="fas fa-building text-white"></i>
              </div>
              <span class="text-xl font-bold">HostelHub</span>
            </div>
            <p class="text-gray-300 text-sm nepali leading-relaxed">
              नेपालका विश्वसनीय होस्टलहरूको लागि आफ्नो विश्वसनीय साथी
            </p>
          </div>
          
          <!-- Quick Links -->
          <div class="space-y-4">
            <h3 class="font-semibold text-lg nepali">द्रुत लिंकहरू</h3>
            <div class="space-y-2">
              <a href="{{ route('hostels.index') }}" 
                 class="block text-gray-300 hover:text-white smooth-transition text-sm nepali focus-visible rounded px-2 py-1">
                सबै होस्टलहरू
              </a>
              <a href="{{ url('/contact') }}" 
                 class="block text-gray-300 hover:text-white smooth-transition text-sm nepali focus-visible rounded px-2 py-1">
                सम्पर्क गर्नुहोस्
              </a>
              <a href="{{ url('/about') }}" 
                 class="block text-gray-300 hover:text-white smooth-transition text-sm nepali focus-visible rounded px-2 py-1">
                हाम्रो बारेमा
              </a>
            </div>
          </div>
          
          <!-- Contact -->
          <div class="space-y-4">
            <h3 class="font-semibold text-lg nepali">सम्पर्क जानकारी</h3>
            <div class="space-y-2 text-sm text-gray-300">
              <div class="flex items-center space-x-2">
                <i class="fas fa-envelope"></i>
                <span>info@hostelhub.com</span>
              </div>
              <div class="flex items-center space-x-2">
                <i class="fas fa-phone"></i>
                <span>+९७७-९८०XXXXXXX</span>
              </div>
              <div class="flex items-center space-x-2">
                <i class="fas fa-map-marker-alt"></i>
                <span class="nepali">काठमाडौं, नेपाल</span>
              </div>
            </div>
          </div>
          
          <!-- Social -->
          <div class="space-y-4">
            <h3 class="font-semibold text-lg nepali">सामाजिक संजाल</h3>
            <div class="flex space-x-4">
              <a href="#" class="w-8 h-8 bg-gray-700 rounded flex items-center justify-center smooth-transition hover:bg-blue-600 focus-visible">
                <i class="fab fa-facebook-f text-sm"></i>
              </a>
              <a href="#" class="w-8 h-8 bg-gray-700 rounded flex items-center justify-center smooth-transition hover:bg-pink-600 focus-visible">
                <i class="fab fa-instagram text-sm"></i>
              </a>
              <a href="#" class="w-8 h-8 bg-gray-700 rounded flex items-center justify-center smooth-transition hover:bg-blue-400 focus-visible">
                <i class="fab fa-twitter text-sm"></i>
              </a>
            </div>
          </div>
        </div>
        
        <div class="border-t border-gray-700 mt-8 pt-8 text-center">
          <p class="text-gray-400 text-sm nepali">
            © {{ date('Y') }} HostelHub. सबै अधिकार सुरक्षित।
          </p>
        </div>
      </div>
    </footer>
  @endif

  <!-- ✅ UPDATED: Conditional Phone Icon - Only show if not modern OR dark theme -->
  @if(isset($hostel) && $hostel->contact_phone)
    @if(!isset($hostel->theme) || ($hostel->theme !== 'modern' && $hostel->theme !== 'dark'))
    <div class="fixed bottom-6 right-6 z-50 no-print">
      <a href="tel:{{ $hostel->contact_phone }}" 
         class="w-14 h-14 bg-green-600 text-white rounded-full flex items-center justify-center shadow-lg hover:shadow-xl smooth-transition hover:bg-green-700 focus-visible"
         aria-label="फोन गर्नुहोस्">
        <i class="fas fa-phone"></i>
      </a>
    </div>
    @endif
  @endif

  <!-- Simple JavaScript for Mobile Menu -->
  <script>
    function toggleMobileMenu() {
      const menu = document.getElementById('mobileMenu');
      menu.classList.toggle('hidden');
    }

    // Close mobile menu when clicking outside
    document.addEventListener('click', function(event) {
      const menu = document.getElementById('mobileMenu');
      const button = document.querySelector('button[onclick="toggleMobileMenu()"]');
      
      if (menu && button) {
        if (!menu.contains(event.target) && !button.contains(event.target)) {
          menu.classList.add('hidden');
        }
      }
    });

    // Add loading state to all links
    document.addEventListener('DOMContentLoaded', function() {
      const links = document.querySelectorAll('a');
      links.forEach(link => {
        link.addEventListener('click', function(e) {
          if (this.getAttribute('href') && !this.getAttribute('href').startsWith('#')) {
            this.classList.add('loading');
          }
        });
      });
    });

    // Handle theme color changes
    document.addEventListener('DOMContentLoaded', function() {
      const themeColor = '{{ $hostel->theme_color ?? '#16a34a' }}';
      if (themeColor) {
        // Update meta theme color for mobile browsers
        const metaThemeColor = document.querySelector('meta[name="theme-color"]');
        if (metaThemeColor) {
          metaThemeColor.setAttribute('content', themeColor);
        } else {
          const meta = document.createElement('meta');
          meta.name = 'theme-color';
          meta.content = themeColor;
          document.head.appendChild(meta);
        }
      }
    });

    // 🚨 FIX: Handle logo loading errors
    document.addEventListener('DOMContentLoaded', function() {
      const hostelLogo = document.querySelector('img[src*="hostel_logos"]');
      if (hostelLogo) {
        hostelLogo.addEventListener('error', function() {
          console.log('Logo failed to load:', this.src);
          // You can set a fallback logo here if needed
          // this.src = '/images/default-logo.png';
        });
      }
    });
  </script>
</body>
</html>