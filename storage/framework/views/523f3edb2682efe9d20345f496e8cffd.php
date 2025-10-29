

<?php $__env->startSection('title', 'भुक्तानी रसिद #' . $payment->id); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">भुक्तानी रसिद</h1>
        <button onclick="window.print()" class="btn btn-primary btn-sm">
            <i class="fas fa-print"></i> प्रिन्ट गर्नुहोस्
        </button>
    </div>

    <div class="row">
        <div class="col-lg-8 mx-auto">
            <div class="card shadow mb-4">
                <div class="card-body">
                    <div class="text-center mb-4">
                        <h2 class="text-primary">भुक्तानी रसिद</h2>
                        <p class="text-muted">रसिद नं: #<?php echo e($payment->id); ?></p>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h6>भुक्तानी गर्ने विवरण</h6>
                            <p><strong>नाम:</strong> <?php echo e($payment->student->user->name); ?></p>
                            <p><strong>ईमेल:</strong> <?php echo e($payment->student->user->email); ?></p>
                            <p><strong>फोन:</strong> <?php echo e($payment->student->phone ?? 'N/A'); ?></p>
                        </div>
                        <div class="col-md-6 text-right">
                            <h6>भुक्तानी विवरण</h6>
                            <p><strong>मिति:</strong> <?php echo e($payment->created_at->format('Y-m-d H:i')); ?></p>
                            <p><strong>स्थिति:</strong> 
                                <?php if($payment->status === 'completed'): ?>
                                <span class="badge badge-success">सफल</span>
                                <?php elseif($payment->status === 'pending'): ?>
                                <span class="badge badge-warning">पेन्डिङ</span>
                                <?php else: ?>
                                <span class="badge badge-danger">असफल</span>
                                <?php endif; ?>
                            </p>
                            <p><strong>भुक्तानी विधि:</strong> <?php echo e($payment->payment_method); ?></p>
                        </div>
                    </div>

                    <div class="table-responsive mb-4">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>विवरण</th>
                                    <th>रकम</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>होस्टेल भाडा - <?php echo e($payment->booking->room->hostel->name); ?></td>
                                    <td>रु <?php echo e(number_format($payment->amount, 2)); ?></td>
                                </tr>
                                <tr>
                                    <td><strong>जम्मा</strong></td>
                                    <td><strong>रु <?php echo e(number_format($payment->amount, 2)); ?></strong></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <h6>बुकिंग विवरण</h6>
                            <p><strong>होस्टेल:</strong> <?php echo e($payment->booking->room->hostel->name); ?></p>
                            <p><strong>कोठा:</strong> <?php echo e($payment->booking->room->room_number); ?></p>
                            <p><strong>अवधि:</strong> 
                                <?php echo e($payment->booking->check_in_date->format('Y-m-d')); ?> बाट 
                                <?php echo e($payment->booking->check_out_date->format('Y-m-d')); ?> सम्म
                            </p>
                        </div>
                        <div class="col-md-6">
                            <div class="text-center mt-4">
                                <div class="border p-3 d-inline-block">
                                    <small class="text-muted">आधिकारिक रसिद</small>
                                    <br>
                                    <strong>Hostel Management System</strong>
                                </div>
                            </div>
                        </div>
                    </div>

                    <hr>

                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i>
                        <strong>नोट:</strong> यो एक कम्प्युटर जनित रसिद हो। कृपया यसलाई सुरक्षित राख्नुहोस्।
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
@media print {
    .d-sm-flex { display: none !important; }
    .btn { display: none !important; }
}
</style>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.student', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\My Projects\HostelHub\resources\views\student\payments\receipt.blade.php ENDPATH**/ ?>