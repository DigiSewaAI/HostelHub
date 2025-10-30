

<?php $__env->startSection('content'); ?>
<div class="container">
    <h1>मेरो प्रोफाइल</h1>
    
    <?php if(session('success')): ?>
        <div class="alert alert-success">
            <?php echo e(session('success')); ?>

        </div>
    <?php endif; ?>

    <?php if($errors->any()): ?>
        <div class="alert alert-danger">
            <ul class="mb-0">
                <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <li><?php echo e($error); ?></li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </ul>
        </div>
    <?php endif; ?>

    <!-- Read-only View -->
    <div id="profileView">
        <div class="row">
            <div class="col-md-6">
                <h4>व्यक्तिगत जानकारी</h4>
                <div class="mb-3">
                    <label class="form-label"><strong>नाम:</strong></label>
                    <p class="ps-2"><?php echo e($student->user->name); ?></p>
                </div>
                
                <div class="mb-3">
                    <label class="form-label"><strong>इमेल:</strong></label>
                    <p class="ps-2"><?php echo e($student->user->email); ?></p>
                </div>
                
                <div class="mb-3">
                    <label class="form-label"><strong>फोन नम्बर:</strong></label>
                    <p class="ps-2" id="currentPhone"><?php echo e($student->phone); ?></p>
                </div>
                
                <div class="mb-3">
                    <label class="form-label"><strong>ठेगाना:</strong></label>
                    <p class="ps-2" id="currentAddress"><?php echo e($student->address); ?></p>
                </div>
            </div>
            
            <div class="col-md-6">
                <h4>अभिभावकको जानकारी</h4>
                <div class="mb-3">
                    <label class="form-label"><strong>अभिभावकको नाम:</strong></label>
                    <p class="ps-2" id="currentGuardianName"><?php echo e($student->guardian_name); ?></p>
                </div>
                
                <div class="mb-3">
                    <label class="form-label"><strong>अभिभावकको फोन:</strong></label>
                    <p class="ps-2" id="currentGuardianPhone"><?php echo e($student->guardian_phone); ?></p>
                </div>
                
                <div class="mb-3">
                    <label class="form-label"><strong>अभिभावकको नाता:</strong></label>
                    <p class="ps-2" id="currentGuardianRelation"><?php echo e($student->guardian_relation); ?></p>
                </div>
                
                <div class="mb-3">
                    <label class="form-label"><strong>अभिभावकको ठेगाना:</strong></label>
                    <p class="ps-2" id="currentGuardianAddress"><?php echo e($student->guardian_address); ?></p>
                </div>
            </div>
        </div>
        
        <button type="button" class="btn btn-primary" onclick="showEditForm()">प्रोफाइल सम्पादन गर्नुहोस्</button>
    </div>

    <!-- Edit Form (Hidden by default) -->
    <form action="<?php echo e(route('student.profile.update')); ?>" method="POST" id="editForm" style="display: none;">
        <?php echo csrf_field(); ?>
        <?php echo method_field('PUT'); ?>
        
        <div class="row">
            <div class="col-md-6">
                <h4>व्यक्तिगत जानकारी</h4>
                
                <div class="mb-3">
                    <label for="phone" class="form-label">फोन नम्बर</label>
                    <input type="text" class="form-control <?php $__errorArgs = ['phone'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" name="phone" 
                           value="<?php echo e(old('phone', $student->phone)); ?>" required>
                    <?php $__errorArgs = ['phone'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <div class="invalid-feedback"><?php echo e($message); ?></div>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
                
                <div class="mb-3">
                    <label for="address" class="form-label">ठेगाना</label>
                    <textarea class="form-control <?php $__errorArgs = ['address'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" name="address" rows="3" required><?php echo e(old('address', $student->address)); ?></textarea>
                    <?php $__errorArgs = ['address'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <div class="invalid-feedback"><?php echo e($message); ?></div>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
            </div>
            
            <div class="col-md-6">
                <h4>अभिभावकको जानकारी</h4>
                <div class="mb-3">
                    <label for="guardian_name" class="form-label">अभिभावकको नाम</label>
                    <input type="text" class="form-control <?php $__errorArgs = ['guardian_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" name="guardian_name" 
                           value="<?php echo e(old('guardian_name', $student->guardian_name)); ?>" required>
                    <?php $__errorArgs = ['guardian_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <div class="invalid-feedback"><?php echo e($message); ?></div>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
                
                <div class="mb-3">
                    <label for="guardian_phone" class="form-label">अभिभावकको फोन</label>
                    <input type="text" class="form-control <?php $__errorArgs = ['guardian_phone'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" name="guardian_phone" 
                           value="<?php echo e(old('guardian_phone', $student->guardian_phone)); ?>" required>
                    <?php $__errorArgs = ['guardian_phone'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <div class="invalid-feedback"><?php echo e($message); ?></div>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
                
                <div class="mb-3">
                    <label for="guardian_relation" class="form-label">अभिभावकको नाता</label>
                    <input type="text" class="form-control <?php $__errorArgs = ['guardian_relation'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" name="guardian_relation" 
                           value="<?php echo e(old('guardian_relation', $student->guardian_relation)); ?>" required>
                    <?php $__errorArgs = ['guardian_relation'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <div class="invalid-feedback"><?php echo e($message); ?></div>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
                
                <div class="mb-3">
                    <label for="guardian_address" class="form-label">अभिभावकको ठेगाना</label>
                    <textarea class="form-control <?php $__errorArgs = ['guardian_address'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" name="guardian_address" rows="3" required><?php echo e(old('guardian_address', $student->guardian_address)); ?></textarea>
                    <?php $__errorArgs = ['guardian_address'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <div class="invalid-feedback"><?php echo e($message); ?></div>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
            </div>
        </div>
        
        <div class="mt-4">
            <button type="submit" class="btn btn-success">अपडेट गर्नुहोस्</button>
            <button type="button" class="btn btn-secondary" onclick="hideEditForm()">रद्द गर्नुहोस्</button>
        </div>
    </form>
    
    <div class="mt-5">
        <h4>अन्य जानकारी</h4>
        <div class="row">
            <div class="col-md-4">
                <p><strong>कलेज:</strong> <?php echo e($student->college ?? 'उपलब्ध छैन'); ?></p>
            </div>
            <div class="col-md-4">
                <p><strong>कोठा नम्बर:</strong> <?php echo e($student->room->room_number ?? 'तोकिएको छैन'); ?></p>
            </div>
            <div class="col-md-4">
                <p><strong>स्थिति:</strong> 
                    <span class="badge bg-<?php echo e($student->status == 'active' ? 'success' : 'warning'); ?>">
                        <?php echo e($student->status == 'active' ? 'सक्रिय' : 'निष्क्रिय'); ?>

                    </span>
                </p>
            </div>
        </div>
    </div>
</div>

<script>
function showEditForm() {
    document.getElementById('profileView').style.display = 'none';
    document.getElementById('editForm').style.display = 'block';
}

function hideEditForm() {
    document.getElementById('editForm').style.display = 'none';
    document.getElementById('profileView').style.display = 'block';
}
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.student', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\HostelHub\resources\views\student\profile.blade.php ENDPATH**/ ?>