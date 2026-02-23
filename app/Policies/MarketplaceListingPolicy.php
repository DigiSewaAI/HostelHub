<?php

namespace App\Policies;

use App\Models\User;
use App\Models\MarketplaceListing;
use Illuminate\Auth\Access\HandlesAuthorization;

class MarketplaceListingPolicy
{
    use HandlesAuthorization;

    public function update(User $user, MarketplaceListing $listing)
    {
        return $user->id === $listing->owner_id && $listing->status === 'pending';
    }
}
