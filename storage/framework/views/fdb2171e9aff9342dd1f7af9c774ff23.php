

<?php $__env->startSection('title', 'कोठा सम्पादन गर्नुहोस्'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">कोठा सम्पादन गर्नुहोस्</h3>
                </div>

                <form action="<?php echo e(route('owner.rooms.update', $room)); ?>" method="POST" enctype="multipart/form-data" id="roomForm">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('PUT'); ?>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="hostel_id">होस्टल</label>
                                    <select class="form-control <?php $__errorArgs = ['hostel_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="hostel_id" name="hostel_id" required>
                                        <option value="">होस्टल छान्नुहोस्</option>
                                        <?php $__currentLoopData = $hostels; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $hostel): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($hostel->id); ?>" <?php echo e($room->hostel_id == $hostel->id ? 'selected' : ''); ?>>
                                                <?php echo e($hostel->name); ?>

                                            </option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                    <?php $__errorArgs = ['hostel_id'];
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
                                <div class="form-group">
                                    <label for="room_number">कोठा नम्बर</label>
                                    <input type="text" class="form-control <?php $__errorArgs = ['room_number'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="room_number" name="room_number" value="<?php echo e(old('room_number', $room->room_number)); ?>" required>
                                    <?php $__errorArgs = ['room_number'];
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

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="type">कोठाको प्रकार</label>
                                    <select class="form-control <?php $__errorArgs = ['type'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="type" name="type" required>
                                        <option value="">प्रकार छान्नुहोस्</option>
                                        
                                        <option value="1 seater" <?php echo e(old('type', $room->type) == '1 seater' ? 'selected' : ''); ?>>१ सिटर कोठा</option>
                                        <option value="2 seater" <?php echo e(old('type', $room->type) == '2 seater' ? 'selected' : ''); ?>>२ सिटर कोठा</option>
                                        <option value="3 seater" <?php echo e(old('type', $room->type) == '3 seater' ? 'selected' : ''); ?>>३ सिटर कोठा</option>
                                        <option value="4 seater" <?php echo e(old('type', $room->type) == '4 seater' ? 'selected' : ''); ?>>४ सिटर कोठा</option>
                                        <option value="साझा कोठा" <?php echo e(old('type', $room->type) == 'साझा कोठा' ? 'selected' : ''); ?>>साझा कोठा</option>
                                    </select>
                                    <?php $__errorArgs = ['type'];
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
                                <div class="form-group">
                                    <label for="capacity">क्षमता (व्यक्ति संख्या)</label>
                                    <input type="number" class="form-control <?php $__errorArgs = ['capacity'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="capacity" name="capacity" 
                                           value="<?php echo e(old('capacity', $room->capacity)); ?>" min="1" max="20" required 
                                           <?php echo e($room->type !== 'साझा कोठा' ? 'readonly' : ''); ?>>
                                    <small class="form-text" id="capacityHelp">
                                        <?php if($room->type == '1 seater'): ?>
                                            १ सिटर कोठाको क्षमता 1 हुनुपर्छ
                                        <?php elseif($room->type == '2 seater'): ?>
                                            २ सिटर कोठाको क्षमता 2 हुनुपर्छ
                                        <?php elseif($room->type == '3 seater'): ?>
                                            ३ सिटर कोठाको क्षमता 3 हुनुपर्छ
                                        <?php elseif($room->type == '4 seater'): ?>
                                            ४ सिटर कोठाको क्षमता 4 हुनुपर्छ
                                        <?php else: ?>
                                            साझा कोठाको क्षमता कम्तिमा 5 हुनुपर्छ
                                        <?php endif; ?>
                                    </small>
                                    <?php $__errorArgs = ['capacity'];
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

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="current_occupancy">हालको अधिभोग</label>
                                    <input type="number" class="form-control <?php $__errorArgs = ['current_occupancy'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="current_occupancy" name="current_occupancy" 
                                           value="<?php echo e(old('current_occupancy', $room->current_occupancy ?? 0)); ?>" min="0" max="<?php echo e($room->capacity); ?>" required>
                                    <small class="form-text text-muted">कोठामा हाल बस्ने विद्यार्थीहरूको संख्या</small>
                                    <?php $__errorArgs = ['current_occupancy'];
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
                                <div class="form-group">
                                    <label for="price">मूल्य (प्रतिमहिना)</label>
                                    <div class="input-group">
                                        <span class="input-group-text">रु.</span>
                                        <input type="number" class="form-control <?php $__errorArgs = ['price'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="price" name="price" value="<?php echo e(old('price', $room->price)); ?>" min="0" step="0.01" required>
                                    </div>
                                    <?php $__errorArgs = ['price'];
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

                        <div class="row">
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="gallery_category" class="form-label">ग्यालरी श्रेणी</label>
                                    <select name="gallery_category" id="gallery_category" class="form-control <?php $__errorArgs = ['gallery_category'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                                        <option value="">स्वचालित रूपमा सेट हुन्छ</option>
                                        <option value="1 seater" <?php echo e(old('gallery_category', $room->gallery_category) == '1 seater' ? 'selected' : ''); ?>>१ सिटर कोठा</option>
                                        <option value="2 seater" <?php echo e(old('gallery_category', $room->gallery_category) == '2 seater' ? 'selected' : ''); ?>>२ सिटर कोठा</option>
                                        <option value="3 seater" <?php echo e(old('gallery_category', $room->gallery_category) == '3 seater' ? 'selected' : ''); ?>>३ सिटर कोठा</option>
                                        <option value="4 seater" <?php echo e(old('gallery_category', $room->gallery_category) == '4 seater' ? 'selected' : ''); ?>>४ सिटर कोठा</option>
                                        <option value="साझा कोठा" <?php echo e(old('gallery_category', $room->gallery_category) == 'साझा कोठा' ? 'selected' : ''); ?>>साझा कोठा</option> <!-- ✅ NEW OPTION -->
                                        <option value="living_room" <?php echo e(old('gallery_category', $room->gallery_category) == 'living_room' ? 'selected' : ''); ?>>लिभिङ रूम</option>
                                        <option value="bathroom" <?php echo e(old('gallery_category', $room->gallery_category) == 'bathroom' ? 'selected' : ''); ?>>बाथरूम</option>
                                        <option value="kitchen" <?php echo e(old('gallery_category', $room->gallery_category) == 'kitchen' ? 'selected' : ''); ?>>भान्सा</option>
                                        <option value="study_room" <?php echo e(old('gallery_category', $room->gallery_category) == 'study_room' ? 'selected' : ''); ?>>अध्ययन कोठा</option>
                                        <option value="events" <?php echo e(old('gallery_category', $room->gallery_category) == 'events' ? 'selected' : ''); ?>>कार्यक्रम</option>
                                        <option value="video_tour" <?php echo e(old('gallery_category', $room->gallery_category) == 'video_tour' ? 'selected' : ''); ?>>भिडियो टुर</option>
                                    </select>
                                    <small class="form-text text-muted">स्वचालित रूपमा कोठा प्रकार अनुसार सेट हुन्छ</small>
                                    <?php $__errorArgs = ['gallery_category'];
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
                                <div class="form-group">
                                    <label for="status">हालको स्थिति</label>
                                    <select class="form-control <?php $__errorArgs = ['status'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="status" name="status" required>
                                        <option value="available" <?php echo e(old('status', $room->status) == 'available' ? 'selected' : ''); ?>>उपलब्ध</option>
                                        <option value="occupied" <?php echo e(old('status', $room->status) == 'occupied' ? 'selected' : ''); ?>>व्यस्त</option>
                                        <option value="maintenance" <?php echo e(old('status', $room->status) == 'maintenance' ? 'selected' : ''); ?>>मर्मत सम्भार</option>
                                        <option value="partially_available" <?php echo e(old('status', $room->status) == 'partially_available' ? 'selected' : ''); ?>>आंशिक उपलब्ध</option>
                                    </select>
                                    <small class="form-text text-muted">स्वचालित रूपमा अद्यावधिक हुन्छ</small>
                                    <?php $__errorArgs = ['status'];
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

                        
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="image">कोठाको फोटो</label>
                                    <input type="file" class="form-control <?php $__errorArgs = ['image'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="image" name="image" 
                                           accept="image/jpeg,image/png,image/jpg,image/gif,image/webp">
                                    <small class="form-text text-muted">JPG, PNG, JPEG, GIF, WEBP format मा मात्र, अधिकतम size: 2MB</small>
                                    <?php $__errorArgs = ['image'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <div class="invalid-feedback"><?php echo e($message); ?></div>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                    
                                    
                                    <?php if($room->image): ?>
                                        <div class="mt-2">
                                            <label>हालको फोटो:</label>
                                            <div>
                                                <img src="<?php echo e(Storage::disk('public')->url($room->image)); ?>" alt="Room Image" 
                                                     style="max-width: 200px; max-height: 150px; border-radius: 8px;">
                                                <div class="mt-1">
                                                    <small class="text-muted">नयाँ फोटो अपलोड गर्दा पुरानो फोटो स्वत: हटाइनेछ</small>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endif; ?>
                                    
                                    
                                    <div id="imagePreview" class="mt-2" style="display: none;">
                                        <label>नयाँ फोटो पूर्वावलोकन:</label>
                                        <div>
                                            <img id="preview" src="#" alt="Image Preview" style="max-width: 200px; max-height: 150px; border-radius: 8px;">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="description">कोठाको विवरण</label>
                            <textarea class="form-control <?php $__errorArgs = ['description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="description" name="description" rows="3"><?php echo e(old('description', $room->description)); ?></textarea>
                            <?php $__errorArgs = ['description'];
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

                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> परिवर्तनहरू सुरक्षित गर्नुहोस्
                        </button>
                        <a href="<?php echo e(route('owner.rooms.index')); ?>" class="btn btn-default">
                            <i class="fas fa-times"></i> रद्द गर्नुहोस्
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // ✅ NEW: Type-capacity validation rules
        const typeCapacityRules = {
            '1 seater': 1,
            '2 seater': 2,
            '3 seater': 3,
            '4 seater': 4,
            'साझा कोठा': 'custom'
        };

        // ✅ NEW: Type-capacity validation function
        function validateTypeCapacity(type, capacity) {
            if (typeCapacityRules[type] && typeCapacityRules[type] !== 'custom') {
                return capacity == typeCapacityRules[type];
            }
            return type !== 'साझा कोठा' || capacity >= 5;
        }

        // Auto-calculate capacity based on room type
        const typeSelect = document.getElementById('type');
        const capacityInput = document.getElementById('capacity');
        const currentOccupancyInput = document.getElementById('current_occupancy');
        const galleryCategorySelect = document.getElementById('gallery_category');
        const statusSelect = document.getElementById('status');
        const capacityHelp = document.getElementById('capacityHelp');
        const form = document.getElementById('roomForm');
        
        if (typeSelect && capacityInput && galleryCategorySelect && currentOccupancyInput && statusSelect) {
            typeSelect.addEventListener('change', function() {
                const selectedType = this.value;
                
                switch(selectedType) {
                    case '1 seater':
                        capacityInput.value = 1;
                        capacityInput.readOnly = true;
                        capacityInput.min = 1;
                        currentOccupancyInput.max = 1;
                        galleryCategorySelect.value = '1 seater';
                        capacityHelp.textContent = '१ सिटर कोठाको क्षमता 1 हुनुपर्छ';
                        capacityHelp.className = 'form-text text-success';
                        break;
                    case '2 seater':
                        capacityInput.value = 2;
                        capacityInput.readOnly = true;
                        capacityInput.min = 1;
                        currentOccupancyInput.max = 2;
                        galleryCategorySelect.value = '2 seater';
                        capacityHelp.textContent = '२ सिटर कोठाको क्षमता 2 हुनुपर्छ';
                        capacityHelp.className = 'form-text text-success';
                        break;
                    case '3 seater':
                        capacityInput.value = 3;
                        capacityInput.readOnly = true;
                        capacityInput.min = 1;
                        currentOccupancyInput.max = 3;
                        galleryCategorySelect.value = '3 seater';
                        capacityHelp.textContent = '३ सिटर कोठाको क्षमता 3 हुनुपर्छ';
                        capacityHelp.className = 'form-text text-success';
                        break;
                    case '4 seater':
                        capacityInput.value = 4;
                        capacityInput.readOnly = true;
                        capacityInput.min = 1;
                        currentOccupancyInput.max = 4;
                        galleryCategorySelect.value = '4 seater';
                        capacityHelp.textContent = '४ सिटर कोठाको क्षमता 4 हुनुपर्छ';
                        capacityHelp.className = 'form-text text-success';
                        break;
                    case 'साझा कोठा':
                        // Keep current value but ensure minimum 5
                        if (parseInt(capacityInput.value) < 5) {
                            capacityInput.value = 5;
                        }
                        capacityInput.readOnly = false;
                        capacityInput.min = 5;
                        currentOccupancyInput.max = capacityInput.value;
                        galleryCategorySelect.value = 'साझा कोठा'; // ✅ CHANGED: '4 seater' → 'साझा कोठा'
                        capacityHelp.textContent = 'साझा कोठाको क्षमता कम्तिमा 5 हुनुपर्छ';
                        capacityHelp.className = 'form-text text-info';
                        break;
                    default:
                        capacityInput.value = 1;
                        capacityInput.readOnly = true;
                        currentOccupancyInput.max = 1;
                        capacityHelp.textContent = 'कोठा प्रकार छान्नुहोस्';
                        capacityHelp.className = 'form-text text-muted';
                }
                
                // Ensure current occupancy doesn't exceed new capacity
                if (parseInt(currentOccupancyInput.value) > parseInt(capacityInput.value)) {
                    currentOccupancyInput.value = capacityInput.value;
                }
                
                // Auto-update status based on new capacity and occupancy
                updateStatus();
            });

            // Update capacity max for current occupancy when capacity changes manually (only for shared rooms)
            capacityInput.addEventListener('change', function() {
                if (typeSelect.value === 'साझा कोठा') {
                    const capacity = parseInt(this.value);
                    currentOccupancyInput.max = capacity;
                    
                    // Ensure current occupancy doesn't exceed new capacity
                    if (parseInt(currentOccupancyInput.value) > capacity) {
                        currentOccupancyInput.value = capacity;
                    }
                    
                    // Auto-update status based on new capacity and occupancy
                    updateStatus();
                }
            });

            // Set initial values based on current room type
            if (typeSelect.value) {
                typeSelect.dispatchEvent(new Event('change'));
            }

            // ✅ FIXED: Update status based on occupancy using normalized English values
            currentOccupancyInput.addEventListener('change', updateStatus);
            
            // ✅ FIXED: Status update function with normalized English values
            function updateStatus() {
                const capacity = parseInt(capacityInput.value);
                const occupancy = parseInt(currentOccupancyInput.value);
                
                // Don't auto-update if status is manually set to maintenance
                if (statusSelect.value === 'maintenance') {
                    return;
                }
                
                if (occupancy === 0) {
                    statusSelect.value = 'available';
                } else if (occupancy === capacity) {
                    statusSelect.value = 'occupied';
                } else if (occupancy > 0 && occupancy < capacity) {
                    statusSelect.value = 'partially_available';
                }
            }

            // Initialize status on page load
            updateStatus();
        }

        // ✅ Image preview functionality
        const imageInput = document.getElementById('image');
        const imagePreview = document.getElementById('imagePreview');
        const preview = document.getElementById('preview');

        if (imageInput && preview) {
            imageInput.addEventListener('change', function() {
                const file = this.files[0];
                if (file) {
                    const reader = new FileReader();
                    
                    reader.addEventListener('load', function() {
                        preview.src = reader.result;
                        imagePreview.style.display = 'block';
                    });
                    
                    reader.readAsDataURL(file);
                } else {
                    imagePreview.style.display = 'none';
                }
            });
        }
        
        // ✅ ENHANCED: Form validation with type-capacity checking
        form.addEventListener('submit', function(e) {
            let isValid = true;
            const requiredFields = form.querySelectorAll('[required]');
            
            // Clear previous invalid states
            form.querySelectorAll('.is-invalid').forEach(field => {
                field.classList.remove('is-invalid');
            });
            
            requiredFields.forEach(field => {
                if (!field.value.trim()) {
                    isValid = false;
                    field.classList.add('is-invalid');
                }
            });

            // Enhanced capacity validation
            const capacity = parseInt(capacityInput.value);
            const occupancy = parseInt(currentOccupancyInput.value);
            const selectedType = typeSelect.value;
            
            // Validate type-capacity consistency
            if (!validateTypeCapacity(selectedType, capacity)) {
                isValid = false;
                capacityInput.classList.add('is-invalid');
                
                const errorMsg = selectedType === 'साझा कोठा' 
                    ? 'साझा कोठाको लागि क्षमता कम्तिमा 5 हुनुपर्छ'
                    : `${selectedType} को लागि क्षमता ${typeCapacityRules[selectedType]} हुनुपर्छ`;
                    
                alert(errorMsg);
            }
            
            // Validate occupancy doesn't exceed capacity
            if (occupancy > capacity) {
                isValid = false;
                currentOccupancyInput.classList.add('is-invalid');
                alert('हालको अधिभोग क्षमताभन्दा बढी हुन सक्दैन');
            }
            
            if (!isValid) {
                e.preventDefault();
                alert('कृपया सबै आवश्यक फिल्डहरू सही ढंगले भर्नुहोस्');
            }
        });

        // ✅ NEW: Reset form handler (for cancel button)
        document.querySelector('a.btn-default').addEventListener('click', function(e) {
            if (!confirm('तपाईं सम्पादन रद्द गर्न चाहनुहुन्छ? सम्पादन गरिएका परिवर्तनहरू हराउनेछन्।')) {
                e.preventDefault();
            }
        });
    });
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.owner', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\HostelHub\resources\views\owner\rooms\edit.blade.php ENDPATH**/ ?>