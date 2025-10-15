<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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

    // Document types in Nepali
    public const DOCUMENT_TYPES = [
        'id_card' => 'पहिचानपत्र',
        'academic' => 'शैक्षिक कागजात',
        'medical' => 'चिकित्साका कागजात',
        'financial' => 'आर्थिक कागजात',
        'contract' => 'सम्झौता कागजात',
        'other' => 'अन्य कागजात'
    ];

    // Multi-tenant scope
    public function scopeForOrganization($query, $organizationId)
    {
        return $query->where('organization_id', $organizationId);
    }

    // Relationships
    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function hostel()
    {
        return $this->belongsTo(Hostel::class);
    }

    public function uploader()
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
}
