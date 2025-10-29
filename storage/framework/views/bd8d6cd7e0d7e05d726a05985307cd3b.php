

<?php $__env->startSection('title', 'विद्यार्थी ड्यासबोर्ड'); ?>

<?php $__env->startSection('content'); ?>
<style>
/* Custom Wave Animation */
.wave-hand {
    display: inline-block;
    animation: wave 2.5s ease-in-out infinite;
    transform-origin: 70% 70%;
}

@keyframes wave {
    0% { transform: rotate(0deg); }
    10% { transform: rotate(14deg); }
    20% { transform: rotate(-8deg); }
    30% { transform: rotate(14deg); }
    40% { transform: rotate(-4deg); }
    50% { transform: rotate(10deg); }
    60% { transform: rotate(0deg); }
    100% { transform: rotate(0deg); }
}

/* Alternative: Bounce Animation */
.bounce-hand {
    display: inline-block;
    animation: bounce 2s infinite;
}

@keyframes bounce {
    0%, 20%, 50%, 80%, 100% {transform: translateY(0);}
    40% {transform: translateY(-10px);}
    60% {transform: translateY(-5px);}
}

/* Simple Pulse Animation */
.pulse-hand {
    display: inline-block;
    animation: pulse 2s infinite;
}

@keyframes pulse {
    0% { transform: scale(1); }
    50% { transform: scale(1.1); }
    100% { transform: scale(1); }
}
</style>

    <!-- Welcome Section with WORKING Animated Hand -->
    <div class="bg-blue-800 rounded-2xl shadow-lg mb-6 border border-blue-700">
        <div class="p-6">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                <div class="flex-1">
                    <!-- Main Heading with Waving Hand -->
                    <h2 class="text-2xl font-bold mb-2 text-white">
                        नमस्ते, <?php echo e($student->user->name); ?>! 
                        <span class="wave-hand">👋</span>
                    </h2>
                    
                    <p class="text-white text-lg font-medium mb-4"><?php echo e($hostel->name); ?> मा तपाईंलाई स्वागत छ</p>
                    
                    <?php if(($unreadCirculars ?? 0) > 0): ?>
                    <div class="bg-yellow-400 text-gray-900 rounded-xl p-3 inline-block border border-yellow-500">
                        <div class="flex items-center">
                            <i class="fas fa-bell mr-2"></i>
                            <span class="font-bold">तपाईंसँग <?php echo e($unreadCirculars); ?> वटा नयाँ सूचनाहरू छन्!</span>
                            <a href="<?php echo e(route('student.circulars.index')); ?>" class="ml-2 text-blue-800 underline font-bold">
                                यहाँ क्लिक गर्नुहोस्
                            </a>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
                
                <div class="mt-4 md:mt-0 flex flex-col space-y-2">
                    <div class="bg-white text-blue-800 p-3 rounded-xl border border-blue-300 font-bold">
                        <div class="flex items-center justify-center">
                            <i class="fas fa-calendar mr-2"></i>
                            <span><?php echo e(now()->format('F j, Y')); ?></span>
                        </div>
                    </div>
                    <!-- 🏠 Homepage Button in Welcome Section -->
                    <a href="<?php echo e(url('/')); ?>" 
                       class="bg-green-600 hover:bg-green-700 text-white p-3 rounded-xl border border-green-500 font-bold text-center transition-colors no-underline">
                        <div class="flex items-center justify-center">
                            <i class="fas fa-home mr-2"></i>
                            <span>मुख्य पृष्ठ</span>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Unread Circulars Alert -->
    <?php if(($unreadCirculars ?? 0) > 0): ?>
    <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-6 rounded">
        <div class="flex items-center">
            <div class="flex-shrink-0">
                <i class="fas fa-bell text-yellow-400 text-2xl"></i>
            </div>
            <div class="ml-3 flex-1">
                <h3 class="text-sm font-semibold text-yellow-800">
                    तपाईंसँग <?php echo e($unreadCirculars); ?> वटा नपढिएका सूचनाहरू छन्!
                </h3>
                <p class="text-sm text-yellow-700 mt-1">
                    कृपया तपाईंको सूचना बाकसमा जाँच गर्नुहोस्।
                </p>
            </div>
            <a href="<?php echo e(route('student.circulars.index')); ?>" 
               class="bg-yellow-600 hover:bg-yellow-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                हेर्नुहोस्
            </a>
        </div>
    </div>
    <?php endif; ?>

    <!-- Urgent Circulars Alert -->
    <?php if($urgentCirculars && $urgentCirculars->count() > 0): ?>
    <div class="bg-red-50 border-l-4 border-red-400 p-4 mb-6 rounded">
        <div class="flex items-center">
            <div class="flex-shrink-0">
                <i class="fas fa-exclamation-triangle text-red-400 text-2xl"></i>
            </div>
            <div class="ml-3 flex-1">
                <h3 class="text-sm font-semibold text-red-800">
                    तपाईंसँग <?php echo e($urgentCirculars->count()); ?> वटा जरुरी सूचनाहरू छन्!
                </h3>
                <p class="text-sm text-red-700 mt-1">
                    कृपया तुरुन्तै यी जरुरी सूचनाहरू पढ्नुहोस्।
                </p>
            </div>
            <a href="<?php echo e(route('student.circulars.index', ['priority' => 'urgent'])); ?>" 
               class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                हेर्नुहोस्
            </a>
        </div>
    </div>
    <?php endif; ?>

    <!-- Quick Stats -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-2xl shadow-sm p-4 border border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm font-medium">कोठा नं.</p>
                    <p class="text-2xl font-bold text-blue-600 mt-1"><?php echo e($student->room->room_number ?? 'N/A'); ?></p>
                </div>
                <div class="bg-blue-100 p-3 rounded-xl">
                    <i class="fas fa-door-open text-blue-600 text-xl"></i>
                </div>
            </div>
            <p class="text-gray-500 text-xs mt-2">तपाईंको कोठा नम्बर</p>
        </div>

        <div class="bg-white rounded-2xl shadow-sm p-4 border border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm font-medium">आजको खाना</p>
                    <p class="text-2xl font-bold text-green-600 mt-1"><?php echo e($todayMeal ? 'उपलब्ध' : 'अपडेट छैन'); ?></p>
                </div>
                <div class="bg-green-100 p-3 rounded-xl">
                    <i class="fas fa-utensils text-green-600 text-xl"></i>
                </div>
            </div>
            <p class="text-gray-500 text-xs mt-2">खानाको अवस्था</p>
        </div>

        <div class="bg-white rounded-2xl shadow-sm p-4 border border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm font-medium">भुक्तानी</p>
                    <p class="text-2xl font-bold text-amber-600 mt-1"><?php echo e($paymentStatus == 'Paid' ? 'भुक्तानी भएको' : 'बाकी'); ?></p>
                </div>
                <div class="bg-amber-100 p-3 rounded-xl">
                    <i class="fas fa-receipt text-amber-600 text-xl"></i>
                </div>
            </div>
            <p class="text-gray-500 text-xs mt-2">भुक्तानी स्थिति</p>
        </div>

        <div class="bg-white rounded-2xl shadow-sm p-4 border border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm font-medium">सूचनाहरू</p>
                    <p class="text-2xl font-bold text-indigo-600 mt-1"><?php echo e($unreadCirculars ?? 0); ?></p>
                </div>
                <div class="bg-indigo-100 p-3 rounded-xl">
                    <i class="fas fa-bullhorn text-indigo-600 text-xl"></i>
                </div>
            </div>
            <p class="text-gray-500 text-xs mt-2">नयाँ सूचनाहरू</p>
        </div>
    </div>

    <!-- Reviews Summary Section -->
    <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-200 mb-6">
        <div class="flex items-center justify-between mb-4">
            <div class="flex items-center">
                <div class="bg-purple-100 p-2 rounded-lg mr-3">
                    <i class="fas fa-star text-purple-600"></i>
                </div>
                <h3 class="text-lg font-bold text-gray-800">मेरो होस्टेल समीक्षा</h3>
            </div>
            <a href="<?php echo e(route('student.reviews.index')); ?>" class="text-purple-600 hover:text-purple-800 text-sm font-medium">
                सबै हेर्नुहोस्
            </a>
        </div>
        
        <?php
            $student = auth()->user()->student;
            $userReviews = $student 
                ? \App\Models\Review::where('student_id', $student->id)
                    ->with('hostel')
                    ->orderBy('created_at', 'desc')
                    ->take(2)
                    ->get()
                : collect();
        ?>
        
        <?php if($userReviews->count() > 0): ?>
            <div class="space-y-3">
                <?php $__currentLoopData = $userReviews; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $review): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="border-l-4 border-purple-500 pl-3 py-2 bg-purple-50 rounded-r-lg">
                        <div class="flex justify-between items-start">
                            <div>
                                <p class="font-medium text-gray-800 text-sm"><?php echo e($review->hostel->name ?? 'होस्टेल'); ?></p>
                                <div class="flex items-center mt-1">
                                    <?php for($i = 1; $i <= 5; $i++): ?>
                                        <i class="fas fa-star text-<?php echo e($i <= $review->rating ? 'yellow-400' : 'gray-300'); ?> text-xs"></i>
                                    <?php endfor; ?>
                                    <span class="text-xs text-gray-600 ml-1">(<?php echo e($review->rating); ?>/5)</span>
                                </div>
                                <p class="text-xs text-gray-600 mt-1"><?php echo e(Str::limit($review->comment, 60)); ?></p>
                            </div>
                            <span class="bg-<?php echo e($review->status == 'approved' ? 'green' : ($review->status == 'pending' ? 'yellow' : 'red')); ?>-100 text-<?php echo e($review->status == 'approved' ? 'green' : ($review->status == 'pending' ? 'yellow' : 'red')); ?>-800 px-2 py-1 rounded-full text-xs">
                                <?php echo e($review->status == 'approved' ? 'स्वीकृत' : ($review->status == 'pending' ? 'पेन्डिङ' : 'अस्वीकृत')); ?>

                            </span>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        <?php else: ?>
            <div class="text-center py-4">
                <i class="fas fa-star text-gray-400 text-3xl mb-2"></i>
                <p class="text-gray-500 mb-3">तपाईंले अहिलेसम्म कुनै होस्टेलको समीक्षा दिनुभएको छैन।</p>
                <a href="<?php echo e(route('student.reviews.create')); ?>" class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                    <i class="fas fa-plus mr-1"></i> पहिलो समीक्षा दिनुहोस्
                </a>
            </div>
        <?php endif; ?>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Left Column - Main Info -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Room & Payment Information -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Room Information -->
                <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-200">
                    <div class="flex items-center mb-4">
                        <div class="bg-blue-100 p-2 rounded-lg mr-3">
                            <i class="fas fa-home text-blue-600"></i>
                        </div>
                        <h3 class="text-lg font-bold text-gray-800">कोठा जानकारी</h3>
                    </div>
                    
                    <div class="space-y-3">
                        <div class="flex justify-between">
                            <span class="text-gray-600">होस्टेल:</span>
                            <span class="font-medium text-gray-800"><?php echo e($hostel->name ?? 'उपलब्ध छैन'); ?></span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">कोठा नं.:</span>
                            <span class="font-medium text-gray-800"><?php echo e($student->room->room_number ?? 'उपलब्ध छैन'); ?></span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">कोठा प्रकार:</span>
                            <span class="font-medium text-gray-800"><?php echo e($student->room->type ?? 'उपलब्ध छैन'); ?></span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">मासिक भुक्तानी:</span>
                            <span class="font-bold text-green-600">रु. <?php echo e($student->room->rent ?? 'उपलब्ध छैन'); ?></span>
                        </div>
                    </div>
                    
                    <button class="w-full mt-4 bg-blue-600 hover:bg-blue-700 text-white py-2 rounded-xl font-medium transition-colors">
                        <i class="fas fa-info-circle mr-2"></i>पूर्ण विवरण हेर्नुहोस्
                    </button>
                </div>

                <!-- Payment Status -->
                <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-200">
                    <div class="flex items-center mb-4">
                        <div class="bg-amber-100 p-2 rounded-lg mr-3">
                            <i class="fas fa-credit-card text-amber-600"></i>
                        </div>
                        <h3 class="text-lg font-bold text-gray-800">भुक्तानी स्थिति</h3>
                    </div>
                    
                    <div class="space-y-3">
                        <div class="flex justify-between">
                            <span class="text-gray-600">अन्तिम भुक्तानी:</span>
                            <span class="font-medium text-gray-800"><?php echo e($lastPayment ? 'रु. ' . $lastPayment->amount : 'कुनै भुक्तानी छैन'); ?></span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">अन्तिम मिति:</span>
                            <span class="font-medium text-gray-800"><?php echo e($lastPayment ? $lastPayment->created_at->format('Y-m-d') : 'हाल अपडेट छैन'); ?></span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600">स्थिति:</span>
                            <span class="bg-<?php echo e($paymentStatus == 'Paid' ? 'green' : 'red'); ?>-100 text-<?php echo e($paymentStatus == 'Paid' ? 'green' : 'red'); ?>-800 px-3 py-1 rounded-full text-sm font-medium">
                                <?php echo e($paymentStatus == 'Paid' ? 'भुक्तानी भएको' : 'बाकी'); ?>

                            </span>
                        </div>
                    </div>
                    
                    <button class="w-full mt-4 bg-amber-600 hover:bg-amber-700 text-white py-2 rounded-xl font-medium transition-colors">
                        <i class="fas fa-money-bill mr-2"></i>भुक्तानी गर्नुहोस्
                    </button>
                </div>
            </div>

            <!-- Today's Meal & Recent Circulars -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Today's Meal -->
                <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-200">
                    <div class="flex items-center mb-4">
                        <div class="bg-green-100 p-2 rounded-lg mr-3">
                            <i class="fas fa-utensils text-green-600"></i>
                        </div>
                        <h3 class="text-lg font-bold text-gray-800">आजको खानाको योजना</h3>
                    </div>
                    
                    <?php if($todayMeal): ?>
                        <div class="space-y-3">
                            <?php if(is_array($todayMeal->items)): ?>
                                <?php if(isset($todayMeal->items['breakfast'])): ?>
                                    <div>
                                        <p class="font-medium text-gray-700">बिहानको खाना:</p>
                                        <p class="text-gray-600"><?php echo e($todayMeal->items['breakfast']); ?></p>
                                    </div>
                                <?php endif; ?>
                                <?php if(isset($todayMeal->items['lunch'])): ?>
                                    <div>
                                        <p class="font-medium text-gray-700">दिउँसोको खाना:</p>
                                        <p class="text-gray-600"><?php echo e($todayMeal->items['lunch']); ?></p>
                                    </div>
                                <?php endif; ?>
                                <?php if(isset($todayMeal->items['dinner'])): ?>
                                    <div>
                                        <p class="font-medium text-gray-700">रातिको खाना:</p>
                                        <p class="text-gray-600"><?php echo e($todayMeal->items['dinner']); ?></p>
                                    </div>
                                <?php endif; ?>
                            <?php else: ?>
                                <p class="text-gray-600">मुख्य खाना: <?php echo e($todayMeal->main_dish ?? 'उपलब्ध छैन'); ?></p>
                                <p class="text-gray-600">साइड डिश: <?php echo e($todayMeal->side_dish ?? 'उपलब्ध छैन'); ?></p>
                            <?php endif; ?>
                            <p class="text-sm text-gray-500"><i class="fas fa-clock mr-1"></i>समय: <?php echo e($todayMeal->serving_time ?? 'उपलब्ध छैन'); ?></p>
                        </div>
                    <?php else: ?>
                        <div class="text-center py-4">
                            <i class="fas fa-utensils text-gray-400 text-3xl mb-2"></i>
                            <p class="text-gray-500">आजको खानाको योजना हाल अपडेट छैन</p>
                        </div>
                    <?php endif; ?>
                    
                    <a href="<?php echo e(route('student.meal-menus')); ?>" class="block w-full mt-4 bg-green-600 hover:bg-green-700 text-white py-2 rounded-xl font-medium text-center transition-colors">
                        <i class="fas fa-calendar mr-2"></i>सप्ताहिक मेनु हेर्नुहोस्
                    </a>
                </div>

                <!-- Recent Circulars -->
                <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-200">
                    <div class="flex items-center mb-4">
                        <div class="bg-indigo-100 p-2 rounded-lg mr-3">
                            <i class="fas fa-bullhorn text-indigo-600"></i>
                        </div>
                        <h3 class="text-lg font-bold text-gray-800">हालैका सूचनाहरू</h3>
                    </div>
                    
                    <?php if($recentStudentCirculars && $recentStudentCirculars->count() > 0): ?>
                        <div class="space-y-3">
                            <?php $__currentLoopData = $recentStudentCirculars->take(3); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $circular): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="border-l-4 border-indigo-500 pl-3 py-2 bg-indigo-50 rounded-r-lg">
                                    <p class="font-medium text-gray-800 text-sm"><?php echo e(Str::limit($circular->title, 40)); ?></p>
                                    <p class="text-xs text-gray-600 mt-1"><?php echo e($circular->created_at->diffForHumans()); ?></p>
                                    <?php if(!$circular->recipients->where('user_id', auth()->id())->first()?->is_read): ?>
                                        <span class="inline-block mt-1 bg-red-100 text-red-800 text-xs px-2 py-1 rounded-full">नयाँ</span>
                                    <?php endif; ?>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                        
                        <a href="<?php echo e(route('student.circulars.index')); ?>" class="block w-full mt-4 bg-indigo-600 hover:bg-indigo-700 text-white py-2 rounded-xl font-medium text-center transition-colors">
                            <i class="fas fa-list mr-2"></i>सबै सूचनाहरू हेर्नुहोस्
                        </a>
                    <?php else: ?>
                        <div class="text-center py-4">
                            <i class="fas fa-bullhorn text-gray-400 text-3xl mb-2"></i>
                            <p class="text-gray-500">कुनै नयाँ सूचना छैन</p>
                            <a href="<?php echo e(route('student.circulars.index')); ?>" class="inline-block mt-2 text-indigo-600 hover:text-indigo-800 text-sm font-medium">
                                सबै सूचनाहरू हेर्नुहोस्
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Right Column - Sidebar -->
        <div class="space-y-6">
            <!-- Quick Actions -->
            <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-200">
                <h3 class="text-lg font-bold text-gray-800 mb-4">द्रुत कार्यहरू</h3>
                <div class="grid grid-cols-2 gap-3">
                    <!-- 🏠 Homepage Button in Quick Actions -->
                    <a href="<?php echo e(url('/')); ?>" class="bg-green-50 hover:bg-green-100 p-3 rounded-xl text-center transition-colors group border border-green-100">
                        <div class="text-green-600 text-xl mb-1">
                            <i class="fas fa-home"></i>
                        </div>
                        <div class="text-green-800 text-xs font-medium">मुख्य पृष्ठ</div>
                    </a>
                    
                    <a href="<?php echo e(route('student.profile')); ?>" class="bg-blue-50 hover:bg-blue-100 p-3 rounded-xl text-center transition-colors group border border-blue-100">
                        <div class="text-blue-600 text-xl mb-1">
                            <i class="fas fa-user"></i>
                        </div>
                        <div class="text-blue-800 text-xs font-medium">मेरो प्रोफाइल</div>
                    </a>
                    
                    <a href="<?php echo e(route('student.meal-menus')); ?>" class="bg-green-50 hover:bg-green-100 p-3 rounded-xl text-center transition-colors group border border-green-100">
                        <div class="text-green-600 text-xl mb-1">
                            <i class="fas fa-utensils"></i>
                        </div>
                        <div class="text-green-800 text-xs font-medium">खानाको योजना</div>
                    </a>
                    
                    <a href="<?php echo e(route('student.circulars.index')); ?>" class="bg-indigo-50 hover:bg-indigo-100 p-3 rounded-xl text-center transition-colors group border border-indigo-100 relative">
                        <div class="text-indigo-600 text-xl mb-1">
                            <i class="fas fa-bullhorn"></i>
                        </div>
                        <div class="text-indigo-800 text-xs font-medium">सबै सूचनाहरू</div>
                        <?php if(($unreadCirculars ?? 0) > 0): ?>
                            <span class="absolute -top-1 -right-1 bg-red-500 text-white text-xs w-5 h-5 rounded-full flex items-center justify-center">
                                <?php echo e($unreadCirculars); ?>

                            </span>
                        <?php endif; ?>
                    </a>

                    <a href="<?php echo e(route('student.reviews.index')); ?>" class="bg-purple-50 hover:bg-purple-100 p-3 rounded-xl text-center transition-colors group border border-purple-100">
                        <div class="text-purple-600 text-xl mb-1">
                            <i class="fas fa-star"></i>
                        </div>
                        <div class="text-purple-800 text-xs font-medium">मेरो समीक्षा</div>
                    </a>

                    <button class="bg-amber-50 hover:bg-amber-100 p-3 rounded-xl text-center transition-colors group border border-amber-100">
                        <div class="text-amber-600 text-xl mb-1">
                            <i class="fas fa-credit-card"></i>
                        </div>
                        <div class="text-amber-800 text-xs font-medium">भुक्तानी गर्नुहोस्</div>
                    </button>
                </div>
            </div>

            <!-- Important Circulars -->
            <?php if($importantCirculars && $importantCirculars->count() > 0): ?>
            <div class="bg-red-50 border border-red-200 rounded-2xl p-6">
                <div class="flex items-center mb-4">
                    <div class="bg-red-100 p-2 rounded-lg mr-3">
                        <i class="fas fa-exclamation-triangle text-red-600"></i>
                    </div>
                    <h3 class="text-lg font-bold text-red-800">जरुरी सूचनाहरू</h3>
                </div>
                
                <div class="space-y-3">
                    <?php $__currentLoopData = $importantCirculars->take(2); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $circular): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="bg-white rounded-xl p-3 border border-red-200">
                            <p class="font-bold text-red-800 text-sm"><?php echo e($circular->title); ?></p>
                            <p class="text-xs text-gray-600 mt-1"><?php echo e(Str::limit($circular->content, 60)); ?></p>
                            <p class="text-xs text-gray-500 mt-2"><?php echo e($circular->created_at->diffForHumans()); ?></p>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>
            <?php endif; ?>

            <!-- Upcoming Events -->
            <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-200">
                <div class="flex items-center mb-4">
                    <div class="bg-purple-100 p-2 rounded-lg mr-3">
                        <i class="fas fa-calendar-alt text-purple-600"></i>
                    </div>
                    <h3 class="text-lg font-bold text-gray-800">आगामी घटनाहरू</h3>
                </div>
                
                <?php if($upcomingEvents->count() > 0): ?>
                    <div class="space-y-3">
                        <?php $__currentLoopData = $upcomingEvents->take(2); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $event): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="border-l-4 border-purple-500 pl-3 py-2">
                                <p class="font-medium text-gray-800 text-sm"><?php echo e($event->title); ?></p>
                                <p class="text-xs text-gray-600 mt-1">
                                    <i class="fas fa-clock mr-1"></i>
                                    <?php echo e($event->date->format('M j')); ?> at <?php echo e($event->time); ?>

                                </p>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                    
                    <a href="<?php echo e(route('student.events')); ?>" class="block w-full mt-4 bg-purple-600 hover:bg-purple-700 text-white py-2 rounded-xl font-medium text-center transition-colors">
                        सबै घटनाहरू हेर्नुहोस्
                    </a>
                <?php else: ?>
                    <div class="text-center py-4">
                        <i class="fas fa-calendar-times text-gray-400 text-2xl mb-2"></i>
                        <p class="text-gray-500 text-sm">कुनै आगामी घटना छैन</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Add any interactive functionality here
    console.log('Student dashboard loaded');
});
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.student', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\My Projects\HostelHub\resources\views\student\dashboard.blade.php ENDPATH**/ ?>