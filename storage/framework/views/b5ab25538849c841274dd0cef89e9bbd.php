

<?php $__env->startSection('page-title', ($hostel->name ?? 'Sanctuary Girls Hostel') . ' - Premium Gallery | HostelHub'); ?>

<?php $__env->startSection('page-header', ($hostel->name ?? 'Sanctuary Girls Hostel') . ' Premium Gallery'); ?>
<?php $__env->startSection('page-description', 'हाम्रो होस्टलको विश्वस्तरीय सुविधाहरू, आधुनिक कोठाहरू, र रमाइलो विद्यार्थी जीवनको immersive experience'); ?>

<?php $__env->startSection('content'); ?>
<?php
    // Get gallery items from database
    $galleries = $hostel->galleries ?? collect();
    $featuredGalleries = $galleries->where('is_featured', true)->where('is_active', true);
    $activeGalleries = $galleries->where('is_active', true);
    
    // Get available rooms
    $availableRooms = $hostel->rooms->whereIn('status', ['उपलब्ध', 'आंशिक उपलब्ध']) ?? collect();
    
    // Count items by category for stats
    $categoryCounts = [
        'rooms' => $activeGalleries->whereIn('category', ['1 seater', '2 seater', '3 seater', '4 seater'])->count(),
        'kitchen' => $activeGalleries->where('category', 'kitchen')->count(),
        'facilities' => $activeGalleries->whereIn('category', ['bathroom', 'common', 'living room', 'study room'])->count(),
        'video' => $activeGalleries->whereIn('media_type', ['local_video', 'external_video'])->count()
    ];

    // For available rooms section - ONLY PHOTOS, NO VIDEOS
    $filteredGalleries = $galleries->where('is_active', true)
        ->where('media_type', 'photo') // Only photos, no videos
        ->filter(function($gallery) use ($availableRooms) {
            return in_array($gallery->category, ['1 seater', '2 seater', '3 seater', '4 seater']);
        });
    
    // FIXED: Ensure all values are integers, not collections
    $availableRoomCounts = [
        '1 seater' => $availableRooms->where('type', '1 seater')->count(),
        '2 seater' => $availableRooms->where('type', '2 seater')->count(),
        '3 seater' => $availableRooms->where('type', '3 seater')->count(),
        '4 seater' => $availableRooms->where('type', '4 seater')->count(),
        'other' => $availableRooms->whereNotIn('type', ['1 seater', '2 seater', '3 seater', '4 seater'])->count()
    ];

    // Calculate available beds for each room type
    $availableBedsCounts = [
        '1 seater' => $availableRooms->where('type', '1 seater')->sum('available_beds'),
        '2 seater' => $availableRooms->where('type', '2 seater')->sum('available_beds'),
        '3 seater' => $availableRooms->where('type', '3 seater')->sum('available_beds'),
        '4 seater' => $availableRooms->where('type', '4 seater')->sum('available_beds'),
        'other' => $availableRooms->whereNotIn('type', ['1 seater', '2 seater', '3 seater', '4 seater'])->sum('available_beds')
    ];

    // PERMANENT FIX: Nepali room types
    $nepaliRoomTypes = [
        '1 seater' => '१ सिटर',
        '2 seater' => '२ सिटर', 
        '3 seater' => '३ सिटर',
        '4 seater' => '४ सिटर',
        'other' => 'अन्य (५+ सिटर)'
    ];
    
    // FIXED: Now all values are integers, so array_sum will work
    $totalAvailableRooms = array_sum($availableRoomCounts);
    $hasAvailableRooms = $totalAvailableRooms > 0 && $filteredGalleries->count() > 0;
?>

