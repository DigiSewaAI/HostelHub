import './bootstrap';

// Alpine.js
import Alpine from 'alpinejs';
window.Alpine = Alpine;
Alpine.start();

// Global error handler
window.addEventListener('error', function(e) {
    console.error('Global error:', e.error);
});

// 🚨 EMERGENCY ADMIN DASHBOARD FIX
document.addEventListener('DOMContentLoaded', function() {
    console.log('🚨 Applying emergency admin dashboard fixes...');
    
    // Force sidebar visibility
    const sidebar = document.querySelector('[id*="sidebar"], .sidebar, aside');
    if (sidebar) {
        sidebar.style.display = 'flex';
        sidebar.style.position = 'fixed';
        sidebar.style.left = '0';
        sidebar.style.top = '0';
        sidebar.style.bottom = '0';
        sidebar.style.width = '16rem';
        sidebar.style.background = 'linear-gradient(180deg, #1e40af 0%, #1e3a8a 100%)';
        sidebar.style.color = 'white';
        sidebar.style.zIndex = '1000';
        sidebar.style.transform = 'translateX(0)';
    }
    
    // Force main content margin
    const mainContent = document.querySelector('.main-content, [class*="main"], .content');
    if (mainContent) {
        mainContent.style.marginLeft = '16rem';
        mainContent.style.width = 'calc(100% - 16rem)';
        mainContent.style.display = 'block';
    }
    
    // Force all cards visible
    document.querySelectorAll('.card, [class*="card"]').forEach(card => {
        card.style.background = 'white';
        card.style.borderRadius = '0.5rem';
        card.style.boxShadow = '0 1px 3px rgba(0,0,0,0.1)';
        card.style.border = '1px solid #e5e7eb';
        card.style.display = 'block';
    });
    
    // Force all buttons visible
    document.querySelectorAll('button, .btn, [class*="btn"]').forEach(btn => {
        if (btn.className.includes('primary') || btn.textContent.includes('थप्नुहोस्')) {
            btn.style.background = '#1e40af';
            btn.style.color = 'white';
        }
        if (btn.className.includes('danger')) {
            btn.style.background = '#dc2626';
            btn.style.color = 'white';
        }
    });
    
    console.log('✅ Emergency fixes applied');
});

// 🚨 FORCE RELOAD IF STILL BROKEN
setTimeout(() => {
    if (document.querySelector('.sidebar') && document.querySelector('.sidebar').offsetHeight === 0) {
        console.log('🔄 Forcing page reload...');
        window.location.reload();
    }
}, 1000);

