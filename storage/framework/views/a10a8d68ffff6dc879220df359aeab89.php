

<?php $__env->startSection('title', 'भुक्तानी प्रतिवेदन'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 nepali">भुक्तानी प्रतिवेदन</h1>
        <div>
            <?php if (\Illuminate\Support\Facades\Blade::check('role', ['admin', 'owner'])): ?>
            <a href="<?php echo e(route('payments.export')); ?>" class="btn btn-primary me-2 nepali">
                <i class="fas fa-file-export me-1"></i> निर्यात गर्नुहोस्
            </a>
            <?php endif; ?>
            <a href="<?php echo e(route('payments.index')); ?>" class="btn btn-secondary nepali">
                <i class="fas fa-arrow-left me-1"></i> भुक्तानीहरूमा फर्कनुहोस्
            </a>
        </div>
    </div>

    <!-- मिति फिल्टर फारम -->
    <?php if (\Illuminate\Support\Facades\Blade::check('role', ['admin', 'owner'])): ?>
    <div class="card mb-4">
        <div class="card-header bg-light">
            <h5 class="card-title mb-0 nepali">प्रतिवेदन फिल्टर गर्नुहोस्</h5>
        </div>
        <div class="card-body">
            <form method="GET" action="<?php echo e(route('payments.report')); ?>">
                <div class="row g-3">
                    <div class="col-md-4">
                        <label for="start_date" class="form-label nepali">सुरु मिति</label>
                        <input type="date" class="form-control" id="start_date" name="start_date" 
                               value="<?php echo e(request('start_date', now()->subDays(30)->format('Y-m-d'))); ?>">
                    </div>
                    <div class="col-md-4">
                        <label for="end_date" class="form-label nepali">अन्त्य मिति</label>
                        <input type="date" class="form-control" id="end_date" name="end_date" 
                               value="<?php echo e(request('end_date', now()->format('Y-m-d'))); ?>">
                    </div>
                    <div class="col-md-4 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary w-100 nepali">
                            <i class="fas fa-filter me-1"></i> फिल्टर लागू गर्नुहोस्
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <?php endif; ?>

    <!-- प्रतिवेदन सारांश -->
    <div class="row mb-4">
        <div class="col-md-4 mb-3">
            <div class="card border-primary">
                <div class="card-body">
                    <h5 class="card-title text-primary nepali">कुल रकम</h5>
                    <p class="card-text fs-3 fw-bold">रु <?php echo e(number_format($totalAmount, 2)); ?></p>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="card border-success">
                <div class="card-body">
                    <h5 class="card-title text-success nepali">पूर्ण भुक्तानीहरू</h5>
                    <p class="card-text fs-3 fw-bold"><?php echo e($completedCount); ?></p>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="card border-warning">
                <div class="card-body">
                    <h5 class="card-title text-warning nepali">प्रतीक्षामा भुक्तानीहरू</h5>
                    <p class="card-text fs-3 fw-bold"><?php echo e($pendingCount); ?></p>
                </div>
            </div>
        </div>
    </div>

    <!-- भुक्तानी तालिका -->
    <div class="card">
        <div class="card-header bg-light">
            <h5 class="card-title mb-0 nepali">भुक्तानी विवरण 
                <?php if (\Illuminate\Support\Facades\Blade::check('role', ['admin', 'owner'])): ?>
                (<?php echo e($startDate); ?> देखि <?php echo e($endDate); ?> सम्म)
                <?php else: ?>
                (तपाइँको भुक्तानीहरू)
                <?php endif; ?>
            </h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th class="nepali">आईडी</th>
                            <?php if (\Illuminate\Support\Facades\Blade::check('role', ['admin', 'owner'])): ?>
                            <th class="nepali">विद्यार्थी</th>
                            <th class="nepali">होस्टल</th>
                            <?php endif; ?>
                            <th class="nepali">रकम</th>
                            <th class="nepali">मिति</th>
                            <th class="nepali">विधि</th>
                            <th class="nepali">स्थिति</th>
                            <?php if (\Illuminate\Support\Facades\Blade::check('role', ['admin', 'owner'])): ?>
                            <th class="nepali">लेनदेन आईडी</th>
                            <?php endif; ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $payments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $payment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td><?php echo e($payment->id); ?></td>
                            <?php if (\Illuminate\Support\Facades\Blade::check('role', ['admin', 'owner'])): ?>
                            <td><?php echo e($payment->student->name ?? 'उपलब्ध छैन'); ?></td>
                            <td><?php echo e($payment->hostel->name ?? 'उपलब्ध छैन'); ?></td>
                            <?php endif; ?>
                            <td>रु <?php echo e(number_format($payment->amount, 2)); ?></td>
                            <td><?php echo e($payment->payment_date); ?></td>
                            <td class="nepali">
                                <?php if($payment->payment_method === 'cash'): ?>
                                    नगद
                                <?php elseif($payment->payment_method === 'bank_transfer'): ?>
                                    बैंक ट्रान्सफर
                                <?php else: ?>
                                    अनलाइन
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if($payment->status === 'completed'): ?>
                                    <span class="badge bg-success nepali">पूर्ण</span>
                                <?php elseif($payment->status === 'pending'): ?>
                                    <span class="badge bg-warning text-dark nepali">प्रतीक्षामा</span>
                                <?php else: ?>
                                    <span class="badge bg-danger nepali">असफल</span>
                                <?php endif; ?>
                            </td>
                            <?php if (\Illuminate\Support\Facades\Blade::check('role', ['admin', 'owner'])): ?>
                            <td><?php echo e($payment->transaction_id ?? 'उपलब्ध छैन'); ?></td>
                            <?php endif; ?>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="<?php if (\Illuminate\Support\Facades\Blade::check('role', ['admin', 'owner'])): ?> 8 <?php else: ?> 5 <?php endif; ?>" class="text-center nepali">
                                चयन गरिएको अवधिका लागि कुनै भुक्तानी फेला परेन
                            </td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            
            <?php if (\Illuminate\Support\Facades\Blade::check('role', ['admin', 'owner'])): ?>
            <div class="d-flex justify-content-between mt-4">
                <div class="nepali">
                    देखाइरहेको छ <?php echo e($payments->firstItem()); ?> देखि <?php echo e($payments->lastItem()); ?> सम्म, कुल <?php echo e($payments->total()); ?> मध्ये
                </div>
                <div>
                    <?php echo e($payments->links()); ?>

                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>
    
    <?php if (\Illuminate\Support\Facades\Blade::check('role', 'student')): ?>
    <div class="alert alert-info mt-4 nepali">
        <i class="fas fa-info-circle me-2"></i>
        यो प्रतिवेदनमा तपाइँको व्यक्तिगत भुक्तानी इतिहास मात्र देखाइएको छ। थप विस्तृत प्रतिवेदनको लागि प्रशासकसँग सम्पर्क गर्नुहोस्।
    </div>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\My Projects\HostelHub\resources\views\admin\payments\report.blade.php ENDPATH**/ ?>