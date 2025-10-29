

<?php $__env->startSection('title', 'ड्यासबोर्ड'); ?>

<?php $__env->startSection('content'); ?>
    <!-- Loading Indicator -->
    <div id="loadingIndicator" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 z-50 flex items-center justify-center">
        <div class="bg-white p-6 rounded-lg shadow-lg text-center">
            <div class="loading-spinner mx-auto mb-4"></div>
            <p class="text-gray-700 font-medium">ड्यासबोर्ड डाटा लोड हुँदैछ...</p>
        </div>
    </div>

    <?php if(isset($error)): ?>
        <!-- Error Alert with Retry Option -->
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded flex justify-between items-center">
            <div>
                <p class="font-medium"><?php echo e($error); ?></p>
            </div>
            <button onclick="window.location.reload()" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded text-sm">
                <i class="fas fa-redo mr-2"></i>पुनः प्रयास गर्नुहोस्
            </button>
        </div>
    <?php endif; ?>

    <!-- Notification Bell Bar -->
    <div class="bg-white rounded-lg shadow-sm p-4 mb-6 flex items-center justify-between">
        <div class="flex items-center">
            <div class="bg-blue-100 p-3 rounded-full mr-4">
                <i class="fas fa-bell text-blue-600 text-xl"></i>
            </div>
            <div>
                <h3 class="font-semibold">तपाईंसँग <?php echo e($metrics['total_contacts']); ?> सम्पर्क सूचनाहरू र <?php echo e($totalCirculars ?? 0); ?> सूचनाहरू छन्</h3>
                <p class="text-sm text-gray-600">हालसम्म <?php echo e($metrics['total_contacts']); ?> सूचनाहरू प्राप्त भएका छन्</p>
            </div>
        </div>
        <div class="flex space-x-2">
            <a href="<?php echo e(route('admin.circulars.index')); ?>" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg flex items-center transition-colors">
                <i class="fas fa-bullhorn mr-2"></i>
                सूचनाहरू हेर्नुहोस्
            </a>
            <a href="<?php echo e(route('admin.contacts.index')); ?>" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center transition-colors">
                <i class="fas fa-eye mr-2"></i>
                सम्पर्क हेर्नुहोस्
            </a>
        </div>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6 mb-8">
        <!-- Hostels Card -->
        <div class="stat-card bg-gradient-to-r from-blue-50 to-blue-100 border-l-4 border-blue-500 p-5 rounded-lg shadow-sm card-hover">
            <div class="flex justify-between items-start">
                <div>
                    <h3 class="text-lg font-bold text-gray-800">होस्टलहरू</h3>
                    <p class="text-3xl font-bold mt-2 text-gray-900"><?php echo e(number_format($metrics['total_hostels'])); ?></p>
                </div>
                <div class="bg-blue-500 text-white p-3 rounded-lg">
                    <i class="fas fa-building text-xl"></i>
                </div>
            </div>
            <p class="text-sm text-gray-600 mt-3">कुल दर्ता भएका होस्टलहरू</p>
        </div>
        
        <!-- Rooms Card -->
        <div class="stat-card bg-gradient-to-r from-green-50 to-green-100 border-l-4 border-green-500 p-5 rounded-lg shadow-sm card-hover">
            <div class="flex justify-between items-start">
                <div>
                    <h3 class="text-lg font-bold text-gray-800">कोठाहरू</h3>
                    <p class="text-3xl font-bold mt-2 text-gray-900"><?php echo e(number_format($metrics['total_rooms'])); ?></p>
                </div>
                <div class="bg-green-500 text-white p-3 rounded-lg">
                    <i class="fas fa-door-open text-xl"></i>
                </div>
            </div>
            <p class="text-sm text-gray-600 mt-3">
                <span class="text-green-600 font-medium"><?php echo e($metrics['room_occupancy']); ?>%</span> अधिभोग दर
            </p>
        </div>
        
        <!-- Students Card -->
        <div class="stat-card bg-gradient-to-r from-amber-50 to-amber-100 border-l-4 border-amber-500 p-5 rounded-lg shadow-sm card-hover">
            <div class="flex justify-between items-start">
                <div>
                    <h3 class="text-lg font-bold text-gray-800">विद्यार्थीहरू</h3>
                    <p class="text-3xl font-bold mt-2 text-gray-900"><?php echo e(number_format($metrics['total_students'])); ?></p>
                </div>
                <div class="bg-amber-500 text-white p-3 rounded-lg">
                    <i class="fas fa-users text-xl"></i>
                </div>
            </div>
            <p class="text-sm text-gray-600 mt-3">कुल दर्ता भएका विद्यार्थीहरू</p>
        </div>
        
        <!-- Circulars Card -->
        <div class="stat-card bg-gradient-to-r from-indigo-50 to-indigo-100 border-l-4 border-indigo-500 p-5 rounded-lg shadow-sm card-hover">
            <div class="flex justify-between items-start">
                <div>
                    <h3 class="text-lg font-bold text-gray-800">सूचनाहरू</h3>
                    <p class="text-3xl font-bold mt-2 text-gray-900"><?php echo e(number_format($totalCirculars ?? 0)); ?></p>
                </div>
                <div class="bg-indigo-500 text-white p-3 rounded-lg">
                    <i class="fas fa-bullhorn text-xl"></i>
                </div>
            </div>
            <p class="text-sm text-gray-600 mt-3">कुल प्रकाशित सूचनाहरू</p>
            <a href="<?php echo e(route('admin.circulars.index')); ?>" class="text-xs text-indigo-600 hover:text-indigo-800 font-medium mt-2 inline-block">
                सबै हेर्नुहोस् <i class="fas fa-arrow-circle-right ml-1"></i>
            </a>
        </div>
    </div>

    <!-- Circular Statistics Section -->
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6 mb-8">
        <!-- Total Circulars Card -->
        <div class="stat-card bg-gradient-to-r from-indigo-50 to-indigo-100 border-l-4 border-indigo-500 p-5 rounded-lg shadow-sm card-hover">
            <div class="flex justify-between items-start">
                <div>
                    <h3 class="text-lg font-bold text-gray-800">कुल सूचनाहरू</h3>
                    <p class="text-3xl font-bold mt-2 text-gray-900"><?php echo e(number_format($totalCirculars ?? 0)); ?></p>
                </div>
                <div class="bg-indigo-500 text-white p-3 rounded-lg">
                    <i class="fas fa-bullhorn text-xl"></i>
                </div>
            </div>
            <p class="text-sm text-gray-600 mt-3">कुल प्रकाशित सूचनाहरू</p>
            <a href="<?php echo e(route('admin.circulars.index')); ?>" class="text-xs text-indigo-600 hover:text-indigo-800 font-medium mt-2 inline-block">
                सबै हेर्नुहोस् <i class="fas fa-arrow-circle-right ml-1"></i>
            </a>
        </div>
        
        <!-- Published Circulars -->
        <div class="stat-card bg-gradient-to-r from-green-50 to-green-100 border-l-4 border-green-500 p-5 rounded-lg shadow-sm card-hover">
            <div class="flex justify-between items-start">
                <div>
                    <h3 class="text-lg font-bold text-gray-800">प्रकाशित</h3>
                    <p class="text-3xl font-bold mt-2 text-gray-900"><?php echo e(number_format($publishedCirculars ?? 0)); ?></p>
                </div>
                <div class="bg-green-500 text-white p-3 rounded-lg">
                    <i class="fas fa-paper-plane text-xl"></i>
                </div>
            </div>
            <p class="text-sm text-gray-600 mt-3">सक्रिय सूचनाहरू</p>
        </div>
        
        <!-- Urgent Circulars -->
        <div class="stat-card bg-gradient-to-r from-red-50 to-red-100 border-l-4 border-red-500 p-5 rounded-lg shadow-sm card-hover">
            <div class="flex justify-between items-start">
                <div>
                    <h3 class="text-lg font-bold text-gray-800">जरुरी</h3>
                    <p class="text-3xl font-bold mt-2 text-gray-900"><?php echo e(number_format($urgentCirculars ?? 0)); ?></p>
                </div>
                <div class="bg-red-500 text-white p-3 rounded-lg">
                    <i class="fas fa-exclamation-triangle text-xl"></i>
                </div>
            </div>
            <p class="text-sm text-gray-600 mt-3">जरुरी प्राथमिकताका</p>
        </div>
        
        <!-- Read Rate -->
        <div class="stat-card bg-gradient-to-r from-blue-50 to-blue-100 border-l-4 border-blue-500 p-5 rounded-lg shadow-sm card-hover">
            <div class="flex justify-between items-start">
                <div>
                    <h3 class="text-lg font-bold text-gray-800">पढ्ने दर</h3>
                    <p class="text-3xl font-bold mt-2 text-gray-900"><?php echo e($circularReadRate ?? 0); ?>%</p>
                </div>
                <div class="bg-blue-500 text-white p-3 rounded-lg">
                    <i class="fas fa-eye text-xl"></i>
                </div>
            </div>
            <p class="text-sm text-gray-600 mt-3">सम्पूर्ण पढ्ने दर</p>
        </div>
    </div>

    <!-- Quick Circular Actions -->
    <div class="bg-white rounded-2xl shadow-sm p-6 mb-6">
        <h2 class="text-xl font-bold text-gray-800 mb-4">सूचना कार्यहरू</h2>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <a href="<?php echo e(route('admin.circulars.create')); ?>" class="p-4 bg-indigo-50 hover:bg-indigo-100 rounded-lg text-center transition-colors group border border-indigo-100">
                <div class="text-indigo-600 text-2xl mb-2 group-hover:scale-110 transition-transform">
                    <i class="fas fa-plus-circle"></i>
                </div>
                <div class="font-medium text-indigo-800">नयाँ सूचना</div>
            </a>
            
            <a href="<?php echo e(route('admin.circulars.index')); ?>" class="p-4 bg-green-50 hover:bg-green-100 rounded-lg text-center transition-colors group border border-green-100">
                <div class="text-green-600 text-2xl mb-2 group-hover:scale-110 transition-transform">
                    <i class="fas fa-list"></i>
                </div>
                <div class="font-medium text-green-800">सबै सूचनाहरू</div>
            </a>
            
            <a href="<?php echo e(route('admin.circulars.analytics')); ?>" class="p-4 bg-blue-50 hover:bg-blue-100 rounded-lg text-center transition-colors group border border-blue-100">
                <div class="text-blue-600 text-2xl mb-2 group-hover:scale-110 transition-transform">
                    <i class="fas fa-chart-bar"></i>
                </div>
                <div class="font-medium text-blue-800">विश्लेषण</div>
            </a>
            
            <a href="<?php echo e(route('admin.circulars.templates')); ?>" class="p-4 bg-purple-50 hover:bg-purple-100 rounded-lg text-center transition-colors group border border-purple-100">
                <div class="text-purple-600 text-2xl mb-2 group-hover:scale-110 transition-transform">
                    <i class="fas fa-stamp"></i>
                </div>
                <div class="font-medium text-purple-800">टेम्प्लेटहरू</div>
            </a>
        </div>
    </div>

    <!-- Room Status Overview -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
        <div class="bg-blue-50 p-4 rounded-2xl text-center hover:bg-blue-100 transition-colors border border-blue-100">
            <div class="text-blue-600 text-2xl font-bold"><?php echo e(number_format($metrics['available_rooms'])); ?></div>
            <div class="text-sm text-blue-800 font-medium">उपलब्ध कोठाहरू</div>
        </div>
        <div class="bg-green-50 p-4 rounded-2xl text-center hover:bg-green-100 transition-colors border border-green-100">
            <div class="text-green-600 text-2xl font-bold"><?php echo e(number_format($metrics['occupied_rooms'])); ?></div>
            <div class="text-sm text-green-800 font-medium">अधिभृत कोठाहरू</div>
        </div>
        <div class="bg-amber-50 p-4 rounded-2xl text-center hover:bg-amber-100 transition-colors border border-amber-100">
            <div class="text-amber-600 text-2xl font-bold"><?php echo e(number_format($metrics['reserved_rooms'])); ?></div>
            <div class="text-sm text-amber-800 font-medium">आरक्षित कोठाहरू</div>
        </div>
        <div class="bg-red-50 p-4 rounded-2xl text-center hover:bg-red-100 transition-colors border border-red-100">
            <div class="text-red-600 text-2xl font-bold"><?php echo e(number_format($metrics['maintenance_rooms'])); ?></div>
            <div class="text-sm text-red-800 font-medium">मर्मतकोठाहरू</div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Recent Activities -->
        <div class="bg-white rounded-2xl shadow-sm p-6">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-xl font-bold text-gray-800">हालका गतिविधिहरू</h2>
                <button class="text-blue-600 hover:text-blue-800 text-sm font-medium flex items-center">
                    <i class="fas fa-filter mr-1"></i> छान्नुहोस्
                </button>
            </div>
            
            <div class="relative">
                <!-- Timeline style activities -->
                <div class="border-l-2 border-gray-200 ml-4 pb-6">
                    <?php if($metrics['recent_students']->isEmpty() && $metrics['recent_contacts']->isEmpty() && $metrics['recent_hostels']->isEmpty() && $metrics['recent_documents']->isEmpty() && empty($recentCirculars)): ?>
                    <!-- Empty State -->
                    <div class="text-center py-8">
                        <i class="fas fa-inbox text-gray-400 text-5xl mb-4"></i>
                        <h4 class="text-lg font-semibold text-gray-600">कुनै गतिविधि छैन</h4>
                        <p class="text-gray-500 mb-4">हाल कुनै गतिविधि दर्ता भएको छैन</p>
                        <a href="<?php echo e(route('admin.students.create')); ?>" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg inline-flex items-center">
                            <i class="fas fa-plus mr-2"></i> नयाँ विद्यार्थी थप्नुहोस्
                        </a>
                    </div>
                    <?php else: ?>
                    <!-- Recent Circulars -->
                    <?php $__currentLoopData = $recentCirculars ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $circular): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="flex items-start mb-6">
                        <div class="bg-indigo-500 rounded-full p-2 -ml-3 mt-1 relative">
                            <i class="fas fa-bullhorn text-white text-sm"></i>
                        </div>
                        <div class="ml-6 flex-1">
                            <h4 class="font-semibold text-gray-800">नयाँ सूचना प्रकाशित</h4>
                            <p class="text-sm text-gray-600 mt-1">
                                <?php echo e($circular->title); ?> - <?php echo e(\Illuminate\Support\Str::limit($circular->content, 50)); ?>

                            </p>
                            <div class="flex items-center mt-2">
                                <span class="text-xs text-gray-500 bg-gray-100 py-1 px-2 rounded-full">
                                    <i class="far fa-clock mr-1"></i> <?php echo e($circular->created_at->diffForHumans()); ?>

                                </span>
                                <span class="text-xs text-indigo-600 bg-indigo-100 py-1 px-2 rounded-full ml-2">
                                    <?php echo e($circular->priority_nepali ?? 'सामान्य'); ?>

                                </span>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    
                    <!-- Recent Students -->
                    <?php $__currentLoopData = $metrics['recent_students']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $student): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="flex items-start mb-6">
                        <div class="bg-red-500 rounded-full p-2 -ml-3 mt-1 relative">
                            <i class="fas fa-user-plus text-white text-sm"></i>
                        </div>
                        <div class="ml-6 flex-1">
                            <h4 class="font-semibold text-gray-800">नयाँ विद्यार्थी दर्ता</h4>
                            <p class="text-sm text-gray-600 mt-1">
                                <?php echo e($student->name); ?> (<?php echo e(optional(optional($student->room)->hostel)->name ?? 'अज्ञात होस्टल'); ?>)
                            </p>
                            <div class="flex items-center mt-2">
                                <span class="text-xs text-gray-500 bg-gray-100 py-1 px-2 rounded-full">
                                    <i class="far fa-clock mr-1"></i> <?php echo e($student->created_at->diffForHumans()); ?>

                                </span>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    
                    <!-- Recent Contacts -->
                    <?php $__currentLoopData = $metrics['recent_contacts']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $contact): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="flex items-start mb-6">
                        <div class="bg-blue-500 rounded-full p-2 -ml-3 mt-1 relative">
                            <i class="fas fa-envelope text-white text-sm"></i>
                        </div>
                        <div class="ml-6 flex-1">
                            <h4 class="font-semibold text-gray-800">नयाँ सम्पर्क सन्देश</h4>
                            <p class="text-sm text-gray-600 mt-1"><?php echo e($contact->name); ?> - <?php echo e(\Illuminate\Support\Str::limit($contact->message, 50)); ?></p>
                            <div class="flex items-center mt-2">
                                <span class="text-xs text-gray-500 bg-gray-100 py-1 px-2 rounded-full">
                                    <i class="far fa-clock mr-1"></i> <?php echo e($contact->created_at->diffForHumans()); ?>

                                </span>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    
                    <!-- Recent Hostels -->
                    <?php $__currentLoopData = $metrics['recent_hostels']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $hostel): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="flex items-start mb-6">
                        <div class="bg-amber-500 rounded-full p-2 -ml-3 mt-1 relative">
                            <i class="fas fa-building text-white text-sm"></i>
                        </div>
                        <div class="ml-6 flex-1">
                            <h4 class="font-semibold text-gray-800">नयाँ होस्टल दर्ता</h4>
                            <p class="text-sm text-gray-600 mt-1"><?php echo e($hostel->name); ?> (<?php echo e($hostel->rooms_count); ?> कोठाहरू)</p>
                            <div class="flex items-center mt-2">
                                <span class="text-xs text-gray-500 bg-gray-100 py-1 px-2 rounded-full">
                                    <i class="far fa-clock mr-1"></i> <?php echo e($hostel->created_at->diffForHumans()); ?>

                                </span>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                    <!-- Recent Documents -->
                    <?php $__currentLoopData = $metrics['recent_documents']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $document): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="flex items-start mb-6">
                        <div class="bg-purple-500 rounded-full p-2 -ml-3 mt-1 relative">
                            <i class="fas fa-file-upload text-white text-sm"></i>
                        </div>
                        <div class="ml-6 flex-1">
                            <h4 class="font-semibold text-gray-800">नयाँ कागजात अपलोड</h4>
                            <p class="text-sm text-gray-600 mt-1">
                                <?php echo e($document->original_name); ?> 
                                (<?php echo e(optional($document->student)->user->name ?? 'अज्ञात विद्यार्थी'); ?>)
                            </p>
                            <div class="flex items-center mt-2">
                                <span class="text-xs text-gray-500 bg-gray-100 py-1 px-2 rounded-full">
                                    <i class="far fa-clock mr-1"></i> <?php echo e($document->created_at->diffForHumans()); ?>

                                </span>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php endif; ?>
                </div>

                <!-- Activity Count (Removed problematic pagination) -->
                <?php if(!$metrics['recent_students']->isEmpty() || !$metrics['recent_contacts']->isEmpty() || !$metrics['recent_hostels']->isEmpty() || !$metrics['recent_documents']->isEmpty() || !empty($recentCirculars)): ?>
                <div class="mt-6">
                    <p class="text-sm text-gray-600">
                        देखाइएको: 
                        <span class="font-medium">
                            <?php echo e($metrics['recent_students']->count() + $metrics['recent_contacts']->count() + $metrics['recent_hostels']->count() + $metrics['recent_documents']->count() + count($recentCirculars ?? [])); ?>

                        </span> गतिविधिहरू
                    </p>
                </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="bg-white rounded-2xl shadow-sm p-6">
            <h2 class="text-xl font-bold text-gray-800 mb-6">द्रुत कार्यहरू</h2>
            <div class="grid grid-cols-2 gap-4">
                <a href="<?php echo e(route('admin.students.create')); ?>" class="p-4 bg-blue-50 hover:bg-blue-100 rounded-lg text-center transition-colors group border border-blue-100">
                    <div class="text-blue-600 text-2xl mb-2 group-hover:scale-110 transition-transform">
                        <i class="fas fa-user-plus"></i>
                    </div>
                    <div class="font-medium text-blue-800">विद्यार्थी थप्नुहोस्</div>
                </a>
                
                <a href="<?php echo e(route('admin.rooms.create')); ?>" class="p-4 bg-green-50 hover:bg-green-100 rounded-lg text-center transition-colors group border border-green-100">
                    <div class="text-green-600 text-2xl mb-2 group-hover:scale-110 transition-transform">
                        <i class="fas fa-door-open"></i>
                    </div>
                    <div class="font-medium text-green-800">कोठा थप्नुहोस्</div>
                </a>
                
                <a href="<?php echo e(route('admin.circulars.create')); ?>" class="p-4 bg-indigo-50 hover:bg-indigo-100 rounded-lg text-center transition-colors group border border-indigo-100">
                    <div class="text-indigo-600 text-2xl mb-2 group-hover:scale-110 transition-transform">
                        <i class="fas fa-bullhorn"></i>
                    </div>
                    <div class="font-medium text-indigo-800">सूचना थप्नुहोस्</div>
                </a>
                
                <a href="<?php echo e(route('admin.circulars.analytics')); ?>" class="p-4 bg-teal-50 hover:bg-teal-100 rounded-lg text-center transition-colors group border border-teal-100">
                    <div class="text-teal-600 text-2xl mb-2 group-hover:scale-110 transition-transform">
                        <i class="fas fa-chart-bar"></i>
                    </div>
                    <div class="font-medium text-teal-800">विश्लेषण हेर्नुहोस्</div>
                </a>
            </div>
        </div>
    </div>

    <!-- Additional Info Section -->
    <div class="mt-6 grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- System Status -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-xl font-bold mb-4">प्रणाली स्थिति</h3>
            <div class="space-y-4">
                <div class="flex items-center justify-between p-4 border rounded-lg hover:bg-gray-50 transition-colors">
                    <div class="flex items-center">
                        <div class="bg-green-100 p-2 rounded-lg mr-4">
                            <i class="fas fa-database text-green-600"></i>
                        </div>
                        <div>
                            <h4 class="font-semibold">डाटाबेस कनेक्सन</h4>
                            <p class="text-sm text-gray-600">सफलतापूर्वक जडान भएको छ</p>
                        </div>
                    </div>
                    <div class="text-green-600">
                        <i class="fas fa-check-circle text-2xl"></i>
                    </div>
                </div>
                
                <div class="flex items-center justify-between p-4 border rounded-lg hover:bg-gray-50 transition-colors">
                    <div class="flex items-center">
                        <div class="bg-green-100 p-2 rounded-lg mr-4">
                            <i class="fas fa-server text-green-600"></i>
                        </div>
                        <div>
                            <h4 class="font-semibold">सर्भर स्थिति</h4>
                            <p class="text-sm text-gray-600">सबै सेवाहरू सामान्य रूपमा चलिरहेका छन्</p>
                        </div>
                    </div>
                    <div class="text-green-600">
                        <i class="fas fa-check-circle text-2xl"></i>
                    </div>
                </div>
                
                <div class="flex items-center justify-between p-4 border rounded-lg hover:bg-gray-50 transition-colors">
                    <div class="flex items-center">
                        <div class="bg-green-100 p-2 rounded-lg mr-4">
                            <i class="fas fa-shield-alt text-green-600"></i>
                        </div>
                        <div>
                            <h4 class="font-semibold">सुरक्षा स्थिति</h4>
                            <p class="text-sm text-gray-600">प्रणाली सुरक्षित रूपमा चलिरहेको छ</p>
                        </div>
                    </div>
                    <div class="text-green-600">
                        <i class="fas fa-check-circle text-2xl"></i>
                    </div>
                </div>

                <div class="flex items-center justify-between p-4 border rounded-lg hover:bg-gray-50 transition-colors">
                    <div class="flex items-center">
                        <div class="bg-blue-100 p-2 rounded-lg mr-4">
                            <i class="fas fa-bullhorn text-blue-600"></i>
                        </div>
                        <div>
                            <h4 class="font-semibold">सूचना प्रणाली</h4>
                            <p class="text-sm text-gray-600"><?php echo e($totalCirculars ?? 0); ?> सक्रिय सूचनाहरू</p>
                        </div>
                    </div>
                    <div class="text-blue-600">
                        <i class="fas fa-check-circle text-2xl"></i>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Additional Quick Actions -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-xl font-bold mb-4">अन्य कार्यहरू</h3>
            <div class="grid grid-cols-2 gap-4">
                <a href="<?php echo e(route('admin.hostels.create')); ?>" class="p-4 bg-amber-50 hover:bg-amber-100 rounded-lg text-center transition-colors group">
                    <div class="text-amber-600 text-2xl mb-2 group-hover:scale-110 transition-transform">
                        <i class="fas fa-building"></i>
                    </div>
                    <div class="font-medium text-amber-800">होस्टल थप्नुहोस्</div>
                </a>
                
                <a href="<?php echo e(route('admin.documents.index')); ?>" class="p-4 bg-purple-50 hover:bg-purple-100 rounded-lg text-center transition-colors group">
                    <div class="text-purple-600 text-2xl mb-2 group-hover:scale-110 transition-transform">
                        <i class="fas fa-file-alt"></i>
                    </div>
                    <div class="font-medium text-purple-800">कागजातहरू हेर्नुहोस्</div>
                </a>

                <a href="<?php echo e(route('admin.contacts.index')); ?>" class="p-4 bg-blue-50 hover:bg-blue-100 rounded-lg text-center transition-colors group">
                    <div class="text-blue-600 text-2xl mb-2 group-hover:scale-110 transition-transform">
                        <i class="fas fa-envelope"></i>
                    </div>
                    <div class="font-medium text-blue-800">सम्पर्कहरू हेर्नुहोस्</div>
                </a>

                <a href="<?php echo e(route('admin.settings.index')); ?>" class="p-4 bg-gray-50 hover:bg-gray-100 rounded-lg text-center transition-colors group">
                    <div class="text-gray-600 text-2xl mb-2 group-hover:scale-110 transition-transform">
                        <i class="fas fa-cog"></i>
                    </div>
                    <div class="font-medium text-gray-800">सेटिङहरू</div>
                </a>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
