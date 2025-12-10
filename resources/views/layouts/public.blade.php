<!DOCTYPE html>
<html lang="ne">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('page-title', 'HostelHub')</title>
    <meta name="description" content="@yield('page-description', 'HostelHub - होस्टल प्रबन्धन प्रणाली')">
    
    <!-- Theme variable -->
    @stack('head')
    
    <!-- Fonts & Icons -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Devanagari:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Vite Assets -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- Dynamic Theme CSS Loading -->
    @if(isset($theme) && $theme)
        <link rel="stylesheet" href="{{ asset('css/themes/' . $theme . '.css') }}">
    @endif
    
    <!-- Global Styles -->
    <style>
        :root {
            --primary-color: {{ $hostel->theme_color ?? '#3b82f6' }};
            --header-height: 80px;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Inter', sans-serif;
            color: #1f2937;
            background-color: #ffffff;
            line-height: 1.6;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        
        .nepali {
            font-family: 'Noto Sans Devanagari', 'Inter', sans-serif;
        }
        
        /* Container */
        .container {
            width: 100%;
            max-width: 1280px;
            margin: 0 auto;
            padding: 0 1.5rem;
        }
        
        /* Main Content */
        main {
            flex: 1;
            width: 100%;
        }
        
        /* Utility Classes */
        .smooth-transition {
            transition: all 0.3s ease-in-out;
        }
        
        /* Theme Color Classes */
        .theme-bg { background-color: var(--primary-color); }
        .theme-text { color: var(--primary-color); }
        
        /* Responsive */
        @media (max-width: 768px) {
            .container {
                padding: 0 1rem;
            }
        }
    </style>
    
    @stack('styles')
</head>
<body class="@if(isset($theme)){{ $theme }}-theme @endif">
    <!-- Skip Link -->
    <a href="#main-content" class="skip-link nepali" style="position:absolute;top:-40px;left:10px;background:var(--primary-color);color:white;padding:8px 16px;border-radius:4px;text-decoration:none;z-index:9999;">सामग्रीमा जानुहोस्</a>
    
    <!-- Global Header -->
    @if(isset($hostel) && ($hostel->show_hostelhub_branding ?? true))
        @include('components.header')
    @endif
    
    <!-- Main Content Area -->
    <main id="main-content" style="padding-top: 80px;">
        @yield('content')
    </main>
    
    <!-- Global Footer -->
    @if(isset($hostel) && ($hostel->show_hostelhub_branding ?? true))
        @include('components.footer')
    @endif
    
    @stack('scripts')
</body>
</html>