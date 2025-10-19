<!-- Vibrant Theme - Youthful & Colorful -->
<div class="min-h-screen bg-gradient-to-br from-orange-50 via-pink-50 to-purple-50">
    <!-- Hero Section -->
    <section class="bg-gradient-to-r from-purple-600 to-pink-600 text-white py-16">
        <div class="container">
            <div class="flex flex-col lg:flex-row items-center justify-between gap-8">
                <div class="flex items-center gap-6">
                    @if($logo)
                        <img src="{{ $logo }}" alt="{{ $hostel->name }}" class="w-24 h-24 rounded-2xl object-cover border-4 border-white shadow-2xl">
                    @else
                        <div class="w-24 h-24 bg-white/20 rounded-2xl flex items-center justify-center border-4 border-white">
                            <i class="fas fa-building text-white text-3xl"></i>
                        </div>
                    @endif
                    <div>
                        <h1 class="text-4xl font-bold nepali mb-2">{{ $hostel->name }}</h1>
                        <div class="flex items-center gap-6 text-white/90">
                            <div class="flex items-center gap-2">
                                <i class="fas fa-map-marker-alt"></i>
                                <span class="nepali">{{ $hostel->city ?? 'काठमाडौं' }}</span>
                            </div>
                            @if($reviewCount > 0)
                                <div class="flex items-center gap-2">
                                    <i class="fas fa-star text-yellow-300"></i>
                                    <span class="font-bold">{{ number_format($avgRating, 1) }}</span>
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
                           class="bg-white text-purple-600 px-6 py-3 rounded-2xl hover:bg-gray-100 transition-colors font-bold nepali flex items-center gap-2 shadow-lg">
                            <i class="fas fa-phone"></i>
                            फोन गर्नुहोस्
                        </a>
                    @endif
                </div>
            </div>

            <!-- Stats Bar -->
            <div class="flex flex-col sm:flex-row gap-4 justify-center items-center mt-8">
                @if($hostel->available_rooms > 0)
                    <div class="text-center bg-white/20 backdrop-blur-sm px-6 py-4 rounded-2xl">
                        <div class="text-3xl font-bold">{{ $hostel->available_rooms }}</div>
                        <div class="text-sm nepali">कोठा उपलब्ध</div>
                    </div>
                @endif

                @if($hostel->total_rooms)
                    <div class="text-center bg-white/20 backdrop-blur-sm px-6 py-4 rounded-2xl">
                        <div class="text-3xl font-bold">{{ $hostel->total_rooms }}</div>
                        <div class="text-sm nepali">कुल कोठा</div>
                    </div>
                @endif

                @if($hostel->students_count)
                    <div class="text-center bg-white/20 backdrop-blur-sm px-6 py-4 rounded-2xl">
                        <div class="text-3xl font-bold">{{ $hostel->students_count }}</div>
                        <div class="text-sm nepali">विद्यार्थी</div>
                    </div>
                @endif
            </div>
        </div>
    </section>

    <!-- Main Content -->
    <div class="container py-12">
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
            <!-- Main Content -->
            <div class="lg:col-span-3 space-y-8">
                <!-- About Section -->
                <section class="bg-white rounded-2xl shadow-xl p-8">
                    <h2 class="text-3xl font-bold text-transparent bg-clip-text bg-gradient-to-r from-purple-600 to-pink-600 mb-6 nepali">
                        हाम्रो बारेमा
                    </h2>
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
                    <section class="bg-white rounded-2xl shadow-xl p-8">
                        <h2 class="text-3xl font-bold text-transparent bg-clip-text bg-gradient-to-r from-purple-600 to-pink-600 mb-6 nepali">
                            सुविधाहरू
                        </h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @foreach($facilities as $facility)
                                @if(trim($facility))
                                    <div class="flex items-center gap-4 p-4 bg-gradient-to-r from-purple-50 to-pink-50 rounded-xl border border-purple-100">
                                        <div class="w-8 h-8 bg-gradient-to-r from-purple-500 to-pink-500 rounded-full flex items-center justify-center">
                                            <i class="fas fa-check text-white text-sm"></i>
                                        </div>
                                        <span class="nepali text-gray-800 font-medium">{{ trim($facility) }}</span>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    </section>
                @endif

                <!-- Reviews Section -->
                <section class="bg-white rounded-2xl shadow-xl p-8">
                    <h2 class="text-3xl font-bold text-transparent bg-clip-text bg-gradient-to-r from-purple-600 to-pink-600 mb-6 nepali">
                        विद्यार्थी समीक्षाहरू
                    </h2>
                    
                    @if($reviewCount > 0)
                        <div class="space-y-6">
                            @foreach($reviews as $review)
                                <div class="bg-gradient-to-r from-purple-50 to-pink-50 rounded-2xl p-6 border border-purple-100">
                                    <div class="flex flex-col sm:flex-row justify-between items-start mb-4 gap-2">
                                        <div>
                                            <h4 class="font-bold text-gray-800 nepali text-lg">
                                                {{ $review->student->user->name ?? 'अज्ञात विद्यार्थी' }}
                                            </h4>
                                            <div class="flex items-center gap-1 mt-1">
                                                @for($i = 1; $i <= 5; $i++)
                                                    <i class="fas fa-star {{ $i <= $review->rating ? 'text-yellow-400' : 'text-gray-300' }}"></i>
                                                @endfor
                                            </div>
                                        </div>
                                        <span class="text-sm text-gray-500 bg-white px-3 py-1 rounded-full">
                                            {{ $review->created_at->format('Y-m-d') }}
                                        </span>
                                    </div>
                                    <p class="text-gray-700 mb-4 nepali">{{ $review->comment }}</p>
                                    
                                    @if($review->reply)
                                        <div class="bg-white border-l-4 border-purple-500 p-4 rounded-xl">
                                            <div class="flex items-start gap-3">
                                                <i class="fas fa-reply text-purple-500 mt-1"></i>
                                                <div>
                                                    <strong class="text-purple-800 nepali text-sm">होस्टलको जवाफ:</strong>
                                                    <p class="text-purple-700 mt-1 nepali text-sm">{{ $review->reply }}</p>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>

                        @if($reviews->hasPages())
                            <div class="mt-6">
                                {{ $reviews->links() }}
                            </div>
                        @endif
                    @else
                        <div class="text-center py-12">
                            <i class="fas fa-comment-slash text-gray-400 text-5xl mb-4"></i>
                            <p class="text-gray-600 nepali">अहिलेसम्म कुनै समीक्षा छैन</p>
                        </div>
                    @endif
                </section>
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Contact Card - UPDATED: Phone Button Removed -->
                <div class="bg-gradient-to-br from-purple-500 to-pink-500 text-white rounded-2xl shadow-xl p-6">
                    <h3 class="text-xl font-bold mb-4 nepali">सम्पर्क गर्नुहोस्</h3>
                    <div class="space-y-4">
                        @if($hostel->contact_person)
                            <div class="flex items-center gap-3">
                                <i class="fas fa-user"></i>
                                <span class="nepali">{{ $hostel->contact_person }}</span>
                            </div>
                        @endif
                        
                        @if($hostel->contact_phone)
                            <div class="flex items-center gap-3">
                                <i class="fas fa-phone"></i>
                                <a href="tel:{{ $hostel->contact_phone }}" class="hover:underline">
                                    {{ $hostel->contact_phone }}
                                </a>
                            </div>
                        @endif

                        @if($hostel->contact_email)
                            <div class="flex items-center gap-3">
                                <i class="fas fa-envelope"></i>
                                <a href="mailto:{{ $hostel->contact_email }}" class="hover:underline">
                                    {{ $hostel->contact_email }}
                                </a>
                            </div>
                        @endif

                        @if($hostel->address)
                            <div class="flex items-start gap-3">
                                <i class="fas fa-map-marker-alt mt-1"></i>
                                <span class="nepali">{{ $hostel->address }}</span>
                            </div>
                        @endif
                    </div>
                    
                    <!-- Phone button removed from here -->
                </div>

                <!-- Stats Card -->
                <div class="bg-white rounded-2xl shadow-xl p-6">
                    <h3 class="text-xl font-bold text-gray-800 mb-4 nepali">होस्टल तथ्याङ्क</h3>
                    <div class="grid grid-cols-2 gap-4">
                        <div class="text-center p-3 bg-purple-50 rounded-lg">
                            <div class="text-lg font-bold text-purple-600">{{ $hostel->total_rooms ?? 0 }}</div>
                            <div class="text-xs text-gray-600 nepali">कुल कोठा</div>
                        </div>
                        <div class="text-center p-3 bg-pink-50 rounded-lg">
                            <div class="text-lg font-bold text-pink-600">{{ $hostel->available_rooms ?? 0 }}</div>
                            <div class="text-xs text-gray-600 nepali">उपलब्ध कोठा</div>
                        </div>
                        <div class="text-center p-3 bg-orange-50 rounded-lg">
                            <div class="text-lg font-bold text-orange-600">{{ $hostel->students_count ?? 0 }}</div>
                            <div class="text-xs text-gray-600 nepali">विद्यार्थी</div>
                        </div>
                        <div class="text-center p-3 bg-blue-50 rounded-lg">
                            <div class="text-lg font-bold text-blue-600">{{ $reviewCount }}</div>
                            <div class="text-xs text-gray-600 nepali">समीक्षा</div>
                        </div>
                    </div>
                </div>

                <!-- Trust Badges -->
                <div class="bg-gradient-to-br from-purple-500 to-pink-500 text-white rounded-2xl shadow-xl p-6 text-center">
                    <h3 class="text-xl font-bold mb-4 nepali">विश्वसनीय होस्टल</h3>
                    <div class="grid grid-cols-2 gap-4">
                        <div class="space-y-2">
                            <div class="w-12 h-12 bg-white/20 rounded-full flex items-center justify-center mx-auto">
                                <i class="fas fa-check"></i>
                            </div>
                            <span class="text-xs nepali">सत्यापित</span>
                        </div>
                        <div class="space-y-2">
                            <div class="w-12 h-12 bg-white/20 rounded-full flex items-center justify-center mx-auto">
                                <i class="fas fa-star"></i>
                            </div>
                            <span class="text-xs nepali">रेटेड</span>
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
    border: 2px solid rgba(255, 255, 255, 0.3);
    font-size: 14px;
    backdrop-filter: blur(10px);
}

.social-icon:hover {
    transform: translateY(-2px) scale(1.1);
    border-color: rgba(255, 255, 255, 0.6);
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
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