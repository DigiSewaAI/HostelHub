<?php

namespace App\Services;

use App\Models\Organization;
use Illuminate\Support\Facades\DB;

class PlanLimitService
{
    /**
     * Check if organization can add more students
     */
    public function canAddStudent(Organization $organization): bool
    {
        // Check if organization has active subscription and plan
        if (!$organization->subscription || !$organization->subscription->plan) {
            return false;
        }

        $currentStudents = DB::table('students')
            ->where('organization_id', $organization->id)
            ->count();

        $maxStudents = $organization->subscription->plan->max_students;

        // Enterprise plan has unlimited students
        if ($organization->subscription->plan->slug === 'enterprise') {
            return true;
        }

        return $currentStudents < $maxStudents;
    }

    /**
     * Check if organization can add more hostels
     */
    public function canAddHostel(Organization $organization): bool
    {
        // Check if organization has active subscription and plan
        if (!$organization->subscription || !$organization->subscription->plan) {
            return false;
        }

        $currentHostels = DB::table('hostels')
            ->where('organization_id', $organization->id)
            ->count();

        $maxHostels = $organization->subscription->plan->max_hostels;

        // Enterprise plan has unlimited hostels
        if ($organization->subscription->plan->slug === 'enterprise') {
            return true;
        }

        return $currentHostels < $maxHostels;
    }

    /**
     * Check if organization can add more rooms
     */
    public function canAddRoom(Organization $organization): bool
    {
        // Check if organization has active subscription and plan
        if (!$organization->subscription || !$organization->subscription->plan) {
            return false;
        }

        $currentRooms = DB::table('rooms')
            ->where('organization_id', $organization->id)
            ->count();

        $maxRooms = $organization->subscription->plan->max_rooms ?? 0;

        // Enterprise plan has unlimited rooms
        if ($organization->subscription->plan->slug === 'enterprise') {
            return true;
        }

        return $currentRooms < $maxRooms;
    }

    /**
     * Get student usage statistics
     */
    public function getStudentUsage(Organization $organization): array
    {
        $currentStudents = DB::table('students')
            ->where('organization_id', $organization->id)
            ->count();

        $maxStudents = $organization->subscription && $organization->subscription->plan
            ? $organization->subscription->plan->max_students
            : 0;

        // Enterprise plan has unlimited students
        if ($organization->subscription && $organization->subscription->plan->slug === 'enterprise') {
            $maxStudents = PHP_INT_MAX;
        }

        return [
            'current' => $currentStudents,
            'max' => $maxStudents,
            'percent' => $maxStudents > 0 ? min(100, ($currentStudents / $maxStudents) * 100) : 0,
            'remaining' => max(0, $maxStudents - $currentStudents)
        ];
    }

    /**
     * Get hostel usage statistics
     */
    public function getHostelUsage(Organization $organization): array
    {
        $currentHostels = DB::table('hostels')
            ->where('organization_id', $organization->id)
            ->count();

        $maxHostels = $organization->subscription && $organization->subscription->plan
            ? $organization->subscription->plan->max_hostels
            : 0;

        // Enterprise plan has unlimited hostels
        if ($organization->subscription && $organization->subscription->plan->slug === 'enterprise') {
            $maxHostels = PHP_INT_MAX;
        }

        return [
            'current' => $currentHostels,
            'max' => $maxHostels,
            'percent' => $maxHostels > 0 ? min(100, ($currentHostels / $maxHostels) * 100) : 0,
            'remaining' => max(0, $maxHostels - $currentHostels)
        ];
    }

