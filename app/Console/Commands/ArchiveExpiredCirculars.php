<?php

namespace App\Console\Commands;

use App\Models\Circular;
use Illuminate\Console\Command;

class ArchiveExpiredCirculars extends Command
{
    protected $signature = 'circulars:archive-expired';
    protected $description = 'म्याद समाप्त भएका सर्कुलरहरूलाई अभिलेखीकृत गर्नुहोस्';

    public function handle()
    {
        $count = Circular::where('status', 'published')
            ->whereNotNull('expires_at')
            ->where('expires_at', '<=', now())
            ->update(['status' => 'archived']);

        $this->info("{$count} म्याद समाप्त सर्कुलर अभिलेखीकृत गरियो।");
    }
}
