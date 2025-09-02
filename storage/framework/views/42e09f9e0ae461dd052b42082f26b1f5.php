<?php $__env->startSection('title', 'खानाको योजना'); ?>

<?php $__env->startSection('content'); ?>
<div class="card">
    <div class="card-header">
        <h3>खानाको योजना</h3>
        <a href="<?php echo e(route('admin.meal-menus.create')); ?>" class="btn btn-primary">थप्नुहोस्</a>
    </div>
    <div class="card-body">
        <table class="table">
            <thead>
                <tr>
                    <th>होस्टल</th>
                    <th>खानाको प्रकार</th>
                    <th>दिन</th>
                    <th>वस्तुहरू</th>
                    <th>तस्बिर</th>
                    <th>स्थिति</th>
                    <th>कार्य</th>
                </tr>
            </thead>
            <tbody>
                <?php $__currentLoopData = $mealMenus; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $menu): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>
                    <td><?php echo e($menu->hostel->name); ?></td>
                    <td><?php echo e(ucfirst($menu->meal_type)); ?></td>
                    <td><?php echo e(ucfirst($menu->day_of_week)); ?></td>
                    <td><?php echo e(implode(', ', $menu->items)); ?></td>
                    <td>
                        <?php if($menu->image): ?>
                            <img src="<?php echo e(Storage::url($menu->image)); ?>" width="50" alt="Meal">
                        <?php endif; ?>
                    </td>
                    <td>
                        <span class="badge bg-<?php echo e($menu->is_active ? 'success' : 'danger'); ?>">
                            <?php echo e($menu->is_active ? 'सक्रिय' : 'निष्क्रिय'); ?>

                        </span>
                    </td>
                    <td>
                        <a href="<?php echo e(route('admin.meal-menus.edit', $menu)); ?>" class="btn btn-sm btn-warning">सम्पादन</a>
                        <form action="<?php echo e(route('admin.meal-menus.destroy', $menu)); ?>" method="POST" class="d-inline">
                            <?php echo csrf_field(); ?>
                            <?php echo method_field('DELETE'); ?>
                            <button class="btn btn-sm btn-danger" onclick="return confirm('हटाउन निश्चित हुनुहुन्छ?')">हटाउनुहोस्</button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\My Projects\HostelHub\resources\views/admin/meal-menus/index.blade.php ENDPATH**/ ?>