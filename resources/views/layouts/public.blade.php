<!doctype html>
<html lang="ne">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>@yield('page-title', $hostel->name ?? 'HostelHub')</title>
  <meta name="description" content="@yield('page-description', Str::limit($hostel->description ?? 'HostelHub', 160))">
  
  @vite(['resources/css/app.css','resources/js/app.js'])
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
  </style>
</head>
<body class="bg-gray-50 text-gray-800">
  <!-- Clean Header -->
  <header class="bg-white shadow-sm border-b sticky top-0 z-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="flex items-center justify-between h-16">
        <!-- Logo - FIXED SIZE -->
        <a href="{{ url('/') }}" class="flex items-center space-x-3">
          <div class="w-10 h-10 bg-blue-600 rounded-lg flex items-center justify-center">
            <i class="fas fa-building text-white text-lg"></i>
          </div>
          <span class="text-xl font-bold text-gray-800">HostelHub</span>
        </a>
        
        <!-- Navigation -->
        <nav class="hidden md:flex items-center space-x-8">
          <a href="{{ route('hostels.index') }}" class="text-gray-600 hover:text-gray-900 smooth-transition text-sm font-medium nepali">
            होस्टलहरू खोज्नुहोस्
          </a>
          <a href="{{ url('/contact') }}" class="text-gray-600 hover:text-gray-900 smooth-transition text-sm font-medium nepali">
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
      </div>
    </div>
  </header>

  <main class="min-h-screen">
    @yield('content')
  </main>

  <!-- Clean Footer -->
  <footer class="bg-gray-800 text-white mt-20">
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
            <a href="{{ route('hostels.index') }}" class="block text-gray-300 hover:text-white smooth-transition text-sm nepali">
              सबै होस्टलहरू
            </a>
            <a href="{{ url('/contact') }}" class="block text-gray-300 hover:text-white smooth-transition text-sm nepali">
              सम्पर्क गर्नुहोस्
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
          </div>
        </div>
        
        <!-- Social -->
        <div class="space-y-4">
          <h3 class="font-semibold text-lg nepali">सामाजिक संजाल</h3>
          <div class="flex space-x-4">
            <div class="w-8 h-8 bg-gray-700 rounded flex items-center justify-center">
              <i class="fab fa-facebook-f text-sm"></i>
            </div>
            <div class="w-8 h-8 bg-gray-700 rounded flex items-center justify-center">
              <i class="fab fa-instagram text-sm"></i>
            </div>
          </div>
        </div>
      </div>
      
      <div class="border-t border-gray-700 mt-8 pt-8 text-center">
        <p class="text-gray-400 text-sm">
          © {{ date('Y') }} HostelHub. सबै अधिकार सुरक्षित।
        </p>
      </div>
    </div>
  </footer>

  <!-- Simple Floating Button -->
  @if($hostel->contact_phone)
    <div class="fixed bottom-6 right-6 z-50">
      <a href="tel:{{ $hostel->contact_phone }}" 
         class="w-14 h-14 bg-green-600 text-white rounded-full flex items-center justify-center shadow-lg hover:shadow-xl smooth-transition">
        <i class="fas fa-phone"></i>
      </a>
    </div>
  @endif
</body>
</html>