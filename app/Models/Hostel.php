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
        'organization_id'
    ];

    protected $casts = [
        'facilities' => 'array'
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

    // ✅ Scope for active hostels
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
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
}