<style>
    /* Gallery Specific Styles */
    .gallery-section {
        padding: 80px 0 60px;
    }
    
    .section-title {
        text-align: center;
        margin-bottom: 40px;
        font-size: 2.2rem;
        color: var(--text-dark);
        position: relative;
    }
    
    .section-title::after {
        content: '';
        position: absolute;
        bottom: -10px;
        left: 50%;
        transform: translateX(-50%);
        width: 80px;
        height: 3px;
        background: var(--secondary);
    }
    
    .gallery-filters {
        display: flex;
        justify-content: center;
        flex-wrap: wrap;
        gap: 12px;
        margin-bottom: 40px;
    }
    
    .filter-btn {
        padding: 10px 24px;
        background: white;
        border: 2px solid var(--border);
        border-radius: 30px;
        cursor: pointer;
        transition: all 0.3s;
        font-weight: 500;
        color: var(--text-dark);
    }
    
    .filter-btn.active, .filter-btn:hover {
        background: var(--primary);
        color: white;
        border-color: var(--primary);
    }
    
    .gallery-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
        gap: 25px;
        margin-bottom: 50px;
    }
    
    .gallery-item {
        position: relative;
        border-radius: var(--radius);
        overflow: hidden;
        box-shadow: var(--shadow);
        transition: transform 0.3s, box-shadow 0.3s;
        height: 280px;
        background: var(--light-bg);
    }
    
    .gallery-item:hover {
        transform: translateY(-8px);
        box-shadow: 0 15px 30px rgba(0, 0, 0, 0.15);
    }
    
    .gallery-item img, .gallery-item video {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.5s;
    }
    
    .gallery-item:hover img, .gallery-item:hover video {
        transform: scale(1.05);
    }
    
    .gallery-overlay {
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        background: linear-gradient(transparent, rgba(0, 0, 0, 0.8));
        color: white;
        padding: 25px 20px;
        transform: translateY(100%);
        transition: transform 0.3s;
    }
    
    .gallery-item:hover .gallery-overlay {
        transform: translateY(0);
    }
    
    .gallery-title {
        font-size: 1.3rem;
        margin-bottom: 8px;
        font-weight: 600;
    }
    
    .featured-badge {
        position: absolute;
        top: 15px;
        right: 15px;
        background: var(--accent);
        color: white;
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 600;
        z-index: 2;
    }

    .book-now-btn {
        position: absolute;
        bottom: 15px;
        right: 15px;
        background: var(--primary);
        color: white;
        padding: 8px 16px;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 600;
        text-decoration: none;
        z-index: 3;
        transition: background 0.3s;
    }

    .book-now-btn:hover {
        background: var(--secondary);
        color: white;
    }
    
    .view-more {
        text-align: center;
        margin-top: 40px;
        display: flex;
        justify-content: center;
        gap: 20px;
        flex-wrap: wrap;
    }
    
    /* Gallery Categories - Fixed Top Alignment */
    .gallery-categories {
        background: var(--bg-light);
        padding: 60px 0 80px;
        margin-top: 0;
        min-height: auto;
        display: block;
    }
    
    .main-description {
        text-align: center;
        margin: 0 auto 60px;
        color: var(--text-dark);
        font-size: 2rem;
        font-weight: 700;
        line-height: 1.3;
        max-width: 900px;
        padding: 40px 20px 0;
    }
    
    .category-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 25px;
        margin-top: 0;
        align-items: stretch;
    }
    
    .category-card {
        background: white;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
        transition: transform 0.3s, box-shadow 0.3s;
        text-align: center;
        padding: 30px 20px;
        height: 100%;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
    }
    
    .category-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
    }
    
    .category-icon {
        font-size: 2.5rem;
        margin-bottom: 20px;
        color: var(--primary);
    }
    
    .category-title {
        font-size: 1.3rem;
        margin-bottom: 12px;
        color: var(--text-dark);
        font-weight: 600;
    }
    
    .category-count {
        background: var(--primary);
        color: white;
        padding: 6px 15px;
        border-radius: 20px;
        font-size: 0.9rem;
        font-weight: 600;
        display: inline-block;
        margin-top: 12px;
    }
    
    /* Available Rooms Specific Styles - UPDATED */
    .available-rooms-section {
        padding: 80px 0 60px;
        background: var(--bg-light);
    }
    
    .availability-stats {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 30px;
        border-radius: 15px;
        margin-bottom: 40px;
        text-align: center;
    }
    
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(5, 1fr);
        gap: 15px;
        margin-top: 20px;
    }
    
    .stat-item {
        background: rgba(255, 255, 255, 0.2);
        padding: 20px;
        border-radius: 10px;
        backdrop-filter: blur(10px);
        transition: transform 0.3s;
        text-align: center;
    }

    .stat-item:hover {
        transform: translateY(-3px);
    }
    
    .stat-count {
        font-size: 2rem;
        font-weight: bold;
        color: white;
        display: block;
        margin-bottom: 5px;
    }
    
    .stat-label {
        color: rgba(255,255,255,0.9);
        font-size: 0.9rem;
        display: block;
        margin-bottom: 5px;
    }

    .stat-subtext {
        color: rgba(255,255,255,0.8);
        font-size: 0.8rem;
        display: block;
        margin-top: 5px;
    }
    
    .room-type-badge {
        position: absolute;
        top: 15px;
        left: 15px;
        background: var(--primary);
        color: white;
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 600;
        z-index: 2;
    }
    
    .available-badge {
        position: absolute;
        top: 15px;
        right: 15px;
        background: #10b981;
        color: white;
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 600;
        z-index: 2;
    }
    
    .full-gallery-cta {
        text-align: center;
        margin-top: 60px;
        padding: 40px;
        background: white;
        border-radius: 12px;
        box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
    }

    /* No Rooms Message Styles */
    .no-rooms-message {
        text-align: center;
        padding: 80px 20px;
        background: #f8f9fa;
        border-radius: 15px;
        margin: 40px 0;
    }
    
    .no-rooms-icon {
        font-size: 4rem;
        color: #6c757d;
        margin-bottom: 20px;
    }
    
    /* Modal Styles */
    .gallery-modal {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.95);
        z-index: 1100;
        justify-content: center;
        align-items: center;
        padding: 20px;
    }
    
    .modal-content {
        max-width: 90%;
        max-height: 90%;
        position: relative;
        border-radius: var(--radius);
        overflow: hidden;
        background: black;
    }
    
    .modal-content img, .modal-content video {
        width: 100%;
        height: auto;
        display: block;
    }
    
    .close-modal {
        position: absolute;
        top: 15px;
        right: 15px;
        color: white;
        font-size: 2rem;
        cursor: pointer;
        background: rgba(0, 0, 0, 0.7);
        width: 45px;
        height: 45px;
        border-radius: 50%;
        display: flex;
        justify-content: center;
        align-items: center;
        z-index: 10;
        transition: background 0.3s;
    }
    
    .close-modal:hover {
        background: rgba(0, 0, 0, 0.9);
    }
    
    .modal-caption {
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        background: rgba(0, 0, 0, 0.8);
        color: white;
        padding: 20px;
        transform: translateY(100%);
        transition: transform 0.3s;
    }
    
    .modal-content:hover .modal-caption {
        transform: translateY(0);
    }
    
    /* Responsive Design */
    @media (max-width: 1200px) {
        .category-grid {
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
        }

        .stats-grid {
            grid-template-columns: repeat(3, 1fr);
        }
    }
    
    @media (max-width: 768px) {
        .gallery-grid {
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 20px;
        }
        
        .gallery-item {
            height: 240px;
        }
        
        .category-grid {
            grid-template-columns: 1fr;
            gap: 20px;
        }
        
        .gallery-section {
            padding: 60px 0 40px;
        }
        
        .gallery-categories {
            padding: 40px 0 60px;
        }
        
        .view-more {
            flex-direction: column;
            align-items: center;
        }
        
        .main-description {
            font-size: 1.6rem;
            margin-bottom: 40px;
            padding: 20px 15px 0;
        }
        
        .category-icon {
            font-size: 2.2rem;
        }
        
        .category-title {
            font-size: 1.2rem;
        }

        .stats-grid {
            grid-template-columns: repeat(2, 1fr);
        }

        .available-rooms-section {
            padding: 60px 0 40px;
        }
    }
    
    @media (max-width: 480px) {
        .gallery-grid {
            grid-template-columns: 1fr;
        }
        
        .gallery-item {
            height: 220px;
        }
        
        .gallery-filters {
            gap: 8px;
        }
        
        .filter-btn {
            padding: 8px 12px;
            font-size: 0.8rem;
        }
        
        .main-description {
            font-size: 1.4rem;
            padding: 15px 10px 0;
        }
        
        .gallery-categories {
            padding: 30px 0 40px;
        }
        
        .category-card {
            padding: 25px 15px;
        }
        
        .category-icon {
            font-size: 2rem;
        }

        .stats-grid {
            grid-template-columns: 1fr;
        }
        
        .stat-item {
            padding: 15px;
        }
        
        .availability-stats {
            padding: 20px 15px;
        }
    }
