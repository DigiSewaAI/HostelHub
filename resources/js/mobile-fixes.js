// mobile-fixes.js - Mobile specific fixes

document.addEventListener('DOMContentLoaded', function() {
    // Mobile viewport height fix
    function fixMobileHeight() {
        let vh = window.innerHeight * 0.01;
        document.documentElement.style.setProperty('--vh', vh + 'px');
    }
    
    fixMobileHeight();
    window.addEventListener('resize', fixMobileHeight);
    
    // Mobile sidebar close when clicking outside
    document.addEventListener('click', function(event) {
        const sidebar = document.getElementById('sidebar');
        const overlay = document.querySelector('.sidebar-overlay');
        
        if (sidebar && sidebar.classList.contains('mobile-open') && 
            overlay && event.target === overlay) {
            sidebar.classList.remove('mobile-open');
            document.body.classList.remove('sidebar-open');
            overlay.remove();
        }
    });
    
    // Mobile sidebar close on escape key
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.querySelector('.sidebar-overlay');
            
            if (sidebar && sidebar.classList.contains('mobile-open')) {
                sidebar.classList.remove('mobile-open');
                document.body.classList.remove('sidebar-open');
                if (overlay) overlay.remove();
            }
        }
    });
    
    // iOS specific fix
    if (/iPhone|iPad/i.test(navigator.userAgent)) {
        document.documentElement.style.height = '100%';
        setTimeout(function() {
            window.scrollTo(0, 0);
        }, 100);
    }
});