<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class Hostel extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'address',
        'city',
        'contact_person',
        'contact_phone',
        'contact_email',
        'description',
        'total_rooms',
        'available_rooms',
        'status',
        'facilities',
        'owner_id',
        'manager_id',
        'organization_id',
        'monthly_rent',
        'security_deposit',
        'image',
        'is_published',
        'published_at',
        'logo_path',
        'theme_color',
        'draft_data',
        // ✅ NEW: Social Media Fields
        'facebook_url',
        'instagram_url',
        'twitter_url',
        'tiktok_url',
        'whatsapp_number',
        'youtube_url',
        'linkedin_url',
        // ✅ NEW: Branding Control Field
        'show_hostelhub_branding',
        // ✅ NEW: Gender field for Boys/Girls filter
        'gender'
    ];

    protected $casts = [
        'facilities' => 'array',
        'is_published' => 'boolean',
        'published_at' => 'datetime',
        'show_hostelhub_branding' => 'boolean',
        // ✅ NEW: Cast gender field
        'gender' => 'string'
    ];

    // ✅ ADDED: Append accessor methods
    protected $appends = [
        'contact_phone_formatted',
        'contact_email_formatted',
        'gender_detected',  // ✅ NEW: Added gender_detected attribute
        'cover_image_url',   // ✅ NEW: Added for gallery background
        'main_image_url'     // ✅ NEW: Added for gallery background
    ];

    /**
     * Validation rules for Hostel model
     */
    public static function validationRules($id = null): array
    {
        return [
            'name' => 'required|string|max:255|unique:hostels,name,' . $id,
            'slug' => 'nullable|string|max:255|unique:hostels,slug,' . $id,
            'address' => 'required|string|max:500',
            'city' => 'required|string|max:100',
            'contact_person' => 'required|string|max:100',
            'contact_phone' => 'required|string|max:20',
            'contact_email' => 'required|email|max:100',
            'description' => 'nullable|string|max:2000',
            'total_rooms' => 'required|integer|min:0',
            'available_rooms' => 'required|integer|min:0',
            'status' => 'required|in:active,inactive,maintenance',
            'facilities' => 'nullable|array',
            'owner_id' => 'required|exists:users,id',
            'manager_id' => 'nullable|exists:users,id',
            'organization_id' => 'nullable|exists:organizations,id',
            'monthly_rent' => 'nullable|numeric|min:0',
            'security_deposit' => 'nullable|numeric|min:0',
            'image' => 'nullable|string|max:500',
            'is_published' => 'boolean',
            'published_at' => 'nullable|date',
            'logo_path' => 'nullable|string|max:500',
            'theme_color' => 'nullable|string|max:7',
            'draft_data' => 'nullable|array',
            'facebook_url' => 'nullable|url|max:500',
            'instagram_url' => 'nullable|url|max:500',
            'twitter_url' => 'nullable|url|max:500',
            'tiktok_url' => 'nullable|url|max:500',
            'whatsapp_number' => 'nullable|string|max:20',
            'youtube_url' => 'nullable|url|max:500',
            'linkedin_url' => 'nullable|url|max:500',
            'show_hostelhub_branding' => 'boolean',
            // ✅ NEW: Gender validation rule
            'gender' => 'nullable|in:boys,girls,mixed'
        ];
    }

    /**
     * Boot method for model events
     */
    protected static function boot()
    {
        parent::boot();

        // Update room counts when hostel is loaded (for existing data)
        static::retrieved(function ($hostel) {
            // Only update if counts are outdated (optional - can be heavy on performance)
            // $hostel->updateRoomCounts();
        });

        // Update slug when name is changed
        static::saving(function ($hostel) {
            if ($hostel->isDirty('name') && !$hostel->isDirty('slug')) {
                $hostel->slug = \Illuminate\Support\Str::slug($hostel->name);
            }
        });

        // ✅ NEW: Clean social media URLs before saving
        static::saving(function ($hostel) {
            $socialFields = [
                'facebook_url',
                'instagram_url',
                'twitter_url',
                'tiktok_url',
                'youtube_url',
                'linkedin_url'
            ];

            foreach ($socialFields as $field) {
                if (!empty($hostel->$field)) {
                    // Remove any whitespace
                    $hostel->$field = trim($hostel->$field);
                    // Ensure it starts with http:// or https://
                    if (!preg_match('/^https?:\/\//', $hostel->$field)) {
                        $hostel->$field = 'https://' . $hostel->$field;
                    }
                }
            }

            // Clean WhatsApp number (remove any non-digit characters except +)
            if (!empty($hostel->whatsapp_number)) {
                $hostel->whatsapp_number = preg_replace('/[^\d+]/', '', $hostel->whatsapp_number);
            }
        });

        // ✅ NEW: Auto-update hostel name in galleries when hostel name changes
        static::updated(function ($hostel) {
            if ($hostel->isDirty('name')) {
                // Update cached hostel name in all galleries
                \App\Models\Gallery::where('hostel_id', $hostel->id)
                    ->update(['hostel_name' => $hostel->name]);
            }
        });
    }

    public function networkProfile()
    {
        return $this->hasOne(OwnerNetworkProfile::class);
    }

    /**
     * Check if hostel is eligible for network visibility
     */
    public function isEligibleForNetwork(): bool
    {
        return $this->status === 'active' && $this->is_published;
    }

    public function marketplaceListings()
    {
        return $this->hasMany(MarketplaceListing::class, 'hostel_id');
    }

    public function rooms(): HasMany
    {
        return $this->hasMany(Room::class);
    }

    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function manager(): BelongsTo
    {
        return $this->belongsTo(User::class, 'manager_id');
    }

    public function images(): HasMany
    {
        return $this->hasMany(HostelImage::class);
    }

    // ✅ UPDATED: Renamed to galleries for consistency
    public function galleries(): HasMany
    {
        return $this->hasMany(Gallery::class);
    }

    // ✅ Organization सँगको relationship
    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class, 'organization_id');
    }

    // ✅ Students सँगको relationship
    public function students(): HasMany
    {
        return $this->hasMany(Student::class);
    }

    // ✅ Meal menus सँगको relationship
    public function mealMenus(): HasMany
    {
        return $this->hasMany(MealMenu::class);
    }

    // ✅ Reviews relationship
    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    // ✅ Approved reviews for the hostel
    public function approvedReviews()
    {
        return $this->reviews()->where('status', 'approved');
    }

    // ✅ Featured reviews for the hostel
    public function featuredReviews()
    {
        return $this->approvedReviews()->where('featured', true);
    }

    // ✅ Payments relationship
    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    // ✅ Payment methods relationship
    public function paymentMethods()
    {
        return $this->hasMany(PaymentMethod::class)->orderBy('order')->orderBy('created_at');
    }

    // ✅ Active payment methods
    public function activePaymentMethods()
    {
        return $this->paymentMethods()->where('is_active', true);
    }

    // ✅ Default payment method
    public function defaultPaymentMethod()
    {
        return $this->paymentMethods()->where('is_default', true)->first();
    }

    // ✅ Get bank payment methods
    public function getBankPaymentMethodsAttribute()
    {
        return $this->paymentMethods()->where('type', 'bank')->where('is_active', true)->get();
    }

    // ✅ Get digital payment methods
    public function getDigitalPaymentMethodsAttribute()
    {
        return $this->paymentMethods()
            ->whereIn('type', ['esewa', 'khalti', 'fonepay', 'imepay', 'connectips'])
            ->where('is_active', true)
            ->get();
    }

    // ✅ Check if hostel has any payment methods
    public function getHasPaymentMethodsAttribute(): bool
    {
        return $this->activePaymentMethods()->exists();
    }

    // ✅ Get payment methods for display in bill
    public function getBillPaymentMethodsAttribute(): array
    {
        $methods = $this->activePaymentMethods()->get();

        return $methods->map(function ($method) {
            return [
                'id' => $method->id,
                'type' => $method->type,
                'type_text' => $method->type_text,
                'title' => $method->title,
                'display_info' => $method->display_info,
                'qr_code_url' => $method->qr_code_url,
                'instructions' => $method->instructions,
                'is_default' => $method->is_default
            ];
        })->toArray();
    }

    // ✅ Scope for active hostels
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    // ✅ Scope for inactive hostels
    public function scopeInactive($query)
    {
        return $query->where('status', 'inactive');
    }

    // ✅ Scope published hostels
    public function scopePublished($query)
    {
        return $query->where('is_published', true)
            ->whereNotNull('published_at');
    }

    // ✅ Scope for user's hostels (owner or manager)
    public function scopeForUser($query, $userId)
    {
        return $query->where('owner_id', $userId)
            ->orWhere('manager_id', $userId);
    }

    // ✅ Scope for organization hostels
    public function scopeForOrganization($query, $organizationId)
    {
        return $query->where('organization_id', $organizationId);
    }

    // ✅ Calculate occupancy rate
    public function getOccupancyRateAttribute()
    {
        if ($this->total_rooms == 0) {
            return 0;
        }

        $occupied = $this->total_rooms - $this->available_rooms;
        return round(($occupied / $this->total_rooms) * 100, 2);
    }

    // ✅ Check if hostel has available rooms
    public function getHasAvailableRoomsAttribute()
    {
        return $this->available_rooms > 0;
    }

    // ✅ Check if hostel is fully published
    public function getIsFullyPublishedAttribute()
    {
        return $this->is_published && $this->published_at;
    }

    // ✅ Get public URL for the hostel
    public function getPublicUrlAttribute()
    {
        if (!$this->is_published) {
            return null;
        }
        return route('hostels.show', $this->slug);
    }

    /**
     * ✅ FIXED: Get logo URL - ALWAYS returns string
     */
    public function getLogoUrlAttribute(): string
    {
        if (!empty($this->logo_path)) {
            try {
                $url = \media_url($this->logo_path);
                if ($url !== asset('images/no-image.png')) {
                    return $url;
                }
            } catch (\Exception $e) {
                // Fall through
            }
        }

        return asset('images/default-hostel.png');
    }

    // ✅ Update room counts from relationships
    public function updateRoomCounts()
    {
        $this->update([
            'total_rooms' => $this->rooms()->count(),
            'available_rooms' => $this->rooms()->where('status', 'available')->count()
        ]);
    }

    // ✅ Get dynamic room statistics
    public function getRoomStatisticsAttribute()
    {
        return [
            'total' => $this->rooms()->count(),
            'available' => $this->rooms()->where('status', 'available')->count(),
            'occupied' => $this->rooms()->where('status', 'occupied')->count(),
            'maintenance' => $this->rooms()->where('status', 'maintenance')->count(),
        ];
    }

    // ✅ Get dynamic student count
    public function getStudentsCountAttribute()
    {
        return $this->students()->count();
    }

    // ✅ Get Nepali status
    public function getNepaliStatusAttribute()
    {
        $statuses = [
            'active' => 'सक्रिय',
            'inactive' => 'निष्क्रिय',
            'maintenance' => 'मर्मतमा'
        ];

        return $statuses[$this->status] ?? $this->status;
    }

    /**
     * ✅ FIXED: Get formatted contact phone with fallback - ALWAYS returns string
     */
    public function getContactPhoneFormattedAttribute(): string
    {
        // First check if hostel has direct contact phone
        if (!empty($this->attributes['contact_phone'])) {
            return (string) $this->attributes['contact_phone'];
        }

        // Fallback to owner's phone if owner relationship is loaded
        if ($this->relationLoaded('owner') && $this->owner) {
            if (!empty($this->owner->phone)) {
                return (string) $this->owner->phone;
            }

            // Fallback to owner's mobile
            if (!empty($this->owner->mobile)) {
                return (string) $this->owner->mobile;
            }
        }

        // Default fallback for public display
        return '+९७७ ९८०XXXXXXXX';
    }

    /**
     * ✅ FIXED: Get formatted contact email with fallback - ALWAYS returns string
     */
    public function getContactEmailFormattedAttribute(): string
    {
        // First check if hostel has direct contact email
        if (!empty($this->attributes['contact_email'])) {
            return (string) $this->attributes['contact_email'];
        }

        // Fallback to owner's email if owner relationship is loaded
        if ($this->relationLoaded('owner') && $this->owner && !empty($this->owner->email)) {
            return (string) $this->owner->email;
        }

        return 'info@hostelhub.com'; // Default fallback
    }

    /**
     * ✅ FIXED: Get contact phone for display (with smart fallback) - ALWAYS returns string
     */
    public function getDisplayPhoneAttribute(): string
    {
        return $this->contact_phone_formatted;
    }

    /**
     * ✅ FIXED: Get contact email for display (with smart fallback) - ALWAYS returns string
     */
    public function getDisplayEmailAttribute(): string
    {
        return $this->contact_email_formatted;
    }

    /**
     * ✅ FIXED: Gender detection for gallery filters - ALWAYS returns string
     */
    public function getGenderDetectedAttribute(): string
    {
        // 1. पहिले gender column check गर्ने
        if (!empty($this->attributes['gender'])) {
            $gender = strtolower(trim($this->attributes['gender']));
            if (in_array($gender, ['boys', 'girls', 'mixed'])) {
                return $gender;
            }
        }

        // 2. Hostel name बाट gender detect गर्ने
        $name = strtolower($this->name ?? '');

        // Boys detection with more keywords
        $boysKeywords = ['boys', 'ब्वाइज', 'पुरुष', 'ब्वायज', 'male', 'mens', 'पुरूष', 'पुर्खा'];
        foreach ($boysKeywords as $keyword) {
            if (str_contains($name, $keyword)) {
                return 'boys';
            }
        }

        // Girls detection with more keywords  
        $girlsKeywords = ['girls', 'गर्ल्स', 'महिला', 'female', 'womens', 'केटी', 'महिला'];
        foreach ($girlsKeywords as $keyword) {
            if (str_contains($name, $keyword)) {
                return 'girls';
            }
        }

        // 3. Description बाट पनि check गर्ने
        if (!empty($this->description)) {
            $desc = strtolower($this->description);
            foreach ($boysKeywords as $keyword) {
                if (str_contains($desc, $keyword)) {
                    return 'boys';
                }
            }
            foreach ($girlsKeywords as $keyword) {
                if (str_contains($desc, $keyword)) {
                    return 'girls';
                }
            }
        }

        return 'mixed';
    }

    /**
     * ✅ FIXED: Get cover image URL for gallery background - ALWAYS returns string
     */
    public function getCoverImageUrlAttribute(): string
    {
        $defaultImage = asset('images/default-hostel-bg.jpg');

        // 1. Try hostel images
        if ($this->images && $this->images->count() > 0) {
            foreach ($this->images as $image) {
                if (!empty($image->file_path)) {
                    try {
                        $url = \media_url($image->file_path);
                        if ($url !== asset('images/no-image.png')) {
                            return $url;
                        }
                    } catch (\Exception $e) {
                        // Continue to next
                    }
                }
            }
        }

        // 2. Try hostel's main image
        if (!empty($this->image)) {
            try {
                $url = \media_url($this->image);
                if ($url !== asset('images/no-image.png')) {
                    return $url;
                }
            } catch (\Exception $e) {
                // Fall through
            }
        }

        return $defaultImage;
    }

    /**
     * ✅ FIXED: Get main image URL for gallery background - ALWAYS returns string
     */
    public function getMainImageUrlAttribute(): string
    {
        $defaultImage = asset('images/default-hostel-bg.jpg');

        // 1. Try hostel's main image
        if (!empty($this->image)) {
            try {
                $url = \media_url($this->image);
                if ($url !== asset('images/no-image.png')) {
                    return $url;
                }
            } catch (\Exception $e) {
                // Fall through
            }
        }

        // 2. Try gallery images
        if ($this->galleries()->count() > 0) {
            $galleryImage = $this->galleries()
                ->where('media_type', 'photo')
                ->whereNotNull('file_path')
                ->first();

            if ($galleryImage && !empty($galleryImage->file_path)) {
                try {
                    $url = \media_url($galleryImage->file_path);
                    if ($url !== asset('images/no-image.png')) {
                        return $url;
                    }
                } catch (\Exception $e) {
                    // Fall through
                }
            }
        }

        // 3. Try hostel images
        if ($this->images && $this->images->count() > 0) {
            $image = $this->images->first();
            if ($image && !empty($image->file_path)) {
                try {
                    $url = \media_url($image->file_path);
                    if ($url !== asset('images/no-image.png')) {
                        return $url;
                    }
                } catch (\Exception $e) {
                    // Fall through
                }
            }
        }

        return $defaultImage;
    }

    /**
     * Get available rooms for this hostel - FIXED: Only rooms with available beds
     */
    public function getAvailableRooms()
    {
        return $this->rooms()
            ->where('status', 'available')
            ->where('available_beds', '>', 0)
            ->get();
    }

    /**
     * Get minimum price from available rooms - FIXED: Only consider available rooms
     */
    public function getMinPriceAttribute()
    {
        $availableRooms = $this->getAvailableRooms();

        // ✅ CRITICAL FIX: Filter out rooms with 0 available beds and null prices
        $availableRooms = $availableRooms->filter(function ($room) {
            return $room->available_beds > 0 && $room->price > 0;
        });

        return $availableRooms->isNotEmpty() ? $availableRooms->min('price') : null;
    }

    /**
     * Get maximum price from available rooms - FIXED: Only consider available rooms
     */
    public function getMaxPriceAttribute()
    {
        $availableRooms = $this->getAvailableRooms();

        // ✅ CRITICAL FIX: Filter out rooms with 0 available beds and null prices
        $availableRooms = $availableRooms->filter(function ($room) {
            return $room->available_beds > 0 && $room->price > 0;
        });

        return $availableRooms->isNotEmpty() ? $availableRooms->max('price') : null;
    }

    /**
     * Get formatted price range for display
     */
    public function getFormattedPriceRangeAttribute()
    {
        $min = $this->min_price;
        $max = $this->max_price;

        if ($min && $max) {
            if ($min === $max) {
                return 'रु. ' . number_format($min);
            }
            return 'रु. ' . number_format($min) . ' - ' . number_format($max);
        } elseif ($min) {
            return 'रु. ' . number_format($min);
        }

        return 'मूल्य उपलब्ध छैन';
    }

    /**
     * Get the starting price (minimum price of available rooms)
     */
    public function getStartingPriceAttribute()
    {
        $cheapestRoom = $this->rooms()
            ->where('status', 'available')
            ->where('available_beds', '>', 0)
            ->orderBy('price', 'asc')
            ->first();

        return $cheapestRoom ? $cheapestRoom->price : null;
    }

    /**
     * Get formatted starting price for display
     */
    public function getFormattedStartingPriceAttribute()
    {
        $startingPrice = $this->starting_price;
        return $startingPrice ? 'रु. ' . number_format($startingPrice) : 'मूल्य उपलब्ध छैन';
    }

    /**
     * TEMPORARY: Debug method to check room prices
     */
    public function debugRoomPrices()
    {
        $availableRooms = $this->getAvailableRooms();

        return [
            'hostel' => $this->name,
            'total_rooms' => $this->rooms->count(),
            'available_rooms' => $availableRooms->count(),
            'min_price' => $availableRooms->min('price'),
            'max_price' => $availableRooms->max('price'),
            'all_prices' => $availableRooms->pluck('price')->toArray(),
            'rooms_details' => $availableRooms->map(function ($room) {
                return [
                    'room_number' => $room->room_number,
                    'type' => $room->type,
                    'price' => $room->price,
                    'available_beds' => $room->available_beds,
                    'status' => $room->status
                ];
            })->toArray()
        ];
    }

    // ✅ Check if hostel can be deleted
    public function getCanBeDeletedAttribute()
    {
        return $this->rooms()->count() === 0 && $this->students()->count() === 0;
    }

    // ✅ UPDATED: Use galleries relationship instead of galleryImages
    public function getActiveGalleryImagesCountAttribute()
    {
        return $this->galleries()->where('is_active', true)->count();
    }

    // ✅ UPDATED: Use galleries relationship instead of galleryImages
    public function getFeaturedGalleryImagesAttribute()
    {
        return $this->galleries()->where('is_active', true)->where('is_featured', true)->get();
    }

    // ✅ ADDED: Get active gallery images
    public function getActiveGalleriesAttribute()
    {
        return $this->galleries()->where('is_active', true)->get();
    }

    // ✅ ADDED: Get featured gallery images
    public function getFeaturedGalleriesAttribute()
    {
        return $this->galleries()->where('is_active', true)->where('is_featured', true)->get();
    }

    // ✅ NEW: Check if hostel has any social media links
    public function getHasSocialMediaAttribute()
    {
        return !empty($this->facebook_url) ||
            !empty($this->instagram_url) ||
            !empty($this->twitter_url) ||
            !empty($this->tiktok_url) ||
            !empty($this->whatsapp_number) ||
            !empty($this->youtube_url) ||
            !empty($this->linkedin_url);
    }

    // ✅ NEW: Get social media links as array
    public function getSocialMediaLinksAttribute()
    {
        return [
            'facebook' => $this->facebook_url,
            'instagram' => $this->instagram_url,
            'twitter' => $this->twitter_url,
            'tiktok' => $this->tiktok_url,
            'whatsapp' => $this->whatsapp_number ? 'https://wa.me/' . $this->whatsapp_number : null,
            'youtube' => $this->youtube_url,
            'linkedin' => $this->linkedin_url
        ];
    }

    // ✅ NEW: Get active social media platforms count
    public function getActiveSocialMediaCountAttribute()
    {
        $count = 0;
        if (!empty($this->facebook_url)) $count++;
        if (!empty($this->instagram_url)) $count++;
        if (!empty($this->twitter_url)) $count++;
        if (!empty($this->tiktok_url)) $count++;
        if (!empty($this->whatsapp_number)) $count++;
        if (!empty($this->youtube_url)) $count++;
        if (!empty($this->linkedin_url)) $count++;

        return $count;
    }

    // ✅ NEW: Gallery Integration Methods

    /**
     * Get room galleries (galleries linked to rooms)
     */
    public function getRoomGalleriesAttribute()
    {
        return $this->galleries()->whereNotNull('room_id')->get();
    }

    /**
     * Get public room galleries for display
     */
    public function getPublicRoomGalleriesAttribute()
    {
        return $this->galleries()
            ->whereNotNull('room_id')
            ->where('is_active', true)
            ->with('room')
            ->get();
    }

    /**
     * Get room galleries count
     */
    public function getRoomGalleriesCountAttribute()
    {
        return $this->galleries()->whereNotNull('room_id')->count();
    }

    /**
     * Get public room galleries count
     */
    public function getPublicRoomGalleriesCountAttribute()
    {
        return $this->galleries()
            ->whereNotNull('room_id')
            ->where('is_active', true)
            ->count();
    }

    /**
     * Check if hostel has room galleries
     */
    public function getHasRoomGalleriesAttribute()
    {
        return $this->galleries()->whereNotNull('room_id')->exists();
    }

    /**
     * Get featured room galleries
     */
    public function getFeaturedRoomGalleriesAttribute()
    {
        return $this->galleries()
            ->whereNotNull('room_id')
            ->where('is_active', true)
            ->where('is_featured', true)
            ->with('room')
            ->get();
    }

    /**
     * Sync all room images to galleries for this hostel
     */
    public function syncAllRoomImagesToGallery(): array
    {
        $results = [
            'success' => 0,
            'failed' => 0,
            'total' => 0
        ];

        $rooms = $this->rooms()->whereNotNull('image')->get();

        foreach ($rooms as $room) {
            try {
                // Delete existing gallery entries for this room
                $room->galleries()->delete();

                // Create new gallery entry
                if ($room->image) {
                    $room->syncImageToGallery();
                    $results['success']++;
                }
            } catch (\Exception $e) {
                \Log::error("Failed to sync room {$room->id} to gallery: " . $e->getMessage());
                $results['failed']++;
            }
            $results['total']++;
        }

        return $results;
    }

    /**
     * Get gallery statistics
     */
    public function getGalleryStatisticsAttribute()
    {
        return [
            'total_galleries' => $this->galleries()->count(),
            'active_galleries' => $this->galleries()->where('is_active', true)->count(),
            'featured_galleries' => $this->galleries()->where('is_featured', true)->count(),
            'room_galleries' => $this->room_galleries_count,
            'public_room_galleries' => $this->public_room_galleries_count,
        ];
    }

    /**
     * Get all gallery categories used by this hostel
     */
    public function getGalleryCategoriesAttribute()
    {
        return $this->galleries()
            ->where('is_active', true)
            ->distinct()
            ->pluck('category')
            ->filter()
            ->values();
    }

    /**
     * Get room types that have gallery images
     */
    public function getRoomTypesWithGalleriesAttribute()
    {
        return $this->rooms()
            ->whereHas('galleries', function ($query) {
                $query->where('is_active', true);
            })
            ->distinct()
            ->pluck('type')
            ->map(function ($type) {
                return [
                    'type' => $type,
                    'nepali_type' => (new Room())->getNepaliTypeAttribute($type),
                    'count' => $this->rooms()->where('type', $type)->whereHas('galleries')->count()
                ];
            });
    }


    /**
     * ✅ FIXED: Get thumbnail URL - ALWAYS returns string
     */
    public function getThumbnailUrlAttribute(): string
    {
        $defaultImage = asset('images/hostel-placeholder.jpg');

        if ($this->images && $this->images->count() > 0) {
            $mainImage = $this->images->first();

            if ($mainImage && !empty($mainImage->thumbnail)) {
                try {
                    $url = \media_url($mainImage->thumbnail);
                    if ($url !== asset('images/no-image.png')) {
                        return $url;
                    }
                } catch (\Exception $e) {
                    // Fall through
                }
            }

            if ($mainImage && !empty($mainImage->file_path)) {
                try {
                    $url = \media_url($mainImage->file_path);
                    if ($url !== asset('images/no-image.png')) {
                        return $url;
                    }
                } catch (\Exception $e) {
                    // Fall through
                }
            }
        }

        return $defaultImage;
    }

    /**
     * Check if user can modify this hostel
     */
    public function canBeModifiedBy($user): bool
    {
        return $user->id === $this->owner_id ||
            $user->id === $this->manager_id ||
            ($this->organization && $this->organization->users()->where('user_id', $user->id)->exists());
    }

    /**
     * ✅ STEP 4: Hostel messages relationship
     * यो HostelMessage model सँगको connection हो
     */
    public function messages()
    {
        return $this->hasMany(HostelMessage::class);
    }

    /**
     * ✅ STEP 4: Unread messages relationship
     */
    public function unreadMessages()
    {
        return $this->hasMany(HostelMessage::class)->where('status', 'unread');
    }

    /**
     * ✅ STEP 4: Get unread messages count (सजिलो पहुँचको लागि)
     */
    public function getUnreadMessagesCountAttribute()
    {
        // Option 1: Relationship बाट count निकाल्ने
        if ($this->relationLoaded('messages')) {
            return $this->messages->where('status', 'unread')->count();
        }

        // Option 2: Database query गर्ने
        return $this->messages()->where('status', 'unread')->count();
    }

    /**
     * ✅ STEP 4: Get latest messages (dashboard को लागि)
     */
    public function getLatestMessagesAttribute()
    {
        return $this->messages()
            ->latest()
            ->take(5)
            ->get();
    }

    /**
     * ✅ STEP 4: Get today's messages
     */
    public function getTodaysMessagesAttribute()
    {
        return $this->messages()
            ->whereDate('created_at', today())
            ->get();
    }

    /**
     * ✅ STEP 4: Check if has new messages
     */
    public function getHasNewMessagesAttribute()
    {
        return $this->unreadMessages()->exists();
    }
}
