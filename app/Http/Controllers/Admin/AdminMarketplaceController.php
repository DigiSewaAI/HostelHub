<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MarketplaceListing;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminMarketplaceController extends Controller
{
    public function index(Request $request)
    {
        $query = MarketplaceListing::with('owner');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $listings = $query->latest()->paginate(15)->withQueryString();

        return view('admin.network.marketplace.index', compact('listings'));
    }

    public function show(MarketplaceListing $listing)
    {
        $listing->load('owner', 'media');
        return view('admin.network.marketplace.show', compact('listing'));
    }

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
            ->with('success', 'Listing approved.');
    }

    public function reject(Request $request, MarketplaceListing $listing)
    {
        $request->validate([
            'rejected_reason' => 'required|string|max:1000',
        ]);

        $listing->update([
            'status' => 'rejected',
            'approved_by' => Auth::id(),
            'approved_at' => null,
            'moderated_by' => Auth::id(),
            'moderated_at' => now(),
            'rejected_reason' => $request->rejected_reason,
        ]);

        return redirect()->route('admin.network.marketplace.index')
            ->with('success', 'Listing rejected.');
    }
}
