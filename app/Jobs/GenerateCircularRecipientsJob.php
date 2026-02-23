<?php

namespace App\Jobs;

use App\Models\Circular;
use App\Services\AudienceResolverService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class GenerateCircularRecipientsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $circularId;

    /**
     * Create a new job instance.
     */
    public function __construct($circularId)
    {
        $this->circularId = $circularId;
    }

    /**
     * Execute the job.
     */
    public function handle(AudienceResolverService $resolver): void
    {
        $circular = Circular::find($this->circularId);
        if (!$circular) {
            Log::error("Circular {$this->circularId} recipient generation को लागि फेला परेन।");
            return;
        }

        // प्रयोगकर्ता ID हरू चंक (chunk) मा समाधान गर्नुहोस्
        $resolver->chunkUserIds($circular, function ($userIds, $userTypeMap) use ($circular) {
            $recipients = [];
            foreach ($userIds as $userId) {
                $recipients[] = [
                    'circular_id' => $circular->id,
                    'user_id'     => $userId,
                    'user_type'   => $userTypeMap[$userId] ?? 'student', // fallback
                    'created_at'  => now(),
                    'updated_at'  => now(),
                ];
            }

            // Batch insert
            DB::table('circular_recipients')->insert($recipients);
        }, 500); // chunk size
    }
}
