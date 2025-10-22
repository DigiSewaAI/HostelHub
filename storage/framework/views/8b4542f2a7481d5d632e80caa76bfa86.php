

<?php $__env->startSection('title', $hostel->name . ' - ग्यालरी'); ?>

<?php $__env->startSection('content'); ?>
<div class="container py-5">
    <div class="row">
        <div class="col-12">
            <h1 class="mb-4"><?php echo e($hostel->name); ?> - ग्यालरी</h1>
            <p class="text-muted">हाम्रो होस्टलको सुविधाहरूको तस्बिर र भिडियोहरू हेर्नुहोस्</p>
        </div>
    </div>

    
    <?php if($categories->count() > 0): ?>
    <div class="row mb-4">
        <div class="col-12">
            <div class="btn-group" role="group">
                <button type="button" class="btn btn-outline-primary active" data-filter="all">सबै</button>
                <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <button type="button" class="btn btn-outline-primary" data-filter="<?php echo e($category); ?>">
                        <?php echo e($category == 'room' ? 'कोठा' : 
                           ($category == 'common_area' ? 'साझा क्षेत्र' : 
                           ($category == 'kitchen' ? 'भान्सा' : 
                           ($category == 'bathroom' ? 'स्नानागार' : 
                           ($category == 'garden' ? 'बगैंचा' : 
                           ($category == 'event' ? 'कार्यक्रम' : $category)))))); ?>

                    </button>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>
    </div>
    <?php endif; ?>

    
    <div class="row" id="gallery-grid">
        <?php $__empty_1 = true; $__currentLoopData = $galleries; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $gallery): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
        <div class="col-md-4 mb-4 gallery-item" data-category="<?php echo e($gallery->category); ?>">
            <div class="card h-100 shadow-sm">
                <?php if($gallery->media_type === 'photo' || $gallery->media_type === 'image'): ?>
                    <img src="<?php echo e(asset('storage/' . $gallery->file_path)); ?>" 
                         class="card-img-top" 
                         alt="<?php echo e($gallery->title); ?>"
                         style="height: 250px; object-fit: cover; cursor: pointer"
                         onclick="openImageModal('<?php echo e(asset('storage/' . $gallery->file_path)); ?>', '<?php echo e($gallery->title); ?>')">
                <?php elseif($gallery->media_type === 'local_video'): ?>
                    <div class="card-img-top position-relative" style="height: 250px; background: #000; cursor: pointer"
                         onclick="openVideoModal('<?php echo e(asset('storage/' . $gallery->file_path)); ?>', '<?php echo e($gallery->title); ?>')">
                        <video class="w-100 h-100" style="object-fit: cover;">
                            <source src="<?php echo e(asset('storage/' . $gallery->file_path)); ?>" type="video/mp4">
                            Your browser does not support the video tag.
                        </video>
                        <div class="position-absolute top-50 start-50 translate-middle">
                            <i class="fas fa-play-circle text-white" style="font-size: 3rem;"></i>
                        </div>
                    </div>
                <?php elseif($gallery->media_type === 'external_video' && $gallery->external_link): ?>
                    <div class="card-img-top position-relative" style="height: 250px; background: #000; cursor: pointer"
                         onclick="openYouTubeModal('<?php echo e($gallery->external_link); ?>', '<?php echo e($gallery->title); ?>')">
                        <?php
                            $youtubeId = null;
                            if (preg_match('/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/', $gallery->external_link, $matches)) {
                                $youtubeId = $matches[1];
                            }
                        ?>
                        <?php if($youtubeId): ?>
                            <img src="https://img.youtube.com/vi/<?php echo e($youtubeId); ?>/mqdefault.jpg" 
                                 class="w-100 h-100" style="object-fit: cover;" alt="<?php echo e($gallery->title); ?>">
                        <?php else: ?>
                            <div class="w-100 h-100 d-flex align-items-center justify-content-center bg-dark">
                                <i class="fas fa-video text-white" style="font-size: 3rem;"></i>
                            </div>
                        <?php endif; ?>
                        <div class="position-absolute top-50 start-50 translate-middle">
                            <i class="fas fa-play-circle text-white" style="font-size: 3rem;"></i>
                        </div>
                    </div>
                <?php else: ?>
                    
                    <div class="card-img-top d-flex align-items-center justify-content-center bg-light" style="height: 250px;">
                        <i class="fas fa-image text-muted" style="font-size: 3rem;"></i>
                    </div>
                <?php endif; ?>
                
                <div class="card-body">
                    <h5 class="card-title text-dark"><?php echo e($gallery->title); ?></h5>
                    <p class="card-text text-muted"><?php echo e(Str::limit($gallery->description, 100)); ?></p>
                    <span class="badge bg-primary">
                        <?php echo e($gallery->category == 'room' ? 'कोठा' : 
                           ($gallery->category == 'common_area' ? 'साझा क्षेत्र' : 
                           ($gallery->category == 'kitchen' ? 'भान्सा' : 
                           ($gallery->category == 'bathroom' ? 'स्नानागार' : 
                           ($gallery->category == 'garden' ? 'बगैंचा' : 
                           ($gallery->category == 'event' ? 'कार्यक्रम' : $gallery->category)))))); ?>

                    </span>
                </div>
            </div>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
        <div class="col-12">
            <div class="alert alert-info text-center">
                <h4>कुनै ग्यालरी आइटम फेला परेन</h4>
                <p>यो होस्टलले अझै कुनै तस्बिर वा भिडियो अपलोड गरेको छैन।</p>
                <a href="<?php echo e(route('hostels.show', $hostel->slug)); ?>" class="btn btn-primary mt-2">
                    होस्टलको पृष्ठमा फर्कनुहोस्
                </a>
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>


