<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Hostel;
use App\Models\Room;
use Carbon\Carbon;

class SearchController extends Controller
{
    public function index(Request $request)
    {
        $query = $request->input('q');
        $city = $request->input('city');
        $checkin = $request->input('check_in');
        $checkout = $request->input('check_out');
        $hostelId = $request->input('hostel_id');

        // Get hostels with search filters
        $hostels = Hostel::when($query, function ($q) use ($query) {
            return $q->where('name', 'like', "%{$query}%")
                ->orWhere('description', 'like', "%{$query}%");
        })
            ->when($city, function ($q) use ($city) {
                return $q->where('city', 'like', "%{$city}%");
            })
            ->when($hostelId, function ($q) use ($hostelId) {
                return $q->where('id', $hostelId);
            })
            ->where('is_published', true)
            ->where('status', 'active')
            ->with(['images', 'rooms' => function ($query) {
                $query->where('status', 'available')
                    ->where('available_beds', '>', 0);
            }])
            ->withCount(['rooms as available_rooms_count' => function ($query) {
                $query->where('status', 'available')
                    ->where('available_beds', '>', 0);
            }])
            ->paginate(12);

        // Pass search filters to view
        $searchFilters = [
            'query' => $query,
            'city' => $city,
            'checkin' => $checkin,
            'checkout' => $checkout,
            'hostel_id' => $hostelId
        ];

        return view('public.search', compact('hostels', 'searchFilters'));
    }
}
