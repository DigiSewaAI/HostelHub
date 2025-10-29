

<?php $__env->startSection('title', 'सदस्यता विवरण'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-12">
            <h2 class="mb-0">सदस्यता व्यवस्थापन</h2>
        </div>
    </div>

    <?php if(session('success')): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?php echo e(session('success')); ?>

            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="बन्द गर्नुहोस्"></button>
        </div>
    <?php endif; ?>

    <?php if(session('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?php echo e(session('error')); ?>

            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="बन्द गर्नुहोस्"></button>
        </div>
    <?php endif; ?>

    <div class="row">
        <div class="col-md-12">
            <div class="card shadow">
                <div class="card-header bg-white py-3">
                    <h6 class="m-0 font-weight-bold text-primary">वर्तमान सदस्यता</h6>
                </div>
                <div class="card-body">
                    <?php if($currentPlan): ?>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label fw-bold">योजनाको नाम</label>
                                    <p><?php echo e($currentPlan->name); ?></p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label fw-bold">मूल्य</label>
                                    <p><?php echo e($currentPlan->getDisplayPrice()); ?></p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label fw-bold">स्थिति</label>
                                    <p>
                                        <span class="badge <?php echo e($organization->subscription->status === 'active' ? 'bg-success' : ($organization->subscription->status === 'trial' ? 'bg-info' : 'bg-warning')); ?>">
                                            <?php if($organization->subscription->status === 'active'): ?>
                                                सक्रिय
                                            <?php elseif($organization->subscription->status === 'trial'): ?>
                                                परीक्षण
                                            <?php else: ?>
                                                अन्य
                                            <?php endif; ?>
                                        </span>
                                    </p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label fw-bold">परीक्षण अन्त्य मिति</label>
                                    <p><?php echo e($organization->subscription->trial_ends_at ? $organization->subscription->trial_ends_at->format('Y-m-d') : 'लागू छैन'); ?></p>
                                </div>
                            </div>
                        </div>

                        <!-- योजना सीमाहरू -->
                        <div class="row mt-3">
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label fw-bold">होस्टेल सीमा</label>
                                    <p><?php echo e($currentPlan->max_hostels == 0 ? 'असीमित' : $currentPlan->max_hostels); ?></p>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label fw-bold">विद्यार्थी सीमा</label>
                                    <p><?php echo e($currentPlan->max_students == 0 ? 'असीमित' : $currentPlan->max_students); ?></p>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label fw-bold">कोठा सीमा</label>
                                    <p><?php echo e($currentPlan->max_rooms == 0 ? 'असीमित' : $currentPlan->max_rooms); ?></p>
                                </div>
                            </div>
                        </div>
                    <?php else: ?>
                        <div class="alert alert-info">
                            कुनै सक्रिय सदस्यता फेला परेन। 
                            <a href="<?php echo e(route('pricing')); ?>" class="alert-link">सुरु गर्न योजना छान्नुहोस्</a>।
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- होस्टेल सीमाहरू सेक्सन -->
    <?php if($currentPlan): ?>
    <div class="row mt-4">
        <div class="col-md-12">
            <div class="card shadow">
                <div class="card-header bg-white py-3">
                    <h6 class="m-0 font-weight-bold text-primary">होस्टेल सीमाहरू</h6>
                </div>
                <div class="card-body">
                    <?php
                        $organizationId = session('current_organization_id');
                        $subscription = $organization->subscription;
                        $hostelsCount = \App\Models\Hostel::where('organization_id', $organizationId)->count();
                        $remainingSlots = $subscription ? $subscription->getRemainingHostelSlots() : 0;
                        $totalAllowed = $currentPlan->getMaxHostelsWithAddons($subscription->extra_hostels ?? 0);
                        $usagePercentage = $totalAllowed > 0 ? round(($hostelsCount / $totalAllowed) * 100) : 0;
                    ?>
                    
                    <div class="row">
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label class="form-label fw-bold">वर्तमान योजना</label>
                                <p class="mb-0"><?php echo e($currentPlan->name); ?></p>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label class="form-label fw-bold">अनुमत होस्टेलहरू</label>
                                <p class="mb-0"><?php echo e($totalAllowed); ?></p>
                                <small class="text-muted">
                                    (मूल: <?php echo e($currentPlan->max_hostels); ?> + अतिरिक्त: <?php echo e($subscription->extra_hostels ?? 0); ?>)
                                </small>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label class="form-label fw-bold">प्रयोग भएका होस्टेलहरू</label>
                                <p class="mb-0"><?php echo e($hostelsCount); ?></p>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label class="form-label fw-bold">बाँकी स्लटहरू</label>
                                <p class="mb-0">
                                    <span class="badge <?php echo e($remainingSlots > 0 ? 'bg-success' : 'bg-danger'); ?>">
                                        <?php echo e($remainingSlots); ?>

                                    </span>
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- प्रगति बार -->
                    <div class="mb-4">
                        <label class="form-label fw-bold">प्रयोग भएको सीमा</label>
                        <div class="progress" style="height: 20px;">
                            <div class="progress-bar <?php echo e($usagePercentage >= 90 ? 'bg-danger' : ($usagePercentage >= 70 ? 'bg-warning' : 'bg-success')); ?>" 
                                 role="progressbar" 
                                 style="width: <?php echo e($usagePercentage); ?>%;"
                                 aria-valuenow="<?php echo e($usagePercentage); ?>" 
                                 aria-valuemin="0" 
                                 aria-valuemax="100">
                                <?php echo e($usagePercentage); ?>%
                            </div>
                        </div>
                        <div class="d-flex justify-content-between mt-1">
                            <small>०</small>
                            <small><?php echo e($totalAllowed); ?></small>
                        </div>
                    </div>

                    <?php if($remainingSlots == 0): ?>
                    <div class="alert alert-warning mt-3">
                        <strong>सीमा पुगिसक्यो!</strong> अतिरिक्त होस्टेल थप्न कृपया अतिरिक्त स्लट खरिद गर्नुहोस् वा योजना अपग्रेड गर्नुहोस्।
                        <a href="<?php echo e(route('subscription.limits')); ?>" class="btn btn-sm btn-warning ms-2">
                            <i class="fas fa-shopping-cart"></i> अतिरिक्त होस्टेल खरिद गर्नुहोस्
                        </a>
                    </div>
                    <?php endif; ?>

                    <?php if($currentPlan->slug === 'enterprise'): ?>
                    <div class="mt-3">
                        <small class="text-muted">
                            <i class="fas fa-info-circle"></i>
                            <strong>अतिरिक्त होस्टल थप्न सकिन्छ:</strong> रु. १,०००/महिना प्रति अतिरिक्त होस्टल
                        </small>
                    </div>
                    <?php endif; ?>

                    <div class="mt-3">
                        <a href="<?php echo e(route('subscription.limits')); ?>" class="btn btn-outline-primary btn-sm">
                            <i class="fas fa-chart-bar"></i> विस्तृत सीमा हेर्नुहोस्
                        </a>
                        <a href="<?php echo e(route('owner.hostels.index')); ?>" class="btn btn-outline-info btn-sm ms-2">
                            <i class="fas fa-building"></i> होस्टेलहरू हेर्नुहोस्
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <?php if($availablePlans->count() > 0): ?>
    <div class="row mt-4">
        <div class="col-md-12">
            <div class="card shadow">
                <div class="card-header bg-white py-3">
                    <h6 class="m-0 font-weight-bold text-primary">उपलब्ध योजनाहरू</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <?php $__currentLoopData = $availablePlans; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $plan): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="col-md-4 mb-3">
                            <div class="card h-100 border-<?php echo e($plan->slug === 'pro' ? 'warning' : ($plan->slug === 'enterprise' ? 'primary' : 'secondary')); ?>">
                                <?php if($plan->slug === 'pro'): ?>
                                    <div class="card-header text-center bg-warning text-dark">
                                        <span class="badge bg-dark">लोकप्रिय</span>
                                <?php elseif($plan->slug === 'enterprise'): ?>
                                    <div class="card-header text-center bg-primary text-white">
                                        <span class="badge bg-success">उत्कृष्ट</span>
                                <?php else: ?>
                                    <div class="card-header text-center bg-secondary text-white">
                                <?php endif; ?>
                                    <h5 class="card-title mb-1"><?php echo e($plan->name); ?></h5>
                                    <h4 class="mb-1"><?php echo e($plan->getDisplayPrice()); ?></h4>
                                    <small class="opacity-75">प्रति महिना</small>
                                </div>
                                <div class="card-body">
                                    <p class="card-text"><?php echo e($plan->description ?? 'व्यावसायिक होस्टल व्यवस्थापन समाधान'); ?></p>
                                    
                                    <div class="mb-3">
                                        <small class="text-muted">
                                            <i class="fas fa-building me-1"></i>
                                            होस्टेल: <?php echo e($plan->max_hostels == 0 ? 'असीमित' : $plan->max_hostels); ?>

                                        </small><br>
                                        <small class="text-muted">
                                            <i class="fas fa-users me-1"></i>
                                            विद्यार्थी: <?php echo e($plan->max_students == 0 ? 'असीमित' : $plan->max_students); ?>

                                        </small><br>
                                        <small class="text-muted">
                                            <i class="fas fa-bed me-1"></i>
                                            कोठा: <?php echo e($plan->max_rooms == 0 ? 'असीमित' : $plan->max_rooms); ?>

                                        </small>
                                    </div>

                                    <form action="<?php echo e(route('subscription.upgrade')); ?>" method="POST" class="plan-form">
                                        <?php echo csrf_field(); ?>
                                        <input type="hidden" name="plan" value="<?php echo e($plan->slug); ?>">
                                        <button type="submit" class="btn <?php echo e($plan->slug === 'pro' ? 'btn-warning' : ($plan->slug === 'enterprise' ? 'btn-primary' : 'btn-secondary')); ?> w-100">
                                            <?php if($currentPlan && $currentPlan->price_month < $plan->price_month): ?>
                                                <i class="fas fa-arrow-up me-1"></i> अपग्रेड गर्नुहोस्
                                            <?php elseif($currentPlan && $currentPlan->price_month > $plan->price_month): ?>
                                                <i class="fas fa-arrow-down me-1"></i> डाउनग्रेड गर्नुहोस्
                                            <?php else: ?>
                                                <i class="fas fa-sync-alt me-1"></i> परिवर्तन गर्नुहोस्
                                            <?php endif; ?>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <!-- योजना विशेषताहरू -->
    <?php if($currentPlan): ?>
    <div class="row mt-4">
        <div class="col-md-12">
            <div class="card shadow">
                <div class="card-header bg-white py-3">
                    <h6 class="m-0 font-weight-bold text-primary">योजना विशेषताहरू</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <?php $__currentLoopData = $currentPlan->getFeaturesArray(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $feature): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="col-md-6 mb-2">
                                <i class="fas fa-check text-success me-2"></i> <?php echo e(trim($feature)); ?>

                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const planForms = document.querySelectorAll('.plan-form');
        
        planForms.forEach(form => {
            form.addEventListener('submit', async function(e) {
                e.preventDefault();
                
                const button = this.querySelector('button[type="submit"]');
                const originalText = button.innerHTML;
                
                // लोडिङ स्थिति देखाउनुहोस्
                button.disabled = true;
                button.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> प्रक्रिया हुदैछ...';
                
                try {
                    const formData = new FormData(this);
                    
                    const response = await fetch(this.action, {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json'
                        }
                    });
                    
                    const data = await response.json();
                    
                    if (data.redirect) {
                        window.location.href = data.redirect;
                    } else if (data.success) {
                        // सफलता सन्देश देखाउनुहोस् र पृष्ठ पुनः लोड गर्नुहोस्
                        alert(data.message || 'योजना सफलतापूर्वक अपग्रेड गरियो');
                        window.location.reload();
                    } else {
                        throw new Error(data.message || 'अज्ञात त्रुटि');
                    }
                } catch (error) {
                    alert('त्रुटि: ' + error.message);
                    button.disabled = false;
                    button.innerHTML = originalText;
                }
            });
        });
    });
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.owner', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\My Projects\HostelHub\resources\views\subscription\show.blade.php ENDPATH**/ ?>