<div class="modal fade" id="imageModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTitle"></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="बन्द"></button>
            </div>
            <div class="modal-body text-center">
                <img id="modalImage" src="" alt="" class="img-fluid">
            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="videoModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="videoModalTitle"></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="बन्द"></button>
            </div>
            <div class="modal-body text-center">
                <video id="modalVideo" controls class="w-100">
                    Your browser does not support the video tag.
                </video>
            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="youTubeModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="youTubeModalTitle"></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="बन्द"></button>
            </div>
            <div class="modal-body text-center">
                <div class="ratio ratio-16x9">
                    <iframe id="youTubeIframe" src="" frameborder="0" allowfullscreen></iframe>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.gallery-item {
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}
.gallery-item:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.15) !important;
}
.card-img-top {
    border-bottom: 1px solid #eee;
}
.badge {
    font-size: 0.75rem;
}
</style>

<script>
// श्रेणी फिल्टर कार्यक्षमता
document.addEventListener('DOMContentLoaded', function() {
    const filterButtons = document.querySelectorAll('[data-filter]');
    const galleryItems = document.querySelectorAll('.gallery-item');
    
    filterButtons.forEach(button => {
        button.addEventListener('click', function() {
            const filter = this.getAttribute('data-filter');
            
            // सक्रिय बटन अपडेट गर्नुहोस्
            filterButtons.forEach(btn => btn.classList.remove('active'));
            this.classList.add('active');
            
            // आइटमहरू फिल्टर गर्नुहोस्
            galleryItems.forEach(item => {
                if (filter === 'all' || item.getAttribute('data-category') === filter) {
                    item.style.display = 'block';
                } else {
                    item.style.display = 'none';
                }
            });
        });
    });
});

// तस्बिर मोडल खोल्ने कार्य
function openImageModal(imageSrc, title) {
    document.getElementById('modalImage').src = imageSrc;
    document.getElementById('modalTitle').textContent = title;
    const imageModal = new bootstrap.Modal(document.getElementById('imageModal'));
    imageModal.show();
}

// भिडियो मोडल खोल्ने कार्य
function openVideoModal(videoSrc, title) {
    const video = document.getElementById('modalVideo');
    video.src = videoSrc;
    document.getElementById('videoModalTitle').textContent = title;
    const videoModal = new bootstrap.Modal(document.getElementById('videoModal'));
    videoModal.show();
}

// YouTube मोडल खोल्ने कार्य
function openYouTubeModal(youtubeLink, title) {
    let youtubeId = null;
    const regex = /(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/;
    const matches = youtubeLink.match(regex);
    
    if (matches && matches[1]) {
        youtubeId = matches[1];
    }
    
    if (youtubeId) {
        document.getElementById('youTubeIframe').src = `https://www.youtube.com/embed/${youtubeId}?autoplay=1`;
        document.getElementById('youTubeModalTitle').textContent = title;
        const youTubeModal = new bootstrap.Modal(document.getElementById('youTubeModal'));
        youTubeModal.show();
    } else {
        alert('Invalid YouTube link');
    }
}

// मोडल बन्द हुँदा भिडियो रोक्ने
document.addEventListener('DOMContentLoaded', function() {
    const videoModal = document.getElementById('videoModal');
    const youTubeModal = document.getElementById('youTubeModal');
    
    if (videoModal) {
        videoModal.addEventListener('hidden.bs.modal', function () {
            const video = document.getElementById('modalVideo');
            video.pause();
            video.currentTime = 0;
        });
    }
    
    if (youTubeModal) {
        youTubeModal.addEventListener('hidden.bs.modal', function () {
            document.getElementById('youTubeIframe').src = '';
        });
    }
});
</script>


<?php $__env->startSection('breadcrumb'); ?>
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="<?php echo e(route('home')); ?>">गृहपृष्ठ</a></li>
        <li class="breadcrumb-item"><a href="<?php echo e(route('hostels.index')); ?>">होस्टलहरू</a></li>
        <li class="breadcrumb-item"><a href="<?php echo e(route('hostels.show', $hostel->slug)); ?>"><?php echo e($hostel->name); ?></a></li>
        <li class="breadcrumb-item active">ग्यालरी</li>
    </ol>
</nav>
<?php $__env->stopSection(); ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.public', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\My Projects\HostelHub\resources\views/public/hostels/gallery.blade.php ENDPATH**/ ?>