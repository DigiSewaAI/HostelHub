<section class="contact-form-section mt-8">
    <h2 class="text-2xl font-bold text-gray-900 nepali mb-6">सम्पर्क फर्म</h2>
    <div class="bg-white rounded-lg shadow border p-6">
        <form action="<?php echo e(route('hostel.contact', $hostel->id)); ?>" method="POST">
            <?php echo csrf_field(); ?>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 nepali mb-2">तपाईंको नाम</label>
                    <input type="text" name="name" required 
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 nepali mb-2">इमेल ठेगाना</label>
                    <input type="email" name="email" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 nepali mb-2">सन्देश</label>
                <textarea name="message" rows="4" required
                          class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 nepali"
                          placeholder="तपाईंको सन्देश यहाँ लेख्नुहोस्..."></textarea>
            </div>
            <button type="submit"
                    class="bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition-colors font-medium nepali">
                सन्देश पठाउनुहोस्
            </button>
        </form>
    </div>
</section><?php /**PATH D:\My Projects\HostelHub\resources\views/public/hostels/partials/contact-form.blade.php ENDPATH**/ ?>