</style>

<!-- Gallery Categories -->
<section class="gallery-categories">
    <div class="container">
        <div class="main-description nepali">
            हाम्रो होस्टलको सुन्दर वातावरण र उत्कृष्ट सुविधाहरूको दृश्यात्मक अनुभव
        </div>
        
        <div class="category-grid">
            <div class="category-card">
                <div class="category-icon">🛏️</div>
                <h3 class="category-title nepali">कोठाहरू</h3>
                <p class="nepali">१, २, ३ र ४ सिटर कोठाहरू</p>
                <span class="category-count nepali"><?php echo e($categoryCounts['rooms']); ?> फोटोहरू</span>
            </div>
            
            <div class="category-card">
                <div class="category-icon">🍳</div>
                <h3 class="category-title nepali">किचन</h3>
                <p class="nepali">आधुनिक किचन सुविधा</p>
                <span class="category-count nepali"><?php echo e($categoryCounts['kitchen']); ?> फोटोहरू</span>
            </div>
            
            <div class="category-card">
                <div class="category-icon">🚽</div>
                <h3 class="category-title nepali">अन्य सुविधाहरू</h3>
                <p class="nepali">अध्ययन क्षेत्र, शौचालय, र अन्य</p>
                <span class="category-count nepali"><?php echo e($categoryCounts['facilities']); ?> फोटोहरू</span>
            </div>
            
            <div class="category-card">
                <div class="category-icon">🎬</div>
                <h3 class="category-title nepali">भिडियो टुर</h3>
                <p class="nepali">होस्टलको पूर्ण टुर</p>
                <span class="category-count nepali"><?php echo e($categoryCounts['video']); ?> भिडियोहरू</span>
            </div>
        </div>
    </div>
