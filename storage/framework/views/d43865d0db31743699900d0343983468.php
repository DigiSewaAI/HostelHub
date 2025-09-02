<footer class="bg-indigo-900 text-white py-8">
    <div class="container mx-auto px-4">
        <div class="flex flex-col md:flex-row justify-between">
            <!-- Logo and description -->
            <div class="mb-6 md:mb-0">
                <img src="<?php echo e(asset('storage/images/logo.png')); ?>" alt="HostelHub Logo" class="w-32 h-10 object-contain mb-4">
                <p class="max-w-xs">नेपालको नम्बर १ होस्टल प्रबन्धन प्रणाली। हामी होस्टल व्यवस्थापनलाई सहज, दक्ष र विश्वसनीय बनाउँछौं।</p>
            </div>

            <!-- Quick Links -->
            <div class="mb-6 md:mb-0">
                <h3 class="text-lg font-semibold mb-4">तिब्र लिङ्कहरू</h3>
                <ul class="space-y-2">
                    <li><a href="/" class="hover:text-blue-200">होम</a></li>
                    <li><a href="<?php echo e(route('features')); ?>" class="hover:text-blue-200">सुविधाहरू</a></li>
                    <li><a href="<?php echo e(route('how-it-works')); ?>" class="hover:text-blue-200">कसरी काम गर्छ</a></li>
                    <li><a href="<?php echo e(route('gallery.public')); ?>" class="hover:text-blue-200">ग्यालरी</a></li>
                    <li><a href="<?php echo e(route('pricing')); ?>" class="hover:text-blue-200">मूल्य</a></li>
                    <li><a href="<?php echo e(route('reviews')); ?>" class="hover:text-blue-200">समीक्षाहरू</a></li>
                </ul>
            </div>

            <!-- Contact Info -->
            <div class="mb-6 md:mb-0">
                <h3 class="text-lg font-semibold mb-4">सम्पर्क जानकारी</h3>
                <p>कमलपोखरी, काठमाडौं, नेपाल</p>
                <p>+९७७ ९८०१२३४५६७</p>
                <p>info@hostelhub.com</p>
                <p>सोम-शुक्र: ९:०० बिहान - ५:०० बेलुका</p>
            </div>

            <!-- Newsletter -->
            <div>
                <h3 class="text-lg font-semibold mb-4">समाचारपत्र</h3>
                <p class="mb-2">हाम्रो नवीनतम अपडेटहरू प्राप्त गर्न तपाईंको इमेल दर्ता गर्नुहोस्</p>
                <form>
                    <input type="email" placeholder="तपाईंको इमेल" class="px-4 py-2 rounded-lg text-gray-800 w-full mb-2">
                    <button type="submit" class="px-4 py-2 bg-white text-indigo-900 rounded-lg hover:bg-blue-100 transition-colors w-full">दर्ता गर्नुहोस्</button>
                </form>
            </div>
        </div>

        <!-- Bottom Copyright -->
        <div class="border-t border-indigo-800 mt-8 pt-6 text-center md:text-left">
            <p>© 2025 HostelHub. सबै अधिकार सुरक्षित।</p>
            <div class="flex justify-center md:justify-start space-x-4 mt-2">
                <a href="<?php echo e(route('privacy')); ?>" class="hover:text-blue-200">गोपनीयता नीति</a>
                <a href="<?php echo e(route('terms')); ?>" class="hover:text-blue-200">सेवा सर्तहरू</a>
            </div>
            <p class="mt-2">संस्करण: 1.0.0</p>
        </div>
    </div>
</footer><?php /**PATH D:\My Projects\HostelHub\resources\views/components/footer.blade.php ENDPATH**/ ?>