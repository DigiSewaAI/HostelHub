<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class College extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'location',
        'contact_email'
    ];

    protected $table = 'colleges'; // convention: 'colleges'
}
