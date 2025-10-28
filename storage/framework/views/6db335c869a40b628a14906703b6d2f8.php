<?php $__env->startSection('title', 'भुक्तानी प्रबन्धन'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex align-items-center justify-content-between">
                <h2 class="mb-0 nepali">भुक्तानी प्रबन्धन</h2>
                <div class="d-flex gap-2">
                    <?php if (\Illuminate\Support\Facades\Blade::check('role', ['admin', 'owner'])): ?>
                    <a href="<?php echo e(route('admin.payments.create')); ?>" class="btn btn-primary nepali">
                        <i class="fas fa-plus me-2"></i>नयाँ भुक्तानी
                    </a>
                    <?php endif; ?>
                    
                    <?php if (\Illuminate\Support\Facades\Blade::check('role', 'admin')): ?>
                    <div class="dropdown">
                        <button class="btn btn-secondary dropdown-toggle nepali" type="button" id="reportDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-file-export me-1"></i> प्रतिवेदनहरू
                        </button>
                        <ul class="dropdown-menu" aria-labelledby="reportDropdown">
                            <li><a class="dropdown-item nepali" href="<?php echo e(route('admin.payments.export', ['format' => 'csv'])); ?>"><i class="fas fa-file-csv me-1"></i> CSV निर्यात गर्नुहोस्</a></li>
                            <li><a class="dropdown-item nepali" href="<?php echo e(route('admin.payments.export', ['format' => 'excel'])); ?>"><i class="fas fa-file-excel me-1"></i> Excel निर्यात गर्नुहोस्</a></li>
                            <li><a class="dropdown-item nepali" href="<?php echo e(route('admin.payments.report')); ?>"><i class="fas fa-chart-bar me-1"></i> प्रतिवेदन हेर्नुहोस्</a></li>
                        </ul>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <?php if(session('success')): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?php echo e(session('success')); ?>

            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <?php if (\Illuminate\Support\Facades\Blade::check('role', ['admin', 'owner'])): ?>
    <div class="card shadow mb-4">
        <div class="card-header bg-white py-3">
            <h6 class="m-0 font-weight-bold text-primary nepali">भुक्तानीहरू फिल्टर गर्नुहोस्</h6>
        </div>
        <div class="card-body">
            <form method="GET" action="<?php echo e(route('admin.payments.index')); ?>">
                <div class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label nepali">मिति सीमा</label>
                        <input type="text" class="form-control date-range nepali" name="date_range"
                               value="<?php echo e(request('date_range', now()->subDays(30)->format('Y-m-d') . ' to ' . now()->format('Y-m-d'))); ?>">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label nepali">भुक्तानी विधि</label>
                        <select class="form-select nepali" name="payment_method">
                            <option value="">सबै विधिहरू</option>
                            <option value="khalti" <?php echo e(request('payment_method') == 'khalti' ? 'selected' : ''); ?>>खल्ती</option>
                            <option value="esewa" <?php echo e(request('payment_method') == 'esewa' ? 'selected' : ''); ?>>eSewa</option>
                            <option value="cash" <?php echo e(request('payment_method') == 'cash' ? 'selected' : ''); ?>>नगद</option>
                            <option value="bank_transfer" <?php echo e(request('payment_method') == 'bank_transfer' ? 'selected' : ''); ?>>बैंक हस्तान्तरण</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label nepali">स्थिति</label>
                        <select class="form-select nepali" name="status">
                            <option value="">सबै स्थितिहरू</option>
                            <option value="completed" <?php echo e(request('status') == 'completed' ? 'selected' : ''); ?>>पूर्ण</option>
                            <option value="pending" <?php echo e(request('status') == 'pending' ? 'selected' : ''); ?>>प्रतीक्षामा</option>
                            <option value="failed" <?php echo e(request('status') == 'failed' ? 'selected' : ''); ?>>असफल</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label nepali">विद्यार्थी खोज्नुहोस्</label>
                        <input type="text" class="form-control nepali" name="search"
                               placeholder="विद्यार्थीको नाम वा मोबाइल नम्बरले खोज्नुहोस्"
                               value="<?php echo e(request('search')); ?>">
                    </div>
                    <div class="col-md-2 d-flex align-items-end">
                        <div class="d-grid gap-2 w-100">
                            <button type="submit" class="btn btn-primary nepali">
                                <i class="fas fa-filter me-1"></i> फिल्टर
                            </button>
                            <a href="<?php echo e(route('admin.payments.index')); ?>" class="btn btn-outline-secondary nepali">
                                <i class="fas fa-sync-alt me-1"></i> रीसेट
                            </a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <?php endif; ?>

    <div class="card shadow">
        <div class="card-header bg-white py-3">
            <h6 class="m-0 font-weight-bold text-primary nepali">सबै भुक्तानीहरू</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover" id="dataTable" width="100%" cellspacing="0">
                    <thead class="table-light">
                        <tr>
                            <th class="nepali">आईडी</th>
                            <th class="nepali">विद्यार्थी</th>
                            <th class="nepali">रकम</th>
                            <th class="nepali">विधि</th>
                            <?php if (\Illuminate\Support\Facades\Blade::check('role', ['admin', 'owner'])): ?>
                            <th class="nepali">लेनदेन आईडी</th>
                            <?php endif; ?>
                            <th class="nepali">मिति</th>
                            <th class="nepali">स्थिति</th>
                            <th class="nepali text-center">कार्यहरू</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = $payments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $payment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td><?php echo e(str_pad($payment->id, 6, '0', STR_PAD_LEFT)); ?></td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <?php if($payment->student && $payment->student->room && $payment->student->room->image): ?>
                                        <img src="<?php echo e(asset('storage/'.$payment->student->room->image)); ?>"
                                             class="rounded me-2"
                                             width="30"
                                             height="30"
                                             style="object-fit: cover;">
                                    <?php else: ?>
                                        <div class="bg-light rounded me-2 d-flex align-items-center justify-content-center" style="width: 30px; height: 30px;">
                                            <i class="fas fa-user text-muted"></i>
                                        </div>
                                    <?php endif; ?>
                                    <div>
                                        <div class="nepali"><?php echo e($payment->student->name ?? 'N/A'); ?></div>
                                        <small class="text-muted"><?php echo e($payment->student->mobile ?? 'N/A'); ?></small>
                                    </div>
                                </div>
                            </td>
                            <td class="fw-bold text-success">रु <?php echo e(number_format($payment->amount, 2)); ?></td>
                            <td>
                                <span class="badge bg-<?php echo e($payment->payment_method === 'khalti' ? 'primary' : ($payment->payment_method === 'cash' ? 'success' : ($payment->payment_method === 'esewa' ? 'warning' : 'info'))); ?> nepali">
                                    <?php echo e($payment->getPaymentMethodText()); ?>

                                </span>
                            </td>
                            <?php if (\Illuminate\Support\Facades\Blade::check('role', ['admin', 'owner'])): ?>
                            <td>
                                <span class="text-truncate d-inline-block" style="max-width: 150px;"
                                      title="<?php echo e($payment->transaction_id); ?>">
                                    <?php echo e($payment->transaction_id ?? 'N/A'); ?>

                                </span>
                            </td>
                            <?php endif; ?>
                            <td><?php echo e($payment->created_at->format('d M Y, h:i A')); ?></td>
                            <td>
                                <span class="badge <?php echo e($payment->status === 'completed' ? 'bg-success' : ($payment->status === 'pending' ? 'bg-warning' : 'bg-danger')); ?> nepali">
                                    <?php echo e($payment->status === 'completed' ? 'पूर्ण' : ($payment->status === 'pending' ? 'प्रतीक्षामा' : 'असफल')); ?>

                                </span>
                            </td>
                            <td>
                                <div class="d-flex gap-2 justify-content-center">
                                    <a href="<?php echo e(route('admin.payments.show', $payment)); ?>"
                                       class="btn btn-sm btn-info"
                                       title="विवरण हेर्नुहोस्">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    
                                    <?php if (\Illuminate\Support\Facades\Blade::check('role', ['admin', 'owner'])): ?>
                                    <!-- Bank Transfer Approval Actions -->
                                    <?php if($payment->payment_method === 'bank_transfer' && $payment->status === 'pending'): ?>
                                    <div class="dropdown">
                                        <button class="btn btn-sm btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                            कार्यहरू
                                        </button>
                                        <ul class="dropdown-menu">
                                            <li>
                                                <a class="dropdown-item nepali" href="<?php echo e(route('admin.payments.proof', $payment)); ?>" target="_blank">
                                                    <i class="fas fa-eye me-1"></i> रसिद हेर्नुहोस्
                                                </a>
                                            </li>
                                            <li>
                                                <form action="<?php echo e(route('admin.payments.approve-bank', $payment)); ?>" method="POST">
                                                    <?php echo csrf_field(); ?>
                                                    <button type="submit" class="dropdown-item nepali text-success" 
                                                            onclick="return confirm('के तपाईं यो बैंक हस्तान्तरण स्वीकृत गर्न चाहनुहुन्छ?')">
                                                        <i class="fas fa-check me-1"></i> स्वीकृत गर्नुहोस्
                                                    </button>
                                                </form>
                                            </li>
                                            <li>
                                                <button type="button" class="dropdown-item nepali text-danger" 
                                                        data-bs-toggle="modal" data-bs-target="#rejectModal<?php echo e($payment->id); ?>">
                                                    <i class="fas fa-times me-1"></i> अस्वीकृत गर्नुहोस्
                                                </button>
                                            </li>
                                        </ul>
                                    </div>

                                    <!-- Reject Modal -->
                                    <div class="modal fade" id="rejectModal<?php echo e($payment->id); ?>" tabindex="-1">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title nepali">भुक्तानी अस्वीकृत गर्नुहोस्</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <form action="<?php echo e(route('admin.payments.reject-bank', $payment)); ?>" method="POST">
                                                    <?php echo csrf_field(); ?>
                                                    <div class="modal-body">
                                                        <div class="mb-3">
                                                            <label class="form-label nepali">कारण</label>
                                                            <textarea class="form-control" name="reason" rows="3" 
                                                                      placeholder="अस्वीकृत गर्नुको कारण लेख्नुहोस्..." required></textarea>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary nepali" data-bs-dismiss="modal">रद्द गर्नुहोस्</button>
                                                        <button type="submit" class="btn btn-danger nepali">अस्वीकृत गर्नुहोस्</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                    <?php else: ?>
                                        <!-- Existing actions for non-bank transfer payments -->
                                        <?php if($payment->status === 'pending'): ?>
                                        <form action="<?php echo e(route('admin.payments.updateStatus', $payment)); ?>"
                                              method="POST"
                                              style="display: inline;">
                                            <?php echo csrf_field(); ?>
                                            <?php echo method_field('PUT'); ?>
                                            <input type="hidden" name="status" value="completed">
                                            <button type="submit"
                                                    class="btn btn-sm btn-success"
                                                    title="पूर्ण गर्नुहोस्"
                                                    onclick="return confirm('के तपाईं यो भुक्तानीलाई पूर्ण गर्न निश्चित हुनुहुन्छ?')">
                                                <i class="fas fa-check"></i>
                                            </button>
                                        </form>
                                        <?php endif; ?>
                                        
                                        <form action="<?php echo e(route('admin.payments.destroy', $payment)); ?>"
                                              method="POST"
                                              class="delete-form"
                                              style="display: inline;">
                                            <?php echo csrf_field(); ?>
                                            <?php echo method_field('DELETE'); ?>
                                            <button type="submit"
                                                    class="btn btn-sm btn-danger"
                                                    title="मेट्नुहोस्"
                                                    onclick="return confirm('के तपाईं यो भुक्तानी रेकर्ड मेटाउन निश्चित हुनुहुन्छ?')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    <?php endif; ?>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-between mt-3">
                <div class="nepali">
                    देखाइरहेको छ <?php echo e($payments->firstItem()); ?> देखि <?php echo e($payments->lastItem()); ?> सम्म, कुल <?php echo e($payments->total()); ?> मध्ये
                </div>
                <div>
                    <?php echo e($payments->links()); ?>

                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize date range picker
        $('.date-range').daterangepicker({
            locale: {
                format: 'YYYY-MM-DD'
            }
        });
    });
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\My Projects\HostelHub\resources\views/admin/payments/index.blade.php ENDPATH**/ ?>