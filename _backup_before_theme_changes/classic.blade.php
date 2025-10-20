<!-- Classic Theme - Professional & Traditional -->
<div class="min-h-screen bg-gray-50">
    <!-- Hero Section -->
    <section class="bg-white border-b py-12">
        <div class="container">
            <div class="flex flex-col lg:flex-row items-center justify-between gap-8 mb-8">
                <!-- Logo & Basic Info -->
                <div class="flex items-center gap-6">
                    @if($logo)
                        <img src="{{ $logo }}" alt="{{ $hostel->name }}" class="w-24 h-24 rounded-full object-cover border-4 border-white shadow-lg">
                    @endif
                    <div class="text-left">
                        <h1 class="text-4xl font-bold text-gray-900 nepali mb-2">{{ $hostel->name }}</h1>
                        <div class="flex items-center gap-6 text-gray-600">
                            <div class="flex items-center gap-2">
                                <i class="fas fa-map-marker-alt"></i>
                                <span class="nepali">{{ $hostel->city ?? 'काठमाडौं' }}</span>
                            </div>
                            @if($reviewCount > 0)
                                <div class="flex items-center gap-2">
                                    <i class="fas fa-star text-yellow-400"></i>
                                    <span>{{ number_format($avgRating, 1) }}</span>
                                    <span class="nepali">({{ $reviewCount }})</span>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- UPDATED: Social Media & Phone - Top Right Corner -->
                <div class="top-right-actions">
                    <!-- Dynamic Social Media Icons from Database -->
                    <div class="social-media-buttons">
                        @if($hostel->facebook_url)
                            <a href="{{ $hostel->facebook_url }}" target="_blank" class="social-icon facebook-bg" title="Facebook">
                                <i class="fab fa-facebook-f"></i>
                            </a>
                        @endif
                        
                        @if($hostel->instagram_url)
                            <a href="{{ $hostel->instagram_url }}" target="_blank" class="social-icon instagram-bg" title="Instagram">
                                <i class="fab fa-instagram"></i>
                            </a>
                        @endif
                        
                        @if($hostel->twitter_url)
                            <a href="{{ $hostel->twitter_url }}" target="_blank" class="social-icon twitter-bg" title="Twitter">
                                <i class="fab fa-twitter"></i>
                            </a>
                        @endif
                        
                        @if($hostel->tiktok_url)
                            <a href="{{ $hostel->tiktok_url }}" target="_blank" class="social-icon tiktok-bg" title="TikTok">
                                <i class="fab fa-tiktok"></i>
                            </a>
                        @endif
                        
                        @if($hostel->whatsapp_number)
                            <a href="https://wa.me/{{ $hostel->whatsapp_number }}" target="_blank" class="social-icon whatsapp-bg" title="WhatsApp">
                                <i class="fab fa-whatsapp"></i>
                            </a>
                        @endif
                        
                        @if($hostel->youtube_url)
                            <a href="{{ $hostel->youtube_url }}" target="_blank" class="social-icon youtube-bg" title="YouTube">
                                <i class="fab fa-youtube"></i>
                            </a>
                        @endif
                        
                        @if($hostel->linkedin_url)
                            <a href="{{ $hostel->linkedin_url }}" target="_blank" class="social-icon linkedin-bg" title="LinkedIn">
                                <i class="fab fa-linkedin-in"></i>
                            </a>
                        @endif
                    </div>

                    <!-- Phone Button -->
                    @if($hostel->contact_phone)
                        <a href="tel:{{ $hostel->contact_phone }}" 
                           class="bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition-colors font-medium nepali flex items-center gap-2">
                            <i class="fas fa-phone"></i>
                            फोन गर्नुहोस्
                        </a>
                    @endif
                </div>
            </div>

            <!-- Stats Bar -->
            <div class="flex flex-wrap justify-center gap-6">
                @if($hostel->available_rooms > 0)
                    <div class="text-center">
                        <div class="text-2xl font-bold text-blue-600">{{ $hostel->available_rooms }}</div>
                        <div class="text-sm text-gray-600 nepali">उपलब्ध कोठा</div>
                    </div>
                @endif
                
                @if($hostel->total_rooms)
                    <div class="text-center">
                        <div class="text-2xl font-bold text-green-600">{{ $hostel->total_rooms }}</div>
                        <div class="text-sm text-gray-600 nepali">कुल कोठा</div>
                    </div>
                @endif

                @if($hostel->students_count)
                    <div class="text-center">
                        <div class="text-2xl font-bold text-purple-600">{{ $hostel->students_count }}</div>
                        <div class="text-sm text-gray-600 nepali">विद्यार्थी</div>
                    </div>
                @endif
            </div>
        </div>
    </section>

    <!-- Main Content -->
    <div class="container py-12">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Main Content -->
            <div class="lg:col-span-2 space-y-8">
                <!-- About Section -->
                <section class="bg-white rounded-lg shadow border p-8">
                    <h2 class="text-2xl font-bold text-gray-800 mb-6 nepali border-b pb-2">हाम्रो बारेमा</h2>
                    <div class="prose max-w-none">
                        @if($hostel->description)
                            <p class="text-gray-700 leading-relaxed nepali whitespace-pre-line text-lg">
                                {{ $hostel->description }}
                            </p>
                        @else
                            <p class="text-gray-500 italic nepali text-center py-8">विवरण उपलब्ध छैन</p>
                        @endif
                    </div>
                </section>

                <!-- Facilities Section -->
                @if(!empty($facilities) && count($facilities) > 0)
                    <section class="bg-white rounded-lg shadow border p-8">
                        <h2 class="text-2xl font-bold text-gray-800 mb-6 nepali border-b pb-2">सुविधाहरू</h2>
                        <div class="grid grid-cols-2 gap-4">
                            @foreach($facilities as $facility)
                                @if(trim($facility))
                                    <div class="flex items-center gap-3 p-3 border rounded-lg">
                                        <i class="fas fa-check text-green-500"></i>
                                        <span class="nepali text-gray-700">{{ trim($facility) }}</span>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    </section>
                @endif
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Contact Card -->
                <div class="bg-white rounded-lg shadow border p-6">
                    <h3 class="text-xl font-bold text-gray-800 mb-4 nepali border-b pb-2">सम्पर्क जानकारी</h3>
                    <div class="space-y-4">
                        @if($hostel->contact_person)
                            <div class="flex items-center gap-3">
                                <i class="fas fa-user text-gray-400"></i>
                                <span class="text-gray-700 nepali">{{ $hostel->contact_person }}</span>
                            </div>
                        @endif
                        
                        @if($hostel->contact_phone)
                            <div class="flex items-center gap-3">
                                <i class="fas fa-phone text-gray-400"></i>
                                <a href="tel:{{ $hostel->contact_phone }}" class="text-gray-700 hover:text-blue-600">
                                    {{ $hostel->contact_phone }}
                                </a>
                            </div>
                        @endif

                        @if($hostel->contact_email)
                            <div class="flex items-center gap-3">
                                <i class="fas fa-envelope text-gray-400"></i>
                                <a href="mailto:{{ $hostel->contact_email }}" class="text-gray-700 hover:text-blue-600">
                                    {{ $hostel->contact_email }}
                                </a>
                            </div>
                        @endif
                        
                        @if($hostel->address)
                            <div class="flex items-start gap-3">
                                <i class="fas fa-map-marker-alt text-gray-400 mt-1"></i>
                                <span class="text-gray-700 nepali">{{ $hostel->address }}</span>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Quick Actions - UPDATED: Phone Button Removed -->
                <div class="bg-blue-50 rounded-lg p-6">
                    <h3 class="text-xl font-bold text-blue-800 mb-4 nepali">तुरुन्तै सम्पर्क गर्नुहोस्</h3>
                    <!-- Phone button removed from here -->
                    
                    @if($hostel->contact_email)
                        <a href="mailto:{{ $hostel->contact_email }}" 
                           class="w-full border border-blue-600 text-blue-600 py-3 px-4 rounded-lg hover:bg-blue-600 hover:text-white transition-colors font-medium nepali flex items-center justify-center gap-2 mb-3">
                            <i class="fas fa-envelope"></i>
                            इमेल गर्नुहोस्
                        </a>
                    @endif

                    <a href="#contact" 
                       class="w-full bg-blue-600 text-white py-3 px-4 rounded-lg hover:bg-blue-700 transition-colors font-medium nepali flex items-center justify-center gap-2">
                        <i class="fas fa-map-marker-alt"></i>
                        स्थान हेर्नुहोस्
                    </a>
                </div>

                <!-- Trust Badges -->
                <div class="bg-white rounded-lg shadow border p-6 text-center">
                    <h3 class="text-xl font-bold text-gray-800 mb-4 nepali">विश्वसनीय होस्टल</h3>
                    <div class="grid grid-cols-2 gap-4">
                        <div class="space-y-2">
                            <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center mx-auto">
                                <i class="fas fa-check text-green-600"></i>
                            </div>
                            <span class="text-xs text-gray-600 nepali">सत्यापित</span>
                        </div>
                        <div class="space-y-2">
                            <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center mx-auto">
                                <i class="fas fa-star text-blue-600"></i>
                            </div>
                            <span class="text-xs text-gray-600 nepali">रेटेड</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.whitespace-pre-line {
    white-space: pre-line;
}

