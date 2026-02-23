<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MessageThreadParticipant extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'message_thread_participants';

    protected $fillable = [
        'thread_id',
        'user_id',
        'last_read_at',
        'is_archived',
    ];

    protected $casts = [
        'last_read_at' => 'datetime',
        'is_archived' => 'boolean',
    ];

    public function thread()
    {
        return $this->belongsTo(MessageThread::class, 'thread_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
