<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Review;

class ReviewController extends Controller
{
    /**
     * Display public testimonials page.
     */
    public function index()
    {
        $testimonials = Review::where('status', 'active')->get();
        return view('frontend.testimonials', compact('testimonials'));
    }
}
