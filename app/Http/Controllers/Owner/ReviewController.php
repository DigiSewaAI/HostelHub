<?php

namespace App\Http\Controllers\Owner;

use App\Models\Review;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ReviewController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // केवल आफ्नो होस्टलका समीक्षाहरू मात्र हेर्नुहोस्
        $reviews = Review::where('hostel_id', auth()->user()->hostel_id)
            ->with('student')
            ->orderBy('created_at', 'desc')
            ->paginate(10); // ✅ CHANGED: get() to paginate(10)

        return view('owner.reviews.index', compact('reviews'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Review $review)
    {
        // सुनिश्चित गर्नुहोस् कि यो समीक्षा आफ्नो होस्टलको हो
        if ($review->hostel_id != auth()->user()->hostel_id) {
            abort(403, 'तपाईंसँग यो समीक्षा हेर्ने अनुमति छैन');
        }

        return view('owner.reviews.show', compact('review'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function reply(Review $review)
    {
        // सुनिश्चित गर्नुहोस् कि यो समीक्षा आफ्नो होस्टलको हो
        if ($review->hostel_id != auth()->user()->hostel_id) {
            abort(403, 'तपाईंसँग यो समीक्षामा जवाफ दिने अनुमति छैन');
        }

        return view('owner.reviews.reply', compact('review'));
    }

    /**
     * Approve a review
     */
    public function approve(Review $review)
    {
        // Ensure the review belongs to owner's hostel
        if ($review->hostel_id != auth()->user()->hostel_id) {
            abort(403, 'तपाईंसँग यो समीक्षा स्वीकृत गर्ने अनुमति छैन');
        }

        $review->update(['status' => 'approved']);

        return redirect()->route('owner.reviews.index')
            ->with('success', 'समीक्षा सफलतापूर्वक स्वीकृत गरियो!');
    }

    /**
     * Reject a review
     */
    public function reject(Review $review)
    {
        // Ensure the review belongs to owner's hostel
        if ($review->hostel_id != auth()->user()->hostel_id) {
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
        // सुनिश्चित गर्नुहोस् कि यो समीक्षा आफ्नो होस्टलको हो
        if ($review->hostel_id != auth()->user()->hostel_id) {
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
        // सुनिश्चित गर्नुहोस् कि यो समीक्षा आफ्नो होस्टलको हो
        if ($review->hostel_id != auth()->user()->hostel_id) {
            abort(403, 'तपाईंसँग यो समीक्षा हटाउने अनुमति छैन');
        }

        $review->delete();

        return redirect()->route('owner.reviews.index')
            ->with('success', 'समीक्षा सफलतापूर्वक हटाइयो!');
    }
}