<script>
// Show loading indicator when page is loading
document.addEventListener('DOMContentLoaded', function() {
    const loadingIndicator = document.getElementById('loadingIndicator');
    if (loadingIndicator) {
        setTimeout(() => {
            loadingIndicator.classList.add('hidden');
        }, 500);
    }
});

// Add hover effects to cards
document.querySelectorAll('.card-hover').forEach(card => {
    card.addEventListener('mouseenter', function() {
        this.style.transform = 'translateY(-5px)';
        this.style.boxShadow = '0 10px 20px rgba(0, 0, 0, 0.1)';
    });
    
    card.addEventListener('mouseleave', function() {
        this.style.transform = 'translateY(0)';
        this.style.boxShadow = '0 4px 6px rgba(0, 0, 0, 0.04)';
    });
});

// Clear dashboard cache function
async function clearDashboardCache() {
    const loadingIndicator = document.getElementById('loadingIndicator');
    if (loadingIndicator) {
        loadingIndicator.classList.remove('hidden');
    }

    try {
        const response = await fetch('<?php echo e(route("admin.dashboard.clear-cache")); ?>', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>'
            }
        });

        const result = await response.json();
        
        if (result.success) {
            // Show success message
            showNotification('क्यास सफलतापूर्वक मेटाइयो', 'success');
            // Reload page after a short delay
            setTimeout(() => {
                window.location.reload();
            }, 1000);
        } else {
            showNotification('क्यास मेटाउन असफल भयो', 'error');
        }
    } catch (error) {
        console.error('Error clearing cache:', error);
        showNotification('त्रुटि भयो', 'error');
    } finally {
        if (loadingIndicator) {
            loadingIndicator.classList.add('hidden');
        }
    }
}

