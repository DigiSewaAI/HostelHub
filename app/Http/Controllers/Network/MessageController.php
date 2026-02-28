<?php

namespace App\Http\Controllers\Network;

use App\Http\Controllers\Controller;
use App\Services\MessageService;
use App\Models\MessageThread;
use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Events\ChatMessageSent;

class MessageController extends Controller
{
    protected $messageService;

    public function __construct(MessageService $messageService)
    {
        $this->messageService = $messageService;
    }

    public function create(Request $request)
    {
        $recipientId = $request->query('recipient');
        $recipient = User::findOrFail($recipientId);
        return view('network.messages.create', compact('recipient'));
    }

    /**
     * Inbox with tabs (marketplace, broadcast, direct)
     */
    public function index(Request $request)
    {
        $filters = $request->only(['category']);
        $threads = $this->messageService->getInbox(Auth::id(), $filters); // participants को paginated collection

        // Eager load आवश्यक सम्बन्धहरू
        $threads->load('thread.messages.sender', 'thread.participants.user.hostels');

        // नपढिएको सन्देशको सङ्ख्या गणना गर्ने
        $marketplaceUnread = 0;
        $broadcastUnread = 0;
        $directUnread = 0;

        foreach ($threads as $participant) {
            $type = $participant->thread->type;
            $isUnread = $participant->last_read_at < $participant->thread->last_message_at;
            if ($type === 'marketplace') {
                if ($isUnread) $marketplaceUnread++;
            } elseif ($type === 'broadcast') {
                if ($isUnread) $broadcastUnread++;
            } else { // direct or null
                if ($isUnread) $directUnread++;
            }
        }

        // हालको ट्याब (default marketplace)
        $tab = $request->get('tab', 'marketplace');

        // ट्याब अनुसार फिल्टर गर्ने
        $filteredCollection = $threads->getCollection()->filter(function ($participant) use ($tab) {
            $type = $participant->thread->type;
            if ($tab === 'direct') {
                return $type === 'direct' || $type === null;
            }
            return $type === $tab;
        });

        // प्याजिनेसन कायम राख्दै नयाँ paginator बनाउने
        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        $perPage = $threads->perPage();
        $currentItems = $filteredCollection->slice(($currentPage - 1) * $perPage, $perPage)->values();
        $filteredThreads = new LengthAwarePaginator(
            $currentItems,
            $filteredCollection->count(),
            $perPage,
            $currentPage,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        return view('network.messages.inbox', compact(
            'filteredThreads',
            'tab',
            'marketplaceUnread',
            'broadcastUnread',
            'directUnread'
        ));
    }

    /**
     * Show single thread with messages
     */
    public function show($threadId)
    {
        $thread = MessageThread::with([
            'messages.sender.hostels' => function ($q) {
                $q->where('status', 'active')->where('is_published', true);
            },
            'participants.user.hostels'
        ])->whereHas('participants', fn($q) => $q->where('user_id', Auth::id()))
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

        // ✅ डाइरेक्टरीबाट आएको हो कि जाँच गर्ने
        $fromDirectory = $request->has('from_directory') && $request->from_directory == 1;

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

            // tenant_id सेट गर्ने
            $thread->tenant_id = $tenantId;

            // thread type निर्धारण गर्ने
            $recipient = User::find($validated['recipient_id']);
            $type = 'direct'; // default

            if ($recipient && $recipient->isAdmin()) {
                $type = 'broadcast';
            } elseif (!$fromDirectory && in_array($validated['category'], ['business_inquiry', 'partnership', 'hostel_sale'])) {
                // डाइरेक्टरीबाट आएको होइन र category यी मध्ये कुनै हो भने मात्र marketplace
                $type = 'marketplace';
            }
            // अन्यथा direct नै रहन्छ (जसमा fromDirectory = true वा category emergency/general समावेश छ)

            $thread->type = $type;
            $thread->save();

            $threadId = $thread->id;
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

            // 🆕 Event Dispatch: सन्देश पठाइसकेपछि ChatMessageSent event फायर गर्ने
            $thread = MessageThread::with('participants.user')->find($threadId);
            $participants = $thread->participants
                ->pluck('user')
                ->reject(function ($user) use ($senderId) {
                    return $user->id == $senderId;
                });

            event(new ChatMessageSent($message, $thread, $participants));
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
