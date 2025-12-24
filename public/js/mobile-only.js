// mobile-only.js - Mobile maa matra kaam garne
// Desktop maa yo file le kei gardaina

document.addEventListener('DOMContentLoaded', function() {
  // Mobile check
  if (window.innerWidth <= 768) {
    // Mobile sidebar toggle
    const mobileToggle = document.getElementById('mobile-sidebar-toggle');
    const sidebar = document.getElementById('sidebar');
    
    if (mobileToggle && sidebar) {
      mobileToggle.addEventListener('click', function() {
        sidebar.classList.toggle('mobile-open');
        
        // Overlay create garne
        if (sidebar.classList.contains('mobile-open')) {
          const overlay = document.createElement('div');
          overlay.style.cssText = `
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0,0,0,0.5);
            z-index: 1039;
          `;
          overlay.addEventListener('click', function() {
            sidebar.classList.remove('mobile-open');
            overlay.remove();
          });
          document.body.appendChild(overlay);
        }
      });
    }
    
    // Fix mobile viewport height
    function fixViewport() {
      let vh = window.innerHeight * 0.01;
      document.documentElement.style.setProperty('--vh', `${vh}px`);
    }
    fixViewport();
    window.addEventListener('resize', fixViewport);
  }
});