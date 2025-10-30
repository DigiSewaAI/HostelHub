<?php $__env->startSection('title', 'सम्पर्क विवरण'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">सम्पर्क विवरण</h3>
                </div>

                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-bordered">
                                <tr>
                                    <th style="width: 30%;">नाम:</th>
                                    <td><?php echo e($contact->name); ?></td>
                                </tr>
                                <tr>
                                    <th>इमेल:</th>
                                    <td><?php echo e($contact->email); ?></td>
                                </tr>
                                <tr>
                                    <th>फोन:</th>
                                    <td><?php echo e($contact->phone ?? 'N/A'); ?></td>
                                </tr>
                                <tr>
                                    <th>विषय:</th>
                                    <td><?php echo e($contact->subject); ?></td>
                                </tr>
                                <tr>
                                    <th>स्थिति:</th>
                                    <td>
                                        <form action="<?php echo e(route('admin.contacts.updateStatus', $contact->id)); ?>" method="POST" class="form-inline">
                                            <?php echo csrf_field(); ?>
                                            <select name="status" class="form-control form-control-sm" onchange="this.form.submit()">
                                                <?php if (\Illuminate\Support\Facades\Blade::check('role', 'admin')): ?>
                                                <option value="नयाँ" <?php echo e($contact->status == 'नयाँ' ? 'selected' : ''); ?>>नयाँ</option>
                                                <option value="पढियो" <?php echo e($contact->status == 'पढियो' ? 'selected' : ''); ?>>पढियो</option>
                                                <option value="जवाफ दिइयो" <?php echo e($contact->status == 'जवाफ दिइयो' ? 'selected' : ''); ?>>जवाफ दिइयो</option>
                                                <?php else: ?>
                                                <option value="pending" <?php echo e($contact->status == 'pending' ? 'selected' : ''); ?>>प्रतीक्षामा</option>
                                                <option value="read" <?php echo e($contact->status == 'read' ? 'selected' : ''); ?>>पढिएको</option>
                                                <option value="replied" <?php echo e($contact->status == 'replied' ? 'selected' : ''); ?>>जवाफ दिइयो</option>
                                                <?php endif; ?>
                                            </select>
                                        </form>
                                    </td>
                                </tr>
                                <tr>
                                    <th>पठाइएको मिति:</th>
                                    <td><?php echo e($contact->created_at->format('Y-m-d H:i:s')); ?></td>
                                </tr>
                            </table>
                        </div>

                        <div class="col-md-6">
                            <h5>सन्देश:</h5>
                            <p><?php echo e($contact->message); ?></p>
                        </div>
                    </div>
                </div>

                <div class="card-footer">
                    <a href="<?php echo e(route('admin.contacts.edit', $contact->id)); ?>" class="btn btn-primary">
                        <i class="fas fa-edit"></i> सम्पादन गर्नुहोस्
                    </a>
                    <a href="<?php echo e(route('admin.contacts.index')); ?>" class="btn btn-default">
                        <i class="fas fa-arrow-left"></i> सम्पर्क सूचीमा फर्कनुहोस्
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\HostelHub\resources\views\admin\contacts\show.blade.php ENDPATH**/ ?>