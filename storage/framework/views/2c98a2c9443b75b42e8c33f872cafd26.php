<div class="modal fade" id="paymentModal" tabindex="-1" aria-labelledby="paymentModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="paymentModalLabel">भुक्तानी गर्नुहोस्</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="payment-details">
                    <h6>भुक्तानी विवरण</h6>
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>भुक्तानी रकम:</strong> <span id="modalPaymentAmount">-</span></p>
                            <p><strong>भुक्तानी महिना:</strong> <span id="modalPaymentMonth">-</span></p>
                            <p><strong>भुक्तानी प्रकार:</strong> <span id="modalPaymentType">-</span></p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>म्याद:</strong> <span id="modalPaymentDueDate">-</span></p>
                            <p><strong>स्थिति:</strong> <span id="modalPaymentStatus">-</span></p>
                            <p><strong>बाँकी रकम:</strong> <span id="modalPaymentDueAmount">-</span></p>
                        </div>
                    </div>
                </div>

                <div class="payment-methods mt-4">
                    <h6>भुक्तानी विधिहरू</h6>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="paymentMethod" id="esewaMethod" value="esewa" checked>
                        <label class="form-check-label" for="esewaMethod">
                            <i class="fas fa-mobile-alt"></i> eSewa
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="paymentMethod" id="khaltiMethod" value="khalti">
                        <label class="form-check-label" for="khaltiMethod">
                            <i class="fas fa-wallet"></i> Khalti
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="paymentMethod" id="bankMethod" value="bank">
                        <label class="form-check-label" for="bankMethod">
                            <i class="fas fa-university"></i> बैंक हस्तान्तरण
                        </label>
                    </div>
                </div>

                <div class="bank-details mt-3" id="bankDetails" style="display: none;">
                    <h6>बैंक विवरण</h6>
                    <div class="alert alert-info">
                        <strong>बैंक:</strong> एनएमबी बैंक<br>
                        <strong>खाता नम्बर:</strong> 1234567890123<br>
                        <strong>खाता नाम:</strong> Hostel Hub<br>
                        <strong>शाखा:</strong> काठमाडौं
                    </div>
                    <div class="mb-3">
                        <label for="transactionId" class="form-label">ट्रान्जेक्सन आईडी</label>
                        <input type="text" class="form-control" id="transactionId" placeholder="ट्रान्जेक्सन आईडी प्रविष्ट गर्नुहोस्">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">रद्द गर्नुहोस्</button>
                <button type="button" class="btn btn-primary" id="proceedPaymentBtn">भुक्तानी गर्नुहोस्</button>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const paymentModal = document.getElementById('paymentModal');
    if (paymentModal) {
        paymentModal.addEventListener('show.bs.modal', function (event) {
            const button = event.relatedTarget;
            const amount = button.getAttribute('data-amount');
            const month = button.getAttribute('data-month');
            const dueDate = button.getAttribute('data-due-date');
            const paymentType = button.getAttribute('data-payment-type');

            document.getElementById('modalPaymentAmount').textContent = amount ? 'रु ' + amount : '-';
            document.getElementById('modalPaymentMonth').textContent = month || '-';
            document.getElementById('modalPaymentDueDate').textContent = dueDate || '-';
            document.getElementById('modalPaymentType').textContent = paymentType || 'मासिक भुक्तानी';
            document.getElementById('modalPaymentStatus').textContent = 'बाँकी';
            document.getElementById('modalPaymentDueAmount').textContent = amount ? 'रु ' + amount : '-';
        });
    }

    const paymentMethods = document.querySelectorAll('input[name="paymentMethod"]');
    const bankDetails = document.getElementById('bankDetails');

    paymentMethods.forEach(method => {
        method.addEventListener('change', function() {
            if (this.value === 'bank') {
                bankDetails.style.display = 'block';
            } else {
                bankDetails.style.display = 'none';
            }
        });
    });

    const proceedBtn = document.getElementById('proceedPaymentBtn');
    if (proceedBtn) {
        proceedBtn.addEventListener('click', function() {
            const selectedMethod = document.querySelector('input[name="paymentMethod"]:checked').value;
            
            if (selectedMethod === 'bank') {
                const transactionId = document.getElementById('transactionId').value;
                if (!transactionId) {
                    alert('कृपया ट्रान्जेक्सन आईडी प्रविष्ट गर्नुहोस्');
                    return;
                }
                alert('बैंक हस्तान्तरणको लागि अनुरोध पेश गरियो। प्रमाणीकरण पछि स्वीकृत गरिनेछ।');
            } else if (selectedMethod === 'esewa') {
                alert('eSewa भुक्तानी प्रणालीमा पठाइँदै...');
            } else if (selectedMethod === 'khalti') {
                alert('Khalti भुक्तानी प्रणालीमा पठाइँदै...');
            }

            const modal = bootstrap.Modal.getInstance(paymentModal);
            modal.hide();
        });
    }
});
</script><?php /**PATH C:\laragon\www\HostelHub\resources\views\student\modals\payment.blade.php ENDPATH**/ ?>