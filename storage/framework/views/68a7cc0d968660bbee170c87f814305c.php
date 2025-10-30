

<?php $__env->startSection('page-title', 'सम्पर्क - HostelHub'); ?>
<?php $__env->startSection('page-header', 'सम्पर्क गर्नुहोस्'); ?>
<?php $__env->startSection('page-description', 'तपाईंसँग कुनै प्रश्न वा सुझाव छ भने हामीलाई तलको फारम वा नक्सा प्रयोग गरेर सम्पर्क गर्न सक्नुहुन्छ।'); ?>

<?php $__env->startSection('content'); ?>
<div class="contact-container">
    <div class="contact-grid">
        
        <div class="contact-form-container">
            <?php if(session('success')): ?>
                <div class="success-message">
                    <i class="fas fa-check-circle"></i>
                    <?php echo e(session('success')); ?>

                </div>
            <?php endif; ?>

            <?php if($errors->any()): ?>
                <div class="error-message">
                    <i class="fas fa-exclamation-circle"></i>
                    <ul>
                        <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <li><?php echo e($error); ?></li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </ul>
                </div>
            <?php endif; ?>

            <form method="POST" action="<?php echo e(route('contact.store')); ?>" class="contact-form">
                <?php echo csrf_field(); ?>
                <div class="form-row">
                    <div class="form-group">
                        <label for="name" class="form-label">नाम</label>
                        <input 
                            type="text" 
                            id="name"
                            name="name" 
                            value="<?php echo e(old('name')); ?>" 
                            required 
                            class="form-input"
                            placeholder="तपाईंको नाम"
                        >
                    </div>
                    <div class="form-group">
                        <label for="email" class="form-label">इमेल</label>
                        <input 
                            type="email" 
                            id="email"
                            name="email" 
                            value="<?php echo e(old('email')); ?>" 
                            required 
                            class="form-input"
                            placeholder="तपाईंको इमेल ठेगाना"
                        >
                    </div>
                </div>

                <div class="form-group">
                    <label for="message" class="form-label">सन्देश</label>
                    <textarea 
                        id="message"
                        name="message" 
                        rows="5" 
                        required 
                        class="form-input"
                        placeholder="तपाईंको सन्देश यहाँ लेख्नुहोस्..."
                    ><?php echo e(old('message')); ?></textarea>
                </div>

                <div class="form-submit">
                    <button type="submit" class="submit-button">
                        <i class="fas fa-paper-plane"></i> पठाउनुहोस्
                    </button>
                </div>
            </form>
        </div>

        
        <div class="map-container">
            <iframe 
                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3532.310614597818!2d85.3240!3d27.7079!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x39eb190a6d1c9d9f%3A0x2ef7c9dfb4b6a0d!2sKathmandu%20Durbar%20Square!5e0!3m2!1sen!2snp!4v1639397261912!5m2!1sen!2snp" 
                width="100%" 
                height="100%" 
                style="border:0;" 
                allowfullscreen="" 
                loading="lazy">
            </iframe>
        </div>
    </div>

    
    <div class="contact-info-grid">
        <div class="contact-info-card">
            <div class="contact-icon">
                <i class="fas fa-map-marker-alt"></i>
            </div>
            <h3 class="contact-info-title">📍 ठेगाना</h3>
            <p class="contact-info-detail">कमलपोखरी, काठमाडौं, नेपाल</p>
        </div>
        <div class="contact-info-card">
            <div class="contact-icon">
                <i class="fas fa-phone-alt"></i>
            </div>
            <h3 class="contact-info-title">📞 फोन</h3>
            <p class="contact-info-detail">+९७७ ९७६१७६२०३६</p>
        </div>
        <div class="contact-info-card">
            <div class="contact-icon">
                <i class="fas fa-envelope"></i>
            </div>
            <h3 class="contact-info-title">✉ इमेल</h3>
            <p class="contact-info-detail">info@hostelhub.com</p>
        </div>
    </div>
</div>

<style>
    .contact-container {
        padding: 2rem 0;
    }
    
    .contact-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 2rem;
        margin-bottom: 3rem;
    }
    
    .contact-form-container {
        background: white;
        padding: 2rem;
        border-radius: var(--radius);
        box-shadow: var(--shadow);
    }
    
    .form-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1rem;
        margin-bottom: 1.5rem;
    }
    
    .form-group {
        margin-bottom: 1.5rem;
    }
    
    .form-label {
        display: block;
        margin-bottom: 0.5rem;
        font-weight: 600;
        color: var(--text-dark);
    }
    
    .form-input {
        width: 100%;
        padding: 0.85rem 1rem;
        border: 1px solid var(--border);
        border-radius: var(--radius);
        font-family: inherit;
        font-size: 1rem;
        transition: var(--transition);
    }
    
    .form-input:focus {
        outline: none;
        border-color: var(--secondary);
        box-shadow: 0 0 0 3px rgba(14, 165, 233, 0.2);
    }
    
    .form-submit {
        text-align: right;
    }
    
    .submit-button {
        background: linear-gradient(to right, var(--primary), var(--secondary));
        color: white;
        border: none;
        border-radius: var(--radius);
        padding: 0.85rem 1.85rem;
        font-weight: 600;
        cursor: pointer;
        transition: var(--transition);
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .submit-button:hover {
        background: linear-gradient(to right, var(--primary-dark), var(--secondary-dark));
        transform: translateY(-2px);
        box-shadow: var(--glow);
    }
    
    .map-container {
        height: 450px;
        border-radius: var(--radius);
        overflow: hidden;
        box-shadow: var(--shadow);
    }
    
    .contact-info-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 1.5rem;
    }
    
    .contact-info-card {
        background: white;
        padding: 1.5rem;
        border-radius: var(--radius);
        text-align: center;
        box-shadow: var(--shadow);
        transition: var(--transition);
    }
    
    .contact-info-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
    }
    
    .contact-icon {
        width: 60px;
        height: 60px;
        margin: 0 auto 1rem;
        background: var(--bg-light);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        color: var(--secondary);
    }
    
    .contact-info-title {
        font-size: 1.2rem;
        margin-bottom: 0.5rem;
        color: var(--primary);
    }
    
    .contact-info-detail {
        color: var(--text-dark);
        margin: 0;
    }
    
    .success-message {
        background: #d4edda;
        color: #155724;
        padding: 1rem;
        border-radius: var(--radius);
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .error-message {
        background: #f8d7da;
        color: #721c24;
        padding: 1rem;
        border-radius: var(--radius);
        margin-bottom: 1.5rem;
    }
    
    .error-message ul {
        margin: 0;
        padding-left: 1.5rem;
    }
    
    /* Responsive Design */
    @media (max-width: 968px) {
        .contact-grid {
            grid-template-columns: 1fr;
        }
        
        .map-container {
            height: 350px;
            order: -1;
        }
    }
    
    @media (max-width: 768px) {
        .form-row {
            grid-template-columns: 1fr;
        }
        
        .contact-info-grid {
            grid-template-columns: 1fr;
        }
    }
</style>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.frontend', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\HostelHub\resources\views\frontend\contact.blade.php ENDPATH**/ ?>