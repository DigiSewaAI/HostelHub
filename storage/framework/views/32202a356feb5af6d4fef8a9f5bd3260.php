

<?php $__env->startSection('title', 'भुक्तानी रिपोर्ट - HostelHub'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <h1 class="h3 mb-4">भुक्तानी रिपोर्ट</h1>
        </div>
    </div>

    <!-- तथ्याङ्क कार्डहरू -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                कुल आय</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">रु <?php echo e(number_format($totalRevenue, 2)); ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-money-bill-wave fa-2x text-gray-300"></i>
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
                                बाँकी बैंक स्थानान्तरण</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo e($pendingTransfers); ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clock fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- म्यानुअल भुक्तानी फर्म -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header">
                    <h6 class="m-0 font-weight-bold text-primary">म्यानुअल भुक्तानी दर्ता गर्नुहोस्</h6>
                </div>
                <div class="card-body">
                    <form action="<?php echo e(route('owner.payments.manual')); ?>" method="POST">
                        <?php echo csrf_field(); ?>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="booking_id">बुकिंग</label>
                                    <select class="form-control" id="booking_id" name="booking_id" required>
                                        <option value="">बुकिंग छान्नुहोस्</option>
                                        <?php $__currentLoopData = \App\Models\Booking::whereIn('hostel_id', auth()->user()->hostels->pluck('id'))->where('status', 'pending_payment')->get(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $booking): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($booking->id); ?>">बुकिंग #<?php echo e($booking->id); ?> - <?php echo e($booking->user->name); ?></option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="amount">रकम (रुपैयाँ)</label>
                                    <input type="number" class="form-control" id="amount" name="amount" step="0.01" required>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="paid_at">भुक्तानी मिति</label>
                                    <input type="date" class="form-control" id="paid_at" name="paid_at" value="<?php echo e(date('Y-m-d')); ?>" required>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>&nbsp;</label>
                                    <button type="submit" class="btn btn-primary btn-block">भुक्तानी दर्ता गर्नुहोस्</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- भुक्तानी तालिका -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header">
                    <h6 class="m-0 font-weight-bold text-primary">सबै भुक्तानीहरू</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" id="paymentsTable">
                            <thead>
                                <tr>
                                    <th>आईडी</th>
                                    <th>विद्यार्थी</th>
                                    <th>होस्टल</th>
                                    <th>रकम</th>
                                    <th>विधि</th>
                                    <th>स्थिति</th>
                                    <th>मिति</th>
                                    <th>कार्यहरू</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__currentLoopData = $payments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $payment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td>#<?php echo e($payment->id); ?></td>
                                    <td><?php echo e($payment->user->name); ?></td>
                                    <td><?php echo e($payment->booking->hostel->name); ?></td>
                                    <td>रु <?php echo e(number_format($payment->amount, 2)); ?></td>
                                    <td>
                                        <span class="badge badge-info">
                                            <?php if($payment->method == 'esewa'): ?> इसेवा
                                            <?php elseif($payment->method == 'khalti'): ?> खल्ती
                                            <?php elseif($payment->method == 'bank_transfer'): ?> बैंक स्थानान्तरण
                                            <?php elseif($payment->method == 'cash'): ?> नगद
                                            <?php else: ?> <?php echo e($payment->method); ?>

                                            <?php endif; ?>
                                        </span>
                                    </td>
                                    <td>
                                        <?php if($payment->status == 'completed'): ?>
                                            <span class="badge badge-success">पूरा भयो</span>
                                        <?php elseif($payment->status == 'pending'): ?>
                                            <span class="badge badge-warning">बाँकी</span>
                                        <?php else: ?>
                                            <span class="badge badge-danger">
                                                <?php if($payment->status == 'rejected'): ?> अस्वीकृत
                                                <?php elseif($payment->status == 'failed'): ?> असफल
                                                <?php else: ?> <?php echo e($payment->status); ?>

                                                <?php endif; ?>
                                            </span>
                                        <?php endif; ?>
                                    </td>
                                    <td><?php echo e($payment->paid_at->format('Y-m-d')); ?></td>
                                    <td>
                                        <?php if($payment->method == 'bank_transfer' && $payment->status == 'pending'): ?>
                                            <button type="button" class="btn btn-sm btn-success" 
                                                    onclick="approvePayment(<?php echo e($payment->id); ?>)">
                                                स्वीकार गर्नुहोस्
                                            </button>
                                            <button type="button" class="btn btn-sm btn-danger" 
                                                    data-toggle="modal" 
                                                    data-target="#rejectModal"
                                                    data-payment-id="<?php echo e($payment->id); ?>">
                                                अस्वीकार गर्नुहोस्
                                            </button>
                                        <?php endif; ?>
                                        <?php if($payment->proof_document): ?>
                                            <a href="<?php echo e(route('owner.payments.proof', $payment)); ?>" 
                                               class="btn btn-sm btn-info" target="_blank">
                                                प्रमाण हेर्नुहोस्
                                            </a>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </div>
                    <?php echo e($payments->links()); ?>

                </div>
            </div>
        </div>
    </div>
</div>

<!-- अस्वीकार गर्ने मोडल -->
<div class="modal fade" id="rejectModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">भुक्तानी अस्वीकार गर्नुहोस्</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <form id="rejectForm" method="POST">
                <?php echo csrf_field(); ?>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="rejection_reason">अस्वीकार गर्ने कारण</label>
                        <textarea class="form-control" id="rejection_reason" name="rejection_reason" rows="3" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">रद्द गर्नुहोस्</button>
                    <button type="submit" class="btn btn-danger">अस्वीकार गर्नुहोस्</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
function approvePayment(paymentId) {
    if (confirm('के तपाइँ यो भुक्तानी स्वीकार गर्न निश्चित हुनुहुन्छ?')) {
        fetch(`/owner/payments/${paymentId}/approve`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>',
                'Content-Type': 'application/json'
            }
        }).then(response => {
            if (response.ok) {
                location.reload();
            }
        });
    }
}

$('#rejectModal').on('show.bs.modal', function (event) {
    var button = $(event.relatedTarget);
    var paymentId = button.data('payment-id');
    var form = $('#rejectForm');
    form.attr('action', `/owner/payments/${paymentId}/reject`);
});
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.owner', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\My Projects\HostelHub\resources\views\owner\payments\report.blade.php ENDPATH**/ ?>