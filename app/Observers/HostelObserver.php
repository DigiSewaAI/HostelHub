<?php

namespace App\Observers;

use App\Models\Hostel;
use App\Http\Controllers\Frontend\PublicController;

class HostelObserver
{
    /**
     * Handle the Hostel "created" event.
     */
    public function created(Hostel $hostel): void
    {
        $this->clearGalleryCache();
    }

    /**
     * Handle the Hostel "updated" event.
     */
    public function updated(Hostel $hostel): void
    {
        if ($hostel->isDirty(['is_published', 'status', 'name'])) {
            $this->clearGalleryCache();
        }
    }

    /**
     * Handle the Hostel "deleted" event.
     */
    public function deleted(Hostel $hostel): void
    {
        $this->clearGalleryCache();
    }

    /**
     * Clear gallery cache
     */
    private function clearGalleryCache(): void
    {
        $controller = app(PublicController::class);
        $controller->clearGalleryCache();
    }
}
