<?php $__env->startSection('title', 'सम्पर्क व्यवस्थापन'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <h2 class="fw-bold text-primary">
                    <i class="fas fa-envelope me-2"></i> 
                    <?php if (\Illuminate\Support\Facades\Blade::check('role', 'admin')): ?>
                    सम्पर्क सन्देशहरू
                    <?php else: ?>
                    मेरा सम्पर्क सन्देशहरू
                    <?php endif; ?>
                </h2>
                <?php if (\Illuminate\Support\Facades\Blade::check('role', 'admin')): ?>
                <a href="<?php echo e(route('admin.contacts.create')); ?>" class="btn btn-success btn-lg shadow-sm">
                    <i class="fas fa-plus-circle me-1"></i> नयाँ सम्पर्क थप्नुहोस्
                </a>
                <?php endif; ?>
            </div>
            <p class="text-muted">
                <?php if (\Illuminate\Support\Facades\Blade::check('role', 'admin')): ?>
                यहाँ तपाईंले आएका सम्पर्क सन्देशहरू व्यवस्थापन गर्न सक्नुहुन्छ।
                <?php else: ?>
                यहाँ तपाईंले आफ्नो होस्टलका लागि आएका सम्पर्क सन्देशहरू हेर्न सक्नुहुन्छ।
                <?php endif; ?>
            </p>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3">
                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                        <h5 class="mb-0 text-secondary">
                            <i class="fas fa-list me-2"></i> सम्पर्क सूची
                        </h5>

                        <!-- Search Form -->
                        <form action="<?php echo e(route('admin.contacts.search')); ?>" method="GET" class="input-group" style="max-width: 300px;">
                            <input type="text" name="search" class="form-control" placeholder="नाम, इमेल वा विषय खोज्नुहोस्..." value="<?php echo e(request('search')); ?>">
                            <button class="btn btn-outline-primary" type="submit">
                                <i class="fas fa-search"></i>
                            </button>
                        </form>
                    </div>
                </div>

                <div class="card-body p-0">
                    <?php if(session('success')): ?>
                        <div class="alert alert-success alert-dismissible fade show m-3" role="alert">
                            <i class="fas fa-check-circle me-2"></i> <?php echo e(session('success')); ?>

                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    <?php endif; ?>

                    <div class="table-responsive">
                        <table class="table table-hover table-striped align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="text-center" style="width: 5%;">क्र.स.</th>
                                    <th><i class="fas fa-user me-1"></i> नाम</th>
                                    <th><i class="fas fa-envelope me-1"></i> इमेल</th>
                                    <th><i class="fas fa-phone me-1"></i> फोन</th>
                                    <th><i class="fas fa-tag me-1"></i> विषय</th>
                                    <th><i class="fas fa-info-circle me-1"></i> स्थिति</th>
                                    <th class="text-center" style="width: 15%;"><i class="fas fa-cogs me-1"></i> क्रियाहरू</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__empty_1 = true; $__currentLoopData = $contacts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $contact): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <tr>
                                    <td class="text-center fw-bold"><?php echo e($loop->iteration); ?></td>
                                    <td><?php echo e($contact->name); ?></td>
                                    <td><a href="mailto:<?php echo e($contact->email); ?>" class="text-decoration-none"><?php echo e($contact->email); ?></a></td>
                                    <td><?php echo e($contact->phone ?? '—'); ?></td>
                                    <td><?php echo e(Str::limit($contact->subject, 30)); ?></td>
                                    <td>
                                        <?php if($contact->status == 'नयाँ' || $contact->status == 'pending'): ?>
                                            <span class="badge bg-warning text-dark">
                                                <?php echo e($contact->status == 'नयाँ' ? 'नयाँ' : 'प्रतीक्षामा'); ?>

                                            </span>
                                        <?php elseif($contact->status == 'पढियो' || $contact->status == 'read'): ?>
                                            <span class="badge bg-info">
                                                <?php echo e($contact->status == 'पढियो' ? 'पढियो' : 'पढिएको'); ?>

                                            </span>
                                        <?php elseif($contact->status == 'जवाफ दिइयो' || $contact->status == 'replied'): ?>
                                            <span class="badge bg-success">जवाफ दिइयो</span>
                                        <?php else: ?>
                                            <span class="badge bg-secondary"><?php echo e($contact->status); ?></span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-center">
                                        <a href="<?php echo e(route('admin.contacts.show', $contact->id)); ?>" class="btn btn-info btn-sm me-1" title="सन्देश हेर्नुहोस्">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="<?php echo e(route('admin.contacts.edit', $contact->id)); ?>" class="btn btn-primary btn-sm me-1" title="सम्पादन गर्नुहोस्">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="<?php echo e(route('admin.contacts.destroy', $contact->id)); ?>" method="POST" class="d-inline">
                                            <?php echo csrf_field(); ?>
                                            <?php echo method_field('DELETE'); ?>
                                            <button type="submit" class="btn btn-danger btn-sm" title="मेटाउनुहोस्"
                                                onclick="return confirm('के तपाईं यो सम्पर्क सन्देश स्थायी रूपमा मेटाउन चाहनुहुन्छ?')">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <tr>
                                    <td colspan="7" class="text-center py-5">
                                        <i class="fas fa-inbox fa-3x text-muted d-block mb-3"></i>
                                        <h5 class="text-muted">कुनै सम्पर्क सन्देश फेला परेन</h5>
                                        <p class="text-muted">तपाईंले नयाँ सम्पर्क थप्न सक्नुहुन्छ वा खोजी गर्न सक्नुहुन्छ।</p>
                                    </td>
                                </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <?php if($contacts->hasPages()): ?>
                        <div class="card-footer bg-white d-flex justify-content-center">
                            <?php echo e($contacts->links()); ?>

                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\HostelHub\resources\views\admin\contacts\index.blade.php ENDPATH**/ ?>