// Notification function
function showNotification(message, type = 'info') {
    const notification = document.createElement('div');
    notification.className = `fixed top-4 right-4 p-4 rounded-lg shadow-lg z-50 ${
        type === 'success' ? 'bg-green-500 text-white' :
        type === 'error' ? 'bg-red-500 text-white' :
        'bg-blue-500 text-white'
    }`;
    notification.innerHTML = `
        <div class="flex items-center">
            <i class="fas fa-${type === 'success' ? 'check' : type === 'error' ? 'exclamation-triangle' : 'info'} mr-2"></i>
            <span>${message}</span>
        </div>
    `;
    
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.remove();
    }, 3000);
}

// Add smooth scrolling for better UX
document.addEventListener('DOMContentLoaded', function() {
    // Smooth scroll for anchor links
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
});
</script>

<style>
.card-hover {
    transition: all 0.3s ease;
}

.stat-card {
    transition: all 0.3s ease;
}

.loading-spinner {
    display: inline-block;
    width: 2rem;
    height: 2rem;
    border: 3px solid rgba(59, 130, 246, 0.3);
    border-radius: 50%;
    border-top-color: #3b82f6;
    animation: spin 1s linear infinite;
}

@keyframes spin {
    to { transform: rotate(360deg); }
}

/* Responsive improvements */
@media (max-width: 640px) {
    .grid-cols-2 {
        grid-template-columns: 1fr;
    }
    
    .stat-card {
        padding: 1rem;
    }
}

/* Print styles */
@media print {
    .card-hover {
        transform: none !important;
        box-shadow: none !important;
    }
    
    .bg-gradient-to-r {
        background: white !important;
    }
}
</style>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\My Projects\HostelHub\resources\views\admin\dashboard.blade.php ENDPATH**/ ?>