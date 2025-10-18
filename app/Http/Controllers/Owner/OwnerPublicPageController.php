<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Hostel;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class OwnerPublicPageController extends Controller
{
    public function edit()
    {
        // Get the owner's hostel - adjust based on your relationship
        $hostel = auth()->user()->hostels()->first();

        if (!$hostel) {
            return redirect()->route('owner.dashboard')
                ->with('error', 'तपाईंसँग कुनै होस्टल छैन। पहिले होस्टल सिर्जना गर्नुहोस्।');
        }

        return view('owner.public-page.edit', compact('hostel'));
    }

    /**
     * Handle form submission for preview (POST request)
     */
    public function updateAndPreview(Request $request)
    {
        $hostel = auth()->user()->hostels()->first();

        if (!$hostel) {
            return back()->with('error', 'होस्टल फेला परेन।');
        }

        $validated = $request->validate([
            'description' => 'nullable|string|max:2000',
            'theme_color' => 'nullable|string|size:7',
            'logo' => 'nullable|image|max:2048'
        ]);

        // Handle logo upload
        if ($request->hasFile('logo')) {
            // Delete old logo if exists
            if ($hostel->logo_path && Storage::disk('public')->exists($hostel->logo_path)) {
                Storage::disk('public')->delete($hostel->logo_path);
            }

            $path = $request->file('logo')->store('hostel_logos', 'public');
            $hostel->logo_path = $path;
        }

        // Update theme color
        if ($request->has('theme_color')) {
            $hostel->theme_color = $validated['theme_color'];
        }

        // Update description
        if ($request->has('description')) {
            $hostel->description = $validated['description'];
        }

        $hostel->save();

        // ✅ FIXED: Redirect to the preview route with the updated hostel
        return redirect()->route('hostels.preview', $hostel->slug)
            ->with('success', 'पूर्वावलोकन सफलतापूर्वक अपडेट गरियो।');
    }

    /**
     * Handle preview display (GET request)
     */
    public function preview($slug)
    {
        // Get the hostel by slug
        $hostel = Hostel::where('slug', $slug)->firstOrFail();

        // ✅ FIXED: Directly render the public view with preview flag
        return view('public.hostels.show', compact('hostel'))->with('preview', true);
    }

    public function publish(Request $request)
    {
        $hostel = auth()->user()->hostels()->first();

        if (!$hostel) {
            return back()->with('error', 'होस्टल फेला परेन।');
        }

        // Generate slug if not exists
        if (empty($hostel->slug)) {
            $hostel->slug = Str::slug($hostel->name) . '-' . substr(uniqid(), -4);
        }

        $hostel->is_published = true;
        $hostel->published_at = now();
        $hostel->save();

        return back()->with('success', 'तपाईंको होस्टल पृष्ठ सफलतापूर्वक प्रकाशित गरियो!');
    }

    public function unpublish(Request $request)
    {
        $hostel = auth()->user()->hostels()->first();

        if (!$hostel) {
            return back()->with('error', 'होस्टल फेला परेन।');
        }

        $hostel->is_published = false;
        $hostel->save();

        return back()->with('success', 'तपाईंको होस्टल पृष्ठ अप्रकाशित गरियो।');
    }
}
