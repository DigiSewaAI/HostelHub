

<?php $__env->startSection('content'); ?>
<div class="container mx-auto px-4 py-6">
    
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">विद्यार्थी विवरण</h1>
        <a href="<?php echo e(route('admin.students.index')); ?>"
           class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg shadow">
            ⬅ फर्कनुहोस्
        </a>
    </div>

    
    <div class="bg-white shadow-md rounded-lg p-6">
        <h2 class="text-xl font-semibold text-blue-700 mb-4"><?php echo e($student->user->name); ?></h2>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            
            <div>
                <p><strong>College:</strong> <?php echo e($student->college); ?></p>
                <p><strong>Phone:</strong> <?php echo e($student->phone); ?></p>
                <p><strong>Date of Birth:</strong> <?php echo e($student->dob ? $student->dob->format('d M, Y') : 'N/A'); ?></p>
                <p><strong>Gender:</strong> <?php echo e(ucfirst($student->gender)); ?></p>
                <p><strong>Address:</strong> <?php echo e($student->address); ?></p>
            </div>

            
            <div>
                <p><strong>Guardian Name:</strong> <?php echo e($student->guardian_name); ?></p>
                <p><strong>Guardian Phone:</strong> <?php echo e($student->guardian_phone); ?></p>
                <p><strong>Relation:</strong> <?php echo e($student->guardian_relation); ?></p>
                <p><strong>Guardian Address:</strong> <?php echo e($student->guardian_address); ?></p>
                <p><strong>Room:</strong> 
                    <?php if($student->room): ?>
                        <?php echo e($student->room->room_number); ?> (<?php echo e($student->room->hostel->name); ?>)
                    <?php else: ?>
                        Not Assigned
                    <?php endif; ?>
                </p>
            </div>
        </div>

        <div class="mt-6 grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="p-4 bg-blue-50 rounded-lg border">
                <p class="text-sm text-gray-600">Admission Date</p>
                <p class="text-lg font-bold text-blue-700"><?php echo e($student->admission_date ? $student->admission_date->format('d M, Y') : 'N/A'); ?></p>
            </div>
            <div class="p-4 bg-green-50 rounded-lg border">
                <p class="text-sm text-gray-600">Status</p>
                <p class="text-lg font-bold text-green-700 capitalize"><?php echo e($student->status); ?></p>
            </div>
            <div class="p-4 bg-yellow-50 rounded-lg border">
                <p class="text-sm text-gray-600">Payment Status</p>
                <p class="text-lg font-bold text-yellow-700 capitalize"><?php echo e($student->payment_status); ?></p>
            </div>
        </div>
    </div>

    
    <div class="mt-6 flex gap-4">
        <a href="<?php echo e(route('admin.students.edit', $student->id)); ?>"
           class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg shadow">
            ✏ सम्पादन गर्नुहोस्
        </a>
        <form action="<?php echo e(route('admin.students.destroy', $student->id)); ?>" method="POST" onsubmit="return confirm('के तपाईं पक्का delete गर्न चाहनुहुन्छ?')">
            <?php echo csrf_field(); ?>
            <?php echo method_field('DELETE'); ?>
            <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-6 py-2 rounded-lg shadow">
                🗑 Delete
            </button>
        </form>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\My Projects\HostelHub\resources\views\admin\students\show.blade.php ENDPATH**/ ?>