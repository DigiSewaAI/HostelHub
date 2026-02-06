<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RoomIssue extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'hostel_id',
        'room_id',
        'issue_type',
        'description',
        'priority',
        'status',
        'image_url',
        'resolution_notes',
        'assigned_to',
        'resolved_at',
        'resolved_by'
    ];

    protected $casts = [
        'resolved_at' => 'datetime'
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function hostel()
    {
        return $this->belongsTo(Hostel::class);
    }

    public function room()
    {
        return $this->belongsTo(Room::class);
    }

    public function resolver()
    {
        return $this->belongsTo(User::class, 'resolved_by');
    }

    public function getIssueTypeNepaliAttribute()
    {
        $types = [
            'plumbing' => 'प्लम्बिङ समस्या',
            'electrical' => 'बिद्युत समस्या',
            'furniture' => 'फर्निचर समस्या',
            'cleaning' => 'सफाइ समस्या',
            'security' => 'सुरक्षा समस्या',
            'noise' => 'शोर समस्या',
            'other' => 'अन्य समस्या'
        ];

        return $types[$this->issue_type] ?? $this->issue_type;
    }

    public function getPriorityNepaliAttribute()
    {
        $priorities = [
            'high' => 'उच्च',
            'medium' => 'मध्यम',
            'low' => 'कम'
        ];

        return $priorities[$this->priority] ?? $this->priority;
    }

    public function getStatusNepaliAttribute()
    {
        $statuses = [
            'pending' => 'पेन्डिङ',
            'processing' => 'प्रक्रियामा',
            'resolved' => 'समाधान भएको',
            'closed' => 'बन्द गरिएको'
        ];

        return $statuses[$this->status] ?? $this->status;
    }
}