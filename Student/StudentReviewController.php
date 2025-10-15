<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Review;
use App\Models\Hostel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StudentReviewController extends Controller
{
    /**
     * विद्यार्थीको लागि समीक्षाहरूको सूची देखाउनुहोस्
     */
    public function index()
    {
        $user = Auth::user();
        $reviews = Review::where('user_id', $user->id)
            ->with('hostel')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('student.reviews.index', compact('reviews'));
    }

    /**
     * नयाँ समीक्षा सिर्जना गर्ने फारम देखाउनुहोस्
     */
    public function create()
    {
        $hostels = Hostel::where('status', 'active')->get();
        return view('student.reviews.create', compact('hostels'));
    }

    /**
     * नयाँ समीक्षा भण्डारण गर्नुहोस्
     */
    public function store(Request $request)
    {
        $request->validate([
            'hostel_id' => 'required|exists:hostels,id',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string|min:10|max:1000',
        ]);

        // यूजरले पहिले नै यो होस्टेलमा समीक्षा दिएको छ कि छैन जाँच गर्नुहोस्
        $existingReview = Review::where('user_id', Auth::id())
            ->where('hostel_id', $request->hostel_id)
            ->first();

        if ($existingReview) {
            return redirect()->back()
                ->with('error', 'तपाईंले यो होस्टेलमा पहिले नै समीक्षा दिनुभएको छ।')
                ->withInput();
        }

        Review::create([
            'user_id' => Auth::id(),
            'hostel_id' => $request->hostel_id,
            'rating' => $request->rating,
            'comment' => $request->comment,
            'status' => 'pending',
        ]);

        return redirect()->route('student.reviews.index')
            ->with('success', 'तपाईंको समीक्षा सफलतापूर्वक पेश गरियो। प्रशासकद्वारा स्वीकृत पछि प्रदर्शित हुनेछ।');
    }

    /**
     * विशेष समीक्षा देखाउनुहोस्
     */
    public function show($id)
    {
        $review = Review::where('user_id', Auth::id())
            ->with('hostel')
            ->findOrFail($id);

        return view('student.reviews.show', compact('review'));
    }

    /**
     * समीक्षा सम्पादन गर्ने फारम देखाउनुहोस्
     */
    public function edit($id)
    {
        $review = Review::where('user_id', Auth::id())->findOrFail($id);
        $hostels = Hostel::where('status', 'active')->get();

        return view('student.reviews.edit', compact('review', 'hostels'));
    }

    /**
     * समीक्षा अपडेट गर्नुहोस्
     */
    public function update(Request $request, $id)
    {
        $review = Review::where('user_id', Auth::id())->findOrFail($id);

        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string|min:10|max:1000',
        ]);

        $review->update([
            'rating' => $request->rating,
            'comment' => $request->comment,
            'status' => 'pending', // अपडेट गर्दा पुनः स्वीकृतिको लागि पठाउनुहोस्
        ]);

        return redirect()->route('student.reviews.index')
            ->with('success', 'समीक्षा सफलतापूर्वक अपडेट गरियो।');
    }

    /**
     * समीक्षा मेटाउनुहोस्
     */
    public function destroy($id)
    {
        $review = Review::where('user_id', Auth::id())->findOrFail($id);
        $review->delete();

        return redirect()->route('student.reviews.index')
            ->with('success', 'समीक्षा सफलतापूर्वक मेटाइयो।');
    }

    /**
     * विद्यार्थीको लागि होस्टेलको समीक्षाहरू देखाउनुहोस्
     */
    public function hostelReviews($hostelId)
    {
        $hostel = Hostel::findOrFail($hostelId);
        $reviews = Review::where('hostel_id', $hostelId)
            ->where('status', 'approved')
            ->with('user')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('student.reviews.hostel', compact('hostel', 'reviews'));
    }
}
