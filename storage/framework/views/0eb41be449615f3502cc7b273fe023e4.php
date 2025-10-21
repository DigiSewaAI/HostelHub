

<?php $__env->startSection('title', 'होस्टल ड्यासबोर्ड'); ?>

<?php $__env->startSection('content'); ?>
    <!-- Welcome Section -->
    <div class="bg-white rounded-2xl shadow-sm p-6 mb-6">
        <div class="flex justify-between items-start">
            <div class="flex-1">
                <h1 class="text-2xl font-bold text-gray-800">नमस्ते, <?php echo e(auth()->user()->name); ?>!</h1>
                <p class="text-gray-600 mt-2">यो तपाईंको होस्टल व्यवस्थापन ड्यासबोर्ड हो</p>
                
                <!-- Circular Notifications -->
                <?php if(($organizationCirculars ?? 0) > 0): ?>
                <div class="mt-4 bg-indigo-50 border-l-4 border-indigo-500 p-4 rounded-xl">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <div class="bg-indigo-100 p-2 rounded-lg mr-3">
                                <i class="fas fa-bullhorn text-indigo-600"></i>
                            </div>
                            <div>
                                <h3 class="font-semibold text-indigo-800">तपाईंसँग <?php echo e($organizationCirculars); ?> सक्रिय सूचनाहरू छन्</h3>
                                <p class="text-sm text-indigo-600">हालसम्म <?php echo e($organizationCirculars); ?> सूचनाहरू प्रकाशित गरिएको छ</p>
                            </div>
                        </div>
                        <a href="<?php echo e(route('owner.circulars.index')); ?>" 
                           class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-xl text-sm font-medium no-underline transition-colors">
                            सबै हेर्नुहोस्
                        </a>
                    </div>
                </div>
                <?php endif; ?>
            </div>
            
            <!-- 🏠 Homepage Button in Welcome Section -->
            <div class="ml-6">
                <a href="<?php echo e(url('/')); ?>" 
                   class="inline-flex items-center bg-green-600 hover:bg-green-700 text-white font-medium rounded-xl px-5 py-3 shadow-sm hover:shadow-md transition-all duration-200 no-underline">
                    <i class="fas fa-home mr-2"></i>
                    मुख्य पृष्ठमा जानुहोस्
                </a>
            </div>
        </div>
    </div>

    <!-- Financial Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-6 gap-6 mb-6">
        <!-- Total Monthly Revenue -->
        <div class="bg-white rounded-2xl shadow-sm p-6 border-l-4 border-blue-500">
            <div class="flex justify-between items-center">
                <div>
                    <h3 class="text-sm font-semibold text-gray-600">कुल मासिक आय</h3>
                    <p class="text-2xl font-bold text-gray-800">रु <?php echo e(number_format($totalMonthlyRevenue ?? 0)); ?></p>
                </div>
                <div class="bg-blue-100 p-3 rounded-xl">
                    <i class="fas fa-money-bill-wave text-blue-600 text-xl"></i>
                </div>
            </div>
        </div>

        <!-- Total Security Deposit -->
        <div class="bg-white rounded-2xl shadow-sm p-6 border-l-4 border-green-500">
            <div class="flex justify-between items-center">
                <div>
                    <h3 class="text-sm font-semibold text-gray-600">जम्मा जमानत</h3>
                    <p class="text-2xl font-bold text-gray-800">रु <?php echo e(number_format($totalSecurityDeposit ?? 0)); ?></p>
                </div>
                <div class="bg-green-100 p-3 rounded-xl">
                    <i class="fas fa-shield-alt text-green-600 text-xl"></i>
                </div>
            </div>
        </div>

        <!-- Average Occupancy -->
        <div class="bg-white rounded-2xl shadow-sm p-6 border-l-4 border-purple-500">
            <div class="flex justify-between items-center">
                <div>
                    <h3 class="text-sm font-semibold text-gray-600">औसत ओक्युपेन्सी</h3>
                    <p class="text-2xl font-bold text-gray-800"><?php echo e($averageOccupancy ?? 0); ?>%</p>
                </div>
                <div class="bg-purple-100 p-3 rounded-xl">
                    <i class="fas fa-chart-line text-purple-600 text-xl"></i>
                </div>
            </div>
        </div>

        <!-- Active Hostels -->
        <div class="bg-white rounded-2xl shadow-sm p-6 border-l-4 border-amber-500">
            <div class="flex justify-between items-center">
                <div>
                    <h3 class="text-sm font-semibold text-gray-600">एक्टिभ होस्टेल</h3>
                    <p class="text-2xl font-bold text-gray-800"><?php echo e($activeHostelsCount ?? 0); ?></p>
                </div>
                <div class="bg-amber-100 p-3 rounded-xl">
                    <i class="fas fa-hotel text-amber-600 text-xl"></i>
                </div>
            </div>
        </div>

        <!-- Circulars Card -->
        <div class="bg-white rounded-2xl shadow-sm p-6 border-l-4 border-indigo-500">
            <div class="flex justify-between items-center">
                <div>
                    <h3 class="text-sm font-semibold text-gray-600">सूचनाहरू</h3>
                    <p class="text-2xl font-bold text-gray-800"><?php echo e($organizationCirculars ?? 0); ?></p>
                </div>
                <div class="bg-indigo-100 p-3 rounded-xl">
                    <i class="fas fa-bullhorn text-indigo-600 text-xl"></i>
                </div>
            </div>
            <a href="<?php echo e(route('owner.circulars.index')); ?>" class="text-xs text-indigo-600 hover:text-indigo-800 font-medium mt-2 inline-block">
                सबै हेर्नुहोस् <i class="fas fa-arrow-circle-right ml-1"></i>
            </a>
        </div>

        <!-- 🆕 PUBLIC PAGE STATUS CARD -->
