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

        // Facilities processing
        $facilities = $hostel->facilities;
        if (is_string($facilities)) {
            $decoded = json_decode($facilities, true);
            if (json_last_error() === JSON_ERROR_NONE) {
                $facilities = $decoded;
            } else {
                $facilities = array_map('trim', explode(',', $facilities));
            }
        }

        // 🆕 THAPA: Logo URL normalize गर्ने (optional)
        $logo = $hostel->logo_path ? asset('storage/' . $hostel->logo_path) : null;

        // 🆕 THAPA: Theme अनुसार view select गर्ने
        $theme = $hostel->theme ?? 'default';

        // Theme file को path
        $themeView = "public.hostels.themes.{$theme}";

        // यदि theme file छैन भने default प्रयोग गर्ने
        if (!view()->exists($themeView)) {
            $themeView = 'public.hostels.show';
        }

        return view($themeView, compact(
            'hostel',
            'reviews',
            'avgRating',
            'reviewCount',
            'studentCount',
            'facilities',
            'logo' // 🆕 Logo variable पनि pass गर्ने
        ));
    }
}
