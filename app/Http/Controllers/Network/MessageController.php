<?php

namespace App\Http\Controllers\Network;

use App\Http\Controllers\Controller;
use App\Services\MessageService;
use App\Models\MessageThread;
use App\Models\Message;
use App\Models\User;
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
        $sender = Auth::user();

        // Get tenant ID from the sender's owner profile
        $tenantId = $sender->ownerProfile->tenant_id ?? null;
        if (!$tenantId) {
            return back()->with('error', 'Tenant जानकारी फेला परेन। कृपया प्रोफाइल पूरा गर्नुहोस्।');
        }

        // 🔐 RECIPIENT ELIGIBILITY CHECK (for new thread)
        if (empty($validated['thread_id']) && isset($validated['recipient_id'])) {
            $recipient = User::find($validated['recipient_id']);
            if (!$recipient) {
                return back()->with('error', 'प्राप्तकर्ता फेला परेन।');
            }
            // Allow admins even if they have no eligible hostel
            if (!$recipient->hasEligibleHostel() && !$recipient->isAdmin()) {
                return back()->with('error', 'यो प्राप्तकर्ता सन्देश प्राप्त गर्न योग्य छैन।');
            }
        }

        // If existing thread, validate that thread belongs to the same tenant
        if (!empty($validated['thread_id'])) {
            $thread = MessageThread::find($validated['thread_id']);
            if (!$thread) {
                return back()->with('error', 'थ्रेड फेला परेन।');
            }
            if ($thread->tenant_id != $tenantId) {
                return back()->with('error', 'तपाईंलाई यो थ्रेडमा सन्देश पठाउने अनुमति छैन।');
            }
        }

        // Create new thread if needed
        if (empty($validated['thread_id'])) {
            $participants = [$senderId, $validated['recipient_id']];
            $thread = $this->messageService->createThread($participants, $validated['subject'] ?? null);
            $threadId = $thread->id;

            // Set tenant_id on the thread
            $thread->tenant_id = $tenantId;
            $thread->save();
        } else {
            $threadId = $validated['thread_id'];
        }

        // Send the message
        $message = $this->messageService->sendMessage(
            $threadId,
            $senderId,
            $validated['body'],
            $validated['category'],
            $validated['priority']
        );

        // Set tenant_id on the message as well (optional, for consistency)
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
