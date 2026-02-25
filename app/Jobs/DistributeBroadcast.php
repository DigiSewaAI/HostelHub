<?php

namespace App\Jobs;

use App\Models\BroadcastMessage;
use App\Services\BroadcastDistributionService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class DistributeBroadcast implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $broadcast;

    public function __construct(BroadcastMessage $broadcast)
    {
        $this->broadcast = $broadcast;
    }

    public function handle(BroadcastDistributionService $service)
    {
        $service->distribute($this->broadcast);
    }
}
