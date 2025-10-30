<?php $__env->startSection('title', 'सम्पर्क सम्पादन गर्नुहोस्'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">सम्पर्क सम्पादन गर्नुहोस्</h3>
                </div>

                <form action="<?php echo e(route('admin.contacts.update', $contact->id)); ?>" method="POST">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('PUT'); ?>
                    <div class="card-body">
                        <div class="form-group">
                            <label for="name">पूरा नाम</label>
                            <input type="text" class="form-control" id="name" name="name" value="<?php echo e($contact->name); ?>" required>
                        </div>

                        <div class="form-group">
                            <label for="email">इमेल ठेगाना</label>
                            <input type="email" class="form-control" id="email" name="email" value="<?php echo e($contact->email); ?>" required>
                        </div>

                        <div class="form-group">
                            <label for="phone">फोन नम्बर</label>
                            <input type="text" class="form-control" id="phone" name="phone" value="<?php echo e($contact->phone); ?>">
                        </div>

                        <div class="form-group">
                            <label for="subject">विषय</label>
                            <input type="text" class="form-control" id="subject" name="subject" value="<?php echo e($contact->subject); ?>" required>
                        </div>

                        <div class="form-group">
                            <label for="message">सन्देश</label>
                            <textarea class="form-control" id="message" name="message" rows="5" required><?php echo e($contact->message); ?></textarea>
                        </div>
                        
                        <?php if (\Illuminate\Support\Facades\Blade::check('role', 'admin')): ?>
                        <div class="form-group">
                            <label for="status">स्थिति</label>
                            <select class="form-control" id="status" name="status">
                                <option value="नयाँ" <?php echo e($contact->status == 'नयाँ' ? 'selected' : ''); ?>>नयाँ</option>
                                <option value="पढियो" <?php echo e($contact->status == 'पढियो' ? 'selected' : ''); ?>>पढियो</option>
                                <option value="जवाफ दिइयो" <?php echo e($contact->status == 'जवाफ दिइयो' ? 'selected' : ''); ?>>जवाफ दिइयो</option>
                            </select>
                        </div>
                        <?php else: ?>
                        <div class="form-group">
                            <label for="status">स्थिति</label>
                            <select class="form-control" id="status" name="status">
                                <option value="pending" <?php echo e($contact->status == 'pending' ? 'selected' : ''); ?>>प्रतीक्षामा</option>
                                <option value="read" <?php echo e($contact->status == 'read' ? 'selected' : ''); ?>>पढियो</option>
                                <option value="replied" <?php echo e($contact->status == 'replied' ? 'selected' : ''); ?>>जवाफ दिइयो</option>
                            </select>
                        </div>
                        <?php endif; ?>
                    </div>

                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> परिवर्तनहरू सुरक्षित गर्नुहोस्
                        </button>
                        <a href="<?php echo e(route('admin.contacts.index')); ?>" class="btn btn-default">
                            <i class="fas fa-times"></i> रद्द गर्नुहोस्
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\HostelHub\resources\views\admin\contacts\edit.blade.php ENDPATH**/ ?>