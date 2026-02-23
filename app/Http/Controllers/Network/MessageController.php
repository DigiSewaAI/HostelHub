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

        // यदि नयाँ थ्रेड हो भने
        if (empty($validated['thread_id'])) {
            $participants = [$senderId, $validated['recipient_id']];
            $thread = $this->messageService->createThread($participants, $validated['subject'] ?? null);
            $threadId = $thread->id;
        } else {
            $threadId = $validated['thread_id'];
        }

        $this->messageService->sendMessage(
            $threadId,
            $senderId,
            $validated['body'],
            $validated['category'],
            $validated['priority']
        );

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
