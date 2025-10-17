<?php

namespace App\Http\Controllers;

use App\Models\Circular;
use App\Models\CircularRecipient;
use App\Models\Organization;
use App\Models\Hostel;
use App\Models\Student;
use App\Models\User;
use App\Http\Requests\StoreCircularRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class CircularController extends Controller
{
    // Circulars list for admin
    public function index(Request $request)
    {
        $user = auth()->user();
        $query = Circular::with(['organization', 'creator']);

        if ($user->hasRole('admin')) {
            // Admin sees all circulars
            if ($request->has('organization_id')) {
                $query->where('organization_id', $request->organization_id);
            }
        } elseif ($user->hasRole('hostel_manager')) {
            // Hostel manager sees only their organization's circulars
            $organizationId = $user->organizations()->first()->id;
            $query->where('organization_id', $organizationId);
        }

        // Filter by status
        if ($request->has('status') && in_array($request->status, ['draft', 'published', 'archived'])) {
            $query->where('status', $request->status);
        }

        // Filter by priority
        if ($request->has('priority') && in_array($request->priority, ['urgent', 'normal', 'info'])) {
            $query->where('priority', $request->priority);
        }

        $circulars = $query->latest()->paginate(20);

        $organizations = $user->hasRole('admin') ? Organization::all() : collect();

        return view('circulars.index', compact('circulars', 'organizations'));
    }

    // Create form with audience selection
    public function create()
    {
        $user = auth()->user();
        $organizations = [];
        $hostels = [];
        $students = [];

        if ($user->hasRole('admin')) {
            $organizations = Organization::all();
            $hostels = Hostel::all();
            $students = Student::with('user')->get();
        } elseif ($user->hasRole('hostel_manager')) {
            $organization = $user->organizations()->first();
            if ($organization) {
                $hostels = $organization->hostels;
                $students = $organization->students()->with('user')->get();
            }
        }

        return view('circulars.create', compact('organizations', 'hostels', 'students'));
    }

    // Save circular with recipient creation
    public function store(StoreCircularRequest $request)
    {
        DB::transaction(function () use ($request) {
            $user = auth()->user();
            $data = $request->validated();

            // Set organization based on user role
            if ($user->hasRole('hostel_manager')) {
                $organization = $user->organizations()->first();
                $data['organization_id'] = $organization->id;
            } elseif (!$user->hasRole('admin')) {
                abort(403, 'तपाईंसँग सूचना सिर्जना गर्ने अनुमति छैन');
            }

            $data['created_by'] = $user->id;

            // Create circular
            $circular = Circular::create($data);

            // Create recipients based on audience type
            $this->createRecipients($circular, $data['audience_type'], $data['target_audience'] ?? null);

            // Auto-publish if not scheduled
            if (!$circular->scheduled_at) {
                $circular->markAsPublished();
            }
        });

        $message = $request->has('scheduled_at') ? 'सूचना सफलतापूर्वक सिर्जना गरियो र तोकिएको समयमा प्रकाशित हुनेछ' : 'सूचना सफलतापूर्वक प्रकाशित गरियो';

        return redirect()->route($this->getRoutePrefix() . 'circulars.index')
            ->with('success', $message);
    }

    // View circular
    public function show(Circular $circular)
    {
        $this->authorizeView($circular);

        $circular->load(['organization', 'creator', 'recipients.user']);

        // For students, mark as read when viewing
        if (auth()->user()->hasRole('student')) {
            $recipient = CircularRecipient::where('circular_id', $circular->id)
                ->where('user_id', auth()->id())
                ->first();
            if ($recipient && !$recipient->is_read) {
                $recipient->markAsRead();
            }
        }

        $readStats = $this->getReadStats($circular);

        return view('circulars.show', compact('circular', 'readStats'));
    }

    // Edit form  
    public function edit(Circular $circular)
    {
        $this->authorizeEdit($circular);

        $user = auth()->user();
        $organizations = [];
        $hostels = [];
        $students = [];

        if ($user->hasRole('admin')) {
            $organizations = Organization::all();
            $hostels = Hostel::all();
            $students = Student::with('user')->get();
        } elseif ($user->hasRole('hostel_manager')) {
            $organization = $user->organizations()->first();
            if ($organization) {
                $hostels = $organization->hostels;
                $students = $organization->students()->with('user')->get();
            }
        }

        return view('circulars.edit', compact('circular', 'organizations', 'hostels', 'students'));
    }

    // Update circular
    public function update(StoreCircularRequest $request, Circular $circular)
    {
        $this->authorizeEdit($circular);

        $circular->update($request->validated());

        return redirect()->route($this->getRoutePrefix() . 'circulars.show', $circular)
            ->with('success', 'सूचना सफलतापूर्वक अद्यावधिक गरियो');
    }

    // Delete circular
    public function destroy(Circular $circular)
    {
        $this->authorizeEdit($circular);

        $circular->delete();

        return redirect()->route($this->getRoutePrefix() . 'circulars.index')
            ->with('success', 'सूचना सफलतापूर्वक मेटाइयो');
    }

    // Publish circular
    public function publish(Circular $circular)
    {
        $this->authorizeEdit($circular);

        $circular->markAsPublished();

        return back()->with('success', 'सूचना सफलतापूर्वक प्रकाशित गरियो');
    }

    // User marks as read
    public function markAsRead(Circular $circular)
    {
        $user = auth()->user();

        $recipient = CircularRecipient::where('circular_id', $circular->id)
            ->where('user_id', $user->id)
            ->first();

        if ($recipient) {
            $recipient->markAsRead();
        }

        return response()->json(['success' => true]);
    }

    // Analytics - FIXED: Handle both general and single circular analytics
    public function analytics(Circular $circular = null)
    {
        $user = auth()->user();

        if ($circular) {
            // Single circular analytics
            $this->authorizeView($circular);
            $stats = $this->getCircularAnalytics($circular);
            return view('circulars.analytics-single', compact('circular', 'stats'));
        } else {
            // General analytics - FIXED: Added this missing case
            if ($user->hasRole('admin')) {
                $stats = $this->getAdminAnalytics();
            } else {
                $stats = $this->getOrganizationAnalytics($user);
            }

            return view('circulars.analytics', compact('stats'));
        }
    }

    // Student-specific index
    public function studentIndex(Request $request)
    {
        $user = auth()->user();

        $circularIds = CircularRecipient::where('user_id', $user->id)
            ->pluck('circular_id');

        $query = Circular::whereIn('id', $circularIds)
            ->published()
            ->active()
            ->with(['creator', 'organization']);

        // Filter by read status
        if ($request->has('read_status')) {
            $readCircularIds = CircularRecipient::where('user_id', $user->id)
                ->where('is_read', $request->read_status === 'read')
                ->pluck('circular_id');
            $query->whereIn('id', $readCircularIds);
        }

        // Search
        if ($request->has('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        $circulars = $query->latest()->paginate(15);

        return view('student.circulars.index', compact('circulars'));
    }

    // Student-specific show
    public function studentShow(Circular $circular)
    {
        $user = auth()->user();

        // Verify the student has access to this circular
        $recipient = CircularRecipient::where('circular_id', $circular->id)
            ->where('user_id', $user->id)
            ->firstOrFail();

        // Mark as read
        if (!$recipient->is_read) {
            $recipient->markAsRead();
        }

        return view('student.circulars.show', compact('circular'));
    }

    // PROTECTED METHODS - CHANGED FROM PRIVATE TO PROTECTED

    protected function createRecipients(Circular $circular, $audienceType, $targetData = null)
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

    protected function authorizeView(Circular $circular)
    {
        $user = auth()->user();

        if ($user->hasRole('admin')) {
            return true;
        }

        if ($user->hasRole('hostel_manager')) {
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

    protected function authorizeEdit(Circular $circular)
    {
        $user = auth()->user();

        if ($user->hasRole('admin')) {
            return true;
        }

        if ($user->hasRole('hostel_manager')) {
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

    protected function getRoutePrefix()
    {
        $user = auth()->user();

        if ($user->hasRole('admin')) {
            return 'admin.';
        } elseif ($user->hasRole('hostel_manager')) {
            return 'owner.';
        }

        return '';
    }

    protected function getReadStats(Circular $circular)
    {
        return [
            'total' => $circular->recipients()->count(),
            'read' => $circular->recipients()->read()->count(),
            'unread' => $circular->recipients()->unread()->count(),
            'read_percentage' => $circular->recipients()->count() > 0 ?
                round(($circular->recipients()->read()->count() / $circular->recipients()->count()) * 100, 2) : 0
        ];
    }

    protected function getCircularAnalytics(Circular $circular)
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

    protected function getAdminAnalytics()
    {
        // Implementation for admin analytics across all organizations
        // This would include cross-organization statistics
        return [
            'total_circulars' => Circular::count(),
            'published_circulars' => Circular::published()->count(),
            'total_organizations' => Organization::count(),
            // Add more analytics as needed
        ];
    }

    protected function getOrganizationAnalytics(User $user)
    {
        $organization = $user->organizations()->first();

        if (!$organization) {
            return [];
        }

        return [
            'total_circulars' => Circular::forOrganization($organization->id)->count(),
            'published_circulars' => Circular::forOrganization($organization->id)->published()->count(),
            'student_count' => $organization->students()->count(),
            // Add more organization-specific analytics
        ];
    }
}
