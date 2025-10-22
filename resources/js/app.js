import './bootstrap';
import 'flowbite';

// Alpine.js
import Alpine from 'alpinejs';
window.Alpine = Alpine;
Alpine.start();

// Global error handler
window.addEventListener('error', function(e) {
    console.error('Global error:', e.error);
});

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

    // Auto-save functionality for forms
    window.enableAutoSave = function(formId, saveUrl) {
        const form = document.getElementById(formId);
        if (!form) return;

        let timeout;
        form.addEventListener('input', function() {
            clearTimeout(timeout);
            timeout = setTimeout(() => {
                const formData = new FormData(form);
                fetch(saveUrl, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                }).then(response => response.json())
                  .then(data => {
                      if (data.success) {
                          console.log('Auto-save successful');
                      }
                  })
                  .catch(error => console.error('Auto-save error:', error));
            }, 1000);
        });
    };

    // Initialize tooltips
    const tooltips = document.querySelectorAll('[data-tooltip]');
    tooltips.forEach(element => {
        element.addEventListener('mouseenter', function() {
            const tooltip = document.createElement('div');
            tooltip.className = 'fixed bg-gray-800 text-white px-2 py-1 rounded text-sm z-50';
            tooltip.textContent = this.dataset.tooltip;
            document.body.appendChild(tooltip);

            const rect = this.getBoundingClientRect();
            tooltip.style.left = rect.left + 'px';
            tooltip.style.top = (rect.top - tooltip.offsetHeight - 5) + 'px';

            this._tooltip = tooltip;
        });

        element.addEventListener('mouseleave', function() {
            if (this._tooltip) {
                this._tooltip.remove();
                this._tooltip = null;
            }
        });
    });
});

// Export for use in other modules
window.HostelHub = {
    previewImage: window.previewImage,
    previewVideo: window.previewVideo,
    openModal: window.openModal,
    closeModal: window.closeModal,
    showToast: window.showToast,
    copyToClipboard: window.copyToClipboard,
    enableAutoSave: window.enableAutoSave,
    addFormField: window.addFormField,
    removeFormField: window.removeFormField
};