</section>

<!-- Available Rooms Section - UPDATED (ONLY AVAILABLE ROOM IMAGES, NO VIDEOS) -->
<section class="available-rooms-section">
    <div class="container">
        <!-- Availability Statistics -->
        <div class="availability-stats">
            <h2 class="nepali" style="color: white; margin-bottom: 10px;">कोठा उपलब्धता</h2>
            <p class="nepali" style="color: rgba(255,255,255,0.9); margin-bottom: 30px;">
                हाल उपलब्ध कोठाहरूको विवरण
            </p>
            
            <div class="stats-grid">
                <?php $__currentLoopData = $nepaliRoomTypes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $englishType => $nepaliType): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="stat-item">
                    <span class="stat-count"><?php echo e($availableRoomCounts[$englishType] ?? 0); ?></span>
                    <span class="stat-label nepali"><?php echo e($nepaliType); ?></span>
                    <?php if(isset($availableRoomCounts[$englishType]) && $availableRoomCounts[$englishType] > 0): ?>
                        <?php
                            $roomsOfType = $hostel->rooms->where('type', $englishType);
                            $totalAvailableBeds = $roomsOfType->sum('available_beds');
                        ?>
                        <span class="stat-subtext nepali">(<?php echo e($totalAvailableBeds); ?> बेड खाली)</span>
                    <?php endif; ?>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>

        <?php if($hasAvailableRooms): ?>
            <h2 class="section-title nepali">हाल उपलब्ध कोठाहरू</h2>
            <p style="text-align: center; margin-bottom: 40px; color: var(--text-dark); opacity: 0.8; max-width: 700px; margin-left: auto; margin-right: auto;" class="nepali">
                तल दिइएका कोठाहरू हाल उपलब्ध छन्। तपाईंको रुचिको कोठा चयन गरी अहिलेै बुक गर्नुहोस्।
            </p>
            
            <!-- Available Rooms Gallery - ONLY PHOTOS -->
            <div class="gallery-grid">
                <?php $__currentLoopData = $filteredGalleries; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $gallery): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php
                        $roomCategory = $gallery->category;
                        $availableCount = $availableRoomCounts[$roomCategory] ?? 0;
                        
                        // Find the room associated with this gallery
                        $room = $hostel->rooms->where('type', $roomCategory)->first();
                        $availableBeds = $room ? $room->available_beds : 0;
                    ?>
                    
                    <div class="gallery-item">
                        <img src="<?php echo e($gallery->thumbnail_url ?? $gallery->media_url); ?>" 
                             alt="<?php echo e($gallery->title); ?>" 
                             onerror="this.src='<?php echo e(asset('images/default-room.jpg')); ?>'">
                        
                        <div class="room-type-badge nepali">
                            <?php echo e($nepaliRoomTypes[$roomCategory] ?? $roomCategory); ?>

                        </div>
                        
                        <!-- UPDATED: Available badge with bed count -->
                        <div class="available-badge nepali">
                            <?php if($availableBeds > 0): ?>
                                <?php echo e($availableBeds); ?> बेड खाली
                            <?php else: ?>
                                पूर्ण व्यस्त
                            <?php endif; ?>
                        </div>
                        
                        <a href="<?php echo e(route('contact')); ?>?room_type=<?php echo e($roomCategory); ?>&hostel=<?php echo e($hostel->slug); ?>" 
                           class="book-now-btn nepali">
                            बुक गर्नुहोस्
                        </a>
                        
                        <div class="gallery-overlay">
                            <h3 class="gallery-title nepali"><?php echo e($gallery->title); ?></h3>
                            <p class="nepali"><?php echo e($gallery->description); ?></p>
                            <button class="btn btn-primary" 
                                    style="margin-top: 12px; padding: 8px 16px; font-size: 0.9rem;" 
                                    onclick="openRoomModal('<?php echo e($gallery->id); ?>')">
                                विस्तृत हेर्नुहोस्
                            </button>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
            
            <!-- Navigation Buttons -->
            <div class="view-more">
                <a href="<?php echo e(route('hostel.full-gallery', $hostel->slug)); ?>" class="btn btn-outline nepali" 
                   style="border-color: var(--primary); color: var(--primary);">
                    पूरा ग्यालरी हेर्नुहोस्
                </a>
                <a href="<?php echo e(route('contact')); ?>?hostel=<?php echo e($hostel->slug); ?>" class="btn btn-primary nepali">
                    अहिले बुक गर्नुहोस्
                </a>
            </div>
            
        <?php else: ?>
            <!-- No Available Rooms Message -->
            <div class="no-rooms-message">
                <div class="no-rooms-icon">🏠</div>
                <h3 class="nepali" style="color: var(--text-dark); margin-bottom: 15px;">हाल कुनै कोठा उपलब्ध छैन</h3>
                <p class="nepali" style="color: var(--text-dark); opacity: 0.8; margin-bottom: 25px;">
                    माफ गर्नुहोस्, हाल यस होस्टलमा कुनै कोठा उपलब्ध छैन।<br>
                    कृपया पछि फेरी जाँच गर्नुहोस् वा हाम्रो अन्य होस्टलहरू हेर्नुहोस्।
                </p>
                <div class="view-more">
                    <a href="<?php echo e(route('hostels.index')); ?>" class="btn btn-primary nepali">
                        अन्य होस्टलहरू हेर्नुहोस्
                    </a>
                    <a href="<?php echo e(route('contact')); ?>" class="btn btn-outline nepali">
                        सम्पर्क गर्नुहोस्
                    </a>
                </div>
            </div>
        <?php endif; ?>
    </div>
