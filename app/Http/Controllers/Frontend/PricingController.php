<?php

namespace App\Http\Controllers\Frontend;

use App\Models\Plan;
use Illuminate\Http\Request;

class PricingController extends Controller
{
    public function index()
    {
        $plans = Plan::where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        return view('pricing.index', compact('plans'));
    }
}
