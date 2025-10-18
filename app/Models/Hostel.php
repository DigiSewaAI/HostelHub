<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
        'theme_color'
    ];

    protected $casts = [
        'facilities' => 'array',
        'is_published' => 'boolean',
        'published_at' => 'datetime'
    ];

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

    // ✅ ADDED: Gallery images relationship
    public function galleryImages(): HasMany
    {
        return $this->hasMany(Gallery::class);
    }

    // ✅ Organization सँगको relationship थप्नुहोस्
    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class, 'organization_id');
    }

    // ✅ Students सँगको relationship थप्नुहोस्
    public function students(): HasMany
    {
        return $this->hasMany(Student::class);
    }

    // ✅ Meal menus सँगको relationship थप्नुहोस्
    public function mealMenus(): HasMany
    {
        return $this->hasMany(MealMenu::class);
    }

    // ✅ ADDED: Reviews relationship (only if Review model exists)
    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    // ✅ ADDED: Approved reviews for the hostel
    public function approvedReviews()
    {
        return $this->reviews()->where('status', 'approved');
    }

    // ✅ ADDED: Featured reviews for the hostel
    public function featuredReviews()
    {
        return $this->approvedReviews()->where('featured', true);
    }

    // ✅ ADDED: Payments relationship
    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
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

    // ✅ ADDED: Scope published hostels
    public function scopePublished($query)
    {
        return $query->where('is_published', true)
            ->whereNotNull('published_at');
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

    // ✅ FIXED: Check if hostel is fully published (both flag set and published date exists)
    public function getIsFullyPublishedAttribute()
    {
        return $this->is_published && $this->published_at;
    }

    // ✅ ADDED: Get public URL for the hostel
    public function getPublicUrlAttribute()
    {
        if (!$this->is_published) {
            return null;
        }
        return route('hostels.show', $this->slug);
    }

    // ✅ ADDED: Get logo URL
    public function getLogoUrlAttribute()
    {
        if ($this->logo_path) {
            return asset('storage/' . $this->logo_path);
        }
        return asset('images/default-hostel.png');
    }

    // ✅ FIX: Add method to update room counts from relationships
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

    // ✅ Check if hostel can be deleted (no rooms or students)
    public function getCanBeDeletedAttribute()
    {
        return $this->rooms()->count() === 0 && $this->students()->count() === 0;
    }

    // ✅ ADDED: Get active gallery images count
    public function getActiveGalleryImagesCountAttribute()
    {
        return $this->galleryImages()->where('is_active', true)->count();
    }

    // ✅ ADDED: Get featured gallery images
    public function getFeaturedGalleryImagesAttribute()
    {
        return $this->galleryImages()->where('is_active', true)->where('is_featured', true)->get();
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
    }
}
