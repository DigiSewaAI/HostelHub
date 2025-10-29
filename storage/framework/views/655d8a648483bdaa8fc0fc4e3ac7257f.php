

<?php $__env->startSection('title', 'नयाँ ग्यालरी थप्नुहोस्'); ?>

<?php $__env->startSection('content'); ?>
<div class="bg-white rounded-2xl shadow-sm p-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800 nepali">नयाँ ग्यालरी थप्नुहोस्</h1>
        <a href="<?php echo e(route('owner.galleries.index')); ?>" 
           class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-xl font-medium no-underline transition-colors">
            <i class="fas fa-arrow-left mr-2"></i>फिर्ता जानुहोस्
        </a>
    </div>

    <form action="<?php echo e(route('owner.galleries.store')); ?>" method="POST" enctype="multipart/form-data">
        <?php echo csrf_field(); ?>
        
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
                                   placeholder="ग्यालरीको शीर्षक लेख्नुहोस्"
                                   value="<?php echo e(old('title')); ?>">
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
                                      placeholder="ग्यालरीको छोटो विवरण लेख्नुहोस्"><?php echo e(old('description')); ?></textarea>
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
                                    <option value="<?php echo e($key); ?>" <?php echo e(old('category') == $key ? 'selected' : ''); ?>><?php echo e($value); ?></option>
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
                                <option value="">मिडिया प्रकार छान्नुहोस्</option>
                                <option value="photo" <?php echo e(old('media_type') == 'photo' ? 'selected' : ''); ?>>तस्बिर</option>
                                <option value="local_video" <?php echo e(old('media_type') == 'local_video' ? 'selected' : ''); ?>>भिडियो फाइल</option>
                                <option value="external_video" <?php echo e(old('media_type') == 'external_video' ? 'selected' : ''); ?>>यूट्युब लिङ्क</option>
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
                        <div id="file_upload_field">
                            <label for="media" class="block text-sm font-medium text-gray-700 mb-2 nepali">फाइल छान्नुहोस् *</label>
                            <input type="file" name="media[]" id="media" multiple
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
                            <?php $__errorArgs = ['media.*'];
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
                        <div id="external_link_field" class="hidden">
                            <label for="external_link" class="block text-sm font-medium text-gray-700 mb-2 nepali">यूट्युब लिङ्क *</label>
                            <input type="url" name="external_link" id="external_link"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                   placeholder="https://www.youtube.com/watch?v=..."
                                   value="<?php echo e(old('external_link')); ?>">
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
                                <option value="active" <?php echo e(old('status') == 'active' ? 'selected' : ''); ?>>सक्रिय</option>
                                <option value="inactive" <?php echo e(old('status') == 'inactive' ? 'selected' : ''); ?>>निष्क्रिय</option>
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
                                   <?php echo e(old('featured') ? 'checked' : ''); ?>

                                   class="w-5 h-5 text-blue-600 rounded focus:ring-blue-500">
                        </div>
                    </div>
                </div>

                <!-- Preview -->
                <div class="bg-gray-50 p-6 rounded-xl border border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-800 mb-4 nepali">पूर्वावलोकन</h2>
                    
                    <div class="aspect-video bg-gray-200 rounded-lg flex items-center justify-center" id="preview_container">
                        <div class="text-center text-gray-500">
                            <i class="fas fa-image text-3xl mb-2"></i>
                            <p class="text-sm nepali">पूर्वावलोकन यहाँ देखाइनेछ</p>
                        </div>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="bg-blue-50 p-6 rounded-xl border border-blue-200">
                    <button type="submit" 
                            class="w-full bg-blue-600 hover:bg-blue-700 text-white py-3 px-6 rounded-xl font-semibold transition-colors nepali">
                        <i class="fas fa-save mr-2"></i>ग्यालरी सेभ गर्नुहोस्
                    </button>
                    <p class="text-xs text-blue-600 text-center mt-2 nepali">ग्यालरी सेभ भएपछि सार्वजनिक पृष्ठमा देखाइनेछ</p>
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
    const previewContainer = document.getElementById('preview_container');
    const fileInput = document.getElementById('media');
    const externalLinkInput = document.getElementById('external_link');

    function updateMediaFields() {
        const mediaType = mediaTypeSelect.value;
        
        if (mediaType === 'external_video') {
            fileUploadField.classList.add('hidden');
            externalLinkField.classList.remove('hidden');
            fileInput.removeAttribute('required');
            externalLinkInput.setAttribute('required', 'required');
            
            // Show YouTube preview if URL is already entered
            if (externalLinkInput.value) {
                updateYouTubePreview(externalLinkInput.value);
            } else {
                previewContainer.innerHTML = `
                    <div class="text-center text-gray-500">
                        <i class="fab fa-youtube text-3xl mb-2 text-red-600"></i>
                        <p class="text-sm nepali">यूट्युब भिडियो पूर्वावलोकन</p>
                    </div>
                `;
            }
        } else {
            fileUploadField.classList.remove('hidden');
            externalLinkField.classList.add('hidden');
            externalLinkInput.removeAttribute('required');
            fileInput.setAttribute('required', 'required');
            
            if (mediaType === 'photo') {
                previewContainer.innerHTML = `
                    <div class="text-center text-gray-500">
                        <i class="fas fa-image text-3xl mb-2"></i>
                        <p class="text-sm nepali">तस्बिर पूर्वावलोकन</p>
                    </div>
                `;
            } else if (mediaType === 'local_video') {
                previewContainer.innerHTML = `
                    <div class="text-center text-gray-500">
                        <i class="fas fa-video text-3xl mb-2"></i>
                        <p class="text-sm nepali">भिडियो पूर्वावलोकन</p>
                    </div>
                `;
            }
        }
    }

    function updateYouTubePreview(url) {
        const youtubeId = getYouTubeId(url);
        if (youtubeId) {
            previewContainer.innerHTML = `
                <div class="relative w-full h-full">
                    <img src="https://img.youtube.com/vi/${youtubeId}/mqdefault.jpg" 
                         class="w-full h-full object-cover rounded-lg"
                         alt="YouTube preview">
                    <div class="absolute inset-0 flex items-center justify-center">
                        <i class="fab fa-youtube text-white text-4xl opacity-80"></i>
                    </div>
                </div>
            `;
        } else {
            previewContainer.innerHTML = `
                <div class="text-center text-gray-500">
                    <i class="fab fa-youtube text-3xl mb-2 text-red-600"></i>
                    <p class="text-sm nepali">अमान्य यूट्युब लिङ्क</p>
                </div>
            `;
        }
    }

    function getYouTubeId(url) {
        if (!url) return null;
        const pattern = /(?:youtube\.com\/(?:[^\/\n\s]+\/\S+\/|(?:v|e(?:mbed)?)\/|\S*?[?&]v=)|youtu\.be\/)([a-zA-Z0-9_-]{11})/;
        const matches = url.match(pattern);
        return matches ? matches[1] : null;
    }

    // Handle file preview for multiple files
    fileInput.addEventListener('change', function(e) {
        const files = e.target.files;
        if (files.length > 0) {
            const file = files[0];
            if (file.type.startsWith('image/')) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    previewContainer.innerHTML = `
                        <div class="relative w-full h-full">
                            <img src="${e.target.result}" class="w-full h-full object-cover rounded-lg" alt="Preview">
                            <div class="absolute bottom-2 right-2 bg-black bg-opacity-50 text-white px-2 py-1 rounded text-sm">
                                ${files.length} फाइल${files.length > 1 ? 'हरू' : ''}
                            </div>
                        </div>
                    `;
                };
                reader.readAsDataURL(file);
            } else if (file.type.startsWith('video/')) {
                previewContainer.innerHTML = `
                    <div class="text-center text-gray-500">
                        <i class="fas fa-video text-3xl mb-2 text-blue-600"></i>
                        <p class="text-sm nepali">भिडियो फाइल: ${file.name}</p>
                        <p class="text-xs text-gray-400 nepali">${files.length} फाइल${files.length > 1 ? 'हरू' : ''} छान्नुभयो</p>
                    </div>
                `;
            }
        }
    });

    // Handle external link input for YouTube preview
    externalLinkInput.addEventListener('input', function(e) {
        if (mediaTypeSelect.value === 'external_video') {
            updateYouTubePreview(e.target.value);
        }
    });

    mediaTypeSelect.addEventListener('change', updateMediaFields);
    
    // Initialize the form
    updateMediaFields();
});
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.owner', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\My Projects\HostelHub\resources\views\owner\galleries\create.blade.php ENDPATH**/ ?>