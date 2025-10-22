<?php

namespace App\Services;

use App\Models\Circular;
use App\Models\CircularRecipient;
use App\Models\User;
use App\Models\Organization;
use App\Models\Hostel;
use App\Models\Student;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class CircularService
{
    /**
     * Create recipients for a circular
     */
    public function createRecipients(Circular $circular, $audienceType, $targetData = null)
    {
        $recipients = [];
        $users = collect();

        switch ($audienceType) {
            case 'all_students':
                $users = User::whereHas('roles', function ($q) {
                    $q->where('name', 'student');
                })->get();
                break;

            case 'all_managers':
                $users = User::whereHas('roles', function ($q) {
                    $q->where('name', 'hostel_manager');
                })->get();
                break;

            case 'all_users':
                $users = User::whereHas('roles', function ($q) {
                    $q->whereIn('name', ['student', 'hostel_manager']);
                })->get();
                break;

            case 'organization_students':
                $users = User::whereHas('student', function ($q) use ($circular) {
                    $q->where('organization_id', $circular->organization_id);
                })->get();
                break;

            case 'organization_managers':
                $users = User::whereHas('organizations', function ($q) use ($circular) {
                    $q->where('organization_id', $circular->organization_id)
                        ->where('role', 'owner');
                })->get();
                break;

            case 'organization_users':
                $users = User::where(function ($q) use ($circular) {
                    $q->whereHas('student', function ($q2) use ($circular) {
                        $q2->where('organization_id', $circular->organization_id);
                    })->orWhereHas('organizations', function ($q2) use ($circular) {
                        $q2->where('organization_id', $circular->organization_id)
                            ->where('role', 'owner');
                    });
                })->get();
                break;

            case 'specific_hostel':
                if ($targetData) {
                    $users = User::whereHas('student', function ($q) use ($targetData) {
                        $q->whereIn('hostel_id', $targetData);
                    })->get();
                }
                break;

            case 'specific_students':
                if ($targetData) {
                    $users = User::whereIn('id', $targetData)->get();
                }
                break;
        }

        foreach ($users as $user) {
            $userType = $user->hasRole('student') ? 'student' : 'hostel_manager';

            $recipients[] = [
                'circular_id' => $circular->id,
                'user_id' => $user->id,
                'user_type' => $userType,
                'created_at' => now(),
                'updated_at' => now()
            ];
        }

        if (!empty($recipients)) {
            CircularRecipient::insert($recipients);
        }
    }

    /**
     * Get read statistics for a circular
     */
    public function getReadStats(Circular $circular)
    {
        return [
            'total' => $circular->recipients()->count(),
            'read' => $circular->recipients()->read()->count(),
            'unread' => $circular->recipients()->unread()->count(),
            'read_percentage' => $circular->recipients()->count() > 0 ?
                round(($circular->recipients()->read()->count() / $circular->recipients()->count()) * 100, 2) : 0
        ];
    }

    /**
     * Get analytics for a specific circular
     */
    public function getCircularAnalytics(Circular $circular)
    {
        $stats = $circular->recipients()
            ->selectRaw('user_type, COUNT(*) as total, SUM(is_read) as read_count')
            ->groupBy('user_type')
            ->get();

        return [
            'by_user_type' => $stats,
            'total_recipients' => $circular->recipients()->count(),
            'total_read' => $circular->recipients()->read()->count(),
            'engagement_rate' => $circular->recipients()->count() > 0 ?
                round(($circular->recipients()->read()->count() / $circular->recipients()->count()) * 100, 2) : 0
        ];
    }

    /**
     * Get admin-level analytics
     */
    public function getAdminAnalytics()
    {
        return [
            'total_circulars' => Circular::count(),
            'published_circulars' => Circular::published()->count(),
            'total_organizations' => Organization::count(),
            'urgent_count' => Circular::where('priority', 'urgent')->count(),
            'normal_count' => Circular::where('priority', 'normal')->count(),
            'info_count' => Circular::where('priority', 'info')->count(),
        ];
    }

    /**
     * Get organization-level analytics
     */
    public function getOrganizationAnalytics(User $user)
    {
        $organization = $user->organizations()->first();

        if (!$organization) {
            return [];
        }

        return [
            'total_circulars' => Circular::forOrganization($organization->id)->count(),
            'published_circulars' => Circular::forOrganization($organization->id)->published()->count(),
            'student_count' => $organization->students()->count(),
            'urgent_count' => Circular::forOrganization($organization->id)->where('priority', 'urgent')->count(),
            'normal_count' => Circular::forOrganization($organization->id)->where('priority', 'normal')->count(),
            'info_count' => Circular::forOrganization($organization->id)->where('priority', 'info')->count(),
        ];
    }

    /**
     * Authorize view access for circular
     */
    public function authorizeView(Circular $circular, User $user)
    {
        if ($user->hasRole('admin')) {
            return true;
        }

        if ($user->hasRole('hostel_manager') || $user->hasRole('owner')) {
            $organization = $user->organizations()->first();
            if ($circular->organization_id === $organization->id) {
                return true;
            }
        }

        if ($user->hasRole('student')) {
            $hasAccess = CircularRecipient::where('circular_id', $circular->id)
                ->where('user_id', $user->id)
                ->exists();
            if ($hasAccess) {
                return true;
            }
        }

        abort(403, 'तपाईंसँग यो सूचना हेर्ने अनुमति छैन');
    }

    /**
     * Authorize edit access for circular
     */
    public function authorizeEdit(Circular $circular, User $user)
    {
        if ($user->hasRole('admin')) {
            return true;
        }

        if ($user->hasRole('hostel_manager') || $user->hasRole('owner')) {
            $organization = $user->organizations()->first();
            if (
                $circular->organization_id === $organization->id &&
                $circular->created_by === $user->id
            ) {
                return true;
            }
        }

        abort(403, 'तपाईंसँग यो सूचना सम्पादन गर्ने अनुमति छैन');
    }
}
