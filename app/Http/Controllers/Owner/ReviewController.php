<?php

namespace App\Http\Controllers\Owner;

use App\Models\Review;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Notifications\NewReviewNotification; // ✅ Import the notification

class ReviewController extends Controller
{
    // ✅ ENHANCED: Student authorization for reviews
    private function authorizeReviewAccess(Review $review = null)
    {
        $user = Auth::user();

        // ✅ ADDED: Student role restriction - students cannot access review management
        if ($user->hasRole('student')) {
            abort(403, 'तपाईंसँग समीक्षा व्यवस्थापन गर्ने अनुमति छैन');
        }

        if ($user->hasRole('hostel_manager')) {
            // Ensure owner has a hostel assigned
            if (!$user->hostel_id) {
                abort(403, 'तपाईंसँग कुनै होस्टल सम्बन्धित छैन');
            }

            // Check specific review access
            if ($review && $review->hostel_id != $user->hostel_id) {
                abort(403, 'तपाईंसँग यो समीक्षा एक्सेस गर्ने अनुमति छैन');
            }
        }

        return true;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();

        // ✅ ENHANCED: Authorization check
        $this->authorizeReviewAccess();

        // ✅ ENHANCED: Data scoping for owners - only their hostel's reviews
        $reviews = Review::where('hostel_id', $user->hostel_id)
            ->with('student')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('owner.reviews.index', compact('reviews'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Review $review)
    {
        $user = Auth::user();

        // ✅ ENHANCED: Role-based authorization
        $this->authorizeReviewAccess($review);

        // ✅ ADDED: Additional check to ensure review belongs to owner's hostel
        if ($review->hostel_id != $user->hostel_id) {
            abort(403, 'तपाईंसँग यो समीक्षा हेर्ने अनुमति छैन');
        }

        return view('owner.reviews.show', compact('review'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function reply(Review $review)
    {
        $user = Auth::user();

        // ✅ ENHANCED: Authorization check
        $this->authorizeReviewAccess($review);

        // ✅ ADDED: Ensure the review belongs to owner's hostel
        if ($review->hostel_id != $user->hostel_id) {
            abort(403, 'तपाईंसँग यो समीक्षामा जवाफ दिने अनुमति छैन');
        }

        return view('owner.reviews.reply', compact('review'));
    }

    /**
     * Approve a review
     */
    public function approve(Review $review)
    {
        $user = Auth::user();

        // ✅ ENHANCED: Authorization check
        $this->authorizeReviewAccess($review);

        // Ensure the review belongs to owner's hostel
        if ($review->hostel_id != $user->hostel_id) {
            abort(403, 'तपाईंसँग यो समीक्षा स्वीकृत गर्ने अनुमति छैन');
        }

        $review->update(['status' => 'approved']);

        // 🔔 Notify the owner about the approved review
        $user->notify(new NewReviewNotification($review));

        return redirect()->route('owner.reviews.index')
            ->with('success', 'समीक्षा सफलतापूर्वक स्वीकृत गरियो!');
    }

    /**
     * Reject a review
     */
    public function reject(Review $review)
    {
        $user = Auth::user();

        // ✅ ENHANCED: Authorization check
        $this->authorizeReviewAccess($review);

        // Ensure the review belongs to owner's hostel
        if ($review->hostel_id != $user->hostel_id) {
            abort(403, 'तपाईंसँग यो समीक्षा अस्वीकृत गर्ने अनुमति छैन');
        }

        $review->update(['status' => 'rejected']);

        return redirect()->route('owner.reviews.index')
            ->with('success', 'समीक्षा सफलतापूर्वक अस्वीकृत गरियो!');
    }

    /**
     * Store reply to a review
     */
    public function storeReply(Request $request, Review $review)
    {
        $user = Auth::user();

        // ✅ ENHANCED: Authorization check
        $this->authorizeReviewAccess($review);

        // सुनिश्चित गर्नुहोस् कि यो समीक्षा आफ्नो होस्टलको हो
        if ($review->hostel_id != $user->hostel_id) {
            abort(403, 'तपाईंसँग यो समीक्षामा जवाफ दिने अनुमति छैन');
        }

        $request->validate([
            'reply' => 'required|string|max:1000',
        ]);

        $review->update([
            'reply' => $request->reply,
            'reply_date' => now()
        ]);

        return redirect()->route('owner.reviews.index')
            ->with('success', 'समीक्षामा जवाफ सफलतापूर्वक थपियो!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Review $review)
    {
        $user = Auth::user();

        // ✅ ENHANCED: Authorization check
        $this->authorizeReviewAccess($review);

        // सुनिश्चित गर्नुहोस् कि यो समीक्षा आफ्नो होस्टलको हो
        if ($review->hostel_id != $user->hostel_id) {
            abort(403, 'तपाईंसँग यो समीक्षा हटाउने अनुमति छैन');
        }

        $review->delete();

        return redirect()->route('owner.reviews.index')
            ->with('success', 'समीक्षा सफलतापूर्वक हटाइयो!');
    }

    // ✅ ADDED: New method to get review statistics
    public function getStatistics()
    {
        $user = Auth::user();

        // ✅ ENHANCED: Authorization check
        $this->authorizeReviewAccess();

        $totalReviews = Review::where('hostel_id', $user->hostel_id)->count();
        $approvedReviews = Review::where('hostel_id', $user->hostel_id)
            ->where('status', 'approved')->count();
        $pendingReviews = Review::where('hostel_id', $user->hostel_id)
            ->where('status', 'pending')->count();
        $repliedReviews = Review::where('hostel_id', $user->hostel_id)
            ->whereNotNull('reply')->count();

        return response()->json([
            'total_reviews' => $totalReviews,
            'approved_reviews' => $approvedReviews,
            'pending_reviews' => $pendingReviews,
            'replied_reviews' => $repliedReviews
        ]);
    }

    // ✅ ADDED: Method to get reviews with filters
    public function filteredReviews(Request $request)
    {
        $user = Auth::user();

        // ✅ ENHANCED: Authorization check
        $this->authorizeReviewAccess();

        $query = Review::where('hostel_id', $user->hostel_id)
            ->with('student');

        // Filter by status
        if ($request->has('status') && in_array($request->status, ['pending', 'approved', 'rejected'])) {
            $query->where('status', $request->status);
        }

        // Filter by rating
        if ($request->has('rating') && is_numeric($request->rating)) {
            $query->where('rating', $request->rating);
        }

        // Filter by date range
        if ($request->has('start_date') && $request->has('end_date')) {
            $query->whereBetween('created_at', [
                $request->start_date,
                $request->end_date
            ]);
        }

        $reviews = $query->orderBy('created_at', 'desc')->paginate(10);

        return view('owner.reviews.index', compact('reviews'));
    }
}
