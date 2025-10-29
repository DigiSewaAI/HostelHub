

<?php $__env->startSection('title', 'सबै बुकिंगहरू'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">सबै बुकिंगहरू</h1>
        <div>
            <a href="<?php echo e(route('admin.bookings.pending')); ?>" class="btn btn-warning btn-sm me-2 nepali">
                <i class="fas fa-clock me-1"></i> पेन्डिङ बुकिंगहरू
                <span class="badge bg-danger"><?php echo e($pendingCount); ?></span>
            </a>
        </div>
    </div>

    <?php if(session('success')): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?php echo e(session('success')); ?>

            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <div class="card shadow mb-4">
        <div class="card-header bg-white py-3">
            <h6 class="m-0 font-weight-bold text-primary nepali">बुकिंगहरू फिल्टर गर्नुहोस्</h6>
        </div>
        <div class="card-body">
            <form method="GET" action="<?php echo e(route('admin.bookings.index')); ?>">
                <div class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label nepali">होस्टेल</label>
                        <select class="form-select nepali" name="hostel_id">
                            <option value="">सबै होस्टेलहरू</option>
                            <?php $__currentLoopData = $hostels; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $hostel): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($hostel->id); ?>" <?php echo e(request('hostel_id') == $hostel->id ? 'selected' : ''); ?>>
                                <?php echo e($hostel->name); ?>

                            </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label nepali">स्थिति</label>
                        <select class="form-select nepali" name="status">
                            <option value="">सबै स्थितिहरू</option>
                            <option value="pending" <?php echo e(request('status') == 'pending' ? 'selected' : ''); ?>>पेन्डिङ</option>
                            <option value="approved" <?php echo e(request('status') == 'approved' ? 'selected' : ''); ?>>स्वीकृत</option>
                            <option value="rejected" <?php echo e(request('status') == 'rejected' ? 'selected' : ''); ?>>अस्वीकृत</option>
                            <option value="cancelled" <?php echo e(request('status') == 'cancelled' ? 'selected' : ''); ?>>रद्द भएको</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label nepali">मिति सीमा</label>
                        <input type="text" class="form-control date-range nepali" name="date_range"
                               value="<?php echo e(request('date_range', now()->subDays(30)->format('Y-m-d') . ' to ' . now()->format('Y-m-d'))); ?>">
                    </div>
                    <div class="col-md-2">
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
                            <a href="<?php echo e(route('admin.bookings.index')); ?>" class="btn btn-outline-secondary nepali">
                                <i class="fas fa-sync-alt me-1"></i> रीसेट
                            </a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1 nepali">
                                कुल बुकिंगहरू
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo e($totalBookings); ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-calendar fa-2x text-gray-300"></i>
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
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1 nepali">
                                पेन्डिङ
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo e($pendingCount); ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clock fa-2x text-gray-300"></i>
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
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1 nepali">
                                स्वीकृत
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo e($approvedCount); ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-check-circle fa-2x text-gray-300"></i>
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
                            <div class="text-xs font-weight-bold text-danger text-uppercase mb-1 nepali">
                                अस्वीकृत
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo e($rejectedCount); ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-times-circle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow">
        <div class="card-header bg-white py-3">
            <h6 class="m-0 font-weight-bold text-primary nepali">सबै बुकिंगहरू</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover" id="dataTable" width="100%" cellspacing="0">
                    <thead class="table-light">
                        <tr>
                            <th class="nepali">आईडी</th>
                            <th class="nepali">विद्यार्थी</th>
                            <th class="nepali">होस्टेल</th>
                            <th class="nepali">कोठा</th>
                            <th class="nepali">चेक-इन</th>
                            <th class="nepali">चेक-आउट</th>
                            <th class="nepali">रकम</th>
                            <th class="nepali">स्थिति</th>
                            <th class="nepali">मिति</th>
                            <th class="nepali text-center">कार्यहरू</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = $bookings; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $booking): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td>#<?php echo e($booking->id); ?></td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div>
                                        <div class="nepali"><?php echo e($booking->student->name); ?></div>
                                        <small class="text-muted"><?php echo e($booking->student->mobile); ?></small>
                                    </div>
                                </div>
                            </td>
                            <td><?php echo e($booking->room->hostel->name); ?></td>
                            <td><?php echo e($booking->room->room_number); ?></td>
                            <td><?php echo e($booking->check_in_date->format('Y-m-d')); ?></td>
                            <td><?php echo e($booking->check_out_date->format('Y-m-d')); ?></td>
                            <td class="fw-bold text-success">रु <?php echo e(number_format($booking->room->price_per_semester, 2)); ?></td>
                            <td>
                                <?php if($booking->status === 'pending'): ?>
                                    <span class="badge bg-warning nepali">पेन्डिङ</span>
                                <?php elseif($booking->status === 'approved'): ?>
                                    <span class="badge bg-success nepali">स्वीकृत</span>
                                <?php elseif($booking->status === 'rejected'): ?>
                                    <span class="badge bg-danger nepali">अस्वीकृत</span>
                                <?php else: ?>
                                    <span class="badge bg-secondary nepali">रद्द भएको</span>
                                <?php endif; ?>
                            </td>
                            <td><?php echo e($booking->created_at->format('d M Y')); ?></td>
                            <td>
                                <div class="d-flex gap-2 justify-content-center">
                                    <a href="<?php echo e(route('admin.bookings.show', $booking)); ?>" 
                                       class="btn btn-info btn-sm"
                                       title="विवरण हेर्नुहोस्">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    
                                    <?php if($booking->status === 'pending'): ?>
                                    <form action="<?php echo e(route('admin.bookings.approve', $booking)); ?>" method="POST" class="d-inline">
                                        <?php echo csrf_field(); ?>
                                        <?php echo method_field('PUT'); ?>
                                        <button type="submit" class="btn btn-success btn-sm"
                                                title="स्वीकृत गर्नुहोस्"
                                                onclick="return confirm('के तपाईं यो बुकिंग स्वीकृत गर्न निश्चित हुनुहुन्छ?')">
                                            <i class="fas fa-check"></i>
                                        </button>
                                    </form>
                                    
                                    <button type="button" class="btn btn-danger btn-sm"
                                            data-bs-toggle="modal"
                                            data-bs-target="#rejectModal<?php echo e($booking->id); ?>"
                                            title="अस्वीकृत गर्नुहोस्">
                                        <i class="fas fa-times"></i>
                                    </button>
                                    <?php endif; ?>

                                    <form action="<?php echo e(route('admin.bookings.destroy', $booking)); ?>" method="POST" class="d-inline">
                                        <?php echo csrf_field(); ?>
                                        <?php echo method_field('DELETE'); ?>
                                        <button type="submit" class="btn btn-outline-danger btn-sm"
                                                title="मेट्नुहोस्"
                                                onclick="return confirm('के तपाईं यो बुकिंग मेटाउन निश्चित हुनुहुन्छ?')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>

                                <!-- Reject Modal for each booking -->
                                <?php if($booking->status === 'pending'): ?>
                                <div class="modal fade" id="rejectModal<?php echo e($booking->id); ?>" tabindex="-1">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title nepali">बुकिंग अस्वीकृत गर्नुहोस्</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                <p class="nepali">बुकिंग #<?php echo e($booking->id); ?> लाई अस्वीकृत गर्नुहुन्छ?</p>
                                                <form action="<?php echo e(route('admin.bookings.reject', $booking)); ?>" method="POST" id="rejectForm<?php echo e($booking->id); ?>">
                                                    <?php echo csrf_field(); ?>
                                                    <?php echo method_field('PUT'); ?>
                                                    <div class="mb-3">
                                                        <label for="reason<?php echo e($booking->id); ?>" class="form-label nepali">कारण (वैकल्पिक)</label>
                                                        <textarea class="form-control" id="reason<?php echo e($booking->id); ?>" name="reason" rows="3" placeholder="अस्वीकृतको कारण लेख्नुहोस्..."></textarea>
                                                    </div>
                                                </form>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary nepali" data-bs-dismiss="modal">रद्द गर्नुहोस्</button>
                                                <button type="submit" form="rejectForm<?php echo e($booking->id); ?>" class="btn btn-danger nepali">अस्वीकृत गर्नुहोस्</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-between mt-3">
                <div class="nepali">
                    देखाइरहेको छ <?php echo e($bookings->firstItem()); ?> देखि <?php echo e($bookings->lastItem()); ?> सम्म, कुल <?php echo e($bookings->total()); ?> मध्ये
                </div>
                <div>
                    <?php echo e($bookings->links()); ?>

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
<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\My Projects\HostelHub\resources\views\admin\bookings\index.blade.php ENDPATH**/ ?>