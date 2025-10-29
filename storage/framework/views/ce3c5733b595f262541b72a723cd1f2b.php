

<?php $__env->startSection('title', 'ग्यालरी सम्पादन गर्नुहोस्'); ?>

<?php $__env->startSection('content'); ?>
<div class="bg-white rounded-2xl shadow-sm p-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800 nepali">ग्यालरी सम्पादन गर्नुहोस्</h1>
        <a href="<?php echo e(route('owner.galleries.index')); ?>" 
           class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-xl font-medium no-underline transition-colors">
            <i class="fas fa-arrow-left mr-2"></i>फिर्ता जानुहोस्
        </a>
    </div>

    <form action="<?php echo e(route('owner.galleries.update', $gallery)); ?>" method="POST" enctype="multipart/form-data">
        <?php echo csrf_field(); ?>
        <?php echo method_field('PUT'); ?>
        
        <!-- Error Messages -->
        <?php if($errors->any()): ?>
        <div class="mb-6 bg-red-50 border border-red-200 rounded-xl p-4">
            <div class="flex items-center mb-2">
                <i class="fas fa-exclamation-triangle text-red-600 mr-2"></i>
                <h3 class="text-red-800 font-semibold nepali">तपाईंको फारममा त्रुटिहरू छन्</h3>
            </div>
            <ul class="list-disc list-inside text-red-700 text-sm">
                <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <li class="nepali"><?php echo e($error); ?></li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </ul>
        </div>
        <?php endif; ?>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Left Column -->
            <div class="space-y-6">
                <!-- Basic Information -->
                <div class="bg-gray-50 p-6 rounded-xl border border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-800 mb-4 nepali">आधारभूत जानकारी</h2>
                    
                    <div class="space-y-4">
                        <div>
                            <label for="title" class="block text-sm font-medium text-gray-700 mb-2 nepali">शीर्षक *</label>
                            <input type="text" name="title" id="title" required
                                   class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                   value="<?php echo e(old('title', $gallery->title)); ?>"
                                   placeholder="ग्यालरीको शीर्षक लेख्नुहोस्">
                            <?php $__errorArgs = ['title'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <p class="text-red-600 text-xs mt-1 nepali"><?php echo e($message); ?></p>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        <div>
                            <label for="description" class="block text-sm font-medium text-gray-700 mb-2 nepali">विवरण</label>
                            <textarea name="description" id="description" rows="3"
                                      class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                      placeholder="ग्यालरीको छोटो विवरण लेख्नुहोस्"><?php echo e(old('description', $gallery->description)); ?></textarea>
                            <?php $__errorArgs = ['description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <p class="text-red-600 text-xs mt-1 nepali"><?php echo e($message); ?></p>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        <div>
                            <label for="category" class="block text-sm font-medium text-gray-700 mb-2 nepali">श्रेणी *</label>
                            <select name="category" id="category" required
                                    class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                                <option value="">श्रेणी छान्नुहोस्</option>
                                <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($key); ?>" <?php echo e(old('category', $gallery->category) == $key ? 'selected' : ''); ?>><?php echo e($value); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                            <?php $__errorArgs = ['category'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <p class="text-red-600 text-xs mt-1 nepali"><?php echo e($message); ?></p>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                    </div>
                </div>

                <!-- Media Settings -->
                <div class="bg-gray-50 p-6 rounded-xl border border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-800 mb-4 nepali">मिडिया सेटिङहरू</h2>
                    
                    <div class="space-y-4">
                        <div>
                            <label for="media_type" class="block text-sm font-medium text-gray-700 mb-2 nepali">मिडिया प्रकार *</label>
                            <select name="media_type" id="media_type" required
                                    class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                                <option value="photo" <?php echo e(old('media_type', $gallery->media_type) == 'photo' ? 'selected' : ''); ?>>तस्बिर</option>
                                <option value="local_video" <?php echo e(old('media_type', $gallery->media_type) == 'local_video' ? 'selected' : ''); ?>>भिडियो फाइल</option>
                                <option value="external_video" <?php echo e(old('media_type', $gallery->media_type) == 'external_video' ? 'selected' : ''); ?>>यूट्युब लिङ्क</option>
                            </select>
                            <?php $__errorArgs = ['media_type'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <p class="text-red-600 text-xs mt-1 nepali"><?php echo e($message); ?></p>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        <!-- File Upload Field -->
                        <div id="file_upload_field" class="<?php echo e($gallery->media_type == 'external_video' ? 'hidden' : ''); ?>">
                            <label for="media" class="block text-sm font-medium text-gray-700 mb-2 nepali">नयाँ फाइल छान्नुहोस्</label>
                            <input type="file" name="media[]" id="media" 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                   accept="image/*,video/*">
                            <p class="text-xs text-gray-500 mt-1 nepali">अनुमतिहरू: JPEG, PNG, JPG, GIF, MP4, MOV, AVI (अधिकतम 100MB)</p>
                            <?php $__errorArgs = ['media'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <p class="text-red-600 text-xs mt-1 nepali"><?php echo e($message); ?></p>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        <!-- External Link Field -->
                        <div id="external_link_field" class="<?php echo e($gallery->media_type == 'external_video' ? '' : 'hidden'); ?>">
                            <label for="external_link" class="block text-sm font-medium text-gray-700 mb-2 nepali">यूट्युब लिङ्क *</label>
                            <input type="url" name="external_link" id="external_link"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                   placeholder="https://www.youtube.com/watch?v=..."
                                   value="<?php echo e(old('external_link', $gallery->external_link)); ?>">
                            <?php $__errorArgs = ['external_link'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <p class="text-red-600 text-xs mt-1 nepali"><?php echo e($message); ?></p>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        <!-- Status Field -->
                        <div>
                            <label for="status" class="block text-sm font-medium text-gray-700 mb-2 nepali">स्थिति *</label>
                            <select name="status" id="status" required
                                    class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                                <option value="active" <?php echo e(old('status', $gallery->is_active ? 'active' : 'inactive') == 'active' ? 'selected' : ''); ?>>सक्रिय</option>
                                <option value="inactive" <?php echo e(old('status', $gallery->is_active ? 'active' : 'inactive') == 'inactive' ? 'selected' : ''); ?>>निष्क्रिय</option>
                            </select>
                            <?php $__errorArgs = ['status'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <p class="text-red-600 text-xs mt-1 nepali"><?php echo e($message); ?></p>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column -->
            <div class="space-y-6">
                <!-- Settings -->
                <div class="bg-gray-50 p-6 rounded-xl border border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-800 mb-4 nepali">सेटिङहरू</h2>
                    
                    <div class="space-y-4">
                        <div class="flex items-center justify-between">
                            <div>
                                <label for="featured" class="text-sm font-medium text-gray-700 nepali">फिचर्ड ग्यालरी बनाउनुहोस्</label>
                                <p class="text-xs text-gray-500 nepali">यो ग्यालरी होमपेजमा देखाइनेछ</p>
                            </div>
                            <input type="checkbox" name="featured" id="featured" value="1"
                                   <?php echo e(old('featured', $gallery->is_featured) ? 'checked' : ''); ?>

                                   class="w-5 h-5 text-blue-600 rounded focus:ring-blue-500">
                        </div>
                    </div>
                </div>

                <!-- Current Media Preview -->
                <div class="bg-gray-50 p-6 rounded-xl border border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-800 mb-4 nepali">हालको मिडिया</h2>
                    
                    <div class="aspect-video bg-gray-200 rounded-lg overflow-hidden">
                        <?php if($gallery->media_type === 'photo'): ?>
                            <img src="<?php echo e($gallery->thumbnail_url); ?>" 
                                 alt="<?php echo e($gallery->title); ?>"
                                 class="w-full h-full object-cover">
                        <?php elseif($gallery->media_type === 'external_video'): ?>
                            <div class="w-full h-full bg-gradient-to-br from-red-500 to-pink-500 flex items-center justify-center">
                                <i class="fab fa-youtube text-white text-4xl"></i>
                            </div>
                            <div class="p-3 bg-gray-800 text-white text-sm">
                                <p class="nepali truncate"><?php echo e($gallery->external_link); ?></p>
                            </div>
                        <?php else: ?>
                            <div class="w-full h-full bg-gradient-to-br from-blue-500 to-cyan-500 flex items-center justify-center">
                                <i class="fas fa-video text-white text-4xl"></i>
                            </div>
                        <?php endif; ?>
                    </div>
                    
                    <div class="mt-3 text-center text-sm text-gray-600">
                        <p class="nepali">मिडिया प्रकार: <?php echo e($gallery->media_type_nepali); ?></p>
                        <?php if($gallery->media_type === 'external_video'): ?>
                            <a href="<?php echo e($gallery->external_link); ?>" target="_blank" class="text-blue-600 hover:text-blue-800 nepali text-xs">
                                लिङ्क हेर्नुहोस्
                            </a>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="bg-blue-50 p-6 rounded-xl border border-blue-200">
                    <button type="submit" 
                            class="w-full bg-blue-600 hover:bg-blue-700 text-white py-3 px-6 rounded-xl font-semibold transition-colors nepali">
                        <i class="fas fa-save mr-2"></i>परिवर्तनहरू सेभ गर्नुहोस्
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const mediaTypeSelect = document.getElementById('media_type');
    const fileUploadField = document.getElementById('file_upload_field');
    const externalLinkField = document.getElementById('external_link_field');

    function updateMediaFields() {
        const mediaType = mediaTypeSelect.value;
        
        if (mediaType === 'external_video') {
            fileUploadField.classList.add('hidden');
            externalLinkField.classList.remove('hidden');
        } else {
            fileUploadField.classList.remove('hidden');
            externalLinkField.classList.add('hidden');
        }
    }

    mediaTypeSelect.addEventListener('change', updateMediaFields);
});
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.owner', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\My Projects\HostelHub\resources\views\owner\galleries\edit.blade.php ENDPATH**/ ?>