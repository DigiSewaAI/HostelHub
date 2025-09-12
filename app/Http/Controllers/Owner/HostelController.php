<?php

namespace App\Http\Controllers\Owner;

use App\Models\Hostel;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Owner\UpdateHostelRequest;

class HostelController extends Controller
{
    public function index()
    {
        $hostel = Hostel::where('id', auth()->user()->hostel_id)->first();
        return view('owner.hostels.index', compact('hostel'));
    }

    public function create()
    {
        abort(403, 'तपाईंसँग नयाँ होस्टल बनाउने अनुमति छैन');
    }

    public function store(Request $request)
    {
        abort(403, 'तपाईंसँग नयाँ होस्टल बनाउने अनुमति छैन');
    }

    public function edit(Hostel $hostel)
    {
        if ($hostel->id != auth()->user()->hostel_id) {
            abort(403, 'तपाईंसँग यो होस्टल सम्पादन गर्ने अनुमति छैन');
        }

        return view('owner.hostels.edit', compact('hostel'));
    }

    public function update(UpdateHostelRequest $request, Hostel $hostel)
    {
        if ($hostel->id != auth()->user()->hostel_id) {
            abort(403, 'तपाईंसँग यो होस्टल सम्पादन गर्ने अनुमति छैन');
        }

        $validated = $request->validated();

        // Handle facilities field
        if ($request->has('facilities')) {
            $validated['facilities'] = json_encode(explode(',', $request->facilities));
        }

        // Handle image upload
        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($hostel->image) {
                Storage::disk('public')->delete($hostel->image);
            }
            $validated['image'] = $request->file('image')->store('hostels', 'public');
        }

        $hostel->update($validated);

        return redirect()->route('owner.hostels.index')
            ->with('success', 'होस्टल विवरण सफलतापूर्वक अद्यावधिक गरियो!');
    }

    public function destroy(Hostel $hostel)
    {
        abort(403, 'तपाईंसँग होस्टल हटाउने अनुमति छैन');
    }
}
