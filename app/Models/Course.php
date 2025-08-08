<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Course extends Model
{
    use SoftDeletes; // यदि तपाईंले migration मा softDeletes() गर्नुभयो भने

    protected $fillable = [
        'name',
        'duration',
        'description'
    ];

    // यदि तपाईंको courses टेबलको नाम फरक छ भने (convention: 'courses')
    protected $table = 'courses';
}
