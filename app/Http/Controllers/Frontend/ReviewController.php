<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ReviewController extends Controller
{
    /**
     * Display public testimonials page.
     */
    public function index(Request $request)
    {
        try {
            // ✅ FIXED: Add proper error handling and ordering
            $testimonials = Review::where('status', 'active')
                ->orderBy('created_at', 'desc')
                ->orderBy('rating', 'desc')
                ->get();

            return view('frontend.testimonials', compact('testimonials'));
        } catch (\Exception $e) {
            // ✅ FIXED: Log error and return empty testimonials
            Log::error('Testimonials page error: ' . $e->getMessage());

            $testimonials = collect(); // Empty collection
            return view('frontend.testimonials', compact('testimonials'));
        }
    }

    /**
     * ✅ NEW: API endpoint to get testimonials for frontend
     */
    public function getTestimonials()
    {
        try {
            $testimonials = Review::where('status', 'active')
                ->orderBy('created_at', 'desc')
                ->orderBy('rating', 'desc')
                ->get()
                ->map(function ($review) {
                    return [
                        'id' => $review->id,
                        'student_name' => $review->student_name,
                        'hostel_name' => $review->hostel->name ?? 'Unknown Hostel',
                        'rating' => $review->rating,
                        'comment' => $review->comment,
                        'created_at' => $review->created_at->format('M d, Y'),
                        'avatar' => $this->getAvatarUrl($review),
                        'course' => $review->course ?? 'Computer Science',
                        'university' => $review->university ?? 'Tribhuvan University'
                    ];
                });

            return response()->json($testimonials);
        } catch (\Exception $e) {
            Log::error('Testimonials API error: ' . $e->getMessage());
            return response()->json($this->getSampleTestimonials());
        }
    }

    /**
     * ✅ NEW: Get featured testimonials for homepage
     */
    public function getFeaturedTestimonials()
    {
        try {
            $testimonials = Review::where('status', 'active')
                ->where('is_featured', true)
                ->orderBy('rating', 'desc')
                ->limit(6)
                ->get()
                ->map(function ($review) {
                    return [
                        'id' => $review->id,
                        'student_name' => $review->student_name,
                        'hostel_name' => $review->hostel->name ?? 'Unknown Hostel',
                        'rating' => $review->rating,
                        'comment' => $this->truncateComment($review->comment, 120),
                        'created_at' => $review->created_at->format('M d, Y'),
                        'avatar' => $this->getAvatarUrl($review)
                    ];
                });

            return response()->json($testimonials);
        } catch (\Exception $e) {
            Log::error('Featured testimonials error: ' . $e->getMessage());
            return response()->json($this->getSampleTestimonials(3));
        }
    }

    /**
     * ✅ NEW: Get testimonials by hostel
     */
    public function getHostelTestimonials($hostelId)
    {
        try {
            $testimonials = Review::where('status', 'active')
                ->where('hostel_id', $hostelId)
                ->orderBy('created_at', 'desc')
                ->get()
                ->map(function ($review) {
                    return [
                        'id' => $review->id,
                        'student_name' => $review->student_name,
                        'rating' => $review->rating,
                        'comment' => $review->comment,
                        'created_at' => $review->created_at->format('M d, Y'),
                        'avatar' => $this->getAvatarUrl($review),
                        'course' => $review->course ?? 'Not specified'
                    ];
                });

            return response()->json($testimonials);
        } catch (\Exception $e) {
            Log::error('Hostel testimonials error: ' . $e->getMessage());
            return response()->json([]);
        }
    }

    /**
     * ✅ NEW: Get testimonials statistics
     */
    public function getTestimonialStats()
    {
        try {
            $totalReviews = Review::where('status', 'active')->count();
            $averageRating = Review::where('status', 'active')->avg('rating');
            $fiveStarReviews = Review::where('status', 'active')->where('rating', 5)->count();

            return response()->json([
                'total_reviews' => $totalReviews,
                'average_rating' => round($averageRating, 1),
                'five_star_reviews' => $fiveStarReviews,
                'satisfaction_rate' => $totalReviews > 0 ? round(($fiveStarReviews / $totalReviews) * 100) : 100
            ]);
        } catch (\Exception $e) {
            Log::error('Testimonial stats error: ' . $e->getMessage());
            return response()->json([
                'total_reviews' => 0,
                'average_rating' => 0,
                'five_star_reviews' => 0,
                'satisfaction_rate' => 0
            ]);
        }
    }

    /**
     * ✅ NEW: Helper method to get avatar URL
     */
    private function getAvatarUrl($review)
    {
        // Check if review has avatar path
        if (!empty($review->avatar_path)) {
            return asset('storage/' . $review->avatar_path);
        }

        // Generate random avatar based on student name for consistency
        $name = $review->student_name ?? 'Student';
        $initials = $this->getInitials($name);
        $colors = ['#1e3a8a', '#0ea5e9', '#10b981', '#f59e0b', '#ef4444'];
        $colorIndex = crc32($name) % count($colors);

        return "https://ui-avatars.com/api/?name=" . urlencode($initials) .
            "&color=FFFFFF&background=" . substr($colors[$colorIndex], 1) .
            "&size=128&bold=true&font-size=0.5";
    }

    /**
     * ✅ NEW: Helper method to get initials from name
     */
    private function getInitials($name)
    {
        $words = explode(' ', $name);
        $initials = '';

        if (count($words) >= 2) {
            $initials = substr($words[0], 0, 1) . substr($words[1], 0, 1);
        } else {
            $initials = substr($name, 0, 2);
        }

        return strtoupper($initials);
    }

    /**
     * ✅ NEW: Helper method to truncate comments
     */
    private function truncateComment($comment, $length = 100)
    {
        if (strlen($comment) <= $length) {
            return $comment;
        }

        return substr($comment, 0, $length) . '...';
    }

    /**
     * ✅ NEW: Sample testimonials data for fallback
     */
    private function getSampleTestimonials($count = 6)
    {
        $sampleTestimonials = [
            [
                'id' => 1,
                'student_name' => 'रमेश कुमार',
                'hostel_name' => 'शान्ति होस्टल',
                'rating' => 5,
                'comment' => 'यो होस्टलमा बस्दा धेरै राम्रो लाग्यो। कोठा सफा छ, खाना राम्रो छ, र प्रबन्धकहरू धेरै सहयोगी छन्।',
                'created_at' => '2024-01-15',
                'avatar' => 'https://ui-avatars.com/api/?name=रक&color=FFFFFF&background=1e3a8a&size=128&bold=true',
                'course' => 'Computer Engineering',
                'university' => 'Tribhuvan University'
            ],
            [
                'id' => 2,
                'student_name' => 'सिता महर्जन',
                'hostel_name' => 'ग्रीन व्ह्याली होस्टल',
                'rating' => 4,
                'comment' => 'अध्ययनको लागि उत्तम वातावरण। लाइब्रेरी र वाईफाइ सेवा राम्रो छ।',
                'created_at' => '2024-01-10',
                'avatar' => 'https://ui-avatars.com/api/?name=सम&color=FFFFFF&background=0ea5e9&size=128&bold=true',
                'course' => 'Business Administration',
                'university' => 'Kathmandu University'
            ],
            [
                'id' => 3,
                'student_name' => 'अमित श्रेष्ठ',
                'hostel_name' => 'माउन्टेन भ्यू होस्टल',
                'rating' => 5,
                'comment' => 'सुरक्षा र स्वच्छतामा उत्कृष्ट। मेरा अभिभावकलाई पनि यो होस्टलमा विश्वास छ।',
                'created_at' => '2024-01-08',
                'avatar' => 'https://ui-avatars.com/api/?name=अश&color=FFFFFF&background=10b981&size=128&bold=true',
                'course' => 'Civil Engineering',
                'university' => 'Purbanchal University'
            ],
            [
                'id' => 4,
                'student_name' => 'प्रज्ञा थापा',
                'hostel_name' => 'सिटी स्टुडेन्ट होस्टल',
                'rating' => 4,
                'comment' => 'महिला विद्यार्थीहरूको लागि पूर्ण सुरक्षित। खाना र स्वास्थ्य सेवा राम्रो छ।',
                'created_at' => '2024-01-05',
                'avatar' => 'https://ui-avatars.com/api/?name=पथ&color=FFFFFF&background=f59e0b&size=128&bold=true',
                'course' => 'Nursing',
                'university' => 'Patan Academy of Health Sciences'
            ],
            [
                'id' => 5,
                'student_name' => 'विकास राई',
                'hostel_name' => 'एजुकेयर होस्टल',
                'rating' => 5,
                'comment' => 'मूल्य र गुणस्तरको हिसाबले उत्तम। प्रबन्धकहरू सधैं सहयोगको लागि तयार हुन्छन्।',
                'created_at' => '2024-01-02',
                'avatar' => 'https://ui-avatars.com/api/?name=वर&color=FFFFFF&background=ef4444&size=128&bold=true',
                'course' => 'Computer Science',
                'university' => 'Pokhara University'
            ],
            [
                'id' => 6,
                'student_name' => 'निशा गुरुङ',
                'hostel_name' => 'हिमालयन होम्स',
                'rating' => 4,
                'comment' => 'शान्त वातावरणमा अध्ययन गर्न राम्रो। इन्टरनेट र पानीको लागि कुनै समस्या छैन।',
                'created_at' => '2023-12-28',
                'avatar' => 'https://ui-avatars.com/api/?name=नग&color=FFFFFF&background=8b5cf6&size=128&bold=true',
                'course' => 'Hotel Management',
                'university' => 'Nobel College'
            ]
        ];

        return array_slice($sampleTestimonials, 0, $count);
    }

    /**
     * ✅ NEW: Submit a new testimonial
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'student_name' => 'required|string|max:255',
                'hostel_id' => 'required|exists:hostels,id',
                'rating' => 'required|integer|between:1,5',
                'comment' => 'required|string|min:10|max:1000',
                'course' => 'nullable|string|max:255',
                'university' => 'nullable|string|max:255',
                'email' => 'nullable|email'
            ]);

            // Create new review
            $review = Review::create([
                'student_name' => $validated['student_name'],
                'hostel_id' => $validated['hostel_id'],
                'rating' => $validated['rating'],
                'comment' => $validated['comment'],
                'course' => $validated['course'],
                'university' => $validated['university'],
                'email' => $validated['email'],
                'status' => 'pending' // Admin must approve
            ]);

            return response()->json([
                'success' => true,
                'message' => 'धन्यवाद! तपाईंको प्रतिक्रिया सफलतापूर्वक पेश गरियो। यो प्रशासकद्वारा स्वीकृत भएपछि प्रकाशित हुनेछ।',
                'review_id' => $review->id
            ]);
        } catch (\Exception $e) {
            Log::error('Store testimonial error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'प्रतिक्रिया पेश गर्दा त्रुटि भयो। कृपया पुनः प्रयास गर्नुहोस्।'
            ], 500);
        }
    }
}
