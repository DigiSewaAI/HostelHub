<?php

namespace App\Console\Commands;

use App\Models\Circular;
use App\Jobs\GenerateCircularRecipientsJob;
use Illuminate\Console\Command;

class PublishScheduledCirculars extends Command
{
    protected $signature = 'circulars:publish';
    protected $description = 'तालिकाबद्ध सर्कुलरहरू प्रकाशित गर्नुहोस् जसको scheduled_at समय आइपुगेको छ';

    public function handle()
    {
        $circulars = Circular::where('status', 'draft')
            ->whereNotNull('scheduled_at')
            ->where('scheduled_at', '<=', now())
            ->get();

        foreach ($circulars as $circular) {
            $circular->update([
                'status' => 'published',
                'published_at' => now(),
            ]);

            // पुराना प्रापक मेटाएर पुनः उत्पन्न गर्नुहोस् (सुरक्षित रहन)
            $circular->recipients()->delete();
            GenerateCircularRecipientsJob::dispatch($circular->id);

            $this->info("प्रकाशित: Circular ID {$circular->id}");
        }

        $this->info("{$circulars->count()} तालिकाबद्ध सर्कुलर प्रकाशित गरियो।");
    }
}
