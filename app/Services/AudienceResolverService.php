<?php

namespace App\Services;

use App\Models\Circular;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;

class AudienceResolverService
{
    /**
     * सर्कुलरको लागि लक्षित प्रयोगकर्ताहरूको क्वेरी प्राप्त गर्नुहोस्।
     */
    public function resolveQuery(Circular $circular): Builder
    {
        $audience = $circular->audience_type;
        $target = $circular->target_audience ?? [];
        $orgId = $circular->organization_id;

        $query = User::query();

        switch ($audience) {
            case 'all_students':
                $query->role('student');
                break;

            case 'all_managers':
                $query->role('hostel_manager');
                break;

            case 'all_users':
                $query->where(function ($q) {
                    $q->role('student')->orWhere->role('hostel_manager');
                });
                break;

            case 'organization_students':
                $query->role('student')
                    ->whereHas('student', fn($q) => $q->where('organization_id', $orgId));
                break;

            case 'organization_managers':
                $query->role('hostel_manager')
                    ->whereHas('hostel', fn($q) => $q->where('organization_id', $orgId));
                break;

            case 'organization_users':
                $query->where(function ($q) use ($orgId) {
                    $q->role('student')->whereHas('student', fn($q) => $q->where('organization_id', $orgId))
                        ->orWhere->role('hostel_manager')->whereHas('hostel', fn($q) => $q->where('organization_id', $orgId));
                });
                break;

            case 'specific_hostel':
                $hostelIds = is_array($target) ? $target : [];
                $query->role('student')
                    ->whereHas('student', fn($q) => $q->whereIn('hostel_id', $hostelIds));
                break;

            case 'specific_students':
                $userIds = is_array($target) ? $target : [];
                $query->whereIn('id', $userIds);
                break;

            default:
                // खाली क्वेरी फर्काउनुहोस्
                $query->whereRaw('1 = 0');
        }

        return $query;
    }

    /**
     * प्रयोगकर्ता ID हरू चंक (chunk) मा लूप गर्नुहोस् र प्रत्येक चंकको लागि callback कल गर्नुहोस्।
     */
    public function chunkUserIds(Circular $circular, callable $callback, int $chunkSize = 500): void
    {
        $query = $this->resolveQuery($circular);

        // user_type पनि सँगै ल्याउँछौं (roles को आधारमा)
        $query->select('users.id')
            ->selectRaw('CASE 
                  WHEN EXISTS (SELECT 1 FROM model_has_roles WHERE model_has_roles.model_id = users.id AND model_has_roles.role_id = (SELECT id FROM roles WHERE name = "student")) 
                  THEN "student" 
                  ELSE "hostel_manager" END as user_type');

        $query->orderBy('users.id')->chunk($chunkSize, function ($users) use ($callback) {
            $userIds = $users->pluck('id')->toArray();
            $userTypeMap = $users->pluck('user_type', 'id')->toArray();
            $callback($userIds, $userTypeMap);
        });
    }
}
