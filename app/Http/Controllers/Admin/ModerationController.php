<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BroadcastMessage;
use App\Models\MarketplaceListing;
use App\Services\BroadcastService;
use App\Services\MarketplaceService;
use Illuminate\Http\Request;

class ModerationController extends Controller
{
    protected $broadcastService;
    protected $marketplaceService;

    public function __construct(BroadcastService $broadcastService, MarketplaceService $marketplaceService)
    {
        $this->broadcastService = $broadcastService;
        $this->marketplaceService = $marketplaceService;
    }

    public function index()
    {
        $pendingBroadcasts = BroadcastMessage::where('status', 'pending')->latest()->get();
        $pendingListings = MarketplaceListing::where('status', 'pending')->latest()->get();

        return view('admin.moderation.index', compact('pendingBroadcasts', 'pendingListings'));
    }

    public function approveBroadcast($id)
    {
        $this->broadcastService->approveBroadcast($id, auth()->id());
        return back()->with('success', 'ब्रोडकास्ट स्वीकृत गरियो।');
    }

    public function rejectBroadcast(Request $request, $id)
    {
        $broadcast = BroadcastMessage::findOrFail($id);
        $broadcast->update([
            'status' => 'rejected',
            'moderated_by' => auth()->id(),
            'moderated_at' => now(),
            'moderation_notes' => $request->notes,
        ]);
        return back()->with('success', 'ब्रोडकास्ट अस्वीकृत गरियो।');
    }

    public function approveListing($id)
    {
        $this->marketplaceService->approveListing($id, auth()->id());
        return back()->with('success', 'सूची स्वीकृत गरियो।');
    }

    public function rejectListing(Request $request, $id)
    {
        $listing = MarketplaceListing::findOrFail($id);
        $listing->update([
            'status' => 'rejected',
            'moderated_by' => auth()->id(),
            'moderated_at' => now(),
            'moderation_notes' => $request->notes,
        ]);
        return back()->with('success', 'सूची अस्वीकृत गरियो।');
    }
}
