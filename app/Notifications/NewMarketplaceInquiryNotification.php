<?php

namespace App\Notifications;

use App\Models\MarketplaceListing;
use App\Models\User;
use App\Models\MessageThread;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class NewMarketplaceInquiryNotification extends Notification
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
        return ['database'];   // Only database channel
    }

    public function toArray($notifiable)
    {
        return [
            'message' => "{$this->sender->name} ले तपाईंको लिस्टिग '{$this->listing->title}' मा इन्क्वायरी पठाएको छ।",
            'listing_id' => $this->listing->id,
            'sender_id' => $this->sender->id,
            'thread_id' => $this->thread->id,
            'url' => route('network.messages.show', $this->thread->id),
            'avatar' => $this->getNotificationAvatar(),
        ];
    }

    protected function getNotificationAvatar()
    {
        if (function_exists('getNotificationAvatar')) {
            return getNotificationAvatar($this->sender);
        }

        return $this->sender->profile_photo_url ?? asset('images/logo.png');
    }
}
