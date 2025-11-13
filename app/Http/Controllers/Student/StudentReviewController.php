<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Review;
use App\Models\Hostel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB; // ✅ ADDED

class StudentReviewController extends Controller
{
    // ✅ ENHANCED: Student authorization for review access
    private function authorizeReviewAccess(Review $review = null)
    {
        $user = Auth::user();

        // ✅ ADDED: Ensure user is a student
        if (!$user->hasRole('student')) {
            abort(403, 'तपाईंसँग समीक्षा व्यवस्थापन गर्ने अनुमति छैन');
        }

        $student = $user->student;
        if (!$student) {
            abort(403, 'विद्यार्थी प्रोफाइल फेला परेन');
        }

        // ✅ ADDED: Check specific review ownership
        if ($review && $review->student_id != $student->id) {
            abort(403, 'तपाईंसँग यो समीक्षा एक्सेस गर्ने अनुमति छैन');
        }

        return true;
    }

    // ✅ ENHANCED: Helper method to get authorized student
    private function getAuthorizedStudent()
    {
        $user = Auth::user();

        // ✅ ENHANCED: Authorization check
        $this->authorizeReviewAccess();

        $student = $user->student;
        if (!$student) {
            abort(403, 'विद्यार्थी प्रोफाइल फेला परेन');
        }

        return $student;
    }

    /**
     * विद्यार्थीको लागि समीक्षाहरूको सूची देखाउनुहोस्
     */
    public function index()
    {
        $user = Auth::user();
        $student = $this->getAuthorizedStudent();

        $reviews = Review::where('student_id', $student->id)
            ->with('hostel')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        // Get review stats for dashboard
        $reviewStats = [
            'total' => Review::where('student_id', $student->id)->count(),
            'approved' => Review::where('student_id', $student->id)->where('status', 'approved')->count(),
            'pending' => Review::where('student_id', $student->id)->where('status', 'pending')->count(),
            'rejected' => Review::where('student_id', $student->id)->where('status', 'rejected')->count(),
        ];

        return view('student.reviews.index', compact('reviews', 'reviewStats'));
    }

    /**
     * नयाँ समीक्षा सिर्जना गर्ने फारम देखाउनुहोस्
     */
    public function create()
    {
        $user = Auth::user();
        $student = $this->getAuthorizedStudent();

        // ✅ ENHANCED: Get only active hostels where student can review
        $hostels = Hostel::where('status', 'active')
            ->whereHas('students', function ($query) use ($student) {
                $query->where('students.id', $student->id);
            })
            ->get();

        return view('student.reviews.create', compact('hostels'));
    }

    /**
     * नयाँ समीक्षा भण्डारण गर्नुहोस्
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        $student = $this->getAuthorizedStudent();

        $request->validate([
            'hostel_id' => 'required|exists:hostels,id',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string|min:10|max:1000',
        ]);

        // ✅ ENHANCED: Verify student belongs to the hostel they're reviewing
        $studentHostel = $student->hostel_id;
        if ($studentHostel != $request->hostel_id) {
            return redirect()->back()
                ->with('error', 'तपाईंले मात्र आफ्नो होस्टेलमा समीक्षा दिन सक्नुहुन्छ।')
                ->withInput();
        }

        DB::beginTransaction();
        try {
            // यूजरले पहिले नै यो होस्टेलमा समीक्षा दिएको छ कि छैन जाँच गर्नुहोस्
            $existingReview = Review::where('student_id', $student->id)
                ->where('hostel_id', $request->hostel_id)
                ->first();

            if ($existingReview) {
                return redirect()->back()
                    ->with('error', 'तपाईंले यो होस्टेलमा पहिले नै समीक्षा दिनुभएको छ।')
                    ->withInput();
            }

            // ✅ FIXED: Complete data with proper defaults
            Review::create([
                'name' => $student->user->name, // Student's name
                'position' => 'Student', // Default position
                'student_id' => $student->id,
                'hostel_id' => $request->hostel_id,
                'rating' => $request->rating,
                'comment' => $request->comment,
                'content' => $request->comment, // Also set content field
                'status' => 'pending', // Use string instead of constant for better compatibility
                'type' => 'review',
            ]);

            DB::commit();

            return redirect()->route('student.reviews.index')
                ->with('success', 'तपाईंको समीक्षा सफलतापूर्वक पेश गरियो। प्रशासकद्वारा स्वीकृत पछि प्रदर्शित हुनेछ।');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'समीक्षा सिर्जना गर्दा त्रुटि भयो: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * विशेष समीक्षा देखाउनुहोस्
     */
    public function show($id)
    {
        $user = Auth::user();
        $student = $this->getAuthorizedStudent();

        $review = Review::where('student_id', $student->id)
            ->with('hostel')
            ->findOrFail($id);

        // ✅ ENHANCED: Authorization check
        $this->authorizeReviewAccess($review);

        return view('student.reviews.show', compact('review'));
    }

