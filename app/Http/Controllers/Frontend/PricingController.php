<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Plan;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PricingController extends Controller
{
    /**
     * Display pricing plans.
     */
    public function index(): View
    {
        $plans = Plan::where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        // ✅ सही view path: frontend.partials.pricing.index
        return view('frontend.partials.pricing.index', compact('plans'));
    }
}
