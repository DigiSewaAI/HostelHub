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

    /**
     * Validation rules for OnboardingProgress model
     */
    public static function validationRules($id = null): array
    {
        return [
            'organization_id' => 'required|exists:organizations,id',
            'current_step' => 'required|integer|min:1|max:' . (self::TOTAL_STEPS + 1),
            'completed' => 'nullable|array'
        ];
    }

    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class, 'organization_id');
    }

    /**
     * Scope for organization onboarding progress
     */
    public function scopeForOrganization($query, $organizationId)
    {
        return $query->where('organization_id', $organizationId);
    }

    /**
     * Scope for incomplete onboarding
     */
    public function scopeIncomplete($query)
    {
        return $query->where('current_step', '<=', self::TOTAL_STEPS);
    }

    /**
     * Scope for completed onboarding
     */
    public function scopeCompleted($query)
    {
        return $query->where('current_step', '>', self::TOTAL_STEPS);
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

    /**
     * Get progress percentage
     */
    public function getProgressPercentageAttribute(): int
    {
        return (int) (($this->current_step - 1) / self::TOTAL_STEPS * 100);
    }

    /**
     * Check if step is completed
     */
    public function isStepCompleted($step): bool
    {
        return isset($this->completed["step{$step}"]) && $this->completed["step{$step}"] === true;
    }

    /**
     * Check if step is skipped
     */
    public function isStepSkipped($step): bool
    {
        return isset($this->completed["step{$step}"]) && $this->completed["step{$step}"] === 'skipped';
    }

    /**
     * Check if user can modify this onboarding progress
     */
    public function canBeModifiedBy($user): bool
    {
        return $this->organization && $this->organization->users()->where('user_id', $user->id)->exists();
    }
}
