<footer class="bg-gray-900 text-gray-300 mt-16">
  <div class="max-w-7xl mx-auto px-4 py-10 grid md:grid-cols-4 gap-8">
    <div>
      <h4 class="text-white font-semibold text-lg">HostelHub</h4>
      <p class="mt-2 text-sm">नेपालको नम्बर १ होस्टल प्रबन्धन प्रणाली।</p>
    </div>
    <div>
      <h5 class="text-white font-semibold">तिब्र लिङ्कहरू</h5>
      <ul class="mt-3 space-y-2 text-sm">
        <li><a href="<?php echo e(route('home')); ?>">होम</a></li>
        <li><a href="<?php echo e(route('features')); ?>">सुविधाहरू</a></li>
        <li><a href="<?php echo e(route('how-it-works')); ?>">कसरी काम गर्छ</a></li>
        <li><a href="<?php echo e(route('pricing')); ?>">मूल्य</a></li>
        <li><a href="<?php echo e(route('gallery.public')); ?>">ग्यालरी</a></li>
      </ul>
    </div>
    <div>
      <h5 class="text-white font-semibold">सम्पर्क जानकारी</h5>
      <ul class="mt-3 space-y-2 text-sm">
        <li>कमलपोखरी, काठमाडौं, नेपाल</li>
        <li>+९७७ ९८०१२३४५६७</li>
        <li>info@hostelhub.com</li>
        <li>सोम–शुक्र: ९:००–५:००</li>
      </ul>
    </div>
    <div>
      <h5 class="text-white font-semibold">कानुनी</h5>
      <ul class="mt-3 space-y-2 text-sm">
        <li><a href="<?php echo e(route('privacy')); ?>">गोपनीयता नीति</a></li>
        <li><a href="<?php echo e(route('terms')); ?>">सर्तहरू</a></li>
        <li><a href="<?php echo e(route('cookies')); ?>">कुकीज</a></li>
      </ul>
    </div>
  </div>
  <div class="border-t border-gray-800">
    <div class="max-w-7xl mx-auto px-4 py-4 text-xs text-gray-400">
      © <?php echo e(now()->year); ?> HostelHub. सबै अधिकार सुरक्षित।
    </div>
  </div>
</footer><?php /**PATH D:\My Projects\HostelHub\resources\views\frontend\partials\footer.blade.php ENDPATH**/ ?>