<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class PaymentMethod extends Model
{
    use HasFactory, SoftDeletes;

    // ✅ TYPE CONSTANTS
    const TYPE_BANK = 'bank';
    const TYPE_ESEWA = 'esewa';
    const TYPE_KHALTI = 'khalti';
    const TYPE_FONEPAY = 'fonepay';
    const TYPE_IMEPAY = 'imepay';
    const TYPE_CONNECTIPS = 'connectips';
    const TYPE_CASH = 'cash';
    const TYPE_OTHER = 'other';

    protected $fillable = [
        'hostel_id',
        'type',
        'title',
        'account_name',
        'account_number',
        'bank_name',
        'branch_name',
        'mobile_number',
        'qr_code_path',
        'wallet_id',
        'is_default',
        'is_active',
        'additional_info',
        'order'
    ];

    protected $casts = [
        'is_default' => 'boolean',
        'is_active' => 'boolean',
        'additional_info' => 'array'
    ];

    protected $appends = [
        'type_text',
        'display_info',
        'qr_code_url',
        'masked_account_number', // Added for dynamic masking
        'formatted_updated_at'   // Added for last updated info
    ];

    /**
     * Validation rules for PaymentMethod model
     */
    public static function validationRules($id = null): array
    {
        return [
            'hostel_id' => 'required|exists:hostels,id',
            'type' => 'required|in:bank,esewa,khalti,fonepay,imepay,connectips,cash,other',
            'title' => 'required|string|max:255',
            'account_name' => 'nullable|required_if:type,bank,other|string|max:255',
            'account_number' => 'nullable|required_if:type,bank|string|max:100',
            'bank_name' => 'nullable|required_if:type,bank|string|max:255',
            'branch_name' => 'nullable|string|max:255',
            'mobile_number' => 'nullable|required_if:type,esewa,khalti,fonepay,imepay|string|max:20',
            'qr_code' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'wallet_id' => 'nullable|string|max:100',
            'is_default' => 'boolean',
            'is_active' => 'boolean',
            'additional_info' => 'nullable|array',
            'order' => 'nullable|integer|min:1' // Changed from min:0 to min:1
        ];
    }

    /**
     * Boot method for model events
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($paymentMethod) {
            // Auto-assign next order when creating
            if (empty($paymentMethod->order)) {
                $maxOrder = self::where('hostel_id', $paymentMethod->hostel_id)
                    ->max('order');
                $paymentMethod->order = ($maxOrder ?: 0) + 1;
            }

            // Ensure unique order per hostel
            $existingOrder = self::where('hostel_id', $paymentMethod->hostel_id)
                ->where('order', $paymentMethod->order)
                ->exists();

            if ($existingOrder) {
                // Shift all existing methods with order >= new order
                self::where('hostel_id', $paymentMethod->hostel_id)
                    ->where('order', '>=', $paymentMethod->order)
                    ->increment('order');
            }
        });

        // Ensure only one default payment method per hostel
        static::saving(function ($paymentMethod) {
            if ($paymentMethod->is_default) {
                self::where('hostel_id', $paymentMethod->hostel_id)
                    ->where('id', '!=', $paymentMethod->id)
                    ->update(['is_default' => false]);
            }
        });

        // Prevent deletion of default method without replacement
        static::deleting(function ($paymentMethod) {
            if ($paymentMethod->is_default) {
                // Find another active method to set as default
                $replacement = self::where('hostel_id', $paymentMethod->hostel_id)
                    ->where('id', '!=', $paymentMethod->id)
                    ->where('is_active', true)
                    ->first();

                if ($replacement) {
                    $replacement->update(['is_default' => true]);
                }
            }

            // Reorder remaining methods after deletion
            $remainingMethods = self::where('hostel_id', $paymentMethod->hostel_id)
                ->where('id', '!=', $paymentMethod->id)
                ->orderBy('order')
                ->get();

            foreach ($remainingMethods as $index => $method) {
                $method->update(['order' => $index + 1]);
            }
        });
    }

    /**
     * Relationship with Hostel
     */
    public function hostel(): BelongsTo
    {
        return $this->belongsTo(Hostel::class);
    }

    /**
     * Relationship with Payments
     */
    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    /**
     * Scope for active payment methods
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for specific type
     */
    public function scopeOfType($query, $type)
    {
        return $query->where('type', $type);
    }

    /**
     * Scope for hostel
     */
    public function scopeForHostel($query, $hostelId)
    {
        return $query->where('hostel_id', $hostelId);
    }

    /**
     * Scope for default payment method
     */
    public function scopeDefault($query)
    {
        return $query->where('is_default', true);
    }

    /**
     * Get type in text format (Nepali)
     */
    public function getTypeTextAttribute(): string
    {
        $types = [
            self::TYPE_BANK => 'बैंक खाता',
            self::TYPE_ESEWA => 'ईसेवा',
            self::TYPE_KHALTI => 'खल्ती',
            self::TYPE_FONEPAY => 'फोनपे',
            self::TYPE_IMEPAY => 'आईमेपे',
            self::TYPE_CONNECTIPS => 'कनेक्टआइपीएस',
            self::TYPE_CASH => 'नगद',
            self::TYPE_OTHER => 'अन्य'
        ];

        return $types[$this->type] ?? 'अन्य';
    }

    /**
     * Get display information for payment method
     */
    public function getDisplayInfoAttribute(): array
    {
        $info = [
            'type' => $this->type,
            'type_text' => $this->type_text,
            'title' => $this->title,
            'details' => []
        ];

        switch ($this->type) {
            case self::TYPE_BANK:
                $info['details'] = [
                    'बैंक' => $this->bank_name,
                    'खाता नम्बर' => $this->masked_account_number,
                    'खाता धनी' => $this->account_name,
                    'शाखा' => $this->branch_name
                ];
                break;

            case self::TYPE_ESEWA:
            case self::TYPE_KHALTI:
            case self::TYPE_FONEPAY:
            case self::TYPE_IMEPAY:
                $info['details'] = [
                    'वालेट' => $this->type_text,
                    'मोबाइल नम्बर' => $this->mobile_number,
                    'आईडी' => $this->wallet_id,
                    'नाम' => $this->account_name
                ];
                break;

            case self::TYPE_CASH:
                $info['details'] = [
                    'विधि' => 'नगद भुक्तानी',
                    'स्थान' => $this->additional_info['location'] ?? 'होस्टेल कार्यालय'
                ];
                break;

            default:
                $info['details'] = [
                    'विवरण' => $this->additional_info['description'] ?? 'कृपया सम्पर्क गर्नुहोस्'
                ];
        }

        // Filter out empty values
        $info['details'] = array_filter($info['details'], function ($value) {
            return !empty($value);
        });

        return $info;
    }

    /**
     * Get QR code URL if exists
     */
    public function getQrCodeUrlAttribute(): ?string
    {
        if (!$this->qr_code_path) {
            return null;
        }

        try {
            return \media_url($this->qr_code_path);
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * Check if payment method can be deleted
     */
    public function getCanBeDeletedAttribute(): bool
    {
        // Can't delete if there are payments using this method
        if ($this->payments()->count() > 0) {
            return false;
        }

        // Can't delete the only active method
        $activeMethodsCount = self::where('hostel_id', $this->hostel_id)
            ->where('is_active', true)
            ->where('id', '!=', $this->id)
            ->count();

        if ($activeMethodsCount === 0 && $this->is_active) {
            return false;
        }

        return true;
    }

    /**
     * Get formatted account number (masked for display)
     */
    public function getMaskedAccountNumberAttribute(): string
    {
        if (!$this->account_number) {
            return 'N/A';
        }

        $cleanNumber = preg_replace('/\s+/', '', $this->account_number);
        $length = strlen($cleanNumber);

        if ($length <= 4) {
            return $cleanNumber;
        }

        $lastFour = substr($cleanNumber, -4);

        // ✅ FIXED: Show only 4 stars followed by last 4 digits
        return '**** ' . $lastFour;
    }

    /**
     * Get formatted updated_at for display
     */
    public function getFormattedUpdatedAtAttribute(): string
    {
        if (!$this->updated_at) {
            return 'नभएको';
        }

        return $this->updated_at->format('Y-m-d H:i');
    }

    /**
     * Activate this payment method
     */
    public function activate(): bool
    {
        return $this->update(['is_active' => true]);
    }

    /**
     * Deactivate this payment method
     */
    public function deactivate(): bool
    {
        // Can't deactivate if it's the only active method
        $otherActiveMethods = self::where('hostel_id', $this->hostel_id)
            ->where('id', '!=', $this->id)
            ->where('is_active', true)
            ->count();

        if ($otherActiveMethods === 0) {
            throw new \Exception('यो एक मात्र सक्रिय भुक्तानी विधि हो। अर्को विधि सक्रिय गर्नुहोस् पहिले।');
        }

        // If deactivating default, set another active method as default
        if ($this->is_default) {
            $newDefault = self::where('hostel_id', $this->hostel_id)
                ->where('id', '!=', $this->id)
                ->where('is_active', true)
                ->first();

            if ($newDefault) {
                $newDefault->update(['is_default' => true]);
            }
        }

        return $this->update(['is_active' => false]);
    }

    /**
     * Mark as default payment method
     */
    public function markAsDefault(): bool
    {
        return $this->update(['is_default' => true]);
    }

    /**
     * Get payment instructions for display
     */
    public function getInstructionsAttribute(): array
    {
        $instructions = [];

        switch ($this->type) {
            case self::TYPE_BANK:
                $instructions = [
                    '१. बैंकमा जानुहोस् वा मोबाइल बैंकिङ प्रयोग गर्नुहोस्',
                    '२. खाता नम्बर र बैंक विवरण प्रयोग गर्नुहोस्',
                    '३. भुक्तानी रसिद होस्टेल कार्यालयमा पेश गर्नुहोस्'
                ];
                break;

            case self::TYPE_ESEWA:
                $instructions = [
                    '१. ईसेवा ऐप खोल्नुहोस्',
                    '२. मोबाइल नम्बर वा आईडीमा भुक्तानी गर्नुहोस्',
                    '३. भुक्तानी रसिद क्याप्चर गर्नुहोस्'
                ];
                break;

            case self::TYPE_CASH:
                $instructions = [
                    '१. होस्टेल कार्यालयमा नगद भुक्तानी गर्नुहोस्',
                    '२. रसिद लिन नबिर्सनुहोस्',
                    '३. रसिद सुरक्षित राख्नुहोस्'
                ];
                break;

            default:
                $instructions = [
                    'कृपया होस्टेल कार्यालयसँग सम्पर्क गर्नुहोस्'
                ];
        }

        if ($this->additional_info && isset($this->additional_info['instructions'])) {
            $customInstructions = is_array($this->additional_info['instructions'])
                ? $this->additional_info['instructions']
                : [$this->additional_info['instructions']];
            $instructions = array_merge($instructions, $customInstructions);
        }

        return $instructions;
    }

    /**
     * Validate if this payment method can be set as default
     */
    public function canBeSetAsDefault(): bool
    {
        return $this->is_active;
    }

    /**
     * Reorder payment methods for a hostel
     */
    public static function reorderForHostel($hostelId): void
    {
        $methods = self::where('hostel_id', $hostelId)
            ->orderBy('order')
            ->orderBy('created_at')
            ->get();

        foreach ($methods as $index => $method) {
            $method->update(['order' => $index + 1]);
        }
    }
}
