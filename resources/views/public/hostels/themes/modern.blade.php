<!-- Modern Theme - Clean & Minimal -->
<div class="min-h-screen bg-white">
    <!-- Hero Section -->
    <section class="bg-gradient-to-r from-gray-50 to-white py-12">
        <div class="container">
            <div class="flex flex-col lg:flex-row items-center justify-between gap-8">
                <!-- Logo & Basic Info -->
                <div class="flex items-center gap-6">
                    @if($logo)
                        <img src="{{ $logo }}" alt="{{ $hostel->name }}" class="w-20 h-20 rounded-xl object-cover shadow-lg">
                    @else
                        <div class="w-20 h-20 bg-gray-200 rounded-xl flex items-center justify-center">
                            <i class="fas fa-building text-gray-400 text-2xl"></i>
                        </div>
                    @endif
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900 nepali">{{ $hostel->name }}</h1>
                        <div class="flex items-center gap-4 mt-2">
                            <div class="flex items-center gap-2 text-gray-600">
                                <i class="fas fa-map-marker-alt"></i>
                                <span class="nepali">{{ $hostel->city ?? 'काठमाडौं' }}</span>
                            </div>
                            @if($reviewCount > 0)
                                <div class="flex items-center gap-2 text-gray-600">
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
                           class="bg-green-600 text-white px-6 py-3 rounded-lg hover:bg-green-700 transition-colors font-medium nepali flex items-center gap-2">
                            <i class="fas fa-phone"></i>
                            फोन गर्नुहोस्
                        </a>
                    @endif
                </div>
            </div>

            <!-- Stats Bar -->
            <div class="flex flex-col sm:flex-row gap-4 justify-center items-center mt-8">
                @if($hostel->available_rooms > 0)
                    <div class="text-center bg-green-50 px-6 py-3 rounded-lg">
                        <div class="text-2xl font-bold text-green-600">{{ $hostel->available_rooms }}</div>
                        <div class="text-sm text-gray-600 nepali">कोठा उपलब्ध</div>
                    </div>
                @endif
            </div>
        </div>
    </section>

    <!-- Main Content -->
    <div class="container py-8">
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
            <!-- Left Content -->
            <div class="lg:col-span-3 space-y-8">
                <!-- About Section -->
                <section class="bg-white rounded-xl shadow-sm border p-6">
                    <h2 class="text-2xl font-semibold text-gray-800 mb-4 nepali">हाम्रो बारेमा</h2>
                    <div class="prose max-w-none">
                        @if($hostel->description)
                            <p class="text-gray-700 leading-relaxed nepali whitespace-pre-line">
                                {{ $hostel->description }}
                            </p>
                        @else
                            <p class="text-gray-500 italic nepali text-center py-8">विवरण उपलब्ध छैन</p>
                        @endif
                    </div>
                </section>

                <!-- Facilities Section -->
                @if(!empty($facilities) && count($facilities) > 0)
                    <section class="bg-white rounded-xl shadow-sm border p-6">
                        <h2 class="text-2xl font-semibold text-gray-800 mb-4 nepali">सुविधाहरू</h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @foreach($facilities as $facility)
                                @if(trim($facility))
                                    <div class="flex items-center gap-3 p-3 rounded-lg hover:bg-gray-50 transition-colors">
                                        <i class="fas fa-check text-green-500"></i>
                                        <span class="nepali text-gray-700">{{ trim($facility) }}</span>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    </section>
                @endif

                <!-- Reviews Section -->
                <section class="bg-white rounded-xl shadow-sm border p-6">
                    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4">
                        <h2 class="text-2xl font-semibold text-gray-800 nepali">विद्यार्थी समीक्षाहरू</h2>
                        <div class="bg-gray-100 px-4 py-2 rounded-full">
                            <span class="text-gray-600 nepali">{{ $reviewCount }} समीक्षाहरू</span>
                        </div>
                    </div>

                    @if($reviewCount > 0)
                        <div class="space-y-6">
                            @foreach($reviews as $review)
                                <div class="border border-gray-200 rounded-lg p-6 hover:shadow-md transition-shadow">
                                    <div class="flex flex-col sm:flex-row justify-between items-start mb-4 gap-2">
                                        <div>
                                            <h4 class="font-semibold text-gray-800 nepali">
                                                {{ $review->student->user->name ?? 'अज्ञात विद्यार्थी' }}
                                            </h4>
                                            <div class="flex items-center gap-1 mt-1">
                                                @for($i = 1; $i <= 5; $i++)
                                                    <i class="fas fa-star {{ $i <= $review->rating ? 'text-yellow-400' : 'text-gray-300' }}"></i>
                                                @endfor
                                            </div>
                                        </div>
                                        <span class="text-sm text-gray-500">
                                            {{ $review->created_at->format('Y-m-d') }}
                                        </span>
                                    </div>
                                    <p class="text-gray-700 mb-4 nepali">{{ $review->comment }}</p>
                                    
                                    @if($review->reply)
                                        <div class="bg-blue-50 border-l-4 border-blue-500 p-4 rounded">
                                            <div class="flex items-start gap-3">
                                                <i class="fas fa-reply text-blue-500 mt-1"></i>
                                                <div>
                                                    <strong class="text-blue-800 nepali text-sm">होस्टलको जवाफ:</strong>
                                                    <p class="text-blue-700 mt-1 nepali text-sm">{{ $review->reply }}</p>
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

            <!-- Right Sidebar -->
            <div class="lg:col-span-1 space-y-6">
                <!-- Contact Info -->
                <div class="bg-white rounded-xl shadow-sm border p-6">
                    <h3 class="text-xl font-semibold text-gray-800 mb-4 nepali">सम्पर्क जानकारी</h3>
                    <div class="space-y-4">
                        @if($hostel->contact_person)
                            <div class="flex items-center gap-3">
                                <i class="fas fa-user text-gray-400 w-5"></i>
                                <span class="text-gray-700 nepali">{{ $hostel->contact_person }}</span>
                            </div>
                        @endif
                        
                        @if($hostel->contact_phone)
                            <div class="flex items-center gap-3">
                                <i class="fas fa-phone text-gray-400 w-5"></i>
                                <a href="tel:{{ $hostel->contact_phone }}" class="text-gray-700 hover:text-blue-600 transition-colors">
                                    {{ $hostel->contact_phone }}
                                </a>
                            </div>
                        @endif
                        
                        @if($hostel->contact_email)
                            <div class="flex items-center gap-3">
                                <i class="fas fa-envelope text-gray-400 w-5"></i>
                                <a href="mailto:{{ $hostel->contact_email }}" class="text-gray-700 hover:text-blue-600 transition-colors">
                                    {{ $hostel->contact_email }}
                                </a>
                            </div>
                        @endif
                        
                        @if($hostel->address)
                            <div class="flex items-start gap-3">
                                <i class="fas fa-map-marker-alt text-gray-400 w-5 mt-1"></i>
                                <span class="text-gray-700 nepali">{{ $hostel->address }}</span>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Quick Stats -->
                <div class="bg-white rounded-xl shadow-sm border p-6">
                    <h3 class="text-xl font-semibold text-gray-800 mb-4 nepali">होस्टल तथ्याङ्क</h3>
                    <div class="grid grid-cols-2 gap-4">
                        <div class="text-center p-4 bg-blue-50 rounded-lg">
                            <div class="text-xl font-bold text-blue-600">{{ $hostel->total_rooms ?? 0 }}</div>
                            <div class="text-xs text-gray-600 nepali">कुल कोठा</div>
                        </div>
                        <div class="text-center p-4 bg-green-50 rounded-lg">
                            <div class="text-xl font-bold text-green-600">{{ $hostel->available_rooms ?? 0 }}</div>
                            <div class="text-xs text-gray-600 nepali">उपलब्ध कोठा</div>
                        </div>
                        <div class="text-center p-4 bg-purple-50 rounded-lg">
                            <div class="text-xl font-bold text-purple-600">{{ $hostel->students_count ?? 0 }}</div>
                            <div class="text-xs text-gray-600 nepali">विद्यार्थी</div>
                        </div>
                        <div class="text-center p-4 bg-orange-50 rounded-lg">
                            <div class="text-xl font-bold text-orange-600">{{ $reviewCount }}</div>
                            <div class="text-xs text-gray-600 nepali">समीक्षा</div>
                        </div>
                    </div>
                </div>

                <!-- Trust Badges -->
                <div class="bg-white rounded-xl shadow-sm border p-6 text-center">
                    <h3 class="text-xl font-semibold text-gray-800 mb-4 nepali">विश्वसनीय होस्टल</h3>
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
    border: 2px solid rgba(255, 255, 255, 0.3);
    font-size: 14px;
}

.social-icon:hover {
    transform: translateY(-2px);
    border-color: rgba(255, 255, 255, 0.6);
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