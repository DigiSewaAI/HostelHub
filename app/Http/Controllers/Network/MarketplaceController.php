<?php

namespace App\Http\Controllers\Network;

use App\Http\Controllers\Controller;
use App\Services\MarketplaceService;
use App\Models\MarketplaceListing;
use App\Models\MarketplaceCategory; // नयाँ थपियो: Category model
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str; // नयाँ थपियो: slug को लागि

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
            $query = MarketplaceListing::with('owner', 'media', 'category') // नयाँ: category पनि लोड गर्ने
                ->where('tenant_id', $tenantId);

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

            // नयाँ फिल्टर: visibility अनुसार
            if ($request->filled('visibility')) {
                $query->where('visibility', $request->visibility);
            }

            // नयाँ फिल्टर: category अनुसार
            if ($request->filled('category_id')) {
                $query->where('category_id', $request->category_id);
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
        // नयाँ थपियो: सक्रिय कोटिहरू लिने
        $categories = MarketplaceCategory::active()->get();
        return view('network.marketplace.create', compact('categories'));
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
            // नयाँ थपियो: नयाँ फिल्डहरूको लागि validation
            'visibility'  => 'required|in:private,both', // owner ले public छान्न पाउँदैन, admin मात्र
            'category_id' => 'nullable|exists:marketplace_categories,id',
            'condition'   => 'nullable|in:new,used',
            'quantity'    => 'integer|min:1',
            'price_type'  => 'required|in:fixed,negotiable',
        ]);

        $user = Auth::user();
        $tenantId = $user->ownerProfile->tenant_id ?? null;

        if (!$tenantId) {
            return back()->with('error', 'Tenant जानकारी फेला परेन। कृपया प्रोफाइल पूरा गर्नुहोस्।');
        }

        // नयाँ थपियो: slug बनाउने
        $validated['slug'] = Str::slug($validated['title']) . '-' . uniqid();
        $validated['tenant_id'] = $tenantId;
        $validated['owner_id'] = Auth::id(); // यो पनि थप्नुपर्छ, पहिले service मा पठाउँदा हुन्थ्यो

        // नयाँ थपियो: यदि quantity खाली छ भने default 1
        if (!isset($validated['quantity']) || empty($validated['quantity'])) {
            $validated['quantity'] = 1;
        }

        // सूची सिर्जना गर्न service मा पठाउने (service ले पनि नयाँ fields सम्हाल्नुपर्छ)
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

        $listing = MarketplaceListing::with('owner', 'media', 'category') // नयाँ: category पनि
            ->where('slug', $slug)
            ->where('tenant_id', $tenantId)
            ->firstOrFail();

        $this->marketplaceService->incrementViews($listing);

        return view('network.marketplace.show', compact('listing'));
    }

    /**
     * सूची सम्पादन गर्ने पृष्ठ
     */
    public function edit($slug)
    {
        $user = Auth::user();
        $tenantId = $user->ownerProfile->tenant_id ?? null;

        $listing = MarketplaceListing::where('slug', $slug)
            ->where('tenant_id', $tenantId)
            ->firstOrFail();

        // जाँच गर्ने: के यो लिस्टिङ यो user को हो?
        if ($listing->owner_id !== Auth::id()) {
            abort(403, 'तपाईंलाई यो सूची सम्पादन गर्ने अनुमति छैन।');
        }

        // नयाँ थपियो: सक्रिय कोटिहरू लिने
        $categories = MarketplaceCategory::active()->get();

        return view('network.marketplace.edit', compact('listing', 'categories'));
    }

    /**
     * सूची अपडेट गर्ने
     */
    public function update(Request $request, $slug)
    {
        $user = Auth::user();
        $tenantId = $user->ownerProfile->tenant_id ?? null;

        $listing = MarketplaceListing::where('slug', $slug)
            ->where('tenant_id', $tenantId)
            ->firstOrFail();

        // जाँच गर्ने: के यो लिस्टिङ यो user को हो?
        if ($listing->owner_id !== Auth::id()) {
            abort(403, 'तपाईंलाई यो सूची सम्पादन गर्ने अनुमति छैन।');
        }

        $validated = $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'required|string',
            'type'        => 'required|in:sale,lease,partnership,investment',
            'price'       => 'nullable|numeric|min:0',
            'location'    => 'nullable|string|max:255',
            // नयाँ थपियो: नयाँ फिल्डहरूको लागि validation
            'visibility'  => 'required|in:private,both',
            'category_id' => 'nullable|exists:marketplace_categories,id',
            'condition'   => 'nullable|in:new,used',
            'quantity'    => 'integer|min:1',
            'price_type'  => 'required|in:fixed,negotiable',
        ]);

        // यदि title परिवर्तन भएमा slug पनि अपडेट गर्ने
        if ($listing->title !== $validated['title']) {
            $validated['slug'] = Str::slug($validated['title']) . '-' . uniqid();
        }

        // नयाँ थपियो: यदि quantity खाली छ भने default 1
        if (!isset($validated['quantity']) || empty($validated['quantity'])) {
            $validated['quantity'] = 1;
        }

        $listing->update($validated);

        // मिडिया ह्यान्डलिङ (यदि नयाँ मिडिया छ भने)
        if ($request->hasFile('media')) {
            $this->marketplaceService->handleMediaUpload($listing, $request->file('media'));
        }

        return redirect()->route('network.marketplace.show', $listing->slug)
            ->with('success', 'सूची सफलतापूर्वक अपडेट गरियो।');
    }

    /**
     * सूची मेटाउने
     */
    public function destroy($slug)
    {
        $user = Auth::user();
        $tenantId = $user->ownerProfile->tenant_id ?? null;

        $listing = MarketplaceListing::where('slug', $slug)
            ->where('tenant_id', $tenantId)
            ->firstOrFail();

        // जाँच गर्ने: के यो लिस्टिङ यो user को हो?
        if ($listing->owner_id !== Auth::id()) {
            abort(403, 'तपाईंलाई यो सूची मेटाउने अनुमति छैन।');
        }

        // मिडिया पनि मेटाउने (service मा method छ भने)
        $this->marketplaceService->deleteListingMedia($listing);
        $listing->delete();

        return redirect()->route('network.marketplace.index')
            ->with('success', 'सूची सफलतापूर्वक मेटाइयो।');
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
            'सोधपुछ: ' . $listing->title
        );

        // Set tenant_id and type on the thread
        $thread->tenant_id = $tenantId;
        $thread->type = 'marketplace';  // ✅ type set गर्ने
        $thread->save();

        return redirect()->route('network.messages.show', $thread->id)
            ->with('success', 'मालिकलाई सन्देश पठाउन सक्नुहुन्छ।');
    }
}
