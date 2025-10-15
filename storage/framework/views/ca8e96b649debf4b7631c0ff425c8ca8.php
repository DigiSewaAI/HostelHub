<div class="modal fade" id="maintenanceModal" tabindex="-1" aria-labelledby="maintenanceModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="maintenanceModalLabel">मर्मतको अनुरोध</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="maintenanceForm" action="<?php echo e(route('student.maintenance.submit')); ?>" method="POST">
                    <?php echo csrf_field(); ?>
                    <div class="mb-3">
                        <label for="maintenanceTitle" class="form-label">शीर्षक</label>
                        <input type="text" class="form-control" id="maintenanceTitle" name="title" required placeholder="मर्मतको शीर्षक प्रविष्ट गर्नुहोस्">
                    </div>
                    <div class="mb-3">
                        <label for="maintenanceDescription" class="form-label">विवरण</label>
                        <textarea class="form-control" id="maintenanceDescription" name="description" rows="3" required placeholder="मर्मतको विस्तृत विवरण प्रविष्ट गर्नुहोस्"></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="maintenancePriority" class="form-label">प्राथमिकता</label>
                        <select class="form-select" id="maintenancePriority" name="priority" required>
                            <option value="">प्राथमिकता छान्नुहोस्</option>
                            <option value="low">कम</option>
                            <option value="medium">मध्यम</option>
                            <option value="high">उच्च</option>
                            <option value="urgent">अति जरुरी</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">सम्बन्धित क्षेत्र</label>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="area" id="areaRoom" value="room" checked>
                            <label class="form-check-label" for="areaRoom">कोठा</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="area" id="areaBathroom" value="bathroom">
                            <label class="form-check-label" for="areaBathroom">बाथरुम</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="area" id="areaCommon" value="common">
                            <label class="form-check-label" for="areaCommon">साझा क्षेत्र</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="area" id="areaOther" value="other">
                            <label class="form-check-label" for="areaOther">अन्य</label>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">रद्द गर्नुहोस्</button>
                <button type="submit" form="maintenanceForm" class="btn btn-primary">अनुरोध पेश गर्नुहोस्</button>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const maintenanceForm = document.getElementById('maintenanceForm');
    if (maintenanceForm) {
        maintenanceForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            
            fetch(this.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('मर्मतको अनुरोध सफलतापूर्वक पेश गरियो।');
                    const modal = bootstrap.Modal.getInstance(document.getElementById('maintenanceModal'));
                    modal.hide();
                    maintenanceForm.reset();
                } else {
                    alert('त्रुटि: ' + (data.message || 'अनुरोध पेश गर्न असफल।'));
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('त्रुटि: नेटवर्क समस्या।');
            });
        });
    }
});
</script><?php /**PATH D:\My Projects\HostelHub\resources\views/student/modals/maintenance.blade.php ENDPATH**/ ?>