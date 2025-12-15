<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ReviewRequest;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    // ✅ NEW: Approve a review
    public function approve(Review $review)
    {
        $user = auth()->user();
        if (!$user->hasRole('admin')) {
            abort(403, 'तपाईंसँग समीक्षा स्वीकृत गर्ने अनुमति छैन');
        }

        $review->update([
            'status' => 'approved',
            'is_approved' => true
        ]);

        return back()->with('success', '✅ समीक्षा स्वीकृत गरियो!');
    }

    // ✅ NEW: Reject a review
    public function reject(Review $review)
    {
        $user = auth()->user();
        if (!$user->hasRole('admin')) {
            abort(403, 'तपाईंसँग समीक्षा अस्वीकृत गर्ने अनुमति छैन');
        }

        $review->update([
            'status' => 'rejected',
            'is_approved' => false
        ]);

        return back()->with('success', '❌ समीक्षा अस्वीकृत गरियो!');
    }

    // ✅ NEW: Feature a review
    public function feature(Review $review)
    {
        $user = auth()->user();
        if (!$user->hasRole('admin')) {
            abort(403, 'तपाईंसँग समीक्षा फिचर गर्ने अनुमति छैन');
        }

        $review->update(['is_featured' => true]);

        return back()->with('success', '⭐ समीक्षा फिचर गरियो!');
    }

    // ✅ ENHANCED: Index with filters
    public function index(Request $request)
    {
        $user = auth()->user();
        if (!$user->hasRole('admin')) {
            abort(403, 'तपाईंसँग समीक्षाहरू हेर्ने अनुमति छैन');
        }

        $query = Review::query();

        // Add filters
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                    ->orWhere('content', 'like', '%' . $request->search . '%')
                    ->orWhere('position', 'like', '%' . $request->search . '%');
            });
        }

        $reviews = $query->latest()->paginate(20);

        return view('admin.reviews.index', compact('reviews'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // ✅ SECURITY FIX: Authorization check
        $user = auth()->user();
        if (!$user->hasRole('admin')) {
            abort(403, 'तपाईंसँग समीक्षा सिर्जना गर्ने अनुमति छैन');
        }

        return view('admin.reviews.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ReviewRequest $request)
    {
        // ✅ SECURITY FIX: Authorization check
        $user = auth()->user();
        if (!$user->hasRole('admin')) {
            abort(403, 'तपाईंसँग समीक्षा सिर्जना गर्ने अनुमति छैन');
        }

        // ✅ SECURITY FIX: Mass assignment protection - use validated data only
        $validated = $request->validated();

        // ✅ SECURITY FIX: Enhanced file upload security
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $originalName = $image->getClientOriginalName();
            $safeName = preg_replace('/[^a-zA-Z0-9\-\._]/', '', $originalName);
            $path = $image->storeAs('reviews', time() . '_' . $safeName, 'public');
            $validated['image'] = $path;
        }

        // ✅ SECURITY FIX: Add user_id to track who created the review
        $validated['created_by'] = $user->id;

        Review::create($validated);

        return redirect()->route('admin.reviews.index')
            ->with('success', 'समीक्षा सफलतापूर्वक सिर्जना गरियो!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Review $review)
    {
        // ✅ SECURITY FIX: Authorization check
        $user = auth()->user();
        if (!$user->hasRole('admin')) {
            abort(403, 'तपाईंसँग समीक्षा हेर्ने अनुमति छैन');
        }

        return view('admin.reviews.show', compact('review'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Review $review)
    {
        // ✅ SECURITY FIX: Authorization check
        $user = auth()->user();
        if (!$user->hasRole('admin')) {
            abort(403, 'तपाईंसँग समीक्षा सम्पादन गर्ने अनुमति छैन');
        }

        return view('admin.reviews.edit', compact('review'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ReviewRequest $request, Review $review)
    {
        // ✅ SECURITY FIX: Authorization check
        $user = auth()->user();
        if (!$user->hasRole('admin')) {
            abort(403, 'तपाईंसँग समीक्षा अद्यावधिक गर्ने अनुमति छैन');
        }

        // ✅ SECURITY FIX: Mass assignment protection - use validated data only
        $validated = $request->validated();

        // ✅ SECURITY FIX: Enhanced file upload security
        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($review->image && Storage::disk('public')->exists($review->image)) {
                Storage::disk('public')->delete($review->image);
            }

            // Save new image with secure naming
            $image = $request->file('image');
            $originalName = $image->getClientOriginalName();
            $safeName = preg_replace('/[^a-zA-Z0-9\-\._]/', '', $originalName);
            $path = $image->storeAs('reviews', time() . '_' . $safeName, 'public');
            $validated['image'] = $path;
        }

        // ✅ SECURITY FIX: Handle image removal if requested
        if ($request->has('remove_image') && $request->remove_image == '1') {
            if ($review->image && Storage::disk('public')->exists($review->image)) {
                Storage::disk('public')->delete($review->image);
            }
            $validated['image'] = null;
        }

        $review->update($validated);

        return redirect()->route('admin.reviews.index')
            ->with('success', 'समीक्षा सफलतापूर्वक अद्यावधिक गरियो!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Review $review)
    {
        // ✅ SECURITY FIX: Authorization check
        $user = auth()->user();
        if (!$user->hasRole('admin')) {
            abort(403, 'तपाईंसँग समीक्षा हटाउने अनुमति छैन');
        }

        // Delete image if exists
        if ($review->image && Storage::disk('public')->exists($review->image)) {
            Storage::disk('public')->delete($review->image);
        }

        $review->delete();

        return redirect()->route('admin.reviews.index')
            ->with('success', 'समीक्षा सफलतापूर्वक हटाइयो!');
    }

    /**
     * ✅ NEW: Toggle review status (active/inactive)
     */
    public function toggleStatus(Review $review)
    {
        // ✅ SECURITY FIX: Authorization check
        $user = auth()->user();
        if (!$user->hasRole('admin')) {
            abort(403, 'तपाईंसँग समीक्षा स्थिति परिवर्तन गर्ने अनुमति छैन');
        }

        $newStatus = $review->status == 'active' ? 'inactive' : 'active';
        $review->update(['status' => $newStatus]);

        $statusText = $newStatus == 'active' ? 'सक्रिय' : 'निष्क्रिय';

        return back()->with('success', "समीक्षा {$statusText} गरियो।");
    }

    /**
     * ✅ NEW: Bulk actions for reviews
     */
    public function bulkAction(Request $request)
    {
        // ✅ SECURITY FIX: Authorization check
        $user = auth()->user();
        if (!$user->hasRole('admin')) {
            abort(403, 'तपाईंसँग बल्क कार्य गर्ने अनुमति छैन');
        }

        $request->validate([
            'action' => 'required|in:activate,deactivate,delete',
            'review_ids' => 'required|array',
            'review_ids.*' => 'exists:reviews,id'
        ]);

        try {
            $reviews = Review::whereIn('id', $request->review_ids)->get();

            switch ($request->action) {
                case 'activate':
                    foreach ($reviews as $review) {
                        $review->update(['status' => 'active']);
                    }
                    $message = 'चयन गरिएका समीक्षाहरू सक्रिय गरियो।';
                    break;

                case 'deactivate':
                    foreach ($reviews as $review) {
                        $review->update(['status' => 'inactive']);
                    }
                    $message = 'चयन गरिएका समीक्षाहरू निष्क्रिय गरियो।';
                    break;

                case 'delete':
                    foreach ($reviews as $review) {
                        // Delete image if exists
                        if ($review->image && Storage::disk('public')->exists($review->image)) {
                            Storage::disk('public')->delete($review->image);
                        }
                        $review->delete();
                    }
                    $message = 'चयन गरिएका समीक्षाहरू मेटाइयो।';
                    break;
            }

            return back()->with('success', $message);
        } catch (\Exception $e) {
            return back()->with('error', 'बल्क कार्य गर्दा त्रुटि: ' . $e->getMessage());
        }
    }

    /**
     * ✅ NEW: Search reviews functionality with security fixes
     */
    public function search(Request $request)
    {
        // ✅ SECURITY FIX: Authorization check
        $user = auth()->user();
        if (!$user->hasRole('admin')) {
            abort(403, 'तपाईंसँग समीक्षा खोज गर्ने अनुमति छैन');
        }

        $request->validate([
            'search' => 'required|string|min:2'
        ], [
            'search.required' => 'खोज शब्द आवश्यक छ',
            'search.min' => 'खोज शब्द कम्तिमा २ अक्षरको हुनुपर्छ'
        ]);

        $query = $request->input('search');

        // ✅ SECURITY FIX: SQL Injection prevention in search
        $safeQuery = '%' . addcslashes($query, '%_') . '%';

        $reviews = Review::where('title', 'like', $safeQuery)
            ->orWhere('content', 'like', $safeQuery)
            ->orWhere('author_name', 'like', $safeQuery)
            ->orWhere('author_position', 'like', $safeQuery)
            ->orWhere('rating', 'like', $safeQuery)
            ->latest()
            ->paginate(10);

        return view('admin.reviews.index', compact('reviews'));
    }
}
