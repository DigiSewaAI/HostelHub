<?php $__env->startSection('title', 'खाना थप्नुहोस्'); ?>

<?php $__env->startSection('content'); ?>
<div class="card">
    <div class="card-header">
        <h3>खानाको योजना थप्नुहोस्</h3>
    </div>
    <div class="card-body">
        <form action="<?php echo e(route('admin.meal-menus.store')); ?>" method="POST" enctype="multipart/form-data">
            <?php echo csrf_field(); ?>
            <div class="form-group">
                <label>होस्टल</label>
                <select name="hostel_id" class="form-control" required>
                    <option value="">होस्टल छान्नुहोस्</option>
                    <?php $__currentLoopData = $hostels; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $hostel): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($hostel->id); ?>"><?php echo e($hostel->name); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>

            <div class="form-group">
                <label>खानाको प्रकार</label>
                <select name="meal_type" class="form-control" required>
                    <?php $__currentLoopData = $mealTypes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $type): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($type); ?>"><?php echo e(ucfirst($type)); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>

            <div class="form-group">
                <label>दिन</label>
                <select name="day_of_week" class="form-control" required>
                    <?php $__currentLoopData = $days; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $day): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($day); ?>"><?php echo e(ucfirst($day)); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>

            <div class="form-group">
                <label>खानाका वस्तुहरू</label>
                <div id="items">
                    <input type="text" name="items[]" class="form-control mb-2" placeholder="उदाहरण: Dal" required>
                </div>
                <button type="button" class="btn btn-sm btn-secondary" onclick="addItem()">+ थप्नुहोस्</button>
            </div>

            <div class="form-group">
                <label>खानाको तस्बिर (वैकल्पिक)</label>
                <input type="file" name="image" class="form-control">
            </div>

            <div class="form-group">
                <label>वर्णन</label>
                <textarea name="description" class="form-control" rows="3"></textarea>
            </div>

            <div class="form-group form-check">
                <input type="checkbox" name="is_active" class="form-check-input" id="is_active" checked>
                <label class="form-check-label" for="is_active">सक्रिय</label>
            </div>

            <button type="submit" class="btn btn-primary">सुरक्षित गर्नुहोस्</button>
        </form>
    </div>
</div>

<script>
function addItem() {
    const container = document.getElementById('items');
    const input = document.createElement('input');
    input.type = 'text';
    input.name = 'items[]';
    input.className = 'form-control mb-2';
    input.placeholder = 'उदाहरण: Bhat';
    input.required = true;
    container.appendChild(input);
}
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\My Projects\HostelHub\resources\views/admin/meal-menus/create.blade.php ENDPATH**/ ?>