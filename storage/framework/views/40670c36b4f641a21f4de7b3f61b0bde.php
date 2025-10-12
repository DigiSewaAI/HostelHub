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

    
    <form action="<?php echo e(route('owner.students.store')); ?>" method="POST" class="bg-white shadow-md rounded-lg p-6">
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
                        <option value="">-- पहिले नै रजिस्टर्ड विद्यार्थी छान्नुहोस् (वैकल्पिक) --</option>
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
                    <label for="name" class="block text-sm font-medium text-gray-700">नाम</label>
                    <input type="text" name="name" value="<?php echo e(old('name')); ?>" required class="mt-1 block w-full border rounded-lg px-3 py-2 focus:ring focus:ring-blue-300">
                </div>

                
                <div class="mb-4">
                    <label for="email" class="block text-sm font-medium text-gray-700">इमेल</label>
                    <input type="email" name="email" value="<?php echo e(old('email')); ?>" required class="mt-1 block w-full border rounded-lg px-3 py-2 focus:ring focus:ring-blue-300">
                </div>

                
                <div class="mb-4">
                    <label for="college_id" class="block text-sm font-medium text-gray-700">कलेज</label>
                    <select name="college_id" id="college_id" class="mt-1 block w-full border rounded-lg px-3 py-2 focus:ring focus:ring-blue-300">
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
                        <input type="text" name="other_college" value="<?php echo e(old('other_college')); ?>" class="mt-1 block w-full border rounded-lg px-3 py-2 focus:ring focus:ring-blue-300" placeholder="कलेजको नाम लेख्नुहोस्">
                    </div>

                    <?php if($colleges->isEmpty()): ?>
                        <p class="text-sm text-red-500 mt-1">
                            ❌ कुनै पनि कलेज भेटिएन। कृपया नयाँ कलेज थप्नुहोस्।
                        </p>
                    <?php endif; ?>
                </div>

                
                <div class="mb-4">
                    <label for="phone" class="block text-sm font-medium text-gray-700">फोन</label>
                    <input type="text" name="phone" value="<?php echo e(old('phone')); ?>" required class="mt-1 block w-full border rounded-lg px-3 py-2 focus:ring focus:ring-blue-300">
                </div>

                
                <div class="mb-4">
                    <label for="dob" class="block text-sm font-medium text-gray-700">जन्म मिति</label>
                    <input type="date" name="dob" value="<?php echo e(old('dob')); ?>" 
                           min="1950-01-01" max="<?php echo e(date('Y-m-d')); ?>"
                           class="mt-1 block w-full border rounded-lg px-3 py-2 focus:ring focus:ring-blue-300">
                </div>

                
                <div class="mb-4">
                    <label for="gender" class="block text-sm font-medium text-gray-700">लिङ्ग</label>
                    <select name="gender" class="mt-1 block w-full border rounded-lg px-3 py-2 focus:ring focus:ring-blue-300">
                        <option value="">-- लिङ्ग छान्नुहोस् --</option>
                        <option value="male" <?php echo e(old('gender') == 'male' ? 'selected' : ''); ?>>पुरुष</option>
                        <option value="female" <?php echo e(old('gender') == 'female' ? 'selected' : ''); ?>>महिला</option>
                        <option value="other" <?php echo e(old('gender') == 'other' ? 'selected' : ''); ?>>अन्य</option>
                    </select>
                </div>
            </div>

            
            <div>
                
                <div class="mb-4">
                    <label for="guardian_name" class="block text-sm font-medium text-gray-700">अभिभावकको नाम</label>
                    <input type="text" name="guardian_name" value="<?php echo e(old('guardian_name')); ?>" required class="mt-1 block w-full border rounded-lg px-3 py-2 focus:ring focus:ring-blue-300">
                </div>

                
                <div class="mb-4">
                    <label for="guardian_phone" class="block text-sm font-medium text-gray-700">अभिभावकको फोन</label>
                    <input type="text" name="guardian_phone" value="<?php echo e(old('guardian_phone')); ?>" required class="mt-1 block w-full border rounded-lg px-3 py-2 focus:ring focus:ring-blue-300">
                </div>

                
                <div class="mb-4">
                    <label for="guardian_relation" class="block text-sm font-medium text-gray-700">नाता</label>
                    <input type="text" name="guardian_relation" value="<?php echo e(old('guardian_relation')); ?>" required class="mt-1 block w-full border rounded-lg px-3 py-2 focus:ring focus:ring-blue-300">
                </div>

                
