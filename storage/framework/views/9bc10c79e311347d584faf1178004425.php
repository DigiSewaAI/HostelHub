<div class="modal fade" id="galleryViewModal" tabindex="-1" aria-labelledby="galleryViewModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="galleryViewModalLabel">ग्यालरी</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <img id="galleryModalImage" src="" alt="Gallery Image" class="img-fluid" style="max-height: 70vh;">
                <div class="mt-3">
                    <h5 id="galleryModalTitle" class="mb-2"></h5>
                    <p id="galleryModalDescription" class="text-muted"></p>
                </div>
            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-secondary" id="prevImageBtn">
                    <i class="fas fa-chevron-left"></i> अघिल्लो
                </button>
                <button type="button" class="btn btn-secondary" id="nextImageBtn">
                    अर्को <i class="fas fa-chevron-right"></i>
                </button>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    let currentGalleryIndex = 0;
    let galleryImages = [];

    const galleryModal = document.getElementById('galleryViewModal');
    if (galleryModal) {
        galleryModal.addEventListener('show.bs.modal', function (event) {
            const button = event.relatedTarget;
            const images = JSON.parse(button.getAttribute('data-images') || '[]');
            const startIndex = parseInt(button.getAttribute('data-index') || '0');
            
            galleryImages = images;
            currentGalleryIndex = startIndex;
            
            updateGalleryModal();
        });
    }

    function updateGalleryModal() {
        if (galleryImages.length === 0) return;
        
        const currentImage = galleryImages[currentGalleryIndex];
        document.getElementById('galleryModalImage').src = currentImage.url || currentImage;
        document.getElementById('galleryModalTitle').textContent = currentImage.title || 'ग्यालरी तस्बिर';
        document.getElementById('galleryModalDescription').textContent = currentImage.description || '';
        
        // Update navigation buttons
        document.getElementById('prevImageBtn').style.display = currentGalleryIndex > 0 ? 'block' : 'none';
        document.getElementById('nextImageBtn').style.display = currentGalleryIndex < galleryImages.length - 1 ? 'block' : 'none';
    }

    document.getElementById('prevImageBtn')?.addEventListener('click', function() {
        if (currentGalleryIndex > 0) {
            currentGalleryIndex--;
            updateGalleryModal();
        }
    });

    document.getElementById('nextImageBtn')?.addEventListener('click', function() {
        if (currentGalleryIndex < galleryImages.length - 1) {
            currentGalleryIndex++;
            updateGalleryModal();
        }
    });
});
</script><?php /**PATH C:\laragon\www\HostelHub\resources\views\student\modals\gallery-view.blade.php ENDPATH**/ ?>