<div class="bg-white rounded-2xl shadow-sm p-6 border-l-4 border-teal-500">
    <div class="flex justify-between items-center">
        <div>
            <h3 class="text-sm font-semibold text-gray-600">सार्वजनिक पृष्ठ</h3>
            <p class="text-2xl font-bold text-gray-800">
                <?php if(isset($hostel) && $hostel->getRawOriginal('is_published')): ?>
                    <span class="text-green-600">प्रकाशित</span>
                <?php else: ?>
                    <span class="text-amber-600">मस्यौदा</span>
                <?php endif; ?>
            </p>
        </div>
        <div class="bg-teal-100 p-3 rounded-xl">
            <i class="fas fa-globe text-teal-600 text-xl"></i>
        </div>
    </div>
    <a href="<?php echo e(route('owner.public-page.edit')); ?>" class="text-xs text-teal-600 hover:text-teal-800 font-medium mt-2 inline-block">
        व्यवस्थापन गर्नुहोस् <i class="fas fa-arrow-circle-right ml-1"></i>
    </a>
</div>

    <?php if(isset($hostel) && $hostel): ?>
        <!-- Hostel Overview -->
        <div class="bg-white rounded-2xl shadow-sm p-6 mb-6">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-xl font-bold text-gray-800"><?php echo e($hostel->name); ?> को विवरण</h2>
                
                <div class="flex space-x-3">
                    <!-- Public Page Quick Action -->
                    <a href="<?php echo e(route('owner.public-page.edit')); ?>" 
                       class="inline-flex items-center bg-teal-600 hover:bg-teal-700 text-white font-medium rounded-xl px-5 py-3 shadow-sm hover:shadow-md transition-all duration-200 no-underline">
                        <i class="fas fa-globe mr-2"></i>
                        सार्वजनिक पृष्ठ
                    </a>
                    
                    <a href="<?php echo e(route('owner.hostels.show', $hostel)); ?>" 
                       class="inline-flex items-center bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-xl px-5 py-3 shadow-sm hover:shadow-md transition-all duration-200 no-underline">
                        <i class="fas fa-eye mr-2"></i>
                        हेर्नुहोस् — Hostel विवरण
                    </a>
                </div>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                <!-- Rooms Card -->
                <div class="bg-blue-50 p-4 rounded-2xl border border-blue-100">
                    <div class="flex justify-between items-center">
                        <div>
                            <h3 class="font-semibold text-blue-800">कुल कोठाहरू</h3>
                            <p class="text-2xl font-bold text-blue-600"><?php echo e($totalRooms ?? 0); ?></p>
                        </div>
                        <div class="bg-blue-600 text-white p-3 rounded-xl">
                            <i class="fas fa-door-open text-xl"></i>
                        </div>
                    </div>
                </div>

                <!-- Occupied Rooms Card -->
                <div class="bg-green-50 p-4 rounded-2xl border border-green-100">
                    <div class="flex justify-between items-center">
                        <div>
                            <h3 class="font-semibold text-green-800">अधिभृत कोठाहरू</h3>
                            <p class="text-2xl font-bold text-green-600"><?php echo e($occupiedRooms ?? 0); ?></p>
                        </div>
                        <div class="bg-green-600 text-white p-3 rounded-xl">
                            <i class="fas fa-users text-xl"></i>
                        </div>
                    </div>
                </div>

                <!-- Students Card -->
                <div class="bg-amber-50 p-4 rounded-2xl border border-amber-100">
                    <div class="flex justify-between items-center">
                        <div>
                            <h3 class="font-semibold text-amber-800">विद्यार्थीहरू</h3>
                            <p class="text-2xl font-bold text-amber-600"><?php echo e($totalStudents ?? 0); ?></p>
                        </div>
                        <div class="bg-amber-600 text-white p-3 rounded-xl">
                            <i class="fas fa-user-graduate text-xl"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Financial Information -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div class="bg-gray-50 p-4 rounded-2xl border border-gray-200">
                    <h3 class="font-semibold text-gray-800 mb-2">मासिक भाडा</h3>
                    <p class="text-2xl font-bold text-green-600">रु <?php echo e(number_format($hostel->monthly_rent ?? 0)); ?></p>
                </div>
                <div class="bg-gray-50 p-4 rounded-2xl border border-gray-200">
                    <h3 class="font-semibold text-gray-800 mb-2">सुरक्षा जमानत</h3>
                    <p class="text-2xl font-bold text-blue-600">रु <?php echo e(number_format($hostel->security_deposit ?? 0)); ?></p>
                </div>
            </div>

            <!-- Today's Meal -->
            <?php if(isset($todayMeal) && $todayMeal): ?>
            <div class="bg-gray-50 p-4 rounded-2xl border border-gray-200">
                <h3 class="font-semibold text-gray-800 mb-2">आजको खाना</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <p class="text-sm text-gray-600">बिहान</p>
                        <p class="font-medium"><?php echo e($todayMeal->breakfast); ?></p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">दिउँसो</p>
                        <p class="font-medium"><?php echo e($todayMeal->lunch); ?></p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">बेलुका</p>
                        <p class="font-medium"><?php echo e($todayMeal->dinner); ?></p>
                    </div>
                </div>
            </div>
            <?php endif; ?>
        </div>
    <?php endif; ?>

    <!-- Quick Actions Section -->
    <div class="bg-white rounded-2xl shadow-sm p-6 mb-6">
        <h2 class="text-xl font-bold text-gray-800 mb-6">द्रुत कार्यहरू</h2>
        <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-8 gap-4">
            <!-- 🏠 Homepage Button in Quick Actions -->
            <a href="<?php echo e(url('/')); ?>" class="p-4 bg-green-50 hover:bg-green-100 rounded-2xl text-center transition-colors no-underline group border border-green-100">
                <div class="text-green-600 text-2xl mb-2 group-hover:scale-110 transition-transform">
                    <i class="fas fa-home"></i>
                </div>
                <div class="font-medium text-green-800 text-sm">मुख्य पृष्ठ</div>
            </a>
            
            <!-- 🆕 PUBLIC PAGE QUICK ACTION -->
            <a href="<?php echo e(route('owner.public-page.edit')); ?>" class="p-4 bg-teal-50 hover:bg-teal-100 rounded-2xl text-center transition-colors no-underline group border border-teal-100">
                <div class="text-teal-600 text-2xl mb-2 group-hover:scale-110 transition-transform">
                    <i class="fas fa-globe"></i>
                </div>
                <div class="font-medium text-teal-800 text-sm">सार्वजनिक पृष्ठ</div>
            </a>

            <!-- 🆕 GALLERY QUICK ACTION -->
            <a href="<?php echo e(route('owner.galleries.index')); ?>" class="p-4 bg-teal-50 hover:bg-teal-100 rounded-2xl text-center transition-colors no-underline group border border-teal-100">
                <div class="text-teal-600 text-2xl mb-2 group-hover:scale-110 transition-transform">
                    <i class="fas fa-images"></i>
                </div>
                <div class="font-medium text-teal-800 text-sm nepali">ग्यालरी</div>
            </a>
            
            <a href="<?php echo e(route('owner.hostels.create')); ?>" class="p-4 bg-blue-50 hover:bg-blue-100 rounded-2xl text-center transition-colors no-underline group border border-blue-100">
                <div class="text-blue-600 text-2xl mb-2 group-hover:scale-110 transition-transform">
                    <i class="fas fa-plus"></i>
                </div>
                <div class="font-medium text-blue-800 text-sm">नयाँ होस्टेल</div>
            </a>
            
            <a href="<?php echo e(route('owner.rooms.index')); ?>" class="p-4 bg-blue-50 hover:bg-blue-100 rounded-2xl text-center transition-colors no-underline group border border-blue-100">
                <div class="text-blue-600 text-2xl mb-2 group-hover:scale-110 transition-transform">
                    <i class="fas fa-door-open"></i>
                </div>
                <div class="font-medium text-blue-800 text-sm">कोठाहरू</div>
            </a>
            
            <a href="<?php echo e(route('owner.students.index')); ?>" class="p-4 bg-green-50 hover:bg-green-100 rounded-2xl text-center transition-colors no-underline group border border-green-100">
                <div class="text-green-600 text-2xl mb-2 group-hover:scale-110 transition-transform">
                    <i class="fas fa-users"></i>
                </div>
                <div class="font-medium text-green-800 text-sm">विद्यार्थीहरू</div>
            </a>
            
            <a href="<?php echo e(route('owner.payments.index')); ?>" class="p-4 bg-purple-50 hover:bg-purple-100 rounded-2xl text-center transition-colors no-underline group border border-purple-100">
                <div class="text-purple-600 text-2xl mb-2 group-hover:scale-110 transition-transform">
                    <i class="fas fa-money-bill-wave"></i>
                </div>
                <div class="font-medium text-purple-800 text-sm">भुक्तानीहरू</div>
            </a>

            <a href="<?php echo e(route('owner.circulars.create')); ?>" class="p-4 bg-indigo-50 hover:bg-indigo-100 rounded-2xl text-center transition-colors no-underline group border border-indigo-100">
                <div class="text-indigo-600 text-2xl mb-2 group-hover:scale-110 transition-transform">
                    <i class="fas fa-bullhorn"></i>
                </div>
                <div class="font-medium text-indigo-800 text-sm">सूचना थप्नुहोस्</div>
            </a>
        </div>
    </div>

    <!-- Circulars & Documents Overview -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Circulars Overview -->
        <div class="bg-white rounded-2xl shadow-sm p-6">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-xl font-bold text-gray-800">सूचना व्यवस्थापन</h2>
                <div class="flex space-x-2">
                    <!-- FIXED: Changed from teal to blue for better visibility -->
                    <a href="<?php echo e(route('owner.circulars.analytics')); ?>" 
                       class="inline-flex items-center bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-xl px-4 py-2 shadow-sm hover:shadow-md transition-all duration-200 no-underline">
                        <i class="fas fa-chart-bar mr-2"></i>
                        विश्लेषण
                    </a>
                    <a href="<?php echo e(route('owner.circulars.index')); ?>" 
                       class="inline-flex items-center bg-indigo-600 hover:bg-indigo-700 text-white font-medium rounded-xl px-5 py-2 shadow-sm hover:shadow-md transition-all duration-200 no-underline">
                        <i class="fas fa-bullhorn mr-2"></i>
                        सबै सूचनाहरू
                    </a>
                </div>
            </div>

            <!-- Recent Circulars -->
            <div class="space-y-3">
                <?php $__empty_1 = true; $__currentLoopData = $recentCirculars ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $circular): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-xl border border-gray-200">
                        <div class="flex items-center">
                            <div class="bg-indigo-100 p-2 rounded-lg mr-3">
                                <i class="fas fa-bullhorn text-indigo-600"></i>
                            </div>
                            <div>
                                <p class="font-medium text-gray-800 text-sm"><?php echo e(Str::limit($circular->title, 40)); ?></p>
                                <p class="text-xs text-gray-600">
                                    <?php echo e($circular->created_at->diffForHumans()); ?>

                                    <span class="ml-2 px-2 py-1 bg-<?php echo e($circular->priority == 'urgent' ? 'red' : ($circular->priority == 'normal' ? 'blue' : 'gray')); ?>-100 text-<?php echo e($circular->priority == 'urgent' ? 'red' : ($circular->priority == 'normal' ? 'blue' : 'gray')); ?>-600 rounded-full text-xs">
                                        <?php echo e($circular->priority_nepali ?? 'सामान्य'); ?>

                                    </span>
                                </p>
                            </div>
                        </div>
                        <div class="flex space-x-2">
                            <a href="<?php echo e(route('owner.circulars.show', $circular)); ?>" 
                               class="text-blue-600 hover:text-blue-800 p-1 transition-colors" 
                               title="हेर्नुहोस्">
                                <i class="fas fa-eye"></i>
                            </a>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <div class="text-center py-4">
                        <i class="fas fa-bullhorn text-gray-400 text-3xl mb-2"></i>
                        <p class="text-gray-500 text-sm">हाल कुनै सूचना छैन</p>
                        <a href="<?php echo e(route('owner.circulars.create')); ?>" class="text-indigo-600 hover:text-indigo-800 text-sm font-medium mt-2 inline-block">
                            पहिलो सूचना सिर्जना गर्नुहोस्
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Documents Overview -->
        <div class="bg-white rounded-2xl shadow-sm p-6">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-xl font-bold text-gray-800">कागजात व्यवस्थापन</h2>
                <a href="<?php echo e(route('owner.documents.index')); ?>" 
                   class="inline-flex items-center bg-purple-600 hover:bg-purple-700 text-white font-medium rounded-xl px-5 py-2 shadow-sm hover:shadow-md transition-all duration-200 no-underline">
                    <i class="fas fa-file-alt mr-2"></i>
                    सबै कागजात हेर्नुहोस्
                </a>
            </div>

            <!-- Recent Documents -->
            <div class="space-y-3">
                <?php $__empty_1 = true; $__currentLoopData = $recentDocuments ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $document): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-xl border border-gray-200">
                        <div class="flex items-center">
                            <div class="bg-purple-100 p-2 rounded-lg mr-3">
                                <i class="fas fa-file text-purple-600"></i>
                            </div>
                            <div>
                                <p class="font-medium text-gray-800 text-sm"><?php echo e($document->original_name); ?></p>
                                <p class="text-xs text-gray-600">
                                    <?php echo e(optional($document->student)->user->name ?? 'अज्ञात विद्यार्थी'); ?> • 
                                    <?php echo e($document->created_at->diffForHumans()); ?>

                                </p>
                            </div>
                        </div>
                        <div class="flex space-x-2">
                            <a href="<?php echo e(route('owner.documents.show', $document)); ?>" 
                               class="text-blue-600 hover:text-blue-800 p-1 transition-colors" 
                               title="हेर्नुहोस्">
                                <i class="fas fa-eye"></i>
                            </a>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <div class="text-center py-4">
                        <i class="fas fa-inbox text-gray-400 text-3xl mb-2"></i>
                        <p class="text-gray-500 text-sm">हाल कुनै कागजात छैन</p>
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
    console.log('Owner dashboard loaded');
});
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.owner', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\My Projects\HostelHub\resources\views/owner/dashboard.blade.php ENDPATH**/ ?>