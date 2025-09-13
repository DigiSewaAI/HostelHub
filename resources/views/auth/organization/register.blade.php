<!DOCTYPE html>
<html lang="ne">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HostelHub - संगठन दर्ता गर्नुहोस्</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
        .error-message {
            color: #e53e3e;
            font-size: 0.875rem;
            margin-top: 0.25rem;
        }
    </style>
</head>
<body class="bg-gray-50">
    <!-- Header Section -->
    <header class="bg-white shadow-md">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-20">
                <!-- Logo -->
                <div class="flex-shrink-0 flex items-center">
                    <a href="{{ route('home') }}" class="text-2xl font-bold text-indigo-600">HostelHub</a>
                </div>
                <!-- Navigation -->
                <nav class="hidden md:flex gap-2">
                    <a href="{{ route('home') }}" class="px-4 py-2 rounded-lg text-gray-700 hover:bg-indigo-100">होम</a>
                    <a href="{{ route('features') }}" class="px-4 py-2 rounded-lg text-gray-700 hover:bg-indigo-100">सुविधाहरू</a>
                    <a href="{{ route('how-it-works') }}" class="px-4 py-2 rounded-lg text-gray-700 hover:bg-indigo-100">कसरी काम गर्छ</a>
                    <a href="{{ route('pricing') }}" class="px-4 py-2 rounded-lg text-gray-700 hover:bg-indigo-100">मूल्य</a>
                </nav>
                <!-- Login Button -->
                <div class="hidden md:flex items-center gap-3">
                    <a href="{{ route('login') }}" class="px-4 py-2 rounded-lg text-indigo-600 border border-indigo-600 hover:bg-indigo-50">लगइन</a>
                </div>
            </div>
        </div>
    </header>

    <!-- Registration Form -->
    <section class="py-16 bg-gray-50">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white rounded-xl shadow-lg p-8">
                <div class="text-center mb-8">
                    <h1 class="text-3xl font-bold text-gray-900 mb-2">होस्टल संचालकको लागि साइन अप गर्नुहोस्</h1>
                    <p class="text-gray-600">हाम्रो {{ $plan->name ?? 'योजना' }} सुरु गर्नुहोस्</p>
                    <div class="mt-4 bg-indigo-50 text-indigo-700 px-4 py-2 rounded-full inline-block">
                        ७ दिन निःशुल्क परीक्षण | कुनै पनि क्रेडिट कार्ड आवश्यक छैन
                    </div>
                </div>
                
                <!-- Validation Errors -->
                @if ($errors->any())
                    <div class="mb-6 bg-red-50 text-red-700 p-4 rounded-lg">
                        <strong class="font-medium">सावधान!</strong>
                        <ul class="mt-1 list-disc list-inside">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                
                <form method="POST" action="{{ route('register.organization.store') }}" autocomplete="off">
                    @csrf
                    
                    <!-- Hidden fields to prevent autofill -->
                    <input type="text" name="fakeusernameremembered" autocomplete="username" style="display:none">
                    <input type="password" name="fakepasswordremembered" autocomplete="new-password" style="display:none">
                    
                    <!-- Use plan slug instead of ID -->
                    <input type="hidden" name="plan_slug" value="{{ $plan->slug ?? '' }}">
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div>
                            <label for="organization_name" class="block text-sm font-medium text-gray-700 mb-1">होस्टल/संगठनको नाम</label>
                            <input type="text" id="organization_name" name="organization_name" required
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                                value="{{ old('organization_name') }}">
                            @error('organization_name')
                                <p class="error-message">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="owner_name" class="block text-sm font-medium text-gray-700 mb-1">प्रबन्धकको नाम</label>
                            <input type="text" id="owner_name" name="owner_name" required
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                                value="{{ old('owner_name') }}">
                            @error('owner_name')
                                <p class="error-message">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="mb-6">
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-1">इमेल ठेगाना</label>
                        <input type="email" id="email" name="email" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                            value="{{ old('email') }}">
                        @error('email')
                            <p class="error-message">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div>
                            <label for="password" class="block text-sm font-medium text-gray-700 mb-1">पासवर्ड</label>
                            <input type="password" id="password" name="password" required autocomplete="new-password"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                            @error('password')
                                <p class="error-message">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">पासवर्ड पुष्टि गर्नुहोस्</label>
                            <input type="password" id="password_confirmation" name="password_confirmation" required autocomplete="new-password"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                        </div>
                    </div>
                    
                    <div class="mb-8 p-4 bg-indigo-50 rounded-lg">
                        <h3 class="font-bold text-indigo-800 mb-2">{{ $plan->name ?? 'योजना' }}</h3>
                        <p class="text-indigo-700 mb-3">मूल्य: रु. {{ number_format($plan->price_month ?? 0) }}/महिना</p>
                        
                        <!-- योजनाका विशेषताहरू सही रूपमा देखाउने -->
                        <h4 class="font-semibold text-indigo-800 mb-2">विशेषताहरू:</h4>
                        <ul class="list-disc pl-5 space-y-1 text-indigo-700">
                            @if($plan)
                                @php
                                    $features = [];
                                    if(is_string($plan->features)) {
                                        $features = json_decode($plan->features, true) ?? [];
                                    } elseif(is_array($plan->features)) {
                                        $features = $plan->features;
                                    }
                                @endphp
                                
                                @if(count($features) > 0)
                                    @foreach($features as $feature)
                                        <li>{{ $feature }}</li>
                                    @endforeach
                                @else
                                    <li>विशेषताहरू उपलब्ध छैनन्</li>
                                @endif
                            @else
                                <li>योजना विवरण उपलब्ध छैन</li>
                            @endif
                        </ul>
                    </div>
                    
                    <button type="submit" class="w-full bg-indigo-600 text-white py-3 px-6 rounded-lg font-medium hover:bg-indigo-700 transition-colors shadow-md">
                        {{ $plan->name ?? 'योजना' }} सुरु गर्नुहोस्
                    </button>
                </form>
                
                <div class="mt-6 text-center">
                    <p class="text-gray-600">पहिले नै खाता छ? <a href="{{ route('login') }}" class="text-indigo-600 hover:underline font-medium">लगइन गर्नुहोस्</a></p>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer Section -->
    <footer class="bg-indigo-900 text-white py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <p class="text-indigo-200">© 2025 HostelHub. सबै अधिकार सुरक्षित।</p>
            </div>
        </div>
    </footer>
</body>
</html>