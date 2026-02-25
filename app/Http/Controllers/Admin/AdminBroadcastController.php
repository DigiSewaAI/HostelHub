<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BroadcastMessage;
use App\Services\BroadcastDistributionService; // Service import गरियो
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Jobs\DistributeBroadcast; // Queue job (यदि प्रयोग गर्ने हो भने)

class AdminBroadcastController extends Controller
{
    protected $distributionService;

    /**
     * Constructor मा BroadcastDistributionService inject गरियो
     */
    public function __construct(BroadcastDistributionService $distributionService)
    {
        $this->distributionService = $distributionService;
    }

    public function index(Request $request)
    {
        $query = BroadcastMessage::with('sender');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $broadcasts = $query->latest()->paginate(15)->withQueryString();

        return view('admin.network.broadcasts.index', compact('broadcasts'));
    }

    public function show(BroadcastMessage $broadcast)
    {
        $broadcast->load('sender');
        return view('admin.network.broadcasts.show', compact('broadcast'));
    }

    /**
     * ब्रॉडकास्ट approve गर्ने मेथड
     * यसले ब्रॉडकास्टलाई approved मार्क गर्छ र सबै eligible प्रापकहरूलाई वितरण गर्छ।
     */
    public function approve(BroadcastMessage $broadcast)
    {
        $broadcast->update([
            'status' => 'approved',
            'approved_by' => Auth::id(),
            'approved_at' => now(),
            'moderated_by' => Auth::id(),
            'moderated_at' => now(),
            'rejected_reason' => null,
        ]);

        // यो लाइन थप्नुहोस् (queue वा सीधै)
        app(\App\Services\BroadcastDistributionService::class)->distribute($broadcast);

        return redirect()->route('admin.network.broadcasts.index')
            ->with('success', 'Broadcast approved.');
    }



    public function reject(Request $request, BroadcastMessage $broadcast)
    {
        $request->validate([
            'rejected_reason' => 'required|string|max:1000',
        ]);

        $broadcast->update([
            'status' => 'rejected',
            'approved_by' => Auth::id(),
            'approved_at' => null,
            'moderated_by' => Auth::id(),
            'moderated_at' => now(),
            'rejected_reason' => $request->rejected_reason,
        ]);

        // Notify owner about rejection (optional)
        // तपाईं यहाँ notification पठाउन सक्नुहुन्छ

        return redirect()->route('admin.network.broadcasts.index')
            ->with('success', 'Broadcast rejected.');
    }
}
