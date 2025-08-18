<div class="bg-white rounded-xl shadow-md overflow-hidden hover:shadow-xl transition-shadow duration-300">
    <!-- Room Image with Fallback -->
    <div class="h-48 overflow-hidden">
        <?php if($room->featured_image): ?>
            <img
                src="<?php echo e(asset('storage/' . $room->featured_image)); ?>"
                alt="<?php echo e($room->name); ?> कोठा"
                class="w-full h-full object-cover"
            >
        <?php else: ?>
            <div class="w-full h-full bg-gray-200 flex items-center justify-center">
                <span class="text-gray-500 text-lg">कोठा फोटो उपलब्ध छैन</span>
            </div>
        <?php endif; ?>
    </div>

    <div class="p-6">
        <div class="flex justify-between items-start">
            <div>
                <h3 class="text-xl font-bold text-gray-900"><?php echo e($room->name); ?></h3>
                <p class="text-gray-600 text-sm mt-1"><?php echo e(ucfirst($room->type)); ?> कोठा</p>
            </div>

            <!-- Status Badge with Color Coding -->
            <?php
                $statusColors = [
                    'available' => 'bg-green-100 text-green-800',
                    'occupied' => 'bg-yellow-100 text-yellow-800',
                    'maintenance' => 'bg-red-100 text-red-800'
                ];
                $statusText = [
                    'available' => 'उपलब्ध',
                    'occupied' => 'भरिएको',
                    'maintenance' => 'मर्मत सम्भार'
                ];
            ?>

            <span class="<?php echo e($statusColors[$room->status] ?? 'bg-gray-100 text-gray-800'); ?> text-xs font-semibold px-2.5 py-0.5 rounded-full">
                <?php echo e($statusText[$room->status] ?? ucfirst($room->status)); ?>

            </span>
        </div>

        <div class="mt-3 grid grid-cols-2 gap-2 text-sm">
            <p class="text-gray-600">
                <span class="font-medium">क्षमता:</span> <?php echo e($room->capacity); ?> विद्यार्थी
            </p>
            <p class="text-gray-600">
                <span class="font-medium">प्रकार:</span>
                <?php if($room->type == 'single'): ?> एकल
                <?php elseif($room->type == 'double'): ?> जोडी
                <?php else: ?> डर्म
                <?php endif; ?>
            </p>
        </div>

        <div class="mt-5 flex items-center justify-between">
            <div>
                <span class="text-2xl font-bold text-gray-900">रु. <?php echo e(number_format($room->price)); ?></span>
                <p class="text-gray-500 text-sm">प्रति महिना</p>
            </div>
            <a href="<?php echo e(route('rooms.show', $room)); ?>"
               class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors">
                विवरण हेर्नुहोस्
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
            </a>
        </div>
    </div>
</div>
<?php /**PATH D:\My Projects\HostelHub\resources\views\components\room-card.blade.php ENDPATH**/ ?>