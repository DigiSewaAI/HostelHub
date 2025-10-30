

<?php $__env->startSection('title', 'भुक्तानी रसिद - HostelHub'); ?>

<?php $__env->startSection('content'); ?>
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card shadow">
                <div class="card-header bg-success text-white">
                    <h4 class="mb-0 nepali">भुक्तानी रसिद</h4>
                </div>
                <div class="card-body">
                    <!-- रसिद जानकारी -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="info-box">
                                <small class="text-muted nepali">रसिद नम्बर</small>
                                <div class="info-value h6">#<?php echo e($payment->id); ?></div>
                                
                                <small class="text-muted nepali">जारी मिति</small>
                                <div class="info-value h6"><?php echo e($payment->paid_at->format('Y-m-d')); ?></div>
                                
                                <small class="text-muted nepali">विद्यार्थीको नाम</small>
                                <div class="info-value h6"><?php echo e($user->name); ?></div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="info-box">
                                <small class="text-muted nepali">होस्टल</small>
                                <div class="info-value h6"><?php echo e($hostel->name); ?></div>
                                
                                <small class="text-muted nepali">बुकिंग अवधि</small>
                                <div class="info-value h6">
                                    <?php echo e($booking->check_in->format('Y-m-d')); ?> बाट <?php echo e($booking->check_out->format('Y-m-d')); ?> सम्म
                                </div>
                                
                                <small class="text-muted nepali">भुक्तानी स्थिति</small>
                                <div class="info-value h6 text-success">
                                    <strong>पूरा भयो</strong>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- भुक्तानी विवरण तालिका -->
                    <div class="table-responsive mb-4">
                        <table class="table table-bordered">
                            <thead class="bg-light">
                                <tr>
                                    <th class="nepali">विवरण</th>
                                    <th class="nepali">भुक्तानी विधि</th>
                                    <th class="nepali">रकम (रुपैयाँ)</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="nepali"><?php echo e($hostel->name); ?> को लागि होस्टल बुकिंग भुक्तानी</td>
                                    <td>
                                        <span class="badge badge-info nepali">
                                            <?php if($payment->method == 'esewa'): ?> इसेवा
                                            <?php elseif($payment->method == 'khalti'): ?> खल्ती
                                            <?php elseif($payment->method == 'bank_transfer'): ?> बैंक स्थानान्तरण
                                            <?php elseif($payment->method == 'cash'): ?> नगद
                                            <?php else: ?> <?php echo e($payment->method); ?>

                                            <?php endif; ?>
                                        </span>
                                    </td>
                                    <td class="h6"><strong>रु <?php echo e(number_format($payment->amount, 2)); ?></strong></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- कुल रकम -->
                    <div class="row">
                        <div class="col-md-6 offset-md-6">
                            <div class="total-section p-3 bg-light rounded">
                                <div class="text-center">
                                    <h5 class="nepali">कुल रकम</h5>
                                    <h3 class="text-success">रु <?php echo e(number_format($payment->amount, 2)); ?></h3>
                                    <small class="text-muted nepali">(नेपाली रुपैयाँ)</small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- थप जानकारी -->
                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="additional-info p-3 bg-light rounded">
                                <h6 class="nepali">भुक्तानी विवरण</h6>
                                <div class="row">
                                    <div class="col-md-6">
                                        <p class="mb-1"><strong class="nepali">कारोबार आईडी:</strong> 
                                            <?php echo e($payment->transaction_id ?? 'उपलब्ध छैन'); ?></p>
                                        <p class="mb-1"><strong class="nepali">भुक्तानी मिति:</strong> 
                                            <?php echo e($payment->paid_at->format('Y-m-d h:i A')); ?></p>
                                    </div>
                                    <div class="col-md-6">
                                        <?php if($payment->verified_by): ?>
                                            <p class="mb-1"><strong class="nepali">पुष्टि गर्ने:</strong> 
                                                होस्टल प्रबन्धन</p>
                                            <p class="mb-1"><strong class="nepali">पुष्टि मिति:</strong> 
                                                <?php echo e($payment->verified_at->format('Y-m-d')); ?></p>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- एक्सन बटनहरू -->
                    <div class="row mt-4">
                        <div class="col-12 text-center">
                            <button onclick="window.print()" class="btn btn-primary mr-2 nepali">
                                <i class="fas fa-print"></i> प्रिन्ट गर्नुहोस्
                            </button>
                            <button id="downloadPdf" class="btn btn-success mr-2 nepali">
                                <i class="fas fa-download"></i> PDF डाउनलोड गर्नुहोस्
                            </button>
                            <a href="<?php echo e(route('student.payments.history')); ?>" class="btn btn-secondary nepali">
                                <i class="fas fa-arrow-left"></i> भुक्तानी इतिहासमा फर्कनुहोस्
                            </a>
                        </div>
                    </div>
                </div>

                <!-- फुटर -->
                <div class="card-footer text-center">
                    <small class="text-muted nepali">
                        HostelHub रोज्नुभएकोमा धन्यवाद! यो कम्प्युटर-जनरेट गरिएको रसिद हो। कुनै हस्ताक्षर आवश्यक छैन।<br>
                        कुनै प्रश्नको लागि सम्पर्क: support@hostelhub.com | +९७७-१-XXXXXXX<br>
                        जनरेट गरिएको मिति: <?php echo e(now()->format('Y-m-d h:i A')); ?>

                    </small>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('styles'); ?>
<style>
.info-box {
    margin-bottom: 1rem;
}
.info-box small {
    font-size: 0.8rem;
}
.total-section {
    border: 2px solid #28a745;
}
.additional-info {
    border-left: 4px solid #007bff;
}
@media print {
    .btn {
        display: none !important;
    }
    .card-footer {
        border-top: 1px solid #dee2e6;
    }
}
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
document.getElementById('downloadPdf').addEventListener('click', function() {
    // PDF डाउनलोड गर्ने लोजिक
    window.location.href = "<?php echo e(route('student.payments.receipt.download', $payment)); ?>";
});
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\HostelHub\resources\views\payment\receipt.blade.php ENDPATH**/ ?>