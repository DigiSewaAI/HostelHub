

<?php $__env->startSection('title', 'बैंक हस्तान्तरण विवरण'); ?>

<?php $__env->startSection('content'); ?>
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header bg-info text-white">
                    <h4 class="mb-0 nepali">बैंक हस्तान्तरण विवरण</h4>
                </div>
                <div class="card-body">
                    <!-- Bank Details -->
                    <div class="alert alert-info">
                        <h6 class="nepali">हाम्रो बैंक विवरण:</h6>
                        <div class="row">
                            <div class="col-md-6">
                                <strong class="nepali">बैंक:</strong> <?php echo e($bankDetails['bank_name']); ?><br>
                                <strong class="nepali">खाता नम्बर:</strong> <?php echo e($bankDetails['account_number']); ?><br>
                                <strong class="nepali">खाता धनी:</strong> <?php echo e($bankDetails['account_name']); ?>

                            </div>
                            <div class="col-md-6">
                                <?php if($bankDetails['branch']): ?>
                                <strong class="nepali">शाखा:</strong> <?php echo e($bankDetails['branch']); ?><br>
                                <?php endif; ?>
                                <strong class="nepali">रकम:</strong> रु <?php echo e(number_format($amount, 2)); ?>

                            </div>
                        </div>
                    </div>

                    <form action="<?php echo e(route('payment.bank.request')); ?>" method="POST" enctype="multipart/form-data">
                        <?php echo csrf_field(); ?>
                        
                        <input type="hidden" name="amount" value="<?php echo e($amount); ?>">
                        <input type="hidden" name="purpose" value="<?php echo e($purpose); ?>">
                        <input type="hidden" name="booking_id" value="<?php echo e($bookingId); ?>">
                        <input type="hidden" name="plan_id" value="<?php echo e($planId); ?>">

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label nepali">बैंकको नाम *</label>
                                    <input type="text" class="form-control" name="bank_name" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label nepali">तपाईंको खाता नम्बर *</label>
                                    <input type="text" class="form-control" name="account_number" required>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label nepali">हस्तान्तरण आईडी *</label>
                                    <input type="text" class="form-control" name="transaction_id" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label nepali">हस्तान्तरण मिति *</label>
                                    <input type="date" class="form-control" name="transaction_date" required>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label nepali">भुक्तानी रसिदको स्क्रिनसट *</label>
                            <input type="file" class="form-control" name="screenshot" accept="image/*" required>
                            <div class="form-text nepali">जेपिइजी, पिङ्ग, वा जिआईएफ फाइल, अधिकतम ५ एमबी</div>
                        </div>

                        <div class="alert alert-warning">
                            <small class="nepali">
                                <strong>नोट:</strong> तपाईंको भुक्तानी प्रशासकद्वारा जाँच गरिएपछि मात्र स्वीकृत गरिनेछ। 
                                यसले २४ घण्टा समय लिन सक्छ।
                            </small>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary nepali">
                                भुक्तानी विवरण पेश गर्नुहोस्
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\HostelHub\resources\views\payment\bank_form.blade.php ENDPATH**/ ?>