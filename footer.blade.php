<footer class="bg-white text-gray-800 py-8 border-t border-gray-200 shadow-md">
    <div class="container mx-auto px-4">
        <div class="flex flex-col md:flex-row justify-between">
            <!-- Logo and description -->
            <div class="mb-6 md:mb-0">
                <img src="{{ asset('storage/images/logo.png') }}" alt="HostelHub Logo" class="w-32 h-10 object-contain mb-4">
                <p class="max-w-xs text-gray-600">नेपालको नम्बर १ होस्टल प्रबन्धन प्रणाली। हामी होस्टल व्यवस्थापनलाई सहज, दक्ष र विश्वसनीय बनाउँछौं।</p>
            </div>

            <!-- Quick Links -->
            <div class="mb-6 md:mb-0">
                <h3 class="text-lg font-semibold mb-4 text-gray-800">तिब्र लिङ्कहरू</h3>
                <ul class="space-y-2">
                    <li><a href="/" class="text-gray-600 hover:text-indigo-600">होम</a></li>
                    <li><a href="{{ route('features') }}" class="text-gray-600 hover:text-indigo-600">सुविधाहरू</a></li>
                    <li><a href="{{ route('how-it-works') }}" class="text-gray-600 hover:text-indigo-600">कसरी काम गर्छ</a></li>
                    <li><a href="{{ route('gallery.public') }}" class="text-gray-600 hover:text-indigo-600">ग्यालरी</a></li>
                    <li><a href="{{ route('pricing') }}" class="text-gray-600 hover:text-indigo-600">मूल्य</a></li>
                    <li><a href="{{ route('reviews') }}" class="text-gray-600 hover:text-indigo-600">समीक्षाहरू</a></li>
                </ul>
            </div>

            <!-- Contact Info -->
            <div class="mb-6 md:mb-0">
                <h3 class="text-lg font-semibold mb-4 text-gray-800">सम्पर्क जानकारी</h3>
                <p class="text-gray-600">कमलपोखरी, काठमाडौं, नेपाल</p>
                <p class="text-gray-600">+९७७ ९८०१२३४५६७</p>
                <p class="text-gray-600">info@hostelhub.com</p>
                <p class="text-gray-600">सोम-शुक्र: ९:०० बिहान - ५:०० बेलुका</p>
            </div>

            <!-- Newsletter -->
            <div>
                <h3 class="text-lg font-semibold mb-4 text-gray-800">समाचारपत्र</h3>
                <p class="mb-2 text-gray-600">हाम्रो नवीनतम अपडेटहरू प्राप्त गर्न तपाईंको इमेल दर्ता गर्नुहोस्</p>
                <form>
                    <input type="email" placeholder="तपाईंको इमेल" class="px-4 py-2 rounded-lg text-gray-800 w-full mb-2 border border-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors w-full">दर्ता गर्नुहोस्</button>
                </form>
            </div>
        </div>

        <!-- Bottom Copyright -->
        <div class="border-t border-gray-200 mt-8 pt-6 text-center md:text-left">
            <p class="text-gray-500">© 2025 HostelHub. सबै अधिकार सुरक्षित।</p>
            <div class="flex justify-center md:justify-start space-x-4 mt-2">
                <a href="{{ route('privacy') }}" class="text-gray-500 hover:text-indigo-600">गोपनीयता नीति</a>
                <a href="{{ route('terms') }}" class="text-gray-500 hover:text-indigo-600">सेवा सर्तहरू</a>
            </div>
            <p class="mt-2 text-gray-400 text-sm">संस्करण: 1.0.0</p>
        </div>
    </div>
</footer>