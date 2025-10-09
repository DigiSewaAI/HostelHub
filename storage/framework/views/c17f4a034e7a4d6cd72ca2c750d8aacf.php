<?php $__env->startSection('title', 'होस्टल विवरण: ' . $hostel->name); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <a href="<?php echo e(route('admin.hostels.index')); ?>" class="btn btn-outline-primary">
                    <i class="fas fa-arrow-left me-1"></i> होस्टलहरूमा फर्कनुहोस्
                </a>
                <div class="d-flex gap-2">
                    <a href="<?php echo e(route('admin.hostels.edit', $hostel)); ?>" class="btn btn-warning">
                        <i class="fas fa-edit me-1"></i> सम्पादन गर्नुहोस्
                    </a>
                    <a href="<?php echo e(route('admin.rooms.create', ['hostel_id' => $hostel->id])); ?>" class="btn btn-success">
                        <i class="fas fa-plus me-1"></i> कोठा थप्नुहोस्
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Left Column - Hostel Information -->
        <div class="col-lg-4">
            <div class="card shadow mb-4">
                <div class="card-header bg-primary text-white py-3">
                    <h6 class="m-0 font-weight-bold">
                        <i class="fas fa-info-circle me-2"></i>होस्टल जानकारी
                    </h6>
                </div>
                <div class="card-body">
                    <!-- Hostel Image Section -->
                    <div class="text-center mb-4">
                        <?php if($hostel->image): ?>
                            <img src="<?php echo e(asset('storage/'.$hostel->image)); ?>"
                                 class="img-fluid rounded shadow-lg"
                                 style="max-height: 250px; object-fit: cover; width: 100%;"
                                 alt="<?php echo e($hostel->name); ?> को तस्वीर"
                                 onerror="this.src='data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMjUwIiBoZWlnaHQ9IjI1MCIgdmlld0JveD0iMCAwIDI1MCAyNTAiIGZpbGw9Im5vbmUiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+CjxyZWN0IHdpZHRoPSIyNTAiIGhlaWdodD0iMjUwIiBmaWxsPSIjRjNGNEY2Ii8+CjxwYXRoIGQ9Ik0xMjUgNzVMMTAwIDEwMEg3NVYxMjVINzcuNUwxMDAgMTAyLjVMMTI1IDEyNy41TDE1MCAxMDIuNUwxNzIuNSAxMjVIMTc1VjEwMEgxNTBMMTI1IDc1Wk0xMjUgMTM3LjVMMTAwIDE2Mi41SDc1VjE3NUgxNzVWMTYyLjVIMTUwTDEyNSAxMzcuNVoiIGZpbGw9IiM5Q0EzQTYiLz4KPC9zdmc+Cg=='">
                        <?php else: ?>
                            <div class="bg-light rounded shadow-sm d-flex flex-column align-items-center justify-content-center p-4"
                                 style="height: 250px; width: 100%;">
                                <i class="fas fa-building text-muted mb-3" style="font-size: 4rem;"></i>
                                <span class="text-muted">कुनै तस्वीर उपलब्ध छैन</span>
                                <small class="text-muted mt-1">
                                    <a href="<?php echo e(route('admin.hostels.edit', $hostel)); ?>" class="text-primary">
                                        तस्वीर थप्नुहोस्
                                    </a>
                                </small>
                            </div>
                        <?php endif; ?>
                    </div>

                    <!-- Hostel Details -->
                    <div class="row">
                        <div class="col-6 mb-3">
                            <div class="text-center p-3 bg-light rounded">
                                <div class="text-primary mb-1">
                                    <i class="fas fa-door-open fa-2x"></i>
                                </div>
                                <!-- FIX: Dynamic total rooms count -->
                                <h5 class="mb-0"><?php echo e($totalRooms ?? $hostel->rooms->count()); ?></h5>
                                <small class="text-muted">कुल कोठाहरू</small>
                            </div>
                        </div>
                        <div class="col-6 mb-3">
                            <div class="text-center p-3 bg-light rounded">
                                <div class="text-success mb-1">
                                    <i class="fas fa-bed fa-2x"></i>
                                </div>
                                <!-- FIX: Dynamic available rooms count -->
                                <h5 class="mb-0"><?php echo e($availableRooms ?? $hostel->rooms->where('status', 'available')->count()); ?></h5>
                                <small class="text-muted">उपलब्ध कोठाहरू</small>
                            </div>
                        </div>
                    </div>

                    <div class="list-group list-group-flush">
                        <div class="list-group-item d-flex justify-content-between align-items-center">
                            <span class="fw-bold">स्थिति:</span>
                            <span class="badge <?php echo e($hostel->status === 'active' ? 'bg-success' : ($hostel->status === 'inactive' ? 'bg-secondary' : 'bg-warning')); ?>">
                                <?php if($hostel->status === 'active'): ?>
                                    <i class="fas fa-check-circle me-1"></i>सक्रिय
                                <?php elseif($hostel->status === 'inactive'): ?>
                                    <i class="fas fa-pause-circle me-1"></i>निष्क्रिय
                                <?php else: ?>
                                    <i class="fas fa-tools me-1"></i>मर्मतमा
                                <?php endif; ?>
                            </span>
                        </div>

                        <div class="list-group-item d-flex justify-content-between align-items-center">
                            <span class="fw-bold">प्रबन्धक:</span>
                            <span>
                                <?php if($hostel->owner): ?>
                                    <i class="fas fa-user me-1"></i><?php echo e($hostel->owner->name); ?>

                                <?php else: ?>
                                    <span class="text-muted"><i class="fas fa-user-slash me-1"></i>तोकिएको छैन</span>
                                <?php endif; ?>
                            </span>
                        </div>

                        <div class="list-group-item">
                            <span class="fw-bold">सम्पर्क जानकारी:</span>
                            <div class="mt-2">
                                <div class="d-flex align-items-center mb-1">
                                    <i class="fas fa-user text-primary me-2"></i>
                                    <span><?php echo e($hostel->contact_person ?? 'उपलब्ध छैन'); ?></span>
                                </div>
                                <div class="d-flex align-items-center mb-1">
                                    <i class="fas fa-phone text-success me-2"></i>
                                    <span><?php echo e($hostel->phone ?? 'उपलब्ध छैन'); ?></span>
                                </div>
                                <?php if($hostel->email): ?>
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-envelope text-info me-2"></i>
                                    <span><?php echo e($hostel->email); ?></span>
                                </div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <div class="list-group-item">
                            <span class="fw-bold">ठेगाना:</span>
                            <div class="d-flex align-items-start mt-1">
                                <i class="fas fa-map-marker-alt text-danger me-2 mt-1"></i>
                                <span><?php echo e($hostel->address); ?></span>
                            </div>
                        </div>

                        <div class="list-group-item">
                            <span class="fw-bold">सुविधाहरू:</span>
                            <div class="mt-2">
                                <?php if($hostel->facilities): ?>
                                    <?php
                                        $facilities = json_decode($hostel->facilities, true);
                                    ?>
                                    <?php if(is_array($facilities) && count($facilities) > 0): ?>
                                        <div class="d-flex flex-wrap gap-2">
                                            <?php $__currentLoopData = $facilities; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $facility): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <span class="badge bg-primary">
                                                    <i class="fas fa-check me-1"></i>
                                                    <?php echo e(trim($facility)); ?>

                                                </span>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </div>
                                    <?php else: ?>
                                        <span class="text-muted">कुनै सुविधा उल्लेख गरिएको छैन</span>
                                    <?php endif; ?>
                                <?php else: ?>
                                    <span class="text-muted">कुनै सुविधा उल्लेख गरिएको छैन</span>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions Card -->
            <div class="card shadow">
                <div class="card-header bg-primary text-white py-3">
                    <h6 class="m-0 font-weight-bold">
                        <i class="fas fa-bolt me-2"></i>तिब्र कार्यहरू
                    </h6>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="<?php echo e(route('admin.hostels.edit', $hostel)); ?>" class="btn btn-warning">
                            <i class="fas fa-edit me-2"></i>होस्टल सम्पादन गर्नुहोस्
                        </a>
                        <a href="<?php echo e(route('admin.hostels.availability', $hostel)); ?>" class="btn btn-info">
                            <i class="fas fa-calendar-check me-2"></i>उपलब्धता व्यवस्थापन
                        </a>
                        <a href="<?php echo e(route('admin.rooms.create', ['hostel_id' => $hostel->id])); ?>" class="btn btn-success">
                            <i class="fas fa-plus me-2"></i>नयाँ कोठा थप्नुहोस्
                        </a>
                        <form action="<?php echo e(route('admin.hostels.destroy', $hostel)); ?>" method="POST" class="mt-2">
                            <?php echo csrf_field(); ?>
                            <?php echo method_field('DELETE'); ?>
                            <button type="submit" class="btn btn-danger w-100"
                                    onclick="return confirm('के तपाइँ निश्चित हुनुहुन्छ कि यो होस्टल मेटाउन चाहनुहुन्छ? सबै कोठाहरू र सम्बन्धित डाटा हटाइनेछ।')">
                                <i class="fas fa-trash me-2"></i>होस्टल मेटाउनुहोस्
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column - Rooms and Description -->
        <div class="col-lg-8">
            <!-- Rooms Card -->
            <div class="card shadow">
                <div class="card-header bg-primary text-white py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold">
                        <i class="fas fa-door-open me-2"></i>
                        <?php echo e($hostel->name); ?> का कोठाहरू
                        <!-- FIX: Dynamic room count -->
                        <span class="badge bg-light text-primary ms-2"><?php echo e($hostel->rooms->count()); ?> कोठा</span>
                    </h6>
                    <div class="d-flex gap-2">
                        <a href="<?php echo e(route('admin.rooms.create', ['hostel_id' => $hostel->id])); ?>" class="btn btn-light btn-sm">
                            <i class="fas fa-plus me-1"></i> कोठा थप्नुहोस्
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <?php if($hostel->rooms->isEmpty()): ?>
                        <div class="text-center py-5">
                            <i class="fas fa-door-closed text-muted mb-3" style="font-size: 3rem;"></i>
                            <h5 class="text-muted">यस होस्टलमा अहिले सम्म कुनै कोठा थपिएको छैन</h5>
                            <p class="text-muted">पहिलो कोठा थपेर यस होस्टललाई सक्रिय बनाउनुहोस्</p>
                            <a href="<?php echo e(route('admin.rooms.create', ['hostel_id' => $hostel->id])); ?>" class="btn btn-primary">
                                <i class="fas fa-plus me-1"></i> पहिलो कोठा थप्नुहोस्
                            </a>
                        </div>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-hover table-striped">
                                <thead class="table-light">
                                    <tr>
                                        <th>कोठा नं.</th>
                                        <th>प्रकार</th>
                                        <th>क्षमता</th>
                                        <th>मूल्य</th>
                                        <th>स्थिति</th>
                                        <th>अधिभृतता</th>
                                        <th>कार्यहरू</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $__currentLoopData = $hostel->rooms; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $room): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        <td>
                                            <strong><?php echo e($room->room_number); ?></strong>
                                        </td>
                                        <td>
                                            <?php if($room->type === 'single'): ?>
                                                <span class="badge bg-info">
                                                    <i class="fas fa-user me-1"></i>एकल
                                                </span>
                                            <?php elseif($room->type === 'double'): ?>
                                                <span class="badge bg-success">
                                                    <i class="fas fa-user-friends me-1"></i>दोहोरो
                                                </span>
                                            <?php else: ?>
                                                <span class="badge bg-warning text-dark">
                                                    <i class="fas fa-users me-1"></i>साझा
                                                </span>
                                            <?php endif; ?>
                                        </td>
                                        <td><?php echo e($room->capacity); ?> ओठ</td>
                                        <td>
                                            <strong>रु <?php echo e(number_format($room->price, 2)); ?></strong>
                                            <small class="text-muted d-block">प्रति महिना</small>
                                        </td>
                                        <td>
                                            <span class="badge <?php echo e($room->status === 'available' ? 'bg-success' : ($room->status === 'occupied' ? 'bg-danger' : 'bg-warning')); ?>">
                                                <?php if($room->status === 'available'): ?>
                                                    <i class="fas fa-check me-1"></i>उपलब्ध
                                                <?php elseif($room->status === 'occupied'): ?>
                                                    <i class="fas fa-times me-1"></i>अधिभृत
                                                <?php else: ?>
                                                    <i class="fas fa-tools me-1"></i>मर्मतमा
                                                <?php endif; ?>
                                            </span>
                                        </td>
                                        <td>
                                            <?php
                                                $currentOccupants = $room->students->count();
                                                $occupancyPercentage = $room->capacity > 0 ? ($currentOccupants / $room->capacity) * 100 : 0;
                                            ?>
                                            <div class="progress" style="height: 8px;">
                                                <div class="progress-bar <?php echo e($occupancyPercentage > 80 ? 'bg-danger' : ($occupancyPercentage > 50 ? 'bg-warning' : 'bg-success')); ?>" 
                                                     style="width: <?php echo e($occupancyPercentage); ?>%">
                                                </div>
                                            </div>
                                            <small class="text-muted">
                                                <?php echo e($currentOccupants); ?>/<?php echo e($room->capacity); ?> (<?php echo e(round($occupancyPercentage)); ?>%)
                                            </small>
                                        </td>
                                        <td>
                                            <div class="d-flex gap-1">
                                                <a href="<?php echo e(route('admin.rooms.show', $room)); ?>" 
                                                   class="btn btn-sm btn-info" 
                                                   title="विवरण हेर्नुहोस्"
                                                   data-bs-toggle="tooltip">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="<?php echo e(route('admin.rooms.edit', $room)); ?>" 
                                                   class="btn btn-sm btn-warning" 
                                                   title="सम्पादन गर्नुहोस्"
                                                   data-bs-toggle="tooltip">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Description Card -->
            <div class="card shadow mt-4">
                <div class="card-header bg-primary text-white py-3">
                    <h6 class="m-0 font-weight-bold">
                        <i class="fas fa-file-alt me-2"></i>होस्टल विवरण
                    </h6>
                </div>
                <div class="card-body">
                    <?php if($hostel->description): ?>
                        <div class="bg-light rounded p-4">
                            <p class="mb-0" style="line-height: 1.6;"><?php echo e($hostel->description); ?></p>
                        </div>
                    <?php else: ?>
                        <div class="text-center py-4 text-muted">
                            <i class="fas fa-file-alt mb-3" style="font-size: 2rem;"></i>
                            <p class="mb-0">कुनै विवरण उपलब्ध छैन</p>
                            <small>
                                <a href="<?php echo e(route('admin.hostels.edit', $hostel)); ?>" class="text-primary">
                                    विवरण थप्नुहोस्
                                </a>
                            </small>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Statistics Card -->
            <div class="card shadow mt-4">
                <div class="card-header bg-primary text-white py-3">
                    <h6 class="m-0 font-weight-bold">
                        <i class="fas fa-chart-bar me-2"></i>होस्टल तथ्याङ्क
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-md-3 mb-3">
                            <div class="border rounded p-3">
                                <div class="text-primary mb-2">
                                    <i class="fas fa-door-open fa-2x"></i>
                                </div>
                                <!-- FIX: Dynamic total rooms count -->
                                <h4 class="mb-1"><?php echo e($totalRooms ?? $hostel->rooms->count()); ?></h4>
                                <small class="text-muted">कुल कोठाहरू</small>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <div class="border rounded p-3">
                                <div class="text-success mb-2">
                                    <i class="fas fa-bed fa-2x"></i>
                                </div>
                                <!-- FIX: Dynamic available rooms count -->
                                <h4 class="mb-1"><?php echo e($availableRooms ?? $hostel->rooms->where('status', 'available')->count()); ?></h4>
                                <small class="text-muted">उपलब्ध कोठाहरू</small>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <div class="border rounded p-3">
                                <div class="text-danger mb-2">
                                    <i class="fas fa-user-check fa-2x"></i>
                                </div>
                                <!-- FIX: Dynamic occupied rooms count -->
                                <h4 class="mb-1"><?php echo e($occupiedRooms ?? $hostel->rooms->where('status', 'occupied')->count()); ?></h4>
                                <small class="text-muted">अधिभृत कोठाहरू</small>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <div class="border rounded p-3">
                                <div class="text-warning mb-2">
                                    <i class="fas fa-tools fa-2x"></i>
                                </div>
                                <!-- FIX: Dynamic maintenance rooms count -->
                                <h4 class="mb-1"><?php echo e($hostel->rooms->where('status', 'maintenance')->count()); ?></h4>
                                <small class="text-muted">मर्मतमा कोठाहरू</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize tooltips
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        });

        // Image error handling
        document.querySelectorAll('img').forEach(img => {
            img.addEventListener('error', function() {
                this.src = 'data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMjUwIiBoZWlnaHQ9IjI1MCIgdmlld0JveD0iMCAwIDI1MCAyNTAiIGZpbGw9Im5vbmUiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+CjxyZWN0IHdpZHRoPSIyNTAiIGhlaWdodD0iMjUwIiBmaWxsPSIjRjNGNEY2Ii8+CjxwYXRoIGQ9Ik0xMjUgNzVMMTAwIDEwMEg3NVYxMjVINzcuNUwxMDAgMTAyLjVMMTI1IDEyNy41TDE1MCAxMDIuNUwxNzIuNSAxMjVIMTc1VjEwMEgxNTBMMTI1IDc1Wk0xMjUgMTM3LjVMMTAwIDE2Mi41SDc1VjE3NUgxNzVWMTYyLjVIMTUwTDEyNSAxMzcuNVoiIGZpbGw9IiM5Q0EzQTYiLz4KPC9zdmc+Cg==';
            });
        });
    });
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\My Projects\HostelHub\resources\views/admin/hostels/show.blade.php ENDPATH**/ ?>