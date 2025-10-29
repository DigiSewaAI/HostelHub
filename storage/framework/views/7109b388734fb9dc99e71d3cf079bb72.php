

<?php $__env->startSection('title', 'भुक्तानी सत्यापन'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">भुक्तानी सत्यापन</h1>
        <span class="badge badge-warning badge-pill"><?php echo e($pendingPayments->total()); ?> पेन्डिङ</span>
    </div>

    <?php if(session('success')): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?php echo e(session('success')); ?>

            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <div class="card shadow mb-4">
        <div class="card-header bg-white py-3">
            <h6 class="m-0 font-weight-bold text-primary nepali">फिल्टर गर्नुहोस्</h6>
        </div>
        <div class="card-body">
            <form method="GET" action="<?php echo e(route('admin.payments.verification')); ?>">
                <div class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label nepali">भुक्तानी विधि</label>
                        <select class="form-select nepali" name="method">
                            <option value="">सबै विधिहरू</option>
                            <option value="cash" <?php echo e(request('method') == 'cash' ? 'selected' : ''); ?>>नगद</option>
                            <option value="bank_transfer" <?php echo e(request('method') == 'bank_transfer' ? 'selected' : ''); ?>>बैंक ट्रान्सफर</option>
                            <option value="online" <?php echo e(request('method') == 'online' ? 'selected' : ''); ?>>अनलाइन</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label nepali">मिति सीमा</label>
                        <input type="text" class="form-control date-range nepali" name="date_range"
                               value="<?php echo e(request('date_range', now()->subDays(7)->format('Y-m-d') . ' to ' . now()->format('Y-m-d'))); ?>">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label nepali">विद्यार्थी खोज्नुहोस्</label>
                        <input type="text" class="form-control nepali" name="search"
                               placeholder="नाम वा मोबाइल नम्बर"
                               value="<?php echo e(request('search')); ?>">
                    </div>
                    <div class="col-md-2 d-flex align-items-end">
                        <div class="d-grid gap-2 w-100">
                            <button type="submit" class="btn btn-primary nepali">
                                <i class="fas fa-filter me-1"></i> फिल्टर
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <?php if($pendingPayments->count() > 0): ?>
    <div class="card shadow">
        <div class="card-header bg-white py-3">
            <h6 class="m-0 font-weight-bold text-primary nepali">सत्यापनका लागि पेन्डिङ भुक्तानीहरू</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover" id="dataTable" width="100%" cellspacing="0">
                    <thead class="table-light">
                        <tr>
                            <th class="nepali">आईडी</th>
                            <th class="nepali">विद्यार्थी</th>
                            <th class="nepali">रकम</th>
                            <th class="nepali">विधि</th>
                            <th class="nepali">लेनदेन आईडी</th>
                            <th class="nepali">बुकिंग</th>
                            <th class="nepali">मिति</th>
                            <th class="nepali text-center">सत्यापन</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = $pendingPayments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $payment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td>#<?php echo e($payment->id); ?></td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div>
                                        <div class="nepali"><?php echo e($payment->student->name); ?></div>
                                        <small class="text-muted"><?php echo e($payment->student->mobile); ?></small>
                                    </div>
                                </div>
                            </td>
                            <td class="fw-bold text-success">रु <?php echo e(number_format($payment->amount, 2)); ?></td>
                            <td>
                                <span class="badge bg-<?php echo e($payment->payment_method === 'cash' ? 'success' : ($payment->payment_method === 'bank_transfer' ? 'info' : 'primary')); ?> nepali">
                                    <?php echo e($payment->payment_method === 'cash' ? 'नगद' : ($payment->payment_method === 'bank_transfer' ? 'बैंक' : 'अनलाइन')); ?>

                                </span>
                            </td>
                            <td>
                                <span class="text-truncate d-inline-block" style="max-width: 150px;"
                                      title="<?php echo e($payment->transaction_id); ?>">
                                    <?php echo e($payment->transaction_id ?? 'N/A'); ?>

                                </span>
                            </td>
                            <td>
                                <?php if($payment->booking): ?>
                                <a href="<?php echo e(route('admin.bookings.show', $payment->booking)); ?>" class="text-decoration-none">
                                    बुकिंग #<?php echo e($payment->booking->id); ?>

                                </a>
                                <?php else: ?>
                                <span class="text-muted">N/A</span>
                                <?php endif; ?>
                            </td>
                            <td><?php echo e($payment->created_at->format('d M Y, h:i A')); ?></td>
                            <td>
                                <div class="d-flex gap-2 justify-content-center">
                                    <form action="<?php echo e(route('admin.payments.verify', $payment)); ?>" method="POST" class="d-inline">
                                        <?php echo csrf_field(); ?>
                                        <?php echo method_field('PUT'); ?>
                                        <input type="hidden" name="status" value="completed">
                                        <button type="submit" class="btn btn-success btn-sm nepali"
                                                onclick="return confirm('के तपाईं यो भुक्तानी सत्यापित गर्न निश्चित हुनुहुन्छ?')">
                                            <i class="fas fa-check me-1"></i> स्वीकृत
                                        </button>
                                    </form>
                                    
                                    <button type="button" class="btn btn-danger btn-sm"
                                            data-bs-toggle="modal"
                                            data-bs-target="#rejectPaymentModal<?php echo e($payment->id); ?>">
                                        <i class="fas fa-times me-1"></i> अस्वीकृत
                                    </button>

                                    <a href="<?php echo e(route('admin.payments.show', $payment)); ?>" 
                                       class="btn btn-info btn-sm"
                                       title="विवरण हेर्नुहोस्">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </div>

                                <!-- Reject Payment Modal -->
                                <div class="modal fade" id="rejectPaymentModal<?php echo e($payment->id); ?>" tabindex="-1">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title nepali">भुक्तानी अस्वीकृत गर्नुहोस्</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                <p class="nepali">भुक्तानी #<?php echo e($payment->id); ?> लाई अस्वीकृत गर्नुहुन्छ?</p>
                                                <form action="<?php echo e(route('admin.payments.verify', $payment)); ?>" method="POST" id="rejectPaymentForm<?php echo e($payment->id); ?>">
                                                    <?php echo csrf_field(); ?>
                                                    <?php echo method_field('PUT'); ?>
                                                    <input type="hidden" name="status" value="failed">
                                                    <div class="mb-3">
                                                        <label for="rejection_reason<?php echo e($payment->id); ?>" class="form-label nepali">कारण (आवश्यक)</label>
                                                        <textarea class="form-control" id="rejection_reason<?php echo e($payment->id); ?>" name="rejection_reason" rows="3" required placeholder="अस्वीकृतको कारण लेख्नुहोस्..."></textarea>
                                                    </div>
                                                </form>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary nepali" data-bs-dismiss="modal">रद्द गर्नुहोस्</button>
                                                <button type="submit" form="rejectPaymentForm<?php echo e($payment->id); ?>" class="btn btn-danger nepali">अस्वीकृत गर्नुहोस्</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-between mt-3">
                <div class="nepali">
                    देखाइरहेको छ <?php echo e($pendingPayments->firstItem()); ?> देखि <?php echo e($pendingPayments->lastItem()); ?> सम्म, कुल <?php echo e($pendingPayments->total()); ?> मध्ये
                </div>
                <div>
                    <?php echo e($pendingPayments->links()); ?>

                </div>
            </div>
        </div>
    </div>
    <?php else: ?>
    <div class="text-center py-5">
        <i class="fas fa-check-circle fa-3x text-gray-300 mb-3"></i>
        <h4 class="text-gray-500 nepali">कुनै पेन्डिङ भुक्तानी छैन</h4>
        <p class="text-muted nepali">सबै भुक्तानीहरू सत्यापित भइसकेका छन्।</p>
    </div>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize date range picker
        $('.date-range').daterangepicker({
            locale: {
                format: 'YYYY-MM-DD',
                separator: ' to ',
                applyLabel: 'लागू गर्नुहोस्',
                cancelLabel: 'रद्द गर्नुहोस्',
                customRangeLabel: 'कस्टम'
            }
        });
    });
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\My Projects\HostelHub\resources\views\admin\payments\verification.blade.php ENDPATH**/ ?>