    /**
     * समीक्षा सम्पादन गर्ने फारम देखाउनुहोस्
     */
    public function edit($id)
    {
        $user = Auth::user();
        $student = $this->getAuthorizedStudent();

        $review = Review::where('student_id', $student->id)->findOrFail($id);

        // ✅ ENHANCED: Authorization check
        $this->authorizeReviewAccess($review);

        // ✅ ENHANCED: Only allow editing of pending reviews
        if ($review->status !== 'pending') {
            return redirect()->route('student.reviews.index')
                ->with('error', 'स्वीकृत वा अस्वीकृत समीक्षा सम्पादन गर्न मिल्दैन।');
        }

        $hostels = Hostel::where('status', 'active')->get();

        return view('student.reviews.edit', compact('review', 'hostels'));
    }

    /**
     * समीक्षा अपडेट गर्नुहोस्
     */
    public function update(Request $request, $id)
    {
        $user = Auth::user();
        $student = $this->getAuthorizedStudent();

        $review = Review::where('student_id', $student->id)->findOrFail($id);

        // ✅ ENHANCED: Authorization check
        $this->authorizeReviewAccess($review);

        // ✅ ENHANCED: Only allow updating of pending reviews
        if ($review->status !== 'pending') {
            return redirect()->route('student.reviews.index')
                ->with('error', 'स्वीकृत वा अस्वीकृत समीक्षा अपडेट गर्न मिल्दैन।');
        }

        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string|min:10|max:1000',
        ]);

        DB::beginTransaction();
        try {
            $review->update([
                'rating' => $request->rating,
                'comment' => $request->comment,
                'content' => $request->comment,
                'status' => 'pending', // अपडेट गर्दा पुनः स्वीकृतिको लागि पठाउनुहोस्
            ]);

            DB::commit();

            return redirect()->route('student.reviews.index')
                ->with('success', 'समीक्षा सफलतापूर्वक अपडेट गरियो।');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'समीक्षा अपडेट गर्दा त्रुटि भयो: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * समीक्षा मेटाउनुहोस्
     */
    public function destroy($id)
    {
        $user = Auth::user();
        $student = $this->getAuthorizedStudent();

        $review = Review::where('student_id', $student->id)->findOrFail($id);

        // ✅ ENHANCED: Authorization check
        $this->authorizeReviewAccess($review);

        // ✅ ENHANCED: Only allow deletion of pending reviews
        if ($review->status !== 'pending') {
            return redirect()->route('student.reviews.index')
                ->with('error', 'स्वीकृत वा अस्वीकृत समीक्षा मेटाउन मिल्दैन।');
        }

        DB::beginTransaction();
        try {
            $review->delete();
            DB::commit();

            return redirect()->route('student.reviews.index')
                ->with('success', 'समीक्षा सफलतापूर्वक मेटाइयो।');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'समीक्षा मेटाउँदा त्रुटि भयो: ' . $e->getMessage());
        }
    }

    /**
     * विद्यार्थीको लागि होस्टेलको समीक्षाहरू देखाउनुहोस्
     */
    public function hostelReviews($hostelId)
    {
        $user = Auth::user();
        $student = $this->getAuthorizedStudent();

        $hostel = Hostel::findOrFail($hostelId);

        $reviews = Review::where('hostel_id', $hostelId)
            ->where('status', 'approved')
            ->with('student.user')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('student.reviews.hostel', compact('hostel', 'reviews'));
    }

    // ✅ ADDED: New method to get review analytics
    public function getReviewAnalytics()
    {
        $user = Auth::user();
        $student = $this->getAuthorizedStudent();

        $totalReviews = Review::where('student_id', $student->id)->count();
        $averageRating = Review::where('student_id', $student->id)->avg('rating');
        $ratingDistribution = Review::where('student_id', $student->id)
            ->select('rating', DB::raw('count(*) as count'))
            ->groupBy('rating')
            ->orderBy('rating', 'desc')
            ->get();

        return response()->json([
            'total_reviews' => $totalReviews,
            'average_rating' => round($averageRating, 2),
            'rating_distribution' => $ratingDistribution,
            'review_activity' => $this->getReviewActivity($student->id)
        ]);
    }

    // ✅ ADDED: Helper method to get review activity timeline
    private function getReviewActivity($studentId)
    {
        return Review::where('student_id', $studentId)
            ->select(
                DB::raw('YEAR(created_at) as year'),
                DB::raw('MONTH(created_at) as month'),
                DB::raw('COUNT(*) as count')
            )
            ->groupBy('year', 'month')
            ->orderBy('year', 'desc')
            ->orderBy('month', 'desc')
            ->limit(12)
            ->get();
    }

    // ✅ ADDED: Method to check if student can review a hostel
    public function canReviewHostel($hostelId)
    {
        $user = Auth::user();
        $student = $this->getAuthorizedStudent();

        // Check if student belongs to this hostel
        $isStudentInHostel = ($student->hostel_id == $hostelId);

        // Check if student already reviewed this hostel
        $hasReviewed = Review::where('student_id', $student->id)
            ->where('hostel_id', $hostelId)
            ->exists();

        return response()->json([
            'can_review' => $isStudentInHostel && !$hasReviewed,
            'is_student_in_hostel' => $isStudentInHostel,
            'has_reviewed' => $hasReviewed
        ]);
    }
}
