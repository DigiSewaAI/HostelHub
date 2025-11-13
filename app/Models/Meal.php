<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Meal extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'hostel_id',
        'meal_type',
        'meal_date',
        'status',
        'remarks'
    ];

    protected $casts = [
        'meal_date' => 'date'
    ];

    /**
     * Validation rules for Meal model
     */
    public static function validationRules($id = null): array
    {
        return [
            'student_id' => 'required|exists:students,id',
            'hostel_id' => 'required|exists:hostels,id',
            'meal_type' => 'required|in:breakfast,lunch,dinner,snack',
            'meal_date' => 'required|date',
            'status' => 'required|in:taken,missed,cancelled',
            'remarks' => 'nullable|string|max:500'
        ];
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function hostel(): BelongsTo
    {
        return $this->belongsTo(Hostel::class);
    }

    /**
     * Scope for student meals
     */
    public function scopeForStudent($query, $studentId)
    {
        return $query->where('student_id', $studentId);
    }

    /**
     * Scope for hostel meals
     */
    public function scopeForHostel($query, $hostelId)
    {
        return $query->where('hostel_id', $hostelId);
    }

    /**
     * Scope for date range
     */
    public function scopeDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('meal_date', [$startDate, $endDate]);
    }

    /**
     * Scope for meal type
     */
    public function scopeMealType($query, $mealType)
    {
        return $query->where('meal_type', $mealType);
    }

    /**
     * Check if meal can be modified
     */
    public function canBeModifiedBy($user): bool
    {
        // Only allow modification if user owns the hostel or is the student
        return $this->hostel->canBeModifiedBy($user) ||
            $this->student->user_id === $user->id;
    }
}
