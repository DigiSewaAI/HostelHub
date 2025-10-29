<?php $__env->startSection('title', 'प्रशंसापत्रहरू - HostelHub'); ?>
<?php $__env->startSection('content'); ?>
<div style="
  max-width: 1200px;
  margin: 0 auto;
  padding: 3rem 1.5rem;
  font-family: 'Segoe UI', sans-serif;
">

  <!-- Page Header -->
  <div style="
    text-align: center;
    margin-bottom: 3rem;
  ">
    <h1 style="
      font-size: 2.5rem;
      font-weight: 800;
      color: #1f2937;
      margin-bottom: 1rem;
    ">
      हाम्रा ग्राहकहरूको प्रशंसापत्र
      
    </h1>
    <p style="
      font-size: 1.125rem;
      color: #4b5563;
      max-width: 700px;
      margin: 0 auto;
      line-height: 1.6;
    ">
      HostelHub प्रयोग गर्ने होस्टल प्रबन्धक र मालिकहरूले के भन्छन् —<br>
      वास्तविक अनुभव, वास्तविक परिणाम।
    </p>
  </div>

  <!-- Testimonials Grid -->
  <div style="
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 1.5rem;
  ">
    <?php $__empty_1 = true; $__currentLoopData = $testimonials; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $testimonial): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
    <div style="
      background: white;
      border: 1px solid #e5e7eb;
      border-radius: 0.75rem;
      padding: 1.5rem;
      box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
      transition: all 0.3s ease;
      display: flex;
      flex-direction: column;
    " onmouseover="this.style.transform='translateY(-6px)'; this.style.boxShadow='0 20px 25px -5px rgba(0, 0, 0, 0.1)';"
       onmouseout="this.style.transform='none'; this.style.boxShadow='0 4px 6px -1px rgba(0, 0, 0, 0.1)';">
      
      <!-- Display image or initials if available -->
      <div style="margin-bottom: 1rem; text-align: center;">
        <?php if($testimonial->image): ?>
        <img src="<?php echo e(asset('storage/' . $testimonial->image)); ?>" alt="<?php echo e($testimonial->name); ?>" style="width: 60px; height: 60px; border-radius: 50%; object-fit: cover;">
        <?php elseif($testimonial->initials): ?>
        <div style="width: 60px; height: 60px; border-radius: 50%; background: #001F5B; color: white; display: inline-flex; align-items: center; justify-content: center; font-weight: bold; font-size: 1.2rem;">
          <?php echo e($testimonial->initials); ?>

        </div>
        <?php else: ?>
        <div style="width: 60px; height: 60px; border-radius: 50%; background: #e5e7eb; color: #6b7280; display: inline-flex; align-items: center; justify-content: center; font-weight: bold;">
          <i class="fas fa-user" style="font-size: 1.2rem;"></i>
        </div>
        <?php endif; ?>
      </div>
      
      <p style="
        font-style: italic;
        color: #374151;
        line-height: 1.6;
        margin: 0 0 1rem 0;
        flex-grow: 1;
      ">
        "<?php echo e($testimonial->content); ?>"
      </p>
      
      <!-- Display rating if available -->
      <?php if($testimonial->rating): ?>
      <div style="margin-bottom: 1rem; text-align: center;">
        <?php for($i = 1; $i <= 5; $i++): ?>
          <span style="color: <?php echo e($i <= $testimonial->rating ? '#fbbf24' : '#d1d5db'); ?>; font-size: 1.2rem;">★</span>
        <?php endfor; ?>
        <span style="margin-left: 0.5rem; color: #6b7280; font-size: 0.9rem;">(<?php echo e($testimonial->rating); ?>/5)</span>
      </div>
      <?php endif; ?>
      
      <div style="
        margin-top: auto;
        font-weight: 600;
        color: #001F5B;
        font-size: 0.875rem;
        text-align: center;
        border-top: 1px solid #f3f4f6;
        padding-top: 1rem;
      ">
        — <?php echo e($testimonial->name); ?><?php echo e($testimonial->position ? ', ' . $testimonial->position : ''); ?>

      </div>
    </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
    <div style="
      grid-column: 1 / -1;
      text-align: center;
      padding: 3rem;
      background: white;
      border-radius: 0.75rem;
      border: 1px solid #e5e7eb;
      box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
    ">
      <div style="font-size: 4rem; color: #e5e7eb; margin-bottom: 1rem;">
        <i class="fas fa-comments"></i>
      </div>
      <h3 style="font-size: 1.5rem; color: #374151; margin-bottom: 0.5rem;">कुनै प्रशंसापत्र उपलब्ध छैन</h3>
      <p style="color: #6b7280;">हामी छिट्टै नयाँ प्रशंसापत्रहरू थप्नेछौं।</p>
    </div>
    <?php endif; ?>
  </div>

  <!-- CTA Section -->
  <div style="
    text-align: center;
    margin-top: 4rem;
    background: linear-gradient(90deg, #0a3a6a, #001F5B, #0a3a6a);
    color: white;
    padding: 2.5rem 1.5rem;
    border-radius: 1rem;
    box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.2);
    max-width: 800px;
    margin-left: auto;
    margin-right: auto;
  ">
    <h2 style="font-size: 1.875rem; font-weight: bold; margin-bottom: 1rem;">
      आफैंले अनुभव गर्नुहोस्
    </h2>
    <p style="font-size: 1.25rem; margin-bottom: 2rem; opacity: 0.9;">
      ७ दिनको निःशुल्क परीक्षणमा साइन अप गरेर तपाइँको होस्टललाई आधुनिक बनाउनुहोस्।
    </p>
    <div style="display: flex; flex-direction: column; gap: 1rem; align-items: center;">
      <a href="/register" style="
        background-color: white;
        color: #001F5B;
        font-weight: 600;
        padding: 0.75rem 2rem;
        border-radius: 0.5rem;
        text-decoration: none;
        min-width: 180px;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        transition: all 0.3s ease;
      " onmouseover="this.style.backgroundColor='#f3f4f6'; this.style.transform='translateY(-2px)';"
         onmouseout="this.style.backgroundColor='white'; this.style.transform='none';">
        निःशुल्क साइन अप
      </a>
      <a href="/demo" style="
        border: 2px solid white;
        color: white;
        font-weight: 600;
        padding: 0.75rem 2rem;
        border-radius: 0.5rem;
        text-decoration: none;
        min-width: 180px;
        background-color: transparent;
        transition: all 0.3s ease;
      " onmouseover="this.style.backgroundColor='white'; this.style.color='#001F5B'; this.style.transform='translateY(-2px)';"
         onmouseout="this.style.backgroundColor='transparent'; this.style.color='white'; this.style.transform='none';">
        डेमो हेर्नुहोस्
      </a>
    </div>
  </div>

</div>

<!-- Add Font Awesome for icons -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.frontend', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\My Projects\HostelHub\resources\views\frontend\testimonials.blade.php ENDPATH**/ ?>