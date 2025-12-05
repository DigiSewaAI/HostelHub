<?php

namespace App\Observers;

use App\Models\Room;
use App\Http\Controllers\Frontend\PublicController;

class RoomObserver
{
    /**
     * Handle the Room "created" event.
     */
    public function created(Room $room): void
    {
        $this->clearGalleryCache();
    }

    /**
     * Handle the Room "updated" event.
     */
    public function updated(Room $room): void
    {
        if ($room->isDirty(['image', 'hostel_id'])) {
            $this->clearGalleryCache();
        }
    }

    /**
     * Handle the Room "deleted" event.
     */
    public function deleted(Room $room): void
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
