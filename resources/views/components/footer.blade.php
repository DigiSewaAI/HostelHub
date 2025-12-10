<footer class="bg-indigo-900 text-white py-8">
    <div class="container mx-auto px-6"> {{-- Reduced side padding --}}
        <div class="footer-grid"> {{-- Changed to grid layout --}}
            <!-- Logo and description - ENLARGED LOGO -->
            <div class="footer-col">
                <img src="{{ asset('storage/images/logo.png') }}" alt="HostelHub Logo" class="footer-logo-large mb-4"> {{-- Added large logo class --}}
                <p class="nepali max-w-xs">नेपालको नम्बर १ होस्टल प्रबन्धन प्रणाली। हामी होस्टल व्यवस्थापनलाई सहज, दक्ष र विश्वसनीय बनाउँछौं।</p>
            </div>

            <!-- Quick Links -->
            <div class="footer-col">
                <h3 class="text-lg font-semibold mb-4 nepali">तिब्र लिङ्कहरू</h3>
                <ul class="space-y-2">
                    <li><a href="/" class="hover:text-blue-200 nepali">होम</a></li>
                    <li><a href="{{ route('features') }}" class="hover:text-blue-200 nepali">सुविधाहरू</a></li>
                    <li><a href="{{ route('how-it-works') }}" class="hover:text-blue-200 nepali">कसरी काम गर्छ</a></li>
                    <li><a href="{{ route('gallery.index') }}" class="hover:text-blue-200 nepali">ग्यालरी</a></li>
                    <li><a href="{{ route('pricing') }}" class="hover:text-blue-200 nepali">मूल्य</a></li>
                    <li><a href="{{ route('testimonials') }}" class="hover:text-blue-200 nepali">प्रशंसापत्रहरू</a></li>
                </ul>
            </div>

            <!-- Contact Info -->
            <div class="footer-col">
                <h3 class="text-lg font-semibold mb-4 nepali">सम्पर्क जानकारी</h3>
                <p class="nepali">कमलपोखरी, काठमाडौं, नेपाल</p>
                <p>+९७७ ९८०१२३४५६७</p>
                <p>info@hostelhub.com</p>
                <p class="nepali">सोम-शुक्र: ९:०० बिहान - ५:०० बेलुका</p>
            </div>

            <!-- Newsletter -->
            <div class="footer-col">
                <h3 class="text-lg font-semibold mb-4 nepali">समाचारपत्र</h3>
                <p class="mb-2 nepali">हाम्रो नवीनतम अपडेटहरू प्राप्त गर्न तपाईंको इमेल दर्ता गर्नुहोस्</p>
                <form>
                    <input type="email" placeholder="तपाईंको इमेल" class="px-4 py-2 rounded-lg text-gray-800 w-full mb-2">
                    <button type="submit" class="px-4 py-2 bg-white text-indigo-900 rounded-lg hover:bg-blue-100 transition-colors w-full nepali">दर्ता गर्नुहोस्</button>
                </form>
            </div>
        </div>

        <!-- Bottom Copyright -->
        <div class="border-t border-indigo-800 mt-8 pt-6 text-center md:text-left">
            <p class="nepali">© 2025 HostelHub. सबै अधिकार सुरक्षित।</p>
            <div class="flex justify-center md:justify-start space-x-4 mt-2">
                <a href="{{ route('privacy') }}" class="hover:text-blue-200 nepali">गोपनीयता नीति</a>
                <a href="{{ route('terms') }}" class="hover:text-blue-200 nepali">सेवा सर्तहरू</a>
            </div>
            <p class="mt-2">संस्करण: 1.0.0</p>
        </div>
    </div>

    <style>
        /* Footer Grid Layout */
        .footer-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 2rem;
            align-items: start;
            justify-content: space-between;
        }

        /* Enlarged Footer Logo - 9-10 times bigger */
        .footer-logo-large {
            width: 180px; /* Approximately 9 times larger than original */
            height: auto;
            object-fit: contain;
        }

        .footer-col {
            display: flex;
            flex-direction: column;
        }

        /* Responsive Design */
        @media (max-width: 1024px) {
            .footer-grid {
                grid-template-columns: repeat(2, 1fr);
                gap: 1.5rem;
            }
        }

        @media (max-width: 768px) {
            .footer-grid {
                grid-template-columns: 1fr;
                gap: 1rem;
            }
            
            .footer-logo-large {
                width: 150px; /* Slightly smaller on mobile */
            }
        }

        @media (max-width: 480px) {
            .footer-logo-large {
                width: 120px;
            }
        }
    </style>
</footer>