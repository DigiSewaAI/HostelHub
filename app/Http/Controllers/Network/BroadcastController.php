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
        if (Gate::denies('create', BroadcastMessage::class)) {
            return back()->with('error', 'कूलडाउन अवधि समाप्त भएको छैन।');
        }

        $validated = $request->validate([
            'subject' => 'required|string|max:255',
            'body' => 'required|string',
        ]);

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
