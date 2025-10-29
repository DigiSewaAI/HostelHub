<?php $__env->startPush('head'); ?>
<style>
    :root {
        --theme-color: <?php echo e($hostel->theme_color ?? '#3b82f6'); ?>;
        --primary-color: <?php echo e($hostel->theme_color ?? '#3b82f6'); ?>;
    }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('page-title', $hostel->name); ?>
<?php $__env->startSection('page-description', $hostel->description ? \Illuminate\Support\Str::limit($hostel->description, 160) : 'होस्टलको विवरण'); ?>

<?php $__env->startSection('content'); ?>
<?php
  // ✅ FIXED: Use normalized variables from controllers
  // $logo and $facilities are now prepared by controllers
  
  // Use the correct counts from relationships
  $avgRating = $hostel->approved_reviews_avg_rating ?? 0;
  $reviewCount = $hostel->approved_reviews_count ?? 0;
  $studentCount = $hostel->students_count ?? 0;

  // थीम स्विचिंग लजिक
  $theme = $hostel->theme ?? 'default';
  $themeFile = "public.hostels.themes.{$theme}";
  if (!view()->exists($themeFile)) {
      $themeFile = 'public.hostels.themes.modern';
  }
?>

<?php if($theme === 'default'): ?>
<style>
.whitespace-pre-line {
  white-space: pre-line;
}

.smooth-transition {
  transition: all 0.3s ease-in-out;
}

.pro-card {
  background: white;
  border-radius: 16px;
  box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
  border: 1px solid #e2e8f0;
  overflow: hidden;
}

