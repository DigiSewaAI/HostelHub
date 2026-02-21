@extends('layouts.student')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card shadow-lg border-0">
                <!-- Card Header -->
                <div class="card-header bg-gradient-primary py-3" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h3 class="card-title mb-0 text-white">
                                <i class="fas fa-utensils me-2 text-white"></i>खानाको मेनु
                            </h3>
                            <p class="mb-0 text-white opacity-75">तपाईंको हस्टेलको खानाको साप्ताहिक योजना</p>
                        </div>
                        <div class="text-end">
                            <span class="badge bg-white text-primary fs-6 p-2 shadow" style="border: 1px solid #ffffff;">
                                <i class="fas fa-calendar-alt me-1"></i>
                                सप्ताह: {{ \Carbon\Carbon::now()->startOfWeek()->format('Y-m-d') }} देखि {{ \Carbon\Carbon::now()->endOfWeek()->format('Y-m-d') }} सम्म
                            </span>
                        </div>
                    </div>
                </div>
                
                <!-- Card Body -->
                <div class="card-body p-0">
                    <!-- Statistics Cards -->
                    <div class="row m-3">
                        <div class="col-xl-3 col-md-6 mb-3">
                            <div class="card border-left-primary shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                                कुल मेनुहरू
                                            </div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                                {{ $mealMenus->count() }}
                                            </div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-clipboard-list fa-2x text-primary"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-xl-3 col-md-6 mb-3">
                            <div class="card border-left-warning shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                                नास्ता (बिहान)
                                            </div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                                {{ $mealMenus->where('meal_type', 'breakfast')->count() }}
                                            </div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-sun fa-2x text-warning"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-xl-3 col-md-6 mb-3">
                            <div class="card border-left-info shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                                दिउँसोको खाना
                                            </div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                                {{ $mealMenus->where('meal_type', 'lunch')->count() }}
                                            </div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-utensil-spoon fa-2x text-info"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-xl-3 col-md-6 mb-3">
                            <div class="card border-left-success shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                                रात्रिको खाना
                                            </div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                                {{ $mealMenus->where('meal_type', 'dinner')->count() }}
                                            </div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-moon fa-2x text-success"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Filter Tabs -->
                    <div class="border-bottom">
                        <ul class="nav nav-tabs nav-fill" id="mealTab" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" id="all-tab" data-bs-toggle="tab" href="#all" role="tab">
                                    <i class="fas fa-list me-2"></i>सबै मेनुहरू
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="breakfast-tab" data-bs-toggle="tab" href="#breakfast" role="tab">
                                    <i class="fas fa-sun me-2"></i>नास्ता (बिहान)
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="lunch-tab" data-bs-toggle="tab" href="#lunch" role="tab">
                                    <i class="fas fa-utensil-spoon me-2"></i>दिउँसोको खाना
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="dinner-tab" data-bs-toggle="tab" href="#dinner" role="tab">
                                    <i class="fas fa-moon me-2"></i>रात्रिको खाना
                                </a>
                            </li>
                        </ul>
                    </div>

                    <!-- Tab Content -->
                    <div class="tab-content p-3" id="mealTabContent">
                        <!-- All Menus Tab -->
                        <div class="tab-pane fade show active" id="all" role="tabpanel">
                            @if($mealMenus->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-hover table-bordered">
                                    <thead class="bg-light">
                                        <tr>
                                            <th width="15%">दिन / मिति</th>
                                            <th width="15%">खानाको प्रकार</th>
                                            <th width="30%">खानाका वस्तुहरू</th>
                                            <th width="20%">तस्बिर</th>
                                            <th width="20%">विवरण</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($mealMenus as $menu)
                                        <tr>
                                            <!-- Day/Date Column -->
                                            <td>
                                                <div class="d-flex flex-column">
                                                    @php
                                                        $carbonDate = \Carbon\Carbon::parse($menu->date);
                                                        $dayMap = [
                                                            'Sunday' => 'आइतबार',
                                                            'Monday' => 'सोमबार', 
                                                            'Tuesday' => 'मंगलबार',
                                                            'Wednesday' => 'बुधबार',
                                                            'Thursday' => 'बिहिबार',
                                                            'Friday' => 'शुक्रबार',
                                                            'Saturday' => 'शनिबार'
                                                        ];
                                                        $nepaliDay = $dayMap[$carbonDate->format('l')] ?? $carbonDate->format('l');
                                                    @endphp
                                                    <strong class="text-primary">{{ $nepaliDay }}</strong>
                                                    <small class="text-muted">{{ $carbonDate->format('Y-m-d') }}</small>
                                                    <small class="text-info">
                                                        @if($menu->meal_type == 'breakfast')
                                                        <i class="fas fa-clock me-1"></i> ७:०० - ९:०० बिहान
                                                        @elseif($menu->meal_type == 'lunch')
                                                        <i class="fas fa-clock me-1"></i> १२:०० - २:०० दिउँसो
                                                        @else
                                                        <i class="fas fa-clock me-1"></i> ७:०० - ९:०० बेलुका
                                                        @endif
                                                    </small>
                                                </div>
                                            </td>
                                            
                                            <!-- Meal Type Column -->
                                            <td>
                                                <span class="badge 
                                                    @if($menu->meal_type == 'breakfast') 
                                                        bg-warning text-dark
                                                    @elseif($menu->meal_type == 'lunch') 
                                                        bg-info text-white
                                                    @else 
                                                        bg-success text-white
                                                    @endif 
                                                    p-2" style="font-size: 0.9rem;">
                                                    @if($menu->meal_type == 'breakfast')
                                                        <i class="fas fa-sun me-1"></i>नास्ता
                                                    @elseif($menu->meal_type == 'lunch')
                                                        <i class="fas fa-utensil-spoon me-1"></i>दिउँसो
                                                    @else
                                                        <i class="fas fa-moon me-1"></i>रात्रि
                                                    @endif
                                                </span>
                                            </td>
                                            
                                            <!-- Items Column -->
                                            <td class="text-dark">
                                                <div class="meal-items">
                                                    @if(is_array($menu->items))
                                                        @foreach($menu->items as $item)
                                                        <span class="d-inline-block mb-1 me-1">
                                                            <span class="badge bg-light text-dark border p-2">
                                                                <i class="fas fa-check text-success me-1"></i>
                                                                {{ $item }}
                                                            </span>
                                                        </span>
                                                        @endforeach
                                                    @else
                                                        <div class="p-2 bg-light rounded">
                                                            <i class="fas fa-list-ul me-2 text-primary"></i>
                                                            {{ $menu->items }}
                                                        </div>
                                                    @endif
                                                </div>
                                            </td>
                                            
                                            <!-- Image Column -->
                                            <td>
                                                @if($menu->image && file_exists(storage_path('app/public/' . $menu->image)))
                                                <div class="meal-image-container">
                                                    <img src="{{ asset('storage/' . $menu->image) }}" 
                                                         alt="Meal Image" 
                                                         class="img-fluid rounded shadow-sm meal-image" 
                                                         style="max-height: 100px; width: auto; cursor: pointer;"
                                                         data-src="{{ asset('storage/' . $menu->image) }}"
                                                         data-title="{{ $menu->meal_type }} - {{ $carbonDate->format('Y-m-d') }}">
                                                    <small class="d-block text-center text-muted mt-1">तस्बिर हेर्न क्लिक गर्नुहोस्</small>
                                                </div>
                                                @else
                                                <div class="text-center">
                                                    <div class="no-image-placeholder rounded bg-light d-flex align-items-center justify-content-center" 
                                                         style="height: 100px; width: 100%; border: 2px dashed #dee2e6;">
                                                        <div>
                                                            <i class="fas fa-image fa-2x text-muted mb-2"></i>
                                                            <p class="text-muted mb-0" style="font-size: 0.8rem;">तस्बिर उपलब्ध छैन</p>
                                                        </div>
                                                    </div>
                                                </div>
                                                @endif
                                            </td>
                                            
                                            <!-- Description Column -->
                                            <td>
                                                @if($menu->description)
                                                <div class="description-box p-3 bg-light rounded">
                                                    <p class="mb-0">{{ $menu->description }}</p>
                                                </div>
                                                @else
                                                <span class="text-muted">
                                                    <i class="fas fa-info-circle me-1"></i>
                                                    कुनै विवरण छैन
                                                </span>
                                                @endif
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            @else
                            <div class="text-center py-5">
                                <div class="mb-4">
                                    <i class="fas fa-utensils fa-4x text-muted opacity-25"></i>
                                </div>
                                <h4 class="text-muted mb-3">कुनै मेनु उपलब्ध छैन</h4>
                                <p class="text-muted mb-4">तपाईंको हस्टेलले अहिलेसम्म कुनै खानाको मेनु थपेको छैन।</p>
                            </div>
                            @endif
                        </div>
                        
                        <!-- Breakfast Tab -->
                        <div class="tab-pane fade" id="breakfast" role="tabpanel">
                            @include('student.partials.meal-table', [
                                'menus' => $mealMenus->where('meal_type', 'breakfast'),
                                'type' => 'breakfast',
                                'typeNepali' => 'नास्ता',
                                'icon' => 'sun',
                                'color' => 'warning'
                            ])
                        </div>
                        
                        <!-- Lunch Tab -->
                        <div class="tab-pane fade" id="lunch" role="tabpanel">
                            @include('student.partials.meal-table', [
                                'menus' => $mealMenus->where('meal_type', 'lunch'),
                                'type' => 'lunch',
                                'typeNepali' => 'दिउँसो',
                                'icon' => 'utensil-spoon',
                                'color' => 'info'
                            ])
                        </div>
                        
                        <!-- Dinner Tab -->
                        <div class="tab-pane fade" id="dinner" role="tabpanel">
                            @include('student.partials.meal-table', [
                                'menus' => $mealMenus->where('meal_type', 'dinner'),
                                'type' => 'dinner',
                                'typeNepali' => 'रात्रि',
                                'icon' => 'moon',
                                'color' => 'success'
                            ])
                        </div>
                    </div>
                </div>
                
                <!-- Card Footer -->
                <div class="card-footer bg-light">
                    <div class="row">
                        <div class="col-md-8">
                            <div class="d-flex flex-wrap align-items-center">
                                <div class="legend-item me-4 mb-2">
                                    <span class="badge bg-warning text-dark p-2 me-2"></span>
                                    <small>नास्ता (बिहान)</small>
                                </div>
                                <div class="legend-item me-4 mb-2">
                                    <span class="badge bg-info text-white p-2 me-2"></span>
                                    <small>दिउँसोको खाना</small>
                                </div>
                                <div class="legend-item mb-2">
                                    <span class="badge bg-success text-white p-2 me-2"></span>
                                    <small>रात्रिको खाना</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 text-end">
                            <small class="text-muted">
                                <i class="fas fa-history me-1"></i>
                                अन्तिम अपडेट: {{ \Carbon\Carbon::now()->format('Y-m-d h:i A') }}
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- CUSTOM IMAGE MODAL - NO Bootstrap classes -->
<div id="imageModalOverlay" class="modal-overlay" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.8); z-index: 9999;">
    <div class="modal-container" style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); background: white; border-radius: 8px; width: 90%; max-width: 800px; max-height: 90vh; overflow: hidden;">
        <div class="modal-header" style="background: #0d6efd; color: white; padding: 10px 15px; display: flex; justify-content: space-between; align-items: center;">
            <h5 class="modal-title" style="margin: 0; font-size: 1.1rem;" id="imageModalLabel">खानाको तस्बिर</h5>
            <button type="button" class="close-btn" style="background: none; border: none; color: white; font-size: 1.5rem; cursor: pointer; padding: 0; width: 30px; height: 30px; display: flex; align-items: center; justify-content: center;" id="closeModalBtn">
                &times;
            </button>
        </div>
        <div class="modal-body" style="padding: 20px; max-height: calc(90vh - 120px); overflow-y: auto; text-align: center;">
            <img id="modalImage" src="" alt="Meal Image" style="max-width: 100%; max-height: 65vh; object-fit: contain;">
        </div>
        <div class="modal-footer" style="padding: 15px; background: #f8f9fa; border-top: 1px solid #dee2e6; display: flex; justify-content: flex-end; gap: 10px;">
            <a id="modalDownload" href="#" class="btn btn-primary btn-sm" download style="padding: 8px 15px;">
                <i class="fas fa-download me-1"></i>डाउनलोड गर्नुहोस्
            </a>
            <button type="button" class="btn btn-secondary btn-sm" id="closeModalBtn2" style="padding: 8px 15px;">
                <i class="fas fa-times me-1"></i>बन्द गर्नुहोस्
            </button>
        </div>
    </div>
