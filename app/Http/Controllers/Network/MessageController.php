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

    /**
     * इनबक्स देखाउँछ (जसमा ब्रॉडकास्ट थ्रेडहरू पनि आउँछन्)
     */
    public function index(Request $request)
    {
        $filters = $request->only(['category']);
        $threads = $this->messageService->getInbox(Auth::id(), $filters);
        return view('network.messages.inbox', compact('threads'));
    }

    /**
     * एकल थ्रेड (message thread) देखाउँछ
     */
    public function show($threadId)
    {
        $thread = MessageThread::with(['messages.sender', 'participants.user'])
            ->whereHas('participants', fn($q) => $q->where('user_id', Auth::id()))
            ->findOrFail($threadId);

        $this->messageService->markAsRead($threadId, Auth::id());

        return view('network.messages.show', compact('thread'));
    }

    /**
     * नयाँ सन्देश पठाउँछ (वा अवस्थित थ्रेडमा जवाफ)
     */
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

        // पठाउने व्यक्तिको tenant ID प्राप्त गर्ने (Owner प्रोफाइलबाट)
        $tenantId = $sender->ownerProfile->tenant_id ?? null;
        if (!$tenantId) {
            return back()->with('error', 'Tenant जानकारी फेला परेन। कृपया प्रोफाइल पूरा गर्नुहोस्।');
        }

        // 🔐 नयाँ थ्रेडको लागि प्राप्तकर्ता योग्य छ कि छैन जाँच गर्ने
        if (empty($validated['thread_id']) && isset($validated['recipient_id'])) {
            $recipient = User::find($validated['recipient_id']);
            if (!$recipient) {
                return back()->with('error', 'प्राप्तकर्ता फेला परेन।');
            }
            // एडमिनलाई पनि अनुमति दिने (तर यहाँ ownerProfile भएका मात्र आउँछन्)
            if (!$recipient->hasEligibleHostel() && !$recipient->isAdmin()) {
                return back()->with('error', 'यो प्राप्तकर्ता सन्देश प्राप्त गर्न योग्य छैन।');
            }
        }

        // यदि अवस्थित थ्रेड हो भने, tenant ID मिल्दो छ कि छैन जाँच गर्ने
        if (!empty($validated['thread_id'])) {
            $thread = MessageThread::find($validated['thread_id']);
            if (!$thread) {
                return back()->with('error', 'थ्रेड फेला परेन।');
            }
            if ($thread->tenant_id != $tenantId) {
                return back()->with('error', 'तपाईंलाई यो थ्रेडमा सन्देश पठाउने अनुमति छैन।');
            }
        }

        // नयाँ थ्रेड सिर्जना गर्ने (यदि आवश्यक भए)
        if (empty($validated['thread_id'])) {
            $participants = [$senderId, $validated['recipient_id']];
            $thread = $this->messageService->createThread($participants, $validated['subject'] ?? null);
            $threadId = $thread->id;

            // tenant_id सेट गर्ने
            $thread->tenant_id = $tenantId;
            $thread->save();
        } else {
            $threadId = $validated['thread_id'];
        }

        // सन्देश पठाउने
        $message = $this->messageService->sendMessage(
            $threadId,
            $senderId,
            $validated['body'],
            $validated['category'],
            $validated['priority']
        );

        // थ्रेड र सन्देश दुवैमा tenant_id छ भनी सुनिश्चित गर्ने (वैकल्पिक)
        if ($message) {
            $message->tenant_id = $tenantId;
            $message->save();
        }

        return redirect()->route('network.messages.show', $threadId)
            ->with('success', 'सन्देश पठाइयो।');
    }

    /**
     * थ्रेडलाई अभिलेख (archive) गर्ने
     */
    public function archive($threadId)
    {
        $this->messageService->archiveThread($threadId, Auth::id());
        return redirect()->route('network.messages.index')
            ->with('success', 'थ्रेड अभिलेख गरियो।');
    }
}
