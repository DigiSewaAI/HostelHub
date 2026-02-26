<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MarketplaceListing;
use App\Models\MarketplaceCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminMarketplaceController extends Controller
{
    /**
     * सबै लिस्टिङहरूको सूची (फिल्टर सहित)
     */
    public function index(Request $request)
    {
        $query = MarketplaceListing::with(['owner', 'category'])
            ->where('tenant_id', 35); // HostelHub organization

        // फिल्टरहरू
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('visibility')) {
            $query->where('visibility', $request->visibility);
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%")
                    ->orWhere('location', 'like', "%{$search}%");
            });
        }

        // क्रमबद्ध
        $query->latest();

        $listings = $query->paginate(15)->withQueryString();

        // फिल्टर ड्रपडाउनका लागि डेटा
        $categories = MarketplaceCategory::active()->get();

        return view('admin.network.marketplace.index', compact('listings', 'categories'));
    }

    /**
     * एकल लिस्टिङको विवरण देखाउने (पहिले जस्तै)
     */
    public function show(MarketplaceListing $listing)
    {
        $listing->load('owner', 'media', 'category');
        return view('admin.network.marketplace.show', compact('listing'));
    }

    /**
     * लिस्टिङ सम्पादन पृष्ठ (नयाँ)
     */
    public function edit(MarketplaceListing $listing)
    {
        $listing->load('owner', 'media', 'category');
        $categories = MarketplaceCategory::active()->get();
        return view('admin.network.marketplace.edit', compact('listing', 'categories'));
    }

    /**
     * लिस्टिङ अपडेट गर्ने (नयाँ फिल्डहरू सहित)
     */
    public function update(Request $request, MarketplaceListing $listing)
    {
        $validated = $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'description' => 'sometimes|required|string',
            'type' => 'sometimes|required|in:sale,lease,partnership,investment',
            'price' => 'sometimes|nullable|numeric|min:0',
            'location' => 'sometimes|nullable|string|max:255',
            'status' => 'sometimes|required|in:pending,approved,rejected,sold,closed',
            'visibility' => 'sometimes|required|in:private,public,both',
            'category_id' => 'sometimes|nullable|exists:marketplace_categories,id',
            'condition' => 'sometimes|nullable|in:new,used',
            'quantity' => 'sometimes|integer|min:1',
            'price_type' => 'sometimes|required|in:fixed,negotiable',
            'moderation_notes' => 'nullable|string|max:1000',
            'rejected_reason' => 'nullable|string|required_if:status,rejected|max:1000',
        ]);

        // यदि status approved गरिएको छ र पहिले approved थिएन भने approved_by/approved_at सेट गर्ने
        if (isset($validated['status']) && $validated['status'] === 'approved' && $listing->status !== 'approved') {
            $validated['approved_by'] = Auth::id();
            $validated['approved_at'] = now();
            $validated['moderated_by'] = Auth::id();
            $validated['moderated_at'] = now();
            $validated['rejected_reason'] = null;
        }

        // यदि status rejected गरिएको छ भने
        if (isset($validated['status']) && $validated['status'] === 'rejected') {
            $validated['moderated_by'] = Auth::id();
            $validated['moderated_at'] = now();
            $validated['approved_by'] = null;
            $validated['approved_at'] = null;
        }

        // यदि status approved बाट अरूमा परिवर्तन गरिएको छ भने approved fields खाली गर्ने
        if (isset($validated['status']) && $validated['status'] !== 'approved' && $listing->status === 'approved') {
            $validated['approved_by'] = null;
            $validated['approved_at'] = null;
        }

        $listing->update($validated);

        return redirect()->route('admin.network.marketplace.index')
            ->with('success', 'लिस्टिङ सफलतापूर्वक अपडेट गरियो।');
    }

    /**
     * लिस्टिङ स्वीकृत गर्ने (द्रुत कार्य)
     */
    public function approve(MarketplaceListing $listing)
    {
        $listing->update([
            'status' => 'approved',
            'approved_by' => Auth::id(),
            'approved_at' => now(),
            'moderated_by' => Auth::id(),
            'moderated_at' => now(),
            'rejected_reason' => null,
        ]);

        return redirect()->route('admin.network.marketplace.index')
            ->with('success', 'लिस्टिङ स्वीकृत गरियो।');
    }

    /**
     * लिस्टिङ अस्वीकृत गर्ने (कारण सहित)
     */
    public function reject(Request $request, MarketplaceListing $listing)
    {
        $request->validate([
            'rejected_reason' => 'required|string|max:1000',
        ]);

        $listing->update([
            'status' => 'rejected',
            'approved_by' => null,
            'approved_at' => null,
            'moderated_by' => Auth::id(),
            'moderated_at' => now(),
            'rejected_reason' => $request->rejected_reason,
        ]);

        return redirect()->route('admin.network.marketplace.index')
            ->with('success', 'लिस्टिङ अस्वीकृत गरियो।');
    }

    /**
     * लिस्टिङ मेटाउने (वैकल्पिक)
     */
    public function destroy(MarketplaceListing $listing)
    {
        // सम्बन्धित मिडिया पनि मेटाउने
        foreach ($listing->media as $media) {
            \Storage::delete($media->file_path);
            $media->delete();
        }

        $listing->delete();

        return redirect()->route('admin.network.marketplace.index')
            ->with('success', 'लिस्टिङ स्थायी रूपमा मेटाइयो।');
    }

    /**
     * बल्क कारबाही (धेरै लिस्टिङ एकै पटक)
     */
    public function bulkAction(Request $request)
    {
        $request->validate([
            'action' => 'required|in:approve,reject,delete',
            'ids' => 'required|array',
            'ids.*' => 'exists:marketplace_listings,id',
        ]);

        $ids = $request->ids;
        $action = $request->action;

        switch ($action) {
            case 'approve':
                MarketplaceListing::whereIn('id', $ids)
                    ->where('tenant_id', 35)
                    ->update([
                        'status' => 'approved',
                        'approved_by' => Auth::id(),
                        'approved_at' => now(),
                        'moderated_by' => Auth::id(),
                        'moderated_at' => now(),
                        'rejected_reason' => null,
                    ]);
                $message = 'चयनित लिस्टिङहरू स्वीकृत गरियो।';
                break;

            case 'reject':
                MarketplaceListing::whereIn('id', $ids)
                    ->where('tenant_id', 35)
                    ->update([
                        'status' => 'rejected',
                        'rejected_reason' => 'एडमिनद्वारा अस्वीकृत (बल्क)',
                        'approved_by' => null,
                        'approved_at' => null,
                        'moderated_by' => Auth::id(),
                        'moderated_at' => now(),
                    ]);
                $message = 'चयनित लिस्टिङहरू अस्वीकृत गरियो।';
                break;

            case 'delete':
                // पहिले सम्बन्धित मिडिया मेटाउने
                $listings = MarketplaceListing::whereIn('id', $ids)
                    ->where('tenant_id', 35)
                    ->get();
                foreach ($listings as $listing) {
                    foreach ($listing->media as $media) {
                        \Storage::delete($media->file_path);
                        $media->delete();
                    }
                    $listing->delete();
                }
                $message = 'चयनित लिस्टिङहरू मेटाइयो।';
                break;
        }

        return back()->with('success', $message);
    }
}
