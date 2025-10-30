

<?php $__env->startSection('title', 'सार्वजनिक पृष्ठ व्यवस्थापन'); ?>

<?php $__env->startSection('content'); ?>
<div class="max-w-4xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-xl font-semibold text-gray-800 nepali">तपाईंको सार्वजनिक पृष्ठ व्यवस्थापन</h2>
            <p class="text-gray-600 mt-1 nepali">तपाईंको होस्टलको सार्वजनिक पृष्ठलाई अनुकूलन गर्नुहोस् र प्रकाशित गर्नुहोस्</p>
        </div>

        <div class="p-6">
            <?php if(session('success')): ?>
                <div class="mb-6 p-4 bg-green-50 border border-green-200 text-green-700 rounded-lg flex items-center">
                    <i class="fas fa-check-circle mr-2"></i>
                    <span class="nepali"><?php echo e(session('success')); ?></span>
                </div>
            <?php endif; ?>

            <?php if(session('error')): ?>
                <div class="mb-6 p-4 bg-red-50 border border-red-200 text-red-700 rounded-lg flex items-center">
                    <i class="fas fa-exclamation-circle mr-2"></i>
                    <span class="nepali"><?php echo e(session('error')); ?></span>
                </div>
            <?php endif; ?>

            <!-- 🛠️ FIXED: Form action route -->
            <form method="POST" action="<?php echo e(route('owner.public-page.preview')); ?>" enctype="multipart/form-data" class="space-y-6">
                <?php echo csrf_field(); ?>

                <!-- Theme Selection -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2 nepali">पृष्ठ डिजाइन चयन गर्नुहोस्</label>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        <!-- Modern Theme -->
                        <label class="relative cursor-pointer">
                            <input type="radio" name="theme" value="modern" <?php echo e(($hostel->theme ?? 'modern') == 'modern' ? 'checked' : ''); ?> class="sr-only">
                            <div class="border-2 rounded-lg p-4 transition-all <?php echo e(($hostel->theme ?? 'modern') == 'modern' ? 'border-blue-500 bg-blue-50' : 'border-gray-300 hover:border-gray-400'); ?>">
                                <div class="w-full h-20 bg-gradient-to-r from-gray-50 to-white rounded mb-2"></div>
                                <div class="text-center">
                                    <span class="text-sm font-medium nepali">आधुनिक</span>
                                </div>
                            </div>
                        </label>

                        <!-- Classic Theme -->
                        <label class="relative cursor-pointer">
                            <input type="radio" name="theme" value="classic" <?php echo e(($hostel->theme ?? 'modern') == 'classic' ? 'checked' : ''); ?> class="sr-only">
                            <div class="border-2 rounded-lg p-4 transition-all <?php echo e(($hostel->theme ?? 'modern') == 'classic' ? 'border-blue-500 bg-blue-50' : 'border-gray-300 hover:border-gray-400'); ?>">
                                <div class="w-full h-20 bg-white rounded mb-2 border"></div>
                                <div class="text-center">
                                    <span class="text-sm font-medium nepali">क्लासिक</span>
                                </div>
                            </div>
                        </label>

                        <!-- Vibrant Theme -->
                        <label class="relative cursor-pointer">
                            <input type="radio" name="theme" value="vibrant" <?php echo e(($hostel->theme ?? 'modern') == 'vibrant' ? 'checked' : ''); ?> class="sr-only">
                            <div class="border-2 rounded-lg p-4 transition-all <?php echo e(($hostel->theme ?? 'modern') == 'vibrant' ? 'border-blue-500 bg-blue-50' : 'border-gray-300 hover:border-gray-400'); ?>">
                                <div class="w-full h-20 bg-gradient-to-r from-purple-500 to-pink-500 rounded mb-2"></div>
                                <div class="text-center">
                                    <span class="text-sm font-medium nepali">वाइब्रन्ट</span>
                                </div>
                            </div>
                        </label>

                        <!-- Dark Theme -->
                        <label class="relative cursor-pointer">
                            <input type="radio" name="theme" value="dark" <?php echo e(($hostel->theme ?? 'modern') == 'dark' ? 'checked' : ''); ?> class="sr-only">
                            <div class="border-2 rounded-lg p-4 transition-all <?php echo e(($hostel->theme ?? 'modern') == 'dark' ? 'border-blue-500 bg-blue-50' : 'border-gray-300 hover:border-gray-400'); ?>">
                                <div class="w-full h-20 bg-gray-800 rounded mb-2"></div>
                                <div class="text-center">
                                    <span class="text-sm font-medium nepali">डार्क</span>
                                </div>
                            </div>
                        </label>
                    </div>
                </div>

                <!-- Logo Upload -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2 nepali">होस्टल लोगो</label>
                    <div class="flex items-center space-x-4">
                        <?php if($hostel->logo_path): ?>
                            <!-- 🛠️ FIXED: Logo preview using correct path -->
                            <img src="<?php echo e(asset('storage/' . $hostel->logo_path)); ?>" alt="<?php echo e($hostel->name); ?>" class="h-16 w-16 rounded-lg object-cover">
                        <?php else: ?>
                            <div class="h-16 w-16 bg-gray-200 rounded-lg flex items-center justify-center">
                                <i class="fas fa-building text-gray-400"></i>
                            </div>
                        <?php endif; ?>
                        <div class="flex-1">
                            <input type="file" name="logo" accept="image/*" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                            <p class="text-xs text-gray-500 mt-1 nepali">JPG, PNG, GIF, 2MB सम्म</p>
                        </div>
                    </div>
                </div>

                <!-- Theme Color -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2 nepali">थीम रङ</label>
                    <input type="color" name="theme_color" value="<?php echo e($hostel->theme_color ?? '#3b82f6'); ?>" class="h-10 w-20 rounded border border-gray-300">
                    <p class="text-xs text-gray-500 mt-1 nepali">तपाईंको होस्टल पृष्ठको प्रमुख रङ चयन गर्नुहोस्</p>
                </div>

                <!-- Description -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2 nepali">होस्टल विवरण</label>
                    <textarea name="description" rows="6" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 nepali" placeholder="तपाईंको होस्टलको बारेमा विस्तृत विवरण लेख्नुहोस्..."><?php echo e(old('description', $hostel->description)); ?></textarea>
                    <p class="text-xs text-gray-500 mt-1 nepali">यो विवरण तपाईंको सार्वजनिक पृष्ठमा देखिनेछ</p>
                </div>

                <!-- Social Media Links Section -->
                <div class="border-t border-gray-200 pt-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4 nepali flex items-center">
                        <i class="fas fa-share-alt text-purple-600 mr-2"></i>
                        सोसियल मिडिया लिङ्कहरू
                    </h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Facebook -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2 nepali">
                                <i class="fab fa-facebook text-blue-600 mr-2"></i>
                                Facebook URL
                            </label>
                            <input type="url" name="facebook_url" 
                                   value="<?php echo e(old('facebook_url', $hostel->facebook_url)); ?>" 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all"
                                   placeholder="https://facebook.com/yourpage">
                            <p class="text-xs text-gray-500 mt-1 nepali">तपाईंको Facebook page को URL</p>
                        </div>

                        <!-- Instagram -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2 nepali">
                                <i class="fab fa-instagram text-pink-600 mr-2"></i>
                                Instagram URL
                            </label>
                            <input type="url" name="instagram_url" 
                                   value="<?php echo e(old('instagram_url', $hostel->instagram_url)); ?>"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all"
                                   placeholder="https://instagram.com/yourpage">
                            <p class="text-xs text-gray-500 mt-1 nepali">तपाईंको Instagram profile को URL</p>
                        </div>

                        <!-- Twitter -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2 nepali">
                                <i class="fab fa-twitter text-blue-400 mr-2"></i>
                                Twitter URL
                            </label>
                            <input type="url" name="twitter_url" 
                                   value="<?php echo e(old('twitter_url', $hostel->twitter_url)); ?>"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all"
                                   placeholder="https://twitter.com/yourpage">
                            <p class="text-xs text-gray-500 mt-1 nepali">तपाईंको Twitter profile को URL</p>
                        </div>

                        <!-- TikTok -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2 nepali">
                                <i class="fab fa-tiktok text-black mr-2"></i>
                                TikTok URL
                            </label>
                            <input type="url" name="tiktok_url" 
                                   value="<?php echo e(old('tiktok_url', $hostel->tiktok_url)); ?>"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all"
                                   placeholder="https://tiktok.com/@yourpage">
                            <p class="text-xs text-gray-500 mt-1 nepali">तपाईंको TikTok profile को URL</p>
                        </div>

                        <!-- WhatsApp -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2 nepali">
                                <i class="fab fa-whatsapp text-green-600 mr-2"></i>
                                WhatsApp Number
                            </label>
                            <input type="text" name="whatsapp_number" 
                                   value="<?php echo e(old('whatsapp_number', $hostel->whatsapp_number)); ?>"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all"
                                   placeholder="9800000000">
                            <p class="text-xs text-gray-500 mt-1 nepali">WhatsApp number (country code बिना)</p>
                        </div>

                        <!-- YouTube -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2 nepali">
                                <i class="fab fa-youtube text-red-600 mr-2"></i>
                                YouTube URL
                            </label>
                            <input type="url" name="youtube_url" 
                                   value="<?php echo e(old('youtube_url', $hostel->youtube_url)); ?>"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all"
                                   placeholder="https://youtube.com/c/yourchannel">
                            <p class="text-xs text-gray-500 mt-1 nepali">तपाईंको YouTube channel को URL</p>
                        </div>

                        <!-- LinkedIn -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2 nepali">
                                <i class="fab fa-linkedin text-blue-700 mr-2"></i>
                                LinkedIn URL
                            </label>
                            <input type="url" name="linkedin_url" 
                                   value="<?php echo e(old('linkedin_url', $hostel->linkedin_url)); ?>"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all"
                                   placeholder="https://linkedin.com/company/yourcompany">
                            <p class="text-xs text-gray-500 mt-1 nepali">तपाईंको LinkedIn page को URL</p>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex flex-wrap gap-3 pt-6 border-t border-gray-200">
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors nepali">
                        <i class="fas fa-eye mr-2"></i>पूर्वावलोकन हेर्नुहोस्
                    </button>

                    <?php if($hostel->is_published): ?>
                        <form method="POST" action="<?php echo e(route('owner.public-page.unpublish')); ?>" class="inline">
                            <?php echo csrf_field(); ?>
                            <button type="submit" class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition-colors nepali">
                                <i class="fas fa-eye-slash mr-2"></i>अप्रकाशित गर्नुहोस्
                            </button>
                        </form>
                    <?php else: ?>
                        <form method="POST" action="<?php echo e(route('owner.public-page.publish')); ?>" class="inline">
                            <?php echo csrf_field(); ?>
                            <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors nepali">
                                <i class="fas fa-globe mr-2"></i>प्रकाशित गर्नुहोस्
                            </button>
                        </form>
                    <?php endif; ?>

                    <?php if($hostel->is_published): ?>
                        <a href="<?php echo e(route('hostels.show', $hostel->slug)); ?>" target="_blank" class="px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition-colors nepali">
                            <i class="fas fa-external-link-alt mr-2"></i>लाइव पृष्ठ हेर्नुहोस्
                        </a>
                    <?php endif; ?>
                </div>
            </form>

            <!-- Status Card -->
            <div class="mt-8 p-4 bg-gray-50 rounded-lg border">
                <h3 class="font-medium text-gray-800 mb-2 nepali">पृष्ठ स्थिति</h3>
                <div class="flex items-center space-x-2">
                    <?php if($hostel->is_published): ?>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                            <i class="fas fa-check-circle mr-1"></i>
                            <span class="nepali">प्रकाशित</span>
                        </span>
                        <span class="text-sm text-gray-600 nepali">
                            प्रकाशित मिति: <?php echo e($hostel->published_at ? $hostel->published_at->format('Y-m-d') : '—'); ?>

                        </span>
                    <?php else: ?>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                            <i class="fas fa-clock mr-1"></i>
                            <span class="nepali">अप्रकाशित</span>
                        </span>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.owner', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\HostelHub\resources\views\owner\public-page\edit.blade.php ENDPATH**/ ?>