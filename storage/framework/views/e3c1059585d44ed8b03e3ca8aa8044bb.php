<?php $__env->startSection('content'); ?>
<!-- Classic Theme - Professional & Traditional -->
<div class="min-h-screen bg-gray-50">
    <!-- Hero Section -->
    <section class="bg-white border-b py-12">
        <div class="container">
            <div class="flex flex-col lg:flex-row items-center justify-between gap-8 mb-8">
                <!-- Logo & Basic Info -->
                <div class="flex items-center gap-6">
                    <?php if($logo): ?>
                        <img src="<?php echo e($logo); ?>" alt="<?php echo e($hostel->name); ?>" class="w-24 h-24 rounded-full object-cover border-4 border-white shadow-lg">
                    <?php endif; ?>
                    <div class="text-left">
                        <h1 class="text-4xl font-bold text-gray-900 nepali mb-2"><?php echo e($hostel->name); ?></h1>
                        <div class="flex items-center gap-6 text-gray-600">
                            <div class="flex items-center gap-2">
                                <i class="fas fa-map-marker-alt"></i>
                                <span class="nepali"><?php echo e($hostel->city ?? 'काठमाडौं'); ?></span>
                            </div>
                            <?php if($reviewCount > 0): ?>
                                <div class="flex items-center gap-2">
                                    <i class="fas fa-star text-yellow-400"></i>
                                    <span><?php echo e(number_format($avgRating, 1)); ?></span>
                                    <span class="nepali">(<?php echo e($reviewCount); ?>)</span>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- UPDATED: Social Media & Phone - Top Right Corner -->
                <div class="top-right-actions">
                    <!-- Dynamic Social Media Icons from Database -->
                    <div class="social-media-buttons">
                        <?php if($hostel->facebook_url): ?>
                            <a href="<?php echo e($hostel->facebook_url); ?>" target="_blank" class="social-icon facebook-bg" title="Facebook">
                                <i class="fab fa-facebook-f"></i>
                            </a>
                        <?php endif; ?>
                        
                        <?php if($hostel->instagram_url): ?>
                            <a href="<?php echo e($hostel->instagram_url); ?>" target="_blank" class="social-icon instagram-bg" title="Instagram">
                                <i class="fab fa-instagram"></i>
                            </a>
                        <?php endif; ?>
                        
                        <?php if($hostel->twitter_url): ?>
                            <a href="<?php echo e($hostel->twitter_url); ?>" target="_blank" class="social-icon twitter-bg" title="Twitter">
                                <i class="fab fa-twitter"></i>
                            </a>
                        <?php endif; ?>
                        
                        <?php if($hostel->tiktok_url): ?>
                            <a href="<?php echo e($hostel->tiktok_url); ?>" target="_blank" class="social-icon tiktok-bg" title="TikTok">
                                <i class="fab fa-tiktok"></i>
                            </a>
                        <?php endif; ?>
                        
                        <?php if($hostel->whatsapp_number): ?>
                            <a href="https://wa.me/<?php echo e($hostel->whatsapp_number); ?>" target="_blank" class="social-icon whatsapp-bg" title="WhatsApp">
                                <i class="fab fa-whatsapp"></i>
                            </a>
                        <?php endif; ?>
                        
                        <?php if($hostel->youtube_url): ?>
                            <a href="<?php echo e($hostel->youtube_url); ?>" target="_blank" class="social-icon youtube-bg" title="YouTube">
                                <i class="fab fa-youtube"></i>
                            </a>
                        <?php endif; ?>
                        
                        <?php if($hostel->linkedin_url): ?>
                            <a href="<?php echo e($hostel->linkedin_url); ?>" target="_blank" class="social-icon linkedin-bg" title="LinkedIn">
                                <i class="fab fa-linkedin-in"></i>
                            </a>
                        <?php endif; ?>
                    </div>

                    <!-- Phone Button -->
                    <?php if($hostel->contact_phone): ?>
                        <a href="tel:<?php echo e($hostel->contact_phone); ?>" 
                           class="bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition-colors font-medium nepali flex items-center gap-2">
                            <i class="fas fa-phone"></i>
                            फोन गर्नुहोस्
                        </a>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Stats Bar -->
            <div class="flex flex-wrap justify-center gap-6">
                <?php if($hostel->available_rooms > 0): ?>
                    <div class="text-center">
                        <div class="text-2xl font-bold text-blue-600"><?php echo e($hostel->available_rooms); ?></div>
                        <div class="text-sm text-gray-600 nepali">उपलब्ध कोठा</div>
                    </div>
                <?php endif; ?>
                
                <?php if($hostel->total_rooms): ?>
                    <div class="text-center">
                        <div class="text-2xl font-bold text-green-600"><?php echo e($hostel->total_rooms); ?></div>
                        <div class="text-sm text-gray-600 nepali">कुल कोठा</div>
                    </div>
                <?php endif; ?>

                <?php if($hostel->students_count): ?>
                    <div class="text-center">
                        <div class="text-2xl font-bold text-purple-600"><?php echo e($hostel->students_count); ?></div>
                        <div class="text-sm text-gray-600 nepali">विद्यार्थी</div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <!-- Main Content -->
    <div class="container py-12">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Main Content -->
            <div class="lg:col-span-2 space-y-8">
                <!-- About Section -->
                <section class="bg-white rounded-lg shadow border p-8">
                    <h2 class="text-2xl font-bold text-gray-800 mb-6 nepali border-b pb-2">हाम्रो बारेमा</h2>
                    <div class="prose max-w-none">
                        <?php if($hostel->description): ?>
                            <p class="text-gray-700 leading-relaxed nepali whitespace-pre-line text-lg">
                                <?php echo e($hostel->description); ?>

                            </p>
                        <?php else: ?>
                            <p class="text-gray-500 italic nepali text-center py-8">विवरण उपलब्ध छैन</p>
                        <?php endif; ?>
                    </div>
                </section>

                <!-- Facilities Section -->
                <?php if(!empty($facilities) && count($facilities) > 0): ?>
                    <section class="bg-white rounded-lg shadow border p-8">
                        <h2 class="text-2xl font-bold text-gray-800 mb-6 nepali border-b pb-2">सुविधाहरू</h2>
                        <div class="grid grid-cols-2 gap-4">
                            <?php $__currentLoopData = $facilities; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $facility): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php if(trim($facility)): ?>
                                    <div class="flex items-center gap-3 p-3 border rounded-lg">
                                        <i class="fas fa-check text-green-500"></i>
                                        <span class="nepali text-gray-700"><?php echo e(trim($facility)); ?></span>
                                    </div>
                                <?php endif; ?>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    </section>
                <?php endif; ?>

                <!-- Gallery Section -->
                <?php echo $__env->make('public.hostels.partials.gallery', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

                <!-- Contact Form Section -->
                <?php echo $__env->make('public.hostels.partials.contact-form', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

                <!-- Reviews Section -->
                <section class="bg-white rounded-lg shadow border p-8">
                    <h2 class="text-2xl font-bold text-gray-800 mb-6 nepali border-b pb-2">विद्यार्थी समीक्षाहरू</h2>
                    
                    <?php if($reviewCount > 0): ?>
                        <div class="space-y-6">
                            <?php $__currentLoopData = $reviews; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $review): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="border border-gray-200 rounded-lg p-6">
                                    <div class="flex flex-col sm:flex-row justify-between items-start mb-4 gap-2">
                                        <div>
                                            <h4 class="font-bold text-gray-800 nepali">
                                                <?php echo e($review->student->user->name ?? 'अज्ञात विद्यार्थी'); ?>

                                            </h4>
                                            <div class="flex items-center gap-1 mt-1">
                                                <?php for($i = 1; $i <= 5; $i++): ?>
                                                    <i class="fas fa-star <?php echo e($i <= $review->rating ? 'text-yellow-400' : 'text-gray-300'); ?>"></i>
                                                <?php endfor; ?>
                                            </div>
                                        </div>
                                        <span class="text-sm text-gray-500 bg-gray-100 px-3 py-1 rounded-full">
                                            <?php echo e($review->created_at->format('Y-m-d')); ?>

                                        </span>
                                    </div>
                                    <p class="text-gray-700 mb-4 nepali"><?php echo e($review->comment); ?></p>
                                    
                                    <?php if($review->reply): ?>
                                        <div class="bg-blue-50 border-l-4 border-blue-500 p-4 rounded">
                                            <div class="flex items-start gap-3">
                                                <i class="fas fa-reply text-blue-500 mt-1"></i>
                                                <div>
                                                    <strong class="text-blue-800 nepali text-sm">होस्टलको जवाफ:</strong>
                                                    <p class="text-blue-700 mt-1 nepali text-sm"><?php echo e($review->reply); ?></p>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>

                        <?php if($reviews->hasPages()): ?>
                            <div class="mt-6">
                                <?php echo e($reviews->links()); ?>

                            </div>
                        <?php endif; ?>
                    <?php else: ?>
                        <div class="text-center py-12">
                            <i class="fas fa-comment-slash text-gray-400 text-5xl mb-4"></i>
                            <p class="text-gray-500 nepali">अहिलेसम्म कुनै समीक्षा छैन</p>
                        </div>
                    <?php endif; ?>
                </section>
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Contact Card -->
                <div class="bg-white rounded-lg shadow border p-6">
                    <h3 class="text-xl font-bold text-gray-800 mb-4 nepali border-b pb-2">सम्पर्क जानकारी</h3>
                    <div class="space-y-4">
                        <?php if($hostel->contact_person): ?>
                            <div class="flex items-center gap-3">
                                <i class="fas fa-user text-gray-400"></i>
                                <span class="text-gray-700 nepali"><?php echo e($hostel->contact_person); ?></span>
                            </div>
                        <?php endif; ?>
                        
                        <?php if($hostel->contact_phone): ?>
                            <div class="flex items-center gap-3">
                                <i class="fas fa-phone text-gray-400"></i>
                                <a href="tel:<?php echo e($hostel->contact_phone); ?>" class="text-gray-700 hover:text-blue-600">
                                    <?php echo e($hostel->contact_phone); ?>

                                </a>
                            </div>
                        <?php endif; ?>

                        <?php if($hostel->contact_email): ?>
                            <div class="flex items-center gap-3">
                                <i class="fas fa-envelope text-gray-400"></i>
                                <a href="mailto:<?php echo e($hostel->contact_email); ?>" class="text-gray-700 hover:text-blue-600">
                                    <?php echo e($hostel->contact_email); ?>

                                </a>
                            </div>
                        <?php endif; ?>
                        
                        <?php if($hostel->address): ?>
                            <div class="flex items-start gap-3">
                                <i class="fas fa-map-marker-alt text-gray-400 mt-1"></i>
                                <span class="text-gray-700 nepali"><?php echo e($hostel->address); ?></span>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Quick Actions - UPDATED: Phone Button Removed -->
                <div class="bg-blue-50 rounded-lg p-6">
                    <h3 class="text-xl font-bold text-blue-800 mb-4 nepali">तुरुन्तै सम्पर्क गर्नुहोस्</h3>
                    <!-- Phone button removed from here -->
                    
                    <?php if($hostel->contact_email): ?>
                        <a href="mailto:<?php echo e($hostel->contact_email); ?>" 
                           class="w-full border border-blue-600 text-blue-600 py-3 px-4 rounded-lg hover:bg-blue-600 hover:text-white transition-colors font-medium nepali flex items-center justify-center gap-2 mb-3">
                            <i class="fas fa-envelope"></i>
                            इमेल गर्नुहोस्
                        </a>
                    <?php endif; ?>

                    <a href="#contact" 
                       class="w-full bg-blue-600 text-white py-3 px-4 rounded-lg hover:bg-blue-700 transition-colors font-medium nepali flex items-center justify-center gap-2">
                        <i class="fas fa-map-marker-alt"></i>
                        स्थान हेर्नुहोस्
                    </a>
                </div>

                <!-- Trust Badges -->
                <div class="bg-white rounded-lg shadow border p-6 text-center">
                    <h3 class="text-xl font-bold text-gray-800 mb-4 nepali">विश्वसनीय होस्टल</h3>
                    <div class="grid grid-cols-2 gap-4">
                        <div class="space-y-2">
                            <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center mx-auto">
                                <i class="fas fa-check text-green-600"></i>
                            </div>
                            <span class="text-xs text-gray-600 nepali">सत्यापित</span>
                        </div>
                        <div class="space-y-2">
                            <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center mx-auto">
                                <i class="fas fa-star text-blue-600"></i>
                            </div>
                            <span class="text-xs text-gray-600 nepali">रेटेड</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.public', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\My Projects\HostelHub\resources\views/public/hostels/themes/classic.blade.php ENDPATH**/ ?>