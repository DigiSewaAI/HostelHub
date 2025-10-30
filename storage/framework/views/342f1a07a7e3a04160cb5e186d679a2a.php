<?php $__env->startSection('title', 'समीक्षाहरू'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">समीक्षाहरू</h1>
        <a href="<?php echo e(route('admin.reviews.create')); ?>" class="btn btn-primary">
            <i class="fas fa-plus me-1"></i> नयाँ समीक्षा
        </a>
    </div>

    <?php if(session('success')): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i> <?php echo e(session('success')); ?>

            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <?php if(session('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i> <?php echo e(session('error')); ?>

            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <!-- Filters Card -->
    <div class="card mb-4">
        <div class="card-body">
            <form action="<?php echo e(route('admin.reviews.index')); ?>" method="GET">
                <div class="row">
                    <div class="col-md-3 mb-2">
                        <label for="type" class="form-label">प्रकार</label>
                        <select name="type" id="type" class="form-select">
                            <option value="">सबै प्रकार</option>
                            <option value="testimonial" <?php echo e(request('type') == 'testimonial' ? 'selected' : ''); ?>>प्रशंसापत्र</option>
                            <option value="review" <?php echo e(request('type') == 'review' ? 'selected' : ''); ?>>समीक्षा</option>
                            <option value="feedback" <?php echo e(request('type') == 'feedback' ? 'selected' : ''); ?>>प्रतिक्रिया</option>
                        </select>
                    </div>
                    <div class="col-md-3 mb-2">
                        <label for="status" class="form-label">स्थिति</label>
                        <select name="status" id="status" class="form-select">
                            <option value="">सबै स्थिति</option>
                            <option value="active" <?php echo e(request('status') == 'active' ? 'selected' : ''); ?>>सक्रिय</option>
                            <option value="inactive" <?php echo e(request('status') == 'inactive' ? 'selected' : ''); ?>>निष्क्रिय</option>
                        </select>
                    </div>
                    <div class="col-md-3 mb-2">
                        <label for="search" class="form-label">खोज्नुहोस्</label>
                        <input type="text" name="search" id="search" class="form-control" 
                               placeholder="नाम वा पद" value="<?php echo e(request('search')); ?>">
                    </div>
                    <div class="col-md-3 mb-2 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary me-2">
                            <i class="fas fa-filter me-1"></i> फिल्टर
                        </button>
                        <a href="<?php echo e(route('admin.reviews.index')); ?>" class="btn btn-secondary">
                            <i class="fas fa-sync me-1"></i> रिसेट
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <?php if($reviews->count() > 0): ?>
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th width="50">क्र.स.</th>
                            <th width="80">छवि</th>
                            <th>नाम</th>
                            <th>पद</th>
                            <th>प्रकार</th>
                            <th>मूल्याङ्कन</th>
                            <th>स्थिति</th>
                            <th width="150">कार्यहरू</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = $reviews; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $review): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td><?php echo e($loop->iteration + ($reviews->currentPage() - 1) * $reviews->perPage()); ?></td>
                            <td>
                                <?php if($review->image): ?>
                                    <img src="<?php echo e(Storage::disk('public')->exists($review->image) ? Storage::url($review->image) : asset('images/default-avatar.png')); ?>" 
                                         alt="<?php echo e($review->name); ?>" class="rounded-circle" width="40" height="40" style="object-fit: cover;">
                                <?php elseif($review->initials): ?>
                                    <div class="rounded-circle d-flex align-items-center justify-content-center bg-primary text-white" 
                                         style="width: 40px; height: 40px; font-size: 14px; font-weight: bold;">
                                        <?php echo e($review->initials); ?>

                                    </div>
                                <?php else: ?>
                                    <div class="rounded-circle d-flex align-items-center justify-content-center bg-secondary text-white" 
                                         style="width: 40px; height: 40px;">
                                        <i class="fas fa-user" style="font-size: 14px;"></i>
                                    </div>
                                <?php endif; ?>
                            </td>
                            <td><?php echo e($review->name); ?></td>
                            <td><?php echo e($review->position); ?></td>
                            <td>
                                <?php if($review->type == 'testimonial'): ?>
                                    <span class="badge bg-success">प्रशंसापत्र</span>
                                <?php elseif($review->type == 'review'): ?>
                                    <span class="badge bg-primary">समीक्षा</span>
                                <?php else: ?>
                                    <span class="badge bg-info">प्रतिक्रिया</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if($review->rating): ?>
                                    <div class="d-flex align-items-center">
                                        <?php for($i = 1; $i <= 5; $i++): ?>
                                            <span class="<?php echo e($i <= $review->rating ? 'text-warning' : 'text-secondary'); ?>">
                                                <i class="fas fa-star"></i>
                                            </span>
                                        <?php endfor; ?>
                                        <span class="ms-1">(<?php echo e($review->rating); ?>)</span>
                                    </div>
                                <?php else: ?>
                                    <span class="text-muted">नभएको</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if($review->status == 'active'): ?>
                                    <span class="badge bg-success">सक्रिय</span>
                                <?php else: ?>
                                    <span class="badge bg-secondary">निष्क्रिय</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="<?php echo e(route('admin.reviews.show', $review)); ?>" class="btn btn-sm btn-info" 
                                       data-bs-toggle="tooltip" title="हेर्नुहोस्">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="<?php echo e(route('admin.reviews.edit', $review)); ?>" class="btn btn-sm btn-primary" 
                                       data-bs-toggle="tooltip" title="सम्पादन गर्नुहोस्">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="<?php echo e(route('admin.reviews.destroy', $review)); ?>" method="POST" class="d-inline">
                                        <?php echo csrf_field(); ?>
                                        <?php echo method_field('DELETE'); ?>
                                        <button type="submit" class="btn btn-sm btn-danger" 
                                                onclick="return confirm('के तपाईं यो समीक्षा हटाउन चाहनुहुन्छ?')"
                                                data-bs-toggle="tooltip" title="हटाउनुहोस्">
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

            <div class="d-flex justify-content-between align-items-center mt-3">
                <div class="text-muted">
                    कुल <?php echo e($reviews->total()); ?> समीक्षाहरू मध्ये <?php echo e($reviews->firstItem()); ?> देखि <?php echo e($reviews->lastItem()); ?> सम्म देखाइएको छ
                </div>
                <div>
                    <?php echo e($reviews->links()); ?>

                </div>
            </div>
            <?php else: ?>
            <div class="text-center py-5">
                <div class="py-5">
                    <i class="fas fa-comments fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted">कुनै समीक्षा फेला परेन</h5>
                    <p class="text-muted">नयाँ समीक्षा सिर्जना गर्न तलको बटनमा क्लिक गर्नुहोस्</p>
                    <a href="<?php echo e(route('admin.reviews.create')); ?>" class="btn btn-primary mt-3">
                        <i class="fas fa-plus me-1"></i> नयाँ समीक्षा
                    </a>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
    // Initialize tooltips
    document.addEventListener('DOMContentLoaded', function() {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        })
    })
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\HostelHub\resources\views\admin\reviews\index.blade.php ENDPATH**/ ?>