    /**
     * Get room usage statistics
     */
    public function getRoomUsage(Organization $organization): array
    {
        $currentRooms = DB::table('rooms')
            ->where('organization_id', $organization->id)
            ->count();

        $maxRooms = $organization->subscription && $organization->subscription->plan
            ? ($organization->subscription->plan->max_rooms ?? 0)
            : 0;

        // Enterprise plan has unlimited rooms
        if ($organization->subscription && $organization->subscription->plan->slug === 'enterprise') {
            $maxRooms = PHP_INT_MAX;
        }

        return [
            'current' => $currentRooms,
            'max' => $maxRooms,
            'percent' => $maxRooms > 0 ? min(100, ($currentRooms / $maxRooms) * 100) : 0,
            'remaining' => max(0, $maxRooms - $currentRooms)
        ];
    }

    /**
     * Get organization plan limits description
     */
    public function getLimitsDescription(Organization $organization): string
    {
        if (!$organization->subscription || !$organization->subscription->plan) {
            return 'कुनै सक्रिय योजना छैन';
        }

        $plan = $organization->subscription->plan;
        $limits = [];

        if ($plan->max_hostels === 1) {
            $limits[] = "१ होस्टेल";
        } elseif ($plan->max_hostels > 1) {
            $limits[] = "{$plan->max_hostels} होस्टेलहरू";
        } else {
            $limits[] = "असीमित होस्टेलहरू";
        }

        if ($plan->max_students > 0) {
            $limits[] = "{$plan->max_students} विद्यार्थीहरू";
        } else {
            $limits[] = "असीमित विद्यार्थीहरू";
        }

        if (($plan->max_rooms ?? 0) > 0) {
            $limits[] = "{$plan->max_rooms} कोठाहरू";
        } else {
            $limits[] = "असीमित कोठाहरू";
        }

        return implode(', ', $limits);
    }

    /**
     * Check if organization needs upgrade
     */
    public function needsUpgrade(Organization $organization): bool
    {
        return !$this->canAddHostel($organization) ||
            !$this->canAddStudent($organization) ||
            !$this->canAddRoom($organization);
    }

    /**
     * Get remaining hostel slots
     */
    public function getRemainingHostels(Organization $organization): int
    {
        $currentHostels = DB::table('hostels')
            ->where('organization_id', $organization->id)
            ->count();

        if (!$organization->subscription || !$organization->subscription->plan) {
            return 0;
        }

        $maxHostels = $organization->subscription->plan->max_hostels;

        // Enterprise plan has unlimited
        if ($organization->subscription->plan->slug === 'enterprise') {
            return PHP_INT_MAX;
        }

        return max(0, $maxHostels - $currentHostels);
    }

    /**
     * Get remaining student slots
     */
    public function getRemainingStudents(Organization $organization): int
    {
        $currentStudents = DB::table('students')
            ->where('organization_id', $organization->id)
            ->count();

        if (!$organization->subscription || !$organization->subscription->plan) {
            return 0;
        }

        $maxStudents = $organization->subscription->plan->max_students;

        // Enterprise plan has unlimited
        if ($organization->subscription->plan->slug === 'enterprise') {
            return PHP_INT_MAX;
        }

        return max(0, $maxStudents - $currentStudents);
    }

    /**
     * Get remaining room slots
     */
    public function getRemainingRooms(Organization $organization): int
    {
        $currentRooms = DB::table('rooms')
            ->where('organization_id', $organization->id)
            ->count();

        if (!$organization->subscription || !$organization->subscription->plan) {
            return 0;
        }

        $maxRooms = $organization->subscription->plan->max_rooms ?? 0;

        // Enterprise plan has unlimited
        if ($organization->subscription->plan->slug === 'enterprise') {
            return PHP_INT_MAX;
        }

        return max(0, $maxRooms - $currentRooms);
    }

    /**
     * Get plan usage overview
     */
    public function getUsageOverview(Organization $organization): array
    {
        return [
            'hostels' => $this->getHostelUsage($organization),
            'students' => $this->getStudentUsage($organization),
            'rooms' => $this->getRoomUsage($organization),
            'needs_upgrade' => $this->needsUpgrade($organization),
            'limits_description' => $this->getLimitsDescription($organization)
        ];
    }
}
