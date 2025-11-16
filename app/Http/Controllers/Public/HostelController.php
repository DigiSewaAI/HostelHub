<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Hostel;
use App\Models\Gallery;
use Illuminate\Http\Request;

class HostelController extends Controller
{
    public function show($slug)
    {
        $hostel = Hostel::where('slug', $slug)
            ->where('is_published', true)
            ->with(['galleries' => function ($query) {
                $query->where('is_active', true)
                    ->orderBy('is_featured', 'desc')
                    ->orderBy('created_at', 'desc');
            }])
            ->withCount(['galleries' => function ($query) {
                $query->where('is_active', true);
            }])
            ->firstOrFail();

        // Reviews data
        $reviews = $hostel->approvedReviews()
            ->with('student.user')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        $avgRating = $hostel->approvedReviews()->avg('rating');
        $reviewCount = $hostel->approvedReviews()->count();
        $studentCount = $hostel->students()->count();

        // Facilities processing (existing code from your show.blade.php)
        $facilities = $hostel->facilities;
        if (is_string($facilities)) {
            $decoded = json_decode($facilities, true);
            if (json_last_error() === JSON_ERROR_NONE) {
                $facilities = $decoded;
            } else {
                $facilities = array_map('trim', explode(',', $facilities));
            }
        }

        return view('public.hostels.show', compact(
            'hostel',
            'reviews',
            'avgRating',
            'reviewCount',
            'studentCount',
            'facilities'
        ));
    }
}
