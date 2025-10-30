

<?php $__env->startSection('title', 'भुक्तानी विवरण'); ?>

<?php $__env->startSection('page-description', 'भुक्तानीको पूर्ण विवरण'); ?>

<?php $__env->startSection('header-buttons'); ?>
    <a href="<?php echo e(route('owner.payments.index')); ?>" class="btn btn-secondary">
        <i class="fas fa-arrow-left me-2"></i>पछाडि
    </a>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h5 class="card-title mb-0">भुक्तानी विवरण</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="text-muted">मूल विवरण</h6>
                            <table class="table table-borderless">
                                <tr>
                                    <th width="40%">भुक्तानी आईडी:</th>
                                    <td>#<?php echo e($payment->id); ?></td>
                                </tr>
                                <tr>
                                    <th>विद्यार्थी:</th>
                                    <td>
                                        <?php if($payment->student): ?>
                                            <?php echo e($payment->student->name); ?>

                                            <br>
                                            <small class="text-muted"><?php echo e($payment->student->email); ?></small>
                                        <?php else: ?>
                                            <span class="text-danger">विद्यार्थी उपलब्ध छैन</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <tr>
                                    <th>होस्टल:</th>
                                    <td>
                                        <?php if($payment->hostel): ?>
                                            <?php echo e($payment->hostel->name); ?>

                                        <?php else: ?>
                                            <span class="text-muted">होस्टल उपलब्ध छैन</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <tr>
                                    <th>रकम:</th>
                                    <td class="fw-bold text-success">रु <?php echo e(number_format($payment->amount, 2)); ?></td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-muted">भुक्तानी जानकारी</h6>
                            <table class="table table-borderless">
                                <tr>
                                    <th width="40%">भुक्तानी मिति:</th>
                                    <td><?php echo e($payment->payment_date->format('Y-m-d')); ?></td>
                                </tr>
                                <tr>
                                    <th>भुक्तानी विधि:</th>
                                    <td>
                                        <span class="badge bg-info text-dark">
                                            <?php echo e($payment->payment_method); ?>

                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <th>स्थिति:</th>
                                    <td>
                                        <?php if($payment->status == 'completed'): ?>
                                            <span class="badge bg-success">सफल</span>
                                        <?php elseif($payment->status == 'pending'): ?>
                                            <span class="badge bg-warning text-dark">पेन्डिङ</span>
                                        <?php else: ?>
                                            <span class="badge bg-danger">असफल</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <tr>
                                    <th>सिर्जना गरिएको:</th>
                                    <td>
                                        <?php echo e($payment->created_at->format('Y-m-d H:i')); ?>

                                        <?php if($payment->createdBy): ?>
                                            <br>
                                            <small class="text-muted">द्वारा: <?php echo e($payment->createdBy->name); ?></small>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <?php if($payment->notes): ?>
                    <div class="row mt-4">
                        <div class="col-12">
                            <h6 class="text-muted">टिप्पणी</h6>
                            <div class="border rounded p-3 bg-light">
                                <?php echo e($payment->notes); ?>

                            </div>
                        </div>
                    </div>
                    <?php endif; ?>

                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="d-flex justify-content-end gap-2">
                                <a href="<?php echo e(route('owner.payments.edit', $payment)); ?>" class="btn btn-warning">
                                    <i class="fas fa-edit me-2"></i>सम्पादन गर्नुहोस्
                                </a>
                                <form action="<?php echo e(route('owner.payments.destroy', $payment)); ?>" method="POST" class="d-inline">
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('DELETE'); ?>
                                    <button type="submit" class="btn btn-danger" 
                                            onclick="return confirm('के तपाईं यो भुक्तानी मेटाउन निश्चित हुनुहुन्छ?')">
                                        <i class="fas fa-trash me-2"></i>मेटाउनुहोस्
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.owner', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\HostelHub\resources\views\owner\payments\show.blade.php ENDPATH**/ ?>