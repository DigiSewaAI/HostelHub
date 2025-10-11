

<?php $__env->startSection('title', 'कोठा व्यवस्थापन'); ?>

<?php $__env->startSection('content'); ?>
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3">कोठा व्यवस्थापन</h1>
            
            <a href="<?php echo e(route('owner.rooms.create')); ?>" class="btn btn-primary">
                <i class="fas fa-plus-circle me-2"></i>नयाँ कोठा थप्नुहोस्
            </a>
        </div>

        <?php if(session('success')): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?php echo e(session('success')); ?>

                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <?php if(session('error')): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <?php echo e(session('error')); ?>

                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-primary">कोठाहरूको सूची</h6>
                <form action="<?php echo e(route('owner.rooms.search')); ?>" method="GET" class="d-flex">
                    <input type="text" name="search" class="form-control form-control-sm me-2"
                           placeholder="खोज्नुहोस्..." value="<?php echo e(request('search')); ?>">
                    <button class="btn btn-sm btn-primary" type="submit">
                        <i class="fas fa-search"></i>
                    </button>
                </form>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>क्रम संख्या</th>
                                <th>होस्टल</th>
                                <th>कोठा नम्बर</th>
                                <th>प्रकार</th>
                                <th>क्षमता</th>
                                <th>मूल्य</th>
                                <th>स्थिति</th>
                                <th class="text-center">कार्यहरू</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__empty_1 = true; $__currentLoopData = $rooms; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $room): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <tr>
                                    <td><?php echo e($loop->iteration); ?></td>
                                    <td><?php echo e($room->hostel ? $room->hostel->name : 'N/A'); ?></td>
                                    <td><?php echo e($room->room_number); ?></td>
                                    <td>
                                        <?php if($room->type == 'single'): ?>
                                            एकल कोठा
                                        <?php elseif($room->type == 'double'): ?>
                                            दुई ब्यक्ति कोठा
                                        <?php elseif($room->type == 'shared'): ?>
                                            साझा कोठा
                                        <?php else: ?>
                                            <?php echo e($room->type); ?>

                                        <?php endif; ?>
                                    </td>
                                    <td><?php echo e($room->capacity); ?></td>
                                    <td>रु. <?php echo e(number_format($room->price)); ?></td>
                                    <td>
                                        <?php if($room->status == 'available' || $room->status == 'उपलब्ध'): ?>
                                            <span class="badge bg-success">उपलब्ध</span>
                                        <?php elseif($room->status == 'occupied' || $room->status == 'बुक भएको'): ?>
                                            <span class="badge bg-danger">व्यस्त</span>
                                        <?php else: ?>
                                            <span class="badge bg-warning">मर्मत सम्भार</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-center">
                                        <a href="<?php echo e(route('owner.rooms.show', $room)); ?>" class="btn btn-sm btn-info me-1" title="हेर्नुहोस्">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="<?php echo e(route('owner.rooms.edit', $room)); ?>" class="btn btn-sm btn-primary me-1" title="सम्पादन गर्नुहोस्">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="<?php echo e(route('owner.rooms.destroy', $room)); ?>" method="POST" class="d-inline" onsubmit="return confirm('के तपाईं यो कोठा हटाउन चाहनुहुन्छ?')">
                                            <?php echo csrf_field(); ?>
                                            <?php echo method_field('DELETE'); ?>
                                            <button type="submit" class="btn btn-sm btn-danger" title="हटाउनुहोस्">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <tr>
                                    <td colspan="8" class="text-center">कुनै कोठा फेला परेन</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

                
                <?php if($rooms instanceof \Illuminate\Pagination\LengthAwarePaginator): ?>
                <div class="d-flex justify-content-center mt-3">
                    <?php echo e($rooms->links()); ?>

                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.owner', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\My Projects\HostelHub\resources\views/owner/rooms/index.blade.php ENDPATH**/ ?>