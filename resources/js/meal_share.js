// public/js/meal-share.js
// Advanced meal sharing functionality with analytics
class MealShareManager {
    constructor() {
        this.shareData = {
            url: window.location.href,
            title: 'HostelHub - खानाको ग्यालरी'
        };
        this.init();
    }

    init() {
        this.bindShareEvents();
        this.trackShareAnalytics();   // Now defined
    }

    bindShareEvents() {
        // Bind to all share buttons
        document.querySelectorAll('[data-share-meal]').forEach(btn => {
            btn.addEventListener('click', (e) => this.handleShare(e));
        });
    }

    async handleShare(e) {
        const mealId = e.currentTarget.dataset.shareMeal;
        const mealDescription = e.currentTarget.dataset.mealDescription;
        
        this.shareData.text = `HostelHub मा यो खाना हेर्नुहोस्: ${mealDescription}`;
        
        // Try Web Share API first
        if (navigator.share) {
            try {
                await navigator.share(this.shareData);
                this.logShare('web_share', mealId);
            } catch (err) {
                console.log('Web share cancelled:', err);
            }
        } else {
            // Fallback to custom modal
            this.showShareModal(mealId, mealDescription);
        }
    }

    showShareModal(mealId, description) {
        // Modal implementation here
        console.log('Showing share modal for:', mealId);
    }

    logShare(platform, mealId) {
        // Send analytics data
        if (typeof gtag !== 'undefined') {
            gtag('event', 'share', {
                'content_type': 'meal',
                'item_id': mealId,
                'method': platform
            });
        }
    }

    // ✅ Added missing method to fix "trackShareAnalytics is not a function"
    trackShareAnalytics() {
        // You can add actual tracking logic here if needed
        console.log('Meal share analytics tracking initialized');
    }
}

// Initialize when DOM is ready
document.addEventListener('DOMContentLoaded', () => {
    new MealShareManager();
});