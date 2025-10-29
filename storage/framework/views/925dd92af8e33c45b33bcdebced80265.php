<!DOCTYPE html>
<html lang="ne">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>डेमो - HostelHub</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Noto+Sans+Devanagari:wght@400;500;600;700&display=swap');
        :root {
            --primary: #3730a3;
            --primary-dark: #312e81;
            --primary-light: #4f46e5;
            --secondary: #f97316;
            --dark: #1f2937;
            --light: #f8fafc;
        }
        body {
            font-family: 'Noto Sans Devanagari', sans-serif;
            background-color: #f8fafc;
            scroll-behavior: smooth;
        }
        .demo-hero {
            background: linear-gradient(to right, var(--primary-dark), var(--primary));
            color: white;
            position: relative;
            overflow: hidden;
        }
        .demo-hero::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100" width="100" height="100" opacity="0.05"><text x="50%" y="50%" font-size="16" text-anchor="middle" dominant-baseline="middle" fill="white">HostelHub</text></svg>');
            background-repeat: repeat;
        }
        .feature-card {
            transition: all 0.3s ease;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
            background: white;
            height: 100%;
        }
        .feature-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 12px 20px rgba(0, 0, 0, 0.15);
        }
        .video-container {
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }
        .step-card {
            transition: all 0.3s ease;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
            background: white;
            height: 100%;
        }
        .step-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 20px rgba(0, 0, 0, 0.15);
        }
        .cta-section {
            background: linear-gradient(to right, var(--primary-dark), var(--primary));
            border-radius: 16px;
            overflow: hidden;
            position: relative;
        }
        .cta-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100" width="100" height="100" opacity="0.05"><text x="50%" y="50%" font-size="16" text-anchor="middle" dominant-baseline="middle" fill="white">HostelHub</text></svg>');
            background-repeat: repeat;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-fade-in {
            animation: fadeIn 0.6s ease forwards;
        }
    </style>
