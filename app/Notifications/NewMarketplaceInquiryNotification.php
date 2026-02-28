<?php

namespace App\Notifications;

use App\Models\MarketplaceListing;
use App\Models\User;
use App\Models\MessageThread;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\BroadcastMessage;

class NewMarketplaceInquiryNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $listing;
    protected $sender;
    protected $thread;

    public function __construct(MarketplaceListing $listing, User $sender, MessageThread $thread)
    {
        $this->listing = $listing;
        $this->sender = $sender;
        $this->thread = $thread;
    }

    public function via($notifiable)
    {
        return ['database', 'broadcast'];
    }

    public function toDatabase($notifiable)
    {
        $avatar = getNotificationAvatar($this->sender, 'marketplace');

        return [
            'type'      => 'marketplace',
            'title'     => 'नयाँ सोधपुछ',
            'message'   => $this->sender->name . ' ले "' . $this->listing->title . '" को बारेमा सोधेका छन्।',
            'avatar'    => $avatar,
            'url'       => route('network.messages.show', $this->thread->id),
            'sender_id' => $this->sender->id,
        ];
    }

    public function toBroadcast($notifiable)
    {
        return new BroadcastMessage($this->toDatabase($notifiable));
    }
}
