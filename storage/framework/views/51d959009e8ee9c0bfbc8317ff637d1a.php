

<?php $__env->startSection('title', 'कागजात खोज'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header bg-warning text-dark py-3">
                    <h5 class="mb-0">
                        <i class="fas fa-search me-2"></i>कागजात खोज
                    </h5>
                </div>
                
                <div class="card-body">
                    <!-- Search Form -->
                    <form action="<?php echo e(route('owner.documents.search')); ?>" method="GET" class="mb-4">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label for="student_name" class="form-label">विद्यार्थीको नाम</label>
                                <input type="text" name="student_name" id="student_name" 
                                       class="form-control" value="<?php echo e(request('student_name')); ?>"
                                       placeholder="विद्यार्थीको नाम लेख्नुहोस्...">
                            </div>
                            
                            <div class="col-md-3">
                                <label for="document_type" class="form-label">कागजातको प्रकार</label>
                                <select name="document_type" id="document_type" class="form-select">
                                    <option value="">सबै प्रकार</option>
                                    <?php $__currentLoopData = $documentTypes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $type): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($key); ?>" <?php echo e(request('document_type') == $key ? 'selected' : ''); ?>>
                                            <?php echo e($type); ?>

                                        </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                            
                            <div class="col-md-2">
                                <label for="start_date" class="form-label">सुरु मिति</label>
                                <input type="date" name="start_date" id="start_date" 
                                       class="form-control" value="<?php echo e(request('start_date')); ?>">
                            </div>
                            
                            <div class="col-md-2">
                                <label for="end_date" class="form-label">अन्त्य मिति</label>
                                <input type="date" name="end_date" id="end_date" 
                                       class="form-control" value="<?php echo e(request('end_date')); ?>">
                            </div>
                            
                            <div class="col-md-1 d-flex align-items-end">
                                <button type="submit" class="btn btn-primary w-100">
                                    <i class="fas fa-search"></i> खोज
                                </button>
                            </div>
                        </div>
                    </form>

                    <?php if(isset($results)): ?>
                        <?php if($results->count() > 0): ?>
                            <div class="alert alert-success">
                                <i class="fas fa-check-circle me-2"></i>
                                <?php echo e($results->count()); ?> वटा कागजातहरू भेटिए
                            </div>
                            
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover">
                                    <thead class="table-light">
                                        <tr>
                                            <th>#</th>
                                            <th>विद्यार्थी</th>
                                            <th>कागजातको प्रकार</th>
                                            <th>कागजातको नाम</th>
                                            <th>अपलोड मिति</th>
                                            <th>कार्यहरू</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $__currentLoopData = $results; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $document): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr>
                                            <td><?php echo e($loop->iteration); ?></td>
                                            <td><?php echo e($document->student->user->name ?? 'N/A'); ?></td>
                                            <td>
                                                <span class="badge bg-info">
                                                    <?php echo e($document->getDocumentTypeNepaliAttribute()); ?>

                                                </span>
                                            </td>
                                            <td><?php echo e($document->original_name); ?></td>
                                            <td><?php echo e($document->created_at->format('Y-m-d')); ?></td>
                                            <td>
                                                <div class="btn-group btn-group-sm">
                                                    <a href="<?php echo e(route('owner.documents.show', $document)); ?>" 
                                                       class="btn btn-info" title="हेर्नुहोस्">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <a href="<?php echo e(route('owner.documents.download', $document)); ?>" 
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
                        <?php else: ?>
                            <div class="alert alert-warning text-center py-4">
                                <i class="fas fa-search fa-2x mb-3"></i>
                                <h5>कुनै कागजात भेटिएन</h5>
                                <p class="mb-0">कृपया आफ्नो खोज क्वेरी परिवर्तन गरेर पुनः प्रयास गर्नुहोस्</p>
                            </div>
                        <?php endif; ?>
                    <?php else: ?>
                        <div class="text-center py-5 text-muted">
                            <i class="fas fa-search fa-3x mb-3"></i>
                            <h5>खोज फलक</h5>
                            <p>माथिको फर्म प्रयोग गरेर कागजातहरू खोज्नुहोस्</p>
                        </div>
                    <?php endif; ?>
                </div>
                
                <div class="card-footer">
                    <a href="<?php echo e(route('owner.documents.index')); ?>" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-1"></i>कागजात सूचीमा फर्कनुहोस्
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.owner', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\HostelHub\resources\views\owner\documents\search.blade.php ENDPATH**/ ?>