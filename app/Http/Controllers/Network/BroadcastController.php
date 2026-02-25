<?php

namespace App\Http\Controllers\Network;

use App\Http\Controllers\Controller;
use App\Services\BroadcastService;
use App\Models\BroadcastMessage;
use App\Helpers\TenantHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BroadcastController extends Controller
{
    protected $broadcastService;

    public function __construct(BroadcastService $broadcastService)
    {
        $this->broadcastService = $broadcastService;
    }

    public function create()
    {
        $user = Auth::user();
        $tenantId = TenantHelper::getTenantId($user);
        $canSend = $this->broadcastService->checkCooldown($user->id);

        // यदि tenantId छैन भने
        if (!$tenantId) {
            dd('Tenant ID missing for user: ' . $user->id);
        }

        // यदि cooldown छैन भने
        if (!$canSend) {
            dd('Cooldown blocked for user: ' . $user->id);
        }

        session()->forget('error');
        return view('network.broadcast.create');
    }

    public function store(Request $request)
    {
        $user = Auth::user();

        if (!$this->broadcastService->checkCooldown($user->id)) {
            return back()->with('error', 'कूलडाउन अवधि समाप्त भएको छैन।');
        }

        $validated = $request->validate([
            'subject' => 'required|string|max:255',
            'body'    => 'required|string',
        ]);

        $tenantId = TenantHelper::getTenantId($user);

        if (!$tenantId) {
            return back()->with('error', 'Tenant जानकारी फेला परेन। कृपया प्रोफाइल पूरा गर्नुहोस्।');
        }

        $validated['tenant_id'] = $tenantId;

        $this->broadcastService->createBroadcast($user->id, $validated);

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
