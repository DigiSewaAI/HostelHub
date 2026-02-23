<?php

namespace App\Http\Controllers\Network;

use App\Http\Controllers\Controller;
use App\Services\MessageService;
use App\Models\MessageThread;
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MessageController extends Controller
{
    protected $messageService;

    public function __construct(MessageService $messageService)
    {
        $this->messageService = $messageService;
    }

    public function index(Request $request)
    {
        $filters = $request->only(['category']);
        $threads = $this->messageService->getInbox(Auth::id(), $filters);
        return view('network.messages.inbox', compact('threads'));
    }

    public function show($threadId)
    {
        $thread = MessageThread::with(['messages.sender', 'participants.user'])
            ->whereHas('participants', fn($q) => $q->where('user_id', Auth::id()))
            ->findOrFail($threadId);

        $this->messageService->markAsRead($threadId, Auth::id());

        return view('network.messages.show', compact('thread'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'thread_id' => 'required_without:recipient_id|exists:message_threads,id',
            'recipient_id' => 'required_without:thread_id|exists:users,id',
            'body' => 'required|string',
            'category' => 'required|in:business_inquiry,partnership,hostel_sale,emergency,general',
            'priority' => 'required|in:low,medium,high,urgent',
            'subject' => 'nullable|string|max:255',
        ]);

        $senderId = Auth::id();

        // ✅ Tenant ID प्राप्त गर्ने (तपाईंको डाटाबेस संरचना अनुसार मिलाउनुहोस्)
        $user = Auth::user();
        $tenantId = $user->ownerProfile->tenant_id ?? null;  // यदि ownerProfile छ भने

        // यदि tenant ID छैन भने error फर्काउने (वा आवश्यकता अनुसार ह्यान्डल)
        if (!$tenantId) {
            return back()->with('error', 'Tenant जानकारी फेला परेन। कृपया प्रोफाइल पूरा गर्नुहोस्।');
        }

        // यदि नयाँ थ्रेड हो भने
        if (empty($validated['thread_id'])) {
            $participants = [$senderId, $validated['recipient_id']];
            $thread = $this->messageService->createThread($participants, $validated['subject'] ?? null);
            $threadId = $thread->id;

            // ✅ नयाँ थ्रेडमा tenant_id सेट गर्ने
            $thread->tenant_id = $tenantId;
            $thread->save();
        } else {
            $threadId = $validated['thread_id'];
        }

        // सन्देश पठाउने (messageService ले message object फर्काउँछ भन्ने मानिएको छ)
        $message = $this->messageService->sendMessage(
            $threadId,
            $senderId,
            $validated['body'],
            $validated['category'],
            $validated['priority']
        );

        // ✅ सन्देशमा tenant_id सेट गर्ने
        if ($message) {
            $message->tenant_id = $tenantId;
            $message->save();
        }

        return redirect()->route('network.messages.show', $threadId)
            ->with('success', 'सन्देश पठाइयो।');
    }

    public function archive($threadId)
    {
        $this->messageService->archiveThread($threadId, Auth::id());
        return redirect()->route('network.messages.index')
            ->with('success', 'थ्रेड अभिलेख गरियो।');
    }
}
