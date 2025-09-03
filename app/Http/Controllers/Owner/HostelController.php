<?php

namespace App\Http\Controllers\Owner;

use App\Models\Hostel;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class HostelController extends Controller
{
    public function index()
    {
        $hostel = Hostel::where('id', auth()->user()->hostel_id)->first();
        return view('owner.hostels.index', compact('hostel'));
    }

    public function create()
    {
        // होस्टल मालिकले नयाँ होस्टल बनाउन पाउँदैनन्
        abort(403, 'तपाईंसँग नयाँ होस्टल बनाउने अनुमति छैन');
    }

    public function store(Request $request)
    {
        // होस्टल मालिकले नयाँ होस्टल बनाउन पाउँदैनन्
        abort(403, 'तपाईंसँग नयाँ होस्टल बनाउने अनुमति छैन');
    }

    public function edit(Hostel $hostel)
    {
        if ($hostel->id != auth()->user()->hostel_id) {
            abort(403, 'तपाईंसँग यो होस्टल सम्पादन गर्ने अनुमति छैन');
        }

        return view('owner.hostels.edit', compact('hostel'));
    }

    public function update(Request $request, Hostel $hostel)
    {
        if ($hostel->id != auth()->user()->hostel_id) {
            abort(403, 'तपाईंसँग यो होस्टल सम्पादन गर्ने अनुमति छैन');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string',
            'city' => 'required|string',
            'state' => 'required|string',
            'postal_code' => 'required|string',
            'phone' => 'required|string',
            'email' => 'required|email',
            'description' => 'nullable|string',
            'max_capacity' => 'required|integer',
            'price_per_month' => 'required|numeric',
        ]);

        $hostel->update($request->all());

        return redirect()->route('owner.hostels.index')
            ->with('success', 'होस्टल विवरण सफलतापूर्वक अद्यावधिक गरियो!');
    }

    public function destroy(Hostel $hostel)
    {
        // होस्टल मालिकले होस्टल हटाउन पाउँदैनन्
        abort(403, 'तपाईंसँग होस्टल हटाउने अनुमति छैन');
    }
}