// Form handling enhancements
document.addEventListener('DOMContentLoaded', function() {
    // Auto-dismiss alerts
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(alert => {
        setTimeout(() => {
            if (alert && alert.parentNode) {
                alert.style.opacity = '0';
                alert.style.transition = 'opacity 0.5s';
                setTimeout(() => {
                    if (alert.parentNode) {
                        alert.parentNode.removeChild(alert);
                    }
                }, 500);
            }
        }, 5000);
    });

    // Form submission enhancements
    const forms = document.querySelectorAll('form');
    forms.forEach(form => {
        form.addEventListener('submit', function(e) {
            const submitBtn = this.querySelector('button[type="submit"], input[type="submit"]');
            if (submitBtn) {
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> प्रक्रिया हुदैछ...';
            }
        });
    });

    // Image preview functionality
    window.previewImage = function(input, previewElement) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                previewElement.innerHTML = `<img src="${e.target.result}" class="max-w-full max-h-64 object-contain rounded-lg">`;
            };
            reader.readAsDataURL(input.files[0]);
        }
    };

    // Video preview functionality
    window.previewVideo = function(input, previewElement) {
        if (input.files && input.files[0]) {
            const file = input.files[0];
            if (file.type.startsWith('video/')) {
                const url = URL.createObjectURL(file);
                previewElement.innerHTML = `
                    <video controls class="max-w-full max-h-64 rounded-lg">
                        <source src="${url}" type="${file.type}">
                        Your browser does not support the video tag.
                    </video>
                `;
            }
        }
    };

    // Modal functionality
    window.openModal = function(modalId) {
        const modal = document.getElementById(modalId);
        if (modal) {
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            document.body.style.overflow = 'hidden';
        }
    };

    window.closeModal = function(modalId) {
        const modal = document.getElementById(modalId);
        if (modal) {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
            document.body.style.overflow = 'auto';
        }
    };

    // Close modal on background click
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('modal-background')) {
            const modal = e.target.closest('.modal');
            if (modal) {
                closeModal(modal.id);
            }
        }
    });

    // Close modal with Escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            const modals = document.querySelectorAll('.modal:not(.hidden)');
            modals.forEach(modal => {
                closeModal(modal.id);
            });
        }
    });

    // File upload enhancements
    const fileInputs = document.querySelectorAll('input[type="file"]');
    fileInputs.forEach(input => {
        input.addEventListener('change', function() {
            const fileName = this.files[0]?.name || 'कुनै फाइल छानिएन';
            const label = this.nextElementSibling?.querySelector('.file-name') || 
                         this.parentElement?.querySelector('.file-name');
            if (label) {
                label.textContent = fileName;
            }
        });
    });

    // Dynamic form fields
    window.addFormField = function(containerId, templateId) {
        const container = document.getElementById(containerId);
        const template = document.getElementById(templateId);
        if (container && template) {
            const newField = template.content.cloneNode(true);
            container.appendChild(newField);
        }
    };

    window.removeFormField = function(element) {
        element.closest('.form-field-group').remove();
    };

    // Toast notifications
    window.showToast = function(message, type = 'success') {
        const toast = document.createElement('div');
        toast.className = `fixed top-4 right-4 z-50 p-4 rounded-lg shadow-lg text-white ${
            type === 'success' ? 'bg-green-500' : 
            type === 'error' ? 'bg-red-500' : 
            type === 'warning' ? 'bg-yellow-500' : 'bg-blue-500'
        }`;
        toast.innerHTML = `
            <div class="flex items-center">
                <i class="fas fa-${type === 'success' ? 'check' : type === 'error' ? 'exclamation-triangle' : 'info-circle'} mr-2"></i>
                <span>${message}</span>
                <button class="ml-4" onclick="this.parentElement.parentElement.remove()">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        `;
        document.body.appendChild(toast);
        setTimeout(() => {
            if (toast.parentNode) {
                toast.parentNode.removeChild(toast);
            }
        }, 5000);
    };

    // Copy to clipboard
    window.copyToClipboard = function(text) {
        navigator.clipboard.writeText(text).then(() => {
            showToast('क्लिपबोर्डमा प्रतिलिपि गरियो', 'success');
        }).catch(() => {
            showToast('प्रतिलिपि गर्न असफल', 'error');
        });
    };

    // Lazy loading for images
    if ('IntersectionObserver' in window) {
        const imageObserver = new IntersectionObserver((entries, observer) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const img = entry.target;
                    img.src = img.dataset.src;
                    img.classList.remove('lazy');
                    imageObserver.unobserve(img);
                }
            });
        });

        document.querySelectorAll('img[data-src]').forEach(img => {
            imageObserver.observe(img);
        });
    }

    // Smooth scrolling for anchor links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });

    // Check if CSS is loaded
    if (!document.querySelector('link[href*="app-C2VmGskO.css"]')) {
        console.warn('CSS not loaded, injecting fallback');
        const link = document.createElement('link');
        link.rel = 'stylesheet';
        link.href = '/build/assets/app-C2VmGskO.css';
        document.head.appendChild(link);
    }
    
    // Admin sidebar functionality
    window.toggleSidebar = function() {
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('sidebar-overlay');
        if (sidebar && overlay) {
            sidebar.classList.toggle('-translate-x-full');
            overlay.classList.toggle('hidden');
        }
    };
});

// Export for use in other modules
window.HostelHub = {
    previewImage: window.previewImage,
    previewVideo: window.previewVideo,
    openModal: window.openModal,
    closeModal: window.closeModal,
    showToast: window.showToast,
    copyToClipboard: window.copyToClipboard,
    addFormField: window.addFormField,
    removeFormField: window.removeFormField
};