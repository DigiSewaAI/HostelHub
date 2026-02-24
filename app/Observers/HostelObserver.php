<?php

namespace App\Observers;

use App\Models\Hostel;
use App\Http\Controllers\Frontend\PublicController;
use App\Services\NetworkProfileSyncService;

class HostelObserver
{
    protected $syncService;

    /**
     * Inject the network profile sync service.
     */
    public function __construct(NetworkProfileSyncService $syncService)
    {
        $this->syncService = $syncService;
    }

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
        // Network profile will be automatically deleted via foreign key cascade
    }

    /**
     * Handle the Hostel "saved" event.
     * This triggers after both create and update.
     */
    public function saved(Hostel $hostel): void
    {
        // Auto‑sync the network profile based on hostel eligibility
        $this->syncService->syncForHostel($hostel);
    }

    /**
     * Clear gallery cache using the existing PublicController method.
     */
    private function clearGalleryCache(): void
    {
        $controller = app(PublicController::class);
        $controller->clearGalleryCache();
    }
}