/* Social Media Styles */
.social-media-buttons {
    display: flex;
    align-items: center;
    gap: 8px;
    flex-wrap: wrap;
    justify-content: center;
}

.social-icon {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    text-decoration: none;
    transition: all 0.3s ease;
    border: 2px solid rgba(0, 0, 0, 0.1);
    font-size: 14px;
}

.social-icon:hover {
    transform: translateY(-2px);
    border-color: rgba(0, 0, 0, 0.2);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
}

.facebook-bg { background: linear-gradient(135deg, #1877f2 0%, #0d5cb6 100%); }
.instagram-bg { background: linear-gradient(135deg, #e4405f 0%, #c13584 100%); }
.twitter-bg { background: linear-gradient(135deg, #1da1f2 0%, #0d8bd9 100%); }
.tiktok-bg { background: linear-gradient(135deg, #000000 0%, #69c9d0 100%); }
.whatsapp-bg { background: linear-gradient(135deg, #25d366 0%, #128c7e 100%); }
.youtube-bg { background: linear-gradient(135deg, #ff0000 0%, #cc0000 100%); }
.linkedin-bg { background: linear-gradient(135deg, #0077b5 0%, #005885 100%); }

.top-right-actions {
    display: flex;
    align-items: center;
    gap: 16px;
    flex-wrap: wrap;
    justify-content: center;
}

/* Mobile responsiveness */
@media (max-width: 768px) {
    .top-right-actions {
        flex-direction: column;
        gap: 12px;
        margin-top: 16px;
    }
    
    .social-media-buttons {
        justify-content: center;
    }
}
</style>