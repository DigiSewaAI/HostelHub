<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\BelongsToTenant;

class BroadcastMessage extends Model
{
    use HasFactory, BelongsToTenant;

    protected $fillable = [
        'tenant_id',
        'sender_id',
        'subject',
        'body',
        'status',
        'moderated_at',
        'moderated_by',
        'moderation_notes',
        'sent_at',
    ];

    protected $casts = [
        'moderated_at' => 'datetime',
        'sent_at' => 'datetime',
    ];

    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    public function moderator()
    {
        return $this->belongsTo(User::class, 'moderated_by');
    }
    protected static function booted()
    {
        static::addGlobalScope(new TenantScope);
    }
}
