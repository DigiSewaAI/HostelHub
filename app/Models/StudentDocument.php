<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StudentDocument extends Model
{
    use HasFactory;

    protected $fillable = [
        'organization_id',
        'student_id',
        'hostel_id',
        'uploaded_by',
        'document_type',
        'original_name',
        'stored_path',
        'file_size',
        'mime_type',
        'description',
        'expiry_date',
        'is_verified'
    ];

    protected $casts = [
        'expiry_date' => 'date',
        'file_size' => 'integer',
        'is_verified' => 'boolean'
    ];

    // Document types in Nepali
    public const DOCUMENT_TYPES = [
        'id_card' => 'पहिचानपत्र',
        'academic' => 'शैक्षिक कागजात',
        'medical' => 'चिकित्साका कागजात',
        'financial' => 'आर्थिक कागजात',
        'contract' => 'सम्झौता कागजात',
        'other' => 'अन्य कागजात'
    ];

    /**
     * Validation rules for StudentDocument model
     */
    public static function validationRules($id = null): array
    {
        return [
            'organization_id' => 'required|exists:organizations,id',
            'student_id' => 'required|exists:students,id',
            'hostel_id' => 'nullable|exists:hostels,id',
            'uploaded_by' => 'required|exists:users,id',
            'document_type' => 'required|in:id_card,academic,medical,financial,contract,other',
            'original_name' => 'required|string|max:255',
            'stored_path' => 'required|string|max:500',
            'file_size' => 'required|integer|min:0',
            'mime_type' => 'required|string|max:100',
            'description' => 'nullable|string|max:500',
            'expiry_date' => 'nullable|date|after:today',
            'is_verified' => 'boolean'
        ];
    }

    // Multi-tenant scope
    public function scopeForOrganization($query, $organizationId)
    {
        return $query->where('organization_id', $organizationId);
    }

    // Scope for student documents
    public function scopeForStudent($query, $studentId)
    {
        return $query->where('student_id', $studentId);
    }

    // Scope for hostel documents
    public function scopeForHostel($query, $hostelId)
    {
        return $query->where('hostel_id', $hostelId);
    }

    // Scope for verified documents
    public function scopeVerified($query)
    {
        return $query->where('is_verified', true);
    }

    // Scope for unverified documents
    public function scopeUnverified($query)
    {
        return $query->where('is_verified', false);
    }

    // Scope for expired documents
    public function scopeExpired($query)
    {
        return $query->where('expiry_date', '<', now());
    }

    // Scope for user access control
    public function scopeForUser($query, $userId)
    {
        return $query->whereHas('student', function ($q) use ($userId) {
            $q->where('user_id', $userId);
        })->orWhereHas('organization', function ($q) use ($userId) {
            $q->whereHas('users', function ($q2) use ($userId) {
                $q2->where('user_id', $userId);
            });
        })->orWhereHas('hostel', function ($q) use ($userId) {
            $q->where('owner_id', $userId)
                ->orWhere('manager_id', $userId);
        });
    }

    // Relationships
    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function hostel(): BelongsTo
    {
        return $this->belongsTo(Hostel::class);
    }

    public function uploader(): BelongsTo
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    // Helper method to get document type in Nepali
    public function getDocumentTypeNepaliAttribute()
    {
        return self::DOCUMENT_TYPES[$this->document_type] ?? 'अन्य कागजात';
    }

    // Check if document is expired
    public function getIsExpiredAttribute()
    {
        if (!$this->expiry_date) {
            return false;
        }
        return $this->expiry_date->isPast();
    }

    /**
     * Get document URL
     */
    public function getDocumentUrlAttribute(): string
    {
        return $this->stored_path ? asset('storage/' . $this->stored_path) : '';
    }

    /**
     * Get file size in human readable format
     */
    public function getFileSizeFormattedAttribute(): string
    {
        if ($this->file_size >= 1048576) {
            return round($this->file_size / 1048576, 2) . ' MB';
        } elseif ($this->file_size >= 1024) {
            return round($this->file_size / 1024, 2) . ' KB';
        } else {
            return $this->file_size . ' bytes';
        }
    }

    /**
     * Check if user can modify this document
     */
    public function canBeModifiedBy($user): bool
    {
        return $this->uploaded_by === $user->id ||
            $this->organization->canBeModifiedBy($user) ||
            $this->hostel->canBeModifiedBy($user) ||
            ($this->student->user_id && $this->student->user_id === $user->id);
    }

    /**
     * Verify document
     */
    public function verify(): bool
    {
        return $this->update(['is_verified' => true]);
    }

    /**
     * Unverify document
     */
    public function unverify(): bool
    {
        return $this->update(['is_verified' => false]);
    }
}
