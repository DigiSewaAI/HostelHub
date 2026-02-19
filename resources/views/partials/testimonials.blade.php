@if($featuredTestimonials->isNotEmpty())
<section class="homepage-testimonials" style="padding: 3rem 0; background: #f8fafc;">
    <div class="container">
        <h2 class="section-title nepali">हाम्रा ग्राहकहरू के भन्छन्</h2>
        <div class="testimonial-grid-home" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 2rem;">
            @foreach($featuredTestimonials as $testimonial)
            <div class="testimonial-card" style="background: white; border-radius: 1rem; padding: 1.5rem; box-shadow: 0 4px 6px rgba(0,0,0,0.05);">
                {{-- समीक्षा पाठ --}}
                <div class="testimonial-text" style="font-style: italic; margin-bottom: 1rem; color: #1f2937;">
                    "{{ Str::limit($testimonial->comment, 100) }}"
                </div>

                {{-- अवतार र जानकारी --}}
                <div class="testimonial-author" style="display: flex; align-items: center; gap: 1rem;">
                    {{-- अवतार (Avatar) --}}
                    <div class="author-avatar" style="width: 50px; height: 50px; border-radius: 50%; overflow: hidden; background: linear-gradient(135deg, var(--primary), var(--secondary)); display: flex; align-items: center; justify-content: center; color: white; font-weight: bold; flex-shrink: 0;">
                        @php
                            $avatarUrl = null;
                            $useInitials = true;
                            $name = $testimonial->name ?? 'प्रयोगकर्ता';

                            // 🚨 सरल जाँच: पहिले होस्टलको छवि खोज्ने
                            if($testimonial->hostel_id && $testimonial->hostel && $testimonial->hostel->image) {
                                $hostelImage = $testimonial->hostel->image;
                                
                                // 🟢 यदि छविमा पहिले नै 'hostels/' छ भने
                                if (strpos($hostelImage, 'hostels/') === 0) {
                                    $imagePath = $hostelImage;
                                } else {
                                    $imagePath = 'hostels/' . $hostelImage;
                                }
                                
                                // 🟢 पूरा URL बनाउने
                                $avatarUrl = asset('storage/' . $imagePath);
                                $useInitials = false;
                                
                                // 🚨 डिबग: URL जाँच गर्न (यो लाइन पछि हटाउन सक्नुहुन्छ)
                                // echo "<!-- Hostel Image URL: " . $avatarUrl . " -->";
                            }
                            
                            // 🚨 यदि होस्टलको छवि छैन भने मात्र विद्यार्थीको छवि खोज्ने
                            if($useInitials && $testimonial->student_id && $testimonial->student && $testimonial->student->image) {
                                $studentImage = $testimonial->student->image;
                                
                                // 🟢 यदि छविमा पहिले नै 'students/' छ भने
                                if (strpos($studentImage, 'students/') === 0) {
                                    $imagePath = $studentImage;
                                } else {
                                    $imagePath = 'students/' . $studentImage;
                                }
                                
                                $avatarUrl = asset('storage/' . $imagePath);
                                $useInitials = false;
                                
                                // 🚨 डिबग: URL जाँच गर्न
                                // echo "<!-- Student Image URL: " . $avatarUrl . " -->";
                            }

                            // 3. प्रारम्भिक अक्षर (initials) बनाउने
                            if($useInitials) {
                                $nameParts = explode(' ', trim($name));
                                if(count($nameParts) >= 2) {
                                    // पहिलो शब्दको पहिलो अक्षर + अन्तिम शब्दको पहिलो अक्षर
                                    $initials = strtoupper(substr($nameParts[0], 0, 1) . substr(end($nameParts), 0, 1));
                                } else {
                                    // एउटै शब्द भए पहिलो दुई अक्षर
                                    $initials = strtoupper(substr($name, 0, 2));
                                }
                            }
                        @endphp

                        @if(!$useInitials && $avatarUrl)
                            <img src="{{ $avatarUrl }}" alt="{{ $name }}" style="width: 100%; height: 100%; object-fit: cover;">
                        @else
                            <span>{{ $initials }}</span>
                        @endif
                    </div>

                    {{-- लेखक जानकारी --}}
                    <div>
                        <h4 style="margin: 0; color: var(--primary);">{{ $name }}</h4>
                        @if($testimonial->hostel)
                            <p class="hostel-name" style="margin: 0; font-size: 0.9rem; color: #6b7280;">{{ $testimonial->hostel->name }}</p>
                        @elseif($testimonial->student)
                            <p class="hostel-name" style="margin: 0; font-size: 0.9rem; color: #6b7280;">विद्यार्थी</p>
                        @endif
                        {{-- रेटिङ --}}
                        <div class="rating" style="color: #fbbf24;">
                            @for($i=1;$i<=5;$i++)
                                <i class="fas fa-star" style="color: {{ $i <= $testimonial->rating ? '#fbbf24' : '#cbd5e1' }};"></i>
                            @endfor
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        {{-- सबै प्रशंसापत्र हेर्ने बटन --}}
        <div style="text-align: center; margin-top: 2rem;">
            <a href="{{ route('testimonials') }}" class="btn btn-primary nepali" style="display: inline-block; padding: 0.75rem 2rem; background: linear-gradient(135deg, var(--primary), var(--secondary)); color: white; border-radius: 0.5rem; text-decoration: none; font-weight: 600;">
                सबै प्रशंसापत्र हेर्नुहोस्
            </a>
        </div>
    </div>
</section>
@endif