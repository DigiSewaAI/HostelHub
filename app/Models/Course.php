<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Course extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'duration',
        'description'
    ];

    protected $table = 'courses';

    /**
     * Get all students enrolled in this course
     */
    public function students(): HasMany
    {
        return $this->hasMany(Student::class);
    }
}
