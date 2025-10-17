

<?php $__env->startSection('content'); ?>
<div class="container mx-auto px-4 py-6">
    
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">विद्यार्थी सम्पादन: <?php echo e($student->name); ?></h1>
        <a href="<?php echo e(route('owner.students.index')); ?>"
           class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg shadow">
            ⬅ फर्कनुहोस्
        </a>
    </div>

    
    <?php if($errors->any()): ?>
        <div class="bg-red-50 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
            <ul class="list-disc ml-6">
                <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <li><?php echo e($error); ?></li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </ul>
        </div>
    <?php endif; ?>

    
    <form action="<?php echo e(route('owner.students.update', $student->id)); ?>" method="POST" class="bg-white shadow-md rounded-lg p-6">
        <?php echo csrf_field(); ?>
        <?php echo method_field('PUT'); ?>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            
            <div>
                
                <div class="mb-4">
                    <label for="name" class="block text-sm font-medium text-gray-700">Name *</label>
                    <input type="text" name="name" id="name" value="<?php echo e(old('name', $student->name)); ?>"
                           class="mt-1 block w-full border rounded-lg px-3 py-2 focus:ring focus:ring-blue-300" required>
                </div>

                
                <div class="mb-4">
                    <label for="user_id" class="block text-sm font-medium text-gray-700">User</label>
                    <select name="user_id" id="user_id" class="mt-1 block w-full border rounded-lg px-3 py-2 focus:ring focus:ring-blue-300">
                        <option value="0">-- No User --</option>
                        <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($user->id); ?>" <?php echo e(old('user_id', $student->user_id) == $user->id ? 'selected' : ''); ?>>
                                <?php echo e($user->name); ?> (<?php echo e($user->email); ?>)
                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>

                
                <div class="mb-4">
                    <label for="college_id" class="block text-sm font-medium text-gray-700">College *</label>
                    <select name="college_id" id="college_id" class="mt-1 block w-full border rounded-lg px-3 py-2 focus:ring focus:ring-blue-300" required>
                        <option value="">-- Select College --</option>
                        <?php $__currentLoopData = $colleges; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $college): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($college->id); ?>" <?php echo e(old('college_id', $student->college_id) == $college->id ? 'selected' : ''); ?>>
                                <?php echo e($college->name); ?>

                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>>
                        <option value="others" <?php echo e(old('college_id', $student->college_id) === null && $student->college ? 'selected' : ''); ?>>Others</option>
                    </select>
                </div>
                <div class="mb-4" id="other_college_field" style="<?php echo e((old('college_id', $student->college_id) === null && $student->college) ? '' : 'display: none;'); ?>">
                    <label for="other_college" class="block text-sm font-medium text-gray-700">Other College Name</label>
                    <input type="text" name="other_college" id="other_college" value="<?php echo e(old('other_college', $student->college_id ? '' : $student->college)); ?>"
                           class="mt-1 block w-full border rounded-lg px-3 py-2 focus:ring focus:ring-blue-300">
                </div>

                
                <div class="mb-4">
                    <label for="phone" class="block text-sm font-medium text-gray-700">Phone *</label>
                    <input type="text" name="phone" id="phone" value="<?php echo e(old('phone', $student->phone)); ?>"
                           class="mt-1 block w-full border rounded-lg px-3 py-2 focus:ring focus:ring-blue-300" required>
                </div>

                
                <div class="mb-4">
                    <label for="dob" class="block text-sm font-medium text-gray-700">जन्म मिति</label>
                    <input type="date" name="dob" id="dob" value="<?php echo e(old('dob', $student->dob ? $student->dob->format('Y-m-d') : '')); ?>"
                           class="mt-1 block w-full border rounded-lg px-3 py-2 focus:ring focus:ring-blue-300">
                </div>

                
                <div class="mb-4">
                    <label for="gender" class="block text-sm font-medium text-gray-700">Gender</label>
                    <select name="gender" id="gender" class="mt-1 block w-full border rounded-lg px-3 py-2 focus:ring focus:ring-blue-300">
                        <option value="">-- Select Gender --</option>
                        <option value="male" <?php echo e(old('gender', $student->gender)=='male' ? 'selected' : ''); ?>>Male</option>
                        <option value="female" <?php echo e(old('gender', $student->gender)=='female' ? 'selected' : ''); ?>>Female</option>
                        <option value="other" <?php echo e(old('gender', $student->gender)=='other' ? 'selected' : ''); ?>>Other</option>
                    </select>
                </div>
            </div>

            
            <div>
                
                <div class="mb-4">
                    <label for="guardian_name" class="block text-sm font-medium text-gray-700">Guardian Name *</label>
                    <input type="text" name="guardian_name" id="guardian_name" value="<?php echo e(old('guardian_name', $student->guardian_name)); ?>"
                           class="mt-1 block w-full border rounded-lg px-3 py-2 focus:ring focus:ring-blue-300" required>
                </div>

                
                <div class="mb-4">
                    <label for="guardian_contact" class="block text-sm font-medium text-gray-700">Guardian Contact *</label>
                    <input type="text" name="guardian_contact" id="guardian_contact" value="<?php echo e(old('guardian_contact', $student->guardian_contact)); ?>"
                           class="mt-1 block w-full border rounded-lg px-3 py-2 focus:ring focus:ring-blue-300" required>
                </div>

                
                <div class="mb-4">
                    <label for="guardian_relation" class="block text-sm font-medium text-gray-700">Guardian Relation *</label>
                    <input type="text" name="guardian_relation" id="guardian_relation" value="<?php echo e(old('guardian_relation', $student->guardian_relation)); ?>"
                           class="mt-1 block w-full border rounded-lg px-3 py-2 focus:ring focus:ring-blue-300" required>
                </div>

                
                <div class="mb-4">
                    <label for="room_id" class="block text-sm font-medium text-gray-700">Assign Room</label>
                    <select name="room_id" id="room_id" class="mt-1 block w-full border rounded-lg px-3 py-2 focus:ring focus:ring-blue-300">
                        <option value="">-- No Room Assigned --</option>
                        <?php $__currentLoopData = $rooms; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $room): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($room->id); ?>" <?php echo e(old('room_id', $student->room_id)==$room->id ? 'selected' : ''); ?>>
                                <?php echo e($room->room_number); ?> (<?php echo e($room->hostel->name); ?>)
                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>

                
                <div class="mb-4">
                    <label for="admission_date" class="block text-sm font-medium text-gray-700">Admission Date *</label>
                    <input type="date" name="admission_date" id="admission_date" value="<?php echo e(old('admission_date', $student->admission_date ? $student->admission_date->format('Y-m-d') : '')); ?>"
                           class="mt-1 block w-full border rounded-lg px-3 py-2 focus:ring focus:ring-blue-300" required>
                </div>

                
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700">Status *</label>
                        <select name="status" id="status" class="mt-1 block w-full border rounded-lg px-3 py-2 focus:ring focus:ring-blue-300" required>
                            <option value="pending" <?php echo e(old('status', $student->status)=='pending' ? 'selected' : ''); ?>>Pending</option>
                            <option value="approved" <?php echo e(old('status', $student->status)=='approved' ? 'selected' : ''); ?>>Approved</option>
                            <option value="active" <?php echo e(old('status', $student->status)=='active' ? 'selected' : ''); ?>>Active</option>
                            <option value="inactive" <?php echo e(old('status', $student->status)=='inactive' ? 'selected' : ''); ?>>Inactive</option>
                        </select>
                    </div>
                    <div>
                        <label for="payment_status" class="block text-sm font-medium text-gray-700">Payment Status *</label>
                        <select name="payment_status" id="payment_status" class="mt-1 block w-full border rounded-lg px-3 py-2 focus:ring focus:ring-blue-300" required>
                            <option value="pending" <?php echo e(old('payment_status', $student->payment_status)=='pending' ? 'selected' : ''); ?>>Pending</option>
                            <option value="paid" <?php echo e(old('payment_status', $student->payment_status)=='paid' ? 'selected' : ''); ?>>Paid</option>
                            <option value="unpaid" <?php echo e(old('payment_status', $student->payment_status)=='unpaid' ? 'selected' : ''); ?>>Unpaid</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        
        <div class="mt-6">
            <label for="address" class="block text-sm font-medium text-gray-700">Address *</label>
            <textarea name="address" id="address" rows="3"
                      class="mt-1 block w-full border rounded-lg px-3 py-2 focus:ring focus:ring-blue-300" required><?php echo e(old('address', $student->address)); ?></textarea>
        </div>

        <div class="mt-4">
            <label for="guardian_address" class="block text-sm font-medium text-gray-700">Guardian Address *</label>
            <textarea name="guardian_address" id="guardian_address" rows="3"
                      class="mt-1 block w-full border rounded-lg px-3 py-2 focus:ring focus:ring-blue-300" required><?php echo e(old('guardian_address', $student->guardian_address)); ?></textarea>
        </div>

        
        <div class="mt-6 flex justify-end">
            <button type="submit"
                    class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg shadow">
                🔄 Update Student
            </button>
        </div>
    </form>
</div>

<script>
// JavaScript to handle college selection
document.addEventListener('DOMContentLoaded', function() {
    const collegeSelect = document.getElementById('college_id');
    const otherCollegeField = document.getElementById('other_college_field');
    
    if (collegeSelect && otherCollegeField) {
        collegeSelect.addEventListener('change', function() {
            if (this.value === 'others') {
                otherCollegeField.style.display = 'block';
            } else {
                otherCollegeField.style.display = 'none';
            }
        });
        
        // Trigger on page load
        collegeSelect.dispatchEvent(new Event('change'));
    }
});
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.owner', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\My Projects\HostelHub\resources\views/owner/students/edit.blade.php ENDPATH**/ ?>