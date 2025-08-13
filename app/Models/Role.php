<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Role extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    // रोलहरूको सम्बन्ध
    const ADMIN = 'admin';
    const HOSTEL_MANAGER = 'hostel_manager';
    const STUDENT = 'student';
}
