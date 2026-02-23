<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Enums\PriorityEnum;
use App\Enums\MessageCategoryEnum;

class Message extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'thread_id',
        'sender_id',
        'body',
        'category',
        'priority',
        'read_at',
    ];

    protected $casts = [
        'read_at' => 'datetime',
        'priority' => PriorityEnum::class,
        'category' => MessageCategoryEnum::class,
    ];

    public function thread()
    {
        return $this->belongsTo(MessageThread::class, 'thread_id');
    }

    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }
}