.pro-card-header {
  background: linear-gradient(135deg, var(--theme-color) 0%, #764ba2 100%);
  color: white;
  padding: 20px;
}

.pro-card-body {
  padding: 24px;
}

.stat-card {
  background: linear-gradient(135deg, var(--theme-color) 0%, #7c3aed 100%);
  color: white;
  border-radius: 12px;
  padding: 20px;
  text-align: center;
  box-shadow: 0 8px 25px rgba(79, 70, 229, 0.3);
  min-width: 120px;
}

.facility-chip {
  background: #f0f9ff;
  border: 1px solid #e0f2fe;
  border-radius: 20px;
  padding: 10px 16px;
  display: flex;
  align-items: center;
  gap: 8px;
  font-weight: 500;
  color: #0369a1;
}

.contact-item {
  background: #f8fafc;
  border-radius: 12px;
  padding: 16px;
  border-left: 4px solid var(--theme-color);
  margin-bottom: 12px;
}

.review-card {
  background: #fafafa;
  border-radius: 12px;
  padding: 20px;
  border: 1px solid #e5e7eb;
  margin-bottom: 16px;
}

/* Social Media Icons */
.social-media-buttons {
  display: flex;
  gap: 10px;
  flex-wrap: wrap;
  margin-bottom: 15px;
}

.social-icon {
  width: 40px;
  height: 40px;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  color: white;
  text-decoration: none;
  transition: all 0.3s ease;
  font-size: 16px;
}

.social-icon:hover {
  transform: translateY(-2px);
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
}

.facebook-bg { background: #3b5998; }
.instagram-bg { background: #e4405f; }
.twitter-bg { background: #1da1f2; }
.tiktok-bg { background: #000000; }
.whatsapp-bg { background: #25d366; }
.youtube-bg { background: #ff0000; }
.linkedin-bg { background: #0077b5; }

/* UPDATED: Phone button size fixed */
.btn-phone-custom {
  background: linear-gradient(135deg, #10b981 0%, #059669 100%);
  color: white;
  border: none;
  border-radius: 10px;
  padding: 10px 18px;
  font-weight: 600;
  transition: all 0.3s ease;
  box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
  white-space: nowrap;
  font-size: 14px;
  display: inline-flex;
  align-items: center;
  gap: 8px;
  text-decoration: none;
}

.btn-phone-custom:hover {
  transform: translateY(-2px);
  box-shadow: 0 6px 20px rgba(16, 185, 129, 0.4);
}

.hostel-logo-container {
  width: 100px;
  height: 100px;
  border-radius: 20px;
  overflow: hidden;
  border: 4px solid white;
  box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
}

.hostel-logo-container img {
  width: 100%;
  height: 100%;
  object-fit: cover;
}

.rating-stars {
  display: flex;
  align-items: center;
  gap: 4px;
}

.star-filled {
  color: #fbbf24;
}

.star-empty {
  color: #d1d5db;
}

/* Action buttons in sidebar */
.action-buttons {
  display: flex;
  flex-direction: column;
  gap: 16px;
}

.action-button {
  width: 100%;
  padding: 16px 24px;
  border-radius: 12px;
  font-weight: 600;
  text-align: center;
  transition: all 0.3s ease;
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 12px;
  text-decoration: none;
}

.action-button:hover {
  transform: translateY(-2px);
  box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
}

/* Left actions section */
.left-actions {
  margin-top: 20px;
}

/* Mobile responsiveness */
@media (max-width: 768px) {
  .left-actions {
    align-items: center;
  }
  
  .btn-phone-custom {
    padding: 8px 16px;
    font-size: 13px;
  }
  
  .social-media-buttons {
    justify-content: center;
  }
}

/* 🆕 ENHANCED GALLERY SECTION - FULL HEIGHT TO BOTTOM */
.gallery-full-height {
  min-height: 700px; /* Increased height to reach bottom */
  background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
  border-radius: 16px;
  padding: 30px;
  margin-bottom: 30px;
  display: flex;
  flex-direction: column;
  position: relative;
}

.gallery-content {
  flex: 1;
  display: flex;
  flex-direction: column;
  gap: 20px;
}

.gallery-grid-enhanced {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
  gap: 20px;
  flex: 1;
}

.gallery-item-enhanced {
  border-radius: 12px;
  overflow: hidden;
  box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
  transition: all 0.3s ease;
  background: white;
  border: 1px solid #e2e8f0;
  position: relative;
  height: 250px;
}

.gallery-item-enhanced:hover {
  transform: translateY(-5px);
  box-shadow: 0 8px 30px rgba(0, 0, 0, 0.15);
}

.gallery-item-enhanced img {
  width: 100%;
  height: 100%;
  object-fit: cover;
}

.gallery-placeholder-enhanced {
  width: 100%;
  height: 100%;
  background: linear-gradient(135deg, #e2e8f0, #cbd5e1);
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  color: #64748b;
}

/* 🆕 FIXED: "Purai Gallery Hernuhos" Button at Bottom */
.gallery-footer {
  margin-top: auto;
  padding-top: 20px;
  border-top: 1px solid #e2e8f0;
  text-align: center;
}

.view-full-gallery-btn {
  background: linear-gradient(135deg, var(--theme-color), #7c3aed);
  color: white;
  padding: 12px 24px;
  border-radius: 8px;
  text-decoration: none;
  font-weight: 600;
  font-size: 14px;
  transition: all 0.3s ease;
  display: inline-flex;
  align-items: center;
  gap: 8px;
}

.view-full-gallery-btn:hover {
  transform: translateY(-2px);
  box-shadow: 0 8px 25px rgba(79, 70, 229, 0.3);
}
</style>

<!-- Professional Hero Section -->
<section class="bg-gradient-to-br from-blue-50 to-indigo-100 py-8">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Preview Alert -->
    <?php if(isset($preview) && $preview): ?>
      <div class="pro-card max-w-4xl mx-auto mb-6 border-l-4 border-yellow-400">
        <div class="flex items-center justify-between p-4">
          <div class="flex items-center space-x-3">
            <div class="w-8 h-8 bg-yellow-100 rounded-full flex items-center justify-center">
              <i class="fas fa-eye text-yellow-600 text-sm"></i>
            </div>
            <span class="text-yellow-800 font-medium nepali">यो पूर्वावलोकन मोडमा हो</span>
          </div>
          <a href="<?php echo e(route('owner.public-page.edit')); ?>" 
             class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 smooth-transition nepali text-sm">
            <i class="fas fa-edit mr-2"></i>सम्पादन गर्नुहोस्
          </a>
        </div>
      </div>
    <?php endif; ?>

    <!-- Professional Hostel Header -->
    <div class="pro-card max-w-7xl mx-auto">
      <div class="pro-card-header">
        <div class="flex flex-col lg:flex-row items-center justify-between gap-6">
          <!-- Logo and Basic Info -->
          <div class="flex flex-col items-start">
            <div class="flex items-center space-x-6">
              <div class="hostel-logo-container">
                <?php if($logo): ?>
                  <img src="<?php echo e($logo); ?>" alt="<?php echo e($hostel->name); ?>" class="w-full h-full object-cover">
                <?php else: ?>
                  <div class="w-full h-full bg-gradient-to-br from-green-400 to-blue-500 flex items-center justify-center">
                    <i class="fas fa-building text-white text-2xl"></i>
                  </div>
                <?php endif; ?>
              </div>
              <div class="text-white">
                <h1 class="text-3xl font-bold nepali mb-2"><?php echo e($hostel->name); ?></h1>
                <div class="flex items-center space-x-6">
                  <div class="flex items-center space-x-2 bg-white/20 px-3 py-1 rounded-full">
                    <i class="fas fa-map-marker-alt text-sm"></i>
                    <span class="text-sm nepali font-medium"><?php echo e($hostel->city ?? 'काठमाडौं'); ?></span>
                  </div>
                  <?php if($reviewCount > 0 && $avgRating > 0): ?>
                    <div class="flex items-center space-x-2 bg-white/20 px-3 py-1 rounded-full">
                      <div class="rating-stars">
                        <?php for($i = 1; $i <= 5; $i++): ?>
                          <i class="fas fa-star <?php echo e($i <= round($avgRating) ? 'star-filled' : 'star-empty'); ?> text-sm"></i>
                        <?php endfor; ?>
                      </div>
                      <span class="text-sm font-bold"><?php echo e(number_format($avgRating, 1)); ?></span>
                      <span class="text-sm nepali">(<?php echo e($reviewCount); ?>)</span>
                    </div>
                  <?php endif; ?>
                </div>
              </div>
            </div>

            <!-- UPDATED: MOVED TO LEFT SIDE - Social Media and Phone Button -->
            <div class="left-actions">
              <!-- Follow Us Text -->
              <div class="w-full">
                <p class="text-white text-sm font-medium nepali opacity-90 mb-3">
                  Social Media मा हामीलाई follow गर्न सक्नुहुन्छ
                </p>
              </div>

              <!-- Dynamic Social Media Icons from Database -->
              <div class="social-media-buttons">
                <?php if($hostel->facebook_url): ?>
                  <a href="<?php echo e($hostel->facebook_url); ?>" target="_blank" class="social-icon facebook-bg" title="Facebook">
                    <i class="fab fa-facebook-f"></i>
                  </a>
                <?php endif; ?>
                
                <?php if($hostel->instagram_url): ?>
                  <a href="<?php echo e($hostel->instagram_url); ?>" target="_blank" class="social-icon instagram-bg" title="Instagram">
                    <i class="fab fa-instagram"></i>
                  </a>
                <?php endif; ?>
                
                <?php if($hostel->twitter_url): ?>
                  <a href="<?php echo e($hostel->twitter_url); ?>" target="_blank" class="social-icon twitter-bg" title="Twitter">
                    <i class="fab fa-twitter"></i>
                  </a>
                <?php endif; ?>
                
                <?php if($hostel->tiktok_url): ?>
                  <a href="<?php echo e($hostel->tiktok_url); ?>" target="_blank" class="social-icon tiktok-bg" title="TikTok">
                    <i class="fab fa-tiktok"></i>
                  </a>
                <?php endif; ?>
                
                <?php if($hostel->whatsapp_number): ?>
                  <a href="https://wa.me/<?php echo e($hostel->whatsapp_number); ?>" target="_blank" class="social-icon whatsapp-bg" title="WhatsApp">
                    <i class="fab fa-whatsapp"></i>
                  </a>
                <?php endif; ?>
                
                <?php if($hostel->youtube_url): ?>
                  <a href="<?php echo e($hostel->youtube_url); ?>" target="_blank" class="social-icon youtube-bg" title="YouTube">
                    <i class="fab fa-youtube"></i>
                  </a>
                <?php endif; ?>
                
                <?php if($hostel->linkedin_url): ?>
                  <a href="<?php echo e($hostel->linkedin_url); ?>" target="_blank" class="social-icon linkedin-bg" title="LinkedIn">
                    <i class="fab fa-linkedin-in"></i>
                  </a>
                <?php endif; ?>
              </div>

              <!-- UPDATED: Phone Button with proper size -->
              <?php if($hostel->contact_phone): ?>
                <a href="tel:<?php echo e($hostel->contact_phone); ?>" 
                   class="btn-phone-custom nepali">
                  <i class="fas fa-phone text-xs"></i>
                  <span>फोन गर्नुहोस्</span>
                </a>
              <?php endif; ?>
            </div>
          </div>
        </div>
      </div>
      
      <div class="pro-card-body">
        <!-- Quick Info Bar -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
          <div class="text-center p-4 bg-gray-50 rounded-lg">
            <div class="text-xl font-bold text-blue-600"><?php echo e($hostel->total_rooms ?? 0); ?></div>
            <div class="text-sm text-gray-600 nepali">कुल कोठा</div>
          </div>
          <div class="text-center p-4 bg-gray-50 rounded-lg">
            <div class="text-xl font-bold text-green-600"><?php echo e($hostel->available_rooms ?? 0); ?></div>
            <div class="text-sm text-gray-600 nepali">उपलब्ध कोठा</div>
          </div>
          <div class="text-center p-4 bg-gray-50 rounded-lg">
            <div class="text-xl font-bold text-purple-600"><?php echo e($studentCount); ?></div>
            <div class="text-sm text-gray-600 nepali">विद्यार्थी</div>
          </div>
          <div class="text-center p-4 bg-gray-50 rounded-lg">
            <div class="text-xl font-bold text-orange-600"><?php echo e($reviewCount); ?></div>
            <div class="text-sm text-gray-600 nepali">समीक्षा</div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Main Content -->
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
  <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
    <!-- Left Column - Main Content (3/4 width) -->
    <div class="lg:col-span-3 space-y-8">
      <!-- About Section -->
      <section class="pro-card">
        <div class="pro-card-body">
          <h2 class="text-2xl font-bold text-gray-900 nepali mb-6 flex items-center gap-3">
            <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
              <i class="fas fa-info-circle text-blue-600"></i>
            </div>
            हाम्रो बारेमा
          </h2>
          <div class="prose max-w-none">
            <?php if($hostel->description): ?>
              <p class="text-gray-700 leading-relaxed nepali whitespace-pre-line text-base">
                <?php echo e($hostel->description); ?>

              </p>
            <?php else: ?>
              <div class="text-center py-8 text-gray-500">
                <i class="fas fa-file-alt text-4xl mb-3 opacity-50"></i>
                <p class="nepali italic">यस होस्टलको बारेमा विवरण उपलब्ध छैन।</p>
              </div>
            <?php endif; ?>
          </div>
        </div>
      </section>

      <!-- 🆕 ENHANCED GALLERY SECTION - FULL HEIGHT TO BOTTOM -->
      <section class="gallery-full-height">
        <h2 class="text-2xl font-bold text-gray-900 nepali mb-6 flex items-center gap-3">
          <div class="w-10 h-10 bg-purple-100 rounded-full flex items-center justify-center">
            <i class="fas fa-images text-purple-600"></i>
          </div>
          हाम्रो ग्यालरी
        </h2>
        
        <div class="gallery-content">
          <p class="text-gray-600 text-center nepali text-base mb-6">
            हाम्रो होस्टलको सुन्दर तस्बिरहरू र भिडियोहरू हेर्नुहोस्
          </p>
          
          <div class="gallery-grid-enhanced">
            <?php
                $galleries = $hostel->activeGalleries ?? collect();
            ?>
            
            <?php if($galleries->count() > 0): ?>
              <?php $__currentLoopData = $galleries; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $gallery): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="gallery-item-enhanced group">
                  <?php if($gallery->media_type === 'image'): ?>
                    <img src="<?php echo e($gallery->thumbnail_url); ?>" 
                         alt="<?php echo e($gallery->title); ?>"
                         class="w-full h-full object-cover">
                  <?php elseif($gallery->media_type === 'external_video'): ?>
                    <div class="gallery-placeholder-enhanced">
                      <i class="fab fa-youtube text-4xl mb-3"></i>
                      <span class="nepali text-sm">YouTube भिडियो</span>
                    </div>
                  <?php else: ?>
                    <div class="gallery-placeholder-enhanced">
                      <i class="fas fa-video text-4xl mb-3"></i>
                      <span class="nepali text-sm">भिडियो</span>
                    </div>
                  <?php endif; ?>
                  
                  <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-70 transition-all duration-300 flex items-center justify-center p-4">
                    <div class="text-white text-center transform translate-y-4 group-hover:translate-y-0 transition-transform duration-300">
                      <h4 class="font-semibold text-lg mb-2 nepali"><?php echo e($gallery->title); ?></h4>
                      <?php if($gallery->description): ?>
                        <p class="text-sm opacity-90 nepali"><?php echo e(Str::limit($gallery->description, 80)); ?></p>
                      <?php endif; ?>
                      <?php if($gallery->is_featured): ?>
                        <span class="inline-block bg-yellow-500 text-white text-xs px-3 py-1 rounded-full mt-3 nepali">फिचर्ड</span>
                      <?php endif; ?>
                    </div>
                  </div>
                </div>
              <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <?php else: ?>
              <!-- Placeholder for empty gallery -->
              <div class="gallery-item-enhanced">
                <div class="gallery-placeholder-enhanced">
                  <i class="fas fa-images text-4xl mb-3"></i>
                  <span class="nepali text-base">तस्बिरहरू थपिने...</span>
                </div>
              </div>
              <div class="gallery-item-enhanced">
                <div class="gallery-placeholder-enhanced">
                  <i class="fas fa-bed text-4xl mb-3"></i>
                  <span class="nepali text-base">कोठाका तस्बिरहरू</span>
                </div>
              </div>
              <div class="gallery-item-enhanced">
                <div class="gallery-placeholder-enhanced">
                  <i class="fas fa-utensils text-4xl mb-3"></i>
                  <span class="nepali text-base">खानाको परिवेश</span>
                </div>
              </div>
              <div class="gallery-item-enhanced">
                <div class="gallery-placeholder-enhanced">
                  <i class="fas fa-couch text-4xl mb-3"></i>
                  <span class="nepali text-base">आराम कोठा</span>
                </div>
              </div>
            <?php endif; ?>
          </div>
          
          <!-- 🆕 FIXED: "Purai Gallery Hernuhos" Button at Bottom -->
          <div class="gallery-footer">
            <a href="#" class="view-full-gallery-btn nepali">
              <i class="fas fa-images"></i>
              पूरै ग्यालरी हेर्नुहोस्
            </a>
          </div>
        </div>
      </section>

      <!-- ✅ FIXED: Facilities Section with NORMALIZED DATA from Controllers -->
      <?php if(!empty($facilities) && count($facilities) > 0): ?>
        <section class="pro-card">
          <div class="pro-card-body">
            <h2 class="text-2xl font-bold text-gray-900 nepali mb-6 flex items-center gap-3">
              <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center">
                <i class="fas fa-list-check text-green-600"></i>
              </div>
              हाम्रा सुविधाहरू
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
              <?php $__currentLoopData = $facilities; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $facility): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php
                    // Final cleaning for display
                    $displayFacility = trim($facility);
                    $displayFacility = trim($displayFacility, ' ,"\'[]');
                ?>
                
                <?php if(!empty(trim($displayFacility)) && $displayFacility !== '""' && $displayFacility !== "''"): ?>
                  <div class="facility-chip">
                    <i class="fas fa-check text-green-500"></i>
                    <span class="nepali"><?php echo e($displayFacility); ?></span>
                  </div>
                <?php endif; ?>
              <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
          </div>
        </section>
      <?php endif; ?>

      <!-- Reviews Section -->
      <section class="pro-card">
        <div class="pro-card-body">
          <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center mb-6 gap-4">
            <h2 class="text-2xl font-bold text-gray-900 nepali flex items-center gap-3">
              <div class="w-10 h-10 bg-purple-100 rounded-full flex items-center justify-center">
                <i class="fas fa-star text-purple-600"></i>
              </div>
              विद्यार्थी समीक्षाहरू
            </h2>
            <div class="bg-purple-100 text-purple-800 px-4 py-2 rounded-full">
              <span class="nepali font-medium"><?php echo e($reviewCount); ?> समीक्षाहरू</span>
            </div>
          </div>

          <?php if($reviewCount > 0): ?>
            <div class="space-y-6">
              <?php $__currentLoopData = $reviews; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $review): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="review-card">
                  <div class="flex flex-col lg:flex-row justify-between items-start mb-4 gap-3">
                    <div class="flex-1">
                      <h4 class="font-bold text-gray-900 nepali text-lg">
                        <?php echo e($review->student->user->name ?? 'अज्ञात विद्यार्थी'); ?>

                      </h4>
                      <div class="rating-stars mt-2">
                        <?php for($i = 1; $i <= 5; $i++): ?>
                          <i class="fas fa-star <?php echo e($i <= $review->rating ? 'star-filled' : 'star-empty'); ?>"></i>
                        <?php endfor; ?>
                        <span class="text-gray-500 text-sm ml-2"><?php echo e($review->rating); ?>/5</span>
                      </div>
                    </div>
                    <span class="text-sm text-gray-500 bg-gray-100 px-3 py-1 rounded-full">
                      <?php echo e($review->created_at->format('Y-m-d')); ?>

                    </span>
                  </div>
                  
                  <p class="text-gray-700 mb-4 nepali"><?php echo e($review->comment); ?></p>
                  
                  <?php if($review->reply): ?>
                    <div class="bg-blue-50 border-l-4 border-blue-500 p-4 rounded-lg">
                      <div class="flex items-start space-x-3">
                        <i class="fas fa-reply text-blue-500 mt-1"></i>
                        <div>
                          <strong class="text-blue-800 nepali text-sm">होस्टलको जवाफ:</strong>
                          <p class="text-blue-700 mt-2 nepali text-sm"><?php echo e($review->reply); ?></p>
                        </div>
                      </div>
                    </div>
                  <?php endif; ?>
                </div>
              <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>

            <?php if($reviews->hasPages()): ?>
              <div class="mt-8">
                <?php echo e($reviews->links()); ?>

              </div>
            <?php endif; ?>
          <?php else: ?>
            <div class="text-center py-12">
              <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-comment-slash text-gray-400 text-2xl"></i>
              </div>
              <h3 class="text-xl font-bold text-gray-600 nepali mb-2">अहिलेसम्म कुनै समीक्षा छैन</h3>
              <p class="text-gray-500 nepali">यो होस्टलको पहिलो समीक्षा दिनुहोस्!</p>
            </div>
          <?php endif; ?>
        </div>
      </section>

      <!-- Contact Form Section -->
      <?php echo $__env->make('public.hostels.partials.contact-form', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    </div>

    <!-- Right Column - Sidebar (1/4 width) -->
    <div class="lg:col-span-1 space-y-6">
      <!-- Contact Info -->
      <div class="pro-card">
        <div class="pro-card-body">
          <h3 class="text-xl font-bold text-gray-900 nepali mb-4 flex items-center gap-3">
            <i class="fas fa-address-card text-blue-600"></i>
            सम्पर्क जानकारी
          </h3>
          <div class="space-y-3">
            <?php if($hostel->contact_person): ?>
              <div class="contact-item">
                <div class="flex items-center gap-3">
                  <i class="fas fa-user text-gray-500"></i>
                  <span class="text-gray-800 nepali font-medium"><?php echo e($hostel->contact_person); ?></span>
                </div>
              </div>
            <?php endif; ?>
            
            <?php if($hostel->contact_phone): ?>
              <div class="contact-item">
                <div class="flex items-center gap-3">
                  <i class="fas fa-phone text-gray-500"></i>
                  <a href="tel:<?php echo e($hostel->contact_phone); ?>" class="text-gray-800 hover:text-blue-600 smooth-transition font-medium">
                    <?php echo e($hostel->contact_phone); ?>

                  </a>
                </div>
              </div>
            <?php endif; ?>
            
            <?php if($hostel->contact_email): ?>
              <div class="contact-item">
                <div class="flex items-center gap-3">
                  <i class="fas fa-envelope text-gray-500"></i>
                  <a href="mailto:<?php echo e($hostel->contact_email); ?>" class="text-gray-800 hover:text-blue-600 smooth-transition font-medium">
                    <?php echo e($hostel->contact_email); ?>

                  </a>
                </div>
              </div>
            <?php endif; ?>
            
            <?php if($hostel->address): ?>
              <div class="contact-item">
                <div class="flex items-start gap-3">
                  <i class="fas fa-map-marker-alt text-gray-500 mt-1"></i>
                  <span class="text-gray-800 nepali font-medium"><?php echo e($hostel->address); ?></span>
                </div>
              </div>
            <?php endif; ?>
          </div>
        </div>
      </div>

      <!-- UPDATED: Action Buttons - Phone Button Removed -->
      <div class="pro-card">
        <div class="pro-card-body">
          <h3 class="text-xl font-bold text-gray-900 nepali mb-6 flex items-center gap-3">
            <i class="fas fa-bolt text-orange-600"></i>
            क्रियाहरू
          </h3>
          <div class="space-y-4">
            <a href="<?php echo e(route('hostels.index')); ?>" 
               class="w-full bg-gradient-to-r from-gray-600 to-gray-700 text-white py-4 px-6 rounded-xl hover:from-gray-700 hover:to-gray-800 smooth-transition flex items-center justify-center nepali font-medium gap-3 text-base shadow-lg hover:shadow-xl transition-all duration-300">
                <i class="fas fa-building"></i>
                <span>अन्य होस्टलहरू हेर्नुहोस्</span>
            </a>

            <a href="#reviews" 
               class="w-full bg-gradient-to-r from-purple-500 to-purple-600 text-white py-4 px-6 rounded-xl hover:from-purple-600 hover:to-purple-700 smooth-transition flex items-center justify-center nepali font-medium gap-3 text-base shadow-lg hover:shadow-xl transition-all duration-300">
                <i class="fas fa-star"></i>
                <span>समीक्षा लेख्नुहोस्</span>
            </a>
          </div>
        </div>
      </div>

      <!-- Trust Badges -->
      <div class="pro-card">
        <div class="pro-card-body text-center">
          <h3 class="text-xl font-bold text-gray-900 nepali mb-4">विश्वसनीय होस्टल</h3>
          <div class="grid grid-cols-2 gap-4">
            <div class="space-y-2">
              <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center mx-auto">
                <i class="fas fa-shield-check text-green-600"></i>
              </div>
              <span class="text-sm text-gray-600 nepali font-medium">सत्यापित</span>
            </div>
            <div class="space-y-2">
              <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center mx-auto">
                <i class="fas fa-award text-blue-600"></i>
              </div>
              <span class="text-sm text-gray-600 nepali font-medium">प्रमाणित</span>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<?php else: ?>
  <!-- Non-default theme -->
  <?php echo $__env->make($themeFile, array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php endif; ?>

<!-- Add Font Awesome for social icons -->
<script src="https://kit.fontawesome.com/your-fontawesome-kit.js" crossorigin="anonymous"></script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.public', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\My Projects\HostelHub\resources\views\public\hostels\show.blade.php ENDPATH**/ ?>