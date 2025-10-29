

<?php $__env->startSection('title', 'कागजात विवरण'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header bg-info text-white py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            <i class="fas fa-file-alt me-2"></i>कागजात विवरण
                        </h5>
                        <div>
                            <a href="<?php echo e(route('owner.documents.download', $document)); ?>" 
                               class="btn btn-light btn-sm">
                                <i class="fas fa-download me-1"></i>डाउनलोड
                            </a>
                            <?php if($document->uploaded_by == auth()->id()): ?>
                            <form action="<?php echo e(route('owner.documents.destroy', $document)); ?>" 
                                  method="POST" class="d-inline">
                                <?php echo csrf_field(); ?>
                                <?php echo method_field('DELETE'); ?>
                                <button type="submit" class="btn btn-danger btn-sm" 
                                        onclick="return confirm('के तपाइँ यो कागजात मेटाउन निश्चित हुनुहुन्छ?')">
                                    <i class="fas fa-trash me-1"></i>मेटाउनुहोस्
                                </button>
                            </form>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                
                <div class="card-body">
                    <div class="row">
                        <!-- Document Information -->
                        <div class="col-md-6">
                            <h6 class="border-bottom pb-2">कागजात जानकारी</h6>
                            <table class="table table-borderless">
                                <tr>
                                    <th width="40%">कागजातको नाम:</th>
                                    <td><?php echo e($document->original_name); ?></td>
                                </tr>
                                <tr>
                                    <th>प्रकार:</th>
                                    <td>
                                        <span class="badge bg-info">
                                            <?php echo e($document->getDocumentTypeNepaliAttribute()); ?>

                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <th>फाइल साइज:</th>
                                    <td><?php echo e(number_format($document->file_size / 1024, 2)); ?> KB</td>
                                </tr>
                                <tr>
                                    <th>फाइल प्रकार:</th>
                                    <td><?php echo e($document->mime_type); ?></td>
                                </tr>
                                <tr>
                                    <th>अपलोड मिति:</th>
                                    <td><?php echo e($document->created_at->format('Y-m-d H:i:s')); ?></td>
                                </tr>
                                <tr>
                                    <th>म्याद मिति:</th>
                                    <td>
                                        <?php if($document->expiry_date): ?>
                                            <?php if($document->is_expired): ?>
                                                <span class="badge bg-danger"><?php echo e($document->expiry_date->format('Y-m-d')); ?> (म्याद नाघेको)</span>
                                            <?php else: ?>
                                                <span class="badge bg-warning"><?php echo e($document->expiry_date->format('Y-m-d')); ?></span>
                                            <?php endif; ?>
                                        <?php else: ?>
                                            <span class="badge bg-secondary">म्याद नभएको</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            </table>
                        </div>
                        
                        <!-- Student Information -->
                        <div class="col-md-6">
                            <h6 class="border-bottom pb-2">विद्यार्थी जानकारी</h6>
                            <table class="table table-borderless">
                                <tr>
                                    <th width="40%">नाम:</th>
                                    <td><?php echo e($document->student->user->name ?? 'N/A'); ?></td>
                                </tr>
                                <tr>
                                    <th>होस्टल:</th>
                                    <td><?php echo e($document->hostel->name ?? 'N/A'); ?></td>
                                </tr>
                                <tr>
                                    <th>ठेगाना:</th>
                                    <td><?php echo e($document->student->address ?? 'N/A'); ?></td>
                                </tr>
                                <tr>
                                    <th>सम्पर्क:</th>
                                    <td><?php echo e($document->student->user->phone ?? 'N/A'); ?></td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    
                    <!-- Description -->
                    <?php if($document->description): ?>
                    <div class="row mt-3">
                        <div class="col-12">
                            <h6 class="border-bottom pb-2">विवरण</h6>
                            <p class="mb-0"><?php echo e($document->description); ?></p>
                        </div>
                    </div>
                    <?php endif; ?>
                    
                    <!-- File Preview -->
                    <div class="row mt-4">
                        <div class="col-12">
                            <h6 class="border-bottom pb-2">फाइल पूर्वावलोकन</h6>
                            <div class="text-center">
                                <?php if(str_starts_with($document->mime_type, 'image/')): ?>
                                    <img src="<?php echo e(Storage::disk('public')->url($document->stored_path)); ?>" 
                                         class="img-fluid rounded shadow" style="max-height: 500px;" 
                                         alt="<?php echo e($document->original_name); ?>">
                                <?php elseif($document->mime_type == 'application/pdf'): ?>
                                    <div class="p-4 border rounded bg-light">
                                        <i class="fas fa-file-pdf text-danger fa-5x mb-3"></i>
                                        <br>
                                        <span class="text-muted">PDF फाइल - डाउनलोड गरेर हेर्नुहोस्</span>
                                    </div>
                                <?php elseif(str_contains($document->mime_type, 'word') || str_contains($document->mime_type, 'document')): ?>
                                    <div class="p-4 border rounded bg-light">
                                        <i class="fas fa-file-word text-primary fa-5x mb-3"></i>
                                        <br>
                                        <span class="text-muted">Word फाइल - डाउनलोड गरेर हेर्नुहोस्</span>
                                    </div>
                                <?php else: ?>
                                    <div class="p-4 border rounded bg-light">
                                        <i class="fas fa-file text-secondary fa-5x mb-3"></i>
                                        <br>
                                        <span class="text-muted"><?php echo e($document->original_name); ?> - डाउनलोड गरेर हेर्नुहोस्</span>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="card-footer">
                    <div class="d-flex justify-content-between">
                        <small class="text-muted">
                            अपलोड गर्ने: <?php echo e($document->uploader->name ?? 'N/A'); ?>

                        </small>
                        <a href="<?php echo e(route('owner.documents.index')); ?>" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-left me-1"></i>सबै कागजातहरूमा फर्कनुहोस्
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.owner', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\My Projects\HostelHub\resources\views\owner\documents\show.blade.php ENDPATH**/ ?>