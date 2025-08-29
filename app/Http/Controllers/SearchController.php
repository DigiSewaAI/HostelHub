<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Hostel;
use App\Models\Room;

class SearchController extends Controller
{
    public function index(Request $request)
    {
        $query = $request->input('q');
        $city = $request->input('city');

        // Implement your search logic here
        $hostels = Hostel::when($query, function ($q) use ($query) {
            return $q->where('name', 'like', "%{$query}%")
                ->orWhere('description', 'like', "%{$query}%");
        })
            ->when($city, function ($q) use ($city) {
                return $q->where('city', $city);
            })
            ->with('images')
            ->paginate(10);

        return view('public.search', compact('hostels', 'query', 'city'));
    }
}
