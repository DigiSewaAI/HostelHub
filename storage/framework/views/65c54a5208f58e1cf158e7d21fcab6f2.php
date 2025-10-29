<?php $__env->startSection('title', 'नयाँ सम्पर्क थप्नुहोस्'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">नयाँ सम्पर्क थप्नुहोस्</h3>
                </div>

                <form action="<?php echo e(route('admin.contacts.store')); ?>" method="POST">
                    <?php echo csrf_field(); ?>
                    <div class="card-body">
                        <div class="form-group">
                            <label for="name">पूरा नाम</label>
                            <input type="text" class="form-control" id="name" name="name" placeholder="जस्तै: राम श्रेष्ठ" required>
                        </div>

                        <div class="form-group">
                            <label for="email">इमेल ठेगाना</label>
                            <input type="email" class="form-control" id="email" name="email" placeholder="जस्तै: ram@example.com" required>
                        </div>

                        <div class="form-group">
                            <label for="phone">फोन नम्बर</label>
                            <input type="text" class="form-control" id="phone" name="phone" placeholder="जस्तै: ९८०१२३४५६७">
                        </div>

                        <div class="form-group">
                            <label for="subject">विषय</label>
                            <input type="text" class="form-control" id="subject" name="subject" placeholder="जस्तै: कोठा को बारेमा जानकारी" required>
                        </div>

                        <div class="form-group">
                            <label for="message">सन्देश</label>
                            <textarea class="form-control" id="message" name="message" rows="5" placeholder="आफ्नो सन्देश यहाँ लेख्नुहोस्..." required></textarea>
                        </div>
                    </div>

                    <div class="card-footer">
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-save"></i> सम्पर्क सुरक्षित गर्नुहोस्
                        </button>
                        <a href="<?php echo e(route('admin.contacts.index')); ?>" class="btn btn-default">
                            <i class="fas fa-times"></i> रद्द गर्नुहोस्
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\My Projects\HostelHub\resources\views\admin\contacts\create.blade.php ENDPATH**/ ?>