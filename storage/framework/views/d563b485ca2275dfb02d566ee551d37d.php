<?php $__env->startSection('title', 'होस्टल ग्यालरी - HostelHub'); ?>

<?php $__env->startSection('content'); ?>
<div class="min-h-screen bg-gray-50">
    <!-- Hero Section -->
    <section class="bg-indigo-700 text-white py-16">
        <div class="container mx-auto px-4">
            <div class="max-w-3xl mx-auto text-center">
                <h1 class="text-4xl md:text-5xl font-bold mb-6">हाम्रा होस्टलहरूको ग्यालरी</h1>
                <p class="text-xl text-indigo-100 mb-8">
                    हाम्रा विभिन्न होस्टलहरूको कोठा, सुविधा र आवासीय क्षेत्रहरूको वास्तविक झलक
                </p>

                <!-- Filter Buttons -->
                <div class="flex flex-wrap justify-center gap-3 mb-10">
                    <button class="filter-btn active bg-white text-indigo-700 px-5 py-2.5 rounded-full font-medium shadow-md hover:shadow-lg transition-all"
                            data-filter="all">
                        सबै
                    </button>
                    <button class="filter-btn bg-white/10 hover:bg-white/20 px-5 py-2.5 rounded-full font-medium transition-colors"
                            data-filter="single">
                        एकल कोठा
                    </button>
                    <button class="filter-btn bg-white/10 hover:bg-white/20 px-5 py-2.5 rounded-full font-medium transition-colors"
                            data-filter="double">
                        दुई ब्यक्ति कोठा
                    </button>
                    <button class="filter-btn bg-white/10 hover:bg-white/20 px-5 py-2.5 rounded-full font-medium transition-colors"
                            data-filter="common">
                        सामान्य क्षेत्र
                    </button>
                    <button class="filter-btn bg-white/10 hover:bg-white/20 px-5 py-2.5 rounded-full font-medium transition-colors"
                            data-filter="dining">
                        भोजन कक्ष
                    </button>
                    <button class="filter-btn bg-white/10 hover:bg-white/20 px-5 py-2.5 rounded-full font-medium transition-colors"
                            data-filter="video">
                        भिडियो टुर
                    </button>
                </div>

                <div class="relative max-w-xl mx-auto">
                    <input type="text" id="search-gallery"
                           class="w-full px-5 py-3 rounded-full bg-white/20 backdrop-blur-sm border border-white/30 text-white placeholder-indigo-100 focus:outline-none focus:ring-2 focus:ring-white"
                           placeholder="कोठा, सुविधा वा स्थान खोज्नुहोस्...">
                    <button class="absolute right-3 top-1/2 -translate-y-1/2 text-white">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </section>

    <!-- Gallery Grid -->
    <section class="py-16">
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
                <!-- Single Room -->
                <div class="gallery-item bg-white rounded-2xl overflow-hidden shadow-lg transition-all duration-300 hover:shadow-2xl"
                     data-category="single">
                    <div class="relative group">
                        <img src="<?php echo e(asset('storage/hostel/single-room.jpg')); ?>"
                             alt="एकल कोठा"
                             class="w-full h-64 object-cover transition-transform duration-500 group-hover:scale-105">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/70 to-transparent opacity-0 group-hover:opacity-100 transition-opacity"></div>
                        <div class="absolute bottom-4 left-4">
                            <span class="bg-indigo-600 text-white px-3 py-1 rounded-full text-sm font-medium">एकल कोठा</span>
                            <h3 class="text-xl font-bold text-white mt-2">आरामदायी एकल कोठा</h3>
                        </div>
                    </div>
                    <div class="p-5">
                        <p class="text-gray-600 mb-4">एकल व्यक्तिका लागि डिजाइन गरिएको शान्त र आरामदायी कोठा, अध्ययन र विश्रामको लागि उत्तम</p>
                        <div class="flex justify-between items-center">
                            <span class="text-indigo-600 font-semibold">काठमाडौं, त्रिपुरेश्वर</span>
                            <button class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg transition-colors">
                                हेर्नुहोस्
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Double Room -->
                <div class="gallery-item bg-white rounded-2xl overflow-hidden shadow-lg transition-all duration-300 hover:shadow-2xl"
                     data-category="double">
                    <div class="relative group">
                        <img src="<?php echo e(asset('storage/hostel/double-room.jpg')); ?>"
                             alt="दुई ब्यक्ति कोठा"
                             class="w-full h-64 object-cover transition-transform duration-500 group-hover:scale-105">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/70 to-transparent opacity-0 group-hover:opacity-100 transition-opacity"></div>
                        <div class="absolute bottom-4 left-4">
                            <span class="bg-purple-600 text-white px-3 py-1 rounded-full text-sm font-medium">दुई ब्यक्ति कोठा</span>
                            <h3 class="text-xl font-bold text-white mt-2">दुई ब्यक्ति कोठा</h3>
                        </div>
                    </div>
                    <div class="p-5">
                        <p class="text-gray-600 mb-4">दुई विद्यार्थीहरूका लागि उपयुक्त, व्यक्तिगत स्थान र सामान्य अध्ययन क्षेत्र सहित</p>
                        <div class="flex justify-between items-center">
                            <span class="text-purple-600 font-semibold">पोखरा, बागर</span>
                            <button class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-lg transition-colors">
                                हेर्नुहोस्
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Video Tour -->
                <div class="gallery-item bg-white rounded-2xl overflow-hidden shadow-lg transition-all duration-300 hover:shadow-2xl"
                     data-category="video">
                    <div class="relative group">
                        <video class="w-full h-64 object-cover"
                               poster="<?php echo e(asset('storage/hostel/video-tour-thumbnail.jpg')); ?>"
                               muted>
                            <source src="<?php echo e(asset('storage/hostel/hostel-tour.mp4')); ?>" type="video/mp4">
                            <source src="<?php echo e(asset('storage/hostel/hostel-tour.webm')); ?>" type="video/webm">
                            तपाईंको ब्राउजरले भिडियो समर्थन गर्दैन
                        </video>
                        <div class="absolute inset-0 bg-gradient-to-t from-black/70 to-transparent opacity-0 group-hover:opacity-100 transition-opacity"></div>
                        <div class="absolute inset-0 flex items-center justify-center">
                            <div class="bg-red-600 w-16 h-16 rounded-full flex items-center justify-center shadow-lg">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                        </div>
                        <div class="absolute bottom-4 left-4">
                            <span class="bg-red-500 text-white px-3 py-1 rounded-full text-sm font-medium">भिडियो टुर</span>
                            <h3 class="text-xl font-bold text-white mt-2">होस्टल भर्चुअल टुर</h3>
                        </div>
                    </div>
                    <div class="p-5">
                        <p class="text-gray-600 mb-4">हाम्रो होस्टलको पूर्ण भर्चुअल टुर हेर्नुहोस्, सबै कोठा र सुविधाहरूको ३६० डिग्री दृश्य</p>
                        <div class="flex justify-between items-center">
                            <span class="text-red-500 font-semibold">काठमाडौं होस्टल</span>
                            <button class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg transition-colors play-video-btn"
                                    data-video="<?php echo e(asset('storage/hostel/hostel-tour.mp4')); ?>"
                                    data-title="होस्टल भर्चुअल टुर">
                                हेर्नुहोस्
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Dining Hall -->
                <div class="gallery-item bg-white rounded-2xl overflow-hidden shadow-lg transition-all duration-300 hover:shadow-2xl"
                     data-category="dining">
                    <div class="relative group">
                        <img src="<?php echo e(asset('storage/hostel/dining-hall.jpg')); ?>"
                             alt="भोजन कक्ष"
                             class="w-full h-64 object-cover transition-transform duration-500 group-hover:scale-105">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/70 to-transparent opacity-0 group-hover:opacity-100 transition-opacity"></div>
                        <div class="absolute bottom-4 left-4">
                            <span class="bg-amber-500 text-white px-3 py-1 rounded-full text-sm font-medium">भोजन कक्ष</span>
                            <h3 class="text-xl font-bold text-white mt-2">स्वास्थ्यकर भोजन कक्ष</h3>
                        </div>
                    </div>
                    <div class="p-5">
                        <p class="text-gray-600 mb-4">हाम्रो होस्टलको भोजन कक्ष जहाँ ताजा र पौष्टिक खाना उपलब्ध छ, सफा र स्वच्छ वातावरणमा</p>
                        <div class="flex justify-between items-center">
                            <span class="text-amber-500 font-semibold">सबै होस्टलहरूमा</span>
                            <button class="bg-amber-500 hover:bg-amber-600 text-white px-4 py-2 rounded-lg transition-colors">
                                हेर्नुहोस्
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Study Area -->
                <div class="gallery-item bg-white rounded-2xl overflow-hidden shadow-lg transition-all duration-300 hover:shadow-2xl"
                     data-category="common">
                    <div class="relative group">
                        <img src="<?php echo e(asset('storage/hostel/study-area.jpg')); ?>"
                             alt="अध्ययन क्षेत्र"
                             class="w-full h-64 object-cover transition-transform duration-500 group-hover:scale-105">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/70 to-transparent opacity-0 group-hover:opacity-100 transition-opacity"></div>
                        <div class="absolute bottom-4 left-4">
                            <span class="bg-emerald-600 text-white px-3 py-1 rounded-full text-sm font-medium">अध्ययन क्षेत्र</span>
                            <h3 class="text-xl font-bold text-white mt-2">शान्त अध्ययन क्षेत्र</h3>
                        </div>
                    </div>
                    <div class="p-5">
                        <p class="text-gray-600 mb-4">शान्त र प्रकाशयुक्त अध्ययन क्षेत्र जहाँ विद्यार्थीहरू एकान्तमा अध्ययन गर्न सक्छन्</p>
                        <div class="flex justify-between items-center">
                            <span class="text-emerald-600 font-semibold">काठमाडौं होस्टल</span>
                            <button class="bg-emerald-600 hover:bg-emerald-700 text-white px-4 py-2 rounded-lg transition-colors">
                                हेर्नुहोस्
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Living Room -->
                <div class="gallery-item bg-white rounded-2xl overflow-hidden shadow-lg transition-all duration-300 hover:shadow-2xl"
                     data-category="common">
                    <div class="relative group">
                        <img src="<?php echo e(asset('storage/hostel/living-room.jpg')); ?>"
                             alt="लिभिङ रूम"
                             class="w-full h-64 object-cover transition-transform duration-500 group-hover:scale-105">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/70 to-transparent opacity-0 group-hover:opacity-100 transition-opacity"></div>
                        <div class="absolute bottom-4 left-4">
                            <span class="bg-rose-500 text-white px-3 py-1 rounded-full text-sm font-medium">लिभिङ रूम</span>
                            <h3 class="text-xl font-bold text-white mt-2">सामाजिक लिभिङ रूम</h3>
                        </div>
                    </div>
                    <div class="p-5">
                        <p class="text-gray-600 mb-4">विद्यार्थीहरूले सामाजिकरूपमा जोडिने, आराम गर्ने र साथीहरूसँग कुराकानी गर्ने स्थान</p>
                        <div class="flex justify-between items-center">
                            <span class="text-rose-500 font-semibold">पोखरा होस्टल</span>
                            <button class="bg-rose-500 hover:bg-rose-600 text-white px-4 py-2 rounded-lg transition-colors">
                                हेर्नुहोस्
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Bathroom -->
                <div class="gallery-item bg-white rounded-2xl overflow-hidden shadow-lg transition-all duration-300 hover:shadow-2xl"
                     data-category="common">
                    <div class="relative group">
                        <img src="<?php echo e(asset('storage/hostel/bathroom.jpg')); ?>"
                             alt="बाथरूम"
                             class="w-full h-64 object-cover transition-transform duration-500 group-hover:scale-105">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/70 to-transparent opacity-0 group-hover:opacity-100 transition-opacity"></div>
                        <div class="absolute bottom-4 left-4">
                            <span class="bg-cyan-500 text-white px-3 py-1 rounded-full text-sm font-medium">बाथरूम</span>
                            <h3 class="text-xl font-bold text-white mt-2">आधुनिक बाथरूम</h3>
                        </div>
                    </div>
                    <div class="p-5">
                        <p class="text-gray-600 mb-4">सफा, स्वच्छ र आधुनिक सुविधा युक्त बाथरूम, २४/७ तातो पानीको व्यवस्था</p>
                        <div class="flex justify-between items-center">
                            <span class="text-cyan-500 font-semibold">सबै होस्टलहरूमा</span>
                            <button class="bg-cyan-500 hover:bg-cyan-600 text-white px-4 py-2 rounded-lg transition-colors">
                                हेर्नुहोस्
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Video Tour 2 -->
                <div class="gallery-item bg-white rounded-2xl overflow-hidden shadow-lg transition-all duration-300 hover:shadow-2xl"
                     data-category="video">
                    <div class="relative group">
                        <video class="w-full h-64 object-cover"
                               poster="<?php echo e(asset('storage/hostel/study-area-video.jpg')); ?>"
                               muted>
                            <source src="<?php echo e(asset('storage/hostel/study-area-tour.mp4')); ?>" type="video/mp4">
                            <source src="<?php echo e(asset('storage/hostel/study-area-tour.webm')); ?>" type="video/webm">
                            तपाईंको ब्राउजरले भिडियो समर्थन गर्दैन
                        </video>
                        <div class="absolute inset-0 bg-gradient-to-t from-black/70 to-transparent opacity-0 group-hover:opacity-100 transition-opacity"></div>
                        <div class="absolute inset-0 flex items-center justify-center">
                            <div class="bg-red-600 w-16 h-16 rounded-full flex items-center justify-center shadow-lg">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                        </div>
                        <div class="absolute bottom-4 left-4">
                            <span class="bg-red-500 text-white px-3 py-1 rounded-full text-sm font-medium">भिडियो टुर</span>
                            <h3 class="text-xl font-bold text-white mt-2">अध्ययन क्षेत्र टुर</h3>
                        </div>
                    </div>
                    <div class="p-5">
                        <p class="text-gray-600 mb-4">हाम्रो शान्त र प्रकाशयुक्त अध्ययन क्षेत्रको भिडियो टुर, विद्यार्थीहरूको लागि उत्तम वातावरण</p>
                        <div class="flex justify-between items-center">
                            <span class="text-red-500 font-semibold">काठमाडौं होस्टल</span>
                            <button class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg transition-colors play-video-btn"
                                    data-video="<?php echo e(asset('storage/hostel/study-area-tour.mp4')); ?>"
                                    data-title="अध्ययन क्षेत्र टुर">
                                हेर्नुहोस्
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Empty State -->
            <div id="empty-state" class="hidden text-center py-16 bg-white rounded-2xl mt-12">
                <div class="inline-block p-4 bg-indigo-100 rounded-full mb-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                    </svg>
                </div>
                <h3 class="text-2xl font-bold text-gray-800 mb-2">यस श्रेणीमा कुनै फोटो छैन</h3>
                <p class="text-gray-600 max-w-md mx-auto">कृपया अर्को श्रेणी छान्नुहोस् वा सबै फिल्टर चयन गर्नुहोस्</p>
                <button id="reset-filter" class="mt-4 bg-indigo-600 text-white px-6 py-2.5 rounded-lg font-medium hover:bg-indigo-700 transition-colors">
                    सबै फोटो हेर्नुहोस्
                </button>
            </div>
        </div>
    </section>

    <!-- Hostel Stats -->
    <section class="bg-white py-12 border-t">
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-8 text-center">
                <div class="p-6 bg-indigo-50 rounded-xl">
                    <div class="text-4xl font-bold text-indigo-600 mb-2">125+</div>
                    <div class="text-gray-600 font-medium">खुसी विद्यार्थीहरू</div>
                </div>
                <div class="p-6 bg-purple-50 rounded-xl">
                    <div class="text-4xl font-bold text-purple-600 mb-2">24</div>
                    <div class="text-gray-600 font-medium">सहयोगी होस्टल</div>
                </div>
                <div class="p-6 bg-amber-50 rounded-xl">
                    <div class="text-4xl font-bold text-amber-600 mb-2">5</div>
                    <div class="text-gray-600 font-medium">शहरहरूमा उपलब्ध</div>
                </div>
                <div class="p-6 bg-emerald-50 rounded-xl">
                    <div class="text-4xl font-bold text-emerald-600 mb-2">98%</div>
                    <div class="text-gray-600 font-medium">सन्तुष्टि दर</div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="bg-gradient-to-r from-indigo-600 to-purple-700 py-16">
        <div class="container mx-auto px-4 text-center">
            <h2 class="text-3xl md:text-4xl font-bold text-white mb-6">तपाईंको होस्टललाई HostelHub संग जोड्नुहोस्</h2>
            <p class="text-xl text-indigo-100 max-w-3xl mx-auto mb-10">
                ७ दिन निःशुल्क परीक्षण गर्नुहोस् र होस्टल व्यवस्थापनलाई सजिलो, द्रुत र भरपर्दो बनाउनुहोस्
            </p>
            <div class="flex flex-col sm:flex-row justify-center gap-4">
                <a href="#" class="bg-white text-indigo-700 font-bold px-8 py-4 rounded-xl shadow-lg hover:shadow-xl transition-all text-lg">
                    निःशुल्क साइन अप गर्नुहोस्
                </a>
                <a href="#" class="bg-indigo-800/30 backdrop-blur-sm text-white border-2 border-white/20 font-bold px-8 py-4 rounded-xl hover:bg-indigo-800/50 transition-colors text-lg">
                    डेमो हेर्नुहोस्
                </a>
            </div>
        </div>
    </section>
