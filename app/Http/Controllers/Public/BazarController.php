<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\MarketplaceListing;
use App\Models\MarketplaceCategory;
use Illuminate\Http\Request;

class BazarController extends Controller
{
    /**
     * सार्वजनिक बजारको मुख्य पृष्ठ (सूची)
     */
    public function index(Request $request)
    {
        $query = MarketplaceListing::public()
            ->with(['category', 'owner', 'media']);

        // कोटि अनुसार फिल्टर
        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        // स्थान अनुसार फिल्टर
        if ($request->filled('location')) {
            $query->where('location', 'like', '%' . $request->location . '%');
        }

        // मूल्य दायरा अनुसार फिल्टर
        if ($request->filled('min_price')) {
            $query->where('price', '>=', $request->min_price);
        }
        if ($request->filled('max_price')) {
            $query->where('price', '<=', $request->max_price);
        }

        // अवस्था (condition) अनुसार फिल्टर
        if ($request->filled('condition')) {
            $query->where('condition', $request->condition);
        }

        // खोज (शीर्षक वा विवरणमा)
        if ($request->filled('q')) {
            $query->where(function ($q) use ($request) {
                $q->where('title', 'like', '%' . $request->q . '%')
                    ->orWhere('description', 'like', '%' . $request->q . '%');
            });
        }

        // क्रमबद्ध (सजिलो)
        $sort = $request->get('sort', 'latest'); // latest, price_low, price_high
        if ($sort === 'price_low') {
            $query->orderBy('price', 'asc');
        } elseif ($sort === 'price_high') {
            $query->orderBy('price', 'desc');
        } else {
            $query->latest();
        }

        $listings = $query->paginate(12);

        $categories = MarketplaceCategory::active()->get();

        return view('public.bazar.index', compact('listings', 'categories'));
    }

    /**
     * एकल लिस्टिङ विवरण पृष्ठ
     */
    public function show($slug)
    {
        $listing = MarketplaceListing::public()
            ->with(['category', 'owner', 'media'])
            ->where('slug', $slug)
            ->firstOrFail();

        // हेराइ गणना बढाउने
        $listing->increment('views');

        // सम्बन्धित लिस्टिङहरू (एउटै कोटि, एउटै स्थान, आदि)
        $related = MarketplaceListing::public()
            ->where('id', '!=', $listing->id)
            ->where(function ($q) use ($listing) {
                $q->where('category_id', $listing->category_id)
                    ->orWhere('location', $listing->location);
            })
            ->limit(4)
            ->get();

        return view('public.bazar.show', compact('listing', 'related'));
    }
}
