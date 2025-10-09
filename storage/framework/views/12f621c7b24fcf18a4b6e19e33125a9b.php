<?php $__env->startSection('title', 'होस्टल व्यवस्थापन'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex align-items-center justify-content-between">
                <h2 class="mb-0">होस्टल व्यवस्थापन</h2>
                <a href="<?php echo e(route('admin.hostels.create')); ?>" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i>नयाँ होस्टल
                </a>
            </div>
        </div>
    </div>

    <?php if(session('success')): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?php echo e(session('success')); ?>

            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <div class="card shadow">
        <div class="card-header bg-white py-3">
            <h6 class="m-0 font-weight-bold text-primary">सबै होस्टलहरू</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover" id="dataTable" width="100%" cellspacing="0">
                    <thead class="table-light">
                        <tr>
                            <th>क्रम संख्या</th>
                            <th>होस्टेलको नाम</th>
                            <th>ठेगाना</th>
                            <th>शहर</th>
                            <th>सम्पर्क</th>
                            <th>कोठाहरू</th>
                            <th>प्रबन्धक</th>
                            <th>स्थिति</th>
                            <th>कार्यहरू</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = $hostels; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $hostel): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td><?php echo e($hostel->id); ?></td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <?php if($hostel->image): ?>
                                        <img src="<?php echo e(asset('storage/'.$hostel->image)); ?>"
                                             class="rounded me-2"
                                             width="40"
                                             height="40"
                                             style="object-fit: cover;">
                                    <?php else: ?>
                                        <div class="bg-light rounded me-2 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                            <i class="fas fa-building text-muted"></i>
                                        </div>
                                    <?php endif; ?>
                                    <span><?php echo e($hostel->name); ?></span>
                                </div>
                            </td>
                            <td><?php echo e(Str::limit($hostel->address, 20)); ?></td>
                            <td><?php echo e($hostel->city); ?></td>
                            <td><?php echo e($hostel->contact_phone); ?></td>
                            <td>
    <?php
        $totalRooms = $hostel->rooms_count ?? $hostel->rooms->count();
        $availableRooms = $hostel->rooms->where('status', 'available')->count();
    ?>
    <?php echo e($totalRooms); ?> / <?php echo e($availableRooms); ?> उपलब्ध
</td>

                            <td>
                                <?php if($hostel->manager): ?>
                                    <?php echo e($hostel->manager->name); ?>

                                <?php else: ?>
                                    <span class="text-muted">तोकिएको छैन</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <span class="badge <?php echo e($hostel->status === 'active' ? 'bg-success' : ($hostel->status === 'inactive' ? 'bg-secondary' : 'bg-warning')); ?>">
                                    <?php if($hostel->status === 'active'): ?>
                                        सक्रिय
                                    <?php elseif($hostel->status === 'inactive'): ?>
                                        निष्क्रिय
                                    <?php else: ?>
                                        <?php echo e(ucfirst(str_replace('_', ' ', $hostel->status))); ?>

                                    <?php endif; ?>
                                </span>
                            </td>
                            <td>
                                <div class="d-flex gap-2">
                                    <a href="<?php echo e(route('admin.hostels.show', $hostel)); ?>"
                                       class="btn btn-sm btn-info"
                                       title="विवरण हेर्नुहोस्">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="<?php echo e(route('admin.hostels.edit', $hostel)); ?>"
                                       class="btn btn-sm btn-warning"
                                       title="सम्पादन गर्नुहोस्">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="<?php echo e(route('admin.hostels.destroy', $hostel)); ?>"
                                          method="POST"
                                          class="delete-form"
                                          style="display: inline;">
                                        <?php echo csrf_field(); ?>
                                        <?php echo method_field('DELETE'); ?>
                                        <button type="submit"
                                                class="btn btn-sm btn-danger"
                                                title="हटाउनुहोस्"
                                                onclick="return confirm('के तपाइँ यो होस्टल मेटाउन निश्चित हुनुहुन्छ?')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>
            <div class="d-flex justify-content-end mt-3">
                <?php echo e($hostels->links()); ?>

            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Delete confirmation
        const deleteForms = document.querySelectorAll('.delete-form');
        deleteForms.forEach(form => {
            form.addEventListener('submit', function(e) {
                if (!confirm('के तपाइँ यो होस्टल मेटाउन निश्चित हुनुहुन्छ? सबै कोठाहरू र सम्बन्धित डाटा हटाइनेछ।')) {
                    e.preventDefault();
                }
            });
        });
    });
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\My Projects\HostelHub\resources\views/admin/hostels/index.blade.php ENDPATH**/ ?>