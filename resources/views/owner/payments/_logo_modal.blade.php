<!-- Logo Upload Modal -->
<div class="modal fade" id="logoUploadModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">
                    <i class="fas fa-upload me-2"></i>होस्टल लोगो अपलोड गर्नुहोस्
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('owner.hostels.logo.upload', $hostel->id ?? 0) }}" method="POST" enctype="multipart/form-data" id="logoUploadForm">
                @csrf
                <div class="modal-body">
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        बिल र रसिद जारी गर्नका लागि होस्टलको लोगो आवश्यक छ।
                    </div>

                    <div class="mb-3">
                        <label for="logo" class="form-label">लोगो छनौट गर्नुहोस्</label>
                        <input type="file" class="form-control" id="logo" name="logo" accept="image/*" required>
                        <div class="form-text">
                            स्वीकार्य फाइलहरू: JPEG, PNG, JPG, GIF। अधिकतम साइज: 2MB
                        </div>
                    </div>

                    <div class="logo-preview mb-3 text-center" style="display: none;">
                        <img id="logoPreview" src="#" alt="Logo Preview" class="img-thumbnail" style="max-height: 150px;">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">रद्द गर्नुहोस्</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-upload me-2"></i>अपलोड गर्नुहोस्
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Logo preview
    const logoInput = document.getElementById('logo');
    const logoPreview = document.getElementById('logoPreview');
    const logoPreviewContainer = document.querySelector('.logo-preview');
    
    if (logoInput) {
        logoInput.addEventListener('change', function() {
            const file = this.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    logoPreview.src = e.target.result;
                    logoPreviewContainer.style.display = 'block';
                }
                reader.readAsDataURL(file);
            } else {
                logoPreviewContainer.style.display = 'none';
            }
        });
    }
    
    // Show modal if triggered
    @if(session('show_logo_modal'))
        const modal = new bootstrap.Modal(document.getElementById('logoUploadModal'));
        modal.show();
    @endif
});
</script>
@endpush