</div>

<!-- Custom CSS -->
<style>
    /* Card header white text */
    .card-header.bg-gradient-primary,
    .card-header.bg-gradient-primary *:not(.badge) {
        color: white !important;
    }
    
    .card-header.bg-gradient-primary .card-title,
    .card-header.bg-gradient-primary p,
    .card-header.bg-gradient-primary i:not(.badge i) {
        color: white !important;
    }
    
    /* Fixed badge visibility */
    .card-header .badge {
        background-color: white !important;
        color: #0d6efd !important;
        border: 1px solid #ffffff !important;
        box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    }
    
    /* Table styles */
    .table th {
        background-color: #f8f9fa;
        font-weight: 600;
        border-top: 1px solid #dee2e6;
    }
    
    .table-hover tbody tr:hover {
        background-color: rgba(13, 110, 253, 0.05);
        transition: all 0.3s;
    }
    
    /* Tabs styles */
    .nav-tabs {
        border-bottom: 1px solid #dee2e6;
    }
    
    .nav-tabs .nav-link {
        border: none;
        color: #6c757d;
        font-weight: 500;
        padding: 12px 20px;
        transition: all 0.3s;
    }
    
    .nav-tabs .nav-link:hover {
        color: #0d6efd;
        background-color: rgba(13, 110, 253, 0.05);
    }
    
    .nav-tabs .nav-link.active {
        color: #0d6efd;
        border-bottom: 3px solid #0d6efd;
        background-color: transparent;
        font-weight: 600;
    }
    
    /* Card border colors */
    .border-left-primary { border-left: 4px solid #0d6efd !important; }
    .border-left-warning { border-left: 4px solid #ffc107 !important; }
    .border-left-info { border-left: 4px solid #0dcaf0 !important; }
    .border-left-success { border-left: 4px solid #198754 !important; }
    
    /* Other styles */
    .meal-image-container img {
        transition: transform 0.3s;
    }
    
    .meal-image-container img:hover {
        transform: scale(1.05);
    }
    
    .description-box {
        border-left: 3px solid #6c757d;
        background: linear-gradient(to right, #f8f9fa, #ffffff);
    }
    
    .no-image-placeholder {
        transition: all 0.3s;
    }
    
    .no-image-placeholder:hover {
        background-color: #f1f3f4;
    }
    
    .meal-items .badge {
        transition: all 0.2s;
    }
    
    .meal-items .badge:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    }
    
    /* Custom Modal Styles */
    .modal-overlay {
        animation: fadeIn 0.3s ease;
    }
    
    .modal-container {
        animation: slideIn 0.3s ease;
    }
    
    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }
    
    @keyframes slideIn {
        from { 
            opacity: 0;
            transform: translate(-50%, -60%);
        }
        to { 
            opacity: 1;
            transform: translate(-50%, -50%);
        }
    }
    
    /* Responsive adjustments */
    @media (max-width: 768px) {
        .card-header .badge {
            font-size: 0.8rem !important;
            padding: 5px 10px !important;
            margin-top: 10px;
        }
        
        .table th, .table td {
            padding: 8px !important;
            font-size: 0.9rem;
        }
        
        .modal-container {
            width: 95%;
            max-width: 95%;
        }
        
        .modal-body img {
            max-height: 60vh !important;
        }
    }
</style>
<style>
    /* Fix for meal items badges - override global white badge color */
    .meal-items .badge.text-dark {
        color: #333333 !important;
    }
    /* Fix for the fallback div (bg-light) */
    .meal-items .bg-light.rounded {
        color: #333333 !important;
    }
</style>

<!-- JavaScript - CUSTOM MODAL (NO Bootstrap) -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Tab functionality - UNCHANGED
    const triggerTabList = document.querySelectorAll('#mealTab a');
    triggerTabList.forEach(function (triggerEl) {
        triggerEl.addEventListener('click', function (event) {
            event.preventDefault();
            const target = this.getAttribute('href');
            const activeTab = document.querySelector('#mealTab .nav-link.active');
            const activePane = document.querySelector('#mealTabContent .tab-pane.active');
            
            if (activeTab) activeTab.classList.remove('active');
            if (activePane) activePane.classList.remove('show', 'active');
            
            this.classList.add('active');
            document.querySelector(target).classList.add('show', 'active');
        });
    });
    
    // Custom Modal Elements
    const modalOverlay = document.getElementById('imageModalOverlay');
    const modalImage = document.getElementById('modalImage');
    const modalTitle = document.getElementById('imageModalLabel');
    const modalDownload = document.getElementById('modalDownload');
    const closeModalBtn = document.getElementById('closeModalBtn');
    const closeModalBtn2 = document.getElementById('closeModalBtn2');
    
    // Function to open modal
    function openCustomModal(imageSrc, title) {
        modalImage.src = imageSrc;
        modalTitle.textContent = title || 'खानाको तस्बिर';
        
        // Set download link
        const filename = imageSrc.split('/').pop() || 'meal-image.jpg';
        modalDownload.href = imageSrc;
        modalDownload.download = filename;
        
        // Show modal
        modalOverlay.style.display = 'block';
        document.body.style.overflow = 'hidden'; // Prevent background scroll
    }
    
    // Function to close modal
    function closeCustomModal() {
        modalOverlay.style.display = 'none';
        document.body.style.overflow = ''; // Restore scroll
    }
    
    // Event listeners for images in main table
    document.querySelectorAll('.meal-image').forEach(function(img) {
        img.addEventListener('click', function() {
            const imageSrc = this.getAttribute('data-src');
            const imageTitle = this.getAttribute('data-title') || 'खानाको तस्बिर';
            openCustomModal(imageSrc, imageTitle);
        });
    });
    
    // Event listeners for close buttons
    closeModalBtn.addEventListener('click', closeCustomModal);
    closeModalBtn2.addEventListener('click', closeCustomModal);
    
    // Close modal when clicking on overlay (outside modal)
    modalOverlay.addEventListener('click', function(event) {
        if (event.target === modalOverlay) {
            closeCustomModal();
        }
    });
    
    // ESC key to close modal
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape' && modalOverlay.style.display === 'block') {
            closeCustomModal();
        }
    });
    
    // Global function for partial view compatibility
    window.openImageModal = function(imageSrc, title) {
        openCustomModal(imageSrc, title);
    };

    // ✅ Download button functionality (यो भित्र राखिएको छ)
    if (modalDownload) {
        modalDownload.addEventListener('click', function(e) {
            e.preventDefault();
            const imageUrl = this.href;
            const filename = imageUrl.split('/').pop() || 'meal-image.jpg';
            
            fetch(imageUrl)
                .then(response => {
                    if (!response.ok) throw new Error('Network response was not ok');
                    return response.blob();
                })
                .then(blob => {
                    const blobUrl = window.URL.createObjectURL(blob);
                    const link = document.createElement('a');
                    link.href = blobUrl;
                    link.download = filename;
                    document.body.appendChild(link);
                    link.click();
                    document.body.removeChild(link);
                    window.URL.revokeObjectURL(blobUrl);
                })
                .catch(error => {
                    console.error('Download error:', error);
                    alert('डाउनलोड असफल भयो।');
                });
        });
    }
});
</script>
@endsection