<div class="mb-4">
    <label for="room_id" class="block text-sm font-medium text-gray-700">कोठा छान्नुहोस्</label>
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
            ✅ कुल <?php echo e($rooms->count()); ?> वटा कोठाहरू फेला पर्यो
        </p>
    <?php endif; ?>
</div>

                
                <div class="mb-4">
                    <label for="admission_date" class="block text-sm font-medium text-gray-700">भर्ना मिति</label>
                    <input type="date" name="admission_date" value="<?php echo e(old('admission_date')); ?>" 
                           min="2000-01-01" max="<?php echo e(date('Y-m-d', strtotime('+1 year'))); ?>"
                           required class="mt-1 block w-full border rounded-lg px-3 py-2 focus:ring focus:ring-blue-300">
                </div>

                
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700">स्थिति</label>
                        <select name="status" class="mt-1 block w-full border rounded-lg px-3 py-2 focus:ring focus:ring-blue-300" required>
                            <option value="pending" <?php echo e(old('status') == 'pending' ? 'selected' : ''); ?>>पेन्डिङ</option>
                            <option value="approved" <?php echo e(old('status') == 'approved' ? 'selected' : ''); ?>>स्वीकृत</option>
                            <option value="active" <?php echo e(old('status') == 'active' ? 'selected' : ''); ?>>सक्रिय</option>
                            <option value="inactive" <?php echo e(old('status') == 'inactive' ? 'selected' : ''); ?>>निष्क्रिय</option>
                        </select>
                    </div>
                    <div>
                        <label for="payment_status" class="block text-sm font-medium text-gray-700">भुक्तानी स्थिति</label>
                        <select name="payment_status" class="mt-1 block w-full border rounded-lg px-3 py-2 focus:ring focus:ring-blue-300" required>
                            <option value="pending" <?php echo e(old('payment_status') == 'pending' ? 'selected' : ''); ?>>पेन्डिङ</option>
                            <option value="paid" <?php echo e(old('payment_status') == 'paid' ? 'selected' : ''); ?>>भुक्तानी भएको</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        
        <div class="mt-6">
            <label for="address" class="block text-sm font-medium text-gray-700">ठेगाना</label>
            <textarea name="address" rows="3" required class="mt-1 block w-full border rounded-lg px-3 py-2 focus:ring focus:ring-blue-300"><?php echo e(old('address')); ?></textarea>
        </div>

        <div class="mt-4">
            <label for="guardian_address" class="block text-sm font-medium text-gray-700">अभिभावकको ठेगाना</label>
            <textarea name="guardian_address" rows="3" required class="mt-1 block w-full border rounded-lg px-3 py-2 focus:ring focus:ring-blue-300"><?php echo e(old('guardian_address')); ?></textarea>
        </div>

        
        <div class="mt-6 flex justify-end">
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg shadow flex items-center gap-1">
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
    
    if (collegeSelect && otherCollegeField) {
        collegeSelect.addEventListener('change', function() {
            if (this.value === 'others') {
                otherCollegeField.classList.remove('hidden');
            } else {
                otherCollegeField.classList.add('hidden');
            }
        });
        
        // Initialize on page load
        if (collegeSelect.value === 'others') {
            otherCollegeField.classList.remove('hidden');
        }
    }
});
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.owner', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\My Projects\HostelHub\resources\views/owner/students/create.blade.php ENDPATH**/ ?>