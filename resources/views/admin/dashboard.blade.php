@extends('layouts.admin')

@section('title', 'ड्यासबोर्ड')

@section('content')
    <!-- Notification Bell Bar -->
    <div class="bg-white rounded-lg shadow-sm p-4 mb-6 flex items-center justify-between">
        <div class="flex items-center">
            <div class="bg-blue-100 p-3 rounded-full mr-4">
                <i class="fas fa-bell text-blue-600 text-xl"></i>
            </div>
            <div>
                <h3 class="font-semibold">तपाईंसँग ३ नयाँ सूचनाहरू छन्</h3>
                <p class="text-sm text-gray-600">हालसम्म १२ सूचनाहरू प्राप्त भएका छन्</p>
            </div>
        </div>
        <button class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center">
            <i class="fas fa-eye mr-2"></i>
            सबै हेर्नुहोस्
        </button>
    </div>

    <!-- Nepali Guide Section -->
    <div class="guide-note mb-6">
        <h3 class="font-bold text-lg text-amber-800">नमस्ते! HostelHub मा स्वागत छ</h3>
        <p class="mt-2">यो ड्यासबोर्ड HostelHub प्रणालीको लागि बनाइएको हो। यसले तीनवटा भूमिकाहरू (प्रशासक, मालिक, विद्यार्थी) को लागि अलग-अलग इन्टरफेस देखाउँछ।</p>
        <p class="mt-2"><strong>यो के मात्र UI प्रदर्शन हो</strong> - वास्तविक प्रणालीमा तपाईंको भूमिका अनुसार तपाईंलाई फरक-फरक विकल्पहरू देखिनेछन्।</p>
    </div>
    
    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <h2 class="text-2xl font-bold mb-6">प्रणाली संक्षिप्त विवरण</h2>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <div class="stat-card bg-gradient-to-r from-blue-50 to-blue-100 border-l-4 border-blue-500 p-5 rounded-lg shadow-sm card-hover">
                <div class="flex justify-between items-start">
                    <div>
                        <h3 class="text-lg font-bold text-gray-800">होस्टलहरू</h3>
                        <p class="text-3xl font-bold mt-2 text-gray-900">१२</p>
                    </div>
                    <div class="bg-blue-500 text-white p-3 rounded-lg">
                        <i class="fas fa-building text-xl"></i>
                    </div>
                </div>
                <p class="text-sm text-gray-600 mt-3"><span class="text-green-600 font-medium"><i class="fas fa-arrow-up"></i> २ नयाँ</span> यस महिना</p>
            </div>
            
            <div class="stat-card bg-gradient-to-r from-green-50 to-green-100 border-l-4 border-green-500 p-5 rounded-lg shadow-sm card-hover">
                <div class="flex justify-between items-start">
                    <div>
                        <h3 class="text-lg font-bold text-gray-800">कोठाहरू</h3>
                        <p class="text-3xl font-bold mt-2 text-gray-900">८४</p>
                    </div>
                    <div class="bg-green-500 text-white p-3 rounded-lg">
                        <i class="fas fa-door-open text-xl"></i>
                    </div>
                </div>
                <p class="text-sm text-gray-600 mt-3"><span class="text-green-600 font-medium"><i class="fas fa-arrow-up"></i> ५%</span> अधिभोग वृद्धि</p>
            </div>
            
            <div class="stat-card bg-gradient-to-r from-amber-50 to-amber-100 border-l-4 border-amber-500 p-5 rounded-lg shadow-sm card-hover">
                <div class="flex justify-between items-start">
                    <div>
                        <h3 class="text-lg font-bold text-gray-800">विद्यार्थीहरू</h3>
                        <p class="text-3xl font-bold mt-2 text-gray-900">२१६</p>
                    </div>
                    <div class="bg-amber-500 text-white p-3 rounded-lg">
                        <i class="fas fa-users text-xl"></i>
                    </div>
                </div>
                <p class="text-sm text-gray-600 mt-3"><span class="text-green-600 font-medium"><i class="fas fa-arrow-up"></i> १२ नयाँ</span> दर्ताहरू</p>
            </div>
            
            <div class="stat-card bg-gradient-to-r from-red-50 to-red-100 border-l-4 border-red-500 p-5 rounded-lg shadow-sm card-hover">
                <div class="flex justify-between items-start">
                    <div>
                        <h3 class="text-lg font-bold text-gray-800">आम्दानी</h3>
                        <p class="text-3xl font-bold mt-2 text-gray-900">रु १८.२K</p>
                    </div>
                    <div class="bg-red-500 text-white p-3 rounded-lg">
                        <i class="fas fa-dollar-sign text-xl"></i>
                    </div>
                </div>
                <p class="text-sm text-gray-600 mt-3"><span class="text-green-600 font-medium"><i class="fas fa-arrow-up"></i> ८%</span> गत महिना भन्दा</p>
            </div>
        </div>
        
        <div class="border-t pt-6">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-xl font-bold">हालको गतिविधिहरू</h3>
                <button class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                    <i class="fas fa-history mr-1"></i> सबै गतिविधि हेर्नुहोस्
                </button>
            </div>
            
            <div class="relative">
                <!-- Timeline style activities -->
                <div class="border-l-2 border-gray-200 ml-4 pb-10">
                    <div class="flex items-start mb-6">
                        <div class="bg-blue-500 rounded-full p-2 -ml-3 mt-1 relative">
                            <i class="fas fa-key text-white text-sm"></i>
                        </div>
                        <div class="ml-6">
                            <h4 class="font-semibold text-gray-800">अनुमति अपडेट गरियो</h4>
                            <p class="text-sm text-gray-600 mt-1">प्रशासकले होस्टल प्रबन्धकको लागि भूमिका अनुमतिहरू अपडेट गरे</p>
                            <div class="flex items-center mt-2">
                                <span class="text-xs text-gray-500 bg-gray-100 py-1 px-2 rounded-full"><i class="far fa-clock mr-1"></i> २ घण्टा अघि</span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="flex items-start mb-6">
                        <div class="bg-amber-500 rounded-full p-2 -ml-3 mt-1 relative">
                            <i class="fas fa-building text-white text-sm"></i>
                        </div>
                        <div class="ml-6">
                            <h4 class="font-semibold text-gray-800">नयाँ होस्टल दर्ता भयो</h4>
                            <p class="text-sm text-gray-600 mt-1">मालिक "सूर्योदय होस्टल" ले नयाँ सम्पत्ति थपे</p>
                            <div class="flex items-center mt-2">
                                <span class="text-xs text-gray-500 bg-gray-100 py-1 px-2 rounded-full"><i class="far fa-clock mr-1"></i> ५ घण्टा अघि</span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="flex items-start">
                        <div class="bg-red-500 rounded-full p-2 -ml-3 mt-1 relative">
                            <i class="fas fa-user-plus text-white text-sm"></i>
                        </div>
                        <div class="ml-6">
                            <h4 class="font-semibold text-gray-800">विद्यार्थी दर्ता</h4>
                            <p class="text-sm text-gray-600 mt-1">नयाँ विद्यार्थी आईडी STU-00482 सँग दर्ता भए</p>
                            <div class="flex items-center mt-2">
                                <span class="text-xs text-gray-500 bg-gray-100 py-1 px-2 rounded-full"><i class="far fa-clock mr-1"></i> हिजो</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-xl font-bold mb-4">प्रमाणीकरण स्थिति</h3>
            <div class="space-y-4">
                <div class="flex items-center justify-between p-4 border rounded-lg hover:bg-gray-50 transition-colors">
                    <div class="flex items-center">
                        <div class="bg-green-100 p-2 rounded-lg mr-4">
                            <i class="fas fa-database text-green-600"></i>
                        </div>
                        <div>
                            <h4 class="font-semibold">डाटाबेस माइग्रेसनहरू</h4>
                            <p class="text-sm text-gray-600">मोडेलहरू, सम्बन्धहरू र सिडिङ</p>
                        </div>
                    </div>
                    <div class="text-green-600">
                        <i class="fas fa-check-circle text-2xl"></i>
                    </div>
                </div>
                
                <div class="flex items-center justify-between p-4 border rounded-lg hover:bg-gray-50 transition-colors">
                    <div class="flex items-center">
                        <div class="bg-green-100 p-2 rounded-lg mr-4">
                            <i class="fas fa-user-lock text-green-600"></i>
                        </div>
                        <div>
                            <h4 class="font-semibold">भूमिका-आधारित प्रमाणीकरण</h4>
                            <p class="text-sm text-gray-600">Spatie Permission कार्यान्वयन</p>
                        </div>
                    </div>
                    <div class="text-green-600">
                        <i class="fas fa-check-circle text-2xl"></i>
                    </div>
                </div>
                
                <div class="flex items-center justify-between p-4 border rounded-lg hover:bg-gray-50 transition-colors">
                    <div class="flex items-center">
                        <div class="bg-green-100 p-2 rounded-lg mr-4">
                            <i class="fas fa-cogs text-green-600"></i>
                        </div>
                        <div>
                            <h4 class="font-semibold">नियन्त्रक संरचना</h4>
                            <p class="text-sm text-gray-600">प्रशासक, मालिक, विद्यार्थी नियन्त्रकहरू</p>
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
                            <h4 class="font-semibold">नीति कार्यान्वयन</h4>
                            <p class="text-sm text-gray-600">HostelPolicy, RoomPolicy, StudentPolicy</p>
                        </div>
                    </div>
                    <div class="text-green-600">
                        <i class="fas fa-check-circle text-2xl"></i>
                    </div>
                </div>
                
                <div class="flex items-center justify-between p-4 border rounded-lg bg-blue-50 hover:bg-blue-100 transition-colors">
                    <div class="flex items-center">
                        <div class="bg-blue-100 p-2 rounded-lg mr-4">
                            <i class="fas fa-tasks text-blue-600"></i>
                        </div>
                        <div>
                            <h4 class="font-semibold">प्रमाणीकरण पूरा गर्दै</h4>
                            <p class="text-sm text-gray-600">सबै नियन्त्रकहरूमा authorize() method थप्दै</p>
                        </div>
                    </div>
                    <div class="text-blue-600">
                        <i class="fas fa-hourglass-half text-2xl"></i>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-xl font-bold mb-4">अर्को विकास कार्यहरू</h3>
            <div class="space-y-4">
                <div class="border-l-4 border-blue-500 pl-4 py-3 hover:bg-gray-50 rounded-r transition-colors">
                    <h4 class="font-semibold text-gray-800">प्रमाणीकरण पूरा गर्ने</h4>
                    <p class="text-sm text-gray-600 mt-1">सबै नियन्त्रकहरूमा authorize() method थप्ने</p>
                    <div class="flex mt-2">
                        <span class="bg-blue-500 text-white text-xs px-2 py-1 rounded">उच्च प्राथमिकता</span>
                        <span class="bg-gray-200 text-gray-700 text-xs px-2 py-1 rounded ml-2"><i class="far fa-clock mr-1"></i> २ दिन</span>
                    </div>
                </div>
                
                <div class="border-l-4 border-amber-500 pl-4 py-3 hover:bg-gray-50 rounded-r transition-colors">
                    <h4 class="font-semibold text-gray-800">दृश्यहरू विकास गर्ने</h4>
                    <p class="text-sm text-gray-600 mt-1">प्रशासक, मालिक, विद्यार्थी भूमिकाका लागि दृश्यहरू बनाउने</p>
                    <div class="flex mt-2">
                        <span class="bg-amber-500 text-white text-xs px-2 py-1 rounded">मध्यम प्राथमिकता</span>
                        <span class="bg-gray-200 text-gray-700 text-xs px-2 py-1 rounded ml-2"><i class="far fa-clock mr-1"></i> ५ दिन</span>
                    </div>
                </div>
                
                <div class="border-l-4 border-red-500 pl-4 py-3 hover:bg-gray-50 rounded-r transition-colors">
                    <h4 class="font-semibold text-gray-800">मध्यपर्दा कार्यान्वयन</h4>
                    <p class="text-sm text-gray-600 mt-1">मार्गहरूमा भूमिका-आधारित मध्यपर्दा</p>
                    <div class="flex mt-2">
                        <span class="bg-red-500 text-white text-xs px-2 py-1 rounded">उच्च प्राथमिकता</span>
                        <span class="bg-gray-200 text-gray-700 text-xs px-2 py-1 rounded ml-2"><i class="far fa-clock mr-1"></i> ३ दिन</span>
                    </div>
                </div>
                
                <div class="border-l-4 border-green-500 pl-4 py-3 hover:bg-gray-50 rounded-r transition-colors">
                    <h4 class="font-semibold text-gray-800">परीक्षण</h4>
                    <p class="text-sm text-gray-600 mt-1">सबै कार्यक्षमता र भूमिका-आधारित पहुँचको परीक्षण</p>
                    <div class="flex mt-2">
                        <span class="bg-green-500 text-white text-xs px-2 py-1 rounded">मध्यम प्राथमिकता</span>
                        <span class="bg-gray-200 text-gray-700 text-xs px-2 py-1 rounded ml-2"><i class="far fa-clock mr-1"></i> ७ दिन</span>
                    </div>
                </div>
            </div>
            
            <div class="mt-8 pt-6 border-t">
                <h3 class="text-xl font-bold mb-4">भूमिका प्रदर्शन</h3>
                <div class="flex flex-wrap gap-3">
                    <button class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg flex items-center transition-colors">
                        <i class="fas fa-user-shield mr-2"></i>
                        प्रशासक दृश्य
                    </button>
                    <button class="px-4 py-2 bg-amber-600 hover:bg-amber-700 text-white rounded-lg flex items-center transition-colors">
                        <i class="fas fa-home mr-2"></i>
                        मालिक दृश्य
                    </button>
                    <button class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg flex items-center transition-colors">
                        <i class="fas fa-user-graduate mr-2"></i>
                        विद्यार्थी दृश्य
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        // Role tabs functionality
        const roleTabs = document.querySelectorAll('[id$="-tab"]');
        roleTabs.forEach(tab => {
            tab.addEventListener('click', function() {
                // Remove active class from all tabs
                roleTabs.forEach(t => {
                    t.classList.remove('active-role-tab');
                    t.classList.remove('border-admin', 'border-owner', 'border-student');
                });
                
                // Add active class to clicked tab
                this.classList.add('active-role-tab');
                
                // Add appropriate border color based on role
                if (this.id === 'admin-tab') {
                    this.classList.add('border-admin');
                    alert('प्रशासक दृश्यमा स्विच गर्दै। वास्तविक अनुप्रयोगमा, यसले इन्टरफेस र अनुमतिहरू परिवर्तन गर्थ्यो।');
                } else if (this.id === 'owner-tab') {
                    this.classList.add('border-owner');
                    alert('मालिक दृश्यमा स्विच गर्दै। वास्तविक अनुप्रयोगमा, यसले इन्टरफेस र अनुमतिहरू परिवर्तन गर्थ्यो।');
                } else if (this.id === 'student-tab') {
                    this.classList.add('border-student');
                    alert('विद्यार्थी दृश्यमा स्विच गर्दै। वास्तविक अनुप्रयोगमा, यसले इन्टरफेस र अनुमतिहरू परिवर्तन गर्थ्यो।');
                }
            });
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
    </script>
@endsection

<style>
    .card-hover {
        transition: all 0.3s ease;
    }
    
    .guide-note {
        background-color: #FFFBEB;
        border-left: 4px solid #F59E0B;
        padding: 1rem;
        margin: 1rem 0;
        border-radius: 0.375rem;
    }
    
    .stat-card {
        transition: all 0.3s ease;
    }
</style>