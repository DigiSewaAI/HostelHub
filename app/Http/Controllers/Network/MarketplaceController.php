<?php

namespace App\Http\Controllers\Network;

use App\Http\Controllers\Controller;
use App\Services\MarketplaceService;
use App\Models\MarketplaceListing;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MarketplaceController extends Controller
{
    protected $marketplaceService;

    public function __construct(MarketplaceService $marketplaceService)
    {
        $this->marketplaceService = $marketplaceService;
    }

    public function index(Request $request)
    {
        $query = MarketplaceListing::with('owner', 'media')
            ->where('tenant_id', session('tenant_id'));

        if ($request->has('type')) {
            $query->where('type', $request->type);
        }

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        $listings = $query->latest()->paginate(12);
        return view('network.marketplace.index', compact('listings'));
    }

    public function create()
    {
        return view('network.marketplace.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'type' => 'required|in:sale,lease,partnership,investment',
            'price' => 'nullable|numeric|min:0',
            'location' => 'nullable|string|max:255',
            'media.*' => 'nullable|image|max:2048',
        ]);

        $listing = $this->marketplaceService->createListing(Auth::id(), $validated);

        if ($request->hasFile('media')) {
            $this->marketplaceService->handleMediaUpload($listing, $request->file('media'));
        }

        return redirect()->route('network.marketplace.show', $listing->slug)
            ->with('success', 'सूची सिर्जना गरियो। मध्यस्थता पछि प्रकाशित हुनेछ।');
    }

    public function show($slug)
    {
        $listing = MarketplaceListing::with('owner', 'media')
            ->where('slug', $slug)
            ->firstOrFail();

        $this->marketplaceService->incrementViews($listing);

        return view('network.marketplace.show', compact('listing'));
    }

    public function contact(Request $request, $listingId)
    {
        $listing = MarketplaceListing::findOrFail($listingId);
        $messageService = app(\App\Services\MessageService::class);

        $thread = $messageService->createThread(
            [Auth::id(), $listing->owner_id],
            'सूची: ' . $listing->title
        );

        return redirect()->route('network.messages.show', $thread->id);
    }
}
