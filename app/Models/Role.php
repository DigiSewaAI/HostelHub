<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
// use Spatie\Permission\Traits\HasRoles; // अनकमेन्ट गर्नुहोस् यदि Spatie प्रयोग गर्दै हुनुहुन्छ

class Role extends Model
{
    use HasFactory;
    // use HasRoles; // अनकमेन्ट गर्नुहोस् यदि Spatie प्रयोग गर्दै हुनुहुन्छ

    protected $fillable = ['name'];
}
