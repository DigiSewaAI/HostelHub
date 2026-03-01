<?php

namespace App\Events;

use App\Models\MarketplaceListing;
use App\Models\User;
use App\Models\MessageThread;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\InteractsWithSockets;

class MarketplaceInquirySent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $listing;
    public $sender;
    public $thread;
    public $recipient;

    public function __construct(MarketplaceListing $listing, User $sender, MessageThread $thread, User $recipient)
    {
        $this->listing = $listing;
        $this->sender = $sender;
        $this->thread = $thread;
        $this->recipient = $recipient;
    }
}
