<?php

namespace App\Services;

use App\Models\Organization;
use Illuminate\Support\Facades\DB;

class PlanLimitService
{
    public function canAddStudent(Organization $organization): bool
    {
        $currentStudents = DB::table('students')
            ->where('organization_id', $organization->id)
            ->count();

        $maxStudents = $organization->subscription->plan->max_students;

        return $currentStudents < $maxStudents;
    }

    public function canAddHostel(Organization $organization): bool
    {
        $currentHostels = DB::table('hostels')
            ->where('organization_id', $organization->id)
            ->count();

        $maxHostels = $organization->subscription->plan->max_hostels;

        return $currentHostels < $maxHostels;
    }

    public function getStudentUsage(Organization $organization): array
    {
        $currentStudents = DB::table('students')
            ->where('organization_id', $organization->id)
            ->count();

        $maxStudents = $organization->subscription->plan->max_students;

        return [
            'current' => $currentStudents,
            'max' => $maxStudents,
            'percent' => min(100, ($currentStudents / $maxStudents) * 100)
        ];
    }

    public function getHostelUsage(Organization $organization): array
    {
        $currentHostels = DB::table('hostels')
            ->where('organization_id', $organization->id)
            ->count();

        $maxHostels = $organization->subscription->plan->max_hostels;

        return [
            'current' => $currentHostels,
            'max' => $maxHostels,
            'percent' => min(100, ($currentHostels / $maxHostels) * 100)
        ];
    }
}
