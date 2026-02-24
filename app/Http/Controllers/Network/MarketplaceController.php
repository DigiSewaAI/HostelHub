<?php

namespace App\Http\Controllers\Network;

use App\Http\Controllers\Controller;
use App\Services\MarketplaceService;
use App\Models\MarketplaceListing;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MarketplaceController extends Controller
{
    protected $marketplaceService;

    public function __construct(MarketplaceService $marketplaceService)
    {
        $this->marketplaceService = $marketplaceService;
    }

    /**
     * सबै सूचीहरू देखाउने (फिल्टर सहित)
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $tenantId = $user->ownerProfile->tenant_id ?? null;

        // यदि tenant ID छैन भने खाली नतिजा देखाउने
        if (!$tenantId) {
            $listings = collect([]); // खाली collection
        } else {
            $query = MarketplaceListing::with('owner', 'media')
                ->where('tenant_id', $tenantId); // ✅ सही tenant_id प्रयोग

            // फिल्टरहरू
            if ($request->filled('type')) {
                $query->where('type', $request->type);
            }

            if ($request->filled('status')) {
                $query->where('status', $request->status);
            }

            if ($request->filled('location')) {
                $query->where('location', 'like', '%' . $request->location . '%');
            }

            $listings = $query->latest()->paginate(12)->withQueryString();
        }

        return view('network.marketplace.index', compact('listings'));
    }

    /**
     * नयाँ सूची सिर्जना गर्ने पृष्ठ
     */
    public function create()
    {
        return view('network.marketplace.create');
    }

    /**
     * नयाँ सूची भण्डारण गर्ने
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'required|string',
            'type'        => 'required|in:sale,lease,partnership,investment',
            'price'       => 'nullable|numeric|min:0',
            'location'    => 'nullable|string|max:255',
            'media.*'     => 'nullable|image|max:2048', // प्रति फाइल 2MB
        ]);

        $user = Auth::user();
        $tenantId = $user->ownerProfile->tenant_id ?? null;

        if (!$tenantId) {
            return back()->with('error', 'Tenant जानकारी फेला परेन। कृपया प्रोफाइल पूरा गर्नुहोस्।');
        }

        $validated['tenant_id'] = $tenantId;

        // सूची सिर्जना गर्न service मा पठाउने
        $listing = $this->marketplaceService->createListing(Auth::id(), $validated);

        // मिडिया अपलोड भएमा ह्यान्डल गर्ने
        if ($request->hasFile('media')) {
            $this->marketplaceService->handleMediaUpload($listing, $request->file('media'));
        }

        return redirect()->route('network.marketplace.show', $listing->slug)
            ->with('success', 'सूची सिर्जना गरियो। मध्यस्थता पछि प्रकाशित हुनेछ।');
    }

    /**
     * एकल सूचीको विवरण देखाउने
     */
    public function show($slug)
    {
        $user = Auth::user();
        $tenantId = $user->ownerProfile->tenant_id ?? null;

        $listing = MarketplaceListing::with('owner', 'media')
            ->where('slug', $slug)
            ->where('tenant_id', $tenantId)
            ->firstOrFail();

        $this->marketplaceService->incrementViews($listing);

        return view('network.marketplace.show', compact('listing'));
    }

    /**
     * सूचीको मालिकलाई सम्पर्क गर्ने (सन्देश पठाउने)
     */
    public function contact(Request $request, $listingId)
    {
        $listing = MarketplaceListing::findOrFail($listingId);

        $user = Auth::user();
        $tenantId = $user->ownerProfile->tenant_id ?? null;

        if (!$tenantId || $listing->tenant_id != $tenantId) {
            return back()->with('error', 'तपाईंलाई यो सूचीमा सम्पर्क गर्ने अनुमति छैन।');
        }

        // 🔐 RECIPIENT ELIGIBILITY CHECK (listing owner)
        $owner = $listing->owner;
        if (!$owner->hasEligibleHostel() && !$owner->isAdmin()) {
            return back()->with('error', 'यो सूचीको मालिक हाल सन्देश प्राप्त गर्न योग्य छैन।');
        }

        $messageService = app(\App\Services\MessageService::class);

        // नयाँ सन्देश थ्रेड सिर्जना गर्ने
        $thread = $messageService->createThread(
            [Auth::id(), $listing->owner_id],
            'सूची: ' . $listing->title
        );

        // Set tenant_id on the thread
        $thread->tenant_id = $tenantId;
        $thread->save();

        return redirect()->route('network.messages.show', $thread->id)
            ->with('success', 'मालिकलाई सन्देश पठाउन सक्नुहुन्छ।');
    }
}
