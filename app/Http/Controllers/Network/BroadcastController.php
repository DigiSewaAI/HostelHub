<?php

namespace App\Http\Controllers\Network;

use App\Http\Controllers\Controller;
use App\Services\BroadcastService;
use App\Models\BroadcastMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class BroadcastController extends Controller
{
    protected $broadcastService;

    public function __construct(BroadcastService $broadcastService)
    {
        $this->broadcastService = $broadcastService;
    }

    public function create()
    {
        if (Gate::denies('create', BroadcastMessage::class)) {
            return back()->with('error', 'तपाईंले अघिल्लो ब्रोडकास्ट पठाएको ७ दिन पूरा भएको छैन।');
        }

        return view('network.broadcast.create');
    }

    public function store(Request $request)
    {
        // कूलडाउन चेक (यदि गेट छ भने)
        if (Gate::denies('create', BroadcastMessage::class)) {
            return back()->with('error', 'कूलडाउन अवधि समाप्त भएको छैन।');
        }

        // डाटा भ्यालिडेसन
        $validated = $request->validate([
            'subject' => 'required|string|max:255',
            'body'    => 'required|string',
        ]);

        // ✅ Tenant ID प्राप्त गर्ने (तपाईंको डाटाबेस संरचना अनुसार)
        $user = Auth::user();
        $tenantId = $user->ownerProfile->tenant_id ?? null;

        // यदि tenant ID छैन भने error फर्काउने (वा आवश्यकता अनुसार ह्यान्डल)
        if (!$tenantId) {
            return back()->with('error', 'Tenant जानकारी फेला परेन। कृपया प्रोफाइल पूरा गर्नुहोस्।');
        }

        // validated data मा tenant_id थप्ने
        $validated['tenant_id'] = $tenantId;

        // ब्रोडकास्ट सिर्जना गर्न service मा पठाउने
        $broadcast = $this->broadcastService->createBroadcast(Auth::id(), $validated);

        return redirect()->route('network.broadcast.index')
            ->with('success', 'ब्रोडकास्ट पेश गरियो। मध्यस्थता पछि प्रकाशित हुनेछ।');
    }

    public function index()
    {
        $broadcasts = BroadcastMessage::where('sender_id', Auth::id())
            ->latest()
            ->paginate(15);
        return view('network.broadcast.index', compact('broadcasts'));
    }
}
