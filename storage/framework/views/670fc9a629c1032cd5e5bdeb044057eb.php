<div class="text-center mb-4">
    <h4 class="font-weight-bold">२. आफ्नो होस्टल सिर्जना गर्नुहोस्</h4>
    <p>तपाईंको होस्टलको बारेमा जानकारी प्रदान गर्नुहोस्</p>
</div>

<form method="POST" action="<?php echo e(route('onboarding.store', 2)); ?>">
    <?php echo csrf_field(); ?>
    
    <div class="row">
        <div class="col-md-6 mb-3">
            <label for="hostel_name" class="form-label">होस्टलको नाम</label>
            <input type="text" name="hostel_name" id="hostel_name" class="form-control" required>
        </div>
        
        <div class="col-md-6 mb-3">
            <label for="hostel_city" class="form-label">शहर</label>
            <input type="text" name="hostel_city" id="hostel_city" class="form-control" 
                   value="<?php echo e($organization->settings['city'] ?? ''); ?>" required>
        </div>
        
        <div class="col-md-12 mb-3">
            <label for="hostel_address" class="form-label">ठेगाना</label>
            <textarea name="hostel_address" id="hostel_address" class="form-control" rows="2" required></textarea>
        </div>
        
        <div class="col-md-6 mb-3">
            <label for="contact_phone" class="form-label">सम्पर्क फोन नम्बर</label>
            <input type="tel" name="contact_phone" id="contact_phone" class="form-control" 
                   value="<?php echo e($organization->settings['contact_phone'] ?? ''); ?>" required>
        </div>
    </div>
    
    <div class="d-flex justify-content-between mt-4">
        <button type="submit" class="btn btn-primary px-4">
            सुरक्षित गर्नुहोस् र अगाडि बढ्नुहोस्
        </button>
        <button type="button" class="btn btn-outline-secondary px-4" 
                onclick="window.location.href='<?php echo e(route('onboarding.skip', 2)); ?>'">
            यो चरण छोड्नुहोस्
        </button>
    </div>
</form><?php /**PATH D:\My Projects\HostelHub\resources\views\onboarding\steps\step2.blade.php ENDPATH**/ ?>