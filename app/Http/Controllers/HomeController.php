<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request; // Request class import
use App\Models\Hostel;
use App\Models\Review;
use App\Models\Room;

class HomeController extends Controller
{
    public function index()
    {
        $cities = Hostel::distinct()->pluck('city');
        $hostels = Hostel::with('images')->take(4)->get();
        $reviews = Review::take(3)->get();
        $roomTypes = Room::distinct()->pluck('type');

        return view('welcome', compact('cities', 'hostels', 'reviews', 'roomTypes'));
    }

    public function search(Request $request)
    {
        $request->validate([
            'city' => 'required|string',
            'hostel_id' => 'nullable|exists:hostels,id',
            'check_in' => 'required|date',
            'check_out' => 'required|date|after:check_in',
        ]);

        $rooms = Room::where('status', 'available')
            ->whereHas('hostel', function ($query) use ($request) {
                $query->where('city', $request->city)
                    ->when($request->hostel_id, function ($q) use ($request) {
                        return $q->where('id', $request->hostel_id);
                    });
            })
            ->with('hostel')
            ->get();

        return view('search-results', compact('rooms'));
    }
}
