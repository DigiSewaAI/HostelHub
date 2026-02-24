<?php
// app/Http/Controllers/Network/DirectoryController.php

namespace App\Http\Controllers\Network;

use App\Http\Controllers\Controller;
use App\Models\Hostel;
use Illuminate\Http\Request;

class DirectoryController extends Controller
{
    /**
     * Display a listing of eligible hostels with filters.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        // Base query: only active and published hostels
        $query = Hostel::query()
            ->where('status', 'active')
            ->where('is_published', true)
            ->with('networkProfile'); // eager load network profile for verification badge

        // 1. City filter (partial match)
        if ($request->filled('city')) {
            $query->where('city', 'like', '%' . $request->city . '%');
        }

        // 2. Facility filter (single facility, e.g., 'wifi', 'parking')
        if ($request->filled('facility')) {
            $query->whereJsonContains('facilities', $request->facility);
        }

        // 3. Price range filter (based on available rooms)
        if ($request->filled('min_price') || $request->filled('max_price')) {
            $query->whereHas('rooms', function ($q) use ($request) {
                // Only consider rooms that are available and have beds
                $q->where('status', 'available')
                    ->where('available_beds', '>', 0);

                if ($request->filled('min_price')) {
                    $q->where('price', '>=', $request->min_price);
                }
                if ($request->filled('max_price')) {
                    $q->where('price', '<=', $request->max_price);
                }
            });
        }

        // 4. Room count range filter (based on total_rooms)
        if ($request->filled('min_rooms')) {
            $query->where('total_rooms', '>=', $request->min_rooms);
        }
        if ($request->filled('max_rooms')) {
            $query->where('total_rooms', '<=', $request->max_rooms);
        }

        // 5. Verified only filter (network profile has verified_at)
        if ($request->filled('verified_only') && $request->verified_only) {
            $query->whereHas('networkProfile', function ($q) {
                $q->whereNotNull('verified_at');
            });
        }

        // Paginate results (15 per page) and preserve query string for filters
        $hostels = $query->paginate(15)->withQueryString();

        return view('network.directory.index', compact('hostels'));
    }
}
