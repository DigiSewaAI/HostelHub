<?php $__env->startSection('content'); ?>
<div class="container mx-auto px-4 py-6">
    
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">विद्यार्थी व्यवस्थापन</h1>
        <a href="<?php echo e(route('owner.students.create')); ?>"
           class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg shadow">
            ➕ नयाँ विद्यार्थी थप्नुहोस्
        </a>
    </div>

    
    <form method="GET" class="flex flex-wrap items-center gap-3 mb-6">
        <input type="text" name="search" value="<?php echo e(request('search')); ?>"
               placeholder="नाम वा ईमेल खोज्नुहोस्..."
               class="border px-3 py-2 rounded-lg focus:outline-none focus:ring w-64">

        <select name="status" class="border px-3 py-2 rounded-lg focus:outline-none focus:ring">
            <option value="">-- Status Filter --</option>
            <option value="active" <?php echo e(request('status')=='active' ? 'selected' : ''); ?>>Active</option>
            <option value="inactive" <?php echo e(request('status')=='inactive' ? 'selected' : ''); ?>>Inactive</option>
        </select>

        <button type="submit"
                class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg shadow">
            🔍 खोज्नुहोस्
        </button>

        <a href="<?php echo e(route('owner.students.index')); ?>"
           class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg shadow">
            ♻️ Reset
        </a>

        <a href="<?php echo e(route('owner.students.export-csv')); ?>"
           class="ml-auto bg-emerald-600 hover:bg-emerald-700 text-white px-4 py-2 rounded-lg shadow">
            📥 एक्सेलमा निर्यात
        </a>
    </form>

    
    <?php if($students->count()): ?>
        <div class="overflow-x-auto bg-white shadow-md rounded-lg">
            <table class="w-full border-collapse">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-4 py-3 border-b text-left">#</th>
                        <th class="px-4 py-3 border-b text-left">नाम</th>
                        <th class="px-4 py-3 border-b text-left">ईमेल</th>
                        <th class="px-4 py-3 border-b text-left">फोन</th>
                        <th class="px-4 py-3 border-b text-left">Status</th>
                        <th class="px-4 py-3 border-b text-center">कार्य</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__currentLoopData = $students; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $student): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-2 border-b"><?php echo e($student->id); ?></td>
                            <td class="px-4 py-2 border-b font-medium text-gray-800"><?php echo e($student->name); ?></td>
                            <td class="px-4 py-2 border-b"><?php echo e($student->email); ?></td>
                            <td class="px-4 py-2 border-b"><?php echo e($student->phone ?? '-'); ?></td>
                            <td class="px-4 py-2 border-b">
                                <?php if($student->status == 'active'): ?>
                                    <span class="px-2 py-1 text-sm rounded-full bg-green-100 text-green-700">
                                        Active
                                    </span>
                                <?php else: ?>
                                    <span class="px-2 py-1 text-sm rounded-full bg-red-100 text-red-700">
                                        Inactive
                                    </span>
                                <?php endif; ?>
                            </td>
                            <td class="px-4 py-2 border-b text-center">
                                <a href="<?php echo e(route('owner.students.show', $student)); ?>"
                                   class="text-blue-600 hover:underline">👁 हेर्नुहोस्</a>
                                <a href="<?php echo e(route('owner.students.edit', $student)); ?>"
                                   class="ml-2 text-yellow-600 hover:underline">✏ सम्पादन</a>
                                <form action="<?php echo e(route('owner.students.destroy', $student)); ?>"
                                      method="POST" class="inline"
                                      onsubmit="return confirm('पक्का delete गर्ने?')">
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('DELETE'); ?>
                                    <button type="submit"
                                            class="ml-2 text-red-600 hover:underline">🗑 Delete</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
        </div>

        
        <div class="mt-4">
            <?php echo e($students->links()); ?>

        </div>
    <?php else: ?>
        <div class="bg-yellow-50 border border-yellow-300 text-yellow-800 px-4 py-3 rounded">
            हाल कुनै विद्यार्थी दर्ता भएको छैन।
        </div>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.owner', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\My Projects\HostelHub\resources\views/owner/students/index.blade.php ENDPATH**/ ?>