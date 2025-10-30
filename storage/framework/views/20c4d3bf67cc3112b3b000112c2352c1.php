

<?php $__env->startSection('title', 'खानाको योजना'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h3><i class="fas fa-utensils me-2"></i> खानाको योजना</h3>
                <a href="<?php echo e(route('owner.meal-menus.create')); ?>" class="btn btn-primary">
                    <i class="fas fa-plus me-1"></i> नयाँ योजना
                </a>
            </div>

            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>दिन</th>
                                    <th>खानाको प्रकार</th>
                                    <th>खानाका वस्तुहरू</th>
                                    <th>तस्बिर</th>
                                    <th>कार्य</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__currentLoopData = $mealMenus; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $menu): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td><?php echo e($menu->day); ?></td>
                                    <td><span class="badge bg-primary"><?php echo e(ucfirst($menu->meal_type)); ?></span></td>
                                    <td><?php echo e($menu->items); ?></td>
                                    <td>
                                        <?php if($menu->image): ?>
                                            <img src="<?php echo e(asset('storage/'.$menu->image)); ?>" alt="Meal Image" class="img-thumbnail" width="100">
                                        <?php else: ?>
                                            <span class="text-muted">N/A</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="<?php echo e(route('owner.meal-menus.edit', $menu)); ?>" class="btn btn-sm btn-warning">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="<?php echo e(route('owner.meal-menus.destroy', $menu)); ?>" method="POST" class="d-inline">
                                                <?php echo csrf_field(); ?>
                                                <?php echo method_field('DELETE'); ?>
                                                <button class="btn btn-sm btn-danger" onclick="return confirm('हटाउन निश्चित हुनुहुन्छ?')">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.owner', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\HostelHub\resources\views/admin/meal-menus/index.blade.php ENDPATH**/ ?>