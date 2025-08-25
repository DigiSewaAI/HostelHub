@extends('layouts.app')
@section('title', 'डेमो - HostelHub')
@section('content')
<div class="min-h-screen bg-gray-50 pt-20">
    <div class="container mx-auto px-4 py-8">
        <div class="max-w-4xl mx-auto bg-white rounded-xl shadow-sm p-6 md:p-8">
            <h1 class="text-3xl font-bold text-gray-800 mb-6">हाम्रो HostelHub को डेमो हेर्नुहोस्</h1>
            
            <div class="aspect-w-16 aspect-h-9 mb-8">
                <iframe 
                    src="https://www.youtube.com/embed/YOUR_VIDEO_ID" 
                    frameborder="0" 
                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
                    allowfullscreen
                    class="w-full h-96 rounded-xl shadow-lg">
                </iframe>
            </div>

            <p class="text-gray-600 mb-6">
                यो भिडियोमा हामीले HostelHub को प्रमुख सुविधाहरू, प्रयोगकर्ता अनुभव, र व्यवस्थापन इन्टरफेस हेर्न सक्नुहुनेछ।
            </p>

            <div class="bg-blue-50 border-l-4 border-blue-500 p-4 mb-6">
                <p class="text-blue-800">
                    <strong>सुझाव:</strong> पूर्ण प्रभावको लागि HD मा हेर्नुहोस् र पूर्णस्क्रीन मोडमा स्विच गर्नुहोस्।
                </p>
            </div>

            <div class="flex flex-col sm:flex-row gap-4">
                <a href="{{ route('register') }}" class="bg-indigo-600 text-white text-center px-6 py-3 rounded-lg font-medium hover:bg-indigo-700 transition">
                    अहिले नै साइन अप गर्नुहोस्
                </a>
                <a href="{{ route('gallery.public') }}" class="bg-gray-600 text-white text-center px-6 py-3 rounded-lg font-medium hover:bg-gray-700 transition">
                    ग्यालरी हेर्नुहोस्
                </a>
            </div>

            <div class="mt-8">
                <a href="{{ url()->previous() }}" class="text-indigo-600 hover:underline">← पछाडि फर्कनुहोस्</a>
            </div>
        </div>
    </div>
</div>
@endsection