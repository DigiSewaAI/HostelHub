<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Circular;
use App\Models\Hostel;
use App\Models\Student;
use App\Models\CircularRecipient;
use App\Http\Requests\StoreCircularRequest;
use App\Http\Requests\UpdateCircularRequest; // ✅ Use UpdateCircularRequest instead of StoreCircularRequest for update
use App\Services\CircularService;
use App\Jobs\GenerateCircularRecipientsJob; // ✅ Job for recipient generation
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class CircularController extends Controller
{
    protected $circularService;

    public function __construct(CircularService $circularService)
    {
        $this->circularService = $circularService;
    }

    // ✅ Helper method for owner authorization (used in index/create)
    private function authorizeOwnerAccess($organizationId = null)
    {
        $user = Auth::user();

        if ($user->hasRole('hostel_manager')) {
            if ($organizationId) {
                $userOrganization = $user->organizations()->first();
                if (!$userOrganization || $userOrganization->id != $organizationId) {
                    abort(403, 'तपाईंसँग यो संस्थाको डाटा एक्सेस गर्ने अनुमति छैन');
                }
            }

            // Additional hostel-based authorization
            if (!$user->hostel_id) {
                abort(403, 'तपाईंसँग कुनै होस्टल सम्बन्धित छैन');
            }
        }

        return true;
    }

    public function index(Request $request)
    {
        $user = auth()->user();

        if ($user->hasRole('hostel_manager')) {
            $this->authorizeOwnerAccess();
        }

        $organization = $user->organizations()->first();

        $query = Circular::with(['organization', 'creator'])
            ->where('organization_id', $organization->id);

        if ($request->has('status') && in_array($request->status, ['draft', 'published', 'archived'])) {
            $query->where('status', $request->status);
        }

        if ($request->has('priority') && in_array($request->priority, ['urgent', 'normal', 'info'])) {
            $query->where('priority', $request->priority);
        }

        $circulars = $query->latest()->paginate(20);

        return view('owner.circulars.index', compact('circulars'));
    }

    public function create()
    {
        $user = auth()->user();

        if ($user->hasRole('hostel_manager')) {
            $this->authorizeOwnerAccess();
        }

        $organization = $user->organizations()->first();

        $hostels = $organization->hostels;
        $students = $organization->students()->with('user')->get();

        return view('owner.circulars.create', compact('hostels', 'students', 'organization'));
    }

    public function store(StoreCircularRequest $request)
    {
        $user = auth()->user();
        $organization = $user->organizations()->first();

        try {
            DB::transaction(function () use ($request, $user, $organization) {
                $data = $request->validated();

                // override sensitive fields
                $data['organization_id'] = $organization->id;
                $data['created_by'] = $user->id;

                // Schedule vs immediate publish
                if ($request->filled('scheduled_at') && $request->scheduled_at > now()) {
                    $data['status'] = 'draft';
                } else {
                    $data['status'] = 'published';
                    $data['published_at'] = now();
                }

                // Validate target_audience against org
                if (in_array($data['audience_type'], ['specific_hostel', 'specific_students'])) {
                    $data['target_audience'] = array_filter($data['target_audience'], function ($id) use ($organization, $data) {
                        if ($data['audience_type'] === 'specific_hostel') {
                            return in_array($id, $organization->hostels->pluck('id')->toArray());
                        }
                        if ($data['audience_type'] === 'specific_students') {
                            return in_array($id, $organization->students->pluck('id')->toArray());
                        }
                        return false;
                    });
                }

                $circular = Circular::create($data);

                // Recipient creation
                $this->circularService->createRecipients(
                    $circular,
                    $data['audience_type'],
                    $data['target_audience'] ?? [],
                    $organization->id
                );

                \Log::info('Circular created', [
                    'id' => $circular->id,
                    'audience' => $data['audience_type'],
                    'org' => $organization->id,
                    'status' => $circular->status
                ]);
            });

            $message = ($request->filled('scheduled_at') && $request->scheduled_at > now())
                ? 'सूचना सफलतापूर्वक सिर्जना गरियो र तोकिएको समयमा प्रकाशित हुनेछ'
                : 'सूचना सफलतापूर्वक प्रकाशित गरियो';

            return redirect()->route('owner.circulars.index')
                ->with('success', $message);
        } catch (\Exception $e) {
            \Log::error('Circular creation failed: ' . $e->getMessage(), $request->except('password'));
            return redirect()->back()->withInput()->with('error', 'सूचना सिर्जना गर्दा त्रुटि भयो: ' . $e->getMessage());
        }
    }

    public function show(Circular $circular)
    {
        $user = auth()->user();

        // ✅ Use Policy instead of service
        $this->authorize('view', $circular);

        $circular->load(['organization', 'creator', 'recipients.user']);
        $readStats = $this->circularService->getReadStats($circular); // This service method can stay

        return view('owner.circulars.show', compact('circular', 'readStats'));
    }

    public function edit(Circular $circular)
    {
        $user = auth()->user();

        // ✅ Use Policy instead of service
        $this->authorize('update', $circular);

        $organization = $user->organizations()->first();

        $hostels = $organization->hostels;
        $students = $organization->students()->with('user')->get();

        return view('owner.circulars.edit', compact('circular', 'hostels', 'students'));
    }

    /**
     * Update the specified circular.
     * Uses UpdateCircularRequest for validation.
     */
    public function update(UpdateCircularRequest $request, Circular $circular)
    {
        $user = auth()->user();

        // ✅ Use Policy
        $this->authorize('update', $circular);

        // Capture old audience data before update
        $oldAudience = [
            'audience_type' => $circular->audience_type,
            'target_audience' => $circular->target_audience,
            'organization_id' => $circular->organization_id,
        ];

        $data = $request->validated();

        // Handle scheduling logic
        if (isset($data['scheduled_at']) && $data['scheduled_at'] > now()) {
            $data['status'] = 'draft';
            $data['published_at'] = null;
        } elseif ($circular->status === 'draft' && (!isset($data['scheduled_at']) || $data['scheduled_at'] <= now())) {
            // If updating from draft without future scheduled_at, keep status as draft unless explicitly published
            // But we can keep existing logic: if scheduled_at is past or not set, set as published? Let's keep original logic.
            // Actually original logic: if scheduled_at present and future -> draft, else published. But for update, we need to be careful.
            // We'll follow original: if request has scheduled_at > now -> draft, else published (with published_at set if not already).
            $data['status'] = 'published';
            if (!$circular->published_at) {
                $data['published_at'] = now();
            }
        }

        $circular->update($data);

        // Check if audience changed
        $newAudience = [
            'audience_type' => $circular->audience_type,
            'target_audience' => $circular->target_audience,
            'organization_id' => $circular->organization_id,
        ];

        if ($oldAudience != $newAudience) {
            // Delete existing recipients and dispatch job to regenerate
            $circular->recipients()->delete();
            GenerateCircularRecipientsJob::dispatch($circular->id);
        }

        return redirect()->route('owner.circulars.show', $circular)
            ->with('success', 'सूचना सफलतापूर्वक अद्यावधिक गरियो');
    }

    public function destroy(Circular $circular)
    {
        $user = auth()->user();

        // ✅ Use Policy
        $this->authorize('delete', $circular);

        $circular->delete();

        return redirect()->route('owner.circulars.index')
            ->with('success', 'सूचना सफलतापूर्वक मेटाइयो');
    }

    public function publish(Circular $circular)
    {
        $user = auth()->user();

        // ✅ Use Policy (maybe a custom policy method 'publish')
        $this->authorize('update', $circular); // Or create a separate 'publish' policy

        $circular->update([
            'status' => 'published',
            'published_at' => now()
        ]);

        return back()->with('success', 'सूचना सफलतापूर्वक प्रकाशित गरियो');
    }

    // General analytics for all circulars
    public function analytics()
    {
        $user = auth()->user();

        if ($user->hasRole('hostel_manager')) {
            $this->authorizeOwnerAccess();
        }

        $organization = $user->organizations()->first();

        $stats = [
            'total_circulars' => Circular::where('organization_id', $organization->id)->count(),
            'published_circulars' => Circular::where('organization_id', $organization->id)
                ->where('status', 'published')->count(),
            'student_count' => $organization->students()->count(),
            'total_read' => CircularRecipient::whereHas('circular', function ($query) use ($organization) {
                $query->where('organization_id', $organization->id);
            })->where('is_read', true)->count(),
            'total_recipients' => CircularRecipient::whereHas('circular', function ($query) use ($organization) {
                $query->where('organization_id', $organization->id);
            })->count(),
            'urgent_count' => Circular::where('organization_id', $organization->id)
                ->where('priority', 'urgent')->count(),
            'normal_count' => Circular::where('organization_id', $organization->id)
                ->where('priority', 'normal')->count(),
            'info_count' => Circular::where('organization_id', $organization->id)
                ->where('priority', 'info')->count(),
        ];

        $recentCirculars = Circular::with('recipients')
            ->where('organization_id', $organization->id)
            ->where('status', 'published')
            ->orderBy('published_at', 'desc')
            ->take(10)
            ->get();

        return view('owner.circulars.analytics', compact('stats', 'recentCirculars'));
    }

    // Single circular analytics (optimized)
    public function analyticsSingle(Circular $circular)
    {
        $user = auth()->user();

        // ✅ Use Policy
        $this->authorize('view', $circular);

        // Already optimized with direct queries
        $stats = [
            'total_recipients' => $circular->recipients()->count(),
            'total_read' => $circular->recipients()->where('is_read', true)->count(),
            'by_user_type' => $circular->recipients()
                ->select('user_type')
                ->selectRaw('COUNT(*) as total')
                ->selectRaw('SUM(is_read) as read_count')
                ->groupBy('user_type')
                ->get(),
            'engagement_rate' => $circular->recipients()->count() > 0 ?
                round(($circular->recipients()->where('is_read', true)->count() / $circular->recipients()->count()) * 100, 1) : 0
        ];

        return view('owner.circulars.analytics-single', compact('circular', 'stats'));
    }

    public function markAsRead(Circular $circular)
    {
        $user = auth()->user();

        if ($user->hasRole('student')) {
            $student = Student::where('user_id', $user->id)->first();
            if (!$student) {
                return response()->json(['success' => false, 'message' => 'विद्यार्थी रेकर्ड फेला परेन'], 403);
            }
        }

        $recipient = CircularRecipient::where('circular_id', $circular->id)
            ->where('user_id', $user->id)
            ->first();

        if ($recipient) {
            $recipient->markAsRead();
        }

        return response()->json(['success' => true]);
    }

    // Template management method
    public function templates()
    {
        $user = auth()->user();

        if ($user->hasRole('hostel_manager')) {
            $this->authorizeOwnerAccess();
        }

        return view('owner.circulars.templates');
    }
}
