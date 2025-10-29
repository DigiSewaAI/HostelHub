

<?php $__env->startSection('title', 'भुक्तानीहरू'); ?>

<?php $__env->startSection('page-description', 'सबै भुक्तानीहरूको सूची'); ?>

<?php $__env->startSection('header-buttons'); ?>
    <a href="<?php echo e(route('owner.payments.create')); ?>" class="btn btn-primary">
        <i class="fas fa-plus me-2"></i>नयाँ भुक्तानी
    </a>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <!-- Payment Statistics -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                कुल भुक्तानी</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">रु <?php echo e(number_format($payments->sum('amount'))); ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-money-bill-wave fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                सफल भुक्तानी</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo e($payments->where('status', 'completed')->count()); ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                पेन्डिङ भुक्तानी</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo e($payments->where('status', 'pending')->count()); ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clock fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-danger shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                                असफल भुक्तानी</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo e($payments->where('status', 'failed')->count()); ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-times-circle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Payments Table -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold text-primary">भुक्तानीहरूको सूची</h6>
            <div class="d-flex gap-2">
                <form method="GET" action="<?php echo e(route('owner.payments.search')); ?>" class="d-flex">
                    <input type="text" name="search" class="form-control form-control-sm me-2" 
                           placeholder="खोज्नुहोस्..." value="<?php echo e(request('search')); ?>">
                    <button type="submit" class="btn btn-sm btn-primary">
                        <i class="fas fa-search"></i>
                    </button>
                </form>
            </div>
        </div>
        <div class="card-body">
            <?php if($payments->count() > 0): ?>
                <div class="table-responsive">
                    <table class="table table-bordered table-hover" id="paymentsTable" width="100%" cellspacing="0">
                        <thead class="bg-light">
                            <tr>
                                <th>#</th>
                                <th>विद्यार्थी</th>
                                <th>रकम</th>
                                <th>भुक्तानी मिति</th>
                                <th>भुक्तानी विधि</th>
                                <th>स्थिति</th>
                                <th>क्रियाहरू</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__currentLoopData = $payments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $payment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td><?php echo e($loop->iteration); ?></td>
                                    <td>
                                        <?php if($payment->student): ?>
                                            <?php echo e($payment->student->name); ?>

                                        <?php else: ?>
                                            <span class="text-muted">विद्यार्थी उपलब्ध छैन</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>रु <?php echo e(number_format($payment->amount)); ?></td>
                                    <td><?php echo e($payment->payment_date->format('Y-m-d')); ?></td>
                                    <td>
                                        <span class="badge bg-info text-dark">
                                            <?php echo e($payment->payment_method); ?>

                                        </span>
                                    </td>
                                    <td>
                                        <?php if($payment->status == 'completed'): ?>
                                            <span class="badge bg-success">सफल</span>
                                        <?php elseif($payment->status == 'pending'): ?>
                                            <span class="badge bg-warning text-dark">पेन्डिङ</span>
                                        <?php else: ?>
                                            <span class="badge bg-danger">असफल</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            <a href="<?php echo e(route('owner.payments.show', $payment)); ?>" 
                                               class="btn btn-info" title="हेर्नुहोस्">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="<?php echo e(route('owner.payments.edit', $payment)); ?>" 
                                               class="btn btn-warning" title="सम्पादन गर्नुहोस्">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="<?php echo e(route('owner.payments.destroy', $payment)); ?>" 
                                                  method="POST" class="d-inline">
                                                <?php echo csrf_field(); ?>
                                                <?php echo method_field('DELETE'); ?>
                                                <button type="submit" class="btn btn-danger" 
                                                        onclick="return confirm('के तपाईं यो भुक्तानी मेटाउन निश्चित हुनुहुन्छ?')"
                                                        title="मेटाउनुहोस्">
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

                <!-- Pagination -->
                <div class="d-flex justify-content-between align-items-center mt-3">
                    <div class="text-muted">
                        देखाइएको: <?php echo e($payments->firstItem()); ?> - <?php echo e($payments->lastItem()); ?> कुल: <?php echo e($payments->total()); ?>

                    </div>
                    <?php echo e($payments->links()); ?>

                </div>
            <?php else: ?>
                <div class="text-center py-4">
                    <i class="fas fa-money-bill-wave fa-3x text-gray-300 mb-3"></i>
                    <h5 class="text-gray-500">कुनै भुक्तानी फेला परेन</h5>
                    <p class="text-muted">पहिलो भुक्तानी थप्नुहोस्</p>
                    <a href="<?php echo e(route('owner.payments.create')); ?>" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i>नयाँ भुक्तानी
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Auto-hide success/error messages after 5 seconds
        setTimeout(function() {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(alert => {
                const bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            });
        }, 5000);
    });
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.owner', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\My Projects\HostelHub\resources\views\owner\payments\index.blade.php ENDPATH**/ ?>