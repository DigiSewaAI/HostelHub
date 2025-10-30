

<?php $__env->startSection('title', 'भुक्तानी इतिहास'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">भुक्तानी इतिहास</h1>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">भुक्तानीहरू</h6>
                </div>
                <div class="card-body">
                    <?php if($payments->count() > 0): ?>
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>भुक्तानी ID</th>
                                    <th>बुकिंग</th>
                                    <th>रकम</th>
                                    <th>भुक्तानी विधि</th>
                                    <th>मिति</th>
                                    <th>स्थिति</th>
                                    <th>कार्यहरू</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__currentLoopData = $payments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $payment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td>#<?php echo e($payment->id); ?></td>
                                    <td>बुकिंग #<?php echo e($payment->booking_id); ?></td>
                                    <td>रु <?php echo e(number_format($payment->amount, 2)); ?></td>
                                    <td><?php echo e($payment->payment_method); ?></td>
                                    <td><?php echo e($payment->created_at->format('Y-m-d H:i')); ?></td>
                                    <td>
                                        <?php if($payment->status === 'completed'): ?>
                                        <span class="badge badge-success">सफल</span>
                                        <?php elseif($payment->status === 'pending'): ?>
                                        <span class="badge badge-warning">पेन्डिङ</span>
                                        <?php else: ?>
                                        <span class="badge badge-danger">असफल</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <a href="<?php echo e(route('student.payments.receipt', $payment->id)); ?>" class="btn btn-info btn-sm">
                                            <i class="fas fa-receipt"></i> रसिद
                                        </a>
                                    </td>
                                </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="d-flex justify-content-center">
                        <?php echo e($payments->links()); ?>

                    </div>
                    <?php else: ?>
                    <div class="text-center py-5">
                        <i class="fas fa-credit-card fa-3x text-gray-300 mb-3"></i>
                        <h4 class="text-gray-500">कुनै भुक्तानी इतिहास छैन</h4>
                        <p class="text-muted">तपाईंले अहिले सम्म कुनै भुक्तानी गर्नुभएको छैन।</p>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.student', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\HostelHub\resources\views\student\payments\history.blade.php ENDPATH**/ ?>