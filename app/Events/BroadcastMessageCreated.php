<?php

namespace App\Events;

use App\Models\BroadcastMessage;
use Illuminate\Database\Eloquent\Collection;

class BroadcastMessageCreated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $broadcast;
    public $recipients;

    public function __construct(BroadcastMessage $broadcast, Collection $recipients)
    {
        $this->broadcast = $broadcast;
        $this->recipients = $recipients;
    }
}
