<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OnboardingProgress extends Model
{
    use HasFactory;

    const TOTAL_STEPS = 5;

    protected $fillable = [
        'organization_id',
        'current_step',
        'completed'
    ];

    protected $casts = [
        'current_step' => 'integer',
        'completed' => 'array'
    ];

    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class, 'organization_id');
    }

    public function isCompleted(): bool
    {
        return $this->current_step > self::TOTAL_STEPS;
    }

    public function markStepComplete(int $step): void
    {
        $completed = $this->completed;
        $completed["step{$step}"] = true;

        $this->update([
            'completed' => $completed,
            'current_step' => $step < self::TOTAL_STEPS ? $step + 1 : self::TOTAL_STEPS + 1
        ]);
    }

    public function skipStep(int $step): void
    {
        $completed = $this->completed;
        $completed["step{$step}"] = 'skipped';

        $this->update([
            'completed' => $completed,
            'current_step' => $step < self::TOTAL_STEPS ? $step + 1 : self::TOTAL_STEPS + 1
        ]);
    }
}
