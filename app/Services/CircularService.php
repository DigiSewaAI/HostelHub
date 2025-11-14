<?php

namespace App\Services;

use App\Models\Circular;
use App\Models\CircularRecipient;
use App\Models\User;
use App\Models\Organization;
use App\Models\Hostel;
use App\Models\Student;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class CircularService
{
    /**
     * Create recipients for a circular
     */
    public function createRecipients(Circular $circular, $audienceType, $targetData = null, $organizationId = null)
    {
        // ✅ FIX: Add proper validation and error handling
        if (!$circular->exists) {
            Log::error('Circular does not exist for recipient creation');
            throw new \Exception('Circular does not exist');
        }

        $recipients = [];
        $users = collect();

        // ✅ FIX: Ensure targetData is always an array
        $targetData = $targetData ?? [];
        if (!is_array($targetData)) {
            $targetData = [];
        }

        // ✅ FIX: Use provided organizationId or fall back to circular's organization
        $orgId = $organizationId ?? $circular->organization_id;

        Log::info('Creating recipients', [
            'circular_id' => $circular->id,
            'audience_type' => $audienceType,
            'target_data' => $targetData,
            'organization_id' => $orgId
        ]);

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
                if ($orgId) {
                    $users = User::whereHas('student', function ($q) use ($orgId) {
                        $q->where('organization_id', $orgId);
                    })->get();
                } else {
                    Log::warning('Organization ID missing for organization_students audience');
                }
                break;

            case 'organization_managers':
                if ($orgId) {
                    $users = User::whereHas('organizations', function ($q) use ($orgId) {
                        $q->where('organization_id', $orgId)
                            ->where('role', 'owner');
                    })->get();
                } else {
                    Log::warning('Organization ID missing for organization_managers audience');
                }
                break;

            case 'organization_users':
                if ($orgId) {
                    $users = User::where(function ($q) use ($orgId) {
                        $q->whereHas('student', function ($q2) use ($orgId) {
                            $q2->where('organization_id', $orgId);
                        })->orWhereHas('organizations', function ($q2) use ($orgId) {
                            $q2->where('organization_id', $orgId)
                                ->where('role', 'owner');
                        });
                    })->get();
                } else {
                    Log::warning('Organization ID missing for organization_users audience');
                }
                break;

            case 'specific_hostel':
                if (!empty($targetData)) {
                    $users = User::whereHas('student', function ($q) use ($targetData) {
                        $q->whereIn('hostel_id', $targetData);
                    })->get();
                    Log::info('Specific hostel users found', ['count' => $users->count()]);
                } else {
                    Log::warning('Target data empty for specific_hostel audience');
                }
                break;

            case 'specific_students':
                if (!empty($targetData)) {
                    $users = User::whereIn('id', $targetData)->get();
                    Log::info('Specific students users found', ['count' => $users->count()]);
                } else {
                    Log::warning('Target data empty for specific_students audience');
                }
                break;

            default:
                Log::warning('Unknown audience type', ['audience_type' => $audienceType]);
                break;
        }

        // ✅ FIX: Check if we have users to avoid empty inserts
        if ($users->isEmpty()) {
            Log::warning('No users found for circular recipients', [
                'circular_id' => $circular->id,
                'audience_type' => $audienceType,
                'target_data' => $targetData,
                'organization_id' => $orgId
            ]);
            return 0; // Return 0 recipients created
        }

        foreach ($users as $user) {
            $userType = $user->hasRole('student') ? 'student' : 'hostel_manager';

            $recipients[] = [
                'circular_id' => $circular->id,
                'user_id' => $user->id,
                'user_type' => $userType,
                'is_read' => false,
                'created_at' => now(),
                'updated_at' => now()
            ];
        }

        if (!empty($recipients)) {
            try {
                CircularRecipient::insert($recipients);
                Log::info('Recipients created successfully', [
                    'circular_id' => $circular->id,
                    'recipient_count' => count($recipients)
                ]);
                return count($recipients); // Return number of recipients created
            } catch (\Exception $e) {
                Log::error('Failed to create recipients: ' . $e->getMessage(), [
                    'circular_id' => $circular->id,
                    'recipient_count' => count($recipients),
                    'error' => $e->getMessage()
                ]);
                throw $e; // Re-throw to trigger transaction rollback
            }
        }

        return 0;
    }

    /**
     * Get read statistics for a circular
     */
    public function getReadStats(Circular $circular)
    {
        try {
            $total = $circular->recipients()->count();
            $read = $circular->recipients()->where('is_read', true)->count();
            $unread = $total - $read;
            $readPercentage = $total > 0 ? round(($read / $total) * 100, 2) : 0;

            return [
                'total' => $total,
                'read' => $read,
                'unread' => $unread,
                'read_percentage' => $readPercentage
            ];
        } catch (\Exception $e) {
            Log::error('Error getting read stats: ' . $e->getMessage());
            return [
                'total' => 0,
                'read' => 0,
                'unread' => 0,
                'read_percentage' => 0
            ];
        }
    }

    /**
     * Get analytics for a specific circular
     */
    public function getCircularAnalytics(Circular $circular)
    {
        try {
            $stats = $circular->recipients()
                ->selectRaw('user_type, COUNT(*) as total, SUM(CASE WHEN is_read = true THEN 1 ELSE 0 END) as read_count')
                ->groupBy('user_type')
                ->get();

            $totalRecipients = $circular->recipients()->count();
            $totalRead = $circular->recipients()->where('is_read', true)->count();
            $engagementRate = $totalRecipients > 0 ? round(($totalRead / $totalRecipients) * 100, 2) : 0;

            return [
                'by_user_type' => $stats,
                'total_recipients' => $totalRecipients,
                'total_read' => $totalRead,
                'engagement_rate' => $engagementRate
            ];
        } catch (\Exception $e) {
            Log::error('Error getting circular analytics: ' . $e->getMessage());
            return [
                'by_user_type' => collect(),
                'total_recipients' => 0,
                'total_read' => 0,
                'engagement_rate' => 0
            ];
        }
    }

    /**
     * Get admin-level analytics
     */
    public function getAdminAnalytics()
    {
        try {
            return [
                'total_circulars' => Circular::count(),
                'published_circulars' => Circular::where('status', 'published')->count(),
                'total_organizations' => Organization::count(),
                'urgent_count' => Circular::where('priority', 'urgent')->count(),
                'normal_count' => Circular::where('priority', 'normal')->count(),
                'info_count' => Circular::where('priority', 'info')->count(),
            ];
        } catch (\Exception $e) {
            Log::error('Error getting admin analytics: ' . $e->getMessage());
            return [
                'total_circulars' => 0,
                'published_circulars' => 0,
                'total_organizations' => 0,
                'urgent_count' => 0,
                'normal_count' => 0,
                'info_count' => 0,
            ];
        }
    }

    /**
     * Get organization-level analytics
     */
    public function getOrganizationAnalytics(User $user)
    {
        try {
            $organization = $user->organizations()->first();

            if (!$organization) {
                Log::warning('No organization found for user', ['user_id' => $user->id]);
                return [];
            }

            return [
                'total_circulars' => Circular::where('organization_id', $organization->id)->count(),
                'published_circulars' => Circular::where('organization_id', $organization->id)
                    ->where('status', 'published')->count(),
                'student_count' => $organization->students()->count(),
                'urgent_count' => Circular::where('organization_id', $organization->id)
                    ->where('priority', 'urgent')->count(),
                'normal_count' => Circular::where('organization_id', $organization->id)
                    ->where('priority', 'normal')->count(),
                'info_count' => Circular::where('organization_id', $organization->id)
                    ->where('priority', 'info')->count(),
            ];
        } catch (\Exception $e) {
            Log::error('Error getting organization analytics: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Authorize view access for circular
     */
    public function authorizeView(Circular $circular, User $user)
    {
        try {
            if ($user->hasRole('admin')) {
                return true;
            }

            if ($user->hasRole('hostel_manager') || $user->hasRole('owner')) {
                $organization = $user->organizations()->first();
                if ($organization && $circular->organization_id === $organization->id) {
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

            Log::warning('Unauthorized view access attempt', [
                'user_id' => $user->id,
                'circular_id' => $circular->id,
                'user_roles' => $user->getRoleNames()
            ]);

            abort(403, 'तपाईंसँग यो सूचना हेर्ने अनुमति छैन');
        } catch (\Exception $e) {
            Log::error('Error in authorizeView: ' . $e->getMessage());
            abort(403, 'तपाईंसँग यो सूचना हेर्ने अनुमति छैन');
        }
    }

    /**
     * Authorize edit access for circular
     */
    public function authorizeEdit(Circular $circular, User $user)
    {
        try {
            if ($user->hasRole('admin')) {
                return true;
            }

            if ($user->hasRole('hostel_manager') || $user->hasRole('owner')) {
                $organization = $user->organizations()->first();
                if (
                    $organization &&
                    $circular->organization_id === $organization->id &&
                    $circular->created_by === $user->id
                ) {
                    return true;
                }
            }

            Log::warning('Unauthorized edit access attempt', [
                'user_id' => $user->id,
                'circular_id' => $circular->id,
                'user_roles' => $user->getRoleNames()
            ]);

            abort(403, 'तपाईंसँग यो सूचना सम्पादन गर्ने अनुमति छैन');
        } catch (\Exception $e) {
            Log::error('Error in authorizeEdit: ' . $e->getMessage());
            abort(403, 'तपाईंसँग यो सूचना सम्पादन गर्ने अनुमति छैन');
        }
    }

    /**
     * ✅ NEW: Mark circular as read for a user
     */
    public function markAsRead(Circular $circular, User $user)
    {
        try {
            $recipient = CircularRecipient::where('circular_id', $circular->id)
                ->where('user_id', $user->id)
                ->first();

            if ($recipient && !$recipient->is_read) {
                $recipient->update([
                    'is_read' => true,
                    'read_at' => now()
                ]);
                return true;
            }

            return false;
        } catch (\Exception $e) {
            Log::error('Error marking circular as read: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * ✅ NEW: Get unread circulars count for user
     */
    public function getUnreadCount(User $user)
    {
        try {
            return CircularRecipient::where('user_id', $user->id)
                ->where('is_read', false)
                ->count();
        } catch (\Exception $e) {
            Log::error('Error getting unread count: ' . $e->getMessage());
            return 0;
        }
    }
}
