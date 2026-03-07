<?php

namespace App\Observers;

use App\Models\Hostel;
use App\Models\Organization;
use App\Http\Controllers\Frontend\PublicController;
use App\Services\NetworkProfileSyncService;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

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
     * Handle the Hostel "creating" event.
     * Automatically assign the correct organization based on the hostel name.
     */
    public function creating(Hostel $hostel): void
    {
        // Find or create an organization with the same name as the hostel
        $organization = Organization::firstOrCreate(
            ['name' => $hostel->name],
            [
                'slug'     => Str::slug($hostel->name) . '-' . uniqid(),
                'is_ready' => true,
            ]
        );

        $hostel->organization_id = $organization->id;
    }

    /**
     * Handle the Hostel "created" event.
     */
    public function created(Hostel $hostel): void
    {
        $this->clearGalleryCache();

        // If the currently authenticated user is an owner, link them to the organization
        if (Auth::check() && Auth::user()->hasRole('owner')) {
            $organization = $hostel->organization;
            $user = Auth::user();

            if (!$organization->users()->where('user_id', $user->id)->exists()) {
                $organization->users()->attach($user->id, ['role' => 'owner']);
            }
        }
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
