<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class Review extends Model
{
    use SoftDeletes;

    protected $table = 'reviews';

    // Constants for type
    const TYPE_TESTIMONIAL = 'testimonial';
    const TYPE_REVIEW = 'review';
    const TYPE_FEEDBACK = 'feedback';

    // Constants for status
    const STATUS_PENDING = 'pending';
    const STATUS_APPROVED = 'approved';
    const STATUS_REJECTED = 'rejected';
    const STATUS_ACTIVE = 'active';
    const STATUS_INACTIVE = 'inactive';

    protected $fillable = [
        'name',
        'position',
        'content',
        'initials',
        'image',
        'type',
        'status',
        'rating',
        'user_id',
        'hostel_id',
        'student_id',
        'comment',
        'reply',
        'reply_date'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'rating' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
        'reply_date' => 'datetime',
    ];

    /**
     * Set default attribute values
     */
    protected $attributes = [
        'status' => self::STATUS_PENDING,
        'type' => self::TYPE_REVIEW,
        'rating' => 0,
        'name' => '',
        'position' => '',
    ];

    /**
     * Boot method for model events
     */
    protected static function boot()
    {
        parent::boot();

        // Set default name and position before creating
        static::creating(function ($review) {
            if (empty($review->name)) {
                if ($review->student && $review->student->user) {
                    $review->name = $review->student->user->name;
                } elseif ($review->user) {
                    $review->name = $review->user->name;
                } else {
                    $review->name = 'Anonymous';
                }
            }

            if (empty($review->position)) {
                $review->position = 'Student';
            }
        });

        // Delete associated image when review is deleted
        static::deleting(function ($review) {
            if ($review->image && Storage::disk('public')->exists($review->image)) {
                Storage::disk('public')->delete($review->image);
            }
        });
    }

    /**
     * Get the route key for the model.
     */
    public function getRouteKeyName(): string
    {
        return 'id';
    }

    /**
     * Get all available types with their labels.
     *
     * @return array
     */
    public static function getTypes(): array
    {
        return [
            self::TYPE_TESTIMONIAL => 'प्रशंसापत्र',
            self::TYPE_REVIEW => 'समीक्षा',
            self::TYPE_FEEDBACK => 'प्रतिक्रिया',
        ];
    }

    /**
     * Get all available statuses with their labels.
     *
     * @return array
     */
    public static function getStatuses(): array
    {
        return [
            self::STATUS_PENDING => 'पेन्डिंग',
            self::STATUS_APPROVED => 'स्वीकृत',
            self::STATUS_REJECTED => 'अस्वीकृत',
            self::STATUS_ACTIVE => 'सक्रिय',
            self::STATUS_INACTIVE => 'निष्क्रिय',
        ];
    }

    /**
     * Get the type label for the current review.
     *
     * @return string
     */
    public function getTypeLabel(): string
    {
        return self::getTypes()[$this->type] ?? 'अज्ञात';
    }

    /**
     * Get the status label for the current review.
     *
     * @return string
     */
    public function getStatusLabel(): string
    {
        return self::getStatuses()[$this->status] ?? 'अज्ञात';
    }

    /**
     * Get the image URL with fallback to initials or default avatar.
     *
     * @return string
     */
    public function getImageUrl(): string
    {
        if ($this->image && Storage::disk('public')->exists($this->image)) {
            return Storage::disk('public')->url($this->image);
        }

        if ($this->initials) {
            // Generate a colored background based on initials
            $colors = ['#001F5B', '#0a3a6a', '#1e4a76', '#2a5c8a', '#3b6ea0'];
            $colorIndex = crc32($this->initials) % count($colors);
            $backgroundColor = $colors[$colorIndex];

            return "data:image/svg+xml;utf8,<svg xmlns='http://www.w3.org/2000/svg' width='100' height='100' viewBox='0 0 100 100'><rect width='100' height='100' fill='{$backgroundColor}'/><text x='50' y='50' font-size='40' text-anchor='middle' dy='.3em' fill='white'>{$this->initials}</text></svg>";
        }

        // Default avatar
        return asset('images/default-avatar.png');
    }

    /**
     * Get the student that owns the review.
     */
    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    /**
     * Get the hostel that owns the review.
     */
    public function hostel()
    {
        return $this->belongsTo(Hostel::class);
    }

    /**
     * Get the user that owns the review.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope a query to only include active reviews.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActive($query)
    {
        return $query->where('status', self::STATUS_ACTIVE);
    }

    /**
     * Scope a query to only include approved reviews.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeApproved($query)
    {
        return $query->where('status', self::STATUS_APPROVED);
    }

    /**
     * Scope a query to only include pending reviews.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopePending($query)
    {
        return $query->where('status', self::STATUS_PENDING);
    }

    /**
     * Scope a query to only include testimonials.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeTestimonials($query)
    {
        return $query->where('type', self::TYPE_TESTIMONIAL);
    }

    /**
     * Scope a query to only include reviews of a specific type.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  string  $type
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOfType($query, $type)
    {
        return $query->where('type', $type);
    }

    /**
     * Scope a query to only include student reviews.
     */
    public function scopeStudentReviews($query)
    {
        return $query->where('type', self::TYPE_REVIEW);
    }

    /**
     * Check if the review has a reply.
     *
     * @return bool
     */
    public function hasReply(): bool
    {
        return !empty($this->reply);
    }
}
