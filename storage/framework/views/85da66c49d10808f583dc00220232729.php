<?php $__env->startSection('content'); ?>
<div class="container mx-auto px-4 py-6">
    
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">नयाँ विद्यार्थी दर्ता</h1>
        <a href="<?php echo e(route('owner.students.index')); ?>"
           class="inline-flex items-center bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium px-4 py-2 rounded-lg shadow transition duration-300 no-underline z-10"
           style="text-decoration: none;">
            ⬅ फर्कनुहोस्
        </a>
    </div>

    
    <?php if(session('success')): ?>
        <div class="bg-green-50 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
            <div class="flex items-center">
                <span class="text-lg">✅</span>
                <span class="ml-2 font-medium"><?php echo e(session('success')); ?></span>
            </div>
        </div>
    <?php endif; ?>

    
    <?php if($errors->any()): ?>
        <div class="bg-red-50 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
            <strong class="font-medium">गल्तीहरू:</strong>
            <ul class="list-disc ml-6 mt-1">
                <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <li><?php echo e($error); ?></li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </ul>
        </div>
    <?php endif; ?>

    
    <form action="<?php echo e(route('owner.students.store')); ?>" method="POST" class="bg-white shadow-md rounded-lg p-6" id="studentForm">
        <?php echo csrf_field(); ?>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            
            <div>
                
                <div class="mb-4">
                    <label for="user_id" class="block font-semibold mb-2">पहिले नै रजिस्टर्ड प्रयोगकर्ता छान्नुहोस् (वैकल्पिक)</label>
                    <p class="text-sm text-gray-500 mb-2">
                        यदि विद्यार्थी पहिले नै registered user हुन् भने, यहाँबाट छान्नुहोस्।  
                        नभए तलको form भरेर नयाँ विद्यार्थी दर्ता गर्नुहोस्।
                    </p>
                    <select name="user_id" id="user_id" class="mt-1 block w-full border rounded-lg px-3 py-2 focus:ring focus:ring-blue-300">
    <option value="">-- नयाँ विद्यार्थी दर्ता गर्नुहोस् --</option>
                        <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($user->id); ?>" <?php echo e(old('user_id') == $user->id ? 'selected' : ''); ?>>
                                <?php echo e($user->name); ?> (<?php echo e($user->email); ?>)
                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                    <?php if($users->isEmpty()): ?>
                        <p class="text-sm text-red-500 mt-1">
                            ❌ कुनै पनि available user छैन। तलको form भरेर नयाँ विद्यार्थी दर्ता गर्नुहोस्।
                        </p>
                    <?php endif; ?>
                </div>

                
                <div class="mb-4">
                    <label for="name" class="block text-sm font-medium text-gray-700">नाम *</label>
                    <input type="text" name="name" value="<?php echo e(old('name')); ?>" required 
                           class="mt-1 block w-full border rounded-lg px-3 py-2 focus:ring focus:ring-blue-300"
                           placeholder="विद्यार्थीको पुरा नाम">
                    <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <p class="text-red-500 text-sm mt-1"><?php echo e($message); ?></p>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                
                <div class="mb-4">
                    <label for="email" class="block text-sm font-medium text-gray-700">इमेल (वैकल्पिक)</label>
                    <input type="email" name="email" value="<?php echo e(old('email')); ?>" 
                           class="mt-1 block w-full border rounded-lg px-3 py-2 focus:ring focus:ring-blue-300"
                           placeholder="student@example.com">
                    <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <p class="text-red-500 text-sm mt-1"><?php echo e($message); ?></p>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                
                <div class="mb-4">
                    <label for="college_id" class="block text-sm font-medium text-gray-700">कलेज *</label>
                    <select name="college_id" id="college_id" required
                            class="mt-1 block w-full border rounded-lg px-3 py-2 focus:ring focus:ring-blue-300">
                        <option value="">-- कलेज छान्नुहोस् --</option>
                        <?php $__currentLoopData = $colleges; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $college): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($college->id); ?>" <?php echo e(old('college_id') == $college->id ? 'selected' : ''); ?>>
                                <?php echo e($college->name); ?>

                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <option value="others" <?php echo e(old('college_id') == 'others' ? 'selected' : ''); ?>>अन्य (Others)</option>
                    </select>
                    
                    <!-- Hidden input for manual college entry -->
                    <div id="other_college_field" class="mt-2 <?php echo e(old('college_id') == 'others' ? '' : 'hidden'); ?>">
                        <input type="text" name="other_college" value="<?php echo e(old('other_college')); ?>" 
                               class="mt-1 block w-full border rounded-lg px-3 py-2 focus:ring focus:ring-blue-300" 
                               placeholder="कलेजको नाम लेख्नुहोस्"
                               id="other_college_input">
                        <?php $__errorArgs = ['other_college'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <p class="text-red-500 text-sm mt-1"><?php echo e($message); ?></p>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <?php if($colleges->isEmpty()): ?>
                        <p class="text-sm text-red-500 mt-1">
                            ❌ कुनै पनि कलेज भेटिएन। कृपया नयाँ कलेज थप्नुहोस्।
                        </p>
                    <?php endif; ?>
                    <?php $__errorArgs = ['college_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <p class="text-red-500 text-sm mt-1"><?php echo e($message); ?></p>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                
                <div class="mb-4">
                    <label for="phone" class="block text-sm font-medium text-gray-700">फोन *</label>
                    <input type="text" name="phone" value="<?php echo e(old('phone')); ?>" required 
                           maxlength="15"
                           class="mt-1 block w-full border rounded-lg px-3 py-2 focus:ring focus:ring-blue-300"
                           placeholder="98XXXXXXXX">
                    <?php $__errorArgs = ['phone'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <p class="text-red-500 text-sm mt-1"><?php echo e($message); ?></p>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                
                <div class="mb-4">
                    <label for="dob" class="block text-sm font-medium text-gray-700">जन्म मिति (वैकल्पिक)</label>
                    <input type="date" name="dob" value="<?php echo e(old('dob')); ?>" 
                           min="1950-01-01" max="<?php echo e(date('Y-m-d')); ?>"
                           class="mt-1 block w-full border rounded-lg px-3 py-2 focus:ring focus:ring-blue-300">
                    <?php $__errorArgs = ['dob'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <p class="text-red-500 text-sm mt-1"><?php echo e($message); ?></p>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                
                <div class="mb-4">
                    <label for="gender" class="block text-sm font-medium text-gray-700">लिङ्ग (वैकल्पिक)</label>
                    <select name="gender" class="mt-1 block w-full border rounded-lg px-3 py-2 focus:ring focus:ring-blue-300">
                        <option value="">-- लिङ्ग छान्नुहोस् --</option>
                        <option value="male" <?php echo e(old('gender') == 'male' ? 'selected' : ''); ?>>पुरुष</option>
                        <option value="female" <?php echo e(old('gender') == 'female' ? 'selected' : ''); ?>>महिला</option>
                        <option value="other" <?php echo e(old('gender') == 'other' ? 'selected' : ''); ?>>अन्य</option>
                    </select>
                    <?php $__errorArgs = ['gender'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <p class="text-red-500 text-sm mt-1"><?php echo e($message); ?></p>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
            </div>

            
            <div>
                
                <div class="mb-4">
                    <label for="guardian_name" class="block text-sm font-medium text-gray-700">अभिभावकको नाम *</label>
                    <input type="text" name="guardian_name" value="<?php echo e(old('guardian_name')); ?>" required 
                           class="mt-1 block w-full border rounded-lg px-3 py-2 focus:ring focus:ring-blue-300"
                           placeholder="अभिभावकको पुरा नाम">
                    <?php $__errorArgs = ['guardian_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <p class="text-red-500 text-sm mt-1"><?php echo e($message); ?></p>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                
                <div class="mb-4">
                    <label for="guardian_contact" class="block text-sm font-medium text-gray-700">अभिभावकको फोन *</label>
                    <input type="text" name="guardian_contact" value="<?php echo e(old('guardian_contact')); ?>" required 
                           maxlength="15"
                           class="mt-1 block w-full border rounded-lg px-3 py-2 focus:ring focus:ring-blue-300"
                           placeholder="98XXXXXXXX">
                    <?php $__errorArgs = ['guardian_contact'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <p class="text-red-500 text-sm mt-1"><?php echo e($message); ?></p>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                
                <div class="mb-4">
                    <label for="guardian_relation" class="block text-sm font-medium text-gray-700">नाता *</label>
                    <input type="text" name="guardian_relation" value="<?php echo e(old('guardian_relation')); ?>" required 
                           class="mt-1 block w-full border rounded-lg px-3 py-2 focus:ring focus:ring-blue-300"
                           placeholder="जस्तै: बुबा, आमा, दाजु, etc.">
                    <?php $__errorArgs = ['guardian_relation'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <p class="text-red-500 text-sm mt-1"><?php echo e($message); ?></p>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                
                <div class="mb-4">
                    <label for="room_id" class="block text-sm font-medium text-gray-700">कोठा छान्नुहोस् (वैकल्पिक)</label>
                    <select name="room_id" class="mt-1 block w-full border rounded-lg px-3 py-2 focus:ring focus:ring-blue-300">
                        <option value="">-- कोठा तोकिएको छैन --</option>
                        <?php $__empty_1 = true; $__currentLoopData = $rooms; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $room): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <option value="<?php echo e($room->id); ?>" <?php echo e(old('room_id') == $room->id ? 'selected' : ''); ?>>
                                <?php echo e($room->room_number); ?> 
                                (<?php echo e($room->hostel?->name ?? 'Unknown Hostel'); ?>)
                                - <?php echo e($room->type ?? 'Standard'); ?>

                                - स्थिति: <?php echo e($room->nepali_status ?? $room->status); ?>

                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <option disabled>❌ कुनै पनि कोठा उपलब्ध छैन। कृपया पहिले कोठा सिर्जना गर्नुहोस्।</option>
                        <?php endif; ?>
                    </select>
                    <?php if($rooms->isEmpty()): ?>
                        <p class="text-sm text-red-500 mt-1">
                            ❌ कुनै पनि कोठा उपलब्ध छैन। कृपया पहिले कोठा सिर्जना गर्नुहोस्।
                        </p>
                    <?php else: ?>
                        <p class="text-sm text-green-600 mt-1">
                            ✅ कुल <?php echo e($rooms->count()); ?> वटा कोठाहरू उपलब्ध छन्
                        </p>
                    <?php endif; ?>
                    <?php $__errorArgs = ['room_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <p class="text-red-500 text-sm mt-1"><?php echo e($message); ?></p>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                
                <div class="mb-4">
                    <label for="admission_date" class="block text-sm font-medium text-gray-700">भर्ना मिति *</label>
                    <input type="date" name="admission_date" value="<?php echo e(old('admission_date', date('Y-m-d'))); ?>" 
                           min="2000-01-01" max="<?php echo e(date('Y-m-d', strtotime('+1 year'))); ?>"
                           required class="mt-1 block w-full border rounded-lg px-3 py-2 focus:ring focus:ring-blue-300">
                    <?php $__errorArgs = ['admission_date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <p class="text-red-500 text-sm mt-1"><?php echo e($message); ?></p>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700">स्थिति *</label>
                        <select name="status" class="mt-1 block w-full border rounded-lg px-3 py-2 focus:ring focus:ring-blue-300" required>
                            <option value="pending" <?php echo e(old('status', 'pending') == 'pending' ? 'selected' : ''); ?>>पेन्डिङ</option>
                            <option value="approved" <?php echo e(old('status') == 'approved' ? 'selected' : ''); ?>>स्वीकृत</option>
                            <option value="active" <?php echo e(old('status') == 'active' ? 'selected' : ''); ?>>सक्रिय</option>
                            <option value="inactive" <?php echo e(old('status') == 'inactive' ? 'selected' : ''); ?>>निष्क्रिय</option>
                        </select>
                        <?php $__errorArgs = ['status'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <p class="text-red-500 text-sm mt-1"><?php echo e($message); ?></p>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                    <div>
                        <label for="payment_status" class="block text-sm font-medium text-gray-700">भुक्तानी स्थिति *</label>
                        <select name="payment_status" class="mt-1 block w-full border rounded-lg px-3 py-2 focus:ring focus:ring-blue-300" required>
                            <option value="pending" <?php echo e(old('payment_status', 'pending') == 'pending' ? 'selected' : ''); ?>>पेन्डिङ</option>
                            <option value="paid" <?php echo e(old('payment_status') == 'paid' ? 'selected' : ''); ?>>भुक्तानी भएको</option>
                            <option value="unpaid" <?php echo e(old('payment_status') == 'unpaid' ? 'selected' : ''); ?>>भुक्तानी नभएको</option>
                        </select>
                        <?php $__errorArgs = ['payment_status'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <p class="text-red-500 text-sm mt-1"><?php echo e($message); ?></p>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                </div>
            </div>
        </div>

        
        <div class="mt-6">
            <label for="address" class="block text-sm font-medium text-gray-700">ठेगाना *</label>
            <textarea name="address" rows="3" required 
                      class="mt-1 block w-full border rounded-lg px-3 py-2 focus:ring focus:ring-blue-300"
                      placeholder="विद्यार्थीको हालको ठेगाना"><?php echo e(old('address')); ?></textarea>
            <?php $__errorArgs = ['address'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                <p class="text-red-500 text-sm mt-1"><?php echo e($message); ?></p>
            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>

        <div class="mt-4">
            <label for="guardian_address" class="block text-sm font-medium text-gray-700">अभिभावकको ठेगाना *</label>
            <textarea name="guardian_address" rows="3" required 
                      class="mt-1 block w-full border rounded-lg px-3 py-2 focus:ring focus:ring-blue-300"
                      placeholder="अभिभावकको स्थायी ठेगाना"><?php echo e(old('guardian_address')); ?></textarea>
            <?php $__errorArgs = ['guardian_address'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                <p class="text-red-500 text-sm mt-1"><?php echo e($message); ?></p>
            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>

        
        <input type="hidden" name="organization_id" value="<?php echo e(auth()->user()->organization_id); ?>">

        
        <div class="mt-6 flex justify-end space-x-4">
            <a href="<?php echo e(route('owner.students.index')); ?>" 
               class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-lg shadow flex items-center gap-1 transition duration-300">
                ❌ रद्द गर्नुहोस्
            </a>
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg shadow flex items-center gap-1 transition duration-300">
                💾 विद्यार्थी दर्ता गर्नुहोस्
            </button>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // College "Others" option functionality
    const collegeSelect = document.getElementById('college_id');
    const otherCollegeField = document.getElementById('other_college_field');
    const otherCollegeInput = document.getElementById('other_college_input');
    
    if (collegeSelect && otherCollegeField) {
        collegeSelect.addEventListener('change', function() {
            if (this.value === 'others') {
                otherCollegeField.classList.remove('hidden');
                if (otherCollegeInput) {
                    otherCollegeInput.required = true;
                }
            } else {
                otherCollegeField.classList.add('hidden');
                if (otherCollegeInput) {
                    otherCollegeInput.required = false;
                    otherCollegeInput.value = '';
                }
            }
        });
        
        // Initialize on page load
        if (collegeSelect.value === 'others') {
            otherCollegeField.classList.remove('hidden');
            if (otherCollegeInput) {
                otherCollegeInput.required = true;
            }
        }
    }

    // ✅ FIXED: Form submit भन्दा अगाडि final validation
    const form = document.getElementById('studentForm');
    if (form) {
        form.addEventListener('submit', function(e) {
            let isValid = true;
            let firstErrorField = null;

            // Required fields validation
            const requiredFields = form.querySelectorAll('[required]');
            requiredFields.forEach(field => {
                if (!field.value.trim()) {
                    isValid = false;
                    if (!firstErrorField) {
                        firstErrorField = field;
                    }
                    field.classList.add('border-red-500');
                } else {
                    field.classList.remove('border-red-500');
                }
            });

            // College validation
            const collegeSelect = document.getElementById('college_id');
            if (collegeSelect && collegeSelect.value === 'others') {
                const otherCollege = document.querySelector('input[name="other_college"]');
                if (!otherCollege || !otherCollege.value.trim()) {
                    isValid = false;
                    if (!firstErrorField) {
                        firstErrorField = otherCollege;
                    }
                    if (otherCollege) {
                        otherCollege.classList.add('border-red-500');
                    }
                    alert('कृपया कलेजको नाम लेख्नुहोस्।');
                }
            }

            // Phone number validation
            const phone = document.querySelector('input[name="phone"]');
            if (phone && phone.value) {
                const phoneRegex = /^[0-9+\-\s()]{7,15}$/;
                if (!phoneRegex.test(phone.value)) {
                    isValid = false;
                    if (!firstErrorField) {
                        firstErrorField = phone;
                    }
                    phone.classList.add('border-red-500');
                    alert('कृपया वैध फोन नम्बर लेख्नुहोस्।');
                }
            }

            // Guardian contact validation
            const guardianContact = document.querySelector('input[name="guardian_contact"]');
            if (guardianContact && guardianContact.value) {
                const phoneRegex = /^[0-9+\-\s()]{7,15}$/;
                if (!phoneRegex.test(guardianContact.value)) {
                    isValid = false;
                    if (!firstErrorField) {
                        firstErrorField = guardianContact;
                    }
                    guardianContact.classList.add('border-red-500');
                    alert('कृपया वैध अभिभावकको फोन नम्बर लेख्नुहोस्।');
                }
            }

            if (!isValid) {
                e.preventDefault();
                if (firstErrorField) {
                    firstErrorField.focus();
                    firstErrorField.scrollIntoView({ behavior: 'smooth', block: 'center' });
                }
                return false;
            }

            // Show loading state
            const submitButton = form.querySelector('button[type="submit"]');
            if (submitButton) {
                submitButton.disabled = true;
                submitButton.innerHTML = '⏳ दर्ता हुँदैछ...';
            }
        });
    }

    // Real-time validation
    const inputs = form.querySelectorAll('input, select, textarea');
    inputs.forEach(input => {
        input.addEventListener('input', function() {
            if (this.hasAttribute('required') && !this.value.trim()) {
                this.classList.add('border-red-500');
            } else {
                this.classList.remove('border-red-500');
            }
        });

        input.addEventListener('blur', function() {
            if (this.hasAttribute('required') && !this.value.trim()) {
                this.classList.add('border-red-500');
            } else {
                this.classList.remove('border-red-500');
            }
        });
    });
});
</script>

<style>
.border-red-500 {
    border-color: #ef4444 !important;
    border-width: 2px;
}
</style>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.owner', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\My Projects\HostelHub\resources\views\owner\students\create.blade.php ENDPATH**/ ?>