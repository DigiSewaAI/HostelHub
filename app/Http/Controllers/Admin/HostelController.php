<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreHostelRequest;
use App\Http\Requests\UpdateHostelRequest;
use App\Models\Hostel;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class HostelController extends Controller
{
    public function index()
    {
        $hostels = Hostel::with('manager')->latest()->paginate(10);
        return view('admin.hostels.index', compact('hostels'));
    }

    public function create()
    {
        $managers = User::where('role', 'manager')->get();
        return view('admin.hostels.create', compact('managers'));
    }

    public function store(StoreHostelRequest $request)
    {
        $validated = $request->validated();
        $validated['slug'] = Str::slug($request->name);
        $validated['facilities'] = json_encode(explode(',', $request->facilities));

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('hostels', 'public');
        }

        Hostel::create($validated);

        return redirect()->route('admin.hostels.index')
            ->with('success', 'होस्टल सफलतापूर्वक थपियो');
    }

    public function show(Hostel $hostel)
    {
        return view('admin.hostels.show', compact('hostel'));
    }

    public function edit(Hostel $hostel)
    {
        $managers = User::where('role', 'manager')->get();
        return view('admin.hostels.edit', compact('hostel', 'managers'));
    }

    public function update(UpdateHostelRequest $request, Hostel $hostel)
    {
        $validated = $request->validated();
        $validated['facilities'] = json_encode(explode(',', $request->facilities));

        if ($request->hasFile('image')) {
            // Delete old image
            if ($hostel->image) {
                Storage::disk('public')->delete($hostel->image);
            }
            $validated['image'] = $request->file('image')->store('hostels', 'public');
        }

        $hostel->update($validated);

        return redirect()->route('admin.hostels.index')
            ->with('success', 'होस्टल सफलतापूर्वक अद्यावधिक गरियो');
    }

    public function destroy(Hostel $hostel)
    {
        if ($hostel->image) {
            Storage::disk('public')->delete($hostel->image);
        }

        $hostel->delete();

        return redirect()->route('admin.hostels.index')
            ->with('success', 'होस्टल सफलतापूर्वक मेटाइयो');
    }

    public function updateAvailability(Request $request, Hostel $hostel)
    {
        $request->validate([
            'available_rooms' => 'required|integer|min:0|max:' . $hostel->total_rooms
        ]);

        $hostel->update(['available_rooms' => $request->available_rooms]);

        return back()->with('success', 'उपलब्ध कोठाहरू अद्यावधिक गरियो');
    }
}