</head>
<body class="bg-gray-50">
    <!-- Header -->
    <header class="demo-hero">
        <div class="container mx-auto px-4 py-8">
            <div class="text-center py-12">
                <h1 class="text-4xl md:text-5xl font-bold mb-4 animate-fade-in">HostelHub को डेमो हेर्नुहोस्</h1>
                <p class="text-xl max-w-3xl mx-auto mb-8 animate-fade-in" style="animation-delay: 0.2s;">हाम्रो प्रणालीको सबै सुविधाहरू निःशुल्क परीक्षण गर्नुहोस्, कुनै पनि बाध्यता बिना</p>
                <div class="flex flex-col sm:flex-row justify-center gap-4 animate-fade-in" style="animation-delay: 0.4s;">
                    <a href="<?php echo e(route('register')); ?>" class="px-8 py-3 bg-white text-indigo-900 font-medium rounded-lg hover:bg-gray-100 transition-colors duration-300 shadow-lg">
                        निःशुल्क साइन अप गर्नुहोस्
                    </a>
                    <a href="<?php echo e(url()->previous()); ?>" class="px-8 py-3 border border-white text-white font-medium rounded-lg hover:bg-white hover:text-indigo-900 transition-colors duration-300">
                        पछाडि फर्कनुहोस्
                    </a>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="container mx-auto px-4 py-12">
        <!-- Video Demo Section -->
        <div class="mb-16">
            <h2 class="text-3xl font-bold text-center text-gray-800 mb-4">हाम्रो प्रणालीको भिडियो डेमो</h2>
            <p class="text-gray-600 text-center max-w-3xl mx-auto mb-8">यो भिडियोमा हामीले HostelHub को प्रमुख सुविधाहरू, प्रयोगकर्ता अनुभव, र व्यवस्थापन इन्टरफेस हेर्न सक्नुहुनेछ</p>
            
            <div class="video-container max-w-4xl mx-auto animate-fade-in">
                <div class="aspect-w-16 aspect-h-9">
                    <iframe 
                        src="https://www.youtube.com/embed/dQw4w9WgXcQ" 
                        frameborder="0" 
                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
                        allowfullscreen
                        class="w-full h-96 rounded-xl">
                    </iframe>
                </div>
            </div>

            <div class="bg-blue-50 border-l-4 border-blue-500 p-4 mt-6 max-w-4xl mx-auto rounded-lg">
                <p class="text-blue-800">
                    <strong>सुझाव:</strong> पूर्ण प्रभावको लागि HD मा हेर्नुहोस् र पूर्णस्क्रीन मोडमा स्विच गर्नुहोस्।
                </p>
            </div>
        </div>

        <!-- Key Features Section -->
        <div class="mb-16">
            <h2 class="text-3xl font-bold text-center text-gray-800 mb-4">प्रमुख सुविधाहरू</h2>
            <p class="text-gray-600 text-center max-w-3xl mx-auto mb-8">हाम्रो प्रणालीले प्रदान गर्ने विशेष सुविधाहरू जसले तपाईंको होस्टल व्यवस्थापनलाई सजिलो बनाउँछ</p>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="feature-card p-6 animate-fade-in" style="animation-delay: 0.1s;">
                    <div class="w-12 h-12 bg-indigo-100 rounded-lg flex items-center justify-center text-indigo-600 text-xl mb-4">
                        <i class="fas fa-users"></i>
                    </div>
                    <h3 class="font-bold text-lg mb-2">विद्यार्थी व्यवस्थापन</h3>
                    <p class="text-gray-600">सबै विद्यार्थी विवरण एउटै ठाउँमा प्रबन्धन गर्नुहोस्, अध्ययन स्थिति, सम्पर्क जानकारी र भुक्तानी इतिहास</p>
                </div>
                
                <div class="feature-card p-6 animate-fade-in" style="animation-delay: 0.2s;">
                    <div class="w-12 h-12 bg-indigo-100 rounded-lg flex items-center justify-center text-indigo-600 text-xl mb-4">
                        <i class="fas fa-bed"></i>
                    </div>
                    <h3 class="font-bold text-lg mb-2">कोठा उपलब्धता</h3>
                    <p class="text-gray-600">रियल-टाइम कोठा उपलब्धता देख्नुहोस्, आवंटन गर्नुहोस् र बुकिंग प्रबन्धन गर्नुहोस्</p>
                </div>
                
                <div class="feature-card p-6 animate-fade-in" style="animation-delay: 0.3s;">
                    <div class="w-12 h-12 bg-indigo-100 rounded-lg flex items-center justify-center text-indigo-600 text-xl mb-4">
                        <i class="fas fa-credit-card"></i>
                    </div>
                    <h3 class="font-bold text-lg mb-2">भुक्तानी प्रणाली</h3>
                    <p class="text-gray-600">स्वचालित भुक्तानी ट्र्याकिंग, बिल जनरेट गर्नुहोस्, रिमाइन्डर पठाउनुहोस् र वित्तीय विवरण हेर्नुहोस्</p>
                </div>
            </div>
        </div>

        <!-- How It Works Section -->
        <div class="mb-16">
            <h2 class="text-3xl font-bold text-center text-gray-800 mb-4">कसरी काम गर्छ</h2>
            <p class="text-gray-600 text-center max-w-3xl mx-auto mb-8">हाम्रो प्रणाली प्रयोग गर्ने सजिलो ३ चरणहरू</p>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="step-card p-6 text-center animate-fade-in" style="animation-delay: 0.1s;">
                    <div class="w-16 h-16 bg-indigo-100 rounded-full flex items-center justify-center text-indigo-600 text-2xl font-bold mx-auto mb-4">
                        1
                    </div>
                    <h3 class="font-bold text-lg mb-2">खाता सिर्जना गर्नुहोस्</h3>
                    <p class="text-gray-600">निःशुल्क खाताको लागि साइन अप गर्नुहोस् र आफ्नो होस्टल विवरणहरू थप्नुहोस्</p>
                </div>
                
                <div class="step-card p-6 text-center animate-fade-in" style="animation-delay: 0.2s;">
                    <div class="w-16 h-16 bg-indigo-100 rounded-full flex items-center justify-center text-indigo-600 text-2xl font-bold mx-auto mb-4">
                        2
                    </div>
                    <h3 class="font-bold text-lg mb-2">व्यवस्थापन सुरु गर्नुहोस्</h3>
                    <p class="text-gray-600">विद्यार्थीहरू थप्नुहोस्, कोठा आवंटन गर्नुहोस्, र भुक्तानीहरू ट्र्याक गर्नुहोस्</p>
                </div>
                
                <div class="step-card p-6 text-center animate-fade-in" style="animation-delay: 0.3s;">
                    <div class="w-16 h-16 bg-indigo-100 rounded-full flex items-center justify-center text-indigo-600 text-2xl font-bold mx-auto mb-4">
                        3
                    </div>
                    <h3 class="font-bold text-lg mb-2">विस्तार गर्नुहोस्</h3>
                    <p class="text-gray-600">हाम्रा उन्नत सुविधाहरू प्रयोग गरेर आफ्नो होस्टल व्यवसायलाई बढाउनुहोस्</p>
                </div>
            </div>
        </div>

        <!-- CTA Section -->
        <div class="cta-section p-10 text-center text-white mb-12">
            <h2 class="text-3xl font-bold mb-4 relative z-10">तपाईंको होस्टललाई HostelHub संग जोड्नुहोस्</h2>
            <p class="mb-6 max-w-2xl mx-auto relative z-10">७ दिन निःशुल्क परीक्षण गर्नुहोस् र होस्टल व्यवस्थापनलाई सजिलो, द्रुत र भरपर्दो बनाउनुहोस्</p>
            <div class="flex flex-col sm:flex-row justify-center gap-4 relative z-10">
                <a href="<?php echo e(route('register')); ?>" class="px-8 py-3 bg-white text-indigo-900 font-medium rounded-lg hover:bg-gray-100 transition-colors duration-300 shadow-lg">निःशुल्क साइन अप गर्नुहोस्</a>
                <a href="<?php echo e(route('gallery.public')); ?>" class="px-8 py-3 border border-white text-white font-medium rounded-lg hover:bg-white hover:text-indigo-900 transition-colors duration-300">ग्यालरी हेर्नुहोस्</a>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-gray-800 text-white py-8">
        <div class="container mx-auto px-4 text-center">
            <p>© 2025 HostelHub. सबै अधिकार सुरक्षित।</p>
            <div class="flex justify-center space-x-6 mt-4">
                <a href="#" class="hover:text-indigo-300">गोपनीयता नीति</a>
                <a href="#" class="hover:text-indigo-300">सेवा सर्तहरू</a>
                <a href="#" class="hover:text-indigo-300">सम्पर्क गर्नुहोस्</a>
            </div>
        </div>
    </footer>
</body>
</html><?php /**PATH D:\My Projects\HostelHub\resources\views\frontend\pages\demo.blade.php ENDPATH**/ ?>