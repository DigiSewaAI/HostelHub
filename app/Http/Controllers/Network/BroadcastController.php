<?php

namespace App\Http\Controllers\Network;

use App\Http\Controllers\Controller;
use App\Services\BroadcastService;
use App\Services\MessageService;
use App\Models\BroadcastMessage;
use App\Models\User;
use App\Helpers\TenantHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Events\BroadcastMessageCreated;

class BroadcastController extends Controller
{
    protected $broadcastService;
    protected $messageService;

    public function __construct(BroadcastService $broadcastService, MessageService $messageService)
    {
        $this->broadcastService = $broadcastService;
        $this->messageService = $messageService;
    }

    /**
     * ब्रोडकास्ट सिर्जना पृष्ठ
     */
    public function create()
    {
        $user = Auth::user();
        $tenantId = TenantHelper::getTenantId($user);
        $canSend = $this->broadcastService->checkCooldown($user->id);

        if (!$tenantId) {
            return redirect()->route('network.broadcast.index')
                ->with('error', 'Tenant जानकारी फेला परेन। कृपया प्रोफाइल पूरा गर्नुहोस्।');
        }

        if (!$canSend) {
            return redirect()->route('network.broadcast.index')
                ->with('error', 'कूलडाउन अवधि समाप्त भएको छैन। कृपया पर्खनुहोस्।');
        }

        return view('network.broadcast.create');
    }

    /**
     * ब्रोडकास्ट भण्डारण गर्ने र सबै योग्य प्राप्तकर्तालाई सन्देश पठाउने
     */
    public function store(Request $request)
    {
        $user = Auth::user();

        // 🔐 SENDER ELIGIBILITY CHECK (networking approval)
        // यदि user admin होइन भने networking approved हुनैपर्छ
        if (!$user->isAdmin() && !$user->isNetworkingApproved()) {
            return back()->with('error', 'तपाईंलाई ब्रोडकास्ट पठाउने अनुमति छैन। कृपया आफ्नो खाता र network profile स्वीकृत गराउनुहोस्।');
        }

        // कूलडाउन जाँच
        if (!$this->broadcastService->checkCooldown($user->id)) {
            return back()->with('error', 'कूलडाउन अवधि समाप्त भएको छैन। कृपया पर्खनुहोस्।');
        }

        $validated = $request->validate([
            'subject' => 'required|string|max:255',
            'body'    => 'required|string',
        ]);

        $tenantId = TenantHelper::getTenantId($user);
        if (!$tenantId) {
            return back()->with('error', 'Tenant जानकारी फेला परेन। कृपया प्रोफाइल पूरा गर्नुहोस्।');
        }

        // 1. BroadcastMessage मा रेकर्ड सेभ गर्ने (यदि तपाईंलाई चाहिन्छ भने)
        $broadcast = $this->broadcastService->createBroadcast($user->id, array_merge($validated, ['tenant_id' => $tenantId]));

        // 2. सबै योग्य प्राप्तकर्ताहरू लिने (admin वा networking approved प्रयोगकर्ता)
        $recipients = User::where(function ($query) {
            // Admin हरू (role_id 1 वा 'admin' role भएका)
            $query->where('role_id', 1)  // तपाईंको role_id अनुसार मिलाउनुहोस्
                ->orWhereHas('roles', function ($q) {
                    $q->where('name', 'admin');
                });
        })
            ->orWhere(function ($query) {
                // Networking approved प्रयोगकर्ता (account approved + कम्तीमा एउटा approved network profile)
                $query->whereHas('organizationRequests', function ($q) {
                    $q->where('status', 'approved');
                })
                    ->whereHas('hostels.networkProfile', function ($q) {
                        $q->whereNotNull('verified_at')
                            ->whereIn('trust_level', ['verified', 'trusted']);
                    });
            })
            ->where('id', '!=', $user->id) // आफूलाई छोडेर
            ->get();

        if ($recipients->isEmpty()) {
            return redirect()->route('network.broadcast.index')
                ->with('warning', 'कुनै प्राप्तकर्ता फेला परेन। ब्रोडकास्ट पठाइएन।');
        }

        // 3. प्रत्येक प्राप्तकर्ताको लागि थ्रेड सिर्जना गर्ने र सन्देश पठाउने
        foreach ($recipients as $recipient) {
            // थ्रेड सिर्जना
            $thread = $this->messageService->createThread(
                [$user->id, $recipient->id],
                $validated['subject']
            );

            // थ्रेडमा tenant_id र type सेट गर्ने
            $thread->tenant_id = $tenantId;
            $thread->type = 'broadcast';
            $thread->save();

            // सन्देश पठाउने
            $this->messageService->sendMessage(
                $thread->id,
                $user->id,
                $validated['body'],
                'general',        // ब्रोडकास्टको लागि category सामान्य
                'medium'          // priority मध्यम
            );
        }

        // 🆕 Event Dispatch: BroadcastMessageCreated event फायर गर्ने
        event(new BroadcastMessageCreated($broadcast, $recipients));

        return redirect()->route('network.broadcast.index')
            ->with('success', 'ब्रोडकास्ट सफलतापूर्वक पठाइयो।');
    }

    /**
     * पठाइएका ब्रोडकास्टहरूको सूची
     */
    public function index()
    {
        $broadcasts = BroadcastMessage::where('sender_id', Auth::id())
            ->latest()
            ->paginate(15);
        return view('network.broadcast.index', compact('broadcasts'));
    }
}
