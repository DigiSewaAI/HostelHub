<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;

class College extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'location',
        'contact_email'
    ];

    protected $table = 'colleges';

    /**
     * Get all students belonging to this college
     */
    public function students(): HasMany
    {
        return $this->hasMany(Student::class);
    }
}