</section>

<!-- Room Detail Modal -->
<div class="gallery-modal" id="roomModal">
    <div class="modal-content">
        <span class="close-modal" onclick="closeModal()">&times;</span>
        <img id="modalRoomImage" src="" alt="">
        <div class="modal-caption">
            <h3 id="modalRoomTitle" class="nepali"></h3>
            <p id="modalRoomDescription" class="nepali"></p>
            <div id="modalRoomDetails" class="nepali" style="margin-top: 10px;"></div>
            <a href="#" id="modalBookButton" class="btn btn-accent nepali" style="margin-top: 15px;">
                यो कोठा बुक गर्नुहोस्
            </a>
        </div>
    </div>
</div>

<script>
    // Room gallery data
    const roomGalleryData = {
        <?php $__currentLoopData = $filteredGalleries; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $gallery): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        '<?php echo e($gallery->id); ?>': {
            title: '<?php echo e($gallery->title); ?>',
            description: '<?php echo e($gallery->description); ?>',
            media_url: '<?php echo e($gallery->media_url); ?>',
            room_type: '<?php echo e($gallery->category); ?>',
            available_count: <?php echo e($availableRoomCounts[$gallery->category] ?? 0); ?>,
            nepali_type: {
                '1 seater': '१ सिटर',
                '2 seater': '२ सिटर',
                '3 seater': '३ सिटर', 
                '4 seater': '४ सिटर'
            }['<?php echo e($gallery->category); ?>'] || '<?php echo e($gallery->category); ?>'
        },
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    };

    function openRoomModal(galleryId) {
        const room = roomGalleryData[galleryId];
        if (!room) return;

        document.getElementById('roomModal').style.display = 'flex';
        document.getElementById('modalRoomImage').src = room.media_url;
        document.getElementById('modalRoomTitle').textContent = room.title;
        document.getElementById('modalRoomDescription').textContent = room.description;
        
        // Room details
        const detailsHtml = `
            <strong>कोठाको प्रकार:</strong> ${room.nepali_type}<br>
            <strong>उपलब्धता:</strong> ${room.available_count} कोठा उपलब्ध
        `;
        document.getElementById('modalRoomDetails').innerHTML = detailsHtml;
        
        // Book button
        document.getElementById('modalBookButton').href = 
            "<?php echo e(route('contact')); ?>?room_type=" + room.room_type + "&hostel=<?php echo e($hostel->slug); ?>";
    }
    
    function closeModal() {
        document.getElementById('roomModal').style.display = 'none';
    }
    
    // Close modal when clicking outside the content
    window.addEventListener('click', function(event) {
        const roomModal = document.getElementById('roomModal');
        
        if (event.target === roomModal) {
            roomModal.style.display = 'none';
        }
    });
    
    // Close modal with Escape key
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            closeModal();
        }
    });

    // Simple gallery item hover effect
    document.addEventListener('DOMContentLoaded', function() {
        const galleryItems = document.querySelectorAll('.gallery-item');
        
        galleryItems.forEach(item => {
            item.addEventListener('mouseenter', function() {
                this.style.transform = 'translateY(-8px)';
            });
            
            item.addEventListener('mouseleave', function() {
                this.style.transform = 'translateY(0)';
            });
        });
    });
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.frontend', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\My Projects\HostelHub\resources\views\public\hostels\gallery.blade.php ENDPATH**/ ?>