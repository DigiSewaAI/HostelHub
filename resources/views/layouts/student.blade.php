<!DOCTYPE html>
<html lang='en'>
<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <title>HostelHub - Student Dashboard</title>
    <!-- CSS -->
    <link href='{{ asset('css/app.css') }}' rel='stylesheet'>
</head>
<body class='font-sans antialiased'>
    <div class='min-h-screen bg-gray-100'>
        <nav class='bg-white shadow'>
            <div class='max-w-7xl mx-auto px-4 sm:px-6 lg:px-8'>
                <div class='flex justify-between h-16'>
                    <div class='flex'>
                        <div class='flex-shrink-0 flex items-center'>
                            <h1 class='text-xl font-bold'>HostelHub</h1>
                        </div>
                        <div class='hidden sm:ml-6 sm:flex sm:space-x-8'>
                            <a href='{{ route('student.dashboard') }}' class='border-indigo-500 text-gray-900 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium'>ड्यासबोर्ड</a>
                            <a href='{{ route('student.profile') }}' class='border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium'>प्रोफाइल</a>
                            <a href='{{ route('student.payments') }}' class='border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium'>भुक्तानी</a>
                            <a href='{{ route('student.meal-menus.index') }}' class='border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium'>खानाको योजना</a>
                        </div>
                    </div>
                    <div class='hidden sm:ml-6 sm:flex sm:items-center'>
                        <span class='mr-4'>{{ auth()->user()->name }}</span>
                        <form action='{{ route('logout') }}' method='POST'>
                            @csrf
                            <button type='submit' class='text-red-500'>लग आउट</button>
                        </form>
                    </div>
                </div>
            </div>
        </nav>

        <div class='py-6'>
            <div class='max-w-7xl mx-auto sm:px-6 lg:px-8'>
                @if(session('success'))
                    <div class='bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4'>
                        {{ session('success') }}
                    </div>
                @endif
                
                @yield('content')
            </div>
        </div>
    </div>
    
    <!-- JavaScript -->
    <script src='{{ asset('js/app.js') }}'></script>
</body>
</html>