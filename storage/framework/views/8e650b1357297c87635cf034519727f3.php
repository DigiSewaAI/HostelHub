

<?php $__env->startSection('title', 'सबै कागजातहरू'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header bg-primary text-white py-3">
                    <h5 class="mb-0">
                        <i class="fas fa-file-alt me-2"></i>सबै संस्थाका कागजातहरू
                    </h5>
                </div>
                
                <div class="card-body">
                    <!-- Search and Filter Form -->
                    <form action="<?php echo e(route('admin.documents.index')); ?>" method="GET" class="mb-4">
                        <div class="row g-3">
                            <div class="col-md-3">
                                <input type="text" name="search" class="form-control" 
                                       placeholder="विद्यार्थी वा कागजातको नाम..." 
                                       value="<?php echo e(request('search')); ?>">
                            </div>
                            <div class="col-md-2">
                                <select name="document_type" class="form-select">
                                    <option value="">सबै प्रकार</option>
                                    <?php $__currentLoopData = $documentTypes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $type): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($key); ?>" <?php echo e(request('document_type') == $key ? 'selected' : ''); ?>>
                                            <?php echo e($type); ?>

                                        </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <input type="date" name="start_date" class="form-control" 
                                       value="<?php echo e(request('start_date')); ?>" placeholder="सुरु मिति">
                            </div>
                            <div class="col-md-2">
                                <input type="date" name="end_date" class="form-control" 
                                       value="<?php echo e(request('end_date')); ?>" placeholder="अन्त्य मिति">
                            </div>
                            <div class="col-md-1">
                                <button type="submit" class="btn btn-primary w-100">
                                    <i class="fas fa-filter"></i>
                                </button>
                            </div>
                        </div>
                    </form>

                    <?php if($documents->count() > 0): ?>
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>#</th>
                                        <th>संस्था</th>
                                        <th>विद्यार्थी</th>
                                        <th>कागजातको प्रकार</th>
                                        <th>कागजातको नाम</th>
                                        <th>फाइल साइज</th>
                                        <th>अपलोड मिति</th>
                                        <th>म्याद</th>
                                        <th>कार्यहरू</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $__currentLoopData = $documents; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $document): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        <td><?php echo e($loop->iteration); ?></td>
                                        <td>
                                            <span class="badge bg-secondary">
                                                <?php echo e($document->organization->name ?? 'N/A'); ?>

                                            </span>
                                        </td>
                                        <td><?php echo e($document->student->user->name ?? 'N/A'); ?></td>
                                        <td>
                                            <span class="badge bg-info">
                                                <?php echo e($document->getDocumentTypeNepaliAttribute()); ?>

                                            </span>
                                        </td>
                                        <td><?php echo e($document->original_name); ?></td>
                                        <td><?php echo e(number_format($document->file_size / 1024, 2)); ?> KB</td>
                                        <td><?php echo e($document->created_at->format('Y-m-d')); ?></td>
                                        <td>
                                            <?php if($document->expiry_date): ?>
                                                <?php if($document->is_expired): ?>
                                                    <span class="badge bg-danger">म्याद नाघेको</span>
                                                <?php else: ?>
                                                    <span class="badge bg-warning"><?php echo e($document->expiry_date->format('Y-m-d')); ?></span>
                                                <?php endif; ?>
                                            <?php else: ?>
                                                <span class="badge bg-secondary">म्याद नभएको</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <div class="btn-group btn-group-sm">
                                                <a href="<?php echo e(route('admin.documents.show', $document)); ?>" 
                                                   class="btn btn-info" title="हेर्नुहोस्">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="<?php echo e(route('admin.documents.download', $document)); ?>" 
                                                   class="btn btn-success" title="डाउनलोड गर्नुहोस्">
                                                    <i class="fas fa-download"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                            </table>
                        </div>
                        
                        <!-- Pagination -->
                        <div class="d-flex justify-content-center mt-4">
                            <?php echo e($documents->links()); ?>

                        </div>
                    <?php else: ?>
                        <div class="text-center py-5">
                            <i class="fas fa-folder-open fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">कुनै कागजात भेटिएन</h5>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\My Projects\HostelHub\resources\views/admin/documents/index.blade.php ENDPATH**/ ?>