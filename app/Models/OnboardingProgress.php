<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OnboardingProgress extends Model
{
    use HasFactory;

    protected $fillable = [
        'org_id',
        'current_step',
        'completed'
    ];

    protected $casts = [
        'current_step' => 'integer',
        'completed' => 'array'
    ];

    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class, 'org_id');
    }

    public function isCompleted(): bool
    {
        return $this->current_step > 5;
    }

    public function markStepComplete(int $step): void
    {
        $completed = $this->completed;
        $completed["step{$step}"] = true;

        $this->update([
            'completed' => $completed,
            'current_step' => $step < 5 ? $step + 1 : 6
        ]);
    }

    public function skipStep(int $step): void
    {
        $completed = $this->completed;
        $completed["step{$step}"] = 'skipped';

        $this->update([
            'completed' => $completed,
            'current_step' => $step < 5 ? $step + 1 : 6
        ]);
    }
}
