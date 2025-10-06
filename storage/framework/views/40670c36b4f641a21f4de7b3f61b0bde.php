<?php $__env->startSection('content'); ?>
<div class="container mx-auto px-4 py-6">
    
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">नयाँ विद्यार्थी दर्ता</h1>
        <a href="<?php echo e(route('admin.students.index')); ?>"
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

    
    <form action="<?php echo e(route('admin.students.store')); ?>" method="POST" class="bg-white shadow-md rounded-lg p-6">
        <?php echo csrf_field(); ?>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            
            <div>
                
                <div class="mb-4">
                    <label for="user_id" class="block text-sm font-medium text-gray-700">User छान्नुहोस्</label>
                    <select name="user_id" id="user_id" class="mt-1 block w-full border rounded-lg px-3 py-2 focus:ring focus:ring-blue-300">
                        <option value="">-- Select User (Optional) --</option>
                        <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($user->id); ?>" <?php echo e(old('user_id') == $user->id ? 'selected' : ''); ?>>
                                <?php echo e($user->name); ?> (<?php echo e($user->email); ?>)
                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>

                
                <div class="mb-4">
                    <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
                    <input type="text" name="name" value="<?php echo e(old('name')); ?>" required class="mt-1 block w-full border rounded-lg px-3 py-2 focus:ring focus:ring-blue-300">
                </div>

                
                <div class="mb-4">
                    <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                    <input type="email" name="email" value="<?php echo e(old('email')); ?>" required class="mt-1 block w-full border rounded-lg px-3 py-2 focus:ring focus:ring-blue-300">
                </div>

                
                <div class="mb-4">
                    <label for="college_id" class="block text-sm font-medium text-gray-700">College</label>
                    <select name="college_id" class="mt-1 block w-full border rounded-lg px-3 py-2 focus:ring focus:ring-blue-300">
                        <option value="">-- Select College --</option>
                        <?php $__currentLoopData = $colleges; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $college): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($college->id); ?>" <?php echo e(old('college_id') == $college->id ? 'selected' : ''); ?>>
                                <?php echo e($college->name); ?>

                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>

                
                <div class="mb-4">
                    <label for="phone" class="block text-sm font-medium text-gray-700">Phone</label>
                    <input type="text" name="phone" value="<?php echo e(old('phone')); ?>" required class="mt-1 block w-full border rounded-lg px-3 py-2 focus:ring focus:ring-blue-300">
                </div>

                
                <div class="mb-4">
                    <label for="dob" class="block text-sm font-medium text-gray-700">जन्म मिति</label>
                    <input type="date" name="dob" value="<?php echo e(old('dob')); ?>" class="mt-1 block w-full border rounded-lg px-3 py-2 focus:ring focus:ring-blue-300">
                </div>

                
                <div class="mb-4">
                    <label for="gender" class="block text-sm font-medium text-gray-700">Gender</label>
                    <select name="gender" class="mt-1 block w-full border rounded-lg px-3 py-2 focus:ring focus:ring-blue-300">
                        <option value="">-- Select Gender --</option>
                        <option value="male" <?php echo e(old('gender') == 'male' ? 'selected' : ''); ?>>Male</option>
                        <option value="female" <?php echo e(old('gender') == 'female' ? 'selected' : ''); ?>>Female</option>
                        <option value="other" <?php echo e(old('gender') == 'other' ? 'selected' : ''); ?>>Other</option>
                    </select>
                </div>
            </div>

            
            <div>
                
                <div class="mb-4">
                    <label for="guardian_name" class="block text-sm font-medium text-gray-700">Guardian Name</label>
                    <input type="text" name="guardian_name" value="<?php echo e(old('guardian_name')); ?>" required class="mt-1 block w-full border rounded-lg px-3 py-2 focus:ring focus:ring-blue-300">
                </div>

                
                <div class="mb-4">
                    <label for="guardian_phone" class="block text-sm font-medium text-gray-700">Guardian Phone</label>
                    <input type="text" name="guardian_phone" value="<?php echo e(old('guardian_phone')); ?>" required class="mt-1 block w-full border rounded-lg px-3 py-2 focus:ring focus:ring-blue-300">
                </div>

                
                <div class="mb-4">
                    <label for="guardian_relation" class="block text-sm font-medium text-gray-700">Guardian Relation</label>
                    <input type="text" name="guardian_relation" value="<?php echo e(old('guardian_relation')); ?>" required class="mt-1 block w-full border rounded-lg px-3 py-2 focus:ring focus:ring-blue-300">
                </div>

                
                <div class="mb-4">
                    <label for="room_id" class="block text-sm font-medium text-gray-700">Assign Room</label>
                    <select name="room_id" class="mt-1 block w-full border rounded-lg px-3 py-2 focus:ring focus:ring-blue-300">
                        <option value="">-- No Room Assigned --</option>
                        <?php $__currentLoopData = $rooms; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $room): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($room->id); ?>" <?php echo e(old('room_id') == $room->id ? 'selected' : ''); ?>>
                                <?php echo e($room->room_number); ?> (<?php echo e($room->hostel?->name ?? 'Unknown Hostel'); ?>)
                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>

                
                <div class="mb-4">
                    <label for="admission_date" class="block text-sm font-medium text-gray-700">Admission Date</label>
                    <input type="date" name="admission_date" value="<?php echo e(old('admission_date')); ?>" required class="mt-1 block w-full border rounded-lg px-3 py-2 focus:ring focus:ring-blue-300">
                </div>

                
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                        <select name="status" class="mt-1 block w-full border rounded-lg px-3 py-2 focus:ring focus:ring-blue-300" required>
                            <option value="pending" <?php echo e(old('status') == 'pending' ? 'selected' : ''); ?>>Pending</option>
                            <option value="approved" <?php echo e(old('status') == 'approved' ? 'selected' : ''); ?>>Approved</option>
                            <option value="active" <?php echo e(old('status') == 'active' ? 'selected' : ''); ?>>Active</option>
                            <option value="inactive" <?php echo e(old('status') == 'inactive' ? 'selected' : ''); ?>>Inactive</option>
                        </select>
                    </div>
                    <div>
                        <label for="payment_status" class="block text-sm font-medium text-gray-700">Payment Status</label>
                        <select name="payment_status" class="mt-1 block w-full border rounded-lg px-3 py-2 focus:ring focus:ring-blue-300" required>
                            <option value="pending" <?php echo e(old('payment_status') == 'pending' ? 'selected' : ''); ?>>Pending</option>
                            <option value="paid" <?php echo e(old('payment_status') == 'paid' ? 'selected' : ''); ?>>Paid</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        
        <div class="mt-6">
            <label for="address" class="block text-sm font-medium text-gray-700">Address</label>
            <textarea name="address" rows="3" required class="mt-1 block w-full border rounded-lg px-3 py-2 focus:ring focus:ring-blue-300"><?php echo e(old('address')); ?></textarea>
        </div>

        <div class="mt-4">
            <label for="guardian_address" class="block text-sm font-medium text-gray-700">Guardian Address</label>
            <textarea name="guardian_address" rows="3" required class="mt-1 block w-full border rounded-lg px-3 py-2 focus:ring focus:ring-blue-300"><?php echo e(old('guardian_address')); ?></textarea>
        </div>

        
        <div class="mt-6 flex justify-end">
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg shadow flex items-center gap-1">
                💾 Create Student
            </button>
        </div>
    </form>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.owner', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\My Projects\HostelHub\resources\views/owner/students/create.blade.php ENDPATH**/ ?>