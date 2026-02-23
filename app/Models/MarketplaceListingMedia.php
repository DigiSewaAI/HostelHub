<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MarketplaceListingMedia extends Model
{
    use HasFactory;

    protected $table = 'marketplace_listing_media';

    protected $fillable = [
        'listing_id',
        'file_path',
        'order',
    ];

    protected $casts = [
        'order' => 'integer',
    ];

    public function listing()
    {
        return $this->belongsTo(MarketplaceListing::class, 'listing_id');
    }
}
