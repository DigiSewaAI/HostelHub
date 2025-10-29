

<?php $__env->startSection('title', 'पेन्डिङ बुकिंगहरू'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">पेन्डिङ बुकिंगहरू</h1>
        <span class="badge badge-warning badge-pill"><?php echo e($pendingBookings->total()); ?> पेन्डिङ</span>
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
            <form method="GET" action="<?php echo e(route('admin.bookings.pending')); ?>">
                <div class="row g-3">
                    <div class="col-md-4">
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
                    <div class="col-md-4">
                        <label class="form-label nepali">मिति सीमा</label>
                        <input type="text" class="form-control date-range nepali" name="date_range"
                               value="<?php echo e(request('date_range', now()->subDays(7)->format('Y-m-d') . ' to ' . now()->format('Y-m-d'))); ?>">
                    </div>
                    <div class="col-md-4 d-flex align-items-end">
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

    <?php if($pendingBookings->count() > 0): ?>
    <div class="card shadow">
        <div class="card-header bg-white py-3">
            <h6 class="m-0 font-weight-bold text-primary nepali">पेन्डिङ बुकिंगहरू</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead class="table-light">
                        <tr>
                            <th class="nepali">आईडी</th>
                            <th class="nepali">विद्यार्थी</th>
                            <th class="nepali">होस्टेल</th>
                            <th class="nepali">कोठा</th>
                            <th class="nepali">चेक-इन</th>
                            <th class="nepali">चेक-आउट</th>
                            <th class="nepali">उद्देश्य</th>
                            <th class="nepali">मिति</th>
                            <th class="nepali text-center">कार्यहरू</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = $pendingBookings; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $booking): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
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
                            <td>
                                <span class="text-truncate d-inline-block" style="max-width: 200px;" 
                                      title="<?php echo e($booking->purpose); ?>">
                                    <?php echo e($booking->purpose); ?>

                                </span>
                            </td>
                            <td><?php echo e($booking->created_at->format('d M Y')); ?></td>
                            <td>
                                <div class="d-flex gap-2 justify-content-center">
                                    <button type="button" class="btn btn-success btn-sm" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#approveModal<?php echo e($booking->id); ?>"
                                            title="स्वीकृत गर्नुहोस्">
                                        <i class="fas fa-check"></i>
                                    </button>
                                    
                                    <button type="button" class="btn btn-danger btn-sm"
                                            data-bs-toggle="modal"
                                            data-bs-target="#rejectModal<?php echo e($booking->id); ?>"
                                            title="अस्वीकृत गर्नुहोस्">
                                        <i class="fas fa-times"></i>
                                    </button>
                                    
                                    <a href="<?php echo e(route('admin.bookings.show', $booking)); ?>" 
                                       class="btn btn-info btn-sm"
                                       title="विवरण हेर्नुहोस्">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </div>

                                <!-- Approve Modal -->
                                <div class="modal fade" id="approveModal<?php echo e($booking->id); ?>" tabindex="-1">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title nepali">बुकिंग स्वीकृत गर्नुहोस्</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                <p class="nepali">के तपाईं बुकिंग #<?php echo e($booking->id); ?> लाई स्वीकृत गर्न निश्चित हुनुहुन्छ?</p>
                                                <p><strong class="nepali">विद्यार्थी:</strong> <?php echo e($booking->student->name); ?></p>
                                                <p><strong class="nepali">होस्टेल:</strong> <?php echo e($booking->room->hostel->name); ?></p>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary nepali" data-bs-dismiss="modal">रद्द गर्नुहोस्</button>
                                                <form action="<?php echo e(route('admin.bookings.approve', $booking)); ?>" method="POST">
                                                    <?php echo csrf_field(); ?>
                                                    <?php echo method_field('PUT'); ?>
                                                    <button type="submit" class="btn btn-success nepali">स्वीकृत गर्नुहोस्</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Reject Modal -->
                                <div class="modal fade" id="rejectModal<?php echo e($booking->id); ?>" tabindex="-1">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title nepali">बुकिंग अस्वीकृत गर्नुहोस्</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                <p class="nepali">के तपाईं बुकिंग #<?php echo e($booking->id); ?> लाई अस्वीकृत गर्न निश्चित हुनुहुन्छ?</p>
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
                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-between mt-3">
                <div class="nepali">
                    देखाइरहेको छ <?php echo e($pendingBookings->firstItem()); ?> देखि <?php echo e($pendingBookings->lastItem()); ?> सम्म, कुल <?php echo e($pendingBookings->total()); ?> मध्ये
                </div>
                <div>
                    <?php echo e($pendingBookings->links()); ?>

                </div>
            </div>
        </div>
    </div>
    <?php else: ?>
    <div class="text-center py-5">
        <i class="fas fa-inbox fa-3x text-gray-300 mb-3"></i>
        <h4 class="text-gray-500 nepali">कुनै पेन्डिङ बुकिंग छैन</h4>
        <p class="text-muted nepali">अहिले कुनै नयाँ बुकिंग अनुरोध छैन।</p>
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
<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\My Projects\HostelHub\resources\views\admin\bookings\pending.blade.php ENDPATH**/ ?>