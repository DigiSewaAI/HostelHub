

<?php $__env->startSection('title', 'कोठा विवरण'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">कोठा विवरण</h3>
                </div>

                <div class="card-body">
                    
                    <?php if($room->has_image): ?>
                    <div class="row mb-4">
                        <div class="col-md-12">
                            <h5>कोठाको फोटो:</h5>
                            <div class="text-center">
                                <img src="<?php echo e($room->image_url); ?>" 
                                     alt="Room Image" 
                                     class="img-fluid rounded"
                                     style="max-height: 400px; object-fit: cover;">
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>

                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-bordered">
                                <tr>
                                    <th style="width: 30%;">कोठा नम्बर:</th>
                                    <td><?php echo e($room->room_number); ?></td>
                                </tr>
                                <tr>
                                    <th>होस्टल:</th>
                                    <td><?php echo e($room->hostel->name ?? 'N/A'); ?></td>
                                </tr>
                                <tr>
                                    <th>प्रकार:</th>
                                    <td>
                                        
                                        <?php if($room->type == '1 seater'): ?>
                                            एक सिटर कोठा
                                        <?php elseif($room->type == '2 seater'): ?>
                                            दुई सिटर कोठा
                                        <?php elseif($room->type == '3 seater'): ?>
                                            तीन सिटर कोठा
                                        <?php elseif($room->type == '4 seater'): ?>
                                            चार सिटर कोठा
                                        <?php elseif($room->type == 'साझा कोठा'): ?>
                                            साझा कोठा
                                        <?php else: ?>
                                            <?php echo e($room->type); ?>

                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <tr>
                                    <th>क्षमता:</th>
                                    <td><?php echo e($room->capacity); ?> जना</td>
                                </tr>
                                <tr>
                                    <th>हालको अधिभोग:</th>
                                    <td><?php echo e($room->current_occupancy ?? 0); ?> जना</td>
                                </tr>
                                <tr>
                                    <th>खाली ठाउँ:</th>
                                    <td><?php echo e(($room->capacity - ($room->current_occupancy ?? 0))); ?> जना</td>
                                </tr>
                                <tr>
                                    <th>मूल्य:</th>
                                    <td>रु. <?php echo e(number_format($room->price, 2)); ?></td>
                                </tr>
                                <tr>
                                    
                                    <th>ग्यालरी श्रेणी:</th>
                                    <td>
                                        <?php
                                            $galleryCategories = [
                                                '1 seater' => '१ सिटर कोठा',
                                                '1_seater' => '१ सिटर कोठा',
                                                '2 seater' => '२ सिटर कोठा', 
                                                '2_seater' => '२ सिटर कोठा',
                                                '3 seater' => '३ सिटर कोठा',
                                                '3_seater' => '३ सिटर कोठा',
                                                '4 seater' => '४ सिटर कोठा',
                                                '4_seater' => '४ सिटर कोठा',
                                                'साझा कोठा' => 'साझा कोठा',
                                                'living_room' => 'लिभिङ रूम',
                                                'bathroom' => 'बाथरूम',
                                                'kitchen' => 'भान्सा',
                                                'study_room' => 'अध्ययन कोठा',
                                                'events' => 'कार्यक्रम',
                                                'video_tour' => 'भिडियो टुर'
                                            ];
                                        ?>
                                        <?php echo e($galleryCategories[$room->gallery_category] ?? $room->gallery_category); ?>

                                    </td>
                                </tr>
                                <tr>
                                    <th>स्थिति:</th>
                                    <td>
                                        
                                        <?php
                                            $status = $room->status;
                                            $available_beds = $room->capacity - ($room->current_occupancy ?? 0);
                                            
                                            if ($status === 'maintenance') {
                                                $displayStatus = 'मर्मत सम्भार';
                                                $badgeClass = 'bg-secondary text-white';
                                            } elseif ($status === 'occupied') {
                                                $displayStatus = 'व्यस्त';
                                                $badgeClass = 'bg-danger text-white';
                                            } elseif ($status === 'partially_available') {
                                                $displayStatus = 'आंशिक उपलब्ध (' . $available_beds . ' बेड खाली)';
                                                $badgeClass = 'bg-warning text-dark';
                                            } else {
                                                $displayStatus = 'उपलब्ध';
                                                $badgeClass = 'bg-success text-white';
                                            }
                                        ?>
                                        <span class="badge <?php echo e($badgeClass); ?> p-2">
                                            <?php echo e($displayStatus); ?>

                                        </span>
                                    </td>
                                </tr>
                            </table>
                        </div>

                        <div class="col-md-6">
                            <h5>विवरण:</h5>
                            <div class="border rounded p-3 bg-light">
                                <p class="mb-0"><?php echo e($room->description ?? 'कुनै विवरण उपलब्ध छैन'); ?></p>
                            </div>

                            
                            <div class="mt-4">
                                <h5>अतिरिक्त जानकारी:</h5>
                                <div class="border rounded p-3">
                                    <div class="row">
                                        <div class="col-6">
                                            <small class="text-muted">सिर्जना गरिएको:</small><br>
                                            <strong><?php echo e($room->created_at->format('Y-m-d')); ?></strong>
                                        </div>
                                        <div class="col-6">
                                            <small class="text-muted">अन्तिम अपडेट:</small><br>
                                            <strong><?php echo e($room->updated_at->format('Y-m-d')); ?></strong>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card-footer">
                    <a href="<?php echo e(route('owner.rooms.edit', $room)); ?>" class="btn btn-primary">
                        <i class="fas fa-edit"></i> सम्पादन गर्नुहोस्
                    </a>
                    <a href="<?php echo e(route('owner.rooms.index')); ?>" class="btn btn-default">
                        <i class="fas fa-arrow-left"></i> कोठा सूचीमा फर्कनुहोस्
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.owner', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\My Projects\HostelHub\resources\views/owner/rooms/show.blade.php ENDPATH**/ ?>