</div>

<!-- Video Modal -->
<div id="video-modal" class="fixed inset-0 bg-black bg-opacity-90 z-50 hidden flex items-center justify-center p-4">
    <div class="relative w-full max-w-4xl">
        <button id="close-video-modal" class="absolute -top-12 right-0 text-white text-xl hover:text-gray-300 transition-colors">
            बन्द गर्नुहोस् ×
        </button>
        <div class="bg-black rounded-xl overflow-hidden">
            <div class="relative pb-[56.25%] h-0">
                <video id="modal-video-player" class="absolute inset-0 w-full h-full" controls>
                    <source src="" type="video/mp4">
                    तपाईंको ब्राउजरले भिडियो समर्थन गर्दैन
                </video>
            </div>
            <div class="p-4 bg-gray-900">
                <h3 id="video-title" class="text-white font-bold text-xl"></h3>
            </div>
        </div>
    </div>
</div>

<?php $__env->startPush('scripts'); ?>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const filterBtns = document.querySelectorAll('.filter-btn');
        const galleryItems = document.querySelectorAll('.gallery-item');
        const emptyState = document.getElementById('empty-state');
        const resetFilter = document.getElementById('reset-filter');
        const searchInput = document.getElementById('search-gallery');
        const videoModal = document.getElementById('video-modal');
        const modalVideoPlayer = document.getElementById('modal-video-player');
        const videoTitle = document.getElementById('video-title');
        const closeVideoModal = document.getElementById('close-video-modal');
        const playVideoBtns = document.querySelectorAll('.play-video-btn');

        // Filter functionality
        filterBtns.forEach(btn => {
            btn.addEventListener('click', () => {
                // Update active button
                filterBtns.forEach(b => b.classList.remove('active', 'bg-white', 'text-indigo-700'));
                btn.classList.add('active', 'bg-white', 'text-indigo-700');

                const filter = btn.getAttribute('data-filter');
                let visibleItems = 0;

                galleryItems.forEach(item => {
                    if (filter === 'all' || item.getAttribute('data-category') === filter) {
                        item.style.display = 'block';
                        visibleItems++;
                    } else {
                        item.style.display = 'none';
                    }
                });

                // Show empty state if no items
                emptyState.classList.toggle('hidden', visibleItems > 0);
            });
        });

        // Reset filter
        resetFilter.addEventListener('click', () => {
            filterBtns.forEach(b => b.classList.remove('active', 'bg-white', 'text-indigo-700'));
            filterBtns[0].classList.add('active', 'bg-white', 'text-indigo-700');

            galleryItems.forEach(item => item.style.display = 'block');
            emptyState.classList.add('hidden');
        });

        // Search functionality
        searchInput.addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            let visibleItems = 0;

            galleryItems.forEach(item => {
                const title = item.querySelector('h3').textContent.toLowerCase();
                const location = item.querySelector('.font-semibold').textContent.toLowerCase();
                const description = item.querySelector('p').textContent.toLowerCase();

                if (title.includes(searchTerm) ||
                    location.includes(searchTerm) ||
                    description.includes(searchTerm)) {
                    item.style.display = 'block';
                    visibleItems++;
                } else {
                    item.style.display = 'none';
                }
            });

            // Update filter buttons state
            if (searchTerm) {
                filterBtns.forEach(b => b.classList.remove('active', 'bg-white', 'text-indigo-700'));
            }

            // Show empty state if no results
            emptyState.classList.toggle('hidden', visibleItems > 0);
        });

        // Video Modal Functionality
        playVideoBtns.forEach(btn => {
            btn.addEventListener('click', function() {
                const videoUrl = this.getAttribute('data-video');
                const title = this.getAttribute('data-title');

                modalVideoPlayer.querySelector('source').src = videoUrl;
                videoTitle.textContent = title;

                // Reload video to ensure it loads the new source
                modalVideoPlayer.load();

                // Show modal
                videoModal.classList.remove('hidden');

                // Autoplay when loaded
                modalVideoPlayer.onloadeddata = function() {
                    modalVideoPlayer.play().catch(e => console.log("Autoplay prevented:", e));
                };
            });
        });

        // Close video modal
        closeVideoModal.addEventListener('click', function() {
            videoModal.classList.add('hidden');
            modalVideoPlayer.pause();
            modalVideoPlayer.currentTime = 0;
        });

        // Close modal when clicking outside video
        videoModal.addEventListener('click', function(e) {
            if (e.target === videoModal) {
                videoModal.classList.add('hidden');
                modalVideoPlayer.pause();
                modalVideoPlayer.currentTime = 0;
            }
        });

        // Close modal on escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && !videoModal.classList.contains('hidden')) {
                videoModal.classList.add('hidden');
                modalVideoPlayer.pause();
                modalVideoPlayer.currentTime = 0;
            }
        });
    });
</script>
<?php $__env->stopPush(); ?>

<?php $__env->startPush('styles'); ?>
<style>
    .gallery-item {
        transform: translateY(20px);
        opacity: 0;
        animation: fadeInUp 0.5s ease forwards;
    }

    @keyframes fadeInUp {
        to {
            transform: translateY(0);
            opacity: 1;
        }
    }

    .gallery-item:nth-child(2) { animation-delay: 0.1s; }
    .gallery-item:nth-child(3) { animation-delay: 0.2s; }
    .gallery-item:nth-child(4) { animation-delay: 0.3s; }
    .gallery-item:nth-child(5) { animation-delay: 0.4s; }
    .gallery-item:nth-child(6) { animation-delay: 0.5s; }
    .gallery-item:nth-child(7) { animation-delay: 0.6s; }
    .gallery-item:nth-child(8) { animation-delay: 0.7s; }

    #video-modal {
        animation: modalFadeIn 0.3s ease-out;
    }

    @keyframes modalFadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }
</style>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\My Projects\HostelHub\resources\views/gallery.blade.php ENDPATH**/ ?>