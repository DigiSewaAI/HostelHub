<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Subscription extends Model
{
    use HasFactory;

    protected $fillable = [
        'org_id',
        'plan_id',
        'status',
        'trial_ends_at',
        'renews_at',
        'notes'
    ];

    protected $casts = [
        'trial_ends_at' => 'datetime',
        'renews_at' => 'datetime'
    ];

    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class, 'org_id');
    }

    public function plan(): BelongsTo
    {
        return $this->belongsTo(Plan::class);
    }

    public function isActive(): bool
    {
        return $this->status === 'active' || ($this->status === 'trial' && now()->lessThan($this->trial_ends_at));
    }
}
